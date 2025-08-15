<div class="content-wrapper">
    @if(session()->has('message'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fa fa-check-circle me-2"></i>{{ session('message') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    @if(session()->has('meal_success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fa fa-check-circle me-2"></i>{{ session('meal_success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    @if(session()->has('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fa fa-exclamation-circle me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="row mb-4">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <h3 class="font-weight-bold">Create New Order</h3>
<div>
                <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary">
                    <i class="fa fa-arrow-left me-2"></i>Back to Orders
                </a>
            </div>
        </div>
    </div>

    <form wire:submit.prevent="store">
        <div class="row">
            <!-- Customer Information -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fa fa-user me-2"></i>Customer Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Customer Name <span class="text-danger">*</span></label>
                            <input type="text" wire:model="name" class="form-control" required>
                            @error('name') <div class="text-danger text-xs mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" wire:model="email" class="form-control" required>
                            @error('email') <div class="text-danger text-xs mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Phone <span class="text-danger">*</span></label>
                            <input type="text" wire:model="phone" class="form-control" required>
                            @error('phone') <div class="text-danger text-xs mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Delivery Type <span class="text-danger">*</span></label>
                            <select wire:model="delivery_type" class="form-control" required>
                                <option value="pickup">Pickup</option>
                                <option value="delivery">Delivery</option>
                            </select>
                            @error('delivery_type') <div class="text-danger text-xs mt-1">{{ $message }}</div> @enderror
                        </div>

                        @if($delivery_type === 'delivery')
                        <div class="mb-3">
                            <label class="form-label">Address</label>
                            <textarea wire:model="address" class="form-control" rows="3"></textarea>
                            @error('address') <div class="text-danger text-xs mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">City</label>
                                    <input type="text" wire:model="city" class="form-control">
                                    @error('city') <div class="text-danger text-xs mt-1">{{ $message }}</div> @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">State</label>
                                    <input type="text" wire:model="state" class="form-control">
                                    @error('state') <div class="text-danger text-xs mt-1">{{ $message }}</div> @enderror
                                </div>
                            </div>
                        </div>
                        @endif

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Delivery Date</label>
                                    <input type="date" wire:model="delivery_date" class="form-control">
                                    @error('delivery_date') <div class="text-danger text-xs mt-1">{{ $message }}</div> @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Delivery Time</label>
                                    <input type="time" wire:model="delivery_time" class="form-control">
                                    @error('delivery_time') <div class="text-danger text-xs mt-1">{{ $message }}</div> @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Special Instructions</label>
                            <textarea wire:model="instructions" class="form-control" rows="3"></textarea>
                            @error('instructions') <div class="text-danger text-xs mt-1">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order Items -->
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0"><i class="fa fa-shopping-cart me-2"></i>Order Items</h5>
                    </div>
                    <div class="card-body">
                        @if(session()->has('meal_error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fa fa-exclamation-circle me-2"></i>{{ session('meal_error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        @endif
                        @if(session()->has('meal_success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fa fa-check-circle me-2"></i>{{ session('meal_success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        @endif
                        <!-- Add Meal Form -->
                        <div class="card bg-light mb-4">
                            <div class="card-body">
                                <h6 class="mb-3">Add Meal to Order</h6>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="form-label">Select Meal <span class="text-danger">*</span></label>
                                        <select wire:model.live="selectedMeal" class="form-control">
                                            <option value="">Select Meal</option>
                                            @foreach($meals as $meal)
                                            <option value="{{ $meal->id }}">{{ $meal->name }}</option>
                                            @endforeach
                                        </select>
                                        @if(session()->has('meal_error'))
                                        <div class="text-danger text-xs mt-1">{{ session('meal_error') }}</div>
                                        @endif
                                    </div>
                                    <div class="col-md-6 d-flex align-items-end">
                                        <button type="button" class="btn btn-primary" wire:click="addMeal('{{ $selectedMeal }}')" {{ !$selectedMeal ? 'disabled' : '' }}>
                                            <i class="fa fa-plus me-2"></i>Add Meal
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Order Items List -->
                        @if(count($orderItems) > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 5%;">#</th>
                                        <th style="width: 20%;">Food</th>
                                        <th style="width: 30%;">Size</th>
                                        <th style="width: 15%;">Price</th>
                                        <th style="width: 15%;">Quantity</th>
                                        <th style="width: 15%;">Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                    $groupedItems = collect($orderItems)->groupBy('meal_id');
                                    @endphp
                                    @foreach($groupedItems as $mealId => $items)
                                    @php
                                    $meal = $meals->find($mealId);
                                    $mealTotal = $items->sum('amount');
                                    @endphp
                                    <!-- Meal Header -->
                                    <tr class="table-primary">
                                        <td colspan="5">
                                            <strong>{{ $meal ? $meal->name : 'N/A' }}</strong>
                                            <span class="badge bg-success ms-2">{{ $items->count() }} items</span>
                                        </td>
                                        <td>
                                            <strong class="text-primary">₦{{ number_format($mealTotal, 2) }}</strong>
                                        </td>
                                    </tr>
                                    <!-- Food Items -->
                                    @foreach($items as $index => $item)
                                    @php
                                    $food = $meal ? $meal->foods->find($item['food_id']) : null;
                                    $currentSize = $food ? $food->sizes->find($item['food_size_id']) : null;
                                    $itemIndex = array_search($item, $orderItems);
                                    @endphp
                                    <tr>
                                        <td>{{ $index + 1 }}</td>

                                        <td>{{ $food ? $food->name : 'N/A' }}</td>
                                        <td>
                                            <select wire:change="updateOrderItemSize({{ $itemIndex }}, $event.target.value)" class="form-control form-select" style="width: 100%; min-width: 150px;">
                                                @if($food)
                                                @foreach($food->sizes as $size)
                                                <option value="{{ $size->id }}" {{ $size->id == $item['food_size_id'] ? 'selected' : '' }}>
                                                    {{ $size->name }} - ₦{{ number_format($size->price, 2) }}
                                                </option>
                                                @endforeach
                                                @endif
                                            </select>
                                        </td>
                                        <td>₦{{ number_format($item['price'], 2) }}</td>
                                        <td>
                                            <input type="number" wire:change="updateOrderItemQuantity({{ $itemIndex }}, $event.target.value)" class="form-control form-control-sm" value="{{ $item['quantity'] }}" min="1" style="width: 80px;">
                                        </td>
                                        <td><strong>₦{{ number_format($item['amount'], 2) }}</strong></td>
                                    </tr>
                                    @endforeach
                                    <!-- Meal Remove Button -->
                                    <tr class="table-light">
                                        <td colspan="6" class="text-end">
                                            <button type="button" class="btn btn-sm btn-outline-danger" wire:click="removeMealItems({{ $mealId }})" title="Remove all items from this meal">
                                                <i class="fa fa-trash me-1"></i>Remove {{ $meal ? $meal->name : 'Meal' }}
                                            </button>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot class="table-light">
                                    <tr>
                                        <td colspan="5" class="text-end"><strong>Total:</strong></td>
                                        <td><strong class="text-success">₦{{ number_format($subtotal, 2) }}</strong></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        @else
                        <div class="text-center py-4">
                            <i class="fa fa-shopping-cart text-muted" style="font-size: 3rem;"></i>
                            <h6 class="text-muted mt-2">No Items Added</h6>
                            <p class="text-muted">Start by selecting a meal and adding items to the order.</p>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Payment Information -->
                <div class="card mt-4">
                    <div class="card-header bg-warning text-dark">
                        <h5 class="mb-0"><i class="fa fa-credit-card me-2"></i>Payment Information</h5>
                    </div>
                    <div class="card-body">

                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Subtotal</label>
                                    <input type="text" class="form-control" value="₦{{ number_format($subtotal, 2) }}" readonly>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">VAT Amount</label>
                                    <input type="text" class="form-control" value="₦{{ number_format($vat_amount, 2) }}" readonly>
                                    <small class="text-muted">Auto-calculated {{ $vat_rate }}%</small>
                                </div>

                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Shipment Fee</label>
                                    <input type="number" wire:model.live="shipment_fee" class="form-control" step="0.01" min="0" value="0">
                                    @error('shipment_fee') <div class="text-danger text-xs mt-1">{{ $message }}</div> @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Payment Status <span class="text-danger">*</span></label>
                                    <select wire:model="payment_status" class="form-control" required>
                                        <option value="pending">Pending</option>
                                        <option value="success">Success</option>
                                        <option value="failed">Failed</option>
                                    </select>
                                    @error('payment_status') <div class="text-danger text-xs mt-1">{{ $message }}</div> @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Order Status <span class="text-danger">*</span></label>
                                    <select wire:model="order_status" class="form-control" required>
                                        <option value="pending">Pending</option>
                                        <option value="processing">Processing</option>
                                        <option value="completed">Completed</option>
                                        <option value="cancelled">Cancelled</option>
                                    </select>
                                    @error('order_status') <div class="text-danger text-xs mt-1">{{ $message }}</div> @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Payment Method <span class="text-danger">*</span></label>
                                    <select wire:model="payment_method" class="form-control" required>
                                        <option value="online">Online</option>
                                        <option value="cash">Cash</option>
                                        <option value="card">Card</option>
                                    </select>
                                    @error('payment_method') <div class="text-danger text-xs mt-1">{{ $message }}</div> @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Total Amount</label>
                                    <input type="text" class="form-control form-control-lg" value="₦{{ number_format($total, 2) }}" readonly style="font-size: 1.5rem; font-weight: bold; color: #28a745;">
                                </div>
                            </div>
                            <div class="col-md-6 d-flex align-items-end">
                                <button type="submit" class="btn btn-success text-white btn-lg w-100" wire:loading.attr="disabled" {{ count($orderItems) == 0 ? 'disabled' : '' }}>
                                    <span wire:loading.remove>
                                        <i class="fa fa-save me-2"></i>Create Order
                                    </span>
                                    <span wire:loading>
                                        <i class="fa fa-spinner fa-spin me-2"></i>Creating...
                                    </span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>