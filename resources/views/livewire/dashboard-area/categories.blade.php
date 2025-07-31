<div class="content-wrapper">
    @if(session()->has('message'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="ri-check-line me-2"></i>{{ session('message') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="row mb-4">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <h3 class="font-weight-bold">Categories Management</h3>
            <button class="btn btn-sm btn-outline-primary">
                <i class="mdi mdi-calendar"></i> Switch to Dashboard
            </button>
        </div>
    </div>

    <div class="row">
        <!-- Add Category Form -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="ri-add-line me-2"></i>Add New Category</h5>
                </div>
                <div class="card-body">
                    <form wire:submit.prevent="store">
                        <div class="mb-3">
                            <label for="name" class="form-label">Category Name *</label>
                            <input type="text" class="form-control" id="name" wire:model="name" placeholder="Enter category name" required>
                            @error('name') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        

                        <div class="mb-3">
                            <label for="description" class="form-label">Description *</label>
                            <textarea class="form-control" id="description" wire:model="description" rows="3" placeholder="Enter category description" required></textarea>
                            @error('description') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <div class="mb-3">
                            <label for="image" class="form-label">Image URL</label>
                            <input type="url" class="form-control" id="image" wire:model="image" placeholder="https://example.com/image.jpg">
                            @error('image') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <div class="mb-3">
                            <label for="type" class="form-label">Type</label>
                            <select class="form-select" id="type" wire:model="type">
                                <option value="post">Post</option>
                                <option value="meal">Meal</option>
                            </select>
                            @error('type') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input ms-0" value="1" type="checkbox" id="is_active" wire:model="is_active" checked>
                                <label class="form-check-label" for="is_active">
                                    Active Category
                                </label>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">
                            <i class="ri-save-line me-2"></i>Add Category
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Categories Table -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="ri-list-check me-2"></i>All Categories</h5>
                    <span class="badge bg-light text-dark">{{ count($categories) }} Categories</span>
                </div>
                <div class="card-body">
                    @if(count($categories) > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Image</th>
                                    <th>Name</th>
                                    <th>Type</th>
                                    <th>Status</th>
                                    <th>Created</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($categories as $index => $category)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        @if($category->image)
                                        <img src="{{ $category->image }}" alt="{{ $category->name }}" class="rounded" style="width: 40px; height: 40px; object-fit: cover;">
                                        @else
                                        <div class="bg-secondary rounded d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                            <i class="ri-image-line text-white"></i>
                                        </div>
                                        @endif
                                    </td>
                                    <td>
                                        <strong>{{ $category->name }}</strong>
                                        @if($category->description)
                                        <br><small class="text-muted">{{ Str::limit($category->description, 50) }}</small>
                                        @endif
                                    </td>
                                    
                                    <td>
                                        <span class="badge bg-info">{{ ucfirst($category->type) }}</span>
                                    </td>
                                    <td>
                                        @if($category->is_active)
                                        <span class="badge bg-success">Active</span>
                                        @else
                                        <span class="badge bg-secondary">Inactive</span>
                                        @endif
                                    </td>
                                    <td>
                                        <small class="text-muted">{{ $category->created_at->format('M d, Y') }}</small>
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-outline-primary edit-btn"
                                            data-bs-toggle="modal"
                                            data-bs-target="#editCategoryModal"
                                            data-id="{{ $category->id }}"
                                            data-name="{{ $category->name }}"
                                            
                                            data-description="{{ $category->description }}"
                                            data-image="{{ $category->image }}"
                                            data-type="{{ $category->type }}"
                                            data-is-active="{{ $category->is_active ? 1 : 0 }}">
                                            <i class="fa fa-edit"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-danger delete-btn"
                                            onclick="confirmDelete({{ $category->id }}, '{{ $category->name }}')">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="text-center py-5">
                        <i class="ri-folder-line text-muted" style="font-size: 4rem;"></i>
                        <h5 class="text-muted mt-3">No Categories Found</h5>
                        <p class="text-muted">Start by adding your first category using the form on the left.</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <!-- Edit Category Modal -->
    <div wire:ignore.self class="modal fade" id="editCategoryModal" tabindex="-1" aria-labelledby="editCategoryModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="editCategoryModalLabel">
                        <i class="ri-edit-line me-2"></i>Edit Category
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form wire:submit.prevent="update">
                    <div class="modal-body">
                        <input type="hidden" id="edit_id" wire:model="edit_id">
                        @error('edit_id') <small class="text-danger">{{ $message }}</small> @enderror

                        <div class="mb-3">
                            <label for="edit_name" class="form-label">Category Name *</label>
                            <input type="text" class="form-control" id="edit_name" wire:model="edit_name" required>
                            @error('edit_name') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        

                        <div class="mb-3">
                            <label for="edit_description" class="form-label">Description *</label>
                            <textarea class="form-control" id="edit_description" wire:model="edit_description" rows="3" required></textarea>
                            @error('edit_description') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <div class="mb-3">
                            <label for="edit_image" class="form-label">Image URL</label>
                            <input type="url" class="form-control" id="edit_image" wire:model="edit_image">
                            @error('edit_image') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <div class="mb-3">
                            <label for="edit_type" class="form-label">Type</label>
                            <select class="form-select" id="edit_type" wire:model="edit_type">
                                <option value="post">Post</option>
                                <option value="meal">Meal</option>
                            </select>
                            @error('edit_type') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <div class="m-3">
                            <div class="form-check">
                                <input class="form-check-input ms-0" value="1" type="checkbox" id="edit_is_active" wire:model="edit_is_active" @if($edit_is_active) checked @endif>
                                <label class="form-check-label" for="edit_is_active">
                                    Active Category
                                </label>
                            </div>
                        </div>
                        @error('edit_is_active') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="ri-save-line me-2"></i>Update Category
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>



@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const editModal = document.getElementById('editCategoryModal');
        const editBtns = document.querySelectorAll('.edit-btn');
        const deleteBtns = document.querySelectorAll('.delete-btn');

        editBtns.forEach(btn => {
            btn.addEventListener('click', function(event) {
                console.log('edit btn clicked');
                const button = event.currentTarget;
                const id = button.dataset.id;
                const name = button.dataset.name;
                const description = button.dataset.description;
                const image = button.dataset.image;
                const type = button.dataset.type;
                const isActive = button.dataset.isActive;
                console.log(isActive)
                window.Livewire.dispatch('editCategory', {
                    id: button.dataset.id,
                    name: button.dataset.name,
                    description: button.dataset.description,
                    image: button.dataset.image,
                    type: button.dataset.type,
                    isActive: button.dataset.isActive
                });
                
            });
        });

        // Clear validation errors when modal opens
        editModal.addEventListener('show.bs.modal', function() {
            const livewireId = document.querySelector('[wire\\:id]').getAttribute('wire:id');
            const livewireComponent = window.Livewire.find(livewireId);
            
            if (livewireComponent) {
                // Clear validation errors and reset form
                livewireComponent.call('clearValidationErrors');
            }
        });
    });

    // Confirm delete function
    function confirmDelete(id, name) {
        if (confirm('Are you sure you want to delete the category "' + name + '"? This action cannot be undone.')) {
            const livewireId = document.querySelector('[wire\\:id]').getAttribute('wire:id');
            const livewireComponent = window.Livewire.find(livewireId);
            
            if (livewireComponent) {
                livewireComponent.call('delete', id);
            }
        }
    }
</script>
@endpush