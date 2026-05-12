<div id="mobile-search-overlay" class="mobile-search-overlay fixed inset-0 z-50 transition-all duration-300 ease-in-out opacity-0 invisible pointer-events-none">
    <div id="mobile-search-backdrop" class="mobile-search-backdrop fixed inset-0 bg-black/50 backdrop-blur-xs transition-opacity duration-300 opacity-0"></div>
    <div id="mobile-search-content" class="mobile-search-content fixed inset-0 bg-background flex flex-col transition-transform duration-300 ease-in-out -translate-y-full">
        <div class="flex items-center justify-between gap-4">
            <div class="mobile-search-wrapper flex-1">
                <form id="mobile-marketplace-search-form" class="mobile-site-sub-search site-sub-search" role="search">
                    <input type="hidden" name="tabs" value="artworks">
                    <span class="search-icon-left" aria-hidden="true">
                        <img
                            src="{{ asset('assets/svg/icons/searchIcon.svg') }}"
                            alt="Search"
                            width="20"
                            height="20"
                        />
                    </span>
                    <input
                        id="mobile-marketplace-search-input"
                        type="input"
                        class="sub-search-input"
                        placeholder="Search artworks, artists, galleries..."
                        aria-label="Search artworks, artists, galleries"
                    />
                    <button id="mobile-search-clear" class="sub-search-clear" type="button" aria-label="Clear search" style="display: none;">
                        <img
                            src="{{ asset('assets/svg/icons/close---24---outline.svg') }}"
                            alt="Clear"
                            width="20"
                            height="20"
                        />
                    </button>
                </form>
                <div id="mobile-search-suggestions" class="search-suggestions mobile-search-suggestions" style="display: none;">
                    <div class="search-suggestions-header">SUGGESTIONS</div>
                    <ul id="mobile-search-suggestions-list" class="search-suggestions-list" role="listbox"></ul>
                    <a href="javascript:;" class="search-suggestions-footer" id="mobile_see_all_results">See All Results</a>
                </div>
            </div>
            <button id="mobile-search-close" class="mobile-search-close-button" type="button" aria-label="Close search">
                <img
                    src="{{ asset('assets/svg/icons/close---24---outline.svg') }}"
                    alt="Close"
                    width="20"
                    height="20"
                />
            </button>
        </div>
    </div>
</div>