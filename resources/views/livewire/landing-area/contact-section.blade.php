<section class="contact-section py-5" id="contact">
    <div class="container">
        <h2 class="section-title">Get In Touch</h2>
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="contact-form-card">
                    <!-- Success/Error Messages -->
                    @if (session()->has('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if (session()->has('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="mb-4">
                        <label for="contactCategory" class="form-label fw-bold">What can we help you with?</label>
                        <select class="form-select" wire:model.live="contact_type" id="contactCategory">
                            <option value="inquiry">General Inquiry</option>
                            <option value="booking">Booking Request</option>
                            <option value="feedback">Feedback</option>
                            <option value="review">Review</option>
                        </select>
                        @error('contact_type') <span class="text-danger small">{{ $message }}</span> @enderror
                    </div>

                    <form wire:submit.prevent="submitContactForm">
                        <!-- Common fields that appear for all categories -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="contactName" class="form-label">Full Name *</label>
                                <input type="text" wire:model="name" class="form-control @error('name') is-invalid @enderror" id="contactName" required>
                                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="contactEmail" class="form-label">Email Address *</label>
                                <input type="email" wire:model="email" class="form-control @error('email') is-invalid @enderror" id="contactEmail" required>
                                @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="contactPhone" class="form-label">Phone Number</label>
                                <input type="tel" wire:model="phone" class="form-control @error('phone') is-invalid @enderror" id="contactPhone">
                                @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="contactDate" class="form-label"> @if($contact_type === 'booking') Event Date @else Preferred Date we reach you @endif </label>
                                <input type="date" wire:model="preferred_date" class="form-control @error('preferred_date') is-invalid @enderror" id="contactDate">
                                @error('preferred_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <!-- Dynamic fields based on category -->
                        <div class="row" id="dynamicFields">
                            <!-- Inquiry Fields -->
                            @if($contact_type === 'inquiry')
                                <div class="col-md-6 mb-3">
                                    <label for="inquirySubject" class="form-label">Subject *</label>
                                    <input type="text" wire:model="inquiry_subject" class="form-control @error('inquiry_subject') is-invalid @enderror" id="inquirySubject" required>
                                    @error('inquiry_subject') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="inquiryType" class="form-label">Inquiry Type *</label>
                                    <select wire:model="inquiry_type" class="form-select @error('inquiry_type') is-invalid @enderror" id="inquiryType" required>
                                        <option value="">Select Inquiry Type</option>
                                        <option value="General Question">General Question</option>
                                        <option value="Pricing">Pricing</option>
                                        <option value="Menu">Menu</option>
                                        <option value="Other">Other</option>
                                    </select>
                                    @error('inquiry_type') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            @endif

                            <!-- Booking Fields -->
                            @if($contact_type === 'booking')
                                <div class="col-md-6 mb-3">
                                    <label for="eventType" class="form-label">Event Type *</label>
                                    <input type="text" wire:model="event_type" class="form-control @error('event_type') is-invalid @enderror" id="eventType" required>
                                    @error('event_type') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="guestCount" class="form-label">Number of Guests *</label>
                                    <input type="number" wire:model="guest_count" class="form-control @error('guest_count') is-invalid @enderror" id="guestCount" min="1" required>
                                    @error('guest_count') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="eventLocation" class="form-label">Event Location *</label>
                                    <input type="text" wire:model="event_location" class="form-control @error('event_location') is-invalid @enderror" id="eventLocation" required>
                                    @error('event_location') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="serviceType" class="form-label">Service Type *</label>
                                    <select wire:model="service_type" class="form-select @error('service_type') is-invalid @enderror" id="serviceType" required>
                                        <option value="">Select Service Type</option>
                                        <option value="Full Catering">Full Catering</option>
                                        <option value="Drop-off">Drop-off</option>
                                        <option value="Personal Chef">Personal Chef</option>
                                        <option value="Custom Menu">Custom Menu</option>
                                    </select>
                                    @error('service_type') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            @endif

                            <!-- Feedback Fields -->
                            @if($contact_type === 'feedback')
                                <div class="col-md-6 mb-3">
                                    <label for="feedbackType" class="form-label">Feedback Type *</label>
                                    <select wire:model="feedback_type" class="form-select @error('feedback_type') is-invalid @enderror" id="feedbackType" required>
                                        <option value="">Select Feedback Type</option>
                                        <option value="Suggestion">Suggestion</option>
                                        <option value="Complaint">Complaint</option>
                                        <option value="Compliment">Compliment</option>
                                        <option value="Improvement">Improvement</option>
                                    </select>
                                    @error('feedback_type') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="rating" class="form-label">Overall Rating *</label>
                                    <select wire:model="rating" class="form-select @error('rating') is-invalid @enderror" id="rating" required>
                                        <option value="">Select Rating</option>
                                        <option value="5 Stars">5 Stars</option>
                                        <option value="4 Stars">4 Stars</option>
                                        <option value="3 Stars">3 Stars</option>
                                        <option value="2 Stars">2 Stars</option>
                                        <option value="1 Star">1 Star</option>
                                    </select>
                                    @error('rating') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            @endif

                            <!-- Review Fields -->
                            @if($contact_type === 'review')
                                <div class="col-md-6 mb-3">
                                    <label for="dishName" class="form-label">Dish/Service Reviewed *</label>
                                    <input type="text" wire:model="dish_name" class="form-control @error('dish_name') is-invalid @enderror" id="dishName" required>
                                    @error('dish_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="reviewRating" class="form-label">Rating *</label>
                                    <select wire:model="review_rating" class="form-select @error('review_rating') is-invalid @enderror" id="reviewRating" required>
                                        <option value="">Select Rating</option>
                                        <option value="5 Stars">5 Stars</option>
                                        <option value="4 Stars">4 Stars</option>
                                        <option value="3 Stars">3 Stars</option>
                                        <option value="2 Stars">2 Stars</option>
                                        <option value="1 Star">1 Star</option>
                                    </select>
                                    @error('review_rating') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" wire:model="publish_review" id="publishReview">
                                        <label class="form-check-label" for="publishReview">
                                            Allow us to publish this review
                                        </label>
                                    </div>
                                    @error('publish_review') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            @endif
                        </div>

                        <div class="mb-3">
                            <label for="contactMessage" class="form-label">Message *</label>
                            <textarea wire:model="message" class="form-control @error('message') is-invalid @enderror" id="contactMessage" rows="4" required></textarea>
                            @error('message') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="text-center">
                            <button type="submit" class="btn btn-primary btn-lg px-5" wire:loading.attr="disabled">
                                <span wire:loading.remove>Send Message</span>
                                <span wire:loading>
                                    <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                                    Sending...
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
