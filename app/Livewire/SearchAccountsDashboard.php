<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
use App\Models\user_posts;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Log; 
use Illuminate\Support\Facades\Storage;
use App\Events\PostLikeStatusChanged; // Import the updated event
use Illuminate\Database\Eloquent\Collection;


class SearchAccountsDashboard extends Component
{
    public $query = '';
    public $users = [];
    public ?User $profileUser = null;

    public bool $showLikersModal = false;
    public Collection $likersList;
    public ?int $targetUserId = null;
    public $count;



    #[Computed(persist: true)]

    public function currentUserPosts()
    {
        if (!Auth::check()) {
            return collect(); // Return empty if no user logged in
        }
        $loggedInUserId = Auth::id();

        // Fetch posts for the currently logged-in user
        return user_posts::where('user_id', $loggedInUserId)
        ->with('user') // Load author info
        // *** ADD THESE LINES ***
        ->withCount('likers') // Get like count as 'likers_count'
        ->with(['likers' => function ($q) use ($loggedInUserId) {
            $q->where('user_id', $loggedInUserId); // Load relation for is_liked check
        }])
        // *** END ADDED LINES ***
        ->latest()
        ->get();


    }

    #[On('refreshSearchDashboard')]
    public function refreshData()
    {
        Log::debug('--- SearchAccountsDashboard: Refreshing data via refreshSearchDashboard event ---');
        // Unsetting computed properties forces them to re-run on next render
        unset($this->currentUserPosts);
        unset($this->profileUserPosts);
        $this->query = '';
        $this->users = [];
    }

    public function showLikers($postId)
    {
        $post = user_posts::with(['likers' => function ($query) {
                            $query->select('users.id', 'users.first_name', 'users.last_name', 'users.profile_photo_path');
                        }])
                        ->find($postId);

        if (!$post) {
            session()->flash('error', 'Post not found.');
            return;
        }

        // Assign the loaded likers collection to the property
        $this->likersList = $post->likers;
        // Set modal visibility
        $this->showLikersModal = true;
    }
    // *** END METHOD TO SHOW LIKERS MODAL ***

    // *** ADD METHOD TO CLOSE LIKERS MODAL ***
    public function closeLikersModal()
    {
        $this->showLikersModal = false;
        $this->likersList = new Collection(); // Reset to empty collection
        Log::debug("Likers modal closed via SearchAccountsDashboard.");
    }


    public function toggleFollow($userIdToFollow)
    {
        if (!Auth::check()) {
            session()->flash('error', 'Please log in to follow users.');
            return;
        }

        $user = Auth::user();
        $userToFollow = User::find($userIdToFollow);
        if (!$userToFollow) {
            session()->flash('error', 'User not found.');
            return;
        }



        // Prevent following self
        if ($user->id === $userToFollow) {
            session()->flash('error', 'You cannot follow yourself.');
            return;
        }

        try {
            // Use the 'following' relationship's toggle method.
            // This will attach if not following, detach if already following.
            $user->following()->toggle($userIdToFollow);

            // Determine if now following or unfollowing for the flash message
            $isNowFollowing = $user->following()->where('following_id', $userIdToFollow)->exists();

            if ($isNowFollowing) {
                session()->flash('success', 'You are now following ' . $userToFollow->first_name . '.');
                Log::info("User ID {$user->id} started following User ID {$userIdToFollow}");
            } else {
                session()->flash('success', 'You have unfollowed ' . $userToFollow->first_name . '.');
                Log::info("User ID {$user->id} unfollowed User ID {$userIdToFollow}");
            }

            // Force re-render to update button state via the accessor.
            // No need to unset computed properties unless they explicitly depend on follow counts.
            // Livewire will re-render the component, and the accessor will be re-evaluated.
            // Optionally, explicitly reload the profileUser if it's the one being followed/unfollowed
            if ($this->profileUser && $this->profileUser->id === $userIdToFollow) {
                $this->profileUser = $this->profileUser->fresh(); // Reload the model instance
                unset($this->profileUserPosts); // Also refresh posts if needed
            }

        } catch (\Exception $e) {
            Log::error("Error toggling follow for user ID {$userIdToFollow} by user ID {$user->id}: " . $e->getMessage());
            session()->flash('error', 'Could not update follow status. Please try again.'. $e->getMessage());
        }
    }

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

            // Get the fresh like count directly from the relationship
            $newLikeCount = $post->likers()->count();
            PostLikeStatusChanged::dispatch($post->id, $newLikeCount, $user->id, $wasLiked);
            // Unset computed properties to force recalculation on next render
            unset($this->currentUserPosts);
            unset($this->profileUserPosts);

        } catch (\Exception $e) {
            Log::error("Error toggling like for post ID {$postId} by user ID {$user->id}: " . $e->getMessage());
            session()->flash('error', 'Could not update like status. Please try again.');
        }
    }

    public function deletePostFromProfile($postId) // <-- The new method
    {
        // Find the post
        $post = user_posts::find($postId);
        if (!$post) {
            session()->flash('error', 'Post not found.');
            return;
        }

        // Authorization check (same as in :canShow)
        $user = Auth::user();
        if (!$user || ($user->id !== $post->user_id && !in_array($user->account_type, ['admin', 'moderator']))) {
            session()->flash('error', 'You are not authorized to delete this post.');
            Log::warning("Unauthorized delete attempt for post ID: {$postId} from profile view by user ID: {$user->id}");
            return;
        }

        try {
            // Delete image from storage if it exists
            if ($post->image && Storage::disk('public')->exists($post->image)) {
                Storage::disk('public')->delete($post->image);
            }

            // Delete the post record
            $post->delete();

            // Flash success message
            session()->flash('success', 'Post deleted successfully.'); // Use 'success' key

            // *** Refresh the correct computed property ***
            // Unset the computed property cache to force re-evaluation on the next render cycle
            if ($this->profileUser) {
                unset($this->profileUserPosts); // Clear cache for profile user's posts
            } else {
                unset($this->currentUserPosts); // Clear cache for logged-in user's posts
            }

        } catch (\Exception $e) {
            session()->flash('error', 'Failed to delete post. Please try again.');
            Log::error("Error deleting post ID {$postId} from profile view: " . $e->getMessage());
        }
    }

    #[Computed] // No need to persist heavily, depends on $profileUser
    public function profileUserPosts()
    {
        if (!$this->profileUser) {
            return collect();
        }
        $loggedInUserId = Auth::id(); // Can be null if guest views profile

        // Fetch posts for the selected user WITH LIKE INFO
        return user_posts::where('user_id', $this->profileUser->id)
                         ->with('user') // Load author info
                         // *** ADD THESE LINES ***
                         ->withCount('likers') // Get like count as 'likers_count'
                         // Load relation for is_liked check *if* a user is logged in
                         ->when($loggedInUserId, function ($query) use ($loggedInUserId) {
                             $query->with(['likers' => function ($q) use ($loggedInUserId) {
                                 $q->where('user_id', $loggedInUserId);
                             }]);
                         })
                         // *** END ADDED LINES ***
                         ->latest()
                         ->limit(9) // Keep your limit
                         ->get();
    }
    #[On('showProfile')] // This attribute links the event to this method
    public function showProfile($userId)
    {
        Log::debug("showProfile called with User ID: {$userId}");
        $foundUser = User::find($userId);
        if ($foundUser) {
            $this->profileUser = $foundUser;
            // Optional: Clear search results when viewing a profile
            // $this->query = '';
            // $this->users = [];
            Log::info("Displaying profile for User ID: {$userId}");
        } else {
            // Handle case where user ID is invalid
            $this->profileUser = null; // Reset to show logged-in user or handle error
            session()->flash('error', 'Could not find the requested user profile.');
            Log::warning("User profile not found for ID: {$userId}");
        }
         // Ensure computed properties depending on $profileUser are reset
         unset($this->profileUserPosts);
    }

    public function deleteUser($userId){
        $currentUser = Auth::user();
        if (!$currentUser || !in_array($currentUser->account_type, ['admin', 'moderator']) || $currentUser->id == $userId) {
            session()->flash('error', 'You are not authorized to perform this action.');
            Log::warning("Unauthorized delete attempt for user ID: {$userId} by user ID: {$currentUser->id}");
            return; // Stop execution
       }
        $userToDelete = User::find($userId);

        if (!$userToDelete) {
            session()->flash('error', 'User not found.');
            return;
        }

        try {
            $userName = $userToDelete->first_name . ' ' . $userToDelete->last_name; // Get name before deleting
            $userToDelete->delete();

            session()->flash('success', "User '{$userName}' deleted successfully.");
            Log::info("User ID: {$userId} deleted by User ID: {$currentUser->id}");

            // Reset the profile view if the deleted user was being shown
            if ($this->profileUser && $this->profileUser->id === $userId) {
                $this->profileUser = null;
            }
            // Optionally refresh search results if the user might have been listed
            $this->updatedQuery(); // Re-run the search logic

        } catch (\Exception $e) {
            session()->flash('error', 'Failed to delete user. Please try again.');
            Log::error("Error deleting user ID {$userId}: " . $e->getMessage());
        }
    }
    
    public function updatedQuery(){
        if (strlen($this->query) >= 2) { // Example: search only if 2+ chars
            $this->users = User::where('first_name', 'like' , '%' . $this->query . '%')
                               ->orWhere('last_name', 'like', '%' . $this->query . '%')
                               ->limit(10) // Optional: limit results
                               ->get();
       } else {
           $this->users = []; // Clear results if query is too short
       }
    }

    public function mount()
    {


        Log::debug("--- SearchAccountsDashboard: Mounting. Target User ID from parent: " . ($this->targetUserId ?? 'NULL'));
        // Load profile if a target ID was passed during mount
        if ($this->targetUserId) {
            $this->showProfile($this->targetUserId); // Handles initial load when switching TO this tab
        }
        // Initialize collections if not already done
        if (!isset($this->likersList)) {
             $this->likersList = new Collection();
        }
        // Initialize $users if needed
        if (!isset($this->users)) {
             $this->users = [];
        }
    }

    #[On('loadProfile')]
    public function handleLoadProfile($userId)
    {
        if (!$this->profileUser || $this->profileUser->id !== (int)$userId) {
             Log::debug("--- SearchAccountsDashboard: Loading new profile via event.");
             $this->showProfile($userId); // Call the method to load the profile data
        } else {
             Log::debug("--- SearchAccountsDashboard: Profile requested via event is already loaded.");
        }
    }
    public function render()
    {
        return view('livewire.search-accounts-dashboard');
    }
}
