<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Requests') }}
        </h2>
    </x-slot>
    <div>
        
        @livewire('request-table')
    </div>
</x-app-layout>