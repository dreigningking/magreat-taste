<?php

namespace App\Livewire\DashboardArea\Orders;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Payment;
use App\Models\Order;
use Carbon\Carbon;

class Payments extends Component
{
    use WithPagination;

    // Filter properties
    public $dateFrom = '';
    public $dateTo = '';
    public $status = '';
    public $method = '';
    public $search = '';

    // Sort properties
    public $sortBy = 'created_at';
    public $sortDirection = 'desc';

    // Modal properties
    public $selectedPaymentId = null;
    public $selectedOrderDetails = null;

    protected $queryString = [
        'dateFrom' => ['except' => ''],
        'dateTo' => ['except' => ''],
        'status' => ['except' => ''],
        'method' => ['except' => ''],
        'search' => ['except' => ''],
        'sortBy' => ['except' => 'created_at'],
        'sortDirection' => ['except' => 'desc'],
    ];

    public function mount()
    {
        // Set default date range to last 30 days
        $this->dateFrom = Carbon::now()->subDays(30)->format('Y-m-d');
        $this->dateTo = Carbon::now()->format('Y-m-d');
    }

    public function updatedDateFrom()
    {
        $this->resetPage();
    }

    public function updatedDateTo()
    {
        $this->resetPage();
    }

    public function updatedStatus()
    {
        $this->resetPage();
    }

    public function updatedMethod()
    {
        $this->resetPage();
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortBy === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function clearFilters()
    {
        $this->reset(['dateFrom', 'dateTo', 'status', 'method', 'search']);
        $this->dateFrom = Carbon::now()->subDays(30)->format('Y-m-d');
        $this->dateTo = Carbon::now()->format('Y-m-d');
        $this->resetPage();
    }

    public function getPaymentStatusColor($status)
    {
        $colors = [
            'pending' => 'warning',
            'success' => 'success',
            'failed' => 'danger'
        ];
        return $colors[$status] ?? 'secondary';
    }

    public function getPaymentMethodColor($method)
    {
        $colors = [
            'online' => 'primary',
            'cash' => 'success',
            'card' => 'info'
        ];
        return $colors[$method] ?? 'secondary';
    }

    public function render()
    {
        $query = Payment::with(['order.orderItems.meal', 'order.orderItems.food', 'order.orderItems.foodSize'])
            ->when($this->dateFrom, function ($query) {
                $query->whereDate('created_at', '>=', $this->dateFrom);
            })
            ->when($this->dateTo, function ($query) {
                $query->whereDate('created_at', '<=', $this->dateTo);
            })
            ->when($this->status, function ($query) {
                $query->where('status', $this->status);
            })
            ->when($this->method, function ($query) {
                $query->where('method', $this->method);
            })
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('reference', 'like', '%' . $this->search . '%')
                      ->orWhereHas('order', function ($orderQuery) {
                          $orderQuery->where('name', 'like', '%' . $this->search . '%')
                                   ->orWhere('email', 'like', '%' . $this->search . '%')
                                   ->orWhere('phone', 'like', '%' . $this->search . '%');
                      });
                });
            });

        // Apply sorting
        $query->orderBy($this->sortBy, $this->sortDirection);

        $payments = $query->paginate(15);

        // Calculate statistics
        $totalPayments = $query->count();
        $totalAmount = $query->sum('amount');
        $successfulPayments = $query->where('status', 'success')->count();
        $pendingPayments = $query->where('status', 'pending')->count();
        $failedPayments = $query->where('status', 'failed')->count();

        return view('livewire.dashboard-area.orders.payments', [
            'payments' => $payments,
            'totalPayments' => $totalPayments,
            'totalAmount' => $totalAmount,
            'successfulPayments' => $successfulPayments,
            'pendingPayments' => $pendingPayments,
            'failedPayments' => $failedPayments,
        ]);
    }
}
