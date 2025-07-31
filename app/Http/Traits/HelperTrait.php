<?php
namespace App\Http\Traits;

use Ixudra\Curl\Facades\Curl;

trait HelperTrait
{
    public function getTimeConversion($minutes){
        $hours = floor($minutes / 60);
        $days = floor($hours / 24);
        $weeks = floor($days / 7);
        $months = floor($days / 30);
        
        if ($minutes < 60) {
            return $minutes . ' mins';
        } elseif ($hours < 24) {
            return $hours . ' hr' . ($hours > 1 ? 's' : '');
        } elseif ($days < 7) {
            return $days . ' day' . ($days > 1 ? 's' : '');
        } elseif ($weeks < 4) {
            return $weeks . ' week' . ($weeks > 1 ? 's' : '');
        } else {
            return $months . ' month' . ($months > 1 ? 's' : '');
        }
    }

    
}