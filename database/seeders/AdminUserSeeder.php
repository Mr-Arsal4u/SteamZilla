<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@steamzilla.com'],
            [
                'name' => 'Admin',
                'email' => 'admin@steamzilla.com',
                'password' => Hash::make('admin123'),
                'role' => 'admin',
            ]
        );

        $this->command->info('Admin user created successfully!');
        $this->command->info('Email: admin@steamzilla.com');
        $this->command->info('Password: admin123');
    }
}
