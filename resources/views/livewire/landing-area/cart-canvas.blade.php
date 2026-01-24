<div>
    <a href="#" class="cart-btn" data-bs-toggle="offcanvas" data-bs-target="#cartOffcanvas" aria-controls="cartOffcanvas">
        <i class="fas fa-shopping-cart fa-lg"></i>
        <span class="cart-badge">{{ $this->getTotalCartItemsCount() }}</span>
    </a>
    <div wire:ignore.self class="offcanvas offcanvas-end" tabindex="-1" id="cartOffcanvas">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title">Your Order</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <div class="cart-items">
                @if(!$this->isCartEmpty())
                @foreach($cartItems as $mealId => $mealGroup)
                <div class="meal-card mb-4 p-3 border rounded shadow-sm">
                    <div class="meal-header d-flex justify-content-between align-items-center mb-3">
                        <h6 class="meal-title mb-0 fw-bold text-primary">
                            <i class="fas fa-utensils me-2"></i>{{ $mealGroup->first()['meal']['name'] ?? 'Meal' }}
                        </h6>
                        <div class="meal-actions">
                            @php
                            $mealTotal = $mealGroup->sum(function($item) {
                                return $item['price'] * $item['quantity'];
                            });
                            @endphp
                            <span class="meal-total fw-bold text-success">₦{{ number_format($mealTotal, 2) }}</span>
                            <button class="btn btn-outline-danger btn-sm ms-2"
                                wire:click="removeFromCart({{ $mealId }})"
                                title="Remove this meal from cart">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                    <div class="food-items">
                        @foreach($mealGroup as $item)
                        <div class="food-item d-flex justify-content-between align-items-center py-2 @if(!$loop->last) border-bottom @endif">
                            <div class="item-details">
                                <span class="item-name fw-semibold">{{ $item['food']['name'] ?? 'Food' }}</span>
                                <small class="text-muted d-block">{{ $item['size']['name'] ?? 'Size' }} × {{ $item['quantity'] }}</small>
                            </div>
                            <div class="item-price-actions d-flex align-items-center">
                                <span class="item-price fw-bold me-3">₦{{ number_format($item['price'] * $item['quantity'], 2) }}</span>
                                <button class="btn btn-outline-danger btn-sm"
                                    wire:click="removeCartItem({{ $item['id'] }})"
                                    title="Remove this item">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                        @endforeach
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
@push('styles')
<style>
    .cart-btn {
        position: fixed;
        top: 20px;
        right: 20px;
        background: var(--primary);
        color: white;
        padding: 12px 16px;
        border-radius: 50px;
        text-decoration: none;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        z-index: 1000;
        transition: all 0.3s ease;
    }

    .cart-btn:hover {
        background: var(--primary-dark);
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.2);
        color: white;
    }

    .cart-badge {
        position: absolute;
        top: -8px;
        right: -8px;
        background: #dc3545;
        color: white;
        border-radius: 50%;
        width: 20px;
        height: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.75rem;
        font-weight: bold;
    }

    .meal-card {
        background: #f8f9fa;
        transition: all 0.3s ease;
    }

    .meal-card:hover {
        background: #e9ecef;
        transform: translateY(-1px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    .cart-summary {
        background: #f8f9fa;
        padding: 1rem;
        border-radius: 8px;
        margin-top: 1rem;
    }

    .offcanvas {
        width: 400px;
    }

    @media (max-width: 576px) {
        .offcanvas {
            width: 100%;
        }

        .cart-btn {
            top: 10px;
            right: 10px;
            padding: 10px 14px;
        }
    }
</style>
@endpush