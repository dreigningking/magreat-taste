<?php

namespace App\Livewire\LandingArea\Policies;

use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.landing')]
class PrivacyPolicy extends Component
{
    public $title = 'Privacy Policy | Wonegig';
    public $meta_title = 'Privacy Policy - Wonegig';
    public $meta_description = 'Read Wonegig\'s privacy policy to learn how we protect your data and privacy.';

    public function render()
    {
        return view('livewire.landing-area.policies.privacy-policy');
    }
}
