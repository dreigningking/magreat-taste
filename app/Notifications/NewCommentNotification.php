<?php

namespace App\Notifications;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Str;

class NewCommentNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $comment;
    public $post;

    /**
     * Create a new notification instance.
     */
    public function __construct(Comment $comment, Post $post)
    {
        $this->comment = $comment;
        $this->post = $post;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $commenterName = $this->comment->commenter_name;
        $postTitle = $this->post->title;
        $commentContent = Str::limit($this->comment->content, 150);

        return (new MailMessage)
            ->subject("New Comment on: {$postTitle}")
            ->greeting("Hello {$notifiable->name}!")
            ->line("A new comment has been posted on the blog post: {$postTitle}")
            ->line("Comment by: {$commenterName}")
            ->line("Comment: {$commentContent}")
            ->action('View Post', route('blog.show', $this->post->slug))
            ->line('Thank you for being part of our community!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'comment_id' => $this->comment->id,
            'post_id' => $this->post->id,
            'commenter_name' => $this->comment->commenter_name,
            'post_title' => $this->post->title,
            'comment_content' => Str::limit($this->comment->content, 150),
        ];
    }
}
