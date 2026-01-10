<?php

/**
 * Quick SendGrid Email Test Script
 * 
 * This script tests your SendGrid configuration
 * Run: php test-sendgrid.php your-email@example.com
 */

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Get email from command line argument
$testEmail = $argv[1] ?? null;

if (!$testEmail) {
    echo "❌ Please provide an email address to test\n";
    echo "Usage: php test-sendgrid.php your-email@example.com\n";
    exit(1);
}

if (!filter_var($testEmail, FILTER_VALIDATE_EMAIL)) {
    echo "❌ Invalid email address: $testEmail\n";
    exit(1);
}

echo "🧪 Testing SendGrid Email Configuration...\n\n";

// Set SendGrid credentials temporarily
config([
    'mail.default' => 'smtp',
    'mail.mailers.smtp' => [
        'transport' => 'smtp',
        'host' => 'smtp.sendgrid.net',
        'port' => 587,
        'username' => 'apikey',
        'password' => 'SG.your_sendgrid_api_key_here',
        'encryption' => 'tls',
        'timeout' => null,
    ],
    'mail.from' => [
        'address' => env('MAIL_FROM_ADDRESS', 'mr.arsal69@gmail.com'), // Use verified email
        'name' => env('MAIL_FROM_NAME', 'SteamZilla'),
    ],
]);

echo "📋 Configuration:\n";
echo "   Host: " . config('mail.mailers.smtp.host') . "\n";
echo "   Port: " . config('mail.mailers.smtp.port') . "\n";
echo "   Username: " . config('mail.mailers.smtp.username') . "\n";
echo "   From: " . config('mail.from.address') . "\n";
echo "\n";

echo "📧 Sending test email to: $testEmail\n\n";

try {
    \Illuminate\Support\Facades\Mail::raw(
        "This is a test email from SteamZilla.\n\n" .
        "Your SendGrid configuration is working correctly!\n\n" .
        "Configuration Details:\n" .
        "- Host: smtp.sendgrid.net\n" .
        "- Port: 587\n" .
        "- Encryption: TLS\n\n" .
        "If you received this email, your SendGrid integration is successful! ✅",
        function ($message) use ($testEmail) {
            $message->to($testEmail)
                    ->subject('SteamZilla - SendGrid Test Email');
        }
    );
    
    echo "✅ Test email sent successfully!\n";
    echo "   Please check your inbox (and spam folder) at: $testEmail\n";
    echo "\n";
    echo "💡 Next Steps:\n";
    echo "   1. Check your email inbox\n";
    echo "   2. Check spam/junk folder if not received\n";
    echo "   3. Add these credentials to your .env file:\n";
    echo "\n";
    echo "MAIL_MAILER=smtp\n";
    echo "MAIL_HOST=smtp.sendgrid.net\n";
    echo "MAIL_PORT=587\n";
    echo "MAIL_USERNAME=apikey\n";
    echo "MAIL_PASSWORD=SG.your_sendgrid_api_key_here\n";
    echo "MAIL_ENCRYPTION=tls\n";
    echo "MAIL_FROM_ADDRESS=noreply@yourdomain.com\n";
    echo "MAIL_FROM_NAME=\"SteamZilla\"\n";
    echo "MAIL_ADMIN_EMAIL=admin@yourdomain.com\n";
    
} catch (\Exception $e) {
    echo "❌ Error sending email: " . $e->getMessage() . "\n";
    echo "\n";
    echo "💡 Troubleshooting:\n";
    echo "   1. Verify SendGrid API key is correct\n";
    echo "   2. Check MAIL_USERNAME is exactly 'apikey'\n";
    echo "   3. Verify sender email is verified in SendGrid\n";
    echo "   4. Check SendGrid account has credits\n";
    echo "   5. Check logs: storage/logs/laravel.log\n";
    exit(1);
}

