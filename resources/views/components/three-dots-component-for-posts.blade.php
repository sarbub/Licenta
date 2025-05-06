{{-- resources/views/components/action-dropdown.blade.php --}}

{{-- Define the properties the component accepts --}}
@props([
    'itemId',                       // The ID of the item (post, user, etc.)
    'itemType' => 'item',           // Type of item ('post', 'user') for text customization
    'deleteAction',                 // The Livewire method name to call for deletion (e.g., 'deletePosts', 'deleteUser')
    'canShow' => false,             // Boolean: Should the dropdown be shown based on authorization?
    'positionClasses' => 'absolute top-2 right-2' // Default positioning
])

{{-- Only render if the 'canShow' prop is true --}}
@if ($canShow)
    <div x-data="{ open: false, showConfirmModal: false }" @keydown.escape.window="showConfirmModal = false" class="{{ $positionClasses }}">

        {{-- Three Dots Button --}}
        <button @click="open = !open" class="text-gray-500 hover:text-gray-700 p-2 rounded-full hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z" />
            </svg>
        </button>

        {{-- Dropdown Menu --}}
        <div x-show="open"
             @click.away="open = false"
             x-transition:enter="transition ease-out duration-100"
             x-transition:enter-start="transform opacity-0 scale-95"
             x-transition:enter-end="transform opacity-100 scale-100"
             x-transition:leave="transition ease-in duration-75"
             x-transition:leave-start="transform opacity-100 scale-100"
             x-transition:leave-end="transform opacity-0 scale-95"
             class="absolute right-0 z-20 mt-2 w-48 origin-top-right rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none"
             style="display: none;"> {{-- Hide initially --}}
            <div class="py-1" role="menu" aria-orientation="vertical" aria-labelledby="options-menu">
                {{-- Delete Button --}}
                <button
                    type="button"
                    @click="showConfirmModal = true; open = false" {{-- Show modal, close dropdown --}}
                    class="w-full text-left text-red-600 block px-4 py-2 text-sm hover:bg-gray-100 hover:text-red-800"
                    role="menuitem">
                    Delete {{ Str::ucfirst($itemType) }} {{-- Dynamic text --}}
                </button>
                {{-- Add other options like 'Edit' here later if needed, passing relevant props --}}
            </div>
        </div>

        {{-- Custom Confirmation Modal --}}
        <div x-show="showConfirmModal"
             x-cloak
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-30 flex items-center justify-center bg-black bg-opacity-50 p-4"
             style="display: none;">

            {{-- Modal Content --}}
            <div @click.away="showConfirmModal = false"
                 x-show="showConfirmModal"
                 x-transition:enter="ease-out duration-300"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="ease-in duration-200"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="bg-white rounded-lg shadow-xl overflow-hidden max-w-md w-full mx-auto">

                {{-- Modal Body --}}
                <div class="p-6">
                    <h3 class="text-lg font-medium leading-6 text-gray-900 mb-2">Confirm Deletion</h3>
                    <p class="text-sm text-gray-600">
                        {{-- Dynamic confirmation message --}}
                        Are you sure you want to delete this {{ $itemType }}, this is ireversibile?
                    </p>
                </div>

                {{-- Modal Footer --}}
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    {{-- Confirm Button --}}
                    <button type="button"
                            {{-- Dynamically call the Livewire action passed via prop --}}
                            wire:click.prevent="{{ $deleteAction }}({{ $itemId }})"
                            @click="showConfirmModal = false" {{-- Close modal on click --}}
                            wire:loading.attr="disabled"
                            wire:target="{{ $deleteAction }}({{ $itemId }})" {{-- Target specific action call --}}
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm disabled:opacity-50">
                        <span wire:loading.remove wire:target="{{ $deleteAction }}({{ $itemId }})">Delete</span>
                        <span wire:loading wire:target="{{ $deleteAction }}({{ $itemId }})">Deleting...</span>
                    </button>
                    {{-- Cancel Button --}}
                    <button type="button"
                            @click="showConfirmModal = false"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
        {{-- End Confirmation Modal --}}

    </div>
@endif
