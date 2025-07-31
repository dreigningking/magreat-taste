<div class="content-wrapper">
    @if($walletsAreFrozen)
    <div class="alert alert-warning alert-dismissible fade show" role="alert">
        <i class="ri-error-warning-line me-2"></i>
        <strong>Wallet Operations Disabled:</strong> {{ $frozenReason }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <!-- Header -->
    <div class="card mb-4">
        <div class="card-body d-flex justify-content-between align-items-center">
            <div>
                <h1 class="h4 mb-0">
                    @switch($activeTab)
                    @case('withdrawals')
                    Withdrawals
                    @break
                    @case('exchanges')
                    Currency Exchanges
                    @break
                    @case('settlements')
                    @default
                    Earnings & Settlements
                    @endswitch
                </h1>
                <p class="text-muted mb-0">
                    @switch($activeTab)
                    @case('withdrawals')
                    Manage your withdrawal requests and track payment status
                    @break
                    @case('exchanges')
                    View your currency exchange history and rates
                    @break
                    @case('settlements')
                    @default
                    Track your earnings from completed tasks and referrals
                    @endswitch
                </p>
            </div>
        </div>
    </div>

    <!-- Wallet Cards -->
    <div class="row g-3 mb-4">
        @foreach($wallets as $wallet)
        <div class="col-md-6 col-lg-4">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <h6 class="text-muted mb-1">{{ $wallet->currency }}</h6>
                            <h3 class="fw-bold mb-0">{{ number_format($wallet->balance, 2) }}</h3>
                        </div>
                        <div class="d-flex gap-1">
                            @if($wallet->balance > 0)
                            @if($wallet->currency === Auth::user()->country->currency)
                            <button wire:click="openWithdrawModal('{{ $wallet->currency }}')"
                                class="btn btn-outline-secondary btn-sm"
                                @if($walletsAreFrozen || $wallet->is_frozen) disabled @endif>
                                <i class="ri-bank-card-line me-1"></i> Withdraw
                            </button>
                            @endif
                            @if(count($wallets) > 1 && $wallet->currency !== Auth::user()->country->currency)
                            <button wire:click="openExchangeModal('{{ $wallet->currency }}')"
                                class="btn btn-outline-primary btn-sm"
                                @if($walletsAreFrozen || $wallet->is_frozen) disabled @endif>
                                <i class="ri-exchange-dollar-line me-1"></i> Exchange
                            </button>
                            @endif
                            @endif
                        </div>
                    </div>
                    <div class="border-top pt-3">
                        <div class="row g-2">
                            <div class="col-6">
                                <small class="text-muted">Total Earned</small>
                                <div class="fw-semibold">{{ number_format($wallet->total_earned, 2) }}</div>
                            </div>
                            <div class="col-6">
                                <small class="text-muted">Total Withdrawn</small>
                                <div class="fw-semibold">{{ number_format($wallet->total_withdrawn, 2) }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Navigation Tabs -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="btn-group w-100" role="group">
                <a href="{{ route('earnings.settlements') }}" class="btn {{ $activeTab === 'settlements' ? 'btn-primary' : 'btn-outline-secondary' }}">
                    Settlements
                </a>
                <a href="{{ route('earnings.withdrawals') }}" class="btn {{ $activeTab === 'withdrawals' ? 'btn-primary' : 'btn-outline-secondary' }}">
                    Withdrawals
                </a>
                <a href="{{ route('earnings.exchanges') }}" class="btn {{ $activeTab === 'exchanges' ? 'btn-primary' : 'btn-outline-secondary' }}">
                    Exchanges
                </a>
            </div>
        </div>
    </div>

    <!-- Content Tabs -->
    <div class="card">
        <div class="card-body">
            <!-- Settlements Tab -->
            @if($activeTab === 'settlements')
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Source</th>
                            <th>Title</th>
                            <th>Currency</th>
                            <th>Amount</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($settlements as $settlement)
                        <tr>
                            <td>
                                @if ($settlement->settlementable_type === 'App\\Models\\Task')
                                <span class="badge bg-primary">Task</span>
                                @elseif ($settlement->settlementable_type === 'App\\Models\\Referral')
                                @if ($settlement->settlementable->type === 'internal')
                                <span class="badge bg-info">Task Invitation</span>
                                @else
                                <span class="badge bg-success">Referral</span>
                                @endif
                                @else
                                <span class="badge bg-secondary">-</span>
                                @endif
                            </td>
                            <td>
                                @if ($settlement->settlementable_type === 'App\\Models\\Task' && $settlement->settlementable)
                                <div class="fw-medium">{{ $settlement->settlementable->title }}</div>
                                @elseif ($settlement->settlementable_type === 'App\\Models\\Referral' && $settlement->settlementable)
                                <div class="fw-medium">{{ $settlement->settlementable->email }}</div>
                                @else
                                <div class="text-muted">-</div>
                                @endif
                            </td>
                            <td>
                                <span class="fw-semibold">{{ $settlement->currency }}</span>
                            </td>
                            <td>
                                <span class="fw-bold text-success">{{ number_format($settlement->amount, 2) }}</span>
                            </td>
                            <td>
                                <small class="text-muted">{{ $settlement->created_at->format('M d, Y H:i') }}</small>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">
                                <i class="ri-money-dollar-circle-line display-4 mb-2"></i>
                                <p>No settlements found</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-between align-items-center mt-4">
                <div class="text-muted small">
                    Showing <span class="fw-semibold">{{ $settlements->firstItem() }}</span> to <span class="fw-semibold">{{ $settlements->lastItem() }}</span> of <span class="fw-semibold">{{ $settlements->total() }}</span> results
                </div>
                <div>
                    {{ $settlements->links() }}
                </div>
            </div>
            @endif

            <!-- Withdrawals Tab -->
            @if($activeTab === 'withdrawals')
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Reference</th>
                            <th>Currency</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($withdrawals as $withdrawal)
                        <tr>
                            <td>
                                <span class="fw-semibold">{{ $withdrawal->reference }}</span>
                            </td>
                            <td>
                                <span class="fw-semibold">{{ $withdrawal->currency }}</span>
                            </td>
                            <td>
                                <span class="fw-bold text-primary">{{ number_format($withdrawal->amount, 2) }}</span>
                            </td>
                            <td>
                                @if($withdrawal->status === 'paid')
                                <span class="badge bg-success">Paid</span>
                                @elseif($withdrawal->status === 'approved')
                                <span class="badge bg-info">Approved</span>
                                @elseif($withdrawal->status === 'pending')
                                <span class="badge bg-warning">Pending</span>
                                @elseif($withdrawal->status === 'rejected')
                                <span class="badge bg-danger">Rejected</span>
                                @else
                                <span class="badge bg-secondary">{{ ucfirst($withdrawal->status) }}</span>
                                @endif
                            </td>
                            <td>
                                <small class="text-muted">{{ $withdrawal->created_at->format('M d, Y H:i') }}</small>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">
                                <i class="ri-bank-card-line display-4 mb-2"></i>
                                <p>No withdrawals found</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-between align-items-center mt-4">
                <div class="text-muted small">
                    Showing <span class="fw-semibold">{{ $withdrawals->firstItem() }}</span> to <span class="fw-semibold">{{ $withdrawals->lastItem() }}</span> of <span class="fw-semibold">{{ $withdrawals->total() }}</span> results
                </div>
                <div>
                    {{ $withdrawals->links() }}
                </div>
            </div>
            @endif

            <!-- Exchanges Tab -->
            @if($activeTab === 'exchanges')
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Reference</th>
                            <th>From</th>
                            <th>To</th>
                            <th>Rate</th>
                            <th>Status</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($exchanges as $exchange)
                        <tr>
                            <td>
                                <span class="fw-semibold">{{ $exchange->reference }}</span>
                            </td>
                            <td>
                                <div class="fw-semibold">{{ number_format($exchange->base_amount, 2) }}</div>
                                <small class="text-muted">{{ $exchange->base_currency }}</small>
                            </td>
                            <td>
                                <div class="fw-semibold">{{ number_format($exchange->target_amount, 2) }}</div>
                                <small class="text-muted">{{ $exchange->target_currency }}</small>
                            </td>
                            <td>
                                <span class="fw-semibold">{{ number_format($exchange->exchange_rate, 4) }}</span>
                            </td>
                            <td>
                                @if($exchange->status === 'completed')
                                <span class="badge bg-success">Completed</span>
                                @elseif($exchange->status === 'pending')
                                <span class="badge bg-warning">Pending</span>
                                @elseif($exchange->status === 'failed')
                                <span class="badge bg-danger">Failed</span>
                                @else
                                <span class="badge bg-secondary">{{ ucfirst($exchange->status) }}</span>
                                @endif
                            </td>
                            <td>
                                <small class="text-muted">{{ $exchange->created_at->format('M d, Y H:i') }}</small>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">
                                <i class="ri-exchange-dollar-line display-4 mb-2"></i>
                                <p>No exchanges found</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-between align-items-center mt-4">
                <div class="text-muted small">
                    Showing <span class="fw-semibold">{{ $exchanges->firstItem() }}</span> to <span class="fw-semibold">{{ $exchanges->lastItem() }}</span> of <span class="fw-semibold">{{ $exchanges->total() }}</span> results
                </div>
                <div>
                    {{ $exchanges->links() }}
                </div>
            </div>
            @endif
        </div>
    </div>

    @if (session()->has('message'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('message') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    @if (session()->has('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <!-- Withdrawal Modal -->
    <div class="modal fade" id="withdrawModal" tabindex="-1" aria-labelledby="withdrawModalLabel" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="withdrawModalLabel">Withdraw {{ $withdrawCurrency }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="withdrawAmount" class="form-label">Amount</label>
                        <div class="input-group">
                            <span class="input-group-text">{{ $withdrawCurrency }}</span>
                            <input type="number"
                                id="withdrawAmount"
                                wire:model.live="withdrawAmount"
                                step="0.01"
                                class="form-control"
                                placeholder="0.00">
                        </div>
                        @error('withdrawAmount') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                    </div>

                    <div class="alert alert-info">
                        <div class="d-flex justify-content-between mb-2">
                            <span>Available Balance:</span>
                            <span class="fw-semibold">{{ number_format($availableBalance, 2) }} {{ $withdrawCurrency }}</span>
                        </div>

                        @if($withdrawAmount > 0 && !$errors->has('withdrawAmount'))
                        <hr>
                        <div class="d-flex justify-content-between mb-1">
                            <span>Withdrawal Fee:</span>
                            <span class="text-danger">{{ number_format($withdrawalFee, 2) }} {{ $withdrawCurrency }}</span>
                        </div>
                        <div class="d-flex justify-content-between fw-bold">
                            <span>You will receive:</span>
                            <span class="text-primary">{{ number_format($netAmount, 2) }} {{ $withdrawCurrency }}</span>
                        </div>
                        @endif
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" wire:click="submitWithdrawal" class="btn btn-primary">Submit Withdrawal</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Exchange Modal -->
    <div class="modal fade" id="exchangeModal" tabindex="-1" aria-labelledby="exchangeModalLabel" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exchangeModalLabel">Exchange Currency</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <span>From Currency:</span>
                            <span class="fw-semibold">{{ $fromCurrency }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-1">
                            <span>Available Balance:</span>
                            <span class="fw-semibold">{{ number_format($exchangeAvailableBalance, 2) }} {{ $fromCurrency }}</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>To Currency:</span>
                            <span class="fw-semibold">{{ $toCurrency }}</span>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="amount" class="form-label">Amount to Exchange</label>
                        <div class="input-group">
                            <span class="input-group-text">{{ $fromCurrency }}</span>
                            <input type="number"
                                id="amount"
                                wire:model.live="amount"
                                step="0.01"
                                class="form-control"
                                placeholder="0.00">
                        </div>
                        @error('amount') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                    </div>

                    @if($targetAmount > 0)
                    <div class="alert alert-success">
                        <div class="d-flex justify-content-between mb-1">
                            <span>Exchange Rate:</span>
                            <span class="fw-semibold">1 {{ $fromCurrency }} ≈ {{ number_format($exchangeRate, 4) }} {{ $toCurrency }}</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between fw-bold">
                            <span>You will receive:</span>
                            <span class="text-success">{{ number_format($targetAmount, 2) }} {{ $toCurrency }}</span>
                        </div>
                    </div>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" wire:click="executeExchange" class="btn btn-primary" @if($targetAmount <=0) disabled @endif>Confirm Exchange</button>
                </div>
            </div>
        </div>
    </div>
</div>



@push('scripts')
<script>
    document.addEventListener('livewire:init', () => {
        Livewire.on('openWithdrawModal', () => {
            new bootstrap.Modal(document.getElementById('withdrawModal')).show();
        });

        Livewire.on('closeWithdrawModal', () => {
            bootstrap.Modal.getInstance(document.getElementById('withdrawModal')).hide();
        });

        Livewire.on('openExchangeModal', () => {
            new bootstrap.Modal(document.getElementById('exchangeModal')).show();
        });

        Livewire.on('closeExchangeModal', () => {
            bootstrap.Modal.getInstance(document.getElementById('exchangeModal')).hide();
        });
    });
</script>
@endpush
