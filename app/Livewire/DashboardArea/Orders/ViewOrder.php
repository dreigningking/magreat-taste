<?php

namespace App\Livewire\DashboardArea\Orders;

use Livewire\Component;
use App\Models\Order;
use App\Models\Meal;
use App\Models\Food;
use App\Models\FoodSize;

class ViewOrder extends Component
{
    public Order $order;
    public $selectedOrderId = null;
    public $newOrderStatus = '';
    public $newPaymentStatus = '';

    public function mount($order)
    {
        $this->order = $order;
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
            $this->order->refresh();
            session()->flash('message', 'Order status updated successfully!');
            
            // Reset modal properties
            $this->selectedOrderId = null;
            $this->newOrderStatus = '';
        }
    }

    public function updatePaymentStatus()
    {
        $this->validate([
            'selectedOrderId' => 'required|exists:orders,id',
            'newPaymentStatus' => 'required|in:success,pending,failed',
        ]);

        $payment = $this->order->payment;
        if ($payment) {
            $payment->update(['status' => $this->newPaymentStatus]);
            $this->order->refresh();
            session()->flash('message', 'Payment status updated successfully!');
            
            // Reset modal properties
            $this->selectedOrderId = null;
            $this->newPaymentStatus = '';
        }
    }

    public function printOrder()
    {
        return redirect()->route('orders.print', $this->order);
    }

    public function render()
    {
        return view('livewire.dashboard-area.orders.view-order', [
            'order' => $this->order,
        ]);
    }
}
