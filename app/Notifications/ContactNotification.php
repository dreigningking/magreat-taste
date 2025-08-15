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
        $mailMessage = (new MailMessage)
            ->subject('New Contact Form Submission: ' . ucfirst($this->data['contact_type']))
            ->greeting('Hello Admin,')
            ->line('You have received a new contact form submission on MaGreat Taste:')
            ->line('**Name:** ' . $this->data['name'])
            ->line('**Email:** ' . $this->data['email'])
            ->line('**Contact Type:** ' . ucfirst($this->data['contact_type']))
            ->line('**Message:**')
            ->line($this->data['message']);

        // Add contact type specific information
        switch ($this->data['contact_type']) {
            case 'inquiry':
                if (isset($this->data['inquiry_subject'])) {
                    $mailMessage->line('**Subject:** ' . $this->data['inquiry_subject']);
                }
                if (isset($this->data['inquiry_type'])) {
                    $mailMessage->line('**Inquiry Type:** ' . $this->data['inquiry_type']);
                }
                break;
                
            case 'booking':
                if (isset($this->data['event_type'])) {
                    $mailMessage->line('**Event Type:** ' . $this->data['event_type']);
                }
                if (isset($this->data['guest_count'])) {
                    $mailMessage->line('**Guest Count:** ' . $this->data['guest_count']);
                }
                if (isset($this->data['event_location'])) {
                    $mailMessage->line('**Event Location:** ' . $this->data['event_location']);
                }
                if (isset($this->data['service_type'])) {
                    $mailMessage->line('**Service Type:** ' . $this->data['service_type']);
                }
                break;
                
            case 'feedback':
                if (isset($this->data['feedback_type'])) {
                    $mailMessage->line('**Feedback Type:** ' . $this->data['feedback_type']);
                }
                if (isset($this->data['rating'])) {
                    $mailMessage->line('**Rating:** ' . $this->data['rating']);
                }
                break;
                
            case 'review':
                if (isset($this->data['dish_name'])) {
                    $mailMessage->line('**Dish/Service:** ' . $this->data['dish_name']);
                }
                if (isset($this->data['review_rating'])) {
                    $mailMessage->line('**Rating:** ' . $this->data['review_rating']);
                }
                if (isset($this->data['publish_review'])) {
                    $mailMessage->line('**Publish Review:** ' . ($this->data['publish_review'] ? 'Yes' : 'No'));
                }
                break;
        }

        $mailMessage->line('---')
            ->line('This message was sent via the MaGreat Taste contact form.');

        return $mailMessage;
    }
} 