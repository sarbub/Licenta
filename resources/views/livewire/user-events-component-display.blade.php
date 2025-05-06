{{-- resources/views/livewire/user-events-component-display.blade.php --}}
<div>
    <div class="p-4 md:p-6">

        {{-- Flash Messages (Success, Error, Info) --}}
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
        @if (session()->has('event_info'))
            <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)"
                 class="bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('event_info') }}</span>
            </div>
        @endif

        {{-- Display Public Events Section --}}
        <div class="mt-10 border-t border-gray-200 pt-6">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-semibold">Public Events</h3>
                {{-- Add Create Event Button --}}
                <button
                    {{-- Dispatch an event that the parent (DashboardMainComponent) will listen for --}}
                    wire:click.prevent="$dispatch('switchToCreateEvent')"
                    class="inline-flex items-center px-4 py-2 bg-blue-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-600 active:bg-blue-700 focus:outline-none focus:border-blue-700 focus:ring focus:ring-blue-200 disabled:opacity-25 transition ease-in-out duration-150">
                    {{-- Optional Plus Icon --}}
                    <svg class="w-4 h-4 mr-2 -ml-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    Create Event
                </button>
            </div>
            <div class="space-y-6">
                @forelse ($publicEvents as $event)
                    {{-- Event Card --}}
                    <div wire:key="event-display-{{ $event->id }}" class="bg-white shadow-lg rounded-xl p-6 border border-gray-100 transition-shadow duration-200 space-y-4">

                        {{-- Header: Title & Creator --}}
                        {{-- ... (same as before) ... --}}
                         <div>
                            <h4 class="text-xl font-bold text-gray-800 mb-1">{{ $event->title }}</h4>
                            @if ($event->creator)
                                <p class="text-sm text-gray-500 cursor-pointer">
                                    Organized by: <span wire:click.prevent=" $dispatch('showUserProfile', { userId: {{ $event->creator->id }} })" class="font-medium text-gray-600">{{ $event->creator->first_name }} {{ $event->creator->last_name }}</span>
                                </p>
                            @else
                                 <p class="text-sm text-gray-500">By: Unknown User</p>
                            @endif
                        </div>

                        {{-- Details: Time & Location --}}
                        {{-- ... (same as before) ... --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-3 text-sm text-gray-700 border-t border-b border-gray-100 py-3">
                            {{-- Start Time --}}
                            <div class="flex items-start gap-2">
                                <svg class="size-5 text-gray-400 mt-0.5 shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
                                </svg>
                                <div>
                                    <span class="font-semibold text-gray-800">Starts:</span><br>
                                    {{ $event->start_time->format('D, M j, Y') }}<br>
                                    {{ $event->start_time->format('g:i A') }}
                                </div>
                            </div>
                            {{-- End Time --}}
                            <div class="flex items-start gap-2">
                                <svg class="size-5 text-gray-400 mt-0.5 shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                     <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
                                </svg>
                                <div>
                                    <span class="font-semibold text-gray-800">Ends:</span><br>
                                    {{ $event->end_time->format('D, M j, Y') }}<br>
                                    {{ $event->end_time->format('g:i A') }}
                                </div>
                            </div>
                            {{-- Location --}}
                            @if ($event->location)
                                <div class="flex items-start gap-2 sm:col-span-2"> {{-- Span 2 cols on small+ --}}
                                    <svg class="size-5 text-gray-400 mt-0.5 shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                      <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                                      <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
                                    </svg>
                                    <div>
                                        <span class="font-semibold text-gray-800">Location:</span> {{ $event->location }}
                                    </div>
                                </div>
                            @endif
                        </div>

                        {{-- Description --}}
                        {{-- ... (same as before) ... --}}
                         @if ($event->description)
                            <div class="prose prose-sm max-w-none text-gray-800">
                                <h5 class="font-semibold mb-1">Details:</h5>
                                <p>{{ $event->description }}</p>
                            </div>
                        @endif

                        {{-- Footer: Participants & Action Button --}}
                        <div class="mt-2 pt-4 border-t border-gray-100 flex flex-wrap justify-between items-center gap-4">
                            {{-- Participant Count (Clickable if logged in) --}}
                            <div class="text-sm text-gray-600 flex items-center gap-1">
                                <svg class="size-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                  <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z" />
                                </svg>
                                @auth {{-- Only make clickable if logged in --}}
                                    @if($event->participants_count > 0)
                                        <button
                                            wire:click.prevent="showParticipants({{ $event->id }})"
                                            wire:loading.attr="disabled"
                                            wire:target="showParticipants({{ $event->id }})"
                                            class="hover:text-blue-600 hover:underline focus:outline-none focus:text-blue-700">
                                            <span>{{ $event->participants_count }} {{ Str::plural('Participant', $event->participants_count) }}</span>
                                        </button>
                                    @else
                                        <span>{{ $event->participants_count }} {{ Str::plural('Participant', $event->participants_count) }}</span>
                                    @endif
                                @else {{-- Display non-clickable count if not logged in --}}
                                    <span>{{ $event->participants_count }} {{ Str::plural('Participant', $event->participants_count) }}</span>
                                @endauth
                                {{-- Loading indicator for showing participants --}}
                                <span wire:loading wire:target="showParticipants({{ $event->id }})" class="ml-2">
                                    <svg class="animate-spin h-4 w-4 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                </span>
                            </div>

                            {{-- Participation Button/Status --}}
                            {{-- ... (same as before) ... --}}
                            <div>
                                @auth
                                    {{-- Check the eager-loaded 'is_participating' attribute --}}
                                    @if ($event->is_participating)
                                        {{-- Show Unenroll Button --}}
                                        <button
                                            wire:click.prevent="unenroll({{ $event->id }})"
                                            wire:loading.attr="disabled"
                                            wire:target="unenroll({{ $event->id }})"
                                            class="inline-flex items-center px-4 py-2 bg-red-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-600 active:bg-red-700 focus:outline-none focus:border-red-700 focus:ring focus:ring-red-200 disabled:opacity-50 transition ease-in-out duration-150">
                                            <span wire:loading.remove wire:target="unenroll({{ $event->id }})">Unenroll</span>
                                            <span wire:loading wire:target="unenroll({{ $event->id }})">Unenrolling...</span>
                                        </button>
                                    @else
                                        {{-- Show Participate Button --}}
                                        <button
                                            wire:click.prevent="participate({{ $event->id }})"
                                            wire:loading.attr="disabled"
                                            wire:target="participate({{ $event->id }})"
                                            class="inline-flex items-center px-4 py-2 bg-green-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-600 active:bg-green-700 focus:outline-none focus:border-green-700 focus:ring focus:ring-green-200 disabled:opacity-50 transition ease-in-out duration-150">
                                            <span wire:loading.remove wire:target="participate({{ $event->id }})">Participate</span>
                                            <span wire:loading wire:target="participate({{ $event->id }})">Registering...</span>
                                        </button>
                                    @endif
                                @else
                                    {{-- Link for non-logged-in users --}}
                                    <a href="{{ route('login') }}" class="text-sm text-blue-600 hover:underline">Log in to participate</a>
                                @endauth
                            </div>
                        </div> {{-- End Footer --}}

                    </div> {{-- End Event Card --}}
                @empty
                    <p class="text-gray-500">There are no public events scheduled at the moment.</p>
                @endforelse
            </div>
        </div> {{-- End Display Public Events Section --}}

    </div>

    {{-- Participants Modal --}}
    <div
        x-data="{ show: @entangle('showParticipantsModal') }"
        x-show="show"
        z-40
        x-on:keydown.escape.window="$wire.closeParticipantsModal()"
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900 bg-opacity-75 p-4"
        style="display: none;" {{-- Hide initially --}}
    >
        {{-- Modal Content --}}
        <div
            @click.away="$wire.closeParticipantsModal()" {{-- Close on overlay click --}}
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
                    Participants for "{{ $selectedEvent?->title ?? 'Event' }}"
                </h3>
                <button
                    wire:click.prevent="closeParticipantsModal"
                    class="text-gray-400 hover:text-gray-600 focus:outline-none">
                    <svg class="size-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            {{-- Modal Body --}}
            <div class="p-6 max-h-96 overflow-y-auto"> {{-- Add max height and scroll --}}
                <ul class="space-y-3">
                    @forelse ($selectedEventParticipants as $participant)
                        <li wire:key="participant-{{ $participant->id }}" class="flex items-center gap-3 p-2 rounded hover:bg-gray-50">
                            <img src="{{ $participant->profile_photo_url }}" alt="{{ $participant->first_name }}" class="size-8 rounded-full object-cover">
                            <span class="text-gray-700">{{ $participant->first_name }} {{ $participant->last_name }}</span>
                        </li>
                    @empty
                        <li class="text-gray-500 text-center py-4">No participants yet.</li>
                    @endforelse
                </ul>
            </div>

            {{-- Modal Footer (Optional) --}}
            <div class="bg-gray-50 px-4 py-3 sm:px-6 flex justify-end">
                <button
                    type="button"
                    wire:click.prevent="closeParticipantsModal"
                    class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-400 text-sm">
                    Close
                </button>
            </div>
        </div>
    </div>
    {{-- End Participants Modal --}}

</div>
