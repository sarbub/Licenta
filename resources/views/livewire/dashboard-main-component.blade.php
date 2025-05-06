<!-- resources/views/livewire/dashboard-main-component.blade.php -->

<div class="" >
    <ul class="flex gap-2 bg-white p-4 rounded-t-large sm:sticky top-0 z-10" style="border-top-left-radius: 10px; border-top-right-radius: 10px;">
        <li>
            {{-- Posts Button --}}
            <button
                wire:click="setActiveComponent('user-post')"
                @click="window.scrollTo({ top: 0, behavior: 'smooth' })" {{-- Add scroll to top --}}
                @class([
                    'px-4 py-2 rounded-full transition-colors duration-150 ease-in-out',
                    'text-blue-600' => $activeComponent === 'user-post', // Active state: blue text, bold
                    'text-gray-700 hover:text-blue-500' => $activeComponent !== 'user-post' // Inactive state: gray text, blue on hover
                ])>
                Posts
            </button>
        </li>
        <li>
            {{-- Search Button --}}
            <button
                wire:click="setActiveComponent('search-accounts-dashboard')"
                @click="window.scrollTo({ top: 0, behavior: 'smooth' })" {{-- Add scroll to top --}}
                @class([
                    'px-4 py-2 rounded-full transition-colors duration-150 ease-in-out',
                    'text-blue-600 font' => $activeComponent === 'search-accounts-dashboard', // Active state: blue text, bold
                    'text-gray-700 hover:text-blue-500' => $activeComponent !== 'search-accounts-dashboard' // Inactive state: gray text, blue on hover
                ])>
                Search
            </button>
        </li>
        <li>
        <button
                wire:click="setActiveComponent('events-livewire-component')"
                @click="window.scrollTo({ top: 0, behavior: 'smooth' })" {{-- Add scroll to top --}}
                @class([
                    'px-4 py-2 rounded-full transition-colors duration-150 ease-in-out',
                    'text-blue-600 font' => $activeComponent === 'events-livewire-component', // Active state: blue text, bold
                    'text-gray-700 hover:text-blue-500' => $activeComponent !== 'events-livewire-component' // Inactive state: gray text, blue on hover
                ])>
                Events
            </button>
        </li>
    </ul>

     {{-- *** SCROLL-TO-TOP BUTTON HERE *** --}}
     <div
        x-data="{ showScrollTopButton: false }"
        @scroll.window.debounce.100ms="showScrollTopButton = (window.scrollY > 200)" {{-- Show after scrolling 200px --}}
        class="fixed bottom-5 right-5 z-50" {{-- Fixed position, bottom-right --}}
    >
        <button
            x-show="showScrollTopButton"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 transform scale-90"
            x-transition:enter-end="opacity-100 transform scale-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 transform scale-100"
            x-transition:leave-end="opacity-0 transform scale-90"
            @click="window.scrollTo({ top: 0, behavior: 'smooth' })"
            class="p-3 bg-blue-500 text-white rounded-[10px] shadow-lg hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-300 focus:ring-offset-2"
            aria-label="Scroll to top"
            style="display: none;" {{-- Hide initially to prevent flash --}}
        >
            {{-- Simple Arrow Up SVG Icon --}}
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path  d="M5 15l7-7 7 7" />
            </svg>
        </button>
    </div>
    {{-- *** END SCROLL-TO-TOP BUTTON *** --}}

    <div class="bg-white" style=" border-bottom-left-radius: 10px; border-bottom-right-radius: 10px;">
        @if ($activeComponent === 'user-post')
            @livewire('get-user-post-form')
            @livewire('user-post')
        @elseif ($activeComponent === 'search-accounts-dashboard')
            {{-- *** PASS the targetProfileUserId property *** --}}
            @livewire('search-accounts-dashboard', ['targetUserId' => $targetProfileUserId], key('search-dash-' . $targetProfileUserId ?? 'default'))
            {{-- Added key to help Livewire differentiate instances if ID changes --}}
        @elseif ($activeComponent === 'events-livewire-component')
            @livewire('events-livewire-component')
        @endif
    </div>

</div>
