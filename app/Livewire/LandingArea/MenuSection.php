<?php

namespace App\Livewire\LandingArea;

use Livewire\Component;
use App\Models\Meal;
use App\Models\Food;
use App\Models\Size;
use App\Models\Category;
use App\Http\Traits\CartTrait;
use Illuminate\Support\Facades\Log;

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
        Log::info('MenuSection component mounted');
        $this->loadCategories();
        $this->loadMeals();
        Log::info('MenuSection component initialization completed');
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
            
            // Load existing cart selections if any
            $this->loadExistingCartSelections();
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
        // Debug: Log the method call
        Log::info('updateFoodSizeQuantity called', [
            'foodId' => $foodId,
            'sizeId' => $sizeId,
            'quantity' => $quantity,
            'selectedSizes' => $this->selectedSizes
        ]);
        
        $quantity = max(0, (int) $quantity);
        
        if ($quantity > 0) {
            $this->selectedSizes[$foodId][$sizeId] = $quantity;
        } else {
            // Check if this is the last size being removed for this food
            $currentSizes = $this->selectedSizes[$foodId] ?? [];
            $hasOtherSizes = false;
            
            foreach ($currentSizes as $sid => $qty) {
                if ($sid != $sizeId && $qty > 0) {
                    $hasOtherSizes = true;
                    break;
                }
            }
            
            if ($hasOtherSizes) {
                unset($this->selectedSizes[$foodId][$sizeId]);
            } else {
                // Don't allow removing the last size - set minimum quantity of 1
                $this->selectedSizes[$foodId][$sizeId] = 1;
                return;
            }
        }
        
        // Debug: Log the updated selectedSizes
        Log::info('updateFoodSizeQuantity completed', [
            'updated_selectedSizes' => $this->selectedSizes
        ]);
        
        $this->calculateModalTotal();
        $this->updateModalFoodItems();
    }

    public function removeFoodSize($foodId, $sizeId)
    {
        // Check if this is the last size being removed for this food
        $currentSizes = $this->selectedSizes[$foodId] ?? [];
        $hasOtherSizes = false;
        
        foreach ($currentSizes as $sid => $qty) {
            if ($sid != $sizeId && $qty > 0) {
                $hasOtherSizes = true;
                break;
            }
        }
        
        if ($hasOtherSizes) {
            unset($this->selectedSizes[$foodId][$sizeId]);
        } else {
            // Don't allow removing the last size - set minimum quantity of 1
            $this->selectedSizes[$foodId][$sizeId] = 1;
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
        
        if (!$this->selectedMealFoods) {
            return $total;
        }
        
        if (isset($this->selectedSizes[$foodId])) {
            foreach ($this->selectedSizes[$foodId] as $sizeId => $quantity) {
                $food = $this->selectedMealFoods->where('id', $foodId)->first();
                if ($food && $food->sizes) {
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
        if (!$this->selectedMealFoods) {
            $this->modalFoodItems = [];
            return;
        }
        
        $this->modalFoodItems = $this->selectedMealFoods->map(function($food) {
            $sizes = $food->sizes->map(function($size) {
                return [
                    'id' => $size->id,
                    'name' => $size->name,
                    'price' => $size->pivot->price ?? 0,
                    'image' => $size->image_url,
                    'description' => $size->description
                ];
            })->toArray();
            
            // Calculate lowest price and best value size
            $lowestPrice = collect($sizes)->min('price') ?? 0;
            $bestValueSize = collect($sizes)->firstWhere('price', $lowestPrice);
            
            return [
                'id' => $food->id,
                'name' => $food->name,
                'description' => $food->description,
                'total_price' => $this->calculateFoodTotal($food->id),
                'lowest_price' => $lowestPrice,
                'best_value_size_id' => $bestValueSize['id'] ?? null,
                'sizes' => $sizes
            ];
        })->toArray();
    }
    
    public function calculateModalTotal()
    {
        $total = 0;
        
        if ($this->selectedMealFoods) {
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
        }
        
        $this->modalTotal = $total;
    }
    
    public function isBestValueSize($foodId, $sizeId)
    {
        $food = collect($this->modalFoodItems)->firstWhere('id', $foodId);
        return $food && $food['best_value_size_id'] == $sizeId;
    }
    
    public function canRemoveSize($foodId, $sizeId)
    {
        $currentSizes = $this->selectedSizes[$foodId] ?? [];
        $hasOtherSizes = false;
        
        foreach ($currentSizes as $sid => $qty) {
            if ($sid != $sizeId && $qty > 0) {
                $hasOtherSizes = true;
                break;
            }
        }
        
        return $hasOtherSizes;
    }
    
    public function getSizeQuantity($foodId, $sizeId)
    {
        return $this->selectedSizes[$foodId][$sizeId] ?? 0;
    }
    
    public function getSizeImageUrl($size)
    {
        return $size['image'] ?? 'https://images.unsplash.com/photo-1513104890138-7c749659a591?ixlib=rb-4.0.3&auto=format&fit=crop&w=200&q=80';
    }
    
    public function formatPrice($price)
    {
        return '₦' . number_format($price, 2);
    }
    
    
    public function addToCart()
    {
        Log::info('addToCart method called');
        
        // Validate that all foods have at least one size selected
        if (empty($this->selectedSizes)) {
            Log::info('addToCart: No sizes selected');
            session()->flash('error', 'Please select at least one size for each food item.');
            return;
        }

        // Check if each food has at least one size with quantity > 0
        foreach ($this->selectedSizes as $foodId => $sizes) {
            $hasValidSize = false;
            foreach ($sizes as $sizeId => $quantity) {
                if ($quantity > 0) {
                    $hasValidSize = true;
                    break;
                }
            }
            
            if (!$hasValidSize) {
                Log::info('addToCart: Food has no valid sizes', ['foodId' => $foodId]);
                session()->flash('error', 'Please select at least one size for each food item.');
                return;
            }
        }

        Log::info('addToCart: Validation passed, processing cart update');

        // Get the selected meal
        $meal = $this->selectedMeal;
        
        if (!$meal) {
            Log::info('addToCart: No meal selected');
            session()->flash('error', 'Meal not found.');
            return;
        }

        Log::info('addToCart: Processing meal', ['mealId' => $meal->id]);

        // Remove existing cart items for this meal first (to avoid duplicates)
        try {
            Log::info('addToCart: Removing existing cart items');
            $this->removeFromCartDb($meal->id);
            Log::info('addToCart: Existing cart items removed successfully');
        } catch (\Exception $e) {
            // Log the error but continue
            Log::warning('addToCart: Could not remove existing items', ['error' => $e->getMessage()]);
            session()->flash('warning', 'Could not remove existing items: ' . $e->getMessage());
        }

        // Add each selected size to cart
        $itemsAdded = 0;
        foreach ($this->selectedSizes as $foodId => $sizes) {
            foreach ($sizes as $sizeId => $quantity) {
                if ($quantity > 0) {
                    // Get food and size details
                    $food = Food::find($foodId);
                    $size = Size::find($sizeId);
                    
                    if ($food && $size) {
                        // Get price from pivot table
                        $price = $food->sizes()->where('size_id', $sizeId)->first()->pivot->price ?? 0;
                        
                        Log::info('addToCart: Adding item to cart', [
                            'mealId' => $meal->id,
                            'foodId' => $foodId,
                            'sizeId' => $sizeId,
                            'price' => $price,
                            'quantity' => $quantity
                        ]);
                        
                        // Add to cart using the trait method
                        try {
                            $this->addToCartDb(
                                $meal->id,
                                $food->id,
                                $sizeId,
                                $price,
                                $quantity
                            );
                            $itemsAdded++;
                            Log::info('addToCart: Item added successfully', ['itemsAdded' => $itemsAdded]);
                        } catch (\Exception $e) {
                            Log::error('addToCart: Error adding item to cart', ['error' => $e->getMessage()]);
                            session()->flash('error', 'Error adding item to cart: ' . $e->getMessage());
                        }
                    }
                }
            }
        }

        Log::info('addToCart: Cart update completed', ['itemsAdded' => $itemsAdded]);

        // Reset selections
        $this->selectedSizes = [];
        $this->selectedMeal = null;
        $this->modalTotal = 0;
        $this->modalFoodItems = [];
        
        Log::info('addToCart: Dispatching closeModal and updateCart');
        
        // Close modal and update cart
        $this->dispatch('closeModal', ['modalId' => 'mealModal']);
        $this->dispatch('updateCart');
        
        Log::info('addToCart: Method completed successfully');
        
        session()->flash('success', 'Cart updated successfully!');
    }

    public function loadExistingCartSelections()
    {
        if (!$this->selectedMeal) {
            return;
        }

        // Get existing cart items for this meal
        $existingCartItems = $this->getCartDb();
        $mealCartItems = collect($existingCartItems)->where('meal_id', $this->selectedMeal->id);

        if ($mealCartItems->count() > 0) {
            // Reset current selections
            $this->selectedSizes = [];
            
            // Populate selections from cart
            foreach ($mealCartItems as $cartItem) {
                $foodId = $cartItem->food_id;
                $sizeId = $cartItem->food_size_id;
                $quantity = $cartItem->quantity;
                
                if (!isset($this->selectedSizes[$foodId])) {
                    $this->selectedSizes[$foodId] = [];
                }
                
                $this->selectedSizes[$foodId][$sizeId] = $quantity;
            }
            
            // Update modal food items and total
            $this->updateModalFoodItems();
            $this->calculateModalTotal();
        }
    }

    public function testMethod()
    {
        Log::info('testMethod called successfully');
        return 'test method working';
    }

    public function render()
    {
        return view('livewire.landing-area.menu-section');
    }
}
