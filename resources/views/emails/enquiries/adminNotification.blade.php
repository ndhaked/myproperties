<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Artwork Enquiry</title>
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
                            <img src="{{ url('assets/images/adai_logo_white_email.png') }}" alt="ADAI Logo"
                                style="height:28px; width:100px;" />
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
                                                <img src="{{ url('assets/images/artwork-email.svg') }}"
                                                    alt="ADAI Logo" style="height:36px; width:36px;" />
                                            </td>
                                        </tr>
                                    </table>
                                </span>


                            </div>

                            <!-- Title -->
                            <h2 style="margin:0 0 8px;font-size:28px;font-weight:700;color:#000000;">
                                New Artwork Enquiry
                            </h2>

                            <p style="margin:0 0 24px;font-size:18px;color:#555555; font-weight: 400;">
                                A user has submitted an enquiry for the artwork for
                                <strong>{{ @$enquiry->artwork->gallery->gallery_name ??'' }}</strong><br />
                                ({{ @$enquiry->artwork->gallery->primaryBranch->city ??''}}, {{ @$enquiry->artwork->gallery->primaryBranch->countries->country ??''}})
                            </p>
                            <!-- Artwork Card -->
                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0"
                                style="border:1px solid #e5e5e5;border-radius:6px;margin-bottom:24px;">
                                <tr>
                                    <td style="padding:12px;">
                                        <table role="presentation" width="100%" cellspacing="0" cellpadding="0"
                                            border="0">
                                            <tr>
                                                <td width="64" valign="top">
                                                    <img src="{{ @$enquiry->artwork->primaryImage->full_file_path}}"
                                                        alt="ADAI Logo"
                                                        style="height:56px; width:56px;border-radius:4px;display:block;" />

                                                </td>
                                                <td valign="top" style="padding-left:12px;text-align:left;">
                                                    <p style="margin:0;font-size:18px;color:#555555; font-weight: 700;">
                                                        {{ $enquiry->artwork->artist->firstname }} {{ $enquiry->artwork->artist->middlename }} {{ $enquiry->artwork->artist->lastname }}
                                                    </p>
                                                    <p style="margin:2px 0 0;font-size:18px;color:#555555; font-weight: 400;">
                                                       {{ @$enquiry->artwork->title ??'' }}, {{ @$enquiry->artwork->year_created ?? '' }}
                                                    </p>
                                                </td>
                                                <td align="right" valign="top">
                                                    <p style="margin:0;font-size:18px;color:#555555; font-weight: 700;">
                                                       {{ @$enquiry->artwork->gallery->gallery_name ??'' }}
                                                    </p>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            <!-- User Details -->
                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0"
                                style="text-align:left;">
                                <tr>
                                    <td style="padding:6px 0;color:#555555;width:120px; font-weight: 700; font-size: 18px;">User Name:</td>
                                    <td style="padding:6px 0;color:#555555; font-weight: 400; font-size: 18px;">{{ $enquiry->name }}</td>
                                </tr>
                                <tr>
                                    <td style="padding:6px 0;color:#555555; font-weight: 700; font-size: 18px;">Email:</td>
                                    <td style="padding:6px 0;">
                                        <a href="mailto:email.address@gmail.com"
                                            style="color:#3c57c4;text-decoration:underline;  font-weight: 400; font-size: 18px;">
                                            {{ $enquiry->email }}
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding:6px 0;color:#555555; font-weight: 700; font-size: 18px;">Phone:</td>
                                    <td style="padding:6px 0;color:#555555; font-weight: 400; font-size: 18px;">{{ $enquiry->country_code }} {{ $enquiry->phone }}</td>
                                </tr>
                                <tr>
                                    <td style="padding:6px 0;color:#555555; font-weight: 700; font-size: 18px;">Message:</td>
                                    <td style="padding:6px 0;color:#555555; font-weight: 400; font-size: 18px;">{{ $enquiry->message }}</td>
                                </tr>
                            </table>
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
                            

                            <p style="margin:12px 0 0; font-size:11px; color:#9D9D9C;
font-weight: 600;">
                                © <?php echo date('Y')?> ADAI. All rights reserved.
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