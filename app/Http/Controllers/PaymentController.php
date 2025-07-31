<?php

namespace App\Http\Controllers;

use App\Models\Payout;
use App\Models\Payment;
use Illuminate\Http\Request;
use App\Http\Traits\CartTrait;
use App\Http\Traits\PaymentTrait;

class PaymentController extends Controller
{
    use PaymentTrait,CartTrait;
    
    public function __construct(){
        
    }

    public function paymentcallback(){       
        $user = auth()->user();
        //dd(request()->query());
        if(!request()->reference){
           abort(404);
        }else $reference = request()->reference;
        $payment = Payment::where('reference',$reference)->first();
        //if there's no payent or payment is already successful or the payer is not the auth user
        if(!$payment || $payment->status == 'success'){
            return redirect()->route('index')->with(['result'=> 0,'message'=> 'Payment was not successful. Please try again']);
        }
        $details = $this->verifyPayment($payment);
        $payment->status = $details['trx_status'];
        $payment->save();
        if($details['trx_status'] == 'success'){
            $this->removeFromCartDb();
        }
        return redirect()->route('payment.status',['payment'=> $payment]);       
    }

    
}
