<?php

namespace App\Livewire\LandingArea;

use Livewire\Component;
use App\Models\Contact;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Log;
use App\Notifications\ContactNotification;
use Livewire\Attributes\Layout;

class ContactSection extends Component
{
    public string $pageTitle = 'Contact Us | MaGreat Taste';
    public string $metaTitle = 'Contact MaGreat Taste - Catering Services';
    public string $metaDescription = "Get in touch with the MaGreat Taste team. We're here to help with support, partnerships, and general inquiries.";
    
    // Common fields
    public $contact_type = 'inquiry';
    public $name = '';
    public $email = '';
    public $phone = '';
    public $preferred_date = '';
    public $message = '';
    
    // Dynamic fields for inquiry
    public $inquiry_subject = '';
    public $inquiry_type = '';
    
    // Dynamic fields for booking
    public $event_type = '';
    public $guest_count = '';
    public $event_location = '';
    public $service_type = '';
    
    // Dynamic fields for feedback
    public $feedback_type = '';
    public $rating = '';
    
    // Dynamic fields for review
    public $dish_name = '';
    public $review_rating = '';
    public $publish_review = false;

    protected $rules = [
        'name' => 'required|string|max:100',
        'email' => 'required|email|max:100',
        'phone' => 'nullable|string|max:20',
        'preferred_date' => 'nullable|date|after_or_equal:today',
        'message' => 'required|string|max:2000',
        'contact_type' => 'required|in:inquiry,booking,feedback,review',
        
        // Inquiry validation
        'inquiry_subject' => 'required_if:contact_type,inquiry|string|max:150',
        'inquiry_type' => 'required_if:contact_type,inquiry|in:General Question,Pricing,Menu,Other',
        
        // Booking validation
        'event_type' => 'required_if:contact_type,booking|string|max:100',
        'guest_count' => 'required_if:contact_type,booking|integer|min:1',
        'event_location' => 'required_if:contact_type,booking|string|max:200',
        'service_type' => 'required_if:contact_type,booking|in:Full Catering,Drop-off,Personal Chef,Custom Menu',
        
        // Feedback validation
        'feedback_type' => 'required_if:contact_type,feedback|in:Suggestion,Complaint,Compliment,Improvement',
        'rating' => 'required_if:contact_type,feedback|in:5 Stars,4 Stars,3 Stars,2 Stars,1 Star',
        
        // Review validation
        'dish_name' => 'required_if:contact_type,review|string|max:100',
        'review_rating' => 'required_if:contact_type,review|in:5 Stars,4 Stars,3 Stars,2 Stars,1 Star',
        'publish_review' => 'boolean',
    ];

    protected $messages = [
        'inquiry_subject.required_if' => 'The subject field is required for inquiries.',
        'inquiry_type.required_if' => 'The inquiry type field is required for inquiries.',
        'event_type.required_if' => 'The event type field is required for bookings.',
        'guest_count.required_if' => 'The number of guests field is required for bookings.',
        'event_location.required_if' => 'The event location field is required for bookings.',
        'service_type.required_if' => 'The service type field is required for bookings.',
        'feedback_type.required_if' => 'The feedback type field is required for feedback.',
        'rating.required_if' => 'The rating field is required for feedback.',
        'dish_name.required_if' => 'The dish name field is required for reviews.',
        'review_rating.required_if' => 'The rating field is required for reviews.',
    ];

    public function updatedContactType()
    {
        // Reset dynamic fields when contact type changes
        $this->resetDynamicFields();
    }

    private function resetDynamicFields()
    {
        $this->inquiry_subject = '';
        $this->inquiry_type = '';
        $this->event_type = '';
        $this->guest_count = '';
        $this->event_location = '';
        $this->service_type = '';
        $this->feedback_type = '';
        $this->rating = '';
        $this->dish_name = '';
        $this->review_rating = '';
        $this->publish_review = false;
    }

    public function submitContactForm()
    {
        $this->validate();

        try {
            // Debug: Log the form data
            Log::info('Contact form submission', [
                'contact_type' => $this->contact_type,
                'name' => $this->name,
                'email' => $this->email,
                'phone' => $this->phone,
                'preferred_date' => $this->preferred_date,
                'message' => $this->message,
            ]);

            // Create contact record
            $contact = Contact::create([
                'name' => $this->name,
                'email' => $this->email,
                'phone' => $this->phone,
                'preferred_date' => $this->preferred_date,
                'message' => $this->message,
                'contact_type' => $this->contact_type,
                'status' => 'pending',
                
                // Inquiry fields
                'inquiry_subject' => $this->contact_type === 'inquiry' ? $this->inquiry_subject : null,
                'inquiry_type' => $this->contact_type === 'inquiry' ? $this->inquiry_type : null,
                
                // Booking fields
                'event_type' => $this->contact_type === 'booking' ? $this->event_type : null,
                'guest_count' => $this->contact_type === 'booking' ? (int) $this->guest_count : null,
                'event_location' => $this->contact_type === 'booking' ? $this->event_location : null,
                'service_type' => $this->contact_type === 'booking' ? $this->service_type : null,
                
                // Feedback fields
                'feedback_type' => $this->contact_type === 'feedback' ? $this->feedback_type : null,
                'rating' => $this->contact_type === 'feedback' ? $this->rating : null,
                
                // Review fields
                'dish_name' => $this->contact_type === 'review' ? $this->dish_name : null,
                'review_rating' => $this->contact_type === 'review' ? $this->review_rating : null,
                'publish_review' => $this->contact_type === 'review' ? $this->publish_review : false,
            ]);

            Log::info('Contact created successfully', ['contact_id' => $contact->id]);

            // Send notification email
            $contactEmail = config('app.contact_email', config('mail.from.address'));
            if ($contactEmail) {
                // Prepare notification data with all fields
                $notificationData = [
                    'name' => $this->name,
                    'email' => $this->email,
                    'contact_type' => $this->contact_type,
                    'message' => $this->message,
                    'contact_id' => $contact->id,
                ];

                // Add contact type specific data
                switch ($this->contact_type) {
                    case 'inquiry':
                        $notificationData['inquiry_subject'] = $this->inquiry_subject;
                        $notificationData['inquiry_type'] = $this->inquiry_type;
                        break;
                    case 'booking':
                        $notificationData['event_type'] = $this->event_type;
                        $notificationData['guest_count'] = $this->guest_count;
                        $notificationData['event_location'] = $this->event_location;
                        $notificationData['service_type'] = $this->service_type;
                        break;
                    case 'feedback':
                        $notificationData['feedback_type'] = $this->feedback_type;
                        $notificationData['rating'] = $this->rating;
                        break;
                    case 'review':
                        $notificationData['dish_name'] = $this->dish_name;
                        $notificationData['review_rating'] = $this->review_rating;
                        $notificationData['publish_review'] = $this->publish_review;
                        break;
                }

                Log::info('Sending notification', ['notification_data' => $notificationData]);

                Notification::route('mail', $contactEmail)
                    ->notify(new ContactNotification($notificationData));
            }

            session()->flash('success', 'Thank you for your message! We will get back to you soon.');
            $this->resetForm();

        } catch (\Exception $e) {
            Log::error('Contact form submission error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            session()->flash('error', 'There was an error sending your message. Please try again.');
        }
    }

    private function resetForm()
    {
        $this->reset([
            'name', 'email', 'phone', 'preferred_date', 'message',
            'contact_type'
        ]);
        $this->resetDynamicFields();
    }

    public function render()
    {
        return view('livewire.landing-area.contact-section');
    }
}
