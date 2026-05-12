import {  handleFilterBarPosition } from '../utils/filter-ui';
/**
 * Gallery Details Page JavaScript
 * 
 * This file handles all functionality for the gallery details page, including:
 * - Tab switching (Artworks, Artists, Biography)
 * - Filtering artworks (style, medium, artist, price)
 * - Sorting options
 * - Pagination
 * - Mobile filter overlay
 * - Filter counts and active filter tags
 * - URL parameter parsing and state management
 * - AJAX-based artwork loading
 * 
 * Page Detection:
 * - Only runs on gallery details page (has #gallery-artworks-filters element)
 * - Uses top-level check to prevent execution on other pages
 * 
 * Filter State Management:
 * - Initializes from window.galleryDetailsState or URL parameters
 * - Maintains separate state for artworks and artists tabs
 * - Syncs desktop and mobile filter inputs
 * 
 * @file gallery-details.js
 * @requires jQuery
 */

// Only run on gallery details page - check at top level like artist-details.js
if (document.getElementById('gallery-artworks-filters')) {
$(document).ready(function() {
    'use strict';
    
    // Prevent any form submissions within gallery artworks filters
    $('#gallery-artworks-filters').on('submit', 'form', function(e) {
        e.preventDefault();
    });
    
    // Prevent default on any links within filter section that might cause navigation
    // But allow checkboxes and radios to work normally
    $('#gallery-artworks-filters').on('click', 'a.artworks-clear-all', function(e) {
        e.preventDefault();
        e.stopPropagation();
        return false;
    });
    
    // Get initial state from window object
    const galleryState = window.galleryDetailsState || {
        galleryId: null,
        artworksRoute: '',
        csrfToken: '',
        activeTab: 'artworks',
        filterState: {
            style: [],
            medium: [],
            artist: [],
            price: {
                min: 0,
                max: 20000
            },
            sort: 'recently-added'
        }
    };

    let activeTab = galleryState.activeTab || 'artworks';
    let isPriceTouched = false;
    
    // Masonry instance
    var masonryInstance = null;
    
    /**
     * Initialize Masonry layout for gallery artworks
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

    // Parse URL parameters on page load
    function parseUrlParams() {
        const urlParams = new URLSearchParams(window.location.search);
        const tab = urlParams.get('tab');
        if (tab && (tab === 'artworks' || tab === 'biography')) {
            activeTab = tab;
        }
       


        // Parse filterState from URL
        const styleParams = urlParams.getAll('filterState[style][]');
        if (styleParams.length > 0) {
            galleryState.filterState.style = styleParams;
        }

        const mediumParams = urlParams.getAll('filterState[medium][]');
        if (mediumParams.length > 0) {
            galleryState.filterState.medium = mediumParams;
        }

        const artistParams = urlParams.getAll('filterState[artist][]');
        if (artistParams.length > 0) {
            galleryState.filterState.artist = artistParams.map(id => parseInt(id));
        }

        const priceMin = urlParams.get('filterState[price][min]');
        const priceMax = urlParams.get('filterState[price][max]');

        if (priceMin !== null) {
            galleryState.filterState.price.min = parseInt(priceMin) || 0;
        }

        if (priceMax !== null) {
            galleryState.filterState.price.max = parseInt(priceMax) || 20000;
        }

        if (priceMin !== null || priceMax !== null) {
            isPriceTouched = true;
        }

        const sortParam = urlParams.get('filterState[sort]') || urlParams.get('sort');
        if (sortParam) {
            galleryState.filterState.sort = sortParam;
        }

        const pageParam = urlParams.get('page');
        if (pageParam) {
            galleryState.currentPage = parseInt(pageParam);
        }
    }

    // Initialize URL params parsing
    parseUrlParams();

    // Track gallery detail view on page load
    if (galleryState.galleryId) {
        trackEvent('gallery_detail_view', {
            gallery_id: galleryState.galleryId
        });
    }

    // Analytics tracking function
    function trackEvent(eventName, eventData) {
        // Google Analytics 4 (gtag)
        if (typeof gtag !== 'undefined') {
            gtag('event', eventName, eventData);
        }
        
        // Google Analytics Universal (dataLayer)
        if (typeof dataLayer !== 'undefined') {
            dataLayer.push({
                'event': eventName,
                ...eventData
            });
        }
        
        // Custom analytics (if needed)
        if (typeof window.trackAnalytics === 'function') {
            window.trackAnalytics(eventName, eventData);
        }
        
        // Console log for debugging
        if (typeof console !== 'undefined' && console.log) {
            console.log('Analytics Event:', eventName, eventData);
        }
    }

    // Direct filter state management - like artwork-listing.js (WORKING APPROACH)
    let isUserAction = false;

    function normalizeFilterType(filterType) {
        if (!filterType || typeof filterType !== 'string') return '';
        return filterType.replace('[]', '').replace('-mobile', '');
    }
    
    function findInputByValue(name, value) {
        name = normalizeFilterType(name);
        if (!name) return $();
        return $('input').filter(function () {
            var inputName = $(this).attr('name');
            if (!inputName) return false;
            var matchesName = (
                inputName === name ||
                inputName === name + '[]' ||
                inputName === name + '-mobile' ||
                inputName === name + '-mobile[]'
            );
            return matchesName && String($(this).val()) === String(value);
        });
    }
    
    // Filter state - direct management
    var filterState = {
        style: Array.isArray(galleryState.filterState.style) ? [...galleryState.filterState.style] : [],
        medium: Array.isArray(galleryState.filterState.medium) ? [...galleryState.filterState.medium] : [],
        artist: Array.isArray(galleryState.filterState.artist) ? galleryState.filterState.artist.map(id => parseInt(id)).filter(id => !isNaN(id)) : [],
        price: {
            min: parseInt(galleryState.filterState.price.min) || 0,
            max: parseInt(galleryState.filterState.price.max) || 20000
        },
        sort: galleryState.filterState.sort || 'recently-added'
    };
    
    // Sync checkboxes on page load
    filterState.style.forEach(function(value) {
        findInputByValue('style', value).prop('checked', true);
    });
    filterState.medium.forEach(function(value) {
        findInputByValue('medium', value).prop('checked', true);
    });
    filterState.artist.forEach(function(value) {
        findInputByValue('artist', value).prop('checked', true);
    });
    
    // Set sort radio and label
    if (filterState.sort) {
        $('.artworks-filter-radio[value="' + filterState.sort + '"]').prop('checked', true);
        var sortLabel = $('.artworks-filter-radio[value="' + filterState.sort + '"]').closest('label').find('span').last().text().trim();
        if (sortLabel) {
            $('#sort-label').text(sortLabel);
        }
    }
    
    // Initialize filter counts and tags after syncing checkboxes
    updateFilterCount('style');
    updateFilterCount('medium');
    updateFilterCount('artist');
    updateConsolidatedFilterCount();
    updateActiveTags();
    updateClearAllVisibility();
    updateMobileFilterLabels();
    
    // Handle checkbox changes - DIRECT APPROACH (WORKING)
    // Use document delegation but check we're on gallery details page
    $(document).on('change', '.artworks-filter-checkbox', function () {

        if (!$('#gallery-artworks-filters').length) return;
        if (activeTab !== 'artworks') return;
    
        isUserAction = true;
    
        const rawFilterType = $(this).attr('name');
        const filterType = normalizeFilterType(rawFilterType);
        if (!filterType) return;
    
        const value = String($(this).val());
        const isChecked = $(this).is(':checked');
    
        if (isChecked) {
            if (!filterState[filterType].includes(value)) {
                filterState[filterType].push(value);
            }
        } else {
            filterState[filterType] = filterState[filterType].filter(v => v !== value);
        }
    
        // ✅ Sync desktop ↔ mobile
        findInputByValue(filterType, value).prop('checked', isChecked);
    
        updateFilterCount(filterType);
        // Ensure price count is maintained when changing other filters
        if (filterType !== 'price') {
            updateFilterCount('price');
        }
        updateActiveTags();
        updateClearAllVisibility();
        updateMobileFilterLabels();
    
        getArtworks(1);
    });
    
    
    
  
    
    // Handle radio changes - DIRECT APPROACH (WORKING)
    // Use document delegation but check we're on gallery details page
    $(document).on('change', '.artworks-filter-radio', function() {
        // Only handle if we're on gallery details page, not artwork listing
        // if (!$('#gallery-artworks-filters').length || $('#artworks-list-container').length) {
        //     return;
        // }
        if (!$('#gallery-artworks-filters').length) {
            return;
        }
        if (activeTab !== 'artworks') return;
        
        isUserAction = true;
        var sortValue = $(this).val();
        filterState.sort = sortValue;
        
        // Update sort label
        var sortLabel = $(this).closest('label').find('span').last().text().trim();
        $('#sort-label').text(sortLabel);
        
        // Uncheck all radio buttons and check the selected one (like artwork-listing.js)
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
        
        // Apply sort immediately (like artwork-listing.js)
        getArtworks(1);
    });
    
    // Price range handlers - NO count, NO chip, NO immediate navigation
    var minPriceInput = $('#price-min-input');
    var maxPriceInput = $('#price-max-input');
    var minPriceRange = $('.artworks-price-range-min');
    var maxPriceRange = $('.artworks-price-range-max');
    var minPriceDisplay = $('#price-min-display');
    var maxPriceDisplay = $('#price-max-display');
    
    // Set price inputs and sliders from filterState (after variables are defined)
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
    
    function formatPrice(value) {
        if (value >= 20000) return '20,000+';
        return value.toLocaleString();
    }
    
    function updatePriceDisplay() {
        var min = filterState.price.min || 0;
        var max = filterState.price.max || 20000;
        var minFormatted = formatPrice(min);
        var maxFormatted = formatPrice(max);
        
        if (minPriceDisplay.length) minPriceDisplay.text(minFormatted + ' USD');
        // Only add '+' if formatPrice didn't already include it
        if (maxPriceDisplay.length) {
            var maxText = maxFormatted.endsWith('+') ? maxFormatted + ' USD' : maxFormatted + '+ USD';
            maxPriceDisplay.text(maxText);
        }
        $('#price-min-display-mobile').text(minFormatted + ' USD');
        var maxMobileText = maxFormatted.endsWith('+') ? maxFormatted + ' USD' : maxFormatted + '+ USD';
        $('#price-max-display-mobile').text(maxMobileText);
        
        // Update CSS custom properties for range visualization
        var maxValue = 20000;
        var minPercent = (min / maxValue) * 100;
        var maxPercent = (max / maxValue) * 100;
        $('.artworks-price-slider').css({
            '--range-min': minPercent + '%',
            '--range-max': maxPercent + '%'
        });
    }
    
    // Update price display after setting values
    updatePriceDisplay();
    
    // Debounce function for price filter
    var priceFilterTimeout;
    function applyPriceFilter() {
        if (activeTab !== 'artworks') return;
    
        isUserAction = true;
        isPriceTouched = true; 
    
        clearTimeout(priceFilterTimeout);
        priceFilterTimeout = setTimeout(function() {
            getArtworks(1);
        }, 500);
    }
    
    // Price range sliders - only update display, debounced filter
    if (minPriceRange.length) {
        minPriceRange.on('input', function() {
            var val = parseInt($(this).val()) || 0;
            minPriceInput.val(val);
            filterState.price.min = val;
            isPriceTouched = true; 
            updatePriceDisplay();
            updateFilterCount('price');
            updateActiveTags();
            updateClearAllVisibility();
            applyPriceFilter();

        });
    }
    
    if (maxPriceRange.length) {
        maxPriceRange.on('input', function() {
            var val = parseInt($(this).val()) || 20000;
            maxPriceInput.val(val);
            filterState.price.max = val;
            isPriceTouched = true; 
            updatePriceDisplay();
            updateFilterCount('price');
            updateActiveTags();
            updateClearAllVisibility();
            applyPriceFilter();
        });
    }
    
    // Price inputs - only update display, debounced filter
    if (minPriceInput.length) {
        minPriceInput.on('input', function() {
            var val = parseInt($(this).val()) || 0;
            if (val >= 0 && val <= 20000) {
                minPriceRange.val(val);
                filterState.price.min = val;
                isPriceTouched = true;
                updatePriceDisplay();
                updateFilterCount('price');
                updateActiveTags();
            }
            updateClearAllVisibility();
            applyPriceFilter();
        });
    }
    
    if (maxPriceInput.length) {
        maxPriceInput.on('input', function() {
            var val = parseInt($(this).val()) || 20000;
            if (val >= 0 && val <= 20000) {
                maxPriceRange.val(val);
                filterState.price.max = val;
                updatePriceDisplay();
                updateFilterCount('price');
                updateActiveTags();
            }
            updateClearAllVisibility();
            applyPriceFilter();
        });
    }
    
    // Update filter count
    function updateFilterCount(filterType) {
        if (!filterType || typeof filterType !== 'string') return;
    
        filterType = normalizeFilterType(filterType);
        if (!filterType) return;
    
        let count = 0;
    
        if (filterType === 'price') {
            if (
                isPriceTouched &&
                (filterState.price.min !== 0 || filterState.price.max !== 20000)
            ) {
                count = 1;
            }
        } else if (Array.isArray(filterState[filterType])) {
            count = filterState[filterType].length;
        }
    
        const countEl = document.getElementById(filterType + '-count');
        if (!countEl) return;
    
        if (count > 0) {
            countEl.textContent = `(${count})`;
            countEl.style.display = 'inline';
        } else {
            countEl.textContent = '';
            countEl.style.display = 'none';
        }
    
        updateConsolidatedFilterCount();
    }
    
    
    // Update consolidated filter count (excludes price)
    function updateConsolidatedFilterCount() {
        try {
            var totalCount = filterState.style.length + filterState.medium.length + filterState.artist.length;
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

    // Update active filter tags - NO price chip
    function updateActiveTags() {
        var tagsContainerEl = document.getElementById('active-filter-tags');
        if (!tagsContainerEl) return;
        var tagsContainer = $(tagsContainerEl);
        
        if (tagsContainer.find('.artworks-filter-tags-label').length === 0) {
            tagsContainer.prepend('<span class="artworks-filter-tags-label">Applied Filters:</span>');
        }
        
        // Remove all filter tags, including any price-related tags
        tagsContainer.find('.artworks-filter-tag').remove();
        
        // Explicitly ensure any price-related tags are removed (double-check)
        tagsContainer.find('[data-filter="price"]').remove();
        tagsContainer.find('.artworks-filter-tag').filter(function() {
            var tagText = $(this).text();
            return tagText.indexOf('$') !== -1 || tagText.indexOf('USD') !== -1 || $(this).data('filter') === 'price';
        }).remove();
        
        function getLabelText(filterType, value) {
            if (!filterType || value === undefined || value === null) return '';
            try {
                var $input = findInputByValue(filterType, value).first();
                if ($input.length) {
                    var $span = $input.closest('label').find('span').first();
                    if ($span.length) {
                        var labelText = $span.text().trim();
                        if (labelText && labelText !== String(value)) {
                            return labelText;
                        }
                    }
                }
            } catch (e) {
                console.warn('Error getting label text:', e);
            }
            return String(value);
        }
        
        // Add style tags
        if (Array.isArray(filterState.style)) {
            filterState.style.forEach(function(value) {
                if (value !== undefined && value !== null) {
                    var label = getLabelText('style', value);
                    if (label) {
                        var $tag = $('<span>').addClass('artworks-filter-tag').text(label);
                        
                        // Create the remove button with icon
                        var $removeBtn = $('<button>')
                            .addClass('artworks-filter-tag-remove')
                            .attr('type', 'button')
                            .attr('data-filter', 'style')
                            .attr('data-value', String(value).replace(/"/g, '&quot;'))
                            .attr('aria-label', 'Remove filter');
                        
                        // Add close-filter icon
                        var $icon = $('<img>')
                            .attr('src', '/assets/svg/icons/close-filter.svg')
                            .addClass('close-filter')
                            .attr('alt', 'close-filter')
                            .attr('width', '8')
                            .attr('height', '8');
                        
                        $removeBtn.append($icon);
                        $tag.append($removeBtn);
                        tagsContainer.append($tag);
                    }
                }
            });
        }
        
        // Add medium tags
        if (Array.isArray(filterState.medium)) {
            filterState.medium.forEach(function(value) {
                if (value !== undefined && value !== null) {
                    var label = getLabelText('medium', value);
                    if (label) {
                        var $tag = $('<span>').addClass('artworks-filter-tag').text(label);
                        
                        // Create the remove button with icon
                        var $removeBtn = $('<button>')
                            .addClass('artworks-filter-tag-remove')
                            .attr('type', 'button')
                            .attr('data-filter', 'medium')
                            .attr('data-value', String(value).replace(/"/g, '&quot;'))
                            .attr('aria-label', 'Remove filter');
                        
                        // Add close-filter icon
                        var $icon = $('<img>')
                            .attr('src', '/assets/svg/icons/close-filter.svg')
                            .addClass('close-filter')
                            .attr('alt', 'close-filter')
                            .attr('width', '8')
                            .attr('height', '8');
                        
                        $removeBtn.append($icon);
                        $tag.append($removeBtn);
                        tagsContainer.append($tag);
                    }
                }
            });
        }
        
        // Add artist tags
        if (Array.isArray(filterState.artist)) {
            filterState.artist.forEach(function(value) {
                if (value !== undefined && value !== null) {
                    var label = getLabelText('artist', value);
                    if (label) {
                        var $tag = $('<span>').addClass('artworks-filter-tag').text(label);
                        
                        // Create the remove button with icon
                        var $removeBtn = $('<button>')
                            .addClass('artworks-filter-tag-remove')
                            .attr('type', 'button')
                            .attr('data-filter', 'artist')
                            .attr('data-value', String(value).replace(/"/g, '&quot;'))
                            .attr('aria-label', 'Remove filter');
                        
                        // Add close-filter icon
                        var $icon = $('<img>')
                            .attr('src', '/assets/svg/icons/close-filter.svg')
                            .addClass('close-filter')
                            .attr('alt', 'close-filter')
                            .attr('width', '8')
                            .attr('height', '8');
                        
                        $removeBtn.append($icon);
                        $tag.append($removeBtn);
                        tagsContainer.append($tag);
                    }
                }
            });
        }
        
        // Add price tag (only if different from default)
       // Add price tag ONLY after user interaction
       if (
        isPriceTouched &&
        (filterState.price.min !== 0 || filterState.price.max !== 20000)
    ) {
    var priceText = '$' + formatPrice(filterState.price.min) + ' - $' + formatPrice(filterState.price.max);
    var $tag = $('<span>').addClass('artworks-filter-tag').text(priceText);

    var $removeBtn = $('<button>')
        .addClass('artworks-filter-tag-remove')
        .attr('type', 'button')
        .attr('data-filter', 'price')
        .attr('data-value', 'price')
        .attr('aria-label', 'Remove filter');

    var $icon = $('<img>')
        .attr('src', '/assets/svg/icons/close-filter.svg')
        .addClass('close-filter')
        .attr('alt', 'close-filter')
        .attr('width', '8')
        .attr('height', '8');

    $removeBtn.append($icon);
    $tag.append($removeBtn);
    tagsContainer.append($tag);
}
        
        var hasTags = tagsContainer.find('.artworks-filter-tag').length > 0;
        if (hasTags) {
            tagsContainer.show();
        } else {
            tagsContainer.hide();
        }
        
        updateConsolidatedFilterCount();
        updateMobileFilterLabels();
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

    
    // Update clear all visibility
    function updateClearAllVisibility() {
        var hasNonPriceFilters =
            filterState.style.length > 0 ||
            filterState.medium.length > 0 ||
            filterState.artist.length > 0;
    
        var hasPriceFilter =
            isPriceTouched &&
            (filterState.price.min !== 0 || filterState.price.max !== 20000);
    
        var hasFilters = hasNonPriceFilters || hasPriceFilter;
    
        var clearAllEl = document.getElementById('clear-all-filters');
        if (clearAllEl) {
            clearAllEl.style.display = hasFilters ? 'inline' : 'none';
        }
        $('.artworks-filter-section')
          .toggleClass('sticky', hasFilters);
    }

    $(window).on('scroll resize', function () {
        var hasNonPriceFilters =
            filterState.style.length > 0 ||
            filterState.medium.length > 0 ||
            filterState.artist.length > 0;
    
        var hasPriceFilter =
            isPriceTouched &&
            (filterState.price.min !== 0 || filterState.price.max !== 20000);
    
        var hasFilters = hasNonPriceFilters || hasPriceFilter;
        if (hasFilters) {
            handleFilterBarPosition();
        } 
    });
    
    // Remove filter tag
    $('#gallery-artworks-filters').on('click', '.artworks-filter-tag-remove', function(e) {
        e.preventDefault();
        e.stopPropagation();
        if (activeTab !== 'artworks') return;
        
        var filterType = $(this).data('filter');
        var value = String($(this).data('value'));
        
        if (filterType && value) {
            if (filterType === 'price') {
                // Reset to default price range
                filterState.price.min = 0;
                filterState.price.max = 20000;
                $('.artworks-price-range-min').val(0);
                $('.artworks-price-range-max').val(20000);
                $('#price-min-input, #price-min-input-mobile').val(0);
                $('#price-max-input, #price-max-input-mobile').val(20000);
                updatePriceDisplay();
                updateFilterCount('price');
                isPriceTouched = false;
            } else if (Array.isArray(filterState[filterType])) {
                filterState[filterType] = filterState[filterType].filter(v => String(v) !== value);
                findInputByValue(filterType, value).prop('checked', false);
            }
            
            updateFilterCount(filterType);
            // Ensure price count is maintained when removing other filters
            // if (filterType !== 'price') {
            //     updateFilterCount('price');
            // }
            updateActiveTags();
            updateClearAllVisibility();
            
            // Ensure count display is correct based on active tab
            if (activeTab === 'artworks') {
                $('#artworks-count, #artworks-label').show();
                $('#artists-count, #artists-label').hide();
            } else {
                $('#artworks-count, #artworks-label').hide();
                $('#artists-count, #artists-label').show();
            }
            
            getArtworks(1);
        }
    });
    
    // Clear all filters
    $('#gallery-artworks-filters').on('click', '.artworks-clear-all', function(e) {
        e.preventDefault();
        e.stopPropagation();
        if (activeTab !== 'artworks') return;
        
        isUserAction = true;
        
        filterState.style = [];
        filterState.medium = [];
        filterState.artist = [];
        filterState.price = { min: 0, max: 20000 };
        filterState.sort = 'recently-added';
        
        $('#gallery-artworks-filters .artworks-filter-checkbox').prop('checked', false);
        $('.artworks-filter-radio[value="recently-added"]').prop('checked', true);
        $('#sort-label').text('Recently Added');
        
        minPriceRange.val(0);
        maxPriceRange.val(20000);
        minPriceInput.val(0);
        maxPriceInput.val(20000);
        updatePriceDisplay();
        updateFilterCount('price');
        
        updateFilterCount('style');
        updateFilterCount('medium');
        updateFilterCount('artist');
        updateActiveTags();
        updateClearAllVisibility();
        
        // Ensure count display is correct after clearing (based on active tab)
        if (activeTab === 'artworks') {
            $('#artworks-count, #artworks-label').show();
            $('#artists-count, #artists-label').hide();
            getArtworks(1);
        } else {
            $('#artworks-count, #artworks-label').hide();
            $('#artists-count, #artists-label').show();
        }
        isPriceTouched = false;
    });
    
    // Mobile clear all
    $(document).on('click', '#clear-all-filters-mobile', function(e) {
        if (!$('#gallery-artworks-filters').length) return;
        e.preventDefault();
        e.stopPropagation();
        $('#gallery-artworks-filters .artworks-clear-all').click();
    });
    
    // Get artworks via AJAX
    var currentPage = 1;
    function getArtworks(page) {
        if (activeTab !== 'artworks') return;
        
        page = page || 1;
        currentPage = page;
        
        var requestData = { page: page, tab: 'artworks' };
        
        if (filterState.sort) {
            requestData['filterState[sort]'] = filterState.sort;
        }
        
        if (filterState.style && filterState.style.length > 0) {
            filterState.style.forEach(function(value, index) {
                requestData['filterState[style][' + index + ']'] = value;
            });
        }
        
        if (filterState.medium && filterState.medium.length > 0) {
            filterState.medium.forEach(function(value, index) {
                requestData['filterState[medium][' + index + ']'] = value;
            });
        }
        
        if (filterState.artist && filterState.artist.length > 0) {
            filterState.artist.forEach(function(value, index) {
                requestData['filterState[artist][' + index + ']'] = value;
            });
        }
        
        if (filterState.price) {
            if (filterState.price.min !== undefined && filterState.price.min !== null) {
                requestData['filterState[price][min]'] = filterState.price.min;
            }
            if (filterState.price.max !== undefined && filterState.price.max !== null) {
                requestData['filterState[price][max]'] = filterState.price.max;
            }
        }
        
        var ajaxUrl = galleryState.artworksRoute;
        if (!ajaxUrl || ajaxUrl.indexOf('artworks-list') !== -1) {
            var galleryId = galleryState.galleryId || window.location.pathname.match(/\/galleries\/(\d+)/)?.[1];
            if (galleryId) {
                ajaxUrl = '/galleries/' + galleryId;
            } else {
                console.error('Gallery details route not found');
                return;
            }
        }
        
        $.ajax({
            url: ajaxUrl,
            type: 'GET',
            data: requestData,
            headers: {
                'X-CSRF-TOKEN': galleryState.csrfToken,
                'X-Requested-With': 'XMLHttpRequest'
            },
            beforeSend: function() {
                if (activeTab === 'artworks') {
                    var containerEl = document.getElementById('artworks-list-container');
                    if (containerEl) {
                        containerEl.innerHTML = '<div class="text-center py-12">Loading...</div>';
                    }
                }
            },
            success: function(response) {
                if (activeTab === 'artworks') {
                    var containerEl = document.getElementById('artworks-list-container');
                    if (containerEl) {
                        containerEl.innerHTML = response;
                    }
            
                    updateArtworkCount();
                    updateActiveTags();
                    
                    // Initialize Masonry after content is loaded
                    setTimeout(function() {
                        initializeMasonry();
                    }, 100);
            
                    if (isUserAction && window.history && window.history.pushState) {
                        var params = new URLSearchParams();
                        params.append('tab', 'artworks');
            
                        if (filterState.sort && filterState.sort !== 'recently-added') {
                            params.append('filterState[sort]', filterState.sort);
                        }
            
                        filterState.style.forEach(v => params.append('filterState[style][]', v));
                        filterState.medium.forEach(v => params.append('filterState[medium][]', v));
                        filterState.artist.forEach(v => params.append('filterState[artist][]', v));
            
                        // ✅ URL params for price (NOT UI)
                        if (isPriceTouched) {
                            params.append('filterState[price][min]', filterState.price.min);
                            params.append('filterState[price][max]', filterState.price.max);
                        }
            
                        if (page > 1) params.append('page', page);
            
                        var newUrl = ajaxUrl + '?' + params.toString();
                        window.history.pushState({ path: newUrl }, '', newUrl);
                    }
                }
            },
            error: function(xhr) {
                console.error('Error fetching artworks:', xhr);
                if (activeTab === 'artworks') {
                    var containerEl = document.getElementById('artworks-list-container');
                    if (containerEl) {
                        containerEl.innerHTML = '<div class="text-center py-12 text-red-600">Error loading artworks.</div>';
                    }
                }
            }
        });
    }

    // Update artwork count - respect active tab
    function updateArtworkCount() {
        // Only update if we're on artworks tab
        if (activeTab !== 'artworks') {
            // Ensure correct count display - hide artworks, show artists
            $('#artworks-count, #artworks-label').hide();
            $('#artists-count, #artists-label').show();
            return;
        }
        
        const total = $('[data-artwork-total]').data('artwork-total') || 0;
        $('#artworks-count').text(total);
        const text = total === 1 ? 'Artwork' : 'Artworks';
        $('#artworks-label').text(text + ':');
        
        // Ensure correct count display based on active tab
        $('#artworks-count, #artworks-label').show();
        $('#artists-count, #artists-label').hide();
        
        // Restore price count after updating artwork count
        // updateFilterCount('price');
    }

    // Update URL
    function updateUrl(push = true) {
        const params = new URLSearchParams();
        params.append('tab', activeTab);

        if (activeTab === 'artworks') {
            filterState.style.forEach(s => params.append('filterState[style][]', s));
            filterState.medium.forEach(m => params.append('filterState[medium][]', m));
            filterState.artist.forEach(a => params.append('filterState[artist][]', a));
            if (filterState.price.min !== 0) params.append('filterState[price][min]', filterState.price.min);
            if (filterState.price.max !== 20000) params.append('filterState[price][max]', filterState.price.max);
            if (filterState.sort !== 'recently-added') params.append('sort', filterState.sort);
            if (currentPage !== 1) params.append('page', currentPage);
        }

        const baseUrl = window.location.pathname;
        const newUrl = `${baseUrl}?${params.toString()}`;
        const state = {
            tab: activeTab,
            filterState: filterState,
            page: currentPage
        };

        if (push) {
            window.history.pushState(state, '', newUrl);
        } else {
            window.history.replaceState(state, '', newUrl);
        }
    }

    // Tab switching
    $('.artist-tab').on('click', function(e) {
        e.preventDefault();
        const tab = $(this).data('tab');
        if (tab === activeTab) return;

        activeTab = tab;
        const tabId = tab === 'artworks' ? 'artworks-tab' : 'biography-tab';
        const contentId = tab === 'artworks' ? 'artworks-content' : 'biography-content';

        $('.artist-tab').removeClass('artist-tab-active').attr('aria-selected', 'false');
        $(this).addClass('artist-tab-active').attr('aria-selected', 'true');

        $('.artist-tab-content').hide().attr('aria-hidden', 'true');
        // Validate contentId to prevent invalid selector
        if (contentId && typeof contentId === 'string' && contentId.trim() !== '') {
            const contentEl = document.getElementById(contentId);
            if (contentEl) {
                $(contentEl).show().attr('aria-hidden', 'false');
            }
        }

        // Show/hide filters based on tab
        if (activeTab === 'artworks') {
            $('#gallery-artworks-filters').show();
        } else {
            $('#gallery-artworks-filters').hide();
        }

        // Update count display based on active tab
        if (activeTab === 'artworks') {
            $('#artworks-count, #artworks-label').show();
            $('#artists-count, #artists-label').hide();
            // Initialize Masonry when switching to artworks tab
            setTimeout(function() {
                initializeMasonry();
            }, 100);
        } else {
            $('#artworks-count, #artworks-label').hide();
            $('#artists-count, #artists-label').show();
        }

        // Track tab change
        trackEvent('gallery_tab_change', {
            tab: activeTab
        });

        updateUrl();
    });
    
    // Note: Tab initialization is now handled in the "Initialize on page load" section below

    // Toggle filter panels (desktop only - exclude mobile buttons)
    // Only work on gallery details page
    $('#gallery-artworks-filters').on('click', '.artworks-filter-button:not(.artworks-filter-button-mobile)', function(e) {

        e.stopPropagation();
        
        var filterType = $(this).data('filter');
        if (!filterType || typeof filterType !== 'string') return;
        
        var panelId = filterType + '-panel';
        var panelEl = document.getElementById(panelId);
        if (!panelEl) return;
        
        var panel = $(panelEl);
        var isOpen = panel.hasClass('active');
        
        // Close all desktop panels only
        $('#gallery-artworks-filters .artworks-filter-panel').not('#mobile-filter-content .artworks-filter-panel').removeClass('active');
        $('#gallery-artworks-filters .artworks-filter-button').not('.artworks-filter-button-mobile').removeClass('active');
        
        // Toggle current panel
        if (!isOpen) {
            panel.addClass('active');
            $(this).addClass('active');
        }
    });
    
    // Handle mobile filter button clicks - 
    // Handle mobile filter button clicks - 
    $(document).on('click', '.artworks-filter-button-mobile', function(e) {
        // Only gallery details page
        if (!$('#gallery-artworks-filters').length) return;

        e.preventDefault();
        e.stopPropagation(); // ✅ ONLY this, nothing more

        const filterType = $(this).data('filter');
        if (!filterType) return;

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

    // Close panels when clicking outside (desktop only)
    // Scope to gallery-artworks-filters to only work on gallery details page
    $(document).on('click', function(e) {
        // Only handle desktop panels, not mobile overlay panels
        if (!$(e.target).closest('#mobile-filter-content').length) {
            if (!$(e.target).closest('#gallery-artworks-filters .artworks-filter-button-wrapper').length) {
                $('#gallery-artworks-filters .artworks-filter-panel').not('#mobile-filter-content .artworks-filter-panel').removeClass('active');
                $('#gallery-artworks-filters .artworks-filter-button').not('.artworks-filter-button-mobile').removeClass('active');
                // Ensure price count is maintained when panel closes
                updateFilterCount('price');
            }
        }
    });

    // Read more/less toggle for About section
    $('#gallery-read-more').on('click', function(e) {
        e.preventDefault();
        const fullText = $('#gallery-about-full').text();
        $('#gallery-about-text').text(fullText).removeClass('gallery-about-truncated');
        $(this).hide();
        $('#gallery-read-less').show();
    });

    $('#gallery-read-less').on('click', function(e) {
        e.preventDefault();
        const truncatedText = $('#gallery-about-text').data('truncated') || '';
        $('#gallery-about-text').text(truncatedText).addClass('gallery-about-truncated');
        $(this).hide();
        $('#gallery-read-more').show();
    });

    // Browser back/forward handling
    window.addEventListener('popstate', function(event) {
        if (event.state) {
            activeTab = event.state.tab || 'artworks';
            const savedFilterState = event.state.filterState || galleryState.filterState;
            const savedPage = event.state.page || 1;
            
            filterState.style = Array.isArray(savedFilterState.style) ? [...savedFilterState.style] : [];
            filterState.medium = Array.isArray(savedFilterState.medium) ? [...savedFilterState.medium] : [];
            filterState.artist = Array.isArray(savedFilterState.artist) ? savedFilterState.artist.map(id => parseInt(id)).filter(id => !isNaN(id)) : [];
            filterState.price = {
                min: parseInt(savedFilterState.price.min) || 0,
                max: parseInt(savedFilterState.price.max) || 20000
            };
            filterState.sort = savedFilterState.sort || 'recently-added';
            currentPage = savedPage;
            
            if (activeTab === 'artists' || activeTab === 'biography') {
                $('.artist-tab[data-tab="biography"]').click();
            } else {
                $('.artist-tab[data-tab="artworks"]').click();
            }
            
            $('.artworks-filter-checkbox').prop('checked', false);
            filterState.style.forEach(value => {
                findInputByValue('style', value).prop('checked', true);
            });
            filterState.medium.forEach(value => {
                findInputByValue('medium', value).prop('checked', true);
            });
            filterState.artist.forEach(id => {
                findInputByValue('artist', id).prop('checked', true);
            });
            
            $('#price-min-input').val(filterState.price.min);
            $('#price-max-input').val(filterState.price.max);
            $('.artworks-price-range-min').val(filterState.price.min);
            $('.artworks-price-range-max').val(filterState.price.max);
            updatePriceDisplay();
            
            $(`.artworks-filter-radio[value="${filterState.sort}"]`).prop('checked', true);
            $('#sort-label').text($(`.artworks-filter-radio[value="${filterState.sort}"]`).closest('label').find('span').last().text().trim());
            
            updateFilterCount('style');
            updateFilterCount('medium');
            updateFilterCount('artist');
            updateFilterCount('price');
            updateActiveTags();
            
            // Ensure count display is correct based on active tab
            if (activeTab === 'artworks') {
                $('#artworks-count, #artworks-label').show();
                $('#artists-count, #artists-label').hide();
                getArtworks(currentPage);
            } else {
                $('#artworks-count, #artworks-label').hide();
                $('#artists-count, #artists-label').show();
            }
        }
    });

    // Initialize on page load
    // Set initial tab state based on URL parameter
    $('.artist-tab').removeClass('artist-tab-active').attr('aria-selected', 'false');
    
    // Determine which tab should be active
    const tabToShow = (activeTab === 'artists' || activeTab === 'biography') ? 'biography' : 'artworks';
    const tabId = tabToShow === 'artworks' ? 'artworks-tab' : 'biography-tab';
    const contentId = tabToShow === 'artworks' ? 'artworks-content' : 'biography-content';
    
    // Set active tab highlighting
    $('.artist-tab[data-tab="' + tabToShow + '"]').addClass('artist-tab-active').attr('aria-selected', 'true');
    
    // Show/hide tab content
    $('.artist-tab-content').hide().attr('aria-hidden', 'true');
    // Validate contentId to prevent invalid selector
    if (contentId && typeof contentId === 'string' && contentId.trim() !== '') {
        const contentEl = document.getElementById(contentId);
        if (contentEl) {
            $(contentEl).show().attr('aria-hidden', 'false');
        }
    }
    
    // Show/hide filters based on active tab
    if (tabToShow === 'artworks') {
        $('#gallery-artworks-filters').show();
        $('#artworks-count, #artworks-label').show();
        $('#artists-count, #artists-label').hide();
    } else {
        $('#gallery-artworks-filters').hide();
        $('#artworks-count, #artworks-label').hide();
        $('#artists-count, #artists-label').show();
    }
    
    // Update activeTab to match what we're showing
    activeTab = tabToShow;
    
    // Initialize filter counts and tags
    updateFilterCount('style');
    updateFilterCount('medium');
    updateFilterCount('artist');
    updateConsolidatedFilterCount();
    updatePriceDisplay();
    updateFilterCount('price');
    updateActiveTags();
    updateClearAllVisibility();
    updateMobileFilterLabels();
    
    // Sync mobile filters on page load (like artwork-listing.js)
    syncMobileFiltersOnLoad();
    
    // Track artist card clicks (for artists tab)
    $(document).on('click', '.artist-card-link', function(e) {
        const artistId = $(this).data('artist-id') || 
                        $(this).attr('href').match(/\/(\d+)$/)?.[1];
        if (artistId) {
            trackEvent('gallery_artist_card_click', {
                artist_id: artistId,
                gallery_id: galleryState.galleryId
            });
        }
    });
    
    /**
     * Intercept artwork card clicks to preserve filter state in URL
     * When user clicks an artwork card, update URL with current filters before navigation
     * This ensures filters are maintained when user presses browser back button
     */
    $(document).on('click', '.gallery-artwork-card-link', function(e) {
        if (activeTab !== 'artworks') return;
        
        // Build URL with current filter state
        const params = new URLSearchParams();
        params.append('tab', 'artworks');
        
        if (filterState.sort && filterState.sort !== 'recently-added') {
            params.append('filterState[sort]', filterState.sort);
        }
        
        if (filterState.style && filterState.style.length > 0) {
            filterState.style.forEach(function(v) { params.append('filterState[style][]', v); });
        }
        if (filterState.medium && filterState.medium.length > 0) {
            filterState.medium.forEach(function(v) { params.append('filterState[medium][]', v); });
        }
        if (filterState.artist && filterState.artist.length > 0) {
            filterState.artist.forEach(function(v) { params.append('filterState[artist][]', v); });
        }
        if (filterState.price && (filterState.price.min !== 0 || filterState.price.max !== 20000)) {
            params.append('filterState[price][min]', filterState.price.min);
            params.append('filterState[price][max]', filterState.price.max);
        }
        
        // Update URL with filters before navigation
        const baseUrl = window.location.pathname;
        const newUrl = baseUrl + '?' + params.toString();
        window.history.replaceState({ path: newUrl }, '', newUrl);
        
        // Allow default navigation to proceed
    });

    // Mobile Filter Overlay Functionality
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

    // Toggle mobile filter overlay - use direct binding like artwork listing
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
    // Make sure backdrop doesn't interfere with content clicks
    $('#mobile-filter-close').on('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        mobileFilterOverlay.close();
    });
    
    // Backdrop click - only close if clicking directly on backdrop, not on content
    $('#mobile-filter-backdrop').on('click', function(e) {
        if (e.target !== this) return; // ✅ critical
        e.preventDefault();
        mobileFilterOverlay.close();
    });

    // See Results button - close overlay (like artwork-listing.js)
    // Filters are applied immediately when checkboxes change, so we just close the overlay
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

    // Sync mobile filter checkboxes with desktop (like artwork-listing.js)
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
        const minVal = filterState.price.min || 0;
        const maxVal = filterState.price.max || 20000;
        
        $('#mobile-filter-content .artworks-price-range-min').val(minVal);
        $('#mobile-filter-content .artworks-price-range-max').val(maxVal);
        $('#price-min-input-mobile').val(minVal);
        $('#price-max-input-mobile').val(maxVal);
        var minFormatted = formatPrice(minVal);
        var maxFormatted = formatPrice(maxVal);
        $('#price-min-display-mobile').text(minFormatted + ' USD');
        var maxMobileText = maxFormatted.endsWith('+') ? maxFormatted + ' USD' : maxFormatted + '+ USD';
        $('#price-max-display-mobile').text(maxMobileText);
    }
    
    // Handle mobile price range sliders - sync with desktop, debounced filter
    $(document).on('input', '#mobile-filter-content .artworks-price-range-min', function() {
        const val = parseInt($(this).val()) || 0;
        $('#price-min-input-mobile').val(val);
        $('#price-min-input').val(val);
        $('.artworks-price-range-min').val(val);
        filterState.price.min = val;
        isPriceTouched = true;
        updatePriceDisplay();
        updateFilterCount('price');
        updateActiveTags();
        applyPriceFilter();
    });

    $(document).on('input', '#mobile-filter-content .artworks-price-range-max', function() {
        const val = parseInt($(this).val()) || 20000;
        $('#price-max-input-mobile').val(val);
        $('#price-max-input').val(val);
        $('.artworks-price-range-max').val(val);
        filterState.price.max = val;
        updatePriceDisplay();
        updateFilterCount('price');
        updateActiveTags();
        applyPriceFilter();
    });

    // Mobile price inputs - sync with desktop, debounced filter
    $(document).on('input', '#price-min-input-mobile', function() {
        const val = parseInt($(this).val()) || 0;
        if (val >= 0 && val <= 20000) {
            $('#price-min-input').val(val);
            $('.artworks-price-range-min').val(val);
            filterState.price.min = val;
            updatePriceDisplay();
            updateFilterCount('price');
            updateActiveTags();
        }
        applyPriceFilter();
    });

    $(document).on('input', '#price-max-input-mobile', function() {
        const val = parseInt($(this).val()) || 20000;
        if (val >= 0 && val <= 20000) {
            $('#price-max-input').val(val);
            $('.artworks-price-range-max').val(val);
            filterState.price.max = val;
            updatePriceDisplay();
            updateFilterCount('price');
            updateActiveTags();
        }
        applyPriceFilter();
    });

    // Mobile filter button handler is now at line 819 - removed duplicate

    // Close mobile panels when clicking outside (but inside overlay) - EXACTLY like artwork-listing.js
    $(document).on('click', '#mobile-filter-content', function(e) {
        // Skip if on artwork listing page
        if (!$('#gallery-artworks-filters').length) {
            return;
        }
        
        if (!$(e.target).closest('.artworks-filter-button-wrapper').length && 
            !$(e.target).closest('.artworks-filter-panel').length) {
            // Close all panels except Price Range which is always active
            $('#mobile-filter-content .artworks-filter-panel').not('#price-mobile-panel').removeClass('active');
            $('#mobile-filter-content .artworks-filter-button-mobile').removeClass('active');
        }
    });

    // Initialize filter counts on page load (after all functions are defined)
    // This ensures counts are displayed correctly when page loads with filters in URL
    updateFilterCount('style');
    updateFilterCount('medium');
    updateFilterCount('artist');
    updateConsolidatedFilterCount();
    updateFilterCount('price');
    
    // Initialize price range CSS variables for proper display on page load
    // This ensures the selected range displays correctly from the start
    updatePriceDisplay();
    
    // Initialize mobile filter labels and sync on page load (like artwork-listing.js)
    updateMobileFilterLabels();
    syncMobileFiltersOnLoad();
    
    // Ensure Price Range panel is always active in mobile view (like artwork-listing.js)
    $('#price-mobile-panel').addClass('active');
    
    // Initialize Masonry on page load
    if (activeTab === 'artworks') {
        setTimeout(function() {
            initializeMasonry();
        }, 500);
    }
    
    // Handle pagination clicks
    $(document).on('click', '.paginate-gallery-artworks', function(e) {
        e.preventDefault();
        if (activeTab !== 'artworks') return;
        
        var page = $(this).data('page');
        if (page) {
            getArtworks(page);
        }
    });
});
} // End of gallery details page check
