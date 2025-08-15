<?php

namespace App\Models;

use App\Models\Location;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ShipmentRoute extends Model
{
    use SoftDeletes;

    protected $connection = 'mysql';
    protected $table = 'shipment_routes';
    protected $fillable = [
        'shipper_name', 'route_name', 'slug', 'location_id','destination_city_id','base_price','estimated_minutes','notes','status'
    ];

    public function orders()
    {
        return $this->hasMany(Order::class, 'shipment_route_id');
    }
    
    public function location()
    {
        return $this->belongsTo(Location::class, 'location_id');
    }

    public function destinationCity()
    {
        return $this->belongsTo(City::class, 'destination_city_id');
    }

    public function getEstimatedMinutesStringAttribute()
    {
        $minutes = (int) $this->estimated_minutes;

        if ($minutes < 60) {
            return $minutes . ' mins';
        }

        $hours = intdiv($minutes, 60);
        $remainingMinutes = $minutes % 60;

        if ($hours < 24) {
            $result = $hours . ' hr' . ($hours > 1 ? 's' : '');
            if ($remainingMinutes > 0) {
                $result .= ' ' . $remainingMinutes . ' min' . ($remainingMinutes > 1 ? 's' : '');
            }
            return $result;
        }

        $days = intdiv($hours, 24);
        $remainingHours = $hours % 24;

        $result = $days . ' day' . ($days > 1 ? 's' : '');
        if ($remainingHours > 0) {
            $result .= ' ' . $remainingHours . ' hr' . ($remainingHours > 1 ? 's' : '');
        }
        if ($remainingMinutes > 0) {
            $result .= ' ' . $remainingMinutes . ' min' . ($remainingMinutes > 1 ? 's' : '');
        }
        return $result;
    }
}
