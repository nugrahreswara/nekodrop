<?php

namespace App\Services;

use App\Models\Download;
use App\Models\Platform;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FacebookDownloaderService extends BaseDownloaderService
{
    protected array $defaultSettings = [
        'quality' => 'best',
        'format' => 'mp4',
        'audio_only' => false,
        'subtitles' => false,
        'embed_metadata' => true
    ];

    protected array $audioFormats = ['mp3', 'm4a', 'wav', 'opus', 'flac'];

    public function getVideoInfo(string $url): array
    {
        Log::info('Getting video info for Facebook URL', ['url' => $url]);

        // Try multiple methods
        $methods = [
            'getVideoInfoFromYtDlp',
            'getVideoInfoFromApi',
        ];

        foreach ($methods as $method) {
            try {
                $info = $this->$method($url);
                if ($info && !empty($info['thumbnail'])) {
                    Log::info('Successfully got video info using ' . $method, [
                        'thumbnail' => $info['thumbnail'],
                        'title' => $info['title']
                    ]);
                    return $info;
                }
            } catch (\Exception $e) {
                Log::warning("Method {$method} failed", ['error' => $e->getMessage()]);
                continue;
            }
        }

        return $this->getBasicInfoFromUrl($url);
    }

    protected function getVideoInfoFromYtDlp(string $url): ?array
    {
        $commands = [];

        // Try with cookies from browser
        $browsers = ['chrome', 'firefox', 'edge'];
        foreach ($browsers as $browser) {
            $commands[] = [
                'yt-dlp',
                '--dump-json',
                '--no-download',
                '--cookies-from-browser', $browser,
                '--user-agent', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                '--referer', 'https://www.facebook.com/',
                '--no-check-certificate',
                $url
            ];
        }

        // Try with cookies file
        $cookiesFile = base_path('cookies.txt');
        if (file_exists($cookiesFile)) {
            $commands[] = [
                'yt-dlp',
                '--dump-json',
                '--no-download',
                '--cookies', $cookiesFile,
                '--user-agent', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                '--referer', 'https://www.facebook.com/',
                '--no-check-certificate',
                $url
            ];
        }

        // Basic fallback
        $commands[] = [
            'yt-dlp',
            '--dump-json',
            '--no-download',
            '--user-agent', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
            '--referer', 'https://www.facebook.com/',
            '--no-check-certificate',
            $url
        ];

        foreach ($commands as $command) {
            $result = $this->executeCommand($command);
            
            if ($result['success']) {
                $info = json_decode($result['output'], true);
                if ($info && isset($info['title'])) {
                    $thumbnail = null;
                    
                    if (isset($info['thumbnail'])) {
                        $thumbnail = $info['thumbnail'];
                    } elseif (isset($info['thumbnails']) && !empty($info['thumbnails'])) {
                        $thumbnails = $info['thumbnails'];
                        $thumbnail = end($thumbnails)['url'] ?? null;
                    }

                    return [
                        'title' => $info['title'] ?? 'Facebook Video',
                        'duration' => $this->formatDuration($info['duration'] ?? 0),
                        'thumbnail' => $thumbnail,
                        'description' => $info['description'] ?? null,
                        'uploader' => $info['uploader'] ?? 'Facebook User',
                        'view_count' => $info['view_count'] ?? null,
                        'upload_date' => $info['upload_date'] ?? null,
                        'formats' => $info['formats'] ?? []
                    ];
                }
            }
        }

        return null;
    }

    protected function getVideoInfoFromApi(string $url): ?array
    {
        $videoId = $this->extractVideoId($url);
        if (!$videoId) {
            return null;
        }

        try {
            // Try Facebook Graph API for thumbnail
            $thumbnailUrl = "https://graph.facebook.com/v18.0/{$videoId}/picture?type=large";
            
            $response = Http::timeout(10)->head($thumbnailUrl);
            
            if ($response->successful()) {
                return [
                    'title' => 'Facebook Video ' . $videoId,
                    'duration' => '00:00',
                    'thumbnail' => $thumbnailUrl,
                    'description' => null,
                    'uploader' => 'Facebook User',
                    'view_count' => null,
                    'upload_date' => null,
                    'formats' => []
                ];
            }
        } catch (\Exception $e) {
            Log::warning('Facebook API method failed', ['error' => $e->getMessage()]);
        }

        return null;
    }

    protected function getBasicInfoFromUrl(string $url): array
    {
        $videoId = $this->extractVideoId($url);
        $thumbnailUrl = $videoId ? "https://graph.facebook.com/v18.0/{$videoId}/picture?type=large" : null;
        
        return [
            'title' => 'Facebook Video ' . ($videoId ?: 'Unknown'),
            'duration' => '00:00',
            'thumbnail' => $thumbnailUrl,
            'description' => null,
            'uploader' => 'Facebook User',
            'view_count' => null,
            'upload_date' => null,
            'formats' => []
        ];
    }

    protected function extractVideoId(string $url): ?string
    {
        if (preg_match('/\/videos\/(\d+)/', $url, $matches)) {
            return $matches[1];
        } elseif (preg_match('/\/watch\/\?v=(\d+)/', $url, $matches)) {
            return $matches[1];
        } elseif (preg_match('/\/reel\/(\d+)/', $url, $matches)) {
            return $matches[1];
        } elseif (preg_match('/fbid=(\d+)/', $url, $matches)) {
            return $matches[1];
        } elseif (preg_match('/\/(\d{15,})/', $url, $matches)) {
            return $matches[1];
        }
        return null;
    }

    public function downloadVideo(string $url, array $options = []): Download
    {
        $download = $this->createDownloadRecord($url);
        
        try {
            // Get video info first
            $info = $this->getVideoInfo($url);
            
            Log::info('Video info retrieved', [
                'title' => $info['title'],
                'thumbnail' => $info['thumbnail'] ?? 'NO THUMBNAIL'
            ]);

            // Download thumbnail locally
            $localThumbnail = null;
            if (!empty($info['thumbnail'])) {
                $localThumbnail = $this->downloadThumbnailLocally($info['thumbnail'], $url);
            }

            $download->update([
                'title' => $info['title'],
                'thumbnail' => $localThumbnail ?: $info['thumbnail'],
                'duration' => $info['duration'],
                'metadata' => $info
            ]);

            Log::info('Download record updated', [
                'id' => $download->id,
                'thumbnail' => $download->thumbnail
            ]);

            $this->updateDownloadStatus($download, 'downloading');

            $success = false;
            $lastError = null;

            // Try multiple download methods
            $downloadCommands = [
                $this->buildCommand($url, $options),
                $this->buildFallbackCommand($url, $options, 'browser_chrome'),
                $this->buildFallbackCommand($url, $options, 'browser_firefox'),
                $this->buildFallbackCommand($url, $options, 'browser_edge'),
                $this->buildFallbackCommand($url, $options, 'basic')
            ];

            foreach ($downloadCommands as $command) {
                $result = $this->executeCommand($command);

                if ($result['success']) {
                    $filePath = $this->extractFilePath($result['output'], $options);
                    if ($filePath && file_exists($filePath)) {
                        $fileSize = filesize($filePath);
                        
                        // Ensure thumbnail is downloaded
                        if (!$localThumbnail && isset($info['thumbnail'])) {
                            $localThumbnail = $this->downloadThumbnailLocally($info['thumbnail'], $url);
                            if ($localThumbnail) {
                                $download->update(['thumbnail' => $localThumbnail]);
                            }
                        }
                        
                        $this->updateDownloadStatus($download, 'completed', [
                            'file_path' => $filePath,
                            'file_size' => $fileSize
                        ]);
                        $success = true;
                        break;
                    }
                }
                $lastError = $result['error'];
            }

            if (!$success) {
                $this->updateDownloadStatus($download, 'failed', [
                    'error_message' => $lastError ?: 'All download methods failed'
                ]);
            }

        } catch (\Exception $e) {
            Log::error('Download failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            $this->updateDownloadStatus($download, 'failed', [
                'error_message' => $e->getMessage()
            ]);
        }

        return $download;
    }

    protected function downloadThumbnailLocally(string $thumbnailUrl, string $originalUrl): ?string
    {
        try {
            $videoId = $this->extractVideoId($originalUrl);
            if (!$videoId) {
                $videoId = 'thumb_' . time() . '_' . rand(1000, 9999);
            }

            $thumbnailDir = storage_path('app/public/thumbnails');
            if (!is_dir($thumbnailDir)) {
                mkdir($thumbnailDir, 0755, true);
            }

            $parsedUrl = parse_url($thumbnailUrl);
            $pathInfo = pathinfo($parsedUrl['path'] ?? '');
            $extension = $pathInfo['extension'] ?? 'jpg';
            
            $extension = explode('?', $extension)[0];
            if (!in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'webp'])) {
                $extension = 'jpg';
            }

            $fileName = 'facebook_' . $videoId . '_' . time() . '.' . $extension;
            $filePath = $thumbnailDir . '/' . $fileName;

            Log::info('Attempting to download thumbnail', [
                'url' => $thumbnailUrl,
                'destination' => $filePath
            ]);

            // Method 1: Try with curl
            $cookiesFile = base_path('cookies.txt');
            $command = [
                'curl',
                '-s',
                '-L',
                '--max-redirs', '5',
                '--max-time', '30',
                '-A', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                '-H', 'Accept: image/webp,image/apng,image/*,*/*;q=0.8',
                '-H', 'Referer: https://www.facebook.com/',
            ];

            if (file_exists($cookiesFile) && is_readable($cookiesFile)) {
                $command[] = '-b';
                $command[] = $cookiesFile;
            }

            $command[] = '-o';
            $command[] = $filePath;
            $command[] = $thumbnailUrl;

            $result = $this->executeCommand($command);

            if ($result['success'] && file_exists($filePath) && filesize($filePath) > 100) {
                Log::info('Thumbnail downloaded successfully', [
                    'file' => $fileName,
                    'size' => filesize($filePath)
                ]);
                return 'thumbnails/' . $fileName;
            }

            // Method 2: Try with HTTP client
            if (!$result['success'] || !file_exists($filePath) || filesize($filePath) <= 100) {
                @unlink($filePath);
                
                try {
                    $response = Http::timeout(30)
                        ->withHeaders([
                            'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                            'Accept' => 'image/webp,image/apng,image/*,*/*;q=0.8',
                            'Referer' => 'https://www.facebook.com/',
                        ])
                        ->get($thumbnailUrl);

                    if ($response->successful() && strlen($response->body()) > 100) {
                        file_put_contents($filePath, $response->body());
                        Log::info('Thumbnail downloaded via HTTP client', [
                            'file' => $fileName,
                            'size' => filesize($filePath)
                        ]);
                        return 'thumbnails/' . $fileName;
                    }
                } catch (\Exception $e) {
                    Log::warning('HTTP client download failed', ['error' => $e->getMessage()]);
                }
            }

            Log::warning('Thumbnail download failed', [
                'url' => $thumbnailUrl,
                'file_exists' => file_exists($filePath),
                'file_size' => file_exists($filePath) ? filesize($filePath) : 0
            ]);

            return null;
        } catch (\Exception $e) {
            Log::error('Failed to download thumbnail', [
                'url' => $thumbnailUrl,
                'error' => $e->getMessage()
            ]);
            return null;
        }
    }

    protected function buildFallbackCommand(string $url, array $options = [], string $type = 'browser'): array
    {
        $quality = $options['quality'] ?? $this->getSetting('quality', 'best');
        $format = $options['format'] ?? $this->getSetting('format', 'mp4');
        $audioOnly = $options['audio_only'] ?? $this->getSetting('audio_only', false);
        $subtitles = $options['subtitles'] ?? $this->getSetting('subtitles', false);

        if (in_array(strtolower($format), $this->audioFormats)) {
            $audioOnly = true;
        }

        $command = ['yt-dlp'];

        if (strpos($type, 'browser_') === 0) {
            $browser = str_replace('browser_', '', $type);
            $command[] = '--cookies-from-browser';
            $command[] = $browser;
        } elseif ($type === 'basic') {
            $cookiesFile = base_path('cookies.txt');
            if (file_exists($cookiesFile) && is_readable($cookiesFile)) {
                $command[] = '--cookies';
                $command[] = $cookiesFile;
            }
        }

        $command[] = '--user-agent';
        $command[] = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36';
        $command[] = '--referer';
        $command[] = 'https://www.facebook.com/';
        $command[] = '--no-check-certificate';
        $command[] = '--write-thumbnail';

        $command[] = '-o';
        if ($audioOnly) {
            $command[] = $this->downloadPath . '/%(title)s.' . $format;
        } else {
            $command[] = $this->downloadPath . '/%(title)s.%(ext)s';
        }

        if ($audioOnly) {
            $command[] = '--extract-audio';
            $command[] = '--audio-format';
            $command[] = $format;
            $command[] = '--audio-quality';
            $command[] = '0';
            if (strtolower($format) === 'mp3') {
                $command[] = '--postprocessor-args';
                $command[] = 'ffmpeg:-b:a 320k';
            }
            $command[] = '--format';
            $command[] = 'bestaudio/best';
        } else {
            $command[] = '--format';
            $command[] = $this->getQualityFormat($quality);
        }

        if (!$audioOnly && $subtitles) {
            $command[] = '--write-subs';
            $command[] = '--write-auto-subs';
        }

        $command[] = '--no-playlist';
        $command[] = '--ignore-errors';
        $command[] = $url;

        return $command;
    }

    protected function buildCommand(string $url, array $options = []): array
    {
        $quality = $options['quality'] ?? $this->getSetting('quality', 'best');
        $format = $options['format'] ?? $this->getSetting('format', 'mp4');
        $audioOnly = $options['audio_only'] ?? $this->getSetting('audio_only', false);
        $subtitles = $options['subtitles'] ?? $this->getSetting('subtitles', false);
        $embedMetadata = $options['embed_metadata'] ?? $this->getSetting('embed_metadata', true);

        if (in_array(strtolower($format), $this->audioFormats)) {
            $audioOnly = true;
        }

        $command = ['yt-dlp'];

        // Try cookies from browser first
        $command[] = '--cookies-from-browser';
        $command[] = 'chrome';

        $cookiesFile = base_path('cookies.txt');
        if (file_exists($cookiesFile) && is_readable($cookiesFile)) {
            $command[] = '--cookies';
            $command[] = $cookiesFile;
        }

        $command[] = '--user-agent';
        $command[] = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36';
        $command[] = '--referer';
        $command[] = 'https://www.facebook.com/';
        $command[] = '--no-check-certificate';
        $command[] = '--write-thumbnail';

        $command[] = '-o';
        if ($audioOnly) {
            $command[] = $this->downloadPath . '/%(title)s.' . $format;
        } else {
            $command[] = $this->downloadPath . '/%(title)s.%(ext)s';
        }

        if ($audioOnly) {
            $command[] = '--extract-audio';
            $command[] = '--audio-format';
            $command[] = $format;
            $command[] = '--audio-quality';
            $command[] = '0';
            
            if (strtolower($format) === 'mp3') {
                $command[] = '--postprocessor-args';
                $command[] = 'ffmpeg:-b:a 320k';
            }
            
            $command[] = '--format';
            $command[] = 'bestaudio/best';
        } else {
            $command[] = '--format';
            $command[] = $this->getQualityFormat($quality);
        }

        if ($embedMetadata) {
            $command[] = '--embed-metadata';
            if (!$audioOnly) {
                $command[] = '--embed-thumbnail';
            } else {
                $command[] = '--embed-thumbnail';
                $command[] = '--ppa';
                $command[] = 'EmbedThumbnail+ffmpeg_o:-c:v mjpeg';
            }
        }

        if (!$audioOnly && $subtitles) {
            $command[] = '--write-subs';
            $command[] = '--write-auto-subs';
        }

        $command[] = '--no-playlist';
        $command[] = '--ignore-errors';
        $command[] = '--no-warnings';

        $command[] = $url;

        return $command;
    }

    protected function getQualityFormat(string $quality): string
    {
        return match($quality) {
            'best' => 'best',
            '720p' => 'best[height<=720]',
            '480p' => 'best[height<=480]',
            '360p' => 'best[height<=360]',
            'worst' => 'worst',
            default => 'best'
        };
    }

    protected function formatDuration(int $seconds): string
    {
        $hours = floor($seconds / 3600);
        $minutes = floor(($seconds % 3600) / 60);
        $seconds = $seconds % 60;
        
        if ($hours > 0) {
            return sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);
        }
        
        return sprintf('%02d:%02d', $minutes, $seconds);
    }

    protected function extractFilePath(string $output, array $options = []): ?string
    {
        $lines = explode("\n", $output);
        $format = $options['format'] ?? 'mp4';
        $audioOnly = in_array(strtolower($format), $this->audioFormats);
        
        $destinations = [];
        foreach ($lines as $line) {
            if (strpos($line, '[download] Destination:') !== false) {
                $destinations[] = trim(str_replace('[download] Destination:', '', $line));
            }
            if (strpos($line, '[ExtractAudio] Destination:') !== false) {
                return trim(str_replace('[ExtractAudio] Destination:', '', $line));
            }
            if (strpos($line, '[ffmpeg] Destination:') !== false) {
                return trim(str_replace('[ffmpeg] Destination:', '', $line));
            }
        }
        
        if ($audioOnly && !empty($destinations)) {
            $lastDestination = end($destinations);
            $pathInfo = pathinfo($lastDestination);
            $expectedPath = $pathInfo['dirname'] . '/' . $pathInfo['filename'] . '.' . $format;
            
            if (file_exists($expectedPath)) {
                return $expectedPath;
            }
        }
        
        return !empty($destinations) ? end($destinations) : null;
    }

    public function getAvailableFormats(string $url): array
    {
        $command = [
            'yt-dlp',
            '--list-formats',
            '--cookies-from-browser', 'chrome',
            $url
        ];

        $result = $this->executeCommand($command);

        if (!$result['success']) {
            return [];
        }

        $formats = [];
        $lines = explode("\n", $result['output']);

        $maxHeight = 0;
        foreach ($lines as $line) {
            if (preg_match('/^(\d+)\s+(\w+)\s+(\d+x\d+|\w+)\s+(.+)/', $line, $matches)) {
                $resolution = $matches[3];
                if (preg_match('/(\d+)x(\d+)/', $resolution, $resMatches)) {
                    $height = (int)$resMatches[2];
                    $maxHeight = max($maxHeight, $height);
                }
                $formats[] = [
                    'id' => $matches[1],
                    'extension' => $matches[2],
                    'resolution' => $resolution,
                    'note' => trim($matches[4])
                ];
            }
        }

        $formats['max_height'] = $maxHeight;

        return $formats;
    }

    public function downloadAudio(string $url, string $format = 'mp3', array $options = []): Download
    {
        $options['audio_only'] = true;
        $options['format'] = $format;

        return $this->downloadVideo($url, $options);
    }

    public function getSupportedAudioFormats(): array
    {
        return $this->audioFormats;
    }
}