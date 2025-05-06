{{-- resources/views/livewire/my-following.blade.php --}}
<div class="bg-white p-4 rounded-lg shadow border border-gray-100">
    {{-- Changed Heading --}}
    <h3 class="text-lg font-semibold mb-4 text-gray-800 border-b pb-2">Following</h3>

    {{-- Use the new property name --}}
    @if($followingList->isNotEmpty())
        {{-- Keep the grid layout --}}
        <ul class="grid grid-cols-2 gap-4 max-h-96 overflow-y-auto pr-2">
            {{-- Update loop variable and key --}}
            @foreach ($followingList as $userBeingFollowed)
                <li wire:key="following-{{ $userBeingFollowed->id }}" wire:click.prevent="$dispatch('showUserProfile', { userId: {{ $userBeingFollowed->id }} })" class="p-2 cursor-pointer rounded hover:bg-gray-50 transition duration-150 ease-in-out">
                    <div class="flex flex-col items-center gap-2 text-center">
                        {{-- Use the loop variable --}}
                        <img src="{{ $userBeingFollowed->profile_photo_url }}" alt="{{ $userBeingFollowed->first_name }}" class="size-16 rounded-[10px] object-cover">
                        <span class="text-sm font-medium text-gray-700">{{ $userBeingFollowed->first_name }} {{ $userBeingFollowed->last_name }}</span>
                    </div>

                    {{-- Optional: Add Unfollow Button Here --}}
                    {{-- Example (would require adding toggleFollow logic to this component):
                    <button wire:click="toggleFollow({{ $userBeingFollowed->id }})" class="mt-2 text-xs bg-gray-200 text-gray-700 px-2 py-1 rounded hover:bg-gray-300">
                        Unfollow
                    </button>
                    --}}
                </li>
            @endforeach
        </ul>
    @else
        {{-- Update empty message --}}
        <p class="text-sm text-gray-500 text-center py-4">You are not following anyone yet.</p>
    @endif
</div>
