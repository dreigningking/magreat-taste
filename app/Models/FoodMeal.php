<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class FoodMeal extends Pivot
{
    protected $fillable = [
        'food_id', 'meal_id'
    ];

    public function food()
    {
        return $this->belongsTo(Food::class);
    }

    public function meal()
    {
        return $this->belongsTo(Meal::class);
    }
}
