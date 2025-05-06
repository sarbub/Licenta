<div>
@if (session()->has('post_created'))
    <div
    wire:ignore
        x-data="{ show: true }" {{-- Alpine controls visibility and timeout --}}
        x-show="show"
        x-init="setTimeout(() => show = false, 2000)"
        wire:key="flash-message-{{ now()->timestamp . rand() }}"
        class="fixed bottom-5 right-5 bg-green-500 text-white p-4 rounded-md shadow-lg transition duration-500"
    >
        {{ session('post_created') }}
    </div>
@endif

@csrf
    <form wire:submit.prevent="submit" class="border-gray-700 bg-white rounded-lg w-full pb-2" enctype="multipart/form-data">

        <!-- *** MOVED Image Preview HERE *** -->
          <!-- Text input -->
        <div>
            <textarea
                wire:ignore.self
                x-data
                x-init="$el.style.height = $el.scrollHeight + 'px'"
                x-on:input="$el.style.height = 'auto'; $el.style.height = $el.scrollHeight + 'px'"
                wire:model="content"
                class="w-full bg-transparent border-none rounded-md focus:outline-none focus:ring-0 resize-none overflow-hidden p-4"
                rows="1"
                placeholder="What's on your mind?"
                required
                minlength="5"
                ></textarea>

            @error('content') <span class="text-red-500 text-sm p-4">{{ $message }}</span> @enderror
        </div>
        @if ($image)
        {{-- Container for the preview, added padding and bottom margin/padding --}}
        <div class="px-4 pb-4 relative w-full"> {{-- Added px-4, pb-4, removed max-w-xs --}}
            <!-- X Button -->
            <button
                type="button"
                wire:click="removeImage"
                {{-- Adjusted position slightly due to container padding --}}
                class="absolute top-1 right-5 bg-gray-800 bg-opacity-50 text-white rounded-full p-1 shadow hover:bg-red-500 transition duration-200 z-10"
                title="Remove image">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 8.586l-4.95-4.95a1 1 0 00-1.414 1.414L8.586 10l-4.95 4.95a1 1 0 001.414 1.414L10 11.414l4.95 4.95a1 1 0 001.414-1.414L11.414 10l4.95-4.95a1 1 0 00-1.414-1.414L10 8.586z" clip-rule="evenodd" />
                </svg>
            </button>

            <!-- Image -->
            <img
                src="{{ $image->temporaryUrl() }}"
                alt="Image Preview"
                {{-- Make image full width within its container --}}
                class="w-full rounded-lg shadow-md" />
        </div>
        @endif
        {{-- *** END MOVED Image Preview *** --}}

        <!-- Container for Controls -->
        {{-- Removed mb-4 as spacing is handled by preview's pb-4 --}}
        <div class="px-4">
            {{-- hide the native input --}}
            <input
                type="file"
                wire:model="image"
                accept="image/*"
                id="imageInput"
                class="hidden" />

            {{-- Row for Controls (Label + Button) --}}
            <div class="flex gap-2 w-full justify-between items-center">
                <label
                    for="imageInput"
                    class="inline-block px-4 py-2 text-gray-500 rounded cursor-pointer flex items-center
                           focus:outline-none focus:ring-0
                           hover:text-gray-500
                           ">
                    <ion-icon wire:ignore
                        name="image-outline"
                        style="font-size:30px; color: #6B7280;" {{-- Hex for gray-500 --}}
                        >
                    </ion-icon>
                </label>

                <!-- Submit button -->
                <button
                    type="submit"
                    class="px-6 py-2 bg-blue-500 text-white rounded-full outline-none hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-300 disabled:opacity-50"
                    wire:loading.attr="disabled"
                    wire:target="image, submit">
                    <span wire:loading.remove wire:target="submit">Post</span>
                    <span wire:loading wire:target="submit">Posting...</span>
                </button>
            </div>

            {{-- Image Error Message --}}
            @error('image') <div class="text-red-500 text-sm mt-2">{{ $message }}</div> @enderror

            {{-- Loading Indicator for file upload --}}
            <div wire:loading wire:target="image" class="text-sm text-gray-500 mt-1">Uploading image...</div>

        </div>
    </form>
</div>
