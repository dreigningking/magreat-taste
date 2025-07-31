<?php

namespace App\Livewire\LandingArea;

use App\Models\Meal;
use App\Models\Category;
use Livewire\Component;
use App\Models\Platform;
use Livewire\Attributes\Layout;
use App\Http\Traits\HelperTrait;
use Illuminate\Support\Facades\Auth;
use App\Http\Traits\GeoLocationTrait;

#[Layout('components.layouts.landing')]
class Welcome extends Component
{
    use HelperTrait,GeoLocationTrait;
    
    public $meals;
    public $categories;
    


    public function mount()
    {
        
        $this->meals = Meal::latest()->get();
        $this->categories = Category::where('is_active', true)->get();
    }

    public function render()
    {
       return view('livewire.landing-area.welcome');
    }
}
