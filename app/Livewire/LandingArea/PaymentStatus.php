<?php

namespace App\Livewire\LandingArea;

use Livewire\Component;
use App\Models\Payment;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.landing')]
class PaymentStatus extends Component
{
    public $payment;
    public $order;
    public $status;
    public $message;
    public $icon;
    public $color;
    public $showRetry = false;
    public $showMenu = false;

    public function mount(Payment $payment)
    {
        $this->payment = $payment;
        $this->order = $payment->order;
        $this->setStatusProperties();
    }

    public function setStatusProperties()
    {
        switch ($this->payment->status) {
            case 'success':
            case 'completed':
                $this->status = 'Payment Successful';
                $this->message = 'Your payment has been processed successfully! We will contact you shortly to confirm your order details and delivery arrangements.';
                $this->icon = 'fas fa-check-circle';
                $this->color = 'success';
                $this->showRetry = false;
                $this->showMenu = false;
                break;
                
            case 'failed':
            case 'declined':
                $this->status = 'Payment Failed';
                $this->message = 'Unfortunately, your payment could not be processed. Please try again or contact our support team if the problem persists.';
                $this->icon = 'fas fa-times-circle';
                $this->color = 'danger';
                $this->showRetry = true;
                $this->showMenu = true;
                break;
                
            case 'cancelled':
                $this->status = 'Payment Cancelled';
                $this->message = 'Your payment was cancelled. You can try again or return to our menu to place a new order.';
                $this->icon = 'fas fa-ban';
                $this->color = 'warning';
                $this->showRetry = false;
                $this->showMenu = true;
                break;
                
            case 'pending':
                $this->status = 'Payment Pending';
                $this->message = 'Your payment is being processed. Please wait while we confirm your transaction.';
                $this->icon = 'fas fa-clock';
                $this->color = 'info';
                $this->showRetry = false;
                $this->showMenu = false;
                break;
                
            default:
                $this->status = 'Payment Status Unknown';
                $this->message = 'We could not determine the status of your payment. Please contact our support team for assistance.';
                $this->icon = 'fas fa-question-circle';
                $this->color = 'secondary';
                $this->showRetry = true;
                $this->showMenu = true;
                break;
        }
    }

    public function retryPayment()
    {
        // Redirect to checkout to retry payment
        return redirect()->route('checkout');
    }

    public function goToMenu()
    {
        return redirect()->route('index') . '#menu';
    }

    public function contactSupport()
    {
        // You can implement contact support functionality
        return redirect()->route('contact');
    }

    public function render()
    {
        return view('livewire.landing-area.payment-status');
    }
}
