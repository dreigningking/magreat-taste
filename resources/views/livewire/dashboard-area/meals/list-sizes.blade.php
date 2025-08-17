<div class="content-wrapper">
    @if(session()->has('message'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fa fa-check-circle me-2"></i>{{ session('message') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="row mb-4">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <h3 class="font-weight-bold">Size Management</h3>
            <div>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createSizeModal">
                    <i class="fa fa-plus me-2"></i>Add New Size
                </button>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card border-primary">
                <div class="card-body">
                    <h6 class="text-muted">Total Sizes</h6>
                    <h3 class="mb-0">{{ $totalSizes }}</h3>
                    <small class="text-muted">All available sizes</small>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card border-info">
                <div class="card-body">
                    <h6 class="text-muted">Size Types</h6>
                    <h3 class="mb-0">{{ $sizes->count() }}</h3>
                    <small class="text-muted">Currently displayed</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Sizes Table -->
    <div class="card">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="fa fa-ruler me-2"></i>All Sizes</h5>
            <span class="badge bg-light text-dark">{{ $sizes->total() }} Sizes</span>
        </div>
        <div class="card-body">
            @if($sizes->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Image</th>
                            <th>Size Name</th>
                            <th>Slug</th>
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
                                <small class="text-muted">{{ $size->slug }}</small>
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
                    {{ $sizes->links() }}
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

    <!-- Create Size Modal -->
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
                        <div class="mb-3">
                            <label for="name" class="form-label">Size Name *</label>
                            <input type="text" class="form-control" id="name" wire:model="name" required>
                            @error('name') <small class="text-danger">{{ $message }}</small> @enderror
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

                        <div class="mb-3">
                            <label for="edit_name" class="form-label">Size Name *</label>
                            <input type="text" class="form-control" id="edit_name" wire:model="edit_name" required>
                            @error('edit_name') <small class="text-danger">{{ $message }}</small> @enderror
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