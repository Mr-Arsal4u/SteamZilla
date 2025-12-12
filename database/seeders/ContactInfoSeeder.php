<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;

class ContactInfoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Setting::set('contact_email', 'mrzilla89@thesteamzilla.com', 'text', 'general');
        Setting::set('contact_phone', '(413) 352-9444', 'text', 'general');
        
        $this->command->info('Contact information updated successfully!');
        $this->command->info('Email: mrzilla89@thesteamzilla.com');
        $this->command->info('Phone: (413) 352-9444');
    }
}
