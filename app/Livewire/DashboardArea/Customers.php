<?php

namespace App\Livewire\DashboardArea;

use App\Models\Order;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;

class Customers extends Component
{
    use WithPagination;

    public $stats = [];

    public function mount()
    {
        $this->loadStats();
    }

    public function loadStats()
    {
        $this->stats = [
            'customers' => Order::distinct('phone')->count('phone'),
            'states' => Order::distinct('state')->count('state'),
            'cities' => Order::distinct('city')->count('city'),
            'places' => Order::distinct('address')->count('address'),
        ];
    }

    public function getCustomers()
    {
        return Order::select([
                'name',
                'email',
                'phone',
                'address',
                DB::raw('COUNT(*) as order_count'),
                DB::raw('SUM(total_amount) as total_spent'),
                DB::raw('MAX(created_at) as last_order_date')
            ])
            ->whereNotNull('phone')
            ->groupBy('phone', 'name', 'email', 'address')
            ->orderBy('last_order_date', 'desc')
            ->paginate(10);
    }

    public function render()
    {
        return view('livewire.dashboard-area.customers', [
            'customers' => $this->getCustomers()
        ]);
    }
}
