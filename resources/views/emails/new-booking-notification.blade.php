<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>New Booking Notification - SteamZilla</title>
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
            background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
            color: white; 
            padding: 40px 30px; 
            text-align: center; 
        }
        .header h1 { font-size: 28px; font-weight: 700; margin-bottom: 10px; }
        .header p { font-size: 16px; opacity: 0.95; }
        .content { padding: 40px 30px; }
        .alert-badge { 
            background: #fef2f2; 
            border-left: 4px solid #dc2626; 
            padding: 15px 20px; 
            margin-bottom: 30px; 
            border-radius: 6px; 
        }
        .alert-badge p { margin: 0; color: #991b1b; font-weight: 600; font-size: 16px; }
        .booking-details { 
            background: #f9fafb; 
            padding: 25px; 
            margin: 30px 0; 
            border-radius: 8px; 
            border-left: 4px solid #dc2626;
        }
        .booking-details h2 { 
            font-size: 20px; 
            color: #111827; 
            margin-bottom: 20px; 
            font-weight: 600;
        }
        .section-title { 
            font-size: 14px; 
            font-weight: 700; 
            color: #6b7280; 
            text-transform: uppercase; 
            letter-spacing: 0.5px; 
            margin-top: 25px; 
            margin-bottom: 15px; 
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
        .customer-info { 
            background: #eff6ff; 
            padding: 20px; 
            margin: 20px 0; 
            border-radius: 8px; 
            border-left: 4px solid #3b82f6;
        }
        .addons-section { margin-top: 20px; padding-top: 20px; border-top: 1px solid #e5e7eb; }
        .addon-item { padding: 8px 0; color: #4b5563; }
        .total-row { 
            margin-top: 20px; 
            padding-top: 20px; 
            border-top: 2px solid #dc2626; 
            font-size: 18px; 
            font-weight: 700; 
        }
        .total-row .detail-value { color: #dc2626; font-size: 20px; }
        .action-box { 
            background: #fef3c7; 
            border-left: 4px solid #f59e0b; 
            padding: 20px; 
            margin: 30px 0; 
            border-radius: 6px; 
        }
        .action-box p { margin: 0; color: #92400e; font-size: 15px; }
        .action-box a { 
            display: inline-block; 
            margin-top: 15px; 
            background: #f59e0b; 
            color: white; 
            padding: 12px 24px; 
            text-decoration: none; 
            border-radius: 6px; 
            font-weight: 600; 
        }
        .footer { 
            text-align: center; 
            padding: 30px; 
            background: #111827; 
            color: #9ca3af; 
            font-size: 13px; 
        }
        .footer p { margin: 5px 0; }
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
            <h1>🔔 New Booking Received</h1>
            <p>Action Required - Review Booking Details</p>
        </div>
        
        <div class="content">
            <div class="alert-badge">
                <p>⚠️ A new booking has been submitted and payment has been processed</p>
            </div>
            
            <div class="booking-details">
                <h2>Booking Summary</h2>
                
                <div class="detail-row">
                    <span class="detail-label">Booking ID:</span>
                    <span class="detail-value">#{{ str_pad($booking->id, 6, '0', STR_PAD_LEFT) }}</span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">Booking Status:</span>
                    <span class="detail-value" style="color: #16a34a; font-weight: 700;">{{ ucfirst($booking->status) }}</span>
                </div>
                
                <div class="detail-row">
                    <span class="detail-label">Payment Status:</span>
                    <span class="detail-value" style="color: #16a34a; font-weight: 700;">{{ ucfirst($booking->payment_status) }}</span>
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
                    <span class="detail-label">Total Amount:</span>
                    <span class="detail-value">${{ number_format($booking->total_price, 2) }}</span>
                </div>
            </div>
            
            <div class="section-title">Customer Information</div>
            <div class="customer-info">
                <div class="detail-row">
                    <span class="detail-label">Name:</span>
                    <span class="detail-value">{{ $booking->user_name }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Email:</span>
                    <span class="detail-value">
                        <a href="mailto:{{ $booking->user_email }}" style="color: #3b82f6; text-decoration: none;">{{ $booking->user_email }}</a>
                    </span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Phone:</span>
                    <span class="detail-value">
                        <a href="tel:{{ $booking->user_phone }}" style="color: #3b82f6; text-decoration: none;">{{ $booking->user_phone }}</a>
                    </span>
                </div>
                @if($booking->notes)
                <div style="margin-top: 15px; padding-top: 15px; border-top: 1px solid #bfdbfe;">
                    <div class="detail-label" style="margin-bottom: 8px;">Customer Notes:</div>
                    <div style="color: #1e40af; font-style: italic;">{{ $booking->notes }}</div>
                </div>
                @endif
            </div>
            
            @if($booking->square_payment_id)
            <div class="section-title">Payment Information</div>
            <div style="background: #f9fafb; padding: 15px; border-radius: 6px; margin-bottom: 20px;">
                <div class="detail-row">
                    <span class="detail-label">Payment Method:</span>
                    <span class="detail-value">{{ ucfirst($booking->payment_method) }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Transaction ID:</span>
                    <span class="detail-value" style="font-family: monospace; font-size: 12px;">{{ $booking->square_payment_id }}</span>
                </div>
                @if($booking->square_receipt_url)
                <div class="detail-row">
                    <span class="detail-label">Receipt:</span>
                    <span class="detail-value">
                        <a href="{{ $booking->square_receipt_url }}" target="_blank" style="color: #3b82f6; text-decoration: none;">View Receipt</a>
                    </span>
                </div>
                @endif
            </div>
            @endif
            
            <div class="action-box">
                <p><strong>Action Required:</strong> Please review this booking in the admin panel and confirm the appointment with the customer.</p>
                <a href="{{ route('admin.bookings.show', $booking->id) }}">View Booking in Admin Panel →</a>
            </div>
        </div>
        
        <div class="footer">
            <p><strong>SteamZilla</strong> - Admin Notification System</p>
            <p>This is an automated notification. Please review the booking in your admin dashboard.</p>
        </div>
    </div>
</body>
</html>

