<?php

namespace Database\Seeders;

use App\Models\BusType;
use App\Models\SeatLayout;
use Illuminate\Database\Seeder;

class BusTypeSeeder extends Seeder
{
    public function run(): void
    {
        $standard = SeatLayout::where('layout_name', '10x4 Standard')->first();
        $express  = SeatLayout::where('layout_name', '12x4 Express')->first();
        $deluxe   = SeatLayout::where('layout_name', '8x4 Deluxe')->first();
        $mini     = SeatLayout::where('layout_name', '6x2 Mini')->first();

        $types = [
            [
                'type_name'      => 'Ordinary',
                'seat_layout_id' => $standard?->id,
                'status'         => 'active',
                'description'    => 'Standard non-aircon bus. Basic seating, affordable fares.',
            ],
            [
                'type_name'      => 'Aircon',
                'seat_layout_id' => $standard?->id,
                'status'         => 'active',
                'description'    => 'Air-conditioned bus with standard seating.',
            ],
            [
                'type_name'      => 'Express',
                'seat_layout_id' => $express?->id,
                'status'         => 'active',
                'description'    => 'High-capacity express bus with limited stops.',
            ],
            [
                'type_name'      => 'Deluxe',
                'seat_layout_id' => $deluxe?->id,
                'status'         => 'active',
                'description'    => 'Deluxe aircon bus with extra legroom and reclining seats.',
            ],
            [
                'type_name'      => 'Mini Bus',
                'seat_layout_id' => $mini?->id,
                'status'         => 'active',
                'description'    => 'Small minibus for short-distance or feeder routes.',
            ],
        ];

        foreach ($types as $type) {
            BusType::updateOrCreate(['type_name' => $type['type_name']], $type);
        }
    }
}