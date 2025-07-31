<?php

namespace App\Models;

use App\Models\User;
use App\Models\State;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    protected $fillable = ['name','state_id','delivery'];
    protected $connection = 'sqlite_cities';
    protected $table = 'cities'; // adjust table name if different

    public function state(){
        return $this->belongsTo(State::class);
    }

    // public function country(){
    //     return $this->belongsTo(State::class)->belongsTo(Country::class);
    // }

    
    public function users(){
        return $this->hasMany(User::class);
    }
    

}
