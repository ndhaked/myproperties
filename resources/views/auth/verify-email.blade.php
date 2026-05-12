@extends('layouts.login_guest')
@section('content') 
 <section class="auth-verify-email-card login-card">
     <div class="responsive-card">
          <form class="flex flex-col p-14 gap-8">
            <div class="flex flex-col items-start gap-1">
              <div
                class="login-title"
              >
                Verify your email address
              </div>
              <p
                class="login-subtitle"
              >
                <span class="font-light leading-6">To confirm your identity we've sent you a verification code on </span>
                <span class="[font-family:'BR_Hendrix-SemiBold',Helvetica] font-semibold leading-6">J•••••K@G•••L.COM</span>
                <span class="font-light leading-6">. Please enter the code below.</span>
              </p>
            </div>
            <div class="verification-code-inputs">
              <input type="text" maxlength="1" class="verification-code-input" required />
              <input type="text" maxlength="1" class="verification-code-input" required />
              <input type="text" maxlength="1" class="verification-code-input" required />
              <input type="text" maxlength="1" class="verification-code-input" required />
              <input type="text" maxlength="1" class="verification-code-input" required />
              <input type="text" maxlength="1" class="verification-code-input" required />
            </div>
            <button
              type="submit"
              class="flex items-center justify-center gap-1 px-6 py-3 bg-[#1c1b1b] rounded-[999px] backdrop-blur-sm backdrop-brightness-[100%] [-webkit-backdrop-filter:blur(4px)_brightness(100%)] border-none cursor-pointer"
            >
              <div
                class="font-typography-paragraph-base-medium font-[number:var(--typography-paragraph-base-medium-font-weight)] text-white text-[length:var(--typography-paragraph-base-medium-font-size)] tracking-[var(--typography-paragraph-base-medium-letter-spacing)] leading-[var(--typography-paragraph-base-medium-line-height)] whitespace-nowrap [font-style:var(--typography-paragraph-base-medium-font-style)]"
              >
                Reset Password
              </div>
            </button>
            <div class="flex items-center justify-center">
              <button
                type="button"
                class="[font-family:'BR_Hendrix-Medium',Helvetica] font-normal text-transparent text-base leading-4 tracking-[0] border-none bg-transparent cursor-pointer p-0"
              >
                <span
                  class="font-[number:var(--typography-paragraph-base-medium-font-weight)] text-[#1c1b1b] leading-[var(--typography-paragraph-base-medium-line-height)] underline font-typography-paragraph-base-medium [font-style:var(--typography-paragraph-base-medium-font-style)] tracking-[var(--typography-paragraph-base-medium-letter-spacing)] text-[length:var(--typography-paragraph-base-medium-font-size)]"
                  >Resend code</span
                >
              </button>
            </div>
          </form>
        </div>
</section>
@endsection
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const inputs = document.querySelectorAll('.verification-code-input');
        
        inputs.forEach((input, index) => {
          input.addEventListener('input', function(e) {
            if (e.target.value.length === 1 && index < inputs.length - 1) {
              inputs[index + 1].focus();
            }
          });
          
          input.addEventListener('keydown', function(e) {
            if (e.key === 'Backspace' && !e.target.value && index > 0) {
              inputs[index - 1].focus();
            }
          });
        });
      });
</script>

    <!-- <x-guest-layout>
    <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
        {{ __('Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.') }}
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-4 font-medium text-sm text-green-600 dark:text-green-400">
            {{ __('A new verification link has been sent to the email address you provided during registration.') }}
        </div>
    @endif

    <div class="mt-4 flex items-center justify-between">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf

            <div>
                <x-primary-button>
                    {{ __('Resend Verification Email') }}
                </x-primary-button>
            </div>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf

            <button type="submit" class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
                {{ __('Log Out') }}
            </button>
        </form>
    </div>
</x-guest-layout> -->
