<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @if(env('APP_ENV') === 'production')
          <!-- Google Tag Manager -->
          <!-- End Google Tag Manager -->
        @endif
        <meta charset="utf-8">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>@yield('title', config('app.name', 'Properties - Log in to your account'))</title>
        <link rel="icon" type="image/svg+xml" href="{{ asset('public/favicon.svg') }}">
        <link rel="icon" type="image/x-icon" href="{{ asset('public/favicon.ico') }}">
        <!-- <link rel="preconnect" href="https://fonts.bunny.net"> -->
        <!-- <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" /> -->
        <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css"> -->
        <!-- GSAP libraries loaded before Vite bundle to avoid module conflicts -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
        <script src="https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/gsap.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/ScrollTrigger.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/gsap@3.12.5/dist/ScrollToPlugin.min.js"></script>
        <script>
            const RECAPTCHA_SITE_KEY = "{{ config('services.recaptcha.site_key') }}";
        </script>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <script type="text/javascript">
          var _publicPath = "{{url('/')}}";
          var REQUEST_URL = "{{Request::url()}}";
          var App_ENV = "{{config('app.env')}}";
          var _authUserId_ = "{{optional(auth()->user())->id}}";
       </script>
    </head>
    <body>
      @if(env('APP_ENV') === 'production')
        <!-- add google tags here -->
      @endif
        <main class="login-page">
          <div class="background-container">
            <img
              class="background-image"
              src="{{ asset('assets/images/auth-bg-professional.jpg') }}"
              alt="Professional Ultra-HD Background"
            />
          </div>
          <header class="site-header">
            <a href="{{ url('/') }}">
              <img class="site-logo" src="{{asset('assets/images/logo.svg')}}" alt="ADAI Logo" />
            </a>
          </header>
          @yield('content')
          <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
          @yield('uniquePageScript')
            <script src="https://cdn.jsdelivr.net/npm/lobibox@1.2.7/dist/js/lobibox.min.js"></script>
          @yield('script')
    </body>
</html>
