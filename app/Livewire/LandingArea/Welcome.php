<?php

namespace App\Livewire\LandingArea;


use App\Models\Meal;

use Livewire\Component;
use App\Models\Category;

use Livewire\Attributes\Layout;
use App\Http\Traits\HelperTrait;

#[Layout('components.layouts.landing')]
class Welcome extends Component
{
    use HelperTrait;
    
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
