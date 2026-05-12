/**
 * Marketplace Animation Script
 * Slider Animations for Category, Artwork, Exhibition, Artist, and Gallery sliders
 */

(function ($) {
    'use strict';

    // Strict check for GSAP and ScrollTrigger
    if (typeof gsap === 'undefined' || typeof ScrollTrigger === 'undefined') {
        console.warn('Market Animation: GSAP or ScrollTrigger not loaded');
        return;
    }

    // Register ScrollTrigger plugin
    gsap.registerPlugin(ScrollTrigger);

    function animateStaggeredChildrenOnScroll(
        parentSelector,
        childSelector,
        options = {}
    ) {
        const {
            y = 0,
            scaleFrom = 1,
            scaleTo = 1,
            opacity = 1,
            duration = 0.3,
            stagger = 0.15,
            ease = "easeInOut",
            start = "top 99.99%",
            delay = 0,
        } = options;
        
        // Check if parent element exists
        const parentElement = document.querySelector(parentSelector);
        //console.log('parentElement:::', parentElement);
        if (!parentElement) {
            return; // Exit early if parent doesn't exist
        }
        
        // Function to actually run the animation
        function runAnimation() {
            // Get children elements
            const children = gsap.utils.toArray(childSelector);
            //console.log('runAnimation - children found:', children.length, 'for selector:', childSelector);
            if (children.length === 0) {
                console.warn('No children found for selector:', childSelector);
                return; // Exit early if no children found
            }
            
            // Check if already animated (prevent duplicate triggers)
            if (parentElement.dataset.animated === 'true') {
                console.log('Already animated, skipping:', parentSelector);
                return;
            }
            
            // Mark as animated
            parentElement.dataset.animated = 'true';
            
            console.log('Creating ScrollTrigger for:', parentSelector);
            let tl = gsap.timeline({
                scrollTrigger: {
                    trigger: parentElement,
                    start: start,
                    once: true
                },
            });
            
            children.forEach(function (child, i) {
                tl.fromTo(
                    child,
                    {
                        opacity: 0,
                        scale: scaleFrom,
                        y: y,
                    },
                    {
                        opacity: opacity,
                        scale: scaleTo,
                        y: 0,
                        duration: duration,
                        ease: ease,
                    },
                    delay + i * stagger
                );
            });
        }
        
        // Check if this is a Slick slider
        const isSlickSlider = typeof $ !== 'undefined' && $.fn.slick && $(parentSelector).hasClass('slick-initialized');
        
        if (isSlickSlider) {
            // This is a Slick slider, wait for initialization
            const $parent = $(parentSelector);
            if ($parent.length) {
                if ($parent.hasClass('slick-initialized')) {
                    // Slick is already initialized, run animation
                    runAnimation();
                } else {
                    // Wait for Slick to initialize
                    $parent.on('init', function() {
                        runAnimation();
                    });
                    // Also try after a delay in case init event doesn't fire
                    setTimeout(function() {
                        if ($parent.hasClass('slick-initialized') && parentElement.dataset.animated !== 'true') {
                            runAnimation();
                        }
                    }, 500);
                    // Additional fallback with longer delay
                    setTimeout(function() {
                        if ($parent.hasClass('slick-initialized') && parentElement.dataset.animated !== 'true') {
                            runAnimation();
                        }
                    }, 1000);
                }
            }
        } else {
            // Not a Slick slider, run animation directly (services-container, etc.)
            console.log('Not a Slick slider, running animation directly for:', parentSelector);
            // Try immediately
            runAnimation();
            // Also try after a delay in case elements aren't ready
            setTimeout(function() {
                if (parentElement.dataset.animated !== 'true') {
                    runAnimation();
                }
            }, 300);
            // Additional fallback
            setTimeout(function() {
                if (parentElement.dataset.animated !== 'true') {
                    runAnimation();
                }
            }, 800);
        }
    }

    // Wait for DOM to be ready
    document.addEventListener('DOMContentLoaded', function() {
        // Strict check for GSAP availability
        if (typeof gsap === 'undefined') {
            console.error('Market Animation: GSAP not available');
            return;
        }

        //console.log('Market Animation: Initializing all slider animations');

        // ============================================
        // CATEGORY SLIDER ANIMATION
        // ============================================
        animateStaggeredChildrenOnScroll(
            ".category-slider",
            ".category-slider .slick-track > *",
            {
                y: 20,
                scaleFrom: 0,
                scaleTo: 1,
                opacity: 1,
                duration: 0.6,
                stagger: 0.15,
                ease: "easeInOut",
                start: "top 99.99%",
            }
        );

        // ============================================
        // ARTWORK SLIDER ANIMATION (Curator's Picks & New on Adai)
        // ============================================
        animateStaggeredChildrenOnScroll(
            ".artwork-slider",
            ".artwork-slider .slick-track > *",
            {
                y: 20,
                scaleFrom: 0,
                scaleTo: 1,
                opacity: 1,
                duration: 0.6,
                stagger: 0.15,
                ease: "easeInOut",
                start: "top 99.99%",
            }
        );

        // ============================================
        // CURATOR ARTWORK SLIDER ANIMATION
        // ============================================
        animateStaggeredChildrenOnScroll(
            ".curator-artwork-slider",
            ".curator-artwork-slider .slick-track > *",
            {
                y: 20,
                scaleFrom: 0,
                scaleTo: 1,
                opacity: 1,
                duration: 0.6,
                stagger: 0.15,
                ease: "easeInOut",
                start: "top 99.99%",
            }
        );

        // ============================================
        // EXHIBITION SLIDER ANIMATION
        // ============================================
        animateStaggeredChildrenOnScroll(
            ".exhibition-slider",
            ".exhibition-slider .slick-track > *",
            {
                y: 20,
                scaleFrom: 0,
                scaleTo: 1,
                opacity: 1,
                duration: 0.6,
                stagger: 0.15,
                ease: "easeInOut",
                start: "top 99.99%",
            }
        );

        // ============================================
        // ARTIST SLIDER ANIMATION
        // ============================================
        animateStaggeredChildrenOnScroll(
            ".artist-slider",
            ".artist-slider .slick-track > *",
            {
                y: 20,
                scaleFrom: 0,
                scaleTo: 1,
                opacity: 1,
                duration: 0.6,
                stagger: 0.15,
                ease: "easeInOut",
                start: "top 99.99%",
            }
        );

        // ============================================
        // GALLERY SLIDER ANIMATION
        // ============================================
        animateStaggeredChildrenOnScroll(
            ".gallery-slider",
            ".gallery-slider .slick-track > *",
            {
                y: 20,
                scaleFrom: 0,
                scaleTo: 1,
                opacity: 1,
                duration: 0.6,
                stagger: 0.15,
                ease: "easeInOut",
                start: "top 99.99%",
            }
        );

        // ============================================
        // SERVICES CONTAINER ANIMATION
        // ============================================
        function animateStaggeredChildrenOnScroll(
    container,
    children,
    options = {}
) {
    const {
        y = 30,
        scaleFrom = 0.95,
        scaleTo = 1,
        opacity = 1,
        duration = 0.6,
        stagger = 0.15,
        ease = "power2.out",
        start = "top 85%"
    } = options;

    const elements = gsap.utils.toArray(children);

    // Set initial hidden state
    gsap.set(elements, {
        y,
        scale: scaleFrom,
        autoAlpha: 0
    });

    ScrollTrigger.create({
        trigger: container,
        start,
        once: true,
        onEnter: () => {
            gsap.to(elements, {
                y: 0,
                scale: scaleTo,
                autoAlpha: opacity,
                duration,
                stagger,
                ease
            });
        },
        onEnterBack: () => {
            gsap.to(elements, {
                y: 0,
                scale: scaleTo,
                autoAlpha: opacity,
                duration,
                stagger,
                ease
            });
        }
    });
}

// Required for bottom sections
window.addEventListener("load", () => {
    ScrollTrigger.refresh();
});

       /* animateStaggeredChildrenOnScroll(
            ".services-container",
            ".services-container > *",
            {
                y: 0,
                scaleFrom: 0,
                scaleTo: 1,
                opacity: 1,
                duration: 0.6,
                stagger: 0.15,
                ease: "easeInOut",
                start: "top 99.99%",
            }
        );*/

        // ============================================
        // ARTWORKS LISTING PAGE ANIMATIONS
        // ============================================
        
        // Artworks Hero Section
        animateStaggeredChildrenOnScroll(
            ".artworks-hero-section",
            ".artworks-hero-content > *",
            {
                y: 30,
                scaleFrom: 1,
                scaleTo: 1,
                opacity: 1,
                duration: 0.8,
                stagger: 0.2,
                ease: "easeInOut",
                start: "top 99.99%",
            }
        );

        // Artworks Filter Section
        animateStaggeredChildrenOnScroll(
            ".artworks-filter-section",
            ".artworks-filter-button",
            {
                y: 20,
                scaleFrom: 1,
                scaleTo: 1,
                opacity: 1,
                duration: 0.6,
                stagger: 0.1,
                ease: "easeInOut",
                start: "top 99.99%",
            }
        );

        // Artworks Grid Items
        animateStaggeredChildrenOnScroll(
            ".artworks-grid-section",
            ".artwork-item",
            {
                y: 50,
                scaleFrom: 0.9,
                scaleTo: 1,
                opacity: 1,
                duration: 0.6,
                stagger: 0.1,
                ease: "easeInOut",
                start: "top 99.99%",
            }
        );

        // Artworks Pagination
        animateStaggeredChildrenOnScroll(
            ".artworks-pagination-section",
            ".artworks-pagination-container",
            {
                y: 20,
                scaleFrom: 1,
                scaleTo: 1,
                opacity: 1,
                duration: 0.6,
                stagger: 0,
                ease: "easeInOut",
                start: "top 99.99%",
            }
        );

        // ============================================
        // ARTISTS LISTING PAGE ANIMATIONS
        // ============================================
        
        // Artists Grid Items (uses same hero and filter animations as artworks)
        animateStaggeredChildrenOnScroll(
            ".artists-grid-section",
            ".artist-card-wrapper",
            {
                y: 50,
                scaleFrom: 0.9,
                scaleTo: 1,
                opacity: 1,
                duration: 0.6,
                stagger: 0.1,
                ease: "easeInOut",
                start: "top 99.99%",
            }
        );

        // ============================================
        // GALLERIES LISTING PAGE ANIMATIONS
        // ============================================
        
        // Galleries Grid Items (uses same hero and filter animations as artworks)
        animateStaggeredChildrenOnScroll(
            ".artists-grid-section#gallery-grid-section",
            ".gallery-search-card-wrapper",
            {
                y: 50,
                scaleFrom: 0.9,
                scaleTo: 1,
                opacity: 1,
                duration: 0.6,
                stagger: 0.1,
                ease: "easeInOut",
                start: "top 99.99%",
            }
        );

        // ============================================
        // ARTWORK DETAILS PAGE ANIMATIONS (excluding sliders)
        // ============================================
        
        // Breadcrumbs Section
        animateStaggeredChildrenOnScroll(
            ".artwork-details-breadcrumbs",
            ".artwork-breadcrumb-nav",
            {
                y: 20,
                scaleFrom: 1,
                scaleTo: 1,
                opacity: 1,
                duration: 0.6,
                stagger: 0,
                ease: "easeInOut",
                start: "top 99.99%",
            }
        );

        // Main Artwork Details - Image Column
        animateStaggeredChildrenOnScroll(
            ".artwork-details-main",
            ".artwork-details-image-column",
            {
                y: 30,
                scaleFrom: 0.95,
                scaleTo: 1,
                opacity: 1,
                duration: 0.8,
                stagger: 0,
                ease: "easeInOut",
                start: "top 99.99%",
            }
        );

        // Main Artwork Details - Info Column
        animateStaggeredChildrenOnScroll(
            ".artwork-details-main",
            ".artwork-details-info-column",
            {
                y: 30,
                scaleFrom: 1,
                scaleTo: 1,
                opacity: 1,
                duration: 0.8,
                stagger: 0,
                ease: "easeInOut",
                start: "top 99.99%",
            }
        );

        // About Artwork Section
        animateStaggeredChildrenOnScroll(
            ".artwork-about-section",
            ".artwork-about-content, .artwork-specifications",
            {
                y: 30,
                scaleFrom: 1,
                scaleTo: 1,
                opacity: 1,
                duration: 0.8,
                stagger: 0.2,
                ease: "easeInOut",
                start: "top 99.99%",
            }
        );

        // Section Headers (for MORE FROM THIS ARTIST, SIMILAR ARTWORKS, RELATED ARTISTS)
        animateStaggeredChildrenOnScroll(
            ".section-container",
            ".mp-section-header",
            {
                y: 20,
                scaleFrom: 1,
                scaleTo: 1,
                opacity: 1,
                duration: 0.6,
                stagger: 0,
                ease: "easeInOut",
                start: "top 99.99%",
            }
        );

        // ============================================
        // ARTIST DETAILS PAGE ANIMATIONS (top part only, excluding lists)
        // ============================================
        
        // Breadcrumbs Section
        animateStaggeredChildrenOnScroll(
            ".artist-details-breadcrumbs",
            ".artist-breadcrumb-nav",
            {
                y: 20,
                scaleFrom: 1,
                scaleTo: 1,
                opacity: 1,
                duration: 0.6,
                stagger: 0,
                ease: "easeInOut",
                start: "top 99.99%",
            }
        );

        // Artist Details Section - Image Column
        animateStaggeredChildrenOnScroll(
            ".artist-details-section",
            ".artist-details-image-column",
            {
                y: 30,
                scaleFrom: 0.95,
                scaleTo: 1,
                opacity: 1,
                duration: 0.8,
                stagger: 0,
                ease: "easeInOut",
                start: "top 99.99%",
            }
        );

        // Artist Details Section - Info Column
        animateStaggeredChildrenOnScroll(
            ".artist-details-section",
            ".artist-details-info-column",
            {
                y: 30,
                scaleFrom: 1,
                scaleTo: 1,
                opacity: 1,
                duration: 0.8,
                stagger: 0,
                ease: "easeInOut",
                start: "top 99.99%",
            }
        );

        // Artist Info Column Children (header, details, biography)
        animateStaggeredChildrenOnScroll(
            ".artist-details-info-column",
            ".artist-header-row, .artist-details-text, .artist-biography",
            {
                y: 20,
                scaleFrom: 1,
                scaleTo: 1,
                opacity: 1,
                duration: 0.6,
                stagger: 0.15,
                ease: "easeInOut",
                start: "top 99.99%",
            }
        );

        // Tab Navigation Section
        animateStaggeredChildrenOnScroll(
            ".artist-tabs-section",
            ".artist-tab-button, .tab-nav-item",
            {
                y: 20,
                scaleFrom: 1,
                scaleTo: 1,
                opacity: 1,
                duration: 0.6,
                stagger: 0.1,
                ease: "easeInOut",
                start: "top 99.99%",
            }
        );

        // Refresh ScrollTrigger on window load and re-initialize animations
        if (typeof ScrollTrigger !== 'undefined') {
            window.addEventListener('load', function() {
                if (typeof ScrollTrigger !== 'undefined' && ScrollTrigger.refresh) {
                    ScrollTrigger.refresh();
                }
                
                // Re-run animations after everything is loaded (in case Slick initialized late)
                setTimeout(function() {
                    // Re-run artwork slider animation
                    const artworkSlider = document.querySelector(".artwork-slider");
                    if (artworkSlider && artworkSlider.dataset.animated !== 'true') {
                        animateStaggeredChildrenOnScroll(
                            ".artwork-slider",
                            ".artwork-slider .slick-track > *",
                            {
                                y: 20,
                                scaleFrom: 0,
                                scaleTo: 1,
                                opacity: 1,
                                duration: 0.6,
                                stagger: 0.15,
                                ease: "easeInOut",
                                start: "top 99.99%",
                            }
                        );
                    }
                    
                    // Re-run artist slider animation
                    const artistSlider = document.querySelector(".artist-slider");
                    if (artistSlider && artistSlider.dataset.animated !== 'true') {
                        animateStaggeredChildrenOnScroll(
                            ".artist-slider",
                            ".artist-slider .slick-track > *",
                            {
                                y: 20,
                                scaleFrom: 0,
                                scaleTo: 1,
                                opacity: 1,
                                duration: 0.6,
                                stagger: 0.15,
                                ease: "easeInOut",
                                start: "top 99.99%",
                            }
                        );
                    }
                    
                    // Re-run gallery slider animation
                    const gallerySlider = document.querySelector(".gallery-slider");
                    if (gallerySlider && gallerySlider.dataset.animated !== 'true') {
                        animateStaggeredChildrenOnScroll(
                            ".gallery-slider",
                            ".gallery-slider .slick-track > *",
                            {
                                y: 20,
                                scaleFrom: 0,
                                scaleTo: 1,
                                opacity: 1,
                                duration: 0.6,
                                stagger: 0.15,
                                ease: "easeInOut",
                                start: "top 99.99%",
                            }
                        );
                    }
                    
                    // Re-run services container animation
                    const servicesContainer = document.querySelector(".services-container");
                    if (servicesContainer && servicesContainer.dataset.animated !== 'true') {
                        animateStaggeredChildrenOnScroll(
                            ".services-container",
                            ".services-container > *",
                            {
                                y: 0,
                                scaleFrom: 0,
                                scaleTo: 1,
                                opacity: 1,
                                duration: 0.6,
                                stagger: 0.15,
                                ease: "easeInOut",
                                start: "top 99.99%",
                            }
                        );
                    }
                    
                    // Re-run artworks grid items animation (in case loaded via AJAX)
                    const artworksGrid = document.querySelector(".artworks-grid-section");
                    if (artworksGrid && artworksGrid.dataset.animated !== 'true') {
                        animateStaggeredChildrenOnScroll(
                            ".artworks-grid-section",
                            ".artwork-item",
                            {
                                y: 50,
                                scaleFrom: 0.9,
                                scaleTo: 1,
                                opacity: 1,
                                duration: 0.6,
                                stagger: 0.1,
                                ease: "easeInOut",
                                start: "top 99.99%",
                            }
                        );
                    }
                    
                    // Re-run artists grid items animation (in case loaded via AJAX)
                    const artistsGrid = document.querySelector(".artists-grid-section");
                    if (artistsGrid && artistsGrid.dataset.animated !== 'true' && !artistsGrid.id) {
                        animateStaggeredChildrenOnScroll(
                            ".artists-grid-section",
                            ".artist-card-wrapper",
                            {
                                y: 50,
                                scaleFrom: 0.9,
                                scaleTo: 1,
                                opacity: 1,
                                duration: 0.6,
                                stagger: 0.1,
                                ease: "easeInOut",
                                start: "top 99.99%",
                            }
                        );
                    }
                    
                    // Re-run galleries grid items animation (in case loaded via AJAX)
                    const galleriesGrid = document.querySelector(".artists-grid-section#gallery-grid-section");
                    if (galleriesGrid && galleriesGrid.dataset.animated !== 'true') {
                        animateStaggeredChildrenOnScroll(
                            ".artists-grid-section#gallery-grid-section",
                            ".gallery-search-card-wrapper",
                            {
                                y: 50,
                                scaleFrom: 0.9,
                                scaleTo: 1,
                                opacity: 1,
                                duration: 0.6,
                                stagger: 0.1,
                                ease: "easeInOut",
                                start: "top 99.99%",
                            }
                        );
                    }
                }, 500);
            });
        }

        console.log('Market Animation: All slider animations initialized');
    });

})(jQuery);
