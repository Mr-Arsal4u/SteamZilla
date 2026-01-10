# Test SendGrid Email - Quick Guide

## ✅ Your SendGrid Credentials

Your API key has been configured. Here's what you need to add to your `.env` file:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.sendgrid.net
MAIL_PORT=587
MAIL_USERNAME=apikey
MAIL_PASSWORD=SG.your_sendgrid_api_key_here
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@yourdomain.com
MAIL_FROM_NAME="SteamZilla"
MAIL_ADMIN_EMAIL=admin@yourdomain.com
```

**⚠️ Important:**
- Replace `noreply@yourdomain.com` with your verified SendGrid sender email
- Replace `admin@yourdomain.com` with your actual admin email address

## 🧪 Test Methods

### Method 1: Quick Test Script (Easiest)

```bash
php test-sendgrid.php your-email@example.com
```

This will send a test email immediately using your credentials.

### Method 2: Artisan Command

First, update your `.env` file with the credentials above, then:

```bash
php artisan config:clear
php artisan email:test-sendgrid your-email@example.com
```

### Method 3: Using Tinker

```bash
php artisan tinker
```

Then run:
```php
\App\Services\EmailService::sendTestEmail('your-email@example.com');
```

### Method 4: Test with Real Booking

If you have a booking in your database:

```bash
php artisan tinker
```

```php
$booking = \App\Models\Booking::first();
if ($booking) {
    \App\Services\EmailService::sendBookingEmails($booking);
    echo "Emails sent!";
}
```

## 📋 Step-by-Step Testing

1. **Update .env file** with the credentials above
2. **Clear config cache**: `php artisan config:clear`
3. **Run test**: `php test-sendgrid.php your-email@example.com`
4. **Check your email** (and spam folder)

## ✅ What to Expect

- ✅ Email should arrive within seconds
- ✅ Subject: "SteamZilla - SendGrid Test Email"
- ✅ Check spam folder if not in inbox
- ✅ Verify in SendGrid dashboard → Activity

## 🔍 Troubleshooting

### Email Not Received?

1. **Check Laravel Logs**
   ```bash
   tail -f storage/logs/laravel.log
   ```

2. **Verify SendGrid Dashboard**
   - Go to https://app.sendgrid.com/ → Activity
   - Check if email was sent
   - Look for bounces or blocks

3. **Verify Sender Email**
   - Go to Settings → Sender Authentication
   - Make sure your sender email is verified

4. **Check Spam Folder**
   - Emails might go to spam initially
   - Mark as "Not Spam" to improve deliverability

## 🎯 Next Steps After Testing

Once test email works:

1. ✅ Update `.env` with your actual sender email
2. ✅ Set `MAIL_ADMIN_EMAIL` to your admin email
3. ✅ Create a test booking to verify full flow
4. ✅ Check both customer and admin emails are sent

---

**Ready to test?** Run: `php test-sendgrid.php your-email@example.com`

