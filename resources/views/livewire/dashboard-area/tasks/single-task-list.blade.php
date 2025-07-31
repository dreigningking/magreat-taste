<div>
    <div class="job-card card p-4 mb-4 shadow-sm border-0">
        <div class="row">
            <div class="col-md-2">
                <div class="company-logo">
                    @if($task->platform && $task->platform->image)
                        <img src="{{ $task->platform->image }}" class="img-fluid rounded" alt="{{ $task->platform->name }}">
                    @else
                        <div class="bg-light rounded d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                            <i class="fas fa-building text-muted"></i>
                        </div>
                    @endif
                </div>
            </div>
            <div class="col-md-8">
                <h5 class="mb-2">
                    <a href="#" wire:click="showTaskDetails({{ $task->id }})" class="text-dark text-decoration-none job-title">
                        {{ $task->title }}
                    </a>
                </h5>
                <p class="text-muted mb-2">{{ $task->user->username }}</p>
                <div class="d-flex flex-wrap mb-3">
                    <span class="text-muted me-3 mb-1">
                        <i class="fas fa-tasks me-1"></i>{{ $task->template->name ?? 'N/A' }}
                    </span>
                    <span class="text-muted me-3 mb-1">
                        <i class="fas fa-clock me-1"></i>{{ $task->created_at->diffForHumans() }}
                    </span>
                    <span class="text-muted mb-1">
                        <i class="fas fa-users me-1"></i>{{ $task->workers->whereNotNull('accepted_at')->count() }}/{{ $task->number_of_people }}
                    </span>
                </div>
                <p class="text-muted mb-3" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                    {{ $task->description }}
                </p>
                <div class="d-flex flex-wrap gap-2">
                    @if($task->platform)
                    <span class="badge bg-primary">{{ $task->platform->name }}</span>
                    @endif
                    <span class="badge bg-info">
                        @if($task->expected_completion_minutes < 60)
                            {{ $task->expected_completion_minutes }} mins
                        @elseif($task->expected_completion_minutes < 1440)
                            {{ floor($task->expected_completion_minutes / 60) }} hrs
                        @elseif($task->expected_completion_minutes < 10080)
                            {{ floor($task->expected_completion_minutes / 1440) }} days
                        @elseif($task->expected_completion_minutes < 43200)
                            {{ floor($task->expected_completion_minutes / 10080) }} weeks
                        @else
                            {{ floor($task->expected_completion_minutes / 43200) }} months
                        @endif
                    </span>
                </div>
            </div>
            <div class="col-md-2 text-md-end mt-3 mt-md-0">
                <div class="d-flex flex-column gap-2">
                    <span class="badge bg-success fs-6 px-3 py-2">
                        {{ $task->user->country->currency_symbol ?? '$' }}{{ $task->budget_per_person }}
                    </span>
                    <button wire:click="showTaskDetails({{ $task->id }})" class="btn btn-primary btn-sm">
                        View Details
                    </button>
                    @auth
                    <div class="d-flex gap-1 justify-content-md-end">
                        <button wire:click="hideTask({{ $task->id }})" class="btn btn-sm btn-light text-secondary p-1" title="Hide this task">
                            <i class="fas fa-eye-slash"></i>
                        </button>
                        <button wire:click="reportTask({{ $task->id }})" class="btn btn-sm btn-light text-danger p-1" title="Report this task">
                            <i class="fas fa-flag"></i>
                        </button>
                    </div>
                    @endauth
                </div>
            </div>
        </div>
    </div>
</div>