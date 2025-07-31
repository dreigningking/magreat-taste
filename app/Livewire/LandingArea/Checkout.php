<?php

namespace App\Livewire\LandingArea;

use App\Models\Order;
use App\Models\Payment;
use Livewire\Component;
use App\Models\OrderItem;
use Livewire\Attributes\On;
use App\Http\Traits\CartTrait;
use App\Http\Traits\PaymentTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Checkout extends Component
{
    use CartTrait,PaymentTrait;

    public $cartItems;
    public $subtotal = 0;
    public $vatAmount = 0;
    public $total = 0;
    public $vatRate = 0;
    
    // Customer information
    public $customerName = '';
    public $customerEmail = '';
    public $customerPhone = '';
    public $deliveryAddress = '';
    public $deliveryDate = '';
    public $deliveryTime = '';
    public $deliveryType = 'delivery'; // delivery or pickup
    public $specialInstructions = '';
    
    public function mount()
    {
        $this->vatRate = config('services.vat_rate', 0);
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

    public function completePayment()
    {
        // Validate required fields
        $this->validate([
            'customerName' => 'required|string|max:255',
            'customerEmail' => 'required|email|max:255',
            'customerPhone' => 'required|string|max:20',
            'deliveryAddress' => 'required_if:deliveryType,delivery|string|max:500',
            'deliveryDate' => 'required|date|after_or_equal:today',
            'deliveryTime' => 'required',
            'deliveryType' => 'required|in:delivery,pickup',
        ]);
        try {
            DB::beginTransaction();
            // Create order
            $order = Order::create([
                'ip' => request()->ip(),
                'name' => $this->customerName,
                'email' => $this->customerEmail,
                'phone' => $this->customerPhone,
                'address' => $this->deliveryType === 'delivery' ? $this->deliveryAddress : null,
                'delivery_date' => $this->deliveryDate,
                'delivery_time' => $this->deliveryTime,
                'delivery_type' => $this->deliveryType,
                'instructions' => $this->specialInstructions,
                'vat_amount' => $this->vatAmount,
                'total_amount' => $this->total,
                'status' => 'pending',
            ]);
            // Create order items
            foreach ($this->cartItems as $mealGroup) {
                foreach ($mealGroup as $cartItem) {
                    OrderItem::create([
                        'order_id' => $order->id,
                        'meal_id' => $cartItem->meal_id,
                        'food_id' => $cartItem->food_id,
                        'food_size_id' => $cartItem->food_size_id,
                        'quantity' => $cartItem->quantity,
                        'price' => $cartItem->price,
                        'amount' => $cartItem->price * $cartItem->quantity,
                    ]);
                }
            }
            // Create payment record
            $payment = Payment::create([
                'order_id' => $order->id,
                'amount' => $this->total,
                'payment_method' => 'cash_on_delivery',
                'status' => 'pending',
                'reference' => 'ORD-' . $order->id . '-' . time(),
            ]);
            
            // Clear cart
            // $this->clearCart();
            
            DB::commit();
            // session()->flash('success', 'Order placed successfully! Order #' . $order->id);
            
            $link = $this->initializePayment($payment);
            // Redirect to payment gateway
            Log::info('Redirecting to payment gateway: ' . $link);
            return redirect()->to($link);

        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Error placing order: ' . $e->getMessage());
        }
    }

    public function clearCart()
    {
        // Clear all cart items for current IP
        $this->removeFromCartDb(null); // Remove all items
    }

    public function render()
    {
        return view('livewire.landing-area.checkout');
    }
}
