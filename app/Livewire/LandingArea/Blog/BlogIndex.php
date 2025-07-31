<?php

namespace App\Livewire\LandingArea\Blog;

use App\Models\Post;
use Livewire\Component;
use App\Models\Category;
use Livewire\WithPagination;
use Livewire\Attributes\Layout;


#[Layout('components.layouts.landing')]
class BlogIndex extends Component
{
    use WithPagination;

    public $categories;
    public $selectedCategory = 'all';
    public $search = '';
    public $popularPosts;
    public $categoryCounts;

    public function mount()
    {
        $this->categories = Category::where('type', 'post')->get();
        $this->loadSidebarData();
        
        // Handle category filter from URL parameter
        if (request()->has('category')) {
            $this->selectedCategory = request()->get('category');
        }
    }

    public function updatedSelectedCategory()
    {
        $this->resetPage();
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function clearFilters()
    {
        $this->selectedCategory = 'all';
        $this->search = '';
        $this->resetPage();
    }

    public function loadSidebarData()
    {
        // Get popular posts (posts with most comments)
        $this->popularPosts = Post::published()
            ->withCount('comments')
            ->having('comments_count', '>', 0)
            ->orderBy('comments_count', 'desc')
            ->limit(3)
            ->get();

        // Get category counts
        $this->categoryCounts = Category::where('type', 'post')
            ->withCount(['posts' => function($query) {
                $query->published();
            }])
            ->get();
    }
    
    public function render()
    {
        $query = Post::published()
            ->with(['user', 'category', 'comments']);

        // Filter by category
        if ($this->selectedCategory && $this->selectedCategory !== 'all') {
            $query->whereHas('category', function($q) {
                $q->where('slug', $this->selectedCategory);
            });
        }

        // Search functionality
        if ($this->search) {
            $query->where(function($q) {
                $q->where('title', 'like', '%' . $this->search . '%')
                  ->orWhere('excerpt', 'like', '%' . $this->search . '%')
                  ->orWhere('content', 'like', '%' . $this->search . '%');
            });
        }

        $posts = $query->orderBy('published_at', 'desc')->paginate(8);

        return view('livewire.landing-area.blog.blog-index', compact('posts'));
    }
}
