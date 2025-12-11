<?php

namespace Database\Seeders;

use App\Models\Addon;
use Illuminate\Database\Seeder;

class AddonSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $addons = [
            [
                'name' => 'Odor Neutralization Strike',
                'price' => 0.00, // Set your price
                'description' => 'Targeted attack on stubborn smells.',
                'category' => 'Interior',
                'is_active' => true,
                'has_quantity' => false,
            ],
            [
                'name' => 'Leather Lair Restoration',
                'price' => 0.00, // Set your price
                'description' => 'Steam clean & nourish leather seats.',
                'category' => 'Interior',
                'is_active' => true,
                'has_quantity' => false,
            ],
            [
                'name' => 'Headlight De-Yellowing',
                'price' => 0.00, // Set your price
                'description' => 'Steam-prep and optical clarity restoration.',
                'category' => 'Exterior',
                'is_active' => true,
                'has_quantity' => false,
            ],
            [
                'name' => 'Fabric Guard Application',
                'price' => 0.00, // Set your price
                'description' => 'Protect your seats after their deep clean.',
                'category' => 'Interior',
                'is_active' => true,
                'has_quantity' => false,
            ],
        ];

        foreach ($addons as $addon) {
            Addon::updateOrCreate(
                ['name' => $addon['name']],
                $addon
            );
        }
    }
}
