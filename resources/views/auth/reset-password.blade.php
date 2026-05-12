@extends('layouts.login_guest')
@section('content') 
 <section class="auth-reset-password-card login-card">

        <form class="flex flex-col p-14 gap-8" id="F_createNewPassword" method="POST" action="{{ route('password.store') }}">
           @csrf
           <input type="hidden" name="token" id="token" value="{{  $request->route('token') }}">
            <input type="hidden" name="email" id="email" value="{{  $request->query('email') }}">

            <div class="flex flex-col items-start gap-1">
              <div
                class="login-title"
              >
                Create new password
              </div>
              <p
                class="login-subtitle"
              >
                Please enter your new password.
              </p>
            </div>
            <div class="form-input-wrapper ermsg">
              <div class="password-field-wrapper">
                <input
                  name="password"
                  type="password"
                  id="password"
                  class="form-input-field"
                  placeholder="New password"
                  title="Please eneter new password"
                  required
                />
                <button type="button" class="password-toggle-btn"
                    data-show-icon="{{ asset('assets/images/password-show.svg') }}"
                    data-hide-icon="{{ asset('assets/images/password-hide.svg') }}">
                  <img src="{{ URL::to('assets/images/password-hide.svg') }}" alt="" width="24" height="24" />
                </button>
              </div>
            </div>
            <div class="form-input-wrapper ermsg">
              <div class="password-field-wrapper ">
                <input
                  name="password_confirmation"
                  type="password"
                  class="form-input-field"
                  placeholder="Repeat password"
                  id="password_confirmation"
                  title="Please enter your password again"
                  required
               

                />
                <button type="button" class="password-toggle-btn"
                    data-show-icon="{{ asset('assets/images/password-show.svg') }}"
                    data-hide-icon="{{ asset('assets/images/password-hide.svg') }}">
                  <img src="{{ URL::to('assets/images/password-hide.svg') }}" alt="" width="24" height="24" />
                </button>
              </div>
            </div>
           
            <button
              id="createNewPassword"
              type="submit"
              class="directSubmit flex items-center justify-center gap-1 px-6 py-3 bg-[#1c1b1b] rounded-[999px] backdrop-blur-sm backdrop-brightness-[100%] [-webkit-backdrop-filter:blur(4px)_brightness(100%)] border-none cursor-pointer"
            >
              <div
                class="font-typography-paragraph-base-medium font-[number:var(--typography-paragraph-base-medium-font-weight)] text-white text-[length:var(--typography-paragraph-base-medium-font-size)] tracking-[var(--typography-paragraph-base-medium-letter-spacing)] leading-[var(--typography-paragraph-base-medium-line-height)] whitespace-nowrap [font-style:var(--typography-paragraph-base-medium-font-style)]"
              >
                Submit
              </div>
            </button>
          </form>
      </section>
      <!-- @include('modal.expire-token'); -->
@endsection
@section('uniquePageScript')

@endsection