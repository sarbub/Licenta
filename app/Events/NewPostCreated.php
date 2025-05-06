<?php

namespace App\Events;

use App\Models\User;
use App\Models\user_posts;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class NewPostCreated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public string $userName;
    public string $postPreview;
    public int $postId;
    public string $userImageUrl;

    public function __construct(User $user, user_posts $post)
    {
        $imageUrl = null;

        if ($user->profile_photo_path) {

            $imageUrl = Storage::disk('public')->url($user->profile_photo_path);
        }

        \Illuminate\Support\Facades\Log::debug('NewPostCreated Event Data:', [
            'user_id' => $user->id ?? null,
            'generated_image_url' => $imageUrl, // Log the final URL being used
            'app_url_config' => config('app.url')
        ]);
        $this->userName = $user->first_name; // Or $user->name depending on your User model
        $this->postPreview = Str::limit($post->content, 50);
        $this->postId = $post->id;

        $this->userImageUrl = $imageUrl ?? $user->profile_photo_url; // Fallback to accessor which might provide default avatar
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('posts'),
        ];
    }
}
