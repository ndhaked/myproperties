@extends('layouts.login_guest')
@section('content')
<section class="auth-forgot-password-card login-card">
  <div class="responsive-card">
    @if (session('status'))
        <div class="text-green-600">
            {{ session('status') }}
        </div>
    @endif
    <form class="flex flex-col p-14 gap-8" method="POST" action="{{ route('password.email') }}" id="F_resetForm">
      @csrf
      <div class="flex flex-col items-start gap-1">
        <div
          class="login-title">
          Reset your password
        </div>
        <p
          class="login-subtitle">
          To reset your password, please enter your email address you may have used with CRM. We will send you a
          verification code.
        </p>
      </div>
      <div class="form-input-wrapper ermsg">
        <input
          type="email"
          class="form-input-field"
          placeholder="Email address"
          name="email"
          title="Please enter  email address"
          required />
          @error('email')
              <div class="error" style="color:red;">
                  {{ $message }}
              </div>
          @enderror
      </div>
       @if(config('app.env') !== 'local')
          <input type="hidden" name="recaptcha_token" id="recaptcha_token">
       @endif
      <button
        id="resetForm"
        type="submit"
        class="flex items-center justify-center gap-1 px-6 py-3 bg-[#1c1b1b] rounded-[999px] backdrop-blur-sm backdrop-brightness-[100%] [-webkit-backdrop-filter:blur(4px)_brightness(100%)] border-none cursor-pointer directSubmit">
        <div
          class="font-typography-paragraph-base-medium font-[number:var(--typography-paragraph-base-medium-font-weight)] text-white text-[length:var(--typography-paragraph-base-medium-font-size)] tracking-[var(--typography-paragraph-base-medium-letter-spacing)] leading-[var(--typography-paragraph-base-medium-line-height)] whitespace-nowrap [font-style:var(--typography-paragraph-base-medium-font-style)]">
          Continue
        </div>
      </button>
    </form>
  </div>
</section>
@endsection
@section('uniquePageScript')
  @if(config('app.env') !== 'local')
  <script src="https://www.google.com/recaptcha/api.js?render={{ config('services.recaptcha.site_key') }}"></script>
  @endif
@endsection