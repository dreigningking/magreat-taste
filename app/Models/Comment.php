<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'content',
        'guest_name',
        'guest_email',
        'post_id',  
        'ip_address',
        'user_agent',
        'mentions',
        'status',
        'is_featured',
        'approved_at',
        'approved_by',
        'likes_count',
        
        
    ];

    protected $casts = [
        'mentions' => 'array',
        'is_featured' => 'boolean',
        'approved_at' => 'datetime',
        'likes_count' => 'integer',
    ];

    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class, 'post_id');
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    // Accessors
    public function getCommenterNameAttribute(): string
    {
        return $this->guest_name;
    }

    public function getCommenterEmailAttribute(): string
    {
        return $this->guest_email;
    }

    public function getIsApprovedAttribute(): bool
    {
        return $this->status === 'approved';
    }

    public function getIsPendingAttribute(): bool
    {
        return $this->status === 'pending';
    }

    public function getIsSpamAttribute(): bool
    {
        return $this->status === 'spam';
    }

    public function getIsRejectedAttribute(): bool
    {
        return $this->status === 'rejected';
    }

    public function getFormattedContentAttribute(): string
    {
        return $this->formatContentWithMentions();
    }

    public function getTimeAgoAttribute(): string
    {
        return $this->created_at->diffForHumans();
    }

    // Scopes
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeSpam($query)
    {
        return $query->where('status', 'spam');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeByPost($query, $post_id)
    {
        return $query->where('post_id', $post_id);
    }

    public function scopeRecent($query, $days = 7)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    // Methods
    public function approve($approvedBy = null): bool
    {
        $this->update([
            'status' => 'approved',
            'approved_at' => now(),
            'approved_by' => $approvedBy,
        ]);

        return true;
    }

    public function reject($rejectedBy = null): bool
    {
        $this->update([
            'status' => 'rejected',
            'approved_by' => $rejectedBy,
        ]);
        return true;
    }

    public function markAsSpam(): bool
    {
        $this->update(['status' => 'spam']);
        return true;
    }

    public function toggleFeatured(): bool
    {
        $this->update(['is_featured' => !$this->is_featured]);
        return $this->is_featured;
    }

    public function incrementLikes(): void
    {
        $this->increment('likes_count');
    }

}
