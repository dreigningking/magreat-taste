<?php

namespace App\Livewire\LandingArea;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Http\Traits\CartTrait;

class CartCanvas extends Component
{
    use CartTrait;
    public $cartItems;
    public $subtotal = 0;
    public $vatAmount = 0;
    public $total = 0;
    public $vatRate = 0;

    public function mount()
    {
        $this->vatRate = config('services.settings.vat_rate', 0);
        $this->getCartItems();
    }

    #[On('updateCart')]
    public function getCartItems()
    {
        $cartItems = $this->getCartDb();
        $this->cartItems = collect($cartItems)->groupBy('meal_id');
        $this->calculateCartTotals();
    }

    public function calculateCartTotals()
    {
        $this->subtotal = 0;
        
        if ($this->cartItems && $this->cartItems->count() > 0) {
            foreach ($this->cartItems as $mealGroup) {
                foreach ($mealGroup as $item) {
                    $this->subtotal += $item->price * $item->quantity;
                }
            }
        }
        
        // Calculate VAT
        $this->vatAmount = $this->subtotal * ($this->vatRate / 100);
        
        // Calculate total
        $this->total = $this->subtotal + $this->vatAmount;
    }

    public function isCartEmpty()
    {
        return !$this->cartItems || $this->cartItems->count() === 0;
    }

    public function getTotalCartItemsCount()
    {
        if (!$this->cartItems || $this->cartItems->count() === 0) {
            return 0;
        }
        
        $totalCount = 0;
        foreach ($this->cartItems as $mealGroup) {
            foreach ($mealGroup as $item) {
                $totalCount += $item->quantity;
            }
        }
        
        return $totalCount;
    }

    public function removeFromCart($meal_id)
    {
        // Use the trait method to remove items
        $this->removeFromCartDb($meal_id);
        
        // Refresh cart items and recalculate totals
        $this->getCartItems();
    }

    public function render()
    {
        return view('livewire.landing-area.cart-canvas');
    }
}
