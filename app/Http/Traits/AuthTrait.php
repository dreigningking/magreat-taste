<?php
namespace App\Http\Traits;


use Illuminate\Support\Facades\Auth;


trait AuthTrait
{

    public function logoutUser()
    {
        Auth::logout();
        return redirect()->route('signin');
    }

    public function forgotUserPassword()
    {
        dd($this->email);
    }

    public function resetUserPassword() 
    {
        dd($this->email, $this->password, $this->password_confirmation);
    }

    public function updateUserPassword()    
    {
        dd($this->email, $this->password, $this->password_confirmation);
    }

    public function verifyUserEmail()
    {
        dd($this->email);
    }

    public function resendVerificationEmailToUser()
    {   
        dd($this->email);
    }

    public function sendResetLinkToUser()
    {
        dd($this->email);
    }
    
    
}
