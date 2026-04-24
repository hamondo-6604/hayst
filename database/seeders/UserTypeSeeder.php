<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\UserType;

class UserTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            [
                'name'         => 'admin',
                'display_name' => 'Administrator',
                'description'  => 'Full system access. Manages buses, routes, schedules, users, and reports.',
                'is_active'    => true,
            ],
            [
                'name'         => 'driver',
                'display_name' => 'Bus Driver',
                'description'  => 'Assigned to trips and schedules. Can view their own trip details.',
                'is_active'    => true,
            ],
            [
                'name'         => 'customer',
                'display_name' => 'Customer',
                'description'  => 'Passengers who book seats on available trips.',
                'is_active'    => true,
            ],
        ];

        foreach ($types as $type) {
            UserType::updateOrCreate(['name' => $type['name']], $type);
        }
    }
}