<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class PostView extends Model
{
    protected $fillable = [
        'post_id',
        'ip_address',
        'user_agent',
        'viewed_at',
        'duration_seconds',
        'is_qualified',
    ];

    protected $casts = [
        'viewed_at' => 'datetime',
        'is_qualified' => 'boolean',
    ];

    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    /**
     * Check if a view is qualified (5+ minutes)
     */
    public function isQualified()
    {
        return $this->duration_seconds >= 300; // 5 minutes = 300 seconds
    }

    /**
     * Mark view as qualified if duration is sufficient
     */
    public function markAsQualified()
    {
        if ($this->duration_seconds >= 300) {
            $this->update(['is_qualified' => true]);
        }
    }
}
