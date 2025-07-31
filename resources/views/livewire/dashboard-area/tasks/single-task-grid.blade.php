{{--
<div class="card h-100 shadow-sm">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-start mb-3">
            <div>
                <span class="badge bg-light text-secondary mb-2">{{ $task->platform ?? 'Uncategorized' }}</span>
                <h5 class="fw-semibold mb-1">{{ $task->title }}</h5>
            </div>
            <span class="h5 fw-bold text-primary">{{ $task->user->country->currency_symbol ?? '$' }}{{ $task->budget_per_person }}</span>
        </div>
        <p class="text-muted mb-3" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">{{ $task->description }}</p>
        <div class="d-flex justify-content-between text-muted small mb-3">
            <div class="d-flex align-items-center">
                <i class="ri-time-line me-1"></i>
                <span>{{ $task->estimated_time }}</span>
            </div>
            <div class="d-flex align-items-center">
                <i class="ri-user-line me-1"></i>
                <span>{{ $task->user->name ?? 'Anonymous' }}</span>
            </div>
        </div>
        <button class="btn btn-primary w-100">View Details</button>
    </div>
</div>
--}}
<div class="job-card card p-4">
    <a href="{{ route('explore.task',$task) }}">
        <div class="row">
            <div class="col-md-2">
                <img src="{{ $task->platform->image }}" class="img-fluid rounded" alt="Company Logo">
            </div>
            <div class="col-md-8">
                <h5>{{ $task->title }}</h5>
                <p class="text-muted mb-1">
                    {{
                        \Illuminate\Support\Str::words(strip_tags($task->description), 10, '...')
                    }}
                </p>
                <div class="d-flex">
                    <p class="text-muted me-3"><i class="fas fa-clock me-1"></i>{{ $task->estimated_time }}</p>
                    <p class="text-muted"><i class="fas fa-user me-1"></i>{{ $task->user->name ?? 'Anonymous' }}</p>
                </div>
            </div>
            <div class="col-md-2 text-end">
                <span class="job-type fulltime">{{ $task->user->country->currency_symbol ?? '$' }}{{ $task->budget_per_person }}</span>
            </div>
        </div>
    </a>
</div>