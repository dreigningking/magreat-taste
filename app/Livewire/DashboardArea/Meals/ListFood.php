<?php

namespace App\Livewire\DashboardArea\Meals;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\Food;
use App\Models\FoodSize;
use App\Models\Meal;

class ListFood extends Component
{
    use WithPagination, WithFileUploads;

    // Create food properties
    public $name = '';
    public $description = '';
    public $sizes = [];

    // Edit food properties
    public $edit_id = '';
    public $edit_name = '';
    public $edit_description = '';
    public $editSizes = [];

    public function mount()
    {
        // Initialize with one empty size for create form
        $this->sizes = [
            ['name' => '', 'price' => '', 'image' => null]
        ];
    }

    public function addSize()
    {
        $this->sizes[] = ['name' => '', 'price' => '', 'image' => null];
    }

    public function removeSize($index)
    {
        unset($this->sizes[$index]);
        $this->sizes = array_values($this->sizes);
    }

    public function addEditSize()
    {
        $this->editSizes[] = ['name' => '', 'price' => '', 'image' => null];
    }

    public function removeEditSize($index)
    {
        unset($this->editSizes[$index]);
        $this->editSizes = array_values($this->editSizes);
    }

    public function store()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:500',
            'sizes.*.name' => 'required|string|max:255',
            'sizes.*.price' => 'required|numeric|min:0',
            'sizes.*.image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Create the food
        $food = Food::create([
            'name' => $this->name,
            'description' => $this->description,
        ]);

        // Create the sizes
        foreach ($this->sizes as $sizeData) {
            $imagePath = null;
            if ($sizeData['image']) {
                $imagePath = $sizeData['image']->store('foods', 'public');
            }

            FoodSize::create([
                'food_id' => $food->id,
                'name' => $sizeData['name'],
                'price' => $sizeData['price'],
                'image' => $imagePath,
            ]);
        }

        // Reset form
        $this->reset(['name', 'description']);
        $this->sizes = [['name' => '', 'price' => '', 'image' => null]];
        $this->dispatch('closeModal', ['modalId' => 'createFoodModal']);
        session()->flash('message', 'Food created successfully!');
    }

    public function editFood($id, $name, $description)
    {
        $food = Food::with('sizes')->find($id);
        
        if ($food) {
            $this->edit_id = $food->id;
            $this->edit_name = $food->name;
            $this->edit_description = $food->description;
            
            // Populate edit sizes
            $this->editSizes = [];
            foreach ($food->sizes as $size) {
                $this->editSizes[] = [
                    'id' => $size->id,
                    'name' => $size->name,
                    'price' => $size->price,
                    'image' => null,
                    'existing_image' => $size->image
                ];
            }
        }
    }

    public function update()
    {
        $this->validate([
            'edit_id' => 'required|exists:food,id',
            'edit_name' => 'required|string|max:255',
            'edit_description' => 'required|string|max:500',
            'editSizes.*.name' => 'required|string|max:255',
            'editSizes.*.price' => 'required|numeric|min:0',
            'editSizes.*.image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $food = Food::find($this->edit_id);
        $food->update([
            'name' => $this->edit_name,
            'description' => $this->edit_description,
        ]);

        // Update or create sizes
        foreach ($this->editSizes as $sizeData) {
            $imagePath = $sizeData['existing_image'] ?? null;
            
            if (isset($sizeData['image']) && $sizeData['image']) {
                $imagePath = $sizeData['image']->store('foods', 'public');
            }

            if (isset($sizeData['id'])) {
                // Update existing size
                FoodSize::where('id', $sizeData['id'])->update([
                    'name' => $sizeData['name'],
                    'price' => $sizeData['price'],
                    'image' => $imagePath,
                ]);
            } else {
                // Create new size
                FoodSize::create([
                    'food_id' => $food->id,
                    'name' => $sizeData['name'],
                    'price' => $sizeData['price'],
                    'image' => $imagePath,
                ]);
            }
        }

        // Reset edit form
        $this->reset(['edit_id', 'edit_name', 'edit_description', 'editSizes']);
        $this->dispatch('closeModal', ['modalId' => 'editFoodModal']);
        session()->flash('message', 'Food updated successfully!');
    }

    public function delete($id)
    {
        $food = Food::find($id);
        if ($food) {
            // Delete associated sizes first
            $food->sizes()->delete();
            $food->delete();
            $this->dispatch('closeModal', ['modalId' => 'deleteFoodModal']);
            session()->flash('message', 'Food deleted successfully!');
        }
    }

    public function render()
    {
        $foods = Food::with(['sizes', 'meals'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Calculate statistics
        $totalFoods = Food::count();
        $totalSizes = FoodSize::count();
        $totalMeals = Meal::count();
        $averagePrice = FoodSize::avg('price') ?? 0;

        return view('livewire.dashboard-area.meals.list-food', [
            'foods' => $foods,
            'totalFoods' => $totalFoods,
            'totalSizes' => $totalSizes,
            'totalMeals' => $totalMeals,
            'averagePrice' => $averagePrice,
        ]);
    }
}
