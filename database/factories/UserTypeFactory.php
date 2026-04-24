<?php

namespace Database\Factories;

use App\Models\UserType;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserTypeFactory extends Factory
{
    protected $model = UserType::class;

    public function definition(): array
    {
        // Normally you'd use the seeder for the 3 fixed types,
        // but this factory is useful for testing edge cases.
        $name = fake()->unique()->randomElement(['admin', 'driver', 'customer']);

        return [
            'name'         => $name,
            'display_name' => ucfirst($name),
            'description'  => fake()->sentence(),
            'is_active'    => true,
        ];
    }

    public function inactive(): static
    {
        return $this->state(fn () => ['is_active' => false]);
    }
}