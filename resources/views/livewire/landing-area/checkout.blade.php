<div wire:ignore.self class="modal fade" id="checkoutModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Complete Your Order</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <!-- Customer Information -->
                    <div class="col-md-6">
                        <h5 class="mb-4">Customer Information</h5>
                        <form wire:submit.prevent="completePayment">
                            @if($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                            @endif
                            
                            <div class="mb-3">
                                <label for="name" class="form-label">Full Name *</label>
                                <input type="text" class="form-control" id="name" wire:model="customerName" required>
                                @error('customerName') <span class="text-danger small">{{ $message }}</span> @enderror
                            </div>
                            
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email Address *</label>
                                        <input type="email" class="form-control" id="email" wire:model="customerEmail" required>
                                        @error('customerEmail') <span class="text-danger small">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="phone" class="form-label">Phone Number *</label>
                                        <input type="tel" class="form-control" id="phone" wire:model="customerPhone" required>
                                        @error('customerPhone') <span class="text-danger small">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="deliveryType" class="form-label">Delivery Type *</label>
                                <select class="form-select" id="deliveryType" wire:model.live="deliveryType" required>
                                    <option value="delivery">Home Delivery</option>
                                    <option value="pickup">Pickup</option>
                                </select>
                                @error('deliveryType') <span class="text-danger small">{{ $message }}</span> @enderror
                            </div>
                            
                            <div class="row mb-3">
                                <div class="col-md-6">       
                                    <label for="state" class="form-label">{{ ucwords($deliveryType) }} State *</label>
                                    <select class="form-control form-select" id="state" wire:model.live="deliveryState" required>
                                        <option value="">Select State</option>
                                        @foreach($states as $state)
                                        <option value="{{ $state->id }}">{{ $state->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('deliveryState') <span class="text-danger small">{{ $message }}</span> @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="city" class="form-label">{{ ucwords($deliveryType) }} City *</label>
                                    <select class="form-control form-select" id="city" wire:model.live="deliveryCity" required {{ empty($cities) ? 'disabled' : '' }}>
                                        <option value="">Select City</option>
                                        @foreach($cities as $city)
                                        <option value="{{ $city->id }}">{{ $city->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('deliveryCity') <span class="text-danger small">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            
                            @if($deliveryType === 'delivery')
                            <div class="mb-3" id="addressField">
                                <label for="address" class="form-label">Delivery Address *</label>
                                <textarea class="form-control" id="address" rows="3" wire:model="deliveryAddress" placeholder="Enter your full delivery address"></textarea>
                                @error('deliveryAddress') <span class="text-danger small">{{ $message }}</span> @enderror
                            </div>
                            @else
                            <div class="mb-3" id="pickupAddressField">
                                <label for="pickupAddress" class="form-label">Pickup Location</label>
                                <div class="form-control-plaintext bg-light p-2">
                                    @if($deliveryAddress)
                                        <i class="fa fa-map-marker text-primary me-2"></i>{{ $deliveryAddress }}
                                    @else
                                        <span class="text-muted">Select a city to see pickup location</span>
                                    @endif
                                </div>
                            </div>
                            @endif

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="date" class="form-label">{{ ucwords($deliveryType) }} Date *</label>
                                    <input type="date" 
                                           min="{{ $minDeliveryDate ?: now()->format('Y-m-d') }}" 
                                           class="form-control" 
                                           id="date" 
                                           wire:model.live="deliveryDate" 
                                           required>
                                    @error('deliveryDate') <span class="text-danger small">{{ $message }}</span> @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="time" class="form-label">{{ ucwords($deliveryType) }} Time *</label>
                                    <input type="time" 
                                           min="{{ $minDeliveryTime }}" 
                                           class="form-control" 
                                           id="time" 
                                           wire:model.live="deliveryTime" 
                                           required>
                                    @error('deliveryTime') <span class="text-danger small">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            
                            @if($userGuidance)
                            <div class="mb-3">
                                <div class="alert alert-info">
                                    <i class="fa fa-info-circle me-2"></i>
                                    <strong>Preparation Time:</strong> {{ $userGuidance }}
                                </div>
                            </div>
                            @endif

                            <div class="mb-3">
                                <label for="instructions" class="form-label">Special Instructions</label>
                                <textarea class="form-control" id="instructions" rows="2" wire:model="specialInstructions" placeholder="Any special instructions for your order..."></textarea>
                            </div>
                        </form>
                    </div>

                    <!-- Order Summary -->
                    <div class="col-md-6">
                        <h5 class="mb-4">Order Summary</h5>
                        <div class="card">
                            <div class="card-body">
                                <div class="order-items mb-3">
                                    @if($cartItems && $cartItems->count() > 0)
                                    @foreach($cartItems as $mealGroup)
                                    <div class="mb-3 p-3 border rounded bg-light">
                                        <h6 class="mb-2 fw-bold">{{ $mealGroup->first()['meal']['name'] ?? 'Meal' }}</h6>
                                        @foreach($mealGroup as $item)
                                        <div class="d-flex justify-content-between mb-1">
                                            <span class="text-muted small">
                                                {{ $item['food']['name'] ?? 'Food' }} -
                                                {{ $item['food_size']['name'] ?? 'Size' }} × {{ $item['quantity'] }}
                                            </span>
                                            <span class="small fw-bold">₦{{ number_format($item['price'] * $item['quantity'], 2) }}</span>
                                        </div>
                                        @endforeach
                                        <div class="d-flex justify-content-between mt-2 pt-2 border-top">
                                            <span class="fw-bold">Meal Total:</span>
                                            <span class="fw-bold text-primary">
                                                ₦{{ number_format($mealGroup->sum(function($item) { return $item['price'] * $item['quantity']; }), 2) }}
                                            </span>
                                        </div>
                                    </div>
                                    @endforeach
                                    @else
                                    <p class="text-muted">No items in cart</p>
                                    @endif
                                </div>
                                
                                <div class="order-summary">
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>Subtotal:</span>
                                        <span>₦{{ number_format($subtotal, 2) }}</span>
                                    </div>
                                    
                                    @if($vatAmount > 0)
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>VAT ({{ $vatRate }}%):</span>
                                        <span>₦{{ number_format($vatAmount, 2) }}</span>
                                    </div>
                                    @endif
                                    
                                    @if($shipmentFee > 0)
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>Delivery Fee:</span>
                                        <span class="text-primary fw-bold">₦{{ number_format($shipmentFee, 2) }}</span>
                                    </div>
                                    @elseif($deliveryType === 'pickup')
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>Pickup Fee:</span>
                                        <span class="text-success">Free</span>
                                    </div>
                                    @endif
                                    
                                    <hr class="my-3">
                                    
                                    <div class="d-flex justify-content-between mb-3 fw-bold fs-5">
                                        <span>Total:</span>
                                        <span class="text-primary">₦{{ number_format($total, 2) }}</span>
                                    </div>
                                </div>
                                
                                <button class="btn btn-primary w-100 mt-4 py-3" wire:click="completePayment" wire:loading.attr="disabled" wire:loading.class="opacity-50">
                                    <span wire:loading.remove>Complete Order</span>
                                    <span wire:loading>Processing...</span>
                                </button>
                                
                                @if($deliveryType === 'pickup' && $shipmentFee === 0)
                                <div class="text-center mt-3">
                                    <small class="text-success">
                                        <i class="fa fa-info-circle me-1"></i>
                                        Pickup orders have no delivery fee
                                    </small>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Show/hide address field based on delivery type
        // const deliveryTypeSelect = document.getElementById('deliveryType');
        // const addressField = document.getElementById('addressField');

        // function toggleAddressField() {
        //     if (deliveryTypeSelect.value === 'delivery') {
        //         addressField.style.display = 'block';
        //     } else {
        //         addressField.style.display = 'none';
        //     }
        // }

        // if (deliveryTypeSelect) {
        //     deliveryTypeSelect.addEventListener('change', toggleAddressField);
        //     toggleAddressField(); // Initial state
        // }
    });
</script>