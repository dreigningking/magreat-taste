<div class="content-wrapper">
    @if(session()->has('message'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fa fa-check-circle me-2"></i>{{ session('message') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row mb-4">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <h3 class="font-weight-bold">Orders Management</h3>
            <div>
                <a href="{{ route('orders.create') }}" class="btn btn-primary me-2">
                    <i class="fa fa-plus me-2"></i>Create Order
                </a>
                <button class="btn btn-outline-primary" onclick="window.print()">
                    <i class="fa fa-print me-2"></i>Print Orders
                </button>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-2">
            <div class="card border-primary">
                <div class="card-body">
                    <h6 class="text-muted">Total Orders</h6>
                    <h3 class="mb-0">{{ $totalOrders }}</h3>
                    <small class="text-muted">All time</small>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card border-success">
                <div class="card-body">
                    <h6 class="text-muted">Today's Orders</h6>
                    <h3 class="mb-0">{{ $todayOrders }}</h3>
                    <small class="text-muted">New orders</small>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card border-warning">
                <div class="card-body">
                    <h6 class="text-muted">Pickup Orders</h6>
                    <h3 class="mb-0">{{ $pickupOrders }}</h3>
                    <small class="text-muted">Self pickup</small>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card border-info">
                <div class="card-body">
                    <h6 class="text-muted">Delivery Orders</h6>
                    <h3 class="mb-0">{{ $deliveryOrders }}</h3>
                    <small class="text-muted">Home delivery</small>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card border-danger">
                <div class="card-body">
                    <h6 class="text-muted">Total Revenue</h6>
                    <h3 class="mb-0">₦{{ number_format($totalRevenue, 2) }}</h3>
                    <small class="text-muted">All time</small>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card border-secondary">
                <div class="card-body">
                    <h6 class="text-muted">Today's Revenue</h6>
                    <h3 class="mb-0">₦{{ number_format($todayRevenue, 2) }}</h3>
                    <small class="text-muted">Today only</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters and Search -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <label class="form-label">Search Orders</label>
                    <input type="text" wire:model.live="search" class="form-control" placeholder="Order ID, Name, Email, Phone, Meal...">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Order Date</label>
                    <input type="date" wire:model.live="dateFilter" class="form-control">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Delivery Type</label>
                    <select wire:model.live="deliveryTypeFilter" class="form-control">
                        <option value="">All Types</option>
                        <option value="pickup">Pickup</option>
                        <option value="delivery">Delivery</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Delivery Date</label>
                    <input type="date" wire:model.live="deliveryDateFilter" class="form-control">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Status</label>
                    <select wire:model.live="statusFilter" class="form-control">
                        <option value="">All Status</option>
                        <option value="pending">Pending</option>
                        <option value="today">Today's Delivery</option>
                        <option value="completed">Completed</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Branch</label>
                    <select wire:model.live="locationFilter" class="form-control">
                        <option value="">All Branches</option>
                        @foreach($locations as $location)
                            <option value="{{ $location->id }}">{{ $location->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-1">
                    <label class="form-label">&nbsp;</label>
                    <button class="btn btn-outline-secondary w-100" wire:click="$set('search', ''); $set('dateFilter', ''); $set('deliveryTypeFilter', ''); $set('deliveryDateFilter', ''); $set('statusFilter', ''); $set('locationFilter', '')">
                        <i class="fa fa-refresh"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Orders Table -->
    <div class="card">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="fa fa-shopping-cart me-2"></i>All Orders</h5>
            <span class="badge bg-light text-dark">{{ $orders->total() }} Orders</span>
        </div>
        <div class="card-body">
            @if($orders->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Order ID</th>
                                <th>Customer Info</th>
                                <th>Delivery Details</th>
                                <th>Branch</th>
                                <th>Order Items</th>
                                <th>Total Amount</th>
                                <th>Status</th>
                                <th>Created</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($orders as $order)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <strong class="text-primary">#{{ $order->id }}</strong>
                                </td>
                                <td>
                                    <div class="d-flex flex-column">
                                        <strong>{{ $order->name }}</strong>
                                        <small class="text-muted">{{ $order->email }}</small>
                                        <small class="text-muted">{{ $order->phone }}</small>
                                        <small class="text-muted">IP: {{ $order->ip }}</small>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex flex-column">
                                        <span class="mb-2 badge bg-{{ $order->delivery_color }}">
                                            {{ ucfirst($order->delivery_details) }}
                                        </span>
                                        
                                        @if($order->delivery_type === 'delivery' && $order->address)
                                            <small class="text-muted">{{ Str::limit($order->address, 50) }}</small>
                                            @if($order->city)
                                                <small class="text-muted">{{ $order->city }}, {{ $order->state }}</small>
                                            @endif
                                        @endif
                                        
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex flex-column">
                                        @if($order->shipmentRoute && $order->shipmentRoute->location)
                                            <strong class="text-primary">{{ $order->shipmentRoute->location->name }}</strong>
                                            <small class="text-muted">{{ $order->shipmentRoute->location->city->name ?? '' }}</small>
                                        @elseif($order->delivery_type === 'pickup')
                                            <span class="badge bg-info">Pickup</span>
                                            <small class="text-muted">No branch assigned</small>
                                        @else
                                            <span class="text-muted">N/A</span>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex flex-column">
                                        @php
                                            $uniqueMeals = $order->orderItems->groupBy('meal_id')->count();
                                            $totalFoods = $order->orderItems->count();
                                            $totalQuantity = $order->orderItems->sum('quantity');
                                        @endphp
                                        <span class="badge bg-success mb-2">{{ $uniqueMeals }} meals</span>
                                        <span class="badge bg-info mb-2">{{ $totalFoods }} items</span>
                                        <small class="text-muted">Qty: {{ $totalQuantity }}</small>
                                    </div>
                                </td>
                                <td>
                                    <strong>₦{{ number_format($order->total, 2) }}</strong>
                                </td>
                                <td>
                                    @php
                                        $statusColor = $order->status_color;
                                    @endphp
                                    <span class="badge bg-{{ $statusColor }}">{{ ucfirst($order->status) }}</span>
                                </td>
                                <td>
                                    <small class="text-muted">{{ $order->created_at->format('M d, Y') }}</small>
                                    <br>
                                    <small class="text-muted">{{ $order->created_at->format('H:i') }}</small>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('orders.view', $order) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                        <a href="{{ route('orders.edit', $order) }}" class="btn btn-sm btn-outline-secondary">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                        <button class="btn btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#changeStatusModal" data-order-id="{{ $order->id }}" data-order-status="{{ $order->status }}">
                                            <i class="fa fa-exchange"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-between align-items-center mt-4">
                    <div class="text-muted">
                        Showing {{ $orders->firstItem() }} to {{ $orders->lastItem() }} of {{ $orders->total() }} orders
                    </div>
                    <div>
                        {{ $orders->links() }}
                    </div>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fa fa-shopping-cart text-muted" style="font-size: 4rem;"></i>
                    <h5 class="text-muted mt-3">No Orders Found</h5>
                    <p class="text-muted">No orders match your current filters.</p>
                    <button class="btn btn-outline-secondary" wire:click="$set('search', ''); $set('dateFilter', ''); $set('deliveryTypeFilter', ''); $set('deliveryDateFilter', ''); $set('statusFilter', ''); $set('locationFilter', '')">
                        <i class="fa fa-refresh me-2"></i>Clear Filters
                    </button>
                </div>
            @endif
        </div>
    </div>

    <!-- Change Status Modal -->
<div class="modal fade" id="changeStatusModal" tabindex="-1" aria-labelledby="changeStatusModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="changeStatusModalLabel">Change Order Status</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form wire:submit.prevent="updateOrderStatus">
                    <input type="hidden" wire:model="selectedOrderId" id="edit_order_id">
                    <div class="mb-3">
                        <label for="newStatus" class="form-label">New Status</label>
                        <select wire:model="newOrderStatus" class="form-control" id="newStatus" required>
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
</div>



@push('scripts')
<script>
    // Handle status change modal
    document.addEventListener('DOMContentLoaded', function() {
        const changeStatusModal = document.getElementById('changeStatusModal');
        const editOrderIdField = document.getElementById('edit_order_id');
        const newStatusSelect = document.getElementById('newStatus');
        
        // When modal is shown, populate the hidden field and set current status
        changeStatusModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const orderId = button.getAttribute('data-order-id');
            const orderStatus = button.getAttribute('data-order-status');
            
            // Set the order ID in the hidden field
            editOrderIdField.value = orderId;
            
            // Set the current status in the dropdown
            newStatusSelect.value = orderStatus;
            
            // Trigger Livewire to update the model
            editOrderIdField.dispatchEvent(new Event('input', { bubbles: true }));
            newStatusSelect.dispatchEvent(new Event('change', { bubbles: true }));
        });
        
        // Reset form when modal is hidden
        changeStatusModal.addEventListener('hidden.bs.modal', function() {
            editOrderIdField.value = '';
            newStatusSelect.value = '';
        });
    });

    function markAsCompleted(orderId) {
        if (confirm('Mark this order as completed?')) {
            // You can implement this functionality
            console.log('Marking order as completed:', orderId);
        }
    }

    function printOrder(orderId) {
        // You can implement print functionality
        console.log('Printing order:', orderId);
        window.open(`/orders/${orderId}/print`, '_blank');
    }
</script>
@endpush
