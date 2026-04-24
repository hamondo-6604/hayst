<?php

namespace Database\Factories;

use App\Models\SeatLayout;
use App\Models\SeatType;
use Illuminate\Database\Eloquent\Factories\Factory;

class LayoutMapFactory extends Factory
{
    public function definition(): array
    {
        $layout    = SeatLayout::inRandomOrder()->first() ?? SeatLayout::factory()->create();
        $row       = fake()->numberBetween(1, $layout->total_rows);
        $col       = fake()->numberBetween(1, $layout->total_columns);
        $seatLabel = $row . chr(64 + $col); // e.g. 1A, 3B

        return [
            'seat_layout_id' => $layout->id,
            'seat_type_id'   => SeatType::inRandomOrder()->first()?->id,
            'row_number'     => $row,
            'column_number'  => $col,
            'seat_label'     => $seatLabel,
            'cell_type'      => 'seat',
            'is_bookable'    => true,
        ];
    }

    // ------------------------------------------------------------------
    // STATES
    // ------------------------------------------------------------------

    public function aisle(): static
    {
        return $this->state(fn () => [
            'cell_type'   => 'aisle',
            'is_bookable' => false,
            'seat_type_id'=> null,
        ]);
    }

    public function empty(): static
    {
        return $this->state(fn () => [
            'cell_type'   => 'empty',
            'is_bookable' => false,
            'seat_type_id'=> null,
        ]);
    }

    public function driver(): static
    {
        return $this->state(fn () => [
            'cell_type'   => 'driver',
            'is_bookable' => false,
            'seat_label'  => 'DRV',
            'seat_type_id'=> null,
        ]);
    }

    public function economy(): static
    {
        return $this->state(fn () => [
            'seat_type_id' => SeatType::where('name', 'economy')->first()?->id,
        ]);
    }

    public function business(): static
    {
        return $this->state(fn () => [
            'seat_type_id' => SeatType::where('name', 'business')->first()?->id,
        ]);
    }
}