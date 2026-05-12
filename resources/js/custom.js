// Helper function to close mobile menu
function closeMobileMenu() {
    const mobileMenu = $("#mobile-menu");
    const sidebarMenu = $("#sidebar-menu-container");
    const isMobileOpen = mobileMenu.hasClass("opacity-100");
    const isSidebarOpen = sidebarMenu.hasClass("opacity-100");

    if (isMobileOpen || isSidebarOpen) {
        mobileMenu
            .removeClass("opacity-100 visible pointer-events-auto")
            .addClass("opacity-0 invisible pointer-events-none");
        $("#mobile-backdrop")
            .removeClass("opacity-100")
            .addClass("opacity-0");
        $("#mobile-menu-content")
            .removeClass("translate-y-0")
            .addClass("-translate-y-full");

        sidebarMenu
            .removeClass("opacity-100 visible pointer-events-auto")
            .addClass("opacity-0 invisible pointer-events-none");
        $("#sidebar-backdrop")
            .removeClass("opacity-100")
            .addClass("opacity-0");
        $("#sidebar-menu")
            .removeClass("translate-x-0")
            .addClass("translate-x-full");

        // Reset menu icon state
        $("#menu-icon").removeClass("hidden");
        $("#close-icon").addClass("hidden");
        $("#mobile-menu-toggle").removeClass("active");
        $("body").css("overflow", "");
    }
}

// Expose closeMobileMenu globally for use by mobile search overlay
window.closeMobileMenu = closeMobileMenu;

$(document).ready(function () {
    // Mobile Menu Toggle
    $("#mobile-menu-toggle").on("click", function (e) {
        e.preventDefault();
        e.stopPropagation();

        // Close mobile search if open (using exposed API from marketplace_search.js)
        if (window.MobileSearchOverlay && window.MobileSearchOverlay.isOpen()) {
            window.MobileSearchOverlay.close();
        }

        const mobileMenu = $("#mobile-menu");
        const sidebarMenu = $("#sidebar-menu-container");
        const isMobileOpen = mobileMenu.hasClass("opacity-100");
        const isSidebarOpen = sidebarMenu.hasClass("opacity-100");

        if (isMobileOpen || isSidebarOpen) {
            // Close menu - slide up
            mobileMenu
                .removeClass("opacity-100 visible pointer-events-auto")
                .addClass("opacity-0 invisible pointer-events-none");
            $("#mobile-backdrop")
                .removeClass("opacity-100")
                .addClass("opacity-0");
            $("#mobile-menu-content")
                .removeClass("translate-y-0")
                .addClass("-translate-y-full");

            sidebarMenu
                .removeClass("opacity-100 visible pointer-events-auto")
                .addClass("opacity-0 invisible pointer-events-none");
            $("#sidebar-backdrop")
                .removeClass("opacity-100")
                .addClass("opacity-0");
            $("#sidebar-menu")
                .removeClass("translate-x-0")
                .addClass("translate-x-full");

            $("#menu-icon").removeClass("hidden");
            $("#close-icon").addClass("hidden");
            $("body").css("overflow", "");
        } else {
            // Open menu - slide down from top (mobile) or slide in from right (sidebar)
            const isMobile = window.innerWidth < 640; // sm breakpoint

            if (isMobile) {
                // Mobile menu - slide down from top
                mobileMenu
                    .removeClass("opacity-0 invisible pointer-events-none")
                    .addClass("opacity-100 visible pointer-events-auto");
                $("#mobile-backdrop")
                    .removeClass("opacity-0")
                    .addClass("opacity-100");
                $("#mobile-menu-content")
                    .removeClass("-translate-y-full")
                    .addClass("translate-y-0");
            } else {
                // Sidebar menu - slide in from right
                sidebarMenu
                    .removeClass("opacity-0 invisible pointer-events-none")
                    .addClass("opacity-100 visible pointer-events-auto");
                $("#sidebar-backdrop")
                    .removeClass("opacity-0")
                    .addClass("opacity-100");
                $("#sidebar-menu")
                    .removeClass("translate-x-full")
                    .addClass("translate-x-0");
            }

            $("#menu-icon").addClass("hidden");
            $("#close-icon").removeClass("hidden");
            $("body").css("overflow", "hidden");
        }
    });

    // Close mobile menu when clicking backdrop
    $("#mobile-backdrop, #sidebar-backdrop").on("click", function (e) {
        e.preventDefault();
        e.stopPropagation();
        $("#mobile-menu-toggle").click();
    });

    // Close mobile menu when clicking close button
    $("#mobile-menu-close").on("click", function (e) {
        e.preventDefault();
        e.stopPropagation();
        $("#mobile-menu-toggle").click();
    });

    // Close mobile menu when clicking a link
    $("#mobile-menu a, #sidebar-menu a").on("click", function () {
        $("#mobile-menu-toggle").click();
    });

    // Close menu on window resize if switching between mobile and desktop
    let menuResizeTimer;
    $(window).on("resize", function () {
        clearTimeout(menuResizeTimer);
        menuResizeTimer = setTimeout(function () {
            const isMobile = window.innerWidth < 640;
            const mobileMenu = $("#mobile-menu");
            const sidebarMenu = $("#sidebar-menu-container");

            if (isMobile && sidebarMenu.hasClass("opacity-100")) {
                sidebarMenu
                    .removeClass("opacity-100 visible pointer-events-auto")
                    .addClass("opacity-0 invisible pointer-events-none");
                $("#sidebar-backdrop")
                    .removeClass("opacity-100")
                    .addClass("opacity-0");
                $("#sidebar-menu")
                    .removeClass("translate-x-0")
                    .addClass("translate-x-full");
            } else if (!isMobile && mobileMenu.hasClass("opacity-100")) {
                mobileMenu
                    .removeClass("opacity-100 visible pointer-events-auto")
                    .addClass("opacity-0 invisible pointer-events-none");
                $("#mobile-backdrop")
                    .removeClass("opacity-100")
                    .addClass("opacity-0");
                $("#mobile-menu-content")
                    .removeClass("translate-y-0")
                    .addClass("-translate-y-full");
            }
        }, 250);
    });

    // Video Modal with Vimeo Player API
    let modalPlayer = null;
    let backgroundPlayer = null;
    let isVideoPlaying = false;
    let isMuted = false;
    const desktopVideoId = "1128844383";
    const mobileVideoId = "1128844049";

    // Load Vimeo Player API
    function loadVimeoScript() {
        if (window.Vimeo) return Promise.resolve();

        return new Promise((resolve, reject) => {
            const script = document.createElement("script");
            script.src = "https://player.vimeo.com/api/player.js";
            script.async = true;
            script.onload = () => resolve();
            script.onerror = () =>
                reject(new Error("Failed to load Vimeo script"));
            document.head.appendChild(script);
        });
    }

    // Initialize hero background video
    let currentVideoId = null;
    let heroLoadPercent = 0;
    let isHeroVideoReady = false;

    function updateHeroLoadingUI(percent) {
        heroLoadPercent = percent;
        $("#hero-video-load-percent").text(Math.floor(percent) + "%");
        $("#hero-video-progress-bar").css("width", percent + "%");

        if (percent >= 100 && isHeroVideoReady) {
            $("#hero-video-loading").fadeOut(300);
            $("#hero-background-video")
                .removeClass("opacity-0")
                .addClass("opacity-100");
        }
    }

    function initHeroBackgroundVideo() {
        const $videoIframe = $("#hero-background-video");
        if (!$videoIframe.length) return;

        const isMobile = window.innerWidth < 768;
        const desktopId =
            $videoIframe.data("desktop-video-id") || desktopVideoId;
        const mobileId = $videoIframe.data("mobile-video-id") || mobileVideoId;
        const videoId = isMobile ? mobileId : desktopId;

        // Don't reload if same video
        if (currentVideoId === videoId && $videoIframe.attr("src")) {
            return;
        }

        currentVideoId = videoId;

        // Reset loading state
        heroLoadPercent = 0;
        isHeroVideoReady = false;
        $("#hero-video-loading").show();
        $videoIframe.removeClass("opacity-100").addClass("opacity-0");
        updateHeroLoadingUI(0);

        // Set iframe src
        const videoUrl = `https://player.vimeo.com/video/${videoId}?badge=0&autopause=0&player_id=0&app_id=58479&background=1&autoplay=1&loop=1&muted=1&controls=0&quality=auto&responsive=1`;
        $videoIframe.attr("src", videoUrl);

        // Load Vimeo API and initialize player
        loadVimeoScript()
            .then(() => {
                if (!window.Vimeo) return;

                // Wait for iframe to load
                const checkInterval = setInterval(() => {
                    try {
                        backgroundPlayer = new window.Vimeo.Player(
                            $videoIframe[0]
                        );
                        clearInterval(checkInterval);
                        initBackgroundPlayerEvents();
                    } catch (error) {
                        // Iframe might not be ready yet, continue checking
                    }
                }, 100);

                // Timeout after 5 seconds
                setTimeout(() => {
                    clearInterval(checkInterval);
                    if (!backgroundPlayer) {
                        // Fallback: just show the video
                        isHeroVideoReady = true;
                        updateHeroLoadingUI(100);
                    }
                }, 5000);
            })
            .catch((error) => {
                console.error(
                    "Failed to load Vimeo for background video:",
                    error
                );
                // Fallback: show video anyway
                setTimeout(() => {
                    isHeroVideoReady = true;
                    updateHeroLoadingUI(100);
                }, 2000);
            });
    }

    function initBackgroundPlayerEvents() {
        if (!backgroundPlayer) return;

        // Simulate loading progress
        let progressInterval = setInterval(function () {
            if (heroLoadPercent < 90 && !isHeroVideoReady) {
                const increment =
                    heroLoadPercent < 60 ? 6 : heroLoadPercent < 80 ? 3.5 : 2;
                updateHeroLoadingUI(Math.min(90, heroLoadPercent + increment));
            }
        }, 40);

        backgroundPlayer
            .ready()
            .then(() => {
                updateHeroLoadingUI(25);

                // Listen for progress events
                backgroundPlayer.on("progress", function (data) {
                    const percent = Math.round((data.percent || 0) * 100);
                    if (percent > heroLoadPercent) {
                        updateHeroLoadingUI(percent);
                    }
                });

                // Listen for loaded event
                backgroundPlayer.on("loaded", function () {
                    updateHeroLoadingUI(Math.max(heroLoadPercent, 25));
                });

                // Listen for play event
                backgroundPlayer.on("play", () => {
                    isHeroVideoReady = true;
                    updateHeroLoadingUI(100);
                    clearInterval(progressInterval);
                });

                // Get initial play state
                backgroundPlayer.getPaused().then((paused) => {
                    if (!paused) {
                        isHeroVideoReady = true;
                        updateHeroLoadingUI(100);
                        clearInterval(progressInterval);
                    }
                });

                // Timeout fallback - mark as ready after 3 seconds
                setTimeout(function () {
                    if (!isHeroVideoReady) {
                        isHeroVideoReady = true;
                        updateHeroLoadingUI(100);
                        clearInterval(progressInterval);
                    }
                }, 3000);
            })
            .catch(function () {
                // If ready fails, still mark as ready after delay
                setTimeout(function () {
                    isHeroVideoReady = true;
                    updateHeroLoadingUI(100);
                    clearInterval(progressInterval);
                }, 2000);
            });
    }

    // Initialize background video player (legacy function for modal)
    function initBackgroundPlayer() {
        if (!window.Vimeo || !$("#hero-background-video").length) return;

        try {
            if (!backgroundPlayer) {
                backgroundPlayer = new window.Vimeo.Player(
                    $("#hero-background-video")[0]
                );
            }
        } catch (error) {
            console.warn("Background player initialization failed:", error);
        }
    }

    // Initialize modal video player
    let loadPercent = 0;
    let isVideoReady = false;

    function updateLoadingUI(percent) {
        loadPercent = percent;
        $("#video-load-percent").text(Math.floor(percent) + "%");
        $("#video-progress-bar").css("width", percent + "%");

        if (percent >= 100 && isVideoReady) {
            $("#video-loading").fadeOut(300);
            $("#modal-video").removeClass("opacity-0").addClass("opacity-100");
        }
    }

    function initModalPlayer() {
        if (!window.Vimeo || !$("#modal-video").length) return;

        const isMobile = window.innerWidth < 768;
        const videoId = isMobile ? mobileVideoId : desktopVideoId;

        // Reset loading state
        loadPercent = 0;
        isVideoReady = false;
        $("#video-loading").show();
        $("#modal-video").removeClass("opacity-100").addClass("opacity-0");
        updateLoadingUI(0);

        // Set iframe src
        $("#modal-video").attr(
            "src",
            `https://player.vimeo.com/video/${videoId}?badge=0&autopause=0&player_id=0&app_id=58479&background=0&autoplay=1&loop=0&muted=0&controls=0&quality=auto&responsive=1`
        );

        // Initialize player
        modalPlayer = new window.Vimeo.Player($("#modal-video")[0]);

        // Simulate loading progress
        let progressInterval = setInterval(function () {
            if (loadPercent < 90 && !isVideoReady) {
                const increment =
                    loadPercent < 60 ? 6 : loadPercent < 80 ? 3.5 : 2;
                updateLoadingUI(Math.min(90, loadPercent + increment));
            }
        }, 40);

        modalPlayer
            .ready()
            .then(() => {
                updateLoadingUI(25);

                // Set initial volume
                modalPlayer.setVolume(1);
                isMuted = false;
                updateMuteButton();

                // Listen for progress events
                modalPlayer.on("progress", function (data) {
                    const percent = Math.round((data.percent || 0) * 100);
                    if (percent > loadPercent) {
                        updateLoadingUI(percent);
                    }
                });

                // Listen for loaded event
                modalPlayer.on("loaded", function () {
                    updateLoadingUI(Math.max(loadPercent, 25));
                });

                // Listen for play/pause events
                modalPlayer.on("play", () => {
                    isVideoPlaying = true;
                    isVideoReady = true;
                    updateLoadingUI(100);
                    clearInterval(progressInterval);
                    updatePlayButton();
                    $("#play-icon").addClass("hidden");
                    $("#pause-icon").removeClass("hidden");
                    $("#play-text").text("Pause Video");
                });

                modalPlayer.on("pause", () => {
                    isVideoPlaying = false;
                    updatePlayButton();
                    $("#play-icon").removeClass("hidden");
                    $("#pause-icon").addClass("hidden");
                    $("#play-text").text("Play Video");
                });

                // Get initial play state
                modalPlayer.getPaused().then((paused) => {
                    isVideoPlaying = !paused;
                    if (!paused) {
                        isVideoReady = true;
                        updateLoadingUI(100);
                        clearInterval(progressInterval);
                    }
                    updatePlayButton();
                });

                // Timeout fallback - mark as ready after 3 seconds
                setTimeout(function () {
                    if (!isVideoReady) {
                        isVideoReady = true;
                        updateLoadingUI(100);
                        clearInterval(progressInterval);
                    }
                }, 3000);
            })
            .catch(function () {
                // If ready fails, still mark as ready after delay
                setTimeout(function () {
                    isVideoReady = true;
                    updateLoadingUI(100);
                    clearInterval(progressInterval);
                }, 2000);
            });
    }

    function updatePlayButton() {
        if (isVideoPlaying) {
            $("#modal-play-icon").addClass("hidden");
            $("#modal-pause-icon").removeClass("hidden");
            $("#modal-play-text").text("Pause");
        } else {
            $("#modal-play-icon").removeClass("hidden");
            $("#modal-pause-icon").addClass("hidden");
            $("#modal-play-text").text("Play");
        }
    }

    function updateMuteButton() {
        if (isMuted) {
            $("#volume-icon").addClass("hidden");
            $("#mute-icon").removeClass("hidden");
            $("#mute-text").text("Listen Audio");
        } else {
            $("#volume-icon").removeClass("hidden");
            $("#mute-icon").addClass("hidden");
            $("#mute-text").text("Mute Audio");
        }
    }

    // Initialize hero background video on page load
    if ($("#hero-background-video").length) {
        // Try multiple initialization methods for reliability
        if (document.readyState === "loading") {
            document.addEventListener("DOMContentLoaded", function () {
                setTimeout(initHeroBackgroundVideo, 100);
            });
        } else {
            setTimeout(initHeroBackgroundVideo, 100);
        }

        // Also try on window load as fallback
        $(window).on("load", function () {
            if (!$("#hero-background-video").attr("src")) {
                initHeroBackgroundVideo();
            }
        });

        // Handle window resize to switch between mobile/desktop videos
        let heroResizeTimer;
        $(window).on("resize", function () {
            clearTimeout(heroResizeTimer);
            heroResizeTimer = setTimeout(function () {
                const isMobile = window.innerWidth < 768;
                const $videoIframe = $("#hero-background-video");
                const desktopId =
                    $videoIframe.data("desktop-video-id") || desktopVideoId;
                const mobileId =
                    $videoIframe.data("mobile-video-id") || mobileVideoId;
                const newVideoId = isMobile ? mobileId : desktopId;

                // Only reload if video ID changed
                if (currentVideoId !== newVideoId) {
                    initHeroBackgroundVideo();
                }
            }, 500);
        });
    }

    // Open video modal
    $("#play-video-btn").on("click", function () {
        loadVimeoScript()
            .then(() => {
                // Pause background video when opening modal
                if (backgroundPlayer) {
                    backgroundPlayer.pause().catch(() => { });
                }

                $("#video-modal").removeClass("hidden");
                $("body").css("overflow", "hidden");

                // Small delay to ensure iframe is in DOM
                setTimeout(() => {
                    initModalPlayer();
                }, 100);
            })
            .catch((error) => {
                console.error("Failed to load Vimeo:", error);
            });
    });

    // Close video modal
    $("#close-video-modal").on("click", function () {
        if (modalPlayer) {
            modalPlayer.pause().catch(() => { });
            modalPlayer = null;
        }
        $("#modal-video").attr("src", "");
        $("#video-modal").addClass("hidden");
        $("body").css("overflow", "unset");
        isVideoPlaying = false;
        isMuted = false;
        isVideoReady = false;
        loadPercent = 0;
        $("#play-icon").removeClass("hidden");
        $("#pause-icon").addClass("hidden");
        $("#play-text").text("Play Video");
        $("#video-loading").show();
        $("#modal-video").removeClass("opacity-100").addClass("opacity-0");

        // Resume background video when closing modal
        if (backgroundPlayer) {
            backgroundPlayer.play().catch(() => { });
        }
    });

    // Close on Escape key
    $(document).on("keydown", function (e) {
        if (e.key === "Escape" && !$("#video-modal").hasClass("hidden")) {
            $("#close-video-modal").click();
        }
    });

    // Handle window resize to reinitialize background player if needed (for modal)
    let resizeTimer;
    $(window).on("resize", function () {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(function () {
            if (
                window.Vimeo &&
                $("#hero-background-video").length &&
                !backgroundPlayer
            ) {
                initBackgroundPlayer();
            }
        }, 500);
    });

    // Play/Pause button in modal
    $("#modal-play-pause-btn").on("click", function () {
        if (!modalPlayer) return;

        if (isVideoPlaying) {
            modalPlayer.pause();
        } else {
            modalPlayer.setVolume(1);
            modalPlayer.play();
        }
    });

    // Mute button in modal
    $("#modal-mute-btn").on("click", function () {
        if (!modalPlayer) return;

        if (isMuted) {
            modalPlayer.setVolume(1);
            isMuted = false;
        } else {
            modalPlayer.setVolume(0);
            isMuted = true;
        }
        updateMuteButton();
    });

    // Artwork Gallery
    const artworks = [
        {
            id: 1,
            title: "Abstract Composition",
            image: "assets/images/home-artpiece-1.png",
            width: 320,
            height: 202,
        },
        {
            id: 2,
            title: "Modern Artwork",
            image: "assets/images/home-artpiece-2.png",
            width: 280,
            height: 287,
        },
        {
            id: 3,
            title: "Cultural Expression",
            image: "assets/images/home-artpiece-3.png",
            width: 280,
            height: 398,
        },
        {
            id: 4,
            title: "Artistic Vision",
            image: "assets/images/home-artpiece-4.png",
            width: 280,
            height: 376,
        },
        {
            id: 5,
            title: "Creative Work",
            image: "assets/images/home-artpiece-5.png",
            width: 474,
            height: 263,
        },
        {
            id: 6,
            title: "Abstract Art",
            image: "assets/images/home-artpiece-6.png",
            width: 280,
            height: 367,
        },
        {
            id: 7,
            title: "Landscape Art",
            image: "assets/images/home-artpiece-7.png",
            width: 441,
            height: 280,
        },
        {
            id: 8,
            title: "Cultural Expression",
            image: "assets/images/home-artpiece-8.png",
            width: 280,
            height: 367,
        },
        {
            id: 9,
            title: "Creative Work",
            image: "assets/images/home-artpiece-9.png",
            width: 259,
            height: 280,
        },
        {
            id: 10,
            title: "Sculptural Form",
            image: "assets/images/home-artpiece-10.png",
            width: 312,
            height: 376,
        },
        {
            id: 11,
            title: "Urban Portrait",
            image: "assets/images/home-artpiece-11.png",
            width: 542,
            height: 260,
        },
    ];

    const gallery = $("#artwork-gallery");
    // Duplicate artworks 3 times for seamless loop
    for (let i = 0; i < 3; i++) {
        artworks.forEach(function (artwork) {
            gallery.append(`
                <div class="artwork-item relative flex-shrink-0 overflow-hidden cursor-pointer group flex items-end">
                    <img src="${artwork.image}" alt="${artwork.title}" class="object-contain" style="width: ${artwork.width}px; height: ${artwork.height}px; display: block; max-width: 100%;">
                </div>
            `);
        });
    }

    // Pause gallery animation on hover
    $(".artwork-item")
        .on("mouseenter", function () {
            $("#artwork-gallery").addClass("paused");
        })
        .on("mouseleave", function () {
            $("#artwork-gallery").removeClass("paused");
        });

    // Newsletter Modal
    function openNewsletterModal() {
        $("#newsletter-modal").removeClass("hidden").addClass("flex");
    }

    function closeNewsletterModal() {
        $("#newsletter-modal").addClass("hidden").removeClass("flex");
    }

    $(
        "#subscribe-marketplace, #subscribe-marketplace-mobile, #subscribe-concierge"
    ).on("click", function (e) {
        e.preventDefault();
        openNewsletterModal();
    });

    $("#close-newsletter-modal").on("click", closeNewsletterModal);

    $("#newsletter-modal").on("click", function (e) {
        if ($(e.target).is("#newsletter-modal")) {
            closeNewsletterModal();
        }
    });

    $("#newsletter-form").on("submit", function (e) {
        e.preventDefault();
        const email = $(this).find('input[type="email"]').val();
        alert("Thank you for subscribing! We will notify you at " + email);
        closeNewsletterModal();
        $(this)[0].reset();
    });

    // Smooth scroll for anchor links
    $('a[href^="#"]').on("click", function (e) {
        const href = this.getAttribute("href");
        // Prevent error if href is just "#" or empty - this causes "Syntax error, unrecognized expression: #"
        if (!href || href === '#' || href.trim() === '#') {
            return;
        }
        try {
            const target = $(href);
        if (target.length) {
            e.preventDefault();
            $("html, body").animate(
                {
                    scrollTop: target.offset().top - 64,
                },
                800
            );
            }
        } catch (err) {
            // Silently ignore invalid selectors to prevent console errors
            return;
        }
    });

    // Password toggle functionality
    $(".password-toggle, .password-toggle-btn").on("click", function () {
        var $button = $(this);
        var $input = $button.siblings('input[type="password"], input[type="text"]');
        var $icon = $button.find("img");

        var showIcon = $button.data("show-icon");
        var hideIcon = $button.data("hide-icon");

        if ($input.attr("type") === "password") {
            $input.attr("type", "text");
            $icon.attr("src", showIcon);   // Use full URL
            $button.attr("aria-pressed", "true");
        } else {
            $input.attr("type", "password");
            $icon.attr("src", hideIcon);   // Use full URL
            $button.attr("aria-pressed", "false");
        }
    });

    // list gallery
    // --- Overlay / Sidebar Panel Logic ---

    function openProfilePanel(galleryId) {
        // In a real application, you would load data based on galleryId here.
        console.log("Opening profile for Gallery ID:", galleryId);
        $("#profile-overlay").fadeIn(200).addClass("open");
    }

    function closeProfilePanel() {
        $("#profile-overlay").removeClass("open").fadeOut(300);
    }

    // 1. Open Panel on Row Click
    $("#artwork-list").on("click", ".list-row-grid", function (e) {
        // Check if the click originated from an explicit interactive element (checkbox, button, menu button)
        // If it did, we don't open the profile, we let the element handle its own action.
        if (
            $(e.target).is('input[type="checkbox"]') ||
            $(e.target).closest(".action-menu-btn").length ||
            $(e.target).closest(".row-button").length
        ) {
            return;
        }

        // Get the gallery ID from the row's data attribute (for future use)
        const galleryId = $(this).data("gallery-id");
        openProfilePanel(galleryId);
    });
    $("#artwork-list").on("click", ".artist-list-row-grid", function (e) {
        // Check if the click originated from an explicit interactive element (checkbox, button, menu button)
        // If it did, we don't open the profile, we let the element handle its own action.
        if (
            $(e.target).is('input[type="checkbox"]') ||
            $(e.target).closest(".action-menu-btn").length ||
            $(e.target).closest(".row-button").length
        ) {
            return;
        }

        // Get the gallery ID from the row's data attribute (for future use)
        const galleryId = $(this).data("gallery-id");
        openProfilePanel(galleryId);
    });

    // 2. Close Panel on Button Click
    $("#close-profile-btn").on("click", closeProfilePanel);

    // 3. Close Panel on Overlay Background Click (Left half)
    $("#overlay-background").on("click", closeProfilePanel);

    // --- Existing Dashboard Interactivity ---

    // 1. Sidebar Dropdown Toggles (New Structure)
    $(document).ready(function () {
        // Initially hide all submenus
        $(".nav-submenu").hide();

        // Handle dropdown clicks - use setTimeout to ensure DOM is ready
        setTimeout(function () {
            $(".nav-dropdown")
                .off("click")
                .on("click", function (e) {
                    e.preventDefault();
                    e.stopPropagation();

                    var $dropdown = $(this);
                    var $wrapper = $dropdown.closest(".nav-dropdown-wrapper");
                    var $submenu = $wrapper.find(".nav-submenu");
                    var $chevron = $dropdown.find(".chevron");

                    if ($submenu.length) {
                        // Toggle submenu with animation
                        $submenu.slideToggle(200);

                        // Toggle dropdown open state
                        $dropdown.toggleClass("open");
                        var isOpen = $dropdown.hasClass("open");
                        $dropdown.attr("aria-expanded", isOpen);

                        // Rotate chevron icon
                        if (isOpen) {
                            $chevron.css("transform", "rotate(180deg)");
                        } else {
                            $chevron.css("transform", "rotate(0deg)");
                        }

                        // Close other dropdowns
                        $(".nav-dropdown")
                            .not($dropdown)
                            .removeClass("open")
                            .attr("aria-expanded", "false");
                        $(".nav-submenu").not($submenu).slideUp(200);
                        $(".nav-dropdown")
                            .not($dropdown)
                            .find(".chevron")
                            .css("transform", "rotate(0deg)");
                    }
                });
        }, 100);
    });

    // Legacy support for old dropdown structure
    $("#clients-dropdown, #content-dropdown").each(function () {
        var $dropdown = $(this);
        var $toggle = $dropdown.find("[data-target]");
        var $submenu = $($toggle.data("target"));
        var $icon = $toggle.find(".material-icons-outlined:last");

        if (!$toggle.length || !$submenu.length) {
            return; // Skip if structure doesn't match
        }

        // Check if submenu is initially visible (Clients is in HTML)
        if ($submenu.hasClass("hidden")) {
            // Initial state for collapsed menus
            $icon.removeClass("rotate-180").addClass("rotate-0");
        } else {
            // Initial state for expanded menus (Clients)
            $icon.removeClass("rotate-0").addClass("rotate-180");
            $toggle
                .find(".text-gray-500")
                .removeClass("text-gray-500")
                .addClass("text-violet-700");
            $toggle.find(".text-sm").addClass("font-semibold");
        }

        $toggle.on("click", function () {
            $submenu.slideToggle(200, function () {
                // Toggle the icon rotation
                if ($submenu.is(":visible")) {
                    $icon.removeClass("rotate-0").addClass("rotate-180");
                    $dropdown
                        .addClass("bg-gray-100")
                        .removeClass("hover:bg-gray-50");
                    // Apply active text styles
                    $toggle
                        .find(".text-gray-500")
                        .removeClass("text-gray-500")
                        .addClass("text-violet-700");
                    $toggle.find(".text-sm").addClass("font-semibold");
                } else {
                    $icon.removeClass("rotate-180").addClass("rotate-0");
                    $dropdown
                        .removeClass("bg-gray-100")
                        .addClass("hover:bg-gray-50");
                    // Revert text styles
                    $toggle
                        .find(".text-violet-700")
                        .removeClass("text-violet-700")
                        .addClass("text-gray-500");
                    $toggle.find(".text-sm").removeClass("font-semibold");
                }
            });
        });
    });

    // 2. Action Menu Dropdown Toggles (Row-specific options)
    $(document).on("click", ".action-menu-btn", function (e) {
        e.preventDefault();
        e.stopPropagation(); // Prevent document click from closing immediately and row click from opening panel

        var $dropdown = $(this).next(".action-menu-dropdown");

        // Close all other open menus
        $(".action-menu-dropdown")
            .not($dropdown)
            //.removeClass("hidden")
            .slideUp(100, function () {
                $(this).addClass("hidden");
            });

        // Toggle the current menu - handle Tailwind 'hidden' class
        if ($dropdown.hasClass("hidden")) {
            $dropdown.removeClass("hidden").hide().slideDown(100);
        } else {
            $dropdown.slideUp(100, function () {
                $(this).addClass("hidden");
            });
        }
    });

    // Close the dropdown when clicking anywhere else on the document
    $(document).on("click", function (e) {
        // Don't close if clicking on action menu button, dropdown, or any links/buttons inside dropdown
        var $target = $(e.target);
        var isInsideDropdown =
            $target.closest(".action-menu-btn, .action-menu-dropdown").length >
            0;
        var isDeleteLink =
            $target.hasClass("deleteGallery") ||
            $target.hasClass("deleteArtist") ||
            $target.closest(".deleteGallery, .deleteArtist").length > 0;

        if (!isInsideDropdown && !isDeleteLink) {
            $(".action-menu-dropdown").each(function () {
                if (!$(this).hasClass("hidden")) {
                    $(this).slideUp(100, function () {
                        $(this).addClass("hidden");
                    });
                }
            });
        }
    });

    // 3. Filter/Tab Switching Logic
    $(".filter-tab").on("click", function () {
        // Remove active styling from all tabs
        $(".filter-tab")
            .removeClass("bg-lime-400 border-lime-500 text-gray-800")
            .addClass(
                "bg-white border-gray-200 text-gray-500 hover:bg-gray-50"
            );

        // Add active styling to the clicked tab
        $(this)
            .addClass("bg-lime-400 border-lime-500 text-gray-800")
            .removeClass(
                "bg-white border-gray-200 text-gray-500 hover:bg-gray-50"
            );

        // Log the switch
        console.log("Switched to tab:", $(this).data("tab"));
    });

    // =============================================
    // MultiSelect Dropdown
    // =============================================
    const $wrapper = $(".mui-multiselect-wrapper");
    const $input = $("#specialisations-dropdown");
    const $menu = $wrapper.find(".mui-multiselect-menu");
    const $hiddenInput = $("#specialisations-input");

    // Set selectedValues from hidden input
    let selectedValues = $hiddenInput.val()
        ? $hiddenInput.val().split(",")
        : [];

    // -------------------------------
    // OPEN/CLOSE DROPDOWN
    // -------------------------------
    $input.on("click", function (e) {
        // If clicked on a chip → DO NOT open dropdown
        if (
            $(e.target).closest(".mui-chip").length ||
            $(e.target).closest(".mui-chip-delete").length
        ) {
            return;
        }

        e.stopPropagation();
        $menu.toggleClass("hidden");
    });

    // Close dropdown on outside click
    $(document).on("click", function (e) {
        if (!$(e.target).closest(".mui-multiselect-wrapper").length) {
            $menu.addClass("hidden");
        }
    });

    // -------------------------------
    // OPTION CLICK (SELECT/UNSELECT)
    // -------------------------------
    $menu.on("click", ".mui-option", function (e) {
        e.stopPropagation();

        const $option = $(this);
        const value = $option.data("value");
        const label = $option.data("label");
        const $checkbox = $option.find(".mui-checkbox");
        // If click is NOT on checkbox, toggle it
        if (!$(e.target).is("input[type='checkbox']")) {
            $checkbox.prop("checked", !$checkbox.prop("checked"));
        }

        const isChecked = $checkbox.prop("checked");

        // Toggle checkbox
        // $checkbox.prop("checked", !isChecked);

        if (isChecked) {
            // Add chip
            selectedValues.push(value);
            addChip(value, label, $input.find(".flex-wrap"));
        } else {
            // Remove chip
            selectedValues = selectedValues.filter((v) => v !== value);
            $input.find(`.mui-chip[data-value="${value}"]`).remove();
        }

        updateHiddenInput();
    });

    // -------------------------------
    // DELETE CHIP
    // -------------------------------
    $(document).on("click", ".mui-chip-delete", function (e) {
        e.stopPropagation();
        e.preventDefault();

        const value = $(this).data("value");

        // Remove from array
        selectedValues = selectedValues.filter((v) => v !== value);

        // Remove chip
        $(this).closest(".mui-chip").remove();

        // Uncheck checkbox
        $menu
            .find(`.mui-option[data-value="${value}"] .mui-checkbox`)
            .prop("checked", false);

        updateHiddenInput();
    });

    // -------------------------------
    // ADD CHIP
    // -------------------------------
    function addChip(value, label, $container) {
        const chip = $(`
        <div class="mui-chip inline-flex items-center justify-center px-3 py-1 bg-neutral-50 rounded-full border border-[#dddddd]"
             data-value="${value}">

            <span>${label}</span>

            <button type="button" class="mui-chip-delete ml-1 cursor-pointer" data-value="${value}">
                <img class="w-4 h-4" src="../../assets/images/adai-admin/icon-close.svg" />
            </button>
        </div>
    `);

        $container.append(chip);
    }

    // -------------------------------
    // UPDATE HIDDEN INPUT
    // -------------------------------
    function updateHiddenInput() {
        $hiddenInput.val(selectedValues.join(","));
    }

    // Fixed modal opening/closing for edit image (works with dynamically loaded content too)
    function handleImageModal() {
        const $modalOverlay = $("#modalOverlay");
        const $editImageButton = $("#editImageButton");

        // Only bind if elements exist
        if ($modalOverlay.length && $editImageButton.length) {
            // Open modal
            $editImageButton.on("click", function (e) {
                e.preventDefault();
                $modalOverlay.addClass("active");
            });

            // Close modal when clicking close button (even if loaded later), or cancel button
            $modalOverlay.on(
                "click",
                "#closeModal, .modal-cancel",
                function (e) {
                    e.preventDefault();
                    $modalOverlay.removeClass("active");
                }
            );

            // Close if click backdrop (but NOT if click children)
            $modalOverlay.on("click", function (e) {
                if (e.target === this) {
                    $modalOverlay.removeClass("active");
                }
            });
        }
    }

    // Run after DOM is ready
    $(handleImageModal);
    // Gallery Logo Modal
    // Fixed modal opening/closing for edit image (works with dynamically loaded content too)
    function handleLogoModal() {
        const $modalLogoOverlay = $("#modalLogoOverlay");
        const $editUpdateLogo = $("#editUpdateLogo");

        // Only bind if elements exist
        if ($modalLogoOverlay.length && $editUpdateLogo.length) {
            // Open modal
            $editUpdateLogo.on("click", function (e) {
                e.preventDefault();
                $modalLogoOverlay.addClass("active");
            });

            // Close modal when clicking close button (even if loaded later), or cancel button
            $modalLogoOverlay.on(
                "click",
                "#closeModal, .modal-cancel",
                function (e) {
                    e.preventDefault();
                    $modalLogoOverlay.removeClass("active");
                }
            );

            // Close if click backdrop (but NOT if click children)
            $modalLogoOverlay.on("click", function (e) {
                if (e.target === this) {
                    $modalLogoOverlay.removeClass("active");
                }
            });
        }
    }

    $(handleLogoModal);

    // Gallery Cover Modal
    // Fixed modal opening/closing for edit image (works with dynamically loaded content too)
    function handleCoverModal() {
        const $modalCoververlay = $("#modalCoververlay");
        const $editCoverImage = $("#editCoverImage");

        // Only bind if elements exist
        if ($modalCoververlay.length && $editCoverImage.length) {
            // Open modal
            $editCoverImage.on("click", function (e) {
                e.preventDefault();
                $modalCoververlay.addClass("active");
            });

            // Close modal when clicking close button (even if loaded later), or cancel button
            $modalCoververlay.on(
                "click",
                "#closeModal, .modal-cancel",
                function (e) {
                    e.preventDefault();
                    $modalCoververlay.removeClass("active");
                }
            );

            // Close if click backdrop (but NOT if click children)
            $modalCoververlay.on("click", function (e) {
                if (e.target === this) {
                    $modalCoververlay.removeClass("active");
                }
            });
        }
    }

    $(handleCoverModal);

    // Gallery Space Image Modal
    // Fixed modal opening/closing for edit image (works with dynamically loaded content too)
    function handleSpaceImageModal() {
        const $modalSpaceoverlay = $("#modalSpaceoverlay");
        const $editGallerySpaceImage = $("#editGallerySpaceImage");

        // Only bind if elements exist
        if ($modalSpaceoverlay.length && $editGallerySpaceImage.length) {
            // Open modal
            $editGallerySpaceImage.on("click", function (e) {
                e.preventDefault();
                $modalSpaceoverlay.addClass("active");
            });

            // Close modal when clicking close button (even if loaded later), or cancel button
            $modalSpaceoverlay.on(
                "click",
                "#closeModal, .modal-cancel",
                function (e) {
                    e.preventDefault();
                    $modalSpaceoverlay.removeClass("active");
                }
            );

            // Close if click backdrop (but NOT if click children)
            $modalSpaceoverlay.on("click", function (e) {
                if (e.target === this) {
                    $modalSpaceoverlay.removeClass("active");
                }
            });
        }
    }

    $(handleSpaceImageModal);

    const addBtn = document.getElementById("addBranchBtn");
    const wrapper = document.getElementById("branchesWrapper");
   

    if (addBtn && wrapper) {
        addBtn.addEventListener("click", function () {
            let allBranches = wrapper.querySelectorAll(".branch-block");
            let newIndex = allBranches.length; // Next index


            // Clone the first branch block (works even if it was empty)
            const firstBranch = allBranches[0];
            const clone = firstBranch.cloneNode(true);
            // 2. REMOVE OLD ERROR MESSAGES (Add this section)
            // -----------------------------------------------------------
            // This finds all <label class="error"> inside the clone and deletes them
            clone.querySelectorAll("label.error").forEach(function(el) {
                el.remove();
            });

            // Optional: If your inputs have a red border class (like .error), remove it too
            clone.querySelectorAll(".error").forEach(function(el) {
                el.classList.remove("error");
            });
            
            clone.querySelectorAll('input').forEach(input => input.value = '');
            clone.querySelectorAll('select').forEach(select => select.selectedIndex = 0);

            // Show remove button for cloned block
            clone.querySelector('.delete-branch').style.display = 'inline-block';


            // UPDATE BRANCH TITLE
            let title = clone.querySelector("label.text-gray-600.font-medium");
            if (title) {
                console.log("hello");
                title.textContent = `Branch ${newIndex + 1}`;
            }

            // UPDATE ALL INPUT NAMES + CLEAR VALUES + UPDATE IDs
            clone.querySelectorAll("input, select").forEach((input) => {
                let oldName = input.getAttribute("name");
                if (!oldName) return;

                // Extract field inside brackets → branch_name, address_line, city, post_code
                let field = oldName.substring(
                    oldName.lastIndexOf("[") + 1,
                    oldName.length - 1
                );

                // Set new name with updated index
                input.setAttribute("name", `branches[${newIndex}][${field}]`);

                // Update ID for input fields and select dropdowns
                if (input.id) {
                    const oldId = input.id;
                    const newId = `${field}_${newIndex}`;
                    input.setAttribute("id", newId);

                    // Update corresponding label's 'for' attribute
                    const label = clone.querySelector(`label[for="${oldId}"]`);
                    if (label) {
                        label.setAttribute("for", newId);
                        label.classList.remove("active");
                    }
                }

                // Special handling for country code selects
                if (input.classList.contains('country-code-select')) {
                    // Update onchange handler with new index
                    if (field === 'mobile_country_code') {
                        input.setAttribute('onchange', `updateCode(this, 'mobile_code_${newIndex}')`);
                        // Update the code display div ID
                        const codeDiv = input.parentElement.querySelector('[id^="mobile_code_"]');
                        if (codeDiv) {
                            codeDiv.id = `mobile_code_${newIndex}`;
                            codeDiv.textContent = ''; // Clear the display
                        }
                    } else if (field === 'landline_country_code') {
                        input.setAttribute('onchange', `updateCode(this, 'landline_code_${newIndex}')`);
                        // Update the code display div ID
                        const codeDiv = input.parentElement.querySelector('[id^="landline_code_"]');
                        if (codeDiv) {
                            codeDiv.id = `landline_code_${newIndex}`;
                            codeDiv.textContent = ''; // Clear the display
                        }
                    }
                }

                // Clear values and reset data-state
                if (input.tagName === "SELECT") {
                    input.selectedIndex = 0;
                    input.setAttribute("data-state", "placeholder");
                } else {
                    input.value = "";
                    input.setAttribute("data-state", "placeholder");
                }
            });

            // Insert before the button row
            wrapper.insertBefore(clone, addBtn.parentElement);


            // Initialize floating labels for the newly added branch
            setTimeout(function () {
                if (typeof window.initializeFloatingLabels === 'function') {
                    window.initializeFloatingLabels(clone);
                } else {
                    // Fallback: manually initialize if function not available
                    clone.querySelectorAll('input[data-state], select[data-state]').forEach(function (field) {
                        const label = clone.querySelector(`label[for="${field.id}"]`);
                        if (label) {
                            const hasValue = field.tagName === 'SELECT'
                                ? field.value && field.value !== ''
                                : field.value && field.value.toString().trim() !== '';
                            if (hasValue || field === document.activeElement) {
                                label.classList.add('active');
                            }
                        }
                    });
                }
                
                // Clear country code display divs for the new branch
                clone.querySelectorAll('[id^="mobile_code_"], [id^="landline_code_"]').forEach(function(div) {
                    div.textContent = '';
                    div.style.padding = '';
                    div.style.background = '';
                });
            }, 50);
        });
    }
});


$(document).on("click", ".delete-branch", function () {
    this.closest(".branch-block").remove();
        reindexBranches();


});
function reindexBranches() {
    const wrapper = document.getElementById("branchesWrapper");
    const branches = wrapper.querySelectorAll(".branch-block");

    branches.forEach((block, index) => {

        // Update label
        const title = block.querySelector("label.text-gray-600.font-medium");
        if (title) title.textContent = `Branch ${index + 1}`;

        // Update input/select names & ids
        block.querySelectorAll("input, select").forEach(input => {

            if (input.name) {
                const match = input.name.match(/\[([^\]]+)\]$/);
                if (match) {
                    const field = match[1]; // ✅ correct field
                    input.name = `branches[${index}][${field}]`;
                    
                    // Special handling for country code selects
                    if (input.classList.contains('country-code-select')) {
                        // Update onchange handler with new index
                        if (field === 'mobile_country_code') {
                            input.setAttribute('onchange', `updateCode(this, 'mobile_code_${index}')`);
                            // Update the code display div ID
                            const codeDiv = input.parentElement.querySelector('[id^="mobile_code_"]');
                            if (codeDiv) {
                                codeDiv.id = `mobile_code_${index}`;
                            }
                        } else if (field === 'landline_country_code') {
                            input.setAttribute('onchange', `updateCode(this, 'landline_code_${index}')`);
                            // Update the code display div ID
                            const codeDiv = input.parentElement.querySelector('[id^="landline_code_"]');
                            if (codeDiv) {
                                codeDiv.id = `landline_code_${index}`;
                            }
                        }
                    }
                }
            }

            if (input.id) {
                const parts = input.id.split("_");
                input.id = `${parts[0]}_${index}`;
            }
        });

        // Toggle delete button
        const del = block.querySelector(".delete-branch");
        if (del) {
            del.style.display = (branches.length > 1) ? "inline-flex" : "none";
        }
    });
}
