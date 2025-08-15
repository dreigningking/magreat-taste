<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Post extends Model
{
    use HasFactory, Sluggable;

    protected $fillable = [
        'title',
        'slug',
        'category_id',
        'excerpt',
        'content',
        'featured_image',
        'user_id',
        'status',
        'published_at',
        'meta_keywords',
        'tags',
        'reading_time',
        'allow_comments',
        'featured',
        'broadcasted_at',
    ];

    protected $casts = [
        'tags' => 'array',
        'published_at' => 'datetime',
        'allow_comments' => 'boolean',
        'featured' => 'boolean',
        'reading_time' => 'integer',
        'broadcasted_at' => 'datetime',
    ];

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class, 'post_id');
    }

    public function approvedComments(): HasMany
    {
        return $this->hasMany(Comment::class, 'post_id')->where('status', 'approved');
    }

    public function views(): HasMany
    {
        return $this->hasMany(PostView::class);
    }

    // Accessors
    public function getAuthorNameAttribute(): string
    {
        return $this->user ? $this->user->name : 'Admin';
    }

    public function getIsPublishedAttribute(): bool
    {
        return $this->status === 'published' && 
               $this->published_at && 
               $this->published_at <= now();
    }

    public function getFormattedPublishedDateAttribute(): string
    {
        return $this->published_at ? $this->published_at->format('M d, Y') : 'Not published';
    }

    public function getReadingTimeAttribute($value): int
    {
        if ($value) {
            return $value;
        }

        // Calculate reading time based on content length
        $wordCount = str_word_count(strip_tags($this->content));
        $readingTime = ceil($wordCount / 200); // Average 200 words per minute
        
        // Update the database
        $this->update(['reading_time' => $readingTime]);
        
        return $readingTime;
    }

    public function getTagsListAttribute(): array
    {
        return $this->tags ?? [];
    }

    public function getFeaturedImageUrlAttribute(): string
    {
        if ($this->featured_image) {
            return Storage::url($this->featured_image);
        }
        
        // Return a default image
        return 'https://picsum.photos/seed/' . $this->id . '/1200/420';
    }

    // Scopes
    public function scopePublished($query)
    {
        return $query->where('status', 'published')
                    ->where('published_at', '<=', now());
    }

    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    public function scopeArchived($query)
    {
        return $query->where('status', 'archived');
    }

    public function scopeFeatured($query)
    {
        return $query->where('featured', true);
    }

    public function scopeByTag($query, $tag)
    {
        return $query->whereJsonContains('tags', $tag);
    }

    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('title', 'like', "%{$search}%")
              ->orWhere('content', 'like', "%{$search}%")
              ->orWhere('excerpt', 'like', "%{$search}%");
        });
    }
    

    public function getCommentsCountAttribute(): int
    {
        return $this->comments()->where('status', 'approved')->count();
    }

    public function canBeCommentedOn(): bool
    {
        return $this->allow_comments && $this->is_published;
    }
}
