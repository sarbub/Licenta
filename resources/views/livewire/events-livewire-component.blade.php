<div>
    <div class="p-4 md:p-6"> {{-- Add padding --}}

        {{-- Flash Messages --}}
        {{-- ... (remain the same) ... --}}
         @if (session()->has('event_success'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)"
                 class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('event_success') }}</span>
            </div>
        @endif
        @if (session()->has('event_error'))
            <div x-data="{ show: true }" x-show="show"
                 class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('event_error') }}</span>
                 <button @click="show = false" class="absolute top-0 bottom-0 right-0 px-4 py-3 text-red-500 hover:text-red-700">&times;</button>
            </div>
        @endif

        {{-- Create Event Form Section --}}
        {{-- ... (remain the same) ... --}}
        <h2 class="text-xl font-semibold mb-4">Create New Event</h2>
        <form wire:submit.prevent="saveEvent" class="space-y-4">
            {{-- Event Title --}}
            <div>
                <label for="title" class="block text-sm font-medium text-gray-700">Event Title</label>
                <input type="text" id="title" wire:model.lazy="title"
                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('title') border-red-500 @enderror"
                       placeholder="e.g., Community Meetup">
                @error('title') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
            </div>

            {{-- Event Description --}}
            <div>
                <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                <textarea id="description" wire:model.lazy="description" rows="4"
                          class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('description') border-red-500 @enderror"
                          placeholder="Provide details about the event..."></textarea>
                @error('description') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
            </div>

            {{-- Event Location --}}
            <div>
                <label for="location" class="block text-sm font-medium text-gray-700">Location</label>
                <input type="text" id="location" wire:model.lazy="location"
                       class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('location') border-red-500 @enderror"
                       placeholder="e.g., Central Park Pavilion">
                @error('location') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
            </div>

            {{-- Start and End Time --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="start_time" class="block text-sm font-medium text-gray-700">Start Time</label>
                    <input type="datetime-local" id="start_time" wire:model.lazy="start_time"
                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('start_time') border-red-500 @enderror">
                    @error('start_time') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label for="end_time" class="block text-sm font-medium text-gray-700">End Time</label>
                    <input type="datetime-local" id="end_time" wire:model.lazy="end_time"
                           class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('end_time') border-red-500 @enderror">
                    @error('end_time') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                </div>
            </div>

            {{-- Is Public Checkbox --}}
            <div class="flex items-center">
                <input id="is_public" type="checkbox" wire:model="is_public"
                       class="h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                <label for="is_public" class="ml-2 block text-sm text-gray-900">
                    Make this event public? (Visible to everyone)
                </label>
                @error('is_public') <span class="text-red-500 text-xs ml-4">{{ $message }}</span> @enderror
            </div>

            {{-- Submit Button --}}
            <div class="flex justify-end pt-2">
                <button type="submit"
                        class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50"
                        wire:loading.attr="disabled"
                        wire:target="saveEvent">
                    <span wire:loading.remove wire:target="saveEvent">Create Event</span>
                    <span wire:loading wire:target="saveEvent">Creating...</span>
                </button>
            </div>
        </form>
        {{-- End Create Event Form Section --}}


        {{-- Display User's Events Section --}}
        <div class="mt-10 border-t border-gray-200 pt-6">
            <h3 class="text-xl font-semibold mb-6">My Created Events</h3>

            @auth
                <div class="space-y-6">
                    @forelse ($userEvents as $event)
                        <div wire:key="user-event-{{ $event->id }}" class="bg-white shadow-md rounded-lg p-4 border border-gray-100 space-y-3 relative">

                            {{-- Header: Title & Public Status --}}
                            {{-- ... (remain the same) ... --}}
                             <div class="flex justify-between items-start">
                                <h4 class="text-lg font-bold text-gray-800">{{ $event->title }}</h4>
                                <span @class([
                                    'px-2 py-0.5 rounded-full text-xs font-medium',
                                    'bg-green-100 text-green-800' => $event->is_public,
                                    'bg-yellow-100 text-yellow-800' => !$event->is_public,
                                ])>
                                    {{ $event->is_public ? 'Public' : 'Private' }}
                                </span>
                            </div>

                            {{-- Details: Time & Location --}}
                            {{-- ... (remain the same) ... --}}
                             <div class="text-sm text-gray-600 space-y-1 border-t border-gray-100 pt-2">
                                <p>
                                    <span class="font-semibold">Starts:</span>
                                    {{ $event->start_time->format('D, M j, Y - g:i A') }}
                                </p>
                                <p>
                                    <span class="font-semibold">Ends:</span>
                                    {{ $event->end_time->format('D, M j, Y - g:i A') }}
                                </p>
                                @if ($event->location)
                                    <p><span class="font-semibold">Location:</span> {{ $event->location }}</p>
                                @endif
                            </div>

                            {{-- Description --}}
                            {{-- ... (remain the same) ... --}}
                             @if ($event->description)
                                <div class="prose prose-sm max-w-none text-gray-700 border-t border-gray-100 pt-2">
                                    <p>{{ $event->description }}</p>
                                </div>
                            @endif

                            {{-- Footer: Participants & Actions --}}
                            <div class="mt-2 pt-3 border-t border-gray-100 flex flex-wrap justify-between items-center gap-3">
                                {{-- Participant Count --}}
                                <div class="text-sm text-gray-500 flex items-center gap-1">
                                    {{-- ... (remain the same) ... --}}
                                    <svg class="size-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z" />
                                    </svg>
                                    @if($event->participants_count > 0)
                                        <button
                                            wire:click.prevent="showMyParticipants({{ $event->id }})"
                                            wire:loading.attr="disabled"
                                            wire:target="showMyParticipants({{ $event->id }})"
                                            class="hover:text-blue-600 hover:underline focus:outline-none focus:text-blue-700">
                                            <span>{{ $event->participants_count }} {{ Str::plural('Participant', $event->participants_count) }}</span>
                                        </button>
                                    @else
                                        <span>{{ $event->participants_count }} {{ Str::plural('Participant', $event->participants_count) }}</span>
                                    @endif
                                    <span wire:loading wire:target="showMyParticipants({{ $event->id }})" class="ml-2">
                                        {{-- ... (Spinner SVG) ... --}}
                                        <svg class="animate-spin h-4 w-4 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                    </span>
                                </div>

                                {{-- Action Buttons --}}
                                <div class="flex gap-2">
                                    <button
                                        wire:click.prevent="editEvent({{ $event->id }})"
                                        wire:loading.attr="disabled"
                                        wire:target="editEvent({{ $event->id }})"
                                        class="text-xs text-blue-600 hover:underline focus:outline-none">
                                        Edit
                                    </button>
                                    {{-- *** MODIFIED Delete Button *** --}}
                                    <button
                                        wire:click.prevent="confirmDelete({{ $event->id }})"
                                        wire:loading.attr="disabled"
                                        wire:target="confirmDelete({{ $event->id }})"
                                        class="text-xs text-red-600 hover:underline focus:outline-none">
                                        Delete
                                    </button>
                                    {{-- *** END MODIFIED Delete Button *** --}}
                                </div>
                            </div> {{-- End Footer --}}

                        </div> {{-- End Event Card --}}
                    @empty
                        <p class="text-gray-500">You haven't created any events yet.</p>
                    @endforelse
                </div>
            @else
                <p class="text-gray-500">Log in to view and manage your events.</p>
            @endauth
        </div>
        {{-- End Display User's Events Section --}}

    </div> {{-- End Padding Div --}}


    {{-- Participants Modal for My Events --}}
    {{-- ... (remain the same) ... --}}
    <div
        x-data="{ show: @entangle('showMyEventParticipantsModal') }"
        x-show="show"
        x-on:keydown.escape.window="$wire.closeMyParticipantsModal()"
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900 bg-opacity-75 p-4"
        style="display: none;"
    >
        {{-- Modal Content --}}
        <div
            @click.away="$wire.closeMyParticipantsModal()"
            x-show="show"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            class="bg-white rounded-lg shadow-xl overflow-hidden max-w-lg w-full mx-auto"
        >
            {{-- Modal Header --}}
            <div class="flex justify-between items-center p-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800">
                    Participants for "{{ $myModalEventTitle ?? 'Event' }}"
                </h3>
                <button
                    wire:click.prevent="closeMyParticipantsModal"
                    class="text-gray-400 hover:text-gray-600 focus:outline-none">
                    <svg class="size-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            {{-- Modal Body --}}
            <div class="p-6 max-h-96 overflow-y-auto">
                <ul class="space-y-3">
                    @forelse ($myModalParticipants as $participant)
                        <li wire:key="my-modal-participant-{{ $participant->id }}" wire:click.prevent=" $dispatch('showUserProfile', { userId: {{ $participant->id }} })" class="flex cursor-pointer items-center gap-3 p-2 rounded hover:bg-gray-50">
                            <img src="{{ $participant->profile_photo_url }}" alt="{{ $participant->first_name }}" class="size-8 rounded-full object-cover">
                            <span class="text-gray-700">{{ $participant->first_name }} {{ $participant->last_name }}</span>
                        </li>
                    @empty
                        <li class="text-gray-500 text-center py-4">No participants have registered yet.</li>
                    @endforelse
                </ul>
            </div>

            {{-- Modal Footer --}}
            <div class="bg-gray-50 px-4 py-3 sm:px-6 flex justify-end">
                <button
                    type="button"
                    wire:click.prevent="closeMyParticipantsModal"
                    class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-400 text-sm">
                    Close
                </button>
            </div>
        </div>
    </div>
    {{-- End Participants Modal --}}


    {{-- Edit Event Modal --}}
    {{-- ... (remain the same) ... --}}
    <div
        x-data="{ show: @entangle('showEditModal') }"
        x-show="show"
        x-on:keydown.escape.window="$wire.cancelEdit()"
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900 bg-opacity-75 p-4"
        style="display: none;"
    >
        {{-- Modal Content --}}
        <div
            @click.away="$wire.cancelEdit()"
            x-show="show"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            class="bg-white rounded-lg shadow-xl overflow-hidden max-w-2xl w-full mx-auto"
        >
            {{-- Form for updating --}}
            <form wire:submit.prevent="updateEvent">
                {{-- Modal Header --}}
                <div class="flex justify-between items-center p-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-800">
                        Edit Event: {{ $editingEventData['title'] ?? '' }}
                    </h3>
                    <button
                        type="button"
                        wire:click.prevent="cancelEdit"
                        class="text-gray-400 hover:text-gray-600 focus:outline-none">
                        <svg class="size-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                {{-- Modal Body (Form Fields) --}}
                <div class="p-6 space-y-4 max-h-[70vh] overflow-y-auto">
                    {{-- Event Title --}}
                    <div>
                        <label for="edit_title" class="block text-sm font-medium text-gray-700">Event Title</label>
                        <input type="text" id="edit_title" wire:model.lazy="editingEventData.title"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('editingEventData.title') border-red-500 @enderror">
                        @error('editingEventData.title') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    {{-- Event Description --}}
                    <div>
                        <label for="edit_description" class="block text-sm font-medium text-gray-700">Description</label>
                        <textarea id="edit_description" wire:model.lazy="editingEventData.description" rows="4"
                                  class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('editingEventData.description') border-red-500 @enderror"></textarea>
                        @error('editingEventData.description') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    {{-- Event Location --}}
                    <div>
                        <label for="edit_location" class="block text-sm font-medium text-gray-700">Location</label>
                        <input type="text" id="edit_location" wire:model.lazy="editingEventData.location"
                               class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('editingEventData.location') border-red-500 @enderror">
                        @error('editingEventData.location') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>

                    {{-- Start and End Time --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="edit_start_time" class="block text-sm font-medium text-gray-700">Start Time</label>
                            <input type="datetime-local" id="edit_start_time" wire:model.lazy="editingEventData.start_time"
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('editingEventData.start_time') border-red-500 @enderror">
                            @error('editingEventData.start_time') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label for="edit_end_time" class="block text-sm font-medium text-gray-700">End Time</label>
                            <input type="datetime-local" id="edit_end_time" wire:model.lazy="editingEventData.end_time"
                                   class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm @error('editingEventData.end_time') border-red-500 @enderror">
                            @error('editingEventData.end_time') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    {{-- Is Public Checkbox --}}
                    <div class="flex items-center">
                        <input id="edit_is_public" type="checkbox" wire:model="editingEventData.is_public"
                               class="h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                        <label for="edit_is_public" class="ml-2 block text-sm text-gray-900">
                            Make this event public?
                        </label>
                        @error('editingEventData.is_public') <span class="text-red-500 text-xs ml-4">{{ $message }}</span> @enderror
                    </div>

                </div> {{-- End Modal Body --}}

                {{-- Modal Footer --}}
                <div class="bg-gray-50 px-4 py-3 sm:px-6 flex justify-end gap-3">
                    <button
                        type="button"
                        wire:click.prevent="cancelEdit"
                        class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-400 text-sm">
                        Cancel
                    </button>
                    <button
                        type="submit"
                        class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 text-sm disabled:opacity-50"
                        wire:loading.attr="disabled"
                        wire:target="updateEvent">
                        <span wire:loading.remove wire:target="updateEvent">Update Event</span>
                        <span wire:loading wire:target="updateEvent">Updating...</span>
                    </button>
                </div>
            </form> {{-- End Form --}}
        </div>
    </div>
    {{-- End Edit Event Modal --}}


    {{-- *** ADDED: Delete Confirmation Modal *** --}}
    <div
        x-data="{ show: @entangle('showDeleteConfirmModal') }" {{-- Bind to new property --}}
        x-show="show"
        x-on:keydown.escape.window="$wire.cancelDelete()" {{-- Call cancel method --}}
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900 bg-opacity-75 p-4"
        style="display: none;"
    >
        {{-- Modal Content --}}
        <div
            @click.away="$wire.cancelDelete()" {{-- Call cancel method --}}
            x-show="show"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            class="bg-white rounded-lg shadow-xl overflow-hidden max-w-md w-full mx-auto" {{-- Standard modal width --}}
        >
            {{-- Modal Header --}}
            <div class="flex justify-between items-center p-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800">
                    Confirm Deletion
                </h3>
                <button
                    type="button"
                    wire:click.prevent="cancelDelete"
                    class="text-gray-400 hover:text-gray-600 focus:outline-none">
                    <svg class="size-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            {{-- Modal Body --}}
            <div class="p-6">
                <p class="text-sm text-gray-700">
                    Are you sure you want to delete the event
                    <strong class="font-medium">"{{ $deletingEventTitle ?? 'this event' }}"</strong>?
                    This action cannot be undone.
                </p>
            </div>

            {{-- Modal Footer --}}
            <div class="bg-gray-50 px-4 py-3 sm:px-6 flex justify-end gap-3">
                <button
                    type="button"
                    wire:click.prevent="cancelDelete"
                    class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-400 text-sm">
                    Cancel
                </button>
                <button
                    type="button" {{-- Use type="button" as form submission is handled by wire:click --}}
                    wire:click.prevent="deleteEvent"
                    class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 text-sm disabled:opacity-50"
                    wire:loading.attr="disabled"
                    wire:target="deleteEvent">
                    <span wire:loading.remove wire:target="deleteEvent">Delete Event</span>
                    <span wire:loading wire:target="deleteEvent">Deleting...</span>
                </button>
            </div>
        </div>
    </div>
    {{-- *** End Delete Confirmation Modal *** --}}


</div> {{-- End Root Div --}}
