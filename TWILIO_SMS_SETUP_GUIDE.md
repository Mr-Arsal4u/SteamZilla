# Twilio SMS Setup - Step by Step Guide

## Why Twilio? ⭐

**Best Choice for Your Project Because:**
- ✅ **Easiest setup** (15-20 minutes)
- ✅ **Most reliable** SMS service in USA
- ✅ **Excellent documentation** and Laravel support
- ✅ **Free trial**: $15.50 credit (enough for ~2,000 SMS)
- ✅ **Simple API integration** (already implemented!)
- ✅ **No special packages needed** (uses HTTP client)

---

## Step-by-Step Setup

### Step 1: Create Twilio Account (5 minutes)

1. Go to https://www.twilio.com/
2. Click **"Sign up"** (top right)
3. Fill in your details:
   - Email address
   - Password
   - Full name
   - Phone number (for verification)
4. **Verify your phone number** (they'll send you a code)
5. Complete the account setup

**🎁 You'll get $15.50 free credit!** (enough for ~2,000 SMS messages)

### Step 2: Get a Phone Number (5 minutes)

1. After logging in, you'll see the **Twilio Console Dashboard**
2. Click **"Get a number"** (or go to **Phone Numbers** → **Manage** → **Buy a number**)
3. Select your preferences:
   - **Country**: United States
   - **Type**: Local (or Toll-Free)
   - **Capabilities**: Check **"SMS"** ✅
4. Click **"Search"**
5. Choose a number from the list (usually $1/month)
6. Click **"Buy"** and confirm

**Note**: 
- Local numbers: ~$1/month
- Toll-free numbers: ~$2/month
- You only pay monthly fee, not per SMS on the number itself

### Step 3: Get Your API Credentials (3 minutes)

1. In the Twilio Console Dashboard, you'll see:
   - **Account SID**: Starts with `AC...`
   - **Auth Token**: Click "View" to reveal it
2. **Copy both** - you'll need them for `.env`
3. Also note your **phone number** (format: +1234567890)

**⚠️ Important**: 
- Keep your Auth Token secret!
- Don't commit it to Git
- You can regenerate it if needed

### Step 4: Configure Laravel (2 minutes)

Add these lines to your `.env` file:

```env
SMS_PROVIDER=twilio
SMS_ADMIN_PHONE=+14135551234

TWILIO_ACCOUNT_SID=ACxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
TWILIO_AUTH_TOKEN=your_auth_token_here
TWILIO_FROM_NUMBER=+1234567890
```

**Important Notes:**
- `SMS_ADMIN_PHONE`: Your phone number where you want to receive notifications
  - Format: `+1` followed by 10 digits (e.g., `+14135551234`)
  - Include the `+1` country code!
- `TWILIO_FROM_NUMBER`: The Twilio number you just purchased
  - Format: `+1234567890` (with country code)
- Replace all placeholder values with your actual credentials

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
// Test SMS to yourself
\App\Services\SmsService::send('+14135551234', 'Test SMS from SteamZilla! 🚗');

// Or test with a booking
$booking = \App\Models\Booking::first();
if ($booking) {
    \App\Services\SmsService::sendBookingNotification($booking);
    echo "SMS sent! Check your phone.";
} else {
    echo "No bookings found. Create a test booking first.";
}
```

**You should receive the SMS within seconds!** 📱

---

## Pricing

### Free Trial
- **$15.50 free credit** when you sign up
- Enough for approximately **2,000 SMS messages**
- No credit card required initially

### Pay-As-You-Go Pricing
- **$0.0075 per SMS** in USA
- **$0.75 per 100 messages**
- **$7.50 per 1,000 messages**

### Monthly Costs
- **Phone number**: ~$1/month (local) or ~$2/month (toll-free)
- **SMS**: Pay only for what you use

### Example Costs
- **10 SMS/day** = ~$2.25/month + $1 (number) = **$3.25/month**
- **50 SMS/day** = ~$11.25/month + $1 (number) = **$12.25/month**
- **100 SMS/day** = ~$22.50/month + $1 (number) = **$23.50/month**

**Very affordable for small to medium businesses!**

---

## Phone Number Format

### Correct Formats ✅
```
+14135551234    (USA with country code)
+1234567890     (USA with country code)
14135551234     (Will be auto-formatted to +14135551234)
```

### Incorrect Formats ❌
```
(413) 555-1234  (Don't use parentheses or dashes)
413-555-1234   (Don't use dashes)
4135551234     (Missing country code - will fail)
```

**The system auto-formats numbers, but it's best to use `+1` format from the start.**

---

## Troubleshooting

### SMS Not Sending?

1. **Check Laravel Logs**
   ```bash
   tail -f storage/logs/laravel.log | grep SMS
   ```

2. **Verify Credentials**
   - Check `TWILIO_ACCOUNT_SID` starts with `AC`
   - Check `TWILIO_AUTH_TOKEN` is correct
   - Check `TWILIO_FROM_NUMBER` includes `+1`

3. **Check Phone Number Format**
   - Must include country code: `+1` for USA
   - No spaces, dashes, or parentheses

4. **Check Twilio Account**
   - Go to Twilio Console → Dashboard
   - Check account balance (need credit to send)
   - Check phone number is active

5. **Test Connection**
   ```bash
   php artisan tinker
   ```
   ```php
   \App\Services\SmsService::send('+14135551234', 'Test message');
   ```

### Common Errors

**Error: "Authentication failed"**
- Check Account SID and Auth Token are correct
- Make sure no extra spaces in `.env` file

**Error: "Invalid phone number"**
- Add country code: `+1` for USA
- Remove spaces, dashes, parentheses

**Error: "Insufficient funds"**
- Add credit to your Twilio account
- Go to Billing → Add Funds

**Error: "Phone number not verified"**
- For trial accounts, you can only send to verified numbers
- Verify your phone number in Twilio Console
- Or upgrade to paid account

### Trial Account Limitations

**Free Trial Accounts:**
- Can only send to **verified phone numbers**
- To verify: Twilio Console → Phone Numbers → Verified Caller IDs
- Add your phone number and verify it

**Paid Accounts:**
- Can send to any phone number
- No verification needed
- Just add payment method

---

## Production Checklist

Before going live:
- [ ] Phone number purchased and active
- [ ] Account upgraded from trial (if needed)
- [ ] Payment method added
- [ ] Admin phone number configured correctly
- [ ] Test SMS sent and received
- [ ] Error logging configured
- [ ] Monitoring set up (optional)

---

## SMS Message Format

The system automatically formats booking notifications like this:

```
🔔 NEW BOOKING #12345

Customer: John Doe
Phone: +14135551234
Package: Premium Detail
Date: Jan 15, 2024 at 2:00 PM
Address: 123 Main St, Springfield, MA
Total: $150.00

Payment: paid
```

**You can customize this in `app/Services/SmsService.php`**

---

## Advanced: Customize SMS Messages

Edit `app/Services/SmsService.php` to customize the message format:

```php
private static function formatBookingMessage($booking)
{
    // Customize this message format
    $date = $booking->booking_date->format('M j, Y');
    $time = \Carbon\Carbon::parse($booking->booking_time)->format('g:i A');
    $total = number_format($booking->total_price, 2);
    
    return "New booking #{$booking->id}\n" .
           "Customer: {$booking->user_name}\n" .
           "Date: {$date} at {$time}\n" .
           "Total: \${$total}";
}
```

---

## Support

- **Twilio Docs**: https://www.twilio.com/docs
- **Twilio Console**: https://console.twilio.com/
- **Twilio Support**: Available in dashboard
- **Laravel Integration**: Already implemented in `SmsService.php`

---

## Comparison with Other Services

| Service | Price/SMS | Setup | Reliability | Best For |
|---------|-----------|-------|-------------|----------|
| **Twilio** | $0.0075 | Easy | ⭐⭐⭐⭐⭐ | ✅ Your project |
| AWS SNS | $0.00645 | Medium | ⭐⭐⭐⭐ | AWS users |
| Vonage | $0.0055 | Medium | ⭐⭐⭐ | Cost-conscious |

**Twilio wins on ease of use and reliability!**

---

## Next Steps

Once SMS is working:
1. Test with a real booking
2. Verify admin receives notifications
3. Monitor usage in Twilio Console
4. Set up billing alerts (optional)

**That's it! You're ready to send SMS! 📱**

---

## Quick Reference

**Your `.env` should have:**
```env
SMS_PROVIDER=twilio
SMS_ADMIN_PHONE=+14135551234
TWILIO_ACCOUNT_SID=ACxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
TWILIO_AUTH_TOKEN=your_auth_token_here
TWILIO_FROM_NUMBER=+1234567890
```

**Test command:**
```bash
php artisan tinker
\App\Services\SmsService::send('+14135551234', 'Test!');
```

**Check logs:**
```bash
tail -f storage/logs/laravel.log | grep SMS
```

