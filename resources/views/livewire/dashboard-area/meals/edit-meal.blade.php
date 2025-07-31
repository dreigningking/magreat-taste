<div class="content-wrapper">
    @if(session()->has('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fa fa-exclamation-circle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row mb-4">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <h3 class="font-weight-bold">Edit Meal: {{ $meal->name }}</h3>
            <div>
                <a href="{{ route('meals.index') }}" class="btn btn-outline-secondary">
                    <i class="fa fa-arrow-left me-2"></i>Back to Meals
                </a>
            </div>
        </div>
    </div>

    <form wire:submit.prevent="update">
        <div class="row">
            <!-- Main Content -->
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fa fa-edit me-2"></i>Edit Meal Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Meal Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="name" wire:model="name" placeholder="Enter meal name">
                                    @error('name') <div class="text-danger text-xs mt-1">{{ $message }}</div> @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="category_id" class="form-label">Category <span class="text-danger">*</span></label>
                                    <select class="form-control" id="category_id" wire:model="category_id">
                                        <option value="">Select Category</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('category_id') <div class="text-danger text-xs mt-1">{{ $message }}</div> @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="excerpt" class="form-label">Excerpt <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="excerpt" wire:model="excerpt" rows="3" placeholder="Brief description of the meal"></textarea>
                            @error('excerpt') <div class="text-danger text-xs mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="description" wire:model="description" rows="6" placeholder="Detailed description of the meal"></textarea>
                            @error('description') <div class="text-danger text-xs mt-1">{{ $message }}</div> @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="image" class="form-label">Meal Image</label>
                                    <input type="file" class="form-control" id="image" wire:model="image" accept="image/*">
                                    @error('image') <div class="text-danger text-xs mt-1">{{ $message }}</div> @enderror
                                    <small class="text-muted">Max size: 2MB. Formats: JPEG, PNG, JPG, GIF</small>
                                    
                                    @if($currentImage)
                                        <div class="mt-2">
                                            <small class="text-muted">Current image:</small>
                                            <img src="{{ Storage::url($currentImage) }}" alt="Current" class="img-thumbnail mt-1" style="max-height: 100px;">
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="video" class="form-label">Video URL</label>
                                    <input type="url" class="form-control" id="video" wire:model="video" placeholder="https://youtube.com/watch?v=...">
                                    @error('video') <div class="text-danger text-xs mt-1">{{ $message }}</div> @enderror
                                    <small class="text-muted">YouTube, Vimeo, or other video platform URL</small>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input ms-0" type="checkbox" id="is_active" wire:model="is_active">
                                <label class="form-check-label" for="is_active">
                                    Active Meal
                                </label>
                            </div>
                            <small class="text-muted">Active meals will be visible to customers</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0"><i class="fa fa-list me-2"></i>Food Selection</h5>
                    </div>
                    <div class="card-body">
                        <div wire:ignore class="mb-3">
                            <label for="selectedFoods" class="form-label">Select Foods <span class="text-danger">*</span></label>
                            <select class="form-control" id="selectedFoods" wire:model="selectedFoods" multiple>
                                @foreach($foods as $food)
                                    <option value="{{ $food->id }}">
                                        {{ $food->name }} 
                                        @if($food->sizes->count() > 0)
                                            (₦{{ number_format($food->sizes->min('price'), 2) }} - ₦{{ number_format($food->sizes->max('price'), 2) }})
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                            @error('selectedFoods') <div class="text-danger text-xs mt-1">{{ $message }}</div> @enderror
                            <small class="text-muted">Select one or more foods to include in this meal</small>
                        </div>

                        <!-- Selected Foods Preview -->
                        @if(!empty($selectedFoods))
                            <div class="mt-3">
                                <h6 class="text-muted">Selected Foods:</h6>
                                <div class="list-group list-group-flush">
                                    @foreach($foods->whereIn('id', $selectedFoods) as $food)
                                        <div class="list-group-item d-flex justify-content-between align-items-center py-2">
                                            <div>
                                                <strong>{{ $food->name }}</strong>
                                                <br>
                                                <small class="text-muted">{{ Str::limit($food->description, 50) }}</small>
                                            </div>
                                            <span class="badge bg-primary">
                                                {{ $food->sizes->count() }} sizes
                                            </span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Submit Button -->
                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary w-100" wire:loading.attr="disabled">
                                <span wire:loading.remove>
                                    <i class="fa fa-save me-2"></i>Update Meal
                                </span>
                                <span wire:loading>
                                    <i class="fa fa-spinner fa-spin me-2"></i>Updating...
                                </span>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Image Preview -->
                @if($image)
                    <div class="card mt-3">
                        <div class="card-header">
                            <h6 class="mb-0">New Image Preview</h6>
                        </div>
                        <div class="card-body">
                            <img src="{{ $image->temporaryUrl() }}" alt="Preview" class="img-fluid rounded">
                        </div>
                    </div>
                @endif

                <!-- Current Meal Info -->
                <div class="card mt-3">
                    <div class="card-header">
                        <h6 class="mb-0">Current Meal Info</h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-2">
                            <strong>Category:</strong> 
                            @if($meal->category)
                                <span class="badge bg-secondary">{{ $meal->category->name }}</span>
                            @else
                                <span class="text-muted">No Category</span>
                            @endif
                        </div>
                        <div class="mb-2">
                            <strong>Foods:</strong> 
                            <span class="badge bg-info">{{ $meal->foods->count() }} foods</span>
                        </div>
                        <div class="mb-2">
                            <strong>From Price:</strong> 
                            <span class="badge bg-success">{{ $meal->formatted_from_price }}</span>
                        </div>
                        <div class="mb-2">
                            <strong>Status:</strong> 
                            @if($meal->is_active)
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-secondary">Inactive</span>
                            @endif
                        </div>
                        <div class="mb-0">
                            <strong>Created:</strong> 
                            <small class="text-muted">{{ $meal->created_at->format('M d, Y') }}</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    .select2-container--default .select2-selection--multiple {
        border: 1px solid #ced4da;
        border-radius: 0.375rem;
        min-height: 38px;
    }
    .select2-container--default .select2-selection--multiple .select2-selection__choice {
        background-color: #007bff;
        border: 1px solid #0056b3;
        color: white;
        border-radius: 0.25rem;
        padding: 2px 8px;
        margin: 2px;
    }
    .select2-container--default .select2-selection--multiple .select2-selection__choice__remove {
        color: white;
        margin-right: 5px;
    }
    .select2-container--default .select2-selection--multiple .select2-selection__choice__remove:hover {
        color: #fff;
        background-color: #0056b3;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize Select2
        $('#selectedFoods').select2({
            placeholder: 'Select foods for this meal',
            allowClear: true,
            width: '100%'
        });

        // Set initial values for Select2
        const selectedValues = @json($selectedFoods);
        $('#selectedFoods').val(selectedValues).trigger('change');

        // Handle Select2 change events
        $('#selectedFoods').on('change', function() {
            @this.set('selectedFoods', $(this).val());
        });

        // Update Select2 when Livewire updates the property
        Livewire.on('selectedFoodsUpdated', function(value) {
            $('#selectedFoods').val(value).trigger('change');
        });
    });
</script>
@endpush
