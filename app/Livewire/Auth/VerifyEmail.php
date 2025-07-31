<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Livewire\Component;
use Tzsk\Otp\Facades\Otp;
use App\Livewire\Auth\Logout;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Illuminate\Support\Facades\Auth;
use App\Notifications\OTPNotification;
use Illuminate\Support\Facades\Session;

#[Layout('components.layouts.landing')]
class VerifyEmail extends Component
{
    
    public $code;
    public $otp_response;
    public $otp_error;
    public $user;
    public $email;
    public $showEditEmail = false;
    public $showCodeInput = false;
    
    public function mount(){
        $this->user = auth()->user();
        if($this->user->email_verified_at){
            return redirect()->route('dashboard');
        }
    }
    
    public function otp_send(){
        // Check if OTP was recently sent (within last 2 minutes)
        if (!session()->has('otp_sent') || abs(now()->diffInMinutes(session('otp_sent'))) > 10) {
            $this->initiateOTP();
            // Reset the OTP sent timestamp
            session(['otp_sent' => now()]);
            $this->otp_error = null;
            $this->otp_response = 'OTP Sent';
        }else{
            $this->otp_response = null;
            $this->otp_error = 'Wait for about 10 minutes to request again';
        }
        
        
    }

    public function initiateOTP(){
        $otp = Otp::generate($this->user->email);
        $this->user->notify(new OTPNotification($otp));
    }

    public function otp_verify(){
        $this->validate([
            'code' => ['required', 'string']
        ]);
        $valid = Otp::match($this->code, $this->user->email);
        if($valid){
            User::where('id',$this->user->id)->update(['email_verified_at' => now()]);
            session(['2fa_passed' => true]);
            session()->forget('otp_sent');
            return redirect()->route('dashboard');
        }else{
            $this->otp_response = null;
            $this->otp_error = 'Invalid OTP';
        }
    }

    public function saveEmail()
    {
        //dd()
        $this->validate([
            'email' => 'required|email|unique:users,email,' . $this->user->id,
        ]);
        $this->user->email = $this->email;
        $this->user->save();
        $this->showEditEmail = false;
        $this->otp_error = null;
        $this->otp_response = null;
    }

    public function updatedShowCodeInput($value)
    {
        if ($value) {
            $this->otp_error = null;
            $this->otp_response = null;
        }
    }

}
