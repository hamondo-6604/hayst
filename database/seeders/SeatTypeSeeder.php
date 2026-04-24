<?php

namespace Database\Seeders;

use App\Models\SeatType;
use Illuminate\Database\Seeder;

class SeatTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            [
                'name'             => 'economy',
                'display_name'     => 'Economy Class',
                'description'      => 'Standard comfortable seating at the most affordable fare.',
                'price_multiplier' => 1.00,
                'icon'             => 'fas fa-chair',
                'color_hex'        => '#059669',
                'is_active'        => true,
            ],
            [
                'name'             => 'business',
                'display_name'     => 'Business Class',
                'description'      => 'Extra legroom with reclining seats and USB charging ports.',
                'price_multiplier' => 1.50,
                'icon'             => 'fas fa-couch',
                'color_hex'        => '#b8912a',
                'is_active'        => true,
            ],
            [
                'name'             => 'sleeper',
                'display_name'     => 'Sleeper Class',
                'description'      => 'Lie-flat seats with blanket, pillow, and personal reading light. Ideal for overnight trips.',
                'price_multiplier' => 2.00,
                'icon'             => 'fas fa-bed',
                'color_hex'        => '#7c3aed',
                'is_active'        => true,
            ],
            [
                'name'             => 'premium',
                'display_name'     => 'Premium Class',
                'description'      => 'Front-of-bus premium seating with wider armrests and priority boarding.',
                'price_multiplier' => 1.80,
                'icon'             => 'fas fa-star',
                'color_hex'        => '#0e1117',
                'is_active'        => true,
            ],
        ];

        foreach ($types as $type) {
            SeatType::updateOrCreate(['name' => $type['name']], $type);
        }
    }
}