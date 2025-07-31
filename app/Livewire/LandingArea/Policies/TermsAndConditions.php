<?php

namespace App\Livewire\LandingArea\Policies;

use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.landing')]
class TermsAndConditions extends Component
{
    public function render()
    {
        return view('livewire.landing-area.policies.terms-and-conditions');
    }
}
