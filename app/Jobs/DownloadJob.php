<?php

namespace App\Jobs;

use App\Models\Download;
use App\Services\DownloaderServiceFactory;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class DownloadJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected Download $download;
    protected array $options;

    /**
     * Create a new job instance.
     */
    public function __construct(Download $download, array $options = [])
    {
        $this->download = $download;
        $this->options = $options;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            Log::info('Starting download job', [
                'download_id' => $this->download->id,
                'url' => $this->download->url,
                'platform' => $this->download->platform
            ]);

            // Update status to downloading
            $this->download->update([
                'status' => 'downloading',
                'progress' => 0
            ]);

            // Get the service and download
            $service = DownloaderServiceFactory::create($this->download->platform);
            $result = $service->downloadVideo($this->download->url, $this->options);

            // Update the download record with results
            if ($result && $result->status === 'completed') {
                Log::info('Download job completed successfully', [
                    'download_id' => $this->download->id,
                    'file_path' => $result->file_path
                ]);
            } else {
                Log::error('Download job failed', [
                    'download_id' => $this->download->id,
                    'error' => $result->error_message ?? 'Unknown error'
                ]);
            }

        } catch (\Exception $e) {
            Log::error('Download job exception', [
                'download_id' => $this->download->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            $this->download->update([
                'status' => 'failed',
                'error_message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error('Download job failed permanently', [
            'download_id' => $this->download->id,
            'error' => $exception->getMessage()
        ]);

        $this->download->update([
            'status' => 'failed',
            'error_message' => 'Job failed: ' . $exception->getMessage()
        ]);
    }
}
