<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Support\Facades\Storage;

class Meal extends Model
{
    use Sluggable;

    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'excerpt',
        'description',
        'image',
        'video',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    // Relationships
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function foods(): BelongsToMany
    {
        return $this->belongsToMany(Food::class);
    }

    // Accessors
    public function getFromPriceAttribute()
    {
        $totalPrice = 0;
        
        foreach ($this->foods as $food) {
            $minPrice = $food->sizes()->min('food_sizes.price') ?? 0;
            $totalPrice += $minPrice;
        }
        
        return $totalPrice;
    }

    public function getFormattedFromPriceAttribute()
    {
        return '₦' . number_format($this->from_price, 2);
    }

    public function getImageUrlAttribute()
    {
        if ($this->image) {
            return Storage::url($this->image);
        }
        
        // Return a default image
        return 'https://picsum.photos/seed/' . $this->id . '/400/300';
    }

    public function getVideoUrlAttribute()
    {
        return $this->video;
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByCategory($query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }

    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('excerpt', 'like', "%{$search}%")
              ->orWhere('description', 'like', "%{$search}%");
        });
    }
}
