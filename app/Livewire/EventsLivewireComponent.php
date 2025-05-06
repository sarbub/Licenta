<?php // app/Livewire/EventsLivewireComponent.php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Event;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\QueryException;
use Livewire\Attributes\On;
use Carbon\Carbon; // <-- Add Carbon for date comparison

class EventsLivewireComponent extends Component
{
    // --- ADD THESE FORM PROPERTIES ---
    public $title = '';
    public $description = '';
    public $location = '';
    public $start_time = '';
    public $end_time = '';
    public $is_public = true; // Default to public
    // --- END FORM PROPERTIES ---

    // --- ADD VALIDATION RULES ---
    protected function rules(): array
    {
        return [
            'title' => 'required|string|min:3|max:255',
            'description' => 'nullable|string|max:5000',
            'location' => 'nullable|string|max:255',
            'start_time' => 'required|date|after_or_equal:now', // Ensure start time is not in the past
            'end_time' => 'required|date|after:start_time', // Ensure end time is after start time
            'is_public' => 'required|boolean',
        ];
    }
    // --- END VALIDATION RULES ---

    // --- ADD VALIDATION MESSAGES (Optional but recommended) ---
    protected function messages(): array
    {
        return [
            'title.required' => 'The event title is required.',
            'title.min' => 'The title must be at least 3 characters.',
            'start_time.required' => 'Please specify the start time.',
            'start_time.after_or_equal' => 'The start time cannot be in the past.',
            'end_time.required' => 'Please specify the end time.',
            'end_time.after' => 'The end time must be after the start time.',
        ];
    }
    // --- END VALIDATION MESSAGES ---

    // --- ADD THE saveEvent METHOD ---
    public function saveEvent()
    {
        // Validate the data using the rules defined above
        $validatedData = $this->validate();

        // Ensure user is logged in before creating
        if (!Auth::check()) {
            session()->flash('event_error', 'You must be logged in to create an event.');
            return;
        }

        try {
            // Create the event
            Event::create([
                'title' => $validatedData['title'],
                'description' => $validatedData['description'],
                'location' => $validatedData['location'],
                'start_time' => Carbon::parse($validatedData['start_time']), // Ensure it's a Carbon instance
                'end_time' => Carbon::parse($validatedData['end_time']),     // Ensure it's a Carbon instance
                'is_public' => $validatedData['is_public'],
                'created_by' => Auth::id(), // Associate with the logged-in user
                'status' => 'scheduled', // Default status
            ]);

            // Reset form fields after successful save
            $this->reset(['title', 'description', 'location', 'start_time', 'end_time', 'is_public']);
            $this->is_public = true; // Reset checkbox default if needed

            // Flash success message
            session()->flash('event_success', 'Event created successfully!');

            // Dispatch an event to notify other components (like UserEventsComponentDisplay)
            $this->dispatch('event-created');

        } catch (QueryException $e) {
            Log::error("Database error creating event: " . $e->getMessage());
            session()->flash('event_error', 'Could not save the event due to a database error. Please try again.');
        } catch (\Exception $e) {
            Log::error("Error creating event: " . $e->getMessage());
            session()->flash('event_error', 'An unexpected error occurred while creating the event. Please try again.');
        }
    }
    // --- END saveEvent METHOD ---

    // --- Modal State Properties for "My Events" ---
    public $showMyEventParticipantsModal = false;
    public $myModalEventId = null; // Initialize to null
    public $myModalEventTitle = '';
    public $myModalParticipants = [];
     // --- Method to Show Participants Modal for "My Events" ---
     public function showMyParticipants($eventId)
     {
         Log::debug("showMyParticipants called for event ID: {$eventId}");
 
         // Find the event (ensure it belongs to the user for security if needed, though listing implies ownership)
         $event = Event::where('created_by', Auth::id()) // Optional: Re-verify ownership
                      ->with(['participants' => function ($query) {
                          $query->select('users.id', 'users.first_name', 'users.last_name', 'users.profile_photo_path');
                       }])
                      ->select('id', 'title')
                      ->find($eventId);
 
         if (!$event) {
             session()->flash('event_error', 'Event not found or you do not own it.');
             Log::warning("Event not found or ownership mismatch in showMyParticipants for ID: {$eventId}, User: " . Auth::id());
             return;
         }
 
         // Set the specific modal properties
         $this->myModalEventId = $event->id;
         $this->myModalEventTitle = $event->title;
         $this->myModalParticipants = $event->participants;
         Log::debug('My Event Modal properties set', ['id' => $this->myModalEventId, 'title' => $this->myModalEventTitle, 'participants_count' => count($this->myModalParticipants)]);
 
         // Set modal visibility
         $this->showMyEventParticipantsModal = true;
         Log::debug('showMyEventParticipantsModal set to true');
     }

     public function closeMyParticipantsModal()
    {
        $this->showMyEventParticipantsModal = false;
        $this->myModalEventId = null;
        $this->myModalEventTitle = null; // Reset title
        $this->myModalParticipants = []; // Reset participants array
        Log::debug('My Event Participants modal closed and properties reset');
    }

    public function editEvent($eventId)
    {
        Log::debug("editEvent called for event ID: {$eventId}");
        $event = Event::where('created_by', Auth::id()) // Ensure user owns the event
                     ->find($eventId);

        if (!$event) {
            session()->flash('event_error', 'Event not found or you are not authorized to edit it.');
            Log::warning("Edit attempt failed: Event not found or ownership mismatch for ID: {$eventId}, User: " . Auth::id());
            return;
        }

        $this->editingEventId = $event->id;
        // Populate the editing data array
        $this->editingEventData = [
            'title' => $event->title,
            'description' => $event->description,
            'location' => $event->location,
            // Format dates for datetime-local input
            'start_time' => $event->start_time->format('Y-m-d\TH:i'),
            'end_time' => $event->end_time->format('Y-m-d\TH:i'),
            'is_public' => $event->is_public,
        ];

        $this->showEditModal = true; // Open the modal
        Log::debug('Edit modal opened for event ID: ' . $this->editingEventId);
    }

    // --- *** ADD Edit Event Methods *** ---
    public $showEditModal = false; // Add this property
    public $editingEventId = null;
    public $editingEventData = [];

    public function updateEvent()
    {
        Log::debug("updateEvent called for event ID: {$this->editingEventId}");

        // Validate the editing data
        $validatedData = $this->validate([
            'editingEventData.title' => 'required|string|min:3|max:255',
            'editingEventData.description' => 'nullable|string|max:5000',
            'editingEventData.location' => 'nullable|string|max:255',
            'editingEventData.start_time' => 'required|date|after_or_equal:now',
            'editingEventData.end_time' => 'required|date|after:editingEventData.start_time',
            'editingEventData.is_public' => 'required|boolean',
        ]); // Pass specific rules for the editing array

        if (!$this->editingEventId) {
            session()->flash('event_error', 'Cannot update event: No event selected.');
            return;
        }

        $event = Event::where('created_by', Auth::id()) // Re-verify ownership
                     ->find($this->editingEventId);

        if (!$event) {
            session()->flash('event_error', 'Event not found or you are not authorized to update it.');
            Log::warning("Update attempt failed: Event not found or ownership mismatch for ID: {$this->editingEventId}, User: " . Auth::id());
            $this->cancelEdit(); // Close modal if event disappears
            return;
        }

        try {
            // Update the event fields from the validated editing data
            $event->update([
                'title' => $validatedData['editingEventData']['title'],
                'description' => $validatedData['editingEventData']['description'],
                'location' => $validatedData['editingEventData']['location'],
                'start_time' => Carbon::parse($validatedData['editingEventData']['start_time']),
                'end_time' => Carbon::parse($validatedData['editingEventData']['end_time']),
                'is_public' => $validatedData['editingEventData']['is_public'],
            ]);

            session()->flash('event_success', 'Event updated successfully!');
            $this->dispatch('event-created');
            $this->cancelEdit(); // Close modal and reset state
            // No need to explicitly dispatch 'event-created' here,
            // the component will re-render with updated data from the render method.

        } catch (QueryException $e) {
            Log::error("Database error updating event ID {$this->editingEventId}: " . $e->getMessage());
            session()->flash('event_error', 'Could not update the event due to a database error.');
        } catch (\Exception $e) {
            Log::error("Error updating event ID {$this->editingEventId}: " . $e->getMessage());
            session()->flash('event_error', 'An unexpected error occurred while updating the event.');
        }
    }

    public function cancelEdit()
    {
        $this->showEditModal = false;
        $this->editingEventId = null;
        // Reset the editing data array
        $this->editingEventData = [
            'title' => '', 'description' => '', 'location' => '',
            'start_time' => '', 'end_time' => '', 'is_public' => true,
        ];
        Log::debug('Edit modal cancelled/closed.');
    }
    // --- *** END Edit Event Methods *** ---
    

    #[On('refreshEventsComponent')]
    public function refreshData()
    {
        Log::debug('--- EventsLivewireComponent: Refreshing data via refreshEventsComponent event ---');
        // Since the render method fetches the data, simply triggering a re-render
        // is often enough. An empty listener method body achieves this.
        // If you had a specific loadUserEvents() method, you could call it here.
    }

    // --- *** ADDED: Delete Event Methods *** ---
    public function confirmDelete($eventId)
    {
        Log::debug("confirmDelete called for event ID: {$eventId}");
        $event = Event::where('created_by', Auth::id()) // Ensure user owns the event
                     ->select('id', 'title') // Only need ID and title
                     ->find($eventId);

        if (!$event) {
            session()->flash('event_error', 'Event not found or you are not authorized to delete it.');
            Log::warning("Delete confirmation failed: Event not found or ownership mismatch for ID: {$eventId}, User: " . Auth::id());
            return;
        }

        $this->deletingEventId = $event->id;
        $this->deletingEventTitle = $event->title; // Store title for modal message
        $this->showDeleteConfirmModal = true; // Open the confirmation modal
        Log::debug('Delete confirmation modal opened for event ID: ' . $this->deletingEventId);
    }

    public function deleteEvent()
    {
        Log::debug("deleteEvent called for event ID: {$this->deletingEventId}");

        if (!$this->deletingEventId) {
            session()->flash('event_error', 'Cannot delete event: No event selected.');
            return;
        }

        $event = Event::where('created_by', Auth::id()) // Re-verify ownership
                     ->find($this->deletingEventId);

        if (!$event) {
            session()->flash('event_error', 'Event not found or you are not authorized to delete it.');
            Log::warning("Delete attempt failed: Event not found or ownership mismatch for ID: {$this->deletingEventId}, User: " . Auth::id());
            $this->cancelDelete(); // Close modal if event disappears
            return;
        }

        try {
            $event->delete(); // Perform the deletion

            session()->flash('event_success', 'Event deleted successfully!');
            Log::info("Event ID {$this->deletingEventId} deleted by User ID: " . Auth::id());

            // Dispatch event to notify other components (like the display list)
            $this->dispatch('event-created'); // Reusing event name is fine for refresh

            $this->cancelDelete(); // Close modal and reset state

        } catch (QueryException $e) {
            Log::error("Database error deleting event ID {$this->deletingEventId}: " . $e->getMessage());
            session()->flash('event_error', 'Could not delete the event due to a database error.');
        } catch (\Exception $e) {
            Log::error("Error deleting event ID {$this->deletingEventId}: " . $e->getMessage());
            session()->flash('event_error', 'An unexpected error occurred while deleting the event.');
        }
    }

    public function cancelDelete()
    {
        $this->showDeleteConfirmModal = false;
        $this->deletingEventId = null;
        $this->deletingEventTitle = null;
        Log::debug('Delete confirmation modal cancelled/closed.');
    }
    // --- *** END Delete Event Methods *** ---
    // --- *** ADD Delete Event Methods *** ---
    public $showDeleteConfirmModal = false; // Add this property
    public $deletingEventId = null;
    public $deletingEventTitle = null;



    // The render method was already present, keep it
    public function render()
    {
        $userEvents = collect(); // Default to empty collection

        // Fetch events created by the logged-in user
        if (Auth::check()) {
            $userEvents = Event::where('created_by', Auth::id())
                               ->withCount('participants') // Get participant count
                               ->orderBy('start_time', 'asc') // Show upcoming first
                               ->get();
        }

        // Pass the user's events to the view
        return view('livewire.events-livewire-component', [
            'userEvents' => $userEvents, // Changed variable name
        ]);
    }
}
