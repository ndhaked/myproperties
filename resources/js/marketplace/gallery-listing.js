import {  handleFilterBarPosition } from '../utils/filter-ui';
/**
 * Gallery Listing Page JavaScript
 * Handles search, filtering, sorting, and pagination for gallery listings
 */
$(document).ready(function() {
    // Only run on gallery listing page
    if (!$('#gallery-list-container').length) {
        return;
    }
    
    // Get initial filter state from data attributes
    const initialFilterState = window.galleryFilterState || {
        country: [],
        specialty: [],
        sort: 'recently-added'
    };
    
    // Filter state for galleries - initialize from URL parameters
    var filterState = {
        country: Array.isArray(initialFilterState.country) ? initialFilterState.country : (initialFilterState.country ? [initialFilterState.country] : []),
        specialty: Array.isArray(initialFilterState.specialty) ? initialFilterState.specialty : (initialFilterState.specialty ? [initialFilterState.specialty] : []),
        sort: initialFilterState.sort || 'recently-added'
    };
    
    // Get route URL from data attribute
    const galleriesRoute = window.galleriesRoute || '/marketplace/galleries';
    
    // Parse URL parameters to update filterState
    function parseUrlParams() {
        const params = new URLSearchParams(window.location.search);
        
        // Parse search
        const search = params.get('search');
        if (search !== null) {
            $('#gallery-search-input, #gallery-search-input-mobile').val(search);
        }
        
        // Parse country (multi-select)
        const countryParams = params.getAll('filterState[country][]');
        if (countryParams.length > 0) {
            filterState.country = countryParams;
        } else {
            filterState.country = [];
        }
        
        // Parse specialty
        const specialtyParams = params.getAll('filterState[specialty][]');
        if (specialtyParams.length > 0) {
            filterState.specialty = specialtyParams;
        } else {
            filterState.specialty = [];
        }
        
        // Parse sort
        const sort = params.get('filterState[sort]') || params.get('sort');
        if (sort) {
            filterState.sort = sort;
        } else {
            filterState.sort = 'recently-added';
        }
    }
    
    // Restore filters from filterState to UI
    function restoreFiltersFromState() {
        // Restore country filter (multi-select)
        $('.gallery-filter-country, .gallery-filter-country-mobile').prop('checked', false);
        if (filterState.country && filterState.country.length > 0) {
            filterState.country.forEach(function(id) {
                $('input[name="country"][value="' + id + '"], input[name="country-mobile"][value="' + id + '"]').prop('checked', true);
            });
        }
        
        // Restore specialty filters
        $('.gallery-filter-specialty, .gallery-filter-specialty-mobile').prop('checked', false);
        if (filterState.specialty && filterState.specialty.length > 0) {
            filterState.specialty.forEach(function(id) {
                $('input[name="specialty"][value="' + id + '"], input[name="specialty-mobile"][value="' + id + '"]').prop('checked', true);
            });
        }
        
        // Restore sort
        $('.gallery-sort-radio').prop('checked', false);
        const sortRadio = $('.gallery-sort-radio[value="' + filterState.sort + '"]');
        if (sortRadio.length) {
            sortRadio.prop('checked', true);
            const sortLabel = sortRadio.closest('label').find('span').last().text().trim();
            $('#sort-label').text(sortLabel);
        }
        
        // Update UI
        updateFilterCounts();
        updateActiveTags();
        updateClearAllVisibility();
        updateMobileFilterLabels();
        
        // Reload galleries with restored filters
        getGalleries(1);
    }
    
    // Parse URL params on initial load
    parseUrlParams();
    
    // Initialize filter checkboxes from filterState
    if (filterState.country && filterState.country.length > 0) {
        filterState.country.forEach(function(id) {
            $('input[name="country"][value="' + id + '"], input[name="country-mobile"][value="' + id + '"]').prop('checked', true);
        });
    }
    if (filterState.specialty && filterState.specialty.length > 0) {
        filterState.specialty.forEach(function(id) {
            $('input[name="specialty"][value="' + id + '"], input[name="specialty-mobile"][value="' + id + '"]').prop('checked', true);
        });
    }
    
    // Initialize search input from URL
    const searchValue = window.gallerySearchValue || '';
    if (searchValue) {
        $('#gallery-search-input, #gallery-search-input-mobile').val(searchValue);
    }
    
    // Function to update Clear All visibility based on active filters
    function updateClearAllVisibility() {
        const searchValue = $('#gallery-search-input').val().trim() || $('#gallery-search-input-mobile').val().trim();
        const hasActiveFilters = (filterState.country && filterState.country.length > 0) || 
                                 (filterState.specialty && filterState.specialty.length > 0) ||
                                 (searchValue.length >= 2);
        
        if (hasActiveFilters) {
            $('#clear-all-filters, #clear-all-filters-mobile').show();
            $('.artworks-filter-section').addClass('sticky');
        } else {
            $('#clear-all-filters, #clear-all-filters-mobile').hide();
            $('.artworks-filter-section').removeClass('sticky');
        }
    }
    $(window).on('scroll resize', function () {
       const hasActiveFilters = filterState.country || 
            (filterState.specialty && filterState.specialty.length > 0) ||
            (searchValue.length >= 2);
        if (hasActiveFilters) {
            handleFilterBarPosition();
        } 
    });
    // Initialize filter counts and tags on page load
    updateFilterCounts();
    updateActiveTags();
    updateClearAllVisibility();

    // Toggle filter panels (desktop only - exclude mobile buttons)
    $('.artworks-filter-button').not('.artworks-filter-button-mobile').on('click', function(e) {
        e.stopPropagation();
        const filterType = $(this).data('filter');
        // Validate filterType to prevent invalid selector
        if (!filterType || typeof filterType !== 'string' || filterType.trim() === '') {
            return;
        }
        const panelId = filterType + '-panel';
        const panelEl = document.getElementById(panelId);
        if (!panelEl) return;
        const panel = $(panelEl);
        const isOpen = panel.hasClass('active');

        // Close all desktop panels only
        $('.artworks-filter-panel').not('#mobile-filter-content .artworks-filter-panel').removeClass('active');
        $('.artworks-filter-button').not('.artworks-filter-button-mobile').removeClass('active');

        // Toggle current panel
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

    // Search input handler (min 2 characters, on Enter)
    $('#gallery-search-input').on('keypress', function(e) {
        if (e.which === 13) { // Enter key
            e.preventDefault();
            updateClearAllVisibility();
            getGalleries(1);
        }
    });

    // Search input handler for mobile (min 2 characters, on Enter) - updates results without page reload
    $('#gallery-search-input-mobile').on('keypress', function(e) {
        if (e.which === 13) { // Enter key
            e.preventDefault();
            const search = $(this).val().trim();
            // Sync with desktop search
            $('#gallery-search-input').val(search);
            // Update Clear All visibility
            updateClearAllVisibility();
            // Only search if 2+ characters or empty
            if (search.length >= 2 || search.length === 0) {
                getGalleries(1);
            }
        }
    });
    
    // Sync search inputs between desktop and mobile
    $('#gallery-search-input').on('input', function() {
        $('#gallery-search-input-mobile').val($(this).val());
        // Update Clear All visibility when search changes
        updateClearAllVisibility();
        // Clear search when input is cleared - updates results without page reload
        if ($(this).val().trim() === '') {
            getGalleries(1);
        }
    });
    
    $('#gallery-search-input-mobile').on('input', function() {
        $('#gallery-search-input').val($(this).val());
        // Update Clear All visibility when search changes
        updateClearAllVisibility();
        // Clear search when input is cleared - updates results without page reload
        if ($(this).val().trim() === '') {
            getGalleries(1);
        }
    });

    // Country filter (checkbox - multi-select) - desktop and mobile
    // Using event delegation to handle dynamically added elements
    $(document).on('change', '.gallery-filter-country, .gallery-filter-country-mobile', function() {
        const value = $(this).val();
        const isChecked = $(this).is(':checked');
        
        // Update filterState array
        if (isChecked) {
            if (!filterState.country.includes(value)) {
                filterState.country.push(value);
            }
        } else {
            filterState.country = filterState.country.filter(id => id !== value);
        }
        
        // Sync mobile and desktop
        $('input[name="country"][value="' + value + '"], input[name="country-mobile"][value="' + value + '"]').not(this).prop('checked', isChecked);
        
        // Update filter counts and active tags immediately
        updateFilterCounts();
        updateActiveTags();
        updateClearAllVisibility();
        
        // Update results without page reload (AJAX)
        getGalleries(1);
    });

    // Specialty filter (checkbox - multi-select) - desktop and mobile
    // Using event delegation to handle dynamically added elements
    $(document).on('change', '.gallery-filter-specialty, .gallery-filter-specialty-mobile', function() {
        const value = $(this).val();
        const isChecked = $(this).is(':checked');
        
        if (isChecked) {
            if (!filterState.specialty.includes(value)) {
                filterState.specialty.push(value);
            }
        } else {
            filterState.specialty = filterState.specialty.filter(v => v !== value);
        }
        
        // Sync mobile and desktop
        $('input[name="specialty"][value="' + value + '"], input[name="specialty-mobile"][value="' + value + '"]').not(this).prop('checked', isChecked);
        
        // Update filter counts and active tags immediately
        updateFilterCounts();
        updateActiveTags();
        updateClearAllVisibility();
        
        // Update results without page reload (AJAX)
        getGalleries(1);
    });

    // Sort filter
    $('.gallery-sort-radio').on('change', function() {
        filterState.sort = $(this).val();
        $('#sort-label').text($(this).closest('label').find('span').last().text().trim());
        getGalleries(1);
        $('#sort-panel').removeClass('active');
    });

    // Update filter count badges
    function updateFilterCounts() {
        // Location count (multi-select)
        const locationCount = filterState.country && filterState.country.length > 0 ? filterState.country.length : 0;
        if (locationCount > 0) {
            $('#location-count').text('(' + locationCount + ')').show();
        } else {
            $('#location-count').text('').hide();
        }

        // Specialty count
        const specialtyCount = filterState.specialty ? filterState.specialty.length : 0;
        if (specialtyCount > 0) {
            $('#specialty-count').text('(' + specialtyCount + ')').show();
        } else {
            $('#specialty-count').text('').hide();
        }

        // Update consolidated filter count (mobile)
        const totalCount = locationCount + specialtyCount;
        const consolidatedCountElement = $('#consolidated-filter-count');
        if (consolidatedCountElement.length) {
            if (totalCount > 0) {
                consolidatedCountElement.text('(' + totalCount + ')').show();
            } else {
                consolidatedCountElement.text('').hide();
            }
        }
    }

    // Update active filter tags (removable chips)
    function updateActiveTags() {
        const tagsContainer = $('#active-filter-tags');
        // Ensure "Applied Filters:" label exists
        if (tagsContainer.find('.artworks-filter-tags-label').length === 0) {
            tagsContainer.prepend('<span class="artworks-filter-tags-label">Applied Filters:</span>');
        }
        // Remove only filter tags, preserve the label
        tagsContainer.find('.artworks-filter-tag').remove();

        // Add country/location tags (removable chips) - multi-select
        if (filterState.country && filterState.country.length > 0) {
            filterState.country.forEach(function(countryId) {
                const countryLabel = $('input[name="country"][value="' + countryId + '"]').closest('label').find('span').text() ||
                                     $('input[name="country-mobile"][value="' + countryId + '"]').closest('label').find('span').text();
                if (countryLabel) {
                    tagsContainer.append(createFilterTag('country', countryId, countryLabel));
                }
            });
        }

        // Add specialty tags (removable chips)
        if (filterState.specialty && filterState.specialty.length > 0) {
            filterState.specialty.forEach(value => {
                const label = $('input[name="specialty"][value="' + value + '"]').closest('label').find('span').text() ||
                              $('input[name="specialty-mobile"][value="' + value + '"]').closest('label').find('span').text();
                if (label) {
                    tagsContainer.append(createFilterTag('specialty', value, label));
                }
            });
        }

        // Show/hide tags container (check if there are any filter tags, excluding the label)
        const hasTags = tagsContainer.find('.artworks-filter-tag').length > 0;
        if (hasTags) {
            tagsContainer.show();
        } else {
            tagsContainer.hide();
        }

        // Update mobile filter labels
        updateMobileFilterLabels();
    }

    function createFilterTag(filterType, value, label) {
        // Create the tag container
        const $tag = $('<span>').addClass('artworks-filter-tag');
        
        // Create the label text
        $tag.append($('<span>').text(label));
        
        // Create the remove button with proper attributes and styling
        const $removeBtn = $('<button>')
            .addClass('artworks-filter-tag-remove')
            .attr('type', 'button')
            .attr('data-filter', filterType)
            .attr('data-value', value)
            .attr('aria-label', 'Remove ' + label + ' filter');
        
        // Add close-filter icon
        const $icon = $('<img>')
            .attr('src', 'assets/svg/icons/close-filter.svg')
            .addClass('close-filter')
            .attr('alt', 'close-filter')
            .attr('width', '8')
            .attr('height', '8');
        
        $removeBtn.append($icon);
        $tag.append($removeBtn);
        return $tag;
    }

    // Remove filter tag (removable chips) - using event delegation for dynamically added elements
    $(document).on('click', '.artworks-filter-tag-remove', function(e) {
        e.preventDefault();
        e.stopPropagation();
        e.stopImmediatePropagation();
        
        const $btn = $(this);
        const filterType = $btn.attr('data-filter') || $btn.data('filter');
        const value = $btn.attr('data-value') || $btn.data('value');

        if (filterType === 'country') {
            // Remove specific country from array
            filterState.country = filterState.country.filter(id => String(id) !== String(value));
            // Uncheck the specific country filter (desktop and mobile)
            $('input[name="country"][value="' + value + '"], input[name="country-mobile"][value="' + value + '"]').prop('checked', false);
        } else if (filterType === 'specialty') {
            filterState.specialty = filterState.specialty.filter(v => String(v) !== String(value));
            // Uncheck specialty filters (desktop and mobile)
            $('input[name="specialty"][value="' + value + '"], input[name="specialty-mobile"][value="' + value + '"]').prop('checked', false);
        }

        // Update filter counts and tags immediately
        updateFilterCounts();
        updateActiveTags();
        updateClearAllVisibility();
        
        // Reload galleries without page reload
        getGalleries(1);
        
        return false;
    });

    // Clear all filters - removes all active filters and resets results
    // Use event delegation to ensure it works even if elements are dynamically added
    $(document).on('click', '#clear-all-filters, #clear-all-filters-mobile', function(e) {
        e.preventDefault();
        e.stopPropagation();

        // Reset filter state
        filterState.country = [];
        filterState.specialty = [];
        filterState.sort = 'recently-added';

        // Clear search inputs (desktop and mobile)
        $('#gallery-search-input, #gallery-search-input-mobile').val('');

        // Uncheck all checkboxes and radios (desktop and mobile)
        $('.gallery-filter-country, .gallery-filter-country-mobile').prop('checked', false);
        $('.gallery-filter-specialty, .gallery-filter-specialty-mobile').prop('checked', false);
        $('.gallery-sort-radio').prop('checked', false);
        $('.gallery-sort-radio[value="recently-added"]').prop('checked', true);
        $('#sort-label').text('Recently Added');

        // Update filter counts and active tags immediately
        updateFilterCounts();
        updateActiveTags();
        updateClearAllVisibility();

        // Reload galleries without page reload
        getGalleries(1);

        // Close all filter panels
        $('.artworks-filter-panel').removeClass('active');
        $('.artworks-filter-button').removeClass('active');
        
        return false;
    });

    // Pagination handler
    $(document).on('click', '.paginate-gallery', function(e) {
        e.preventDefault();
        const page = $(this).data('page');
        if (page) {
            getGalleries(page);
        }
    });

    // Build URL with current filters
    function buildGalleryUrl(page = 1) {
        const search = $('#gallery-search-input').val().trim() || $('#gallery-search-input-mobile').val().trim();
        const params = new URLSearchParams();
        
        if (search && search.length >= 2) {
            params.append('search', search);
        }
        
        if (filterState.country && filterState.country.length > 0) {
            filterState.country.forEach(function(id) {
                params.append('filterState[country][]', id);
            });
        }
        
        if (filterState.specialty && filterState.specialty.length > 0) {
            filterState.specialty.forEach(function(id) {
                params.append('filterState[specialty][]', id);
            });
        }
        
        if (filterState.sort && filterState.sort !== 'recently-added') {
            params.append('filterState[sort]', filterState.sort);
        }
        
        if (page > 1) {
            params.append('page', page);
        }
        
        const queryString = params.toString();
        return galleriesRoute + (queryString ? '?' + queryString : '');
    }
    
    // AJAX function to load galleries - updates results without page reload
    // Make it globally accessible for retry button
    window.getGalleries = function(page = 1) {
        const search = $('#gallery-search-input').val().trim() || $('#gallery-search-input-mobile').val().trim();
        
        // Only search if 2+ characters or empty
        if (search.length > 0 && search.length < 2) {
            return; // Don't search with less than 2 characters
        }

        // Build AJAX data - send filterState as nested object (like artworks page)
        $.ajax({
            url: galleriesRoute,
            type: 'GET',
            data: {
                page: page,
                search: search,
                sort: filterState.sort,
                filterState: filterState  // Send entire filterState object
            },
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            success: function(response) {
                // Update results without page reload
                $('#gallery-list-container').html(response);
                
                // Extract total count from data attribute in response
                let totalCount = null;
                
                // Try to extract from response string first (more reliable)
                const countMatch = response.match(/data-gallery-total=["']?(\d+)["']?/);
                if (countMatch && countMatch[1]) {
                    totalCount = parseInt(countMatch[1]);
                } else {
                    // Fallback: try to get from DOM after insertion
                    const $dataElement = $('#gallery-list-container [data-gallery-total]');
                    if ($dataElement.length) {
                        totalCount = parseInt($dataElement.attr('data-gallery-total'));
                    }
                }
                
                // Update count display if we found a valid count
                if (totalCount !== null && !isNaN(totalCount)) {
                    $('#gallery-total-count').text(totalCount);
                    $('.globa-count-title').html('<span id="gallery-total-count">' + totalCount + '</span> ' + (totalCount == 1 ? 'Gallery' : 'Galleries'));
                }
                
                // Update filter counts and active tags (removable chips) after results update
                updateFilterCounts();
                updateActiveTags();
                updateClearAllVisibility();
                
                // Update URL without page reload (using pushState for browser history)
                const newUrl = buildGalleryUrl(page);
                if (window.history && window.history.pushState) {
                    window.history.pushState({ path: newUrl }, '', newUrl);
                }
                
                // Scroll to top of results
                $('html, body').animate({
                    scrollTop: $('.artworks-filter-section').offset().top - 100
                }, 300);
            },
            error: function(xhr) {
                console.error('Error fetching galleries:', xhr);
                $('#gallery-list-container').html(
                    '<div class="text-center py-12">' +
                    '<p class="text-red-600 mb-4">An error occurred while loading galleries.</p>' +
                    '<button id="retry-galleries-btn" class="px-4 py-2 bg-gray-900 text-white rounded">Retry</button>' +
                    '</div>'
                );
            }
        });
    }
    
    // Make getGalleries globally accessible for retry button
    window.getGalleries = getGalleries;
    
    // Handle retry button click using event delegation
    $(document).on('click', '#retry-galleries-btn', function(e) {
        e.preventDefault();
        getGalleries(1);
    });

    // Initialize filter counts on page load
    updateFilterCounts();
    updateActiveTags();

    // Mobile Filter Overlay Management
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
                syncMobileFiltersOnLoad();
                updateMobileFilterLabels();
                
                overlay.removeClass('opacity-0 invisible pointer-events-none')
                       .addClass('opacity-100 visible pointer-events-auto');
                backdrop.removeClass('opacity-0').addClass('opacity-100');
                content.removeClass('-translate-x-full').addClass('translate-x-0');
                
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
                
                $('body').css('overflow', '');
            }
        }
    };

    $('#mobile-filters-toggle').on('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        if (mobileFilterOverlay.isOpen()) {
            mobileFilterOverlay.close();
        } else {
            mobileFilterOverlay.open();
        }
    });

    $('#mobile-filter-close, #mobile-filter-backdrop').on('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        mobileFilterOverlay.close();
    });

    $('#mobile-filter-see-results').on('click', function(e) {
        e.preventDefault();
        mobileFilterOverlay.close();
    });

    $(document).on('keydown', function(e) {
        if (e.key === 'Escape' && mobileFilterOverlay.isOpen()) {
            mobileFilterOverlay.close();
        }
    });

    function syncMobileFiltersOnLoad() {
        // Sync specialty checkboxes
        $('.gallery-filter-specialty, .gallery-filter-specialty-mobile').each(function() {
            const value = $(this).val();
            const isChecked = $(this).is(':checked');
            $('input[name="specialty"][value="' + value + '"], input[name="specialty-mobile"][value="' + value + '"]').not(this).prop('checked', isChecked);
        });
        // Sync country checkboxes
        $('.gallery-filter-country, .gallery-filter-country-mobile').each(function() {
            const value = $(this).val();
            const isChecked = $(this).is(':checked');
            $('input[name="country"][value="' + value + '"], input[name="country-mobile"][value="' + value + '"]').not(this).prop('checked', isChecked);
        });
    }

    function updateMobileFilterLabels() {
        // Location label (multi-select)
        const locationLabel = $('#location-mobile-label');
        if (filterState.country && filterState.country.length > 0) {
            if (filterState.country.length === 1) {
                const label = $('input[name="country-mobile"][value="' + filterState.country[0] + '"]').closest('label').find('span').text();
                locationLabel.text(label || 'Location Selected');
            } else {
                locationLabel.text(filterState.country.length + ' Selected');
            }
        } else {
            locationLabel.text('All Locations');
        }

        // Specialty label
        const specialtyLabel = $('#specialty-mobile-label');
        const specialtyCount = filterState.specialty ? filterState.specialty.length : 0;
        if (specialtyCount === 0) {
            specialtyLabel.text('All Specialities');
        } else if (specialtyCount === 1) {
            const label = $('input[name="specialty-mobile"][value="' + filterState.specialty[0] + '"]').closest('label').find('span').text();
            specialtyLabel.text(label || 'Speciality Selected');
        } else {
            specialtyLabel.text(specialtyCount + ' Selected');
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
        const filterType = $(this).data('filter');
        // Validate filterType to prevent invalid selector
        if (!filterType || typeof filterType !== 'string' || filterType.trim() === '') {
            return;
        }
        const panelId = filterType + '-panel';
        const panelEl = document.getElementById(panelId);
        if (!panelEl) return;
        const panel = $(panelEl);
        const isOpen = panel.hasClass('active');

        // Close all mobile panels first
        $('#mobile-filter-content .artworks-filter-panel').removeClass('active');
        $('#mobile-filter-content .artworks-filter-button-mobile').removeClass('active');

        // Toggle current panel
        if (!isOpen) {
            panel.addClass('active');
            $(this).addClass('active');
        }
    });

    // Close mobile panels when clicking outside (but inside overlay)
    $(document).on('click', '#mobile-filter-content', function(e) {
        if (!$(e.target).closest('.artworks-filter-button-wrapper').length && 
            !$(e.target).closest('.artworks-filter-panel').length) {
            $('#mobile-filter-content .artworks-filter-panel').removeClass('active');
            $('#mobile-filter-content .artworks-filter-button-mobile').removeClass('active');
        }
    });

    // Mobile filter see results button
    $('#mobile-filter-see-results').on('click', function(e) {
        e.preventDefault();
        mobileFilterOverlay.close();
        getGalleries(1);
    });

    $('#clear-all-filters-mobile').on('click', function(e) {
        e.preventDefault();
        $('#clear-all-filters').click();
        updateMobileFilterLabels();
    });

    updateMobileFilterLabels();
    syncMobileFiltersOnLoad();

    // Handle browser back/forward navigation
    window.addEventListener('popstate', function(event) {
        // Parse URL parameters and restore filters
        parseUrlParams();
        restoreFiltersFromState();
    });

    document.addEventListener('DOMContentLoaded', function () {
        const referrer = document.referrer;

        // Only rewrite history if user came from homepage
        if (referrer && referrer.includes('/marketplace')) {
            history.replaceState(
                null,
                '',
                referrer + '#galleries-section'
            );
        }
    });

});

