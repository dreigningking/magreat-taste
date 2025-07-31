<div class="content-wrapper">
    @if(session()->has('message'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fa fa-check-circle me-2"></i>{{ session('message') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <h3 class="font-weight-bold">Contact Management</h3>
            <p class="text-muted">View and manage all contact form submissions</p>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0">{{ $totalContacts }}</h4>
                            <p class="mb-0">Total Contacts</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fa fa-envelope fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-dark">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0">{{ $pendingContacts }}</h4>
                            <p class="mb-0">Pending</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fa fa-clock fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0">{{ $resolvedContacts }}</h4>
                            <p class="mb-0">Resolved</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fa fa-check-circle fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-secondary text-dark">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="mb-0">{{ $closedContacts }}</h4>
                            <p class="mb-0">Closed</p>
                        </div>
                        <div class="align-self-center">
                            <i class="fa fa-times-circle fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-header bg-light">
            <h5 class="mb-0"><i class="fa fa-filter me-2"></i>Filters</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-2">
                    <label class="form-label">Date From</label>
                    <input type="date" wire:model.live="dateFrom" class="form-control">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Date To</label>
                    <input type="date" wire:model.live="dateTo" class="form-control">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Status</label>
                    <select wire:model.live="status" class="form-control">
                        <option value="">All Status</option>
                        <option value="pending">Pending</option>
                        <option value="resolved">Resolved</option>
                        <option value="closed">Closed</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Contact Type</label>
                    <select wire:model.live="contactType" class="form-control">
                        <option value="">All Types</option>
                        <option value="inquiry">Inquiry</option>
                        <option value="booking">Booking</option>
                        <option value="feedback">Feedback</option>
                        <option value="review">Review</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Search</label>
                    <input type="text" wire:model.live="search" class="form-control" placeholder="Name, Email, Phone, Message">
                </div>
                <div class="col-md-1 d-flex align-items-end">
                    <button type="button" wire:click="clearFilters" class="btn btn-outline-secondary">
                        <i class="fa fa-times"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Contacts Table -->
    <div class="card">
        <div class="card-header bg-light">
            <h5 class="mb-0"><i class="fa fa-list me-2"></i>Contact Submissions</h5>
        </div>
        <div class="card-body">
            @if($contacts->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>
                                <button type="button" class="btn btn-link p-0 text-decoration-none" wire:click="sortBy('name')">
                                    Name
                                    @if($sortBy === 'name')
                                    <i class="fa fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} ms-1"></i>
                                    @else
                                    <i class="fa fa-sort ms-1 text-muted"></i>
                                    @endif
                                </button>
                            </th>
                            <th>
                                <button type="button" class="btn btn-link p-0 text-decoration-none" wire:click="sortBy('created_at')">
                                    Date
                                    @if($sortBy === 'created_at')
                                    <i class="fa fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} ms-1"></i>
                                    @else
                                    <i class="fa fa-sort ms-1 text-muted"></i>
                                    @endif
                                </button>
                            </th>
                            <th>Contact Info</th>
                            <th>Type</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($contacts as $contact)
                        <tr>
                            <td>
                                <strong>{{ $contact->name }}</strong>
                            </td>
                            <td>
                                <div class="d-flex flex-column">
                                    <span>{{ $contact->created_at->format('M d, Y') }}</span>
                                    <small class="text-muted">{{ $contact->created_at->format('h:i A') }}</small>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex flex-column">
                                    <span>{{ $contact->email }}</span>
                                    @if($contact->phone)
                                    <small class="text-muted">{{ $contact->phone }}</small>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-{{ $contact->contact_type_color }}">
                                    {{ ucfirst($contact->contact_type) }}
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-{{ $contact->status_color }}">
                                    {{ ucfirst($contact->status) }}
                                </span>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <button type="button"
                                        class="btn btn-sm btn-outline-info"
                                        data-bs-toggle="modal"
                                        data-bs-target="#contactDetailsModal"
                                        data-contact-id="{{ $contact->id }}"
                                        data-contact-name="{{ $contact->name }}"
                                        data-contact-email="{{ $contact->email }}"
                                        data-contact-phone="{{ $contact->phone }}"
                                        data-contact-type="{{ $contact->contact_type }}"
                                        data-contact-status="{{ $contact->status }}"
                                        data-contact-message="{{ $contact->message }}"
                                        data-preferred-date="{{ $contact->preferred_date ? $contact->preferred_date->format('M d, Y') : '' }}"
                                        data-inquiry-subject="{{ $contact->inquiry_subject }}"
                                        data-inquiry-type="{{ $contact->inquiry_type }}"
                                        data-event-type="{{ $contact->event_type }}"
                                        data-guest-count="{{ $contact->guest_count }}"
                                        data-event-location="{{ $contact->event_location }}"
                                        data-service-type="{{ $contact->service_type }}"
                                        data-feedback-type="{{ $contact->feedback_type }}"
                                        data-rating="{{ $contact->rating }}"
                                        data-dish-name="{{ $contact->dish_name }}"
                                        data-review-rating="{{ $contact->review_rating }}"
                                        data-publish-review="{{ $contact->publish_review ? 'Yes' : 'No' }}"
                                        title="View Details">
                                        <i class="fa fa-eye"></i>
                                    </button>
                                    <div class="btn-group" role="group">
                                        <button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
                                            <i class="fa fa-cog"></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                            @if($contact->status === 'pending')
                                            <li><a class="dropdown-item" href="#" wire:click="updateStatus({{ $contact->id }}, 'resolved')">Mark as Resolved</a></li>
                                            <li><a class="dropdown-item" href="#" wire:click="updateStatus({{ $contact->id }}, 'closed')">Mark as Closed</a></li>
                                            @elseif($contact->status === 'resolved')
                                            <li><a class="dropdown-item" href="#" wire:click="updateStatus({{ $contact->id }}, 'closed')">Mark as Closed</a></li>
                                            <li><a class="dropdown-item" href="#" wire:click="updateStatus({{ $contact->id }}, 'pending')">Mark as Pending</a></li>
                                            @else
                                            <li><a class="dropdown-item" href="#" wire:click="updateStatus({{ $contact->id }}, 'pending')">Mark as Pending</a></li>
                                            <li><a class="dropdown-item" href="#" wire:click="updateStatus({{ $contact->id }}, 'resolved')">Mark as Resolved</a></li>
                                            @endif
                                        </ul>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center mt-4">
                {{ $contacts->links() }}
            </div>
            @else
            <div class="text-center py-5">
                <i class="fa fa-envelope text-muted" style="font-size: 4rem;"></i>
                <h5 class="text-muted mt-3">No Contacts Found</h5>
                <p class="text-muted">No contact submissions match your current filters.</p>
                <button type="button" wire:click="clearFilters" class="btn btn-primary">
                    <i class="fa fa-filter me-2"></i>Clear Filters
                </button>
            </div>
            @endif
        </div>
    </div>

    <!-- Contact Details Modal -->
    <div class="modal fade" id="contactDetailsModal" tabindex="-1" aria-labelledby="contactDetailsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="contactDetailsModalLabel">Contact Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <!-- Contact Information -->
                        <div class="col-md-6">
                            <h6 class="text-primary mb-3">Contact Information</h6>
                            <div class="mb-2">
                                <strong>Name:</strong> <span id="modal-contact-name"></span>
                            </div>
                            <div class="mb-2">
                                <strong>Email:</strong> <span id="modal-contact-email"></span>
                            </div>
                            <div class="mb-2">
                                <strong>Phone:</strong> <span id="modal-contact-phone"></span>
                            </div>
                            <div class="mb-2">
                                <strong>Contact Type:</strong> <span class="badge" id="modal-contact-type"></span>
                            </div>
                            <div class="mb-2">
                                <strong>Status:</strong> <span class="badge" id="modal-contact-status"></span>
                            </div>
                            <div class="mb-2">
                                <strong>Preferred Date:</strong> <span id="modal-preferred-date"></span>
                            </div>
                        </div>

                        <!-- Message -->
                        <div class="col-md-6">
                            <h6 class="text-success mb-3">Message</h6>
                            <div class="mb-2">
                                <strong>Message:</strong>
                                <p class="mt-2" id="modal-contact-message"></p>
                            </div>
                        </div>
                    </div>

                    <!-- Dynamic Fields -->
                    <div class="mt-4" id="modal-dynamic-fields">
                        <!-- Dynamic fields will be populated by JavaScript -->
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>



@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const contactDetailsModal = document.getElementById('contactDetailsModal');

        contactDetailsModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;

            // Get data from button attributes
            const contactName = button.getAttribute('data-contact-name');
            const contactEmail = button.getAttribute('data-contact-email');
            const contactPhone = button.getAttribute('data-contact-phone');
            const contactType = button.getAttribute('data-contact-type');
            const contactStatus = button.getAttribute('data-contact-status');
            const contactMessage = button.getAttribute('data-contact-message');
            const preferredDate = button.getAttribute('data-preferred-date');

            // Populate basic contact information
            document.getElementById('modal-contact-name').textContent = contactName || 'N/A';
            document.getElementById('modal-contact-email').textContent = contactEmail || 'N/A';
            document.getElementById('modal-contact-phone').textContent = contactPhone || 'N/A';
            document.getElementById('modal-preferred-date').textContent = preferredDate || 'N/A';
            document.getElementById('modal-contact-message').textContent = contactMessage || 'N/A';

            // Set contact type badge
            const typeBadge = document.getElementById('modal-contact-type');
            typeBadge.textContent = contactType ? contactType.charAt(0).toUpperCase() + contactType.slice(1) : 'N/A';
            typeBadge.className = 'badge bg-' + (contactType === 'inquiry' ? 'primary' : contactType === 'booking' ? 'success' : contactType === 'feedback' ? 'warning' : 'info');

            // Set status badge
            const statusBadge = document.getElementById('modal-contact-status');
            statusBadge.textContent = contactStatus ? contactStatus.charAt(0).toUpperCase() + contactStatus.slice(1) : 'N/A';
            statusBadge.className = 'badge bg-' + (contactStatus === 'pending' ? 'warning' : contactStatus === 'resolved' ? 'success' : 'secondary');

            // Populate dynamic fields based on contact type
            const dynamicFields = document.getElementById('modal-dynamic-fields');
            let dynamicFieldsHTML = '';

            if (contactType === 'inquiry') {
                const inquirySubject = button.getAttribute('data-inquiry-subject');
                const inquiryType = button.getAttribute('data-inquiry-type');

                dynamicFieldsHTML = `
                <h6 class="text-info mb-3">Inquiry Details</h6>
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-2">
                            <strong>Subject:</strong> <span>${inquirySubject || 'N/A'}</span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-2">
                            <strong>Inquiry Type:</strong> <span>${inquiryType || 'N/A'}</span>
                        </div>
                    </div>
                </div>
            `;
            } else if (contactType === 'booking') {
                const eventType = button.getAttribute('data-event-type');
                const guestCount = button.getAttribute('data-guest-count');
                const eventLocation = button.getAttribute('data-event-location');
                const serviceType = button.getAttribute('data-service-type');

                dynamicFieldsHTML = `
                <h6 class="text-success mb-3">Booking Details</h6>
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-2">
                            <strong>Event Type:</strong> <span>${eventType || 'N/A'}</span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-2">
                            <strong>Guest Count:</strong> <span>${guestCount || 'N/A'}</span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-2">
                            <strong>Event Location:</strong> <span>${eventLocation || 'N/A'}</span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-2">
                            <strong>Service Type:</strong> <span>${serviceType || 'N/A'}</span>
                        </div>
                    </div>
                </div>
            `;
            } else if (contactType === 'feedback') {
                const feedbackType = button.getAttribute('data-feedback-type');
                const rating = button.getAttribute('data-rating');

                dynamicFieldsHTML = `
                <h6 class="text-warning mb-3">Feedback Details</h6>
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-2">
                            <strong>Feedback Type:</strong> <span>${feedbackType || 'N/A'}</span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-2">
                            <strong>Rating:</strong> <span>${rating || 'N/A'}</span>
                        </div>
                    </div>
                </div>
            `;
            } else if (contactType === 'review') {
                const dishName = button.getAttribute('data-dish-name');
                const reviewRating = button.getAttribute('data-review-rating');
                const publishReview = button.getAttribute('data-publish-review');

                dynamicFieldsHTML = `
                <h6 class="text-info mb-3">Review Details</h6>
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-2">
                            <strong>Dish/Service:</strong> <span>${dishName || 'N/A'}</span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-2">
                            <strong>Rating:</strong> <span>${reviewRating || 'N/A'}</span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-2">
                            <strong>Publish Review:</strong> <span>${publishReview || 'N/A'}</span>
                        </div>
                    </div>
                </div>
            `;
            }

            dynamicFields.innerHTML = dynamicFieldsHTML;
        });
    });
</script>
@endpush