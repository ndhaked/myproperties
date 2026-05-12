<button class="mobile-menu-toggle" id="mobileMenuToggle" aria-label="Toggle menu" aria-expanded="false">
  <div class="hamburger">
    <span></span>
    <span></span>
    <span></span>
  </div>
</button>
<div class="sidebar-backdrop" id="sidebarBackdrop"></div>
<aside class="sidebar">
  <div class="sidebar-content">
    <div class="sidebar-header">
      <img src="{{ asset('assets/images/adai-admin/adai-logo.svg') }}" alt="ADAI Logo" class="logo">
      <button class="sidebar-close-button" id="sidebarCloseButton" aria-label="Close menu">
        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path d="M12 4L4 12M4 4L12 12" stroke="#1C1B1B" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
      </button>
    </div>
    <nav class="sidebar-nav">
      <h2 class="nav-section-title">Admin Center</h2>
      <ul class="nav-list">
        <li>
          <a href="{{route('dashboard')}}" class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <img src="{{ asset('assets/svg/icons/dashboard/dashboard.svg') }}" alt="">
            <span>Dashboard</span>
          </a>
        </li>
        <li>
          <a href="{{route('projects.index')}}" class="nav-item {{ request()->routeIs('projects.*') ? 'active' : '' }}">
            <img src="{{ asset('assets/svg/icons/dashboard/artist.svg') }}" alt="Projects">
            <span>Projects</span>
          </a>
        </li>
        <li>
          <a href="{{route('leads.index')}}" class="nav-item {{ request()->routeIs('leads.*') ? 'active' : '' }}">
            <img src="{{ asset('assets/svg/icons/dashboard/artwork.svg') }}" alt="">
            <span>Leads</span>
          </a>
        </li>
        <li>
          <a href="{{route('deals.index')}}" class="nav-item {{ request()->routeIs('deals.*') ? 'active' : '' }}">
            <img src="{{ asset('assets/svg/icons/dashboard/artist.svg') }}" alt="Deals">
            <span>Deals</span>
          </a>
        </li>   

        <li>
          <a href="{{route('users.index')}}" class="nav-item {{ request()->routeIs('users.*') ? 'active' : '' }}">
            <img src="{{ asset('assets/svg/icons/dashboard/artist.svg') }}" alt="Users">
            <span>Users</span>
          </a>
        </li>
       <!--  <li>
          <a href="javascript:;" class="nav-item">
            <img src="{{ asset('assets/svg/icons/dashboard/orders.svg') }}" alt="Orders">
            <span>Orders</span>
          </a>
        </li> -->
      </ul>
    </nav>

    <div class="sidebar-footer">
      <div class="divider"></div>
      <ul class="nav-list">
        @role('adai_admin')
        <li>
          <a href="javascript:;" class="nav-item {{ request()->routeIs('settings.*') ? 'active' : '' }}">
            <img src="{{ asset('assets/svg/icons/dashboard/settings.svg') }}" alt="">
            <span>Settings</span>
          </a>
        </li>
        @endrole
      </ul>
      <div id="sidebar" data-route="{{ request()->routeIs('profile.account') ? 'true' : 'false' }}">
</div>

      <div class="nav-dropdown-wrapper">
        <button class="nav-item nav-dropdown" data-dropdown="clients" aria-expanded="false">
          <div class="user-info">
            <img src="{{ asset('assets/images/profile-image.png') }}"
              alt="{{ucwords(auth()->user()->name)}}"
              class="user-avatar"
              onerror="this.onerror=null;this.src='https://ui-avatars.com/api/?name={{ucwords(auth()->user()->name)}}&background=ccc&color=000';">

            <div class="user-details">
              <span class="user-name">{{ucwords(auth()->user()->name)}}</span>
              <span class="user-role">{{ str_replace('Adai', 'ADAI', Str::headline(auth()->user()->getRoleNames()->first())) }}</span>
            </div>
          </div>
          <img src="{{ asset('assets/images/icon-chevron-down.svg') }}" alt="" class="chevron">
        </button>

        <ul class="nav-submenu">
          <li>
            <a href="{{ route('profile.account') }}"
              class="nav-item {{ request()->routeIs('profile.account') ? 'active' : '' }}">
              <span>Edit Account Details</span>
            </a>
          </li>
          <li>
            <a href="javascript:;"
              class="nav-item"
              onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
              <span>Logout</span>
            </a>
            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
              @csrf
            </form>
          </li>
        </ul>
      </div>
    </div>
  </div>
</aside>