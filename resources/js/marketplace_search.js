(function() {
    'use strict';

    // Wait for DOM to be ready
    if (typeof jQuery !== 'undefined') {
        $(document).ready(initMarketplaceSearch);
    } else {
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initMarketplaceSearch);
        } else {
            initMarketplaceSearch();
        }
    }

    function initMarketplaceSearch() {
        const searchWrapper = document.querySelector('.search-wrapper');
        const searchForm = document.getElementById('marketplace-search-form');
        const searchInput = document.getElementById('marketplace-search-input');
        const clearButton = document.querySelector('.sub-search-clear');
        const suggestionsDropdown = document.getElementById('search-suggestions');
        const suggestionsList = document.getElementById('search-suggestions-list');
        const navigation = document.querySelector('.site-sub-nav');

        if (!searchWrapper || !searchInput || !suggestionsDropdown || !suggestionsList) {
            console.warn('Marketplace search elements not found');
            return;
        }

        // Hardcoded suggestions data - always show the same suggestions
        const hardcodedSuggestions = [
            { text: "Pablo Picasso", type: "Artist" },
            { text: "Amalia Pica", type: "Artist" },
            { text: "Teodora Pica", type: "Artist" },
            { text: "Candida Alwarez, Pica Pica 6 (2006)", type: "Artwork" },
            { text: "Noah Pica, Ajar (2023)", type: "Artwork" }
        ];

        // Expand search on focus/click
        function expandSearch() {
            searchWrapper.classList.add('expanded');
            if (navigation) {
                navigation.style.display = 'none';
            }
            updateClearButton(); 
            showSuggestions();
        }

        // Collapse search
        function collapseSearch() {
            searchWrapper.classList.remove('expanded');
            if (navigation) {
                navigation.style.display = '';
            }
            hideSuggestions();
        }

        // Show suggestions dropdown
        function showSuggestions() {
            if (suggestionsDropdown) {
                suggestionsDropdown.style.display = 'block';
                renderHardcodedSuggestions();
            }
        }

        // Hide suggestions dropdown
        function hideSuggestions() {
            if (suggestionsDropdown) {
                suggestionsDropdown.style.display = 'none';
            }
        }

        // Highlight matching text in suggestion
        function highlightMatch(text, query) {
            if (!query || query.trim() === '') {
                return text;
            }

            // Escape special regex characters in query
            const escapedQuery = query.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
            const regex = new RegExp(`(${escapedQuery})`, 'gi');
            return text.replace(regex, '<strong>$1</strong>');
        }

        // Render hardcoded suggestions in dropdown (always shows same 5 items)
        function renderHardcodedSuggestions() {
            const query = searchInput.value;

            // Clear existing suggestions
            suggestionsList.innerHTML = '';

            // Always render all 5 hardcoded suggestions
            hardcodedSuggestions.forEach(suggestion => {
                const item = document.createElement('li');
                item.className = 'search-suggestions-item';
                item.setAttribute('role', 'option');
                item.setAttribute('tabindex', '0');

                // Highlight matching text based on current input value
                const highlightedText = highlightMatch(suggestion.text, query);

                item.innerHTML = `
                    <span class="search-suggestions-item-icon" aria-hidden="true">
                        <img src="${getAssetBase()}assets/svg/icons/searchIcon.svg" alt="" width="20" height="20">
                    </span>
                    <div class="search-suggestions-item-content">
                        <div class="search-suggestions-item-text">${highlightedText}</div>
                        <div class="search-suggestions-item-type">${suggestion.type}</div>
                    </div>
                `;

                // Handle click on suggestion
                item.addEventListener('click', () => {
                    searchInput.value = suggestion.text;
                    updateClearButton();
                    renderHardcodedSuggestions();
                    // In future: navigate to result page
                });

                // Handle keyboard navigation
                item.addEventListener('keydown', (e) => {
                    if (e.key === 'Enter' || e.key === ' ') {
                        e.preventDefault();
                        item.click();
                    }
                });

                suggestionsList.appendChild(item);
            });
        }

        // Update clear button visibility
        function updateClearButton() {
            if (clearButton) {
                if (searchInput.value && searchInput.value.trim() !== '') {
                    clearButton.style.display = 'inline-flex';
                } else {
                    clearButton.style.display = 'none';
                }
            }
        }

        // Handle clear button click
        if (clearButton) {
            clearButton.addEventListener('click', (e) => {
                e.preventDefault();
                e.stopPropagation();
                searchInput.value = '';
                searchInput.focus();
                updateClearButton();
                renderHardcodedSuggestions();
            });
        }

        // Handle search input focus
        searchInput.addEventListener('focus', expandSearch);
        searchInput.addEventListener('click', expandSearch);

        // Handle search input input event (typing)
        // Updates highlighting in suggestions but keeps same 5 items
        searchInput.addEventListener('input', () => {
            updateClearButton();
            if (searchWrapper.classList.contains('expanded')) {
                renderHardcodedSuggestions();
            }
        });

        // Handle click outside to collapse
        document.addEventListener('click', (e) => {
            if (!searchWrapper.contains(e.target) && searchWrapper.classList.contains('expanded')) {
                collapseSearch();
            }
        });

        // Handle escape key to collapse
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && searchWrapper.classList.contains('expanded')) {
                collapseSearch();
                searchInput.blur();
            }
        });

        // Prevent form submission for now (frontend only)
        if (searchForm) {
            searchForm.addEventListener('submit', (e) => {
                e.preventDefault();
                // In future: handle search submission
                console.log('Search submitted:', searchInput.value);
            });
        }

        // Get asset base path
        function getAssetBase() {
            const body = document.body;
            if (body && body.dataset.assetBase) {
                return body.dataset.assetBase;
            }
            return '/';
        }
    }

    // Mobile Search Overlay API
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

    // Initialize Mobile Search Overlay
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
            
            // Close mobile menu if open and reset icon state
            if (window.closeMobileMenu) {
                window.closeMobileMenu();
            }
            
            // Ensure menu icon state is reset (in case closeMobileMenu didn't handle it)
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

        // Close on window resize to desktop size
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

    // Initialize Mobile Marketplace Search
    function initMobileMarketplaceSearch() {
        const searchWrapper = document.querySelector('.mobile-search-wrapper');
        const searchForm = document.getElementById('mobile-marketplace-search-form');
        const searchInput = document.getElementById('mobile-marketplace-search-input');
        const clearButton = document.getElementById('mobile-search-clear');
        const suggestionsDropdown = document.getElementById('mobile-search-suggestions');
        const suggestionsList = document.getElementById('mobile-search-suggestions-list');

        if (!searchWrapper || !searchInput || !suggestionsDropdown || !suggestionsList) {
            return;
        }

        // Hardcoded suggestions data - same as desktop
        const hardcodedSuggestions = [
            { text: "Pablo Picasso", type: "Artist" },
            { text: "Amalia Pica", type: "Artist" },
            { text: "Teodora Pica", type: "Artist" },
            { text: "Candida Alwarez, Pica Pica 6 (2006)", type: "Artwork" },
            { text: "Noah Pica, Ajar (2023)", type: "Artwork" }
        ];

        // Show suggestions
        function showSuggestions() {
            if (suggestionsDropdown) {
                suggestionsDropdown.style.display = 'block';
                renderHardcodedSuggestions();
            }
        }

        // Hide suggestions
        function hideSuggestions() {
            if (suggestionsDropdown) {
                suggestionsDropdown.style.display = 'none';
            }
        }

        // Highlight matching text in suggestion
        function highlightMatch(text, query) {
            if (!query || query.trim() === '') {
                return text;
            }

            // Escape special regex characters in query
            const escapedQuery = query.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
            const regex = new RegExp(`(${escapedQuery})`, 'gi');
            return text.replace(regex, '<strong>$1</strong>');
        }

        // Render hardcoded suggestions
        function renderHardcodedSuggestions() {
            const query = searchInput.value;

            // Clear existing suggestions
            suggestionsList.innerHTML = '';

            // Always render all 5 hardcoded suggestions
            hardcodedSuggestions.forEach(suggestion => {
                const item = document.createElement('li');
                item.className = 'search-suggestions-item';
                item.setAttribute('role', 'option');
                item.setAttribute('tabindex', '0');

                // Highlight matching text based on current input value
                const highlightedText = highlightMatch(suggestion.text, query);

                item.innerHTML = `
                    <span class="search-suggestions-item-icon" aria-hidden="true">
                        <img src="${getAssetBase()}assets/svg/icons/searchIcon.svg" alt="" width="20" height="20">
                    </span>
                    <div class="search-suggestions-item-content">
                        <div class="search-suggestions-item-text">${highlightedText}</div>
                        <div class="search-suggestions-item-type">${suggestion.type}</div>
                    </div>
                `;

                // Handle click on suggestion
                item.addEventListener('click', () => {
                    searchInput.value = suggestion.text;
                    updateClearButton();
                    renderHardcodedSuggestions();
                    // In future: navigate to result page
                });

                // Handle keyboard navigation
                item.addEventListener('keydown', (e) => {
                    if (e.key === 'Enter' || e.key === ' ') {
                        e.preventDefault();
                        item.click();
                    }
                });

                suggestionsList.appendChild(item);
            });
        }

        // Update clear button visibility
        function updateClearButton() {
            if (clearButton) {
                if (searchInput.value && searchInput.value.trim() !== '') {
                    clearButton.style.display = 'inline-flex';
                } else {
                    clearButton.style.display = 'none';
                }
            }
        }

        // Get asset base path
        function getAssetBase() {
            const body = document.body;
            if (body && body.dataset.assetBase) {
                return body.dataset.assetBase;
            }
            return '/';
        }

        // Handle clear button click
        if (clearButton) {
            clearButton.addEventListener('click', (e) => {
                e.preventDefault();
                e.stopPropagation();
                searchInput.value = '';
                searchInput.focus();
                updateClearButton();
                renderHardcodedSuggestions();
            });
        }

        // Handle search input focus
        searchInput.addEventListener('focus', showSuggestions);
        searchInput.addEventListener('click', showSuggestions);

        // Handle search input input event (typing)
        searchInput.addEventListener('input', () => {
            updateClearButton();
            if (window.MobileSearchOverlay.isOpen()) {
                renderHardcodedSuggestions();
                showSuggestions();
            }
        });

        // Prevent form submission for now (frontend only)
        if (searchForm) {
            searchForm.addEventListener('submit', (e) => {
                e.preventDefault();
                // In future: handle search submission
                console.log('Mobile search submitted:', searchInput.value);
            });
        }
    }

    // Initialize mobile search on DOM ready
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