<?php

namespace App\Livewire\LandingArea;


use Carbon\Carbon;
use App\Models\City;
use App\Models\Order;
use App\Models\State;
use App\Models\Payment;
use Livewire\Component;
use App\Models\Location;
use App\Models\OrderItem;
use Livewire\Attributes\On;
use App\Models\ShipmentRoute;
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
    public $shipmentFee = 0;
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
    
    // Location selection
    public $deliveryState = '';
    public $deliveryCity = '';
    public $shipment_route_id = null;
    
    // Minimum delivery calculation
    public $minDeliveryDate = '';
    public $minDeliveryTime = '';
    public $totalPreparationMinutes = 0;
    public $userGuidance = '';
    public $deliveryTimeError = '';

    public $states;
    public $cities;
    public $locations;
    
    public function mount()
    {
        $this->vatRate = config('services.settings.vat_rate', 0);
        $this->getCartItems();
        $this->loadLocations();
    }

    public function loadLocations()
    {
        $this->locations = Location::all();
        
        // Get states that have locations
        $stateIds = $this->locations->pluck('state_id')->unique();
        $this->states = State::whereIn('id', $stateIds)->orderBy('name')->get();
        
        $this->cities = collect(); // Will be populated when state is selected
    }

    #[On('updateCart')]
    public function getCartItems()
    {
        $cartItems = $this->getCartDb();
        $this->cartItems = collect($cartItems)->groupBy('meal_id');
        $this->calculateCartTotals();
    }

    public function resetMinimumDeliveryTime()
    {
        $this->minDeliveryDate = '';
        $this->minDeliveryTime = '';
        $this->totalPreparationMinutes = 0;
        $this->userGuidance = '';
        $this->deliveryDate = '';
        $this->deliveryTime = '';
        $this->deliveryTimeError = '';
    }

    public function updatedDeliveryState($value)
    {
        if ($value) {
            // Get all locations in the selected state
            $storeLocations = Location::where('state_id', $value)->get();
            
            // Get all shipment routes where location_id IN storeLocations IDs
            $shipmentRoutes = ShipmentRoute::whereIn('location_id', $storeLocations->pluck('id'))->get();
            
            // Get all cities where id IN shipment routes' destination_city_id
            $destinationCityIds = $shipmentRoutes->pluck('destination_city_id')->unique();
            $this->cities = City::whereIn('id', $destinationCityIds)->orderBy('name')->get();
            
            // Log for debugging
            Log::info('Cities populated for state', [
                'state_id' => $value,
                'store_locations_count' => $storeLocations->count(),
                'shipment_routes_count' => $shipmentRoutes->count(),
                'available_cities_count' => $this->cities->count(),
                'city_ids' => $destinationCityIds->toArray()
            ]);
            
            $this->deliveryCity = '';
            $this->resetMinimumDeliveryTime();
            $this->deliveryTimeError = '';
            $this->calculateDeliveryFee();
        } else {
            $this->cities = collect();
            $this->deliveryCity = '';
            $this->shipmentFee = 0;
            $this->shipment_route_id = null;
            $this->resetMinimumDeliveryTime();
            $this->deliveryTimeError = '';
            $this->calculateCartTotals();
        }
    }

    public function updatedDeliveryCity($value)
    {
        if ($value) {
            if ($this->deliveryType === 'pickup') {
                // For pickup, update delivery address
                $this->updateDeliveryAddressForPickup();
                $this->shipmentFee = 0;
                $this->shipment_route_id = null;
            } else {
                // For delivery, calculate delivery fee
                $this->calculateDeliveryFee();
            }
            
            // Calculate total preparation minutes when city is selected
            $this->calculateTotalPreparationMinutes();
            // Validate delivery time if date and time are already selected
            if ($this->deliveryDate && $this->deliveryTime) {
                $this->updatedDeliveryTime($this->deliveryTime);
            }
        } else {
            $this->deliveryAddress = '';
            $this->shipmentFee = 0;
            $this->shipment_route_id = null;
            $this->minDeliveryDate = '';
            $this->minDeliveryTime = '';
            $this->totalPreparationMinutes = 0;
            $this->userGuidance = '';
            $this->deliveryTimeError = '';
            $this->calculateCartTotals();
        }
    }

    public function updatedDeliveryType($value)
    {
        if ($value === 'pickup') {
            // For pickup, clear delivery address and set shipment fee to 0
            $this->deliveryAddress = '';
            $this->shipmentFee = 0;
            $this->shipment_route_id = null;
            
            // If state and city are already selected, update delivery address
            if ($this->deliveryState && $this->deliveryCity) {
                $this->updateDeliveryAddressForPickup();
                $this->calculateTotalPreparationMinutes();
            }
        } else {
            // For delivery, clear delivery address if it was set for pickup
            if ($this->deliveryState && $this->deliveryCity) {
                $this->deliveryAddress = '';
                $this->calculateTotalPreparationMinutes();
            }
        }
        
        // Clear delivery time error when delivery type changes
        $this->deliveryTimeError = '';
        
        $this->calculateCartTotals();
    }

    public function calculateDeliveryFee()
    {
        if (!$this->deliveryCity) {
            $this->shipmentFee = 0;
            $this->shipment_route_id = null;
            $this->calculateCartTotals();
            return;
        }

        // For delivery, calculate delivery fee based on shipment route
        // Find shipment route where destination_city_id matches the selected city
        $shipmentRoute = ShipmentRoute::where('destination_city_id', $this->deliveryCity)->first();
        
        if ($shipmentRoute) {
            $basePrice = $shipmentRoute->base_price ?? 0;
            $quantityMultiplier = config('services.settings.shipment_quantity_multiplier', 1);
            $quantityCap = config('services.settings.shipment_quantity_cap', 5);
            
            // Store the shipment route ID for order creation
            $this->shipment_route_id = $shipmentRoute->id;
            
            // Calculate total quantity of items
            $totalQuantity = 0;
            if ($this->cartItems && $this->cartItems->count() > 0) {
                foreach ($this->cartItems as $mealGroup) {
                    foreach ($mealGroup as $item) {
                        $totalQuantity += $item->quantity;
                    }
                }
            }
            
            // Apply quantity multiplier with cap
            $quantityToUse = min($totalQuantity, $quantityCap);
            $additionalFee = $basePrice * $quantityToUse * $quantityMultiplier;
            
            $this->shipmentFee = $basePrice + $additionalFee;
            
            // Log for debugging
            Log::info('Shipment fee calculated', [
                'city_id' => $this->deliveryCity,
                'shipment_route_id' => $shipmentRoute->id,
                'location_id' => $shipmentRoute->location_id,
                'base_price' => $basePrice,
                'total_quantity' => $totalQuantity,
                'quantity_to_use' => $quantityToUse,
                'multiplier' => $quantityMultiplier,
                'additional_fee' => $additionalFee,
                'final_fee' => $this->shipmentFee
            ]);
        } else {
            $this->shipmentFee = 0;
            $this->shipment_route_id = null;
            Log::warning('No shipment route found for city', ['city_id' => $this->deliveryCity]);
        }
        
        $this->calculateCartTotals();
    }

    public function updateDeliveryAddressForPickup()
    {
        //Log::info('Delivery city', ['delivery_city' => $this->deliveryCity]);
        if ($this->deliveryCity) {
            $shipmentRoute = ShipmentRoute::where('destination_city_id', $this->deliveryCity)->first();
            $location = Location::where('id', $shipmentRoute->location_id)->first();
            if ($location) {
                $this->deliveryAddress = $location->address.', '.$location->city->name.', '.$location->state->name;
                Log::info('Pickup address updated', [
                    'city_id' => $this->deliveryCity,
                    'location_id' => $location->id,
                    'address' => $location->address
                ]);
            }
        }
    }

    public function calculateTotalPreparationMinutes()
    {
        // Get cooking minutes from config
        $cookingMinutes = config('services.settings.cooking_minutes', 30);
        
        // Get estimated shipment minutes if city is selected
        $estimatedShipmentMinutes = 0;
        if ($this->deliveryCity) {
            $shipmentRoute = ShipmentRoute::where('destination_city_id', $this->deliveryCity)->first();
            if ($shipmentRoute) {
                $estimatedShipmentMinutes = $shipmentRoute->estimated_minutes ?? 0;
            }
        }
        
        // Calculate total preparation time
        $this->totalPreparationMinutes = $cookingMinutes + $estimatedShipmentMinutes;
        
        $now = now();
        
        // If past operating hours, minimum delivery is next day with proper start time
        if ($this->isPastOperatingHours()) {
            $this->minDeliveryDate = $now->addDay()->format('Y-m-d');
            
            // Calculate earliest possible delivery time for next day
            $nextDayStart = $this->getStartOfOperatingHours($this->minDeliveryDate);
            $earliestDeliveryTime = $nextDayStart->copy()->addMinutes($this->totalPreparationMinutes);
            
            $this->minDeliveryTime = $earliestDeliveryTime->format('H:i');
        } else {
            // Calculate minimum delivery date and time based on preparation time
            $minDateTime = $now->copy()->addMinutes($this->totalPreparationMinutes);
            
            // Check if preparation time extends beyond operating hours
            $endOfOperatingHours = $this->getEndOfOperatingHours();
            
            if ($minDateTime->gt($endOfOperatingHours)) {
                // Preparation extends beyond operating hours, move to next day
                $this->minDeliveryDate = $now->addDay()->format('Y-m-d');
                $nextDayStart = $this->getStartOfOperatingHours($this->minDeliveryDate);
                $earliestDeliveryTime = $nextDayStart->copy()->addMinutes($this->totalPreparationMinutes);
                $this->minDeliveryTime = $earliestDeliveryTime->format('H:i');
            } else {
                // Can deliver today within operating hours
                $this->minDeliveryDate = $now->format('Y-m-d');
                $this->minDeliveryTime = $minDateTime->format('H:i');
            }
        }
        
        // Generate user guidance
        $this->generateUserGuidance();
        
        Log::info('Minimum delivery time calculated', [
            'cooking_minutes' => $cookingMinutes,
            'estimated_shipment_minutes' => $estimatedShipmentMinutes,
            'total_preparation_minutes' => $this->totalPreparationMinutes,
            'operating_hours_end' => $this->getEndOfOperatingHours()->format('H:i'),
            'is_past_operating_hours' => $this->isPastOperatingHours(),
            'min_delivery_date' => $this->minDeliveryDate,
            'min_delivery_time' => $this->minDeliveryTime
        ]);
    }

    /**
     * Get the minimum delivery date (today if before 4pm, tomorrow if after 4pm)
     */
    public function getMinimumDeliveryDate()
    {
        $now = now();
        $endHour = config('services.settings.operating_hours_end', 16);
        $endTime = $now->copy()->setTime($endHour, 0, 0);
        
        if ($now->gt($endTime)) {
            return now()->addDay()->format('Y-m-d');
        }
        return now()->format('Y-m-d');
    }

    /**
     * Validate delivery date (must be >= minimum date)
     */
    public function updatedDeliveryDate($value)
    {
        $this->deliveryTimeError = '';
        
        if ($value) {
            $selectedDate = Carbon::parse($value);
            $minDate = Carbon::parse($this->getMinimumDeliveryDate());
            
            if ($selectedDate->lt($minDate)) {
                $this->deliveryTimeError = 'Delivery date must be ' . ($minDate->isToday() ? 'today' : 'tomorrow') . ' or later.';
            }
        }
    }

    /**
     * Validate delivery time (must be after start hour + cooking + delivery, and before end hour)
     */
    public function updatedDeliveryTime($value)
    {
        $this->deliveryTimeError = '';
        
        if ($value && $this->deliveryDate) {
            $selectedDateTime = Carbon::parse($this->deliveryDate . ' ' . $value);
            
            // Get start and end of operating hours for the selected date
            $startHour = config('services.settings.operating_hours_start', 6);
            $endHour = config('services.settings.operating_hours_end', 16);
            
            $startTime = Carbon::parse($this->deliveryDate)->setTime($startHour, 0, 0);
            $endTime = Carbon::parse($this->deliveryDate)->setTime($endHour, 0, 0);
            
            // Calculate earliest possible delivery time (start hour + cooking + delivery)
            $earliestTime = $startTime->copy()->addMinutes($this->totalPreparationMinutes);
            
            // Check if time is after earliest possible time
            if ($selectedDateTime->lt($earliestTime)) {
                $this->deliveryTimeError = "Delivery time must be after {$earliestTime->format('g:i A')} to allow for preparation and delivery time.";
                return;
            }
            
            // Check if time is before end of operating hours
            if ($selectedDateTime->gt($endTime)) {
                $this->deliveryTimeError = "Delivery time must be before {$endTime->format('g:i A')} (end of operating hours).";
                return;
            }
        }
    }



    public function generateUserGuidance()
    {
        $cookingMinutes = config('services.settings.cooking_minutes', 30);
        $estimatedShipmentMinutes = 0;
        
        if ($this->deliveryCity) {
            $shipmentRoute = ShipmentRoute::where('destination_city_id', $this->deliveryCity)->first();
            if ($shipmentRoute) {
                $estimatedShipmentMinutes = $shipmentRoute->estimated_minutes ?? 0;
            }
        }
        
        $totalHours = floor($this->totalPreparationMinutes / 60);
        $totalMinutes = $this->totalPreparationMinutes % 60;
        
        if ($totalHours > 0) {
            $timeString = $totalHours . ' hour' . ($totalHours > 1 ? 's' : '');
            if ($totalMinutes > 0) {
                $timeString .= ' and ' . $totalMinutes . ' minute' . ($totalMinutes > 1 ? 's' : '');
            }
        } else {
            $timeString = $totalMinutes . ' minute' . ($totalMinutes > 1 ? 's' : '');
        }
        
        // Simple guidance message
        $endHour = config('services.settings.operating_hours_end', 16);
        $endTime = now()->setTime($endHour, 0, 0);
        
        if (now()->gt($endTime)) {
            $this->userGuidance = "Orders placed after {$endHour}:00 (end of operating hours) can only be delivered from tomorrow onwards. Please select a delivery date for tomorrow or later.";
        } else {
            $this->userGuidance = "Please select a date and time at least {$timeString} from now to allow for cooking ({$cookingMinutes} min) and delivery time ({$estimatedShipmentMinutes} min). Orders placed after {$endHour}:00 (end of operating hours) can only be delivered from tomorrow onwards.";
        }
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
        
        // Calculate total with delivery fee
        $this->total = $this->subtotal + $this->vatAmount + $this->shipmentFee;
    }

    public function completePayment()
    {
        Log::info('Processing order',['entered']);
        // Validate required fields
        $this->validate([
            'customerName' => 'required|string|max:255',
            'customerEmail' => 'required|email|max:255',
            'customerPhone' => 'required|string|max:20',
            'deliveryState' => 'required|exists:sqlite_states.states,id',
            'deliveryCity' => 'required|exists:sqlite_cities.cities,id',
            'deliveryAddress' => 'required_if:deliveryType,delivery|string|max:500',
            'deliveryDate' => 'required|date|after_or_equal:' . $this->getMinimumDeliveryDate(),
            'deliveryTime' => 'required',
            'deliveryType' => 'required|in:delivery,pickup',
        ]);
        Log::info('Processing order',['validated']);
        // Additional validation for minimum delivery time
        if ($this->deliveryDate && $this->deliveryTime) {
            

            

            
            // Check if the selected time allows for preparation and delivery
            $minDateTime = $this->getEarliestDeliveryTimeForDate($this->deliveryDate);
            if (!$minDateTime) {
                session()->flash('error', 'Cannot deliver on the selected date. Please choose a different date.');
                return;
            }
            
            Log::info('Minimum delivery time calculated', [
                'min_datetime' => $minDateTime->format('Y-m-d H:i:s'),
                'selected_datetime' => $selectedDateTime->format('Y-m-d H:i:s'),
                'is_valid' => $selectedDateTime->gte($minDateTime)
            ]);
            
            if ($selectedDateTime->lt($minDateTime)) {
                $minTimeFormatted = $minDateTime->format('g:i A');
                session()->flash('error', "Delivery time must be after {$minTimeFormatted} on the selected date to allow for preparation and delivery time.");
                return;
            }
            
            // Check if we're past operating hours and trying to order for today
            if ($this->isPastOperatingHours() && $selectedDateTime->isToday()) {
                $endOfOperatingHours = $this->getEndOfOperatingHours();
                session()->flash('error', "Orders placed after {$endOfOperatingHours->format('g:i A')} (end of operating hours) can only be delivered from tomorrow onwards. Please select a delivery date for tomorrow or later.");
                return;
            }

            // Check if delivery time is within operating hours
            $deliveryDate = Carbon::parse($this->deliveryDate);
            $startOfOperatingHours = $this->getStartOfOperatingHours($deliveryDate);
            $endOfOperatingHours = $this->getStartOfOperatingHours($deliveryDate)->copy()->setTime(
                config('services.settings.operating_hours_end', 16), 
                0, 
                0
            );

            // Check if delivery time is before start of operating hours
            if ($selectedDateTime->lt($startOfOperatingHours)) {
                session()->flash('error', "Delivery time must be after {$startOfOperatingHours->format('g:i A')} (start of operating hours) on the selected date.");
                return;
            }

            // Check if delivery time is after end of operating hours
            if ($selectedDateTime->gt($endOfOperatingHours)) {
                session()->flash('error', "Delivery time must be before {$endOfOperatingHours->format('g:i A')} (end of operating hours) on the selected date.");
                return;
            }
        }
        Log::info('Processing order',['before try']);
        try {
            DB::beginTransaction();
            
            // Get state and city names for storage
            $stateName = null;
            $cityName = null;
            
            if ($this->deliveryState) {
                $state = State::find($this->deliveryState);
                $stateName = $state ? $state->name : null;
            }
            
            if ($this->deliveryCity) {
                $city = City::find($this->deliveryCity);
                $cityName = $city ? $city->name : null;
            }
            
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
                'shipment_fee' => $this->shipmentFee,
                'total_amount' => $this->total,
                'status' => 'pending',
                'state' => $stateName,
                'city' => $cityName,
                'shipment_route_id' => $this->shipment_route_id,
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
            
            DB::commit();
            
            $link = $this->initializePayment($payment);
            Log::info('Redirecting to payment gateway: ' . $link);
            return redirect()->to($link);

        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Error placing order: ' . $e->getMessage());
        }
    }

    public function clearCart()
    {
        $this->removeFromCartDb(null);
    }

    public function render()
    {
        return view('livewire.landing-area.checkout');
    }
}
