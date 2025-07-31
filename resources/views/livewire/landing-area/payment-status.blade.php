<div class="payment-status-page">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8 col-md-10">
                <div class="payment-status-card">
                    <!-- Status Icon and Header -->
                    <div class="status-header text-center mb-5">
                        <div class="status-icon-wrapper mb-4">
                            <i class="{{ $icon }} status-icon status-{{ $color }}"></i>
                        </div>
                        <h1 class="status-title mb-3">{{ $status }}</h1>
                        <p class="status-message">{{ $message }}</p>
                    </div>

                    <!-- Order Details -->
                    @if($order)
                    <div class="order-details mb-5">
                        <div class="card border-0 shadow-sm">
                            <div class="card-header bg-light">
                                <h5 class="mb-0">
                                    <i class="fas fa-receipt me-2"></i>
                                    Order Details
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="detail-item mb-3">
                                            <span class="detail-label">Order Number:</span>
                                            <span class="detail-value fw-bold">#{{ $order->id }}</span>
                                        </div>
                                        <div class="detail-item mb-3">
                                            <span class="detail-label">Customer:</span>
                                            <span class="detail-value">{{ $order->name }}</span>
                                        </div>
                                        <div class="detail-item mb-3">
                                            <span class="detail-label">Email:</span>
                                            <span class="detail-value">{{ $order->email }}</span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="detail-item mb-3">
                                            <span class="detail-label">Total Amount:</span>
                                            <span class="detail-value fw-bold text-primary">₦{{ number_format($order->total_amount, 2) }}</span>
                                        </div>
                                        <div class="detail-item mb-3">
                                            <span class="detail-label">Payment Status:</span>
                                            <span class="badge bg-{{ $color }}">{{ ucfirst($payment->status) }}</span>
                                        </div>
                                        <div class="detail-item mb-3">
                                            <span class="detail-label">Date:</span>
                                            <span class="detail-value">{{ $order->created_at->format('M d, Y H:i') }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Action Buttons -->
                    <div class="action-buttons text-center">
                        @if($showRetry)
                        <div class="row justify-content-center">
                            <div class="col-md-4 mb-3">
                                <button class="btn btn-primary btn-lg w-100" wire:click="retryPayment">
                                    <i class="fas fa-redo me-2"></i>
                                    Retry Payment
                                </button>
                            </div>
                        </div>
                        @endif

                        @if($showMenu)
                        <div class="row justify-content-center">
                            <div class="col-md-4 mb-3">
                                <a href="{{ route('index') }}#menu" class="btn btn-outline-primary btn-lg w-100">
                                    <i class="fas fa-utensils me-2"></i>
                                    Back to Menu
                                </a>
                            </div>
                        </div>
                        @endif

                        @if($payment->status === 'success' || $payment->status === 'completed')
                        <div class="row justify-content-center">
                            <div class="col-md-6 mb-3">
                                <a href="{{ route('index') }}" class="btn btn-primary btn-lg w-100">
                                    <i class="fas fa-home me-2"></i>
                                    Go to Homepage
                                </a>
                            </div>
                        </div>
                        @endif

                        <div class="row justify-content-center">
                            <div class="col-md-6">
                                <a href="https://wa.me/2349058271973" target="_blank" class="btn btn-outline-success btn-lg w-100">
                                    <i class="fab fa-whatsapp me-2"></i>
                                    Chat with Chef
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Additional Information -->
                    @if($payment->status === 'success' || $payment->status === 'completed')
                    <div class="success-info mt-5">
                        <div class="alert alert-success border-0 shadow-sm">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-info-circle me-3 fs-4"></i>
                                <div>
                                    <h6 class="mb-1">What happens next?</h6>
                                    <p class="mb-0 small">
                                        Our team will review your order and contact you within 30 minutes to confirm your delivery details. 
                                        You'll receive updates via email and SMS throughout the process.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
.payment-status-page {
    min-height: 100vh;
    padding: 60px 0;
}

.payment-status-card {
    background: white;
    border-radius: 20px;
    padding: 40px;
    box-shadow: 0 20px 40px #d4a76a;
    position: relative;
    overflow: hidden;
}

.payment-status-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: #d4a76a;
}

.status-icon-wrapper {
    position: relative;
    display: inline-block;
}

.status-icon {
    font-size: 4rem;
    padding: 30px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(10px);
    border: 3px solid;
    animation: pulse 2s infinite;
}

.status-success {
    color: #28a745;
    border-color: #28a745;
}

.status-danger {
    color: #dc3545;
    border-color: #dc3545;
}

.status-warning {
    color: #ffc107;
    border-color: #ffc107;
}

.status-info {
    color: #17a2b8;
    border-color: #17a2b8;
}

.status-secondary {
    color: #6c757d;
    border-color: #6c757d;
}

.status-title {
    font-size: 2.5rem;
    font-weight: 700;
    color: #2c3e50;
    margin-bottom: 1rem;
}

.status-message {
    font-size: 1.1rem;
    color: #6c757d;
    line-height: 1.6;
    max-width: 600px;
    margin: 0 auto;
}

.order-details .card {
    border-radius: 15px;
    overflow: hidden;
}

.order-details .card-header {
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    border-bottom: 1px solid #dee2e6;
    padding: 1.5rem;
}

.detail-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.5rem 0;
    border-bottom: 1px solid #f8f9fa;
}

.detail-item:last-child {
    border-bottom: none;
}

.detail-label {
    font-weight: 600;
    color: #495057;
}

.detail-value {
    color: #2c3e50;
}

.action-buttons .btn {
    border-radius: 12px;
    font-weight: 600;
    padding: 12px 24px;
    transition: all 0.3s ease;
    box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
}

.action-buttons .btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
}

.success-info .alert {
    border-radius: 15px;
    background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
    border: none;
}

@keyframes pulse {
    0% {
        transform: scale(1);
        box-shadow: 0 0 0 0 rgba(102, 126, 234, 0.7);
    }
    70% {
        transform: scale(1.05);
        box-shadow: 0 0 0 10px rgba(102, 126, 234, 0);
    }
    100% {
        transform: scale(1);
        box-shadow: 0 0 0 0 rgba(102, 126, 234, 0);
    }
}

/* Responsive Design */
@media (max-width: 768px) {
    .payment-status-card {
        padding: 30px 20px;
        margin: 20px;
    }
    
    .status-title {
        font-size: 2rem;
    }
    
    .status-icon {
        font-size: 3rem;
        padding: 20px;
    }
    
    .action-buttons .btn {
        margin-bottom: 10px;
    }
}

/* Loading Animation */
.status-icon.loading {
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}
</style>
@endpush
