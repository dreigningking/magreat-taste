<?php

namespace App\Livewire\DashboardArea\Blog;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Post;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CreatePost extends Component
{
    use WithFileUploads;

    // Form properties
    public $title = '';
    public $excerpt = '';
    public $content = '';
    public $category_id = '';
    public $featured_image;
    public $status = 'draft';
    public $published_at = '';
    public $meta_keywords = '';
    public $tags = '';
    public $reading_time = '';
    public $featured = false;
    public $allow_comments = true;

    protected function getListeners()
    {
        return [
            'summernoteContentUpdated' => 'handleDescriptionUpdate',
        ];
    }

    public function handleDescriptionUpdate($content, $wireModel)
    {
        if ($wireModel === 'content') {
            $this->content = $content;
        }
    }

    public function mount()
    {
        // Set default publish date to now
        $this->published_at = now()->format('Y-m-d\TH:i');
    }

    public function store()
    {
        $this->validate([
            'title' => 'required|string|max:255',
            'excerpt' => 'required|string|max:500',
            'content' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|in:draft,published,archived',
            'published_at' => 'nullable|date',
            'meta_keywords' => 'nullable|string|max:255',
            'tags' => 'nullable|string',
            'reading_time' => 'nullable|integer|min:1',
            'featured' => 'boolean',
            'allow_comments' => 'boolean',
        ]);

        // Handle featured image upload
        $featuredImagePath = null;
        if ($this->featured_image) {
            $featuredImagePath = $this->featured_image->store('blog', 'public');
        }

        // Process tags
        $tagsArray = null;
        if ($this->tags) {
            $tagsArray = array_map('trim', explode(',', $this->tags));
        }

        // Calculate reading time if not provided
        if (!$this->reading_time) {
            $wordCount = str_word_count(strip_tags($this->content));
            $this->reading_time = ceil($wordCount / 200); // Average 200 words per minute
        }

        // Create the post
        $post = Post::create([
            'title' => $this->title,
            'excerpt' => $this->excerpt,
            'content' => $this->content,
            'category_id' => $this->category_id,
            'featured_image' => $featuredImagePath,
            'user_id' => Auth::id(),
            'status' => $this->status,
            'published_at' => $this->status === 'published' ? $this->published_at : null,
            'meta_keywords' => $this->meta_keywords,
            'tags' => $tagsArray,
            'reading_time' => $this->reading_time,
            'featured' => $this->featured,
            'allow_comments' => $this->allow_comments,
            'views_count' => 0,
            'likes_count' => 0,
        ]);

        // Reset form
        $this->reset([
            'title', 'excerpt', 'content', 'category_id', 'featured_image',
            'status', 'published_at', 'meta_keywords', 'tags', 'reading_time', 
            'featured', 'allow_comments'
        ]);

        session()->flash('message', 'Post created successfully!');
        
        // Redirect to the posts list
        return redirect()->route('posts.index');
    }

    public function render()
    {
        $categories = Category::where('type', 'post')->where('is_active', true)->get();
        
        return view('livewire.dashboard-area.blog.create-post', [
            'categories' => $categories
        ]);
    }
}
