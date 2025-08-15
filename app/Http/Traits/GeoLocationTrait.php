<?php
namespace App\Http\Traits;

use App\Models\State;
use App\Models\Country;
use App\Models\Location;


trait GeoLocationTrait
{

    protected function saveLocation($ip,$result){
        $country = $this->getCountry($result->country_code);
        $state = $this->getState($country->id,$result->region,$result->region_code);
        Location::create([
            'ip' => $ip,
            'continent' => $result->continent_name,
            'country_id' => $country->id,
            'country' => $country->name,
            'code' => $country->iso2,
            'dial' => $country->phonecode,
            'state_id' => $state ? $state->id : null,
            'state' => $state ? $state->name : null,
            'city' => $result->city,
        ]);
    }

    protected function getCountry($code){
        $country = Country::where('iso2', $code)->first();
        return $country;
    }

    protected function getState($country_id,$name,$code){
        $state = State::where('country_id', $country_id)->where(function($query) use ($name,$code){
            $query->where('name', 'like', '%'.$name.'%')->orWhere('iso2', 'like', '%'.$code.'%');
        })->first();
        return $state;
    }
    
    protected function visitorIp(){
        $ip = request()->ip() == '::1'|| request()->ip() == '127.0.0.1'? '197.211.58.12' : request()->ip();
        return $ip;
    }
    
}

