{{-- 1. PREPARE PAGINATION ELEMENTS --}}
@php
    if ($paginator->hasPages()) {
        $window = \Illuminate\Pagination\UrlWindow::make($paginator);
        $elements = [
            $window['first'],
            is_array($window['slider']) ? '...' : null,
            $window['slider'],
            is_array($window['last']) ? '...' : null,
            $window['last'],
        ];
    }
@endphp

{{-- 2. PAGINATION HTML STRUCTURE --}}
<section class="artworks-pagination-section" id="front_ajax_public_pagination">
@if ($paginator->hasPages())
    <div class="artworks-pagination-container">
        <nav class="artworks-pagination">

            {{-- PREVIOUS BUTTON --}}
            @if ($paginator->onFirstPage())
                <button class="artworks-pagination-btn artworks-pagination-prev" disabled>
                    <img src="{{ asset('assets/images/marketplace/arrow-left.svg') }}" alt="Prev"> 
                    Prev
                </button>
            @else
                {{-- AUTOMATIC: previousPageUrl() will now include ?q=...&tab=... because of appends() --}}
                <button class="artworks-pagination-btn artworks-pagination-prev ajax-pagination-btn" 
                        data-href="{{ $paginator->previousPageUrl() }}">
                    <img src="{{ asset('assets/images/marketplace/arrow-left.svg') }}" alt="Prev"> 
                    Prev
                </button>
            @endif

            {{-- PAGE NUMBERS --}}
            <div class="artworks-pagination-numbers">
                @foreach ($elements as $element)
                    {{-- "..." Separator --}}
                    @if (is_string($element))
                        <span class="artworks-pagination-ellipsis">{{ $element }}</span>
                    @endif

                    {{-- Array Of Links --}}
                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <button class="artworks-pagination-number artworks-pagination-active">
                                    {{ $page }}
                                </button>
                            @else
                                {{-- AUTOMATIC: $url will now include ?q=...&tab=... --}}
                                <button class="artworks-pagination-number ajax-pagination-btn" 
                                        data-href="{{ $url }}">
                                    {{ $page }}
                                </button>
                            @endif
                        @endforeach
                    @endif
                @endforeach
            </div>

            {{-- NEXT BUTTON --}}
            @if ($paginator->hasMorePages())
                <button class="artworks-pagination-btn artworks-pagination-next ajax-pagination-btn"
                        data-href="{{ $paginator->nextPageUrl() }}">
                    Next 
                    <img src="{{ asset('assets/images/marketplace/arrow-right.svg') }}" alt="Next">
                </button>
            @else
                <button class="artworks-pagination-btn artworks-pagination-next" disabled>
                    Next 
                    <img src="{{ asset('assets/images/marketplace/arrow-right.svg') }}" alt="Next">
                </button>
            @endif

        </nav>
    </div>
@endif
</section>

@section('uniquePageScript')
<script type="text/javascript">
    // 1. UPDATED: Use request('q') to safely get the query string
    const SEARCH_QUERY = "{{ request('q') }}"; 
    
    // Optional: Log it to check
    // console.log('Current Page Search:', SEARCH_QUERY);

    // If REQUEST_URL is defined globally in your layout, this is fine.
    // If not, you might want to use: "{{ url()->current() }}"
    const BASE_URL = typeof REQUEST_URL !== 'undefined' ? REQUEST_URL : "{{ url()->current() }}";
</script>
@endsection