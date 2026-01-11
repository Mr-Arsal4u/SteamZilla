<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed admin user
        $this->call(AdminUserSeeder::class);

        // Seed packages, addons, gift cards, vehicle types, time slots, and social links
        $this->call([
            PackageSeeder::class,
            AddonSeeder::class,
            GiftCardSeeder::class,
            VehicleTypeSeeder::class,
            TimeSlotSeeder::class,
            SocialLinkSeeder::class,
        ]);
    }
}
