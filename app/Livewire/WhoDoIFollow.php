<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Collection; // Import Collection

class WhoDoIFollow extends Component
{
    // Property to hold the list of users being followed
    public Collection $followingList;

    /**
     * Load the users being followed when the component mounts.
     */
    public function mount()
    {
        $this->loadFollowing();
    }

    /**
     * Fetches the users the authenticated user is following.
     */
    public function loadFollowing()
    {
        if (Auth::check()) {
            $user = Auth::user();
            // Use the 'following' relationship defined in the User model
            $this->followingList = $user->following()
                                        // Select only the necessary columns
                                        ->select('users.id', 'users.first_name', 'users.last_name', 'users.profile_photo_path')
                                        ->get();
        } else {
            // If no user is logged in, initialize as an empty collection
            $this->followingList = new Collection();
        }
    }

    /**
     * Render the component's view.
     */
    public function render()
    {
        // The $followingList property is automatically available in the view
        return view('livewire.who-do-i-follow');
    }
}
