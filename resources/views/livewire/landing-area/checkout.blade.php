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
                            @foreach($errors->all() as $error)
                                <div class="alert alert-danger">{{ $error }}</div>
                            @endforeach
                            <div class="mb-3">
                                <label for="name" class="form-label">Full Name</label>
                                <input type="text" class="form-control" id="name" wire:model="customerName" required>
                                @error('customerName') <span class="text-danger small">{{ $message }}</span> @enderror
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email Address</label>
                                <input type="email" class="form-control" id="email" wire:model="customerEmail" required>
                                @error('customerEmail') <span class="text-danger small">{{ $message }}</span> @enderror
                            </div>
                            <div class="mb-3">
                                <label for="phone" class="form-label">Phone Number</label>
                                <input type="tel" class="form-control" id="phone" wire:model="customerPhone" required>
                                @error('customerPhone') <span class="text-danger small">{{ $message }}</span> @enderror
                            </div>
                            <div class="mb-3">
                                <label for="deliveryType" class="form-label">Delivery Type</label>
                                <select class="form-select" id="deliveryType" wire:model="deliveryType" required>
                                    <option value="delivery">Home Delivery</option>
                                    <option value="pickup">Pickup</option>
                                </select>
                                @error('deliveryType') <span class="text-danger small">{{ $message }}</span> @enderror
                            </div>
                            <div class="mb-3" id="addressField">
                                <label for="address" class="form-label">Delivery Address</label>
                                <textarea class="form-control" id="address" rows="3" wire:model="deliveryAddress"></textarea>
                                @error('deliveryAddress') <span class="text-danger small">{{ $message }}</span> @enderror
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="date" class="form-label">Delivery Date</label>
                                    <input type="date" min="{{ now()->format('Y-m-d') }}" class="form-control" id="date" wire:model="deliveryDate" required>
                                    @error('deliveryDate') <span class="text-danger small">{{ $message }}</span> @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="time" class="form-label">Delivery Time</label>
                                    <input type="time" class="form-control" id="time" wire:model="deliveryTime" required>
                                    @error('deliveryTime') <span class="text-danger small">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="instructions" class="form-label">Special Instructions</label>
                                <textarea class="form-control" id="instructions" rows="2" wire:model="specialInstructions"></textarea>
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
                                        @foreach($cartItems as $cartItem)
                                            <div class="mb-3">
                                                <h6 class="mb-1">{{ optional($cartItem->first())->meal->name ?? 'Meal' }}</h6>
                                                @foreach($cartItem as $item)
                                                    <div class="d-flex justify-content-between mb-1">
                                                        <span class="text-muted small">
                                                            {{ optional($item->food)->name ?? 'Food' }} - 
                                                            {{ optional($item->foodSize)->name ?? 'Size' }} × {{ $item->quantity }}
                                                        </span>
                                                        <span class="small">₦{{ number_format($item->price * $item->quantity, 2) }}</span>
                                                    </div>
                                                @endforeach
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
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>VAT ({{ $vatRate }}%):</span>
                                        <span>₦{{ number_format($vatAmount, 2) }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between mb-3 fw-bold">
                                        <span>Total:</span>
                                        <span>₦{{ number_format($total, 2) }}</span>
                                    </div>
                                </div>
                                <button class="btn btn-primary w-100 mt-4 py-3" wire:click="completePayment" wire:loading.attr="disabled" wire:loading.class="opacity-50">
                                    <span wire:loading.remove>Complete Order</span>
                                    <span wire:loading>Processing...</span>
                                </button>
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
    const deliveryTypeSelect = document.getElementById('deliveryType');
    const addressField = document.getElementById('addressField');
    
    function toggleAddressField() {
        if (deliveryTypeSelect.value === 'delivery') {
            addressField.style.display = 'block';
        } else {
            addressField.style.display = 'none';
        }
    }
    
    if (deliveryTypeSelect) {
        deliveryTypeSelect.addEventListener('change', toggleAddressField);
        toggleAddressField(); // Initial state
    }
});
</script>