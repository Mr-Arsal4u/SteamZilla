# Firebase Notifications Setup Guide

## Understanding Firebase for Notifications

**Important**: Firebase does **not** have native email or SMS services. However, you can use Firebase in the following ways:

### What Firebase Offers:

1. **Firebase Cloud Messaging (FCM)** - Push notifications to mobile apps (NOT SMS)
2. **Firebase Cloud Functions** - Serverless functions that can call email/SMS APIs
3. **Firebase Extensions** - Pre-built solutions that integrate with third-party services

---

## Option 1: Firebase Cloud Functions + Email/SMS Services

You can use Firebase Cloud Functions to send emails and SMS by calling third-party APIs. This requires:

- Firebase project setup
- Cloud Functions deployment
- Integration with email/SMS services (SendGrid, Twilio, etc.)

### Setup Steps:

1. **Install Firebase CLI**
   ```bash
   npm install -g firebase-tools
   firebase login
   ```

2. **Initialize Firebase in your project**
   ```bash
   firebase init functions
   ```

3. **Create Cloud Function for Email/SMS**
   ```javascript
   // functions/index.js
   const functions = require('firebase-functions');
   const admin = require('firebase-admin');
   const sgMail = require('@sendgrid/mail');
   const twilio = require('twilio');
   
   admin.initializeApp();
   
   // Email function
   exports.sendBookingEmail = functions.https.onCall(async (data, context) => {
     sgMail.setApiKey(process.env.SENDGRID_API_KEY);
     
     const msg = {
       to: data.email,
       from: 'noreply@yourdomain.com',
       subject: 'Booking Confirmation',
       html: data.htmlContent,
     };
     
     await sgMail.send(msg);
     return { success: true };
   });
   
   // SMS function
   exports.sendBookingSMS = functions.https.onCall(async (data, context) => {
     const client = twilio(
       process.env.TWILIO_ACCOUNT_SID,
       process.env.TWILIO_AUTH_TOKEN
     );
     
     await client.messages.create({
       body: data.message,
       from: process.env.TWILIO_FROM_NUMBER,
       to: data.phoneNumber,
     });
     
     return { success: true };
   });
   ```

4. **Call from Laravel**
   ```php
   use Kreait\Firebase\Factory;
   
   $firebase = (new Factory)
       ->withServiceAccount('path/to/serviceAccountKey.json')
       ->create();
   
   $functions = $firebase->getFunctions();
   $result = $functions->httpsCallable('sendBookingEmail')->call([
       'email' => $booking->user_email,
       'htmlContent' => view('emails.booking-confirmation', compact('booking'))->render(),
   ]);
   ```

**Pros:**
- Serverless, scalable
- No server management
- Good for high volume

**Cons:**
- More complex setup
- Requires Firebase project
- Additional costs (Cloud Functions)
- Still need email/SMS service accounts

---

## Option 2: Firebase Cloud Messaging (FCM) for Push Notifications

If you have a mobile app, you can use FCM instead of SMS for push notifications.

### Setup Steps:

1. **Install Firebase Admin SDK in Laravel**
   ```bash
   composer require kreait/firebase-php
   ```

2. **Get Firebase Service Account**
   - Go to Firebase Console → Project Settings → Service Accounts
   - Generate new private key
   - Download JSON file

3. **Create FCM Service**
   ```php
   // app/Services/FcmService.php
   namespace App\Services;
   
   use Kreait\Firebase\Factory;
   use Kreait\Firebase\Messaging\CloudMessage;
   use Kreait\Firebase\Messaging\Notification;
   
   class FcmService
   {
       private $messaging;
       
       public function __construct()
       {
           $factory = (new Factory)
               ->withServiceAccount(storage_path('app/firebase-service-account.json'));
           
           $this->messaging = $factory->createMessaging();
       }
       
       public function sendBookingNotification($deviceToken, $booking)
       {
           $message = CloudMessage::withTarget('token', $deviceToken)
               ->withNotification(Notification::create(
                   'New Booking Received',
                   "Booking #{$booking->id} from {$booking->user_name}"
               ))
               ->withData([
                   'booking_id' => (string)$booking->id,
                   'type' => 'new_booking',
               ]);
           
           $this->messaging->send($message);
       }
   }
   ```

4. **Update PaymentController**
   ```php
   use App\Services\FcmService;
   
   // In processPayment method
   try {
       $fcm = new FcmService();
       $adminDeviceToken = config('firebase.admin_device_token');
       if ($adminDeviceToken) {
           $fcm->sendBookingNotification($adminDeviceToken, $booking);
       }
   } catch (\Exception $e) {
       Log::error('FCM notification failed: ' . $e->getMessage());
   }
   ```

**Pros:**
- Free for unlimited messages
- Real-time push notifications
- Better user experience (if you have mobile app)

**Cons:**
- Requires mobile app
- Users must install app and enable notifications
- Not SMS (different delivery method)

---

## Option 3: Firebase Extensions

Firebase offers extensions that can help with notifications:

1. **Trigger Email Extension**
   - Automatically sends emails via SendGrid when documents are created
   - Requires Firestore database
   - Not directly compatible with Laravel

2. **Send Email via SMTP Extension**
   - Sends emails via SMTP when triggered
   - Requires Firestore triggers

**Note**: These extensions work best with Firebase-native apps, not Laravel applications.

---

## Recommendation

**For a Laravel application, Firebase is NOT the best choice for email/SMS because:**

1. ❌ No native email service
2. ❌ No native SMS service
3. ❌ Requires additional setup and complexity
4. ❌ Still needs third-party services (SendGrid, Twilio)
5. ❌ Additional costs (Cloud Functions)
6. ❌ More complex than direct integration

**Better alternatives:**
- ✅ **Direct integration** with SendGrid/Twilio (what we've already set up)
- ✅ **AWS SES + SNS** (if you want AWS ecosystem)
- ✅ **FCM** (only if you have a mobile app and want push notifications)

---

## If You Still Want to Use Firebase

If you have specific reasons to use Firebase (e.g., you already have a Firebase project, mobile app, etc.), here's what you need:

### Required Packages:
```bash
composer require kreait/firebase-php
```

### Configuration:
```env
FIREBASE_PROJECT_ID=your-project-id
FIREBASE_CREDENTIALS_PATH=storage/app/firebase-service-account.json
```

### Service Account Setup:
1. Go to Firebase Console
2. Project Settings → Service Accounts
3. Generate new private key
4. Save JSON file to `storage/app/firebase-service-account.json`

### Implementation:
You would need to:
1. Create Cloud Functions for email/SMS
2. Call them from Laravel via HTTP
3. Still maintain SendGrid/Twilio accounts

**This adds complexity without significant benefits for a Laravel web app.**

---

## Conclusion

**For SteamZilla (Laravel web application):**
- ✅ Use **SendGrid + Twilio** (direct integration - already implemented)
- ❌ Don't use Firebase (adds complexity, no real benefit)

**If you have a mobile app:**
- ✅ Consider **FCM** for push notifications (in addition to email)
- ✅ Keep **email** for web users
- ✅ Keep **SMS** for critical admin notifications

---

## Quick Comparison

| Solution | Setup Time | Cost | Complexity | Best For |
|----------|------------|------|------------|----------|
| **SendGrid + Twilio** (Current) | 30 min | Low | Low | ✅ Laravel web apps |
| **Firebase Functions** | 2-3 hours | Medium | High | Firebase-native apps |
| **FCM** | 1 hour | Free | Medium | Mobile apps only |
| **AWS SES + SNS** | 45 min | Low | Medium | AWS ecosystem |

**Recommendation: Stick with the current SendGrid + Twilio setup!** 🎯

