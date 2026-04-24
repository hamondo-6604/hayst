<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class SeatLayoutFactory extends Factory
{
    public function definition(): array
    {
        $rows    = fake()->numberBetween(8, 14);
        $columns = fake()->randomElement([2, 4]);   // 2-seat or 4-seat rows

        return [
            'layout_name'  => $rows . 'x' . $columns . ' Standard',
            'total_rows'   => $rows,
            'total_columns'=> $columns,
            'capacity'     => $rows * $columns,
            'layout_map'   => $this->generateLayoutMap($rows, $columns),
            'status'       => 'active',
            'description'  => 'Standard ' . ($rows * $columns) . '-seat layout',
        ];
    }

    // ------------------------------------------------------------------
    // STATES
    // ------------------------------------------------------------------

    public function standard(): static
    {
        return $this->state(fn () => [
            'layout_name'   => '10x4 Standard',
            'total_rows'    => 10,
            'total_columns' => 4,
            'capacity'      => 40,
            'layout_map'    => $this->generateLayoutMap(10, 4),
        ]);
    }

    public function minibus(): static
    {
        return $this->state(fn () => [
            'layout_name'   => '6x2 Mini',
            'total_rows'    => 6,
            'total_columns' => 2,
            'capacity'      => 12,
            'layout_map'    => $this->generateLayoutMap(6, 2),
        ]);
    }

    // ------------------------------------------------------------------
    // HELPERS
    // ------------------------------------------------------------------

    private function generateLayoutMap(int $rows, int $columns): array
    {
        $map  = [];
        $cols = range('A', chr(ord('A') + $columns - 1));

        for ($r = 1; $r <= $rows; $r++) {
            $rowSeats = [];
            foreach ($cols as $col) {
                $rowSeats[] = [
                    'seat'   => $r . $col,
                    'type'   => 'regular',
                    'status' => 'available',
                ];
            }
            $map[] = $rowSeats;
        }

        return $map;
    }
}