<div class="content-wrapper">
    @if(session()->has('message'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fa fa-check-circle me-2"></i>{{ session('message') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="row mb-4">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <h3 class="font-weight-bold">Location Management</h3>
            <div>
                <button class="btn btn-primary create_location" data-bs-toggle="modal" data-bs-target="#createLocationModal">
                    <i class="fa fa-plus me-2"></i>Add New Location
                </button>
            </div>
        </div>
    </div>

    <!-- Locations Table -->
    <div class="card">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="fa fa-map-marker me-2"></i>All Locations</h5>
            <span class="badge bg-light text-dark">{{ $locations->count() }}</span>
        </div>
        <div class="card-body">
            @if($locations->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Location Name</th>
                            <th>Country</th>
                            <th>State</th>
                            <th>City</th>
                            <th>Address</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($locations as $index => $location)
                        <tr>
                            <td>{{ $locations->firstItem() + $index }}</td>
                            <td>
                                <strong class="text-primary">{{ $location->name }}</strong>
                            </td>
                            <td>
                                <span class="text-muted">{{ $location->country->name ?? 'N/A' }}</span>
                            </td>
                            <td>
                                <span class="text-muted">{{ $location->state->name ?? 'N/A' }}</span>
                            </td>
                            <td>
                                <span class="text-muted">{{ $location->city->name ?? 'N/A' }}</span>
                            </td>
                            <td>
                                <span class="text-muted">{{ Str::limit($location->address, 50) }}</span>
                            </td>
                            <td>
                                @if($location->status)
                                <span class="badge bg-success">Active</span>
                                @else
                                <span class="badge bg-warning">Inactive</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <button class="btn btn-sm btn-outline-primary edit-location-btn"
                                        data-bs-toggle="modal"
                                        data-bs-target="#editLocationModal"
                                        data-id="{{ $location->id }}">
                                        <i class="fa fa-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-danger"
                                        wire:click="delete({{ $location->id }})"
                                        onclick="return confirm('Are you sure you want to delete the location: \'{{ $location->name }}\'? This action cannot be undone.')">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-between align-items-center mt-4">
                <div class="text-muted">
                    Showing {{ $locations->firstItem() }} to {{ $locations->lastItem() }} of {{ $locations->total() }} locations
                </div>
                <div>
                    {{ $locations->links('vendor.pagination.bootstrap-5') }}
                </div>
            </div>
            @else
            <div class="text-center py-5">
                <i class="fa fa-map-marker text-muted" style="font-size: 4rem;"></i>
                <h5 class="text-muted mt-3">No Locations Found</h5>
                <p class="text-muted">Start by adding your first location</p>
                <button class="btn btn-primary create_location" data-bs-toggle="modal" data-bs-target="#createLocationModal">
                    <i class="fa fa-plus me-2"></i>Add Your First Location
                </button>
            </div>
            @endif
        </div>
    </div>

    <!-- Create Location Modal -->
    <div wire:ignore.self class="modal fade" id="createLocationModal" tabindex="-1" aria-labelledby="createLocationModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="createLocationModalLabel">
                        <i class="fa fa-plus me-2"></i>Add New Location
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form wire:submit.prevent="store">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Location Name *</label>
                                    <input type="text" class="form-control" id="name" wire:model="name" required>
                                    @error('name') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="status" class="form-label">Status</label>
                                    <select wire:model="status" class="form-select" id="status">
                                        <option value="1">Active</option>
                                        <option value="0">Inactive</option>
                                    </select>
                                    @error('status') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="country_id" class="form-label">Country *</label>
                                    <select wire:model.live="country_id" class="form-select" id="country_id" required>
                                        <option value="">Select Country</option>
                                        @foreach($countries as $country)
                                        <option value="{{ $country->id }}">{{ $country->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('country_id') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="state_id" class="form-label">State *</label>
                                    <select wire:model.live="state_id" class="form-select" id="state_id" required {{ empty($states) ? 'disabled' : '' }}>
                                        <option value="">Select State</option>
                                        @foreach($states as $state)
                                        <option value="{{ $state->id }}">{{ $state->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('state_id') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="city_id" class="form-label">City *</label>
                                    <select wire:model="city_id" class="form-select" id="city_id" required {{ empty($cities) ? 'disabled' : '' }}>
                                        <option value="">Select City</option>
                                        @foreach($cities as $city)
                                        <option value="{{ $city->id }}">{{ $city->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('city_id') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="address" class="form-label">Address *</label>
                                    <textarea class="form-control" id="address" wire:model="address" rows="3" placeholder="Enter full address"></textarea>
                                    @error('address') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" wire:click="cancelCreate">Cancel</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-save me-2"></i>Create Location
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Location Modal -->
    <div wire:ignore.self class="modal fade" id="editLocationModal" tabindex="-1" aria-labelledby="editLocationModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="editLocationModalLabel">
                        <i class="fa fa-edit me-2"></i>Edit Location
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form wire:submit.prevent="update">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="edit_name" class="form-label">Location Name *</label>
                                    <input type="text" class="form-control" id="edit_name" wire:model="edit_name" required>
                                    @error('edit_name') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="edit_status" class="form-label">Status</label>
                                    <select wire:model="edit_status" class="form-select" id="edit_status">
                                        <option value="1">Active</option>
                                        <option value="0">Inactive</option>
                                    </select>
                                    @error('edit_status') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="edit_country_id" class="form-label">Country *</label>
                                    <select wire:model.live="edit_country_id" class="form-select" id="edit_country_id" required>
                                        <option value="">Select Country</option>
                                        @foreach($countries as $country)
                                        <option value="{{ $country->id }}">{{ $country->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('edit_country_id') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="edit_state_id" class="form-label">State *</label>
                                    <select wire:model.live="edit_state_id" class="form-select" id="edit_state_id" required {{ empty($edit_states) ? 'disabled' : '' }}>
                                        <option value="">Select State</option>
                                        @foreach($edit_states as $state)
                                        <option value="{{ $state->id }}">{{ $state->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('edit_state_id') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="edit_city_id" class="form-label">City *</label>
                                    <select wire:model="edit_city_id" class="form-select" id="edit_city_id" required {{ empty($edit_cities) ? 'disabled' : '' }}>
                                        <option value="">Select City</option>
                                        @foreach($edit_cities as $city)
                                        <option value="{{ $city->id }}">{{ $city->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('edit_city_id') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="edit_address" class="form-label">Address *</label>
                                    <textarea class="form-control" id="edit_address" wire:model="edit_address" rows="3" placeholder="Enter full address"></textarea>
                                    @error('edit_address') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" wire:click="cancelEdit">Cancel</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-save me-2"></i>Update Location
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


@push('styles')
<style>
    .modal-content {
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    }
    
    .modal-header {
        border-radius: 15px 15px 0 0;
    }
    
    .form-control, .form-select {
        border-radius: 8px;
        border: 2px solid #e9ecef;
        transition: all 0.3s ease;
    }
    
    .form-control:focus, .form-select:focus {
        border-color: #d4a76a;
        box-shadow: 0 0 0 0.2rem rgba(212, 167, 106, 0.25);
    }
    
    .btn {
        border-radius: 8px;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    
    .btn:hover {
        transform: translateY(-1px);
    }
    
    .table {
        border-radius: 10px;
        overflow: hidden;
    }
    
    .table thead th {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border-bottom: 2px solid #dee2e6;
        font-weight: 600;
        color: #495057;
    }
    
    .table tbody tr:hover {
        background-color: rgba(212, 167, 106, 0.05);
    }
    
    .badge {
        font-size: 0.8rem;
        padding: 0.5rem 0.75rem;
    }
    
    .card {
        border-radius: 15px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        border: none;
    }
    
    .card-header {
        border-radius: 15px 15px 0 0;
        border-bottom: 1px solid rgba(0, 0, 0, 0.125);
    }
    
    .form-check-input:checked {
        background-color: #d4a76a;
        border-color: #d4a76a;
    }
    
    .alert {
        border-radius: 10px;
        border: none;
    }
    
    .btn-group .btn {
        margin-right: 2px;
    }
    
    .btn-group .btn:last-child {
        margin-right: 0;
    }
    
    /* Responsive adjustments */
    @media (max-width: 768px) {
        .table-responsive {
            font-size: 0.9rem;
        }
        
        .btn-group .btn {
            padding: 0.25rem 0.5rem;
            font-size: 0.8rem;
        }
        
        .modal-dialog {
            margin: 1rem;
        }
    }
</style>
@endpush

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Handle edit button clicks
        const editBtns = document.querySelectorAll('.edit-location-btn');
        editBtns.forEach(btn => {
            btn.addEventListener('click', function(event) {
                const button = event.currentTarget;
                const id = button.dataset.id;
                window.Livewire.dispatch('edit-location', {id: id});
            });
        });
    });
</script>
@endpush