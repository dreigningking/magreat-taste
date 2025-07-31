<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FoodSize extends Model
{
    protected $fillable = [
        'food_id',
        'name',
        'image',
        'price',
    ];

    protected $casts = [
        'price' => 'decimal:2',
    ];

    // Relationships
    public function food(): BelongsTo
    {
        return $this->belongsTo(Food::class);
    }

    // Accessors
    public function getFormattedPriceAttribute()
    {
        return '₦' . number_format($this->price, 2);
    }
}
