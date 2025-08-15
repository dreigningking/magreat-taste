<?php

namespace App\Models;

use App\Models\User;
use App\Models\State;

use App\Models\Location;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    protected $connection = 'sqlite_cities';
    protected $table = 'cities'; // adjust table name if different

    public function state(){
        return $this->belongsTo(State::class);
    }

    public function locations(){
        return $this->setConnection('mysql')->hasMany(Location::class);
    }

    
    public function users(){
        return $this->hasMany(User::class);
    }
    

}
