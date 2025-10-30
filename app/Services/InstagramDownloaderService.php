<?php

namespace App\Services;

use App\Models\Download;
use App\Models\Platform;

class InstagramDownloaderService extends BaseDownloaderService
{
    protected array $defaultSettings = [
        'quality' => 'best',
        'format' => 'mp4',
        'audio_only' => false,
        'stories' => false,
        'reels' => true,
        'videos' => true,
        'embed_metadata' => true
    ];

    protected array $audioFormats = ['mp3', 'm4a', 'wav', 'opus', 'flac'];

    public function getVideoInfo(string $url): array
    {
        // Try HTML extraction first (faster)
        $info = $this->getVideoInfoFromHtml($url);
        if ($info) {
            return $info;
        }

        $sessionid = $this->getSetting('sessionid', '');

        $commands = [];
        if (!empty($sessionid)) {
            // Primary approach with sessionid
            $commands[] = [
                'yt-dlp',
                '--dump-json',
                '--no-download',
                '--socket-timeout', '30',
                '--user-agent', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
                '--referer', 'https://www.instagram.com/',
                '--no-check-certificate',
                '--extractor-args', 'instagram:sessionid=' . $sessionid,
                $url
            ];
        }

        // Fallback with cookies
        $cookiesFile = base_path('cookies.txt');
        if (file_exists($cookiesFile)) {
            $commands[] = [
                'yt-dlp',
                '--dump-json',
                '--no-download',
                '--socket-timeout', '30',
                '--cookies', $cookiesFile,
                '--user-agent', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
                '--referer', 'https://www.instagram.com/',
                '--no-check-certificate',
                $url
            ];
        }

        // Fallback with browser cookies (only try 2 most common browsers)
        $browsers = ['chrome', 'firefox'];
        foreach ($browsers as $browser) {
            $commands[] = [
                'yt-dlp',
                '--dump-json',
                '--no-download',
                '--socket-timeout', '30',
                '--cookies-from-browser', $browser,
                '--user-agent', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
                '--referer', 'https://www.instagram.com/',
                '--no-check-certificate',
                '--extractor-args', 'instagram:api=https://i.instagram.com/api/v1/',
                $url
            ];
        }

        // Basic fallback without cookies
        $commands[] = [
            'yt-dlp',
            '--dump-json',
            '--no-download',
            '--socket-timeout', '30',
            '--user-agent', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
            '--referer', 'https://www.instagram.com/',
            '--no-check-certificate',
            '--no-cookies',
            '--extractor-args', 'instagram:api=https://i.instagram.com/api/v1/',
            $url
        ];

        $lastError = null;
        foreach ($commands as $index => $command) {
            \Log::info("Trying video info method " . ($index + 1) . "/" . count($commands));
            
            // Use executeInfoCommand with short timeout
            $result = $this->executeInfoCommand($command);

            if ($result['success']) {
                $info = json_decode($result['output'], true);
                if ($info && isset($info['title'])) {
                    return [
                        'title' => $info['title'] ?? 'Unknown',
                        'duration' => $this->formatDuration($info['duration'] ?? 0),
                        'thumbnail' => $info['thumbnail'] ?? null,
                        'description' => $info['description'] ?? null,
                        'uploader' => $info['uploader'] ?? null,
                        'view_count' => $info['view_count'] ?? null,
                        'upload_date' => $info['upload_date'] ?? null,
                        'formats' => $info['formats'] ?? []
                    ];
                }
            }
            
            $lastError = $result['error'];
            
            // If timeout, skip remaining attempts
            if (isset($result['timeout']) && $result['timeout']) {
                \Log::warning('Video info detection timed out, using basic info');
                break;
            }
        }

        // If all methods fail, try to extract basic info from URL
        return $this->getBasicInfoFromUrl($url);
    }

    protected function getVideoInfoFromHtml(string $url): ?array
    {
        // Extract post ID from URL
        $postId = null;
        if (preg_match('/\/p\/([A-Za-z0-9_-]+)/', $url, $matches)) {
            $postId = $matches[1];
        } elseif (preg_match('/\/reel\/([A-Za-z0-9_-]+)/', $url, $matches)) {
            $postId = $matches[1];
        }

        if (!$postId) {
            return null;
        }

        // Use curl to fetch the HTML content
        $cookiesFile = base_path('cookies.txt');
        $command = [
            'curl',
            '-s',
            '--max-time', '20',
            '--connect-timeout', '10',
            '-A', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
            '--referer', 'https://www.instagram.com/',
        ];

        if (file_exists($cookiesFile) && is_readable($cookiesFile)) {
            $command[] = '--cookie';
            $command[] = $cookiesFile;
            $command[] = '--cookie-jar';
            $command[] = $cookiesFile;
        }

        $command[] = $url;

        // Use short timeout for HTML fetch
        $result = $this->executeCommand($command, 30);

        if ($result['success'] && $result['output']) {
            $html = $result['output'];

            // Extract sharedData JSON
            if (preg_match('/window\._sharedData = ({.*?});<\/script>/s', $html, $matches)) {
                $sharedData = json_decode($matches[1], true);
                if ($sharedData && isset($sharedData['entry_data']['PostPage'][0]['graphql']['shortcode_media'])) {
                    $media = $sharedData['entry_data']['PostPage'][0]['graphql']['shortcode_media'];

                    $videoUrl = $media['video_url'] ?? null;
                    $displayUrl = $media['display_url'] ?? null;
                    $description = $media['edge_media_to_caption']['edges'][0]['node']['text'] ?? null;
                    $username = $media['owner']['username'] ?? 'Instagram User';
                    $viewCount = $media['video_view_count'] ?? null;
                    $timestamp = $media['taken_at_timestamp'] ?? null;
                    $isVideo = $media['is_video'] ?? false;

                    return [
                        'title' => $description ? substr($description, 0, 100) : 'Instagram Post ' . $postId,
                        'duration' => $isVideo ? $this->formatDuration($media['video_duration'] ?? 0) : '00:00',
                        'thumbnail' => $displayUrl,
                        'description' => $description,
                        'uploader' => $username,
                        'view_count' => $viewCount,
                        'upload_date' => $timestamp ? date('Y-m-d', $timestamp) : null,
                        'direct_url' => $videoUrl,
                        'formats' => $videoUrl ? [['url' => $videoUrl, 'format' => 'mp4']] : []
                    ];
                }
            }

            // Fallback to additional data if sharedData not found
            if (preg_match('/window\.__additionalDataLoaded\([^,]+,({.*?})\);<\/script>/s', $html, $matches)) {
                $additionalData = json_decode($matches[1], true);
                if ($additionalData && isset($additionalData['shortcode_media'])) {
                    $media = $additionalData['shortcode_media'];

                    $videoUrl = $media['video_url'] ?? null;
                    $displayUrl = $media['display_url'] ?? null;
                    $description = $media['edge_media_to_caption']['edges'][0]['node']['text'] ?? null;
                    $username = $media['owner']['username'] ?? 'Instagram User';
                    $viewCount = $media['video_view_count'] ?? null;
                    $timestamp = $media['taken_at_timestamp'] ?? null;
                    $isVideo = $media['is_video'] ?? false;

                    return [
                        'title' => $description ? substr($description, 0, 100) : 'Instagram Post ' . $postId,
                        'duration' => $isVideo ? $this->formatDuration($media['video_duration'] ?? 0) : '00:00',
                        'thumbnail' => $displayUrl,
                        'description' => $description,
                        'uploader' => $username,
                        'view_count' => $viewCount,
                        'upload_date' => $timestamp ? date('Y-m-d', $timestamp) : null,
                        'direct_url' => $videoUrl,
                        'formats' => $videoUrl ? [['url' => $videoUrl, 'format' => 'mp4']] : []
                    ];
                }
            }
        }

        return null;
    }

    protected function getBasicInfoFromUrl(string $url): array
    {
        // Extract post ID from URL
        $postId = null;
        if (preg_match('/\/p\/([A-Za-z0-9_-]+)/', $url, $matches)) {
            $postId = $matches[1];
        } elseif (preg_match('/\/reel\/([A-Za-z0-9_-]+)/', $url, $matches)) {
            $postId = $matches[1];
        }

        return [
            'title' => 'Instagram Post ' . ($postId ?: 'Unknown'),
            'duration' => '00:00',
            'thumbnail' => null,
            'description' => null,
            'uploader' => 'Instagram User',
            'view_count' => null,
            'upload_date' => null,
            'formats' => []
        ];
    }

    public function downloadVideo(string $url, array $options = []): Download
    {
        $download = $this->createDownloadRecord($url);

        try {
            // Get video info first (with timeout protection)
            $info = $this->getVideoInfo($url);

            // Download thumbnail locally for Instagram
            $localThumbnail = null;
            if ($info['thumbnail']) {
                $localThumbnail = $this->downloadThumbnailLocally($info['thumbnail'], $url);
            }

            $download->update([
                'title' => $info['title'],
                'thumbnail' => $localThumbnail ?: $info['thumbnail'],
                'duration' => $info['duration'],
                'metadata' => $info
            ]);

            $this->updateDownloadStatus($download, 'downloading');

            // Try multiple download approaches
            $success = false;
            $lastError = null;

            // Check if we have direct URL from info extraction (fastest method)
            if (isset($info['direct_url']) && $info['direct_url']) {
                \Log::info('Trying direct URL download');
                $filePath = $this->downloadDirectUrl($info['direct_url'], $url, $options);
                if ($filePath && file_exists($filePath)) {
                    $fileSize = filesize($filePath);
                    $this->updateDownloadStatus($download, 'completed', [
                        'file_path' => $filePath,
                        'file_size' => $fileSize
                    ]);
                    return $download;
                }
            }

            // Prepare download commands (limit to 4 most effective methods)
            $downloadCommands = [
                // Primary with cookies
                $this->buildCommand($url, $options),
                // Browser cookies (chrome only - most common)
                $this->buildFallbackCommand($url, $options, 'browser_chrome'),
                // Firefox fallback
                $this->buildFallbackCommand($url, $options, 'browser_firefox'),
                // Basic fallback
                $this->buildFallbackCommand($url, $options, 'basic')
            ];

            // Try yt-dlp approaches
            foreach ($downloadCommands as $index => $command) {
                \Log::info("Trying download method " . ($index + 1) . "/" . count($downloadCommands));
                
                // Use executeDownloadCommand with extended timeout
                $result = $this->executeDownloadCommand($command);

                if ($result['success']) {
                    $filePath = $this->extractFilePath($result['output'], $options);
                    if ($filePath && file_exists($filePath)) {
                        $fileSize = filesize($filePath);
                        $this->updateDownloadStatus($download, 'completed', [
                            'file_path' => $filePath,
                            'file_size' => $fileSize
                        ]);
                        return $download;
                    }
                }
                
                $lastError = $result['error'];
                
                // If timeout occurred, skip remaining methods
                if (isset($result['timeout']) && $result['timeout']) {
                    \Log::error('Download timed out, stopping attempts');
                    $this->updateDownloadStatus($download, 'failed', [
                        'error_message' => 'Download timeout. Video mungkin terlalu besar atau koneksi lambat.'
                    ]);
                    return $download;
                }
            }

            // If yt-dlp fails, try gallery-dl as last resort
            \Log::info('Trying gallery-dl as fallback');
            $filePath = $this->downloadWithGalleryDl($url, $options);
            if ($filePath && file_exists($filePath)) {
                $fileSize = filesize($filePath);
                $this->updateDownloadStatus($download, 'completed', [
                    'file_path' => $filePath,
                    'file_size' => $fileSize
                ]);
                return $download;
            }

            $this->updateDownloadStatus($download, 'failed', [
                'error_message' => $lastError ?: 'Semua metode download gagal. Coba lagi nanti.'
            ]);

        } catch (\Exception $e) {
            \Log::error('Download exception: ' . $e->getMessage());
            $this->updateDownloadStatus($download, 'failed', [
                'error_message' => $e->getMessage()
            ]);
        }

        return $download;
    }

    protected function buildFallbackCommand(string $url, array $options = [], string $type = 'browser'): array
    {
        $quality = $options['quality'] ?? $this->getSetting('quality', 'best');
        $format = $options['format'] ?? $this->getSetting('format', 'mp4');
        $audioOnly = $options['audio_only'] ?? $this->getSetting('audio_only', false);

        $command = ['yt-dlp'];

        // Timeout settings for yt-dlp
        $command[] = '--socket-timeout';
        $command[] = '30';
        $command[] = '--retries';
        $command[] = '3';

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

        // User agent and headers
        $command[] = '--user-agent';
        $command[] = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36';
        $command[] = '--referer';
        $command[] = 'https://www.instagram.com/';
        $command[] = '--no-check-certificate';

        // Instagram specific extractor args
        $command[] = '--extractor-args';
        $command[] = 'instagram:api=https://i.instagram.com/api/v1/';

        // Output path
        if ($audioOnly) {
            $command[] = '-o';
            $command[] = $this->downloadPath . '/%(title)s.' . $format;
        } else {
            $command[] = '-o';
            $command[] = $this->downloadPath . '/%(title)s.%(ext)s';
        }

        // Quality selection
        if ($audioOnly) {
            $command[] = '--format';
            $command[] = 'bestaudio';
            $command[] = '--extract-audio';
            $command[] = '--audio-format';
            $command[] = $format;
            $command[] = '--audio-quality';
            $command[] = '0';
        } else {
            $command[] = '--format';
            $command[] = $this->getQualityFormat($quality);
        }

        // Additional options
        $command[] = '--no-playlist';
        $command[] = '--ignore-errors';

        $command[] = $url;

        return $command;
    }

    protected function downloadWithGalleryDl(string $url, array $options = []): ?string
    {
        $postId = null;
        if (preg_match('/\/p\/([A-Za-z0-9_-]+)/', $url, $matches)) {
            $postId = $matches[1];
        } elseif (preg_match('/\/reel\/([A-Za-z0-9_-]+)/', $url, $matches)) {
            $postId = $matches[1];
        }

        if (!$postId) {
            return null;
        }

        $downloadDir = $this->downloadPath . '/instagram_' . $postId;
        if (!is_dir($downloadDir)) {
            mkdir($downloadDir, 0755, true);
        }

        $command = [
            'gallery-dl',
            '--destination', $downloadDir,
            '--filename', '{id}_{num}.{extension}',
            '--cookies-from-browser', 'chrome',
            '--user-agent', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
            '--range', '1',
            '--timeout', '30',
            '--retries', '3',
            $url
        ];

        // Use download timeout
        $result = $this->executeDownloadCommand($command);

        if ($result['success']) {
            $files = glob($downloadDir . '/*');
            foreach ($files as $file) {
                if (is_file($file) && (strpos($file, '.mp4') !== false || strpos($file, '.jpg') !== false)) {
                    return $file;
                }
            }
        }

        return null;
    }

    protected function downloadDirectUrl(string $directUrl, string $originalUrl, array $options = []): ?string
    {
        $postId = null;
        if (preg_match('/\/p\/([A-Za-z0-9_-]+)/', $originalUrl, $matches)) {
            $postId = $matches[1];
        } elseif (preg_match('/\/reel\/([A-Za-z0-9_-]+)/', $originalUrl, $matches)) {
            $postId = $matches[1];
        }

        if (!$postId) {
            $postId = 'unknown_' . time();
        }

        $extension = strpos($directUrl, '.mp4') !== false ? 'mp4' : 'jpg';
        $fileName = 'instagram_' . $postId . '.' . $extension;
        $filePath = $this->downloadPath . '/' . $fileName;

        $cookiesFile = base_path('cookies.txt');
        $command = [
            'curl',
            '-L',
            '--max-time', '600', // 10 minutes max
            '--connect-timeout', '30',
            '-A', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
            '--referer', 'https://www.instagram.com/',
        ];

        if (file_exists($cookiesFile) && is_readable($cookiesFile)) {
            $command[] = '--cookie';
            $command[] = $cookiesFile;
        }

        $command[] = '-o';
        $command[] = $filePath;
        $command[] = $directUrl;

        // Use download timeout
        $result = $this->executeDownloadCommand($command, 900); // 15 minutes

        if ($result['success'] && file_exists($filePath) && filesize($filePath) > 0) {
            return $filePath;
        }

        return null;
    }

    protected function buildCommand(string $url, array $options = []): array
    {
        $quality = $options['quality'] ?? $this->getSetting('quality', 'best');
        $format = $options['format'] ?? $this->getSetting('format', 'mp4');
        $audioOnly = $options['audio_only'] ?? $this->getSetting('audio_only', false);
        $embedMetadata = $options['embed_metadata'] ?? $this->getSetting('embed_metadata', true);

        if (in_array(strtolower($format), $this->audioFormats)) {
            $audioOnly = true;
        }

        $command = ['yt-dlp'];

        // Timeout and retry settings
        $command[] = '--socket-timeout';
        $command[] = '30';
        $command[] = '--retries';
        $command[] = '3';

        // Authentication
        $cookiesFile = base_path('cookies.txt');
        if (file_exists($cookiesFile) && is_readable($cookiesFile)) {
            $command[] = '--cookies';
            $command[] = $cookiesFile;
        }

        $command[] = '--user-agent';
        $command[] = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36';
        $command[] = '--referer';
        $command[] = 'https://www.instagram.com/';

        // Output path
        $command[] = '-o';
        if ($audioOnly) {
            $command[] = $this->downloadPath . '/%(title)s.' . $format;
        } else {
            $command[] = $this->downloadPath . '/%(title)s.%(ext)s';
        }

        // Format selection
        if ($audioOnly) {
            $command[] = '--extract-audio';
            $command[] = '--audio-format';
            $command[] = $format;
            $command[] = '--audio-quality';
            $command[] = '0';
            $command[] = '--format';
            $command[] = 'bestaudio/best';
        } else {
            $command[] = '--format';
            $command[] = $this->getQualityFormat($quality);
        }

        // Metadata
        if ($embedMetadata) {
            $command[] = '--embed-metadata';
            $command[] = '--embed-thumbnail';
        }

        $command[] = '--no-playlist';
        $command[] = '--ignore-errors';
        $command[] = '--no-check-certificate';
        $command[] = '--extractor-args';
        $command[] = 'instagram:api=https://i.instagram.com/api/v1/';

        $command[] = $url;

        return $command;
    }

    protected function getQualityFormat(string $quality): string
    {
        return match($quality) {
            'best' => 'b',
            'worst' => 'worst',
            default => 'b'
        };
    }

    protected function formatDuration(int $seconds): string
    {
        $minutes = floor($seconds / 60);
        $seconds = $seconds % 60;
        
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
            '--socket-timeout', '30',
            $url
        ];

        $result = $this->executeInfoCommand($command);

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