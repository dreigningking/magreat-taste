<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $fillable = [
        'ip',
        'meal_id',
        'food_id',
        'food_size_id',
        'price',
        'quantity',
        'amount'
    ];



    public function meal()
    {
        return $this->belongsTo(Meal::class);
    }

    public function food()
    {
        return $this->belongsTo(Food::class);
    }

    public function foodSize()
    {
        return $this->belongsTo(FoodSize::class);
    }

    public function getTotalAttribute()
    {
        return $this->price * $this->quantity;
    }

    
}
