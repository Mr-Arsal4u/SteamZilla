<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\VehicleType;

class VehicleTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $vehicleTypes = [
            ['name' => 'Sedan', 'description' => 'Standard 4-door sedan', 'sort_order' => 1],
            ['name' => 'SUV', 'description' => 'Sport Utility Vehicle', 'sort_order' => 2],
            ['name' => 'Truck', 'description' => 'Pickup truck', 'sort_order' => 3],
            ['name' => 'Van', 'description' => 'Van or minivan', 'sort_order' => 4],
            ['name' => 'Coupe', 'description' => '2-door coupe', 'sort_order' => 5],
            ['name' => 'Convertible', 'description' => 'Convertible car', 'sort_order' => 6],
            ['name' => 'Hatchback', 'description' => 'Hatchback vehicle', 'sort_order' => 7],
            ['name' => 'Other', 'description' => 'Other vehicle type', 'sort_order' => 8],
        ];

        foreach ($vehicleTypes as $type) {
            VehicleType::firstOrCreate(
                ['name' => $type['name']],
                [
                    'description' => $type['description'],
                    'sort_order' => $type['sort_order'],
                    'is_active' => true,
                ]
            );
        }
    }
}
