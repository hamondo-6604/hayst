<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PromotionFactory extends Factory
{
    public function definition(): array
    {
        $type = fake()->randomElement(['percent', 'fixed']);

        return [
            'code'             => strtoupper(fake()->bothify('????##')),
            'name'             => fake()->randomElement([
                'Summer Sale', 'Holiday Promo', 'Early Bird',
                'Weekend Deal', 'Loyalty Discount', 'New User Promo',
            ]),
            'description'      => fake()->sentence(),
            'discount_type'    => $type,
            'discount_value'   => $type === 'percent'
                                    ? fake()->randomFloat(2, 5, 30)    // 5%–30% off
                                    : fake()->randomFloat(2, 50, 300), // ₱50–₱300 off
            'minimum_fare'     => fake()->randomFloat(2, 200, 500),
            'maximum_discount' => $type === 'percent'
                                    ? fake()->randomFloat(2, 100, 500)
                                    : null,
            'max_uses'         => fake()->randomElement([50, 100, 200, null]),
            'used_count'       => 0,
            'max_uses_per_user'=> 1,
            'starts_at'        => now(),
            'expires_at'       => fake()->dateTimeBetween('+30 days', '+180 days'),
            'is_active'        => true,
        ];
    }

    // ------------------------------------------------------------------
    // STATES
    // ------------------------------------------------------------------

    public function expired(): static
    {
        return $this->state(fn () => [
            'starts_at'  => now()->subMonths(3),
            'expires_at' => now()->subDay(),
        ]);
    }

    public function inactive(): static
    {
        return $this->state(fn () => ['is_active' => false]);
    }

    public function percentDiscount(float $percent = 10.0): static
    {
        return $this->state(fn () => [
            'discount_type'  => 'percent',
            'discount_value' => $percent,
        ]);
    }

    public function fixedDiscount(float $amount = 100.0): static
    {
        return $this->state(fn () => [
            'discount_type'  => 'fixed',
            'discount_value' => $amount,
        ]);
    }
}