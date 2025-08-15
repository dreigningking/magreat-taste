<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class NewOrderNotification extends Notification
{
    use Queueable;

    public $order;

    /**
     * Create a new notification instance.
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
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
        $orderItems = $this->order->orderItems;
        $totalItems = $orderItems->sum('quantity');
        
        return (new MailMessage)
            ->subject('Order Confirmation - #' . $this->order->id)
            ->view('emails.new-order', [
                'order' => $this->order,
                'orderItems' => $orderItems,
                'totalItems' => $totalItems
            ]);
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray($notifiable): array
    {
        return [
            'order_id' => $this->order->id,
            'message' => 'Your order #' . $this->order->id . ' has been confirmed.'
        ];
    }
}
