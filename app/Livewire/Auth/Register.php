<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Livewire\Component;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Traits\GeoLocationTrait;
use Illuminate\Auth\Events\Registered;
use App\Notifications\WelcomeNotification;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.landing')]
class Register extends Component
{
    use GeoLocationTrait;

    public string $name = '';

    public string $email = '';

    public string $password = '';

    public string $password_confirmation = '';

    public bool $terms = false;

    /**
     * Handle an incoming registration request.
     */
    public function register(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
            'terms'=> ['required', 'accepted'],
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $location = $this->getLocation();
        $validated['country_id'] = $location->country_id;
        $validated['state_id'] = $location->state_id;
        $validated['city_id'] = $location->city_id;

        event(new Registered(($user = User::create($validated))));

        Auth::login($user);

        // Send welcome notification
        $user->notify(new WelcomeNotification($user));

        $this->redirect(route('dashboard', absolute: false), navigate: true);
    }
}
