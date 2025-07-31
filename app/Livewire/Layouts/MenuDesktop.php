<?php

namespace App\Livewire\Layouts;

use Livewire\Component;
use App\Livewire\Actions\Logout;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;

class MenuDesktop extends Component
{
    public $isAuthenticated = false;

    public function mount()
    {
        $this->isAuthenticated = Auth::check();
    }

    #[On('UserHasLoggedIn')]
    public function handleUserLogin()
    {
        $this->isAuthenticated = true;
    }

    public function logout()
    {
        return app(Logout::class)();
    }
    
    public function render()
    {
        return view('livewire.layouts.menu-desktop');
    }
}
