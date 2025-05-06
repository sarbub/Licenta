<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Event; // Use correct model name
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\QueryException;

class UserEventsComponentDisplay extends Component
{
    public $events;
    public $showNewEventNotification = false;

    // --- Modal State Properties ---
    public $showParticipantsModal = false;
    public ?Event $selectedEvent = null; // Store the whole event for title etc.
    public $selectedEventParticipants = [];
    // --- End Modal State Properties ---

    // Centralized method to load events with participation status
    private function loadEvents()
    {
        $query = Event::where('is_public', true)
                      ->where('start_time', '>=', now())
                      ->orderBy('start_time', 'asc');
                       // Keep the limit for the display component

        // Eager load participation status *only if* a user is logged in
        if (Auth::check()) {
            $userId = Auth::id();
            $query->withExists(['participants as is_participating' => function ($subQuery) use ($userId) {
                $subQuery->where('user_id', $userId);
            }]);
        }
        // Always load participant count and creator
        $query->withCount('participants');
        $query->with('creator:id,first_name,last_name'); // Select specific columns for creator

        $this->events = $query->get();
    }

    public function participate($eventId)
    {
        if (!Auth::check()) {
            session()->flash('event_error', 'You must be logged in to participate.');
            return;
        }
        $event = Event::find($eventId);
        if (!$event) {
            session()->flash('event_error', 'Event not found.');
            return;
        }
        $isAlreadyParticipating = $event->participants()->where('user_id', Auth::id())->exists();
        if ($isAlreadyParticipating) {
            session()->flash('event_info', 'You are already registered for this event.');
            $this->loadEvents();
            return;
        }
        try {
            $event->participants()->attach(Auth::id(), ['registered_at' => now(), 'is_confirmed' => false]);
            session()->flash('event_success', 'Successfully registered for the event!');
            $this->loadEvents();
        } catch (QueryException $e) {
            if ($e->errorInfo[1] == 1062) {
                 session()->flash('event_info', 'You are already registered for this event.');
            } else {
                Log::error("Participation registration failed for event {$eventId}, user " . Auth::id() . ": " . $e->getMessage());
                session()->flash('event_error', 'Could not register for the event. Please try again.');
            }
            $this->loadEvents();
        } catch (\Exception $e) {
            Log::error("Participation registration failed for event {$eventId}, user " . Auth::id() . ": " . $e->getMessage());
            session()->flash('event_error', 'Could not register for the event. Please try again.');
            $this->loadEvents();
        }
    }

    public function unenroll($eventId)
    {
        if (!Auth::check()) {
            session()->flash('event_error', 'You must be logged in to unenroll.');
            return;
        }
        $event = Event::find($eventId);
        if (!$event) {
            session()->flash('event_error', 'Event not found.');
            return;
        }
        $isParticipating = $event->participants()->where('user_id', Auth::id())->exists();
        if (!$isParticipating) {
            session()->flash('event_info', 'You are not currently registered for this event.');
            $this->loadEvents();
            return;
        }
        try {
            $event->participants()->detach(Auth::id());
            session()->flash('event_success', 'Successfully unenrolled from the event.');
            $this->loadEvents();
        } catch (\Exception $e) {
            Log::error("Unenrollment failed for event {$eventId}, user " . Auth::id() . ": " . $e->getMessage());
            session()->flash('event_error', 'Could not unenroll from the event. Please try again.');
            $this->loadEvents();
        }
    }

    // --- Method to Show Participants Modal ---
    public function showParticipants($eventId)
    {
        // Find the event and eager load participants with specific columns
        $event = Event::with(['participants' => function ($query) {
                        $query->select('users.id', 'users.first_name', 'users.last_name', 'users.profile_photo_path'); // Select columns from users table
                     }])
                     ->find($eventId);

        if (!$event) {
            session()->flash('event_error', 'Event not found.');
            return;
        }

        $this->selectedEvent = $event;
        $this->selectedEventParticipants = $event->participants;
        $this->showParticipantsModal = true;
        $this->loadEvents();
    }
    // --- End Method to Show Participants Modal ---

    // --- Method to Close Participants Modal ---
    public function closeParticipantsModal()
    {
        $this->showParticipantsModal = false;
        $this->loadEvents();
    }
    // --- End Method to Close Participants Modal ---

    public function mount()
    {
        $this->loadEvents();
    }

    #[On('event-created')]
    public function refreshEvents()
    {
        $this->loadEvents();
        $this->showNewEventNotification = true;
    }

    public function render()
    {
        return view('livewire.user-events-component-display', [
            'publicEvents' => $this->events,
        ]);
    }
}
