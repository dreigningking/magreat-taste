<div class="content-wrapper">
    @if(session()->has('message'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fa fa-check-circle me-2"></i>{{ session('message') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="row mb-4">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <h3 class="font-weight-bold">Food Management</h3>
            <div>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createFoodModal">
                    <i class="fa fa-plus me-2"></i>Add New Food
                </button>

            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-primary">
                <div class="card-body">
                    <h6 class="text-muted">Total Foods</h6>
                    <h3 class="mb-0">{{ $totalFoods }}</h3>
                    <small class="text-muted">All foods</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-success">
                <div class="card-body">
                    <h6 class="text-muted">Total Sizes</h6>
                    <h3 class="mb-0">{{ $totalSizes }}</h3>
                    <small class="text-muted">All sizes</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-warning">
                <div class="card-body">
                    <h6 class="text-muted">Total Meals</h6>
                    <h3 class="mb-0">{{ $totalMeals }}</h3>
                    <small class="text-muted">Active meals</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-info">
                <div class="card-body">
                    <h6 class="text-muted">Avg Price</h6>
                    <h3 class="mb-0">${{ number_format($averagePrice, 2) }}</h3>
                    <small class="text-muted">Per size</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Foods Table -->
    <div class="card">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="fa fa-utensils me-2"></i>All Foods</h5>
            <span class="badge bg-light text-dark">{{ $foods->total() }} Foods</span>
        </div>
        <div class="card-body">
            @if($foods->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Image</th>
                            <th>Food Name</th>
                            <th>Description</th>
                            <th>Sizes & Prices</th>
                            <th>Meals Count</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($foods as $index => $food)
                        <tr>
                            <td>{{ $foods->firstItem() + $index }}</td>
                            <td>
                                @if($food->default_image)
                                <img src="{{ Storage::url($food->default_image) }}" alt="{{ $food->name }}" class="rounded" style="width: 60px; height: 40px; object-fit: cover;">
                                @else
                                <div class="bg-secondary rounded d-flex align-items-center justify-content-center" style="width: 60px; height: 40px;">
                                    <i class="fa fa-image text-white"></i>
                                </div>
                                @endif
                            </td>
                            <td>
                                <strong class="text-primary">{{ $food->name }}</strong>
                            </td>
                            <td>
                                <small class="text-muted">{{ Str::limit($food->description, 80) }}</small>
                            </td>
                            <td>
                                <div class="d-flex flex-column">
                                    @foreach($food->sizes as $size)
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <small class="text-muted">{{ $size->name }}</small>
                                        <span class="badge bg-success">₦{{ number_format($size->pivot->price, 2) }}</span>
                                    </div>
                                    @endforeach
                                    @if($food->sizes->count() > 1)
                                    <small class="text-muted">Range: {{ $food->price_range }}</small>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-info">{{ $food->meals_count }} meals</span>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <button class="btn btn-sm btn-outline-primary edit-food-btn"
                                        data-bs-toggle="modal"
                                        data-bs-target="#editFoodModal"
                                        data-id="{{ $food->id }}">
                                        <i class="fa fa-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-danger"
                                        wire:click="delete({{ $food->id }})"
                                        onclick="return confirm('Are you sure you want to delete the food \'{{ $food->name }}\'? This action cannot be undone.')">
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
                    Showing {{ $foods->firstItem() }} to {{ $foods->lastItem() }} of {{ $foods->total() }} foods
                </div>
<div>
                    {{ $foods->links() }}
                </div>
            </div>
            @else
            <div class="text-center py-5">
                <i class="fa fa-utensils text-muted" style="font-size: 4rem;"></i>
                <h5 class="text-muted mt-3">No Foods Found</h5>
                <p class="text-muted">Start by adding your first food item.</p>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createFoodModal">
                    <i class="fa fa-plus me-2"></i>Add Your First Food
                </button>
            </div>
            @endif
        </div>
    </div>

    <!-- Create Food Modal -->
    <div wire:ignore.self class="modal fade" id="createFoodModal" tabindex="-1" aria-labelledby="createFoodModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="createFoodModalLabel">
                        <i class="fa fa-plus me-2"></i>Add New Food
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form wire:submit.prevent="store">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Food Name *</label>
                                    <input type="text" class="form-control" id="name" wire:model="name" required>
                                    @error('name') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="description" class="form-label">Description *</label>
                                    <textarea class="form-control" id="description" wire:model="description" rows="3" required></textarea>
                                    @error('description') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Sizes Section -->
                        <div class="card mt-3">
                            <div class="card-header">
                                <h6 class="mb-0">Food Sizes & Prices</h6>
                            </div>
                            <div class="card-body">
                                <div id="sizes-container">
                                    @foreach($selectedSizes as $index => $size)
                                    <div class="row mb-3 size-row" data-index="{{ $index }}">
                                        <div class="col-md-5">
                                            <label class="form-label">Select Size *</label>
                                            <select class="form-select" wire:model="selectedSizes.{{ $index }}.size_id" required>
                                                <option value="">Choose a size...</option>
                                                @foreach($availableSizes as $availableSize)
                                                <option value="{{ $availableSize->id }}">{{ $availableSize->name }}</option>
                                                @endforeach
                                            </select>
                                            @error("selectedSizes.{$index}.size_id") <small class="text-danger">{{ $message }}</small> @enderror
                                        </div>
                                        <div class="col-md-5">
                                            <label class="form-label">Price *</label>
                                            <input type="number" class="form-control" wire:model="selectedSizes.{{ $index }}.price" step="0.01" min="0" placeholder="0.00" required>
                                            @error("selectedSizes.{$index}.price") <small class="text-danger">{{ $message }}</small> @enderror
                                        </div>
                                        <div class="col-md-2 d-flex align-items-end">
                                            @if($index > 0)
                                            <button type="button" class="btn btn-outline-danger btn-sm" wire:click="removeSize({{ $index }})">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                            @endif
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                                <button type="button" class="btn btn-outline-primary btn-sm" wire:click="addSize">
                                    <i class="fa fa-plus me-2"></i>Add Size
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-save me-2"></i>Create Food
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Food Modal -->
    <div wire:ignore.self class="modal fade" id="editFoodModal" tabindex="-1" aria-labelledby="editFoodModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="editFoodModalLabel">
                        <i class="fa fa-edit me-2"></i>Edit Food
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form wire:submit.prevent="update">
                    <div class="modal-body">
                        <input type="hidden" id="edit_id" wire:model="edit_id">

                        <div class="row">
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="edit_name" class="form-label">Food Name *</label>
                                    <input type="text" class="form-control" id="edit_name" wire:model="edit_name" required>
                                    @error('edit_name') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="mb-3">
                                    <label for="edit_description" class="form-label">Description *</label>
                                    <textarea class="form-control" id="edit_description" wire:model="edit_description" rows="3" required></textarea>
                                    @error('edit_description') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Edit Sizes Section -->
                        <div class="card mt-3">
                            <div class="card-header">
                                <h6 class="mb-0">Food Sizes & Prices</h6>
                            </div>
                            <div class="card-body">
                                <div id="edit-sizes-container">
                                    @foreach($editSelectedSizes as $index => $size)
                                    <div class="row mb-3 edit-size-row" data-index="{{ $index }}">
                                        <div class="col-md-5">
                                            <label class="form-label">Select Size *</label>
                                            <select class="form-select" wire:model="editSelectedSizes.{{ $index }}.size_id" required>
                                                <option value="">Choose a size...</option>
                                                @foreach($availableSizes as $availableSize)
                                                <option value="{{ $availableSize->id }}">{{ $availableSize->name }}</option>
                                                @endforeach
                                            </select>
                                            @error("editSelectedSizes.{$index}.size_id") <small class="text-danger">{{ $message }}</small> @enderror
                                        </div>
                                        <div class="col-md-5">
                                            <label class="form-label">Price *</label>
                                            <input type="number" class="form-control" wire:model="editSelectedSizes.{{ $index }}.price" step="0.01" min="0" placeholder="0.00" required>
                                            @error("editSelectedSizes.{$index}.price") <small class="text-danger">{{ $message }}</small> @enderror
                                        </div>
                                        <div class="col-md-2 d-flex align-items-end">
                                            @if($index > 0)
                                            <button type="button" class="btn btn-outline-danger btn-sm" wire:click="removeEditSize({{ $index }})">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                            @endif
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                                <button type="button" class="btn btn-outline-primary btn-sm" wire:click="addEditSize">
                                    <i class="fa fa-plus me-2"></i>Add Size
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-save me-2"></i>Update Food
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
        const editBtns = document.querySelectorAll('.edit-food-btn');

        editBtns.forEach(btn => {
            btn.addEventListener('click', function(event) {
                const button = event.currentTarget;
                const id = button.dataset.id;

                // Set the ID in the form
                document.getElementById('edit_id').value = id;

                // Dispatch event to Livewire to populate edit data
                const livewireId = document.querySelector('[wire\\:id]').getAttribute('wire:id');
                const livewireComponent = window.Livewire.find(livewireId);

                if (livewireComponent) {
                    livewireComponent.call('editFood', id);
                }
            });
        });
    });
</script>
@endpush