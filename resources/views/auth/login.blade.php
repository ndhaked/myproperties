@extends('layouts.login_guest')
@section('title', "Log in to your account | " .config('app.name'))
@section('content') 
 <section class="auth-login-card login-card">
        <div class="login-card-content">
          <header class="login-header">
            <h1 class="login-title">Log in to your account</h1>
            <p class="login-subtitle">Welcome back! Please enter your login details.</p>
          </header>
          {{-- Show global error (like wrong credentials) --}}
            @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif
          <form class="login-form" action="{{ route('login') }}" method="post" id='F_doLogin'>
            @csrf
            <div class="form-group ermsg">
              <label for="email" class="form-label visually-hidden">Email address</label>
              <input
                type="email"
                id="email"
                name="email"
                class="form-input-field"
                placeholder="Email address"
                required
                autocomplete="email"
                aria-required="true"
                value="{{old('email')}}" 
                autofocus
                autocomplete="username"
                title="Please enter email address"
              />
                {{-- ERROR MESSAGE --}}
                @error('email')
                    <div class="error" style="color:red;">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div class="form-group ermsg password-wrapper">
              <label for="password" class="form-label visually-hidden">Password</label>
              <div class="password-input-wrapper">
                <input
                  type="password"
                  id="password"
                  name="password"
                  class="form-input"
                  placeholder="Password"
                  required
                  autocomplete="current-password"
                  aria-required="true"
                  title="Please enter password"
                />
                <button
                  type="button"
                  class="password-toggle-btn"
                  aria-label="Toggle password visibility"
                  aria-pressed="false"
                  data-show-icon="{{ asset('assets/images/password-show.svg') }}"
                  data-hide-icon="{{ asset('assets/images/password-hide.svg') }}"
                >
                  <img
                    class="password-toggle-icon"
                    src="{{asset('assets/images/password-hide.svg')}}"
                    alt="Show/Hide"
                    aria-hidden="true"
                  />
                </button>
              </div>
                {{-- ERROR MESSAGE --}}
                @error('password')
                    <div class="error" style="color:red;">
                        {{ $message }}
                    </div>
                @enderror
            </div>
            <div class="form-options">
              <div class="remember-me-wrapper">
                <input type="checkbox" id="remember-me" name="remember" class="checkbox-input" />
                <label for="remember-me" class="checkbox-label">Remember me</label>
              </div>
              @if (Route::has('password.request'))
              <a href="{{ route('password.request') }}" class="forgot-password-link">Forgot password?</a>
              @endif
            </div>
            <?php /*
            @if(config('app.env') !== 'local')
                <input type="hidden" name="recaptcha_token" id="recaptcha_token">
            @endif
            */ ?>
            <input type="hidden" name="ga4-subscription-type" value="login-form" id="ga4-subscription-type">
            <button class="login-button directSubmit" type="submit" id="doLogin" data-content-cta-section="Login">Login</button>
          </form>
        </div>
      </section>
@endsection
@section('uniquePageScript')
 <?php /*  @if(config('app.env') !== 'local')
  <script src="https://www.google.com/recaptcha/api.js?render={{ config('services.recaptcha.site_key') }}"></script>
  @endif */ ?>
@endsection