<?php

namespace App\Livewire\DashboardArea;

use App\Models\Payment;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Auth;


class Transactions extends Component
{
    use WithPagination;

    public function render()
    {
        $user = Auth::user();
        $payments = Payment::with(['order.items.orderable'])
            ->where('user_id', $user->id)
            ->orderByDesc('id')
            ->paginate(10);

        return view('livewire.dashboard-area.transactions',[
            'payments' => $payments
        ]);
    }
} 