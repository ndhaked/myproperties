import {  handleFilterBarPosition } from './utils/filter-ui';
if($("#front_artwork_details_page").length > 0 ) {
    $(document).ready(function() {
        // Toggle collapsible sections
        $('.artwork-info-toggle').on('click', function () {
            const $toggle = $(this);
            const $section = $toggle.closest('.artwork-info-section');
            const content = $toggle.next('.artwork-info-content');
            const plusIcon = $toggle.find('.artwork-dropdown-icon.plus');
            const minusIcon = $toggle.find('.artwork-dropdown-icon.minus');
            
            const isExpanding = !$section.hasClass('active');

            if (isExpanding) {
                // Expanding: Fade out plus icon, fade in minus icon
                $section.addClass('active');
                plusIcon.css('opacity', '0');
                setTimeout(function() {
                    minusIcon.css('opacity', '1');
                }, 200);
                
                // Smooth slide down animation
                content.css('display', 'block');
                const height = content[0].scrollHeight;
                content.css('max-height', '0');
                content.css('opacity', '0');
                
                // Use requestAnimationFrame for smooth animation
                requestAnimationFrame(function() {
                    content.css('max-height', height + 'px');
                    content.css('opacity', '1');
                });
            } else {
                // Collapsing: Fade out minus icon, fade in plus icon
                minusIcon.css('opacity', '0');
                setTimeout(function() {
                    plusIcon.css('opacity', '1');
                }, 200);
                
                // Smooth slide up animation
                const height = content[0].scrollHeight;
                content.css('max-height', height + 'px');
                content.css('opacity', '1');
                
                // Remove active class first to trigger CSS transition
                $section.removeClass('active');
                
                // Use requestAnimationFrame for smooth animation
                requestAnimationFrame(function() {
                    requestAnimationFrame(function() {
                        content.css('max-height', '0');
                        content.css('opacity', '0');
                        
                        // Listen for transition end to hide element smoothly
                        const handleTransitionEnd = function(e) {
                            // Only handle max-height transition end
                            if (e.target === content[0] && e.propertyName === 'max-height') {
                                content.css('display', 'none');
                                content.off('transitionend', handleTransitionEnd);
                            }
                        };
                        
                        content.on('transitionend', handleTransitionEnd);
                        
                        // Fallback timeout in case transitionend doesn't fire
                        setTimeout(function() {
                            content.off('transitionend', handleTransitionEnd);
                            if (content.css('max-height') === '0px' || parseFloat(content.css('max-height')) === 0) {
                                content.css('display', 'none');
                            }
                        }, 450);
                    });
                });
            }
        });

     
        let currentImageIndex = 0;
        const totalImages = _artworkSlidersImages.length;
        const $mainImage = $('#artwork-main-image');

        function updateGallery() {
            // Update image source
            $mainImage.attr('src', _artworkSlidersImages[currentImageIndex]);
            
            // Update counter (1-based for display)
            $('.artwork-gallery-counter').text(`Image ${currentImageIndex + 1} of ${totalImages}`);
            
            // Update mobile counter (artwork-image-gallery-counter)
            $('.artwork-image-gallery-counter .artwork-current-index').text(currentImageIndex + 1);
            $('.artwork-image-gallery-counter .artwork-total-count').text(totalImages);
            
            // Update active dot
            $('.artwork-gallery-dot').removeClass('active');
            $('.artwork-gallery-dot[data-image-index="' + currentImageIndex + '"]').addClass('active');
        }

        // Previous button click
        $('.artwork-gallery-prev').on('click', function() {
            currentImageIndex = (currentImageIndex - 1 + totalImages) % totalImages;
            updateGallery();
        });

        // Next button click
        $('.artwork-gallery-next').on('click', function() {
            currentImageIndex = (currentImageIndex + 1) % totalImages;
            updateGallery();
        });

        // Gallery dot navigation
        $('.artwork-gallery-dot').on('click', function() {
            currentImageIndex = parseInt($(this).data('image-index'));
            updateGallery();
        });

        // Enquire to Buy Modal
        const enquireModal = $('#enquire-modal');
        const enquireBtn = $('#enquire-to-buy-btn');
        const enquireCloseBtn = $('#enquire-modal-close');
        const enquireOverlay = $('.enquire-modal-overlay');
        const enquireForm = $('#enquire-form');

        // Open modal
        enquireBtn.on('click', function(e) {
            e.preventDefault();
            enquireModal.addClass('active');
            $('body').css('overflow', '');
        });

        // Close modal
        function closeEnquireModal() {
            enquireModal.removeClass('active');
            $('body').css('overflow', '');
        }

        enquireCloseBtn.on('click', closeEnquireModal);
        enquireOverlay.on('click', closeEnquireModal);

        // Close on Escape key
        $(document).on('keydown', function(e) {
            if (e.key === 'Escape' && enquireModal.hasClass('active')) {
                closeEnquireModal();
            }
        });

        // Form submission
        enquireForm.on('submit', function(e) {
            e.preventDefault();
            // Add form submission logic here
            // For now, just close the modal
            closeEnquireModal();
            // You can add AJAX call here to submit the form
        });

        $(document).on('click', '#more-artworks-with-artist', function (e) {
            e.preventDefault();

            const artistId = $(this).data('artist-id');

            if (artistId) {
                const params = new URLSearchParams();
                params.append('filterState[artist][0]', artistId);

                window.location.href = '/artworks-list?' + params.toString();
            } else {
                window.location.href = '/artworks-list';
            }
        });

        let touchStartX = 0;
        let touchStartY = 0;
        let touchEndX = 0;
        let isSwiping = false;

        const swipeThreshold = 40; // smoother

        $('.art-img-wrapper').on('touchstart', function (e) {
            const touch = e.originalEvent.touches[0];
            touchStartX = touch.clientX;
            touchStartY = touch.clientY;
            touchEndX = touchStartX;
            isSwiping = true;
        });

        $('.art-img-wrapper').on('touchmove', function (e) {
            if (!isSwiping) return;

            const touch = e.originalEvent.touches[0];
            touchEndX = touch.clientX;

            const diffX = Math.abs(touchStartX - touchEndX);
            const diffY = Math.abs(touchStartY - touch.clientY);

            // Cancel swipe if user is scrolling vertically
            if (diffY > diffX) {
                isSwiping = false;
                return;
            }

            e.preventDefault(); // critical for smooth swipe
        });

        $('.art-img-wrapper').on('touchend', function () {
            if (!isSwiping) return;

            const diff = touchStartX - touchEndX;

            if (Math.abs(diff) > swipeThreshold) {
                if (diff > 0) {
                    currentImageIndex = (currentImageIndex + 1) % totalImages;
                } else {
                    currentImageIndex = (currentImageIndex - 1 + totalImages) % totalImages;
                }

                // ✅ YOUR FUNCTION — untouched
                updateGallery();
            }

            isSwiping = false;
        });

    });
}
if ($("#enquire-modal").length) {
    const phoneCodeSelect = document.getElementById('countryCode');
    window.updateCode = function (select, codeElementId) {
            const codeElement = document.getElementById(codeElementId);
            if (codeElement && select.value) {
                codeElement.textContent = select.value;
                codeElement.style.background = '#ffffff';
                codeElement.style.left = window.innerWidth <= 786 ? '25px' : '60px';
                codeElement.style.fontWeight = '500';
                codeElement.style.fontSize = '20px';
                codeElement.style.lineHeight = '46px';
                codeElement.style.color = 'rgb(112, 112, 112)';
                codeElement.style.width =  window.innerWidth <= 786 ? '75px' : '68px';
            }
    }
    function autoSelectPhoneCode() {
            if (phoneCodeSelect) {
                console.log("uuuu");
                const selectedOption = phoneCodeSelect.options[phoneCodeSelect.selectedIndex];
                const code = selectedOption.getAttribute('data-code');
                
                if (code) {
                    const phoneCodeValue =code;
                    onsole.log(phoneCodeValue);
                    // Find and select the matching option without removing other options
                    for (let i = 0; i < phoneCodeSelect.options.length; i++) {
                        if (phoneCodeSelect.options[i].value === phoneCodeValue) {
                            phoneCodeSelect.selectedIndex = i;
                            updateCode(phoneCodeSelect,'country_code');
                            break;
                        }
                    }
                }
            }
    }
    phoneCodeSelect.addEventListener('change', autoSelectPhoneCode);
}
if ($("#front_artist_listing_page").length > 0) {

    window.filterState = {
        style: [],
        nationality: [],
        sort: 'newest',
        search: '',
        labels: {
            style: {},
            nationality: {}
        }
    };

    $(document).ready(function () {

        $(window).on('scroll resize', function () {
            const hasFilters =
            filterState.style.length > 0 ||
            filterState.nationality.length > 0 ||
            filterState.search !== ''||
            filterState.sort != 'newest';
            if (hasFilters) {
                handleFilterBarPosition();
            } 
        });
        readFiltersFromURL();

        $('.artworks-filter-button:not(.artworks-filter-button-mobile)').on('click', function (e) {
            e.stopPropagation();

            const filterType = $(this).data('filter');
            const panel = $('#' + filterType + '-panel');
            const isOpen = panel.hasClass('active');

            $('.artworks-filter-panel').removeClass('active');
            $('.artworks-filter-button').removeClass('active');

            if (!isOpen) {
                panel.addClass('active');
                $(this).addClass('active');
            }
        });

        $(document).on('click', function () {
            $('.artworks-filter-panel').removeClass('active');
            $('.artworks-filter-button').removeClass('active');
        });

        //  CHECKBOX FILTERS (DESKTOP + MOBILE)
        // Only handle if we're on artist listing page, not gallery details or artwork listing
        $(document).on('change', '.artworks-filter-checkbox', function () {
            // Skip if on gallery details page or artwork listing page
            if ($('#gallery-artworks-filters').length || $('#artworks-list-container').length) {
                return;
            }
            const type = $(this).attr('name');
            const value = $(this).val();
            const checked = $(this).is(':checked');

            // 🔁 Sync both mobile & desktop
            $('input[name="' + type + '"][value="' + value + '"]')
                .prop('checked', checked);

            // 🔤 Store label ONCE (desktop or mobile, doesn’t matter)
            if (!filterState.labels[type][value]) {
                filterState.labels[type][value] = $(this)
                    .closest('label')
                    .find('span')
                    .first()
                    .text()
                    .trim();
            }

            if (checked) {
                if (!filterState[type].includes(value)) {
                    filterState[type].push(value);
                }
            } else {
                filterState[type] = filterState[type].filter(v => v !== value);
            }

            updateFilterCount(type);
            toggleClearAllVisibility();
            updateTotalFilterCount();
            updateActiveTags();
            updateMobileFilterLabels();
            updateURL();
            loadArtists(1);
        });


        //  SORT
        // Only handle if we're on artist listing page, not gallery details or artwork listing
        $(document).on('change', '.artworks-filter-radio', function () {
            // Skip if on gallery details page or artwork listing page
            if ($('#gallery-artworks-filters').length || $('#artworks-list-container').length) {
                return;
            }
            filterState.sort = $(this).val();
            updateSortButtonLabel();
            updateURL();
            loadArtists(1);
            $('#sort-panel').removeClass('active');
        });

        //SEARCH (DESKTOP + MOBILE)
        let searchTimeout = null;
        $(document).on('input', '.sub-search-input', function () {
            clearTimeout(searchTimeout);
            filterState.search = $(this).val().trim();

            searchTimeout = setTimeout(() => {
                updateURL();
                toggleClearAllVisibility();
                loadArtists(1);
            }, 800);
        });

        // CLEAR ALL (DESKTOP + MOBILE)
        $('#clear-all-filters, #clear-all-filters-mobile').on('click', function (e) {
            e.preventDefault();

            filterState.style = [];
            filterState.nationality = [];
            filterState.sort = 'newest';
            filterState.search = '';

            $('.artworks-filter-checkbox').prop('checked', false);
            $('.artworks-filter-radio[value="newest"]').prop('checked', true);
            $('.sub-search-input').val('');

            updateFilterCount('style');
            updateFilterCount('nationality');
            toggleClearAllVisibility();    
            updateTotalFilterCount();
            updateSortButtonLabel();
            updateActiveTags();
            updateMobileFilterLabels();
            updateURL();
            loadArtists(1);
        });

        // MOBILE OVERLAY
        const mobileFilterOverlay = {
            isOpen: () => $('#mobile-filter-overlay').hasClass('opacity-100'),
            open: function () {
                syncMobileFiltersOnLoad();
                updateMobileFilterLabels();

                $('#mobile-filter-overlay')
                    .removeClass('opacity-0 invisible pointer-events-none')
                    .addClass('opacity-100 visible pointer-events-auto');

                $('#mobile-filter-backdrop').removeClass('opacity-0').addClass('opacity-100');
                $('#mobile-filter-content').removeClass('-translate-x-full').addClass('translate-x-0');
                $('body').css('overflow', 'hidden');
            },
            close: function () {
                $('#mobile-filter-overlay')
                    .removeClass('opacity-100 visible pointer-events-auto')
                    .addClass('opacity-0 invisible pointer-events-none');

                $('#mobile-filter-backdrop').removeClass('opacity-100').addClass('opacity-0');
                $('#mobile-filter-content').removeClass('translate-x-0').addClass('-translate-x-full');
                $('body').css('overflow', '');
            }
        };

        $('#mobile-filters-toggle').on('click', function (e) {
            e.preventDefault();
            e.stopPropagation();
            mobileFilterOverlay.isOpen() ? mobileFilterOverlay.close() : mobileFilterOverlay.open();
        });

        $('#mobile-filter-close, #mobile-filter-backdrop').on('click', function (e) {
            e.preventDefault();
            mobileFilterOverlay.close();
        });

        $('#mobile-filter-see-results').on('click', function () {
            mobileFilterOverlay.close();
        });

        // MOBILE PANELS
        // Only handle if we're on artist listing page, not gallery details or artwork listing
        $(document).on('click', '.artworks-filter-button-mobile', function (e) {
            // Skip if on gallery details page or artwork listing page
            if ($('#gallery-artworks-filters').length || $('#artworks-list-container').length) {
                return;
            }
            
            e.preventDefault();
            e.stopPropagation();

            const filterType = $(this).data('filter');
            const panel = $('#' + filterType + '-panel');
            const isOpen = panel.hasClass('active');

            $('#mobile-filter-content .artworks-filter-panel').removeClass('active');
            $('#mobile-filter-content .artworks-filter-button-mobile').removeClass('active');

            if (!isOpen) {
                panel.addClass('active');
                $(this).addClass('active');
            }
        });

    });

    // AJAX
    function loadArtists(page = 1) {
        $.ajax({
            url: '/artists-list',
            data: { filterState, page },

            beforeSend: function () {
                $('#artists-list-wrapper').html(
                    '<p class="text-center py-8">Loading artists...</p>'
                );
                $('.ajaxloader').show();
            },

            success: function (html) {
                $('#artists-list-wrapper').html(html);
                  const hasFilters =
                    filterState.style.length > 0 ||
                    filterState.nationality.length > 0 ||
                    filterState.search !== ''||
                    filterState.sort != 'newest';
                    if (hasFilters) {
                    $('#empty-artist-list').html("No artists match your filters. Try searching for a different artist.");
                    } 
            },

            error: function () {
                $('#artists-list-wrapper').html(
                    '<p class="text-center py-8">Something went wrong. Please try again.</p>'
                );
            },

            complete: function () {
                $('.ajaxloader').hide();
            }
        });
    }

    function toggleClearAllVisibility() {
        const hasFilters =
            filterState.style.length > 0 ||
            filterState.nationality.length > 0 ||
            filterState.search !== ''||
            filterState.sort != 'newest';

        if (hasFilters) {
            $('#clear-all-filters, #clear-all-filters-mobile')
                .attr('style', 'display:block !important');
        } else {
            $('#clear-all-filters, #clear-all-filters-mobile')
                .attr('style', 'display:none !important');
        }
        $('.artworks-filter-section')
        .toggleClass('sticky', hasFilters);
    }





    function updateFilterCount(type) {
        const count = filterState[type].length;
        const el = $('#' + type + '-count');
        count ? el.text('(' + count + ')').show() : el.hide();
    }
    function updateTotalFilterCount() {
        const total =
            filterState.style.length +
            filterState.nationality.length;

        const el = $('#consolidated-filter-count');

        if (total > 0) {
            el.text(`(${total})`).show();
        } else {
            el.text('').hide();
        }
    }
    function updateActiveTags() {
        const container = $('#active-filter-tags');
        // Remove only filter tags, preserve the label
        container.find('.artworks-filter-tag').remove();

        ['style', 'nationality'].forEach(type => {
            filterState[type].forEach(value => {
                const label = filterState.labels[type][value] || value;

                container.append(`
                    <span class="artworks-filter-tag">
                        ${label}
                        <button class="artworks-filter-tag-remove"
                                data-filter="${type}"
                                data-value="${value}"><img src="assets/svg/icons/close-filter.svg" class="close-filter" alt="close-filter" width="8" height="8" /></button>
                    </span>
                `);
            });
        });

        // Show/hide container based on filter tags (excluding label)
        const hasTags = container.find('.artworks-filter-tag').length > 0;
        container.toggle(hasTags);
    }
    $(document).on('click', '.artworks-filter-tag-remove', function (e) {
        e.preventDefault();
        e.stopImmediatePropagation(); // ⬅️ THIS is the key

        const type = $(this).data('filter');
        const value = $(this).data('value');

        // Update state
        filterState[type] = filterState[type].filter(v => v !== value);

        // Sync checkboxes
        $('input[name="' + type + '"][value="' + value + '"]')
            .prop('checked', false);

        updateFilterCount(type);
        toggleClearAllVisibility();
        updateTotalFilterCount();
        updateActiveTags();
        updateMobileFilterLabels();
        updateURL();
        loadArtists(1);
    });


    function updateMobileFilterLabels() {
        const styleCount = filterState.style.length;

        $('#style-mobile-label').text(
            styleCount === 0 ? 'All Styles' :
            styleCount === 1 ? filterState.labels.style[filterState.style[0]] :
            styleCount + ' Selected'
        );

        const natCount = filterState.nationality.length;

        $('#nationality-mobile-label').text(
            natCount === 0 ? 'All Nationalities' :
            natCount === 1 ? filterState.labels.nationality[filterState.nationality[0]] :
            natCount + ' Selected'
        );
    }

    function syncMobileFiltersOnLoad() {
        $('.artworks-filter-checkbox').each(function () {
            const type = $(this).attr('name');
            const value = $(this).val();
            const checked = filterState[type].includes(value);
            $('input[name="' + type + '"][value="' + value + '"]').prop('checked', checked);
        });
    }

    function updateURL() {
        const params = new URLSearchParams();

        filterState.style.forEach(v => params.append('filterState[style][]', v));
        filterState.nationality.forEach(v => params.append('filterState[nationality][]', v));
        if (filterState.sort) params.set('filterState[sort]', filterState.sort);
        if (filterState.search) params.set('filterState[search]', filterState.search);

        history.replaceState({}, '', window.location.pathname + '?' + params);
    }

    function readFiltersFromURL() {
        const params = new URLSearchParams(window.location.search);

        params.forEach((value, key) => {
            if (key.includes('style')) filterState.style.push(value);
            if (key.includes('nationality')) filterState.nationality.push(value);
            if (key.includes('sort')) filterState.sort = value;
            if (key.includes('search')) filterState.search = value;
        });
        $('.artworks-filter-radio')
            .prop('checked', false)
            .filter('[value="' + filterState.sort + '"]')
            .prop('checked', true);

        syncMobileFiltersOnLoad();
        updateFilterCount('style');
        toggleClearAllVisibility();
        updateFilterCount('nationality');
        updateSortButtonLabel();
        updateTotalFilterCount();
        updateActiveTags();
        updateMobileFilterLabels();
    }

    function updateSortButtonLabel() {
        const checked = $('.artworks-filter-radio:checked');
        if (!checked.length) return;
        $('#sort-label').text( checked.closest('label').find('span').text());
    }
}

if ($("#mktArtworkSliderImages").length) {
    document.addEventListener('DOMContentLoaded', function() {
            const imageModal = document.getElementById('imageGalleryModal');
            const closeImageModal = document.getElementById('closeImageGalleryModal');
            const imageElement = document.getElementById('imageGalleryImage');
            const imagePrev = document.getElementById('imageGalleryPrev');
            const imageNext = document.getElementById('imageGalleryNext');
            const imagePagination = document.getElementById('imageGalleryPagination');

            let currentImageIndex = 0;
            let images = []; // Array of {src: string, alt: string}

            // Collect all images from the page
            function collectImages() {
                images = [];

                _artworkSlidersImages.forEach((src, index) => {
                    if (src) {
                        images.push({
                            src: src,
                            alt: `Artwork Image ${index + 1}`,
                            index: index
                        });
                    }
                });
            }
            // Open modal and show image
            function openImageModal(index) {
                if (!images || images.length === 0) {
                    collectImages();
                }
                if (images && index >= 0 && index < images.length) {
                    currentImageIndex = index;
                    const image = images[index];
                    // Set image source
                    if (imageElement) {
                        imageElement.src = image.src;
                        imageElement.alt = image.alt;
                    }
                    // Show modal
                    if (imageModal) {
                        imageModal.classList.remove('hidden');
                        imageModal.style.display = 'flex';
                        document.body.style.overflow = 'hidden';
                    }
                    updateImagePagination();
                }
            }
            // Close modal
            function closeModal() {
                if (imageModal) {
                    imageModal.classList.add('hidden');
                    imageModal.style.display = 'none';
                    document.body.style.overflow = '';
                }
            }
            // Update pagination dots
            function updateImagePagination() {
                if (!imagePagination) return;
                imagePagination.innerHTML = '';
                images.forEach((_, index) => {
                    const dot = document.createElement('button');
                    dot.className = `w-2 h-2 rounded-full transition-all ${
                      index === currentImageIndex ? 'bg-white' : 'bg-white/50'
                    }`;
                    dot.setAttribute('aria-label', `Go to image ${index + 1}`);
                    dot.addEventListener('click', () => openImageModal(index));
                    imagePagination.appendChild(dot);
                });
            }
            // Event listeners for image clicks
            document.addEventListener('click', function(e) {
                if (e.target.closest('#artwork-main-image')) {
                    e.preventDefault();
                      const activeDot = document.querySelector('.artwork-gallery-dot.active');
        const index = activeDot ? Number(activeDot.dataset.imageIndex) : 0;
                    collectImages();
                    openImageModal(index);
                }
            });
            // Close button
            if (closeImageModal) {
                closeImageModal.addEventListener('click', closeModal);
            }
            // Close on backdrop click
            if (imageModal) {
                imageModal.addEventListener('click', function(e) {
                    if (e.target === imageModal) {
                        closeModal();
                    }
                });
            }
            // Close on Escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && imageModal && !imageModal.classList.contains('hidden')) {
                    closeModal();
                }
            });
            // Previous/Next navigation
            if (imagePrev) {
                imagePrev.addEventListener('click', function(e) {
                    e.stopPropagation();
                    const newIndex = currentImageIndex > 0 ? currentImageIndex - 1 : images.length - 1;
                    openImageModal(newIndex);
                });
            }
            if (imageNext) {
                imageNext.addEventListener('click', function(e) {
                    e.stopPropagation();
                    const newIndex = (images && currentImageIndex < images.length - 1) ? currentImageIndex +
                        1 : 0;
                    openImageModal(newIndex);
                });
            }
            // Keyboard navigation (arrow keys)
            document.addEventListener('keydown', function(e) {
                if (imageModal && !imageModal.classList.contains('hidden')) {
                    if (e.key === 'ArrowLeft') {
                        e.preventDefault();
                        const newIndex = currentImageIndex > 0 ? currentImageIndex - 1 : images.length - 1;
                        openImageModal(newIndex);
                    } else if (e.key === 'ArrowRight') {
                        e.preventDefault();
                        const newIndex = (images && currentImageIndex < images.length - 1) ?
                            currentImageIndex + 1 : 0;
                        openImageModal(newIndex);
                    }
                }
            });

            // Re-collect images when new ones are added
            const observer = new MutationObserver(function(mutations) {
                collectImages();
            });
    });
}

function frontpaginate(url = '', page = 1, forceTab = '') {
    // 1. Determine Current Tab
    let activeTab = forceTab;
    if (!activeTab) {
        // Find which tab has the active class if not forced
        if($('#tab-btn-artists').hasClass('artist-tab-active')) activeTab = 'artists';
        else if($('#tab-btn-galleries').hasClass('artist-tab-active')) activeTab = 'galleries';
        else activeTab = 'artworks';
    }

    // 2. Construct URL
    // If a full URL (pagination link) is passed, use it, but update params
    let targetUrl = url ? url : REQUEST_URL;
    
    // Create a URL object to easily manipulate params
    // Note: Using a dummy base if targetUrl is relative, fixed later
    let urlObj = new URL(targetUrl, window.location.origin);
    
    // Update Query Params
    urlObj.searchParams.set('q', SEARCH_QUERY);
    urlObj.searchParams.set('tab', activeTab);
    
    // Only set page if we are constructing from scratch or override needed
    if (!url && page) {
        urlObj.searchParams.set('page', page);
    }

    let finalUrl = urlObj.toString();

    // 3. Update Browser History (PushState)
    try {
         window.history.pushState({path: finalUrl}, '', finalUrl);
        if(APP_ENV=='local'){
           // window.history.pushState({path: finalUrl}, '', finalUrl);
        }else{
           if (location.protocol === 'http:') {
                //finalUrl = finalUrl.replace(/^http:/, 'https:');
            } 
        }
        //window.history.pushState({ path: finalUrl }, '', finalUrl);
    } catch(e) {}

    // 4. AJAX Request
    $.ajax({
        type: "GET",
        url: finalUrl,
        dataType: "json", // Expect JSON response
        beforeSend: function() {
            $('.ajaxloader').show(); // Ensure you have a loader div or remove this
            $('#search-results-container').css('opacity', '0.5'); // Visual feedback
        },
        success: function(response) {
            $('.ajaxloader').hide();
            $('#search-results-container').css('opacity', '1');

            if (response.status === 'success') {
                // A. Update the Content Body
                $('#search-results-container').html(response.body);

                // B. Update the Tab Counts
                if (response.counts) {
                    $('#count-artworks').text('(' + response.counts.artworks + ')');
                    $('#count-artists').text('(' + response.counts.artists + ')');
                    $('#count-galleries').text('(' + response.counts.galleries + ')');
                }
                
                // Initialize Masonry after content is loaded (only for artworks tab)
                setTimeout(function() {
                    initializeSearchMasonry();
                }, 100);
                
                // C. Scroll to top of results (optional, good UX)
               /* $('html, body').animate({
                    scrollTop: $(".artist-tabs-section").offset().top - 100
                }, 500);*/
            }
        },
        error: function(xhr) {
            $('.ajaxloader').hide();
            $('#search-results-container').css('opacity', '1');
            console.error("Error loading data");
        }
    });
}

// Masonry instance for search results
var searchMasonryInstance = null;

/**
 * Initialize Masonry layout for search results
 */
function initializeSearchMasonry() {
    var grid = document.querySelector('#artworks-content .masonry-grid');
    if (!grid) return;
    
    // Destroy existing instance if it exists
    if (searchMasonryInstance) {
        searchMasonryInstance.destroy();
        searchMasonryInstance = null;
    }
    
    // Wait for images to load before initializing
    var images = grid.querySelectorAll('img');
    var imagesLoaded = 0;
    var totalImages = images.length;
    
    if (totalImages === 0) {
        // No images, initialize immediately
        initMasonry();
    } else {
        // Wait for all images to load
        images.forEach(function(img) {
            if (img.complete) {
                imagesLoaded++;
                if (imagesLoaded === totalImages) {
                    initMasonry();
                }
            } else {
                img.addEventListener('load', function() {
                    imagesLoaded++;
                    if (imagesLoaded === totalImages) {
                        initMasonry();
                    }
                });
                img.addEventListener('error', function() {
                    imagesLoaded++;
                    if (imagesLoaded === totalImages) {
                        initMasonry();
                    }
                });
            }
        });
    }
    
    function initMasonry() {
        if (typeof Masonry === 'undefined') {
            console.warn('Masonry.js is not loaded');
            return;
        }
        
        try {
            searchMasonryInstance = new Masonry(grid, {
                itemSelector: '.grid-item',
                columnWidth: '.grid-sizer',
                percentPosition: true,
                horizontalOrder: true
            });
        } catch (e) {
            console.error('Error initializing Masonry:', e);
        }
    }
}

if( $("#global_search_tabs").length > 0 ){

    $(document).ready(function() {
        // 1. Tab Switching Listener
        $(document).on('click', '.artist-tab', function(e) {
            e.preventDefault();
            let tabName = $(this).data('tab');
            $('.artist-tab').removeClass('artist-tab-active');
            $(this).addClass('artist-tab-active');
            frontpaginate('', 1, tabName);
        });

        // 2. PAGINATION CLICK LISTENER (NEW)
        // This grabs the URL from data-href and passes it to the paginate function
        $(document).on('click', '.ajax-pagination-btn', function(e) {
            e.preventDefault();
            
            // Get the URL from the data attribute
            let url = $(this).data('href');
            if(APP_ENV !='local'){
                if (url) url = url.replace('http://', 'https://');
            }
            //alert(url)
            if(url) {
                frontpaginate(url);
            }
        });
        
        // Initialize Masonry on page load
        $(window).on('load', function() {
            setTimeout(function() {
                initializeSearchMasonry();
            }, 300);
        });
        
        // Also try to initialize after a short delay in case window.load already fired
        setTimeout(function() {
            if (!searchMasonryInstance) {
                initializeSearchMasonry();
            }
        }, 500);
    });
}