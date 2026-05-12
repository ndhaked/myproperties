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
          <h2>Launch Your Gallery on ADAI</h2>
        </div>

      </section>
      <?php
      if ($hasNull ==  true) {
        $btn = "disabled";
        $opacity = "opacity-20";
      } else {
        $opacity = "opacity-100";
      }
      if ($totalArtists == 0) {
        $btn = "disabled";
        $totalartistopacity = "opacity-20";
      } else {
        $totalartistopacity = "opacity-100";
      }
      if ($totalArtworks == 0) {
        $btn = "disabled";
        $totalartiworktopacity = "opacity-20";
      } else {
        $totalartiworktopacity = "opacity-100";
      }
      if (!@$btn) {
        $url = url('submit-for-review');
      }
      ?>
      <section class="dashboard-cards">
        <!-- Step 1: Build a Gallery Profile -->
        <article class="card card-galleries">
          <header class="card-header">
            <div class="card-header-content">
              <h3>Step 1</h3>
              <p>Build a Gallery Profile


              </p>
            </div>
            <img src="{{ asset('assets/svg/icons/tick-circle.svg') }}" alt="Completed" class="checkmark-icon {{ @$opacity  }}">
          </header>
          <div class="card-content">
            <div class="card-stats">
              <span class="text-light">Add your gallery details to set up your presence on ADAI's marketplace.</span>
            </div>
            @if($gallery_id)
            <a href="{{ route('gallery.edit', $gallery_id) }}" class="btn portal-btn-primary-small">
              Edit Profile
            </a>
            @else
            <button class="btn portal-btn-primary-small" disabled>
              Edit Profile{{ $gallery_id }}
            </button>
            @endif


          </div>
        </article>

        <!-- Step 2: Add Artists -->
        <article class="card card-artists">
          <header class="card-header">
            <div class="card-header-content">
              <h3>Step 2</h3>
              <p>Add Artists</p>
            </div>
            <img src="{{ asset('assets/svg/icons/tick-circle.svg') }}" alt="Completed" class="checkmark-icon {{ @$totalartistopacity }}">
          </header>
          <div class="card-content">
            <div class="card-stats">
              <span class="text-light">Start by adding your first represented artist to your gallery profile.</span>
            </div>
            <a
    href="{{ $hasNull ? 'javascript:void(0)' : route('artist.create') }}"
    class="btn portal-btn-primary-small {{ $hasNull ? 'opacity-50 cursor-not-allowed pointer-events-none' : '' }}"
>
    Add Artist
</a>
          </div>
        </article>

        <!-- Step 3: Upload Artworks -->
        <article class="card card-artworks">
          <header class="card-header">
            <div class="card-header-content">
              <h3 class="text-white">Step 3</h3>
              <p class="text-white">Upload Artworks</p>
            </div>
            <img src="{{ asset('assets/svg/icons/tick-circle.svg') }}" alt="Completed" class="checkmark-icon {{ $totalartiworktopacity }} ">
          </header>
          <div class="card-content">
            <div class="card-stats">
              <span class="text-light">Upload artworks with images and details.</span>
            </div>
           <a
    href="{{ route('artwork.create') }}"
    class="btn portal-btn-primary-small {{ $hasNull ? 'opacity-50 cursor-not-allowed' : '' }}"
    @if($hasNull)
        onclick="event.preventDefault();"
    @endif
>
    Upload Artworks
</a>
          </div>
        </article>
      </section>
      @php
          $showSubmitButton =
              // Case 1: gallery not yet submitted
              !$gallerySubmitted
              // Case 2: gallery submitted but artist & artwork are valid
              || ($gallerySubmitted && ($artistValid || $artworkValid));
      @endphp
      @if($showSubmitButton)
      <section class="flex items-center justify-center mt-4 bg-light-gray w-full" style="background: #FAFAFA;
    padding: 16px; border-radius: 6px;">
        <div class="card-submit-container flex flex-col items-center justify-center text-center w-full">
          <header class="card-submit-header w-full flex flex-col items-center">
            <div class="card-header-content flex flex-col items-center">
              <h2 class="text-center text-[18px] font-bold">Step 4</h2>
              <p class="text-center text-[24px] font-bold">Submit for Review</p>
            </div>
          </header>

          <div class="card-submit-content w-full text-center flex flex-col items-center">
            <div class="card-stats mb-4">
              <span class="text-light block text-[14px] font-normal">Once your profile and artworks are complete, submit for verification. Our curatorial team will review and confirm when your gallery is live.</span>
            </div>
            @if(@$url)
            <a href="{{ @$url??'#' }}"><button type="button" class="btn portal-btn-primary-small" {{ @$btn }}>
                Submit for Review
              </button>
            </a>
            @else
             <button type="button" class="btn portal-btn-primary-small" {{ @$btn }}>
                Submit for Review
              </button>
            
            @endif
          </div>
        </div>
      </section>
      @endif
    </div>
  </main>
</x-dashboard-layout>