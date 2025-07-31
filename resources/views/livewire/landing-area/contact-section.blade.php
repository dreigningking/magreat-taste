<section class="contact-section py-5" id="contact">
    <div class="container">
        <h2 class="section-title">Get In Touch</h2>
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="contact-form-card">
                    <div class="mb-4">
                        <label for="contactCategory" class="form-label fw-bold">What can we help you with?</label>
                        <select class="form-select" wire:model="contact_type" id="contactCategory">
                            <option value="inquiry">General Inquiry</option>
                            <option value="booking">Booking Request</option>
                            <option value="feedback">Feedback</option>
                            <option value="review">Review</option>
                        </select>
                    </div>

                    <form id="contactForm">
                        <!-- Common fields that appear for all categories -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="contactName" class="form-label">Full Name *</label>
                                <input type="text" wire:model="name" class="form-control" id="contactName" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="contactEmail" class="form-label">Email Address *</label>
                                <input type="email" wire:model="email" class="form-control" id="contactEmail" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="contactPhone" class="form-label">Phone Number</label>
                                <input type="tel" wire:model="phone" class="form-control" id="contactPhone">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="contactDate" class="form-label">Preferred Date</label>
                                <input type="date" wire:model="preferred_date" class="form-control" id="contactDate">
                            </div>
                        </div>

                        <!-- Dynamic fields based on category -->
                        <div class="row" id="dynamicFields">
                            <!-- Fields will be populated by JavaScript -->
                        </div>

                        <div class="mb-3">
                            <label for="contactMessage" class="form-label">Message *</label>
                            <textarea class="form-control" id="contactMessage" rows="4" required></textarea>
                        </div>

                        <div class="text-center">
                            <button type="submit" class="btn btn-primary btn-lg px-5">Send Message</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

@push('scripts')
<script>
    // Contact form dynamic functionality
    document.addEventListener('DOMContentLoaded', function() {
        const contactForm = document.getElementById('contactForm');
        const contactCategory = document.getElementById('contactCategory');
        const dynamicFields = document.getElementById('dynamicFields');
        if (!contactForm || !contactCategory || !dynamicFields) return; // Only run if the form is present
        // Define field configurations for each category
        const categoryFields = {
            inquiry: [{
                    type: 'text',
                    id: 'inquirySubject',
                    label: 'Subject',
                    required: true
                },
                {
                    type: 'select',
                    id: 'inquiryType',
                    label: 'Inquiry Type',
                    options: ['General Question', 'Pricing', 'Menu', 'Other'],
                    required: true
                }
            ],
            booking: [{
                    type: 'text',
                    id: 'eventType',
                    label: 'Event Type',
                    required: true
                },
                {
                    type: 'number',
                    id: 'guestCount',
                    label: 'Number of Guests',
                    required: true
                },
                {
                    type: 'text',
                    id: 'eventLocation',
                    label: 'Event Location',
                    required: true
                },
                {
                    type: 'select',
                    id: 'serviceType',
                    label: 'Service Type',
                    options: ['Full Catering', 'Drop-off', 'Personal Chef', 'Custom Menu'],
                    required: true
                }
            ],
            feedback: [{
                    type: 'select',
                    id: 'feedbackType',
                    label: 'Feedback Type',
                    options: ['Suggestion', 'Complaint', 'Compliment', 'Improvement'],
                    required: true
                },
                {
                    type: 'select',
                    id: 'rating',
                    label: 'Overall Rating',
                    options: ['5 Stars', '4 Stars', '3 Stars', '2 Stars', '1 Star'],
                    required: true
                }
            ],
            review: [{
                    type: 'text',
                    id: 'dishName',
                    label: 'Dish/Service Reviewed',
                    required: true
                },
                {
                    type: 'select',
                    id: 'reviewRating',
                    label: 'Rating',
                    options: ['5 Stars', '4 Stars', '3 Stars', '2 Stars', '1 Star'],
                    required: true
                },
                {
                    type: 'checkbox',
                    id: 'publishReview',
                    label: 'Allow us to publish this review',
                    required: false
                }
            ]
        };

        // Function to update dynamic fields
        function updateDynamicFields() {
            const selectedCategory = contactCategory.value;
            const fields = categoryFields[selectedCategory] || [];

            let fieldsHTML = '';
            fields.forEach(field => {
                if (field.type === 'select') {
                    let optionsHTML = '';
                    field.options.forEach(option => {
                        optionsHTML += `<option value="${option}">${option}</option>`;
                    });
                    fieldsHTML += `
                            <div class="col-md-6 mb-3 dynamic-field">
                                <label for="${field.id}" class="form-label">${field.label}${field.required ? ' *' : ''}</label>
                                <select class="form-select" id="${field.id}"${field.required ? ' required' : ''}>
                                    <option value="">Select ${field.label}</option>
                                    ${optionsHTML}
                                </select>
                            </div>
                        `;
                } else if (field.type === 'checkbox') {
                    fieldsHTML += `
                            <div class="col-md-6 mb-3 dynamic-field">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="${field.id}">
                                    <label class="form-check-label" for="${field.id}">
                                        ${field.label}
                                    </label>
                                </div>
                            </div>
                        `;
                } else {
                    fieldsHTML += `
                            <div class="col-md-6 mb-3 dynamic-field">
                                <label for="${field.id}" class="form-label">${field.label}${field.required ? ' *' : ''}</label>
                                <input type="${field.type}" class="form-control" id="${field.id}"${field.required ? ' required' : ''}>
                            </div>
                        `;
                }
            });

            dynamicFields.innerHTML = fieldsHTML;
        }

        if (contactCategory) {
            // Update fields when category changes
            contactCategory.addEventListener('change', updateDynamicFields);

            // Initialize fields on page load
            updateDynamicFields();
        }

        if (contactForm) {
            // Handle form submission
            contactForm.addEventListener('submit', function(e) {
                e.preventDefault();

            // Collect form data
            const formData = new FormData(contactForm);
            const data = {
                category: contactCategory.value,
                name: document.getElementById('contactName').value,
                email: document.getElementById('contactEmail').value,
                phone: document.getElementById('contactPhone').value,
                date: document.getElementById('contactDate').value,
                message: document.getElementById('contactMessage').value
            };

            // Add dynamic fields data
            const selectedCategory = contactCategory.value;
            const fields = categoryFields[selectedCategory] || [];
            fields.forEach(field => {
                const element = document.getElementById(field.id);
                if (element) {
                    if (field.type === 'checkbox') {
                        data[field.id] = element.checked;
                    } else {
                        data[field.id] = element.value;
                    }
                }
            });

            // Show success message (in a real app, you'd send this to a server)
            alert('Thank you for your message! We will get back to you soon.');
            contactForm.reset();
            updateDynamicFields();
        });
    }
    });
</script>
@endpush