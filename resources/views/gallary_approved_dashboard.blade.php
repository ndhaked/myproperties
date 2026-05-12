<x-dashboard-layout>
    <main class="main-content artist-dashboard-page">
    <header class="page-header">
      <h1>Dashboard</h1>
    </header>
    <div class="content-body">
    <section class="welcome-section">
      <div class="welcome-header">
        <h2>Gallery Overview {{ @$status == 'approved' ? '' : '(Under Review)' }}</h2>
      </div>
      <p class="welcome-text">
       Manage your gallery's artists and artworks on ADAI<br><br>
         Last Update: {{ Auth::user()->last_login_at 
            ? Auth::user()->last_login_at->format('M d, Y') 
            : 'N/A' 
        }}
      </p>
    </section>

    <section class="dashboard-cards">
        <article class="card card-artists">
          <header class="card-header">
            <h3>Artists</h3>
            <img src="{{ asset('assets/svg/icons/artist-white.svg') }}" alt="Artist Icon">
          </header>
          <div class="card-content">
            <div class="card-stats">
              <span class="stat-number">{{ number_format($totalArtists) }}</span>
              <span class="list-status-published status_for_review">
                <strong>{{ number_format($reviewArtists) }}</strong> Under Review
              </span>
            </div>
            <a href="{{ route('artist.index', ['status' => 'in-review']) }}" class="btn btn-dark-outline">
              View Artists
            </a>
          </div>
        </article>
        <article class="card card-artworks">
          <header class="card-header">
            <h3>Artworks</h3>
            <img src="{{ asset('assets/svg/icons/artwork-white.svg') }}" alt="Artwork Icon">
          </header>
          <div class="card-content">
            <div class="card-stats">
              <span class="stat-number">{{ number_format($totalArtworks) }}</span>
              <span class="list-status-published status_for_review">
                <strong>{{ number_format($reviewArtworks) }}</strong> Under Review
              </span>
            </div>
            <a href="{{ route('artwork.index', ['status' => 'in-review']) }}" class="btn btn-dark-outline">
              View Artworks
            </a>
          </div>
        </article>
      </section>
    </div>
  </main>
</x-dashboard-layout>

