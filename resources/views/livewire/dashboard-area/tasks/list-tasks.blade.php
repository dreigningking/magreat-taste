<div class="content-wrapper">
    <!-- Header -->
    <div class="card mb-4">
        <div class="card-body d-flex justify-content-between align-items-center">
            <div>
                <h1 class="h4 mb-0">
                    @switch($status)
                        @case('accepted')
                            Ongoing Tasks
                            @break
                        @case('completed')
                            Completed Tasks
                            @break
                        @case('submitted')
                            Submitted Tasks
                            @break
                        @case('saved')
                            Saved Tasks
                            @break
                        @default
                            My Tasks
                    @endswitch
                </h1>
                <p class="text-muted mb-0">
                    @switch($status)
                        @case('accepted')
                            Tasks you're currently working on
                            @break
                        @case('completed')
                            Tasks you've successfully completed
                            @break
                        @case('submitted')
                            Tasks you've submitted for review
                            @break
                        @case('saved')
                            Tasks you've saved for later
                            @break
                        @default
                            Manage all your signed-up tasks in one place
                    @endswitch
                </p>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row g-3 mb-4">
        <div class="col-md-2">
            <div class="card h-100">
                <div class="card-body d-flex flex-column justify-content-between">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h6 class="mb-0">Total</h6>
                        <span class="bg-primary bg-opacity-10 rounded-circle p-2"><i class="ri-briefcase-4-line text-primary"></i></span>
                    </div>
                    <h3 class="fw-bold mb-0">{{ $stats['total'] }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card h-100">
                <div class="card-body d-flex flex-column justify-content-between">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h6 class="mb-0">Ongoing</h6>
                        <span class="bg-primary bg-opacity-10 rounded-circle p-2"><i class="ri-time-line text-primary"></i></span>
                    </div>
                    <h3 class="fw-bold mb-0">{{ $stats['accepted'] }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card h-100">
                <div class="card-body d-flex flex-column justify-content-between">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h6 class="mb-0">Saved</h6>
                        <span class="bg-warning bg-opacity-10 rounded-circle p-2"><i class="ri-bookmark-line text-warning"></i></span>
                    </div>
                    <h3 class="fw-bold mb-0">{{ $stats['saved'] }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card h-100">
                <div class="card-body d-flex flex-column justify-content-between">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h6 class="mb-0">Submitted</h6>
                        <span class="bg-purple bg-opacity-10 rounded-circle p-2"><i class="ri-send-plane-line text-purple"></i></span>
                    </div>
                    <h3 class="fw-bold mb-0">{{ $stats['submitted'] }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card h-100">
                <div class="card-body d-flex flex-column justify-content-between">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h6 class="mb-0">Completed</h6>
                        <span class="bg-success bg-opacity-10 rounded-circle p-2"><i class="ri-check-double-line text-success"></i></span>
                    </div>
                    <h3 class="fw-bold mb-0">{{ $stats['completed'] }}</h3>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card h-100">
                <div class="card-body d-flex flex-column justify-content-between">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h6 class="mb-0">Cancelled</h6>
                        <span class="bg-danger bg-opacity-10 rounded-circle p-2"><i class="ri-close-circle-line text-danger"></i></span>
                    </div>
                    <h3 class="fw-bold mb-0">{{ $stats['cancelled'] }}</h3>
                </div>
            </div>
        </div>
    </div>

    <!-- Status Tabs -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="btn-group w-100" role="group">
                <a href="{{ route('tasks.index') }}" class="btn {{ $status === 'all' ? 'btn-primary' : 'btn-outline-secondary' }}">
                    All Tasks ({{ $stats['total'] }})
                </a>
                <a href="{{ route('tasks.ongoing') }}" class="btn {{ $status === 'accepted' ? 'btn-primary' : 'btn-outline-secondary' }}">
                    Ongoing ({{ $stats['accepted'] }})
                </a>
                <a href="{{ route('tasks.saved') }}" class="btn {{ $status === 'saved' ? 'btn-primary' : 'btn-outline-secondary' }}">
                    Saved ({{ $stats['saved'] }})
                </a>
                <a href="{{ route('tasks.submitted') }}" class="btn {{ $status === 'submitted' ? 'btn-primary' : 'btn-outline-secondary' }}">
                    Submitted ({{ $stats['submitted'] }})
                </a>
                <a href="{{ route('tasks.completed') }}" class="btn {{ $status === 'completed' ? 'btn-primary' : 'btn-outline-secondary' }}">
                    Completed ({{ $stats['completed'] }})
                </a>
            </div>
        </div>
    </div>

    <!-- Search and Filter Controls -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-8">
                    <div class="input-group">
                        <input type="text" wire:model.live.debounce.300ms="search" class="form-control" placeholder="Search tasks by title or description...">
                        <span class="input-group-text"><i class="ri-search-line"></i></span>
                    </div>
                </div>
                <div class="col-md-4">
                    <select wire:model.live="sortBy" class="form-select">
                        <option value="latest">Latest First</option>
                        <option value="oldest">Oldest First</option>
                        <option value="budget_desc">Budget (High to Low)</option>
                        <option value="budget_asc">Budget (Low to High)</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- Tasks List -->
    <div class="card">
        <div class="card-body">
            <!-- Mobile Cards View -->
            <div class="d-md-none">
                <div class="row g-3">
                    @forelse($tasks as $taskWorker)
                        <div class="col-12">
                            <div class="card shadow-sm">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <div>
                                            <h5 class="mb-1">{{ $taskWorker->task->title }}</h5>
                                            <div class="text-muted small">{{ $taskWorker->task->platform->name ?? 'N/A' }}</div>
                                        </div>
                                        <div>
                                            @if($taskWorker->accepted_at && !$taskWorker->submitted_at && !$taskWorker->completed_at && !$taskWorker->cancelled_at)
                                                <span class="badge bg-primary">Ongoing</span>
                                            @elseif($taskWorker->saved_at && !$taskWorker->accepted_at && !$taskWorker->submitted_at && !$taskWorker->completed_at && !$taskWorker->cancelled_at)
                                                <span class="badge bg-warning">Saved</span>
                                            @elseif($taskWorker->submitted_at && !$taskWorker->completed_at && !$taskWorker->cancelled_at)
                                                <span class="badge bg-purple">Submitted</span>
                                            @elseif($taskWorker->completed_at)
                                                <span class="badge bg-success">Completed</span>
                                            @elseif($taskWorker->cancelled_at)
                                                <span class="badge bg-danger">Cancelled</span>
                                            @else
                                                <span class="badge bg-secondary">Pending</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-6 small"><span class="text-muted">Budget:</span> <span class="fw-semibold">{{ $taskWorker->task->user->country->currency_symbol ?? '$' }}{{ number_format($taskWorker->task->budget_per_person, 2) }}</span></div>
                                        <div class="col-6 small"><span class="text-muted">Signed Up:</span> <span class="fw-semibold">{{ $taskWorker->created_at->format('M d, Y') }}</span></div>
                                    </div>
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('tasks.view', $taskWorker->task) }}" class="btn btn-primary btn-sm flex-fill"><i class="ri-eye-line me-1"></i> View</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12 text-center text-muted py-4">
                            <i class="ri-task-line display-4 mb-2"></i>
                            <p>No tasks found</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Desktop Table View -->
            <div class="d-none d-md-block table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Task Title</th>
                            <th>Platform</th>
                            <th>Signed Up Date</th>
                            <th>Budget</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tasks as $taskWorker)
                            <tr>
                                <td>{{ $taskWorker->task->title }}</td>
                                <td>{{ $taskWorker->task->platform->name ?? 'N/A' }}</td>
                                <td>{{ $taskWorker->created_at->format('M d, Y') }}</td>
                                <td>{{ $taskWorker->task->user->country->currency_symbol ?? '$' }}{{ number_format($taskWorker->task->budget_per_person, 2) }}</td>
                                <td>
                                    @if($taskWorker->accepted_at && !$taskWorker->submitted_at && !$taskWorker->completed_at && !$taskWorker->cancelled_at)
                                        <span class="badge bg-primary">Ongoing</span>
                                    @elseif($taskWorker->saved_at && !$taskWorker->accepted_at && !$taskWorker->submitted_at && !$taskWorker->completed_at && !$taskWorker->cancelled_at)
                                        <span class="badge bg-warning">Saved</span>
                                    @elseif($taskWorker->submitted_at && !$taskWorker->completed_at && !$taskWorker->cancelled_at)
                                        <span class="badge bg-purple">Submitted</span>
                                    @elseif($taskWorker->completed_at)
                                        <span class="badge bg-success">Completed</span>
                                    @elseif($taskWorker->cancelled_at)
                                        <span class="badge bg-danger">Cancelled</span>
                                    @else
                                        <span class="badge bg-secondary">Pending</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('tasks.view', $taskWorker->task) }}" class="btn btn-primary btn-sm me-1"><i class="ri-eye-line me-1"></i> View</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">No tasks found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-between align-items-center mt-4">
                <div class="text-muted small">
                    Showing <span class="fw-semibold">{{ $tasks->firstItem() }}</span> to <span class="fw-semibold">{{ $tasks->lastItem() }}</span> of <span class="fw-semibold">{{ $tasks->total() }}</span> results
                </div>
                <div>
                    {{ $tasks->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
