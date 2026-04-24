<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class SeatTypeFactory extends Factory
{
    public function definition(): array
    {
        $name = fake()->unique()->randomElement([
            'economy', 'business', 'sleeper', 'premium', 'vip',
        ]);

        return [
            'name'             => $name,
            'display_name'     => ucfirst($name) . ' Class',
            'description'      => fake()->sentence(),
            'price_multiplier' => fake()->randomFloat(2, 1.00, 2.50),
            'icon'             => 'fas fa-chair',
            'color_hex'        => fake()->hexColor(),
            'is_active'        => true,
        ];
    }

    // ------------------------------------------------------------------
    // STATES
    // ------------------------------------------------------------------

    public function economy(): static
    {
        return $this->state(fn () => [
            'name'             => 'economy',
            'display_name'     => 'Economy Class',
            'price_multiplier' => 1.00,
        ]);
    }

    public function business(): static
    {
        return $this->state(fn () => [
            'name'             => 'business',
            'display_name'     => 'Business Class',
            'price_multiplier' => 1.50,
        ]);
    }

    public function sleeper(): static
    {
        return $this->state(fn () => [
            'name'             => 'sleeper',
            'display_name'     => 'Sleeper Class',
            'price_multiplier' => 2.00,
        ]);
    }

    public function inactive(): static
    {
        return $this->state(fn () => ['is_active' => false]);
    }
}