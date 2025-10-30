<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Visitor extends Model
{
    protected $fillable = [
        'ip_address',
        'user_agent',
        'visited_at'
    ];

    protected $dates = [
        'visited_at'
    ];

    // Don't use timestamps for this model since we have visited_at
    public $timestamps = false;

    /**
     * Track a visitor (count every page view/refresh)
     */
    public static function trackVisitor($ipAddress, $userAgent = null)
    {
        // Create a new visitor record for every page view
        return self::create([
            'ip_address' => $ipAddress,
            'user_agent' => $userAgent,
            'visited_at' => now()
        ]);
    }

    /**
     * Get total page views (every refresh counts)
     */
    public static function getTotalVisitors()
    {
        return self::count();
    }
}
