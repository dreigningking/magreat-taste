<?php

namespace App\Models;

use App\Models\Food;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Cviebrock\EloquentSluggable\Sluggable;

class Size extends Model
{
    use Sluggable;

    protected $fillable = [
        'name',
        'slug',
        'type',
        'unit',
        'value',
        'image',
    ];

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }

    public function foods(): BelongsToMany
    {
        return $this->belongsToMany(Food::class, 'food_sizes')->withPivot('price');
    }

    public function getImageUrlAttribute()
    {
        return Storage::url($this->image);
    }
}
