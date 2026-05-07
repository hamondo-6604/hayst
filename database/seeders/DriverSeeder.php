<?php

namespace Database\Seeders;

use App\Models\Driver;
use App\Models\User;
use Illuminate\Database\Seeder;

class DriverSeeder extends Seeder
{
    public function run(): void
    {
        // Create profile for the fixed test driver user
        $fixedDriverUser = User::where('email', 'driver@busco.ph')->first();

        if ($fixedDriverUser && ! $fixedDriverUser->driver) {
            Driver::create([
                'user_id'          => $fixedDriverUser->id,
                'license_number'   => 'PROF-01-2024-001',
                'license_expiry'   => now()->addYears(3)->toDateString(),
                'experience_years' => 8,
                'contact_number'   => $fixedDriverUser->phone,
                'address'          => '123 Rizal St., Manila',
                'status'           => 'available',
            ]);
        }

        // Create driver profiles for all users with role = 'driver'
        // who do not already have a driver profile
        $driverUsers = User::where('role', 'driver')
            ->whereDoesntHave('driver')
            ->get();

        foreach ($driverUsers as $user) {
            Driver::create([
                'user_id'          => $user->id,
                'license_number'   => 'PROF-' . str_pad($user->id, 4, '0', STR_PAD_LEFT),
                'license_expiry'   => fake()->dateTimeBetween('+1 year', '+5 years')->format('Y-m-d'),
                'experience_years' => fake()->numberBetween(1, 15),
                'contact_number'   => $user->phone ?? fake()->numerify('09#########'),
                'address'          => fake()->address(),
                'status'           => 'available',
            ]);
        }
    }
}