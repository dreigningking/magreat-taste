<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Http\Traits\HasMentions;
use App\Models\User;

class Comment extends Model
{
    use HasFactory, HasMentions;

    protected $fillable = [
        'content',
        'user_id',
        'post_id',
        'guest_name',
        'guest_email',  
        'mentions',
        'status',
        'is_featured',
        'approved_at',
        'approved_by',
        'likes_count',
        'user_agent',
        'ip_address',
    ];

    protected $casts = [
        'mentions' => 'array',
        'is_featured' => 'boolean',
        'approved_at' => 'datetime',
        'likes_count' => 'integer',
    ];

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

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
        return $this->user ? $this->user->name : $this->guest_name;
    }

    public function getCommenterEmailAttribute(): string
    {
        return $this->user ? $this->user->email : $this->guest_email;
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

    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
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

    public function isByGuest(): bool
    {
        return is_null($this->user_id);
    }

    public function isByUser(User $user): bool
    {
        return $this->user_id === $user->id;
    }

    public function canBeEditedBy(User $user): bool
    {
        return $this->isByUser($user) && $this->is_pending;
    }

    public function canBeDeletedBy(User $user): bool
    {
        return $this->isByUser($user) || $user->hasRole('admin');
    }

    // Boot method for processing mentions
    protected static function boot()
    {
        parent::boot();

        static::created(function ($comment) {
            $comment->processMentions();
        });

        static::updated(function ($comment) {
            if ($comment->isDirty('content')) {
                $comment->processMentions();
            }
        });
    }
}
