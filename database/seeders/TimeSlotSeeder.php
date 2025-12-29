<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\TimeSlot;

class TimeSlotSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $timeSlots = [
            ['start_time' => '08:00', 'end_time' => null, 'label' => '8:00 AM', 'sort_order' => 1],
            ['start_time' => '09:00', 'end_time' => null, 'label' => '9:00 AM', 'sort_order' => 2],
            ['start_time' => '10:00', 'end_time' => null, 'label' => '10:00 AM', 'sort_order' => 3],
            ['start_time' => '11:00', 'end_time' => null, 'label' => '11:00 AM', 'sort_order' => 4],
            ['start_time' => '12:00', 'end_time' => null, 'label' => '12:00 PM', 'sort_order' => 5],
            ['start_time' => '13:00', 'end_time' => null, 'label' => '1:00 PM', 'sort_order' => 6],
            ['start_time' => '14:00', 'end_time' => null, 'label' => '2:00 PM', 'sort_order' => 7],
            ['start_time' => '15:00', 'end_time' => null, 'label' => '3:00 PM', 'sort_order' => 8],
            ['start_time' => '16:00', 'end_time' => null, 'label' => '4:00 PM', 'sort_order' => 9],
            ['start_time' => '17:00', 'end_time' => null, 'label' => '5:00 PM', 'sort_order' => 10],
            ['start_time' => '18:00', 'end_time' => null, 'label' => '6:00 PM', 'sort_order' => 11],
        ];

        foreach ($timeSlots as $slot) {
            TimeSlot::firstOrCreate(
                ['start_time' => $slot['start_time']],
                [
                    'end_time' => $slot['end_time'],
                    'label' => $slot['label'],
                    'sort_order' => $slot['sort_order'],
                    'is_active' => true,
                ]
            );
        }
    }
}
