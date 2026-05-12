@extends('layouts.login_guest')
@section('content')
<section class="auth-verify-otp-card login-card">
    <div class="responsive-card">
        <form class="flex flex-col p-14 gap-8" id="F_verify-otp" method="POST" action="{{ route('verify.otp') }}">
            @csrf
            <input type="hidden" name="email" value="{{ $email }}">
            <input type="hidden" id="resend_url" value="{{ route('resend.otp') }}">

            <div class="flex flex-col items-start gap-1">
                <div
                    class="login-title">
                    Verify your email address
                </div>
                <p
                    class="login-subtitle">
                    <span class="font-light leading-6">To confirm your identity we've sent you a verification code on </span>
                    <span class="[font-family:'BR_Hendrix-SemiBold',Helvetica] font-semibold leading-6">{{ $maskedEmail }}</span>
                    <span class="font-light leading-6">. Please enter the code below.</span>
                </p>
            </div>
            <div class="verification-code-inputs ermsg">
                <input type="text" name="otp[]" maxlength="1" class="verification-code-input" required />
                <input type="text" name="otp[]" maxlength="1" class="verification-code-input" required />
                <input type="text" name="otp[]" maxlength="1" class="verification-code-input" required />
                <input type="text" name="otp[]" maxlength="1" class="verification-code-input" required />
                <input type="text" name="otp[]" maxlength="1" class="verification-code-input" required />
                <input type="text" name="otp[]" maxlength="1" class="verification-code-input" required />
            </div>
            <button

                id="verify-otp"

                type="submit"
                class="directSubmit flex items-center justify-center gap-1 px-6 py-3 bg-[#1c1b1b] rounded-[999px] backdrop-blur-sm backdrop-brightness-[100%] [-webkit-backdrop-filter:blur(4px)_brightness(100%)] border-none cursor-pointer">
                <div
                    class="font-typography-paragraph-base-medium font-[number:var(--typography-paragraph-base-medium-font-weight)] text-white text-[length:var(--typography-paragraph-base-medium-font-size)] tracking-[var(--typography-paragraph-base-medium-letter-spacing)] leading-[var(--typography-paragraph-base-medium-line-height)] whitespace-nowrap [font-style:var(--typography-paragraph-base-medium-font-style)]">
                    Reset Password
                </div>
            </button>
            <div class="flex items-center justify-center">
                <button
                    type="button"
                    class="resend-btn [font-family:'BR_Hendrix-Medium',Helvetica] font-normal text-transparent text-base leading-4 tracking-[0] border-none bg-transparent cursor-pointer p-0">
                    <span id="resend-text"
                        class="font-[number:var(--typography-paragraph-base-medium-font-weight)] text-[#1c1b1b] leading-[var(--typography-paragraph-base-medium-line-height)] underline font-typography-paragraph-base-medium [font-style:var(--typography-paragraph-base-medium-font-style)] tracking-[var(--typography-paragraph-base-medium-letter-spacing)] text-[length:var(--typography-paragraph-base-medium-font-size)]">Resend code</span>
                    <span id="countdown-badge" class="countdown-badge d-none">30s</span>

                </button>
            </div>
        </form>
    </div>
</section>
@endsection
