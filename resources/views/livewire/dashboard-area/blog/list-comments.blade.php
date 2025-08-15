<div class="content-wrapper">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h3 class="font-weight-bold mb-1">Manage Comments</h3>
                    <p class="text-muted mb-0">Approve, reject, or moderate blog comments</p>
                </div>
                <div class="d-flex gap-2">
                    @if(!empty($selectedComments))
                        <button wire:click="bulkApprove" class="btn btn-success btn-sm">
                            <i class="fa fa-check me-1"></i> Approve Selected
                        </button>
                        <button wire:click="bulkReject" class="btn btn-warning btn-sm">
                            <i class="fa fa-ban me-1"></i> Reject Selected
                        </button>
                        <button wire:click="bulkDelete" class="btn btn-danger btn-sm" 
                                onclick="return confirm('Are you sure you want to delete the selected comments?')">
                            <i class="fa fa-trash me-1"></i> Delete Selected
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Status Cards -->
    <div class="row mb-4">
        <div class="col-md-2">
            <div class="card border-primary">
                <div class="card-body text-center">
                    <h4 class="text-primary mb-1">{{ $statusCounts['total'] }}</h4>
                    <small class="text-muted">Total Comments</small>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card border-warning">
                <div class="card-body text-center">
                    <h4 class="text-warning mb-1">{{ $statusCounts['pending'] }}</h4>
                    <small class="text-muted">Pending</small>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card border-success">
                <div class="card-body text-center">
                    <h4 class="text-success mb-1">{{ $statusCounts['approved'] }}</h4>
                    <small class="text-muted">Approved</small>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card border-danger">
                <div class="card-body text-center">
                    <h4 class="text-danger mb-1">{{ $statusCounts['rejected'] }}</h4>
                    <small class="text-muted">Rejected</small>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card border-secondary">
                <div class="card-body text-center">
                    <h4 class="text-secondary mb-1">{{ $statusCounts['spam'] }}</h4>
                    <small class="text-muted">Spam</small>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card border-info">
                <div class="card-body text-center">
                    <h4 class="text-info mb-1">{{ $comments->total() }}</h4>
                    <small class="text-muted">Showing</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <label for="search" class="form-label">Search Comments</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fa fa-search"></i></span>
                        <input type="text" wire:model.live="search" class="form-control" 
                               placeholder="Search by content, name, or email...">
                    </div>
                </div>
                <div class="col-md-3">
                    <label for="statusFilter" class="form-label">Status</label>
                    <select wire:model.live="statusFilter" class="form-select">
                        <option value="">All Statuses</option>
                        <option value="pending">Pending</option>
                        <option value="approved">Approved</option>
                        <option value="rejected">Rejected</option>
                        <option value="spam">Spam</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="postFilter" class="form-label">Blog Post</label>
                    <select wire:model.live="postFilter" class="form-select">
                        <option value="">All Posts</option>
                        @foreach($posts as $post)
                            <option value="{{ $post->id }}">{{ Str::limit($post->title, 50) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button wire:click="$set('search', '')" wire:click="$set('statusFilter', '')" 
                            wire:click="$set('postFilter', '')" class="btn btn-outline-secondary w-100">
                        <i class="fa fa-refresh me-1"></i> Reset
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Comments Table -->
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Comments</h5>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" wire:model.live="selectAll" id="selectAll">
                    <label class="form-check-label" for="selectAll">
                        Select All
                    </label>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            @if($comments->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th width="50">
                                    <input type="checkbox" wire:model.live="selectAll" class="form-check-input">
                                </th>
                                <th>Comment</th>
                                <th>Author</th>
                                <th>Post</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th width="200">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($comments as $comment)
                                <tr>
                                    <td>
                                        <input type="checkbox" wire:model.live="selectedComments" 
                                               value="{{ $comment->id }}" class="form-check-input">
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <div class="fw-medium mb-1">
                                                {{ Str::limit($comment->content, 100) }}
                                            </div>
                                            @if($comment->mentions)
                                                <small class="text-muted">
                                                    Mentions: {{ implode(', ', $comment->mentions) }}
                                                </small>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <span class="fw-medium">{{ $comment->guest_name ?? 'Anonymous' }}</span>
                                            <small class="text-muted">{{ $comment->guest_email ?? 'No email' }}</small>
                                            @if($comment->ip_address)
                                                <small class="text-muted">{{ $comment->ip_address }}</small>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <a href="{{ route('blog.show', $comment->post->slug) }}" 
                                               target="_blank" class="text-decoration-none">
                                                <span class="fw-medium text-primary">
                                                    {{ Str::limit($comment->post->title, 40) }}
                                                </span>
                                            </a>
                                            <small class="text-muted">
                                                {{ $comment->post->category->name ?? 'Uncategorized' }}
                                            </small>
                                        </div>
                                    </td>
                                    <td>
                                        @switch($comment->status)
                                            @case('pending')
                                                <span class="badge bg-warning">Pending</span>
                                                @break
                                            @case('approved')
                                                <span class="badge bg-success">Approved</span>
                                                @break
                                            @case('rejected')
                                                <span class="badge bg-danger">Rejected</span>
                                                @break
                                            @case('spam')
                                                <span class="badge bg-secondary">Spam</span>
                                                @break
                                        @endswitch
                                        
                                        @if($comment->is_featured)
                                            <span class="badge bg-info ms-1">Featured</span>
                                        @endif
                                        
                                        @if($comment->approved_at && $comment->approvedBy)
                                            <div class="mt-1">
                                                <small class="text-muted">
                                                    Approved by {{ $comment->approvedBy->name }}
                                                </small>
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <span class="fw-medium">{{ $comment->created_at->format('M d, Y') }}</span>
                                            <small class="text-muted">{{ $comment->created_at->format('H:i') }}</small>
                                            <small class="text-muted">{{ $comment->time_ago }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm" role="group">
                                            @if($comment->status === 'pending')
                                                <button wire:click="approveComment({{ $comment->id }})" 
                                                        class="btn btn-success" title="Approve">
                                                    <i class="fa fa-check"></i>
                                                </button>
                                                <button wire:click="rejectComment({{ $comment->id }})" 
                                                        class="btn btn-warning" title="Reject">
                                                    <i class="fa fa-ban"></i>
                                                </button>
                                            @endif
                                            
                                            @if($comment->status !== 'spam')
                                                <button wire:click="markAsSpam({{ $comment->id }})" 
                                                        class="btn btn-secondary" title="Mark as Spam">
                                                    <i class="fa fa-shield"></i>
                                                </button>
                                            @endif
                                            
                                            <button wire:click="toggleFeatured({{ $comment->id }})" 
                                                    class="btn btn-{{ $comment->is_featured ? 'warning' : 'info' }}" 
                                                    title="{{ $comment->is_featured ? 'Unfeature' : 'Feature' }}">
                                                <i class="fa fa-star"></i>
                                            </button>
                                            
                                            <button wire:click="confirmDelete({{ $comment->id }})" 
                                                    class="btn btn-danger" title="Delete">
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
                <div class="d-flex justify-content-between align-items-center p-3 border-top">
                    <div class="text-muted">
                        Showing {{ $comments->firstItem() }} to {{ $comments->lastItem() }} 
                        of {{ $comments->total() }} comments
                    </div>
                    <div>
                        {{ $comments->links() }}
                    </div>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fa fa-comments text-muted" style="font-size: 3rem;"></i>
                    <h5 class="text-muted mt-3">No comments found</h5>
                    <p class="text-muted">Try adjusting your filters or search terms.</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    @if($showDeleteModal)
        <div class="modal fade show" style="display: block;" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Confirm Delete</h5>
                        <button type="button" class="btn-close" wire:click="$set('showDeleteModal', false)"></button>
                    </div>
                    <div class="modal-body">
                        <p>Are you sure you want to delete this comment? This action cannot be undone.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" wire:click="$set('showDeleteModal', false)">
                            Cancel
                        </button>
                        <button type="button" class="btn btn-danger" wire:click="deleteConfirmed">
                            Delete Comment
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-backdrop fade show"></div>
    @endif

    <!-- Flash Messages -->
    @if(session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show position-fixed" 
             style="top: 20px; right: 20px; z-index: 9999;">
            <i class="fa fa-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session()->has('error'))
        <div class="alert alert-danger alert-dismissible fade show position-fixed" 
             style="top: 20px; right: 20px; z-index: 9999;">
            <i class="fa fa-exclamation-circle me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
</div>

@push('scripts')
<script>
    // Auto-hide flash messages after 5 seconds
    document.addEventListener('DOMContentLoaded', function() {
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);
    });
</script>
@endpush
