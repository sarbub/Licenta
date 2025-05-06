<div class="w-full p-4">

    {{-- Flash message display area --}}
    {{-- ... existing flash messages ... --}}
    @if (session()->has('success'))
    <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)"
        class="bg-green-100 text-green-800 p-4 rounded-md mb-4">
        {{ session('success') }}
    </div>
    @endif
    @if (session()->has('error'))
    <div x-data="{ show: true }" x-show="show"
        class="bg-red-100 text-red-800 p-4 rounded-md mb-4">
        {{ session('error') }}
    </div>
    @endif
    @foreach ($user_data as $post )
    <div
        wire:key="post-{{ $post->id }}"
        data-post-id="{{ $post->id }}" {{-- Add data attribute for JS --}}
        class="post-container relative flex flex-col w-full overflow-hidden mt-2 mb-2 pb-3 bg-white rounded-lg shadow" style="border-top:1px solid #eee"
    >

        {{-- User Info --}}
        <div class="flex w-full items-center gap-4 p-4">
            @if($post->user)
            <img src="{{ $post->user->profile_photo_url }}" alt="{{ $post->user->first_name }}" @click="$dispatch('open-image-modal', { imageUrl: '{{$post->user->profile_photo_url }}' })" class="rounded-full size-10 object-cover cursor-pointer">
            <div class="gap-0 flex cursor-pointer flex-col items-start leading-none" wire:click.prevent="$dispatch('showUserProfile', { userId: {{ $post->user->id }} })">
                <p>{{ $post->user->first_name }} {{ $post->user->last_name }}</p>
                <p class="text-xs text-gray-500">{{ $post->created_at->diffForHumans()}}</p>
            </div>
            @else
            <div class="rounded-full size-10 bg-gray-300"></div>
            <p>Unknown User</p>
            @endif
        </div>

        {{-- Post Content --}}
        <p class="p-4 whitespace-pre-wrap">{{ $post->content }}</p>

        {{-- Post Image --}}
        @if ($post->image && Storage::disk('public')->exists($post->image))
        <div class="px-4"> {{-- Use px-4 for consistency --}}
            <img
                src="{{ Storage::url($post->image) }}"
                {{-- Add click handler to dispatch event --}}
                @click="$dispatch('open-image-modal', { imageUrl: '{{ Storage::url($post->image) }}' })"
                class="rounded-lg w-full h-auto shadow cursor-pointer hover:opacity-90 transition-opacity" {{-- Add cursor, hover effect --}}
                alt="Post image" style="border-radius:10px;">
        </div>
        @endif

        {{-- Like Button and Count --}}
        <div class="flex items-center justify-between px-4 pt-3 mt-2 border-t border-gray-100">
            <div class="flex items-center gap-2"> {{-- Group button and count --}}
                @auth {{-- Only show like button if logged in --}}
                <button
                    wire:click.prevent="toggleLike({{ $post->id }})"
                    wire:loading.attr="disabled"
                    wire:target="toggleLike({{ $post->id }})"
                    id="like-button-{{ $post->id }}" {{-- Add ID for JS targeting --}}
                    class="flex items-center gap-1 text-gray-500 hover:text-red-500 focus:outline-none transition-colors duration-150"
                    aria-label="Like post">

                    {{-- Heart Icon - Conditional Fill --}}
                    @if($post->is_liked) {{-- Check the computed attribute --}}
                    {{-- Filled Heart --}}
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-500" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd" />
                    </svg>
                    @else
                    {{-- Outlined Heart --}}
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                    </svg>
                    @endif

                    {{-- Loading Spinner for Like Toggle --}}
                    <div wire:loading wire:target="toggleLike({{ $post->id }})" class="ml-1">
                        <svg class="animate-spin h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </div>
                </button>
                @else
                {{-- Display heart icon only for non-logged-in users --}}
                <div class="flex items-center gap-1 text-gray-500">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                    </svg>
                </div>
                @endauth

                {{-- *** MODIFIED LIKE COUNT - Make it clickable *** --}}
                <div class="flex items-center">
                    <button
                        wire:click.prevent="showLikes({{ $post->id }})"
                        wire:loading.attr="disabled"
                        wire:target="showLikes({{ $post->id }})" {{-- Corrected wire:target --}}
                        id="like-count-display-{{ $post->id }}" {{-- Consistent ID for JS targeting --}}
                        class="text-sm text-gray-500 hover:text-blue-600 hover:underline focus:outline-none"
                        aria-label="View users who liked this post">
                        {{ $post->likers_count }} {{ Str::plural('like', $post->likers_count) }}
                    </button>
                    {{-- Loading Spinner for showing likers --}}
                    <div wire:loading wire:target="showLikers({{ $post->id }})" class="ml-1">
                        <svg class="animate-spin h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </div>
                </div>
                {{-- *** END MODIFIED LIKE COUNT *** --}}

            </div> {{-- End Like Button/Count Group --}}

            {{-- Placeholder for other actions like comments/share if needed --}}
            <div></div>
        </div>
        {{-- End Like Button and Count Section --}}


        {{-- Delete Trigger (Three Dots) --}}
        <x-three-dots-component-for-posts
            :itemId="$post->id"
            itemType="post"
            deleteAction="deletePosts"
            :canShow="auth()->check() && (auth()->id() === $post->user_id || in_array(auth()->user()->account_type, ['admin', 'moderator']))"
            positionClasses="absolute top-4 right-4" {{-- Adjusted position --}} />

    </div>
    @endforeach
    <div class="flex justify-center text-center text-gray-500 text-4xl">
        <p>.</p>
    </div>

    {{-- *** ADD LIKERS MODAL *** --}}
    <div
        x-data="{ show: @entangle('showLikersModal') }"
        x-show="show"
        x-on:keydown.escape.window="$wire.closeLikersModal()"
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900 bg-opacity-75 p-4"
        style="display: none;" {{-- Hide initially --}}>
        {{-- Modal Content --}}
        <div
            @click.away="$wire.closeLikersModal()" {{-- Close on overlay click --}}
            x-show="show"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            class="bg-white rounded-lg shadow-xl overflow-hidden max-w-md w-full mx-auto" {{-- Adjusted max-width --}}>
            {{-- Modal Header --}}
            <div class="flex justify-between items-center p-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800">
                    Liked By
                </h3>
                <button
                    wire:click.prevent="closeLikersModal"
                    class="text-gray-400 hover:text-gray-600 focus:outline-none">
                    <svg class="size-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            {{-- Modal Body --}}
            {{-- Modal Body --}}
<div class="p-6 max-h-80 overflow-y-auto"> {{-- Add max height and scroll --}}
    <ul class="space-y-3">
        @forelse ($likersList as $liker)
            <li
                wire:key="liker-{{ $liker->id }}"
                wire:click.prevent="closeLikersModal(); $dispatch('showUserProfile', { userId: {{ $liker->id }} })"
                class="flex items-center gap-3 p-2 rounded hover:bg-gray-100 cursor-pointer transition duration-150 ease-in-out">
                <img src="{{ $liker->profile_photo_url }}" alt="{{ $liker->first_name }}" class="size-8 rounded-full object-cover pointer-events-none">
                <span class="text-gray-700 pointer-events-none">{{ $liker->first_name }} {{ $liker->last_name }}</span>
            </li>
        @empty
            <li class="text-gray-500 text-center py-4">No likes yet.</li>
        @endforelse
    </ul>
</div>


            {{-- Modal Footer --}}
            <div class="bg-gray-50 px-4 py-3 sm:px-6 flex justify-end">
                <button
                    type="button"
                    wire:click.prevent="closeLikersModal"
                    class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-400 text-sm">
                    Close
                </button>
            </div>
        </div>
    </div>
    {{-- *** END LIKERS MODAL *** --}}

    

</div>