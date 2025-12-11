<?php

namespace Database\Seeders;

use App\Models\Package;
use Illuminate\Database\Seeder;

class PackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $packages = [
            [
                'name' => 'MISSION: INTERIOR OBLITERATION',
                'price' => 0.00, // Set your price
                'duration' => '2-3 hours',
                'description' => 'The ultimate deep clean for your cabin.',
                'features' => [
                    'Full Interior Steam Sanitization (Seats, Carpets, Headliner*)',
                    'Steam-Cleaned Dash, Console, Door Panels & Vents',
                    'Door Jambs & Trunk Seal Detail',
                    'Interior Glass Cleaned',
                    'Perfect for: Regular maintenance, allergy sufferers, pre-sale prep.'
                ],
                'is_active' => true,
            ],
            [
                'name' => 'MISSION: TOTAL DOMINATION (BEST VALUE)',
                'price' => 0.00, // Set your price
                'duration' => '3-4 hours',
                'description' => 'Complete inside & out transformation.',
                'features' => [
                    'Everything in Interior Obliteration, PLUS:',
                    'Exterior Hand Wash & Dry',
                    'Tire & Wheel Deep Clean',
                    'Steam-Blasted Engine Bay Degrease (Safe & Professional)*',
                    'High-Gloss Spray Sealant',
                    'Perfect for: The full showroom revival.'
                ],
                'is_active' => true,
            ],
            [
                'name' => 'MISSION: ENGINE BAY UNLEASHED',
                'price' => 0.00, // Set your price
                'duration' => '1-2 hours',
                'description' => 'Reveal the heart of your machine.',
                'features' => [
                    'Safe, low-pressure steam degreasing of entire engine bay',
                    'Dressing of plastics & rubber',
                    'Removes grime that causes corrosion and traps heat',
                    '(Service performed on cool engines with sensitive components protected)*'
                ],
                'is_active' => true,
            ],
        ];

        foreach ($packages as $package) {
            Package::updateOrCreate(
                ['name' => $package['name']],
                $package
            );
        }
    }
}
