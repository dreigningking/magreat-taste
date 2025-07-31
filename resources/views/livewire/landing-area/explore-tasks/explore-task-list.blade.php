<div>
    <!-- Flash Messages -->
    @if (session()->has('message'))
        <div class="alert alert-success alert-dismissible fade show position-fixed top-0 end-0 m-3" style="z-index: 1060;">
            {{ session('message') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if (session()->has('error'))
        <div class="alert alert-danger alert-dismissible fade show position-fixed top-0 end-0 m-3" style="z-index: 1060;">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Hero Section -->
    <section class="page-hero">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <h1 class="page-title">Find Your Dream Job</h1>
                    <p class="page-subtitle">Browse thousands of job opportunities and find the perfect match for your skills and career goals</p>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Main Content -->
    <div class="container mt-5">
        <div class="row mb-5">
            <div class="col-lg-3">
                <!-- Filter Toggle Button for Mobile -->
                <button class="filter-toggle d-lg-none w-100 mb-3" type="button" data-bs-toggle="collapse" data-bs-target="#filterColumn">
                    <i class="fas fa-filter me-2"></i> Show Filters
                </button>
                
                <!-- Filters Column -->
                <div class="collapse d-lg-block" id="filterColumn">
                    <!-- Search Filter -->
                    <div class="filter-card mb-4">
                        <div class="filter-header">
                            <h5 class="filter-title">Search</h5>
                        </div>
                        <div class="filter-group">
                            <input type="text" wire:model.live.debounce.300ms="search" class="form-control" placeholder="Search for jobs...">
                        </div>
                    </div>

                    <!-- Platform Filter -->
                    <div class="filter-card mb-4">
                        <div class="filter-header">
                            <h5 class="filter-title">Platforms</h5>
                        </div>
                        <div class="filter-group">
                            @foreach($platforms as $platform)
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" wire:model.live="selectedPlatforms" value="{{ $platform->id }}" id="platform-{{ $platform->id }}">
                                <label class="form-check-label" for="platform-{{ $platform->id }}">
                                    {{ $platform->name }}
                                </label>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    
                    <!-- Price Range Filter -->
                    <div class="filter-card mb-4">
                        <div class="filter-header">
                            <h5 class="filter-title">Price Range</h5>
                        </div>
                        <div class="filter-group">
                            <div class="mb-3">
                                <input type="range" wire:model.live="maxPrice" min="0" max="1000" class="form-range" id="priceRange">
                                <div class="d-flex justify-content-between text-sm text-muted mt-1">
                                    <span>${{ $minPrice }}</span>
                                    <span>${{ $maxPrice }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Duration Filter -->
                    <div class="filter-card mb-4">
                        <div class="filter-header">
                            <h5 class="filter-title">Task Duration</h5>
                        </div>
                        <div class="filter-group">
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" wire:model.live="selectedDurations" value="less_than_1_hour" id="duration-1">
                                <label class="form-check-label" for="duration-1">
                                    Less than 1 hour
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" wire:model.live="selectedDurations" value="1_3_hours" id="duration-2">
                                <label class="form-check-label" for="duration-2">
                                    1-3 hours
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" wire:model.live="selectedDurations" value="3_6_hours" id="duration-3">
                                <label class="form-check-label" for="duration-3">
                                    3-6 hours
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" wire:model.live="selectedDurations" value="6_plus_hours" id="duration-4">
                                <label class="form-check-label" for="duration-4">
                                    6+ hours
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <button wire:click="clearFilters" class="btn btn-outline-secondary w-100 mb-3">Clear All Filters</button>
                </div>
            </div>
            
            <div class="col-lg-9">
                <div class="d-md-flex justify-content-between align-items-center mb-4">
                    <h4 class="mb-0">Found {{ $totalTasks }} jobs matching your criteria</h4>
                    <div class="d-flex justify-content-end align-items-center">
                        <span class="me-2">Sort by:</span>
                        <select wire:model.live="sortBy" class="form-select form-select-sm w-auto">
                            <option value="latest">Newest First</option>
                            <option value="highest_paid">Highest Paying</option>
                            <option value="shortest_time">Quickest Tasks</option>
                            <option value="popular">Most Popular</option>
                        </select>
                    </div>
                </div>
                
                <!-- Job Listings -->
                @if($tasks->count() > 0)
                    @foreach($tasks as $task)
                        @livewire('dashboard-area.tasks.single-task-list', ['task' => $task], key('task-'.$task->id))
                    @endforeach
                @else
                    <div class="text-center text-muted py-5">
                        <i class="fas fa-search fa-3x mb-3"></i>
                        <h5>No tasks found</h5>
                        <p>Try adjusting your search criteria or filters</p>
                    </div>
                @endif
                
                <!-- Pagination -->
                <div class="mt-5">
                    {{ $tasks->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Task Details Modal -->
    @if($showModal && $selectedTask)
    <div class="modal fade show d-block" tabindex="-1" style="background:rgba(0,0,0,0.5);">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ $selectedTask->title }}</h5>
                    <button type="button" class="btn-close" wire:click="closeModal"></button>
                </div>
                <div class="modal-body">
                    <!-- Meta Info -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="d-flex align-items-center mb-2">
                                <i class="fas fa-dollar-sign text-success me-2"></i>
                                <span>{{ $selectedTask->user->country->currency_symbol ?? '$' }}{{ $selectedTask->budget_per_person }} per person</span>
                            </div>
                            <div class="d-flex align-items-center mb-2">
                                <i class="fas fa-users text-primary me-2"></i>
                                <span>{{ $selectedTask->workers->whereNotNull('accepted_at')->count() }} of {{ $selectedTask->number_of_people }} spots filled</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex align-items-center mb-2">
                                <i class="fas fa-clock text-warning me-2"></i>
                                <span>
                                    @php
                                        $minutes = $selectedTask->expected_completion_minutes;
                                        if ($minutes < 60) {
                                            echo $minutes . " minutes";
                                        } elseif ($minutes < 1440) {
                                            echo floor($minutes / 60) . " hours";
                                        } else {
                                            echo floor($minutes / 1440) . " days";
                                        }
                                    @endphp
                                </span>
                            </div>
                            @if($selectedTask->expiry_date)
                            <div class="d-flex align-items-center">
                                <i class="fas fa-calendar-times text-danger me-2"></i>
                                <span>Expires in {{ $selectedTask->expiry_date->diffForHumans() }}</span>
                            </div>
                            @endif
                        </div>
                    </div>

                    <!-- Description Snippet -->
                    <div class="mb-4">
                        <p class="text-muted">{{ Str::limit($selectedTask->description, 200) }}</p>
                    </div>

                    <!-- Action Buttons -->
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex gap-2">
                            @auth
                            <button 
                                wire:click="hideTask({{ $selectedTask->id }})" 
                                class="btn btn-outline-secondary btn-sm">
                                <i class="fas fa-eye-slash me-1"></i>
                                Hide Task
                            </button>
                            <button 
                                wire:click="reportTask({{ $selectedTask->id }})" 
                                class="btn btn-outline-danger btn-sm">
                                <i class="fas fa-flag me-1"></i>
                                Report
                            </button>
                            @endauth
                        </div>
                        <a href="{{ route('explore.task', $selectedTask) }}" class="btn btn-primary">
                            View Full Details
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Report Task Component -->
    @livewire('dashboard-area.tasks.report-task')
</div>

@push('styles')

@endpush
