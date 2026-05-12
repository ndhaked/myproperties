<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @if(env('APP_ENV') === 'production')
         <!-- Google Tag Manager -->
        <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
        new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
        j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
        'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
        })(window,document,'script','dataLayer','GTM-N6CHW59L');</script>
        <!-- End Google Tag Manager -->
        @endif
        <meta charset="utf-8">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>@yield('title', config('app.name', 'ADAI'))</title>
        <!-- Tailwind CSS CDN -->
        <!-- <script src="https://cdn.tailwindcss.com"></script> -->
        <!-- GSAP libraries loaded before Vite bundle to avoid module conflicts -->
        <script src="https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/gsap.min.js"></script>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="antialiased font-sans">
        @if(env('APP_ENV') === 'production')
        <!-- Google Tag Manager (noscript) -->
        <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-N6CHW59L"
        height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
        <!-- End Google Tag Manager (noscript) -->
        @endif
        @yield('content')
        @yield('uniquePageScript')
        <!-- Ensure jQuery is available globally before Summernote and Lobibox -->
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
        @yield('script')
    </body>
</html>
