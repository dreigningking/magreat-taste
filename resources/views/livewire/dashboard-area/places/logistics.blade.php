<div class="content-wrapper">
    @if(session()->has('message'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fa fa-check-circle me-2"></i>{{ session('message') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="row mb-4">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <h3 class="font-weight-bold">Logistics Management</h3>
            <div>
                <button class="btn btn-primary create_shipment" data-bs-toggle="modal" data-bs-target="#createLogisticsModal">
                    <i class="fa fa-plus me-2"></i>Add New Shipment Route
                </button>

            </div>
        </div>
    </div>



    <!-- shipmentRoutes Table -->
    <div class="card">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="fa fa-utensils me-2"></i>All Shipment Routes</h5>
            <span class="badge bg-light text-dark">{{ $shipmentRoutes->total() }} Routes</span>
        </div>
        <div class="card-body">
            @if($shipmentRoutes->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Shipper</th>
                            <th>Route Name</th>
                            <th>Location</th>
                            <th>Destination City</th>
                            <th>Note</th>
                            <th>Base Price</th>
                            <th>Estimated Minutes</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($shipmentRoutes as $index => $shipmentRoute)
                        <tr>
                            <td>{{ $shipmentRoutes->firstItem() + $index }}</td>
                            <td>
                                <strong class="text-info">{{ $shipmentRoute->shipper_name }}</strong>
                            </td>
                            <td>
                                <strong class="text-primary">{{ $shipmentRoute->route_name }}</strong>
                            </td>
                            <td>
                                <small class="text-muted">{{ $shipmentRoute->location->name }}, {{ $shipmentRoute->location->city->name }}</small>
                            </td>
                            <td>
                                <small class="text-muted">{{ $shipmentRoute->destinationCity->name }}, {{ $shipmentRoute->destinationCity->state->name }}</small>
                            </td>
                            <td>
                                <span class="text-muted">{{ Str::limit($shipmentRoute->notes, 50) }}</span>
                            </td>
                            <td>
                                <span class="text-muted">₦{{ number_format($shipmentRoute->base_price, 2) }}</span>
                            </td>
                            <td>
                                <span class="text-muted">{{ $shipmentRoute->estimated_minutes_string }}</span>
                            </td>
                            <td>
                                @if($shipmentRoute->status)
                                <span class="badge bg-success">Active</span>
                                @else
                                <span class="badge bg-warning">InActive</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <button class="btn btn-sm btn-outline-primary edit-shipment-btn"
                                        data-bs-toggle="modal"
                                        data-bs-target="#editshipmentRouteModal"
                                        data-id="{{ $shipmentRoute->id }}"
                                        data-status="{{ $shipmentRoute->status }}">
                                        <i class="fa fa-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-danger"
                                        wire:click="delete({{ $shipmentRoute->id }})"
                                        onclick="return confirm('Are you sure you want to delete the shipment route: \'{{ $shipmentRoute->route_name }}\'? This action cannot be undone.')">
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
                    Showing {{ $shipmentRoutes->firstItem() }} to {{ $shipmentRoutes->lastItem() }} of {{ $shipmentRoutes->total() }} shipmentRoutes
                </div>
                <div>
                    {{ $shipmentRoutes->links('vendor.pagination.bootstrap-5') }}
                </div>
            </div>
            @else
            <div class="text-center py-5">
                <i class="fa fa-utensils text-muted" style="font-size: 4rem;"></i>
                <h5 class="text-muted mt-3">No Shipment Routes Found</h5>
                <p class="text-muted">Start by adding your first Shipment Route</p>
                <button class="btn btn-primary create_shipment" data-bs-toggle="modal" data-bs-target="#createLogisticsModal">
                    <i class="fa fa-plus me-2"></i>Add Your First Shipment Route
                </button>
            </div>
            @endif
        </div>
    </div>

    <!-- Create shipmentRoute Modal -->
    <div wire:ignore.self class="modal fade" id="createLogisticsModal" tabindex="-1" aria-labelledby="createLogisticsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="createLogisticsModalLabel">
                        <i class="fa fa-plus me-2"></i>Add New Shipment Route
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form wire:submit.prevent="store">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="route_name" class="form-label">Shipment Route Name *</label>
                                    <input type="text" class="form-control" id="route_name" wire:model="route_name" required>
                                    @error('route_name') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="shipper_name" class="form-label">Shipper *</label>
                                    <input type="text" class="form-control" id="shipper_name" wire:model="shipper_name" required>
                                    @error('shipper_name') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div wire:ignore class="mb-3">
                                    <label for="location_id" class="form-label">Location *</label>
                                    <select class="form-select select2-city" id="location_id" required>
                                        <option value="">Select Location</option>
                                        @foreach($locations as $location)
                                        <option value="{{ $location->id }}">{{ $location->name }}, {{ $location->city->name}}</option>
                                        @endforeach
                                    </select>
                                    @error('location_id') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div wire:ignore class="mb-3">
                                    <label for="destination_city_id" class="form-label">Destination City *</label>
                                    <select class="form-select select2-city" id="destination_city_id" required>
                                        <option value="">Select Destination City</option>
                                        @foreach($cities as $city)
                                        <option value="{{ $city->id }}">{{ $city->name }}, {{ $city->state->name}}</option>
                                        @endforeach
                                    </select>
                                    @error('destination_city_id') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row align-items-end">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="base_price" class="form-label">Base Price</label>
                                    <input type="number" class="form-control" id="base_price" wire:model="base_price" step="0.01" min="0">
                                    @error('base_price') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="estimated_minutes" class="form-label">Estimated Minutes</label>
                                    <input type="number" class="form-control" id="estimated_minutes" wire:model="estimated_minutes" step="1" min="0">
                                    @error('estimated_minutes') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input ms-0" type="checkbox" id="status" wire:model="status">
                                        <label class="form-check-label" for="status">
                                            Active Route
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="notes" class="form-label">Notes</label>
                                    <textarea class="form-control" id="notes" wire:model="notes" rows="3"></textarea>
                                    @error('notes') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" wire:click="cancelCreate">Cancel</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-save me-2"></i>Create Shipment Route
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit shipmentRoute Modal -->
    <div wire:ignore.self class="modal fade" id="editshipmentRouteModal" tabindex="-1" aria-labelledby="editshipmentRouteModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="editshipmentRouteModalLabel">
                        <i class="fa fa-edit me-2"></i>Edit shipmentRoute
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form wire:submit.prevent="update">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="edit_route_name" class="form-label">Shipment Route Name *</label>
                                    <input type="text" class="form-control" id="edit_route_name" wire:model="edit_route_name" required>
                                    @error('edit_route_name') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="edit_shipper_name" class="form-label">Shipper *</label>
                                    <input type="text" class="form-control" id="edit_shipper_name" wire:model="edit_shipper_name" required>
                                    @error('edit_shipper_name') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div wire:ignore class="mb-3">
                                    <label for="edit_location_id" class="form-label">Location *</label>
                                    <select class="form-select select2-city" id="edit_location_id" required>
                                        <option value="">Select Location</option>
                                        @foreach($locations as $location)
                                        <option value="{{ $location->id }}">{{ $location->name }}, {{ $location->city->name}}</option>
                                        @endforeach
                                    </select>
                                    @error('edit_location_id') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div wire:ignore class="mb-3">
                                    <label for="edit_destination_city_id" class="form-label">Destination City *</label>
                                    <select class="form-select select2-city" id="edit_destination_city_id" required>
                                        <option value="">Select Destination City</option>
                                        @foreach($cities as $city)
                                        <option value="{{ $city->id }}">{{ $city->name }}, {{ $city->state->name}}</option>
                                        @endforeach
                                    </select>
                                    @error('edit_destination_city_id') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="edit_base_price" class="form-label">Base Price</label>
                                    <input type="number" class="form-control" id="edit_base_price" wire:model="edit_base_price" step="0.01" min="0">
                                    @error('edit_base_price') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="edit_estimated_minutes" class="form-label">Estimated Minutes</label>
                                    <input type="number" class="form-control" id="edit_estimated_minutes" wire:model="edit_estimated_minutes" step="1" min="0">
                                    @error('edit_estimated_minutes') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input ms-0" type="checkbox" id="edit_status" wire:model="edit_status" @if($edit_status) checked @endif value="1">
                                        <label class="form-check-label" for="edit_status">
                                            Active Route
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="edit_notes" class="form-label">Notes</label>
                                    <textarea class="form-control" id="edit_notes" wire:model="edit_notes" rows="3"></textarea>
                                    @error('edit_notes') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" wire:click="cancelEdit">Cancel</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-save me-2"></i>Update Shipment Route
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
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
    
    /* Select2 Custom Styling */
    .select2-container {
        width: 100% !important;
    }
    
    .select2-container--default .select2-selection--single {
        height: 38px;
        border: 2px solid #e9ecef;
        border-radius: 8px;
        background-color: #fff;
        transition: all 0.3s ease;
        padding:0px;
    }
    
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 34px;
        padding-left: 12px;
        color: #495057;
    }
    
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 36px;
        right: 8px;
    }
    
    .select2-container--default .select2-selection--single:focus,
    .select2-container--default.select2-container--open .select2-selection--single {
        border-color: #d4a76a;
        box-shadow: 0 0 0 0.2rem rgba(212, 167, 106, 0.25);
    }
    
    .select2-dropdown {
        border: 2px solid #d4a76a;
        border-radius: 8px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }
    
    .select2-container--default .select2-results__option--highlighted[aria-selected] {
        background-color: #d4a76a;
        color: white;
    }
    
    .select2-container--default .select2-results__option[aria-selected=true] {
        background-color: #f8f9fa;
        color: #495057;
    }
    
    .select2-search__field {
        border: 1px solid #ced4da !important;
        border-radius: 4px !important;
        padding: 6px 8px !important;
    }
    
    .select2-search__field:focus {
        border-color: #d4a76a !important;
        box-shadow: 0 0 0 0.2rem rgba(212, 167, 106, 0.25) !important;
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
        
        .select2-container {
            font-size: 14px;
        }
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize Select2 for create modal
        $('.create_shipment').click(function(){
            setTimeout(function() {
                $('.select2-city').select2({
                    dropdownParent: $('#createLogisticsModal'),
                    placeholder: 'Select',
                    allowClear: true,
                    width: '100%',
                    minimumResultsForSearch: 0,
                    language: {
                        noResults: function() {
                            return "No cities found";
                        }
                    }
                });
                
                // Handle Select2 change events for Livewire
                
            }, 100);
        });
        $('.select2-city').on('change', function() {
            let fieldName = $(this).attr('id');
            let value = $(this).val();
            console.log(value)
            window.Livewire.dispatch('select2_change',{field:fieldName,value:value})
        });
        
        // Initialize Select2 for edit modal
        $('.edit-shipment-btn').click(function(){
            let id = $(this).attr('data-id');
            let status = $(this).attr('data-status');
            window.Livewire.dispatch('edit-shipment-route', {id: id});
            $('#edit_status').prop('checked',status)
            setTimeout(function() {
                $('.select2-city').select2({
                    dropdownParent: $('#editshipmentRouteModal'),
                    placeholder: 'Select',
                    allowClear: true,
                    width: '100%',
                    minimumResultsForSearch: 0,
                    language: {
                        noResults: function() {
                            return "No cities found";
                        }
                    }
                });
            }, 100);
        });
        
        
        // Clean up Select2 when modals are hidden
        $('#createLogisticsModal').on('hidden.bs.modal', function() {
            $('.select2-city').select2('destroy');
        });
        
        $('#editshipmentRouteModal').on('hidden.bs.modal', function() {
            $('.select2-city').select2('destroy');
        });
        
        // Listen for Livewire event to reinitialize Select2
        Livewire.on('reinitializeSelect2', function(data) {
            let location = data[0].location
            let destination = data[0].destination
            setTimeout(function() {
                $('.select2-city').each(function() {
                    $('#edit_location_id').val(location).trigger('change')
                    $('#edit_destination_city_id').val(destination).trigger('change')
                    
                });
            }, 200);
        });
    });
</script>
@endpush