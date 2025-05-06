<div class="w-full p-4">
    {{-- Search Input --}}
    <input type="text" wire:model.live.debounce.300ms="query" placeholder="search users....." class="border-gray-400 rounded-full w-full">

    {{-- Loading Indicator --}}
    <div wire:loading wire:target="query" class="text-gray-500 text-sm mt-2">Searching...</div>

    {{-- Search Results --}}
    {{-- ... (search results loop remains the same) ... --}}
    <div class="history w-full mt-4">
        <div class="space-y-2">
            <div wire:loading.remove wire:target="query">
                @forelse($users as $user)
                {{-- Pass wire:key for Livewire loop tracking --}}
                @livewire('user-searched-card', ['user' => $user], key('search-'.$user->id))
                @empty
                @if(strlen($query) >= 2)
                <div class="text-gray-500 p-4">No users found matching "{{ $query }}".</div>
                @elseif(strlen($query) > 0)
                <div class="text-gray-500 p-4">Please enter at least 2 characters.</div>
                @endif
                @endforelse
            </div>
        </div>
    </div>

    {{-- Flash Messages (Good to have here too) --}}
    @if (session()->has('success'))
    <div wire:key="flash-success-{{ now()->timestamp . rand() }}" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)"
        class="bg-green-100 text-green-800 p-4 rounded-md my-4">
        {{ session('success') }}
    </div>
    @endif
    @if (session()->has('error'))
    <div x-data="{ show: true }" x-show="show"
        class="bg-red-100 text-red-800 p-4 rounded-md my-4">
        {{ session('error') }}
    </div>
    @endif

    {{-- Profile Display Section (Current User OR Selected User) --}}
    @auth

    {{-- Loading Profile Indicator --}}
    <div wire:loading wire:target="showProfile" class="mt-6 pt-4 border-t border-gray-200 text-center p-6">
        {{-- ... (spinner) ... --}}
        <div class="inline-flex items-center gap-2 text-gray-500">
            <svg class="animate-spin h-5 w-5 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <span>Loading Profile...</span>
        </div>
    </div>

    @php
    // Use the $profileUser property if it's set (a profile was clicked),
    // otherwise default to the currently authenticated user.
    $displayUser = $profileUser ?? auth()->user();
    // Choose the correct post collection based on which user is being displayed
    $displayPosts = $profileUser ? $this->profileUserPosts : $this->currentUserPosts;
    // Determine if the profile being shown belongs to the logged-in user
    $isOwnProfile = !$profileUser || $profileUser->id === auth()->id();
    @endphp

    {{-- Display User Info --}}
    <div class="mt-6 pt-4 border-t border-gray-200">
        <div class="flex items-center justify-between gap-3 p-2 relative">
            {{-- Info Part (Left Side) --}}
            <div class="flex w-full  flex-col justify-center text-center items-center gap-3">
                <img src="{{ $displayUser->profile_photo_url }}" alt="{{ $displayUser->first_name }}" @click="$dispatch('open-image-modal', { imageUrl: '{{$displayUser->profile_photo_url }}' })" class="rounded-full size-20 object-cover cursor-pointer">
                <div>
                    <p class="font-semibold">{{ $displayUser->first_name }} {{ $displayUser->last_name }}</p>
                    @if($isOwnProfile)
                        <p class="text-sm text-gray-500">Your Account</p>
                    @endif
                </div>
                <div class="flex items-center gap-2">
                    <ul class="flex gap-2">
                        <li>
                            <p>Followers</p>
                            <p class="font-semibold">{{$displayUser->followers_count}}</p>
                        </li>
                        <li>
                            <p>Following</p>
                            <p class="font-semibold">{{ $displayUser->following_count }}</p>
                        </li>
                        <li>
                            <p>Posts</p>
                            <p class="font-semibold">{{ $displayPosts->count() }}</p>
                        </li>
                    </ul>
                </div>
            </div>
            {{-- Actions Part (Right Side) --}}
            <div class="flex items-center gap-2">
            @if(!$isOwnProfile)
                    <button
                        {{-- Call the toggleFollow method with the ID of the user being displayed --}}
                        wire:click.prevent="toggleFollow({{ $displayUser->id }})"
                        {{-- Disable button while the action is processing --}}
                        wire:loading.attr="disabled"
                        wire:target="toggleFollow({{ $displayUser->id }})"
                        {{-- Apply classes conditionally based on follow status --}}
                        @class([
                            'px-4 py-1 rounded-full text-sm font-medium transition-colors duration-150 ease-in-out focus:outline-none focus:ring-2 focus:ring-offset-1 disabled:opacity-70',
                            'bg-blue-500 text-white hover:bg-blue-600 focus:ring-blue-400' => !$displayUser->is_followed_by_current_user, // Style for "Follow"
                            'bg-gray-200 text-gray-700 hover:bg-gray-300 focus:ring-gray-400' => $displayUser->is_followed_by_current_user, // Style for "Unfollow"
                        ])>
                        {{-- Button Text: Show "Unfollow" or "Follow" based on the accessor --}}
                        <span wire:loading.remove wire:target="toggleFollow({{ $displayUser->id }})">
                            {{ $displayUser->is_followed_by_current_user ? 'Unfollow' : 'Follow' }}
                        </span>
                        {{-- Loading Text/Indicator --}}
                        <span wire:loading wire:target="toggleFollow({{ $displayUser->id }})">
                            ... {{-- Or use a spinner SVG --}}
                        </span>
                    </button>
                @endif
            </div>
        </div>
    </div>

    {{-- Display User's Posts --}}
    <div class="mt-6 pt-4 border-t border-gray-200">
        <h3 class="text-lg font-semibold mb-3 px-2">
            {{ $isOwnProfile ? 'Your' : $displayUser->first_name . "'s" }} Posts
        </h3>
        <div class="space-y-4">
            @forelse($displayPosts as $post)
            {{-- Post Card --}}
            <div wire:key="{{ ($isOwnProfile ? 'my' : 'profile') }}-post-{{ $post->id }}" class="relative bg-white rounded-lg shadow p-4 border border-gray-100">

                {{-- Post Header: User Info & Delete Button --}}
                <div class="flex items-center justify-between mb-3">
                    <div class="flex items-center gap-3 cursor-pointer">
                        <img src="{{ $post->user->profile_photo_url }}" @click="$dispatch('open-image-modal', { imageUrl: '{{$post->user->profile_photo_url }}' })" alt="{{ $post->user->first_name }}" class="rounded-full size-9 object-cover">
                        <div>
                            <p class="font-medium text-sm">{{ $post->user->first_name }} {{ $post->user->last_name }}</p>
                            <p class="text-xs text-gray-500">{{ $post->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                    {{-- Delete Trigger (Three Dots) - Positioned relative to the card --}}
                    <x-three-dots-component-for-posts
                        :itemId="$post->id"
                        itemType="post"
                        deleteAction="deletePostFromProfile"
                        :canShow="auth()->check() && (auth()->id() === $post->user_id || in_array(auth()->user()->account_type, ['admin', 'moderator']))"
                        positionClasses="absolute top-3 right-3" {{-- Adjusted position --}}
                    />
                </div>

                {{-- Post Content --}}
                <p class="whitespace-pre-wrap mb-3 text-gray-800">{{ $post->content }}</p>

                {{-- Post Image --}}
                @if ($post->image && Storage::disk('public')->exists($post->image))
                <div class="mb-3">
                    <img src="{{ Storage::url($post->image) }}"
                        class="rounded-lg w-full h-auto shadow cursor-pointer"
                        alt="Post image"
                        @click="$dispatch('open-image-modal', { imageUrl: '{{ Storage::url($post->image) }}' })"
                        onerror="this.style.display='none'; console.error('Error loading image: {{ $post->image }}')">
                </div>
                @endif

                {{-- *** ADDED: Like Button and Count Section *** --}}
                <div class="flex items-center justify-between pt-3 border-t border-gray-100">
                    <div class="flex items-center gap-2"> {{-- Group button and count --}}
                        @auth {{-- Only show like button if logged in --}}
                        <button
                            wire:click.prevent="toggleLike({{ $post->id }})"
                            wire:loading.attr="disabled"
                            wire:target="toggleLike({{ $post->id }})"
                            id="like-button-{{ $post->id }}" {{-- Add ID for JS targeting (heart icon) --}}
                            class="flex items-center gap-1 text-gray-500 hover:text-red-500 focus:outline-none transition-colors duration-150"
                            aria-label="Like post">

                            {{-- Heart Icon - Conditional Fill --}}
                            {{-- Use the is_liked attribute which should now work correctly --}}
                            @if($post->is_liked)
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

                        {{-- Like Count Display --}}
                        {{-- NOTE: Not making this clickable for now, just displaying the count --}}
                        <div class="flex items-center">
                                <button
                                    wire:click.prevent="showLikers({{ $post->id }})"
                                    wire:loading.attr="disabled"
                                    wire:target="showLikers({{ $post->id }})"
                                    id="like-count-button-{{ $post->id }}" {{-- Add ID for JS targeting (count > 0) --}}
                                    class="text-sm text-gray-500 hover:text-blue-600 hover:underline focus:outline-none"
                                    aria-label="View users who liked this post">
                                    {{ $post->likers_count }} {{ Str::plural('like', $post->likers_count) }}
                                </button>
                            {{-- Loading Spinner for showing likers --}}
                            <div wire:loading wire:target="showLikers({{ $post->id }})" class="ml-1">
                                 <svg class="animate-spin h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                            </div>
                        </div>

                    </div> {{-- End Like Button/Count Group --}}

                    {{-- Placeholder for other actions like comments/share if needed --}}
                    <div></div>
                </div>
                {{-- *** END ADDED: Like Button and Count Section *** --}}

            </div> {{-- End Post Card --}}
            @empty
            <p class="text-gray-500 px-2">
                {{ $isOwnProfile ? "You haven't" : $displayUser->first_name . " hasn't" }} created any posts yet.
            </p>
            @endforelse
            <div class="flex justify-center text-center text-gray-500 text-4xl">
                <p>.</p>
            </div>
        </div>
    </div>
    @endauth

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
        style="display: none;" {{-- Hide initially --}}
    >
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
            class="bg-white rounded-lg shadow-xl overflow-hidden max-w-md w-full mx-auto" {{-- Adjusted max-width --}}
        >
            {{-- Modal Header --}}
            <div class="flex justify-between items-center p-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800">
                    Liked By
                </h3>
                <button
                    wire:click.prevent="closeLikersModal"
                    class="text-gray-400 hover:text-gray-600 focus:outline-none">
                    <svg class="size-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            {{-- Modal Body --}}
            <div class="p-6 max-h-80 overflow-y-auto"> {{-- Add max height and scroll --}}
                <ul class="space-y-3">
                    {{-- Use $likersList from the component --}}
                    @forelse ($likersList as $liker)
                        <li wire:key="liker-{{ $liker->id }}" class="flex cursor-pointer items-center gap-3 p-2 rounded hover:bg-gray-50" wire:click.prevent="closeLikersModal(); $dispatch('showUserProfile', { userId: {{ $liker->id }} })">
                            <img src="{{ $liker->profile_photo_url }}" alt="{{ $liker->first_name }}" class="size-8 rounded-full object-cover">
                            <span class="text-gray-700">{{ $liker->first_name }} {{ $liker->last_name }}</span>
                            {{-- Optional: Add follow button or link to profile here --}}
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