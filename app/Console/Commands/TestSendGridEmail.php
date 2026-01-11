<?php

namespace App\Console\Commands;

use App\Services\EmailService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;

class TestSendGridEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:test-sendgrid {email?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test SendGrid email configuration by sending a test email';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🧪 Testing SendGrid Email Configuration...');
        $this->newLine();

        // Get email address
        $email = $this->argument('email') ?? $this->ask('Enter email address to send test email to');

        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->error('❌ Invalid email address!');
            return 1;
        }

        // Display configuration
        $this->info('📋 Current Configuration:');
        $this->line('   Mailer: ' . config('mail.default'));
        $this->line('   Host: ' . config('mail.mailers.smtp.host'));
        $this->line('   Port: ' . config('mail.mailers.smtp.port'));
        $this->line('   Username: ' . config('mail.mailers.smtp.username'));
        $this->line('   From Address: ' . config('mail.from.address'));
        $this->line('   From Name: ' . config('mail.from.name'));
        $this->line('   Admin Email: ' . (config('mail.admin_email') ?: 'Not set'));
        $this->newLine();

        // Check if SendGrid is configured
        if (config('mail.mailers.smtp.host') !== 'smtp.sendgrid.net') {
            $this->warn('⚠️  Warning: Mail host is not set to smtp.sendgrid.net');
            $this->warn('   Current host: ' . config('mail.mailers.smtp.host'));
            $this->newLine();
        }

        if (config('mail.mailers.smtp.username') !== 'apikey') {
            $this->warn('⚠️  Warning: MAIL_USERNAME should be "apikey"');
            $this->warn('   Current username: ' . config('mail.mailers.smtp.username'));
            $this->newLine();
        }

        // Send test email
        $this->info('📧 Sending test email to: ' . $email);
        $this->newLine();

        try {
            $result = EmailService::sendTestEmail($email);

            if ($result) {
                $this->info('✅ Test email sent successfully!');
                $this->info('   Please check your inbox (and spam folder) at: ' . $email);
                $this->newLine();
                $this->info('💡 Next Steps:');
                $this->line('   1. Check your email inbox');
                $this->line('   2. Check spam/junk folder if not received');
                $this->line('   3. Verify sender email in SendGrid dashboard');
                $this->line('   4. Check SendGrid Activity feed for delivery status');
                return 0;
            } else {
                $this->error('❌ Failed to send test email');
                $this->error('   Check logs: storage/logs/laravel.log');
                return 1;
            }
        } catch (\Exception $e) {
            $this->error('❌ Error sending email: ' . $e->getMessage());
            $this->error('   Check logs: storage/logs/laravel.log');
            $this->newLine();
            $this->warn('💡 Troubleshooting:');
            $this->line('   1. Verify SendGrid API key is correct');
            $this->line('   2. Check MAIL_USERNAME is exactly "apikey"');
            $this->line('   3. Verify sender email is verified in SendGrid');
            $this->line('   4. Check SendGrid account has credits');
            return 1;
        }
    }
}

