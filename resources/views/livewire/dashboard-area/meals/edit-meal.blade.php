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
                        <!-- Primary Food Selection -->
                        <div class="mb-4">
                            <label for="primaryFood" class="form-label">Primary Food <span class="text-danger">*</span></label>
                            <select class="form-control" id="primaryFood" wire:model="primaryFood">
                                <option value="">Select Primary Food</option>
                                @foreach($foods as $food)
                                    <option value="{{ $food->id }}">
                                        {{ $food->name }}
                                        @if($food->sizes->count() > 0)
                                            @php
                                                $prices = $food->sizes->pluck('pivot.price')->filter()->sort();
                                                $minPrice = $prices->first();
                                                $maxPrice = $prices->last();
                                            @endphp
                                            @if($minPrice && $maxPrice)
                                                (₦{{ number_format($minPrice, 2) }} - ₦{{ number_format($maxPrice, 2) }})
                                            @endif
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                            @error('primaryFood') <div class="text-danger text-xs mt-1">{{ $message }}</div> @enderror
                            <small class="text-muted">Select the main/required food for this meal</small>
                        </div>

                        <!-- Additional Foods Selection -->
                        <div class="mb-3">
                            <label class="form-label">Additional Foods</label>
                            <small class="text-muted d-block mb-2">Select additional foods (optional)</small>
                            <div class="border rounded p-3" style="max-height: 300px; overflow-y: auto;">
                                @foreach($foods as $food)
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox"
                                               id="additionalFood{{ $food->id }}"
                                               wire:model="additionalFoods"
                                               value="{{ $food->id }}">
                                        <label class="form-check-label" for="additionalFood{{ $food->id }}">
                                            <strong>{{ $food->name }}</strong>
                                            @if($food->sizes->count() > 0)
                                                @php
                                                    $prices = $food->sizes->pluck('pivot.price')->filter()->sort();
                                                    $minPrice = $prices->first();
                                                    $maxPrice = $prices->last();
                                                @endphp
                                                @if($minPrice && $maxPrice)
                                                    <small class="text-muted">(₦{{ number_format($minPrice, 2) }} - ₦{{ number_format($maxPrice, 2) }})</small>
                                                @endif
                                            @endif
                                            <br>
                                            <small class="text-muted">{{ Str::limit($food->description, 60) }}</small>
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                            @error('additionalFoods') <div class="text-danger text-xs mt-1">{{ $message }}</div> @enderror
                        </div>

                        <!-- Selected Foods Preview -->
                        @if($primaryFood || !empty($additionalFoods))
                            <div class="mt-4">
                                <h6 class="text-muted">Selected Foods:</h6>
                                <div class="list-group list-group-flush">
                                    @if($primaryFood)
                                        @php $primaryFoodObj = $foods->find($primaryFood); @endphp
                                        @if($primaryFoodObj)
                                            <div class="list-group-item d-flex justify-content-between align-items-center py-2 bg-light">
                                                <div>
                                                    <strong>{{ $primaryFoodObj->name }}</strong>
                                                    <br>
                                                    <small class="text-muted">{{ Str::limit($primaryFoodObj->description, 50) }}</small>
                                                    @if($primaryFoodObj->sizes->count() > 0)
                                                        <br>
                                                        <small class="text-success">
                                                            @php
                                                                $prices = $primaryFoodObj->sizes->pluck('pivot.price')->filter()->sort();
                                                                $minPrice = $prices->first();
                                                                $maxPrice = $prices->last();
                                                            @endphp
                                                            @if($minPrice && $maxPrice)
                                                                ₦{{ number_format($minPrice, 2) }} - ₦{{ number_format($maxPrice, 2) }}
                                                            @else
                                                                Price not set
                                                            @endif
                                                        </small>
                                                    @endif
                                                </div>
                                                <span class="badge bg-danger">Primary</span>
                                            </div>
                                        @endif
                                    @endif

                                    @if(!empty($additionalFoods))
                                        @foreach($foods->whereIn('id', $additionalFoods) as $food)
                                            @if($food->id != $primaryFood)
                                                <div class="list-group-item d-flex justify-content-between align-items-center py-2">
                                                    <div>
                                                        <strong>{{ $food->name }}</strong>
                                                        <br>
                                                        <small class="text-muted">{{ Str::limit($food->description, 50) }}</small>
                                                        @if($food->sizes->count() > 0)
                                                            <br>
                                                            <small class="text-success">
                                                                @php
                                                                    $prices = $food->sizes->pluck('pivot.price')->filter()->sort();
                                                                    $minPrice = $prices->first();
                                                                    $maxPrice = $prices->last();
                                                                @endphp
                                                                @if($minPrice && $maxPrice)
                                                                    ₦{{ number_format($minPrice, 2) }} - ₦{{ number_format($maxPrice, 2) }}
                                                                @else
                                                                    Price not set
                                                                @endif
                                                            </small>
                                                        @endif
                                                    </div>
                                                    <span class="badge bg-secondary">Additional</span>
                                                </div>
                                            @endif
                                        @endforeach
                                    @endif
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
<style>
    .food-selection-container {
        max-height: 300px;
        overflow-y: auto;
        border: 1px solid #dee2e6;
        border-radius: 0.375rem;
        padding: 1rem;
    }
    .form-check-label {
        cursor: pointer;
    }
</style>
@endpush
