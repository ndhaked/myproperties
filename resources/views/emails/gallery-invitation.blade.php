<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gallery Invitation - {{ $galleryName }}</title>
</head>
<body style="margin: 0; padding: 0; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif; background-color: #f5f5f5; line-height: 1.6;">
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="background-color: #f5f5f5; padding: 40px 20px;">
        <tr>
            <td align="center">
                <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="600" style="max-width: 600px; background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                    <img src="{{URL::to('assets/images/adai-admin/adai-logo.svg')}}" alt="ADAI Logo" class="" style="display:flex; justify-content:center; margin-bottom:20px;">
                    <tr>
                          <td style="
                            /* 1. Background Color (Fallback) */
                            background-color: #1c1b1b;

                            /* 2. Background Image: Gradient (Top) + Logo (Bottom) 
                               Notice the 'rgba' - 0.85 means 85% solid, 15% transparent */
                            background-image: 
                                linear-gradient(135deg, rgba(28, 27, 27, 0.85) 0%, rgba(45, 45, 45, 0.85) 100%),
                                url('{{ asset('assets/images/adai_logo_white_email.png') }}');

                            /* 3. Position: Center both layers */
                            background-position: center center, center center;

                            /* 4. Repeat: Don't repeat either */
                            background-repeat: no-repeat, no-repeat;

                            /* 5. Size: Gradient stretches (cover), Logo stays small (150px) */
                            background-size: cover, 312px auto;

                            padding: 40px 30px;
                            text-align: center;
                            ">
                            <h1 style="margin: 0; color: #ffffff; font-size: 28px; font-weight: 600; letter-spacing: 0.5px;">Gallery Invitation</h1>
                            <p style="margin: 10px 0 0 0; color: #e0e0e0; font-size: 14px; font-weight: 300;">You've been invited to join ADAI</p>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 40px 30px;">
                            <p style="margin: 0 0 20px 0; color: #333333; font-size: 16px;">Dear {{ $adminName }},</p>
                            <p style="margin: 0 0 20px 0; color: #666666; font-size: 15px;">You have been invited to join ADAI as the Gallery Admin for <strong>{{ $galleryName }}</strong>. Please click the button below to complete your onboarding and set up your gallery profile.</p>

                            <!-- Gallery Name Card -->
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: 30px 0; background-color: #f8f9fa; border-left: 4px solid #1c1b1b; border-radius: 4px;">
                                <tr>
                                    <td style="padding: 15px 20px;">
                                        <p style="margin: 0; color: #666666; font-size: 12px; text-transform: uppercase; letter-spacing: 1px; font-weight: 600;">Gallery Name</p>
                                        <p style="margin: 5px 0 0 0; color: #1c1b1b; font-size: 18px; font-weight: 700;">{{ $galleryName }}</p>
                                    </td>
                                </tr>
                            </table>

                            <!-- CTA Button -->
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: 30px 0;">
                                <tr>
                                    <td align="center" style="padding: 20px 0;">
                                        <a href="{{ $onboardingUrl }}" style="display: inline-block; padding: 14px 32px; background-color: #1c1b1b; color: #ffffff; text-decoration: none; border-radius: 6px; font-size: 16px; font-weight: 600; letter-spacing: 0.5px;">Complete Onboarding</a>
                                    </td>
                                </tr>
                            </table>

                            <!-- Link Expiry Notice -->
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: 30px 0; background-color: #fff8e1; border-left: 4px solid #ffc107; border-radius: 4px;">
                                <tr>
                                    <td style="padding: 20px;">
                                        <p style="margin: 0 0 10px 0; color: #1c1b1b; font-size: 14px; font-weight: 600;">⏰ Link Expiry</p>
                                        <p style="margin: 0; color: #666666; font-size: 14px;">This invitation link will expire on <strong>{{ $expiresAt->timezone(config('app.timezone'))->format('d M Y, h:i A') }}</strong> (7 days from now). Please complete your onboarding before this time.</p>
                                    </td>
                                </tr>
                            </table>

                            <!-- Alternative Link -->
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin-top: 20px; padding-top: 20px; border-top: 1px solid #e5e5e5;">
                                <tr>
                                    <td style="padding: 0;">
                                        <p style="margin: 0 0 10px 0; color: #666666; font-size: 13px;">If the button above doesn't work, copy and paste this link into your browser:</p>
                                        <p style="margin: 0; color: #1c1b1b; font-size: 13px; word-break: break-all;">
                                            <a href="{{ $onboardingUrl }}" style="color: #1c1b1b; text-decoration: underline;">{{ $onboardingUrl }}</a>
                                        </p>
                                    </td>
                                </tr>
                            </table>

                            <!-- Support Information -->
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #e5e5e5;">
                                <tr>
                                    <td style="padding: 0;">
                                        <p style="margin: 0 0 10px 0; color: #666666; font-size: 14px;">If you have any questions or need assistance, please contact us:</p>
                                        <p style="margin: 0; color: #1c1b1b; font-size: 15px;">
                                            <strong>Email:</strong> <a href="mailto:{{ config('mail.from.address', 'info@adai.art') }}" style="color: #1c1b1b; text-decoration: underline;">{{ config('mail.from.address', 'info@adai.art') }}</a>
                                        </p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #f8f9fa; padding: 25px 30px; text-align: center; border-top: 1px solid #e5e5e5;">
                            <p style="margin: 0 0 10px 0; color: #666666; font-size: 13px;">We look forward to welcoming you to the ADAI platform.</p>
                            <p style="margin: 0; color: #666666; font-size: 13px;">Best regards,<br><strong style="color: #1c1b1b;">The ADAI Team</strong></p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>

