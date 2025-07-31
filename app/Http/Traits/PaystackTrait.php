<?php
namespace App\Http\Traits;

use App\Models\Payout;
use App\Models\Payment;
use App\Models\Settlement;
use Ixudra\Curl\Facades\Curl;


trait PaystackTrait
{

    public function initiatePaystack(Payment $payment){
      $response = Curl::to('https://api.paystack.co/transaction/initialize')
      ->withHeader('Authorization: Bearer '.config('services.paystack.secret'))
      ->withHeader('Content-Type: application/json')
      ->withData( array('email'=> $payment->user->email,'amount'=> intval($payment->amount*100),'currency'=> strtoupper($payment->currency),
                      'reference'=> $payment->reference,"callback_url"=> route('payment.callback') ) )
      ->asJson()                
      ->post();
      if($response &&  isset($response->status) && $response->status)
        return $response->data->authorization_url;
      else return false;
    }

    protected function verifyPaystackPayment($value){
        $paymentDetails = Curl::to('https://api.paystack.co/transaction/verify/'.$value)
         ->withHeader('Authorization: Bearer '.config('services.paystack.secret'))
         ->asJson()
         ->get();
        return $paymentDetails;
    }

    protected function refundPaystack(Settlement $settlement){
        $response = Curl::to('https://api.paystack.co/refund')
         ->withHeader('Authorization: Bearer '.config('services.paystack.secret'))
         ->withData( array('transaction'=> $settlement->order->payment_item->payment->reference,'amount'=> intval($settlement->amount*100 )) )
         ->asJson()
         ->post();
         if($response &&  isset($response->status) && $response->status)
         return true;
       else return false;
    }

    protected function createRecipient($bank_code,$account_number){
      /** @var \App\Models\User $user */
      $user = auth()->user();
      $type = '';
      $currency = '';
      switch($user->country->iso){
        case 'NG': 
          $type = 'nuban'; 
          $currency = $user->country->currency->iso;
        break;
        case 'GH': $type = 'mobile_money'; $currency = $user->country->currency->iso;
        break;
        case 'ZAR': $type = 'basa'; $currency = $user->country->currency->iso;
        break;
      }
      $response = Curl::to('https://api.paystack.co/transferrecipient')
        ->withHeader('Authorization: Bearer '.config('services.paystack.secret'))
        ->withHeader('Content-Type: application/json')
        ->withData( array('type'=> $type,'account_number'=> $account_number,'currency'=> $currency,
                        'bank_code'=> $bank_code ) )
        
        ->asJson()                
        ->post();
      if($response && isset($response->status) && $response->status)
        return $response->data->recipient_code; 
      else return false;
    }


    public function payoutPaystack(Payout $payout){
        
        $response = Curl::to('https://api.paystack.co/transfer')
        ->withHeader('Authorization: Bearer '.config('services.paystack.secret'))
        ->withHeader('Content-Type: application/json')
        ->withData( array("source" => "balance", "reason"=> "Withdrawal Payout", "amount"=> $payout->amount * 100, "recipient"=> $payout->user->payout_account,
        "currency"=> $payout->currency->code,"reference"=> $payout->reference ) )
        ->asJson()                
        ->post();
        
        
      if(!$response || !$response->status || $response->data->status == 'failed'){
          $payout->transfer_id = $response->data->transfer_code ?? '';
          $payout->status = 'failed';
          $payout->save();
      }
      if($response && $response->status && in_array($response->data->status,['success','pending'])){
          $payout->transfer_id = $response->data->transfer_code ?? '';
          $payout->status = 'processing';
          $payout->save();
      }
    }

    protected function verifyPayoutPaystack(Payout $payout){
      $response = Curl::to("https://api.paystack.co/transfer/verify/$payout->reference")
          ->withHeader('Authorization: Bearer '.config('services.paystack.secret'))
          ->asJson()
          ->get();
          if(!$response || !$response->status || $response->data->status == 'failed'){
                $payout->status = 'failed';
                $payout->save();
          }
          if($response && $response->status && $response->data->status == 'success'){
                $payout->status = 'paid'; 
                $payout->paid_at = now(); 
                $payout->save();
          }
    }
    
    

    protected function retryPayoutPaystack(Settlement $payout){
        if($payout->status == 'failed'){
          $payout->reference = uniqid();
          $payout->save();
        } 
        $response = Curl::to('https://api.paystack.co/transfer')
        ->withHeader('Authorization: Bearer '.config('services.paystack.secret'))
        ->withHeader('Content-Type: application/json')
        ->withData( array("source" => "balance", "reason"=> "Withdrawal Payout", "amount"=> $payout->amount * 100, "recipient"=> $payout->user->payout_account,
        "currency"=> $payout->currency->code,"reference"=> $payout->reference ) )
        ->asJson()                
        ->post();
        // dd($response);
        
        if(!$response || !$response->status || $response->data->status == 'failed'){
            $payout->transfer_id = $response->data->transfer_code ?? '';
            $payout->status = 'failed';
            $payout->save();
        }
        if($response && $response->status && in_array($response->data->status,['success','pending'])){
            $payout->transfer_id = $response->data->transfer_code ?? '';
            $payout->status = 'processing';
            $payout->save();
        }
    }

    protected function banksByPaystack($country){
      $response = Curl::to("https://api.paystack.co/bank?country=".$country)
            ->withHeader('Authorization: Bearer '.config('services.paystack.secret'))
            ->withHeader('Content-Type: application/json')
            ->asJsonResponse()
            ->get();
      if(!$response || !$response->status){
        return false;
      }
      return collect($response->data)->map(function ($bank) {
          return [
              'name' => $bank->name,
              'code' => $bank->code,
              'currency' => $bank->currency,
          ];
      })->all();
    }

    protected function resolveBankAccountByPaystack($bank_code,$account_number){

      $response = Curl::to('https://api.paystack.co/bank/resolve')
            ->withHeader('Authorization: Bearer '.config('services.paystack.secret'))
            ->withHeader('Content-Type: application/json')
            ->withData( array('account_number'=> $account_number,"bank_code" => $bank_code) )
            ->asJsonResponse()
            ->get();
      if(!$response || !$response->status){
        return false;
      }
      return $response->data->account_name;
  }


}