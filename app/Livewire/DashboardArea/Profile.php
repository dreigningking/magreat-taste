<?php

namespace App\Livewire\DashboardArea;

use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules\Password;
use App\Mail\NewUserCredentials;

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
    
    // User Management
    public $users;
    public $newUserName;
    public $newUserEmail;
    public $newUserPhone;
    
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
            'newUserName' => 'required|string|max:255',
            'newUserEmail' => 'required|email|max:255|unique:users,email',
            'newUserPhone' => 'nullable|string|max:20',
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
            'newUserName.required' => 'User name is required.',
            'newUserEmail.required' => 'User email is required.',
            'newUserEmail.email' => 'Please enter a valid email address.',
            'newUserEmail.unique' => 'This email is already taken.',
        ];
    }
    
    public function mount()
    {
        $user = Auth::user();
        $this->name = $user->name;
        $this->email = $user->email;
        $this->phone = $user->phone;
        $this->two_factor_enabled = $user->two_factor_enabled;
        $this->loadUsers();
    }
    
    public function loadUsers()
    {
        $this->users = User::orderBy('created_at', 'desc')->get();
    }
    
    public function setActiveSection($section)
    {
        $this->activeSection = $section;
        if ($section === 'users') {
            $this->loadUsers();
        }
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
    
    public function toggleUserStatus($userId)
    {
        $user = User::findOrFail($userId);
        
        // Prevent user from disabling themselves
        if ($user->id === Auth::id()) {
            session()->flash('error', 'You cannot disable your own account.');
            return;
        }
        
        $user->update([
            'is_active' => !$user->is_active,
        ]);
        
        $status = $user->is_active ? 'enabled' : 'disabled';
        session()->flash('success', "User {$user->name} has been {$status} successfully!");
        
        $this->loadUsers();
    }
    
    public function deleteUser($userId)
    {
        $user = User::findOrFail($userId);
        
        // Prevent user from deleting themselves
        if ($user->id === Auth::id()) {
            session()->flash('error', 'You cannot delete your own account.');
            return;
        }
        
        $userName = $user->name;
        $user->delete();
        
        session()->flash('success', "User {$userName} has been deleted successfully!");
        $this->loadUsers();
    }
    
    
    public function addNewUser()
    {
        $this->validate([
            'newUserName' => 'required|string|max:255',
            'newUserEmail' => 'required|email|max:255|unique:users,email',
            'newUserPhone' => 'nullable|string|max:20',
        ]);
        
        // Generate a random password
        $password = Str::random(12);
        
        // Create the new user
        $newUser = User::create([
            'name' => $this->newUserName,
            'email' => $this->newUserEmail,
            'phone' => $this->newUserPhone,
            'password' => Hash::make($password),
            'is_active' => true,
            'two_factor_enabled' => false,
        ]);
        
        // Send email with credentials
        try {
            Mail::to($newUser->email)->send(new NewUserCredentials($newUser, $password));
            session()->flash('success', "User {$newUser->name} has been created successfully! Login credentials have been sent to their email.");
        } catch (\Exception $e) {
            session()->flash('warning', "User {$newUser->name} has been created successfully, but there was an issue sending the email. Please contact them manually.");
        }
        
        $this->dispatch('closeModal',['modalId'=>'addUserModal']);
        $this->loadUsers();
    }

    public function render()
    {
        return view('livewire.dashboard-area.profile');
    }
}
