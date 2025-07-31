<div class="content-wrapper">
    <!-- Header -->
    <div class="card mb-4">
        <div class="card-body d-flex justify-content-between align-items-center">
            <div>
                <h1 class="h4 mb-0">Subscription Plans</h1>
                <p class="text-muted mb-0">Choose the perfect plan for your needs</p>
            </div>
        </div>
    </div>

    <!-- Plan Type Toggle -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="d-flex justify-content-center">
                <div class="btn-group" role="group">
                    <button wire:click="switchType('worker')" 
                            type="button" 
                            class="btn {{ $selectedType === 'worker' ? 'btn-primary' : 'btn-outline-primary' }}">
                        <i class="ri-user-line me-2"></i>Worker Plans
                    </button>
                    <button wire:click="switchType('taskmaster')" 
                            type="button" 
                            class="btn {{ $selectedType === 'taskmaster' ? 'btn-primary' : 'btn-outline-primary' }}">
                        <i class="ri-briefcase-line me-2"></i>Taskmaster Plans
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Plans Grid -->
    <div class="row g-4">
        @forelse($filteredPlans as $plan)
        <div class="col-lg-4 col-md-6">
            <div class="card h-100 {{ $plan['type'] === 'worker' ? 'border-primary' : 'border-success' }} position-relative">
                @if(in_array($plan['id'], $activeSubscriptionPlanIds))
                    <div class="position-absolute top-0 start-0 w-100">
                        <div class="badge bg-success rounded-0 rounded-top-start">
                            <i class="ri-check-line me-1"></i>Active Subscription
                        </div>
                    </div>
                @endif
                
                <div class="card-body d-flex flex-column">
                    <div class="text-center mb-4">
                        <h5 class="card-title mb-2">{{ $plan['name'] }}</h5>
                        <p class="text-muted mb-3">{{ $plan['description'] }}</p>
                        
                        <div class="mb-3">
                            <span class="display-6 fw-bold text-primary">
                                {{ auth()->user()->country->currency_symbol }}{{ number_format($plan['monthly_price'], 2) }}
                            </span>
                            <span class="text-muted">/month</span>
                        </div>
                    </div>

                    <div class="mb-4 flex-grow-1">
                        <h6 class="fw-semibold mb-3">Features:</h6>
                        <ul class="list-unstyled">
                            @foreach($plan['features'] as $feature)
                                <li class="mb-2">
                                    <i class="ri-check-line text-success me-2"></i>
                                    <span class="small">{{ $feature }}</span>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <div class="mt-auto">
                        <button wire:click="choosePlan('{{ $plan['slug'] }}')" 
                                class="btn btn-primary w-100">
                            <i class="ri-{{ in_array($plan['id'], $activeSubscriptionPlanIds) ? 'refresh' : 'add' }}-line me-1"></i>
                            {{ in_array($plan['id'], $activeSubscriptionPlanIds) ? 'Extend Subscription' : 'Choose Plan' }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="card">
                <div class="card-body text-center py-5">
                    <i class="ri-inbox-line display-4 text-muted mb-3"></i>
                    <h5 class="text-muted">No plans available</h5>
                    <p class="text-muted">No subscription plans are currently available for this type.</p>
                </div>
            </div>
        </div>
        @endforelse
    </div>

    <!-- Subscription Modal -->
    @if($showModal && $selectedPlan)
    <div class="modal fade show" style="display: block;" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="ri-vip-crown-line me-2"></i>Subscribe to {{ $selectedPlan['name'] }}
                    </h5>
                    <button type="button" class="btn-close" wire:click="$set('showModal', false)"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card border-primary">
                                <div class="card-header bg-primary text-white">
                                    <h6 class="card-title mb-0">
                                        <i class="ri-information-line me-2"></i>Plan Details
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="text-center mb-3">
                                        <span class="display-6 fw-bold text-primary">
                                            {{ auth()->user()->country->currency_symbol }}{{ number_format($selectedPlan['monthly_price'], 2) }}
                                        </span>
                                        <span class="text-muted">/month</span>
                                    </div>
                                    
                                    <h6 class="fw-semibold mb-3">Features:</h6>
                                    <ul class="list-unstyled">
                                        @foreach($selectedPlan['features'] as $feature)
                                            <li class="mb-2">
                                                <i class="ri-check-line text-success me-2"></i>
                                                <span class="small">{{ $feature }}</span>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="card-title mb-0">
                                        <i class="ri-settings-line me-2"></i>Subscription Options
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label for="duration" class="form-label">Duration (months)</label>
                                        <input type="number" 
                                               min="1" 
                                               max="24" 
                                               wire:model.live="selectedDuration" 
                                               id="duration" 
                                               class="form-control" />
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label">Payment Method</label>
                                        <div class="d-flex flex-column gap-2">
                                            <div class="form-check">
                                                <input type="radio"
                                                       wire:model="paymentMethod"
                                                       value="wallet"
                                                       class="form-check-input"
                                                       id="payment_wallet"
                                                       @if($walletBalance < $totalAmount) disabled @endif
                                                />
                                                <label class="form-check-label" for="payment_wallet">
                                                    <i class="ri-wallet-line me-2"></i>
                                                    Wallet ({{ auth()->user()->country->currency_symbol }} {{ number_format($walletBalance, 2) }})
                                                    @if($walletBalance < $totalAmount)
                                                        <span class="badge bg-danger ms-1">Insufficient balance</span>
                                                    @endif
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input type="radio" 
                                                       wire:model="paymentMethod" 
                                                       value="gateway" 
                                                       class="form-check-input" 
                                                       id="payment_gateway" />
                                                <label class="form-check-label" for="payment_gateway">
                                                    <i class="ri-bank-card-line me-2"></i>
                                                    Pay with Card/Bank (Gateway)
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    @if($paymentMethod === 'wallet' && $walletBalance < $totalAmount)
                                        <div class="alert alert-warning" role="alert">
                                            <i class="ri-error-warning-line me-2"></i>
                                            Insufficient wallet balance for this subscription.
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Payment Summary -->
                    <div class="card mt-3">
                        <div class="card-header">
                            <h6 class="card-title mb-0">
                                <i class="ri-calculator-line me-2"></i>Payment Summary
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>Subtotal:</span>
                                        <span>{{ auth()->user()->country->currency_symbol }} {{ number_format($subtotal, 2) }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between mb-2">
                                        <span>Tax ({{ $tax_rate }}%):</span>
                                        <span>{{ auth()->user()->country->currency_symbol }} {{ number_format($tax, 2) }}</span>
                                    </div>
                                    <hr>
                                    <div class="d-flex justify-content-between fw-bold">
                                        <span>Total:</span>
                                        <span class="text-primary">{{ auth()->user()->country->currency_symbol }} {{ number_format($totalAmount, 2) }}</span>
                                    </div>
                                </div>
                                <div class="col-md-4 d-flex align-items-center">
                                    <button wire:click="subscribe"
                                            class="btn btn-primary w-100"
                                            @if($paymentMethod === 'wallet' && $walletBalance < $totalAmount) disabled @endif>
                                        <i class="ri-check-line me-1"></i>
                                        Subscribe & Pay
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-backdrop fade show"></div>
    </div>
    @endif
</div>

@push('styles')
<style>
    .card {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }

    .btn-group .btn {
        border-radius: 0;
    }

    .btn-group .btn:first-child {
        border-top-left-radius: 0.375rem;
        border-bottom-left-radius: 0.375rem;
    }

    .btn-group .btn:last-child {
        border-top-right-radius: 0.375rem;
        border-bottom-right-radius: 0.375rem;
    }
</style>
@endpush
