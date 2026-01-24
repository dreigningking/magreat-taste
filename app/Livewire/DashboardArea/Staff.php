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

class Staff extends Component
{
    // User Management
    public $users;
    public $newUserName;
    public $newUserEmail;
    public $newUserPhone;
    
    protected function rules()
    {
        return [
            'newUserName' => 'required|string|max:255',
            'newUserEmail' => 'required|email|max:255|unique:users,email',
            'newUserPhone' => 'nullable|string|max:20',
        ];
    }
    
    protected function messages()
    {
        return [
            'newUserName.required' => 'User name is required.',
            'newUserEmail.required' => 'User email is required.',
            'newUserEmail.email' => 'Please enter a valid email address.',
            'newUserEmail.unique' => 'This email is already taken.',
        ];
    }
    
    public function mount()
    {
        $this->loadUsers();
    }
    
    public function loadUsers()
    {
        $this->users = User::orderBy('created_at', 'desc')->get();
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
        return view('livewire.dashboard-area.staff');
    }
}
