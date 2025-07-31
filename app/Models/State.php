<?php

namespace App\Models;
use App\Models\City;
use App\Models\Location;
use Illuminate\Database\Eloquent\Model;

class State extends Model
{
    protected $connection = 'sqlite_states';
    protected $table = 'states'; // adjust table name if different
    // public $timestamps = false; // if no timestamps
    
    public function country(){
        return $this->belongsTo(Country::class);
    }

    public function cities(){
        return $this->hasMany(City::class);
    }
    
    public function users(){
        return $this->hasMany(User::class);
    }
    public function locations(){
        return $this->hasMany(Location::class);
    }

    
}
