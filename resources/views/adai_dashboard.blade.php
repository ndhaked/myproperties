<x-dashboard-layout>
    <main class="main-content artist-dashboard-page">
    <header class="content-header">
            <div style="display: flex; align-items: center; gap: 16px;">
                <button class="mobile-menu-toggle" id="mobileMenuToggle">
                    <div class="hamburger">
                        <span></span>
                        <span></span>
                        <span></span>
                    </div>
                </button>
                <h1 class="page-title">Dashboard</h1>
            </div>
        </header>
    <div class="content-body">
    <section class="welcome-section">
      <div class="welcome-header">
        <h2>Welcome to Your Dashboard</h2>
      </div>
      <p class="welcome-text">
        Last Update: {{ Auth::user()->last_login_at 
            ? Auth::user()->last_login_at->format('M d, Y') 
            : 'N/A' 
        }}
      </p>
    </section>

    <section class="dashboard-cards">
        <article class="card card-artists">
          <header class="card-header">
            <h3>Projects</h3>
            <img src="{{ asset('assets/svg/icons/artist-white.svg') }}" alt="Artist Icon">
          </header>
          <div class="card-content">
            <div class="card-stats">
              <span class="stat-number">{{ number_format($totalProjects) }}</span>
            </div>
            <a href="{{ route('projects.index', ['status' => 'all']) }}" class="btn portal-btn-primary-small">
              Review Projects
            </a>
          </div>
        </article>

        <article class="card card-galleries">
          <header class="card-header">
            <h3>Deals</h3>
            <img src="{{ asset('assets/svg/icons/gallery-white.svg') }}" alt="Gallery Icon">
          </header>
          <div class="card-content">
            <div class="card-stats">
              <span class="stat-number">{{ $totalDeals }}</span>
            </div>
            <a href="{{ route('deals.index', ['status' => 'in-review']) }}" class="btn portal-btn-primary-small">
              Review Deals
            </a>
          </div>
        </article>

        <article class="card card-artworks">
          <header class="card-header">
            <h3>Leads</h3>
            <img src="{{ asset('assets/svg/icons/artwork-white.svg') }}" alt="Artwork Icon">
          </header>
          <div class="card-content">
            <div class="card-stats">
              <span class="stat-number">{{ number_format($totalLeads) }}</span>
            </div>
            <a href="{{ route('leads.index', ['status' => 'in-review']) }}" class="btn portal-btn-primary-small">
              Review Leads
            </a>
          </div>
        </article>
      </section>
    </div>
  </main>
</x-dashboard-layout>

