<?php
namespace App\Http\Traits;
use App\Models\Coupon;
use App\Models\Payment;
use App\Models\Settlement;
use App\Models\PaymentItem;
use App\Http\Traits\OrderTrait;
use App\Http\Traits\PaypalTrait;
use App\Http\Traits\PaystackTrait;
use App\Http\Traits\FlutterwaveTrait;
use App\Models\BankAccount;

trait PaymentTrait
{
    use PaystackTrait,FlutterwaveTrait,PaypalTrait;

    public function initializePayment(Payment $payment){
        switch($payment->gateway){
            case 'paystack': 
                $link = $this->initiatePaystack($payment);
                return $link;
            break;
            case 'flutterwave': 
                $link = $this->initiateFlutterWave($payment);
                return ['link'=> $link,'reference'=> $payment->reference];
            break;
            case 'paypal': 
                $result = $this->initiatePaypal($payment);
                $payment->request_id = $payment->reference;
                $payment->reference = $result['reference'];
                $payment->save();
                return $result;
            break;
            case 'stripe': $link = $this->initiateStripe($payment);
                return true;
            break;
            default: return false;
        }
        
    }

    public function initializeRefund(Settlement $settlement){
        
        $gateway = $settlement->receiver->country->payment_gateway;
        switch($gateway){
            case 'paystack': return $this->refundPaystack($settlement);
            break;
            case 'flutterwave': return $this->refundFlutterWave($settlement);
            break;
            case 'paypal': return $this->refundPaypal($settlement);
            break;
            case 'stripe': return $this->refundStripe($settlement);
            break;
        }
    }

    public function verifyPayment(Payment $payment){
        $gateway = $payment->gateway;
        switch($gateway){
            case 'paystack': 
                $details = $this->verifyPaystackPayment($payment->reference);
                return ['status'=> $details->status,
                        'trx_status'=> $details->data->status,
                        'amount'=> $details->data->amount/100
                    ];
            break;
            case 'flutterwave': 
                $details = $this->verifyFlutterWavePayment($payment->reference);
                $payment->request_id = $details->data->id;
                $payment->save();
                return ['status'=> $details->status == 'success'? true:false,
                        'trx_status'=> $details->data->status == 'successful' ? 'success':'failed',
                        'amount'=> $details->data->amount
                        ];
            break;
            case 'paypal': 
                $details =  $this->verifyPaypalPayment($payment->reference,$payment->request_id);
                $payment->reference = $details->purchase_units[0]->payments->captures[0]->id;
                $payment->save();
                return ['status'=> $details->status == 'COMPLETED'? true:false,
                        'trx_status'=> $details->purchase_units[0]->payments->captures[0]->final_capture ? 'success':'failed',
                        'amount'=> $details->purchase_units[0]->payments->captures[0]->amount->value,
                        ];
            break;
            case 'stripe': $details =  $this->verifyStripePayment($payment->reference);
            break;
        }
    }

    public function listBanks($gateway,$country){
        switch($gateway){
            case 'paystack': 
                //ghana,kenya,nigeria,south africa
                return $this->banksByPaystack($country);
            break;
            case 'flutterwave': 
                return $this->banksByFlutter($country);
            break;
            case 'paypal': 
                return $this->banksByPaypal($country);
            break;
            case 'stripe':
                return $this->banksByStripe($country);
            break;
            default: return false;
        }

    }

    public function verifyBankAccount($gateway,$bank_code, $account_number,$account_name){
        switch($gateway){
            case 'paystack': 
                $gateway_account_name = $this->resolveBankAccountByPaystack($bank_code, $account_number);
                if ($gateway_account_name && str_contains(strtolower($gateway_account_name), strtolower($account_name))) {
                    return true;
                }
                break;
            case 'flutterwave': 
                $gateway_account_name = $this->resolveBankAccountByFlutter($bank_code, $account_number);
                if ($gateway_account_name && str_contains(strtolower($gateway_account_name), strtolower($account_name))) {
                    return true;
                }
                break;
        }
        return false;
    }


    
    

}