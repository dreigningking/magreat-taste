<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class ContactNotification extends Notification
{
    use Queueable;

    public $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('New Contact Form Submission: ' . $this->data['subject'])
            ->greeting('Hello Admin,')
            ->line('You have received a new contact form submission on Wonegig:')
            ->line('**Name:** ' . $this->data['name'])
            ->line('**Email:** ' . $this->data['email'])
            ->line('**Subject:** ' . $this->data['subject'])
            ->line('**Message:**')
            ->line($this->data['message'])
            ->line('---')
            ->line('This message was sent via the Wonegig contact form.');
    }
} 