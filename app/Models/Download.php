<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Download extends Model
{
    protected $fillable = [
        'url',
        'platform',
        'title',
        'thumbnail',
        'duration',
        'file_path',
        'file_size',
        'status',
        'error_message',
        'metadata',
        'folder'
    ];

    protected $casts = [
        'metadata' => 'array'
    ];

    public function platform(): BelongsTo
    {
        return $this->belongsTo(Platform::class, 'platform', 'name');
    }

    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'pending' => 'warning',
            'downloading' => 'info',
            'completed' => 'success',
            'failed' => 'danger',
            default => 'secondary'
        };
    }

    public function getFormattedFileSizeAttribute(): string
    {
        if (!$this->file_size) return 'Unknown';
        
        $bytes = (int) $this->file_size;
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, 2) . ' ' . $units[$i];
    }

    public function getPublicFilePathAttribute(): ?string
    {
        if (!$this->file_path) {
            return null;
        }

        $storagePath = storage_path('app/public/');
        $fullPath = $this->file_path;

        // If file_path is full path, convert to relative
        if (strpos($fullPath, $storagePath) === 0) {
            $relativePath = substr($fullPath, strlen($storagePath));
        } else {
            // Assume it's already relative
            $relativePath = $this->file_path;
            $fullPath = $storagePath . $relativePath;
        }

        if (!file_exists($fullPath)) {
            return null;
        }

        return $relativePath;
    }

    public function getVideoTypeAttribute(): string
    {
        if (!$this->file_path) {
            return 'video/mp4';
        }

        $extension = pathinfo($this->file_path, PATHINFO_EXTENSION);
        $mimeTypes = [
            'mp4' => 'video/mp4',
            'webm' => 'video/webm',
            'ogg' => 'video/ogg',
            'avi' => 'video/avi',
            'mov' => 'video/quicktime',
        ];

        return $mimeTypes[strtolower($extension)] ?? 'video/mp4';
    }
}
