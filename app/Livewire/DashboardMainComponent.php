<?php

namespace App\Livewire;

use App\Livewire\EventsLivewireComponent; // Import child component classes
use App\Livewire\SearchAccountsDashboard;
use App\Livewire\UserPost;
use Livewire\Livewire;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Log;

class DashboardMainComponent extends Component
{
    public ?int $targetProfileUserId = null;

    #[Url(as : 'tab', keep:true)]
    public $activeComponent = 'user-post'; // Default to showing posts

    public function setActiveComponent($component)
    {
        // Check if the requested component is already the active one
        if ($this->activeComponent === $component) {
            Log::debug("setActiveComponent: Component '{$component}' is already active. Dispatching refresh event.");
            // Dispatch a refresh event specific to the component type
            // We use different event names to avoid unintended refreshes
            match ($component) {
                'user-post' => $this->dispatch('refreshUserPosts'),
                'search-accounts-dashboard' => $this->dispatch('refreshSearchDashboard'),
                'events-livewire-component' => $this->dispatch('refreshEventsComponent'),
                default => null, // Do nothing if component name is unknown
            };
        } else {
            // If it's a different component, just switch
            Log::debug("setActiveComponent: Switching to component '{$component}'.");
            $this->activeComponent = $component;
            // Clear the target user ID if switching away from search
            if ($component !== 'search-accounts-dashboard') {
                $this->targetProfileUserId = null;
            }
        }
    }

    #[On('showUserProfile')]
    public function handleShowUserProfileRequest($userId)
    {
        // ... (previous correct logic remains the same) ...
        $this->targetProfileUserId = (int) $userId;
        if ($this->activeComponent !== 'search-accounts-dashboard') {
            Log::debug('Switching active component to search-accounts-dashboard. Child mount() will handle ID.');
            $this->setActiveComponent('search-accounts-dashboard');
        } else {
            Log::debug('Already on search-accounts-dashboard. Dispatching loadProfile.');
            $this->dispatch('loadProfile', userId: $this->targetProfileUserId)->to(SearchAccountsDashboard::class);
        }
    }

    #[On('switchToCreateEvent')]
    public function handleSwitchToCreateEvent()
    {
        $this->setActiveComponent('events-livewire-component');
    }

    public function render()
    {
        return view('livewire.dashboard-main-component');
    }
}
