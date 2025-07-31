<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Ixudra\Curl\Facades\Curl;

use App\Models\CountrySetting;
use App\Http\Traits\GeoLocationTrait;

class LocationMiddleware
{
    use GeoLocationTrait;
    
    public function handle(Request $request, Closure $next)
    {
        //178.238.11.6 || 197.211.58.12

        $ip = $this->visitorIp();
        if(!cache('visitors') || cache('visitors') == null || cache('visitors') == [] || !in_array($ip,cache('visitors'))){
            $result = Curl::to("https://api.ipdata.co/".$ip."?api-key=".config('services.ipdata'))->asJsonResponse()->get();    
            if($result){
                $this->saveLocation($ip,$result);
                $visitors[] = $ip;
                cache(['visitors'=> $visitors]);
            }
        }
        return $next($request);
    }
}
