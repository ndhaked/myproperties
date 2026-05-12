<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Newsletter Subscription Confirmation</title>
</head>

<body
    style="margin: 0; padding: 0; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif; background-color: #f9f9f9; line-height: 1.6;">
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%"
        style="background-color: #f9f9f9; padding: 40px 20px;">
        <tr>
            <td align="center">
                <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="600"
                    style="max-width: 640px; background-color: #ffffff; border-radius: 8px; overflow: hidden;">
                    <!-- Header -->
                    <tr>
                        <td style="background: #1c1b1b; padding: 10px 0 14px 0; text-align: center;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="128" height="36" viewBox="0 0 128 36"
                                fill="none">
                                <g clip-path="url(#clip0)">
                                    <path d="M110.02 36H128V-0.00159836H110.02V36Z" fill="white" />
                                    <path
                                        d="M99.3218 17.9991L89.8998 -0.00159836L80.91 17.727L71.9199 36H89.8998H107.88L99.3218 17.9991Z"
                                        fill="white" />
                                    <path
                                        d="M27.4018 17.9991L17.98 -0.00159836L8.99002 17.727L0 36H17.98H35.96L27.4018 17.9991Z"
                                        fill="white" />
                                    <path
                                        d="M56.0803 -0.00159836H38.1005V36H56.0803C66.0102 36 74.0602 27.9407 74.0602 17.9991C74.0602 8.0576 66.0102 -0.00159836 56.0803 -0.00159836Z"
                                        fill="white" />
                                </g>
                                <defs>
                                    <clipPath id="clip0">
                                        <rect width="128" height="36" fill="white" />
                                    </clipPath>
                                </defs>
                            </svg>

                        </td>
                    </tr>

                    <!-- Content -->
                    <tr>
                        <td style="padding:28px 25px 56px 26px; text-align:center;">

                            <!-- Icon -->
                            <div style="margin-bottom:16px;">
                                <span style="
    display: inline-block;
    width: 60px;
    height: 60px;
    border-radius: 999px;
    background-color: #f4f4f7;
  ">
                                    <table width="60" height="60" cellpadding="0" cellspacing="0" border="0"
                                        role="presentation">
                                        <tr>
                                            <td align="center" valign="middle">
                                                <img src="url('{{ asset('assets/images/newsletter-email.svg') }}')"
                                                    alt="ADAI Logo" style="height:36px; width:36px;" />

                                            </td>
                                        </tr>
                                    </table>
                                </span>


                            </div>

                            <!-- Title -->
                            <h2
                                style="margin:0 0 21px; font-size:28px; font-weight:700; color:#000000; line-height: 100%;">
                                You’re subscribed 🎉
                            </h2>

                            <!-- Message -->
                            <p style="margin:0 0 21px; font-size:18px; line-height:1.5; color:#555555; font-weight: 400;">
                                Thanks for subscribing to our newsletter.
                            </p>

                            <p style="margin:0 0 21px; font-size:18px; line-height:1.5; color:#555555; font-weight: 400;">
                                You’re now on the list and will start receiving updates, insights, and news from us
                                straight to your inbox.
                            </p>

                            <p style="margin:0 0 10px; font-size:18px; line-height:1.5; color:#555555; font-weight: 400;">
                                If you ever change your mind, you can unsubscribe at any time using the link at the
                                bottom of our emails.
                            </p>

                        </td>
                    </tr>

                    <!-- Divider -->
                    <tr>
                        <td style="padding:0 32px;">
                            <hr style="border:none; border-top:1px solid #dddddd;" />
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="padding:16px 32px 24px; text-align:center;">
                            <a href="{{ unsubscribe_url }}" style="font-size:13px; text-decoration:underline; font-weight: 400; color: #3C57C4;
">
                                Unsubscribe
                            </a>

                            <p style="margin:12px 0 0; font-size:11px; color:#9D9D9C;
font-weight: 600;">
                                © 2025 ADAI. All rights reserved.
                            </p>
                        </td>
                    </tr>

                </table>
                <!-- End Email Card -->

            </td>
        </tr>
    </table>

</body>

</html>