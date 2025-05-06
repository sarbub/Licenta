<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Collection; // Import Collection

class MyFollowers extends Component
{
    public Collection $followers;

    public function mount()
    {
        $this->loadFollowers();
    }

    public function loadFollowers()
    {
        if (Auth::check()) {
            $user = Auth::user();
            // Use the 'followers' relationship
            $this->followers = $user->followers()
                                    // Select only the necessary columns from the related users table
                                    // Include 'profile_photo_path' as the accessor likely needs it
                                    ->select('users.id', 'users.first_name', 'users.last_name', 'users.profile_photo_path')
                                    ->get();
        } else {
            $this->followers = new Collection();
        }
    }

    public function render()
    {
        return view('livewire.my-followers');
    }
}
