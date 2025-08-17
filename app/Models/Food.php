<?php

namespace App\Models;

use App\Models\Size;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Food extends Model
{
    protected $fillable = [
        'name',
        'description',
    ];

    // Relationships
    public function sizes(): BelongsToMany
    {
        return $this->belongsToMany(Size::class, 'food_sizes')->withPivot('price');
    }

    public function meals(): BelongsToMany
    {
        return $this->belongsToMany(Meal::class);
    }

    // Accessors
    public function getMinPriceAttribute()
    {
        return $this->sizes()->min('food_sizes.price') ?? 0;
    }

    public function getMaxPriceAttribute()
    {
        return $this->sizes()->max('food_sizes.price') ?? 0;
    }

    public function getPriceRangeAttribute()
    {
        $min = $this->min_price;
        $max = $this->max_price;
        
        if ($min == $max) {
            return '₦' . number_format($min, 2);
        }
        
        return '₦' . number_format($min, 2) . ' - ₦' . number_format($max, 2);
    }

    public function getMealsCountAttribute()
    {
        return $this->meals()->count();
    }

    public function getDefaultImageAttribute()
    {
        $defaultSize = $this->sizes()->whereNotNull('image')->first();
        return $defaultSize ? $defaultSize->image : null;
    }
}
