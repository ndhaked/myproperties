<x-dashboard-layout>
    @section('title', "Help & Support | " . config('app.name'))
    <!-- Main Content -->
    <main class="main-content help-support">
        <header class="content-header">
            <div style="display: flex; align-items: center; gap: 16px;">
                <button class="mobile-menu-toggle" id="mobileMenuToggle">
                    <div class="hamburger">
                        <span></span>
                        <span></span>
                        <span></span>
                    </div>
                </button>
                <h1 class="page-title">Help & Support</h1>
            </div>
        </header>

        <div class="content-body">
            <div class="flex items-center justify-center min-h-[60vh]" style="max-width:532px; margin:0 auto;">
                <div class="flex flex-col items-center gap-6 max-w-2xl text-center px-4">
                    <h2 class="text-[24px] font-bold text-[#1c1b1b] mb-1">Help & Support</h2>
                    <p class="text-[14px] font-[400] text-[#1c1b1b]">
                        Need assistance? We're here to help! Send us an email at 
                        <a href="mailto:{{ $supportEmail }}" class="text-[#1c1b1b] underline hover:text-gray-600 transition-colors">
                            {{ $supportEmail }}
                        </a> 
                        and our support team will get back to you as soon as possible.
                    </p>
                    <a 
                        href="mailto:{{ $supportEmail }}" 
                        class="btn portal-btn-primary-small inline-flex items-center justify-center gap-2 mt-[16px]"
                    >
                        Email Support
                    </a>
                </div>
            </div>
            
            @if(count($adaiTutorials)>0)
            <div class="flex items-center justify-center" style="max-width:532px; margin:0 auto;">
                <div class="flex flex-col items-center gap-6 max-w-2xl text-center px-4">
                    <h2 class="text-[24px] font-bold text-[#1c1b1b] mb-6">Tutorials Videos</h2>
                </div>
            </div>
            <div class="flex items-center justify-center">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 max-w-6xl w-full px-4">                
                    @foreach($adaiTutorials as $video)
                        <div class="thumb-wrapper cursor-pointer group" data-video-id="{{ $video['video_id'] }}">
                            <div class="relative rounded-lg overflow-hidden bg-gray-200 aspect-video mb-3">
                                <img 
                                    src="{{ $video['thumbnail_image'] }}" 
                                    alt="{{ $video['title'] }}"
                                    class="w-full h-full object-cover group-hover:opacity-90 transition-opacity"
                                    onerror="this.src='{{ asset('assets/images/placeholder_image.svg') }}'"
                                >
                                <!-- Play Button - Bottom Right -->
                                <div class="absolute bottom-3 right-3 bg-black/70 hover:bg-black/90 rounded-full p-3 transition-all group-hover:scale-110">
                                    <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M8 5v14l11-7z"/>
                                    </svg>
                                </div>
                            </div>
                            <!-- Video Title -->
                            <h3 class="text-[18px] font-medium text-gray-800 text-left">{{ $video['title'] }}</h3>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </main>

    <!-- YouTube Video Modal -->
    <div id="youtube-video-modal" class="fixed inset-0 z-50 bg-black/90 backdrop-blur-sm hidden items-center justify-center">
        <div class="relative w-full max-w-4xl mx-4">
            <!-- Close button -->
            <button id="close-youtube-modal" class="absolute -top-12 right-0 text-white hover:text-gray-300 transition-colors z-20">
                <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
           <div class="relative w-full bg-black" style="padding-bottom: 56.25%;">
                <div class="absolute top-0 left-0 w-full h-full">
                     <div id="youtube-player"></div>
                     <div id="click-shield" class="absolute inset-0 z-10 cursor-pointer w-full h-full"></div>
                </div>
            </div>
            <!-- Video Title -->
            <h3 id="youtube-video-title" class="text-white text-lg font-semibold mt-4 text-center"></h3>
        </div>
    </div>

    @section('script')
  <script>
    // --- 1. Load YouTube IFrame API ---
    var tag = document.createElement('script');
    tag.src = "https://www.youtube.com/iframe_api";
    var firstScriptTag = document.getElementsByTagName('script')[0];
    firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);

    var ytPlayer;

    // --- 2. Setup Player when API is Ready ---
    // We attach this to window to ensure the API can find it
    window.onYouTubeIframeAPIReady = function() {
        ytPlayer = new YT.Player('youtube-player', {
            height: '100%',
            width: '100%',
            playerVars: {
                'playsinline': 1,
                'rel': 0,            // Restrict suggestions
                'modestbranding': 1, // Minimize logo
                'controls': 1,       // Allow user control
                'iv_load_policy': 3, // Hide annotations
                'origin': window.location.origin, // Fix for local dev errors
                'disablekb': 1,      // Disable keyboard controls
                'fs': 1              // Disable fullscreen button
            },
            events: {
                'onStateChange': onPlayerStateChange
            }
        });
    };

    // --- 3. THE FIX: Detect End & Reset Instantly ---
    function onPlayerStateChange(event) {
        // State 0 means "Video Ended"
        if (event.data === 0) { 
            ytPlayer.seekTo(0);    // Rewind to 0 seconds (Thumbnail)
            ytPlayer.stopVideo();  // Stop completely
        }
    }

    // --- 4. Connect to Your Modal Buttons ---
    document.addEventListener('DOMContentLoaded', function() {
        // --- NEW: Handle Shield Clicks ---
        /*const shield = document.getElementById('click-shield');
        
        shield.addEventListener('click', function() {
            if (!ytPlayer || !ytPlayer.getPlayerState) return;

            const state = ytPlayer.getPlayerState();
            
            // If playing (1) or buffering (3), then Pause.
            // Otherwise (paused, ended, cued), Play.
            if (state === 1 || state === 3) {
                ytPlayer.pauseVideo();
            } else {
                ytPlayer.playVideo();
            }
        });
        */

        const thumbWrappers = document.querySelectorAll('.thumb-wrapper');
        const modal = document.getElementById('youtube-video-modal');
        const closeBtn = document.getElementById('close-youtube-modal');
        const videoTitle = document.getElementById('youtube-video-title');
        
        // Open Modal
        thumbWrappers.forEach(wrapper => {
            wrapper.addEventListener('click', function() {
                const videoId = this.getAttribute('data-video-id');
                const title = this.querySelector('h3').textContent;
                
                videoTitle.textContent = title;
                
                modal.classList.remove('hidden');
                modal.classList.add('flex');
                document.body.style.overflow = 'hidden';

                // Load the specific video and play
                if (ytPlayer && ytPlayer.loadVideoById) {
                    ytPlayer.loadVideoById(videoId);
                }
            });
        });
        
        // Close Modal
        function closeModal() {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
            document.body.style.overflow = '';
            
            // Stop video so audio doesn't keep playing
            if (ytPlayer && ytPlayer.stopVideo) {
                ytPlayer.stopVideo();
            }
        }
        
        if (closeBtn) closeBtn.addEventListener('click', closeModal);
        
        // Close on Escape
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
                closeModal();
            }
        });
        
        // Close on Background Click
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                closeModal();
            }
        });
    });
    </script>
    @endsection
</x-dashboard-layout>

