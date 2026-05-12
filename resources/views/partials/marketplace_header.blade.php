<!-- Header -->
    <header class="sticky top-0 z-50 w-full bg-foreground backdrop-blur md:px-[64px] px-[24px] header-wrapper" id="header">
        <div class="mx-auto w-full max-w-full">
            <div class="">
                <div class="flex h-16 w-full items-center justify-between custom-header">
                    <!-- Logo -->
                    <div class="flex items-center flex-shrink-0 w-32">
                        <a href="/" class="flex items-center space-x-2">
                            <span class="site-logo">
                                <svg xmlns="http://www.w3.org/2000/svg" width="128" height="36" viewBox="0 0 128 36" fill="none">
                                    <g clip-path="url(#clip0_2136_1261)">
                                        <path d="M110.02 36H128V-0.00159836H110.02V36Z" fill="white"/>
                                        <path d="M99.3218 17.9991L89.8998 -0.00159836L80.91 17.727L71.9199 36H89.8998H107.88L99.3218 17.9991Z" fill="white"/>
                                        <path d="M27.4018 17.9991L17.98 -0.00159836L8.99002 17.727L0 36H17.98H35.96L27.4018 17.9991Z" fill="white"/>
                                        <path d="M56.0803 -0.00159836H38.1005V36H56.0803C66.0102 36 74.0602 27.9407 74.0602 17.9991C74.0602 8.0576 66.0102 -0.00159836 56.0803 -0.00159836Z" fill="white"/>
                                    </g>
                                    <defs>
                                        <clipPath id="clip0_2136_1261">
                                            <rect width="128" height="36" fill="white"/>
                                        </clipPath>
                                    </defs>
                                </svg>
                            </span>
                        </a>
                    </div>

                    <!-- Desktop Navigation -->
                    <nav class="hidden lg:flex lg:items-center lg:gap-x-8 lg:flex-1 lg:justify-center">
                        <a href="{{ url('about') }}" class="text-sm uppercase font-normal transition-colors px-3 py-2 rounded-md text-white hover:text-primary-hover  {{ request()->routeIs('front.about') ? 'active' : '' }}">About</a>
                        <a href="{{ url('marketplace') }}" class="text-sm uppercase font-normal transition-colors px-3 py-2 rounded-md text-white hover:text-primary-hover {{ request()->routeIs(
                                    'front.marketplace.*',
                                    'front.artist.*',
                                    'front.artwork.*',
                                    'front.gallery.*'
                                ) ? 'active' : '' }}">Marketplace & Database</a>
                        <a href="{{ url('art-consultancy') }}" class="text-sm uppercase font-normal transition-colors px-3 py-2 rounded-md text-white hover:text-primary-hover {{ request()->routeIs('front.art-consultancy') ? 'active' : '' }}">Art Consultancy</a>
                        <a href="{{ url('gallery-hub') }}" class="text-sm uppercase font-normal transition-colors px-3 py-2 rounded-md text-white hover:text-primary-hover {{ request()->routeIs('front.gallery-hub') ? 'active' : '' }}">GALLERY HUB</a>
                    </nav>
                    <div class="items-center justify-end space-x-3 sm:space-x-4 flex-shrink-0 lg:flex hidden">
                        <a href="{{ url('login') }}" class="btn btn-dark-outline-white">
                            Login
                        </a>
                    </div>
                    <!-- Mobile Menu Button -->
                    <div class="flex items-center justify-end space-x-3 sm:space-x-4 flex-shrink-0 mobile-menue-buttons-wrapper">
                        <button id="mobile-search-toggle" class="mobile-search-toggle lg:hidden text-white hover:bg-foreground p-2 rounded" aria-label="Open search">
                            <img src="{{ asset('assets/svg/searchWhite.svg') }}" alt="Search" class="mobile-search-toggle-icon" />
                        </button>
                        <button id="mobile-menu-toggle" class="mobile-menu-toggle lg:hidden text-white hover:bg-foreground p-2 rounded">
                            <svg id="menu-icon" class="menu-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                            </svg>
                            <svg id="close-icon" class="close-icon hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Mobile Navigation -->
    <!-- Mobile (< sm) - Full Screen Overlay -->
    <div id="mobile-menu" class="sm:hidden fixed inset-0 z-40 transition-all duration-300 ease-in-out opacity-0 invisible pointer-events-none">
        <div id="mobile-backdrop" class="fixed inset-0 bg-black/50 backdrop-blur-xs transition-opacity duration-300 opacity-0"></div>
        <div id="mobile-menu-content" class="fixed inset-0 bg-background flex flex-col transition-transform duration-300 ease-in-out -translate-y-full">
            <!-- Mobile Menu Header -->
            <div class="flex items-center justify-between px-4 pt-4 pb-6">
                <!-- Logo -->
                <div class="flex items-center flex-shrink-0">
                    <a href="/" class="flex items-center">
                        <span class="text-2xl font-bold text-foreground">ADAI</span>
                    </a>
                </div>
                <!-- Close Button -->
                <button id="mobile-menu-close" class="text-foreground hover:text-muted-foreground p-2">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            <!-- Navigation Links -->
            <div class="flex-1 px-4 mobile-navigation">
                <nav class="flex flex-col space-y-0">
                    <a href="{{ url('about') }}" class="mobile-navigation-link block py-3 text-base font-bold text-foreground transition-colors hover:text-muted-foreground {{ request()->routeIs('front.about') ? 'active' : '' }}">ABOUT</a>
                    
                    <!-- Marketplace & Database Dropdown -->
                    <div class="marketplace-dropdown-wrapper open">
                        <div class="mobile-navigation-link marketplace-dropdown-toggle w-full flex items-center justify-between py-3 text-base font-bold text-foreground transition-colors hover:text-muted-foreground text-left" aria-expanded="false">
                            <a href="{{ url('marketplace') }}" class="flex-1 {{ request()->is('marketplace*') ? 'active' : '' }}">MARKETPLACE & DATABASE</a>
                            <button type="button" class="marketplace-dropdown-chevron-btn flex items-center justify-center p-1 ml-2" aria-label="Toggle dropdown">
                                <img src="{{ asset('assets/images/icon-chevron-down.svg') }}" alt="" class="marketplace-chevron transition-transform duration-200">
                            </button>
                        </div>
                        <ul class="marketplace-submenu flex flex-col space-y-0 pl-4">
                            <li><a href="{{ route('front.artist.artists-list') }}" class="block py-3 text-base font-bold text-foreground transition-colors hover:text-muted-foreground {{ request()->routeIs('front.artist.artists-list') ? 'active' : '' }}">Artists</a></li>
                            <li><a href="{{ route('front.artwork.artworks-list') }}" class="block py-3 text-base font-bold text-foreground transition-colors hover:text-muted-foreground {{ request()->routeIs('front.artwork.artworks-list') ? 'active' : '' }}">Artworks</a></li>
                            <li><a href="{{route('front.gallery.index')}}" class="block py-3 text-base font-bold text-foreground transition-colors hover:text-muted-foreground {{ request()->routeIs('front.gallery.index') ? 'active' : '' }}">Galleries</a></li>
                            <li class="d-none"><a href="#" class="block py-3 text-base font-bold text-foreground transition-colors hover:text-muted-foreground">Articles</a></li>
                            <li class="d-none"><a href="#" class="block py-3 text-base font-bold text-foreground transition-colors hover:text-muted-foreground">Events</a></li>
                            <li class="d-none"><a href="#" class="block py-3 text-base font-bold text-foreground transition-colors hover:text-muted-foreground">Price Database</a></li>
                            <li class="d-none">
                                <a href="#" class="block py-3 text-base font-bold text-foreground transition-colors hover:text-muted-foreground flex items-center gap-2">
                                    <span class="sub-nav-icon" aria-hidden="true">
                                        <img src="{{ asset('assets/svg/icons/aiCuratorIcon.svg') }}" alt="AI Curator" width="20" height="20">
                                    </span>
                                    <span>AI Curator</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                    
                    <a href="{{ url('art-consultancy') }}" class="mobile-navigation-link block py-3 text-base font-bold text-foreground transition-colors hover:text-muted-foreground {{ request()->routeIs('front.art-consultancy') ? 'active' : '' }}">ART CONSULTANCY</a>
                    <a href="{{ route('front.gallery-hub') }}" class="mobile-navigation-link block py-3 text-base font-bold text-foreground transition-colors hover:text-muted-foreground {{ request()->routeIs('front.gallery-hub') ? 'active' : '' }}">ADAI for Galleries</a>
                    <a href="{{ url('contact') }}" class="mobile-navigation-link block py-3 text-base font-bold text-foreground transition-colors hover:text-muted-foreground">Contact us</a>
                    <a href="{{ url('login') }}" class="mobile-navigation-btn btn btn-dark-outline w-full text-center" style="background: transparent; color: #1c1b1b; border: 1px solid #1c1b1b;">
                    Login
                </a></nav>
            </div>
        </div>
    </div>

    <!-- Small Screens (sm+) - Sidebar -->
    <div id="sidebar-menu-container" class="hidden sm:block 2xl:hidden fixed inset-0 z-40 transition-all duration-300 ease-in-out opacity-0 invisible pointer-events-none">
        <div id="sidebar-backdrop" class="fixed inset-0 bg-black/50 backdrop-blur-sm transition-opacity duration-300 opacity-0"></div>
        <div id="sidebar-menu" class="fixed top-16 right-0 h-full w-80 max-w-[85vw] bg-background/95 backdrop-blur-md border-l border-border/50 shadow-2xl flex flex-col transition-transform duration-300 ease-out translate-x-full">
            <div class="flex-1 px-6 py-8 mobile-navigation">
                <nav class="flex flex-col space-y-0">
                    <a href="{{ url('about') }}" class="mobile-navigation-link block py-3 text-base font-bold text-foreground transition-colors hover:text-muted-foreground {{ request()->routeIs('front.about') ? 'active' : '' }}">ABOUT</a>
                    
                    <!-- Marketplace & Database Dropdown -->
                    <div class="marketplace-dropdown-wrapper">
                        <div class="mobile-navigation-link marketplace-dropdown-toggle w-full flex items-center justify-between py-3 text-base font-bold text-foreground transition-colors hover:text-muted-foreground text-left" aria-expanded="false">
                            <a href="{{ url('marketplace') }}" class="flex-1 {{ request()->routeIs(
                                    'front.marketplace.*',
                                    'front.artist.*',
                                    'front.artwork.*',
                                    'front.gallery.*'
                                ) ? 'active' : '' }}">MARKETPLACE & DATABASE</a>
                            <button type="button" class="marketplace-dropdown-chevron-btn flex items-center justify-center p-1 ml-2" aria-label="Toggle dropdown">
                                <img src="{{ asset('assets/images/icon-chevron-down.svg') }}" alt="" class="marketplace-chevron transition-transform duration-200">
                            </button>
                        </div>
                        <ul class="marketplace-submenu flex flex-col space-y-0 pl-4">
                            <li><a href="{{ route('front.artist.artists-list') }}" class="block py-3 text-base font-bold text-foreground transition-colors hover:text-muted-foreground">Artists</a></li>
                            <li><a href="{{ route('front.artwork.artworks-list') }}" class="block py-3 text-base font-bold text-foreground transition-colors hover:text-muted-foreground">Artworks</a></li>
                            <li><a href="{{route('front.gallery.index')}}" class="block py-3 text-base font-bold text-foreground transition-colors hover:text-muted-foreground">Galleries</a></li>
                            <li class="d-none"><a href="#" class="block py-3 text-base font-bold text-foreground transition-colors hover:text-muted-foreground">Articles</a></li>
                            <li class="d-none"><a href="#" class="block py-3 text-base font-bold text-foreground transition-colors hover:text-muted-foreground">Events</a></li>
                            <li class="d-none"><a href="#" class="block py-3 text-base font-bold text-foreground transition-colors hover:text-muted-foreground">Price Database</a></li>
                            <li class="d-none">
                                <a href="#" class="block py-3 text-base font-bold text-foreground transition-colors hover:text-muted-foreground flex items-center gap-2">
                                    <span class="sub-nav-icon" aria-hidden="true">
                                        <img src="{{ asset('assets/svg/icons/aiCuratorIcon.svg') }}" alt="AI Curator" width="20" height="20">
                                    </span>
                                    <span>AI Curator</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                    
                    <a href="{{ url('art-consultancy') }}" class="mobile-navigation-link block py-3 text-base font-bold text-foreground transition-colors hover:text-muted-foreground {{ request()->routeIs('front.art-consultancy') ? 'active' : '' }}">ART CONSULTANCY</a>
                    <a href="{{ route('front.gallery-hub') }}" class="mobile-navigation-link block py-3 text-base font-bold text-foreground transition-colors hover:text-muted-foreground {{ request()->routeIs('front.gallery-hub') ? 'active' : '' }}">ADAI for Galleries</a>
                    <a href="{{ url('contact') }}" class="mobile-navigation-link block py-3 text-base font-bold text-foreground transition-colors hover:text-muted-foreground">Contact us</a>
                <a href="{{ url('login') }}" class="mobile-navigation-btn btn btn-dark-outline w-full text-center" style="background: transparent; color: #1c1b1b; border: 1px solid #1c1b1b;">
                    Login
                </a></nav>
            </div>
        </div>
    </div>

    @include('partials._mobile_marketplace_glabal_search_top_bar')

    <!-- sub header -->
    @include('partials.marketplace_sub_header')
