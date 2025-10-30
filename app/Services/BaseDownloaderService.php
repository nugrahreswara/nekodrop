<?php

namespace App\Services;

use App\Models\Download;
use App\Models\Platform;
use Symfony\Component\Process\Process;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

abstract class BaseDownloaderService
{
    protected Platform $platform;
    protected string $downloadPath;
    protected array $defaultSettings = [];
    
    // Timeout constants untuk berbagai operasi
    protected const TIMEOUT_VIDEO_INFO = 60; // 1 menit untuk get info
    protected const TIMEOUT_DOWNLOAD = 3600; // 1 jam untuk download
    protected const TIMEOUT_THUMBNAIL = 30; // 30 detik untuk thumbnail

    public function __construct(Platform $platform)
    {
        $this->platform = $platform;
        $this->downloadPath = storage_path('app/downloads');
        $this->ensureDownloadDirectory();
    }

    abstract public function getVideoInfo(string $url): array;
    abstract public function downloadVideo(string $url, array $options = []): Download;
    abstract protected function buildCommand(string $url, array $options = []): array;

    protected function ensureDownloadDirectory(): void
    {
        if (!is_dir($this->downloadPath)) {
            mkdir($this->downloadPath, 0755, true);
        }
    }

    /**
     * Execute command with improved timeout handling
     */
    protected function executeCommand(array $command, int $timeout = null): array
    {
        // Gunakan timeout default yang lebih besar jika tidak ditentukan
        if ($timeout === null) {
            $timeout = self::TIMEOUT_DOWNLOAD;
        }
        
        $process = new Process($command);
        $process->setTimeout($timeout);
        
        // Set idle timeout (timeout jika tidak ada output)
        $process->setIdleTimeout(300); // 5 menit tanpa output
        
        try {
            Log::info('Executing command', [
                'command' => implode(' ', array_map(function($arg) {
                    // Hide sensitive data in logs
                    if (strpos($arg, 'cookie') !== false) {
                        return '[COOKIE_HIDDEN]';
                    }
                    return $arg;
                }, $command)),
                'timeout' => $timeout
            ]);
            
            // Run process dengan callback untuk monitoring
            $output = '';
            $errorOutput = '';
            
            $process->run(function ($type, $buffer) use (&$output, &$errorOutput) {
                if (Process::ERR === $type) {
                    $errorOutput .= $buffer;
                    Log::debug('Process error output', ['buffer' => $buffer]);
                } else {
                    $output .= $buffer;
                    Log::debug('Process output', ['buffer' => $buffer]);
                }
            });
            
            $isSuccessful = $process->isSuccessful();
            
            Log::info('Command execution completed', [
                'success' => $isSuccessful,
                'exit_code' => $process->getExitCode(),
                'has_output' => !empty($output),
                'has_error' => !empty($errorOutput)
            ]);
            
            return [
                'success' => $isSuccessful,
                'output' => $output,
                'error' => $errorOutput,
                'exit_code' => $process->getExitCode()
            ];
        } catch (\Symfony\Component\Process\Exception\ProcessTimedOutException $e) {
            Log::error('Command execution timed out', [
                'command' => implode(' ', $command),
                'timeout' => $timeout,
                'error' => $e->getMessage()
            ]);
            
            return [
                'success' => false,
                'output' => $process->getOutput(),
                'error' => 'Process timed out after ' . $timeout . ' seconds. ' . $e->getMessage(),
                'exit_code' => -1,
                'timeout' => true
            ];
        } catch (\Exception $e) {
            Log::error('Command execution failed', [
                'command' => implode(' ', $command),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return [
                'success' => false,
                'output' => '',
                'error' => $e->getMessage(),
                'exit_code' => -1
            ];
        }
    }

    /**
     * Execute command for getting video info with shorter timeout
     */
    protected function executeInfoCommand(array $command): array
    {
        return $this->executeCommand($command, self::TIMEOUT_VIDEO_INFO);
    }

    /**
     * Execute command for downloading with extended timeout
     */
    protected function executeDownloadCommand(array $command, ?int $customTimeout = null): array
    {
        $timeout = $customTimeout ?? self::TIMEOUT_DOWNLOAD;
        return $this->executeCommand($command, $timeout);
    }

    protected function getSetting(string $key, $default = null)
    {
        return $this->platform->getSetting($key, $default);
    }

    protected function parseVideoInfo(string $output): array
    {
        // Default implementation - can be overridden by specific platforms
        $lines = explode("\n", $output);
        $info = [];
        
        foreach ($lines as $line) {
            if (strpos($line, 'title:') !== false) {
                $info['title'] = trim(str_replace('title:', '', $line));
            } elseif (strpos($line, 'duration:') !== false) {
                $info['duration'] = trim(str_replace('duration:', '', $line));
            } elseif (strpos($line, 'thumbnail:') !== false) {
                $info['thumbnail'] = trim(str_replace('thumbnail:', '', $line));
            }
        }
        
        return $info;
    }

    protected function createDownloadRecord(string $url, array $info = []): Download
    {
        return Download::create([
            'url' => $url,
            'platform' => $this->platform->name,
            'title' => $info['title'] ?? null,
            'thumbnail' => $info['thumbnail'] ?? null,
            'duration' => $info['duration'] ?? null,
            'status' => 'pending',
            'metadata' => $info
        ]);
    }

    protected function updateDownloadStatus(Download $download, string $status, array $data = []): void
    {
        $download->update(array_merge(['status' => $status], $data));
        
        Log::info('Download status updated', [
            'download_id' => $download->id,
            'status' => $status,
            'url' => $download->url
        ]);
    }

    protected function getQualityOptions(): array
    {
        return [
            'best' => 'Best Quality',
            'worst' => 'Worst Quality',
            '720p' => '720p HD',
            '480p' => '480p SD',
            '360p' => '360p',
            '240p' => '240p'
        ];
    }

    protected function getFormatOptions(): array
    {
        return [
            'mp4' => 'MP4 Video',
            'webm' => 'WebM Video',
            'mp3' => 'MP3 Audio',
            'wav' => 'WAV Audio',
            'm4a' => 'M4A Audio'
        ];
    }

    protected function downloadThumbnailLocally(string $thumbnailUrl, string $originalUrl): ?string
    {
        try {
            // Extract post ID from original URL for filename
            $postId = null;
            if (preg_match('/\/p\/([A-Za-z0-9_-]+)/', $originalUrl, $matches)) {
                $postId = $matches[1];
            } elseif (preg_match('/\/reel\/([A-Za-z0-9_-]+)/', $originalUrl, $matches)) {
                $postId = $matches[1];
            }

            if (!$postId) {
                $postId = 'unknown_' . md5($originalUrl);
            }

            // Create thumbnails directory if it doesn't exist
            $thumbnailDir = storage_path('app/public/thumbnails');
            if (!is_dir($thumbnailDir)) {
                mkdir($thumbnailDir, 0755, true);
            }

            // Generate filename
            $extension = pathinfo(parse_url($thumbnailUrl, PHP_URL_PATH), PATHINFO_EXTENSION) ?: 'jpg';
            $fileName = 'instagram_' . $postId . '_thumb.' . $extension;
            $filePath = $thumbnailDir . '/' . $fileName;

            // Download thumbnail using curl with timeout
            $command = [
                'curl',
                '-s',
                '--max-time', '30', // Connection timeout
                '--connect-timeout', '10', // Initial connection timeout
                '-A', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
                '--referer', 'https://www.instagram.com/',
                '-o', $filePath,
                $thumbnailUrl
            ];

            $result = $this->executeCommand($command, self::TIMEOUT_THUMBNAIL);

            if ($result['success'] && file_exists($filePath) && filesize($filePath) > 0) {
                Log::info('Thumbnail downloaded successfully', [
                    'url' => $thumbnailUrl,
                    'path' => $filePath,
                    'size' => filesize($filePath)
                ]);
                
                // Return the public URL for the thumbnail
                return 'thumbnails/' . $fileName;
            }

            Log::warning('Thumbnail download failed', [
                'url' => $thumbnailUrl,
                'result' => $result
            ]);

            return null;
        } catch (\Exception $e) {
            Log::error('Failed to download thumbnail locally', [
                'url' => $thumbnailUrl,
                'error' => $e->getMessage()
            ]);
            return null;
        }
    }
    
    /**
     * Get recommended timeout based on video duration
     */
    protected function calculateTimeout(?int $durationSeconds): int
    {
        if (!$durationSeconds) {
            return self::TIMEOUT_DOWNLOAD;
        }
        
        // Formula: duration * 10 (assuming download 10x slower than realtime)
        // Minimum 5 minutes, maximum 2 hours
        $calculatedTimeout = max(300, min(7200, $durationSeconds * 10));
        
        Log::info('Calculated timeout', [
            'duration' => $durationSeconds,
            'timeout' => $calculatedTimeout
        ]);
        
        return $calculatedTimeout;
    }
}