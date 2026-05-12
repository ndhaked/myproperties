<x-dashboard-layout>
    <style>
    .portal-btn-primary-small, .portal-btn-secondary-small {
        width: auto;
        flex: 1 1 0%;
        max-width: 187px;
    }
    </style>
    @section('title', 'Account Details | ' . config('app.name'))
    <!-- Main Content -->
    <main class="main-content" id="main-account-list">
        <header class="content-header">
            <div style="display: flex; align-items: center; gap: 16px;">
                <button class="mobile-menu-toggle" id="mobileMenuToggle">
                    <div class="hamburger">
                        <span></span>
                        <span></span>
                        <span></span>
                    </div>
                </button>
                <h1 class="page-title">Account Details</h1>
            </div>
        </header>

        <div class="content-body">
            <div class="flex items-start p-1 mb-12 space-x-2">
                <form id="F_saveProfileBtn" method="POST" action="{{ route('profile.update') }}"
                    enctype="multipart/form-data" class="flex flex-col items-start w-full max-w-4xl gap-12">
                    @csrf
                    @method('patch')
                    <inpu type="hidden" name="old_password" id="old_password" value="1" />

                    <section
                        class="flex flex-col ml-6 mt-6 items-start gap-8 relative self-stretch w-full flex-[0_0_auto]"
                        aria-labelledby="profile-details-heading">
                        <header
                            class="account-header flex flex-col items-start justify-center gap-1.5 px-3 py-1 relative self-stretch w-full flex-[0_0_auto] bg-[#e2f705] rounded-md"
                            style="background-color: #E84067;">
                            <div
                                class="flex items-center justify-end gap-2 relative self-stretch w-full flex-[0_0_auto]">
                                <div class="relative flex flex-col items-start flex-1 grow">
                                    <div class="flex items-center gap-4 relative self-stretch w-full flex-[0_0_auto]">
                                        <div class="relative flex items-center self-stretch flex-1 gap-2 grow">
                                            <h1 id="profile-details-heading"
                                                class="relative w-fit mt-[-1.00px] [font-family:'BR_Hendrix-Bold',Helvetica] font-bold text-[#FFFFFF] text-base tracking-[0] leading-6 whitespace-nowrap">
                                                PROFILE DETAILS
                                            </h1>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </header>

                        <!-- Name Field -->
                        <div class="account-details-form flex items-start gap-8 relative self-stretch w-full flex-[0_0_auto]">
                            <label for="name"
                                class="relative w-[250px] mt-[-1.00px] font-typography-paragraph-base-medium font-[number:var(--typography-paragraph-base-medium-font-weight)] text-gray-700 text-[length:var(--typography-paragraph-base-medium-font-size)] tracking-[var(--typography-paragraph-base-medium-letter-spacing)] leading-[var(--typography-paragraph-base-medium-line-height)] [font-style:var(--typography-paragraph-base-medium-font-style)]">
                                Name
                            </label>
                            <div class="account-details-input relative flex flex-col items-start flex-1 gap-2 grow shadow-shadow-XS ermsg">
                                <div class="relative w-full">
                                    <label for="name"
                                        class="portal-floating-label {{ old('name', $user->name) ? 'active' : '' }}">Name</label>
                                    <input type="text" id="name" name="name"
                                        value="{{ old('name', $user->name) }}" class="portal-input-field"
                                        aria-label="Full Name" required
                                        title="Please enter full name (letters and spaces only)" pattern="^[a-zA-Z\s]+$"
                                        data-state="{{ old('name', $user->name) ? 'filled' : 'placeholder' }}" />
                                </div>
                            </div>
                        </div>

                        <!-- Email Field -->
                        <div class="account-details-form flex items-start gap-8 relative self-stretch w-full flex-[0_0_auto]">
                            <label for="email"
                                class="relative w-[250px] mt-[-1.00px] font-typography-paragraph-base-medium font-[number:var(--typography-paragraph-base-medium-font-weight)] text-gray-700 text-[length:var(--typography-paragraph-base-medium-font-size)] tracking-[var(--typography-paragraph-base-medium-letter-spacing)] leading-[var(--typography-paragraph-base-medium-line-height)] [font-style:var(--typography-paragraph-base-medium-font-style)]">
                                Email Address
                            </label>
                            <div class="account-details-input relative flex flex-col items-start flex-1 gap-2 grow shadow-shadow-XS ermsg">
                                <div class="relative w-full">
                                    <label for="email"
                                        class="portal-floating-label {{ old('email', $user->email) ? 'active' : '' }}">Email
                                        Address</label>
                                    <input type="email" id="email" name="email"
                                        value="{{ old('email', $user->email) }}" class="portal-input-field"
                                        aria-label="Email Address" required readonly
                                        data-state="{{ old('email', $user->email) ? 'filled' : 'placeholder' }}" />
                                </div>
                            </div>
                        </div>

                        <!-- Role Field (Read-only) -->
                        <div class="account-details-form flex items-start gap-8 relative self-stretch w-full flex-[0_0_auto]">
                            <div
                                class="relative w-[250px] mt-[-1.00px] font-typography-paragraph-base-medium font-[number:var(--typography-paragraph-base-medium-font-weight)] text-gray-700 text-[length:var(--typography-paragraph-base-medium-font-size)] tracking-[var(--typography-paragraph-base-medium-letter-spacing)] leading-[var(--typography-paragraph-base-medium-line-height)] [font-style:var(--typography-paragraph-base-medium-font-style)]">
                                Role
                            </div>
                            <div
                                class="relative flex-1 mt-[-1.00px] font-typography-paragraph-base-medium font-[number:var(--typography-paragraph-base-medium-font-weight)] text-gray-700 text-[length:var(--typography-paragraph-base-medium-font-size)] tracking-[var(--typography-paragraph-base-medium-letter-spacing)] leading-[var(--typography-paragraph-base-medium-line-height)] [font-style:var(--typography-paragraph-base-medium-font-style)]">
                                {{ ucwords(str_replace('_', ' ', $user->getRoleNames()->first() ?? 'User')) }}
                            </div>
                        </div>

                        <!-- Profile Photo Field -->
                        <div class="account-details-form flex items-start gap-8 relative self-stretch w-full flex-[0_0_auto]">
                            <div class="flex flex-col w-[250px] items-start justify-center gap-1 relative">
                                <div class="inline-flex items-center gap-2 relative flex-[0_0_auto]">
                                    <label for="profile_photo"
                                        class="relative w-fit mt-[-1.00px] font-typography-paragraph-base-medium font-[number:var(--typography-paragraph-base-medium-font-weight)] text-gray-700 text-[length:var(--typography-paragraph-base-medium-font-size)] tracking-[var(--typography-paragraph-base-medium-letter-spacing)] leading-[var(--typography-paragraph-base-medium-line-height)] whitespace-nowrap [font-style:var(--typography-paragraph-base-medium-font-style)]">
                                        Profile Photo
                                    </label>
                                </div>
                            </div>
                            <div class="account-details-input relative flex flex-col items-start flex-1 gap-2 grow shadow-shadow-XS">
                                <div
                                    class="flex items-center gap-2 p-3 relative self-stretch w-full flex-[0_0_auto] bg-white rounded border border-solid border-[#dddddd]">
                                    <div class="relative flex items-center flex-1 gap-3 grow">
                                        <div class="relative w-16 h-16 overflow-hidden rounded-full" role="img"
                                            aria-label="Profile photo">
                                            <div id="imagePreview" class="w-full h-full image-preview">
                                                @if ($user->profile_photo_url)
                                                    <img src="{{ $user->profile_photo_url }}" alt="Profile Photo"
                                                        class="object-cover w-full h-full">
                                                @else
                                                    <div
                                                        class="flex items-center justify-center w-full h-full bg-gray-200 text-img">
                                                        <span
                                                            class="text-xs text-gray-500">{{ substr($user->name, 0, 1) }}</span>
                                                    </div>
                                                @endif
                                            </div>
                                            <div
                                                class="h-full rounded-full border-[0.75px] border-solid border-[#00000014] absolute inset-0 pointer-events-none">
                                            </div>
                                        </div>
                                        @if ($user->profile_photo)
                                            <button type="button"
                                                class="all-[unset] box-border inline-flex items-center justify-center gap-2 relative flex-[0_0_auto] rounded-[999px] cursor-pointer"
                                                aria-label="Delete profile photo" id="deletePhotoBtn">
                                                <span
                                                    class="relative flex items-center justify-center w-fit mt-[-1.00px] [font-family:'BR_Hendrix-Medium',Helvetica] font-medium text-[#1c1b1b] text-sm text-center tracking-[0] leading-5 underline whitespace-nowrap">
                                                    Delete Photo
                                                </span>
                                            </button>
                                        @endif
                                    </div>
                                   <!--  <button type="button" id="changeProfilePhotoBtn"
                                        class="portal-btn-secondary-small change-Profile-Photo-Btn all-[unset] box-border flex w-[136px] items-center justify-center gap-2 px-5 py-2 relative rounded-[999px] border border-solid border-[#505050] cursor-pointer"
                                        aria-label="Change profile photo">
                                        <span
                                            class="relative flex items-center justify-center w-fit mt-[-1.00px] font-typography-paragraph-small-semibold font-[number:var(--typography-paragraph-small-semibold-font-weight)] text-[#1c1b1b] text-[length:var(--typography-paragraph-small-semibold-font-size)] text-center tracking-[var(--typography-paragraph-small-semibold-letter-spacing)] leading-[var(--typography-paragraph-small-semibold-line-height)] whitespace-nowrap [font-style:var(--typography-paragraph-small-semibold-font-style)]">
                                            Change Photo
                                        </span>
                                    </button> -->
                                    <input type="file" id="profile_photo" name="profile_photo"
                                        accept="image/jpeg,image/png,image/jpg" class="sr-only"
                                        aria-describedby="profile-photo-hint" />
                                    <input type="hidden" id="f_profile_photo" name="profile_photo_filename"
                                        value="{{ $user->profile_photo }}">
                                    <input type="hidden" id="delete_profile_photo" value="0">
                                </div>
                                <div class="ermsg"></div>
                            </div>
                        </div>

                        <!-- Timezone Field -->
                        <div class="account-details-form flex items-start gap-8 relative self-stretch w-full flex-[0_0_auto]" style="display: none;">
                            <div class="flex flex-col w-[250px] items-start justify-center gap-1 relative">
                                <div class="inline-flex items-center gap-2 relative flex-[0_0_auto]">
                                    <label for="timezone"
                                        class="relative w-fit mt-[-1.00px] font-typography-paragraph-base-medium font-[number:var(--typography-paragraph-base-medium-font-weight)] text-gray-700 text-[length:var(--typography-paragraph-base-medium-font-size)] tracking-[var(--typography-paragraph-base-medium-letter-spacing)] leading-[var(--typography-paragraph-base-medium-line-height)] whitespace-nowrap [font-style:var(--typography-paragraph-base-medium-font-style)]">
                                        Timezone
                                    </label>
                                </div>
                                <p class="relative self-stretch font-[400] text-[#707070] text-[12px] leading[18px]">
                                    The timezone will be used to show the correct date and time on the platform.
                                </p>
                            </div>
                            <div class="account-details-input relative flex flex-col items-start flex-1 gap-2 grow shadow-shadow-XS ermsg">
                                <div class="relative w-full">
                                    <label for="timezone"
                                        class="portal-floating-label {{ old('timezone', $user->timezone ?? 'UTC') ? 'active' : '' }}">Timezone</label>
                                    <select id="timezone" name="timezone"
                                        class="pr-8 appearance-none portal-input-field" aria-label="Select timezone"
                                        data-state="{{ old('timezone', $user->timezone ?? 'UTC') ? 'filled' : 'placeholder' }}">
                                        <option value=""></option>
                                        @foreach ($timezones as $timezone)
                                            <option value="{{ $timezone->timezone }}"
                                                {{ old('timezone', $user->timezone ?? 'UTC') == $timezone->timezone ? 'selected' : '' }}>
                                                {{ $timezone->name }} {{ $timezone->offset }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <svg class="absolute w-4 h-4 text-gray-400 transform -translate-y-1/2 pointer-events-none right-3 top-1/2"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <!-- Change Password Section -->
                        <section class="flex flex-col items-start gap-8 relative self-stretch w-full flex-[0_0_auto]"
                            aria-labelledby="change-password-heading">
                            <header
                                class="account-header flex flex-col items-start justify-center gap-1.5 px-3 py-1 relative self-stretch w-full flex-[0_0_auto] bg-[#e2f705] rounded-md"
                                style="background-color: #E84067;">
                                <div
                                    class="flex items-center justify-end gap-2 relative self-stretch w-full flex-[0_0_auto]">
                                    <div class="relative flex flex-col items-start flex-1 grow">
                                        <div
                                            class="flex items-center gap-4 relative self-stretch w-full flex-[0_0_auto]">
                                            <div class="relative flex items-center self-stretch flex-1 gap-2 grow">
                                                <h2 id="change-password-heading"
                                                    class="relative w-fit mt-[-1.00px] [font-family:'BR_Hendrix-Bold',Helvetica] font-bold text-[#FFFFFF] text-base tracking-[0] leading-6 whitespace-nowrap">
                                                    CHANGE PASSWORD
                                                </h2>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </header>



                            <div class="account-details-form flex items-start gap-8 relative self-stretch w-full flex-[0_0_auto]">
                                <label for="current_password"
                                    class="relative w-[250px] mt-[-1.00px] font-typography-paragraph-base-medium font-[number:var(--typography-paragraph-base-medium-font-weight)] text-gray-700 text-[length:var(--typography-paragraph-base-medium-font-size)] tracking-[var(--typography-paragraph-base-medium-letter-spacing)] leading-[var(--typography-paragraph-base-medium-line-height)] [font-style:var(--typography-paragraph-base-medium-font-style)]">
                                    Current Password
                                </label>
                                <div
                                    class="account-details-input relative flex flex-col items-start flex-1 gap-1 shadow-shadow-XS grow ermsg">
                                    <div class="relative w-full password-wrapper">
                                        <label for="current_password" class="portal-floating-label">Current
                                            Password</label>
                                        <input type="password" id="current_password" name="current_password"
                                            autocomplete="off" value="" class="pr-10 portal-input-field"
                                            aria-label="Current password" data-state="placeholder" />
                                        <button type="button"
                                            class="absolute z-10 w-6 h-6 p-0 transform -translate-y-1/2 bg-transparent border-0 cursor-pointer right-3 top-1/2 password-toggle"
                                            aria-label="Toggle password visibility"
                                            data-show-icon="{{ asset('assets/images/password-show.svg') }}"
                                            data-hide-icon="{{ asset('assets/images/password-hide.svg') }}">
                                            <img class="w-full h-full password-toggle-icon"
                                                src="{{ asset('assets/images/password-hide.svg') }}"
                                                alt="" />
                                        </button>
                                    </div>
                                </div>
                            </div>


                            <!-- New Password Field -->
                            <div class="account-details-form flex items-start gap-8 relative self-stretch w-full flex-[0_0_auto]">
                                <label for="password"
                                    class="relative w-[250px] mt-[-1.00px] font-typography-paragraph-base-medium font-[number:var(--typography-paragraph-base-medium-font-weight)] text-gray-700 text-[length:var(--typography-paragraph-base-medium-font-size)] tracking-[var(--typography-paragraph-base-medium-letter-spacing)] leading-[var(--typography-paragraph-base-medium-line-height)] [font-style:var(--typography-paragraph-base-medium-font-style)]">
                                    New password
                                </label>
                                <div
                                    class="account-details-input relative flex flex-col items-start flex-1 gap-1 shadow-shadow-XS grow ermsg">
                                    <div class="relative w-full password-wrapper">
                                        <label for="password" class="portal-floating-label">New password</label>
                                        <input type="password" id="password" name="password"
                                            autocomplete="new-password" class="pr-10 portal-input-field"
                                            aria-label="New password" aria-describedby="new-password-hint"
                                            data-state="placeholder" />
                                        <button type="button"
                                            class="absolute z-10 w-6 h-6 p-0 transform -translate-y-1/2 bg-transparent border-0 cursor-pointer right-3 top-1/2 password-toggle"
                                            aria-label="Toggle password visibility"
                                            data-show-icon="{{ asset('assets/images/password-show.svg') }}"
                                            data-hide-icon="{{ asset('assets/images/password-hide.svg') }}">
                                            <img class="w-full h-full password-toggle-icon"
                                                src="{{ asset('assets/images/password-hide.svg') }}"
                                                alt="" />
                                        </button>
                                    </div>
                                    <div class="pass-description w-full">
                                        <p id="new-password-hint"
                                            class="relative flex items-center justify-start self-stretch h-4 [font-family:'Roboto',Helvetica] font-normal text-[#707070] text-xs tracking-[0.02px] leading-[18px]">
                                            Password must be at least 8 characters and include upper & lower case, a
                                            number, and a special character.
                                        </p>
                                        <div id="password-requirements-account"
                                            class="mt-2 space-y-1 text-xs text-gray-600">
                                            <div class="flex items-center gap-2 password-req-item" data-rule="length">
                                                <span class="req-icon">○</span> At least 8 characters
                                            </div>
                                            <div class="flex items-center gap-2 password-req-item"
                                                data-rule="uppercase">
                                                <span class="req-icon">○</span> One uppercase letter
                                            </div>
                                            <div class="flex items-center gap-2 password-req-item"
                                                data-rule="lowercase">
                                                <span class="req-icon">○</span> One lowercase letter
                                            </div>
                                            <div class="flex items-center gap-2 password-req-item" data-rule="number">
                                                <span class="req-icon">○</span> One number
                                            </div>
                                            <div class="flex items-center gap-2 password-req-item"
                                                data-rule="special">
                                                <span class="req-icon">○</span> One special character
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Confirm Password Field -->
                            <div class="account-details-form flex items-start gap-8 relative self-stretch w-full flex-[0_0_auto]">
                                <label for="password_confirmation"
                                    class="relative w-[250px] mt-[-1.00px] font-typography-paragraph-base-medium font-[number:var(--typography-paragraph-base-medium-font-weight)] text-gray-700 text-[length:var(--typography-paragraph-base-medium-font-size)] tracking-[var(--typography-paragraph-base-medium-letter-spacing)] leading-[var(--typography-paragraph-base-medium-line-height)] [font-style:var(--typography-paragraph-base-medium-font-style)]">
                                    Confirm New Password
                                </label>
                                <div class="account-details-input relative flex flex-col items-start flex-1 shadow-shadow-XS grow ermsg">
                                    <div class="relative w-full password-wrapper">
                                        <label for="password_confirmation" class="portal-floating-label">Confirm New
                                            Password</label>
                                        <input type="password" id="password_confirmation"
                                            name="password_confirmation" autocomplete="new-password"
                                            class="pr-10 portal-input-field" aria-label="Confirm new password"
                                            data-state="placeholder" />
                                        <button type="button"
                                            class="absolute z-10 w-6 h-6 p-0 transform -translate-y-1/2 bg-transparent border-0 cursor-pointer right-3 top-1/2 password-toggle"
                                            aria-label="Toggle password visibility"
                                            data-show-icon="{{ asset('assets/images/password-show.svg') }}"
                                            data-hide-icon="{{ asset('assets/images/password-hide.svg') }}">
                                            <img class="w-full h-full password-toggle-icon"
                                                src="{{ asset('assets/images/password-hide.svg') }}"
                                                alt="" />
                                        </button>
                                    </div>
                                </div>
                            </div>
            </div>
            </section>
            <footer class="right-2.5 bottom-0 h-16 flex bg-neutral-50 fixed admin-footer">
                <div class="w-full h-[64.0px] flex flex-col gap-3 admin-footer-wrap">
                    <img class="object-cover w-full h-px -mt-px" src="" alt="" aria-hidden="true" />
                    <div class="admin-footer-layout flex right-2rem h-10 relative items-center justify-end gap-14">
                        <div class="admin-footer-btn-wrap inline-flex items-center gap-4 relative flex-[0_0_auto]">
                            <button id="saveProfileBtn" type="submit" name="submit"
                                class="directSubmit portal-btn-secondary-small all-[unset] box-border inline-flex h-10 flex-[0_0_auto] border border-solid border-[#505050] items-center justify-center gap-2 px-6 py-2.5 relative rounded-[999px] cursor-pointer directSubmit"
                                id="btnDraft">
                                <span id="save-profile-text"
                                    class="mt-[-1.00px] text-[#1c1b1b] relative flex items-center justify-center w-fit font-typography-paragraph-small-semibold font-[number:var(--typography-paragraph-small-semibold-font-weight)] text-[length:var(--typography-paragraph-small-semibold-font-size)] text-center tracking-[var(--typography-paragraph-small-semibold-letter-spacing)] leading-[var(--typography-paragraph-small-semibold-line-height)] whitespace-nowrap [font-style:var(--typography-paragraph-small-semibold-font-style)]">
                                    Save Changes
                                </span>
                            </button>
                            <!-- <div class="inline-flex items-center gap-4 relative flex-[0_0_auto]">
                    <button
                      type="submit" name="submit" value="in-review"
                      class="hidden text-sm tracking-wide uppercase portal-btn-primary-small all-[unset] box-border inline-flex h-10 flex-[0_0_auto] bg-[#1c1b1b] items-center justify-center gap-2 px-6 py-2.5 relative rounded-[999px] cursor-pointer directSubmit"
                      id="saveArtist"
                    >
                      <span
                      id="save-profile-progress"
                        class="mt-[-1.00px] text-white relative flex items-center justify-center w-fit font-typography-paragraph-small-semibold font-[number:var(--typography-paragraph-small-semibold-font-weight)] text-[length:var(--typography-paragraph-small-semibold-font-size)] text-center tracking-[var(--typography-paragraph-small-semibold-letter-spacing)] leading-[var(--typography-paragraph-small-semibold-line-height)] whitespace-nowrap [font-style:var(--typography-paragraph-small-semibold-font-style)]"
                      >
                        Processing...
                      </span>
                    </button>
                  </div> -->
                        </div>
                    </div>
                </div>
            </footer>
            <!-- Save Button -->
            <!-- <div class="flex items-center justify-end w-full pb-6 pr-4 mt-4">
                            <button type="submit" id="saveProfileBtn" class="flex items-center justify-center gap-2 portal-btn-primary-small directSubmit disabled:opacity-50 disabled:cursor-not-allowed" value="save">
                                <span id="save-profile-text">Save Changes</span>
                                <span id="save-profile-progress" class="hidden text-sm tracking-wide uppercase">Processing...</span>
                            </button>
                        </div> -->
            </form>
        </div>
        </div>
    </main>

    <!-- Profile Photo Upload Modal -->
    <div id="profilePhotoModal" class="modal-overlay">
        <div class="modal-container">
            <main class="adai-modal-wrapper relative flex flex-col items-start bg-white modal-content" role="dialog" aria-modal="true"
                aria-labelledby="profile-modal-title">
                <header class="flex flex-col items-start gap-3 p-6 relative self-stretch w-full flex-[0_0_auto]">
                    <div class="flex flex-col items-start gap-3 relative self-stretch w-full flex-[0_0_auto]">
                        <div class="flex flex-col items-start gap-3 relative self-stretch w-full flex-[0_0_auto]">
                            <h1 class="relative font-[600] text-[#1c1b1b] text-[20px] leading-[28px]">
                                Upload new profile photo
                            </h1>
                            <p class="relative font-[400] text-[#505050] text-[14px] leading-[20px]">
                                Upload a new photo to personalize your profile. Choose a clear, front-facing image where
                                your face is easy to recognize.
                            </p>
                        </div>
                    </div>
                </header>
                <section
                    class="flex flex-col items-start gap-4 p-6 relative self-stretch w-full flex-[0_0_auto] bg-neutral-50 rounded-[0px_0px_6px_6px]">
                    <div class="flex items-start gap-4 relative self-stretch w-full flex-[0_0_auto] profile-upload-wrapper">
                        <div class="inline-flex items-start justify-center gap-4 relative flex-[0_0_auto]">
                            <figure id="profile-photo-preview" class="relative w-[80px] h-[80px] profile-preview">
                                <div class="w-[80px] flex profile-preview-container"
                                    id="profile-photo-preview-container">
                                    @if ($user->profile_photo_url)
                                        <img id="profile-photo-preview-img"
                                            class="w-[80px] h-[80px] rounded object-cover profile-preview-img"
                                            src="{{ $user->profile_photo_url }}" alt="Current profile photo"
                                            data-original-src="{{ $user->profile_photo_url }}" />
                                    @else
                                        <div
                                            class="w-[80px] h-[80px] rounded bg-gray-200 flex items-center justify-center profile-preview-img profile-preview-img-bg">
                                            <span
                                                class="text-2xl text-gray-500">{{ substr($user->name, 0, 1) }}</span>
                                        </div>
                                    @endif
                                </div>
                            </figure>
                        </div>
                        <label id="dropArea"
                            class="profile-photo-dropzone flex flex-col items-center justify-center gap-2 px-3 py-3.5 relative flex-1 self-stretch grow rounded-md border-2 border-dashed border-[#dddddd] cursor-pointer transition-colors"
                            for="profile-photo-file-upload" tabindex="0" role="button"
                            aria-label="Upload new profile photo">
                            <input type="file" id="profile-photo-file-upload" name="profile-photo-file-upload"
                                accept="image/jpeg,image/png,image/jpg" class="sr-only"
                                aria-describedby="profile-file-requirements" />
                            <svg class="relative w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg" onerror="event.target.style.display='none';">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12">
                                </path>
                            </svg>
                            <p
                                class="relative self-stretch opacity-80 font-typography-paragraph-base-medium font-[number:var(--typography-paragraph-base-medium-font-weight)] text-[#1c1b1b] text-[length:var(--typography-paragraph-base-medium-font-size)] text-center tracking-[var(--typography-paragraph-base-medium-letter-spacing)] leading-[var(--typography-paragraph-base-medium-line-height)] [font-style:var(--typography-paragraph-base-medium-font-style)]">
                                Select or drag file into this area
                            </p>
                            <p id="profile-file-requirements"
                                class="profile-require relative self-stretch font-typography-paragraph-small-regular font-[number:var(--typography-paragraph-small-regular-font-weight)] text-[#505050] text-[length:var(--typography-paragraph-small-regular-font-size)] text-center tracking-[var(--typography-paragraph-small-regular-letter-spacing)] leading-[var(--typography-paragraph-small-regular-line-height)] [font-style:var(--typography-paragraph-small-regular-font-style)]">
                                JPG or PNG, minimum resolution 200px on the longer side
                            </p>
                        </label>
                    </div>
                    <div class="relative flex items-center w-full h-10 gap-2">
                        <div class="relative flex items-start flex-1 gap-4 grow">
                            <button type="button"
                                class="portal-btn-secondary-small width-full modal-cancel-profile-photo all-[unset] box-border flex h-10 items-center justify-center gap-2 px-6 py-2.5 relative flex-1 grow rounded-[999px] border border-solid border-[#505050] cursor-pointer"
                                aria-label="Cancel photo change">
                                <span
                                    class="relative flex items-center justify-center w-fit mt-[-1.00px] font-typography-paragraph-small-semibold font-[number:var(--typography-paragraph-small-semibold-font-weight)] text-[#1c1b1b] text-[length:var(--typography-paragraph-small-semibold-font-size)] text-center tracking-[var(--typography-paragraph-small-semibold-letter-spacing)] leading-[var(--typography-paragraph-small-semibold-line-height)] whitespace-nowrap [font-style:var(--typography-paragraph-small-semibold-font-style)]">
                                    Cancel
                                </span>
                            </button>
                            <button type="button"
                                class="portal-btn-primary-small width-full modal-save-profile-photo all-[unset] box-border flex h-10 items-center justify-center gap-2 px-6 py-2.5 relative flex-1 grow bg-[#1c1b1b] rounded-[999px] cursor-pointer"
                                aria-label="Save new profile photo">
                                <span
                                    class="relative flex items-center justify-center w-fit mt-[-1.00px] font-typography-paragraph-small-semibold font-[number:var(--typography-paragraph-small-semibold-font-weight)] text-white text-[length:var(--typography-paragraph-small-semibold-font-size)] text-center tracking-[var(--typography-paragraph-small-semibold-letter-spacing)] leading-[var(--typography-paragraph-small-semibold-line-height)] whitespace-nowrap [font-style:var(--typography-paragraph-small-semibold-font-style)]">
                                    Save
                                </span>
                            </button>
                        </div>
                    </div>
                </section>
                <button type="button" id="closeProfilePhotoModal"
                    class="absolute p-0 bg-transparent border-0 cursor-pointer top-2 right-2 w-9 h-9"
                    aria-label="Close dialog">
                    <img class="w-9 h-9" src="{{ asset('assets/images/adai-admin/icon-close.svg') }}" alt=""
                        aria-hidden="true" onerror="event.target.style.display='none';" />
                </button>
            </main>
        </div>
    </div>
    @include('profile.partials.delete-profile-photo')
    @section('uniquePageScript')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const formId = 'F_saveProfileBtn';
                const form = document.getElementById(formId);
                if (!form) {
                    return;
                }

                const submitButton = document.getElementById('saveProfileBtn');
                const submitButtonText = document.getElementById('save-profile-text');
                const submitButtonProgress = document.getElementById('save-profile-progress');
                const profilePhotoInput = document.getElementById('profile_photo');
                const profilePhotoFileUpload = document.getElementById('profile-photo-file-upload');
                const profilePhotoModal = document.getElementById('profilePhotoModal');
                const changeProfilePhotoBtn = document.getElementById('changeProfilePhotoBtn');
                const closeProfilePhotoModal = document.getElementById('closeProfilePhotoModal');
                const modalCancelProfilePhoto = document.querySelector('.modal-cancel-profile-photo');
                const modalSaveProfilePhoto = document.querySelector('.modal-save-profile-photo');
                const profilePhotoPreviewContainer = document.getElementById('profile-photo-preview-container');
                const imagePreview = document.getElementById('imagePreview');
                const profilePhotoDropzone = document.querySelector('.profile-photo-dropzone');
                const deletePhotoBtn = document.getElementById('deletePhotoBtn');
                const deleteProfilePhotoInput = document.getElementById('delete_profile_photo');
                const fProfilePhoto = document.getElementById('f_profile_photo');
                const timezoneSelect = document.getElementById('timezone');
                const timezoneArrow = document.getElementById('timezoneArrow');
                const passwordField = form.querySelector('#password');
                const currentPasswordField = form.querySelector('#current_password');
                const passwordRequirements = document.getElementById('password-requirements-account');
                const passwordPolicyRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^A-Za-z0-9]).{8,}$/;
                const dropArea = document.getElementById('dropArea');
                const photoModalBg = document.getElementById('deleteProfilePhotoModalBg');

                // Prevent autocomplete on current_password field
                if (currentPasswordField) {
                    // Ensure field is always empty and autocomplete is disabled
                    currentPasswordField.value = '';
                    currentPasswordField.setAttribute('autocomplete', 'off');

                    // Clear any autofilled value immediately after page load
                    setTimeout(function() {
                        currentPasswordField.value = '';
                    }, 50);

                    // Also clear on form reset
                    form.addEventListener('reset', function() {
                        currentPasswordField.value = '';
                    });
                }
                if (typeof jQuery !== 'undefined' && typeof $.validator !== 'undefined' && !$.validator.methods
                    .strongPassword) {
                    $.validator.addMethod("strongPassword", function(value) {
                        if (!value) return true;
                        return passwordPolicyRegex.test(value);
                    }, "Password must include uppercase, lowercase, number, and special character.");
                }

                if (timezoneArrow && timezoneSelect) {
                    const openTimezoneDropdown = () => {
                        timezoneSelect.focus();
                        timezoneSelect.dispatchEvent(new MouseEvent('mousedown', {
                            bubbles: true
                        }));
                    };

                    timezoneArrow.addEventListener('click', openTimezoneDropdown);
                    timezoneArrow.addEventListener('keydown', (event) => {
                        if (event.key === 'Enter' || event.key === ' ') {
                            event.preventDefault();
                            openTimezoneDropdown();
                        }
                    });
                }


                // Password toggle functionality
                const getPasswordAssets = (() => {
                    let cached;
                    return () => {
                        if (!cached) {
                            cached = {
                                hide: "{{ asset('assets/images/password-hide.svg') }}",
                                show: "{{ asset('assets/images/password-show.svg') }}"
                            };
                        }
                        return cached;
                    };
                })();

                const setDeletePhotoState = (shouldDelete = false) => {
                    if (!deleteProfilePhotoInput) {
                        return;
                    }
                    if (shouldDelete) {
                        deleteProfilePhotoInput.value = '1';
                        deleteProfilePhotoInput.name = 'delete_profile_photo';
                    } else {
                        deleteProfilePhotoInput.value = '0';
                        deleteProfilePhotoInput.removeAttribute('name');
                    }
                };
                setDeletePhotoState(false);

                const togglePasswordVisibility = (button, forceState = null) => {
                    if (!button) {
                        return;
                    }
                    const wrapper = button.closest('.password-wrapper');
                    if (!wrapper) {
                        return;
                    }

                    const input = wrapper.querySelector('input');
                    const icon = button.querySelector('.password-toggle-icon');

                    if (!input || !icon) {
                        return;
                    }

                    const shouldShow = forceState !== null ? forceState : input.type === 'password';
                    input.type = shouldShow ? 'text' : 'password';

                    const assets = getPasswordAssets();
                    icon.src = shouldShow ? assets.show : assets.hide;
                };

                document.querySelectorAll('.password-toggle').forEach(toggle => {
                    toggle.addEventListener('click', function() {
                        togglePasswordVisibility(this);
                    });
                });

                const checkPasswordStrength = (password) => {
                    if (!passwordRequirements) {
                        return;
                    }

                    const rules = {
                        length: password.length >= 8,
                        uppercase: /[A-Z]/.test(password),
                        lowercase: /[a-z]/.test(password),
                        number: /[0-9]/.test(password),
                        special: /[^A-Za-z0-9]/.test(password)
                    };

                    passwordRequirements.querySelectorAll('.password-req-item').forEach(item => {
                        const rule = item.getAttribute('data-rule');
                        const icon = item.querySelector('.req-icon');
                        if (!icon) {
                            return;
                        }
                        if (rules[rule]) {
                            item.classList.add('text-green-600');
                            item.classList.remove('text-gray-600');
                            icon.textContent = '✓';
                        } else {
                            item.classList.remove('text-green-600');
                            item.classList.add('text-gray-600');
                            icon.textContent = '○';
                        }
                    });
                };

                if (passwordField) {
                    passwordField.addEventListener('input', function() {
                        clearFieldError(this);
                        checkPasswordStrength(this.value);
                    });
                    passwordField.addEventListener('blur', function() {
                        checkPasswordStrength(this.value);
                    });
                }

                // Open modal when Change Photo button is clicked
                if (changeProfilePhotoBtn) {
                    changeProfilePhotoBtn.addEventListener('click', function() {
                        if (profilePhotoModal) {
                            profilePhotoModal.classList.add('show');
                            profilePhotoModal.style.display = 'flex';
                            document.body.style.overflow = 'hidden';
                        }
                    });
                }

                // Close modal handlers
                const closeModal = () => {
                    if (profilePhotoModal) {
                        profilePhotoModal.classList.remove('show');
                        profilePhotoModal.style.display = 'none';
                        document.body.style.overflow = '';
                    }
                };

                if (closeProfilePhotoModal) {
                    closeProfilePhotoModal.addEventListener('click', closeModal);
                }

                if (modalCancelProfilePhoto) {
                    modalCancelProfilePhoto.addEventListener('click', closeModal);
                }

                // Close modal on overlay click
                if (profilePhotoModal) {
                    profilePhotoModal.addEventListener('click', function(e) {
                        if (e.target === profilePhotoModal) {
                            closeModal();
                        }
                    });
                }

                const updateModalPreview = (file) => {
                    if (!file || !profilePhotoPreviewContainer) {
                        return;
                    }

                    const reader = new FileReader();
                    reader.onload = function(e) {
                        profilePhotoPreviewContainer.innerHTML =
                            '<img id="profile-photo-preview-img" class="w-[80px] h-[80px] rounded object-cover" style="border-radius: 50%" src="' +
                            e.target.result + '" alt="Selected profile photo">';
                    };
                    reader.readAsDataURL(file);
                };

                // Initialize generic dropzone for profile photo upload
                if (typeof DropzoneUpload !== 'undefined' && profilePhotoDropzone && profilePhotoFileUpload) {
                    DropzoneUpload.init({
                        dropzoneSelector: '.profile-photo-dropzone',
                        fileInputSelector: '#profile-photo-file-upload',
                        previewSelector: '#profile-photo-preview-img',
                        minWidth: 200,
                        minHeight: 200,
                        maxSize: 5 * 1024 * 1024, // 5MB
                        allowedTypes: ['image/jpeg', 'image/png', 'image/jpg'],
                        onFileSelect: function(file, img) {
                            updateModalPreview(file);
                        },
                        onError: function(message) {
                            // Error handling is done by DropzoneUpload
                        }
                    });
                }

                // Update modal preview when user picks a file manually
                if (profilePhotoFileUpload) {
                    profilePhotoFileUpload.addEventListener('change', function(event) {
                        console.log('Click change');
                        const file = event.target?.files?.[0];
                        console.log(file);
                        updateModalPreview(file);
                    });

                    // Prevent default behavior
                    ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                        dropArea.addEventListener(eventName, e => {
                            e.preventDefault();
                            e.stopPropagation();
                        });
                    });

                    // Highlight on dragover
                    dropArea.addEventListener('dragover', () => dropArea.classList.add('drag-over'));
                    dropArea.addEventListener('dragleave', () => dropArea.classList.remove('drag-over'));

                    // On drop
                    dropArea.addEventListener('drop', event => {
                        event.preventDefault();
                        event.stopPropagation();

                        const file = event.dataTransfer.files[0];
                        console.log("Dropped file:", file);

                        // ✅ Save dropped file into file input using DataTransfer()
                        const dataTransfer = new DataTransfer();
                        dataTransfer.items.add(file);
                        profilePhotoFileUpload.files = dataTransfer.files;

                        // Continue with your preview logic
                        updateModalPreview(file);
                    });
                }

                // Save button in modal - copy file to main form input
                if (modalSaveProfilePhoto) {
                    modalSaveProfilePhoto.addEventListener('click', function() {
                        if (profilePhotoFileUpload && profilePhotoFileUpload.files.length > 0) {
                            // Copy file to main form input
                            const dataTransfer = new DataTransfer();
                            dataTransfer.items.add(profilePhotoFileUpload.files[0]);
                            if (profilePhotoInput) {
                                profilePhotoInput.files = dataTransfer.files;
                            }

                            // Update main display preview
                            const reader = new FileReader();
                            reader.onload = function(e) {
                                if (imagePreview) {
                                    imagePreview.innerHTML = '<img src="' + e.target.result +
                                        '" alt="Profile Photo" class="object-cover w-full h-full">';
                                }
                            };
                            reader.readAsDataURL(profilePhotoFileUpload.files[0]);

                            // Reset delete flag
                            setDeletePhotoState(false);

                            // Close modal
                            closeModal();

                            if (typeof Lobibox !== 'undefined') {
                                Lobibox.notify("success", {
                                    rounded: false,
                                    delay: 8000,
                                    delayIndicator: true,
                                    position: "top right",
                                    icon: 'fa fa-check-circle',
                                    msg: 'Profile photo selected. Click "Save Changes" to upload.',
                                });
                            }
                        } else {
                            if (typeof Lobibox !== 'undefined') {
                                Lobibox.notify("error", {
                                    rounded: false,
                                    delay: 8000,
                                    delayIndicator: true,
                                    position: "top right",
                                    icon: 'fa fa-times-circle',
                                    msg: 'Please select an image file',
                                });
                            }
                        }
                    });
                }
                const openPhotoModal = () => {
                    photoModalBg.classList.remove('hidden');
                    photoModalBg.style.display = 'flex';
                };
                const closePhotoModal = () => {
                    photoModalBg.classList.add('hidden');
                    photoModalBg.style.display = 'none';
                };

                document.getElementById('closeDeleteProfilePhotoModal')
                    .addEventListener('click', closePhotoModal);

                document.getElementById('cancelDeleteProfilePhoto')
                    .addEventListener('click', closePhotoModal);

                photoModalBg.addEventListener('click', function (e) {
                    if (e.target === photoModalBg) closePhotoModal();
                });
                // Delete photo button
                if (deletePhotoBtn) {
                    deletePhotoBtn.addEventListener('click', function () {
                        openPhotoModal();
                    });
                    // deletePhotoBtn.addEventListener('click', function() {
                    //     if (confirm('Are you sure you want to delete your profile photo?')) {
                    //         setDeletePhotoState(true);
                    //         // Disable button during request
                    //         const btn = this;
                    //         btn.disabled = true;
                    //         const originalText = btn.querySelector('span').textContent;
                    //         btn.querySelector('span').textContent = 'Deleting...';

                    //         // Make AJAX request to delete photo
                    //         if (typeof jQuery !== 'undefined') {
                    //             // Get CSRF token from meta tag or form
                    //             const csrfToken = $('meta[name="csrf-token"]').attr('content') ||
                    //                 $('input[name="_token"]').val() ||
                    //                 '{{ csrf_token() }}';

                    //             $.ajax({
                    //                 url: '{{ route('profile.delete-photo') }}',
                    //                 type: 'POST',
                    //                 headers: {
                    //                     'X-CSRF-TOKEN': csrfToken
                    //                 },
                    //                 success: function(response) {
                    //                     // Clear file input
                    //                     if (profilePhotoInput) {
                    //                         profilePhotoInput.value = '';
                    //                     }

                    //                     // Clear hidden filename
                    //                     if (fProfilePhoto) {
                    //                         fProfilePhoto.value = '';
                    //                     }

                    //                     // Clear delete flag
                    //                     setDeletePhotoState(false);

                    //                     // Update preview to show placeholder
                    //                     const userName = '{{ $user->name }}';
                    //                     if (imagePreview) {
                    //                         imagePreview.innerHTML =
                    //                             '<div class="flex items-center justify-center w-full h-full bg-gray-200"><span class="text-xs text-gray-500">' +
                    //                             userName.charAt(0).toUpperCase() + '</span></div>';
                    //                     }

                    //                     // Hide delete button
                    //                     btn.style.display = 'none';

                    //                     // Show success message
                    //                     if (typeof Lobibox !== 'undefined') {
                    //                         Lobibox.notify("success", {
                    //                             rounded: false,
                    //                             delay: 3000,
                    //                             delayIndicator: true,
                    //                             position: "top right",
                    //                             icon: 'fa fa-check-circle',
                    //                             msg: response.message ||
                    //                                 'Profile photo deleted successfully.',
                    //                         });
                    //                     }

                    //                     // Reload page if requested
                    //                     if (response.reload) {
                    //                         setTimeout(function() {
                    //                             location.reload();
                    //                         }, 1000);
                    //                     }
                    //                 },
                    //                 error: function(xhr) {
                    //                     // Re-enable button
                    //                     btn.disabled = false;
                    //                     btn.querySelector('span').textContent = originalText;
                    //                     setDeletePhotoState(false);

                    //                     // Show error message
                    //                     let errorMessage =
                    //                         'Failed to delete profile photo. Please try again.';
                    //                     try {
                    //                         const response = JSON.parse(xhr.responseText);
                    //                         if (response.message) {
                    //                             errorMessage = response.message;
                    //                         }
                    //                     } catch (e) {
                    //                         // Use default message
                    //                     }

                    //                     if (typeof Lobibox !== 'undefined') {
                    //                         Lobibox.notify("error", {
                    //                             rounded: false,
                    //                             delay: 3000,
                    //                             delayIndicator: true,
                    //                             position: "top right",
                    //                             icon: 'fa fa-times-circle',
                    //                             msg: errorMessage,
                    //                         });
                    //                     }
                    //                 }
                    //             });
                    //         } else {
                    //             // Fallback if jQuery is not available
                    //             btn.disabled = false;
                    //             btn.querySelector('span').textContent = originalText;
                    //             setDeletePhotoState(false);
                    //             alert('jQuery is required for this operation.');
                    //         }
                    //     }
                    // });
                }
                document.getElementById('confirmDeleteProfilePhoto')
                    .addEventListener('click', function () {

                        const btn = this;

                        setDeletePhotoState(true);
                        btn.disabled = true;
                        btn.textContent = 'Deleting...';

                        const csrfToken =
                            document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ||
                            '{{ csrf_token() }}';

                        $.ajax({
                            url: '{{ route('profile.delete-photo') }}',
                            type: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': csrfToken
                            },
                            success: function (response) {

                                closePhotoModal(); // ✅ close ONLY on success

                                if (profilePhotoInput) profilePhotoInput.value = '';
                                if (fProfilePhoto) fProfilePhoto.value = '';

                                setDeletePhotoState(false);

                                const userName = '{{ $user->name }}';
                                if (imagePreview) {
                                    imagePreview.innerHTML = `
                                        <div class="flex items-center justify-center w-full h-full bg-gray-200">
                                            <span class="text-xs text-gray-500">
                                                ${userName.charAt(0).toUpperCase()}
                                            </span>
                                        </div>`;
                                }

                                deletePhotoBtn.style.display = 'none';

                                Lobibox?.notify("success", {
                                    position: "top right",
                                    msg: response.message || 'Profile photo deleted successfully.',
                                });
                                // Reload page if requested
                                if (response.reload) {
                                    setTimeout(function() {
                                        location.reload();
                                    }, 1000);
                                }
                            },
                            error: function () {

                                // keep modal OPEN on error
                                btn.disabled = false;
                                btn.textContent = 'Delete Photo';
                                setDeletePhotoState(false);

                                Lobibox?.notify("error", {
                                    position: "top right",
                                    msg: 'Failed to delete profile photo.',
                                });
                            }
                        });
                    });


                const showLoader = () => {
                    if (submitButtonText) submitButtonText.classList.add('hidden');
                    if (submitButtonProgress) submitButtonProgress.classList.remove('hidden');
                    if (submitButton) {
                        submitButton.disabled = true;
                        submitButton.classList.add('opacity-50', 'cursor-not-allowed');
                    }
                };

                const hideLoader = () => {
                    if (submitButtonText) submitButtonText.classList.remove('hidden');
                    if (submitButtonProgress) submitButtonProgress.classList.add('hidden');
                    if (submitButton) {
                        submitButton.disabled = false;
                        submitButton.classList.remove('opacity-50', 'cursor-not-allowed');
                    }
                };

                // Clear error styling when user starts typing
                const clearFieldError = (field) => {
                    if (field) {
                        field.classList.remove('border-red-500');
                        const errorContainer = field.closest('.ermsg') || field.closest('.flex.flex-col');
                        if (errorContainer) {
                            const fieldErrors = errorContainer.querySelectorAll('.field-error-message');
                            fieldErrors.forEach(err => err.remove());
                        }
                    }
                };

                // Real-time password validation function
                // Password fields are OPTIONAL - validation only runs when user enters any value
                const validatePasswordFields = () => {
                    const currentPassword = form.querySelector('#current_password');
                    const password = form.querySelector('#password');
                    const passwordConfirmation = form.querySelector('#password_confirmation');

                    if (!currentPassword || !password || !passwordConfirmation) {
                        return;
                    }

                    const currentPasswordValue = currentPassword.value.trim();
                    const passwordValue = password.value.trim();
                    const passwordConfirmationValue = passwordConfirmation.value.trim();

                    // Check if ANY password field has a value (even a single character)
                    const anyPasswordFieldFilled = currentPasswordValue.length > 0 || passwordValue.length > 0 ||
                        passwordConfirmationValue.length > 0;

                    // Always clear previous password field errors first
                    [currentPassword, password, passwordConfirmation].forEach(field => {
                        if (field) {
                            field.classList.remove('border-red-500');
                            const errorContainer = field.closest('.ermsg') || field.closest(
                                '.flex.flex-col');
                            if (errorContainer) {
                                const fieldErrors = errorContainer.querySelectorAll('.field-error-message');
                                fieldErrors.forEach(err => err.remove());
                            }
                        }
                    });

                    // IMPORTANT: If ALL fields are empty, skip validation completely (fields are optional)
                    if (!anyPasswordFieldFilled) {
                        return; // All fields empty - no validation needed, form can be submitted
                    }

                    // If we reach here, at least one field has a value, so all three are now required
                    const fieldLabels = {
                        'current_password': 'Current Password',
                        'password': 'New Password',
                        'password_confirmation': 'Confirm Password'
                    };

                    let hasErrors = false;

                    // Check if all three fields are filled
                    if (!currentPasswordValue) {
                        hasErrors = true;
                        currentPassword.classList.add('border-red-500');
                        const errorContainer = currentPassword.closest('.ermsg') || currentPassword.closest(
                            '.flex.flex-col');
                        if (errorContainer) {
                            const errorDiv = document.createElement('div');
                            errorDiv.className = 'text-red-500 text-sm field-error-message mt-1';
                            errorDiv.innerHTML =
                                `<strong>${fieldLabels['current_password']}:</strong> Current password is required when changing password`;
                            errorContainer.appendChild(errorDiv);
                        }
                    }

                    if (!passwordValue) {
                        hasErrors = true;
                        password.classList.add('border-red-500');
                        const errorContainer = password.closest('.ermsg') || password.closest('.flex.flex-col');
                        if (errorContainer) {
                            const errorDiv = document.createElement('div');
                            errorDiv.className = 'text-red-500 text-sm field-error-message mt-1';
                            errorDiv.innerHTML =
                                `<strong>${fieldLabels['password']}:</strong> New password is required when changing password`;
                            errorContainer.appendChild(errorDiv);
                        }
                    }

                    if (!passwordConfirmationValue) {
                        hasErrors = true;
                        passwordConfirmation.classList.add('border-red-500');
                        const errorContainer = passwordConfirmation.closest('.ermsg') || passwordConfirmation
                            .closest('.flex.flex-col');
                        if (errorContainer) {
                            const errorDiv = document.createElement('div');
                            errorDiv.className = 'text-red-500 text-sm field-error-message mt-1';
                            errorDiv.innerHTML =
                                `<strong>${fieldLabels['password_confirmation']}:</strong> Password confirmation is required when changing password`;
                            errorContainer.appendChild(errorDiv);
                        }
                    }

                    // Only validate password rules if all three fields are filled
                    if (currentPasswordValue && passwordValue && passwordConfirmationValue) {
                        // Validate password length
                        if (passwordValue.length < 8) {
                            hasErrors = true;
                            password.classList.add('border-red-500');
                            const errorContainer = password.closest('.ermsg') || password.closest('.flex.flex-col');
                            if (errorContainer) {
                                const errorDiv = document.createElement('div');
                                errorDiv.className = 'text-red-500 text-sm field-error-message mt-1';
                                errorDiv.innerHTML =
                                    `<strong>${fieldLabels['password']}:</strong> Password must be at least 8 characters long`;
                                errorContainer.appendChild(errorDiv);
                            }
                        } else if (!passwordPolicyRegex.test(passwordValue)) {
                            hasErrors = true;
                            password.classList.add('border-red-500');
                            const errorContainer = password.closest('.ermsg') || password.closest('.flex.flex-col');
                            if (errorContainer) {
                                const errorDiv = document.createElement('div');
                                errorDiv.className = 'text-red-500 text-sm field-error-message mt-1';
                                errorDiv.innerHTML =
                                    `<strong>${fieldLabels['password']}:</strong> Password must include uppercase, lowercase, number, and special character`;
                                errorContainer.appendChild(errorDiv);
                            }
                        }

                        // Validate password match
                        if (passwordValue !== passwordConfirmationValue) {
                            hasErrors = true;
                            passwordConfirmation.classList.add('border-red-500');
                            const errorContainer = passwordConfirmation.closest('.ermsg') || passwordConfirmation
                                .closest('.flex.flex-col');
                            if (errorContainer) {
                                const errorDiv = document.createElement('div');
                                errorDiv.className = 'text-red-500 text-sm field-error-message mt-1';
                                errorDiv.innerHTML =
                                    `<strong>${fieldLabels['password_confirmation']}:</strong> Passwords do not match`;
                                errorContainer.appendChild(errorDiv);
                            }
                        }
                    }
                };

                // Add event listeners to all form fields to clear errors on input
                form.querySelectorAll('input, select').forEach(field => {
                    field.addEventListener('input', function() {
                        // For password fields, run real-time validation
                        if (this.id === 'current_password' || this.id === 'password' || this.id ===
                            'password_confirmation') {
                            // Use a small delay to allow the user to finish typing
                            clearTimeout(this.validationTimeout);
                            this.validationTimeout = setTimeout(() => {
                                validatePasswordFields();
                            }, 300);
                        } else {
                            clearFieldError(this);
                        }
                    });
                    field.addEventListener('change', function() {
                        // For password fields, run real-time validation
                        if (this.id === 'current_password' || this.id === 'password' || this.id ===
                            'password_confirmation') {
                            validatePasswordFields();
                        } else {
                            clearFieldError(this);
                        }
                    });
                });

                // Client-side validation
                const validateForm = () => {
                    let isValid = true;
                    const errors = [];

                    // Validate name
                    const nameInput = form.querySelector('#name');
                    if (nameInput && nameInput.value.trim()) {
                        const namePattern = /^[a-zA-Z\s]+$/;
                        if (!namePattern.test(nameInput.value.trim())) {
                            isValid = false;
                            errors.push({
                                field: nameInput,
                                message: 'Name should contain only letters and spaces'
                            });
                        }
                    }

                    // Validate email
                    const emailInput = form.querySelector('#email');
                    if (emailInput && emailInput.value.trim()) {
                        const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                        if (!emailPattern.test(emailInput.value.trim())) {
                            isValid = false;
                            errors.push({
                                field: emailInput,
                                message: 'Please enter a valid email address'
                            });
                        }
                    }

                    // Validate password fields - OPTIONAL: only validate if user enters any value
                    const currentPassword = form.querySelector('#current_password');
                    const password = form.querySelector('#password');
                    const passwordConfirmation = form.querySelector('#password_confirmation');

                    const currentPasswordValue = currentPassword ? currentPassword.value.trim() : '';
                    const passwordValue = password ? password.value.trim() : '';
                    const passwordConfirmationValue = passwordConfirmation ? passwordConfirmation.value.trim() : '';

                    // Check if ANY password field has a value (even a single character)
                    const anyPasswordFieldFilled = currentPasswordValue.length > 0 || passwordValue.length > 0 ||
                        passwordConfirmationValue.length > 0;

                    // IMPORTANT: Only validate if at least one field has a value
                    // If all fields are empty, skip password validation completely (fields are optional)
                    if (anyPasswordFieldFilled) {
                        // If any password field is filled, all three must be filled
                        if (!currentPasswordValue) {
                            isValid = false;
                            errors.push({
                                field: currentPassword,
                                message: 'Current password is required when changing password'
                            });
                        }
                        if (!passwordValue) {
                            isValid = false;
                            errors.push({
                                field: password,
                                message: 'New password is required when changing password'
                            });
                        }
                        if (!passwordConfirmationValue) {
                            isValid = false;
                            errors.push({
                                field: passwordConfirmation,
                                message: 'Password confirmation is required when changing password'
                            });
                        }

                        // Only validate password rules if all three fields are filled
                        if (currentPasswordValue && passwordValue && passwordConfirmationValue) {
                            // Validate password length
                            if (passwordValue.length < 8) {
                                isValid = false;
                                errors.push({
                                    field: password,
                                    message: 'Password must be at least 8 characters long'
                                });
                            } else if (!passwordPolicyRegex.test(passwordValue)) {
                                isValid = false;
                                errors.push({
                                    field: password,
                                    message: 'Password must include uppercase, lowercase, number, and special character'
                                });
                            }

                            // Validate password match
                            if (passwordValue !== passwordConfirmationValue) {
                                isValid = false;
                                errors.push({
                                    field: passwordConfirmation,
                                    message: 'Passwords do not match'
                                });
                            }
                        }
                    }
                    // If all password fields are empty, no validation needed - form can be submitted

                    // Field labels for error messages
                    const fieldLabels = {
                        'name': 'Name',
                        'email': 'Email Address',
                        'timezone': 'Timezone',
                        'profile_photo': 'Profile Photo',
                        'current_password': 'Current Password',
                        'password': 'New Password',
                        'password_confirmation': 'Confirm Password'
                    };

                    // Display errors
                    errors.forEach(error => {
                        if (error.field) {
                            error.field.classList.add('border-red-500');
                            const errorContainer = error.field.closest('.ermsg') || error.field.closest(
                                '.flex.flex-col');
                            if (errorContainer) {
                                const existingErrors = errorContainer.querySelectorAll(
                                    '.field-error-message');
                                existingErrors.forEach(err => err.remove());

                                // Get field name for label
                                const fieldName = error.field.name || error.field.id;
                                const fieldLabel = fieldLabels[fieldName] || fieldName.replace(/_/g, ' ')
                                    .replace(/\b\w/g, l => l.toUpperCase());

                                const errorDiv = document.createElement('div');
                                errorDiv.className = 'text-red-500 text-sm field-error-message mt-1';
                                errorDiv.innerHTML = `<strong>${fieldLabel}:</strong> ${error.message}`;
                                errorContainer.appendChild(errorDiv);
                            }
                        }
                    });

                    return isValid;
                };

                // Safety timeout to hide loader if no response (30 seconds)
                let loaderTimeout = null;

                // Let developer.js handle form submission
                // We'll add custom validation and show loader via AJAX events
                const applyPasswordValidationOverrides = () => {
                    if (typeof jQuery === 'undefined') {
                        return;
                    }

                    const $form = $('#' + formId);
                    const validator = $form.data('validator');

                    if (!validator || !validator.settings) {
                        return;
                    }

                    const ensureRulesObject = (fieldName) => {
                        if (!validator.settings.rules) {
                            validator.settings.rules = {};
                        }
                        if (!validator.settings.rules[fieldName]) {
                            validator.settings.rules[fieldName] = {};
                        }
                        return validator.settings.rules[fieldName];
                    };

                    const passwordFieldsRequired = () => {
                        const currentPasswordValue = ($('#current_password').val() || '').trim();
                        const passwordValue = ($('#password').val() || '').trim();
                        const passwordConfirmationValue = ($('#password_confirmation').val() || '').trim();
                        return currentPasswordValue.length > 0 || passwordValue.length > 0 ||
                            passwordConfirmationValue.length > 0;
                    };

                    const currentPasswordRule = ensureRulesObject('current_password');
                    currentPasswordRule.required = passwordFieldsRequired;

                    const passwordRule = ensureRulesObject('password');
                    passwordRule.required = passwordFieldsRequired;
                    passwordRule.minlength = 8;
                    passwordRule.strongPassword = true;

                    const confirmRule = ensureRulesObject('password_confirmation');
                    confirmRule.required = passwordFieldsRequired;

                    // Remove equalTo rule completely when fields are empty to prevent false validation
                    const hasPasswordValues = passwordFieldsRequired();

                    if (hasPasswordValues) {
                        // Only validate when password fields have values
                        confirmRule.equalTo = '#password';
                    } else {
                        // When all password fields are empty, completely disable validation for them
                        // IMPORTANT: Remove equalTo rule completely to prevent false validation errors
                        // Use multiple methods to ensure it's removed
                        if (confirmRule.equalTo !== undefined) {
                            delete confirmRule.equalTo;
                        }
                        if (confirmRule.minlength !== undefined) {
                            delete confirmRule.minlength;
                        }
                        // Also check if it's set in the main rules object
                        if (validator.settings.rules && validator.settings.rules.password_confirmation) {
                            if (validator.settings.rules.password_confirmation.equalTo !== undefined) {
                                delete validator.settings.rules.password_confirmation.equalTo;
                            }
                        }

                        // Also ensure password and current_password rules are minimal when empty
                        // Remove validation rules that shouldn't apply when fields are empty
                        if (passwordRule.minlength !== undefined) {
                            delete passwordRule.minlength;
                        }
                        if (passwordRule.strongPassword !== undefined) {
                            delete passwordRule.strongPassword;
                        }

                        // The required functions will return false when fields are empty, so they're fine

                        // Clear all validation errors for password fields
                        const $confirmField = $('#password_confirmation');
                        const $passwordField = $('#password');
                        const $currentPasswordField = $('#current_password');

                        [$confirmField, $passwordField, $currentPasswordField].forEach(function($field) {
                            if ($field.length && validator) {
                                // Remove error class
                                $field.removeClass('error');

                                // Clear from validator's internal error tracking
                                const fieldName = $field.attr('name');
                                const fieldId = $field.attr('id');

                                if (validator.invalid) {
                                    delete validator.invalid[fieldName];
                                    delete validator.invalid[fieldId];
                                }
                                if (validator.errorMap) {
                                    delete validator.errorMap[fieldName];
                                    delete validator.errorMap[fieldId];
                                }

                                // Remove from error list
                                if (validator.errorList) {
                                    validator.errorList = validator.errorList.filter(function(err) {
                                        return err.element !== $field[0];
                                    });
                                }

                                // Remove error messages from DOM
                                $field.siblings('.error').remove();
                                $field.closest('.ermsg, .flex.flex-col').find(
                                    '.field-error-message, .error').remove();
                            }
                        });
                    }

                    if (!validator.settings.messages) {
                        validator.settings.messages = {};
                    }

                    // Set specific error messages for all fields
                    validator.settings.messages.name = validator.settings.messages.name || {};
                    validator.settings.messages.name.required = 'Name is required.';

                    validator.settings.messages.email = validator.settings.messages.email || {};
                    validator.settings.messages.email.required = 'Email Address is required.';
                    validator.settings.messages.email.email = 'Please enter a valid email address.';

                    validator.settings.messages.current_password = validator.settings.messages.current_password ||
                        {};
                    validator.settings.messages.current_password.required =
                        'Current password is required when changing password.';

                    validator.settings.messages.password = validator.settings.messages.password || {};
                    validator.settings.messages.password.required =
                        'New password is required when changing password.';
                    validator.settings.messages.password.minlength = 'Password must be at least 8 characters long.';
                    validator.settings.messages.password.strongPassword =
                        'Password must include uppercase, lowercase, number, and special character.';

                    if (!validator.settings.messages.password_confirmation) {
                        validator.settings.messages.password_confirmation = {};
                    }
                    validator.settings.messages.password_confirmation.required =
                        'Password confirmation is required when changing password.';

                    // Only set equalTo message if the rule is actually active
                    if (hasPasswordValues) {
                        validator.settings.messages.password_confirmation.equalTo =
                            'The password confirmation does not match.';
                    } else {
                        // Remove the message to prevent it from showing
                        delete validator.settings.messages.password_confirmation.equalTo;
                    }

                    // Clear any existing validation errors for password fields when they're empty
                    if (!hasPasswordValues) {
                        const $confirmField = $('#password_confirmation');
                        const $passwordField = $('#password');
                        const $currentPasswordField = $('#current_password');

                        if ($confirmField.length) {
                            $confirmField.removeClass('error');
                            validator.invalid = validator.invalid || {};
                            delete validator.invalid['password_confirmation'];
                            validator.errorList = validator.errorList.filter(err => err.element !== $confirmField[
                                0]);
                            validator.errorMap = validator.errorMap || {};
                            delete validator.errorMap['password_confirmation'];
                        }
                        if ($passwordField.length) {
                            $passwordField.removeClass('error');
                            validator.invalid = validator.invalid || {};
                            delete validator.invalid['password'];
                            validator.errorList = validator.errorList.filter(err => err.element !== $passwordField[
                                0]);
                            validator.errorMap = validator.errorMap || {};
                            delete validator.errorMap['password'];
                        }
                        if ($currentPasswordField.length) {
                            $currentPasswordField.removeClass('error');
                            validator.invalid = validator.invalid || {};
                            delete validator.invalid['current_password'];
                            validator.errorList = validator.errorList.filter(err => err.element !==
                                $currentPasswordField[0]);
                            validator.errorMap = validator.errorMap || {};
                            delete validator.errorMap['current_password'];
                        }

                        // Remove error messages from DOM
                        $confirmField.siblings('.error').remove();
                        $passwordField.siblings('.error').remove();
                        $currentPasswordField.siblings('.error').remove();
                    }
                };

                if (typeof jQuery !== 'undefined' && form) {
                    const $form = $('#' + formId);

                    // Run override immediately on page load and when validator is initialized
                    $(document).ready(function() {
                        // Wait a bit for developer.js to initialize validator
                        setTimeout(function() {
                            applyPasswordValidationOverrides();

                            // Watch for validator initialization and override immediately
                            if ($form.length) {
                                const validator = $form.data('validator');
                                if (validator) {
                                    applyPasswordValidationOverrides();
                                }
                            }
                        }, 100);

                        // Also check periodically for validator initialization (in case it's created later)
                        let checkCount = 0;
                        const checkValidator = setInterval(function() {
                            checkCount++;
                            const validator = $form.data('validator');
                            if (validator) {
                                applyPasswordValidationOverrides();
                                clearInterval(checkValidator);
                            } else if (checkCount > 20) {
                                // Stop checking after 2 seconds
                                clearInterval(checkValidator);
                            }
                        }, 100);
                    });

                    // Run override on form submit attempt
                    // $(form).on('submit', function(e) {
                    //     alert("ffffff");
                    //     applyPasswordValidationOverrides();
                    // });

                    // Ensure overrides run before developer.js validation executes by listening in capture phase
                    // if (submitButton) {
                    //     submitButton.addEventListener('click', function(e) {
                    //         // Run override immediately in capture phase before any other handlers
                    //         applyPasswordValidationOverrides();
                    //     }, true);
                    // }

                    // Also hook into jQuery's click handler to ensure override runs before validation
                    // $(document).on('click', '#' + submitButton.id, function(e) {
                    //     // Apply override synchronously before validation
                    //     applyPasswordValidationOverrides();
                    // });

                    // Hook into form validation event to ensure override is applied
                    $(form).on('validate', function() {
                        applyPasswordValidationOverrides();
                    });

                    // Hook into validator's form method to intercept validation
                    if ($form.length) {
                        // Override the form method to ensure our rules are applied
                        const validator = $form.data('validator');
                        if (validator && validator.form) {
                            const originalForm = validator.form;
                            validator.form = function() {
                                // Apply overrides right before validation
                                applyPasswordValidationOverrides();
                                return originalForm.call(this);
                            };
                        }
                    }

                    // Watch for password field changes and reapply overrides
                    $(form).on('input change', '#current_password, #password, #password_confirmation', function() {
                        setTimeout(function() {
                            applyPasswordValidationOverrides();
                        }, 10);
                    });

                    // Show loader when AJAX request starts (before developer.js's submitHandler)
                    $(document).ajaxSend(function(event, jqXHR, ajaxSettings) {
                        const url = ajaxSettings?.url || '';
                        if (url.includes('/profile') || url.includes('profile.update')) {
                            showLoader();

                            // Set safety timeout
                            if (loaderTimeout) clearTimeout(loaderTimeout);
                            loaderTimeout = setTimeout(() => {
                                hideLoader();
                                if (typeof Lobibox !== 'undefined') {
                                    Lobibox.notify("error", {
                                        rounded: false,
                                        delay: 8000,
                                        delayIndicator: true,
                                        position: "top right",
                                        icon: 'fa fa-times-circle',
                                        msg: 'Request timed out. Please try again.',
                                    });
                                }
                            }, 30000);
                        }
                    });
                }

                const handleValidationErrors = (response) => {
                    if (!response || !response.errors) {
                        return false;
                    }

                    const fieldMapping = {
                        'name': 'name',
                        'email': 'email',
                        'timezone': 'timezone',
                        'profile_photo': 'profile_photo',
                        'current_password': 'current_password',
                        'password': 'password',
                        'password_confirmation': 'password_confirmation'
                    };

                    form.querySelectorAll('input, select').forEach(field => {
                        field.classList.remove('border-red-500');
                    });

                    Object.keys(response.errors).forEach(fieldName => {
                        const messages = response.errors[fieldName];
                        if (!Array.isArray(messages) || !messages.length) {
                            return;
                        }

                        const formFieldName = fieldMapping[fieldName] || fieldName;
                        const field = form.querySelector(`[name="${formFieldName}"]`) ||
                            form.querySelector(`#${formFieldName}`) ||
                            form.querySelector(`[name="${fieldName}"]`);

                        if (!field) {
                            return;
                        }

                        field.classList.add('border-red-500');
                        const errorContainer = field.closest('.ermsg') || field.closest('.flex.flex-col');
                        if (!errorContainer) {
                            return;
                        }

                        const existingErrors = errorContainer.querySelectorAll('.field-error-message');
                        existingErrors.forEach(err => err.remove());

                        const fieldLabels = {
                            'name': 'Name',
                            'email': 'Email Address',
                            'timezone': 'Timezone',
                            'profile_photo': 'Profile Photo',
                            'current_password': 'Current Password',
                            'password': 'New Password',
                            'password_confirmation': 'Confirm Password'
                        };

                        const label = fieldLabels[fieldName] || fieldName.replace(/_/g, ' ').replace(
                            /\b\w/g, l => l.toUpperCase());
                        const errorDiv = document.createElement('div');
                        errorDiv.className = 'text-red-500 text-sm field-error-message mt-1';
                        errorDiv.innerHTML = `<strong>${label}:</strong> ${messages[0]}`;
                        errorContainer.appendChild(errorDiv);
                    });

                    return true;
                };

                const handleSuccessResponse = (response = {}) => {
                    if (loaderTimeout) {
                        clearTimeout(loaderTimeout);
                        loaderTimeout = null;
                    }

                    hideLoader();

                    // Clear password fields on success
                    const currentPassword = form.querySelector('#current_password');
                    const password = form.querySelector('#password');
                    const passwordConfirmation = form.querySelector('#password_confirmation');
                    if (currentPassword) currentPassword.value = '';
                    if (password) password.value = '';
                    if (passwordConfirmation) passwordConfirmation.value = '';

                    // Reset delete photo flag
                    setDeletePhotoState(false);

                    if (response.reset === true) {
                        // Reset form if needed
                        form.reset();
                    }

                    if (response.reload === true) {
                        setTimeout(() => {
                            location.reload();
                        }, 2000);
                    }
                };

                // Intercept jQuery AJAX lifecycle for this form
                // developer.js uses ajaxSubmit() which triggers these events
                if (typeof jQuery !== 'undefined') {
                    const formAction = form.getAttribute('action') || '';

                    // Use ajaxComplete to catch all AJAX requests (including ajaxSubmit)
                    $(document).ajaxComplete(function(event, jqXHR, ajaxSettings) {
                        // Check if this is our profile update request
                        const url = ajaxSettings?.url || '';
                        if (!url.includes('/profile') && !url.includes('profile.update')) {
                            return;
                        }

                        // Clear timeout
                        if (loaderTimeout) {
                            clearTimeout(loaderTimeout);
                            loaderTimeout = null;
                        }

                        // Hide loader
                        hideLoader();

                        // Check if it was an error
                        if (jqXHR.status >= 400) {
                            try {
                                const response = JSON.parse(jqXHR.responseText || '{}');
                                handleValidationErrors(response);
                            } catch (error) {
                                // Errors are handled by developer.js Lobibox notifications
                            }
                        } else {
                            // Success - handle response
                            let responseData = {};
                            try {
                                responseData = jqXHR.responseJSON || JSON.parse(jqXHR.responseText || '{}');
                            } catch (error) {
                                responseData = {};
                            }
                            handleSuccessResponse(responseData);
                        }
                    });
                }
            });
        </script>
    @endsection
</x-dashboard-layout>
