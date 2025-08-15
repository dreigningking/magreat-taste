<?php

namespace App\Livewire\DashboardArea;

use Carbon\Carbon;
use App\Models\Food;
use App\Models\Meal;
use App\Models\Post;
use App\Models\Order;
use App\Models\Comment;
use App\Models\Contact;
use Livewire\Component;
use App\Models\Location;
use App\Models\PostView;
use App\Models\ShipmentRoute;
use Illuminate\Support\Facades\DB;

class Dashboard extends Component
{
    public $topBranches;
    public $recentOrders;
    public $pendingOrders;
    public $stats;
    public $trendingPosts;
    public $recentComments;
    
    public function mount()
    {
        $this->loadDashboardData();
    }

    public function loadDashboardData()
    {
        $this->loadTopBranches();
        $this->loadOrders();
        $this->loadStats();
        $this->loadTrendingPosts();
        $this->loadRecentComments();
    }

    public function loadTopBranches()
    {
        $this->topBranches = DB::table('orders')
            ->join('shipment_routes', 'orders.shipment_route_id', '=', 'shipment_routes.id')
            ->join('locations', 'shipment_routes.location_id', '=', 'locations.id')
            ->select('locations.name as branch_name', 'locations.city_id', DB::raw('COUNT(orders.id) as order_count'))
            ->whereNotNull('orders.shipment_route_id')
            ->groupBy('locations.id', 'locations.name', 'locations.city_id')
            ->orderBy('order_count', 'desc')
            ->limit(5)
            ->get();
    }

    public function loadOrders()
    {
        // Get pending orders first
        $this->pendingOrders = Order::where('status', 'pending')
            ->with(['orderItems.meal', 'orderItems.food'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // If no pending orders, get recent orders
        if ($this->pendingOrders->isEmpty()) {
            $this->recentOrders = Order::with(['orderItems.meal', 'orderItems.food'])
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();
        }
    }

    public function loadStats()
    {
        $this->stats = [
            'total_meals' => Meal::count(),
            'total_foods' => Food::count(),
            'total_locations' => Location::count(),
            'total_shipment_routes' => ShipmentRoute::count(),
            'total_orders' => Order::count(),
            'total_contacts' => Contact::count(),
            'today_orders' => Order::whereDate('created_at', Carbon::today())->count(),
            'monthly_orders' => Order::whereMonth('created_at', Carbon::now()->month)->count(),
            'total_posts' => Post::published()->count(),
            'total_views' => PostView::count(),
            'total_comments' => Comment::count(),
        ];
    }

    public function loadTrendingPosts()
    {
        $this->trendingPosts = Post::published()
            ->withCount([
                'views as total_views_count',
                'comments as comments_count'
            ])
            ->with(['user', 'category'])
            ->orderBy('total_views_count', 'desc')
            ->limit(3)
            ->get();
    }

    public function loadRecentComments()
    {
        $this->recentComments = Comment::with(['post'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
    }

    public function getBlogAnalytics()
    {
        return [
            'total_posts' => Post::published()->count(),
            'total_views' => PostView::count(),
            'total_comments' => Comment::count(),
            'avg_reading_time' => Post::published()->avg('reading_time'),
            'top_performing_post' => Post::published()
                ->withCount('views')
                ->orderBy('total_views_count', 'desc')
                ->first(),
        ];
    }

    public function render()
    {
        return view('livewire.dashboard-area.dashboard');
    }
}
