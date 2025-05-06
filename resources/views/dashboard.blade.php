<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Home') }}
        </h2>
    </x-slot>
    {{-- Real-time Notification Area --}}
    {{-- Add listener for scroll-to-top event --}}
    <div class="flex flex-wrap w-full">
    <div
        x-data="{}"
        @scrollToTop.window="() => {
            console.log('scrollToTop event received. Attempting scroll on MAIN element.');
            const mainElement = document.querySelector('main'); // Target the <main> tag
            if (mainElement) {
                mainElement.scrollTo({ top: 0, behavior: 'smooth' });
                console.log('mainElement.scrollTo executed.');
            } else {
                console.error('Scrolling container (main) not found! Trying window scroll as fallback.');
                window.scrollTo({ top: 0, behavior: 'smooth' }); // Fallback just in case
            }
        }"
    ></div>
        <div class="flex-[100] sm:flex-[3] top-0 p-2 sm:sticky sm:self-start z-30">
            @livewire('user-events-component-display')
        </div>
        {{-- Column 2: Main Content (Posts/Search/Events Management) --}}
        {{-- Changed p-4 on the *inner* div to p-2 as well for consistency, or keep p-4 if you want more internal padding here --}}
        <div class="flex-[100%] sm:flex-[5] p-2 relative"> {{-- Changed outer p-4 to p-2 --}}
            <div class="w-full" style="border-radius:10px;"> {{-- Removed p-4 from here, handled by parent now --}}
                {{-- Start Notification Area --}}
                <div
                    x-data="{
                        show: false,
                        userImageUrl: null, // Start as null
                        userName: '', // Keep user name for alt text
                        timeout: null
                    }"
                    x-init="
                        console.log('Alpine notification component initialized.');
                        window.Echo.channel('posts')
                            .listen('NewPostCreated', (e) => {
                                if (e.userImageUrl) {
                                    userName = e.userName;
                                    userImageUrl = e.userImageUrl;
                                    show = true;
                                    clearTimeout(timeout);
                                    timeout = setTimeout(() => show = false, 8000);
                                } else {
                                    console.warn('NewPostCreated event received, but userImageUrl is missing or null:', e);
                                }
                            });
                    "
                    x-show="show"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 transform scale-90"
                    x-transition:enter-end="opacity-100 transform scale-100"
                    x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100 transform scale-100"
                    x-transition:leave-end="opacity-0 transform scale-90"
                    style = " z-index: 1200;"
                    class="fixed bottom-5 rounded-full left-1/2 -translate-x-1/2 shadow-lg cursor-pointer overflow-hidden border-2 bg-white"
                    @click="
                        show = false;
                        $dispatch('refreshUserPosts');
                        $nextTick(() => {
                             window.scrollTo({ top: 0, behavior: 'smooth' });
                        });" >
                    {{-- Display the user's profile image, bound to userImageUrl --}}
                    {{-- Conditionally render image only if URL is set --}}
                    <template x-if="userImageUrl">
                        <img :src="userImageUrl"
                            :alt="userName + ' posted'"
                            class="w-12 h-12 object-cover" {{-- Increased size slightly for testing --}}
                            onerror="console.error('Image failed to load:', this.src); this.style.display='none';" {{-- Add error handler --}}>
                    </template>
                    {{-- Optional: Add a placeholder if image fails or URL is null --}}
                    <template x-if="!userImageUrl && show">
                        <div class="w-14 h-14 flex items-center justify-center text-gray-400">Image error</div>
                    </template>
                </div>
                
                {{-- End Notification Area --}}
                @livewire('dashboard-main-component')
            </div>
        </div>


        {{-- Column 3: Followers/Following --}}
        {{-- Changed p-4 to p-2. Removed gap-3 as space-y handles vertical spacing --}}
        <div class="flex-[100%] sm:flex-[2] p-2 space-y-4"> {{-- Adjusted space-y if needed --}}
            @livewire('my-followers')
            @livewire('who-do-i-follow') {{-- Corrected component name based on previous steps --}}
        </div>
    </div>
</x-app-layout>