<?php

namespace App\Services;

use App\Models\Download;
use App\Models\Platform;

class YouTubeDownloaderService extends BaseDownloaderService
{
    protected array $defaultSettings = [
        'quality' => 'best',
        'format' => 'mp4',
        'audio_only' => false,
        'subtitles' => false,
        'embed_metadata' => true
    ];

    // Supported audio formats
    protected array $audioFormats = ['mp3', 'm4a'];

    public function getVideoInfo(string $url): array
    {
        $maxRetries = 3;
        $baseTimeout = 300; // 300 seconds for metadata fetch

        for ($attempt = 1; $attempt <= $maxRetries; $attempt++) {
            $command = [
                'yt-dlp',
                '--dump-json',
                '--no-download',
                '--retries', '3',
                '--fragment-retries', '3',
                '--socket-timeout', '30',
                '--user-agent', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
                $url
            ];

            // Override timeout for this specific command (shorter for metadata)
            $result = $this->executeCommand($command, $baseTimeout);

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

            \Log::warning("Video info attempt {$attempt} failed for URL: {$url}", ['error' => $result['error']]);

            if ($attempt < $maxRetries) {
                $delay = pow(2, $attempt - 1); // Exponential backoff: 1s, 2s, 4s
                sleep($delay);
            }
        }

        throw new \Exception('Failed to get video info after ' . $maxRetries . ' attempts: ' . ($result['error'] ?? 'Unknown error'));
    }

    public function downloadVideo(string $url, array $options = []): Download
    {
        $download = $this->createDownloadRecord($url);
        
        try {
            // Get video info first
            $info = $this->getVideoInfo($url);
            $download->update([
                'title' => $info['title'],
                'thumbnail' => $info['thumbnail'],
                'duration' => $info['duration'],
                'metadata' => $info
            ]);

            $this->updateDownloadStatus($download, 'downloading');

            // Build download command
            $command = $this->buildCommand($url, $options);
            
            // Log command for debugging (optional)
            \Log::info('Download command: ' . implode(' ', array_map(function($arg) {
                return strpos($arg, ' ') !== false ? '"' . $arg . '"' : $arg;
            }, $command)));
            
            $result = $this->executeCommand($command);

            if ($result['success']) {
                $format = $options['format'] ?? 'mp4';
                $filePath = $this->extractFilePath($result['output'], $options, $info);
                
                // Log output for debugging
                \Log::info('yt-dlp output: ' . substr($result['output'], -500));
                \Log::info('Extracted file path: ' . ($filePath ?? 'NULL'));
                
                $fileSize = file_exists($filePath) ? filesize($filePath) : null;

                // Ensure we have a valid file path and size for MP4 videos
                if (!$filePath || !$fileSize) {
                    // Try to find the file manually for MP4 downloads
                    if (strtolower($format) === 'mp4' && !in_array(strtolower($format), $this->audioFormats)) {
                        $title = preg_replace('/[^a-zA-Z0-9\-_\.]/', '_', $info['title']);
                        $expectedPath = $this->downloadPath . '/' . $title . '.mp4';
                        if (file_exists($expectedPath)) {
                            $filePath = $expectedPath;
                            $fileSize = filesize($expectedPath);
                        }
                    }
                }

                $audioOnly = in_array(strtolower($format), $this->audioFormats);
                $videoType = $audioOnly ? 'audio/mpeg' : 'video/mp4';

                $this->updateDownloadStatus($download, 'completed', [
                    'file_path' => $filePath,
                    'file_size' => $fileSize,
                    'format' => $format,
                    'video_type' => $videoType
                ]);
            } else {
                $this->updateDownloadStatus($download, 'failed', [
                    'error_message' => $result['error']
                ]);
            }

        } catch (\Exception $e) {
            \Log::error('Download exception: ' . $e->getMessage());
            $this->updateDownloadStatus($download, 'failed', [
                'error_message' => $e->getMessage()
            ]);
        }

        return $download;
    }

    protected function buildCommand(string $url, array $options = []): array
    {
        $quality = $options['quality'] ?? $this->getSetting('quality', 'best');
        $format = $options['format'] ?? $this->getSetting('format', 'mp4');
        $audioOnly = $options['audio_only'] ?? $this->getSetting('audio_only', false);
        $subtitles = $options['subtitles'] ?? $this->getSetting('subtitles', false);
        $embedMetadata = $options['embed_metadata'] ?? $this->getSetting('embed_metadata', true);

        // Auto-detect audio only mode based on format
        if (in_array(strtolower($format), $this->audioFormats)) {
            $audioOnly = true;
        }

        $command = ['yt-dlp'];

        // Output path with proper extension
        $command[] = '-o';
        if ($audioOnly) {
            $command[] = $this->downloadPath . '/%(title)s.' . $format;
        } else {
            // Force MP4 extension for video downloads when MP4 format is selected
            if (strtolower($format) === 'mp4') {
                $command[] = $this->downloadPath . '/%(title)s.mp4';
            } else {
                $command[] = $this->downloadPath . '/%(title)s.%(ext)s';
            }
        }

        // Audio download configuration
        if ($audioOnly) {
            $command[] = '--extract-audio';
            $command[] = '--audio-format';
            $command[] = $format;
            $command[] = '--audio-quality';
            $command[] = '0'; // Best audio quality
            
            // For MP3, add additional quality settings
            if (strtolower($format) === 'mp3') {
                $command[] = '--postprocessor-args';
                $command[] = 'ffmpeg:-b:a 320k'; // High quality MP3 (320kbps)
            }
            
            $command[] = '--format';
            $command[] = 'bestaudio/best';
        } else {
            // Video download configuration - SIMPLIFIED untuk menghindari error
            if (strtolower($format) === 'mp4') {
                $command[] = '--format';
                // Gunakan format yang lebih sederhana dan universal
                $command[] = $this->getQualityFormat($quality);
                
                // Merge ke MP4 menggunakan ffmpeg
                $command[] = '--merge-output-format';
                $command[] = 'mp4';
                
                // Post-processing untuk memastikan kompatibilitas MP4
                $command[] = '--postprocessor-args';
                $command[] = 'ffmpeg:-c:v copy -c:a aac';
            } else {
                $command[] = '--format';
                $command[] = $this->getQualityFormat($quality);
            }
        }

        // Metadata embedding - DISABLED to avoid FFmpeg errors
        // if ($embedMetadata) {
        //     $command[] = '--embed-metadata';
        //     if (!$audioOnly) {
        //         $command[] = '--embed-thumbnail';
        //     } else {
        //         // For audio files, embed thumbnail as cover art
        //         $command[] = '--embed-thumbnail';
        //         $command[] = '--ppa';
        //         $command[] = 'EmbedThumbnail+ffmpeg_o:-c:v mjpeg -vf crop="\'if(gt(ih,iw),iw,ih)\':\'if(gt(iw,ih),ih,iw)\'"';
        //     }
        // }

        // Subtitles - only for video downloads
        if (!$audioOnly && $subtitles) {
            $command[] = '--write-subs';
            $command[] = '--write-auto-subs';
            $command[] = '--sub-lang';
            $command[] = 'en,id'; // English and Indonesian subtitles
        }

        // Additional options untuk menghindari error
        $command[] = '--no-playlist';
        $command[] = '--ignore-errors';
        $command[] = '--no-warnings';

        // Cookies dan user-agent untuk menghindari blocking
        $command[] = '--user-agent';
        $command[] = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36';
        
        // Retry dan timeout settings
        $command[] = '--retries';
        $command[] = '10';
        $command[] = '--fragment-retries';
        $command[] = '10';
        $command[] = '--socket-timeout';
        $command[] = '30';

        $command[] = $url;

        return $command;
    }

    protected function getQualityFormat(string $quality): string
    {
        // Format yang lebih sederhana dan universal untuk menghindari "All download methods failed"
        return match($quality) {
            'best' => 'bestvideo[ext=mp4]+bestaudio[ext=m4a]/bestvideo+bestaudio/best',
            '2160p' => 'bestvideo[height<=2160]+bestaudio/best[height<=2160]',
            '1440p' => 'bestvideo[height<=1440]+bestaudio/best[height<=1440]',
            '1080p' => 'bestvideo[height<=1080]+bestaudio/best[height<=1080]',
            '720p' => 'bestvideo[height<=720]+bestaudio/best[height<=720]',
            '480p' => 'bestvideo[height<=480]+bestaudio/best[height<=480]',
            '360p' => 'bestvideo[height<=360]+bestaudio/best[height<=360]',
            '240p' => 'bestvideo[height<=240]+bestaudio/best[height<=240]',
            'worst' => 'worstvideo+worstaudio/worst',
            default => 'bestvideo+bestaudio/best'
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

    protected function extractFilePath(string $output, array $options = [], array $info = []): ?string
    {
        $lines = explode("\n", $output);
        $format = $options['format'] ?? 'mp4';
        $audioOnly = in_array(strtolower($format), $this->audioFormats);

        // Check for skipped download (already exists)
        if (strpos($output, 'has already been downloaded') !== false || strpos($output, 'already downloaded') !== false) {
            \Log::info('Download skipped - file already exists, constructing expected path');
            
            // Construct expected filename from title
            if (!empty($info['title'])) {
                $title = $info['title'];
                $expectedExt = $audioOnly ? $format : 'mp4';
                $expectedFilename = $this->downloadPath . '/' . $title . '.' . $expectedExt;
                
                // Handle special characters in title (yt-dlp preserves them)
                if (file_exists($expectedFilename)) {
                    \Log::info('Found existing file via title construction: ' . $expectedFilename);
                    return $expectedFilename;
                }
                
                // Fallback: sanitize title for common patterns
                $sanitizedTitle = preg_replace('/[<>:"/\\|?*]/', '_', $title); // Windows invalid chars
                $sanitizedPath = $this->downloadPath . '/' . $sanitizedTitle . '.' . $expectedExt;
                if (file_exists($sanitizedPath)) {
                    \Log::info('Found existing file via sanitized title: ' . $sanitizedPath);
                    return $sanitizedPath;
                }
                
                // Scan directory for matching title prefix
                $pattern = $this->downloadPath . '/' . preg_quote(substr($title, 0, 50), '/') . '*.' . $expectedExt;
                $files = glob($pattern);
                if (!empty($files)) {
                    // Return the most recent matching file
                    usort($files, function($a, $b) { return filemtime($b) - filemtime($a); });
                    $candidate = $files[0];
                    \Log::info('Found existing file via glob: ' . $candidate);
                    return $candidate;
                }
            }
        }

        // Priority 1: Cari [Merger] output untuk MP4 (PALING PENTING)
        foreach ($lines as $line) {
            if (strpos($line, '[Merger] Merging formats into') !== false) {
                // Extract filename dari dalam quotes
                if (preg_match('/"([^"]+)"/', $line, $matches)) {
                    $filename = $matches[1];
                    // Cek apakah path absolute atau relative
                    if (file_exists($filename)) {
                        return $filename;
                    }
                    // Jika relative, tambahkan download path
                    $fullPath = $this->downloadPath . '/' . $filename;
                    if (file_exists($fullPath)) {
                        return $fullPath;
                    }
                }
                // Fallback: extract tanpa quotes (case Instagram)
                if (preg_match('/Merging formats into (.+)$/', $line, $matches)) {
                    $filename = trim($matches[1], ' "');
                    if (file_exists($filename)) {
                        return $filename;
                    }
                    $fullPath = $this->downloadPath . '/' . $filename;
                    if (file_exists($fullPath)) {
                        return $fullPath;
                    }
                }
            }
        }

        // Priority 2: Cari [VideoConvertor] atau [ffmpeg] Destination
        foreach ($lines as $line) {
            if (strpos($line, '[ffmpeg] Destination:') !== false) {
                $path = trim(str_replace('[ffmpeg] Destination:', '', $line));
                if (file_exists($path)) {
                    return $path;
                }
            }
            if (strpos($line, '[VideoConvertor] Not converting video file') !== false) {
                preg_match('/"([^"]+)"/', $line, $matches);
                if (isset($matches[1]) && file_exists($matches[1])) {
                    return $matches[1];
                }
            }
        }

        // Priority 3: Cari [FixupM4a] atau post-processor output
        foreach ($lines as $line) {
            if (strpos($line, 'Destination:') !== false && strpos($line, '.mp4') !== false) {
                if (preg_match('/"([^"]+\.mp4)"/', $line, $matches)) {
                    $path = $matches[1];
                    if (file_exists($path)) {
                        return $path;
                    }
                }
            }
        }

        // Priority 4: ExtractAudio destination (untuk audio)
        if ($audioOnly) {
            foreach ($lines as $line) {
                if (strpos($line, '[ExtractAudio] Destination:') !== false) {
                    $path = trim(str_replace('[ExtractAudio] Destination:', '', $line));
                    if (file_exists($path)) {
                        return $path;
                    }
                }
            }
        }

        // Priority 5: Collect all download destinations
        $destinations = [];
        foreach ($lines as $line) {
            if (strpos($line, '[download] Destination:') !== false) {
                $path = trim(str_replace('[download] Destination:', '', $line));
                // Handle both absolute and relative paths
                if (file_exists($path)) {
                    $destinations[] = $path;
                } else {
                    $fullPath = $this->downloadPath . '/' . basename($path);
                    if (file_exists($fullPath)) {
                        $destinations[] = $fullPath;
                    } else {
                        $destinations[] = $path; // Keep original for later processing
                    }
                }
            }
        }

        // Untuk video MP4, cari file .mp4 yang sebenarnya
        if (!$audioOnly && strtolower($format) === 'mp4' && !empty($destinations)) {
            foreach (array_reverse($destinations) as $dest) {
                $pathInfo = pathinfo($dest);
                
                // Cek file dengan ekstensi .mp4
                $expectedMp4Path = $pathInfo['dirname'] . '/' . $pathInfo['filename'] . '.mp4';
                if (file_exists($expectedMp4Path)) {
                    return $expectedMp4Path;
                }
                
                // Cek file asli jika sudah .mp4
                if (file_exists($dest) && strtolower($pathInfo['extension'] ?? '') === 'mp4') {
                    return $dest;
                }
            }
        }

        // Fallback: Check destinations in reverse order
        foreach (array_reverse($destinations) as $dest) {
            if (file_exists($dest)) {
                return $dest;
            }
        }

        // Last resort: Scan directory untuk file dengan nama yang mirip
        if (!empty($info['title'])) {
            $safeTitle = preg_replace('/[^a-zA-Z0-9\-_]/', '_', $info['title']);
            $patterns = [
                $this->downloadPath . '/' . $safeTitle . '.mp4',
                $this->downloadPath . '/' . $safeTitle . ' *.mp4',
                $this->downloadPath . '/*' . substr($safeTitle, 0, 20) . '*.mp4'
            ];
            
            foreach ($patterns as $pattern) {
                $files = glob($pattern);
                if (!empty($files)) {
                    // Return the most recent file
                    usort($files, function($a, $b) {
                        return filemtime($b) - filemtime($a);
                    });
                    return $files[0];
                }
            }
        }

        // Ultimate fallback: Return last destination or null
        return !empty($destinations) ? end($destinations) : null;
    }

    public function getAvailableFormats(string $url): array
    {
        $command = [
            'yt-dlp',
            '--list-formats',
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

    /**
     * Download audio only in specified format
     */
    public function downloadAudio(string $url, string $format = 'mp3', array $options = []): Download
    {
        $options['audio_only'] = true;
        $options['format'] = $format;
        
        return $this->downloadVideo($url, $options);
    }

    /**
     * Get supported audio formats
     */
    public function getSupportedAudioFormats(): array
    {
        return $this->audioFormats;
    }
}