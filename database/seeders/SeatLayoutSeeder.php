<?php

namespace Database\Seeders;

use App\Models\SeatLayout;
use Illuminate\Database\Seeder;

class SeatLayoutSeeder extends Seeder
{
    public function run(): void
    {
        $layouts = [
            [
                'layout_name'   => '10x4 Standard',
                'total_rows'    => 10,
                'total_columns' => 4,
                'capacity'      => 40,
                'status'        => 'active',
                'description'   => 'Standard 40-seat layout with 4 seats per row (2+2).',
            ],
            [
                'layout_name'   => '12x4 Express',
                'total_rows'    => 12,
                'total_columns' => 4,
                'capacity'      => 48,
                'status'        => 'active',
                'description'   => 'High-capacity 48-seat express bus layout.',
            ],
            [
                'layout_name'   => '8x4 Deluxe',
                'total_rows'    => 8,
                'total_columns' => 4,
                'capacity'      => 32,
                'status'        => 'active',
                'description'   => 'Deluxe 32-seat layout with wider legroom.',
            ],
            [
                'layout_name'   => '6x2 Mini',
                'total_rows'    => 6,
                'total_columns' => 2,
                'capacity'      => 12,
                'status'        => 'active',
                'description'   => 'Minibus 12-seat layout.',
            ],
        ];

        foreach ($layouts as $layoutData) {
            $layout = SeatLayout::updateOrCreate(
                ['layout_name' => $layoutData['layout_name']],
                $layoutData
            );

            // Generate layout_map if not yet set
            if (empty($layout->layout_map)) {
                $layout->layout_map = $this->generateLayoutMap(
                    $layout->total_rows,
                    $layout->total_columns
                );
                $layout->save();
            }
        }
    }

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