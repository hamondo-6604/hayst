<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class AmenityFactory extends Factory
{
    public function definition(): array
    {
        $category = fake()->randomElement([
            'comfort', 'connectivity', 'safety', 'service', 'entertainment',
        ]);

        return [
            'name'         => fake()->unique()->slug(2),
            'display_name' => fake()->words(2, true),
            'icon'         => 'fas fa-' . fake()->randomElement([
                'wifi', 'plug', 'snowflake', 'utensils', 'bed',
                'couch', 'shield-alt', 'tv', 'lightbulb', 'tint',
            ]),
            'description'  => fake()->sentence(),
            'category'     => $category,
            'is_active'    => true,
        ];
    }

    // ------------------------------------------------------------------
    // STATES
    // ------------------------------------------------------------------

    public function comfort(): static
    {
        return $this->state(fn () => ['category' => 'comfort']);
    }

    public function connectivity(): static
    {
        return $this->state(fn () => ['category' => 'connectivity']);
    }

    public function safety(): static
    {
        return $this->state(fn () => ['category' => 'safety']);
    }

    public function service(): static
    {
        return $this->state(fn () => ['category' => 'service']);
    }

    public function entertainment(): static
    {
        return $this->state(fn () => ['category' => 'entertainment']);
    }

    public function inactive(): static
    {
        return $this->state(fn () => ['is_active' => false]);
    }
}