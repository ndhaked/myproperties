<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thank you for your interest in ADAI</title>
</head>
<body style="margin: 0; padding: 0; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif; background-color: #f5f5f5; line-height: 1.6;">
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="background-color: #f5f5f5; padding: 40px 20px;">
        <tr>
            <td align="center">
                <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="600" style="max-width: 600px; background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                    <!-- Header -->
                    <tr>
                        <td style="background: linear-gradient(135deg, #1c1b1b 0%, #2d2d2d 100%); padding: 40px 30px; text-align: center;">
                            <h1 style="margin: 0; color: #ffffff; font-size: 28px; font-weight: 600; letter-spacing: 0.5px;">Thank You!</h1>
                            <p style="margin: 10px 0 0 0; color: #e0e0e0; font-size: 14px; font-weight: 300;">Your Registration of Interest Has Been Received</p>
                        </td>
                    </tr>

                    <!-- Content -->
                    <tr>
                        <td style="padding: 40px 30px;">
                            <p style="margin: 0 0 20px 0; color: #333333; font-size: 16px;">Dear {{ $user->gallery_name ?? $user->name }},</p>
                            <p style="margin: 0 0 20px 0; color: #666666; font-size: 15px;">Thank you for your interest in joining ADAI. We have successfully received your registration of interest and our team will review your submission shortly.</p>

                            <!-- Reference ID Card -->
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: 30px 0; background-color: #f8f9fa; border-left: 4px solid #1c1b1b; border-radius: 4px;">
                                <tr>
                                    <td style="padding: 15px 20px;">
                                        <p style="margin: 0; color: #666666; font-size: 12px; text-transform: uppercase; letter-spacing: 1px; font-weight: 600;">Your Reference ID</p>
                                        <p style="margin: 5px 0 0 0; color: #1c1b1b; font-size: 18px; font-weight: 700; font-family: 'Courier New', monospace;">{{ $reference }}</p>
                                        <p style="margin: 8px 0 0 0; color: #999999; font-size: 12px;">Please keep this reference ID for your records.</p>
                                    </td>
                                </tr>
                            </table>

                            <!-- Submitted Details -->
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin-bottom: 30px;">
                                <tr>
                                    <td style="padding-bottom: 15px;">
                                        <h2 style="margin: 0 0 20px 0; color: #1c1b1b; font-size: 18px; font-weight: 600;">Submitted Details</h2>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding: 12px 0; border-bottom: 1px solid #e5e5e5;">
                                        <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                                            <tr>
                                                <td style="width: 140px; padding-right: 15px; vertical-align: top;">
                                                    <p style="margin: 0; color: #666666; font-size: 13px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">Gallery Name</p>
                                                </td>
                                                <td style="vertical-align: top;">
                                                    <p style="margin: 0; color: #1c1b1b; font-size: 15px; font-weight: 500;">{{ $user->gallery_name ?? $user->name }}</p>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding: 12px 0; border-bottom: 1px solid #e5e5e5;">
                                        <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                                            <tr>
                                                <td style="width: 140px; padding-right: 15px; vertical-align: top;">
                                                    <p style="margin: 0; color: #666666; font-size: 13px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">Email</p>
                                                </td>
                                                <td style="vertical-align: top;">
                                                    <p style="margin: 0; color: #1c1b1b; font-size: 15px;">{{ $user->email }}</p>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding: 12px 0; border-bottom: 1px solid #e5e5e5;">
                                        <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                                            <tr>
                                                <td style="width: 140px; padding-right: 15px; vertical-align: top;">
                                                    <p style="margin: 0; color: #666666; font-size: 13px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">Phone</p>
                                                </td>
                                                <td style="vertical-align: top;">
                                                    <p style="margin: 0; color: #1c1b1b; font-size: 15px;">{{ $fullPhone }}</p>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding: 12px 0; border-bottom: 1px solid #e5e5e5;">
                                        <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                                            <tr>
                                                <td style="width: 140px; padding-right: 15px; vertical-align: top;">
                                                    <p style="margin: 0; color: #666666; font-size: 13px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">Location</p>
                                                </td>
                                                <td style="vertical-align: top;">
                                                    <p style="margin: 0; color: #1c1b1b; font-size: 15px;">{{ $user->city }}{{ optional($user->country) ? ', ' . $user->country->country : '' }}</p>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding: 12px 0; border-bottom: 1px solid #e5e5e5;">
                                        <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                                            <tr>
                                                <td style="width: 140px; padding-right: 15px; vertical-align: top;">
                                                    <p style="margin: 0; color: #666666; font-size: 13px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">Address</p>
                                                </td>
                                                <td style="vertical-align: top;">
                                                    <p style="margin: 0; color: #1c1b1b; font-size: 15px;">{{ $user->address }}</p>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                @if(!empty($user->external_links))
                                <tr>
                                    <td style="padding: 12px 0;">
                                        <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                                            <tr>
                                                <td style="width: 140px; padding-right: 15px; vertical-align: top;">
                                                    <p style="margin: 0; color: #666666; font-size: 13px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">Links</p>
                                                </td>
                                                <td style="vertical-align: top;">
                                                    @foreach($user->external_links as $link)
                                                        <p style="margin: 0 0 8px 0; color: #1c1b1b; font-size: 15px;">
                                                            <a href="{{ $link }}" style="color: #1c1b1b; text-decoration: underline; word-break: break-all;">{{ $link }}</a>
                                                        </p>
                                                    @endforeach
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                @endif
                            </table>

                            <!-- Expected Response Window -->
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: 30px 0; background-color: #fff8e1; border-left: 4px solid #ffc107; border-radius: 4px;">
                                <tr>
                                    <td style="padding: 20px;">
                                        <p style="margin: 0 0 10px 0; color: #1c1b1b; font-size: 14px; font-weight: 600;">⏱️ Expected Response Window</p>
                                        <p style="margin: 0; color: #666666; font-size: 14px;">Our team typically reviews submissions within <strong>3-5 business days</strong>. You will receive a follow-up email with next steps once your submission has been reviewed.</p>
                                    </td>
                                </tr>
                            </table>

                            <!-- Support Information -->
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #e5e5e5;">
                                <tr>
                                    <td style="padding: 0;">
                                        <p style="margin: 0 0 10px 0; color: #666666; font-size: 14px;">If you have any questions or need to update your submission, please contact us:</p>
                                        <p style="margin: 0; color: #1c1b1b; font-size: 15px;">
                                            <strong>Email:</strong> <a href="mailto:{{ $supportEmail }}" style="color: #1c1b1b; text-decoration: underline;">{{ $supportEmail }}</a>
                                        </p>
                                        <p style="margin: 10px 0 0 0; color: #999999; font-size: 12px;">
                                            <strong>Submitted:</strong> {{ optional($user->interest_submitted_at)->timezone(config('app.timezone'))->format('d M Y, h:i A') }}
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

