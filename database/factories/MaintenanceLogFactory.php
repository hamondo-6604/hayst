<?php

namespace Database\Factories;

use App\Models\Bus;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class MaintenanceLogFactory extends Factory
{
    public function definition(): array
    {
        $maintenanceDate = fake()->dateTimeBetween('-6 months', '+1 month');
        $isCompleted     = fake()->boolean(70);

        return [
            'bus_id'               => Bus::inRandomOrder()->first()?->id ?? Bus::factory(),
            'logged_by'            => User::where('role', 'admin')->inRandomOrder()->first()?->id,
            'title'                => fake()->randomElement([
                'Oil Change', 'Brake Inspection', 'Tire Rotation',
                'Engine Check', 'Air Filter Replacement', 'Transmission Service',
                'Battery Replacement', 'Coolant Flush', 'Full Inspection',
            ]),
            'description'          => fake()->paragraph(),
            'type'                 => fake()->randomElement(['preventive', 'corrective', 'emergency']),
            'status'               => $isCompleted ? 'completed' : fake()->randomElement(['scheduled', 'in_progress']),
            'maintenance_date'     => $maintenanceDate->format('Y-m-d'),
            'completed_date'       => $isCompleted
                                        ? fake()->dateTimeBetween($maintenanceDate, 'now')->format('Y-m-d')
                                        : null,
            'cost'                 => $isCompleted ? fake()->randomFloat(2, 500, 15000) : null,
            'performed_by'         => fake()->company() . ' Auto Shop',
            'parts_replaced'       => fake()->randomElement([
                null, 'Oil filter, engine oil',
                'Brake pads', 'Air filter',
                'Battery', 'Spark plugs, air filter',
            ]),
            'next_maintenance_due' => fake()->dateTimeBetween('+1 month', '+6 months')->format('Y-m-d'),
        ];
    }

    public function scheduled(): static
    {
        return $this->state(fn () => [
            'status'         => 'scheduled',
            'completed_date' => null,
            'cost'           => null,
        ]);
    }

    public function completed(): static
    {
        return $this->state(fn () => [
            'status'         => 'completed',
            'completed_date' => now()->toDateString(),
            'cost'           => fake()->randomFloat(2, 500, 15000),
        ]);
    }

    public function emergency(): static
    {
        return $this->state(fn () => [
            'type'  => 'emergency',
            'title' => 'Emergency Breakdown Repair',
        ]);
    }
}

