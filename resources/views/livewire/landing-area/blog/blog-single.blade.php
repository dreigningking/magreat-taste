<div>
    <section class="featured-image" style="background-image: url('{{ $post->featured_image_url }}'); background-size: cover; background-position: center;">
        <div class="featured-content">
            <h1>{{ $post->title }}</h1>
            <div class="featured-meta">
                <div class="meta-item">
                    <i class="fas fa-calendar"></i>
                    <span>{{ $post->published_at->format('F d, Y') }}</span>
                </div>
                <div class="meta-item">
                    <i class="fas fa-user"></i>
                    <span>{{ $post->author_name }}</span>
                </div>
                <div class="meta-item">
                    <i class="fas fa-clock"></i>
                    <span>{{ $post->reading_time }} min read</span>
                </div>
                <div class="meta-item">
                    <i class="fas fa-eye"></i>
                    <span>{{ number_format($post->views_count) }} views</span>
                </div>
                <div class="meta-item">
                    <i class="fas fa-comment"></i>
                    <span>{{ $approvedComments->count() }} comments</span>
                </div>
            </div>
        </div>
    </section>

    <!-- Article Content -->
    <section class="article-content">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <div class="article-meta">
                        <div class="article-tags">
                            @if($post->category)
                                <span class="article-tag">{{ $post->category->name }}</span>
                            @endif
                            @if($post->tags)
                                @foreach($post->tags as $tag)
                                    <span class="article-tag">{{ $tag }}</span>
                                @endforeach
                            @endif
                        </div>
                        <div class="social-share">
                            <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->url()) }}" 
                               target="_blank" class="btn btn-outline-primary btn-sm me-2">
                                <i class="fab fa-facebook"></i>
                            </a>
                            <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->url()) }}&text={{ urlencode($post->title) }}" 
                               target="_blank" class="btn btn-outline-info btn-sm me-2">
                                <i class="fab fa-twitter"></i>
                            </a>
                            <a href="https://pinterest.com/pin/create/button/?url={{ urlencode(request()->url()) }}&description={{ urlencode($post->title) }}" 
                               target="_blank" class="btn btn-outline-danger btn-sm">
                                <i class="fab fa-pinterest"></i>
                            </a>
                        </div>
                    </div>
                    
                    <div class="article-text">
                        {!! $post->content !!}
                    </div>
                    
                    <!-- Comments Section -->
                    <div class="comments-section">
                        <h3 class="comments-title">Comments ({{ $approvedComments->count() }})</h3>
                        
                        @if($approvedComments->count() > 0)
                            @foreach($approvedComments as $comment)
                                <div class="comment">
                                    <div class="comment-header">
                                        <span class="comment-author">{{ $comment->commenter_name }}</span>
                                        <span class="comment-date">{{ $comment->created_at->format('F d, Y') }}</span>
                                    </div>
                                    <div class="comment-text">
                                        <p>{{ $comment->content }}</p>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <p class="text-muted">No comments yet. Be the first to comment!</p>
                        @endif
                        
                        <!-- Comment Form -->
                        <div class="comment-form">
                            <h4>Leave a Comment</h4>
                            
                            @if(session()->has('comment_success'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    {{ session('comment_success') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endif

                            @if(session()->has('comment_error'))
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    {{ session('comment_error') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endif

                            <form wire:submit.prevent="addComment">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="guestName" class="form-label">Name *</label>
                                        <input type="text" class="form-control" id="guestName" 
                                               wire:model="guestName" required>
                                        @error('guestName') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="guestEmail" class="form-label">Email *</label>
                                        <input type="email" class="form-control" id="guestEmail" 
                                               wire:model="guestEmail" required>
                                        @error('guestEmail') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="commentContent" class="form-label">Comment *</label>
                                    <textarea class="form-control" id="commentContent" rows="4" 
                                              wire:model="commentContent" required></textarea>
                                    @error('commentContent') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                                <button type="submit" class="btn btn-primary">Post Comment</button>
                            </form>
                        </div>
                    </div>
                </div>
                
                <!-- Sidebar -->
                <div class="col-lg-4">
                    <!-- Related Posts -->
                    @if($relatedPosts->count() > 0)
                        <div class="sidebar">
                            <h4 class="sidebar-title">Related Posts</h4>
                            @foreach($relatedPosts as $relatedPost)
                                <div class="related-post">
                                    <img src="{{ $relatedPost->featured_image ?? 'https://images.unsplash.com/photo-1563379926898-05f4575a45d8?ixlib=rb-4.0.3&auto=format&fit=crop&w=150&q=80' }}" 
                                         alt="{{ $relatedPost->title }}" class="related-post-img">
                                    <div>
                                        <h6 class="related-post-title">
                                            <a href="{{ route('blog.show', $relatedPost->slug) }}" 
                                               class="text-decoration-none text-dark">
                                                {{ $relatedPost->title }}
                                            </a>
                                        </h6>
                                        <p class="related-post-date">{{ $relatedPost->published_at->format('M d, Y') }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                    
                    <!-- Newsletter -->
                    <div class="sidebar">
                        <h4 class="sidebar-title">Subscribe to Our Newsletter</h4>
                        <p class="mb-3">Get the latest recipes and cooking tips delivered to your inbox.</p>
                        
                        @if(session()->has('subscription_success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('subscription_success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        @if(session()->has('subscription_error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                {{ session('subscription_error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <form wire:submit.prevent="subscribeToNewsletter">
                            <div class="mb-3">
                                <input type="text" class="form-control" placeholder="Your Name" 
                                       wire:model="subscriberName" required>
                                @error('subscriberName') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="mb-3">
                                <input type="email" class="form-control" placeholder="Enter your email" 
                                       wire:model="subscriberEmail" required>
                                @error('subscriberEmail') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <button type="submit" class="btn btn-primary w-100">Subscribe</button>
                        </form>
                    </div>
                    
                    <!-- Categories -->
                    <div class="sidebar">
                        <h4 class="sidebar-title">Categories</h4>
                        <ul class="list-unstyled">
                            @php
                                $categories = \App\Models\Category::where('type', 'post')
                                    ->withCount(['posts' => function($query) {
                                        $query->published();
                                    }])
                                    ->get();
                            @endphp
                            
                            @foreach($categories as $category)
                                @if($category->posts_count > 0)
                                    <li class="mb-2">
                                        <a href="{{ route('blog') }}?category={{ $category->slug }}" 
                                           class="text-decoration-none text-dark d-flex justify-content-between align-items-center">
                                            <span>{{ $category->name }}</span>
                                            <span class="badge bg-secondary">{{ $category->posts_count }}</span>
                                        </a>
                                    </li>
                                @endif
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>