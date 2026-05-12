@extends('layouts.login_guest')
@section('content')
<section class="auth-password-updated-card login-card">
    <div class="responsive-card">
        <div class="flex flex-col gap-8 p-14">
            <div class="flex flex-col items-start gap-1">
                <p
                    class="login-title">
                    Your password has been updated
                </p>
                <p
                    class="login-subtitle">
                    For security purposes we've sent a notification to your account confirming this change.
                </p>
            </div>
            <a href="{{ route('login') }}"
               class="flex items-center justify-center gap-1 px-6 py-3 bg-[#1c1b1b] rounded-[999px] backdrop-blur-sm backdrop-brightness-[100%] [-webkit-backdrop-filter:blur(4px)_brightness(100%)] no-underline">
                <div
                    class="font-typography-paragraph-base-medium font-[number:var(--typography-paragraph-base-medium-font-weight)] text-white text-[length:var(--typography-paragraph-base-medium-font-size)] tracking-[var(--typography-paragraph-base-medium-letter-spacing)] leading-[var(--typography-paragraph-base-medium-line-height)] whitespace-nowrap [font-style:var(--typography-paragraph-base-medium-font-style)]">
                    Continue to Login
                </div>
            </a>
        </div>
    </div>
</section>
@endsection

