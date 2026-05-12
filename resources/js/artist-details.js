import {  handleFilterBarPosition } from './utils/filter-ui';
/**
 * Artist Details Page JavaScript
 * 
 * This file handles all functionality for the artist details page, including:
 * - Tab switching (Artworks, Biography)
 * - Filtering artworks (style, medium, price)
 * - Sorting options
 * - Price range filtering
 * - Mobile filter overlay
 * - Filter counts and active filter tags
 * - Mobile filter label updates
 * - AJAX-based artwork loading and pagination
 * 
 * Page Detection:
 * - Only runs on artist details page (has #artistDetailsPage element)
 * - Uses top-level check to prevent execution on other pages
 * 
 * Filter State Management:
 * - Maintains filter state for style, medium, artist, price, and sort
 * - Syncs desktop and mobile filter inputs
 * - Updates filter counts and tags dynamically
 * 
 * @file artist-details.js
 * @requires jQuery
 */

if ($('#artistDetailsPage').length) {
    'use strict';
    
    /**
     * Debounce function to limit function execution frequency
     * @param {Function} fn - Function to debounce
     * @param {number} delay - Delay in milliseconds
     * @returns {Function} Debounced function
     */
    function debounce(fn, delay) {
        let timer = null;
        return function () {
            const context = this;
            const args = arguments;

            clearTimeout(timer);
            timer = setTimeout(function () {
                fn.apply(context, args);
            }, delay);
        };
    }
    
    // Debounced filter load will be defined inside document.ready
    let debouncedFilterLoad;
    
    // Masonry instance
    var masonryInstance = null;
    
    /**
     * Initialize Masonry layout
     */
    function initializeMasonry() {
        var grid = document.querySelector('#artworks-content .masonry-grid');
        if (!grid) return;
        
        // Destroy existing instance if it exists
        if (masonryInstance) {
            masonryInstance.destroy();
            masonryInstance = null;
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
                masonryInstance = new Masonry(grid, {
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

    /**
     * Helper function to find input by name and value (handles various name formats)
     * Supports: name, name[], name-mobile, name-mobile[]
     * @param {string} name - The input name attribute
     * @param {string|number} value - The input value to match
     * @returns {jQuery} jQuery object containing matching input elements
     */
    function findInputByValue(name, value) {
        if (!name) return $();
        // Remove brackets and mobile suffix for matching
        const baseName = name.replace('[]', '').replace('-mobile', '');
        
        return $('input').filter(function () {
            const inputName = $(this).attr('name');
            if (!inputName) return false;
            
            // Check for various name formats: name, name[], name-mobile, name-mobile[]
            const matchesName = (
                inputName === baseName ||
                inputName === baseName + '[]' ||
                inputName === baseName + '-mobile' ||
                inputName === baseName + '-mobile[]'
            );
            
            return matchesName && String($(this).val()) === String(value);
        });
    }



    /* =============================
     * DEFAULTS
     * ============================= */
    const defaultPriceRange = { min: 0, max: 20000 };
    const MAX_PRICE = 50000;

    /* =============================
     * TAB HANDLING
     * ============================= */
    window.addEventListener('hashchange', function () {
        const hash = window.location.hash.replace('#', '');
        activateArtistTab(hash);
    });

    function activateArtistTab(tabName) {
        if (!tabName) tabName = 'artworks';

        $('.artist-tab').removeClass('artist-tab-active');
        $('.artist-tab-content').hide();

        $('.artist-tab[data-tab="' + tabName + '"]').addClass('artist-tab-active');
        $('#' + tabName + '-content').show();
    }

    /* =============================
     * DOCUMENT READY
     * ============================= */
    $(document).ready(function () {

        activateArtistTab(window.location.hash.replace('#', ''));


        $(document).on('click', '.artist-tab', function (e) {
            e.preventDefault();
            const tabName = $(this).data('tab');
            history.replaceState(null, null, '#' + tabName);
            activateArtistTab(tabName);
        });

        /* =============================
         * FOLLOW BUTTON
         * ============================= */
        $('#artist-follow-btn').on('click', function () {
            $(this).toggleClass('artist-follow-btn-active');
        });

        /* =============================
         * FILTER STATE
         * ============================= */
        const filterState = {
            style: [],
            medium: [],
            artist: [],
            price: { ...defaultPriceRange },
            sort: $('input[name="sort"]:checked').val() || 'recently-added'
        };

        // Debounced filter load to prevent excessive AJAX calls
        debouncedFilterLoad = debounce(() => loadArtworks(1), 400);
        // handle filter scroll
        $(window).on('scroll resize', function () {
            const hasFilters =
            filterState.style.length > 0 || 
            filterState.medium.length > 0 || 
            filterState.artist.length > 0 ||
            filterState.price.min !== defaultPriceRange.min ||
            filterState.price.max !== defaultPriceRange.max ||
            filterState.sort !== 'recently-added';
            if (hasFilters) {
                handleFilterBarPosition();
            } 
        });
        /* =============================
         * RESTORE FILTERS FROM URL PARAMETERS
         * ============================= */
        function restoreFiltersFromUrl() {
            const urlParams = new URLSearchParams(window.location.search);
            let hasParams = false;
            
            // Parse style filters
            const styleParams = urlParams.getAll('style[]');
            if (styleParams.length > 0) {
                filterState.style = styleParams;
                hasParams = true;
            }
            
            // Parse medium filters
            const mediumParams = urlParams.getAll('medium[]');
            if (mediumParams.length > 0) {
                filterState.medium = mediumParams;
                hasParams = true;
            }
            
            // Parse price filters
            const priceMin = urlParams.get('price_min');
            const priceMax = urlParams.get('price_max');
            if (priceMin || priceMax) {
                filterState.price.min = priceMin ? parseInt(priceMin) : defaultPriceRange.min;
                filterState.price.max = priceMax ? parseInt(priceMax) : defaultPriceRange.max;
                hasParams = true;
            }
            
            // Parse sort
            const sortParam = urlParams.get('sort');
            if (sortParam) {
                filterState.sort = sortParam;
                hasParams = true;
            }
            
            // If URL has parameters, restore filters to UI
            if (hasParams) {
                // Uncheck all checkboxes first
                $('.artworks-filter-checkbox').prop('checked', false);
                
                // Restore style filters
                filterState.style.forEach(value => {
                    findInputByValue('style', value).prop('checked', true);
                });
                
                // Restore medium filters
                filterState.medium.forEach(value => {
                    findInputByValue('medium', value).prop('checked', true);
                });
                
                // Restore price filters
                syncPrice(filterState.price.min, filterState.price.max);
                
                // Restore sort
                $(`input[name="sort"][value="${filterState.sort}"]`).prop('checked', true);
                updateSortLabel();
                
                // Update filter counts
                updateFilterCount('style');
                updateFilterCount('medium');
                updateFilterCount('price');
                updateActiveTags();
                updateMobileFilterLabels();
                updateClearAllVisibility();
                
                // Reload artworks with restored filters
                loadArtworks(1);
            }
        }

        /* =============================
         * FILTER PANEL TOGGLE (DESKTOP)
         * ============================= */
        $('.artworks-filter-button').not('.artworks-filter-button-mobile').on('click', function (e) {
            e.stopPropagation();
            const filterType = $(this).data('filter');
            const panel = $('#' + filterType + '-panel');

            $('.artworks-filter-panel').removeClass('active');
            $('.artworks-filter-button').removeClass('active');

            panel.addClass('active');
            $(this).addClass('active');
        });

        $(document).on('click', function (e) {
            if (!$(e.target).closest('.artworks-filter-button-wrapper').length) {
                $('.artworks-filter-panel').removeClass('active');
                $('.artworks-filter-button').removeClass('active');
            }
        });

        /* =============================
         * CHECKBOX FILTERS
         * ============================= */
        $('.artworks-filter-checkbox').on('change', function () {
            const type = $(this).attr('name');
            const value = $(this).val();

            filterState[type] = $('input[name="' + type + '"]:checked')
                .map((_, el) => el.value).get();

            updateFilterCount(type);
            updateActiveTags();
            updateMobileFilterLabels();
            updateClearAllVisibility();
            debouncedFilterLoad();

        });

        /* =============================
         * UPDATE SORT LABEL
         * ============================= */
        function updateSortLabel() {
            const sortValue = filterState.sort || 'recently-added';
            const $sortButton = $('.artworks-filter-button-sort');
            if ($sortButton.length) {
                // Get the label text from the checked radio button
                const $checkedRadio = $(`input[name="sort"][value="${sortValue}"]`);
                let sortLabel = 'Recently Added';
                if ($checkedRadio.length) {
                    sortLabel = $checkedRadio.closest('label').find('span').text().trim();
                } else {
                    // Fallback to value-based labels
                    if (sortValue === 'price-low-high') {
                        sortLabel = 'Price: Low to High';
                    } else if (sortValue === 'price-high-low') {
                        sortLabel = 'Price: High to Low';
                    }
                }
                // Update button text - preserve icon and arrow structure
                const $img = $sortButton.find('img').first();
                const $arrow = $sortButton.find('.artworks-sort-arrow');
                if ($img.length && $arrow.length) {
                    $sortButton.html($img[0].outerHTML + ' ' + sortLabel + ' ' + $arrow[0].outerHTML);
                } else {
                    // Fallback if structure is different
                    $sortButton.html('<img src="/assets/svg/sort.svg" alt="arrow-icon-right" width="16" height="16" /> ' + sortLabel + ' <span class="artworks-sort-arrow"><img src="/assets/svg/icons/arrowdown.svg" alt="arrow-icon-right" width="9" height="4" /></span>');
                }
            }
        }

        /* =============================
         * SORT
         * ============================= */
        $('.artworks-filter-radio').on('change', function () {
            filterState.sort = $(this).val();
            $('#sort-panel').removeClass('active');
            updateSortLabel();
            updateClearAllVisibility();
            debouncedFilterLoad(1);
        });

        /* =============================
         * PRICE FILTER (FIXED)
         * ============================= */
        const minRange = $('.artworks-price-range-min');
        const maxRange = $('.artworks-price-range-max');

        const minInput = $('#price-min-input');
        const maxInput = $('#price-max-input');

        const minDisplay = $('#price-min-display');
        const maxDisplay = $('#price-max-display');

        function syncPrice(min, max) {
            min = parseInt(min) || defaultPriceRange.min;
            max = parseInt(max) || defaultPriceRange.max;

            if (min >= max) min = max - 100;

            filterState.price.min = min;
            filterState.price.max = max;

            minRange.val(min);
            maxRange.val(max);

            minInput.val(min);
            maxInput.val(max);

            minDisplay.text(formatPrice(min) + ' USD');
            maxDisplay.text(formatPrice(max) + '+ USD');

            // Use 20000 as max value for percentage calculation (matches slider max)
            const maxValue = 20000;
            const minPercent = (min / maxValue) * 100;
            const maxPercent = (max / maxValue) * 100;

            $('.artworks-price-slider').css({
                '--range-min': minPercent + '%',
                '--range-max': maxPercent + '%'
            });

            updateFilterCount('price');
            updateActiveTags();
            updateClearAllVisibility();
        }

        // Desktop price range handlers - use input event for sliders
        $(document).on('input', '.artworks-price-range-min', function () {
            syncPrice($(this).val(), maxRange.val());
            debouncedFilterLoad(1);
        });

        $(document).on('input', '.artworks-price-range-max', function () {
            syncPrice(minRange.val(), $(this).val());
            debouncedFilterLoad(1);
        });

        // Desktop price input handlers
        $(document).on('keyup', '#price-min-input', function () {
            syncPrice($(this).val(), maxInput.val());
            debouncedFilterLoad();
        });

        $(document).on('keyup', '#price-max-input', function () {
            syncPrice(minInput.val(), $(this).val());
            debouncedFilterLoad();
        });

        /* =============================
         * CLEAR FILTERS
         * ============================= */
        $('#clear-all-filters').on('click', function (e) {
            e.preventDefault();

            $('.artworks-filter-checkbox').prop('checked', false);
            $('.artworks-filter-radio').prop('checked', false);
            $('input[name="sort"][value="recently-added"]').prop('checked', true);

            filterState.style = [];
            filterState.medium = [];
            filterState.artist = [];
            filterState.sort = 'recently-added';
            filterState.price = {
                min: defaultPriceRange.min,
                max: defaultPriceRange.max
            };


            syncPrice(defaultPriceRange.min, defaultPriceRange.max);

            $('.artworks-filter-count').hide();
            $('#consolidated-filter-count').hide();
            updateActiveTags();
            updateMobileFilterLabels();
            debouncedFilterLoad();
        });

        /* =============================
         * COUNTS
         * ============================= */
        function updateFilterCount(type) {
            let count = 0;

            if (type === 'price') {
                if (
                    filterState.price.min !== defaultPriceRange.min ||
                    filterState.price.max !== defaultPriceRange.max
                ) count = 1;
            } else {
                count = filterState[type].length;
            }

            const el = $('#' + type + '-count');
            count > 0 ? el.text('(' + count + ')').show() : el.hide();

            updateConsolidatedFilterCount();
        }

        function updateConsolidatedFilterCount() {
            const total =
                filterState.style.length +
                filterState.medium.length +
                filterState.artist.length;

            total > 0
                ? $('#consolidated-filter-count').text('(' + total + ')').show()
                : $('#consolidated-filter-count').hide();
        }

        /* =============================
         * UPDATE CLEAR ALL VISIBILITY
         * ============================= */
        function updateClearAllVisibility() {
            const hasFilters = filterState.style.length > 0 || 
                              filterState.medium.length > 0 || 
                              filterState.artist.length > 0 ||
                              filterState.price.min !== defaultPriceRange.min ||
                              filterState.price.max !== defaultPriceRange.max ||
                              filterState.sort !== 'recently-added';
            
            const clearAllEl = document.getElementById('clear-all-filters');
            const clearAllMobileEl = document.getElementById('clear-all-filters-mobile');
            
            if (clearAllEl) {
                clearAllEl.style.display = hasFilters ? 'inline' : 'none';
            }
            if (clearAllMobileEl) {
                clearAllMobileEl.style.display = hasFilters ? 'inline' : 'none';
            }
            $('.artworks-filter-section')
               .toggleClass('sticky', hasFilters);
        }

        /* =============================
         * UPDATE MOBILE FILTER LABELS
         * ============================= */
        function updateMobileFilterLabels() {
            try {
                // Update Style label
                const styleValues = filterState.style || [];
                const styleLabelEl = document.getElementById('style-mobile-label');
                if (styleLabelEl) {
                    if (styleValues.length === 0) {
                        styleLabelEl.textContent = 'All Styles';
                    } else if (styleValues.length === 1) {
                        const $checkbox = findInputByValue('style', styleValues[0]).first();
                        const label = $checkbox.length
                            ? $checkbox.closest('label').find('span').first().text().trim()
                            : String(styleValues[0]);
                        styleLabelEl.textContent = label;
                    } else {
                        styleLabelEl.textContent = styleValues.length + ' Selected';
                    }
                }

                // Update Medium label
                const mediumValues = filterState.medium || [];
                const mediumLabelEl = document.getElementById('medium-mobile-label');
                if (mediumLabelEl) {
                    if (mediumValues.length === 0) {
                        mediumLabelEl.textContent = 'All Mediums';
                    } else if (mediumValues.length === 1) {
                        const $checkbox = findInputByValue('medium', mediumValues[0]).first();
                        const label = $checkbox.length
                            ? $checkbox.closest('label').find('span').first().text().trim()
                            : String(mediumValues[0]);
                        mediumLabelEl.textContent = label;
                    } else {
                        mediumLabelEl.textContent = mediumValues.length + ' Selected';
                    }
                }

                // Update Artist label
                const artistValues = filterState.artist || [];
                const artistLabelEl = document.getElementById('artist-mobile-label');
                if (artistLabelEl) {
                    if (artistValues.length === 0) {
                        artistLabelEl.textContent = 'All Artists';
                    } else if (artistValues.length === 1) {
                        const $checkbox = findInputByValue('artist', artistValues[0]).first();
                        const label = $checkbox.length
                            ? $checkbox.closest('label').find('span').first().text().trim()
                            : String(artistValues[0]);
                        artistLabelEl.textContent = label;
                    } else {
                        artistLabelEl.textContent = artistValues.length + ' Selected';
                    }
                }
            } catch (e) {
                console.warn('Error updating mobile filter labels:', e);
            }
        }

        /* =============================
         * UPDATE ACTIVE FILTER TAGS
         * ============================= */
        function updateActiveTags() {
            const tagsContainer = $('#active-filter-tags');
            if (!tagsContainer.length) return;

            // Ensure "Applied Filters:" label exists
            if (tagsContainer.find('.artworks-filter-tags-label').length === 0) {
                tagsContainer.prepend('<span class="artworks-filter-tags-label">Applied Filters:</span>');
            }

            // Remove all filter tags
            tagsContainer.find('.artworks-filter-tag').remove();

            // Helper function to get label text
            function getLabelText(filterType, value) {
                const $input = $('input[name="' + filterType + '"][value="' + value + '"]');
                if ($input.length) {
                    const $span = $input.closest('label').find('span').first();
                    if ($span.length) {
                        const labelText = $span.text().trim();
                        if (labelText && labelText !== String(value)) {
                            return labelText;
                        }
                    }
                }
                return String(value);
            }

            // Add style tags
            if (filterState.style && filterState.style.length > 0) {
                filterState.style.forEach(value => {
                    const label = getLabelText('style', value);
                    if (label) {
                        tagsContainer.append(createFilterTag('style', value, label));
                    }
                });
            }

            // Add medium tags
            if (filterState.medium && filterState.medium.length > 0) {
                filterState.medium.forEach(value => {
                    const label = getLabelText('medium', value);
                    if (label) {
                        tagsContainer.append(createFilterTag('medium', value, label));
                    }
                });
            }

            // Add price tag (only if different from default)
            if (filterState.price.min !== defaultPriceRange.min || filterState.price.max !== defaultPriceRange.max) {
                const priceText = '$' + formatPrice(filterState.price.min) + ' - $' + formatPrice(filterState.price.max);
                tagsContainer.append(createFilterTag('price', 'price', priceText));
            }

            // Show/hide tags container
            const hasTags = tagsContainer.find('.artworks-filter-tag').length > 0;
            if (hasTags) {
                tagsContainer.show();
            } else {
                tagsContainer.hide();
            }
        }

        function createFilterTag(filterType, value, label) {
            const $tag = $('<span>').addClass('artworks-filter-tag').text(label || String(value));
            const $removeBtn = $('<button>')
                .addClass('artworks-filter-tag-remove')
                .attr('type', 'button')
                .attr('data-filter', filterType)
                .attr('data-value', String(value).replace(/"/g, '&quot;'))
                .attr('aria-label', 'Remove filter');
            const $icon = $('<img>')
                .attr('src', '/assets/svg/icons/close-filter.svg')
                .addClass('close-filter')
                .attr('alt', 'close-filter')
                .attr('width', '8')
                .attr('height', '8');
            $removeBtn.append($icon);
            $tag.append($removeBtn);
            return $tag;
        }

        // Remove filter tag
        $(document).on('click', '.artworks-filter-tag-remove', function (e) {
            e.preventDefault();
            e.stopPropagation();
            const filterType = $(this).data('filter');
            const value = String($(this).data('value'));

            if (filterType && value) {
                if (filterType === 'price') {
                    // Reset to default price range
                    filterState.price.min = defaultPriceRange.min;
                    filterState.price.max = defaultPriceRange.max;
                    syncPrice(defaultPriceRange.min, defaultPriceRange.max);
                } else if (Array.isArray(filterState[filterType])) {
                    // Remove from filter state
                    filterState[filterType] = filterState[filterType].filter(v => String(v) !== value);
                    // Uncheck the checkbox - use findInputByValue to handle various name formats
                    findInputByValue(filterType, value).prop('checked', false);
                }

                // Update UI
                updateFilterCount(filterType);
                updateActiveTags();
                updateClearAllVisibility();
                debouncedFilterLoad();
            }
        });

        /* =============================
         * UTILS
         * ============================= */
        function formatPrice(price) {
            return price.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
        }

        updateConsolidatedFilterCount();
        updateActiveTags();
        updateMobileFilterLabels();
        updateSortLabel();
        updateClearAllVisibility();

        // Restore filters from URL on page load (after all functions are defined)
        restoreFiltersFromUrl();
        
        // Initialize price range CSS variables after URL restoration and tab activation
        // Use setTimeout to ensure DOM elements are visible after tab is shown
        setTimeout(function() {
            syncPrice(filterState.price.min, filterState.price.max);
        }, 50);

        /**
         * Intercept artwork card clicks to preserve filter state in URL
         * When user clicks an artwork card, update URL with current filters before navigation
         * This ensures filters are maintained when user presses browser back button
         * Same functionality as gallery details page
         */
        $(document).on('click', '.artwork-item', function(e) {
            // Only handle if we're in the artworks tab
            const currentHash = window.location.hash.replace('#', '');
            if (currentHash && currentHash !== 'artworks' && currentHash !== '') return;
            
            // Get artwork ID from data attribute
            const artworkId = $(this).data('artwork-id');
            if (!artworkId) return;
            
            // Build URL with current filter state
            const params = new URLSearchParams();
            
            if (filterState.sort && filterState.sort !== 'recently-added') {
                params.append('sort', filterState.sort);
            }
            
            if (filterState.style && filterState.style.length > 0) {
                filterState.style.forEach(function(v) { params.append('style[]', v); });
            }
            if (filterState.medium && filterState.medium.length > 0) {
                filterState.medium.forEach(function(v) { params.append('medium[]', v); });
            }
            if (filterState.price && (filterState.price.min !== defaultPriceRange.min || filterState.price.max !== defaultPriceRange.max)) {
                params.append('price_min', filterState.price.min);
                params.append('price_max', filterState.price.max);
            }
            
            // Update URL with filters before navigation
            const baseUrl = window.location.pathname;
            const newUrl = baseUrl + (params.toString() ? '?' + params.toString() : '') + '#artworks';
            window.history.replaceState({ path: newUrl }, '', newUrl);
            
            // Navigate to artwork details page
            window.location.href = '/artwork-details/' + artworkId;
        });

        /* =============================
         * MOBILE FILTER OVERLAY
         * ============================= */
        const mobileFilterOverlay = {
            isOpen: function() {
                const overlay = $('#mobile-filter-overlay');
                return overlay.hasClass('opacity-100');
            },
            open: function() {
                const overlay = $('#mobile-filter-overlay');
                const backdrop = $('#mobile-filter-backdrop');
                const content = $('#mobile-filter-content');

                if (overlay.length && backdrop.length && content.length) {
                    // Mobile checkboxes already share the same name as desktop, so they're already synced
                    // Just ensure price range is synced
                    
                    // Sync price range
                    const minVal = filterState.price.min || defaultPriceRange.min;
                    const maxVal = filterState.price.max || defaultPriceRange.max;
                    $('#mobile-filter-content .artworks-price-range-min').val(minVal);
                    $('#mobile-filter-content .artworks-price-range-max').val(maxVal);
                    $('#price-min-input-mobile').val(minVal);
                    $('#price-max-input-mobile').val(maxVal);
                    $('#price-min-display-mobile').text(formatPrice(minVal) + ' USD');
                    $('#price-max-display-mobile').text(formatPrice(maxVal) + '+ USD');
                    
                    // Update mobile filter labels
                    updateMobileFilterLabels();
                    
                    // Ensure Price Range panel is always active when overlay opens
                    $('#price-mobile-panel').addClass('active');
                    
                    overlay.removeClass('opacity-0 invisible pointer-events-none')
                           .addClass('opacity-100 visible pointer-events-auto');
                    backdrop.removeClass('opacity-0').addClass('opacity-100');
                    content.removeClass('-translate-x-full').addClass('translate-x-0');
                    
                    // Prevent body scroll
                    $('body').css('overflow', 'hidden');
                }
            },
            close: function() {
                const overlay = $('#mobile-filter-overlay');
                const backdrop = $('#mobile-filter-backdrop');
                const content = $('#mobile-filter-content');

                if (overlay.length && backdrop.length && content.length) {
                    overlay.removeClass('opacity-100 visible pointer-events-auto')
                           .addClass('opacity-0 invisible pointer-events-none');
                    backdrop.removeClass('opacity-100').addClass('opacity-0');
                    content.removeClass('translate-x-0').addClass('-translate-x-full');
                    
                    // Restore body scroll
                    $('body').css('overflow', '');
                }
            }
        };

        // Toggle mobile filter overlay
        $('#mobile-filters-toggle').on('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            if (mobileFilterOverlay.isOpen()) {
                mobileFilterOverlay.close();
            } else {
                mobileFilterOverlay.open();
            }
        });

        // Close mobile filter overlay
        $('#mobile-filter-close, #mobile-filter-backdrop').on('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            mobileFilterOverlay.close();
        });

        // See Results button - close overlay and apply filters
        $('#mobile-filter-see-results').on('click', function(e) {
            e.preventDefault();
            mobileFilterOverlay.close();
            debouncedFilterLoad();
        });

        // Mobile filter button clicks
        $(document).on('click', '.artworks-filter-button-mobile', function(e) {
            e.preventDefault();
            e.stopPropagation();
        
            const filterType = $(this).data('filter');
            if (!filterType || typeof filterType !== 'string') return;
        
            const panelId = filterType + '-panel';
            const panelEl = document.getElementById(panelId);
            if (!panelEl) return;
        
            const panel = $(panelEl);
            const isOpen = panel.hasClass('active');
        
            $('#mobile-filter-content .artworks-filter-panel')
                .not('#price-mobile-panel')
                .removeClass('active');
            $('#mobile-filter-content .artworks-filter-button-mobile')
                .removeClass('active');
        
            if (!isOpen) {
                panel.addClass('active');
                $(this).addClass('active');
            }
        });

        // Close mobile panels when clicking outside (but inside overlay)
        $(document).on('click', '#mobile-filter-content', function(e) {
            if (!$(e.target).closest('.artworks-filter-button-wrapper').length && 
                !$(e.target).closest('.artworks-filter-panel').length) {
                // Close all panels except Price Range which is always active
                $('#mobile-filter-content .artworks-filter-panel').not('#price-mobile-panel').removeClass('active');
                $('#mobile-filter-content .artworks-filter-button-mobile').removeClass('active');
            }
        });

        // Mobile checkboxes use same name as desktop, so the existing change handler will work
        // But we need to ensure they trigger the filter update
        // The existing $('.artworks-filter-checkbox').on('change') handler should handle both desktop and mobile

        // Mobile price range handlers
        $(document).on('input', '#mobile-filter-content .artworks-price-range-min', function() {
            const val = parseInt($(this).val()) || defaultPriceRange.min;
            $('#price-min-input-mobile').val(val);
            minRange.val(val);
            minInput.val(val);
            syncPrice(val, maxRange.val());
            debouncedFilterLoad(1);
        });

        $(document).on('input', '#mobile-filter-content .artworks-price-range-max', function() {
            const val = parseInt($(this).val()) || defaultPriceRange.max;
            $('#price-max-input-mobile').val(val);
            maxRange.val(val);
            maxInput.val(val);
            syncPrice(minRange.val(), val);
            debouncedFilterLoad(1);
        });

        // Mobile price inputs
        $(document).on('keyup', '#price-min-input-mobile', function() {
            const val = parseInt($(this).val()) || defaultPriceRange.min;
            if (val >= 0 && val <= MAX_PRICE) {
                $('#mobile-filter-content .artworks-price-range-min').val(val);
                minRange.val(val);
                minInput.val(val);
                syncPrice(val, maxInput.val());
                debouncedFilterLoad();
            }
        });

        $(document).on('keyup', '#price-max-input-mobile', function() {
            const val = parseInt($(this).val()) || defaultPriceRange.max;
            if (val >= 0 && val <= MAX_PRICE) {
                $('#mobile-filter-content .artworks-price-range-max').val(val);
                maxRange.val(val);
                maxInput.val(val);
                syncPrice(minInput.val(), val);
                debouncedFilterLoad();
            }
        });

        // Mobile clear all filters
        $('#clear-all-filters-mobile').on('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            // Uncheck all checkboxes (both desktop and mobile)
            $('.artworks-filter-checkbox').prop('checked', false);
            $('.artworks-filter-radio').prop('checked', false);
            $('input[name="sort"][value="recently-added"]').prop('checked', true);
            
            filterState.style = [];
            filterState.medium = [];
            filterState.artist = [];
            filterState.sort = 'recently-added';
            filterState.price = {
                min: defaultPriceRange.min,
                max: defaultPriceRange.max
            };
            
            syncPrice(defaultPriceRange.min, defaultPriceRange.max);
            
            $('.artworks-filter-count').hide();
            $('#consolidated-filter-count').hide();
            updateActiveTags();
            updateMobileFilterLabels();
            updateClearAllVisibility();
            debouncedFilterLoad();
        });

        /* =============================
         * AJAX LOAD
         * ============================= */
        function loadArtworks(page) {
            const container = $('#artworks-list-container');

            $.ajax({
                url: container.data('list-url'),
                type: 'GET',
                data: {
                    page: page,
                    artist_id: container.data('artist-id'),
                    style: filterState.style || [],
                    medium: filterState.medium || [],
                    price_min: filterState.price.min,
                    price_max: filterState.price.max,
                    sort: filterState.sort || 'recently-added'
                },
                success: function (res) {
                    container.html(res.html);
                    $('#artworks-count').text(res.total);
                    
                    // Make artwork items clickable (artwork IDs are already in data-artwork-id from view)
                    container.find('.artwork-item').each(function() {
                        const $item = $(this);
                        if ($item.data('artwork-id')) {
                            $item.css('cursor', 'pointer');
                        }
                    });
                    
                    // Initialize Masonry after content is loaded
                    setTimeout(function() {
                        initializeMasonry();
                    }, 100);
                }
            });
        }

        /* =============================
         * PAGINATION
         * ============================= */
        $(document).on('click', '.paginate-artwork', function () {
            loadArtworks($(this).data('page'));
        });
        
        // Initialize Masonry on page load
        $(window).on('load', function() {
            setTimeout(function() {
                initializeMasonry();
            }, 300);
        });
        
        // Also try to initialize after a short delay in case window.load already fired
        setTimeout(function() {
            if (!masonryInstance) {
                initializeMasonry();
            }
        }, 500);
    });
}
