<?php

namespace App\Models;

use App\Models\City;
use App\Models\Plan;

use App\Models\State;
use App\Models\CountryPrice;
use App\Models\TaskTemplate;
use App\Models\CountryBanking;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Country extends Model
{
    use HasFactory;

    protected $connection = 'sqlite_countries';
    protected $table = 'countries'; // adjust table name if different
    protected $fillable = [
        'name',
        'code',
        'phone_code',
        'currency',
        'currency_symbol',
        'is_active',
    ];

    
    public function getRouteKeyName(){
        return 'iso2';
    }
    public function states(){
        return $this->hasMany(State::class);
    }
    public function cities(){
        return $this->hasManyThrough(City::class,State::class,'country_id','state_id');
    }

    public function users(){
        return $this->setConnection('mysql')->hasMany(User::class);
    } 

}
