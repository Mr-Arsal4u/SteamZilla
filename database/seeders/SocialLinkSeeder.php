<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\SocialLink;

class SocialLinkSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $socialLinks = [
            [
                'platform' => 'Facebook',
                'url' => 'https://www.facebook.com',
                'icon' => 'fab fa-facebook',
                'sort_order' => 1,
                'is_active' => false, // Set to false so admin can add real links
            ],
            [
                'platform' => 'Instagram',
                'url' => 'https://www.instagram.com',
                'icon' => 'fab fa-instagram',
                'sort_order' => 2,
                'is_active' => false,
            ],
            [
                'platform' => 'Twitter',
                'url' => 'https://www.twitter.com',
                'icon' => 'fab fa-twitter',
                'sort_order' => 3,
                'is_active' => false,
            ],
        ];

        foreach ($socialLinks as $link) {
            SocialLink::firstOrCreate(
                ['platform' => $link['platform']],
                $link
            );
        }
    }
}
