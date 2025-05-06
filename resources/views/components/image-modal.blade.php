{{-- resources/views/components/image-modal.blade.php --}}

{{--
    AlpineJS component to display a larger version of an image in a modal.
    Listens for a window event 'open-image-modal' with the image URL in the detail.
    e.g., $dispatch('open-image-modal', { imageUrl: '...' })
--}}
<div
    x-data="{ show: false, imageUrl: '' }"
    @open-image-modal.window="show = true; imageUrl = $event.detail.imageUrl; $nextTick(() => $refs.modalContent.focus());" {{-- Listen for event, set data, focus modal --}}
    @keydown.escape.window="show = false" {{-- Close on escape key --}}
    x-show="show"
    x-cloak {{-- Hide until Alpine is initialized --}}
    x-transition:enter="ease-out duration-300"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="ease-in duration-200"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    class="fixed inset-0 z-[999] flex items-center justify-center bg-black bg-opacity-75 p-4"
    style="display: none;" {{-- Hide initially --}}
    aria-labelledby="image-modal-title"
    role="dialog"
    aria-modal="true"
>
    {{-- Modal Content --}}
    <div
        @click.away="show = false" {{-- Close on overlay click --}}
        x-show="show"
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="relative bg-white rounded-lg shadow-xl overflow-hidden max-w-3xl w-full max-h-[90vh]"
        tabindex="-1" {{-- Make it focusable --}}
        x-ref="modalContent" {{-- Reference for focusing --}}
    >
        {{-- Image Display --}}
        <img :src="imageUrl" alt="Enlarged view" class="w-full h-auto object-contain max-h-[85vh]"> {{-- Constrain height --}}

        {{-- Close Button (Optional but recommended for accessibility) --}}
        <button @click="show = false" class="absolute top-2 right-2 text-white bg-black bg-opacity-50 rounded-full p-1 hover:bg-opacity-75 focus:outline-none focus:ring-2 focus:ring-white">
            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>
    </div>
</div>
