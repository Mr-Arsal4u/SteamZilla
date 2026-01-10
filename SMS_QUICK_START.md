# SMS Quick Start Guide - Get SMS Working in 5 Steps

## ✅ What You Need

1. **Twilio Account** (free trial available)
2. **Twilio Phone Number** (to send SMS from)
3. **Your Phone Number** (to receive admin notifications)
4. **5 minutes** of setup time

---

## 🚀 Step-by-Step Setup

### Step 1: Create Twilio Account (2 minutes)

1. Go to **https://www.twilio.com/**
2. Click **"Sign up"** (top right)
3. Fill in your details and verify your phone number
4. **You'll get $15.50 free credit!** (enough for ~2,000 SMS)

### Step 2: Get a Phone Number (2 minutes)

1. In Twilio Console, click **"Get a number"**
2. Select:
   - **Country**: United States
   - **Capabilities**: Check **"SMS"** ✅
3. Click **"Search"** and choose a number
4. Click **"Buy"** (usually $1/month)

### Step 3: Get Your Credentials (1 minute)

In the Twilio Console Dashboard, you'll see:

- **Account SID**: Starts with `AC...` (copy this)
- **Auth Token**: Click "View" to reveal (copy this)
- **Phone Number**: The number you just bought (format: +1234567890)

### Step 4: Add to Your `.env` File

Open your `.env` file and add these lines:

```env
# SMS Configuration
SMS_PROVIDER=twilio
SMS_ADMIN_PHONE=+14135551234

# Twilio Credentials
TWILIO_ACCOUNT_SID=ACxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
TWILIO_AUTH_TOKEN=your_auth_token_here
TWILIO_FROM_NUMBER=+1234567890
```

**Replace with your actual values:**
- `SMS_ADMIN_PHONE`: Your phone number (where you want booking notifications)
- `TWILIO_ACCOUNT_SID`: Your Account SID from Twilio
- `TWILIO_AUTH_TOKEN`: Your Auth Token from Twilio
- `TWILIO_FROM_NUMBER`: The Twilio phone number you purchased

**⚠️ Important:**
- All phone numbers must include country code: `+1` for USA
- Format: `+14135551234` (not `(413) 555-1234`)

### Step 5: Clear Cache & Test (1 minute)

```bash
php artisan config:clear
php artisan config:cache
```

Then test it:

```bash
php artisan tinker
```

```php
// Test SMS to yourself
\App\Services\SmsService::send('+14135551234', 'Test SMS from SteamZilla! 🚗');
```

**You should receive the SMS within seconds!** 📱

---

## ✅ That's It!

Once configured, SMS will automatically send:
- ✅ **Admin SMS** - When a new booking is created
- ✅ **Customer SMS** - Booking confirmation to the customer
- ✅ **Gift Card SMS** - When gift cards are delivered via SMS

---

## 🐛 Troubleshooting

### SMS Not Sending?

1. **Check your `.env` file** - Make sure all values are correct
2. **Clear cache again**: `php artisan config:clear`
3. **Check phone format**: Must be `+1` followed by 10 digits
4. **Check Twilio Console**: 
   - Go to Twilio Console → Dashboard
   - Check account balance (need credit to send)
   - Check phone number is active

### Trial Account Limitations

- **Free trial accounts** can only send to **verified phone numbers**
- To verify: Twilio Console → Phone Numbers → Verified Caller IDs
- Add your phone number and verify it
- Or upgrade to paid account to send to any number

### Common Errors

**"Authentication failed"**
- Check Account SID and Auth Token are correct
- Make sure no extra spaces in `.env` file

**"Invalid phone number"**
- Add country code: `+1` for USA
- Remove spaces, dashes, parentheses

**"Insufficient funds"**
- Add credit to your Twilio account
- Go to Billing → Add Funds

---

## 📋 Complete .env Example

Here's what your SMS section should look like:

```env
# SMS Configuration
SMS_PROVIDER=twilio
SMS_ADMIN_PHONE=+14135551234

# Twilio SMS Configuration
TWILIO_ACCOUNT_SID=ACxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx
TWILIO_AUTH_TOKEN=your_auth_token_here
TWILIO_FROM_NUMBER=+1234567890
```

---

## 🎯 Next Steps

After SMS is working:

1. **Test with a real booking** - Create a booking and verify both admin and customer receive SMS
2. **Test gift card SMS** - Purchase a gift card with SMS delivery method
3. **Monitor usage** - Check Twilio Console for SMS usage and costs

---

## 💰 Pricing

- **Free Trial**: $15.50 credit (enough for ~2,000 SMS)
- **Pay-As-You-Go**: $0.0075 per SMS in USA
- **Phone Number**: ~$1/month (local) or ~$2/month (toll-free)

**Example Costs:**
- 10 SMS/day = ~$2.25/month + $1 (number) = **$3.25/month**
- 50 SMS/day = ~$11.25/month + $1 (number) = **$12.25/month**

---

## 📚 More Help

- **Detailed Setup**: See `TWILIO_SMS_SETUP_GUIDE.md`
- **Complete Setup**: See `SMS_SETUP_COMPLETE.md`
- **Twilio Docs**: https://www.twilio.com/docs

---

**Ready to go!** Just follow these 5 steps and your SMS service will be working! 📱✨

