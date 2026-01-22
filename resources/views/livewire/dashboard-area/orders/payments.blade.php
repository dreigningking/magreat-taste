<div class="content-wrapper">
    @if(session()->has('message'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fa fa-check-circle me-2"></i>{{ session('message') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <h3 class="font-weight-bold">Payment Management</h3>
            <p class="text-muted">View and manage all payment transactions</p>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0">{{ $totalPayments }}</h4>
                            <p class="mb-0">Total Payments</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fa fa-credit-card fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0">₦{{ number_format($totalAmount, 2) }}</h4>
                            <p class="mb-0">Total Amount</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fa fa-money-bill-wave fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0">{{ $successfulPayments }}</h4>
                            <p class="mb-0">Successful</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fa fa-check-circle fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-dark">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
<div>
                            <h4 class="mb-0">{{ $pendingPayments }}</h4>
                            <p class="mb-0">Pending</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fa fa-clock fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-header bg-light">
            <h5 class="mb-0"><i class="fa fa-filter me-2"></i>Filters</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-2">
                    <label class="form-label">Date From</label>
                    <input type="date" wire:model.live="dateFrom" class="form-control">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Date To</label>
                    <input type="date" wire:model.live="dateTo" class="form-control">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Status</label>
                    <select wire:model.live="status" class="form-control">
                        <option value="">All Status</option>
                        <option value="pending">Pending</option>
                        <option value="success">Success</option>
                        <option value="failed">Failed</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Method</label>
                    <select wire:model.live="method" class="form-control">
                        <option value="">All Methods</option>
                        <option value="online">Online</option>
                        <option value="cash">Cash</option>
                        <option value="card">Card</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Search</label>
                    <input type="text" wire:model.live="search" class="form-control" placeholder="Reference, Customer Name, Email, Phone">
                </div>
                <div class="col-md-1 d-flex align-items-end">
                    <button type="button" wire:click="clearFilters" class="btn btn-outline-secondary">
                        <i class="fa fa-times"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Payments Table -->
    <div class="card">
        <div class="card-header bg-light">
            <h5 class="mb-0"><i class="fa fa-list me-2"></i>Payment Transactions</h5>
        </div>
        <div class="card-body">
            @if($payments->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>
                                <button type="button" class="btn btn-link p-0 text-decoration-none" wire:click="sortBy('reference')">
                                    Reference
                                    @if($sortBy === 'reference')
                                    <i class="fa fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} ms-1"></i>
                                    @else
                                    <i class="fa fa-sort ms-1 text-muted"></i>
                                    @endif
                                </button>
                            </th>
                            <th>
                                <button type="button" class="btn btn-link p-0 text-decoration-none" wire:click="sortBy('created_at')">
                                    Date
                                    @if($sortBy === 'created_at')
                                    <i class="fa fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} ms-1"></i>
                                    @else
                                    <i class="fa fa-sort ms-1 text-muted"></i>
                                    @endif
                                </button>
                            </th>
                            <th>Customer</th>
                            <th>Order Details</th>
                            <th>
                                <button type="button" class="btn btn-link p-0 text-decoration-none" wire:click="sortBy('amount')">
                                    Amount
                                    @if($sortBy === 'amount')
                                    <i class="fa fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} ms-1"></i>
                                    @else
                                    <i class="fa fa-sort ms-1 text-muted"></i>
                                    @endif
                                </button>
                            </th>
                            <th>Method</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($payments as $payment)
                        <tr>
                            <td>
                                <strong>{{ $payment->reference }}</strong>
                            </td>
                            <td>
                                <div class="d-flex flex-column">
                                    <span>{{ $payment->created_at->format('M d, Y') }}</span>
                                    <small class="text-muted">{{ $payment->created_at->format('h:i A') }}</small>
                                </div>
                            </td>
                            <td>
                                @if($payment->order)
                                <div class="d-flex flex-column">
                                    <strong>{{ $payment->order->name }}</strong>
                                    <small class="text-muted">{{ $payment->order->email }}</small>
                                    <small class="text-muted">{{ $payment->order->phone }}</small>
                                </div>
                                @else
                                <span class="text-muted">Order not found</span>
                                @endif
                            </td>
                            <td>
                                @if($payment->order)
                                <div class="d-flex flex-column">
                                    <span>Order #{{ $payment->order->id }}</span>
                                    <small class="text-muted">{{ $payment->order->orderItems->count() }} items</small>
                                    <small class="text-muted">{{ ucfirst($payment->order->delivery_type) }}</small>
                                </div>
                                @else
                                <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td>
                                <strong class="text-success">₦{{ number_format($payment->amount, 2) }}</strong>
                                @if($payment->vat > 0)
                                <br><small class="text-muted">VAT: ₦{{ number_format($payment->vat, 2) }}</small>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-{{ $this->getPaymentMethodColor($payment->method) }}">
                                    {{ ucfirst($payment->method) }}
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-{{ $this->getPaymentStatusColor($payment->status) }}">
                                    {{ ucfirst($payment->status) }}
                                </span>
                            </td>
                            <td>
                                <button type="button"
                                    class="btn btn-sm btn-outline-info"
                                    data-bs-toggle="modal"
                                    data-bs-target="#orderDetailsModal"
                                    data-payment-id="{{ $payment->id }}"
                                    data-order-id="{{ $payment->order->id ?? '' }}"
                                    data-customer-name="{{ $payment->order->name ?? '' }}"
                                    data-customer-email="{{ $payment->order->email ?? '' }}"
                                    data-customer-phone="{{ $payment->order->phone ?? '' }}"
                                    data-delivery-type="{{ $payment->order->delivery_type ?? '' }}"
                                    data-delivery-address="{{ $payment->order->address ?? '' }}"
                                    data-delivery-date="{{ $payment->order->delivery_date ? $payment->order->delivery_date->format('M d, Y') : '' }}"
                                    data-delivery-time="{{ $payment->order->delivery_time ? $payment->order->delivery_time->format('h:i A') : '' }}"
                                    data-order-status="{{ $payment->order->status ?? '' }}"
                                    data-payment-status="{{ $payment->status }}"
                                    data-payment-method="{{ $payment->method }}"
                                    data-payment-reference="{{ $payment->reference }}"
                                    data-payment-amount="{{ number_format($payment->amount, 2) }}"
                                    data-payment-vat="{{ number_format($payment->vat, 2) }}"
                                    data-order-items="{{ json_encode($payment->order->orderItems ?? []) }}"
                                    title="View Order Details">
                                    <i class="fa fa-eye"></i>
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4">
                {{ $payments->links('vendor.pagination.bootstrap-5') }}
            </div>
            @else
            <div class="text-center py-5">
                <i class="fa fa-credit-card text-muted" style="font-size: 4rem;"></i>
                <h5 class="text-muted mt-3">No Payments Found</h5>
                <p class="text-muted">No payment transactions match your current filters.</p>
                <button type="button" wire:click="clearFilters" class="btn btn-primary">
                    <i class="fa fa-filter me-2"></i>Clear Filters
                </button>
            </div>
            @endif
        </div>
    </div>

    <!-- Order Details Modal -->
    <div class="modal fade" id="orderDetailsModal" tabindex="-1" aria-labelledby="orderDetailsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="orderDetailsModalLabel">Order Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <!-- Customer Information -->
                        <div class="col-md-6">
                            <h6 class="text-primary mb-3">Customer Information</h6>
                            <div class="mb-2">
                                <strong>Name:</strong> <span id="modal-customer-name"></span>
                            </div>
                            <div class="mb-2">
                                <strong>Email:</strong> <span id="modal-customer-email"></span>
                            </div>
                            <div class="mb-2">
                                <strong>Phone:</strong> <span id="modal-customer-phone"></span>
                            </div>
                            <div class="mb-2">
                                <strong>Delivery Type:</strong>
                                <span class="badge bg-info" id="modal-delivery-type"></span>
                            </div>
                            <div class="mb-2" id="modal-address-section" style="display: none;">
                                <strong>Address:</strong> <span id="modal-delivery-address"></span>
                            </div>
                            <div class="mb-2">
                                <strong>Delivery Date:</strong> <span id="modal-delivery-date"></span>
                            </div>
                            <div class="mb-2">
                                <strong>Delivery Time:</strong> <span id="modal-delivery-time"></span>
                            </div>
                        </div>

                        <!-- Payment Information -->
                        <div class="col-md-6">
                            <h6 class="text-success mb-3">Payment Information</h6>
                            <div class="mb-2">
                                <strong>Reference:</strong> <span id="modal-payment-reference"></span>
                            </div>
                            <div class="mb-2">
                                <strong>Amount:</strong> <span class="text-success fw-bold" id="modal-payment-amount"></span>
                            </div>
                            <div class="mb-2">
                                <strong>VAT:</strong> <span id="modal-payment-vat"></span>
                            </div>
                            <div class="mb-2">
                                <strong>Method:</strong> <span class="badge" id="modal-payment-method"></span>
                            </div>
                            <div class="mb-2">
                                <strong>Payment Status:</strong> <span class="badge" id="modal-payment-status"></span>
                            </div>
                            <div class="mb-2">
                                <strong>Order Status:</strong> <span class="badge" id="modal-order-status"></span>
                            </div>
                        </div>
                    </div>

                    <!-- Order Items -->
                    <div class="mt-4">
                        <h6 class="text-warning mb-3">Order Items</h6>
                        <div class="table-responsive">
                            <table class="table table-sm" id="modal-order-items-table">
                                <thead class="table-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Meal</th>
                                        <th>Food</th>
                                        <th>Size</th>
                                        <th>Price</th>
                                        <th>Qty</th>
                                        <th>Amount</th>
                                    </tr>
                                </thead>
                                <tbody id="modal-order-items-body">
                                    <!-- Items will be populated by JavaScript -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>



@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const orderDetailsModal = document.getElementById('orderDetailsModal');

        orderDetailsModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;

            // Get data from button attributes
            const customerName = button.getAttribute('data-customer-name');
            const customerEmail = button.getAttribute('data-customer-email');
            const customerPhone = button.getAttribute('data-customer-phone');
            const deliveryType = button.getAttribute('data-delivery-type');
            const deliveryAddress = button.getAttribute('data-delivery-address');
            const deliveryDate = button.getAttribute('data-delivery-date');
            const deliveryTime = button.getAttribute('data-delivery-time');
            const orderStatus = button.getAttribute('data-order-status');
            const paymentStatus = button.getAttribute('data-payment-status');
            const paymentMethod = button.getAttribute('data-payment-method');
            const paymentReference = button.getAttribute('data-payment-reference');
            const paymentAmount = button.getAttribute('data-payment-amount');
            const paymentVat = button.getAttribute('data-payment-vat');
            const orderItems = JSON.parse(button.getAttribute('data-order-items') || '[]');

            // Populate customer information
            document.getElementById('modal-customer-name').textContent = customerName || 'N/A';
            document.getElementById('modal-customer-email').textContent = customerEmail || 'N/A';
            document.getElementById('modal-customer-phone').textContent = customerPhone || 'N/A';
            document.getElementById('modal-delivery-type').textContent = deliveryType ? deliveryType.charAt(0).toUpperCase() + deliveryType.slice(1) : 'N/A';
            document.getElementById('modal-delivery-date').textContent = deliveryDate || 'N/A';
            document.getElementById('modal-delivery-time').textContent = deliveryTime || 'N/A';

            // Handle address display
            const addressSection = document.getElementById('modal-address-section');
            const addressSpan = document.getElementById('modal-delivery-address');
            if (deliveryType === 'delivery' && deliveryAddress) {
                addressSection.style.display = 'block';
                addressSpan.textContent = deliveryAddress;
            } else {
                addressSection.style.display = 'none';
            }

            // Populate payment information
            document.getElementById('modal-payment-reference').textContent = paymentReference || 'N/A';
            document.getElementById('modal-payment-amount').textContent = paymentAmount ? '₦' + paymentAmount : 'N/A';
            document.getElementById('modal-payment-vat').textContent = paymentVat ? '₦' + paymentVat : '₦0.00';

            // Set payment method badge
            const methodBadge = document.getElementById('modal-payment-method');
            methodBadge.textContent = paymentMethod ? paymentMethod.charAt(0).toUpperCase() + paymentMethod.slice(1) : 'N/A';
            methodBadge.className = 'badge bg-' + (paymentMethod === 'online' ? 'primary' : paymentMethod === 'cash' ? 'success' : 'info');

            // Set payment status badge
            const paymentStatusBadge = document.getElementById('modal-payment-status');
            paymentStatusBadge.textContent = paymentStatus ? paymentStatus.charAt(0).toUpperCase() + paymentStatus.slice(1) : 'N/A';
            paymentStatusBadge.className = 'badge bg-' + (paymentStatus === 'success' ? 'success' : paymentStatus === 'pending' ? 'warning' : 'danger');

            // Set order status badge
            const orderStatusBadge = document.getElementById('modal-order-status');
            orderStatusBadge.textContent = orderStatus ? orderStatus.charAt(0).toUpperCase() + orderStatus.slice(1) : 'N/A';
            orderStatusBadge.className = 'badge bg-' + (orderStatus === 'completed' ? 'success' : orderStatus === 'processing' ? 'info' : orderStatus === 'cancelled' ? 'danger' : 'warning');

            // Populate order items
            const tbody = document.getElementById('modal-order-items-body');
            tbody.innerHTML = '';

            if (orderItems.length > 0) {
                orderItems.forEach((item, index) => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                    <td>${index + 1}</td>
                    <td>${item.meal ? item.meal.name : 'N/A'}</td>
                    <td>${item.food ? item.food.name : 'N/A'}</td>
                    <td>${item.food_size ? item.food_size.name : 'N/A'}</td>
                    <td>₦${parseFloat(item.price).toFixed(2)}</td>
                    <td>${item.quantity}</td>
                    <td><strong>₦${parseFloat(item.amount).toFixed(2)}</strong></td>
                `;
                    tbody.appendChild(row);
                });
            } else {
                const row = document.createElement('tr');
                row.innerHTML = '<td colspan="7" class="text-center text-muted">No items found</td>';
                tbody.appendChild(row);
            }
        });
    });
</script>
@endpush