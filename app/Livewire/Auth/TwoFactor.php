<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Tzsk\Otp\Facades\Otp;
use App\Notifications\OTPNotification;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.landing')]
class TwoFactor extends Component
{
    public $code;
    public $otp_response;
    public $otp_error;

    public function mount()
    {
        $user = Auth::user();
        if ($user && (!session()->has('otp_sent') || abs(now()->diffInMinutes(session('otp_sent'))) > 10)) {
            $otp = Otp::generate($user->email);
            $user->notify(new OTPNotification($otp));
            session(['otp_sent' => now()]);
        }
    }

    public function resend()
    {
        $user = Auth::user();
        if ($user && (!session()->has('otp_sent') || abs(now()->diffInMinutes(session('otp_sent'))) > 10)) {
            $otp = Otp::generate($user->email);
            $user->notify(new OTPNotification($otp));
            session(['otp_sent' => now()]);
            $this->otp_error = null;
            $this->otp_response = 'OTP Sent';
        } else {
            $this->otp_response = null;
            $this->otp_error = 'Wait for about 10 minutes to request again';
        }
    }

    public function verify()
    {
        $this->validate([
            'code' => ['required', 'string']
        ]);
        $user = Auth::user();
        $valid = Otp::match($this->code, $user->email);
        if ($valid) {
            session(['2fa_passed' => true]);
            session()->forget('otp_sent');
            return redirect()->intended(route('dashboard'));
        } else {
            $this->otp_response = null;
            $this->otp_error = 'Invalid OTP';
        }
    }

    public function render()
    {
        return view('livewire.auth.2fa');
    }
} 