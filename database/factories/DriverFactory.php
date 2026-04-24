<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class DriverFactory extends Factory
{
    public function definition(): array
    {
        return [
            // Must pass a user with role='driver' — the seeder handles this
            'user_id'          => User::factory()->driver(),
            'license_number'   => strtoupper(fake()->bothify('??-##-######')),
            'license_expiry'   => fake()->dateTimeBetween('+6 months', '+5 years')->format('Y-m-d'),
            'license_photo'    => null,
            'experience_years' => fake()->numberBetween(1, 20),
            'contact_number'   => fake()->numerify('09#########'),
            'address'          => fake()->address(),
            'status'           => 'available',
        ];
    }

    // ------------------------------------------------------------------
    // STATES
    // ------------------------------------------------------------------

    public function onTrip(): static
    {
        return $this->state(fn () => ['status' => 'on_trip']);
    }

    public function offDuty(): static
    {
        return $this->state(fn () => ['status' => 'off_duty']);
    }

    public function suspended(): static
    {
        return $this->state(fn () => ['status' => 'suspended']);
    }

    public function expiredLicense(): static
    {
        return $this->state(fn () => [
            'license_expiry' => fake()->dateTimeBetween('-2 years', '-1 day')->format('Y-m-d'),
        ]);
    }
}