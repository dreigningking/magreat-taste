<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class OrderStatusUpdateNotification extends Notification
{
    use Queueable;

    public $order;
    public $oldStatus;
    public $newStatus;

    /**
     * Create a new notification instance.
     */
    public function __construct(Order $order, $oldStatus = null, $newStatus = null)
    {
        $this->order = $order;
        $this->oldStatus = $oldStatus;
        $this->newStatus = $newStatus;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via($notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable): MailMessage
    {
        $statusMessages = [
            'pending' => 'Your order is pending and being reviewed.',
            'processing' => 'Your order is being processed and prepared.',
            'ready' => 'Your order is ready for pickup/delivery!',
            'shipped' => 'Your order has been shipped and is on its way.',
            'delivered' => 'Your order has been delivered successfully!',
            'cancelled' => 'Your order has been cancelled.',
            'refunded' => 'Your order has been refunded.'
        ];

        $statusMessage = $statusMessages[$this->newStatus] ?? 'Your order status has been updated.';
        
        return (new MailMessage)
            ->subject('Order Status Update - #' . $this->order->id)
            ->view('emails.order-status-update', [
                'order' => $this->order,
                'oldStatus' => $this->oldStatus,
                'newStatus' => $this->newStatus,
                'statusMessage' => $statusMessage
            ]);
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray($notifiable): array
    {
        return [
            'order_id' => $this->order->id,
            'old_status' => $this->oldStatus,
            'new_status' => $this->newStatus,
            'message' => 'Order #' . $this->order->id . ' status updated to ' . $this->newStatus
        ];
    }
}
