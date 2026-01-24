<?php

namespace App\Livewire\DashboardArea;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class Profile extends Component
{
    public $activeSection = 'basic';
    
    // Basic Information
    public $name;
    public $email;
    public $phone;
    public $two_factor_enabled;
    
    // Password Change
    public $current_password;
    public $new_password;
    public $new_password_confirmation;
    
    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . Auth::id(),
            'phone' => 'nullable|string|max:20',
            'two_factor_enabled' => 'boolean',
            'current_password' => 'required_with:new_password|string',
            'new_password' => 'nullable|string|min:8|confirmed',
            'new_password_confirmation' => 'nullable|string',
        ];
    }
    
    protected function messages()
    {
        return [
            'name.required' => 'Name is required.',
            'email.required' => 'Email is required.',
            'email.email' => 'Please enter a valid email address.',
            'email.unique' => 'This email is already taken.',
            'phone.max' => 'Phone number is too long.',
            'current_password.required_with' => 'Current password is required to change password.',
            'new_password.min' => 'New password must be at least 8 characters.',
            'new_password.confirmed' => 'Password confirmation does not match.',
        ];
    }
    
    public function mount()
    {
        $user = Auth::user();
        $this->name = $user->name;
        $this->email = $user->email;
        $this->phone = $user->phone;
        $this->two_factor_enabled = $user->two_factor_enabled;
    }
    
    public function setActiveSection($section)
    {
        $this->activeSection = $section;
    }
    
    public function updateBasicInfo()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . Auth::id(),
            'phone' => 'nullable|string|max:20',
        ]);
        
        $user = Auth::user();
        $user->update([
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
        ]);
        
        session()->flash('success', 'Basic information updated successfully!');
    }
    
    public function toggleTwoFactor()
    {
        $user = Auth::user();
        $user->update([
            'two_factor_enabled' => !$user->two_factor_enabled,
        ]);
        
        $this->two_factor_enabled = $user->two_factor_enabled;
        
        $status = $user->two_factor_enabled ? 'enabled' : 'disabled';
        session()->flash('success', "Two-factor authentication {$status} successfully!");
    }
    
    public function updatePassword()
    {
        $this->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:8|confirmed',
            'new_password_confirmation' => 'required|string',
        ]);
        
        $user = Auth::user();
        
        // Check if current password is correct
        if (!Hash::check($this->current_password, $user->password)) {
            session()->flash('error', 'Current password is incorrect.');
            return;
        }
        
        // Update password
        $user->update([
            'password' => Hash::make($this->new_password),
        ]);
        
        // Reset password fields
        $this->reset(['current_password', 'new_password', 'new_password_confirmation']);
        
        session()->flash('success', 'Password updated successfully!');
    }

    public function render()
    {
        return view('livewire.dashboard-area.profile');
    }
}
