<?php

namespace Database\Factories;

use App\Models\DiscountType;
use Illuminate\Database\Eloquent\Factories\Factory;

class DiscountTypeFactory extends Factory
{
    protected $model = DiscountType::class;

    public function definition(): array
    {
        return [
            'name'         => fake()->unique()->slug(2),
            'display_name' => fake()->words(2, true),
            'percentage'   => fake()->randomFloat(2, 0.05, 0.30),
            'description'  => fake()->sentence(),
            'is_active'    => true,
        ];
    }

    public function seniorCitizen(): static
    {
        return $this->state([
            'name'         => 'senior_citizen',
            'display_name' => 'Senior Citizen',
            'percentage'   => 0.20,
        ]);
    }

    public function pwd(): static
    {
        return $this->state([
            'name'         => 'pwd',
            'display_name' => 'Person with Disability (PWD)',
            'percentage'   => 0.20,
        ]);
    }

    public function student(): static
    {
        return $this->state([
            'name'         => 'student',
            'display_name' => 'Student',
            'percentage'   => 0.20,
        ]);
    }

    public function inactive(): static
    {
        return $this->state(fn () => ['is_active' => false]);
    }
}