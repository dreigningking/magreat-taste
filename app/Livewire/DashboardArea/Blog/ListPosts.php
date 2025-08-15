<?php

namespace App\Livewire\DashboardArea\Blog;

use App\Models\Post;
use Livewire\Component;
use App\Models\PostView;
use Livewire\WithPagination;

class ListPosts extends Component
{
    use WithPagination;

    public $search = '';
    public $status = '';
    public $category = '';

    public function mount()
    {
        // Component is ready
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatus()
    {
        $this->resetPage();
    }

    public function updatingCategory()
    {
        $this->resetPage();
    }

    public function delete($id)
    {
        $post = Post::find($id);
        if ($post) {
            $post->delete();
            session()->flash('message', 'Post deleted successfully!');
        }
    }

    public function render()
    {
        $query = Post::with(['category', 'user'])
            ->withCount('comments');

        // Apply search filter
        if ($this->search) {
            $query->where(function($q) {
                $q->where('title', 'like', '%' . $this->search . '%')
                  ->orWhere('excerpt', 'like', '%' . $this->search . '%')
                  ->orWhere('content', 'like', '%' . $this->search . '%');
            });
        }

        // Apply status filter
        if ($this->status) {
            $query->where('status', $this->status);
        }

        // Apply category filter
        if ($this->category) {
            $query->where('category_id', $this->category);
        }

        $posts = $query->orderBy('created_at', 'desc')->paginate(10);

        // Get statistics
        $totalPosts = Post::count();
        $publishedPosts = Post::where('status', 'published')->count();
        $draftPosts = Post::where('status', 'draft')->count();
        $totalViews = PostView::sum('is_qualified');

        return view('livewire.dashboard-area.blog.list-posts', [
            'posts' => $posts,
            'totalPosts' => $totalPosts,
            'publishedPosts' => $publishedPosts,
            'draftPosts' => $draftPosts,
            'totalViews' => $totalViews,
        ]);
    }
}
