if($("#marketplace-home").length > 0 ){
    document.addEventListener('DOMContentLoaded', function () {
        const marketplaceHome = document.getElementById('marketplace-home');
        if (!marketplaceHome) return;

        const getAssetPath = function(path) {
            const assetBase = document.body.getAttribute('data-asset-base') || '';
            // Ensure path starts with / and assetBase doesn't end with /
            const cleanBase = assetBase.endsWith('/') ? assetBase.slice(0, -1) : assetBase;
            const cleanPath = path.startsWith('/') ? path : '/' + path;
            return cleanBase + cleanPath;
        };
        // const arrowPath = getAssetPath('/assets/svg/customArrow.svg');
        const arrowPath = getAssetPath('/assets/svg/icons/arrow-right.svg');
        const heroArrowNext = getAssetPath('/assets/svg/hero-arrow-next.svg');
        /* ---------------- COMMON HELPERS ---------------- */

        function updateSlideOpacity(slider) {
            const $slider = $(slider);
            const $slides = $slider.find('.slick-slide');
            const container = $slider.closest('.swiper-container')[0] || slider;
            if (!container) return;

            const { left, right } = container.getBoundingClientRect();

            $slides.each(function () {
                const rect = this.getBoundingClientRect();
                $(this).css('opacity',
                    rect.left >= left && rect.right <= right ? '1' : '0.6'
                );
            });
        }

        function bindOpacityEvents($slider) {
            $slider.on('afterChange setPosition', function () {
                updateSlideOpacity(this);
            });
        }

        function safeSlickInit($slider, options) {
            if (!$slider.length || $slider.hasClass('slick-initialized')) return;
            $slider.slick(options);
            bindOpacityEvents($slider);
            updateSlideOpacity($slider[0]);
        }

        /* ---------------- STATIC SLIDERS ---------------- */

            // Hero Slider - Auto rotation with 8 seconds
        const heroSlider = $('.hero-slider');
        let progressTimeout;
        let progressAnimationTimeout;

        heroSlider.on('init', function () {
            heroSlider.addClass('slick-initialized');
            $('.hero-arrows').addClass('slick-initialized');
            $('.hero-slider-dots').addClass('slick-initialized');
            // Set opacity for slides - only active slide visible
            $('.hero-slider .slick-slide').css('opacity', '0');
            $('.hero-slider .slick-slide.slick-active').css('opacity', '1');
          });

        heroSlider.slick({
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


        let resizeTimer;

        $(window).on('resize orientationchange', function () {
            clearTimeout(resizeTimer);
            
            // Clear any existing progress bar animations
            if (progressAnimationTimeout) {
                clearTimeout(progressAnimationTimeout);
                progressAnimationTimeout = null;
            }
            // Stop all progress bar animations
            $('.hero-dot-progress').css('transition', 'none');
            $('.hero-dot-progress').css('width', '0%');

            resizeTimer = setTimeout(() => {
                if (heroSlider.hasClass('slick-initialized')) {
                    // Clear progress animations before unslicking
                    if (progressAnimationTimeout) {
                        clearTimeout(progressAnimationTimeout);
                        progressAnimationTimeout = null;
                    }
                    $('.hero-dot-progress').css('transition', 'none');
                    $('.hero-dot-progress').css('width', '0%');
                    
                    heroSlider.slick('unslick');
                }

                heroSlider.slick({
                autoplay: true,
                autoplaySpeed: 7000,
                fade: true,
                cssEase: 'linear',
                arrows: true,
                dots: false,
                infinite: true,
                speed: 500,
                pauseOnHover: false,
                pauseOnFocus: false
                });
                
                // Wait for slider to initialize before starting progress
                heroSlider.one('init', function() {
                    // Set opacity for slides
                    $('.hero-slider .slick-slide').css('opacity', '0');
                    $('.hero-slider .slick-slide.slick-active').css('opacity', '1');
                    
                    setTimeout(function() {
                        const currentSlide = heroSlider.slick('slickCurrentSlide');
                        updateHeroDots(currentSlide);
                    }, 100);
                });
            }, 200);
        });





        // Ensure arrows are visible and properly positioned
        setTimeout(function() {
            const prevBtn = $('.hero-arrows .slick-prev');
            const nextBtn = $('.hero-arrows .slick-next');
            
            if (prevBtn.length != 0 || nextBtn.length != 0) {
                // Create buttons if they don't exist
                // const heroRight = $('.hero-right');
                // if (prevBtn.length === 0) {
                //     heroRight.append('<button type="button" class="slick-prev hero-arrow hero-arrow-prev"><img src="' + heroArrowNext + '" alt="prev-arrow" /></button>');
                // }
                // if (nextBtn.length === 0) {
                //     heroRight.append('<button type="button" class="slick-next hero-arrow hero-arrow-next"><img src="' + heroArrowNext + '" alt="prev-arrow" /></button>');
                // }
                
                // Reinitialize or bind events
                $('.hero-arrows .slick-prev').on('click', function() {
                    heroSlider.slick('slickPrev');
                });
                $('.hero-arrows .slick-next').on('click', function() {
                    heroSlider.slick('slickNext');
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
            // Clear any existing progress animation timeout
            if (progressAnimationTimeout) {
                clearTimeout(progressAnimationTimeout);
                progressAnimationTimeout = null;
            }
            
            // Stop all progress bar animations first
            $('.hero-dot-progress').css('transition', 'none');
            $('.hero-dot-progress').css('width', '0%');
            
            $('.hero-dot').removeClass('active');
            const slideIndex = currentSlide % 3; // Handle infinite loop
            const activeDot = $('.hero-dot[data-slide="' + slideIndex + '"]');
            activeDot.addClass('active');
            
            // Reset and start progress animation
            const progressBar = activeDot.find('.hero-dot-progress');
            if (progressBar.length === 0) return;
            
            progressBar.css('transition', 'none');
            progressBar.css('width', '0%');
            
            // Force reflow
            progressBar[0].offsetHeight;
            
            // Start animation
            progressAnimationTimeout = setTimeout(function() {
                progressBar.css('transition', 'width 8s linear');
                progressBar.css('width', '100%');
                progressAnimationTimeout = null;
            }, 10);
        }

        $('.hero-dot').on('click', function() {
            const slideIndex = $(this).data('slide');
            heroSlider.slick('slickGoTo', slideIndex);
        });

        // Initialize progress on first slide
        setTimeout(function() {
            updateHeroDots(0);
        }, 100);

        // Update on slide change
        heroSlider.on('beforeChange', function(event, slick, currentSlide, nextSlide) {
            $('.hero-dot-progress').css('transition', 'none');
            $('.hero-dot-progress').css('width', '0%');
            // Set all slides to opacity 0 except the next one
            $('.hero-slider .slick-slide').css('opacity', '0');
            // updateHeroDots(nextSlide);
        });

        heroSlider.on('afterChange', function(event, slick, currentSlide) {
            // Ensure only active slide has opacity 1
            $('.hero-slider .slick-slide').css('opacity', '0');
            $('.hero-slider .slick-slide.slick-active').css('opacity', '1');
            // updateHeroDots(currentSlide);
        });

        // Handle manual navigation (arrows)
        heroSlider.on('setPosition', function() {
            const currentSlide = heroSlider.slick('slickCurrentSlide');
            // Ensure only active slide has opacity 1
            $('.hero-slider .slick-slide').css('opacity', '0');
            $('.hero-slider .slick-slide.slick-active').css('opacity', '1');
            updateHeroDots(currentSlide);
        });
        

        function initCategorySlider() {
            safeSlickInit($('.category-slider'), {
                slidesToShow: 5,
                arrows: true,
                variableWidth: true,
                prevArrow: `<button class="slick-prev slider-arrow"><img src="${arrowPath}"></button>`,
                nextArrow: `<button class="slick-next slider-arrow"><img src="${arrowPath}"></button>`,
                responsive: [
                    { breakpoint: 1024, settings: { slidesToShow: 4 }},
                    { breakpoint: 768, settings: { slidesToShow: 3 }},
                    { breakpoint: 640, settings: { slidesToShow: 2 }}
                ]
            });
        }

        function initExhibitionSlider() {
            safeSlickInit($('.exhibition-slider'), {
                slidesToShow: 3,
                arrows: true,
                variableWidth: true,
                prevArrow: `<button class="slick-prev slider-arrow"><img src="${arrowPath}"></button>`,
                nextArrow: `<button class="slick-next slider-arrow"><img src="${arrowPath}"></button>`
            });
        }

        /* ---------------- AJAX SLIDERS ---------------- */

        function initArtworkSlider() {
            safeSlickInit($('.artwork-slider'), {
                slidesToShow: 4,
                arrows: true,
                variableWidth: true,
                prevArrow: `<button class="slick-prev slider-arrow"><img src="${arrowPath}"></button>`,
                nextArrow: `<button class="slick-next slider-arrow"><img src="${arrowPath}"></button>`
            });
        }

        function initCuratorArtworkSlider() {
            safeSlickInit($('.curator-artwork-slider'), {
                slidesToShow: 4,
                arrows: true,
                variableWidth: true,
                prevArrow: `<button class="slick-prev slider-arrow"><img src="${arrowPath}"></button>`,
                nextArrow: `<button class="slick-next slider-arrow"><img src="${arrowPath}"></button>`
            });
        }

        function initArtistSlider() {
            safeSlickInit($('.artist-slider'), {
                slidesToShow: 4,
                arrows: true,
                variableWidth: true,
                prevArrow: `<button class="slick-prev slider-arrow"><img src="${arrowPath}"></button>`,
                nextArrow: `<button class="slick-next slider-arrow"><img src="${arrowPath}"></button>`
            });
        }

        function initGallerySlider() {
            safeSlickInit($('.gallery-slider'), {
                slidesToShow: 4,
                arrows: true,
                variableWidth: true,
                prevArrow: `<button class="slick-prev slider-arrow"><img src="${arrowPath}"></button>`,
                nextArrow: `<button class="slick-next slider-arrow"><img src="${arrowPath}"></button>`
            });
        }

        /* ---------------- AJAX LOADER ---------------- */

        function loadSection(id, initFn, errorText) {
            const section = document.getElementById(id);
            if (!section) return;
            const target = document.getElementById(id);
            if (!target) return;
            fetch(section.dataset.url)
                .then(response => {
                    if (response.status === 204) {
                        //console.log(target.closest('.section-container'));
                        // 🔥 Remove entire section wrapper
                        target.closest('.section-container')?.remove();
                        return null;
                    }
                    if (!response.ok) throw new Error('HTTP error');
                    return response.text();
                })
                .then(html => {
                    section.innerHTML = html;
                    initFn();
                })
                .catch(() => {
                   section.innerHTML = `
                        <div class="text-center py-10">
                            <p class="mb-3">${errorText}</p>
                            <button 
                                class="retry-btn" 
                                data-section="${id}">
                                Retry
                            </button>
                        </div>
                    `;
                });
        }
        document.addEventListener('click', function (e) {
            const btn = e.target.closest('.retry-btn');
            if (!btn) return;

            const sectionId = btn.dataset.section;
            if (!sectionId) return;

            const section = document.getElementById(sectionId);
            if (!section) return;

            section.innerHTML = `<p class="text-center py-10">Loading…</p>`;

            // Retry only THIS section
            if (sectionId === 'curators-picks') {
                loadSection('curators-picks', initCuratorArtworkSlider, 'Can’t load artworks.');
            }
            if (sectionId === 'new-on-adai') {
                loadSection('new-on-adai', initArtworkSlider, 'Can’t load artworks.');
            }
            if (sectionId === 'artists-section') {
                loadSection('artists-section', initArtistSlider, 'Can’t load artists.');
            }
            if (sectionId === 'galleries-section') {
                loadSection('galleries-section', initGallerySlider, 'Can’t load galleries right now.');
            }
        });

        /* ---------------- INIT FLOW ---------------- */

        // Static (already in DOM)
        // initHeroSlider();
        initCategorySlider();
        initExhibitionSlider();

        // AJAX sections
        loadSection('curators-picks', initCuratorArtworkSlider, 'Can’t load artworks.');
        loadSection('new-on-adai', initArtworkSlider, 'Can’t load artworks.');
        loadSection('artists-section', initArtistSlider, 'Can’t load artists.');
        loadSection('galleries-section', initGallerySlider, 'Can’t load galleries right now.');

        // Resize fix
        let isMobileView = window.innerWidth < 768;

        window.addEventListener('resize', () => {
            $('.slick-initialized').each(function () {
                updateSlideOpacity(this);
                const nowMobile = window.innerWidth < 768;

                if (nowMobile !== isMobileView) {
                    isMobileView = nowMobile;

                    // Reload galleries section only
                    loadSection(
                        'galleries-section',
                        initGallerySlider,
                        'Can’t load galleries right now.'
                    );
                }
            });
        });

        /* ---------------- SEO-FRIENDLY URL HELPERS ---------------- */
        // Convert style name to SEO-friendly slug
        function styleToSlug(styleName) {
            const slugMap = {
                'Arabic Calligraphy (Modern Or Traditional)': 'arabic-calligraphy',
                'Miniature Art': 'miniature-art',
                'Ornamentation': 'ornamentation',
                'Figurative': 'figurative',
                'Abstract': 'abstract',
                'Orientalist Art': 'orientalist-art',
                'Portrait': 'portrait',
                'Landscape': 'landscape',
                'Modern': 'modern',
                'Contemporary': 'contemporary',
                'Pop Art': 'pop-art',
                'Photography': 'photography',
                'Digital Art / New Media': 'digital-art',
            };
            
            return slugMap[styleName] || styleName.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/^-+|-+$/g, '');
        }

        /* ---------------- HERO BUTTON CLICKS ---------------- */
        // Handle "Discover Now" button clicks
        $(document).on('click', '.hero-button', function(e) {
            e.preventDefault();
            const style = $(this).data('style');
            if (style) {
                // Navigate to artwork listing with SEO-friendly style slug
                const styleSlug = styleToSlug(style);
                const artworksUrl = '/artworks-list?style=' + encodeURIComponent(styleSlug);
                window.location.href = artworksUrl;
            } else {
                // Fallback: navigate to artwork listing without filter
                window.location.href = '/artworks-list';
            }
        });

        /* ---------------- CATEGORY CARD CLICKS ---------------- */
        // Handle category card clicks - navigate to artwork listing with style or medium filter
        // Sculpture uses medium filter, all other categories use style filter
        $(document).on('click', '.category-card', function(e) {
            e.preventDefault();
            // Use attr() instead of data() to get exact attribute value
            // jQuery's data() converts kebab-case to camelCase, so data-category-medium becomes categoryMedium
            const $card = $(this);
            const styleSlug = $card.attr('data-category-style-slug');
            const mediumName = $card.attr('data-category-medium');
            const categoryTitle = $card.find('.category-card-title').text().trim();
            // For Sculpture, use medium filter instead of style
            // Check if mediumName exists and is not empty
            if (mediumName !== undefined && mediumName !== null && String(mediumName).trim() !== '') {
                // Use URLSearchParams to properly encode the URL
                // This ensures proper URL encoding that both browser and Laravel can parse
                const params = new URLSearchParams();
                params.append('filterState[medium][0]', String(mediumName).trim());
                params.append('fromHome', '1'); // Add fromHome parameter to indicate coming from home page
                const artworksUrl = '/artworks-list?' + params.toString();
                window.location.href = artworksUrl;
                return;
            }
            
            // If category has a style slug, navigate to listing with SEO-friendly style slug
            if (styleSlug !== undefined && styleSlug !== null && String(styleSlug).trim() !== '') {
                const params = new URLSearchParams();
                params.append('style', String(styleSlug).trim());
                params.append('fromHome', '1'); // Add fromHome parameter to indicate coming from home page
                const artworksUrl = '/artworks-list?' + params.toString();
                window.location.href = artworksUrl;
                return;
            }
            
            // Fallback: navigate to artwork listing without filter
            window.location.href = '/artworks-list';
        });
    });
}
