<?php

namespace App\Observers;

use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\Notification;
use App\Notifications\NewOrderNotification;
use App\Notifications\AdminNewOrderNotification;
use App\Notifications\OrderStatusUpdateNotification;

class OrderObserver
{
    /**
     * Handle the Order "created" event.
     */
    public function created(Order $order): void
    {
        // Send order confirmation email to customer
        if ($order->email) {
            Notification::route('mail', $order->email)
                ->notify(new NewOrderNotification($order));
        }
        
        // Send notification to all users (admin)
        $users = User::all();
        if ($users->isNotEmpty()) {
            Notification::send($users, new AdminNewOrderNotification($order));
        }
    }

    /**
     * Handle the Order "updated" event.
     */
    public function updated(Order $order): void
    {
        // Check if status has changed
        if ($order->wasChanged('status')) {
            $oldStatus = $order->getOriginal('status');
            $newStatus = $order->status;
            
            // Send status update email to customer
            if ($order->email) {
                Notification::route('mail', $order->email)
                    ->notify(new OrderStatusUpdateNotification($order, $oldStatus, $newStatus));
            }
        }
    }
    
}
