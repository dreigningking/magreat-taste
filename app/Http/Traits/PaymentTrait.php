<?php
namespace App\Http\Traits;
use App\Models\Payment;
use App\Models\Settlement;
use App\Http\Traits\PaystackTrait;
use App\Http\Traits\FlutterwaveTrait;


trait PaymentTrait
{
    use PaystackTrait,FlutterwaveTrait;

    public function initializePayment(Payment $payment){
        $link = $this->initiatePaystack($payment);
        return $link;   
    }

    public function verifyPayment(Payment $payment){
        $details = $this->verifyPaystackPayment($payment->reference);
        return ['status'=> $details->status,
                'trx_status'=> $details->data->status,
                'amount'=> $details->data->amount/100
            ];
    }
}