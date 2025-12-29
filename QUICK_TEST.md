# Quick Square Payment Test

## Run the Test

```bash
# Basic test (checks configuration and API connection)
php artisan square:test

# Test with custom amount
php artisan square:test --amount=25.00

# Skip payment creation (only test config and API)
php artisan square:test --skip-payment

# Or use the test script
./test-square.sh
```

## What Gets Tested

1. ✅ **Configuration** - Checks if Square credentials are set
2. ✅ **API Connection** - Tests Square client initialization
3. ✅ **Location ID** - Validates/fetches location from Square
4. 💳 **Payment Creation** - Tests payment API (may fail without valid nonce - this is normal)
5. ↩️ **Refund** - Tests refund functionality (if payment was created)
6. 🗄️ **Database** - Verifies all Square fields exist in database

## Expected Results

### ✅ All Tests Pass
- Configuration is correct
- Square API is accessible
- Location ID is valid
- Database schema is correct

### ⚠️ Payment Test May Fail
- This is **NORMAL** - payment creation requires a valid card nonce from Square's frontend SDK
- The test still validates that your API connection works
- For real payment testing, use the web interface with Square's test cards

## Real Payment Testing

To test actual payments:

1. **Start your application:**
   ```bash
   php artisan serve
   ```

2. **Go through booking flow:**
   - Visit: http://localhost:8000/order-now
   - Complete booking steps
   - Select "Square" payment method
   - Use Square test card:
     - Card: 4111 1111 1111 1111
     - CVV: 123
     - Expiry: 12/25
     - ZIP: 12345

3. **Check results:**
   - Check Square Dashboard for transaction
   - Check database: `SELECT * FROM bookings WHERE payment_method = 'square' ORDER BY created_at DESC LIMIT 1;`
   - Check admin panel: http://localhost:8000/admin/bookings

## Troubleshooting

**Command not found?**
```bash
php artisan config:clear
php artisan cache:clear
```

**Configuration errors?**
- Check `.env` file has Square credentials
- Verify credentials in Square Developer Dashboard

**API connection fails?**
- Check internet connection
- Verify Square API is accessible
- Check access token is valid

