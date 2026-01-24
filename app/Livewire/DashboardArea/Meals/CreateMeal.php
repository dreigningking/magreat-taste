<?php

namespace App\Livewire\DashboardArea\Meals;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Meal;
use App\Models\Category;
use App\Models\Food;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class CreateMeal extends Component
{
    use WithFileUploads;

    // Meal form properties
    public $name = '';
    public $excerpt = '';
    public $description = '';
    public $category_id = '';
    public $image;
    public $video = '';
    public $is_active = true;
    public $primaryFood = '';
    public $additionalFoods = [];

    protected $rules = [
        'name' => 'required|string|max:255',
        'excerpt' => 'required|string|max:500',
        'description' => 'required|string',
        'category_id' => 'required|exists:categories,id',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'video' => 'nullable|url|max:255',
        'is_active' => 'boolean',
        'primaryFood' => 'required|exists:food,id',
        'additionalFoods' => 'nullable|array',
        'additionalFoods.*' => 'exists:food,id',
    ];

    protected $messages = [
        'name.required' => 'Meal name is required.',
        'excerpt.required' => 'Meal excerpt is required.',
        'description.required' => 'Meal description is required.',
        'category_id.required' => 'Please select a category.',
        'category_id.exists' => 'Selected category is invalid.',
        'image.image' => 'The file must be an image.',
        'image.mimes' => 'The image must be a file of type: jpeg, png, jpg, gif.',
        'image.max' => 'The image may not be greater than 2MB.',
        'video.url' => 'Please enter a valid video URL.',
        'primaryFood.required' => 'Please select a primary food.',
        'primaryFood.exists' => 'Selected primary food is invalid.',
        'additionalFoods.*.exists' => 'One or more selected additional foods are invalid.',
    ];

    public function mount()
    {
        // Set default values
        $this->is_active = true;
    }

    public function store()
    {
        $this->validate();

        try {
            // Handle image upload
            $imagePath = null;
            if ($this->image) {
                $imagePath = $this->image->store('meals/images', 'public');
            }

            // Create the meal
            $meal = Meal::create([
                'name' => $this->name,
                'excerpt' => $this->excerpt,
                'description' => $this->description,
                'category_id' => $this->category_id,
                'image' => $imagePath,
                'video' => $this->video,
                'is_active' => $this->is_active,
            ]);

            // Attach primary food with required=true
            $foodData = [];
            if ($this->primaryFood) {
                $foodData[$this->primaryFood] = ['required' => true];
            }

            // Attach additional foods with required=false
            if (!empty($this->additionalFoods)) {
                foreach ($this->additionalFoods as $foodId) {
                    if ($foodId != $this->primaryFood) { // Avoid duplicate if primary is also in additional
                        $foodData[$foodId] = ['required' => false];
                    }
                }
            }

            // Attach all foods to the meal
            if (!empty($foodData)) {
                $meal->foods()->attach($foodData);
            }

            // Reset form
            $this->reset([
                'name', 'excerpt', 'description', 'category_id',
                'image', 'video', 'is_active', 'primaryFood', 'additionalFoods'
            ]);

            session()->flash('message', 'Meal created successfully!');
            
            return redirect()->route('meals.index');

        } catch (\Exception $e) {
            session()->flash('error', 'Error creating meal: ' . $e->getMessage());
        }
    }

    public function render()
    {
        $categories = Category::where('type', 'meal')
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        $foods = Food::with('sizes')
            ->orderBy('name')
            ->get();

        return view('livewire.dashboard-area.meals.create-meal', [
            'categories' => $categories,
            'foods' => $foods,
        ]);
    }
}
