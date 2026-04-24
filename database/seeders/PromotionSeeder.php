<?php

namespace Database\Seeders;

use App\Models\Promotion;
use Illuminate\Database\Seeder;

class PromotionSeeder extends Seeder
{
    public function run(): void
    {
        $promotions = [
            [
                'code'              => 'WELCOME20',
                'name'              => 'Welcome Discount',
                'description'       => '20% off your first booking.',
                'discount_type'     => 'percent',
                'discount_value'    => 20.00,
                'minimum_fare'      => 200.00,
                'maximum_discount'  => 300.00,
                'max_uses'          => null,
                'max_uses_per_user' => 1,
                'starts_at'         => now(),
                'expires_at'        => now()->addYear(),
                'is_active'         => true,
            ],
            [
                'code'              => 'SUMMER100',
                'name'              => 'Summer Sale',
                'description'       => '₱100 off on all trips this summer.',
                'discount_type'     => 'fixed',
                'discount_value'    => 100.00,
                'minimum_fare'      => 300.00,
                'maximum_discount'  => null,
                'max_uses'          => 200,
                'max_uses_per_user' => 1,
                'starts_at'         => now(),
                'expires_at'        => now()->addMonths(3),
                'is_active'         => true,
            ],
            [
                'code'              => 'EARLYBIRD',
                'name'              => 'Early Bird Promo',
                'description'       => '15% off when you book 7 days in advance.',
                'discount_type'     => 'percent',
                'discount_value'    => 15.00,
                'minimum_fare'      => 250.00,
                'maximum_discount'  => 200.00,
                'max_uses'          => 100,
                'max_uses_per_user' => 2,
                'starts_at'         => now(),
                'expires_at'        => now()->addMonths(6),
                'is_active'         => true,
            ],
            [
                'code'              => 'EXPIRED50',
                'name'              => 'Old Promo (Expired)',
                'description'       => 'This promo has already ended.',
                'discount_type'     => 'fixed',
                'discount_value'    => 50.00,
                'minimum_fare'      => 150.00,
                'maximum_discount'  => null,
                'max_uses'          => 50,
                'max_uses_per_user' => 1,
                'starts_at'         => now()->subMonths(6),
                'expires_at'        => now()->subDay(),
                'is_active'         => false,
            ],
        ];

        foreach ($promotions as $promo) {
            Promotion::updateOrCreate(['code' => $promo['code']], $promo);
        }
    }
}