# Quick Start: Email & SMS Setup

## 🚀 Fastest Setup (Recommended for USA)

### Email: SendGrid (15 minutes)
1. Sign up at https://sendgrid.com
2. Verify your email address
3. Create API Key → Copy it
4. Add to `.env`:
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.sendgrid.net
MAIL_PORT=587
MAIL_USERNAME=apikey
MAIL_PASSWORD=your_sendgrid_api_key_here
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@yourdomain.com
MAIL_FROM_NAME="SteamZilla"
MAIL_ADMIN_EMAIL=admin@yourdomain.com
```

### SMS: Twilio (15 minutes)
1. Sign up at https://www.twilio.com
2. Get a phone number ($1/month)
3. Copy Account SID and Auth Token
4. Add to `.env`:
```env
SMS_PROVIDER=twilio
SMS_ADMIN_PHONE=+14135551234
TWILIO_ACCOUNT_SID=your_account_sid
TWILIO_AUTH_TOKEN=your_auth_token
TWILIO_FROM_NUMBER=+1234567890
```

### Test It
```bash
php artisan config:clear
php artisan tinker
```

Then in tinker:
```php
// Test email
$booking = \App\Models\Booking::first();
\Illuminate\Support\Facades\Mail::to('your-email@example.com')
    ->send(new \App\Mail\BookingConfirmation($booking));

// Test SMS
\App\Services\SmsService::send('+14135551234', 'Test SMS from SteamZilla');
```

## ✅ Done!

For detailed setup instructions, see `EMAIL_SMS_SETUP.md`

