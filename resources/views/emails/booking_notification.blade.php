<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $subject ?? 'Aditya Utsav Notification' }}</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #FFF8F0; margin: 0; padding: 20px; color: #1F1F1F; }
        .container { max-width: 600px; margin: 0 auto; background: #ffffff; border-radius: 16px; border: 1px solid #EADBCE; overflow: hidden; box-shadow: 0 4px 15px rgba(114, 0, 47, 0.05); }
        .header { background: linear-gradient(135deg, #800033 0%, #72002F 100%); padding: 30px 20px; text-align: center; color: #ffffff; }
        .header h1 { font-family: Georgia, serif; font-size: 24px; margin: 0 0 6px 0; color: #D4AF37; }
        .header p { margin: 0; font-size: 13px; letter-spacing: 0.08em; text-transform: uppercase; color: #FFF8F0; opacity: 0.9; }
        .content { padding: 30px 25px; }
        .badge { display: inline-block; padding: 4px 12px; background-color: #F8F1EA; color: #72002F; font-size: 11px; font-weight: bold; text-transform: uppercase; border-radius: 20px; border: 1px solid #D4AF37; margin-bottom: 15px; }
        .heading { font-family: Georgia, serif; font-size: 20px; color: #72002F; margin-top: 0; margin-bottom: 12px; }
        .message { font-size: 14px; line-height: 1.6; color: #4A4A4A; margin-bottom: 25px; }
        .details-box { background-color: #FFF8F0; border-radius: 12px; border: 1px solid #EADBCE; padding: 16px 20px; margin-bottom: 25px; }
        .details-row { display: flex; justify-content: space-between; padding: 6px 0; border-bottom: 1px dashed #EADBCE; font-size: 13px; }
        .details-row:last-child { border-bottom: none; }
        .details-label { color: #6B5E57; font-weight: 600; }
        .details-value { color: #1F1F1F; font-weight: bold; }
        .btn { display: inline-block; background-color: #72002F; color: #ffffff !important; text-decoration: none; padding: 12px 28px; font-size: 14px; font-weight: bold; border-radius: 30px; text-align: center; }
        .footer { background-color: #F8F1EA; padding: 20px; text-align: center; font-size: 12px; color: #6B5E57; border-top: 1px solid #EADBCE; }
        .footer p { margin: 4px 0; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>ADITYA UTSAV</h1>
            <p>Bihar Wedding Decoration & Event Services</p>
        </div>
        <div class="content">
            <span class="badge">{{ $title ?? 'Notification' }}</span>
            <h2 class="heading">{{ $heading ?? 'Namaste!' }}</h2>
            <p class="message">{{ $message }}</p>

            @if(isset($booking))
            <div class="details-box">
                <div class="details-row">
                    <span class="details-label">Booking Reference:</span>
                    <span class="details-value">{{ $booking->booking_reference }}</span>
                </div>
                <div class="details-row">
                    <span class="details-label">Event Date:</span>
                    <span class="details-value">{{ $booking->formatted_event_date ?? $booking->event_date }}</span>
                </div>
                <div class="details-row">
                    <span class="details-label">Setup Location:</span>
                    <span class="details-value">{{ $booking->city }}, {{ $booking->state }}</span>
                </div>
                <div class="details-row">
                    <span class="details-label">Status:</span>
                    <span class="details-value" style="color: #72002F;">{{ strtoupper($booking->status) }}</span>
                </div>
            </div>
            @endif

            @if(isset($actionUrl))
            <div style="text-align: center; margin: 30px 0;">
                <a href="{{ $actionUrl }}" class="btn">{{ $actionText ?? 'View Details' }}</a>
            </div>
            @endif
        </div>
        <div class="footer">
            <p><strong>{{ $business_name ?? 'Aditya Utsav' }}</strong></p>
            <p>{{ $business_address ?? 'Siwan, Bihar' }} • Phone: {{ $business_phone ?? '+91 98765 43210' }}</p>
            <p style="font-size: 11px; color: #8C7E77; margin-top: 10px;">This is an automated notification from Aditya Utsav Client Portal.</p>
        </div>
    </div>
</body>
</html>
