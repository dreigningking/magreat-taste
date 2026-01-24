<div>
    @if(session()->has('message'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fa fa-check-circle me-2"></i>{{ session('message') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="row">
        <div class="col-12">
            <div class="card mb-4">
                <div class="card-header pb-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6>Sizes</h6>
                        <div>
                            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#createSizeModal">
                                <i class="fa fa-plus me-2"></i>Add New Size
                            </button>
                            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#uploadSizesModal">
                                <i class="fa fa-upload me-2"></i>Upload Sizes
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body pt-0">
                    <div class="row">
                        <div class="col-lg-3 col-md-4 col-12 mt-4 mt-lg-0">
                            <div class="card">
                                <div class="card-body p-3">
                                    <div class="d-flex">
                                        <div>
                                            <div class="icon icon-shape bg-gradient-primary text-center border-radius-md">
                                                <i class="fa fa-ruler text-lg opacity-10" aria-hidden="true"></i>
                                            </div>
                                        </div>
                                        <div class="ms-3">
                                            <div class="numbers">
                                                <p class="text-sm mb-0 text-capitalize font-weight-bold">Total Sizes</p>
                                                <h5 class="font-weight-bolder mb-0">
                                                    {{ $totalSizes }}
                                                </h5>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-3 col-md-4 col-12 mt-4 mt-lg-0">
                            <div class="card">
                                <div class="card-body p-3">
                                    <div class="d-flex">
                                        <div>
                                            <div class="icon icon-shape bg-gradient-info text-center border-radius-md">
                                                <i class="fa fa-tags text-lg opacity-10" aria-hidden="true"></i>
                                            </div>
                                        </div>
                                        <div class="ms-3">
                                            <div class="numbers">
                                                <p class="text-sm mb-0 text-capitalize font-weight-bold">Size Types</p>
                                                <h5 class="font-weight-bolder mb-0">
                                                    {{ $sizes->count() }}
                                                </h5>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body px-0 pt-0 pb-2">
                    <!-- Filter and Sort Controls -->
                    <div class="row mb-3 px-3">
                        <div class="col-md-6">
                            <label for="filterType" class="form-label">Filter by Type</label>
                            <select class="form-control" id="filterType" wire:model.live="filterType">
                                <option value="">All Types</option>
                                @foreach($types as $type)
                                    <option value="{{ $type }}">{{ ucfirst(str_replace('_', ' ', $type)) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
            @if($sizes->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Image</th>
                            <th>Size Name</th>
                            <th>
                                <button class="btn btn-link p-0 text-decoration-none">
                                    Type
                                </button>
                            </th>
                            <th>Unit</th>
                            <th>Value</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($sizes as $index => $size)
                        <tr>
                            <td>{{ $sizes->firstItem() + $index }}</td>
                            <td>
                                @if($size->image)
                                <img src="{{ Storage::url($size->image) }}" alt="{{ $size->name }}" class="rounded" style="width: 60px; height: 40px; object-fit: cover;">
                                @else
                                <div class="bg-secondary rounded d-flex align-items-center justify-content-center" style="width: 60px; height: 40px;">
                                    <i class="fa fa-image text-white"></i>
                                </div>
                                @endif
                            </td>
                            <td>
                                <strong class="text-primary">{{ $size->name }}</strong>
                            </td>
                            <td>
                                <small class="text-muted">{{ $size->type ?? 'N/A' }}</small>
                            </td>
                            <td>
                                <small class="text-muted">{{ $size->unit ?? 'N/A' }}</small>
                            </td>
                            <td>
                                <small class="text-muted">{{ $size->value ?? 'N/A' }}</small>
                            </td>
                            <td>
                                <small class="text-muted">{{ $size->created_at->format('M d, Y') }}</small>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <button class="btn btn-sm btn-outline-primary edit-size-btn"
                                        data-bs-toggle="modal"
                                        data-bs-target="#editSizeModal"
                                        wire:click="editSize({{ $size->id }})">
                                        <i class="fa fa-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-danger"
                                        wire:click="delete({{ $size->id }})"
                                        onclick="return confirm('Are you sure you want to delete the size \'{{ $size->name }}\'? This action cannot be undone.')">
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
                    Showing {{ $sizes->firstItem() }} to {{ $sizes->lastItem() }} of {{ $sizes->total() }} sizes
                </div>
                <div>
                    {{ $sizes->links('vendor.pagination.bootstrap-5') }}
                </div>
            </div>
            @else
            <div class="text-center py-5">
                <i class="fa fa-ruler text-muted" style="font-size: 4rem;"></i>
                <h5 class="text-muted mt-3">No Sizes Found</h5>
                <p class="text-muted">Start by adding your first size.</p>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createSizeModal">
                    <i class="fa fa-plus me-2"></i>Add Your First Size
                </button>
            </div>
            @endif
        </div>
    </div>

    <!-- Upload Sizes Modal -->
    <div wire:ignore.self class="modal fade" id="uploadSizesModal" tabindex="-1" aria-labelledby="uploadSizesModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="uploadSizesModalLabel">
                        <i class="fa fa-upload me-2"></i>Upload Sizes
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form wire:submit.prevent="uploadSizes">
                    <div class="modal-body">
                        <div class="mb-4">
                            <h6>Sample CSV/Excel Format:</h6>
                            <small class="text-muted">Your file should have headers in the first row. Images will be downloaded from the URLs provided.</small>
                            <div class="table-responsive mt-2">
                                <table class="table table-bordered table-sm">
                                    <thead class="table-light">
                                        <tr>
                                            <th>name</th>
                                            <th>type</th>
                                            <th>unit</th>
                                            <th>value</th>
                                            <th>image</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Small Bowl</td>
                                            <td>bowl</td>
                                            <td>L</td>
                                            <td>0.5</td>
                                            <td>https://example.com/small-bowl.jpg</td>
                                        </tr>
                                        <tr>
                                            <td>Medium Bucket</td>
                                            <td>bucket</td>
                                            <td>L</td>
                                            <td>2.0</td>
                                            <td>https://example.com/medium-bucket.jpg</td>
                                        </tr>
                                        <tr>
                                            <td>Large Bottle</td>
                                            <td>bottle</td>
                                            <td>L</td>
                                            <td>1.5</td>
                                            <td>https://example.com/large-bottle.jpg</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="uploadFile" class="form-label">Select File *</label>
                            <input type="file" class="form-control" id="uploadFile" wire:model="uploadFile" accept=".xlsx,.xls,.csv" required>
                            @error('uploadFile') <small class="text-danger">{{ $message }}</small> @enderror
                            <small class="text-muted">Supported formats: Excel (.xlsx, .xls) or CSV. Maximum size: 10MB</small>
                        </div>

                        @if($uploadFile)
                        <div class="alert alert-info">
                            <i class="fa fa-info-circle me-2"></i>
                            File selected: <strong>{{ $uploadFile->getClientOriginalName() }}</strong>
                        </div>
                        @endif
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary" wire:loading.attr="disabled" wire:target="uploadSizes">
                            <span wire:loading.remove wire:target="uploadSizes"><i class="fa fa-upload me-2"></i>Upload Sizes</span>
                            <span wire:loading wire:target="uploadSizes"><i class="fa fa-spinner fa-spin me-2"></i>Uploading...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div wire:ignore.self class="modal fade" id="createSizeModal" tabindex="-1" aria-labelledby="createSizeModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="createSizeModalLabel">
                        <i class="fa fa-plus me-2"></i>Add New Size
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form wire:submit.prevent="store">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Size Name *</label>
                                    <input type="text" class="form-control" id="name" wire:model="name" required>
                                    @error('name') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="type" class="form-label">Type</label>
                                    <select class="form-control" id="type" wire:model="type">
                                        <option value="">Select Type</option>
                                        @foreach($type_options as $option)
                                            <option value="{{ $option }}">{{ ucfirst(str_replace('_', ' ', $option)) }}</option>
                                        @endforeach
                                        
                                    </select>
                                    @error('type') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="unit" class="form-label">Unit *</label>
                                    <input type="text" class="form-control" id="unit" wire:model="unit" value="L" required>
                                    @error('unit') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="value" class="form-label">Value *</label>
                                    <input type="text" class="form-control" id="value" wire:model="value" placeholder="1.5" required>
                                    @error('value') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="image" class="form-label">Size Image</label>
                            <input type="file" class="form-control" id="image" wire:model="image" accept="image/*">
                            @error('image') <small class="text-danger">{{ $message }}</small> @enderror
                            <small class="text-muted">Upload an image to represent this size (optional)</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-save me-2"></i>Create Size
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Size Modal -->
    <div wire:ignore.self class="modal fade" id="editSizeModal" tabindex="-1" aria-labelledby="editSizeModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="editSizeModalLabel">
                        <i class="fa fa-edit me-2"></i>Edit Size
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form wire:submit.prevent="update">
                    <div class="modal-body">
                        <input type="hidden" id="edit_id" wire:model="edit_id">

                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="edit_name" class="form-label">Size Name *</label>
                                    <input type="text" class="form-control" id="edit_name" wire:model="edit_name" required>
                                    @error('edit_name') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="edit_type" class="form-label">Type</label>
                                    <select class="form-control" id="edit_type" wire:model="edit_type">
                                        <option value="">Select Type</option>
                                        @foreach($type_options as $option)
                                            <option value="{{ $option }}">{{ ucfirst(str_replace('_', ' ', $option)) }}</option>
                                        @endforeach
                                    </select>
                                    @error('edit_type') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="edit_unit" class="form-label">Unit *</label>
                                    <input type="text" class="form-control" id="edit_unit" wire:model="edit_unit" required>
                                    @error('edit_unit') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="edit_value" class="form-label">Value *</label>
                                    <input type="text" class="form-control" id="edit_value" wire:model="edit_value" required>
                                    @error('edit_value') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="edit_image" class="form-label">Size Image</label>
                            <input type="file" class="form-control" id="edit_image" wire:model="edit_image" accept="image/*">
                            @error('edit_image') <small class="text-danger">{{ $message }}</small> @enderror
                            <small class="text-muted">Upload a new image to replace the existing one (optional)</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-save me-2"></i>Update Size
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>