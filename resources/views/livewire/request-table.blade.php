
<div class="">

    {{-- Flash Messages --}}
    {{-- Use consistent keys like 'success' and 'error' --}}
    @if (session()->has('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)"
             class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('success') }}</span>
        </div>
    @endif
    @if (session()->has('error'))
        <div x-data="{ show: true }" x-show="show"
             class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline">{{ session('error') }}</span>
             <button @click="show = false" class="absolute top-0 bottom-0 right-0 px-4 py-3 text-red-500 hover:text-red-700">&times;</button>
        </div>
    @endif
    {{-- Old listener - remove if not needed elsewhere or adapt --}}
    {{-- <div
        x-data="{ show: false, message: '' }" x-show="show" x-transition
        x-init="window.addEventListener('request-accepted', event => { message = event.detail.message; show = true; setTimeout(() => show = false, 3000); });"
        class="bg-green-100 text-green-800 p-4 rounded-md mb-4">
        <p x-text="message"></p>
    </div> --}}

    <table class=" w-[90%] table-auto text-center p-[10px] mx-auto bg-white mt-5 rounded-md shadow"> {{-- Added shadow --}}
        <thead>
            <tr class="bg-gray-100"> {{-- Added header background --}}
                <th class="border-b border-gray-300 px-4 py-2 text-left">First name</th>
                <th class="border-b border-gray-300 px-4 py-2 text-left">Last name</th>
                <th class="border-b border-gray-300 px-4 py-2 hidden md:table-cell text-left">Email</th>
                <th class="border-b border-gray-300 px-4 py-2 hidden md:table-cell">Age</th>
                <th class="border-b border-gray-300 px-4 py-2 hidden md:table-cell text-left">College</th> {{-- Changed alignment --}}
                <th class="border-b border-gray-300 px-4 py-2 hidden md:table-cell text-left">Address</th> {{-- Changed alignment --}}
                <th class="border-b border-gray-300 px-4 py-2 hidden md:table-cell">Income</th>
                <th class="border-b border-gray-300 px-4 py-2">Status / Action</th> {{-- Combined header --}}
            </tr>
        </thead>
        <tbody>
            @forelse ($requests as $request)
            <tr wire:key="request-row-{{ $request->id }}" class="hover:bg-gray-50"> {{-- Added hover effect --}}
                <td class="border-b border-gray-200 px-4 py-2 text-left">{{ $request->first_name }}</td>
                <td class="border-b border-gray-200 px-4 py-2 text-left">{{ $request->last_name }}</td>
                <td class="border-b border-gray-200 px-4 py-2 hidden md:table-cell text-left">{{ $request->email }}</td>
                <td class="border-b border-gray-200 px-4 py-2 hidden md:table-cell">{{ $request->age }}</td>
                <td class="border-b border-gray-200 px-4 py-2 hidden md:table-cell text-left">{{ $request->collage }}</td>
                <td class="border-b border-gray-200 px-4 py-2 hidden md:table-cell text-left">{{ $request->address }}</td>
                <td class="border-b border-gray-200 px-4 py-2 hidden md:table-cell text-center"> {{-- Align right for currency --}}
                {{-- Show income only if authorized (e.g., admin) --}}
                @if(Auth::user()->account_type === 'admin')
                    @php
                        $displayIncome = 'N/A'; // Default value
                        try {
                            // Access the attribute directly - Laravel decrypts it automatically
                            // because of the 'encrypted' cast in the PendingRequest model.
                            $incomeValue = Crypt::decryptString($request->income);

                            // Check if the decrypted value is numeric and format it
                            if (is_numeric($incomeValue)) {
                                // Format as currency (e.g., €1.234)
                                // Adjust decimals (0), decimal separator (','), thousands separator ('.') as needed
                                $displayIncome = number_format($incomeValue, 0, ',', '.');
                            } else {
                                // If it's not numeric after decryption (unlikely but possible),
                                // show the raw decrypted value or handle it differently.
                                // Showing 'N/A' if it's empty/null after decryption.
                                $displayIncome = $incomeValue ?: 'N/A';
                            }

                        } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
                            // This catch block handles errors specifically related to decryption.
                            // Common causes: APP_KEY changed after data encryption, corrupted data.
                            \Log::error("Decryption failed for income on request ID {$request->id}: " . $e->getMessage());
                            // Display an error message in the table for this specific row.
                            $displayIncome = '<span class="text-red-500" title="Decryption Error - Check Logs">Error</span>';
                        } catch (\Exception $e) {
                            // Catch any other unexpected errors during access or formatting.
                            \Log::error("Error displaying income for request ID {$request->id}: " . $e->getMessage());
                            $displayIncome = '<span class="text-red-500" title="Display Error - Check Logs">Error</span>';
                        }
                    @endphp
                    {{-- Use {!! !!} because $displayIncome might contain HTML (&euro; or error spans) --}}
                    {!! $displayIncome !!}
                @else
                    {{-- Show N/A for users who are not admins --}}
                    N/A
                @endif
            </td>
                {{-- Status Dropdown Cell --}}
                <td class="relative border-b border-gray-200 px-4 py-2"
                    x-data="{ open: false }" {{-- Simplified Alpine state for dropdown only --}}
                    @click.away="open = false">
                    <div class="relative inline-block text-left">
                        {{-- Button shows current status and toggles dropdown --}}
                        <button type="button" @click="open = !open"
                                @class([
                                    'inline-flex justify-center w-full rounded-md px-3 py-1 text-xs font-medium shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-indigo-500',
                                    'bg-yellow-100 text-yellow-800 hover:bg-yellow-200' => $request->status === 'pending',
                                    'bg-green-100 text-green-800 hover:bg-green-200' => $request->status === 'accepted',
                                    'bg-red-100 text-red-800 hover:bg-red-200' => $request->status === 'declined',
                                    'bg-gray-100 text-gray-800 hover:bg-gray-200' => $request->status === 'deleted', // Style for 'deleted' status
                                ])>
                            <span>{{ Str::ucfirst($request->status) }}</span>
                            <svg class="-mr-1 ml-1 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path> {{-- Chevron down --}}
                            </svg>
                        </button>

                        {{-- Dropdown menu --}}
                        <div x-show="open" x-transition
                             class="absolute right-0 z-10 mt-2 w-40 origin-top-right rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none"
                             style="display: none;">
                            <div class="py-1" role="menu" aria-orientation="vertical" aria-labelledby="options-menu">
                                {{-- Accept Action --}}
                                @if($request->status !== 'accepted')
                                <button type="button"
                                        wire:click.prevent="confirmAction({{ $request->id }}, 'accepted')"
                                        @click="open = false"
                                        class="w-full text-left text-green-700 block px-4 py-2 text-sm hover:bg-gray-100" role="menuitem">
                                    Accept
                                </button>
                                @endif
                                {{-- Decline Action --}}
                                @if($request->status !== 'declined')
                                <button type="button"
                                        wire:click.prevent="confirmAction({{ $request->id }}, 'declined')"
                                        @click="open = false"
                                        class="w-full text-left text-orange-700 block px-4 py-2 text-sm hover:bg-gray-100" role="menuitem">
                                    Decline
                                </button>
                                @endif
                                {{-- Delete Action --}}
                                @if($request->status !== 'deleted') {{-- Don't show delete if already marked --}}
                                <button type="button"
                                        wire:click.prevent="confirmAction({{ $request->id }}, 'deleted')"
                                        @click="open = false"
                                        class="w-full text-left text-red-700 block px-4 py-2 text-sm hover:bg-gray-100" role="menuitem">
                                    Delete
                                </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </td>
            </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center text-gray-500 py-4">No pending requests found.</td> {{-- Updated colspan --}}
                </tr>
            @endforelse
        </tbody>
    </table>

    {{-- *** ADDED: Confirmation Modal *** --}}
    <div
        x-data="{ show: @entangle('showConfirmModal') }"
        x-show="show"
        x-on:keydown.escape.window="$wire.cancelConfirmation()"
        x-transition:enter="ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900 bg-opacity-75 p-4"
        style="display: none;"
    >
        {{-- Modal Content --}}
        <div
            @click.away="$wire.cancelConfirmation()"
            x-show="show"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            class="bg-white rounded-lg shadow-xl overflow-hidden max-w-md w-full mx-auto"
        >
            {{-- Modal Header --}}
            <div class="flex justify-between items-center p-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800">
                    Confirm Action: {{ Str::ucfirst($confirmingAction ?? '') }}
                </h3>
                <button type="button" wire:click.prevent="cancelConfirmation" class="text-gray-400 hover:text-gray-600 focus:outline-none">
                    <svg class="size-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            {{-- Modal Body --}}
            <div class="p-6">
                <p class="text-sm text-gray-700">
                    Are you sure you want to
                    <strong class="font-medium">{{ $confirmingAction ?? 'perform this action' }}</strong>
                    for the request from
                    <strong class="font-medium">{{ $confirmingRequestName ?? 'this user' }}</strong>?
                    @if($confirmingAction === 'deleted')
                        <br>This will mark the request for deletion according to policy.
                    @endif
                </p>
            </div>

            {{-- Modal Footer --}}
            <div class="bg-gray-50 px-4 py-3 sm:px-6 flex justify-end gap-3">
                <button type="button" wire:click.prevent="cancelConfirmation"
                        class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-400 text-sm">
                    Cancel
                </button>
                <button type="button" wire:click.prevent="performConfirmedAction"
                        @class([
                            'px-4 py-2 text-white rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 text-sm disabled:opacity-50',
                            'bg-green-600 hover:bg-green-700 focus:ring-green-500' => $confirmingAction === 'accepted',
                            'bg-orange-600 hover:bg-orange-700 focus:ring-orange-500' => $confirmingAction === 'declined',
                            'bg-red-600 hover:bg-red-700 focus:ring-red-500' => $confirmingAction === 'deleted',
                            'bg-blue-600 hover:bg-blue-700 focus:ring-blue-500' => !$confirmingAction, // Default/fallback
                        ])
                        wire:loading.attr="disabled"
                        wire:target="performConfirmedAction">
                    <span wire:loading.remove wire:target="performConfirmedAction">Confirm {{ Str::ucfirst($confirmingAction ?? '') }}</span>
                    <span wire:loading wire:target="performConfirmedAction">Processing...</span>
                </button>
            </div>
        </div>
    </div>
    {{-- *** End Confirmation Modal *** --}}

</div>
