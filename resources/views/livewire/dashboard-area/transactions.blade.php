<div class="content-wrapper">
    <!-- Header -->
    <div class="card mb-4">
        <div class="card-body d-flex justify-content-between align-items-center">
            <div>
                <h1 class="h4 mb-0">Payment Transactions</h1>
                <p class="text-muted mb-0">View and manage your payment history and transaction details</p>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card h-100">
                <div class="card-body d-flex flex-column justify-content-between">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h6 class="mb-0">Total Payments</h6>
                        <span class="bg-primary bg-opacity-10 rounded-circle p-2"><i class="ri-money-dollar-circle-line text-primary"></i></span>
                    </div>
                    <h3 class="fw-bold mb-0">{{ $payments->total() }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card h-100">
                <div class="card-body d-flex flex-column justify-content-between">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h6 class="mb-0">Successful</h6>
                        <span class="bg-success bg-opacity-10 rounded-circle p-2"><i class="ri-check-double-line text-success"></i></span>
                    </div>
                    <h3 class="fw-bold mb-0">{{ $payments->where('status', 'success')->count() }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card h-100">
                <div class="card-body d-flex flex-column justify-content-between">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h6 class="mb-0">Failed</h6>
                        <span class="bg-danger bg-opacity-10 rounded-circle p-2"><i class="ri-close-circle-line text-danger"></i></span>
                    </div>
                    <h3 class="fw-bold mb-0">{{ $payments->where('status', 'failed')->count() }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card h-100">
                <div class="card-body d-flex flex-column justify-content-between">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h6 class="mb-0">Total Amount</h6>
                        <span class="bg-info bg-opacity-10 rounded-circle p-2"><i class="ri-calculator-line text-info"></i></span>
                    </div>
                    <h3 class="fw-bold mb-0">{{ number_format($payments->sum('amount'), 2) }}</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Transactions Table -->
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">Transaction History</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Reference</th>
                            <th>Currency</th>
                            <th>Amount</th>
                            <th>VAT</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Actions</th>
                    </tr>
                </thead>
                    <tbody>
                    @forelse($payments as $payment)
                        <tr>
                            <td>
                                <span class="fw-semibold">{{ $payment->reference }}</span>
                            </td>
                            <td>
                                <span class="badge bg-secondary">{{ $payment->currency }}</span>
                            </td>
                            <td>
                                <span class="fw-bold text-primary">{{ number_format($payment->amount, 2) }}</span>
                            </td>
                            <td>
                                <span class="text-muted">{{ number_format($payment->vat_value, 2) }}</span>
                            </td>
                            <td>
                                @if($payment->status === 'success')
                                <span class="badge bg-success">Success</span>
                                @elseif($payment->status === 'failed')
                                <span class="badge bg-danger">Failed</span>
                                @else
                                <span class="badge bg-secondary">{{ ucfirst($payment->status) }}</span>
                                @endif
                            </td>
                            <td>
                                <small class="text-muted">{{ $payment->created_at ? $payment->created_at->format('M d, Y H:i') : '-' }}</small>
                            </td>
                            <td>
                                <button class="btn btn-outline-primary btn-sm view-details-btn"
                                    data-bs-toggle="modal"
                                    data-bs-target="#transactionModal"
                                    data-reference="{{ $payment->reference }}"
                                    data-currency="{{ $payment->currency }}"
                                    data-amount="{{ number_format($payment->amount, 2) }}"
                                    data-vat="{{ number_format($payment->vat_value, 2) }}"
                                    data-status="{{ $payment->status }}"
                                    data-date="{{ $payment->created_at ? $payment->created_at->format('M d, Y H:i') : '-' }}"
                                    data-items="{{ json_encode($payment->order->orderItems ?? []) }}">
                                    <i class="ri-eye-line me-1"></i> View Details
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-4">
                                <i class="ri-money-dollar-circle-line display-4 mb-2"></i>
                                <p>No transactions found</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

            <div class="d-flex justify-content-between align-items-center mt-4">
                <div class="text-muted small">
                    Showing <span class="fw-semibold">{{ $payments->firstItem() }}</span> to <span class="fw-semibold">{{ $payments->lastItem() }}</span> of <span class="fw-semibold">{{ $payments->total() }}</span> results
                </div>
                <div>
            {{ $payments->links('vendor.pagination.bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>

    <!-- Transaction Details Modal -->
    <div class="modal fade" id="transactionModal" tabindex="-1" aria-labelledby="transactionModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="transactionModalLabel">
                        Transaction Details
                        <span class="text-muted" id="modalReference"></span>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Payment Information -->
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <div class="card h-100">
                                <div class="card-header">
                                    <h6 class="card-title mb-0">Payment Information</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row g-2">
                                        <div class="col-6">
                                            <small class="text-muted">Reference</small>
                                            <div class="fw-semibold" id="modalReferenceText"></div>
                                        </div>
                                        <div class="col-6">
                                            <small class="text-muted">Status</small>
                                            <div id="modalStatus"></div>
                                        </div>
                                        <div class="col-6">
                                            <small class="text-muted">Currency</small>
                                            <div class="fw-semibold" id="modalCurrency"></div>
                                        </div>
                                        <div class="col-6">
                                            <small class="text-muted">Date</small>
                                            <div class="fw-semibold" id="modalDate"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card h-100">
                                <div class="card-header">
                                    <h6 class="card-title mb-0">Amount Breakdown</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row g-2">
                                        <div class="col-6">
                                            <small class="text-muted">Amount</small>
                                            <div class="fw-bold text-primary" id="modalAmount"></div>
                                        </div>
                                        <div class="col-6">
                                            <small class="text-muted">VAT</small>
                                            <div class="fw-semibold" id="modalVat"></div>
                                        </div>
                                        <div class="col-12">
                                            <hr>
                                            <div class="d-flex justify-content-between">
                                                <span class="fw-bold">Total</span>
                                                <span class="fw-bold text-success" id="modalTotal"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Order Items -->
                    <div class="card" id="orderItemsCard" style="display: none;">
                        <div class="card-header">
                            <h6 class="card-title mb-0">Items Purchased</h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-sm" id="orderItemsTable">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Item Type</th>
                                            <th>Description</th>
                                            <th>Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody id="orderItemsBody">
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- No Items Alert -->
                    <div class="alert alert-info" id="noItemsAlert" style="display: none;">
                        <i class="ri-information-line me-2"></i>
                        No order items found for this payment.
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
    $(document).ready(function() {
        $('.view-details-btn').on('click', function() {
            // Get data from button attributes
            var reference = $(this).data('reference');
            var currency = $(this).data('currency');
            var amount = $(this).data('amount');
            var vat = $(this).data('vat');
            var status = $(this).data('status');
            var date = $(this).data('date');
            var items = $(this).data('items');

            // Calculate total
            var amountValue = parseFloat(amount.replace(/,/g, ''));
            var vatValue = parseFloat(vat.replace(/,/g, ''));
            var total = amountValue + vatValue;

            // Update modal content
            $('#modalReference').text('(' + reference + ')');
            $('#modalReferenceText').text(reference);
            $('#modalCurrency').text(currency);
            $('#modalAmount').text(amount);
            $('#modalVat').text(vat);
            $('#modalDate').text(date);
            $('#modalTotal').text(total.toFixed(2));

            // Update status badge
            var statusBadge = '';
            if (status === 'success') {
                statusBadge = '<span class="badge bg-success">Success</span>';
            } else if (status === 'failed') {
                statusBadge = '<span class="badge bg-danger">Failed</span>';
            } else {
                statusBadge = '<span class="badge bg-secondary">' + status.charAt(0).toUpperCase() + status.slice(1) + '</span>';
            }
            $('#modalStatus').html(statusBadge);

            // Handle order items
            if (items && items.length > 0) {
                $('#orderItemsCard').show();
                $('#noItemsAlert').hide();

                var itemsHtml = '';
                items.forEach(function(item) {
                    var itemType = '';
                    var description = '';

                    // Determine item type and description
                    if (item.orderable_type && item.orderable_type.includes('Task')) {
                        itemType = '<span class="badge bg-primary">Task</span>';
                        description = item.orderable ? item.orderable.title : 'N/A';
                    } else if (item.orderable_type && item.orderable_type.includes('TaskPromotion')) {
                        itemType = '<span class="badge bg-info">Promotion</span>';
                        description = item.orderable ? item.orderable.type : 'N/A';
                    } else if (item.orderable_type && item.orderable_type.includes('Subscription')) {
                        itemType = '<span class="badge bg-success">Subscription</span>';
                        description = item.orderable && item.orderable.plan ? item.orderable.plan.name : 'N/A';
                    } else {
                        itemType = '<span class="badge bg-secondary">' + (item.orderable_type ? item.orderable_type.split('\\').pop() : 'Unknown') + '</span>';
                        description = 'N/A';
                    }

                    itemsHtml += '<tr>' +
                        '<td>' + itemType + '</td>' +
                        '<td>' + description + '</td>' +
                        '<td><span class="fw-semibold">' + parseFloat(item.amount).toFixed(2) + '</span></td>' +
                        '</tr>';
                });

                $('#orderItemsBody').html(itemsHtml);
            } else {
                $('#orderItemsCard').hide();
                $('#noItemsAlert').show();
            }
        });
    });
</script>
@endpush
