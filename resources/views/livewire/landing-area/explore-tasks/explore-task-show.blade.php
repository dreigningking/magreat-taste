<div class="container py-5">
    <div class="row">
        <!-- Main Content -->
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body p-4">
                    <!-- Task Header -->
                    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4">
                        <h1 class="h2 fw-bold text-dark mb-3 mb-md-0">{{ $task->title }}</h1>
                        <span class="badge bg-success fs-6 px-3 py-2">
                            {{ $task->user->country->currency_symbol ?? '$' }}{{ number_format($task->budget_per_person, 2) }}
                        </span>
                    </div>

                    <!-- Task Meta -->
                    <div class="d-flex flex-wrap align-items-center gap-4 text-muted mb-4 pb-4 border-bottom">
                        <span class="d-flex align-items-center gap-2">
                            <i class="fas fa-building text-primary"></i>
                            <span>{{ $task->user->username }}</span>
                        </span>
                        <span class="d-flex align-items-center gap-2">
                            <i class="fas fa-tasks text-primary"></i>
                            <span>{{ $task->template->name ?? 'N/A' }}</span>
                        </span>
                        <span class="d-flex align-items-center gap-2">
                            <i class="fas fa-clock text-primary"></i>
                            <span>{{ floor($task->expected_completion_minutes / 60) }} hours estimated</span>
                        </span>
                        <span class="d-flex align-items-center gap-2">
                            <i class="fas fa-folder text-primary"></i>
                            <span>Platform: {{ $task->platform->name ?? 'N/A' }}</span>
                        </span>
                    </div>

                    <!-- Job Description -->
                    <div class="mb-4">
                        <h3 class="h5 fw-semibold text-dark mb-3">Job Description</h3>
                        <div class="text-muted" style="white-space: pre-line;">{{ $task->description }}</div>
                    </div>
                    
                    <!-- Requirements -->
                    @if (!empty($task->requirements) && count($task->requirements) > 0)
                    <div class="mb-4">
                        <h3 class="h5 fw-semibold text-dark mb-3">Requirements</h3>
                        <ul class="list-unstyled text-muted">
                            @foreach($task->requirements as $requirement)
                                <li class="mb-2">
                                    <i class="fas fa-check text-success me-2"></i>
                                    {{ $requirement }}
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    <!-- Submission Fields -->
                    <div class="mb-4">
                        <h3 class="h5 fw-semibold text-dark mb-3">Submission Requirements</h3>
                        @if($task->template && $task->template->submission_fields)
                            <ul class="list-unstyled text-muted">
                                @foreach($task->template->submission_fields as $field)
                                    <li class="mb-2">
                                        <i class="fas fa-arrow-right text-primary me-2"></i>
                                        {{ $field['title'] }}
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p class="text-muted">No specific submission requirements provided.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Action Card -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body p-4">
                    @if (session()->has('message'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('message') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif
                    @if (session()->has('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if($hasStarted)
                        <div class="text-center p-4 bg-success bg-opacity-10 rounded">
                            <i class="fas fa-check-circle text-success fs-1 mb-3"></i>
                            <p class="fw-semibold text-success mb-3">You have already started this task.</p>
                            <a href="{{ route('tasks.view', $task) }}" class="btn btn-primary">View My Progress</a>
                        </div>
                    @else
                        <!-- Agreement Checkbox -->
                        <div class="mb-4">
                            <div class="form-check">
                                <input type="checkbox" wire:model.live="agreementAccepted" class="form-check-input" id="agreement">
                                <label class="form-check-label text-muted" for="agreement">
                                    I agree to complete this task according to the requirements and submit my work within the estimated time frame.
                                </label>
                            </div>
                        </div>
                        
                        <div class="d-grid gap-3">
                            <button 
                                wire:click="startTask" 
                                class="btn btn-primary btn-lg fw-semibold"
                                @if(!$agreementAccepted) disabled @endif>
                                <i class="fas fa-play me-2"></i>
                                Start Now
                            </button>
                            @if($isSaved)
                                <button 
                                    wire:click="unsaveTask" 
                                    class="btn btn-outline-secondary">
                                    <i class="fas fa-bookmark me-2"></i>
                                    Unsave Task
                                </button>
                            @else
                                <button 
                                    wire:click="saveTask" 
                                    class="btn btn-outline-secondary">
                                    <i class="far fa-bookmark me-2"></i>
                                    Save for Later
                                </button>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
            
            <!-- Task Stats Card -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body p-4">
                    <h3 class="h6 fw-semibold mb-4">Task Stats</h3>
                    <div class="row g-3">
                        <div class="col-6">
                            <div class="text-center p-3 bg-light rounded">
                                <div class="h4 fw-bold text-primary mb-1">{{ $task->workers->whereNotNull('accepted_at')->count() }}</div>
                                <div class="small text-muted">Enrolled</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-center p-3 bg-light rounded">
                                <div class="h4 fw-bold text-success mb-1">{{ $task->number_of_people }}</div>
                                <div class="small text-muted">Total Slots</div>
                            </div>
                        </div>
                    </div>
                    @if($task->expiry_date)
                    <div class="mt-3 pt-3 border-top">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="text-muted">Expires:</span>
                            <span class="fw-medium">{{ $task->expiry_date->format('M d, Y') }}</span>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Client Info Card -->
            <div class="card shadow-sm border-0">
                <div class="card-body p-4">
                    <h3 class="h6 fw-semibold mb-4">About the Client</h3>
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-light rounded-circle d-flex align-items-center justify-content-center" style="width: 60px; height: 60px;">
                                <i class="fas fa-user text-muted fs-4"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h4 class="h6 fw-semibold mb-1">{{ $task->user->name }}</h4>
                            <div class="small text-muted">
                                Member since {{ $task->user->created_at->format('M Y') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> 