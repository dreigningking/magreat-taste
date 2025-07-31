<?php

namespace App\Http\Traits;

use App\Models\User;
use App\Notifications\CommentMentionNotification;
use Illuminate\Support\Facades\Notification;

trait HasMentions
{
    public function processMentions()
    {
        // Parse @username mentions from content
        preg_match_all('/@(\w+)/', $this->content, $matches);
        
        $mentionedUserIds = [];
        
        foreach ($matches[1] as $username) {
            $user = User::where('username', $username)->first();
            if ($user && $user->id !== $this->user_id) { // Don't notify self
                $mentionedUserIds[] = $user->id;
                
                // Send notification
                $user->notify(new CommentMentionNotification($this));
            }
        }
        
        // Store mentioned user IDs in JSON field
        $this->mentions = $mentionedUserIds;
        $this->save();
    }
    
    public function getMentionedUsers()
    {
        if (!$this->mentions) {
            return collect();
        }
        
        return User::whereIn('id', $this->mentions)->get();
    }
    
    public function formatContentWithMentions()
    {
        $content = $this->content;
        
        if ($this->mentions) {
            $users = User::whereIn('id', $this->mentions)->get();
            
            foreach ($users as $user) {
                $content = str_replace(
                    '@' . $user->username,
                    '<a href="' . route('profile.show', $user->username) . '" class="text-primary font-semibold">@' . $user->username . '</a>',
                    $content
                );
            }
        }
        
        return $content;
    }
} 