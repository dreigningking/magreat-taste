<?php

namespace App\Livewire\DashboardArea\Meals;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Meal;
use App\Models\Category;
use App\Models\Food;
use Illuminate\Support\Facades\Storage;

class EditMeal extends Component
{
    use WithFileUploads;

    public Meal $meal;
    public $name = '';
    public $excerpt = '';
    public $description = '';
    public $category_id = '';
    public $image;
    public $currentImage;
    public $video = '';
    public $is_active = true;
    public $selectedFoods = [];

    protected $rules = [
        'name' => 'required|string|max:255',
        'excerpt' => 'required|string|max:500',
        'description' => 'required|string',
        'category_id' => 'required|exists:categories,id',
        'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        'video' => 'nullable|url|max:255',
        'is_active' => 'boolean',
        'selectedFoods' => 'required|array|min:1',
        'selectedFoods.*' => 'exists:food,id',
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
        'selectedFoods.required' => 'Please select at least one food.',
        'selectedFoods.min' => 'Please select at least one food.',
        'selectedFoods.*.exists' => 'One or more selected foods are invalid.',
    ];

    public function mount($meal)
    {
        $this->meal = $meal;
        $this->loadMeal();
    }

    public function loadMeal()
    {
        $meal = Meal::with(['category', 'foods'])->findOrFail($this->meal->id);
        
        $this->name = $meal->name;
        $this->excerpt = $meal->excerpt;
        $this->description = $meal->description;
        $this->category_id = $meal->category_id;
        $this->currentImage = $meal->image;
        $this->video = $meal->video;
        $this->is_active = $meal->is_active;
        $this->selectedFoods = $meal->foods->pluck('id')->toArray();
    }

    public function update()
    {
        $this->validate();

        try {
            $meal = Meal::findOrFail($this->meal->id);

            // Handle image upload
            $imagePath = $this->currentImage;
            if ($this->image) {
                // Delete old image if exists
                if ($this->currentImage && Storage::disk('public')->exists($this->currentImage)) {
                    Storage::disk('public')->delete($this->currentImage);
                }
                $imagePath = $this->image->store('meals/images', 'public');
            }

            // Update the meal
            $meal->update([
                'name' => $this->name,
                'excerpt' => $this->excerpt,
                'description' => $this->description,
                'category_id' => $this->category_id,
                'image' => $imagePath,
                'video' => $this->video,
                'is_active' => $this->is_active,
            ]);

            // Sync selected foods to the meal
            $meal->foods()->sync($this->selectedFoods);

            session()->flash('message', 'Meal updated successfully!');
            
            return redirect()->route('meals.index');

        } catch (\Exception $e) {
            session()->flash('error', 'Error updating meal: ' . $e->getMessage());
        }
    }

    public function render()
    {
        $meal = Meal::with(['category', 'foods'])->findOrFail($this->meal->id);
        
        $categories = Category::where('type', 'meal')
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        $foods = Food::with('sizes')
            ->orderBy('name')
            ->get();

        return view('livewire.dashboard-area.meals.edit-meal', [
            'meal' => $meal,
            'categories' => $categories,
            'foods' => $foods,
        ]);
    }
}
