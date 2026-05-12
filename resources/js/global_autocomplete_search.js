if($("#global_auto_search").length > 0 ) {

    (function() {
        'use strict';

        // Wait for DOM to be ready
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initMarketplaceSearch);
        } else {
            initMarketplaceSearch();
        }

        function initMarketplaceSearch() {
            // --- 1. DOM Elements ---
            const searchWrapper = document.querySelector('.search-wrapper');
            const searchInput = document.getElementById('marketplace-search-input');
            const clearButton = searchWrapper ? searchWrapper.querySelector('.sub-search-clear') : document.querySelector('.sub-search-clear');
            const suggestionsDropdown = document.getElementById('search-suggestions');
            const suggestionsList = document.getElementById('search-suggestions-list');
            const footerLink = document.querySelector('.search-suggestions-footer');
            const navigation = document.querySelector('.site-sub-nav');
            const seeAllResults = document.getElementById('see_all_results');
            
            // Hidden Inputs
            const hiddenId = document.getElementById('selected-id');
            const hiddenType = document.getElementById('selected-type');

            // Validation
            if (!searchWrapper || !searchInput || !suggestionsDropdown || !suggestionsList) {
                console.warn('Marketplace search elements not found');
                return;
            }

            let debounceTimer;
            let activeIndex = -1;

            // --- 2. Event Listeners ---

            // Helper: What to do when user clicks or tabs into the box
            function onSearchInteract() {
                // Force expansion immediately (UI changes)
                expandSearch();
                
                // Optional: If you want to show recent searches or default items immediately,
                // you could call fetchResults('') here.
            }

            // Trigger expansion on Click AND Focus
            searchInput.addEventListener('click', onSearchInteract);
            searchInput.addEventListener('focus', onSearchInteract);

            // Input Handling (Typing)
            searchInput.addEventListener('input', function() {
                updateClearButton();
                
                const query = this.value.trim();

                // Clear previous timer
                clearTimeout(debounceTimer);

                // Debounce: Wait 300ms before sending request
                debounceTimer = setTimeout(() => {
                    if (query.length >= 2) {
                        fetchResults(query);
                    } else {
                        // If input is cleared, clear the list but keep the bar expanded
                        suggestionsList.innerHTML = '';
                        clearActiveIndex();
                        if (suggestionsDropdown) suggestionsDropdown.style.display = 'none';
                    }
                }, 300);
            });

            // Keyboard Navigation (Arrow Up/Down, Enter, Esc)
            searchInput.addEventListener('keydown', function(e) {
                const items = getSuggestionItems();
                const hasItems = items.length > 0;

                if (e.key === 'ArrowDown') {
                    if (!hasItems) return;
                    e.preventDefault();
                    const nextIndex = (activeIndex + 1) % items.length;
                    setActiveIndex(nextIndex);
                    return;
                }

                if (e.key === 'ArrowUp') {
                    if (!hasItems) return;
                    e.preventDefault();
                    const nextIndex = (activeIndex - 1 + items.length) % items.length;
                    setActiveIndex(nextIndex);
                    return;
                }

                if (e.key === 'Enter') {
                    if (hasItems && activeIndex >= 0 && items[activeIndex]) {
                        e.preventDefault();
                        items[activeIndex].click();
                        return;
                    }

                    e.preventDefault(); // Stop form submission
                    const tabType = searchInput?.tabs || 'artworks';
                    window.location.href = `/marketplace-search?q=${encodeURIComponent(this.value)}&tab=${encodeURIComponent(tabType)}`;
                }

                if (e.key === 'Escape') {
                    if (hasItems || suggestionsDropdown.style.display === 'block') {
                        e.preventDefault();
                        clearActiveIndex();
                        collapseSearch();
                    }
                }
            });

            // Clear Button Click
            if (clearButton) {
                clearButton.addEventListener('click', (e) => {
                    e.preventDefault();
                    searchInput.value = '';
                    
                    // Clear hidden fields
                    if(hiddenId) hiddenId.value = '';
                    if(hiddenType) hiddenType.value = '';

                    searchInput.focus();
                    updateClearButton();
                    collapseSearch();
                    suggestionsList.innerHTML = '';
                    
                    // Keep expanded to allow new typing, or call collapseSearch() if you prefer closing it
                    // collapseSearch(); 
                });
            }

            // Close on click outside
            document.addEventListener('click', (e) => {
                if (!searchWrapper.contains(e.target)) {
                    collapseSearch();
                }
            });

            // --- 3. AJAX Fetch Logic ---
            function fetchResults(query) {
                // Update "See All Results" link immediately
                if(seeAllResults) {
                    const tabType = searchInput.tabs || '';
                    seeAllResults.href = `/marketplace-search?q=${encodeURIComponent(query)}&tab=${encodeURIComponent('artworks')}`;
                }
                // Fetch with timestamp to prevent caching
                fetch(`/marketplace/autocomplete-search?query=${encodeURIComponent(query)}&t=${new Date().getTime()}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.length > 0) {
                            renderSuggestions(data, query);
                            // Ensure dropdown is visible now that we have data
                            if (suggestionsDropdown) suggestionsDropdown.style.display = 'block';
                        } else {
                            // No results: Hide dropdown but keep bar expanded
                            clearActiveIndex();
                            if (suggestionsDropdown) suggestionsDropdown.style.display = 'none';
                        }
                    })
                    .catch(error => console.error('Error fetching suggestions:', error));
            }

            // --- 4. Render Logic ---
            function renderSuggestions(data, query) {
                suggestionsList.innerHTML = ''; // Clear previous results
                clearActiveIndex();

                data.forEach(item => {
                    const li = document.createElement('li');
                    li.className = 'search-suggestions-item';
                    li.setAttribute('role', 'option');
                    li.setAttribute('tabindex', '0');
                    li.setAttribute('aria-selected', 'false');

                    const textToHighlight = item.value || item.label; 
                    const regex = new RegExp(`(${query})`, 'gi');
                    const highlightedName = textToHighlight.replace(regex, '<strong>$1</strong>');

                    const iconPath = item.image ? item.image : `${getAssetBase()}assets/svg/icons/searchIcon.svg`;

                    li.innerHTML = `
                        <span class="search-suggestions-item-icon">
                            <img src="${iconPath}" alt="" width="20" height="20" style="object-fit:cover; border-radius:50%;">
                        </span>
                        <div class="search-suggestions-item-content">
                            <div class="search-suggestions-item-text">${highlightedName}</div>
                            <div class="search-suggestions-item-type">${item.type}</div>
                        </div>
                    `;

                    li.addEventListener('click', () => {
                        searchInput.value = textToHighlight; 
                        
                        if(hiddenId) hiddenId.value = item.id || ''; 
                        if(hiddenType) hiddenType.value = item.type || '';
                        
                        collapseSearch();

                        if (item.url) {
                            window.location.href = item.url;
                        } else {
                           // window.location.href = `/marketplace-search?q=${encodeURIComponent(textToHighlight)}`;
                            window.location.href = `/marketplace-search?q=${encodeURIComponent(textToHighlight)}&tab=${encodeURIComponent(item.tabs)}`;
                        }
                    });

                    suggestionsList.appendChild(li);
                });
            }

            // --- 5. Helper Functions ---

            function expandSearch() {
                // 1. Always expand the wrapper (hides nav, widens input)
                searchWrapper.classList.add('expanded');
                if (navigation) navigation.style.display = 'none';

                updateClearButton();

                // 3. Only show the dropdown box if there are actually results inside
                // This prevents an empty white box from showing up immediately
                if (suggestionsDropdown && suggestionsList.children.length > 0) {
                    suggestionsDropdown.style.display = 'block';
                } else {
                    suggestionsDropdown.style.display = 'none';
                }
            }

            function collapseSearch() {
                searchWrapper.classList.remove('expanded');
                if (navigation) navigation.style.display = '';
                if (suggestionsDropdown) suggestionsDropdown.style.display = 'none';
                clearActiveIndex();
            }

            function updateClearButton() {
                if (clearButton) {
                    // Show button only when search is expanded AND has text
                    const hasText = searchInput.value && searchInput.value.trim().length > 0;
                    const isExpanded = searchWrapper.classList.contains('expanded');
                    if (hasText && isExpanded) {
                        clearButton.style.display = 'inline-flex';
                    } else {
                        clearButton.style.display = 'none';
                    }
                }
            }

            function getAssetBase() {
                return document.body.dataset.assetBase || '/';
            }

            function getSuggestionItems() {
                return Array.from(suggestionsList.querySelectorAll('.search-suggestions-item'));
            }

            function clearActiveIndex() {
                const items = getSuggestionItems();
                items.forEach(item => {
                    item.classList.remove('is-active');
                    item.setAttribute('aria-selected', 'false');
                });
                activeIndex = -1;
            }

            function setActiveIndex(index) {
                const items = getSuggestionItems();
                if (!items.length) return;
                if (index < 0 || index >= items.length) return;
                items.forEach(item => {
                    item.classList.remove('is-active');
                    item.setAttribute('aria-selected', 'false');
                });
                items[index].classList.add('is-active');
                items[index].setAttribute('aria-selected', 'true');
                items[index].scrollIntoView({ block: 'nearest' });
                activeIndex = index;
            }
        }



        
  
        window.MobileSearchOverlay = {
            isOpen: function() {
                const overlay = document.getElementById('mobile-search-overlay');
                return overlay && overlay.classList.contains('opacity-100');
            },
            open: function() {
                const overlay = document.getElementById('mobile-search-overlay');
                const backdrop = document.getElementById('mobile-search-backdrop');
                const content = document.getElementById('mobile-search-content');
                const searchInput = document.getElementById('mobile-marketplace-search-input');
                const seeAllResults = document.getElementById('mobile_see_all_results');

                if (overlay && backdrop && content) {
                    overlay.classList.remove('opacity-0', 'invisible', 'pointer-events-none');
                    overlay.classList.add('opacity-100', 'visible', 'pointer-events-auto');
                    backdrop.classList.remove('opacity-0');
                    backdrop.classList.add('opacity-100');
                    content.classList.remove('-translate-y-full');
                    content.classList.add('translate-y-0');
                    
                    // Prevent body scroll
                    document.body.style.overflow = 'hidden';
                    
                    // Focus search input
                    if (searchInput) {
                        setTimeout(() => {
                            searchInput.focus();
                        }, 100);
                    }
                }
            },
            close: function() {
                const overlay = document.getElementById('mobile-search-overlay');
                const backdrop = document.getElementById('mobile-search-backdrop');
                const content = document.getElementById('mobile-search-content');
                const searchInput = document.getElementById('mobile-marketplace-search-input');

                if (overlay && backdrop && content) {
                    overlay.classList.remove('opacity-100', 'visible', 'pointer-events-auto');
                    overlay.classList.add('opacity-0', 'invisible', 'pointer-events-none');
                    backdrop.classList.remove('opacity-100');
                    backdrop.classList.add('opacity-0');
                    content.classList.remove('translate-y-0');
                    content.classList.add('-translate-y-full');
                    
                    // Restore body scroll
                    document.body.style.overflow = '';
                    
                    // Clear search input and hide suggestions
                    if (searchInput) {
                        searchInput.value = '';
                        searchInput.blur();
                    }
                    const suggestions = document.getElementById('mobile-search-suggestions');
                    if (suggestions) {
                        suggestions.style.display = 'none';
                    }
                    const clearButton = document.getElementById('mobile-search-clear');
                    if (clearButton) {
                        clearButton.style.display = 'none';
                    }
                }
            }
        };

       
        function initMobileSearchOverlay() {
            const toggleButton = document.getElementById('mobile-search-toggle');
            const closeButton = document.getElementById('mobile-search-close');
            const overlay = document.getElementById('mobile-search-overlay');
            const backdrop = document.getElementById('mobile-search-backdrop');

            if (!toggleButton || !closeButton || !overlay) {
                return;
            }

            // Toggle button click
            toggleButton.addEventListener('click', (e) => {
                e.preventDefault();
                e.stopPropagation();
                
                // Close mobile menu if open
                if (window.closeMobileMenu) {
                    window.closeMobileMenu();
                }
                
                // Ensure menu icon state is reset
                const menuIcon = document.getElementById('menu-icon');
                const closeIcon = document.getElementById('close-icon');
                const menuToggle = document.getElementById('mobile-menu-toggle');
                if (menuIcon && closeIcon && menuToggle) {
                    menuIcon.classList.remove('hidden');
                    closeIcon.classList.add('hidden');
                    menuToggle.classList.remove('active');
                }
                
                window.MobileSearchOverlay.open();
            });

            // Close button click
            closeButton.addEventListener('click', (e) => {
                e.preventDefault();
                e.stopPropagation();
                window.MobileSearchOverlay.close();
            });

            // Backdrop click to close
            if (backdrop) {
                backdrop.addEventListener('click', (e) => {
                    if (e.target === backdrop) {
                        window.MobileSearchOverlay.close();
                    }
                });
            }

            // Escape key to close
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape' && window.MobileSearchOverlay.isOpen()) {
                    window.MobileSearchOverlay.close();
                }
            });

            // Close on window resize to desktop
            let resizeTimer;
            window.addEventListener('resize', () => {
                clearTimeout(resizeTimer);
                resizeTimer = setTimeout(() => {
                    if (window.innerWidth >= 1024 && window.MobileSearchOverlay.isOpen()) {
                        window.MobileSearchOverlay.close();
                    }
                }, 100);
            });
        }

        
        function initMobileMarketplaceSearch() {
            const searchWrapper = document.querySelector('.mobile-search-wrapper');
            const searchForm = document.getElementById('mobile-marketplace-search-form');
            const searchInput = document.getElementById('mobile-marketplace-search-input');
            const clearButton = document.getElementById('mobile-search-clear');
            const suggestionsDropdown = document.getElementById('mobile-search-suggestions');
            const suggestionsList = document.getElementById('mobile-search-suggestions-list');

            // Optional: If you have a "See All Results" footer in mobile, select it here
            // const footerLink = document.querySelector('.mobile-search-suggestions-footer');

            if (!searchWrapper || !searchInput || !suggestionsDropdown || !suggestionsList) {
                return;
            }

            let debounceTimer;

            // --- Event Listeners ---

            // Input Handling (Typing)
            searchInput.addEventListener('input', function() {
                updateClearButton();
                
                const query = this.value.trim();

                // Clear previous timer
                clearTimeout(debounceTimer);

                // Debounce: Wait 300ms before sending request
                debounceTimer = setTimeout(() => {
                    if (query.length >= 2) {
                        fetchResults(query);
                    } else {
                        // If input is short/empty, hide suggestions
                        suggestionsList.innerHTML = '';
                        clearActiveIndex();
                        if (suggestionsDropdown) suggestionsDropdown.style.display = 'none';
                    }
                }, 300);
            });

            // Handle Form Submit (Enter key or Go button on mobile keyboard)
            if (searchForm) {
                searchForm.addEventListener('submit', (e) => {
                    e.preventDefault();
                    performSearch(searchInput.value);
                });
            }

            // Keyboard Navigation (Arrow Up/Down, Enter, Esc)
            searchInput.addEventListener('keydown', function(e) {
                const items = getSuggestionItems();
                const hasItems = items.length > 0;

                if (e.key === 'ArrowDown') {
                    if (!hasItems) return;
                    e.preventDefault();
                    const nextIndex = (activeIndex + 1) % items.length;
                    setActiveIndex(nextIndex);
                    return;
                }

                if (e.key === 'ArrowUp') {
                    if (!hasItems) return;
                    e.preventDefault();
                    const nextIndex = (activeIndex - 1 + items.length) % items.length;
                    setActiveIndex(nextIndex);
                    return;
                }

                if (e.key === 'Enter') {
                    if (hasItems && activeIndex >= 0 && items[activeIndex]) {
                        e.preventDefault();
                        items[activeIndex].click();
                        return;
                    }
                }

                if (e.key === 'Escape') {
                    if (hasItems || suggestionsDropdown.style.display === 'block') {
                        e.preventDefault();
                        clearActiveIndex();
                        if (suggestionsDropdown) suggestionsDropdown.style.display = 'none';
                    }
                }
            });

            // Handle Clear Button
            if (clearButton) {
                clearButton.addEventListener('click', (e) => {
                    e.preventDefault();
                    e.stopPropagation();
                    searchInput.value = '';
                    searchInput.focus();
                    updateClearButton();
                    suggestionsList.innerHTML = '';
                    clearActiveIndex();
                    if (suggestionsDropdown) suggestionsDropdown.style.display = 'none';
                });
            }

            // --- Logic Functions ---

            function fetchResults(query) {
                const seeAllResults = document.getElementById('mobile_see_all_results');
                if(seeAllResults) {
                    const tabType = searchInput.tabs || '';
                    seeAllResults.href = `/marketplace-search?q=${encodeURIComponent(query)}&tab=${encodeURIComponent('artworks')}`;
                }
                 // Fetch data (Timestamp to prevent caching)
                fetch(`/marketplace/autocomplete-search?query=${encodeURIComponent(query)}&t=${new Date().getTime()}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.length > 0) {
                            renderSuggestions(data, query);
                            if (suggestionsDropdown) suggestionsDropdown.style.display = 'block';
                        } else {
                            clearActiveIndex();
                            if (suggestionsDropdown) suggestionsDropdown.style.display = 'none';
                        }
                    })
                    .catch(error => console.error('Error fetching mobile suggestions:', error));
            }

            function renderSuggestions(data, query) {
                suggestionsList.innerHTML = ''; // Clear previous
                clearActiveIndex();

                data.forEach(item => {
                    const li = document.createElement('li');
                    li.className = 'search-suggestions-item';
                    li.setAttribute('role', 'option');
                    li.setAttribute('tabindex', '0');
                    li.setAttribute('aria-selected', 'false');

                    const textToHighlight = item.value || item.label; 
                    const regex = new RegExp(`(${query})`, 'gi');
                    const highlightedName = textToHighlight.replace(regex, '<strong>$1</strong>');

                    const iconPath = item.image ? item.image : `${getAssetBase()}assets/svg/icons/searchIcon.svg`;

                    // Render Item HTML
                    li.innerHTML = `
                        <span class="search-suggestions-item-icon" aria-hidden="true">
                            <img src="${iconPath}" alt="" width="20" height="20" style="object-fit:cover; border-radius:50%;">
                        </span>
                        <div class="search-suggestions-item-content">
                            <div class="search-suggestions-item-text">${highlightedName}</div>
                            <div class="search-suggestions-item-type">${item.type}</div>
                        </div>
                    `;

                    // Handle Click
                    li.addEventListener('click', () => {
                        searchInput.value = textToHighlight; 
                        
                        // Close the overlay before navigating
                        window.MobileSearchOverlay.close();

                        if (item.url) {
                            window.location.href = item.url;
                        } else {
                            const tab = item.tabs || '';
                            window.location.href = `/marketplace-search?q=${encodeURIComponent(textToHighlight)}&tab=${encodeURIComponent(tab)}`;
                        }
                    });

                    suggestionsList.appendChild(li);
                });
            }

            function performSearch(query) {
                const cleanQuery = query ? query.trim() : '';
                // Determine tab type if available (or default empty)
                const tabType = searchInput.tabs || 'artworks'; 

                if (cleanQuery.length > 0) {
                    window.MobileSearchOverlay.close();
                    window.location.href = `/marketplace-search?q=${encodeURIComponent(cleanQuery)}&tab=${encodeURIComponent(tabType)}`;
                }
            }

            function updateClearButton() {
                if (clearButton) {
                    clearButton.style.display = (searchInput.value && searchInput.value.trim() !== '') ? 'inline-flex' : 'none';
                }
            }

            function getAssetBase() {
                return document.body.dataset.assetBase || '/';
            }

            function getSuggestionItems() {
                return Array.from(suggestionsList.querySelectorAll('.search-suggestions-item'));
            }

            function clearActiveIndex() {
                const items = getSuggestionItems();
                items.forEach(item => {
                    item.classList.remove('is-active');
                    item.setAttribute('aria-selected', 'false');
                });
                activeIndex = -1;
            }

            function setActiveIndex(index) {
                const items = getSuggestionItems();
                if (!items.length) return;
                if (index < 0 || index >= items.length) return;
                items.forEach(item => {
                    item.classList.remove('is-active');
                    item.setAttribute('aria-selected', 'false');
                });
                items[index].classList.add('is-active');
                items[index].setAttribute('aria-selected', 'true');
                items[index].scrollIntoView({ block: 'nearest' });
                activeIndex = index;
            }
        }

       
        if (typeof jQuery !== 'undefined') {
            $(document).ready(function() {
                initMobileSearchOverlay();
                initMobileMarketplaceSearch();
            });
        } else {
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', function() {
                    initMobileSearchOverlay();
                    initMobileMarketplaceSearch();
                });
            } else {
                initMobileSearchOverlay();
                initMobileMarketplaceSearch();
            }
        }
        
    })();

}