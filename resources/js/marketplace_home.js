// Marketplace Home Page JavaScript
// This file contains all slider functionality for the marketplace home page

// Marketplace Dropdown Toggle 
$(document).ready(function() {

    const marketplaceHome = document.getElementById('marketplace-home');
    if (marketplaceHome) return;
    // Marketplace Dropdown Toggle
    $(document).on("click", ".marketplace-chevron, .marketplace-dropdown-chevron-btn", function (e) {
        e.preventDefault();
        e.stopPropagation();
        
        const $chevron = $(this);
        const $wrapper = $chevron.closest(".marketplace-dropdown-wrapper");
        const isOpen = $wrapper.hasClass("open");
        
        // Toggle dropdown open state
        if (isOpen) {
            $wrapper.removeClass("open");
            $wrapper.find(".marketplace-dropdown-toggle").attr("aria-expanded", "false");
        } else {
            $wrapper.addClass("open");
            $wrapper.find(".marketplace-dropdown-toggle").attr("aria-expanded", "true");
        }
    });

    // Close mobile menu when clicking a submenu link (but not the dropdown toggle)
    $(document).on("click", "#mobile-menu .marketplace-submenu a, #sidebar-menu .marketplace-submenu a", function (e) {
        e.stopPropagation();
        $("#mobile-menu-toggle").click();
    });
});

(function() {
    'use strict';
     const marketplaceHome = document.getElementById('marketplace-home');
     if (marketplaceHome) return;

    // Get asset path from data attribute or use default
    const getAssetPath = function(path) {
        const assetBase = document.body.getAttribute('data-asset-base') || '';
        // Ensure path starts with / and assetBase doesn't end with /
        const cleanBase = assetBase.endsWith('/') ? assetBase.slice(0, -1) : assetBase;
        const cleanPath = path.startsWith('/') ? path : '/' + path;
        return cleanBase + cleanPath;
    };

    // ismobile
    function isMobileView() {
        return window.innerWidth <= 768;
    }
    
    // Function to check if slide is fully in viewport and apply opacity
    function updateSlideOpacity(slider) {
        const $slider = $(slider);
        const $slides = $slider.find('.slick-slide');
        const sliderContainer = $slider.closest('.swiper-container').length ? $slider.closest('.swiper-container')[0] : $slider[0];
        
        if (!sliderContainer) return;
        
        const containerRect = sliderContainer.getBoundingClientRect();
        const containerLeft = containerRect.left;
        const containerRight = containerRect.right;

        if (isMobileView()) {
            $slides.css('opacity', '1');
            console.log("opacity11")
            return;
        }
        
        $slides.each(function() {
            const $slide = $(this);
            const slideRect = this.getBoundingClientRect();
            const slideLeft = slideRect.left;
            const slideRight = slideRect.right;
            
            // Check if slide is fully within the container's visible area
            const isFullyVisible = slideLeft >= containerLeft && slideRight <= containerRight;
            
            if (isFullyVisible) {
                $slide.css('opacity', '1');
            } else {
                $slide.css('opacity', '0.6');
            }
        });
    }
    
    // Function to initialize opacity for all sliders
    function initializeSliderOpacity() {
        $('.category-slider, .artwork-slider, .exhibition-slider, .artist-slider, .gallery-slider').each(function() {
            updateSlideOpacity(this);
        });
    }
    
    // Initialize when DOM is ready
    $(document).ready(function() {
        // Get custom arrow asset path
        const customArrowPath = getAssetPath('/assets/svg/customArrow.svg');
        const heroArrowNext = getAssetPath('/assets/svg/hero-arrow-next.svg');
        // const heroArrowPrev = getAssetPath('/assets/svg/hero-arrow-prev.svg');
        
        // Hero Slider - Auto rotation with 8 seconds
        const heroSlider = $('.hero-slider');
        let progressTimeout;
        
        heroSlider && heroSlider.slick({
            autoplay: true,
            autoplaySpeed: 7000,
            fade: true,
            cssEase: 'linear',
            arrows: true,
            prevArrow: '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>',
            nextArrow: '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>',
            dots: false,
            infinite: true,
            speed: 500,
            pauseOnHover: false,
            pauseOnFocus: false
        });

        // Ensure arrows are visible and properly positioned
        setTimeout(function() {
            const prevBtn = $('.hero-right .slick-prev');
            const nextBtn = $('.hero-right .slick-next');
            
            if (prevBtn.length === 0 || nextBtn.length === 0) {
                // Create buttons if they don't exist
                const heroRight = $('.hero-right');
                if (prevBtn.length === 0) {
                    heroRight.append('<button type="button" class="slick-prev hero-arrow hero-arrow-prev"><img src="' + heroArrowNext + '" alt="prev-arrow" /></button>');
                }
                if (nextBtn.length === 0) {
                    heroRight.append('<button type="button" class="slick-next hero-arrow hero-arrow-next"><img src="' + heroArrowNext + '" alt="prev-arrow" /></button>');
                }
                
                // Reinitialize or bind events
                $('.hero-right .slick-prev').on('click', function() {
                    heroSlider && heroSlider.slick('slickPrev');
                });
                $('.hero-right .slick-next').on('click', function() {
                    heroSlider && heroSlider.slick('slickNext');
                });
            }
            
            // Ensure visibility
            $('.hero-right .slick-prev, .hero-right .slick-next').css({
                'display': 'flex',
                'opacity': '1',
                'visibility': 'visible',
                'position': 'absolute',
                'z-index': '30'
            });
        }, 200);

        // Custom pagination dots with progress
        function updateHeroDots(currentSlide) {
            $('.hero-dot').removeClass('active');
            const slideIndex = currentSlide % 3; 
            const activeDot = $('.hero-dot[data-slide="' + slideIndex + '"]');
            
            // 1. Safety Check: If the dot doesn't exist, stop here
            if (activeDot.length === 0) return;

            activeDot.addClass('active');

            const progressBar = activeDot.find('.hero-dot-progress');

            // 2. Safety Check: If the progress bar HTML is missing, stop here
            if (progressBar.length === 0) return;

            // Reset and start progress animation
            progressBar.css('transition', 'none');
            progressBar.css('width', '0%');
            // Force reflow (Now safe because we checked existence)
            void progressBar[0].offsetHeight;

            // Start animation
            setTimeout(function() {
                progressBar.css('transition', 'width 8s linear');
                progressBar.css('width', '100%');
            }, 10);
        }

        $('.hero-dot').on('click', function() {
            const slideIndex = $(this).data('slide');
            heroSlider && heroSlider.slick('slickGoTo', slideIndex);
        });

        // Initialize progress on first slide
        setTimeout(function() {
            updateHeroDots(0);
        }, 100);

        // Update on slide change
        heroSlider && heroSlider.on('beforeChange', function(event, slick, currentSlide, nextSlide) {
            $('.hero-dot-progress').css('transition', 'none');
            $('.hero-dot-progress').css('width', '0%');
            updateHeroDots(nextSlide);
        });

        heroSlider && heroSlider.on('afterChange', function(event, slick, currentSlide) {
            updateHeroDots(currentSlide);
        });

        // Handle manual navigation (arrows)
        heroSlider && heroSlider.on('setPosition', function() {
            const currentSlide = heroSlider.slick('slickCurrentSlide');
            updateHeroDots(currentSlide);
        });

        // Category Slider
        $('.category-slider').slick({
            slidesToShow: 5,
            slidesToScroll: 1,
            arrows: true,
            prevArrow: '<button type="button" class="slick-prev slider-arrow slider-arrow-prev"><img src="' + customArrowPath + '" alt="prev-arrow" /></button>',
            nextArrow: '<button type="button" class="slick-next slider-arrow slider-arrow-next"><img src="' + customArrowPath + '" alt="next-arrow" /></button>',
            dots: false,
            infinite: true,
            variableWidth: true,
            responsive: [
                {
                    breakpoint: 1024,
                    settings: {
                        slidesToShow: 4
                    }
                },
                {
                    breakpoint: 768,
                    settings: {
                        slidesToShow: 3
                    }
                },
                {
                    breakpoint: 640,
                    settings: {
                        slidesToShow: 2
                    }
                }
            ]
        }).on('afterChange', function(event, slick, currentSlide) {
            updateSlideOpacity(this);
        }).on('setPosition', function() {
            updateSlideOpacity(this);
        });

        // Artwork Slider (Curator's Picks & New on Adai)
        $('.artwork-slider').slick({
            slidesToShow: 4,
            slidesToScroll: 1,
            arrows: true,
            prevArrow: '<button type="button" class="slick-prev slider-arrow slider-arrow-prev"><img src="' + customArrowPath + '" alt="prev-arrow" /></button>',
            nextArrow: '<button type="button" class="slick-next slider-arrow slider-arrow-next"><img src="' + customArrowPath + '" alt="next-arrow" /></button>',
            dots: false,
            infinite: true,
            variableWidth: true,
            responsive: [
                {
                    breakpoint: 1024,
                    settings: {
                        slidesToShow: 3
                    }
                },
                {
                    breakpoint: 768,
                    settings: {
                        slidesToShow: 2
                    }
                },
                {
                    breakpoint: 640,
                    settings: {
                        slidesToShow: 1
                    }
                }
            ]
        }).on('afterChange', function(event, slick, currentSlide) {
            updateSlideOpacity(this);
        }).on('setPosition', function() {
            updateSlideOpacity(this);
        });

        // Exhibition Slider
        $('.exhibition-slider').slick({
            slidesToShow: 3,
            slidesToScroll: 1,
            arrows: true,
            prevArrow: '<button type="button" class="slick-prev slider-arrow slider-arrow-prev"><img src="' + customArrowPath + '" alt="prev-arrow" /></button>',
            nextArrow: '<button type="button" class="slick-next slider-arrow slider-arrow-next"><img src="' + customArrowPath + '" alt="next-arrow" /></button>',
            dots: false,
            infinite: true,
            variableWidth: true,
            responsive: [
                {
                    breakpoint: 1024,
                    settings: {
                        slidesToShow: 2
                    }
                },
                {
                    breakpoint: 640,
                    settings: {
                        slidesToShow: 1
                    }
                }
            ]
        }).on('afterChange', function(event, slick, currentSlide) {
            updateSlideOpacity(this);
        }).on('setPosition', function() {
            updateSlideOpacity(this);
        });

        // Artist Slider
        $('.artist-slider').slick({
            slidesToShow: 4,
            slidesToScroll: 1,
            arrows: true,
            prevArrow: '<button type="button" class="slick-prev slider-arrow slider-arrow-prev"><img src="' + customArrowPath + '" alt="prev-arrow" /></button>',
            nextArrow: '<button type="button" class="slick-next slider-arrow slider-arrow-next"><img src="' + customArrowPath + '" alt="next-arrow" /></button>',
            dots: false,
            infinite: true,
            variableWidth: true,
            responsive: [
                {
                    breakpoint: 1024,
                    settings: {
                        slidesToShow: 3
                    }
                },
                {
                    breakpoint: 768,
                    settings: {
                        slidesToShow: 2
                    }
                },
                {
                    breakpoint: 640,
                    settings: {
                        slidesToShow: 1
                    }
                }
            ]
        }).on('afterChange', function(event, slick, currentSlide) {
            updateSlideOpacity(this);
        }).on('setPosition', function() {
            updateSlideOpacity(this);
        });

        // Gallery Slider
        $('.gallery-slider').slick({
            slidesToShow: 4,
            slidesToScroll: 1,
            arrows: true,
            prevArrow: '<button type="button" class="slick-prev slider-arrow slider-arrow-prev"><img src="' + customArrowPath + '" alt="prev-arrow" /></button>',
            nextArrow: '<button type="button" class="slick-next slider-arrow slider-arrow-next"><img src="' + customArrowPath + '" alt="next-arrow" /></button>',
            dots: false,
            infinite: true,
            variableWidth: true,
            responsive: [
                {
                    breakpoint: 1024,
                    settings: {
                        slidesToShow: 3
                    }
                },
                {
                    breakpoint: 768,
                    settings: {
                        slidesToShow: 2
                    }
                },
                {
                    breakpoint: 640,
                    settings: {
                        slidesToShow: 1
                    }
                }
            ]
        }).on('afterChange', function(event, slick, currentSlide) {
            updateSlideOpacity(this);
        }).on('setPosition', function() {
            updateSlideOpacity(this);
        });
        
        // Initialize opacity for all sliders after they're initialized
        setTimeout(function() {
            initializeSliderOpacity();
        }, 300);
        
        // Update opacity on window resize
        $(window).on('resize', function() {
            initializeSliderOpacity();
        });
    });

    $(window).on('resize orientationchange', function () {
        $('.slick-slide').css('opacity', '1');

        if (!isMobileView()) {
            $('.your-slider-class').each(function () {
                updateSlideOpacity(this);
            });
        }
    });
})();

