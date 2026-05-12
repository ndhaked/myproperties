<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    @if(env('APP_ENV') === 'production')
    @endif
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name', 'CRM'))</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset(public/'public/favicon.svg') }}">
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.20/summernote-lite.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @php 
        $REQUEST_URL = config('app.env') === 'production'
            ? secure_url(request()->path())
            : Request::url(); 
    @endphp

    <!-- Quill CSS -->
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    <!-- GSAP -->
    <script src="https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/gsap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/ScrollTrigger.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/ScrollToPlugin.min.js"></script>
    <script>
        var _publicPath = "{{ url('/') }}";
        var REQUEST_URL = "{{ $REQUEST_URL }}";
        var _authUserId_ = "{{optional(auth()->user())->id}}";
    </script>
</head>
<body>
    @if(env('APP_ENV') === 'production')
    <!-- Google Tag Manager (noscript) -->
    <!-- End Google Tag Manager (noscript) -->
    @endif
    <div class="admin-container admin-gallery-list-container">
        @include('partials.dashboard_sidebar')
        @include('dash_loader')

        {{-- ONLY ONE SLOT --}}
        {{ $slot }}
        {{-- ONLY ONE CONTENT YIELD --}}
        @yield('content')
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.20/summernote-lite.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/lobibox@1.2.7/dist/js/lobibox.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

    @yield('uniquePageScript')
    @yield('script')
    <script>
    document.addEventListener('DOMContentLoaded', () => {
        window.openAccountMenuOnLoad = @json(request()->routeIs('profile.account'));

        if (!window.openAccountMenuOnLoad) return;

        setTimeout(() => {

            const dropdownButton = document.querySelector(
                '.profile-section'
            );
            console.log(dropdownButton);
            if (!dropdownButton) return;

            dropdownButton.click();
        }, 1000);
    });
    </script>

</body>
</html>
