{{-- resources/views/livewire/user-searched-card.blade.php --}}
{{-- Use named argument syntax for dispatching --}}
<div wire:click="$dispatch('showProfile', {userId: {{ $user->id }} })"
     class="w-full p-4 flex gap-4 items-center justify-between border-b border-gray-200 hover:bg-gray-50 cursor-pointer transition duration-150 ease-in-out">
    {{-- User Info --}}
    <div class="flex items-center gap-4 pointer-events-none">
        <img src="{{ $user->profile_photo_url }}" alt="{{ $user->first_name }}" class="rounded-full size-10 object-cover">
        <p>{{ $user->first_name }} {{ $user->last_name }}</p>
    </div>
</div>
