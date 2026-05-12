@component('mail::message')
# Password Reset OTP

Your one-time password (OTP) for resetting your account password is:

@component('mail::panel')
**{{ $otp }}**
@endcomponent

This OTP will expire in **10 minutes**.

If you did not request a password reset, please ignore this email.

Thanks,<br>
{{ config('app.name') }}
@endcomponent
