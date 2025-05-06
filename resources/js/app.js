import './bootstrap';
import 'laravel-echo';

// In resources/js/app.js (make sure bootstrap.js is imported before this if Echo is setup there)

document.addEventListener('DOMContentLoaded', () => {
    const currentUserId = document.querySelector("meta[name='user-id']")?.content;

    const filledHeartSVG = `
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-red-500" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd" />
        </svg>`;

    const outlinedHeartSVG = `
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
        </svg>`;

    if (window.Echo) {
        window.Echo.channel('likes') // Or your chosen channel name
            .listen('PostLikeStatusChanged', (event) => {

                const postId = event.postId;
                const newLikeCount = event.newLikeCount;
                const userIdWhoActed = event.userIdWhoActed;
                const wasLiked = event.wasLiked; // True if like, false if unlike

                // Find all like count elements for this post (could be in multiple components)
                const likeCountElements = document.querySelectorAll(
                    `button#like-count-button-${postId}, span#like-count-span-${postId}` // Adjust if your span ID is different
                );
                
                likeCountElements.forEach(el => {
                    el.textContent = `${newLikeCount} ${newLikeCount === 1 ? 'like' : 'likes'}`;
                });

                // If the user who acted is the current viewing user, update their heart icon
                if (currentUserId && parseInt(currentUserId) === userIdWhoActed) {
                    console.log("1");
                    const likeButton = document.getElementById(`like-button-${postId}`);
                    if (likeButton) {
                        console.log("2");
                        // Remove existing SVG and loading spinner before adding new one
                        const existingSpinner = likeButton.querySelector('div[wire\\:loading]');
                        let spinnerHtml = '';
                        if (existingSpinner) {
                            spinnerHtml = existingSpinner.outerHTML; // Preserve spinner if it exists
                            existingSpinner.remove();
                        }
                        
                        // Clear existing heart SVGs
                        const existingSvgs = likeButton.querySelectorAll('svg');
                        existingSvgs.forEach(svg => svg.remove());

                        // Add new heart SVG
                        const heartContainer = document.createElement('span'); // Create a temporary container
                        heartContainer.innerHTML = wasLiked ? filledHeartSVG : outlinedHeartSVG;
                        likeButton.insertBefore(heartContainer.firstChild, likeButton.firstChild); // Insert new SVG
                        console.log("3");

                        // Re-add spinner if it was there
                        if (spinnerHtml) {
                            likeButton.insertAdjacentHTML('beforeend', spinnerHtml);
                        }
                    }
                }
            });
    } else {
        console.log("4");
        console.warn('Laravel Echo not initialized.');
    }
});




