<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $guarded = ['id'];

    public function meal(){
        return $this->belongsTo(Meal::class);
    }

    public function food(){
        return $this->belongsTo(Food::class);
    }

    public function size(){
        return $this->belongsTo(Size::class);
    }
    

    public function order(){
        return $this->belongsTo(Order::class);
    }
}
