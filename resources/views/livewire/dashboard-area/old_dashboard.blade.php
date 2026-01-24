<div class="content-wrapper">
    <div class="row mb-4">
        <div class="col-12">
            <h3 class="font-weight-bold">Dashboard Overview</h3>
            <p class="text-muted">Welcome to your restaurant management dashboard</p>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-2">
            <div class="card border-primary">
                <div class="card-body">
                    <h6 class="text-muted">Total Orders</h6>
                    <h3 class="mb-0">{{ $stats['total_orders'] }}</h3>
                    <small class="text-muted">All time</small>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card border-success">
                <div class="card-body">
                    <h6 class="text-muted">Today's Orders</h6>
                    <h3 class="mb-0">{{ $stats['today_orders'] }}</h3>
                    <small class="text-muted">New orders</small>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card border-warning">
                <div class="card-body">
                    <h6 class="text-muted">Total Meals</h6>
                    <h3 class="mb-0">{{ $stats['total_meals'] }}</h3>
                    <small class="text-muted">Available meals</small>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card border-info">
                <div class="card-body">
                    <h6 class="text-muted">Total Foods</h6>
                    <h3 class="mb-0">{{ $stats['total_foods'] }}</h3>
                    <small class="text-muted">Food items</small>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card border-secondary">
                <div class="card-body">
                    <h6 class="text-muted">Blog Posts</h6>
                    <h3 class="mb-0">{{ $stats['total_posts'] }}</h3>
                    <small class="text-muted">Published posts</small>
                </div>
            </div>
        </div>
        <div class="col-md-2">
            <div class="card border-dark">
                <div class="card-body">
                    <h6 class="text-muted">Total Views</h6>
                    <h3 class="mb-0">{{ $stats['total_views'] }}</h3>
                    <small class="text-muted">5+ min reads</small>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Main Content -->
        <div class="col-lg-8">
            <!-- Top Branches -->
            <div class="card mb-4 border-success">
                <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                    <span><i class="fa fa-building me-2"></i>Top Performing Branches</span>
                    <a href="{{ route('orders.index') }}" class="text-white">View All <i class="fa fa-arrow-right"></i></a>
                </div>
                <div class="card-body">
                    @if($topBranches->count() > 0)
                        @foreach($topBranches as $branch)
                        <div class="d-flex justify-content-between align-items-center mb-3 {{ !$loop->last ? 'border-bottom pb-2' : '' }}">
                            <div>
                                <h6 class="mb-1">{{ $branch->branch_name }}</h6>
                                <small class="text-muted">Branch Location</small>
                            </div>
                            <div class="text-end">
                                <span class="badge bg-success fs-6">{{ $branch->order_count }}</span>
                                <small class="d-block text-muted">Orders</small>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <div class="text-center py-3">
                            <i class="fa fa-building text-muted" style="font-size: 2rem;"></i>
                            <p class="text-muted mt-2">No branch data available</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Orders Section -->
            <div class="card border-primary">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <span>
                        <i class="fa fa-shopping-cart me-2"></i>
                        @if($pendingOrders->count() > 0)
                            Pending Orders ({{ $pendingOrders->count() }})
                        @else
                            Recent Orders
                        @endif
                    </span>
                    <a href="{{ route('orders.index') }}" class="text-white">View All <i class="fa fa-arrow-right"></i></a>
                </div>
                <div class="card-body">
                    @if($pendingOrders->count() > 0)
                        @foreach($pendingOrders as $order)
                        <div class="d-flex justify-content-between align-items-center mb-3 {{ !$loop->last ? 'border-bottom pb-2' : '' }}">
                            <div>
                                <h6 class="mb-1">Order #{{ $order->id }}</h6>
                                <small class="text-muted">{{ $order->name }} - {{ $order->delivery_type }}</small><br>
                                <small class="text-muted">{{ $order->created_at->diffForHumans() }}</small>
                            </div>
                            <div class="text-end">
                                <span class="badge bg-warning">₦{{ number_format($order->total_amount, 2) }}</span>
                                <a href="{{ route('orders.view', $order) }}" class="btn btn-sm btn-primary ms-2">View</a>
                            </div>
                        </div>
                        @endforeach
                    @elseif(isset($recentOrders) && $recentOrders->count() > 0)
                        @foreach($recentOrders as $order)
                        <div class="d-flex justify-content-between align-items-center mb-3 {{ !$loop->last ? 'border-bottom pb-2' : '' }}">
                            <div>
                                <h6 class="mb-1">Order #{{ $order->id }}</h6>
                                <small class="text-muted">{{ $order->name }} - {{ $order->delivery_type }}</small><br>
                                <small class="text-muted">{{ $order->created_at->diffForHumans() }}</small>
                            </div>
                            <div class="text-end">
                                <span class="badge bg-{{ $order->status_color }}">{{ ucfirst($order->status) }}</span>
                                <a href="{{ route('orders.view', $order) }}" class="btn btn-sm btn-primary ms-2">View</a>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <div class="text-center py-3">
                            <i class="fa fa-shopping-cart text-muted" style="font-size: 2rem;"></i>
                            <p class="text-muted mt-2">No orders available</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- System Stats -->
            <div class="card mb-4">
                <div class="card-header">System Statistics</div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6 mb-3">
                            <div class="h4 mb-0 text-primary">{{ $stats['total_locations'] }}</div>
                            <small class="text-muted">Locations</small>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="h4 mb-0 text-success">{{ $stats['total_shipment_routes'] }}</div>
                            <small class="text-muted">Shipment Routes</small>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="h4 mb-0 text-warning">{{ $stats['monthly_orders'] }}</div>
                            <small class="text-muted">This Month</small>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="h4 mb-0 text-info">{{ $stats['total_contacts'] }}</div>
                            <small class="text-muted">Contacts</small>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="h4 mb-0 text-secondary">{{ $stats['total_posts'] }}</div>
                            <small class="text-muted">Blog Posts</small>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="h4 mb-0 text-dark">{{ $stats['total_comments'] }}</div>
                            <small class="text-muted">Comments</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Trending Blog Posts -->
            <div class="card border-info mb-4">
                <div class="card-header bg-success text-white">Trending Blog Posts</div>
                <div class="card-body">
                                            @if($trendingPosts->count() > 0)
                            @foreach($trendingPosts as $post)
                            <div class="d-flex justify-content-between align-items-center mb-2 {{ !$loop->last ? 'border-bottom pb-2' : '' }}">
                                <div>
                                    <h6 class="mb-1">{{ Str::limit($post->title, 30) }}</h6>
                                    <div class="d-flex flex-column">
                                        <small class="text-muted">
                                            <i class="fa fa-eye me-1"></i>{{ $post->total_views_count ?? 0 }} views
                                        </small>
                                        <small class="text-muted">
                                            <i class="fa fa-comment me-1"></i>{{ $post->comments_count ?? 0 }} comments
                                        </small>
                                    </div>
                                </div>
                                <a href="{{ route('blog.show', $post->slug) }}" class="btn btn-sm btn-outline-info">View</a>
                            </div>
                            @endforeach
                        @else
                            <div class="text-center py-3">
                                <i class="fa fa-newspaper text-muted" style="font-size: 2rem;"></i>
                                <p class="text-muted mt-2">No trending posts</p>
                            </div>
                        @endif
                </div>
            </div>

            <!-- Recent Blog Comments -->
            <div class="card border-warning">
                <div class="card-header bg-warning text-white">Recent Blog Comments</div>
                <div class="card-body">
                    @if($recentComments->count() > 0)
                        @foreach($recentComments as $comment)
                        <div class="mb-2 {{ !$loop->last ? 'border-bottom pb-2' : '' }}">
                            <div class="d-flex justify-content-between">
                                <strong>{{ $comment->guest_name ?? $comment->user->name ?? 'Anonymous' }}</strong>
                                <small class="text-muted">{{ $comment->created_at->diffForHumans() }}</small>
                            </div>
                            <small class="text-muted">{{ Str::limit($comment->content, 50) }}</small>
                            <div class="mt-1">
                                <small class="text-muted">On: {{ Str::limit($comment->post->title, 30) }}</small>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <div class="text-center py-3">
                            <i class="fa fa-comments text-muted" style="font-size: 2rem;"></i>
                            <p class="text-muted mt-2">No recent comments</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>