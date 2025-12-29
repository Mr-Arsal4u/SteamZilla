# Square Payment Integration Test Guide

## Overview

This guide explains how to test the Square payment integration to verify that payments are being processed correctly.

## Prerequisites

Before running the tests, ensure:

1. **Square Account Setup:**
   - You have a Square Developer account
   - Your Square application is created
   - You have sandbox credentials (for testing)

2. **Environment Configuration:**
   - `.env` file is configured with Square credentials:
     ```env
     SQUARE_ACCESS_TOKEN=your_sandbox_access_token
     SQUARE_APPLICATION_ID=your_application_id
     SQUARE_LOCATION_ID=your_location_id (optional - will be auto-fetched)
     SQUARE_ENVIRONMENT=sandbox
     ```

3. **Database:**
   - Migrations have been run
   - Database tables exist with Square fields

## Running the Test

### Option 1: Using Artisan Command

```bash
php artisan square:test
```

### Option 2: Using Test Script

```bash
./test-square.sh
```

### Command Options

```bash
# Test with custom amount
php artisan square:test --amount=25.00

# Skip payment creation test (only test configuration and API connection)
php artisan square:test --skip-payment

# Skip refund test
php artisan square:test --skip-refund

# Combine options
php artisan square:test --amount=15.00 --skip-refund
```

## What the Test Checks

### 1. Configuration Check ✅
- Verifies that Square credentials are set in `.env`
- Checks for:
  - `SQUARE_ACCESS_TOKEN`
  - `SQUARE_APPLICATION_ID`
  - `SQUARE_LOCATION_ID` (optional)
  - `SQUARE_ENVIRONMENT`

### 2. Square Client Initialization ✅
- Tests if Square SDK client can be initialized
- Verifies environment (sandbox/production) is set correctly

### 3. Location ID Validation ✅
- If `SQUARE_LOCATION_ID` is not set, attempts to fetch it from Square API
- Validates that at least one location exists in your Square account

### 4. Payment Creation Test 💳
- **Note:** This test requires a valid card nonce from Square's Web Payments SDK
- Attempts to create a test payment
- Uses Square's sandbox test nonce: `cnon:card-nonce-ok`
- **Important:** This test may fail if:
  - The test nonce is not valid for your environment
  - Your Square account doesn't have proper permissions
  - The location ID is incorrect

### 5. Refund Test ↩️
- Tests refund functionality (only if payment was created)
- Requires a successful payment ID from test 4
- Processes a full refund

### 6. Database Schema Check 🗄️
- Verifies that all required Square fields exist in database:
  - `bookings` table: `square_payment_id`, `square_receipt_url`, `square_refund_id`, `payment_status`, `payment_method`
  - `gift_cards` table: `square_payment_id`, `square_receipt_url`, `payment_status`

## Understanding Test Results

### ✅ Success Indicators

- **Configuration:** All required environment variables are set
- **Client Init:** Square SDK initializes without errors
- **Location ID:** Location ID is valid and accessible
- **Payment:** Payment is created successfully (if not skipped)
- **Refund:** Refund is processed successfully (if not skipped)
- **Database:** All required columns exist

### ❌ Common Issues and Solutions

#### 1. Configuration Errors

**Error:** `SQUARE_ACCESS_TOKEN is not set`

**Solution:**
```bash
# Add to .env file
SQUARE_ACCESS_TOKEN=your_token_here
```

#### 2. Client Initialization Failed

**Error:** `Failed to initialize Square client`

**Possible Causes:**
- Invalid access token
- Network connectivity issues
- Square API is down

**Solution:**
- Verify your access token is correct
- Check your internet connection
- Verify Square API status

#### 3. Location ID Not Found

**Error:** `No locations found in Square account`

**Solution:**
- Ensure your Square account has at least one location
- In sandbox, create a test location in Square Dashboard
- Or manually set `SQUARE_LOCATION_ID` in `.env`

#### 4. Payment Creation Failed

**Error:** `Payment creation failed`

**Common Causes:**
- Invalid test nonce (sandbox test nonces may not work in all cases)
- Insufficient permissions
- Invalid location ID

**Solution:**
- For real testing, use the actual Square Web Payments SDK in the frontend
- Verify your Square application has payment permissions
- Check that your location ID is correct

**Note:** Payment creation test may fail even with correct configuration because it requires a valid nonce from Square's frontend SDK. This is expected behavior for a command-line test.

#### 5. Database Schema Errors

**Error:** `Missing column 'square_payment_id'`

**Solution:**
```bash
# Run migrations
php artisan migrate
```

## Testing Real Payments

For testing actual payment flows (not just API connectivity), you need to:

1. **Use the Web Interface:**
   - Go through the booking flow on your website
   - Use Square's test card numbers in sandbox:
     - **Card Number:** 4111 1111 1111 1111
     - **CVV:** Any 3 digits
     - **Expiry:** Any future date
     - **ZIP:** Any 5 digits

2. **Monitor in Square Dashboard:**
   - Log into Square Developer Dashboard
   - Check Payments section for test transactions
   - Verify payment status and details

3. **Check Database:**
   ```sql
   SELECT * FROM bookings WHERE payment_method = 'square' ORDER BY created_at DESC LIMIT 5;
   ```

## Test Output Example

```
🔍 Starting Square Payment Integration Test...

📋 Test 1: Checking Configuration...
   Environment: sandbox
   ✅ SQUARE_ACCESS_TOKEN is set
   ✅ SQUARE_APPLICATION_ID is set
   ✅ SQUARE_LOCATION_ID is set
   ✅ Configuration check passed

🔧 Test 2: Initializing Square Client...
   ✅ Square client initialized successfully

📍 Test 3: Validating Location ID...
   ✅ Using configured location ID: LXXXXXXX
   ✅ Location ID validated

💳 Test 4: Testing Payment Creation...
   Amount: $10.00 (1000 cents)
   Location ID: LXXXXXXX
   ⚠️  Note: This test requires a valid card nonce.
   ⚠️  For sandbox testing, use Square's test card nonces.
   ⚠️  This test will attempt to create a payment but may fail without a valid nonce.

   Do you want to proceed with payment creation test? (yes/no) [no]:
```

## Best Practices

1. **Always test in sandbox first** before using production credentials
2. **Use test card numbers** provided by Square for sandbox testing
3. **Monitor Square Dashboard** to verify transactions
4. **Check database** to ensure data is saved correctly
5. **Test error scenarios** (declined cards, network failures, etc.)

## Troubleshooting

If tests fail:

1. **Check logs:**
   ```bash
   tail -f storage/logs/laravel.log
   ```

2. **Verify Square credentials:**
   - Log into Square Developer Dashboard
   - Check Application settings
   - Verify access tokens are active

3. **Test API connection manually:**
   ```bash
   curl -X GET "https://connect.squareupsandbox.com/v2/locations" \
     -H "Square-Version: 2023-10-18" \
     -H "Authorization: Bearer YOUR_ACCESS_TOKEN"
   ```

4. **Check database:**
   ```bash
   php artisan tinker
   >>> Schema::hasTable('bookings')
   >>> Schema::getColumnListing('bookings')
   ```

## Additional Resources

- [Square Developer Documentation](https://developer.squareup.com/docs)
- [Square Sandbox Testing Guide](https://developer.squareup.com/docs/devtools/sandbox)
- [Square Web Payments SDK](https://developer.squareup.com/docs/web-payments/overview)

