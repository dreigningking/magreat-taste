<div>
    <!-- Blog Hero Section -->
    <section class="blog-hero">
        <div class="blog-hero-content">
            <h1>Culinary Stories & Recipes</h1>
            <p>Discover the art of cooking, behind-the-scenes stories, and exclusive recipes from Chef Margaret</p>
        </div>
    </section>

    <!-- Blog Categories -->
    <section class="blog-categories">
        <div class="container">
            <div class="text-center">
                <button class="category-btn {{ $selectedCategory === 'all' ? 'active' : '' }}" 
                        wire:click="$set('selectedCategory', 'all')">
                    All Posts
                </button>
                @foreach($categories as $category)
                    <button class="category-btn {{ $selectedCategory === $category->slug ? 'active' : '' }}" 
                            wire:click="$set('selectedCategory', '{{ $category->slug }}')">
                        {{ $category->name }}
                    </button>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Blog Content -->
    <section class="blog-content py-5">
        <div class="container">
            <div class="row">
                <!-- Main Blog Posts -->
                <div class="col-lg-8">
                    <!-- Filter and Search Bar -->
                    

                    <div class="row">
                        @forelse($posts as $post)
                            <div class="col-md-6 mb-4">
                                <div class="blog-card">
                                    <div class="position-relative">
                                        <img src="{{ $post->featured_image_url }}" 
                                             alt="{{ $post->title }}" class="blog-card-img">
                                        <div class="blog-card-overlay">
                                            <div>
                                                <span class="badge bg-primary">{{ $post->category->name ?? 'Uncategorized' }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="blog-card-body">
                                        <div class="blog-meta">
                                            <div class="blog-meta-left">
                                                <span><i class="fas fa-calendar me-1"></i>{{ $post->published_at->format('M d, Y') }}</span>
                                            </div>
                                            <div class="blog-meta-right">
                                                <span><i class="fas fa-eye me-1"></i>{{ number_format($post->views->count() ?? 0) }}</span>
                                                <span><i class="fas fa-comment me-1"></i>{{ $post->comments->count() ?? 0 }}</span>
                                            </div>
                                        </div>
                                        <h3 class="blog-card-title">{{ $post->title }}</h3>
                                        <p class="blog-card-excerpt">{{ $post->excerpt ?? Str::limit($post->content, 150) }}</p>
                                        @if($post->tags)
                                            <div class="blog-tags">
                                                @foreach($post->tags as $tag)
                                                    <span class="blog-tag">{{ trim($tag) }}</span>
                                                @endforeach
                                            </div>
                                        @endif
                                        <a href="{{ route('blog.show', $post->slug) }}" class="read-more-btn">Read More</a>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12">
                                <div class="empty-state">
                                    <i class="fas fa-search fa-3x"></i>
                                    <h4>No posts found</h4>
                                    <p>
                                        @if($search)
                                            No posts match your search "{{ $search }}"
                                        @elseif($selectedCategory !== 'all')
                                            No posts found in this category
                                        @else
                                            No posts available at the moment
                                        @endif
                                    </p>
                                    @if($search || $selectedCategory !== 'all')
                                        <button class="btn btn-primary" wire:click="clearFilters">
                                            Clear Filters
                                        </button>
                                    @endif
                                </div>
                            </div>
                        @endforelse
                    </div>

                    <!-- Pagination -->
                    @if($posts->hasPages())
                        <div class="d-flex justify-content-center mt-4">
                            {{ $posts->links() }}
                        </div>
                    @endif
                </div>

                <!-- Sidebar -->
                <div class="col-lg-4">
                    <!-- Search -->
                    <div class="sidebar">
                        <h4 class="sidebar-title">Search</h4>
                        <div class="input-group">
                            <input type="text" class="form-control" placeholder="Search articles..." 
                                   wire:model.live.debounce.300ms="search">
                            <button class="btn btn-primary" type="button">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Popular Posts -->
                    @if($popularPosts->count() > 0)
                        <div class="sidebar">
                            <h4 class="sidebar-title">Popular Posts</h4>
                            @foreach($popularPosts as $post)
                                <div class="popular-post">
                                    <img src="{{ $post->featured_image ?? 'https://images.unsplash.com/photo-1563379926898-05f4575a45d8?ixlib=rb-4.0.3&auto=format&fit=crop&w=150&q=80' }}" 
                                         alt="{{ $post->title }}" class="popular-post-img">
                                    <div>
                                        <h6 class="popular-post-title">{{ $post->title }}</h6>
                                        <p class="popular-post-date">{{ $post->published_at->format('M d, Y') }}</p>
                                        <small class="text-muted">{{ $post->comments_count }} comments</small>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    <!-- Categories -->
                    <div class="sidebar">
                        <h4 class="sidebar-title">Categories</h4>
                        <ul class="list-unstyled">
                            <li class="mb-2">
                                <a href="#" class="text-decoration-none text-dark d-flex justify-content-between align-items-center" 
                                   wire:click="$set('selectedCategory', 'all')">
                                    <span>All Posts</span>
                                    <span class="badge bg-secondary">{{ $posts->total() }}</span>
                                </a>
                            </li>
                            @foreach($categoryCounts as $category)
                                @if($category->posts_count > 0)
                                    <li class="mb-2">
                                        <a href="#" class="text-decoration-none text-dark d-flex justify-content-between align-items-center" 
                                           wire:click="$set('selectedCategory', '{{ $category->slug }}')">
                                            <span>{{ $category->name }}</span>
                                            <span class="badge bg-secondary">{{ $category->posts_count }}</span>
                                        </a>
                                    </li>
                                @endif
                            @endforeach
                        </ul>
                    </div>

                    <!-- Tags -->
                    <div class="sidebar">
                        <h4 class="sidebar-title">Tags</h4>
                        <div class="tag-cloud">
                            @php
                                $allTags = collect();
                                foreach($posts as $post) {
                                    if($post->tags) {
                                        $tags = collect($post->tags)->map(fn($tag) => trim($tag));
                                        $allTags = $allTags->merge($tags);
                                    }
                                }
                                $uniqueTags = $allTags->unique()->take(10);
                            @endphp
                            
                            @foreach($uniqueTags as $tag)
                                <span class="blog-tag">{{ $tag }}</span>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Newsletter Section -->
    <section class="newsletter-section">
        <div class="container">
            <div class="newsletter-content">
                <h3 class="mb-3">Subscribe to Our Newsletter</h3>
                <p class="mb-4">Get the latest recipes, cooking tips, and exclusive content delivered to your inbox.</p>
                <form class="newsletter-form">
                    <input type="email" class="form-control newsletter-input" placeholder="Enter your email address">
                    <button type="submit" class="btn newsletter-btn">Subscribe</button>
                </form>
            </div>
        </div>
    </section>

</div>

@push('scripts')
<script>
    // Add any additional JavaScript for enhanced interactivity
    document.addEventListener('DOMContentLoaded', function() {
        // Smooth scrolling for category buttons
        const categoryButtons = document.querySelectorAll('.category-btn');
        categoryButtons.forEach(button => {
            button.addEventListener('click', function() {
                // Remove active class from all buttons
                categoryButtons.forEach(btn => btn.classList.remove('active'));
                // Add active class to clicked button
                this.classList.add('active');
            });
        });
    });
</script>
@endpush