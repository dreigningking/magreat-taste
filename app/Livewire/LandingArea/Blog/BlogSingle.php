<?php

namespace App\Livewire\LandingArea\Blog;

use App\Models\Post;
use App\Models\User;
use App\Models\Comment;
use Livewire\Component;
use App\Models\Subscriber;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use App\Notifications\NewCommentNotification;

#[Layout('components.layouts.landing')]
class BlogSingle extends Component
{
    public $post;
    public $relatedPosts;
    public $comments;
    public $approvedComments;
    
    // Comment form properties
    public $guestName = '';
    public $guestEmail = '';
    public $commentContent = '';
    
    // Newsletter subscription properties
    public $subscriberName = '';
    public $subscriberEmail = '';
    
    // Meta properties
    public $title;
    public $meta_title;
    public $meta_description;

    public function mount(Post $post)
    {
        $this->post = $post;
        
        if (!$this->post || !$this->post->is_published) {
            abort(404);
        }

        // Increment view count
        $this->post->incrementViews();
        
        // Load related posts
        $this->loadRelatedPosts();
        
        // Load comments
        $this->loadComments();
        
        // Set meta properties
        $this->setMetaProperties();
    }

    public function loadRelatedPosts()
    {
        $this->relatedPosts = Post::published()
            ->where('id', '!=', $this->post->id)
            ->where('category_id', $this->post->category_id)
            ->with(['user', 'category'])
            ->orderBy('published_at', 'desc')
            ->limit(3)
            ->get();
    }

    public function loadComments()
    {
        $userIp = request()->ip();
        
        // Get all comments for this post
        $this->comments = $this->post->comments()
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Filter approved comments based on IP address
        $this->approvedComments = $this->comments->filter(function ($comment) use ($userIp) {
            // Show comment if it's approved OR if it's from the same IP address
            return $comment->status === 'approved' || $comment->ip_address === $userIp;
        });
    }

    public function setMetaProperties()
    {
        $this->title = $this->post->title;
        $this->meta_title = $this->post->meta_title ?? $this->post->title;
        $this->meta_description = $this->post->meta_description ?? $this->post->excerpt;
    }

    public function addComment()
    {
        $this->validate([
            'guestName' => 'required|string|max:255',
            'guestEmail' => 'required|email|max:255',
            'commentContent' => 'required|string|min:10|max:1000',
        ]);

        try {
            DB::beginTransaction();

            $comment = Comment::create([
                'content' => $this->commentContent,
                'guest_name' => $this->guestName,
                'guest_email' => $this->guestEmail,
                'post_id' => $this->post->id,
                'status' => 'pending',
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);

            // Notify all users about the new comment
            $users = User::all();
            Notification::send($users, new NewCommentNotification($comment, $this->post));

            DB::commit();

            // Reset form
            $this->reset(['guestName', 'guestEmail', 'commentContent']);
            
            // Reload comments
            $this->loadComments();
            
            session()->flash('comment_success', 'Your comment has been submitted and is awaiting approval.');

        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('comment_error', 'There was an error submitting your comment. Please try again.');
        }
    }

    public function subscribeToNewsletter()
    {
        $this->validate([
            'subscriberName' => 'required|string|max:255',
            'subscriberEmail' => 'required|email|max:255|unique:subscribers,email',
        ]);

        try {
            Subscriber::create([
                'name' => $this->subscriberName,
                'email' => $this->subscriberEmail,
                'ip_address' => request()->ip(),
                'user_agent' => request()->userAgent(),
            ]);

            $this->reset(['subscriberName', 'subscriberEmail']);
            session()->flash('subscription_success', 'Thank you for subscribing to our newsletter!');

        } catch (\Exception $e) {
            session()->flash('subscription_error', 'There was an error with your subscription. Please try again.');
        }
    }

    public function render()
    {
        return view('livewire.landing-area.blog.blog-single');
    }
}
