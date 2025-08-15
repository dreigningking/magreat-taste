<?php

namespace App\Models;

use App\Models\City;
use App\Models\State;
use App\Models\Country;
use App\Models\ShipmentRoute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Location extends Model
{
    use HasFactory;
    protected $connection = 'mysql';
    protected $table = 'locations';
    protected $fillable = ['name','country_id','state_id','city_id','address','status'];

    public function country(){
        return $this->belongsTo(Country::class);
    }

    public function state(){
        return $this->belongsTo(State::class);
    }

    public function city(){
        return $this->belongsTo(City::class);
    }

    public function shipmentRoutes()
    {
        return $this->hasMany(ShipmentRoute::class, 'location_id');
    }
    
}
