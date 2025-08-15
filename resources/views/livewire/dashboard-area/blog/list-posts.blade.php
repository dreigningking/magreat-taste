<div class="content-wrapper">
    @if(session()->has('message'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="ri-check-line me-2"></i>{{ session('message') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row mb-4">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <h3 class="font-weight-bold">Blog Posts</h3>
            <div>
                <a href="{{ route('posts.create') }}" class="btn btn-primary">
                    <i class="ri-add-line me-2"></i>Create New Post
                </a>
                
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-primary">
                <div class="card-body">
                    <h6 class="text-muted">Total Posts</h6>
                    <h3 class="mb-0">{{ $totalPosts }}</h3>
                    <small class="text-muted">All time</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-success">
                <div class="card-body">
                    <h6 class="text-muted">Published</h6>
                    <h3 class="mb-0">{{ $publishedPosts }}</h3>
                    <small class="text-muted">Live posts</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-warning">
                <div class="card-body">
                    <h6 class="text-muted">Drafts</h6>
                    <h3 class="mb-0">{{ $draftPosts }}</h3>
                    <small class="text-muted">In progress</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-info">
                <div class="card-body">
                    <h6 class="text-muted">Total Views</h6>
                    <h3 class="mb-0">{{ number_format($totalViews) }}</h3>
                    <small class="text-muted">All posts</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Posts Table -->
    <div class="card">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="ri-article-line me-2"></i>All Posts</h5>
            <span class="badge bg-light text-dark">{{ $posts->total() }} Posts</span>
        </div>
        <div class="card-body">
            @if($posts->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Thumbnail</th>
                                <th>Title & Excerpt</th>
                                <th>Category</th>
                                <th>Author</th>
                                <th>Status</th>
                                <th>Engagement</th>
                                <th>Published</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($posts as $index => $post)
                            <tr>
                                <td>{{ $posts->firstItem() + $index }}</td>
                                <td>
                                    @if($post->featured_image)
                                        <img src="{{ Storage::url($post->featured_image) }}" alt="{{ $post->title }}" class="rounded" style="width: 60px; height: 40px; object-fit: cover;">
                                    @else
                                        <div class="bg-secondary rounded d-flex align-items-center justify-content-center" style="width: 60px; height: 40px;">
                                            <i class="ri-image-line text-white"></i>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex flex-column">
                                        <strong class="text-primary">{{ Str::limit($post->title, 50) }}</strong>
                                        @if($post->excerpt)
                                            <small class="text-muted">{{ Str::limit($post->excerpt, 80) }}</small>
                                        @endif
                                        <div class="mt-1">
                                            <span class="badge bg-info">{{ $post->reading_time ?? 0 }} min read</span>
                                            @if($post->featured)
                                                <span class="badge bg-warning">Featured</span>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @if($post->category)
                                        <span class="badge bg-secondary">{{ $post->category->name }}</span>
                                    @else
                                        <span class="text-muted">No Category</span>
                                    @endif
                                </td>
                                <td>
                                    @if($post->user)
                                        <div class="d-flex align-items-center">
                                            @if($post->user->image)
                                                <img src="{{ $post->user->image }}" alt="{{ $post->user->name }}" class="rounded-circle me-2" style="width: 24px; height: 24px; object-fit: cover;">
                                            @else
                                                <div class="bg-primary rounded-circle me-2 d-flex align-items-center justify-content-center" style="width: 24px; height: 24px;">
                                                    <i class="ri-user-line text-white" style="font-size: 12px;"></i>
                                                </div>
                                            @endif
                                            <small>{{ $post->user->name }}</small>
                                        </div>
                                    @else
                                        <span class="text-muted">Unknown</span>
                                    @endif
                                </td>
                                <td>
                                    @if($post->status === 'published')
                                        <span class="badge bg-success">Published</span>
                                    @elseif($post->status === 'draft')
                                        <span class="badge bg-warning">Draft</span>
                                    @else
                                        <span class="badge bg-secondary">Archived</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex flex-column">
                                        <small class="text-muted">
                                            <i class="ri-eye-line me-1"></i>{{ number_format($post->qualifiedViews()->count()) }} views
                                        </small>
                                        
                                        <small class="text-muted">
                                            <i class="ri-message-2-line me-1"></i>{{ $post->comments_count ?? 0 }} comments
                                        </small>
                                    </div>
                                </td>
                                <td>
                                    @if($post->published_at)
                                        <small class="text-muted">{{ $post->published_at->format('M d, Y') }}</small>
                                        <br><small class="text-muted">{{ $post->published_at->format('g:i A') }}</small>
                                    @else
                                        <small class="text-muted">Not published</small>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('posts.edit', $post) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                        <button class="btn btn-sm btn-outline-danger" 
                                                wire:click="delete({{ $post->id }})"
                                                onclick="return confirm('Are you sure you want to delete the post \'{{ $post->title }}\'? This action cannot be undone.')">
                                            <i class="fa fa-trash"></i>
                                        </button>
                                        <a href="{{ route('blog.show', $post) }}" class="btn btn-sm btn-outline-info" target="_blank">
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
                        Showing {{ $posts->firstItem() }} to {{ $posts->lastItem() }} of {{ $posts->total() }} posts
                    </div>
                    <div>
                        {{ $posts->links() }}
                    </div>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="ri-article-line text-muted" style="font-size: 4rem;"></i>
                    <h5 class="text-muted mt-3">No Posts Found</h5>
                    <p class="text-muted">Start by creating your first blog post.</p>
                    <a href="{{ route('posts.create') }}" class="btn btn-primary">
                        <i class="ri-add-line me-2"></i>Create Your First Post
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
