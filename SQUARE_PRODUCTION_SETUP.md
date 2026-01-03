# Square Production Credentials Setup

This guide explains how to configure Square production credentials for your SteamZilla application.

## Production Credentials

You have been provided with the following production credentials:

- **Application ID**: `sq0idp-qZfjMfr8-0xBZ0AAMbAk6A`
- **Access Token**: `EAAAl2nLPLj-8VxDAw2_6XM7wKTJmOdbEkoTQHlQ7y85kcApk0KNDLBXR5YjKT6w`

## Configuration Steps

### 1. Update Your `.env` File

Add or update the following environment variables in your `.env` file:

```env
# Square Production Credentials
SQUARE_ACCESS_TOKEN=EAAAl2nLPLj-8VxDAw2_6XM7wKTJmOdbEkoTQHlQ7y85kcApk0KNDLBXR5YjKT6w
SQUARE_APPLICATION_ID=sq0idp-qZfjMfr8-0xBZ0AAMbAk6A
SQUARE_ENVIRONMENT=production
SQUARE_LOCATION_ID=your_location_id_here
SQUARE_WEBHOOK_SIGNATURE_KEY=your_webhook_signature_key_here
```

### 2. Get Your Location ID

The Location ID will be automatically fetched from Square if not provided. However, you can also:

1. Log into your Square Developer Dashboard
2. Navigate to your application
3. Go to the Locations section
4. Copy your Location ID

Alternatively, the application will automatically fetch the first available location ID when processing payments.

### 3. Webhook Configuration (Optional but Recommended)

If you want to receive webhook notifications for payment events:

1. In your Square Developer Dashboard, configure webhook endpoints
2. Set the webhook URL to: `https://yourdomain.com/webhooks/square`
3. Copy the webhook signature key and add it to your `.env` file

### 4. Verify Configuration

After updating your `.env` file:

1. Clear your configuration cache:
   ```bash
   php artisan config:clear
   ```

2. Test the Square integration:
   ```bash
   php artisan square:test
   ```

## Important Notes

### Environment Switching

- The application automatically uses the correct Square SDK URL based on the `SQUARE_ENVIRONMENT` setting
- **Sandbox**: Uses `https://sandbox.web.squarecdn.com/v1/square.js`
- **Production**: Uses `https://web.squarecdn.com/v1/square.js`

### Security

- **Never commit your `.env` file** to version control
- Keep your access tokens secure
- Rotate tokens if they are ever compromised
- Use environment-specific credentials (sandbox for testing, production for live)

### Testing in Production

⚠️ **Warning**: When using production credentials, all payments will be **real transactions**. 

- Use test cards only in sandbox mode
- In production, use real payment methods
- Monitor your Square dashboard for all transactions

## Payment Flow

The application supports Square payments for:

1. **Bookings**: Multi-step booking process with Square payment at the end
2. **Gift Cards**: Purchase gift cards using Square payments

Both payment flows automatically use the correct Square environment based on your configuration.

## Troubleshooting

### Payment Form Not Loading

- Verify `SQUARE_APPLICATION_ID` is correct
- Check that `SQUARE_ENVIRONMENT` is set to `production`
- Ensure your site is served over HTTPS (required for production)

### Location ID Errors

- The application will try to auto-fetch the location ID
- If errors persist, manually set `SQUARE_LOCATION_ID` in your `.env` file

### API Errors

- Verify your access token is valid and has the correct permissions
- Check your Square Developer Dashboard for API errors
- Review Laravel logs: `storage/logs/laravel.log`

## Support

For Square API issues, refer to:
- [Square Developer Documentation](https://developer.squareup.com/docs)
- [Square Support](https://squareup.com/help)

