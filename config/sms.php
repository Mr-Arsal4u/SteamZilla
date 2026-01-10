<?php

return [

    /*
    |--------------------------------------------------------------------------
    | SMS Provider
    |--------------------------------------------------------------------------
    |
    | This option controls which SMS service provider is used to send SMS
    | messages. Supported providers: "twilio", "aws_sns"
    |
    */

    'provider' => env('SMS_PROVIDER', 'twilio'),

    /*
    |--------------------------------------------------------------------------
    | Admin Phone Number
    |--------------------------------------------------------------------------
    |
    | The phone number where admin notifications should be sent.
    | Format: +1234567890 (E.164 format) or 1234567890 (will be auto-formatted)
    |
    */

    'admin_phone' => env('SMS_ADMIN_PHONE'),

    /*
    |--------------------------------------------------------------------------
    | Twilio Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for Twilio SMS service.
    | Get credentials from: https://www.twilio.com/console
    |
    */

    'twilio' => [
        'account_sid' => env('TWILIO_ACCOUNT_SID'),
        'auth_token' => env('TWILIO_AUTH_TOKEN'),
        'from_number' => env('TWILIO_FROM_NUMBER'), // Your Twilio phone number
    ],

    /*
    |--------------------------------------------------------------------------
    | AWS SNS Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for AWS Simple Notification Service (SNS).
    | Requires aws/aws-sdk-php package.
    |
    */

    'aws_sns' => [
        'access_key' => env('AWS_SNS_ACCESS_KEY_ID'),
        'secret_key' => env('AWS_SNS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_SNS_REGION', 'us-east-1'),
    ],

];

