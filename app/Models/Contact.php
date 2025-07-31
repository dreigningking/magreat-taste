<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'preferred_date',
        'message',
        'contact_type',
        'status',
        'inquiry_subject',
        'inquiry_type',
        'event_type',
        'guest_count',
        'event_location',
        'service_type',
        'feedback_type',
        'rating',
        'dish_name',
        'review_rating',
        'publish_review',
    ];

    protected $casts = [
        'preferred_date' => 'date',
        'publish_review' => 'boolean',
        'guest_count' => 'integer',
    ];

    public function getContactTypeColorAttribute()
    {
        $colors = [
            'inquiry' => 'primary',
            'booking' => 'success',
            'feedback' => 'warning',
            'review' => 'info'
        ];
        return $colors[$this->contact_type] ?? 'secondary';
    }

    public function getStatusColorAttribute()
    {
        $colors = [
            'pending' => 'warning',
            'resolved' => 'success',
            'closed' => 'secondary'
        ];
        return $colors[$this->status] ?? 'secondary';
    }

    public function getRatingStarsAttribute()
    {
        $rating = $this->rating ?? $this->review_rating;
        if (!$rating) return '';
        
        $stars = (int) $rating;
        $starHtml = '';
        for ($i = 1; $i <= 5; $i++) {
            $starHtml .= $i <= $stars ? '★' : '☆';
        }
        return $starHtml;
    }
}
