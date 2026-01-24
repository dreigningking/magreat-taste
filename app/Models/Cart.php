<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $fillable = [
        'ip',
        'meal_id',
        'food_id',
        'size_id',
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

    public function size()
    {
        return $this->belongsTo(Size::class);
    }

    public function getTotalAttribute()
    {
        return $this->price * $this->quantity;
    }

    
}
