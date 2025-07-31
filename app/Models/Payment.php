<?php

namespace App\Models;

use App\Observers\PaymentObserver;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;

#[ObservedBy([PaymentObserver::class])]
class Payment extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'amount' => 'float',
    ];

    public function getRouteKeyName(){
        return 'reference';
    }

    public function order(){
        return $this->belongsTo(Order::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
}
