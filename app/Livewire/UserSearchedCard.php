<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\User;
class UserSearchedCard extends Component

{

    public User $user;
    public function render()
    {
        return view('livewire.user-searched-card');
    }
}
