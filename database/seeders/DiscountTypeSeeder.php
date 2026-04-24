<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DiscountType;

class DiscountTypeSeeder extends Seeder
{
    public function run(): void
    {
        $discounts = [
            [
                'name'         => 'regular',
                'display_name' => 'Regular',
                'percentage'   => 0.00,
                'description'  => 'No discount. Full fare applies.',
                'is_active'    => true,
            ],
            [
                'name'         => 'senior_citizen',
                'display_name' => 'Senior Citizen',
                'percentage'   => 0.20,
                'description'  => '20% discount for passengers 60 years old and above. Valid government-issued senior citizen ID required.',
                'is_active'    => true,
            ],
            [
                'name'         => 'pwd',
                'display_name' => 'Person with Disability (PWD)',
                'percentage'   => 0.20,
                'description'  => '20% discount for persons with disability. Valid PWD ID required.',
                'is_active'    => true,
            ],
            [
                'name'         => 'student',
                'display_name' => 'Student',
                'percentage'   => 0.20,
                'description'  => '20% discount for students. Valid school ID required.',
                'is_active'    => true,
            ],
        ];

        foreach ($discounts as $discount) {
            DiscountType::updateOrCreate(['name' => $discount['name']], $discount);
        }
    }
}