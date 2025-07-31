<?php

namespace App\Observers;

use App\Models\Payment;
use App\Jobs\NotifyUrgentTaskPromotion;
use App\Models\TaskPromotion;

class PaymentObserver
{
    /**
     * Handle the Payment "created" event.
     */
    public function created(Payment $payment): void
    {
        if ($payment->status === 'success') {
            $order = $payment->order;
            if (!$order){
                return;
            } 
            $order->update([
                'status' => 'processing'
            ]);
        }
    }

    /**
     * Handle the Payment "updated" event.
     */
    public function updated(Payment $payment): void
    {
        if ($payment->isDirty('status') && $payment->status === 'success') {
            
            $order = $payment->order;
            if (!$order){
                return;
            } 
            $order->update([
                'status' => 'processing'
            ]);
            
            
        }
    }

    /**
     * Handle the Payment "deleted" event.
     */
    public function deleted(Payment $payment): void
    {
        //
    }

    /**
     * Handle the Payment "restored" event.
     */
    public function restored(Payment $payment): void
    {
        //
    }

    /**
     * Handle the Payment "force deleted" event.
     */
    public function forceDeleted(Payment $payment): void
    {
        //
    }
}
