@push('styles')
<style>
    @media print {
        /* Hide elements that shouldn't be printed */
        .btn-group,
        .modal,
        .alert,
        .btn-close,
        .sidebar {
            display: none !important;
        }
        
        /* Ensure content-wrapper prints properly */
        .content-wrapper {
            width: 100% !important;
            margin: 0 !important;
            padding: 0 !important;
            background: white !important;
        }
        
        /* Ensure cards print properly */
        .card {
            border: 1px solid #000 !important;
            margin-bottom: 20px !important;
            page-break-inside: avoid;
        }
        
        .card-header {
            background: #f8f9fa !important;
            color: #000 !important;
            border-bottom: 1px solid #000 !important;
        }
        
        /* Ensure table prints properly */
        .table {
            width: 100% !important;
            border-collapse: collapse !important;
        }
        
        .table th,
        .table td {
            border: 1px solid #000 !important;
            padding: 8px !important;
        }
        
        .table-light {
            background: #f8f9fa !important;
        }
        
        .table-primary {
            background: #e3f2fd !important;
        }
        
        .table-dark {
            background: #343a40 !important;
            color: white !important;
        }
        
        /* Ensure badges print properly */
        .badge {
            border: 1px solid #000 !important;
            color: #000 !important;
        }
        
        /* Ensure text colors print properly */
        .text-success,
        .text-primary,
        .text-info,
        .text-warning,
        .text-danger {
            color: #000 !important;
        }
        
        /* Ensure background colors print properly */
        .bg-success,
        .bg-primary,
        .bg-info,
        .bg-warning,
        .bg-danger {
            background: #f8f9fa !important;
            color: #000 !important;
        }
        
        /* Page break controls */
        .card {
            page-break-inside: avoid;
        }
        
        .table {
            page-break-inside: avoid;
        }
        
        /* Header styling for print */
        h3, h5 {
            color: #000 !important;
            margin-bottom: 10px !important;
        }
        
        /* Ensure proper spacing */
        .mb-3 {
            margin-bottom: 15px !important;
        }
        
        .mb-0 {
            margin-bottom: 0 !important;
        }
        
        /* Ensure form labels are visible */
        .form-label {
            font-weight: bold !important;
            color: #000 !important;
        }
        
        /* Ensure paragraphs are visible */
        p {
            color: #000 !important;
            margin-bottom: 5px !important;
        }
        
        /* Ensure icons are hidden in print */
        .fa {
            display: none !important;
        }
        
        /* Ensure proper page margins */
        @page {
            margin: 1cm;
        }
        
        /* Ensure body has proper styling */
        body {
            background: white !important;
            color: #000 !important;
        }
    }
</style>
@endpush

<div class="content-wrapper">
    @if(session()->has('message'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fa fa-check-circle me-2"></i>{{ session('message') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <!-- Header with Action Buttons -->
    <div class="row mb-4">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <div>
                <h3 class="font-weight-bold">Order #{{ $order->id }}</h3>
                <p class="text-muted mb-0">Created on {{ $order->created_at->format('M d, Y \a\t H:i') }}</p>
            </div>
            <div class="btn-group" role="group">
                <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary">
                    <i class="fa fa-arrow-left me-2"></i>Back to Orders
                </a>
                <a href="{{ route('orders.edit', $order) }}" class="btn btn-outline-primary">
                    <i class="fa fa-edit me-2"></i>Edit Order
                </a>
                <button class="btn btn-outline-info" data-bs-toggle="modal" data-bs-target="#changeOrderStatusModal" data-order-id="{{ $order->id }}" data-order-status="{{ $order->status }}">
                    <i class="fa fa-exchange me-2"></i>Change Status
                </button>
                <button class="btn btn-outline-warning" data-bs-toggle="modal" data-bs-target="#changePaymentStatusModal" data-order-id="{{ $order->id }}" data-payment-status="{{ $order->payment->status ?? 'pending' }}">
                    <i class="fa fa-credit-card me-2"></i>Payment Status
                </button>
                <button class="btn btn-outline-success" onclick="window.print()">
                    <i class="fa fa-print me-2"></i>Print Order
                </button>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Customer Details -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fa fa-user me-2"></i>Customer Information</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Customer Name</label>
                        <p class="mb-0">{{ $order->name }}</p>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Email</label>
                        <p class="mb-0">{{ $order->email }}</p>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Phone</label>
                        <p class="mb-0">{{ $order->phone }}</p>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Delivery Type</label>
                        <span class="badge bg-{{ $order->delivery_type === 'pickup' ? 'warning' : 'info' }}">
                            {{ ucfirst($order->delivery_type) }}
                        </span>
                    </div>

                    @if($order->delivery_type === 'delivery')
                    @if($order->address)
                    <div class="mb-3">
                        <label class="form-label fw-bold">Address</label>
                        <p class="mb-0">{{ $order->address }}</p>
                    </div>
                    @endif

                    @if($order->city || $order->state)
                    <div class="mb-3">
                        <label class="form-label fw-bold">Location</label>
                        <p class="mb-0">
                            @if($order->city && $order->state)
                            {{ $order->city }}, {{ $order->state }}
                            @elseif($order->city)
                            {{ $order->city }}
                            @elseif($order->state)
                            {{ $order->state }}
                            @endif
                        </p>
                    </div>
                    @endif
                    @endif

                    @if($order->delivery_date)
                    <div class="mb-3">
                        <label class="form-label fw-bold">Delivery Date</label>
                        <p class="mb-0">{{ $order->delivery_date->format('M d, Y') }}</p>
                    </div>
                    @endif

                    @if($order->delivery_time)
                    <div class="mb-3">
                        <label class="form-label fw-bold">Delivery Time</label>
                        <p class="mb-0">{{ $order->delivery_time->format('h:i A') }}</p>
                    </div>
                    @endif

                    @if($order->instructions)
                    <div class="mb-3">
                        <label class="form-label fw-bold">Special Instructions</label>
                        <p class="mb-0">{{ $order->instructions }}</p>
                    </div>
                    @endif
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
                    @if($order->orderItems->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 5%;">#</th>
                                    <th style="width: 20%;">Meal</th>
                                    <th style="width: 25%;">Food</th>
                                    <th style="width: 15%;">Size</th>
                                    <th style="width: 10%;">Price</th>
                                    <th style="width: 10%;">Quantity</th>
                                    <th style="width: 15%;">Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                $groupedItems = $order->orderItems->groupBy('meal_id');
                                @endphp
                                @foreach($groupedItems as $mealId => $items)
                                @php
                                $meal = $items->first()->meal;
                                $mealTotal = $items->sum('amount');
                                @endphp
                                <!-- Meal Header -->
                                <tr class="table-primary">
                                    <td colspan="6">
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
                                $food = $item->food;
                                $size = $item->foodSize;
                                @endphp
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td></td>
                                    <td>{{ $food ? $food->name : 'N/A' }}</td>
                                    <td>{{ $size ? $size->name : 'N/A' }}</td>
                                    <td>₦{{ number_format($item->price, 2) }}</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td><strong>₦{{ number_format($item->amount, 2) }}</strong></td>
                                </tr>
                                @endforeach
                                @endforeach
                            </tbody>
                            <tfoot class="table-light">
                                <tr>
                                    <td colspan="6" class="text-end"><strong>Subtotal:</strong></td>
                                    <td><strong class="text-success">₦{{ number_format($order->sub_total, 2) }}</strong></td>
                                </tr>
                                <tr>
                                    <td colspan="6" class="text-end"><strong>VAT ({{ config('services.vat_rate', 0) }}%):</strong></td>
                                    <td><strong class="text-info">₦{{ number_format($order->vat_amount, 2) }}</strong></td>
                                </tr>
                                <tr>
                                    <td colspan="6" class="text-end"><strong>Shipment Fee:</strong></td>
                                    <td><strong class="text-warning">₦{{ number_format($order->shipment_fee, 2) }}</strong></td>
                                </tr>
                                <tr class="table-dark">
                                    <td colspan="6" class="text-end"><strong>Total:</strong></td>
                                    <td><strong class="text-white">₦{{ number_format($order->total, 2) }}</strong></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                    @else
                    <div class="text-center py-4">
                        <i class="fa fa-shopping-cart text-muted" style="font-size: 3rem;"></i>
                        <h6 class="text-muted mt-2">No Items Found</h6>
                        <p class="text-muted">This order has no items.</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Order & Payment Status -->
            <div class="row mt-4">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header bg-warning text-dark">
                            <h5 class="mb-0"><i class="fa fa-info-circle me-2"></i>Order Status</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Current Status</label>
                                <div>
                                    <span class="badge bg-{{ $order->status_color }} fs-6">{{ ucfirst($order->status) }}</span>
                                </div>
                            </div>
                            @if($order->delivery_date)
                            <div class="mb-3">
                                <label class="form-label fw-bold">Delivery Details</label>
                                <div>
                                    <span class="badge bg-info">{{ $order->delivery_details }}</span>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header bg-info text-white">
                            <h5 class="mb-0"><i class="fa fa-credit-card me-2"></i>Payment Information</h5>
                        </div>
                        <div class="card-body">
                            @if($order->payment)
                            <div class="mb-3">
                                <label class="form-label fw-bold">Payment Status</label>
                                <div>
                                    @php
                                    $paymentStatusColors = [
                                    'pending' => 'warning',
                                    'success' => 'success',
                                    'failed' => 'danger'
                                    ];
                                    $paymentStatusColor = $paymentStatusColors[$order->payment->status] ?? 'secondary';
                                    @endphp
                                    <span class="badge bg-{{ $paymentStatusColor }} fs-6">{{ ucfirst($order->payment->status) }}</span>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Payment Method</label>
                                <p class="mb-0">{{ ucfirst($order->payment->method) }}</p>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Reference</label>
                                <p class="mb-0">{{ $order->payment->reference }}</p>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Amount Paid</label>
                                <p class="mb-0 fw-bold text-success">₦{{ number_format($order->payment->amount, 2) }}</p>
                            </div>
                            @else
                            <div class="text-center py-3">
                                <i class="fa fa-exclamation-triangle text-warning" style="font-size: 2rem;"></i>
                                <p class="text-muted mt-2">No payment record found</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Change Order Status Modal -->
    <div class="modal fade" id="changeOrderStatusModal" tabindex="-1" aria-labelledby="changeOrderStatusModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="changeOrderStatusModalLabel">Change Order Status</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form wire:submit.prevent="updateOrderStatus">
                        <input type="hidden" wire:model="selectedOrderId" id="edit_order_id">
                        <div class="mb-3">
                            <label for="newOrderStatus" class="form-label">New Status</label>
                            <select wire:model="newOrderStatus" class="form-control" id="newOrderStatus" required>
                                <option value="">Select Status</option>
                                <option value="pending">Pending</option>
                                <option value="processing">Processing</option>
                                <option value="completed">Completed</option>
                                <option value="cancelled">Cancelled</option>
                            </select>
                            @error('newOrderStatus') <div class="text-danger text-xs mt-1">{{ $message }}</div> @enderror
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Update Status</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Change Payment Status Modal -->
    <div class="modal fade" id="changePaymentStatusModal" tabindex="-1" aria-labelledby="changePaymentStatusModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="changePaymentStatusModalLabel">Change Payment Status</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form wire:submit.prevent="updatePaymentStatus">
                        <input type="hidden" wire:model="selectedOrderId" id="edit_payment_order_id">
                        <div class="mb-3">
                            <label for="newPaymentStatus" class="form-label">New Payment Status</label>
                            <select wire:model="newPaymentStatus" class="form-control" id="newPaymentStatus" required>
                                <option value="">Select Status</option>
                                <option value="pending">Pending</option>
                                <option value="success">Success</option>
                                <option value="failed">Failed</option>
                            </select>
                            @error('newPaymentStatus') <div class="text-danger text-xs mt-1">{{ $message }}</div> @enderror
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Update Payment Status</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>



@push('scripts')
<script>
    // Handle order status change modal
    document.addEventListener('DOMContentLoaded', function() {
        const changeOrderStatusModal = document.getElementById('changeOrderStatusModal');
        const editOrderIdField = document.getElementById('edit_order_id');
        const newOrderStatusSelect = document.getElementById('newOrderStatus');

        // When modal is shown, populate the hidden field and set current status
        changeOrderStatusModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const orderId = button.getAttribute('data-order-id');
            const orderStatus = button.getAttribute('data-order-status');

            // Set the order ID in the hidden field
            editOrderIdField.value = orderId;

            // Set the current status in the dropdown
            newOrderStatusSelect.value = orderStatus;

            // Trigger Livewire to update the model
            editOrderIdField.dispatchEvent(new Event('input', {
                bubbles: true
            }));
            newOrderStatusSelect.dispatchEvent(new Event('change', {
                bubbles: true
            }));
        });

        // Reset form when modal is hidden
        changeOrderStatusModal.addEventListener('hidden.bs.modal', function() {
            editOrderIdField.value = '';
            newOrderStatusSelect.value = '';
        });
    });

    // Handle payment status change modal
    document.addEventListener('DOMContentLoaded', function() {
        const changePaymentStatusModal = document.getElementById('changePaymentStatusModal');
        const editPaymentOrderIdField = document.getElementById('edit_payment_order_id');
        const newPaymentStatusSelect = document.getElementById('newPaymentStatus');

        // When modal is shown, populate the hidden field and set current status
        changePaymentStatusModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const orderId = button.getAttribute('data-order-id');
            const paymentStatus = button.getAttribute('data-payment-status');

            // Set the order ID in the hidden field
            editPaymentOrderIdField.value = orderId;

            // Set the current status in the dropdown
            newPaymentStatusSelect.value = paymentStatus;

            // Trigger Livewire to update the model
            editPaymentOrderIdField.dispatchEvent(new Event('input', {
                bubbles: true
            }));
            newPaymentStatusSelect.dispatchEvent(new Event('change', {
                bubbles: true
            }));
        });

        // Reset form when modal is hidden
        changePaymentStatusModal.addEventListener('hidden.bs.modal', function() {
            editPaymentOrderIdField.value = '';
            newPaymentStatusSelect.value = '';
        });
    });
</script>
@endpush