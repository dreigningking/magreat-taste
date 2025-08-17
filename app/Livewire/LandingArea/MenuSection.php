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
            
            // Initialize sizes for each food - select the lowest price size with quantity 1
            foreach ($this->selectedMealFoods as $food) {
                if ($food->sizes->count() > 0) {
                    // Find the size with the lowest price from pivot
                    $lowestPriceSize = $food->sizes->sortBy('pivot.price')->first();
                    $this->selectedMealSizes[$food->id] = $lowestPriceSize->id;
                    $this->selectedSizes[$food->id] = [$lowestPriceSize->id => 1];
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
            
            // Update modal food items with the new structure
            $this->updateModalFoodItems();
            
            // Calculate initial total
            $this->calculateModalTotal();
        }
    }
    
    public function updateFoodSize($foodId, $sizeId)
    {
        $this->selectedMealSizes[$foodId] = $sizeId;
        $this->selectedSizes[$foodId] = [$sizeId => 1];
        $this->calculateModalTotal();
    }
    
    public function updateFoodSizeQuantity($foodId, $sizeId, $quantity)
    {
        $quantity = max(0, (int) $quantity);
        
        if ($quantity > 0) {
            $this->selectedSizes[$foodId][$sizeId] = $quantity;
        } else {
            unset($this->selectedSizes[$foodId][$sizeId]);
        }
        
        $this->calculateModalTotal();
        $this->updateModalFoodItems();
    }
    
    public function updateFoodQuantity($foodId, $quantity)
    {
        $this->selectedQuantities[$foodId] = (int) $quantity;
        $this->calculateModalTotal();
    }
    
    public function calculateFoodTotal($foodId)
    {
        $total = 0;
        
        if (isset($this->selectedSizes[$foodId])) {
            foreach ($this->selectedSizes[$foodId] as $sizeId => $quantity) {
                $food = $this->selectedMealFoods->where('id', $foodId)->first();
                if ($food) {
                    $size = $food->sizes->where('id', $sizeId)->first();
                    if ($size && isset($size->pivot->price)) {
                        $total += $size->pivot->price * $quantity;
                    }
                }
            }
        }
        
        return $total;
    }
    
    public function updateModalFoodItems()
    {
        $this->modalFoodItems = $this->selectedMealFoods->map(function($food) {
            return [
                'id' => $food->id,
                'name' => $food->name,
                'description' => $food->description,
                'total_price' => $this->calculateFoodTotal($food->id),
                'sizes' => $food->sizes->map(function($size) {
                    return [
                        'id' => $size->id,
                        'name' => $size->name,
                        'price' => $size->pivot->price ?? 0,
                        'image' => $size->image_url,
                        'description' => $size->description
                    ];
                })->toArray()
            ];
        })->toArray();
    }
    
    public function calculateModalTotal()
    {
        $total = 0;
        
        foreach ($this->selectedMealFoods as $food) {
            if (isset($this->selectedSizes[$food->id])) {
                foreach ($this->selectedSizes[$food->id] as $sizeId => $quantity) {
                    // Find the selected size and get its price from pivot
                    $selectedSize = $food->sizes->where('id', $sizeId)->first();
                    if ($selectedSize && isset($selectedSize->pivot->price)) {
                        $total += $selectedSize->pivot->price * $quantity;
                    }
                }
            }
        }
        
        $this->modalTotal = $total;
    }
    
    
    public function addToCart()
    {
        $cartItems = [];

        foreach ($this->selectedMealFoods as $food) {
            if (isset($this->selectedSizes[$food->id])) {
                foreach ($this->selectedSizes[$food->id] as $sizeId => $quantity) {
                    if ($quantity > 0) {
                        // Find the selected size and get its price from pivot
                        $selectedSize = $food->sizes->where('id', $sizeId)->first();
                        $price = $selectedSize && isset($selectedSize->pivot->price) ? $selectedSize->pivot->price : 0;

                        // Add to cart using the trait method
                        $this->addToCartDb(
                            $this->selectedMeal->id,
                            $food->id,
                            $sizeId,
                            $price,
                            $quantity
                        );
                    }
                }
            }
        }

        if (empty($this->selectedSizes)) {
            session()->flash('error', 'No items selected');
            return;
        }

        $this->dispatch('updateCart');
        session()->flash('success', 'Items added to cart successfully!');

        // Close the modal
        $this->dispatch('closeModal', ['modalId' => 'mealModal']);
    }

    public function render()
    {
        return view('livewire.landing-area.menu-section');
    }
}
