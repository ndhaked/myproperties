<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Contact Form Submission</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
    <div style="background-color: #f4f4f4; padding: 20px; border-radius: 5px;">
        <h2 style="color: #333; margin-top: 0;">New Contact Form Submission</h2>
        
        <div style="background-color: #fff; padding: 15px; border-radius: 5px; margin-bottom: 10px;">
            <p style="margin: 5px 0;"><strong>Name:</strong> {{ $data['full_name'] ?? 'N/A' }}</p>
        </div>
        
        <div style="background-color: #fff; padding: 15px; border-radius: 5px; margin-bottom: 10px;">
            <p style="margin: 5px 0;"><strong>Email:</strong> <a href="mailto:{{ $data['email'] ?? '' }}">{{ $data['email'] ?? 'N/A' }}</a></p>
        </div>
        
        <div style="background-color: #fff; padding: 15px; border-radius: 5px; margin-bottom: 10px;">
            <p style="margin: 5px 0;"><strong>Phone:</strong> {{ $data['phone'] ?? 'N/A' }}</p>
        </div>
        
        <div style="background-color: #fff; padding: 15px; border-radius: 5px; margin-bottom: 10px;">
            <p style="margin: 5px 0;"><strong>Subject:</strong> {{ $data['subject'] ?? 'N/A' }}</p>
        </div>
        
        <div style="background-color: #fff; padding: 15px; border-radius: 5px; margin-bottom: 10px;">
            <p style="margin: 5px 0;"><strong>Message:</strong></p>
            <p style="margin: 10px 0; white-space: pre-wrap;">{{ $data['message'] ?? 'N/A' }}</p>
        </div>
    </div>
</body>
</html>
