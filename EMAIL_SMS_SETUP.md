# Email and SMS Notification Setup Guide

This guide will help you configure email and SMS notifications for SteamZilla booking system.

> **Note**: If you're considering Firebase, please read `FIREBASE_NOTIFICATIONS.md` first. Firebase does not have native email/SMS services and may not be the best choice for a Laravel application.

## Overview

The system sends:
- **Email to Customer**: Professional booking confirmation with all details
- **Email to Admin**: Detailed notification about new bookings
- **SMS to Admin**: Quick notification about new bookings

---

## 📧 Email Configuration

### Recommended Email Services for USA

For USA-based clients, we recommend one of these services:

#### 1. **AWS SES (Amazon Simple Email Service)** ⭐ Recommended
- **Best for**: High volume, cost-effective, reliable
- **Pricing**: $0.10 per 1,000 emails (first 62,000 free/month if on EC2)
- **Setup Time**: 30-45 minutes
- **Pros**: Very reliable, scalable, integrates with AWS ecosystem
- **Cons**: Requires AWS account, initial setup can be complex

#### 2. **SendGrid**
- **Best for**: Easy setup, good deliverability
- **Pricing**: Free tier (100 emails/day), then $19.95/month for 50,000 emails
- **Setup Time**: 15-20 minutes
- **Pros**: Easy to use, great documentation, good free tier
- **Cons**: More expensive at scale

#### 3. **Mailgun**
- **Best for**: Developer-friendly, good API
- **Pricing**: Free tier (5,000 emails/month), then $35/month for 50,000 emails
- **Setup Time**: 20-30 minutes
- **Pros**: Great API, good deliverability, free tier
- **Cons**: Pricing can get expensive

#### 4. **Postmark**
- **Best for**: Transactional emails, excellent deliverability
- **Pricing**: $15/month for 10,000 emails
- **Setup Time**: 15-20 minutes
- **Pros**: Best deliverability, great for transactional emails
- **Cons**: More expensive, no free tier

### Setup Instructions

#### Option 1: AWS SES Setup

1. **Create AWS Account** (if you don't have one)
   - Go to https://aws.amazon.com/
   - Sign up for an account

2. **Access SES Console**
   - Navigate to AWS SES in your AWS Console
   - Select your region (e.g., `us-east-1`)

3. **Verify Your Email Domain or Email Address**
   - For production: Verify your domain (recommended)
   - For testing: Verify your email address
   - Go to "Verified identities" → "Create identity"
   - Follow the verification process

4. **Request Production Access** (if needed)
   - By default, SES is in "Sandbox" mode
   - Request production access to send to any email
   - Go to "Account dashboard" → "Request production access"

5. **Create IAM User for SES**
   - Go to IAM Console
   - Create a new user with programmatic access
   - Attach policy: `AmazonSESFullAccess` (or create custom policy)
   - Save Access Key ID and Secret Access Key

6. **Configure Laravel**
   Add to your `.env` file:
   ```env
   MAIL_MAILER=ses
   MAIL_FROM_ADDRESS=noreply@yourdomain.com
   MAIL_FROM_NAME="SteamZilla"
   MAIL_ADMIN_EMAIL=admin@yourdomain.com
   
   AWS_ACCESS_KEY_ID=your_access_key_id
   AWS_SECRET_ACCESS_KEY=your_secret_access_key
   AWS_DEFAULT_REGION=us-east-1
   ```

7. **Install AWS SDK** (if not already installed)
   ```bash
   composer require aws/aws-sdk-php
   ```

#### Option 2: SendGrid Setup

1. **Create SendGrid Account**
   - Go to https://sendgrid.com/
   - Sign up for a free account

2. **Verify Your Sender Identity**
   - Go to Settings → Sender Authentication
   - Verify a single sender email or your domain

3. **Create API Key**
   - Go to Settings → API Keys
   - Click "Create API Key"
   - Give it a name (e.g., "Laravel App")
   - Select "Full Access" or "Restricted Access" with Mail Send permissions
   - Copy the API key (you won't see it again!)

4. **Configure Laravel**
   Add to your `.env` file:
   ```env
   MAIL_MAILER=smtp
   MAIL_HOST=smtp.sendgrid.net
   MAIL_PORT=587
   MAIL_USERNAME=apikey
   MAIL_PASSWORD=your_sendgrid_api_key
   MAIL_ENCRYPTION=tls
   MAIL_FROM_ADDRESS=noreply@yourdomain.com
   MAIL_FROM_NAME="SteamZilla"
   MAIL_ADMIN_EMAIL=admin@yourdomain.com
   ```

#### Option 3: Mailgun Setup

1. **Create Mailgun Account**
   - Go to https://www.mailgun.com/
   - Sign up for an account

2. **Verify Your Domain**
   - Go to Sending → Domains
   - Add and verify your domain
   - Add the required DNS records

3. **Get API Credentials**
   - Go to Sending → API Keys
   - Copy your API Key and Domain

4. **Install Mailgun Package**
   ```bash
   composer require symfony/mailgun-mailer symfony/http-client
   ```

5. **Configure Laravel**
   Add to your `.env` file:
   ```env
   MAIL_MAILER=mailgun
   MAIL_FROM_ADDRESS=noreply@yourdomain.com
   MAIL_FROM_NAME="SteamZilla"
   MAIL_ADMIN_EMAIL=admin@yourdomain.com
   
   MAILGUN_DOMAIN=your_mailgun_domain
   MAILGUN_SECRET=your_mailgun_api_key
   MAILGUN_ENDPOINT=api.mailgun.net
   ```

   Add to `config/services.php`:
   ```php
   'mailgun' => [
       'domain' => env('MAILGUN_DOMAIN'),
       'secret' => env('MAILGUN_SECRET'),
       'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
   ],
   ```

#### Option 4: Postmark Setup

1. **Create Postmark Account**
   - Go to https://postmarkapp.com/
   - Sign up for an account

2. **Create a Server**
   - Go to Servers → Add Server
   - Give it a name (e.g., "SteamZilla Production")
   - Copy the Server API Token

3. **Verify Your Sender Signature**
   - Go to Signatures → Add Signature
   - Verify your email address or domain

4. **Configure Laravel**
   Add to your `.env` file:
   ```env
   MAIL_MAILER=postmark
   MAIL_FROM_ADDRESS=noreply@yourdomain.com
   MAIL_FROM_NAME="SteamZilla"
   MAIL_ADMIN_EMAIL=admin@yourdomain.com
   
   POSTMARK_API_KEY=your_postmark_server_api_token
   ```

---

## 📱 SMS Configuration

### Recommended SMS Services for USA

#### 1. **Twilio** ⭐ Recommended
- **Best for**: Most popular, reliable, easy to use
- **Pricing**: $0.0075 per SMS (about $0.75 per 100 messages)
- **Setup Time**: 15-20 minutes
- **Pros**: Very reliable, excellent documentation, easy integration
- **Cons**: Slightly more expensive than some alternatives

#### 2. **AWS SNS (Simple Notification Service)**
- **Best for**: If already using AWS, cost-effective
- **Pricing**: $0.00645 per SMS (about $0.65 per 100 messages)
- **Setup Time**: 30-45 minutes
- **Pros**: Cheaper, integrates with AWS, scalable
- **Cons**: More complex setup, requires AWS account

#### 3. **Vonage (formerly Nexmo)**
- **Best for**: International support, good pricing
- **Pricing**: $0.0055 per SMS (about $0.55 per 100 messages)
- **Setup Time**: 20-30 minutes
- **Pros**: Cheapest option, good international support
- **Cons**: Less popular than Twilio

### Setup Instructions

#### Option 1: Twilio Setup (Recommended)

1. **Create Twilio Account**
   - Go to https://www.twilio.com/
   - Sign up for a free account
   - Verify your email and phone number

2. **Get a Phone Number**
   - Go to Phone Numbers → Buy a Number
   - Select a US phone number
   - Choose "SMS" capability
   - Purchase the number (usually $1/month)

3. **Get API Credentials**
   - Go to Console Dashboard
   - Copy your "Account SID" and "Auth Token"
   - Note your phone number (format: +1234567890)

4. **Configure Laravel**
   Add to your `.env` file:
   ```env
   SMS_PROVIDER=twilio
   SMS_ADMIN_PHONE=+14135551234
   
   TWILIO_ACCOUNT_SID=your_account_sid
   TWILIO_AUTH_TOKEN=your_auth_token
   TWILIO_FROM_NUMBER=+1234567890
   ```

5. **Test SMS**
   ```bash
   php artisan tinker
   ```
   Then run:
   ```php
   \App\Services\SmsService::send('+14135551234', 'Test message from SteamZilla');
   ```

#### Option 2: AWS SNS Setup

1. **Create AWS Account** (if you don't have one)
   - Go to https://aws.amazon.com/
   - Sign up for an account

2. **Access SNS Console**
   - Navigate to AWS SNS in your AWS Console
   - Select your region (e.g., `us-east-1`)

3. **Request SMS Sending Quota**
   - Go to SNS → Text messaging (SMS)
   - Request production access (if needed)
   - Set spending limits

4. **Create IAM User**
   - Go to IAM Console
   - Create a new user with programmatic access
   - Attach policy: `AmazonSNSFullAccess` (or create custom policy)
   - Save Access Key ID and Secret Access Key

5. **Install AWS SDK**
   ```bash
   composer require aws/aws-sdk-php
   ```

6. **Configure Laravel**
   Add to your `.env` file:
   ```env
   SMS_PROVIDER=aws_sns
   SMS_ADMIN_PHONE=+14135551234
   
   AWS_SNS_ACCESS_KEY_ID=your_access_key_id
   AWS_SNS_SECRET_ACCESS_KEY=your_secret_access_key
   AWS_SNS_REGION=us-east-1
   ```

7. **Update SmsService.php**
   You'll need to update the `sendViaAwsSns` method to use the AWS SDK:
   ```php
   use Aws\Sns\SnsClient;
   
   private static function sendViaAwsSns($to, $message)
   {
       $sns = new SnsClient([
           'version' => 'latest',
           'region' => config('sms.aws_sns.region'),
           'credentials' => [
               'key' => config('sms.aws_sns.access_key'),
               'secret' => config('sms.aws_sns.secret_key'),
           ],
       ]);
       
       try {
           $result = $sns->publish([
               'PhoneNumber' => self::formatPhoneNumber($to),
               'Message' => $message,
           ]);
           
           Log::info('SMS sent successfully via AWS SNS', [
               'to' => $to,
               'message_id' => $result->get('MessageId'),
           ]);
           return true;
       } catch (\Exception $e) {
           Log::error('AWS SNS SMS exception: ' . $e->getMessage());
           return false;
       }
   }
   ```

---

## 🔧 Environment Variables Summary

Add these to your `.env` file:

```env
# Email Configuration
MAIL_MAILER=ses  # or smtp, mailgun, postmark
MAIL_FROM_ADDRESS=noreply@yourdomain.com
MAIL_FROM_NAME="SteamZilla"
MAIL_ADMIN_EMAIL=admin@yourdomain.com

# For AWS SES
AWS_ACCESS_KEY_ID=your_access_key_id
AWS_SECRET_ACCESS_KEY=your_secret_access_key
AWS_DEFAULT_REGION=us-east-1

# For SendGrid (if using SMTP)
MAIL_HOST=smtp.sendgrid.net
MAIL_PORT=587
MAIL_USERNAME=apikey
MAIL_PASSWORD=your_sendgrid_api_key
MAIL_ENCRYPTION=tls

# For Mailgun
MAILGUN_DOMAIN=your_mailgun_domain
MAILGUN_SECRET=your_mailgun_api_key

# For Postmark
POSTMARK_API_KEY=your_postmark_api_key

# SMS Configuration
SMS_PROVIDER=twilio  # or aws_sns
SMS_ADMIN_PHONE=+14135551234

# For Twilio
TWILIO_ACCOUNT_SID=your_account_sid
TWILIO_AUTH_TOKEN=your_auth_token
TWILIO_FROM_NUMBER=+1234567890

# For AWS SNS
AWS_SNS_ACCESS_KEY_ID=your_access_key_id
AWS_SNS_SECRET_ACCESS_KEY=your_secret_access_key
AWS_SNS_REGION=us-east-1
```

---

## ✅ Testing

### Test Email Configuration

1. **Test Customer Email**
   ```bash
   php artisan tinker
   ```
   ```php
   $booking = \App\Models\Booking::first();
   \Illuminate\Support\Facades\Mail::to('your-email@example.com')
       ->send(new \App\Mail\BookingConfirmation($booking));
   ```

2. **Test Admin Email**
   ```php
   $booking = \App\Models\Booking::first();
   \Illuminate\Support\Facades\Mail::to('admin@example.com')
       ->send(new \App\Mail\NewBookingNotification($booking));
   ```

### Test SMS Configuration

```bash
php artisan tinker
```
```php
// Test SMS to admin
$booking = \App\Models\Booking::first();
\App\Services\SmsService::sendBookingNotification($booking);

// Or test custom SMS
\App\Services\SmsService::send('+14135551234', 'Test message');
```

---

## 📋 Service Comparison

### Email Services

| Service | Free Tier | Paid Pricing | Setup Difficulty | Best For |
|---------|-----------|--------------|------------------|----------|
| AWS SES | 62,000/month* | $0.10/1,000 | Medium | High volume |
| SendGrid | 100/day | $19.95/50k | Easy | Easy setup |
| Mailgun | 5,000/month | $35/50k | Easy | Developers |
| Postmark | None | $15/10k | Easy | Deliverability |

*If on EC2

### SMS Services

| Service | Pricing per SMS | Setup Difficulty | Best For |
|---------|------------------|------------------|----------|
| Twilio | $0.0075 | Easy | Most users |
| AWS SNS | $0.00645 | Medium | AWS users |
| Vonage | $0.0055 | Medium | Cost-conscious |

---

## 🚀 Production Checklist

- [ ] Email service configured and tested
- [ ] SMS service configured and tested
- [ ] Domain verified (for email)
- [ ] Phone number purchased (for SMS)
- [ ] Admin email address set in `.env`
- [ ] Admin phone number set in `.env`
- [ ] Test emails sent and received
- [ ] Test SMS sent and received
- [ ] Error logging configured
- [ ] Monitoring set up (optional)

---

## 🆘 Troubleshooting

### Email Not Sending

1. **Check Mail Logs**
   ```bash
   tail -f storage/logs/laravel.log
   ```

2. **Check Configuration**
   ```bash
   php artisan config:clear
   php artisan config:cache
   ```

3. **Test Mail Connection**
   ```bash
   php artisan tinker
   ```
   ```php
   \Illuminate\Support\Facades\Mail::raw('Test', function($msg) {
       $msg->to('your-email@example.com')->subject('Test');
   });
   ```

### SMS Not Sending

1. **Check Logs**
   ```bash
   tail -f storage/logs/laravel.log | grep SMS
   ```

2. **Verify Phone Number Format**
   - Must be in E.164 format: `+1234567890`
   - Or 10 digits: `1234567890` (will be auto-formatted)

3. **Check Twilio Account**
   - Verify account is active
   - Check account balance
   - Verify phone number is active

---

## 📞 Support

For issues or questions:
- Check Laravel logs: `storage/logs/laravel.log`
- Review service provider documentation
- Contact service provider support

---

## 📝 Notes

- Email templates are located in `resources/views/emails/`
- SMS service is located in `app/Services/SmsService.php`
- Both email and SMS are sent automatically when a booking is created
- SMS is only sent to admin, not to customers
- All notifications are logged for debugging

