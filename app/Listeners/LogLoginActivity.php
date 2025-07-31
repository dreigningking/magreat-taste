<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Models\LoginActivity;
use App\Http\Traits\GeoLocationTrait;
use Jenssegers\Agent\Agent;

class LogLoginActivity
{
    use GeoLocationTrait;

    /**
     * Handle the event.
     */
    public function handle(Login $event)
    {
        $user = $event->user;
        $ip = request()->ip();
        $location = $this->getLocation();
        $locationString = $location ? ($location->city . ', ' . $location->country) : null;

        $userAgent = request()->header('User-Agent');

        $agent = new Agent();
        $agent->setUserAgent($userAgent);

        LoginActivity::create([
            'user_id' => $user->id,
            'ip_address' => $ip,
            'browser' => $userAgent,
            'location' => $locationString,
            'device' => $agent->device(),
            'os' => $agent->platform() . ' ' . $agent->version($agent->platform()),
            'browser_name' => $agent->browser(),
            'browser_version' => $agent->version($agent->browser()),
            'platform' => $agent->platform(),
            'platform_version' => $agent->version($agent->platform()),
        ]);
    }
} 