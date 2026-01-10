<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Booking Confirmation - SteamZilla</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { 
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            line-height: 1.6; 
            color: #1f2937; 
            background-color: #f3f4f6;
            padding: 20px 0;
        }
        .email-wrapper { max-width: 600px; margin: 0 auto; background-color: #ffffff; }
        .header { 
            background: linear-gradient(135deg, #16a34a 0%, #15803d 100%);
            color: white; 
            padding: 40px 30px; 
            text-align: center; 
        }
        .header h1 { font-size: 28px; font-weight: 700; margin-bottom: 10px; }
        .header p { font-size: 16px; opacity: 0.95; }
        .content { padding: 40px 30px; }
        .greeting { font-size: 18px; color: #1f2937; margin-bottom: 20px; }
        .message { font-size: 16px; color: #4b5563; margin-bottom: 30px; line-height: 1.8; }
        .booking-details { 
            background: #f9fafb; 
            padding: 25px; 
            margin: 30px 0; 
            border-radius: 8px; 
            border-left: 4px solid #16a34a;
        }
        .booking-details h2 { 
            font-size: 20px; 
            color: #111827; 
            margin-bottom: 20px; 
            font-weight: 600;
        }
        .detail-row { 
            display: flex; 
            justify-content: space-between; 
            padding: 12px 0; 
            border-bottom: 1px solid #e5e7eb; 
        }
        .detail-row:last-child { border-bottom: none; }
        .detail-label { font-weight: 600; color: #374151; }
        .detail-value { color: #1f2937; text-align: right; }
        .addons-section { margin-top: 20px; padding-top: 20px; border-top: 1px solid #e5e7eb; }
        .addon-item { padding: 8px 0; color: #4b5563; }
        .total-row { 
            margin-top: 20px; 
            padding-top: 20px; 
            border-top: 2px solid #16a34a; 
            font-size: 18px; 
            font-weight: 700; 
        }
        .total-row .detail-value { color: #16a34a; font-size: 20px; }
        .info-box { 
            background: #eff6ff; 
            border-left: 4px solid #3b82f6; 
            padding: 20px; 
            margin: 30px 0; 
            border-radius: 6px; 
        }
        .info-box p { margin: 0; color: #1e40af; font-size: 15px; }
        .contact-info { 
            background: #f9fafb; 
            padding: 25px; 
            margin: 30px 0; 
            border-radius: 8px; 
            text-align: center; 
        }
        .contact-info p { margin: 8px 0; color: #4b5563; }
        .contact-info a { color: #16a34a; text-decoration: none; font-weight: 600; }
        .footer { 
            text-align: center; 
            padding: 30px; 
            background: #111827; 
            color: #9ca3af; 
            font-size: 13px; 
        }
        .footer p { margin: 5px 0; }
        .footer a { color: #60a5fa; text-decoration: none; }
        @media only screen and (max-width: 600px) {
            .content { padding: 25px 20px; }
            .header { padding: 30px 20px; }
            .detail-row { flex-direction: column; }
            .detail-value { text-align: left; margin-top: 5px; }
        }
    </style>
</head>
<body>
    <div class="email-wrapper">
        <div class="header">
            <h1>✓ Booking Confirmed!</h1>
            <p>Your appointment is scheduled</p>
        </div>
        
        <div class="content">
            <div class="greeting">Dear {{ $booking->user_name }},</div>
            
            <div class="message">
                Thank you for choosing <strong>SteamZilla</strong>! We're excited to serve you. Your booking has been successfully confirmed and payment has been processed.
            </div>
            
            <div class="booking-details">
                <h2>Booking Information</h2>
                
                <div class="detail-row">
                    <span class="detail-label">Booking ID:</span>
                    <span class="detail-value">#{{ str_pad($booking->id, 6, '0', STR_PAD_LEFT) }}</span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">Service Package:</span>
                    <span class="detail-value">{{ $booking->package->name }}</span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">Appointment Date:</span>
                    <span class="detail-value">{{ $booking->booking_date->format('l, F j, Y') }}</span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">Appointment Time:</span>
                    <span class="detail-value">{{ \Carbon\Carbon::parse($booking->booking_time)->format('g:i A') }}</span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">Service Address:</span>
                    <span class="detail-value" style="max-width: 60%; word-break: break-word;">{{ $booking->address }}</span>
                </div>
                
                @if($booking->vehicle_type)
                <div class="detail-row">
                    <span class="detail-label">Vehicle Type:</span>
                    <span class="detail-value">{{ ucfirst($booking->vehicle_type) }}</span>
                </div>
                @endif
                
                @if($booking->bookingAddons && $booking->bookingAddons->count() > 0)
                <div class="addons-section">
                    <div class="detail-label" style="margin-bottom: 10px;">Additional Services:</div>
                    @foreach($booking->bookingAddons as $bookingAddon)
                    <div class="addon-item">
                        • {{ $bookingAddon->addon->name }} 
                        @if($bookingAddon->quantity > 1)
                            (Qty: {{ $bookingAddon->quantity }})
                        @endif
                        - ${{ number_format($bookingAddon->price_at_booking * $bookingAddon->quantity, 2) }}
                    </div>
                    @endforeach
                </div>
                @endif
                
                <div class="total-row detail-row">
                    <span class="detail-label">Total Amount Paid:</span>
                    <span class="detail-value">${{ number_format($booking->total_price, 2) }}</span>
                </div>
            </div>
            
            @if($booking->notes)
            <div class="info-box">
                <p><strong>Your Notes:</strong> {{ $booking->notes }}</p>
            </div>
            @endif
            
            <div class="info-box">
                <p><strong>What's Next?</strong> Our team will contact you within 24 hours to confirm your appointment details and answer any questions you may have.</p>
            </div>
            
            <div class="contact-info">
                <p><strong>Need to make changes or have questions?</strong></p>
                <p>Email: <a href="mailto:mrzilla89@thesteamzilla.com">mrzilla89@thesteamzilla.com</a></p>
                <p>Phone: <a href="tel:+14133529444">(413) 352-9444</a></p>
            </div>
            
            <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #e5e7eb;">
                <p style="color: #4b5563; margin-bottom: 10px;">Best regards,</p>
                <p style="color: #1f2937; font-weight: 600; font-size: 18px;">The SteamZilla Team</p>
            </div>
        </div>
        
        <div class="footer">
            <p><strong>SteamZilla</strong> - Professional Car Steam Cleaning Services</p>
            <p>mrzilla89@thesteamzilla.com | (413) 352-9444</p>
            <p style="margin-top: 15px; font-size: 12px; color: #6b7280;">
                This is an automated confirmation email. Please do not reply to this message.
            </p>
        </div>
    </div>
</body>
</html>

