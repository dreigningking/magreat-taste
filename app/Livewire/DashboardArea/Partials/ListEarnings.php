<?php

namespace App\Livewire\DashboardArea\Earnings;

use App\Models\Wallet;
use App\Models\Setting;
use Livewire\Component;
use App\Models\Exchange;
use App\Models\Settlement;
use App\Models\Withdrawal;
use Livewire\WithPagination;
use App\Http\Traits\HelperTrait;
use Illuminate\Support\Facades\Auth;


class ListEarnings extends Component
{
    use WithPagination;
    use HelperTrait;

    // Tab management
    public $activeTab = 'settlements';

    // Withdrawal modal
    public $showWithdrawModal = false;
    public $withdrawCurrency = '';
    public $withdrawAmount = '';
    public $availableBalance = 0;
    public $minWithdrawal = 10;
    public $maxWithdrawal = 10000;
    public $withdrawalFee = 0;
    public $netAmount = 0;
    public $withdrawalCharges = [];

    // Exchange modal
    public $showExchangeModal = false;
    public $fromCurrency = '';
    public $toCurrency = '';
    public $amount = '';
    public $exchangeRate = 0;
    public $targetAmount = 0;
    public $exchangeAvailableBalance = 0;

    // Wallet freeze logic
    public $walletsAreFrozen = false;
    public $frozenReason = '';

    protected function rules()
    {
        return [
            'withdrawAmount' => [
                'required',
                'numeric',
                'min:' . $this->minWithdrawal,
                'max:' . $this->maxWithdrawal,
            ],
        'fromCurrency' => 'required',
        'toCurrency' => 'required|different:fromCurrency',
            'amount' => [
                'required',
                'numeric',
                'min:0.01',
                function ($attribute, $value, $fail) {
                    if ($value > $this->exchangeAvailableBalance) {
                        $fail('The exchange amount cannot exceed your available balance.');
                    }
                },
            ],
        ];
    }

    protected $messages = [
        'withdrawAmount.min' => 'The minimum withdrawal amount is :min.',
        'withdrawAmount.max' => 'The maximum withdrawal amount is :max.',
    ];

    public function mount()
    {
        // Set active tab based on the current route
        $routeName = request()->route()->getName();
        switch ($routeName) {
            case 'earnings.withdrawals':
                $this->activeTab = 'withdrawals';
                break;
            case 'earnings.exchanges':
                $this->activeTab = 'exchanges';
                break;
            case 'earnings.settlements':
            default:
                $this->activeTab = 'settlements';
                break;
        }

        /** @var \App\Models\User $user */
        $user = Auth::user();
        $this->toCurrency = $user->country->currency;

        // Wallet freeze logic
        $globalFreeze = Setting::firstWhere('name', 'freeze_wallets_globally')->value('value');
        $countrySetting = $user->country->setting ?? null;
        $walletStatus = $countrySetting ? $countrySetting->wallet_status : null;
        $anyWalletFrozen = $user->wallets()->where('is_frozen', 1)->exists();

        // Add verification check
        if (
            ($globalFreeze == 1) ||
            ($walletStatus === 'disabled' || is_null($walletStatus)) ||
            $anyWalletFrozen ||
            !$user->is_verified
        ) {
            $this->walletsAreFrozen = true;

            if ($globalFreeze == 1) {
                $this->frozenReason = 'Wallet operations are temporarily disabled for all users.';
            } elseif ($walletStatus === 'disabled' || is_null($walletStatus)) {
                $this->frozenReason = 'Wallet operations are currently disabled in your country.';
            } elseif ($anyWalletFrozen) {
                $this->frozenReason = 'One or more of your wallets are frozen. Please contact support.';
            } elseif (!$user->is_verified) {
                $this->frozenReason = 'Please complete all required verifications to enable withdrawals and exchanges.';
            }
        }

        // Load withdrawal charges from country settings
        if ($countrySetting) {
            if ($countrySetting->withdrawal_charges) {
                $this->withdrawalCharges = $countrySetting->withdrawal_charges;
            } else {
                // Default charges if not set
                $this->withdrawalCharges = [
                    'percentage' => 1,
                    'fixed' => 50,
                    'cap' => 1000
                ];
            }
            $this->minWithdrawal = $countrySetting->min_withdrawal ?? 10;
            $max = $countrySetting->max_withdrawal ?? 10000;

            // Check for active subscription and apply multiplier
            $activeSubscription = $user->activeSubscriptions()->latest('expires_at')->first();
            if ($activeSubscription && isset($activeSubscription->plan->withdrawal_maximum_multiplier)) {
                $multiplier = $activeSubscription->plan->withdrawal_maximum_multiplier;
                if (is_numeric($multiplier) && $multiplier > 0) {
                    $max = $max * $multiplier;
                }
            }

            $this->maxWithdrawal = $max;
        }
    }

    public function updatedAmount()
    {
        $this->validateOnly('amount');
        $this->calculateExchange();
    }

    public function updated($propertyName)
    {
        if (in_array($propertyName, ['fromCurrency'])) {
            $this->calculateExchange();
        }
    }

    private function calculateExchange()
    {
        if (empty($this->fromCurrency) || empty($this->amount) || !is_numeric($this->amount) || $this->amount <= 0) {
            $this->exchangeRate = 0;
            $this->targetAmount = 0;
            return;
        }

        try {
            /** @var \App\Models\User $user */
            $user = Auth::user();
            
            $fromRate = $this->getMarkedUpRate($this->fromCurrency, $user);
            $toRate = $this->getMarkedUpRate($this->toCurrency, $user);

            if ($this->fromCurrency === 'USD') {
                $this->exchangeRate = $toRate;
            } elseif ($this->toCurrency === 'USD') {
                $this->exchangeRate = 1 / $fromRate;
            } else {
                $this->exchangeRate = $toRate / $fromRate;
            }
            
            $this->targetAmount = $this->amount * $this->exchangeRate;
        } catch (\Exception $e) {
            $this->exchangeRate = 0;
            $this->targetAmount = 0;
            // Optionally, flash a message to the user
            session()->flash('error', $e->getMessage());
        }
    }

    // Withdrawal methods
    public function openWithdrawModal($currency)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $this->withdrawCurrency = $currency;
        $wallet = $user->wallets()->where('currency', $currency)->first();
        $this->availableBalance = $wallet ? $wallet->balance : 0;
        $this->showWithdrawModal = true;
    }

    public function closeWithdrawModal()
    {
        $this->showWithdrawModal = false;
        $this->reset(['withdrawCurrency', 'withdrawAmount', 'availableBalance', 'withdrawalFee', 'netAmount']);
    }

    public function updatedWithdrawAmount()
    {
        $this->validateOnly('withdrawAmount');

        if ($this->withdrawAmount > 0) {
            // Calculate withdrawal fee based on country settings
            $percentage = $this->withdrawalCharges['percentage'] ?? 0;
            $fixed = $this->withdrawalCharges['fixed'] ?? 0;
            $cap = $this->withdrawalCharges['cap'] ?? null;

            $percentageFee = ($this->withdrawAmount * $percentage) / 100;
            $totalFee = $percentageFee + $fixed;
            
            // Apply cap if it's set and total fee exceeds it
            if (!is_null($cap) && $totalFee > $cap) {
                $totalFee = $cap;
            }
            
            $this->withdrawalFee = $totalFee;
            $this->netAmount = $this->withdrawAmount - $this->withdrawalFee;
        } else {
            $this->withdrawalFee = 0;
            $this->netAmount = 0;
        }
    }

    public function submitWithdrawal()
    {
        $this->validate();

        /** @var \App\Models\User $user */
        $user = Auth::user();
        $wallet = $user->wallets()->where('currency', $this->withdrawCurrency)->first();
        
        if (!$wallet) {
            session()->flash('error', 'Wallet not found.');
            return;
        }

        // Check if withdrawal amount plus fees exceeds available balance
        if ($wallet->balance < $this->withdrawAmount) {
            session()->flash('error', 'Insufficient balance for withdrawal.');
            return;
        }

        // Double-check that the total amount (withdrawal + fees) doesn't exceed balance
        $totalAmount = $this->withdrawAmount + $this->withdrawalFee;
        if ($wallet->balance < $totalAmount) {
            session()->flash('error', 'Insufficient balance to cover withdrawal amount and fees.');
            return;
        }

        // Create withdrawal record
        $withdrawal = Withdrawal::create([
            'user_id' => $user->id,
            'currency' => $this->withdrawCurrency,
            'amount' => $this->withdrawAmount,
            'reference' => 'WD' . time() . $user->id,
            'status' => 'pending',
            'meta' => [
                'fee' => $this->withdrawalFee,
                'net_amount' => $this->netAmount,
                'charges_breakdown' => [
                    'percentage' => $this->withdrawalCharges['percentage'],
                    'fixed' => $this->withdrawalCharges['fixed'],
                    'cap' => $this->withdrawalCharges['cap'],
                    'percentage_amount' => ($this->withdrawAmount * $this->withdrawalCharges['percentage']) / 100,
                    'total_fee' => $this->withdrawalFee
                ]
            ]
        ]);

        // Deduct from wallet
        $wallet->decrement('balance', $this->withdrawAmount);
        $wallet->increment('total_withdrawn', $this->withdrawAmount);

        $this->closeWithdrawModal();
        session()->flash('message', 'Withdrawal request submitted successfully.');
    }

    // Exchange methods
    public function openExchangeModal($currency)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $this->fromCurrency = $currency;
        $wallet = $user->wallets()->where('currency', $currency)->first();
        $this->exchangeAvailableBalance = $wallet ? $wallet->balance : 0;
        $this->showExchangeModal = true;
    }

    public function closeExchangeModal()
    {
        $this->showExchangeModal = false;
        $this->reset(['fromCurrency', 'amount', 'exchangeRate', 'targetAmount', 'exchangeAvailableBalance']);
    }

    public function getExchangeAmount()
    {
        return $this->targetAmount;
    }

    public function executeExchange()
    {
        $this->validate([
            'fromCurrency' => 'required',
            'toCurrency' => 'required|different:fromCurrency',
            'amount' => [
                'required',
                'numeric',
                'min:0.01',
                'max:' . $this->exchangeAvailableBalance
            ],
        ]);

        /** @var \App\Models\User $user */
        $user = Auth::user();
        
        // Ensure user can only exchange to their home currency
        if ($this->toCurrency !== $user->country->currency) {
            session()->flash('error', 'You can only exchange to your home currency.');
            return;
        }

        $fromWallet = $user->wallets()->where('currency', $this->fromCurrency)->first();
        $toWallet = $user->wallets()->where('currency', $this->toCurrency)->first();

        if (!$fromWallet || !$toWallet) {
            session()->flash('error', 'Invalid wallet selection.');
            return;
        }

        if ($fromWallet->balance < $this->amount) {
            session()->flash('error', 'Insufficient balance.');
            return;
        }

        // Re-calculate before executing to ensure data is fresh
        $this->calculateExchange();
        if ($this->targetAmount <= 0) {
            session()->flash('error', 'Could not calculate exchange amount. Please try again.');
            return;
        }

        // Create exchange record
        $exchange = Exchange::create([
            'user_id' => $user->id,
            'base_currency' => $this->fromCurrency,
            'target_currency' => $this->toCurrency,
            'exchange_rate' => $this->exchangeRate,
            'base_amount' => $this->amount,
            'target_amount' => $this->targetAmount,
            'base_wallet_id' => $fromWallet->id,
            'target_wallet_id' => $toWallet->id,
            'status' => 'completed',
            'reference' => 'EX' . time() . $user->id
        ]);

        // Perform the exchange
        $fromWallet->decrement('balance', $this->amount);
        $toWallet->increment('balance', $this->targetAmount);

        $this->closeExchangeModal();
        session()->flash('message', 'Currency exchange completed successfully.');
    }

    public function render()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $wallets = $user->wallets;

        // Eager load totals to avoid N+1 problem
        $totalEarned = Settlement::where('user_id', $user->id)
            ->groupBy('currency')
            ->selectRaw('currency, sum(amount) as total')
            ->pluck('total', 'currency');

        $totalWithdrawn = Withdrawal::where('user_id', $user->id)
            ->whereNotNull('paid_at')
            ->groupBy('currency')
            ->selectRaw('currency, sum(amount) as total')
            ->pluck('total', 'currency');

        foreach ($wallets as $wallet) {
            $wallet->total_earned = $totalEarned[$wallet->currency] ?? 0;
            $wallet->total_withdrawn = $totalWithdrawn[$wallet->currency] ?? 0;
        }

        $settlements = Settlement::where('user_id', $user->id)
            ->with(['settlementable'])
            ->latest()
            ->paginate(10);

        $withdrawals = Withdrawal::where('user_id', $user->id)
            ->latest()
            ->paginate(10);

        $exchanges = Exchange::where('user_id', $user->id)
            ->latest()
            ->paginate(10);

        return view('livewire.dashboard-area.earnings.list-earnings', [
            'wallets' => $wallets,
            'settlements' => $settlements,
            'withdrawals' => $withdrawals,
            'exchanges' => $exchanges,
            'availableCurrencies' => $wallets->pluck('currency')->toArray()
        ]);
    }
}
