<?php

namespace App\Jobs;

use App\Models\Post;
use App\Models\Subscriber;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Log;
use App\Notifications\NewsLettersNotification;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class NewslettersJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Get all active subscribers
        $subscribers = Subscriber::where('is_active', true)->whereNull('unsubscribed_at')->get();
        
        // Get all posts that haven't been broadcasted yet (not just today's)
        $posts = Post::where('status', 'published')
            ->whereNotNull('published_at')
            ->whereNull('broadcasted_at')
            ->orderBy('published_at', 'desc')
            ->get();
            
        if ($subscribers->isEmpty()) {
            Log::info('No active subscribers found for newsletter');
            return;
        }
        
        if ($posts->isEmpty()) {
            Log::info('No new posts to broadcast');
            return;
        }
            
        try {
            // Send one newsletter email to all subscribers with all new posts
            Notification::send($subscribers, new NewsLettersNotification($posts));
            
            // Mark all posts as broadcasted
            foreach($posts as $post) {
                $post->broadcasted_at = now();
                $post->save();
            }
            
            Log::info('Newsletter sent to ' . $subscribers->count() . ' subscribers with ' . $posts->count() . ' posts');
        } catch (\Exception $e) {
            Log::error('Newsletter job failed: ' . $e->getMessage());
        }
    }
}
