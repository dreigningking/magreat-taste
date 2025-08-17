<?php

namespace App\Livewire\DashboardArea\Meals;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\Food;
use App\Models\Size;
use App\Models\Meal;
use Illuminate\Support\Facades\DB;

class ListFood extends Component
{
    use WithPagination, WithFileUploads;

    // Create food properties
    public $name = '';
    public $description = '';
    public $selectedSizes = [];

    // Edit food properties
    public $edit_id = '';
    public $edit_name = '';
    public $edit_description = '';
    public $editSelectedSizes = [];

    public function mount()
    {
        // Initialize with one empty size selection for create form
        $this->selectedSizes = [
            ['size_id' => '', 'price' => '']
        ];
    }

    public function addSize()
    {
        $this->selectedSizes[] = ['size_id' => '', 'price' => ''];
    }

    public function removeSize($index)
    {
        unset($this->selectedSizes[$index]);
        $this->selectedSizes = array_values($this->selectedSizes);
    }

    public function addEditSize()
    {
        $this->editSelectedSizes[] = ['size_id' => '', 'price' => ''];
    }

    public function removeEditSize($index)
    {
        unset($this->editSelectedSizes[$index]);
        $this->editSelectedSizes = array_values($this->editSelectedSizes);
    }

    public function store()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:500',
            'selectedSizes.*.size_id' => 'required|exists:sizes,id',
            'selectedSizes.*.price' => 'required|numeric|min:0',
        ]);

        // Create the food
        $food = Food::create([
            'name' => $this->name,
            'description' => $this->description,
        ]);

        // Attach sizes with prices
        foreach ($this->selectedSizes as $sizeData) {
            $food->sizes()->attach($sizeData['size_id'], [
                'price' => $sizeData['price']
            ]);
        }

        // Reset form
        $this->reset(['name', 'description']);
        $this->selectedSizes = [['size_id' => '', 'price' => '']];
        $this->dispatch('closeModal', ['modalId' => 'createFoodModal']);
        session()->flash('message', 'Food created successfully!');
    }

    public function editFood($id)
    {
        $food = Food::with('sizes')->find($id);
        
        if ($food) {
            $this->edit_id = $food->id;
            $this->edit_name = $food->name;
            $this->edit_description = $food->description;
            
            // Populate edit sizes with existing data
            $this->editSelectedSizes = [];
            foreach ($food->sizes as $size) {
                $this->editSelectedSizes[] = [
                    'size_id' => $size->id,
                    'price' => $size->pivot->price
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
            'editSelectedSizes.*.size_id' => 'required|exists:sizes,id',
            'editSelectedSizes.*.price' => 'required|numeric|min:0',
        ]);

        $food = Food::find($this->edit_id);
        $food->update([
            'name' => $this->edit_name,
            'description' => $this->edit_description,
        ]);

        // Sync sizes with prices
        $sizeData = [];
        foreach ($this->editSelectedSizes as $sizeItem) {
            $sizeData[$sizeItem['size_id']] = ['price' => $sizeItem['price']];
        }
        
        $food->sizes()->sync($sizeData);

        // Reset edit form
        $this->reset(['edit_id', 'edit_name', 'edit_description', 'editSelectedSizes']);
        $this->dispatch('closeModal', ['modalId' => 'editFoodModal']);
        session()->flash('message', 'Food updated successfully!');
    }

    public function delete($id)
    {
        $food = Food::find($id);
        if ($food) {
            // Detach associated sizes first
            $food->sizes()->detach();
            $food->delete();
            session()->flash('message', 'Food deleted successfully!');
        }
    }

    public function render()
    {
        $foods = Food::with(['sizes', 'meals'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Get all available sizes for dropdowns
        $availableSizes = Size::orderBy('name')->get();

        // Calculate statistics
        $totalFoods = Food::count();
        $totalSizes = Size::count();
        $totalMeals = Meal::count();
        
        // Calculate average price from food_sizes pivot table
        $averagePrice = DB::table('food_sizes')->avg('price') ?? 0;

        return view('livewire.dashboard-area.meals.list-food', [
            'foods' => $foods,
            'availableSizes' => $availableSizes,
            'totalFoods' => $totalFoods,
            'totalSizes' => $totalSizes,
            'totalMeals' => $totalMeals,
            'averagePrice' => $averagePrice,
        ]);
    }
}
