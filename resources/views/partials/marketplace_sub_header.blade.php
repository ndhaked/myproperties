<header id="marketplace-sub-header" class="site-sub-header hidden lg:block">
    <div class="site-sub-header-inner">
      <nav class="site-sub-nav" aria-label="Main navigation">
        <ul class="sub-nav-list">
         <li class="sub-nav-item">
              <a href="{{ route('front.artist.artists-list') }}"
                class="{{ request()->routeIs('front.artist.artists-list') ? 'active' : '' }}">
                  Artists
              </a>
          </li>

          <li class="sub-nav-item">
              <a href="{{ route('front.artwork.artworks-list') }}"
                class="{{ request()->routeIs('front.artwork.artworks-list') ? 'active' : '' }}">
                  Artworks
              </a>
          </li>

          <li class="sub-nav-item">
              <a href="{{ route('front.gallery.index') }}"
                class="{{ request()->routeIs('front.gallery.index') ? 'active' : '' }}">
                  Galleries
              </a>
          </li>
          <li class="sub-nav-item d-none"><a href="#">Articles</a></li>
          <li class="sub-nav-item d-none"><a href="#">Events</a></li>
          <li class="sub-nav-item d-none"><a href="#">Price Database</a></li>
          <li class="sub-nav-item sub-nav-item--highlight d-none">
            <a href="#">
              <span class="sub-nav-icon" aria-hidden="true">
                <img src="{{ asset('assets/svg/icons/aiCuratorIcon.svg') }}" alt="AI Curator" width="20" height="20">
              </span>
              <span>AI Curator</span>
            </a>
          </li>
        </ul>
      </nav>

      <div class="search-wrapper" id="global_auto_search">
        <form id="marketplace-search-form" class="site-sub-search" role="search">
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
            id="marketplace-search-input"
            type="input"
            class="sub-search-input"
            placeholder="Search artworks, artists, galleries..."
            aria-label="Search artworks, artists, galleries"
            autocomplete="off"
          />
          <button class="sub-search-clear" type="button" aria-label="Clear search" style="display: none;">
            <img
              src="{{ asset('assets/svg/icons/close---24---outline.svg') }}"
              alt="Clear"
              width="20"
              height="20"
            />
          </button>
          <!--
          <button class="sub-search-icon-right" type="submit" aria-label="Open AI curator search">
             <img
              src="{{ asset('assets/svg/icons/searchImgIcon.svg') }}"
              alt="Search options"
              class="sub-search-icon-right-img"
            />
          </button>
           -->
        </form>
        <div id="search-suggestions" class="search-suggestions" style="display: none;">
          <div class="search-suggestions-header">SUGGESTIONS</div>
          <ul id="search-suggestions-list" class="search-suggestions-list" role="listbox"></ul>
          <a href="javascript:;" class="search-suggestions-footer" id="see_all_results">See All Results</a>
        </div>
      </div>
    </div>
  </header>
