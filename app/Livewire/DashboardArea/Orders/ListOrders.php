<?php

namespace App\Livewire\DashboardArea\Orders;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Order;
use App\Models\OrderItem;
use Carbon\Carbon;

class ListOrders extends Component
{
    use WithPagination;

    // Search and filter properties
    public $search = '';
    public $dateFilter = '';
    public $deliveryTypeFilter = '';
    public $deliveryDateFilter = '';
    public $statusFilter = '';
    public $locationFilter = '';

    // Status change modal properties
    public $selectedOrderId = null;
    public $newOrderStatus = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingDateFilter()
    {
        $this->resetPage();
    }

    public function updatingDeliveryTypeFilter()
    {
        $this->resetPage();
    }

    public function updatingDeliveryDateFilter()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function updatingLocationFilter()
    {
        $this->resetPage();
    }

    public function updateOrderStatus()
    {
        $this->validate([
            'selectedOrderId' => 'required|exists:orders,id',
            'newOrderStatus' => 'required|in:pending,processing,completed,cancelled',
        ]);

        $order = Order::find($this->selectedOrderId);
        if ($order) {
            $order->update(['status' => $this->newOrderStatus]);
            session()->flash('message', 'Order status updated successfully!');
            
            // Reset modal properties
            $this->selectedOrderId = null;
            $this->newOrderStatus = '';
        }
        $this->dispatch('closeModal',['modalId' => 'changeStatusModal']);
    }

    public function render()
    {
        $query = Order::with(['orderItems.meal', 'orderItems.food', 'orderItems.foodSize', 'shipmentRoute.location']);

        // Apply search filter
        if ($this->search) {
            $query->where(function($q) {
                $q->where('id', 'like', '%' . $this->search . '%')
                  ->orWhere('name', 'like', '%' . $this->search . '%')
                  ->orWhere('email', 'like', '%' . $this->search . '%')
                  ->orWhere('phone', 'like', '%' . $this->search . '%')
                  ->orWhereHas('orderItems.meal', function($mealQuery) {
                      $mealQuery->where('name', 'like', '%' . $this->search . '%');
                  });
            });
        }

        // Apply date filter
        if ($this->dateFilter) {
            $query->whereDate('created_at', $this->dateFilter);
        }

        // Apply delivery type filter
        if ($this->deliveryTypeFilter) {
            $query->where('delivery_type', $this->deliveryTypeFilter);
        }

        // Apply delivery date filter
        if ($this->deliveryDateFilter) {
            $query->whereDate('delivery_date', $this->deliveryDateFilter);
        }

        // Apply status filter (based on delivery date)
        if ($this->statusFilter) {
            $today = Carbon::today();
            switch ($this->statusFilter) {
                case 'pending':
                    $query->where('delivery_date', '>=', $today);
                    break;
                case 'completed':
                    $query->where('delivery_date', '<', $today);
                    break;
                case 'today':
                    $query->whereDate('delivery_date', $today);
                    break;
            }
        }

        // Apply location filter
        if ($this->locationFilter) {
            $query->whereHas('shipmentRoute', function($q) {
                $q->where('location_id', $this->locationFilter);
            });
        }

        $orders = $query->orderBy('created_at', 'desc')->paginate(15);

        // Calculate statistics
        $totalOrders = Order::count();
        $todayOrders = Order::whereDate('created_at', Carbon::today())->count();
        $pickupOrders = Order::where('delivery_type', 'pickup')->count();
        $deliveryOrders = Order::where('delivery_type', 'delivery')->count();
        
        // Calculate total revenue
        $totalRevenue = OrderItem::sum('amount');
        $todayRevenue = OrderItem::whereHas('order', function($q) {
            $q->whereDate('created_at', Carbon::today());
        })->sum('amount');

        // Get locations for filter dropdown
        $locations = \App\Models\Location::orderBy('name')->get();

        return view('livewire.dashboard-area.orders.list-orders', [
            'orders' => $orders,
            'totalOrders' => $totalOrders,
            'todayOrders' => $todayOrders,
            'pickupOrders' => $pickupOrders,
            'deliveryOrders' => $deliveryOrders,
            'totalRevenue' => $totalRevenue,
            'todayRevenue' => $todayRevenue,
            'locations' => $locations,
        ]);
    }
}
