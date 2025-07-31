<?php

namespace App\Http\Controllers;

use App\Models\Payout;
use App\Models\Payment;
use Illuminate\Http\Request;
use App\Http\Traits\PaymentTrait;

class PaymentController extends Controller
{
    use PaymentTrait;
    
    public function __construct(){
        
    }

    public function paymentcallback(){       
        $user = auth()->user();
        //dd(request()->query());
        if(!request()->reference){
            return response()->json([
                'status' => false,
                'message' => 'Reference Not Found',
            ], 401);
        }else $reference = request()->reference;
        $payment = Payment::where('reference',$reference)->first();
        //if there's no payent or payment is already successful or the payer is not the auth user
        if(!$payment || $payment->status == 'success' || $payment->user_id != $user->id){
            if(request()->expectsJson()){
                return response()->json([
                    'status' => false,
                    'message' => 'Payment Completed',
                ], 401);
            }else \abort(404);
        }
        $details = $this->verifyPayment($payment);
        if(!$details['status'] || $details['trx_status'] != 'success' || $details['amount'] < $payment->amount){
            if(request()->expectsJson()){
                return response()->json([
                    'status' => false,
                    'message' => 'Payment was not successful. Please try again',
                ], 401);
            }else return redirect()->route('index')->with(['result'=> 0,'message'=> 'Payment was not successful. Please try again']);
            
        }
        $payment->status = 'success';
        $payment->save();
        $redirectTo = route('dashboard');
        foreach($payment->order->orderItems as $item){           
            if($item->orderable_type == 'App\Models\Task'){
                //send emails to all those subscribed
                $redirectTo = route('jobs.view',$item->orderable);
            }
            if($item->orderable_type == 'App\Models\TaskPromotion'){
                $item->orderable->start_at = now();
                $item->orderable->save();
                $redirectTo = route('jobs.view',$item->orderable->task);
            }
            if($item->orderable_type == 'App\Models\Subscription'){
                $subscription = $item->orderable;

                // Check for an existing active subscription for the same plan
                $activeSubscription = $user->subscriptions()
                    ->where('plan_id', $subscription->plan_id)
                    ->where('id', '!=', $subscription->id)
                    ->where('expires_at', '>', now())
                    ->orderBy('expires_at', 'desc')
                    ->first();

                if ($activeSubscription) {
                    // If an active subscription exists, stack the new one after it
                    $subscription->starts_at = $activeSubscription->expires_at;
                    $subscription->expires_at = $activeSubscription->expires_at->addMonths($subscription->duration_months);
                } else {
                    // Otherwise, the new subscription starts now
                    $subscription->starts_at = now();
                    $subscription->expires_at = now()->addMonths($subscription->duration_months);
                }

                $subscription->save();
            }
        }
        return redirect()->to($redirectTo)->with(['result'=>1,'message'=> 'Payment Successful']);
            
    }

    
}
