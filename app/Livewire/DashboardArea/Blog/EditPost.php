<?php

namespace App\Livewire\DashboardArea\Blog;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Post;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;

class EditPost extends Component
{
    use WithFileUploads;

    public Post $post;
    
    // Form properties
    public $title = '';
    public $excerpt = '';
    public $content = '';
    public $category_id = '';
    public $featured_image;
    public $currentFeaturedImage;
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

    public function mount($post)
    {
        $this->post = $post;
        $this->loadPost();
    }

    public function loadPost()
    {
        $this->title = $this->post->title;
        $this->excerpt = $this->post->excerpt;
        $this->content = $this->post->content;
        $this->category_id = $this->post->category_id;
        $this->currentFeaturedImage = $this->post->featured_image;
        $this->status = $this->post->status;
        $this->published_at = $this->post->published_at ? $this->post->published_at->format('Y-m-d\TH:i') : '';
        $this->meta_keywords = $this->post->meta_keywords;
        $this->tags = $this->post->tags ? implode(', ', $this->post->tags) : '';
        $this->reading_time = $this->post->reading_time;
        $this->featured = $this->post->featured;
        $this->allow_comments = $this->post->allow_comments;
    }

    public function update()
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

        try {
            // Handle featured image upload
            $featuredImagePath = $this->currentFeaturedImage;
            if ($this->featured_image) {
                // Delete old image if exists
                if ($this->currentFeaturedImage && Storage::disk('public')->exists($this->currentFeaturedImage)) {
                    Storage::disk('public')->delete($this->currentFeaturedImage);
                }
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

            // Update the post
            $this->post->update([
                'title' => $this->title,
                'excerpt' => $this->excerpt,
                'content' => $this->content,
                'category_id' => $this->category_id,
                'featured_image' => $featuredImagePath,
                'status' => $this->status,
                'published_at' => $this->status === 'published' ? $this->published_at : null,
                'meta_keywords' => $this->meta_keywords,
                'tags' => $tagsArray,
                'reading_time' => $this->reading_time,
                'featured' => $this->featured,
                'allow_comments' => $this->allow_comments,
            ]);

            session()->flash('message', 'Post updated successfully!');
            
            // Redirect to the posts list
            return redirect()->route('posts.index');

        } catch (\Exception $e) {
            session()->flash('error', 'Error updating post: ' . $e->getMessage());
        }
    }

    public function render()
    {
        $categories = Category::where('type', 'post')->where('is_active', true)->get();
        
        return view('livewire.dashboard-area.blog.edit-post', [
            'categories' => $categories
        ]);
    }
}
