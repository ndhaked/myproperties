<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Artist Rejection Notification</title>
</head>
<body style="margin: 0; padding: 0; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif; background-color: #f5f5f5; line-height: 1.6;">
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="background-color: #f5f5f5; padding: 40px 20px;">
        <tr>
            <td align="center">
                <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="600" style="max-width: 600px; background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                    <!-- Header -->
                    <tr>
                        <td style="background: linear-gradient(135deg, #1c1b1b 0%, #2d2d2d 100%); padding: 40px 30px; text-align: center;">
                            <h1 style="margin: 0; color: #ffffff; font-size: 28px; font-weight: 600; letter-spacing: 0.5px;">New Artist Rejected</h1>
                            <p style="margin: 10px 0 0 0; color: #e0e0e0; font-size: 14px; font-weight: 300;">Artist Rejected</p>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 40px 30px;">
                            <p style="margin: 0 0 20px 0; color: #333333; font-size: 16px;">Hello {{ ucwords($artist->user->name) }},</p>
                            <p style="margin: 0 0 30px 0; color: #666666; font-size: 15px;">Your artist has been rejected due to incomplete information:</p>

                            <!-- Details Table -->
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin-bottom: 30px;">
                                <tr>
                                    <td style="padding: 12px 0; border-bottom: 1px solid #e5e5e5;">
                                        <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                                            <tr>
                                                <td style="width: 140px; padding-right: 15px; vertical-align: top;">
                                                    <p style="margin: 0; color: #666666; font-size: 13px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">Artist Name</p>
                                                </td>
                                                <td style="vertical-align: top;">
                                                    <p style="margin: 0; color: #1c1b1b; font-size: 15px; font-weight: 500;">{{ $artist->FullNameAndBirthDeath }}</p>
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
                                                    <p style="margin: 0; color: #666666; font-size: 13px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">Status</p>
                                                </td>
                                                <td style="vertical-align: top;">
                                                    <p style="margin: 0; color: #1c1b1b; font-size: 15px; font-weight: 500;">Rejected</p>
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
                                                    <p style="margin: 0; color: #666666; font-size: 13px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">Rejected At</p>
                                                </td>
                                                <td style="vertical-align: top;">
                                                    <p style="margin: 0; color: #1c1b1b; font-size: 15px; font-weight: 500;">{{ $artist->reject_reason }}</p>
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
                                                    <p style="margin: 0; color: #666666; font-size: 13px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px;">Reject Reason</p>
                                                </td>
                                                <td style="vertical-align: top;">
                                                    <p style="margin: 0; color: #1c1b1b; font-size: 15px; font-weight: 500;">{{ optional($artist->rejected_at)->timezone(config('app.timezone'))->format('d M Y, h:i A') }}</p>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                
                            </table>

                            <!-- Footer Info -->
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #e5e5e5;">
                                <tr>
                                    <td style="padding: 0;">
                                        <p style="margin: 0 0 5px 0; color: #999999; font-size: 12px; text-align: center;">
                                            <strong style="color: #666666;">Rejected At:</strong> {{ optional($artist->rejected_at)->timezone(config('app.timezone'))->format('d M Y, h:i A') }}
                                        </p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #f8f9fa; padding: 25px 30px; text-align: center; border-top: 1px solid #e5e5e5;">
                            <p style="margin: 0; color: #666666; font-size: 13px;">Thanks,<br><strong style="color: #1c1b1b;">ADAI Platform</strong></p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
