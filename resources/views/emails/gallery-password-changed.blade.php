<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Security Alert - {{ $galleryName }}</title>
</head>

<body style="margin: 0; padding: 0; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Arial; background-color: #f5f5f5; line-height: 1.6;">
<table width="100%" cellpadding="0" cellspacing="0" style="padding:40px 20px;">
<tr>
<td align="center">

<table width="600" style="background:#ffffff;border-radius:8px;overflow:hidden;box-shadow:0 2px 4px rgba(0,0,0,.1);">

<!-- Logo -->
<img src="{{ URL::to('assets/images/adai-admin/adai-logo.svg') }}"
     alt="ADAI Logo"
     style="display:block;margin:20px auto;">

<!-- Header -->
<tr>
<td style="
    background-color:#1c1b1b;
    background-image:
        linear-gradient(135deg, rgba(28,27,27,.85), rgba(45,45,45,.85)),
        url('{{ asset('assets/images/adai_logo_white_email.png') }}');
    background-repeat:no-repeat;
    background-position:center;
    background-size:cover,312px auto;
    padding:40px 30px;
    text-align:center;
">
<h1 style="margin: 0; color: #ffffff; font-size: 28px; font-weight: 600; letter-spacing: 0.5px;">Password Changed Successfully</h1>
</td>
</tr>

<!-- Content -->
<tr>
<td style="padding:40px 30px;">
<p style="font-size:16px;color:#333;">
    Dear {{ $adminName }},
</p>
<p style="font-size:15px;color:#666;">
   This is a confirmation that your account password was successfully changed.
</p>

<!-- Warning -->
{{-- <table width="100%" style="margin:30px 0;background:#fff8e1;border-left:4px solid #dc3545;">
<tr>
<td style="padding:20px;">
    <p style="margin:0;font-size:14px;color:#333;">
        ⚠️ If you did not make this change, please contact ADAI support immediately.
    </p>
</td>
</tr>
</table> --}}

{{-- <p style="font-size:14px;color:#666;">
    <strong>Date & Time:</strong>
    {{ $changedAt->format('d M Y, h:i A') }}
</p> --}}

<!-- CTA -->
{{-- <table width="100%" style="margin:30px 0;">
<tr>
<td align="center">
    <a href="{{ $loginUrl }}"
       style="display:inline-block;padding:14px 32px;background:#1c1b1b;
              color:#ffffff;text-decoration:none;border-radius:6px;
              font-size:16px;font-weight:600;">
        Go to Login
    </a>
</td>
</tr>
</table> --}}

<!-- Support -->
<p style="font-size:14px;color:#666;">
    If you did not request this change, Please reset your password immediately or contact the ADAI Support Team at 
    <a href="mailto:{{ config('mail.from.address') }}"
       style="color:#1c1b1b;text-decoration:underline;">
        {{ config('mail.from.address') }}
    </a>
</p>

</td>
</tr>

<!-- Footer -->
<tr>
<td style="background:#f8f9fa;padding:25px;text-align:center;border-top:1px solid #e5e5e5;">
    <p style="margin:0;color:#666;font-size:13px;">
        Kind regards,<br>
        <strong style="color:#1c1b1b;">The ADAI Team</strong>
    </p>
</td>
</tr>

</table>

</td>
</tr>
</table>
</body>
</html>
