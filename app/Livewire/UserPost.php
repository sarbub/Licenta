<?php

namespace App\Livewire;

// ... other use statements ...
use Livewire\Component;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth; // Ensure Auth is imported
use Illuminate\Support\Facades\Log; // Ensure Log is imported
use App\Models\user_posts; // Ensure correct model name
use Illuminate\Database\Eloquent\Collection; // Import Collection for type hinting
use Livewire\Attributes\On;
use App\Events\PostLikeStatusChanged; // Updated Event
class UserPost extends Component
{
    public $user_data;

    // *** ADD LIKERS MODAL PROPERTIES ***
    public bool $showLikersModal = false;
    public ?user_posts $selectedPostForLikers = null; // Store the post model
    public Collection $likersList; // To store the list of users who liked the post

    public function loadPosts()
    {
        $query = user_posts::with('user') // Load the post author
                           ->withCount('likers'); // Efficiently get the like count as 'likers_count'

        // Eager load relationship *only* to check if the *current user* liked it
        if (Auth::check()) {
            $userId = Auth::id();
            // This adds a 'likers' collection containing *only* the current user if they liked it
            $query->with(['likers' => function ($q) use ($userId) {
                $q->where('user_id', $userId);
            }]);
            // Alternative using withExists for a boolean 'is_liked' attribute:
            // $query->withExists(['likers as is_liked' => fn($q) => $q->where('user_id', $userId)]);
        }

        $this->user_data = $query->latest()->get();
    }
    public function showLikes($postId){
        $post = user_posts::with(['likers' => function ($query) {
                            // Select only necessary columns for efficiency
                            $query->select('users.id', 'users.first_name', 'users.last_name', 'users.profile_photo_path');
                        }])
                        ->find($postId);

        if (!$post) {
            session()->flash('error', 'Post not found.');
            Log::warning("showLikers failed: Post ID {$postId} not found.");
            return;
        }


        $this->selectedPostForLikers = $post;
        $this->likersList = $post->likers; // Assign the loaded likers
        $this->showLikersModal = true;
        $this->loadPosts();
        Log::debug("Likers modal opened for post ID: {$postId}. Found {$this->likersList->count()} likers.");
    }

    #[On('refreshUserPosts')]
    public function refreshData()
    {
        $this->loadPosts();
    }
    public function closeLikersModal()
    {
        $this->showLikersModal = false;
        $this->selectedPostForLikers = null;
        $this->likersList = new Collection(); // Reset to empty collection
        $this->loadPosts();
        Log::debug("Likers modal closed.");
    }



    public function mount()
    {
        $this->likersList = new Collection(); // Initialize the collection
        $this->loadPosts();
    }

    public function deletePosts($postId)
    {
        // ... existing delete logic ...
        $post = user_posts::find($postId);
        if(!$post){
            session()->flash('error','Post not found');
            return;
        }
        $user = Auth::user();
        if(!$user || ($user->id !== $post->user_id && !in_array($user->account_type, ['admin','moderator']))){
            // Use session flash instead of abort for better UX in Livewire
            session()->flash('error', 'You are not authorized to delete this post.');
            Log::warning("Unauthorized delete attempt for post ID: {$postId} by user ID: {$user->id}");
            return;
            // abort(403, 'You are not authorized to delete this post');
        }

        // Consider deleting associated likes if needed, though cascade delete might handle it
        // $post->likers()->detach(); // Manually if cascade isn't set or reliable

        // Delete image if exists
        if ($post->image && Storage::disk('public')->exists($post->image)) {
            Storage::disk('public')->delete($post->image);
        }

        $post->delete();
        $this->loadPosts(); // Reload posts after deletion
        session()->flash('success', 'Post deleted successfully'); // Use consistent key 'success'
    }

    // *** ADD THIS METHOD ***
    public function toggleLike($postId)
    {
        if (!Auth::check()) {
            session()->flash('error', 'Please log in to like posts.');
            return;
        }

        $post = user_posts::find($postId);
        if (!$post) {
            session()->flash('error', 'Post not found.');
            return;
        }

        $user = Auth::user();

        try {
            // Toggle the like and check if it's now liked
            $likedPostsPivots = $user->likedPosts()->toggle($postId);
            $wasLiked = in_array($postId, $likedPostsPivots['attached']);

            $newLikeCount = $post->likers()->count();

            PostLikeStatusChanged::dispatch($post->id, $newLikeCount, $user->id, $wasLiked);

            $this->loadPosts();
        } catch (\Exception $e) {
            Log::error("Error toggling like for post ID {$postId} by user ID {$user->id}: " . $e->getMessage());
            session()->flash('error', 'Could not update like status. Please try again.');
        }
    }
    // *** END ADDED METHOD ***

    public function render()
    {
        return view('livewire.user-post');
    }
}
