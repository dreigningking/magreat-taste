<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ShipmentRoute;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'ip',
        'name',
        'email',
        'phone',
        'delivery_type',
        'delivery_date',
        'delivery_time',
        'address',
        'state',
        'city',
        'instructions',
        'shipment_fee',
        'vat_amount',
        'status',
        'refund_amount',
        'shipment_route_id',
    ];

    protected $casts = [
        'delivery_date' => 'date',
        'delivery_time' => 'datetime',
        'shipment_fee' => 'decimal:2',
        'vat_amount' => 'decimal:2',
        'refund_amount' => 'decimal:2',
    ];

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    public function shipmentRoute()
    {
        return $this->belongsTo(ShipmentRoute::class);
    }

    public function getSubTotalAttribute(){
        return $this->orderItems->sum('price');
    }

    public function getTotalAttribute(){
        return $this->sub_total + $this->shipment_fee + $this->vat_amount;
    }

    public function getStatusColorAttribute(){
        return [
            'pending' => 'warning',
            'processing' => 'info',
            'completed' => 'success',
            'cancelled' => 'danger'
        ][$this->status] ?? 'secondary';
    }

    public function getDeliveryColorAttribute(){
        return [
            'today' => 'success',
            'tomorrow' => 'primary',
            'future' => 'info',
            'past' => 'secondary'
        ][$this->delivery_type] ?? 'secondary';
    }

    public function getDeliveryDetailsAttribute(){
        $message = '';
        if($this->delivery_date && $this->delivery_date->isToday()) {
            $message = $this->delivery_type === 'pickup' ? 'Pickup Today '. $this->delivery_time->format('h:i A') : 'Ship Today '. $this->delivery_time->format('h:i A');
        } elseif ($this->delivery_date->isTomorrow()) {
            $message = $this->delivery_type === 'pickup' ? 'Pickup Tomorrow '. $this->delivery_time->format('h:i A') : 'Delivery Tomorrow '. $this->delivery_time->format('h:i A');
        } elseif ($this->delivery_date->isFuture()) {
            $message = $this->delivery_type === 'pickup' ? 'Pickup ' . $this->delivery_date->format('M d') : 'Ship ' . $this->delivery_date->format('M d');
        } else {
            $message = $this->delivery_type === 'pickup' ? 'Pickup ' . $this->delivery_date->format('M d') : 'Ship ' . $this->delivery_date->format('M d');
        }
        return $message;
    }
}
