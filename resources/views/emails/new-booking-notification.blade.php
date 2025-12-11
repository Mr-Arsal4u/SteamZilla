<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #dc2626; color: white; padding: 20px; text-align: center; border-radius: 8px 8px 0 0; }
        .content { background: #f9fafb; padding: 30px; border: 1px solid #e5e7eb; }
        .booking-details { background: white; padding: 20px; margin: 20px 0; border-radius: 8px; }
        .footer { text-align: center; padding: 20px; color: #6b7280; font-size: 12px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>New Booking Received</h1>
        </div>
        <div class="content">
            <p>A new booking has been submitted:</p>
            
            <div class="booking-details">
                <h2>Booking Details</h2>
                <p><strong>Booking ID:</strong> #{{ $booking->id }}</p>
                <p><strong>Customer:</strong> {{ $booking->user_name }}</p>
                <p><strong>Email:</strong> {{ $booking->user_email }}</p>
                <p><strong>Phone:</strong> {{ $booking->user_phone }}</p>
                <p><strong>Package:</strong> {{ $booking->package->name }}</p>
                <p><strong>Date:</strong> {{ $booking->booking_date->format('F j, Y') }}</p>
                <p><strong>Time:</strong> {{ \Carbon\Carbon::parse($booking->booking_time)->format('g:i A') }}</p>
                <p><strong>Address:</strong> {{ $booking->address }}</p>
                @if($booking->vehicle_type)
                    <p><strong>Vehicle Type:</strong> {{ $booking->vehicle_type }}</p>
                @endif
                <p><strong>Total Price:</strong> ${{ number_format($booking->total_price, 2) }}</p>
                <p><strong>Status:</strong> {{ ucfirst($booking->status) }}</p>
            </div>
            
            <p>Please review and confirm this booking in the admin panel.</p>
        </div>
        <div class="footer">
            <p>SteamZilla Admin Notification</p>
        </div>
    </div>
</body>
</html>

