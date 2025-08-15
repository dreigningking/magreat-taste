<?php

namespace App\Livewire\DashboardArea\Orders;

use Livewire\Component;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Meal;
use App\Models\Food;
use App\Models\FoodSize;
use Illuminate\Support\Facades\DB;

class CreateOrder extends Component
{
    // Customer Information
    public $name = '';
    public $email = '';
    public $phone = '';
    public $delivery_type = 'pickup';
    public $address = '';
    public $state = '';
    public $city = '';
    public $delivery_date = '';
    public $delivery_time = '';
    public $instructions = '';

    // Order Items
    public $orderItems = [];
    public $selectedMeal = '';

    // Payment Information
    public $payment_status = 'pending';
    public $payment_method = 'online';
    public $order_status = 'pending';
    public $vat_rate = 0;
    public $vat_amount = 0;
    public $shipment_fee = 0;
    public $subtotal = 0;
    public $total = 0;

    protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|email|max:255',
        'phone' => 'required|string|max:20',
        'delivery_type' => 'required|in:pickup,delivery',
        'address' => 'nullable|string',
        'state' => 'nullable|string|max:100',
        'city' => 'nullable|string|max:100',
        'delivery_date' => 'nullable|date',
        'delivery_time' => 'nullable',
        'instructions' => 'nullable|string',
        'orderItems' => 'required|array|min:1',
        'orderItems.*.meal_id' => 'required|exists:meals,id',
        'orderItems.*.food_id' => 'required|exists:food,id',
        'orderItems.*.food_size_id' => 'required|exists:food_sizes,id',
        'orderItems.*.quantity' => 'required|integer|min:1',
        'orderItems.*.price' => 'required|numeric|min:0',
        'orderItems.*.amount' => 'required|numeric|min:0',
        'payment_status' => 'required|in:success,pending,failed',
        'payment_method' => 'required|in:online,cash,card',
        'order_status' => 'required|in:pending,processing,completed,cancelled',
        'vat_rate' => 'required|numeric|min:0',
        'vat_amount' => 'required|numeric|min:0',
        'shipment_fee' => 'required|numeric|min:0',
    ];

    public function mount()
    {
        $this->orderItems = [];
        $this->delivery_date = now()->format('Y-m-d');
        $this->delivery_time = now()->format('H:i');
        
        // Initialize VAT rate from config
        $this->vat_rate = config('services.settings.vat_rate', 0);
    }

    public function addMeal($mealId)
    {
        if (!$mealId) {
            session()->flash('meal_error', 'Please select a meal first.');
            return;
        }

        $meal = Meal::with('foods.sizes')->find($mealId);
        
        if (!$meal) {
            session()->flash('meal_error', 'Meal not found.');
            return;
        }

        foreach ($meal->foods as $food) {
            // Get the first available size (default)
            $firstSize = $food->sizes->first();         
            if ($firstSize) {
                $this->orderItems[] = [
                    'meal_id' => $meal->id,
                    'food_id' => $food->id,
                    'food_size_id' => $firstSize->id,
                    'quantity' => 1, // Default quantity
                    'price' => $firstSize->price,
                    'amount' => $firstSize->price * 1,
                ];
            }
        }

        $this->calculateTotals();
        session()->flash('meal_success', 'Meal added to order successfully!');
    }

    public function removeOrderItem($index)
    {
        unset($this->orderItems[$index]);
        $this->orderItems = array_values($this->orderItems);
        $this->calculateTotals();
    }

    public function removeMealItems($mealId)
    {
        // Remove all items from the specified meal
        $this->orderItems = array_filter($this->orderItems, function($item) use ($mealId) {
            return $item['meal_id'] != $mealId;
        });
        $this->orderItems = array_values($this->orderItems);
        $this->calculateTotals();
        session()->flash('message', 'All items from the selected meal have been removed!');
    }

    public function updateOrderItemSize($index, $sizeId)
    {
        if (isset($this->orderItems[$index])) {
            $size = FoodSize::find($sizeId);
            if ($size) {
                $this->orderItems[$index]['food_size_id'] = $sizeId;
                $this->orderItems[$index]['price'] = $size->price;
                $this->orderItems[$index]['amount'] = $size->price * $this->orderItems[$index]['quantity'];
                $this->calculateTotals();
            }
        }
    }

    public function updateOrderItemQuantity($index, $quantity)
    {
        if (isset($this->orderItems[$index]) && $quantity > 0) {
            $this->orderItems[$index]['quantity'] = $quantity;
            $this->orderItems[$index]['amount'] = $this->orderItems[$index]['price'] * $quantity;
            $this->calculateTotals();
        }
    }

    public function calculateTotals()
    {
        $this->subtotal = collect($this->orderItems)->sum('amount');
        
        // Calculate VAT amount based on rate
        if ($this->vat_rate > 0) {
            $this->vat_amount = ($this->subtotal * $this->vat_rate) / 100;
        } else {
            $this->vat_amount = 0;
        }
        
        // Calculate total including VAT and shipment fee
        $this->total = $this->subtotal + $this->vat_amount + $this->shipment_fee;
    }

    public function updatedVat()
    {
        $this->calculateTotals();
    }

    public function updatedVatRate()
    {
        $this->calculateTotals();
    }

    public function updatedShipmentFee()
    {
        if (empty($this->shipment_fee)) {
            $this->shipment_fee = 0;
        }
        $this->calculateTotals();
    }

    public function updatedVatAmount()
    {
        $this->calculateTotals();
    }

    public function store()
    {
        $this->validate();

        try {
            DB::beginTransaction();

            // Create the order
            $order = Order::create([
                'ip' => request()->ip(),
                'name' => $this->name,
                'email' => $this->email,
                'phone' => $this->phone,
                'delivery_type' => $this->delivery_type,
                'address' => $this->address,
                'state' => $this->state,
                'city' => $this->city,
                'delivery_date' => $this->delivery_date,
                'delivery_time' => $this->delivery_time,
                'instructions' => $this->instructions,
                'shipment_fee' => $this->shipment_fee,
                'vat_amount' => $this->vat_amount,
                'status' => $this->order_status,
                'refund_amount' => 0.00,
            ]);

            // Create order items
            foreach ($this->orderItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'meal_id' => $item['meal_id'],
                    'food_id' => $item['food_id'],
                    'food_size_id' => $item['food_size_id'],
                    'price' => $item['price'],
                    'quantity' => $item['quantity'],
                    'amount' => $item['amount'],
                ]);
            }

            // Create payment record
            Payment::create([
                'order_id' => $order->id,
                'reference' => 'ORD-' . $order->id . '-' . time(),
                'vat' => $this->vat_amount,
                'amount' => $this->total,
                'method' => $this->payment_method,
                'status' => $this->payment_status,
            ]);

            DB::commit();

            session()->flash('message', 'Order created successfully!');
            
            return redirect()->route('orders.index');

        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Error creating order: ' . $e->getMessage());
        }
    }

    public function render()
    {
        $meals = Meal::with(['foods.sizes'])
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return view('livewire.dashboard-area.orders.create-order', [
            'meals' => $meals,
        ]);
    }
}
