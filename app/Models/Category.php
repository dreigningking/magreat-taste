<?php

namespace App\Models;

use App\Models\Meal;
use App\Models\Post;
use Illuminate\Database\Eloquent\Model;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory, Sluggable;
    protected $connection = 'mysql'; 

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'slug',
        'description',
        'image',
        'is_active',
        'type'
    ];

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name',
                'onUpdate' => true,
            ]
        ];
    }
    /**
     * Get the tasks for the platform.
     */
    public function meals()
    {
        return $this->hasMany(Meal::class);
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }
}
