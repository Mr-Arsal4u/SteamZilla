# SendGrid Email Setup - Step by Step Guide

## Why SendGrid? ⭐

**Best Choice for Your Project Because:**
- ✅ **Easiest setup** (15-20 minutes)
- ✅ **Free tier**: 100 emails/day (perfect for testing)
- ✅ **Great documentation** and Laravel support
- ✅ **Reliable deliverability** for USA
- ✅ **No credit card required** for free tier
- ✅ **Simple SMTP integration** (no special packages needed)

---

## Step-by-Step Setup

### Step 1: Create SendGrid Account (5 minutes)

1. Go to https://sendgrid.com/
2. Click **"Start for Free"**
3. Fill in your details:
   - Email address
   - Password
   - Company name (optional)
4. **Verify your email** (check your inbox)
5. Complete the setup wizard

### Step 2: Verify Your Sender Identity (5 minutes)

**Option A: Single Sender Verification (Easiest - for testing)**
1. Go to **Settings** → **Sender Authentication**
2. Click **"Verify a Single Sender"**
3. Fill in the form:
   - **From Email**: `noreply@yourdomain.com` (or your email)
   - **From Name**: `SteamZilla`
   - **Reply To**: `admin@yourdomain.com`
   - **Company Address**: Your business address
   - **Website**: Your website URL
4. Click **"Create"**
5. **Check your email** and click the verification link

**Option B: Domain Authentication (Recommended for production)**
1. Go to **Settings** → **Sender Authentication**
2. Click **"Authenticate Your Domain"**
3. Select your DNS provider
4. Add the DNS records provided by SendGrid to your domain
5. Wait for verification (usually 5-10 minutes)

### Step 3: Create API Key (3 minutes)

1. Go to **Settings** → **API Keys**
2. Click **"Create API Key"**
3. Give it a name: `SteamZilla Laravel App`
4. Select **"Full Access"** (or **"Restricted Access"** with Mail Send permission)
5. Click **"Create & View"**
6. **⚠️ IMPORTANT**: Copy the API key immediately (you won't see it again!)
   - It looks like: `SG.xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx`

### Step 4: Configure Laravel (2 minutes)

Add these lines to your `.env` file:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.sendgrid.net
MAIL_PORT=587
MAIL_USERNAME=apikey
MAIL_PASSWORD=SG.xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@yourdomain.com
MAIL_FROM_NAME="SteamZilla"
MAIL_ADMIN_EMAIL=admin@yourdomain.com
```

**Important Notes:**
- `MAIL_USERNAME` must be exactly: `apikey` (not your email!)
- `MAIL_PASSWORD` is your API key from Step 3
- Replace `noreply@yourdomain.com` with your verified sender email
- Replace `admin@yourdomain.com` with your admin email

### Step 5: Clear Laravel Cache (1 minute)

```bash
php artisan config:clear
php artisan config:cache
```

### Step 6: Test It! (2 minutes)

```bash
php artisan tinker
```

Then run:
```php
// Test email to yourself
$booking = \App\Models\Booking::first();
if ($booking) {
    \Illuminate\Support\Facades\Mail::to('your-email@example.com')
        ->send(new \App\Mail\BookingConfirmation($booking));
    echo "Email sent! Check your inbox.";
} else {
    echo "No bookings found. Create a test booking first.";
}
```

---

## Pricing

### Free Tier (Perfect for Testing)
- **100 emails/day**
- **No credit card required**
- **Full features**

### Paid Plans (When you grow)
- **Essentials**: $19.95/month for 50,000 emails
- **Pro**: $89.95/month for 100,000 emails
- **Advanced**: Custom pricing

**You can stay on free tier until you exceed 100 emails/day!**

---

## Troubleshooting

### Email Not Sending?

1. **Check Laravel Logs**
   ```bash
   tail -f storage/logs/laravel.log
   ```

2. **Verify API Key**
   - Make sure `MAIL_USERNAME=apikey` (exactly like this)
   - Make sure API key is correct (starts with `SG.`)

3. **Check Sender Verification**
   - Go to SendGrid → Settings → Sender Authentication
   - Make sure your sender is verified (green checkmark)

4. **Test Connection**
   ```bash
   php artisan tinker
   ```
   ```php
   \Illuminate\Support\Facades\Mail::raw('Test', function($msg) {
       $msg->to('your-email@example.com')->subject('Test Email');
   });
   ```

### Common Errors

**Error: "Authentication failed"**
- Check that `MAIL_USERNAME=apikey` (not your email)
- Verify API key is correct

**Error: "Sender not verified"**
- Verify your sender email in SendGrid dashboard
- Wait a few minutes after verification

**Error: "Rate limit exceeded"**
- You've exceeded 100 emails/day on free tier
- Wait 24 hours or upgrade to paid plan

---

## Production Checklist

Before going live:
- [ ] Domain authenticated (not just single sender)
- [ ] Test emails sent and received
- [ ] Admin email configured
- [ ] Email templates tested
- [ ] Error logging configured
- [ ] Monitoring set up (optional)

---

## Support

- **SendGrid Docs**: https://docs.sendgrid.com/
- **Laravel Mail Docs**: https://laravel.com/docs/mail
- **SendGrid Support**: Available in dashboard

---

## Next Steps

Once email is working:
1. Set up SMS notifications (see `EMAIL_SMS_SETUP.md`)
2. Test booking flow end-to-end
3. Monitor email delivery in SendGrid dashboard

**That's it! You're ready to send emails! 🎉**

