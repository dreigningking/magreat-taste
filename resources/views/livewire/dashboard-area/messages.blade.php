<div>
    @if(session()->has('message'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fa fa-check-circle me-2"></i>{{ session('message') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="font-weight-bold mb-1">
                        <i class="fa fa-envelope text-primary me-2"></i>Contact Management
                    </h3>
                    <p class="text-muted mb-0">View and manage all contact form submissions</p>
                </div>
                <div class="d-flex gap-2">
                    <span class="badge bg-primary fs-6 px-3 py-2">
                        <i class="fa fa-envelope me-1"></i>{{ $totalContacts }} Total
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="d-flex align-items-center mb-2">
                                <div class="icon icon-shape bg-primary text-white rounded-circle me-3">
                                    <i class="fa fa-envelope fs-4"></i>
                                </div>
                                <div>
                                    <h2 class="mb-0 text-primary">{{ $totalContacts }}</h2>
                                </div>
                            </div>
                            <h6 class="text-muted mb-0 font-weight-bold">Total Contacts</h6>
                            <small class="text-muted">All submissions</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="d-flex align-items-center mb-2">
                                <div class="icon icon-shape bg-warning text-white rounded-circle me-3">
                                    <i class="fa fa-clock fs-4"></i>
                                </div>
                                <div>
                                    <h2 class="mb-0 text-warning">{{ $pendingContacts }}</h2>
                                </div>
                            </div>
                            <h6 class="text-muted mb-0 font-weight-bold">Pending</h6>
                            <small class="text-muted">Awaiting response</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="d-flex align-items-center mb-2">
                                <div class="icon icon-shape bg-success text-white rounded-circle me-3">
                                    <i class="fa fa-check-circle fs-4"></i>
                                </div>
                                <div>
                                    <h2 class="mb-0 text-success">{{ $resolvedContacts }}</h2>
                                </div>
                            </div>
                            <h6 class="text-muted mb-0 font-weight-bold">Resolved</h6>
                            <small class="text-muted">Handled successfully</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <div class="d-flex align-items-center mb-2">
                                <div class="icon icon-shape bg-secondary text-white rounded-circle me-3">
                                    <i class="fa fa-times-circle fs-4"></i>
                                </div>
                                <div>
                                    <h2 class="mb-0 text-secondary">{{ $closedContacts }}</h2>
                                </div>
                            </div>
                            <h6 class="text-muted mb-0 font-weight-bold">Closed</h6>
                            <small class="text-muted">No further action</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card mb-4 border-0 shadow-sm">
        <div class="card-header bg-white border-bottom-0 py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0 text-primary">
                    <i class="fa fa-filter me-2"></i>Filters & Search
                </h5>
                <div class="d-flex gap-2">
                    @if($dateFrom || $dateTo || $status || $contactType || $search)
                    <span class="badge bg-info">
                        <i class="fa fa-info-circle me-1"></i>Filters Active
                    </span>
                    @endif
                    <button type="button" wire:click="clearFilters" class="btn btn-outline-secondary btn-sm">
                        <i class="fa fa-times me-1"></i>Clear All
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body bg-light">
            <div class="row g-3">
                <div class="col-lg-2 col-md-6">
                    <label class="form-label fw-bold text-dark">Date From</label>
                    <input type="date" wire:model.live="dateFrom" class="form-control form-control-lg">
                </div>
                <div class="col-lg-2 col-md-6">
                    <label class="form-label fw-bold text-dark">Date To</label>
                    <input type="date" wire:model.live="dateTo" class="form-control form-control-lg">
                </div>
                <div class="col-lg-2 col-md-6">
                    <label class="form-label fw-bold text-dark">Status</label>
                    <select wire:model.live="status" class="form-select form-select-lg">
                        <option value="">All Status</option>
                        <option value="pending">🟡 Pending</option>
                        <option value="resolved">🟢 Resolved</option>
                        <option value="closed">⚫ Closed</option>
                    </select>
                </div>
                <div class="col-lg-2 col-md-6">
                    <label class="form-label fw-bold text-dark">Contact Type</label>
                    <select wire:model.live="contactType" class="form-select form-select-lg">
                        <option value="">All Types</option>
                        <option value="inquiry">🔍 Inquiry</option>
                        <option value="booking">📅 Booking</option>
                        <option value="feedback">💬 Feedback</option>
                        <option value="review">⭐ Review</option>
                    </select>
                </div>
                <div class="col-lg-4 col-md-12">
                    <label class="form-label fw-bold text-dark">Search</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fa fa-search"></i></span>
                        <input type="text" wire:model.live="search" class="form-control form-control-lg"
                               placeholder="Search by name, email, phone, or message...">
                        @if($search)
                        <button class="btn btn-outline-secondary" type="button" wire:click="$set('search', '')">
                            <i class="fa fa-times"></i>
                        </button>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Active Filters Display -->
            @if($dateFrom || $dateTo || $status || $contactType || $search)
            <div class="mt-3 pt-3 border-top">
                <div class="d-flex flex-wrap gap-2">
                    <small class="text-muted fw-bold">Active Filters:</small>
                    @if($dateFrom)
                    <span class="badge bg-primary">From: {{ \Carbon\Carbon::parse($dateFrom)->format('M d, Y') }}</span>
                    @endif
                    @if($dateTo)
                    <span class="badge bg-primary">To: {{ \Carbon\Carbon::parse($dateTo)->format('M d, Y') }}</span>
                    @endif
                    @if($status)
                    <span class="badge bg-{{ $status === 'pending' ? 'warning' : ($status === 'resolved' ? 'success' : 'secondary') }}">
                        {{ ucfirst($status) }}
                    </span>
                    @endif
                    @if($contactType)
                    <span class="badge bg-info">{{ ucfirst($contactType) }}</span>
                    @endif
                    @if($search)
                    <span class="badge bg-dark">Search: "{{ $search }}"</span>
                    @endif
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Contacts Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-bottom-0 py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0 text-primary">
                    <i class="fa fa-list me-2"></i>Contact Submissions
                    <span class="badge bg-primary ms-2">{{ $contacts->total() }}</span>
                </h5>
                <div class="d-flex align-items-center gap-2">
                    <small class="text-muted">Sorted by: {{ ucfirst(str_replace('_', ' ', $sortBy)) }}</small>
                    <div class="btn-group btn-group-sm">
                        <button type="button" class="btn btn-outline-primary active" wire:click="sortBy('created_at')">
                            <i class="fa fa-calendar"></i>
                        </button>
                        <button type="button" class="btn btn-outline-primary" wire:click="sortBy('name')">
                            <i class="fa fa-user"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            @if($contacts->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="border-0 fw-bold text-dark py-3 px-4">
                                <button type="button" class="btn btn-link p-0 text-decoration-none fw-bold" wire:click="sortBy('name')">
                                    Contact Person
                                    @if($sortBy === 'name')
                                    <i class="fa fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} ms-1 text-primary"></i>
                                    @else
                                    <i class="fa fa-sort ms-1 text-muted"></i>
                                    @endif
                                </button>
                            </th>
                            <th class="border-0 fw-bold text-dark py-3">
                                <button type="button" class="btn btn-link p-0 text-decoration-none fw-bold" wire:click="sortBy('created_at')">
                                    Submission Date
                                    @if($sortBy === 'created_at')
                                    <i class="fa fa-sort-{{ $sortDirection === 'asc' ? 'up' : 'down' }} ms-1 text-primary"></i>
                                    @else
                                    <i class="fa fa-sort ms-1 text-muted"></i>
                                    @endif
                                </button>
                            </th>
                            <th class="border-0 fw-bold text-dark py-3">Contact Details</th>
                            <th class="border-0 fw-bold text-dark py-3">Type</th>
                            <th class="border-0 fw-bold text-dark py-3">Status</th>
                            <th class="border-0 fw-bold text-dark py-3 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($contacts as $contact)
                        <tr class="border-bottom border-light">
                            <td class="py-3 px-4">
                                <div class="d-flex align-items-center">
                                    <div class="avatar avatar-sm bg-gradient-{{ $contact->contact_type_color }} text-white rounded-circle me-3 d-flex align-items-center justify-content-center">
                                        <i class="fa fa-{{ $contact->contact_type === 'inquiry' ? 'question' : ($contact->contact_type === 'booking' ? 'calendar' : ($contact->contact_type === 'feedback' ? 'comment' : 'star')) }}"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0 fw-bold">{{ $contact->name }}</h6>
                                        <small class="text-muted">ID: {{ $contact->id }}</small>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3">
                                <div class="d-flex flex-column">
                                    <span class="fw-bold text-dark">{{ $contact->created_at->format('M d, Y') }}</span>
                                    <small class="text-muted">{{ $contact->created_at->format('h:i A') }}</small>
                                    @if($contact->created_at->isToday())
                                    <span class="badge bg-success mt-1">Today</span>
                                    @elseif($contact->created_at->isYesterday())
                                    <span class="badge bg-warning mt-1">Yesterday</span>
                                    @endif
                                </div>
                            </td>
                            <td class="py-3">
                                <div class="d-flex flex-column">
                                    <span class="fw-bold text-primary">{{ $contact->email }}</span>
                                    @if($contact->phone)
                                    <small class="text-muted">
                                        <i class="fa fa-phone me-1"></i>{{ $contact->phone }}
                                    </small>
                                    @endif
                                </div>
                            </td>
                            <td class="py-3">
                                <span class="badge bg-{{ $contact->contact_type_color }} px-3 py-2">
                                    <i class="fa fa-{{ $contact->contact_type === 'inquiry' ? 'question-circle' : ($contact->contact_type === 'booking' ? 'calendar-check' : ($contact->contact_type === 'feedback' ? 'comments' : 'star')) }} me-1"></i>
                                    {{ ucfirst($contact->contact_type) }}
                                </span>
                            </td>
                            <td class="py-3">
                                <span class="badge bg-{{ $contact->status_color }} px-3 py-2 fs-6">
                                    @if($contact->status === 'pending')
                                    <i class="fa fa-clock me-1"></i>
                                    @elseif($contact->status === 'resolved')
                                    <i class="fa fa-check-circle me-1"></i>
                                    @else
                                    <i class="fa fa-times-circle me-1"></i>
                                    @endif
                                    {{ ucfirst($contact->status) }}
                                </span>
                            </td>
                            <td class="py-3 text-center">
                                <div class="btn-group" role="group">
                                    <button type="button"
                                        class="btn btn-sm btn-outline-primary"
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
                                        title="View Full Details">
                                        <i class="fa fa-eye"></i>
                                    </button>
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                            <i class="fa fa-cog"></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                            @if($contact->status === 'pending')
                                            <li><a class="dropdown-item text-success" href="#" wire:click="updateStatus({{ $contact->id }}, 'resolved')">
                                                <i class="fa fa-check-circle me-2"></i>Mark as Resolved
                                            </a></li>
                                            <li><a class="dropdown-item text-secondary" href="#" wire:click="updateStatus({{ $contact->id }}, 'closed')">
                                                <i class="fa fa-times-circle me-2"></i>Mark as Closed
                                            </a></li>
                                            @elseif($contact->status === 'resolved')
                                            <li><a class="dropdown-item text-secondary" href="#" wire:click="updateStatus({{ $contact->id }}, 'closed')">
                                                <i class="fa fa-times-circle me-2"></i>Mark as Closed
                                            </a></li>
                                            <li><a class="dropdown-item text-warning" href="#" wire:click="updateStatus({{ $contact->id }}, 'pending')">
                                                <i class="fa fa-clock me-2"></i>Mark as Pending
                                            </a></li>
                                            @else
                                            <li><a class="dropdown-item text-warning" href="#" wire:click="updateStatus({{ $contact->id }}, 'pending')">
                                                <i class="fa fa-clock me-2"></i>Mark as Pending
                                            </a></li>
                                            <li><a class="dropdown-item text-success" href="#" wire:click="updateStatus({{ $contact->id }}, 'resolved')">
                                                <i class="fa fa-check-circle me-2"></i>Mark as Resolved
                                            </a></li>
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
            @if($contacts->hasPages())
            <div class="card-footer bg-white border-top-0 py-4">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="text-muted">
                        Showing {{ $contacts->firstItem() }} to {{ $contacts->lastItem() }} of {{ $contacts->total() }} contacts
                    </div>
                    <div>
                        {{ $contacts->links('vendor.pagination.bootstrap-5') }}
                    </div>
                </div>
            </div>
            @endif
            @else
            <div class="text-center py-5">
                <div class="icon icon-shape bg-light text-muted rounded-circle mx-auto mb-4 d-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                    <i class="fa fa-envelope fa-3x"></i>
                </div>
                <h5 class="text-muted mb-3">No Contact Submissions Found</h5>
                <p class="text-muted mb-4">No contact submissions match your current filters.</p>
                <div class="d-flex justify-content-center gap-2">
                    <button type="button" wire:click="clearFilters" class="btn btn-primary">
                        <i class="fa fa-filter me-2"></i>Clear Filters
                    </button>
                    @if($dateFrom || $dateTo || $status || $contactType || $search)
                    <button type="button" wire:click="$set('dateFrom', '')" class="btn btn-outline-secondary">
                        <i class="fa fa-refresh me-2"></i>Reset Search
                    </button>
                    @endif
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Contact Details Modal -->
    <div class="modal fade" id="contactDetailsModal" tabindex="-1" aria-labelledby="contactDetailsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-gradient-primary text-white">
                    <h5 class="modal-title fw-bold" id="contactDetailsModalLabel">
                        <i class="fa fa-user-circle me-2"></i>Contact Details
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <!-- Contact Header -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <div class="d-flex align-items-center">
                                <div class="avatar avatar-xl bg-gradient-primary text-white rounded-circle me-4 d-flex align-items-center justify-content-center">
                                    <i class="fa fa-user fa-2x"></i>
                                </div>
                                <div>
                                    <h4 class="mb-1" id="modal-contact-name"></h4>
                                    <p class="text-muted mb-2" id="modal-contact-email"></p>
                                    <div class="d-flex gap-2">
                                        <span class="badge fs-6" id="modal-contact-type"></span>
                                        <span class="badge fs-6" id="modal-contact-status"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Contact Information -->
                        <div class="col-md-6">
                            <div class="card border-0 bg-light">
                                <div class="card-header bg-white border-bottom-0">
                                    <h6 class="text-primary mb-0">
                                        <i class="fa fa-address-card me-2"></i>Contact Information
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="row g-3">
                                        <div class="col-12">
                                            <div class="d-flex justify-content-between">
                                                <strong class="text-muted">Full Name:</strong>
                                                <span id="modal-contact-name-display"></span>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="d-flex justify-content-between">
                                                <strong class="text-muted">Email:</strong>
                                                <a href="mailto:" id="modal-contact-email-link" class="text-primary text-decoration-none"></a>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="d-flex justify-content-between">
                                                <strong class="text-muted">Phone:</strong>
                                                <a href="tel:" id="modal-contact-phone-link" class="text-success text-decoration-none"></a>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="d-flex justify-content-between">
                                                <strong class="text-muted">Preferred Date:</strong>
                                                <span id="modal-preferred-date-display"></span>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="d-flex justify-content-between">
                                                <strong class="text-muted">Submitted:</strong>
                                                <small class="text-muted" id="modal-submission-date"></small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Message -->
                        <div class="col-md-6">
                            <div class="card border-0 bg-light">
                                <div class="card-header bg-white border-bottom-0">
                                    <h6 class="text-success mb-0">
                                        <i class="fa fa-envelope me-2"></i>Message
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="bg-white p-3 rounded border">
                                        <p class="mb-0 text-dark" id="modal-contact-message" style="line-height: 1.6;"></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Dynamic Fields -->
                    <div class="mt-4" id="modal-dynamic-fields">
                        <!-- Dynamic fields will be populated by JavaScript -->
                    </div>
                </div>
                <div class="modal-footer bg-light border-top">
                    <div class="d-flex justify-content-between w-100">
                        <div class="text-muted">
                            <small>Contact ID: <span id="modal-contact-id-display"></span></small>
                        </div>
                        <div>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                <i class="fa fa-times me-2"></i>Close
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions Modal -->
    <div class="modal fade" id="quickActionsModal" tabindex="-1" aria-labelledby="quickActionsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="quickActionsModalLabel">Quick Actions</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <div class="mb-3">
                        <button class="btn btn-success w-100 mb-2" id="quick-resolve-btn">
                            <i class="fa fa-check-circle me-2"></i>Mark as Resolved
                        </button>
                        <button class="btn btn-warning w-100 mb-2" id="quick-pending-btn">
                            <i class="fa fa-clock me-2"></i>Mark as Pending
                        </button>
                        <button class="btn btn-secondary w-100" id="quick-close-btn">
                            <i class="fa fa-times-circle me-2"></i>Mark as Closed
                        </button>
                    </div>
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
            const contactId = button.getAttribute('data-contact-id');
            const contactName = button.getAttribute('data-contact-name');
            const contactEmail = button.getAttribute('data-contact-email');
            const contactPhone = button.getAttribute('data-contact-phone');
            const contactType = button.getAttribute('data-contact-type');
            const contactStatus = button.getAttribute('data-contact-status');
            const contactMessage = button.getAttribute('data-contact-message');
            const preferredDate = button.getAttribute('data-preferred-date');

            // Populate header information
            document.getElementById('modal-contact-name').textContent = contactName || 'N/A';
            document.getElementById('modal-contact-email').textContent = contactEmail || 'N/A';
            document.getElementById('modal-contact-id-display').textContent = contactId || 'N/A';

            // Populate detailed contact information
            document.getElementById('modal-contact-name-display').textContent = contactName || 'N/A';

            const emailLink = document.getElementById('modal-contact-email-link');
            emailLink.textContent = contactEmail || 'N/A';
            emailLink.href = contactEmail ? 'mailto:' + contactEmail : '#';

            const phoneLink = document.getElementById('modal-contact-phone-link');
            phoneLink.textContent = contactPhone || 'N/A';
            phoneLink.href = contactPhone ? 'tel:' + contactPhone : '#';

            document.getElementById('modal-preferred-date-display').textContent = preferredDate || 'N/A';
            document.getElementById('modal-submission-date').textContent = new Date().toLocaleString();
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
                <div class="card border-0 bg-light">
                    <div class="card-header bg-info text-white">
                        <h6 class="mb-0">
                            <i class="fa fa-question-circle me-2"></i>Inquiry Details
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="d-flex justify-content-between">
                                    <strong class="text-muted">Subject:</strong>
                                    <span>${inquirySubject || 'N/A'}</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex justify-content-between">
                                    <strong class="text-muted">Inquiry Type:</strong>
                                    <span>${inquiryType || 'N/A'}</span>
                                </div>
                            </div>
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
                <div class="card border-0 bg-light">
                    <div class="card-header bg-success text-white">
                        <h6 class="mb-0">
                            <i class="fa fa-calendar-check me-2"></i>Booking Details
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="d-flex justify-content-between">
                                    <strong class="text-muted">Event Type:</strong>
                                    <span>${eventType || 'N/A'}</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex justify-content-between">
                                    <strong class="text-muted">Guest Count:</strong>
                                    <span>${guestCount || 'N/A'}</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex justify-content-between">
                                    <strong class="text-muted">Event Location:</strong>
                                    <span>${eventLocation || 'N/A'}</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex justify-content-between">
                                    <strong class="text-muted">Service Type:</strong>
                                    <span>${serviceType || 'N/A'}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            } else if (contactType === 'feedback') {
                const feedbackType = button.getAttribute('data-feedback-type');
                const rating = button.getAttribute('data-rating');

                dynamicFieldsHTML = `
                <div class="card border-0 bg-light">
                    <div class="card-header bg-warning text-dark">
                        <h6 class="mb-0">
                            <i class="fa fa-comments me-2"></i>Feedback Details
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="d-flex justify-content-between">
                                    <strong class="text-muted">Feedback Type:</strong>
                                    <span>${feedbackType || 'N/A'}</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex justify-content-between">
                                    <strong class="text-muted">Rating:</strong>
                                    <span>${rating ? '★'.repeat(rating) : 'N/A'}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            } else if (contactType === 'review') {
                const dishName = button.getAttribute('data-dish-name');
                const reviewRating = button.getAttribute('data-review-rating');
                const publishReview = button.getAttribute('data-publish-review');

                dynamicFieldsHTML = `
                <div class="card border-0 bg-light">
                    <div class="card-header bg-info text-white">
                        <h6 class="mb-0">
                            <i class="fa fa-star me-2"></i>Review Details
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="d-flex justify-content-between">
                                    <strong class="text-muted">Dish/Service:</strong>
                                    <span>${dishName || 'N/A'}</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex justify-content-between">
                                    <strong class="text-muted">Rating:</strong>
                                    <span>${reviewRating ? '★'.repeat(reviewRating) : 'N/A'}</span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex justify-content-between">
                                    <strong class="text-muted">Publish Review:</strong>
                                    <span class="badge bg-${publishReview === 'Yes' ? 'success' : 'secondary'}">${publishReview || 'N/A'}</span>
                                </div>
                            </div>
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
