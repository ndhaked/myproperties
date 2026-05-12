<x-dashboard-layout>
    <main class="main-content add-new-artist-page management-page">
       
        <header class="content-header">
            <div style="display: flex; align-items: center; gap: 16px;">
                <button class="mobile-menu-toggle" id="mobileMenuToggle">
                    <div class="hamburger">
                        <span></span>
                        <span></span>
                        <span></span>
                    </div>
                </button>
                <h1 class="page-title">Page management</h1>
            </div>
        </header>

        <div class="content-body">
            <section class="grid grid-cols-1 md:grid-cols-3 xl:grid-cols-3 gap-8">
                @foreach($cards as $card)
                <article class="rounded-2xl overflow-hidden shadow-[0_4px_18px_rgba(0,0,0,0.08)] bg-white group transition-all duration-300 hover:shadow-[0_8px_24px_rgba(0,0,0,0.12)] hover:-translate-y-1">

                    <!-- Neon Header -->
                    <header class="bg-[#fff] flex justify-between items-center px-6 py-5 border-b border-black/10">
                        <h3 class="mt-2 mb-2 text-xl font-semibold text-gray-900 group-hover:tracking-wide transition-all duration-300">
                            {{ $card['title'] }}
                        </h3>
                        <img src="{{ asset('assets/images/icon-gallery-black.svg') }}"
                            class="w-7 h-7 opacity-80 group-hover:opacity-100 transition"
                            alt="">
                    </header>

                    <!-- Content -->
                    <div class="px-6 py-6">

                        <!-- Subtle description / optional -->
                        @if(!empty($card['description']))
                        <p class="text-sm text-gray-600 leading-relaxed mb-4">
                            {{ $card['description'] }}
                        </p>
                        @endif

                        <!-- CTA Button -->
                        <a href="{{ $card['link'] }}"
                            class="w-full block text-center bg-black text-white py-3 rounded-xl text-sm font-medium tracking-wide hover:bg-gray-800 transition">
                            {{ $card['button'] }}
                        </a>
                    </div>

                </article>
                @endforeach
            </section>
        </div>
        

    </main>
      @section('uniquePageScript')

    @endsection
</x-dashboard-layout>