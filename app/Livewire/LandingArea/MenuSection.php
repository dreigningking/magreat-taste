<?php

namespace App\Livewire\LandingArea;

use App\Models\Meal;
use Livewire\Component;
use App\Models\Category;
use App\Http\Traits\CartTrait;

class MenuSection extends Component
{
    use CartTrait;
    public $categories;
    public $meals;
    public $selectedMeal = null;
    public $selectedMealFoods = [];
    public $selectedMealSizes = [];
    public $modalTitle = '';
    public $modalMealName = '';
    public $modalMealDescription = '';
    public $modalMealFullDescription = '';
    public $modalMealCategory = '';
    public $modalMealImage = '';
    public $modalVideoUrl = '';
    public $modalFoodItems = [];
    public $modalTotal = 0;
    public $selectedSizes = [];
    public $selectedQuantities = [];
    
    public function mount()
    {
        $this->loadCategories();
        $this->loadMeals();
    }
    
    public function loadCategories()
    {
        $this->categories = Category::where('type', 'meal')->orderBy('name')->get();
    }
    
    public function loadMeals()
    {
        $this->meals = Meal::with(['foods.sizes', 'category'])
            ->where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->get();
    }
    
    public function selectMeal($mealId)
    {
        $this->selectedMeal = Meal::with(['foods.sizes', 'category'])->find($mealId);
        
        if ($this->selectedMeal) {
            $this->selectedMealFoods = $this->selectedMeal->foods;
            $this->selectedMealSizes = [];
            $this->selectedSizes = [];
            $this->selectedQuantities = [];
            
            // Initialize sizes for each food - select the lowest price size
            foreach ($this->selectedMealFoods as $food) {
                if ($food->sizes->count() > 0) {
                    // Find the size with the lowest price
                    $lowestPriceSize = $food->sizes->sortBy('price')->first();
                    $this->selectedMealSizes[$food->id] = $lowestPriceSize->id;
                    $this->selectedSizes[$food->id] = $lowestPriceSize->id;
                    $this->selectedQuantities[$food->id] = 1;
                }
            }
            
            $this->modalTitle = $this->selectedMeal->name;
            $this->modalMealName = $this->selectedMeal->name;
            $this->modalMealDescription = $this->selectedMeal->description;
            $this->modalMealFullDescription = $this->selectedMeal->description;
            $this->modalMealCategory = $this->selectedMeal->category ? $this->selectedMeal->category->name : 'Uncategorized';
            $this->modalMealImage = $this->selectedMeal->image_url;
            $this->modalVideoUrl = $this->selectedMeal->video_url;
            $this->modalFoodItems = $this->selectedMealFoods->map(function($food) {
                return [
                    'id' => $food->id,
                    'name' => $food->name,
                    'description' => $food->description,
                    'sizes' => $food->sizes->map(function($size) {
                        return [
                            'id' => $size->id,
                            'name' => $size->name,
                            'price' => $size->price,
                            'image' => $size->image_url,
                            'description' => $size->description
                        ];
                    })->toArray()
                ];
            })->toArray();
            
            // Calculate initial total
            $this->calculateModalTotal();
        }
    }
    
    public function updateFoodSize($foodId, $sizeId)
    {
        $this->selectedMealSizes[$foodId] = $sizeId;
        $this->selectedSizes[$foodId] = $sizeId;
        $this->calculateModalTotal();
    }
    
    public function updateFoodQuantity($foodId, $quantity)
    {
        $this->selectedQuantities[$foodId] = (int) $quantity;
        $this->calculateModalTotal();
    }
    
    public function calculateModalTotal()
    {
        $total = 0;
        
        foreach ($this->selectedMealFoods as $food) {
            if (isset($this->selectedSizes[$food->id]) && isset($this->selectedQuantities[$food->id])) {
                $selectedSizeId = $this->selectedSizes[$food->id];
                $quantity = $this->selectedQuantities[$food->id];
                
                // Find the selected size and get its price
                $selectedSize = $food->sizes->where('id', $selectedSizeId)->first();
                if ($selectedSize) {
                    $total += $selectedSize->price * $quantity;
                }
            }
        }
        
        $this->modalTotal = $total;
    }
    
    
    public function addToCart()
    {

        $cartItems = [];

        foreach ($this->selectedMealFoods as $food) {
            if (isset($this->selectedSizes[$food->id]) && isset($this->selectedQuantities[$food->id])) {
                $selectedSizeId = $this->selectedSizes[$food->id];
                $quantity = $this->selectedQuantities[$food->id];

                // Find the selected size and get its price
                $selectedSize = $food->sizes->where('id', $selectedSizeId)->first();
                $price = $selectedSize ? $selectedSize->price : 0;

                // Add to cart using the trait method
                $this->addToCartDb(
                    $this->selectedMeal->id,
                    $food->id,
                    $selectedSizeId,
                    $price,
                    $quantity
                );
                $this->dispatch('updateCart');
            }
        }

        if (empty($this->selectedMealFoods) || empty($this->selectedSizes) || empty($this->selectedQuantities)) {
            session()->flash('error', 'No items selected');
            return;
        }

        session()->flash('success', 'Items added to cart successfully!');

        // Close the modal
        $this->dispatch('closeModal', ['modalId' => 'mealModal']);
    }

    public function render()
    {
        return view('livewire.landing-area.menu-section');
    }
}
