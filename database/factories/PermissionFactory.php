<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PermissionFactory extends Factory
{
    // Mirrors the groups defined in PermissionSeeder
    private static array $groups = [
        'users', 'buses', 'trips', 'bookings',
        'payments', 'routes', 'drivers', 'maintenance',
        'promotions', 'feedback', 'reports',
    ];

    private static array $actions = [
        'view', 'create', 'edit', 'delete',
    ];

    public function definition(): array
    {
        $group  = fake()->randomElement(self::$groups);
        $action = fake()->randomElement(self::$actions);

        return [
            'name'         => $group . '.' . $action . '_' . fake()->unique()->numberBetween(1, 9999),
            'display_name' => ucfirst($action) . ' ' . ucfirst($group),
            'group'        => $group,
            'description'  => fake()->sentence(),
        ];
    }

    // ------------------------------------------------------------------
    // STATES
    // ------------------------------------------------------------------

    public function forGroup(string $group): static
    {
        return $this->state(fn () => ['group' => $group]);
    }

    public function viewOnly(): static
    {
        return $this->state(fn () => [
            'name'   => fake()->randomElement(self::$groups) . '.view',
            'display_name' => 'View ' . ucfirst(fake()->randomElement(self::$groups)),
        ]);
    }
}