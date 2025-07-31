<div class="content-wrapper">
    @if(session()->has('message'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fa fa-check-circle me-2"></i>{{ session('message') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row mb-4">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <h3 class="font-weight-bold">Meals Management</h3>
            <div>
                <a href="{{ route('meals.create') }}" class="btn btn-primary">
                    <i class="fa fa-plus me-2"></i>Add New Meal
                </a>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-primary">
                <div class="card-body">
                    <h6 class="text-muted">Total Meals</h6>
                    <h3 class="mb-0">{{ $totalMeals }}</h3>
                    <small class="text-muted">All meals</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-success">
                <div class="card-body">
                    <h6 class="text-muted">Active Meals</h6>
                    <h3 class="mb-0">{{ $activeMeals }}</h3>
                    <small class="text-muted">Live meals</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-warning">
                <div class="card-body">
                    <h6 class="text-muted">Categories</h6>
                    <h3 class="mb-0">{{ $totalCategories }}</h3>
                    <small class="text-muted">Meal categories</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-info">
                <div class="card-body">
                    <h6 class="text-muted">Avg Price</h6>
                    <h3 class="mb-0">₦{{ number_format($averagePrice, 2) }}</h3>
                    <small class="text-muted">Per meal</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters and Search -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <label class="form-label">Search Meals</label>
                    <input type="text" wire:model.live="search" class="form-control" placeholder="Search by name, excerpt...">
                </div>
                <div class="col-md-3">
                    <label class="form-label">Filter by Category</label>
                    <select wire:model.live="categoryFilter" class="form-control">
                        <option value="">All Categories</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Sort by</label>
                    <select wire:model.live="sortBy" class="form-control">
                        <option value="created_at">Date Created</option>
                        <option value="name">Name</option>
                        <option value="from_price">Price (Low to High)</option>
                        <option value="from_price_desc">Price (High to Low)</option>
                        <option value="category_id">Category</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Status</label>
                    <select wire:model.live="statusFilter" class="form-control">
                        <option value="">All Status</option>
                        <option value="1">Active</option>
                        <option value="0">Inactive</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- Meals Table -->
    <div class="card">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="fa fa-utensils me-2"></i>All Meals</h5>
            <span class="badge bg-light text-dark">{{ $meals->total() }} Meals</span>
        </div>
        <div class="card-body">
            @if($meals->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Image</th>
                                <th>Meal Name</th>
                                <th>Excerpt</th>
                                <th>Category</th>
                                <th>From Price</th>
                                <th>Foods Count</th>
                                <th>Status</th>
                                <th>Created</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($meals as $index => $meal)
                            <tr>
                                <td>{{ $meals->firstItem() + $index }}</td>
                                <td>
                                    @if($meal->image)
                                        <img src="{{ $meal->image_url }}" alt="{{ $meal->name }}" class="rounded" style="width: 60px; height: 40px; object-fit: cover;">
                                    @else
                                        <div class="bg-secondary rounded d-flex align-items-center justify-content-center" style="width: 60px; height: 40px;">
                                            <i class="fa fa-image text-white"></i>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <strong class="text-primary">{{ $meal->name }}</strong>
                                    @if($meal->video)
                                        <br><small class="text-muted"><i class="fa fa-video me-1"></i>Has Video</small>
                                    @endif
                                </td>
                                <td>
                                    <small class="text-muted">{{ Str::limit($meal->excerpt, 80) }}</small>
                                </td>
                                <td>
                                    @if($meal->category)
                                        <span class="badge bg-secondary">{{ $meal->category->name }}</span>
                                    @else
                                        <span class="text-muted">No Category</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-success">{{ $meal->formatted_from_price }}</span>
                                </td>
                                <td>
                                    <span class="badge bg-info">{{ $meal->foods->count() }} foods</span>
                                </td>
                                <td>
                                    @if($meal->is_active)
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-secondary">Inactive</span>
                                    @endif
                                </td>
                                <td>
                                    <small class="text-muted">{{ $meal->created_at->format('M d, Y') }}</small>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('meals.edit', $meal) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                        <button class="btn btn-sm btn-outline-danger" 
                                                wire:click="delete({{ $meal->id }})"
                                                onclick="return confirm('Are you sure you want to delete the meal \'{{ $meal->name }}\'? This action cannot be undone.')">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                        <a href="#" class="btn btn-sm btn-outline-info" target="_blank">
                                            <i class="fa fa-external-link"></i>
                                        </a>
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
                        Showing {{ $meals->firstItem() }} to {{ $meals->lastItem() }} of {{ $meals->total() }} meals
                    </div>
                    <div>
                        {{ $meals->links() }}
                    </div>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fa fa-utensils text-muted" style="font-size: 4rem;"></i>
                    <h5 class="text-muted mt-3">No Meals Found</h5>
                    <p class="text-muted">Start by creating your first meal.</p>
                    <a href="{{ route('meals.create') }}" class="btn btn-primary">
                        <i class="fa fa-plus me-2"></i>Create Your First Meal
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
