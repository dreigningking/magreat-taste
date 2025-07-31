<div>
    <a href="#" class="cart-btn" data-bs-toggle="offcanvas" data-bs-target="#cartOffcanvas" aria-controls="cartOffcanvas">
        <i class="fas fa-shopping-cart fa-lg"></i>
        <span class="cart-badge">{{ $cartItems->count() }}</span>
    </a>
    <div wire:ignore.self class="offcanvas offcanvas-end" tabindex="-1" id="cartOffcanvas">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title">Your Order</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <div class="cart-items">
                @if(!$this->isCartEmpty())
                    @foreach($cartItems as $cartItem)
                    <div class="cart-item mb-3">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h6 class="mb-1">{{ optional($cartItem->first())->meal->name ?? 'Meal' }}</h6>
                                @foreach($cartItem as $item)
                                    <p class="text-muted mb-0 small">
                                        {{ optional($item->food)->name ?? 'Food' }} - 
                                        {{ optional($item->foodSize)->name ?? 'Size' }} × {{ $item->quantity }}
                                    </p>
                                @endforeach
                            </div>
                            <div class="text-end">
                                @php
                                    $mealTotal = $cartItem->sum(function($item) {
                                        return $item->price * $item->quantity;
                                    });
                                @endphp
                                <span class="fw-bold">₦{{ number_format($mealTotal, 2) }}</span>
                                <span class="remove-item ms-3" wire:click="removeFromCart({{ $cartItem->first()->meal_id }})" style="cursor:pointer;">
                                    <i class="fas fa-trash"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                    @endforeach
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
                        <p class="text-muted">Your cart is empty</p>
                    </div>
                @endif
            </div>
            <div class="cart-summary mt-auto pt-3 border-top">
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
                @if(!$this->isCartEmpty())
                    <button class="btn btn-primary w-100 py-3" data-bs-toggle="modal" data-bs-target="#checkoutModal" data-bs-dismiss="offcanvas">Checkout</button>
                @endif
            </div>
        </div>
    </div>
</div>