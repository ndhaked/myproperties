<x-dashboard-layout>
    @section('title', "Settings | " . config('app.name'))
    <!-- Main Content -->
    <main class="main-content">
        <header class="content-header">
            <div style="display: flex; align-items: center; gap: 16px;">
                <button class="mobile-menu-toggle" id="mobileMenuToggle">
                    <div class="hamburger">
                        <span></span>
                        <span></span>
                        <span></span>
                    </div>
                </button>
                <h1 class="page-title">Settings</h1>
            </div>
        </header>

        <div class="content-body">
        <div class="artist-filter-tabs flex items-center space-x-2 overflow-x-auto settings-tab-bar">

{{-- Notifications Settings --}}
<button 
    class="filter-tab active data-tab="notifications" data-default="notifications">
    Notifications Settings 
</button>
</div>
            <div class="flex items-start space-x-2 mb-12 p-1">
                <form id="F_saveSettingsBtn" method="POST" action="{{ route('settings.update') }}" class="flex flex-col w-full max-w-4xl items-start gap-12">
                    @csrf
                    @method('patch')

                    <section
                        class="flex flex-col ml-6 mt-6 items-start gap-6 relative self-stretch w-full flex-[0_0_auto] notification-wrappar"
                    aria-labelledby="profile-details-heading"
                    >
                        <header
                            class="flex flex-col items-start justify-center gap-1.5 px-3 py-1 relative self-stretch w-full flex-[0_0_auto] bg-[#e2f705] rounded-md"
                            style="background-color: #E84067;"
                        >
                            <div class="flex items-center justify-end gap-2 relative self-stretch w-full flex-[0_0_auto]">
                                <div class="flex flex-col items-start relative flex-1 grow">
                                    <div class="flex items-center gap-4 relative self-stretch w-full flex-[0_0_auto]">
                                        <div class="flex items-center gap-2 relative flex-1 self-stretch grow">
                                            <h1
                                            id="profile-details-heading"
                                                class="section-ribbon-title"
                                            >
                                          EMAIL NOTIFICATIONS
                                            </h1>
                                    </div>
                                    </div>
                                </div>
                            </div>
                        </header>

                        <!-- Email Field -->
                        <div class="flex items-start gap-8 relative self-stretch w-full flex-[0_0_auto] email-field">
                            <label
                                for="email"
                                class="relative w-[250px] color: section-email-address"
                            >
                                 Email Address*
                                 <br>
                                 <span class="section-email-address-label">
                                    This  email address  will be used for the  notifications and  updates.
                                 </span>
                            </label>
                            <div class="flex flex-col items-start gap-2 relative flex-1 grow shadow-shadow-XS email-input-field">
                                <div
                                    class="flex items-center gap-2 px-3 py-1.5 relative self-stretch w-full flex-[0_0_auto] bg-white rounded border border-solid border-[#dddddd]"
                                >
                                    <div class="flex flex-col items-start justify-center relative flex-1 grow ermsg">

                                        <input
                                            type="email"
                                            id="notification_email"
                                            name="notification_email"
                                            title="Please enter email address"
                                            value="{{ old('notification_email', $setting->notification_email ?? $user->email) }}"
                                            class="relative flex items-center justify-center self-stretch -mt-0.5 font-typography-paragraph-base-regular font-[number:var(--typography-paragraph-base-regular-font-weight)] text-[#1c1b1b] text-[length:var(--typography-paragraph-base-regular-font-size)] tracking-[var(--typography-paragraph-base-regular-letter-spacing)] leading-[var(--typography-paragraph-base-regular-line-height)] [font-style:var(--typography-paragraph-base-regular-font-style)] border-0 bg-transparent p-0 m-0 outline-none w-full"
                                            aria-label="Email Address"
                                            required
                                        />
                                    </div>
                                </div>

                            </div>
                        </div>

                    </section>

                    <!-- Save Button -->
                    
                </form>
            </div>
        </div>
        <div class="sticky bottom-0 bg-[#FAFAFA] p-4 border-t border-gray-200 w-full flex flex-col md:flex-row justify-end items-end gap-4 z-10">
                        <button type="submit" id="saveSettingsBtn" class="btn portal-btn-primary-small directSubmit" form="F_saveSettingsBtn" value="save">
                            <span id="save-settings-text">Save Changes</span>
                            <span id="save-settings-progress" class="hidden text-sm tracking-wide uppercase">Processing...</span>
                        </button>
                    </div>
    </main>

@section('uniquePageScript')
    <script>
document.addEventListener('DOMContentLoaded', function () {
    // ===== Full email-only JS =====

    // Elements
    const form = document.getElementById('F_saveSettingsBtn');
    const emailField = document.getElementById('notification_email')
                     || document.querySelector('input[name="notification_email"]');
    const submitButton = document.getElementById('saveSettingsBtn');
    const submitButtonText = document.getElementById('save-settings-text');
    const submitButtonProgress = document.getElementById('save-settings-progress');

    if (!form || !emailField) {
        // nothing to do if the form or email field is missing
        return;
    }

    // Utility: clear displayed errors for a field
    const clearFieldError = (field) => {
        if (!field) return;
        field.classList.remove('border-red-500');
        const errorContainer = field.closest('.ermsg') || field.closest('.flex.flex-col') || field.parentElement;
        if (errorContainer) {
            const fieldErrors = errorContainer.querySelectorAll('.field-error-message');
            fieldErrors.forEach(err => err.remove());
        }
    };

    // Email format check
    const isValidEmail = (value) => {
        if (!value) return false;
        const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailPattern.test(value.trim());
    };

    // Validate the email field and show errors (returns array of messages)
    const validateEmailField = (field) => {
        const errors = [];
        const v = (field.value || '').trim();
        if (!v) {
            errors.push('Email Address is required.');
        } else if (!isValidEmail(v)) {
            errors.push('Please enter a valid email address.');
        }
        return errors;
    };

    // Loader control
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

    // Show validation error on the field (first message)
    const showEmailError = (field, message) => {
        if (!field) return;
        field.classList.add('border-red-500');
        const errorContainer = field.closest('.ermsg') || field.closest('.flex.flex-col') || field.parentElement;
        if (!errorContainer) return;

        // remove existing
        const existing = errorContainer.querySelectorAll('.field-error-message');
        existing.forEach(e => e.remove());

        const errorDiv = document.createElement('div');
        errorDiv.className = 'text-red-500 text-sm field-error-message mt-1';
        // Remove "Email Address:" prefix to avoid duplication with Lobibox
        errorDiv.textContent = message;
        errorContainer.appendChild(errorDiv);
    };

    // Exposed: validate before submit (returns true if valid)
    window.validateEmailBeforeSubmit = function () {
        clearFieldError(emailField);
        const errors = validateEmailField(emailField);
        if (errors.length === 0) {
            return true;
        }
        showEmailError(emailField, errors[0]);
        return false;
    };

    // Handler to map server-side validation response to the email field
    // Expected shape: { errors: { email: ['msg'], notification_email: ['msg'] } }
    window.handleEmailValidationFromServer = function (response) {
        if (!response || !response.errors) return false;
        const possibleKeys = ['notification_email', 'email'];
        for (let key of possibleKeys) {
            if (!response.errors[key]) continue;
            const messages = response.errors[key];
            if (!Array.isArray(messages) || !messages.length) continue;
            const field = document.querySelector(`[name="${key}"]`) || document.getElementById(key) || emailField;
            showEmailError(field, messages[0]);
            // Mark that we've handled this error to prevent duplicate messages
            response._errorHandled = true;
            return true;
        }
        return false;
    };

    // Clear errors while typing/changing
    emailField.addEventListener('input', function () {
        clearFieldError(this);
    });
    emailField.addEventListener('change', function () {
        clearFieldError(this);
    });

    // Keep developer.js for form submission, but intercept errors to prevent duplicates

    // Intercept developer.js AJAX calls to show field errors and prevent duplicate Lobibox messages
    if (typeof jQuery !== 'undefined') {
        // Hook into ajaxError to catch errors before developer.js processes them
        $(document).ajaxError(function(event, jqXHR, ajaxSettings, thrownError) {
            const url = (ajaxSettings && ajaxSettings.url) ? ajaxSettings.url || '' : '';
            if (!url.includes('settings.update') && !url.includes('/settings')) {
                return; // not our request
            }

            // Only handle validation errors (422 status)
            if (jqXHR.status === 422) {
                let response = null;
                try {
                    response = jqXHR.responseJSON || JSON.parse(jqXHR.responseText || '{}');
                } catch (e) {
                    response = null;
                }

                if (response && response.errors) {
                    // Show error in field
                    if (window.handleEmailValidationFromServer(response)) {
                        // Mark that we've handled this error
                        jqXHR._errorHandled = true;
                        // Clear the message from response to prevent developer.js from showing Lobibox
                        if (response.message) {
                            response.message = '';
                        }
                        // Update the response in jqXHR
                        try {
                            jqXHR.responseJSON = response;
                            jqXHR.responseText = JSON.stringify(response);
                        } catch (e) {
                            // Ignore JSON errors
                        }
                    }
                }
            }
        });

        // Handle completion - close any duplicate Lobibox notifications for validation errors
        $(document).ajaxComplete(function(event, jqXHR, ajaxSettings) {
            const url = (ajaxSettings && ajaxSettings.url) ? ajaxSettings.url || '' : '';
            if (!url.includes('settings.update') && !url.includes('/settings')) {
                return; // not our request
            }

            // hide loader
            hideLoader();

            // If it was a validation error we already handled, close any Lobibox that appeared
            if (jqXHR._errorHandled || jqXHR.status === 422) {
                // Close any Lobibox notifications immediately
                setTimeout(function() {
                    if (typeof Lobibox !== 'undefined') {
                        $('.lobibox-notify').each(function() {
                            try {
                                const lobibox = $(this).data('lobibox');
                                if (lobibox && lobibox.close) {
                                    lobibox.close();
                                } else {
                                    $(this).remove();
                                }
                            } catch (e) {
                                $(this).remove();
                            }
                        });
                    }
                }, 50);
                return;
            }

            // success - clear email errors
            if (jqXHR.status >= 200 && jqXHR.status < 300) {
                clearFieldError(emailField);
            }
        });
    }

    // End email-only script
        });
    </script>
    @endsection
</x-dashboard-layout>
