<div id="newsletter-modal" class="fixed inset-0 z-50 bg-black/50 hidden items-center justify-center">
    <div class="bg-white rounded-lg p-8 max-w-md w-full mx-4">
        <div class="flex justify-between items-center mb-4">
            <h3 class="login-title">Subscribe for Updates</h3>
            <button id="close-newsletter-modal" class="text-gray-500 hover:text-gray-700 cursor-pointer">
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <p class="login-subtitle mb-4">Get notified about new artworks, featured artists, and exclusive collections.</p>
        <form id="F_newsletter-form" data-form-bottom-subscribe class="space-y-4" method="POST" action="{{ route('newsletter.subscription') }}">
            @csrf
            <div class="ermsg">
                <input type="hidden" name="ga4-subscription-type" value="data-form-bottom-subscribe" id="ga4-subscription-type">
                <input type="email" name="email" id="email" placeholder="Enter your email address" 
                required 
                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary" 
                title="Please enter email address">
            </div>
            <button type="submit" id="newsletter-form" data-cta="Footer Newsletter Subscribe" class="directSubmit w-full bg-foreground text-background py-2 px-4 rounded-md hover:bg-primary-hover transition-colors cursor-pointer">Subscribe</button>
        </form>
    </div>
</div>