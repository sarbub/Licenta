<?php
namespace App\Events;

use App\Models\User;
use App\Models\user_posts; // Adjust if your model name is different
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class PostLikeStatusChanged implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public int $postId;
    public int $newLikeCount;
    public int $userIdWhoActed; // ID of the user who liked/unliked
    public bool $wasLiked; // True if it was a like action, false if an unlike action

    /**
     * Create a new event instance.
     */
    public function __construct(int $postId, int $newLikeCount, int $userIdWhoActed, bool $wasLiked)
    {
        log::info("lets see if I get here");
        $this->postId = $postId;
        $this->newLikeCount = $newLikeCount;
        $this->userIdWhoActed = $userIdWhoActed;
        $this->wasLiked = $wasLiked;
    }
    


    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        log::info("lets see if I get here");
        // Public channel, anyone can listen
        return [new Channel('likes')];
    }
}
