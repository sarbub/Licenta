{{-- resources/views/livewire/my-followers.blade.php --}}
<div class="bg-white p-4 rounded-lg shadow border border-gray-100">
    <h3 class="text-lg font-semibold mb-4 text-gray-800 border-b pb-2">Your Followers</h3>

    @if($followers->isNotEmpty())
        {{-- Make the UL a grid container with 2 columns and add gaps --}}
        <ul class="grid grid-cols-2 gap-4 max-h-96 overflow-y-auto pr-2"> {{-- Removed space-y-3, added grid classes --}}
            @foreach ($followers as $follower)
                {{-- The LI is now a grid item, styling inside remains the same --}}
                <li wire:key="follower-{{ $follower->id }}" wire:click.prevent="$dispatch('showUserProfile', { userId: {{ $follower->id }} })" class="p-2 rounded cursor-pointer hover:bg-gray-50 transition duration-150 ease-in-out">
                    {{-- Follower Info (arranged vertically within the li) --}}
                    {{-- Added text-center for better name alignment --}}
                    <div class="flex flex-col items-center gap-2 text-center">
                        <img src="{{ $follower->profile_photo_url }}" alt="{{ $follower->first_name }}" class="size-16 rounded-[10px] object-cover">
                        <span class="text-sm font-medium text-gray-700">{{ $follower->first_name }} {{ $follower->last_name }}</span>
                    </div>

                    {{-- Optional: Add Follow Back/Unfollow Button Here --}}
                    {{-- ... --}}
                </li>
            @endforeach
        </ul>
    @else
        <p class="text-sm text-gray-500 text-center py-4">You don't have any followers yet.</p>
    @endif
</div>
