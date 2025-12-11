<?php

namespace Database\Seeders;

use App\Models\GiftCard;
use Illuminate\Database\Seeder;

class GiftCardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $giftCards = [
            [
                'name' => '$50 Gift Card',
                'amount' => 50.00,
                'description' => 'Perfect for a standard package or add-ons',
                'is_active' => true,
            ],
            [
                'name' => '$100 Gift Card',
                'amount' => 100.00,
                'description' => 'Great for premium packages or multiple services',
                'is_active' => true,
            ],
            [
                'name' => '$150 Gift Card',
                'amount' => 150.00,
                'description' => 'Ideal for executive packages or gift bundles',
                'is_active' => true,
            ],
            [
                'name' => '$200 Gift Card',
                'amount' => 200.00,
                'description' => 'Maximum value for the ultimate cleaning experience',
                'is_active' => true,
            ],
        ];

        foreach ($giftCards as $giftCard) {
            GiftCard::create($giftCard);
        }
    }
}

