import {  handleFilterBarPosition } from '../utils/filter-ui';

/**
 * Artwork Listing Page JavaScript
 * 
 * This file handles all filtering, sorting, and pagination functionality for the main artwork listing page.
 * It manages:
 * - Filter state (style, medium, artist, gallery, size, year, price)
 * - Filter UI updates (counts, tags, mobile labels)
 * - Desktop and mobile filter interactions
 * - Price range filtering with debouncing
 * - Sorting options
 * - Pagination
 * - URL parameter parsing and building
 * - AJAX-based artwork loading
 * 
 * Page Detection:
 * - Only runs on artwork listing page (has #artworks-list-container)
 * - Exits if on gallery details page (has #gallery-artworks-filters)
 * - Exits if on artist details page (has #artistDetailsPage)
 * 
 * @file artwork-listing.js
 * @requires jQuery
 */

$(document).ready(function() {
    'use strict';
    
    /**
     * Normalize filter type name by removing array brackets and mobile suffix
     * @param {string} filterType - The filter type name (e.g., "style[]", "medium-mobile")
     * @returns {string} Normalized filter type (e.g., "style", "medium")
     */
    function normalizeFilterType(filterType) {
        if (!filterType || typeof filterType !== 'string') return '';
        return filterType.replace('[]', '').replace('-mobile', '');
    }
    
    /**
     * Find input elements by name and value, handling various name formats
     * Supports: name, name[], name-mobile, name-mobile[]
     * @param {string} name - The input name attribute
     * @param {string|number} value - The input value to match
     * @returns {jQuery} jQuery object containing matching input elements
     */
    function findInputByValue(name, value) {
        name = normalizeFilterType(name);
        if (!name) return $();

        return $('input').filter(function () {
            var inputName = $(this).attr('name');
            if (!inputName) return false;

            // Check for various name formats: name, name[], name-mobile, name-mobile[]
            var matchesName = (
                inputName === name ||
                inputName === name + '[]' ||
                inputName === name + '-mobile' ||
                inputName === name + '-mobile[]'
            );
            
            return matchesName && String($(this).val()) === String(value);
        });
    }
    
    
    /**
     * Page Detection - Only run on artwork listing page
     * Exit if on gallery details page or artist details page to prevent conflicts
     */
    // Preserve fromHome flag from URL - needed for "No works yet — explore all artworks." message
    const urlParams = new URLSearchParams(window.location.search);
    const fromHome = urlParams.get('fromHome') === '1' ? '1' : null;
    
    var artworksContainerEl = document.getElementById('artworks-list-container');
    var galleryArtworksFiltersEl = document.getElementById('gallery-artworks-filters');
    var artistDetailsPageEl = document.getElementById('artistDetailsPage');
    if (!artworksContainerEl || galleryArtworksFiltersEl || artistDetailsPageEl) {
        // Exit if not on artwork listing page OR if on gallery details page OR if on artist details page
        return;
    }
    // Track if this is a user-initiated action (not initial page load)
    var isUserAction = false;
    var isPriceTouched = false;
    
    // Masonry instance
    var masonryInstance = null;
    
    /**
     * Initialize Masonry layout
     */
    function initializeMasonry() {
        var grid = document.querySelector('.masonry-grid');
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
    // Get initial filter state from window object (passed from Blade)
    var initialFilterState = window.artworkFilterState || {
        style: [],
        medium: [],
        artist: [],
        gallery: [],
        size: [],
        year: [],
        price: {
            min: 0,
            max: 20000
        },
        sort: 'recently-added'
    };
    // Default price range
    var defaultPriceRange = {
        min: 0,
        max: 20000
    };
    
    // Filter state
    var filterState = {
        style: Array.isArray(initialFilterState.style) ? initialFilterState.style : (initialFilterState.style ? [initialFilterState.style] : []),
        medium: Array.isArray(initialFilterState.medium) ? initialFilterState.medium : (initialFilterState.medium ? [initialFilterState.medium] : []),
        artist: Array.isArray(initialFilterState.artist) ? initialFilterState.artist : (initialFilterState.artist ? [initialFilterState.artist] : []),
        gallery: Array.isArray(initialFilterState.gallery) ? initialFilterState.gallery : (initialFilterState.gallery ? [initialFilterState.gallery] : []),
        size: Array.isArray(initialFilterState.size) ? initialFilterState.size : (initialFilterState.size ? [initialFilterState.size] : []),
        year: Array.isArray(initialFilterState.year) ? initialFilterState.year : (initialFilterState.year ? [initialFilterState.year] : []),
        price: initialFilterState.price || {
            min: 0,
            max: 20000
        },
        sort: initialFilterState.sort || 'recently-added'
    };
                
   

    // Parse URL parameters to update filterState
    function parseUrlParamsOnLoad() {
        const params = new URLSearchParams(window.location.search);
        
        // SEO style parameter
        const seoStyle = params.get('style');
        if (seoStyle) {
            const styleValue = slugToStyle(seoStyle);
            if (styleValue && !filterState.style.includes(styleValue)) {
                filterState.style.push(styleValue);
            }
        }
        
        // PRICE
        const priceMin = params.get('filterState[price][min]');
        const priceMax = params.get('filterState[price][max]');
        if (priceMin !== null) {
            filterState.price.min = parseInt(priceMin) || defaultPriceRange.min;
        }
        if (priceMax !== null) {
            filterState.price.max = parseInt(priceMax) || defaultPriceRange.max;
        }
        if (
            params.has('filterState[price][min]') ||
            params.has('filterState[price][max]')
        ) {
            isPriceTouched = true; 
        }
        
        // STYLE
        params.getAll('filterState[style][]').forEach(v => {
            if (v && !filterState.style.includes(v)) {
                filterState.style.push(v);
            }
        });
    
        // MEDIUM - handle both indexed format [0] and empty bracket format []
        // First try indexed format (filterState[medium][0], filterState[medium][1], etc.)
        let mediumIndex = 0;
        let mediumValue = params.get('filterState[medium][' + mediumIndex + ']');
        while (mediumValue !== null) {
            if (mediumValue && !filterState.medium.includes(mediumValue)) {
                filterState.medium.push(mediumValue);
            }
            mediumIndex++;
            mediumValue = params.get('filterState[medium][' + mediumIndex + ']');
        }
        // Also handle empty bracket format (filterState[medium][])
        params.getAll('filterState[medium][]').forEach(v => {
            if (v && !filterState.medium.includes(v)) {
                filterState.medium.push(v);
            }
        });
    
        // ARTIST
        params.getAll('filterState[artist][]').forEach(v => {
            if (v && !filterState.artist.includes(v)) {
                filterState.artist.push(v);
            }
        });
    
        // GALLERY
        params.getAll('filterState[gallery][]').forEach(v => {
            if (v && !filterState.gallery.includes(v)) {
                filterState.gallery.push(v);
            }
        });
    
        // SIZE
        params.getAll('filterState[size][]').forEach(v => {
            if (v && !filterState.size.includes(v)) {
                filterState.size.push(v);
            }
        });
    
        // YEAR
        params.getAll('filterState[year][]').forEach(v => {
            if (v && !filterState.year.includes(v)) {
                filterState.year.push(v);
            }
        });
    
        // SORT
        const sort = params.get('filterState[sort]') || params.get('sort');
        if (sort) {
            filterState.sort = sort;
        }
    }
    
    // Parse URL params after filterState is initialized
    parseUrlParamsOnLoad();

    // Get route URL and CSRF token from window object
    var artworksRoute = window.location.pathname || '/artworks-list';
    var csrfToken = window.csrfToken || '';
    
    // Convert style name to SEO-friendly slug
    function styleToSlug(styleName) {
        var slugMap = {
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
    function slugToStyle(slug) {
        const map = {
            'arabic-calligraphy': 'Arabic Calligraphy (Modern Or Traditional)',
            'miniature-art': 'Miniature Art',
            'ornamentation': 'Ornamentation',
            'figurative': 'Figurative',
            'abstract': 'Abstract',
            'orientalist-art': 'Orientalist Art',
            'portrait': 'Portrait',
            'landscape': 'Landscape',
            'modern': 'Modern',
            'contemporary': 'Contemporary',
            'pop-art': 'Pop Art',
            'photography': 'Photography',
            'digital-art': 'Digital Art / New Media'
        };
        return map[slug] || null;
    }
    
    // Build SEO-friendly URL for browser navigation (when style filter is applied)
    function buildArtworksUrl(page) {
        var baseUrl = '/artworks-list';
        var params = new URLSearchParams();
        
        // Preserve fromHome parameter in URL building
        if (fromHome) {
            params.append('fromHome', fromHome);
        }
        
        // Add style as SEO-friendly slug if only one style is selected
        if (filterState.style && filterState.style.length === 1) {
            var styleSlug = styleToSlug(filterState.style[0]);
            params.append('style', styleSlug);
        } else if (filterState.style && filterState.style.length > 1) {
            // Multiple styles - use filterState format (fallback)
            filterState.style.forEach(function(value, index) {
                params.append('filterState[style][' + index + ']', value);
            });
        }
        
        // Add other filters if needed (using filterState format for complex filters)
        if (filterState.medium && filterState.medium.length > 0) {
            filterState.medium.forEach(function(value, index) {
                params.append('filterState[medium][' + index + ']', value);
            });
        }
        
        if (filterState.artist && filterState.artist.length > 0) {
            filterState.artist.forEach(function(value, index) {
                params.append('filterState[artist][' + index + ']', value);
            });
        }
        
        if (filterState.gallery && filterState.gallery.length > 0) {
            filterState.gallery.forEach(function(value, index) {
                params.append('filterState[gallery][' + index + ']', value);
            });
        }
        
        if (filterState.size && filterState.size.length > 0) {
            filterState.size.forEach(function(value, index) {
                params.append('filterState[size][' + index + ']', value);
            });
        }
        
        if (filterState.year && filterState.year.length > 0) {
            filterState.year.forEach(function(value, index) {
                params.append('filterState[year][' + index + ']', value);
            });
        }
        
        if (filterState.price && (filterState.price.min !== defaultPriceRange.min || filterState.price.max !== defaultPriceRange.max)) {
            params.append('filterState[price][min]', filterState.price.min);
            params.append('filterState[price][max]', filterState.price.max);
        }
        
        if (filterState.sort && filterState.sort !== 'recently-added') {
            params.append('filterState[sort]', filterState.sort);
        }
        
        if (page && page > 1) {
            params.append('page', page);
        }
        
        var queryString = params.toString();
        return queryString ? baseUrl + '?' + queryString : baseUrl;
    }

   

    // Toggle filter panels (desktop only - exclude mobile buttons)
    $('.artworks-filter-button').not('.artworks-filter-button-mobile').on('click', function(e) {

        e.stopPropagation();
    
        var filterType = $(this).data('filter');
        if (!filterType || typeof filterType !== 'string') return;
    
        var panelId = filterType + '-panel';
        var panelEl = document.getElementById(panelId);
        if (!panelEl) return;
    
        var panel = $(panelEl);
        var isOpen = panel.hasClass('active');
    
        $('.artworks-filter-panel').not('#mobile-filter-content .artworks-filter-panel').removeClass('active');
        $('.artworks-filter-button').not('.artworks-filter-button-mobile').removeClass('active');
    
        if (!isOpen) {
            panel.addClass('active');
            $(this).addClass('active');
        }
    });

    // Close panels when clicking outside (desktop only)
    $(document).on('click', function(e) {
        // Only handle desktop panels, not mobile overlay panels
        if (!$(e.target).closest('#mobile-filter-content').length) {
            if (!$(e.target).closest('.artworks-filter-button-wrapper').length) {
                $('.artworks-filter-panel').not('#mobile-filter-content .artworks-filter-panel').removeClass('active');
                $('.artworks-filter-button').not('.artworks-filter-button-mobile').removeClass('active');
            }
        }
    });

    // Handle checkbox changes - scope to artwork listing page only
    // Use document delegation but check we're on artwork listing page
    $(document).on('change', '.artworks-filter-checkbox', function() {

        // Only handle if we're on artwork listing page, not gallery details
        if (!$('#artworks-list-container').length || $('#gallery-artworks-filters').length) {
            return;
        }
        isUserAction = true;
        var rawFilterType = $(this).attr('name');
        var actualFilterType = normalizeFilterType(rawFilterType);
        if (!actualFilterType) return;
    
        var value = String($(this).val());
        var isChecked = $(this).is(':checked');
    
        if (isChecked) {
            if (!filterState[actualFilterType].includes(value)) {
                filterState[actualFilterType].push(value);
            }
        } else {
            filterState[actualFilterType] =
                filterState[actualFilterType].filter(v => v !== value);
        }
    
        updateFilterCount(actualFilterType);
        updateActiveTags();
        updateClearAllVisibility();
        getArtWork(1);
    });
    

    // Handle radio changes - scope to artwork listing page only
    // Use document delegation but check we're on artwork listing page
    $(document).on('change', '.artworks-filter-radio', function() {
        // Only handle if we're on artwork listing page, not gallery details
        if (!$('#artworks-list-container').length || $('#gallery-artworks-filters').length) {
            return;
        }
        isUserAction = true;
        var sortValue = $(this).val();
        filterState.sort = sortValue;
        
        // Update sort label
        var sortLabel = $(this).closest('label').find('span').last().text().trim();
        $('#sort-label').text(sortLabel);
        
        // Uncheck all radio buttons and check the selected one
        $('.artworks-filter-radio').prop('checked', false);
        $(this).prop('checked', true);
        
        // Sync mobile radio buttons if they exist - use filter() instead of attribute selector
        if (sortValue) {
            $('input[name="sort-mobile"]').filter(function() {
                return $(this).val() === sortValue;
            }).prop('checked', true);
        }
        $('#sort-panel').removeClass('active');
        updateActiveTags();
        
        // Apply sort immediately
        getArtWork(1);
    });

    // Price range slider
    var minPriceInput = $('#price-min-input');
    var maxPriceInput = $('#price-max-input');
    var minPriceRange = $('.artworks-price-range-min');
    var maxPriceRange = $('.artworks-price-range-max');
    var minPriceDisplay = $('#price-min-display');
    var maxPriceDisplay = $('#price-max-display');

    function updatePriceRange() {
        var min = parseInt(minPriceRange.val());
        var max = parseInt(maxPriceRange.val());
        var maxValue = 20000;

        if (min >= max) {
            minPriceRange.val(max - 100);
            minPriceInput.val(max - 100);
        }

        filterState.price.min = parseInt(minPriceRange.val());
        filterState.price.max = parseInt(maxPriceRange.val());

        minPriceInput.val(filterState.price.min);
        maxPriceInput.val(filterState.price.max);
        minPriceDisplay.text(formatPrice(filterState.price.min) + ' USD');
        maxPriceDisplay.text(formatPrice(filterState.price.max) + '+ USD');

        // Sync mobile price displays
        $('#price-min-input-mobile').val(filterState.price.min);
        $('#price-max-input-mobile').val(filterState.price.max);
        $('#price-min-display-mobile').text(formatPrice(filterState.price.min) + ' USD');
        $('#price-max-display-mobile').text(formatPrice(filterState.price.max) + '+ USD');
        $('#mobile-filter-content .artworks-price-range-min').val(filterState.price.min);
        $('#mobile-filter-content .artworks-price-range-max').val(filterState.price.max);

        // Update CSS custom properties for range visualization
        var minPercent = (filterState.price.min / maxValue) * 100;
        var maxPercent = (filterState.price.max / maxValue) * 100;
        $('.artworks-price-slider').css({
            '--range-min': minPercent + '%',
            '--range-max': maxPercent + '%'
        });
    }

    // Debounce function for price filter
    var priceFilterTimeout;
    function applyPriceFilter() {
        isUserAction = true;
        isPriceTouched = true;
        clearTimeout(priceFilterTimeout);
        priceFilterTimeout = setTimeout(function() {
            getArtWork(1);
        }, 500); // Wait 500ms after user stops adjusting
    }
    
    // Slider to input sync (when slider changes)
    minPriceRange.on('input', function() {
        isPriceTouched = true;
        var val = $(this).val();
        minPriceInput.val(val);
        filterState.price.min = parseInt(val);
        // Update display only, don't trigger full update
        $('#price-min-display').text(formatPrice(parseInt(val)) + ' USD');
        $('#price-min-display-mobile').text(formatPrice(parseInt(val)) + ' USD');
        // Update CSS for range visualization
        var maxValue = 20000;
        var minPercent = (parseInt(val) / maxValue) * 100;
        var maxPercent = (filterState.price.max / maxValue) * 100;
        $('.artworks-price-slider').css({
            '--range-min': minPercent + '%',
            '--range-max': maxPercent + '%'
        });
        updateFilterCount('price');
        updateActiveTags();
        updateClearAllVisibility();
        applyPriceFilter();
    });

    maxPriceRange.on('input', function() {
        isPriceTouched = true;
        var val = $(this).val();
        maxPriceInput.val(val);
        filterState.price.max = parseInt(val);
        // Update display only, don't trigger full update
        $('#price-max-display').text(formatPrice(parseInt(val)) + '+ USD');
        $('#price-max-display-mobile').text(formatPrice(parseInt(val)) + '+ USD');
        // Update CSS for range visualization
        var maxValue = 20000;
        var minPercent = (filterState.price.min / maxValue) * 100;
        var maxPercent = (parseInt(val) / maxValue) * 100;
        $('.artworks-price-slider').css({
            '--range-min': minPercent + '%',
            '--range-max': maxPercent + '%'
        });
        updateFilterCount('price');
        updateActiveTags();
        updateClearAllVisibility();
        applyPriceFilter();
    });

    // Input to slider sync (when typing in input fields)
    minPriceInput.on('input', function() {
        isPriceTouched = true;
        var val = $(this).val();
        // Only update slider if value is valid
        if (val !== '' && !isNaN(val)) {
            var numVal = parseInt(val);
            if (numVal >= 0 && numVal <= 20000) {
                minPriceRange.val(numVal);
                filterState.price.min = numVal;
                // Update display
                $('#price-min-display').text(formatPrice(numVal) + ' USD');
                $('#price-min-display-mobile').text(formatPrice(numVal) + ' USD');
                // Update CSS for range visualization
                var maxValue = 20000;
                var minPercent = (numVal / maxValue) * 100;
                var maxPercent = (filterState.price.max / maxValue) * 100;
                $('.artworks-price-slider').css({
                    '--range-min': minPercent + '%',
                    '--range-max': maxPercent + '%'
                });
                updateFilterCount('price');
                updateActiveTags();
                updateClearAllVisibility();
            }
        }
        // Debounced filter application
        applyPriceFilter();
    });

    maxPriceInput.on('input', function() {
        isPriceTouched = true;
        var val = $(this).val();
        // Only update slider if value is valid
        if (val !== '' && !isNaN(val)) {
            var numVal = parseInt(val);
            if (numVal >= 0 && numVal <= 20000) {
                maxPriceRange.val(numVal);
                filterState.price.max = numVal;
                // Update display
                $('#price-max-display').text(formatPrice(numVal) + '+ USD');
                $('#price-max-display-mobile').text(formatPrice(numVal) + '+ USD');
                // Update CSS for range visualization
                var maxValue = 20000;
                var minPercent = (filterState.price.min / maxValue) * 100;
                var maxPercent = (numVal / maxValue) * 100;
                $('.artworks-price-slider').css({
                    '--range-min': minPercent + '%',
                    '--range-max': maxPercent + '%'
                });
                updateFilterCount('price');
                updateActiveTags();
                updateClearAllVisibility();
            }
        }
        // Debounced filter application
        applyPriceFilter();
    });
    
    // Full update on blur (when user finishes editing)
    minPriceInput.on('blur', function() {
        isUserAction = true;
        isPriceTouched = true;
        var val = $(this).val();
        if (val === '' || isNaN(val)) {
            // Reset to current filterState value if invalid
            $(this).val(filterState.price.min);
        } else {
            var numVal = parseInt(val);
            if (numVal < 0) {
                $(this).val(0);
                filterState.price.min = 0;
            } else if (numVal > 20000) {
                $(this).val(20000);
                filterState.price.min = 20000;
            } else {
                filterState.price.min = numVal;
            }
            // Ensure min doesn't exceed max
            if (filterState.price.min >= filterState.price.max) {
                filterState.price.min = filterState.price.max - 100;
                $(this).val(filterState.price.min);
            }
            minPriceRange.val(filterState.price.min);
            updatePriceRange();
        }
        clearTimeout(priceFilterTimeout);
        getArtWork(1);
    });
    
    maxPriceInput.on('blur', function() {
        isUserAction = true;
        isPriceTouched = true;
        var val = $(this).val();
        if (val === '' || isNaN(val)) {
            // Reset to current filterState value if invalid
            $(this).val(filterState.price.max);
        } else {
            var numVal = parseInt(val);
            if (numVal < 0) {
                $(this).val(0);
                filterState.price.max = 0;
            } else if (numVal > 20000) {
                $(this).val(20000);
                filterState.price.max = 20000;
            } else {
                filterState.price.max = numVal;
            }
            // Ensure max doesn't go below min
            if (filterState.price.max <= filterState.price.min) {
                filterState.price.max = filterState.price.min + 100;
                $(this).val(filterState.price.max);
            }
            maxPriceRange.val(filterState.price.max);
            updatePriceRange();
        }
        clearTimeout(priceFilterTimeout);
        getArtWork(1);
    });

    function formatPrice(price) {
        return price.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
    }

    // Update filter count badges
    function updateFilterCount(filterType) {
        // Validate input
        if (!filterType || typeof filterType !== 'string') {
            return;
        }
        
        filterType = normalizeFilterType(filterType);
        if (!filterType || filterType.trim() === '') {
            return;
        }

        var count = 0;

        if (filterType === 'price') {
            if (
                isPriceTouched &&
                (filterState.price.min !== defaultPriceRange.min ||
                 filterState.price.max !== defaultPriceRange.max)
            ) {
                count = 1;
            }
        } else if (Array.isArray(filterState[filterType])) {
            count = filterState[filterType].length;
        }

        // Safely create selector - use getElementById for better browser support
        // Validate that filterType is not empty before creating ID
        if (!filterType || filterType.length === 0) {
            return;
        }
        
        try {
            var countElementId = filterType + '-count';
            // Additional validation to ensure ID is valid
            if (!countElementId || countElementId === '-count' || countElementId.startsWith('-')) {
                return;
            }
            
            var countElement = document.getElementById(countElementId);
            
            if (!countElement) return;

            if (count > 0) {
                countElement.textContent = '(' + count + ')';
                countElement.style.display = 'inline';
            } else {
                countElement.textContent = '';
                countElement.style.display = 'none';
            }
        } catch (e) {
            console.warn('Error updating filter count for:', filterType, e);
            return;
        }

        updateConsolidatedFilterCount();
    }
    

    // Update consolidated filter count for mobile button
    function updateConsolidatedFilterCount() {
        try {
            var totalCount = filterState.style.length + filterState.medium.length + filterState.artist.length + 
                          filterState.gallery.length;
            var consolidatedCountEl = document.getElementById('consolidated-filter-count');
            if (consolidatedCountEl) {
                if (totalCount > 0) {
                    consolidatedCountEl.textContent = '(' + totalCount + ')';
                    consolidatedCountEl.style.display = 'inline';
                } else {
                    consolidatedCountEl.textContent = '';
                    consolidatedCountEl.style.display = 'none';
                }
            }
        } catch (e) {
            console.warn('Error updating consolidated filter count:', e);
        }
    }

    // Initialize all filter counts on page load
    function initializeAllFilterCounts() {
        try {
            // Only update counts for filter types that exist in the DOM
            var validFilterTypes = ['style', 'medium', 'artist', 'gallery', 'price'];
            
            validFilterTypes.forEach(function(filterType) {
                try {
                    updateFilterCount(filterType);
                } catch (e) {
                    console.warn('Error updating filter count for', filterType, e);
                }
            });
            
            // Check if size and year filters exist before updating
            if (document.getElementById('size-count')) {
                try {
                    updateFilterCount('size');
                } catch (e) {
                    console.warn('Error updating size filter count:', e);
                }
            }
            if (document.getElementById('year-count')) {
                try {
                    updateFilterCount('year');
                } catch (e) {
                    console.warn('Error updating year filter count:', e);
                }
            }
        } catch (e) {
            console.error('Error in initializeAllFilterCounts:', e);
        }
    }

    // Update active filter tags
    function updateActiveTags() {
        // Don't run on gallery details page
        if (document.getElementById('gallery-artworks-filters')) {
            return;
        }
        
        var tagsContainerEl = document.getElementById('active-filter-tags');
        if (!tagsContainerEl) return;
        var tagsContainer = $(tagsContainerEl);
        
        // Ensure "Applied Filters:" label exists in desktop
        if (tagsContainer.find('.artworks-filter-tags-label').length === 0) {
            tagsContainer.prepend('<span class="artworks-filter-tags-label">Applied Filters:</span>');
        }
        
        // Remove only filter tags, preserve the label
        tagsContainer.find('.artworks-filter-tag').remove();

        // Helper function to get label text safely
        function getLabelText(filterType, value) {
            if (!filterType || value === undefined || value === null) return '';
            
            try {
                // Try to find the input by value
                var $input = findInputByValue(filterType, value).first();
                if ($input.length) {
                    var $span = $input.closest('label').find('span').first();
                    if ($span.length) {
                        var labelText = $span.text().trim();
                        // Only return if we got a meaningful label (not empty and not just the value)
                        if (labelText && labelText !== String(value)) {
                            return labelText;
                        }
                    }
                }
                
                // For artist and gallery, try to find by checking all inputs with the same name
                // Use filter() instead of attribute selector to avoid [] issues
                if (filterType === 'artist' || filterType === 'gallery') {
                    var $allInputs = $('input').filter(function() {
                        var inputName = $(this).attr('name');
                        return (inputName === filterType || inputName === filterType + '[]') &&
                               String($(this).val()) === String(value);
                    });
                    if ($allInputs.length) {
                        var $labelSpan = $allInputs.first().closest('label').find('span').first();
                        if ($labelSpan.length) {
                            var text = $labelSpan.text().trim();
                            if (text && text !== String(value)) {
                                return text;
                            }
                        }
                    }
                }
            } catch (e) {
                console.warn('Error getting label text for', filterType, value, e);
            }
            
            // Fallback: use the value itself (but this should be avoided)
            return String(value);
        }

        // Add style tags
        if (Array.isArray(filterState.style)) {
            filterState.style.forEach(function(value) {
                if (value !== undefined && value !== null) {
                    try {
                        var label = getLabelText('style', value);
                        if (label) {
                            var $tag = createFilterTag('style', value, label);
                            if ($tag && $tag.length) {
                                tagsContainer.append($tag);
                            }
                        }
                    } catch (e) {
                        console.warn('Error creating style tag:', e);
                    }
                }
            });
        }

        // Add medium tags
        if (Array.isArray(filterState.medium)) {
            filterState.medium.forEach(function(value) {
                if (value !== undefined && value !== null) {
                    try {
                        var label = getLabelText('medium', value);
                        if (label) {
                            var $tag = createFilterTag('medium', value, label);
                            if ($tag && $tag.length) {
                                tagsContainer.append($tag);
                            }
                        }
                    } catch (e) {
                        console.warn('Error creating medium tag:', e);
                    }
                }
            });
        }

        // Add artist tags
        if (Array.isArray(filterState.artist)) {
            filterState.artist.forEach(function(value) {
                if (value !== undefined && value !== null) {
                    try {
                        var label = getLabelText('artist', value);
                        if (label) {
                            var $tag = createFilterTag('artist', value, label);
                            if ($tag && $tag.length) {
                                tagsContainer.append($tag);
                            }
                        }
                    } catch (e) {
                        console.warn('Error creating artist tag:', e);
                    }
                }
            });
        }

        // Add gallery tags
        if (Array.isArray(filterState.gallery)) {
            filterState.gallery.forEach(function(value) {
                if (value !== undefined && value !== null) {
                    try {
                        var label = getLabelText('gallery', value);
                        if (label) {
                            var $tag = createFilterTag('gallery', value, label);
                            if ($tag && $tag.length) {
                                tagsContainer.append($tag);
                            }
                        }
                    } catch (e) {
                        console.warn('Error creating gallery tag:', e);
                    }
                }
            });
        }

        // Add size tags
        if (Array.isArray(filterState.size)) {
            filterState.size.forEach(function(value) {
                if (value !== undefined && value !== null) {
                    try {
                        var label = getLabelText('size', value);
                        if (label) {
                            var $tag = createFilterTag('size', value, label);
                            if ($tag && $tag.length) {
                                tagsContainer.append($tag);
                            }
                        }
                    } catch (e) {
                        console.warn('Error creating size tag:', e);
                    }
                }
            });
        }

        // Add year tags
        if (Array.isArray(filterState.year)) {
            filterState.year.forEach(function(value) {
                if (value !== undefined && value !== null) {
                    try {
                        var label = getLabelText('year', value);
                        if (label) {
                            var $tag = createFilterTag('year', value, label);
                            if ($tag && $tag.length) {
                                tagsContainer.append($tag);
                            }
                        }
                    } catch (e) {
                        console.warn('Error creating year tag:', e);
                    }
                }
            });
        }

        // Add price tag (only if different from default)
        if (
            isPriceTouched &&
            (filterState.price.min !== defaultPriceRange.min ||
             filterState.price.max !== defaultPriceRange.max)
        ) {
            var priceText = '$' + formatPrice(filterState.price.min) + ' - $' + formatPrice(filterState.price.max);
            tagsContainer.append(createFilterTag('price', 'price', priceText));
        }

        // Show/hide tags container (check if there are any filter tags, excluding the label)
        var hasTags = tagsContainer.find('.artworks-filter-tag').length > 0;
        if (hasTags) {
            tagsContainer.show();
        } else {
            tagsContainer.hide();
        }

        // Update consolidated filter count
        updateConsolidatedFilterCount();
        // Update mobile filter labels
        updateMobileFilterLabels();
    }

    function createFilterTag(filterType, value, label) {
        // Validate inputs
        if (!filterType || typeof filterType !== 'string' || filterType.trim() === '') {
            console.warn('Invalid filterType in createFilterTag:', filterType);
            return $('<span>'); // Return empty span to prevent errors
        }
        
        if (value === undefined || value === null) {
            console.warn('Invalid value in createFilterTag:', value);
            return $('<span>'); // Return empty span to prevent errors
        }
        
        // Ensure label is not duplicated - use text() instead of html() for the label part
        var $tag = $('<span>').addClass('artworks-filter-tag');
        $tag.text(label || String(value));
        
        // Safely create the button with validated attributes
        var buttonHtml = ' <button class="artworks-filter-tag-remove" data-filter="' + 
            String(filterType).replace(/"/g, '&quot;') + 
            '" data-value="' + String(value).replace(/"/g, '&quot;') + 
            '" aria-label="Remove filter"><img src="assets/svg/icons/close-filter.svg" class="close-filter" alt="close-filter" width="8" height="8" /></button>';
        $tag.append(buttonHtml);
        return $tag;
    }

    // Remove filter tag
    $(document).on('click', '.artworks-filter-tag-remove', function(e) {
        isUserAction = true;
        e.stopPropagation();
        e.preventDefault();
        
        try {
            var filterType = $(this).data('filter');
            var value = $(this).data('value');
            
            // Validate filterType and value
            if (!filterType || filterType === '' || value === undefined || value === null) {
                console.warn('Invalid filter type or value:', filterType, value);
                return;
            }
            
            value = String(value); // Convert to string for consistent comparison

            if (filterType === 'price') {
                // Reset to default price range
                filterState.price.min = defaultPriceRange.min;
                filterState.price.max = defaultPriceRange.max;
                isPriceTouched = false;
                if (minPriceRange && minPriceRange.length) {
                    minPriceRange.val(defaultPriceRange.min);
                }
                if (maxPriceRange && maxPriceRange.length) {
                    maxPriceRange.val(defaultPriceRange.max);
                }
                if (minPriceInput && minPriceInput.length) {
                    minPriceInput.val(defaultPriceRange.min);
                }
                if (maxPriceInput && maxPriceInput.length) {
                    maxPriceInput.val(defaultPriceRange.max);
                }
                updatePriceRange();
            } else if (filterState[filterType] && Array.isArray(filterState[filterType])) {
                // Remove from filterState - compare as strings to handle type mismatches
                filterState[filterType] = filterState[filterType].filter(function(v) { return String(v) !== value; });
                
                // Uncheck both desktop and mobile checkboxes
                findInputByValue(filterType, value).prop('checked', false);
                
                updateFilterCount(filterType);
            }

            updateActiveTags();
            getArtWork(1);
        } catch (error) {
            console.error('Error removing filter tag:', error);
        }
    });

    // Clear all filters
    $(document).on('click', '#clear-all-filters', function(e) {
        isUserAction = true;
        e.preventDefault();
        e.stopPropagation();

        try {
            // Reset filter state to defaults
            filterState.style = [];
            filterState.medium = [];
            filterState.artist = [];
            filterState.gallery = [];
            filterState.size = [];
            filterState.year = [];
            filterState.price = {
                min: defaultPriceRange.min,
                max: defaultPriceRange.max
            };
            isPriceTouched = false;

            filterState.sort = 'recently-added';

            // Uncheck all checkboxes
            $('.artworks-filter-checkbox').prop('checked', false);

            // Reset radio buttons
            $('.artworks-filter-radio').prop('checked', false);
            $('.artworks-filter-radio').filter(function () {
                return $(this).val() === 'recently-added';
            }).prop('checked', true);
            
            var sortLabel = $('#sort-label');
            if (sortLabel.length) {
                sortLabel.text('Recently Added');
            }

            // Reset price range inputs to default values
            if (minPriceRange && minPriceRange.length) {
                minPriceRange.val(defaultPriceRange.min);
            }
            if (maxPriceRange && maxPriceRange.length) {
                maxPriceRange.val(defaultPriceRange.max);
            }
            if (minPriceInput && minPriceInput.length) {
                minPriceInput.val(defaultPriceRange.min);
            }
            if (maxPriceInput && maxPriceInput.length) {
                maxPriceInput.val(defaultPriceRange.max);
            }

            // Update price display
            updatePriceRange();

            // Update all filter counts
            initializeAllFilterCounts();

            // Clear active filter tags
            updateActiveTags();

            // Close all filter panels
            $('.artworks-filter-panel').removeClass('active');
            $('.artworks-filter-button').removeClass('active');

            // Update mobile labels
            updateMobileFilterLabels();
            
            // Update clear all visibility
            updateClearAllVisibility();
            
            // Update URL and reload artworks
            getArtWork(1);
        } catch (error) {
            console.error('Error in clear all filters:', error);
        }
    });

    // Initialize sort label and radio button state
    function initializeSortState() {
        var currentSort = filterState.sort || 'recently-added';
        if (currentSort) {
            var $selectedRadio = $('.artworks-filter-radio').filter(function () {
                return $(this).val() === currentSort;
            }).first();
            if ($selectedRadio.length) {
                $selectedRadio.prop('checked', true);
                var sortLabel = $selectedRadio.closest('label').find('span').last().text().trim();
                $('#sort-label').text(sortLabel);
            }
        }
    }

    // Initialize checkboxes from filterState on page load
    function initializeCheckboxesFromFilterState() {
        // Initialize style checkboxes
        if (filterState.style && filterState.style.length > 0) {
            filterState.style.forEach(function(value) {
                findInputByValue('style', value).prop('checked', true);
                 findInputByValue('style-mobile', value).prop('checked', true);
            });
        }
        
       // Initialize medium checkboxes
if (filterState.medium && filterState.medium.length > 0) {
    filterState.medium.forEach(function(value) {
        findInputByValue('medium', value).prop('checked', true);
        findInputByValue('medium-mobile', value).prop('checked', true);
    });
}

// Initialize artist checkboxes
if (filterState.artist && filterState.artist.length > 0) {
    filterState.artist.forEach(function(value) {
        findInputByValue('artist', value).prop('checked', true);
        findInputByValue('artist-mobile', value).prop('checked', true);
    });
}

// Initialize gallery checkboxes
if (filterState.gallery && filterState.gallery.length > 0) {
    filterState.gallery.forEach(function(value) {
        findInputByValue('gallery', value).prop('checked', true);
        findInputByValue('gallery-mobile', value).prop('checked', true);
    });
}

// Initialize size checkboxes
if (filterState.size && filterState.size.length > 0) {
    filterState.size.forEach(function(value) {
        findInputByValue('size', value).prop('checked', true);
        findInputByValue('size-mobile', value).prop('checked', true);
    });
}

// Initialize year checkboxes
if (filterState.year && filterState.year.length > 0) {
    filterState.year.forEach(function(value) {
        findInputByValue('year', value).prop('checked', true);
        findInputByValue('year-mobile', value).prop('checked', true);
    });
}

        // Update filter counts and tags after initializing checkboxes
        updateFilterCount('style');
        updateFilterCount('medium');
        updateFilterCount('artist');
        updateFilterCount('gallery');
        updateFilterCount('size');
        updateFilterCount('year');
        updateActiveTags();
    }
    
    // Initialize checkboxes on page load
    // initializeCheckboxesFromFilterState();
    
    // // Initialize all filter counts on page load
    // initializeAllFilterCounts();
    // updateConsolidatedFilterCount();

    // Mobile Filter Overlay Management
    var mobileFilterOverlay = {
        isOpen: function() {
            var overlay = $('#mobile-filter-overlay');
            return overlay.hasClass('opacity-100');
        },
        open: function() {
            var overlay = $('#mobile-filter-overlay');
            var backdrop = $('#mobile-filter-backdrop');
            var content = $('#mobile-filter-content');

            if (overlay.length && backdrop.length && content.length) {
                // Sync mobile checkboxes with desktop before opening
                syncMobileFiltersOnLoad();
                updateMobileFilterLabels();
                syncMobilePriceRange();
                
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
            var overlay = $('#mobile-filter-overlay');
            var backdrop = $('#mobile-filter-backdrop');
            var content = $('#mobile-filter-content');

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
        if (!$('#artworks-list-filters').length) return;
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

    // See Results button - close overlay
    $('#mobile-filter-see-results').on('click', function(e) {
        e.preventDefault();
        mobileFilterOverlay.close();
    });

    // Close overlay on escape key
    $(document).on('keydown', function(e) {
        if (e.key === 'Escape' && mobileFilterOverlay.isOpen()) {
            mobileFilterOverlay.close();
        }
    });

    // Sync mobile filter checkboxes with desktop
    function syncMobileFiltersOnLoad() {
        $('.artworks-filter-checkbox').each(function() {
            var rawFilterType = $(this).attr('name');
            var actualFilterType = normalizeFilterType(rawFilterType);
            if (!actualFilterType) return;
    
            var value = $(this).val();
            var isChecked = $(this).is(':checked');
    
            findInputByValue(actualFilterType, value).prop('checked', isChecked);
        });
    }
    // Sync mobile price range with desktop
    function syncMobilePriceRange() {
        var minVal = minPriceRange.val();
        var maxVal = maxPriceRange.val();
        $('#mobile-filter-content .artworks-price-range-min').val(minVal);
        $('#mobile-filter-content .artworks-price-range-max').val(maxVal);
        $('#price-min-input-mobile').val(minVal);
        $('#price-max-input-mobile').val(maxVal);
        $('#price-min-display-mobile').text(formatPrice(parseInt(minVal)) + ' USD');
        $('#price-max-display-mobile').text(formatPrice(parseInt(maxVal)) + '+ USD');
    }

    // Update mobile filter labels based on selected values
    function updateMobileFilterLabels() {
        try {
            // Update Style label
            var styleValues = filterState.style || [];
            var styleLabelEl = document.getElementById('style-mobile-label');
            if (styleLabelEl) {
                if (styleValues.length === 0) {
                    styleLabelEl.textContent = 'All Styles';
                } else if (styleValues.length === 1) {
                    var $checkbox = findInputByValue('style', styleValues[0]).first();
                    var label = $checkbox.length
                        ? $checkbox.closest('label').find('span').first().text().trim()
                        : String(styleValues[0]);
                    styleLabelEl.textContent = label;
                } else {
                    styleLabelEl.textContent = styleValues.length + ' Selected';
                }
            }

            // Update Medium label
            var mediumValues = filterState.medium || [];
            var mediumLabelEl = document.getElementById('medium-mobile-label');
            if (mediumLabelEl) {
                if (mediumValues.length === 0) {
                    mediumLabelEl.textContent = 'All Mediums';
                } else if (mediumValues.length === 1) {
                    var $checkbox = findInputByValue('medium', mediumValues[0]).first();
                    var label = $checkbox.length
                        ? $checkbox.closest('label').find('span').first().text().trim()
                        : String(mediumValues[0]);
                    mediumLabelEl.textContent = label;
                } else {
                    mediumLabelEl.textContent = mediumValues.length + ' Selected';
                }
            }

            // Update Artist label
            var artistValues = filterState.artist || [];
            var artistLabelEl = document.getElementById('artist-mobile-label');
            if (artistLabelEl) {
                if (artistValues.length === 0) {
                    artistLabelEl.textContent = 'All Artists';
                } else if (artistValues.length === 1) {
                    var $checkbox = findInputByValue('artist', artistValues[0]).first();
                    var label = $checkbox.length
                        ? $checkbox.closest('label').find('span').first().text().trim()
                        : String(artistValues[0]);
                    artistLabelEl.textContent = label;
                } else {
                    artistLabelEl.textContent = artistValues.length + ' Selected';
                }
            }

            // Update Gallery label
            var galleryValues = filterState.gallery || [];
            var galleryLabelEl = document.getElementById('gallery-mobile-label');
            if (galleryLabelEl) {
                if (galleryValues.length === 0) {
                    galleryLabelEl.textContent = 'All Galleries';
                } else if (galleryValues.length === 1) {
                    var $checkbox = findInputByValue('gallery', galleryValues[0]).first();
                    var label = $checkbox.length
                        ? $checkbox.closest('label').find('span').first().text().trim()
                        : String(galleryValues[0]);
                    galleryLabelEl.textContent = label;
                } else {
                    galleryLabelEl.textContent = galleryValues.length + ' Selected';
                }
            }
        } catch (e) {
            console.warn('Error updating mobile filter labels:', e);
        }
    }

    // Handle mobile filter panel toggles
    $(document).on('click', '.artworks-filter-button-mobile', function(e) {
        // Skip if on gallery details page (gallery-details.js handles it)
        if ($('#gallery-artworks-filters').length) {
            return;
        }
        e.preventDefault();
        e.stopPropagation();
    
        var filterType = $(this).data('filter');
        if (!filterType || typeof filterType !== 'string') return;
    
        var panelId = filterType + '-panel';
        var panelEl = document.getElementById(panelId);
        if (!panelEl) return;
    
        var panel = $(panelEl);
        var isOpen = panel.hasClass('active');
    
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

    // Sync mobile filter checkboxes when changed
    
    $(document).on('change', '.artworks-filter-checkbox', function() {
        // Skip if on gallery details page (gallery-details.js handles it)
        if ($('#gallery-artworks-filters').length) {
            return;
        }
        var rawFilterType = $(this).attr('name');
        var actualFilterType = normalizeFilterType(rawFilterType);
        if (!actualFilterType) return;
    
        var value = $(this).val();
        var isChecked = $(this).is(':checked');
    
        findInputByValue(actualFilterType, value).prop('checked', isChecked);
        updateMobileFilterLabels();
    });

    // Handle mobile price range sliders
    $('#mobile-filter-content .artworks-price-range-min').on('input', function() {
        isPriceTouched = true;
        var val = $(this).val();
        $('#price-min-input-mobile').val(val);
        minPriceRange.val(val);
        minPriceInput.val(val);
        updatePriceRange();
    });

    $('#mobile-filter-content .artworks-price-range-max').on('input', function() {
        isPriceTouched = true;
        var val = $(this).val();
        $('#price-max-input-mobile').val(val);
        maxPriceRange.val(val);
        maxPriceInput.val(val);
        updatePriceRange();
    });

    // Mobile price inputs - allow typing without triggering AJAX on every keystroke
    var mobilePriceFilterTimeout;
    
    $('#price-min-input-mobile').on('input', function() {
        isPriceTouched = true;
        var val = $(this).val();
        // Only update sliders and desktop inputs, don't call updatePriceRange() which overwrites the input
        $('#mobile-filter-content .artworks-price-range-min').val(val);
        minPriceRange.val(val);
        minPriceInput.val(val);
        // Update mobile display only
        if (val !== '' && !isNaN(val)) {
            $('#price-min-display-mobile').text(formatPrice(parseInt(val)) + ' USD');
        }
        // Don't trigger AJAX on input, only on blur
    });
    
    $('#price-min-input-mobile').on('blur', function() {
        isUserAction = true;
        isPriceTouched = true;
        var val = $(this).val();
        if (val === '' || isNaN(val)) {
            $(this).val(filterState.price.min);
        } else {
            var numVal = parseInt(val);
            if (numVal < 0) {
                $(this).val(0);
                filterState.price.min = 0;
            } else if (numVal > 20000) {
                $(this).val(20000);
                filterState.price.min = 20000;
            } else {
                filterState.price.min = numVal;
            }
            if (filterState.price.min >= filterState.price.max) {
                filterState.price.min = filterState.price.max - 100;
                $(this).val(filterState.price.min);
            }
            minPriceRange.val(filterState.price.min);
            updatePriceRange();
        }
        clearTimeout(mobilePriceFilterTimeout);
        getArtWork(1);
    });

    $('#price-max-input-mobile').on('input', function() {
        isPriceTouched = true;
        var val = $(this).val();
        // Only update sliders and desktop inputs, don't call updatePriceRange() which overwrites the input
        $('#mobile-filter-content .artworks-price-range-max').val(val);
        maxPriceRange.val(val);
        maxPriceInput.val(val);
        // Update mobile display only
        if (val !== '' && !isNaN(val)) {
            $('#price-max-display-mobile').text(formatPrice(parseInt(val)) + '+ USD');
        }
        // Don't trigger AJAX on input, only on blur
    });
    
    $('#price-max-input-mobile').on('blur', function() {
        isUserAction = true;
        isPriceTouched = true;
        var val = $(this).val();
        if (val === '' || isNaN(val)) {
            $(this).val(filterState.price.max);
        } else {
            var numVal = parseInt(val);
            if (numVal < 0) {
                $(this).val(0);
                filterState.price.max = 0;
            } else if (numVal > 20000) {
                $(this).val(20000);
                filterState.price.max = 20000;
            } else {
                filterState.price.max = numVal;
            }
            if (filterState.price.max <= filterState.price.min) {
                filterState.price.max = filterState.price.min + 100;
                $(this).val(filterState.price.max);
            }
            maxPriceRange.val(filterState.price.max);
            updatePriceRange();
        }
        clearTimeout(mobilePriceFilterTimeout);
        getArtWork(1);
    });

    // Clear all filters (mobile)
    // Mobile Clear All handler
    $(document).on('click', '#clear-all-filters-mobile', function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        try {
            // Use the same clear all logic as desktop
            isUserAction = true;

            // Reset filter state to defaults
            filterState.style = [];
            filterState.medium = [];
            filterState.artist = [];
            filterState.gallery = [];
            filterState.size = [];
            filterState.year = [];
            filterState.price = {
                min: defaultPriceRange.min,
                max: defaultPriceRange.max
            };
            isPriceTouched = false;
            filterState.sort = 'recently-added';

            // Uncheck all checkboxes
            $('.artworks-filter-checkbox').prop('checked', false);

            // Reset radio buttons
            $('.artworks-filter-radio').prop('checked', false);
            $('.artworks-filter-radio').filter(function () {
                return $(this).val() === 'recently-added';
            }).prop('checked', true);
            
            var sortLabel = $('#sort-label');
            if (sortLabel.length) {
                sortLabel.text('Recently Added');
            }

            // Reset price range inputs to default values
            if (minPriceRange && minPriceRange.length) {
                minPriceRange.val(defaultPriceRange.min);
            }
            if (maxPriceRange && maxPriceRange.length) {
                maxPriceRange.val(defaultPriceRange.max);
            }
            if (minPriceInput && minPriceInput.length) {
                minPriceInput.val(defaultPriceRange.min);
            }
            if (maxPriceInput && maxPriceInput.length) {
                maxPriceInput.val(defaultPriceRange.max);
            }

            // Update price display
            updatePriceRange();

            // Update all filter counts
            initializeAllFilterCounts();

            // Clear active filter tags
            updateActiveTags();

            // Close all filter panels
            $('.artworks-filter-panel').removeClass('active');
            $('.artworks-filter-button').removeClass('active');

            // Update mobile labels
            updateMobileFilterLabels();
            
            // Update URL and reload artworks
            getArtWork(1);
        } catch (error) {
            console.error('Error in mobile clear all filters:', error);
        }
    });

    // Initialize mobile filter labels
    updateMobileFilterLabels();

    // Sync on page load
    syncMobileFiltersOnLoad();
    
    // Ensure Price Range panel is always active in mobile view
    $('#price-mobile-panel').addClass('active');

    $(document).on('click','.paginate-artwork',function(){
        isUserAction = true;
        var page = $(this).data('page');
        getArtWork(page);
    });

    function getArtWork(page){
        // Build request data manually to ensure proper array serialization
        var requestData = {
            page: page || 1
        };
        
        // Always preserve fromHome parameter if it exists in the URL
        // This ensures the "No works yet — explore all artworks." message shows correctly
        // when user comes from home page, even after AJAX reloads
        // Check both the captured fromHome variable and the current URL (in case URL changed)
        const currentUrlParams = new URLSearchParams(window.location.search);
        const currentFromHome = currentUrlParams.get('fromHome');
        
        // Always check current URL first (most reliable), then fallback to captured variable
        if (currentFromHome === '1') {
            requestData.fromHome = '1';
        } else if (fromHome) {
            requestData.fromHome = '1';
        }
        
        
        
        // Add sort
        if (filterState.sort) {
            requestData['filterState[sort]'] = filterState.sort;
        }
        
        // Add style filters
        if (filterState.style && filterState.style.length > 0) {
            filterState.style.forEach(function(value, index) {
                requestData['filterState[style][' + index + ']'] = value;
            });
        }
        
        // Add medium filters
        if (filterState.medium && filterState.medium.length > 0) {
            filterState.medium.forEach(function(value, index) {
                requestData['filterState[medium][' + index + ']'] = value;
            });
        }
        
        // Add artist filters
        if (filterState.artist && filterState.artist.length > 0) {
            filterState.artist.forEach(function(value, index) {
                requestData['filterState[artist][' + index + ']'] = value;
            });
        }
        
        // Add gallery filters
        if (filterState.gallery && filterState.gallery.length > 0) {
            filterState.gallery.forEach(function(value, index) {
                requestData['filterState[gallery][' + index + ']'] = value;
            });
        }
        
        // Add size filters
        if (filterState.size && filterState.size.length > 0) {
            filterState.size.forEach(function(value, index) {
                requestData['filterState[size][' + index + ']'] = value;
            });
        }
        
        // Add year filters
        if (filterState.year && filterState.year.length > 0) {
            filterState.year.forEach(function(value, index) {
                requestData['filterState[year][' + index + ']'] = value;
            });
        }
        
        // Add price filters
        if (filterState.price) {
            if (filterState.price.min !== undefined && filterState.price.min !== null && filterState.price.min !== '') {
                requestData['filterState[price][min]'] = filterState.price.min;
            }
            if (filterState.price.max !== undefined && filterState.price.max !== null && filterState.price.max !== '') {
                requestData['filterState[price][max]'] = filterState.price.max;
            }
        }
        
        
        
        $.ajax({
            url: artworksRoute,
            type: 'GET',
            data: requestData,
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest'
            },
            beforeSend: function() {
                // Show loading state
                try {
                    var containerEl = document.getElementById('artworks-list-container');
                    if (containerEl) {
                        containerEl.classList.add('loading');
                        containerEl.innerHTML = '<div class="artworks-loading-state"><p class="text-center py-8">Loading artworks...</p></div>';
                    }
                } catch (e) {
                    console.warn('Error showing loading state:', e);
                }
            },
            success: function(response) {
                try {
                    var containerEl = document.getElementById('artworks-list-container');
                    if (containerEl) {
                        containerEl.innerHTML = response;
                    }
                    
                    // Initialize Masonry after content is loaded
                    setTimeout(function() {
                        initializeMasonry();
                    }, 100);
                    
                    // Update total count from data attribute in response
                    // Use DOMParser to safely parse HTML without executing scripts
                    var parser = new DOMParser();
                    var doc = parser.parseFromString(response, 'text/html');
                    var totalCountEl = doc.querySelector('[data-artwork-total]');
                    var totalCount = totalCountEl ? totalCountEl.getAttribute('data-artwork-total') : undefined;

                    if (totalCount !== undefined && totalCount !== null && totalCount !== '') {
                        var count = parseInt(totalCount);
                        if (!isNaN(count)) {
                            var countEl = document.getElementById('artwork-total-count');
                            if (countEl) {
                                countEl.textContent = count;
                            }
                            var titleEl = document.querySelector('.globa-count-title');
                            if (titleEl) {
                                // Clear and rebuild safely without innerHTML that might contain scripts
                                titleEl.textContent = '';
                                var countSpan = document.createElement('span');
                                countSpan.id = 'artwork-total-count';
                                countSpan.textContent = count;
                                titleEl.appendChild(countSpan);
                                titleEl.appendChild(document.createTextNode(' ' + (count == 1 ? 'Artwork' : 'Artworks') + ':'));
                            }
                        }
                    }
                } catch (e) {
                    console.error('Error processing artwork response:', e);
                }
                
                // Only update URL if this is a user-initiated action (not initial page load)
                if (isUserAction && window.history && window.history.pushState) {
                    var seoUrl = buildArtworksUrl(page || 1);
                    var currentUrl = window.location.pathname + window.location.search;
                    if (seoUrl !== currentUrl) {
                        window.history.pushState({ path: seoUrl }, '', seoUrl);
                    }
                }
            },
            complete: function() {
                // Remove loading state
                try {
                    var containerEl = document.getElementById('artworks-list-container');
                    if (containerEl) {
                        containerEl.classList.remove('loading');
                    }
                } catch (e) {
                    console.warn('Error removing loading state:', e);
                }
            },
            error: function(xhr) {
                console.error('Error fetching artworks:', xhr);
                console.error('Request data:', requestData);
                console.error('Filter state:', filterState);
                
                // Show user-friendly error message
                try {
                    var containerEl = document.getElementById('artworks-list-container');
                    if (containerEl) {
                        var errorMessage = '<div class="artworks-error-state text-center py-8">' +
                            '<p class="text-gray-600 mb-4">Something went wrong while loading artworks. Please try again.</p>' +
                            '<button class="artworks-retry-btn px-4 py-2 bg-gray-900 text-white rounded-full text-sm font-medium hover:bg-gray-800 transition-colors" onclick="location.reload();">Retry</button>' +
                            '</div>';
                        containerEl.innerHTML = errorMessage;
                    }
                } catch (e) {
                    console.error('Error showing error message:', e);
                }
            }
        });
    }

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
    
    // Function to update Clear All visibility
    function updateClearAllVisibility() {
        try {
            var hasActiveFilters = filterState.style.length > 0 || 
                                  filterState.medium.length > 0 || 
                                  filterState.artist.length > 0 || 
                                  filterState.gallery.length > 0 || 
                                  filterState.size.length > 0 || 
                                  filterState.year.length > 0 ||
                                  (isPriceTouched &&
                                   (filterState.price.min !== defaultPriceRange.min || 
                                    filterState.price.max !== defaultPriceRange.max)) ||
                                  filterState.sort !== 'recently-added';
            
            // Use getElementById instead of jQuery selector to avoid any potential selector issues
            var clearAllEl = document.getElementById('clear-all-filters');
            if (clearAllEl) {
                if (hasActiveFilters) {
                    clearAllEl.style.display = '';
                } else {
                    clearAllEl.style.display = 'none';
                }
            }
            
            // Mobile clear all button should always be visible
            var clearAllMobileEl = document.getElementById('clear-all-filters-mobile');
            if (clearAllMobileEl) {
                clearAllMobileEl.style.display = '';
            }
            $('.artworks-filter-section')
               .toggleClass('sticky', hasActiveFilters);
        } catch (e) {
            console.warn('Error updating clear all visibility:', e);
        }
    }
    $(window).on('scroll resize', function () {
       var hasActiveFilters = filterState.style.length > 0 || 
            filterState.medium.length > 0 || 
            filterState.artist.length > 0 || 
            filterState.gallery.length > 0 || 
            filterState.size.length > 0 || 
            filterState.year.length > 0 ||
            (isPriceTouched &&
            (filterState.price.min !== defaultPriceRange.min || 
            filterState.price.max !== defaultPriceRange.max)) ||
            filterState.sort !== 'recently-added';
        if (hasActiveFilters) {
            handleFilterBarPosition();
        } 
    });

    // Initialize sort state first (before other UI sync)
    initializeSortState();
    
    // Sync UI from filterState (after URL params are parsed)
    initializeCheckboxesFromFilterState();
    initializeAllFilterCounts();
    
    // Sync price inputs from filterState
    if (minPriceInput.length) {
        minPriceInput.val(filterState.price.min);
    }
    if (maxPriceInput.length) {
        maxPriceInput.val(filterState.price.max);
    }
    if (minPriceRange.length) {
        minPriceRange.val(filterState.price.min);
    }
    if (maxPriceRange.length) {
        maxPriceRange.val(filterState.price.max);
    }
    // Update price displays
    if (minPriceDisplay.length) {
        minPriceDisplay.text(formatPrice(filterState.price.min) + ' USD');
    }
    if (maxPriceDisplay.length) {
        var maxFormatted = formatPrice(filterState.price.max);
        var maxText = maxFormatted.endsWith('+') ? maxFormatted + ' USD' : maxFormatted + '+ USD';
        maxPriceDisplay.text(maxText);
    }
    
    // Sync mobile price
    syncMobilePriceRange();
    
    // Initialize price range CSS variables for proper display on page load
    updatePriceRange();
    
    updateActiveTags();
    updateMobileFilterLabels();
    updateClearAllVisibility();
    
    // Load artworks with filters from URL
    if (window.location.search) {
        getArtWork(1);
    }
});
