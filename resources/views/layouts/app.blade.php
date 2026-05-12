<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @if(env('APP_ENV') === 'production')
        <!-- Google Tag Manager -->
        @endif
        <!-- End Google Tag Manager -->
        <!-- Summernote CSS -->
        <script type="text/javascript">
          var RECAPTCHA_SITE_KEY = "{{ config('services.recaptcha.site_key') }}";
          var App_ENV = "{{config('app.env')}}";
          var _authUserId_ = "{{optional(auth()->user())->id}}";
       </script>
        <link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.20/summernote-lite.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
        <meta charset="utf-8">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="viewport" content="width=device-width, initial-scale=1 maximum-scale=1">
        <title>@yield('title', config('app.name', 'ADAI'))</title>
        <link rel="icon" type="image/svg+xml" href="{{ asset('public/favicon.svg') }}">
        <link rel="icon" type="image/x-icon" href="{{ asset('public/favicon.ico') }}">
        <!-- Tailwind CSS CDN -->
        <!-- <script src="https://cdn.tailwindcss.com"></script> -->
        <!-- GSAP libraries loaded before Vite bundle to avoid module conflicts -->
        <script src="https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/gsap.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/ScrollTrigger.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/ScrollToPlugin.min.js"></script>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <!-- Slick Slider CSS -->
        <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.css"/>
        <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick-theme.css"/>
        @stack('head')
    </head>
    <body class="antialiased font-sans">
        @if(env('APP_ENV') === 'production')
        <!-- Google Tag Manager (noscript) -->
        <!-- End Google Tag Manager (noscript) -->
        @endif
        @include('partials.header')
        @yield('content')
        @yield('uniquePageScript')
        <!-- Ensure jQuery is available globally before Summernote and Lobibox -->
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
        <!-- Slick Slider JS -->
        <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.20/summernote-lite.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/lobibox@1.2.7/dist/js/lobibox.min.js"></script>
        @yield('script')
        @include('partials.footer')
    </body>
</html>
