<?php

namespace Database\Seeders;

use App\Models\Bus;
use App\Models\BusType;
use App\Models\LayoutMap;
use App\Models\Seat;
use App\Models\SeatLayout;
use App\Models\SeatType;
use Illuminate\Database\Seeder;

class BusSeeder extends Seeder
{
    public function run(): void
    {
        // Resolve seat type IDs — SeatTypeSeeder must run first
        $seatTypes = SeatType::pluck('id', 'name'); // ['economy' => 1, 'business' => 2, ...]

        $buses = [
            ['bus_number' => 'BUS-001', 'bus_name' => 'Victory Liner 1',   'type' => 'Aircon',   'seat_type' => 'economy'],
            ['bus_number' => 'BUS-002', 'bus_name' => 'Victory Liner 2',   'type' => 'Aircon',   'seat_type' => 'economy'],
            ['bus_number' => 'BUS-003', 'bus_name' => 'Philtranco Express', 'type' => 'Express',  'seat_type' => 'economy'],
            ['bus_number' => 'BUS-004', 'bus_name' => 'Genesis Deluxe 1',  'type' => 'Deluxe',   'seat_type' => 'business'],
            ['bus_number' => 'BUS-005', 'bus_name' => 'Genesis Deluxe 2',  'type' => 'Deluxe',   'seat_type' => 'business'],
            ['bus_number' => 'BUS-006', 'bus_name' => 'Five Star Ordinary', 'type' => 'Ordinary', 'seat_type' => 'economy'],
            ['bus_number' => 'BUS-007', 'bus_name' => 'Florida Mini 1',    'type' => 'Mini Bus',  'seat_type' => 'economy'],
            ['bus_number' => 'BUS-008', 'bus_name' => 'Batangas Star 1',   'type' => 'Aircon',   'seat_type' => 'economy'],
            ['bus_number' => 'BUS-009', 'bus_name' => 'Genesis Premier 1', 'type' => 'Deluxe',   'seat_type' => 'premium'],
            ['bus_number' => 'BUS-010', 'bus_name' => 'Partas Sleeper 1',  'type' => 'Express',  'seat_type' => 'sleeper'],
        ];

        foreach ($buses as $busData) {
            $busType = BusType::where('type_name', $busData['type'])->first();
            $layout  = $busType?->seatLayout ?? SeatLayout::first();

            if (! $busType || ! $layout) {
                $this->command->warn("BusSeeder: type '{$busData['type']}' not found — skipping {$busData['bus_number']}.");
                continue;
            }

            $seatTypeId = $seatTypes[$busData['seat_type']] ?? null;

            $bus = Bus::updateOrCreate(
                ['bus_number' => $busData['bus_number']],
                [
                    'bus_name'              => $busData['bus_name'],
                    'bus_type_id'           => $busType->id,
                    'seat_layout_id'        => $layout->id,
                    'total_seats'           => $layout->effective_capacity,
                    'default_seat_type'     => $busData['seat_type'],
                    'default_seat_type_id'  => $seatTypeId,
                    'status'                => 'active',
                ]
            );

            // Generate LayoutMap rows if none exist for this layout yet
            if (LayoutMap::where('seat_layout_id', $layout->id)->doesntExist()) {
                $this->generateLayoutMaps($layout, $seatTypes);
            }

            // Generate Seat rows for this bus if none exist yet
            if ($bus->seats()->doesntExist()) {
                $this->generateSeats($bus, $layout, $seatTypeId);
            }
        }

        $this->command->info('BusSeeder: seeded ' . Bus::count() . ' buses, ' . Seat::count() . ' seats.');
    }

    /**
     * Create LayoutMap rows from the seat_layout's JSON layout_map.
     * One row per grid cell — this is the relational source of truth.
     */
    private function generateLayoutMaps(SeatLayout $layout, $seatTypes): void
    {
        $rows = [];

        foreach ($layout->layout_map ?? [] as $rowIndex => $rowSeats) {
            foreach ($rowSeats as $colIndex => $seatData) {
                $label    = $seatData['seat'] ?? (($rowIndex + 1) . chr(65 + $colIndex));
                $cellType = $seatData['type'] === 'aisle' ? 'aisle' : 'seat';

                $rows[] = [
                    'seat_layout_id' => $layout->id,
                    'seat_type_id'   => $seatTypes['economy'] ?? null,
                    'row_number'     => $rowIndex + 1,
                    'column_number'  => $colIndex + 1,
                    'seat_label'     => $label,
                    'cell_type'      => $cellType,
                    'is_bookable'    => $cellType === 'seat',
                    'created_at'     => now(),
                    'updated_at'     => now(),
                ];
            }
        }

        foreach (array_chunk($rows, 100) as $chunk) {
            LayoutMap::insert($chunk);
        }

        // Sync the JSON cache back from the new rows
        $layout->rebuildJsonCache();
    }

    /**
     * Create Seat rows for a bus, inheriting the layout's grid.
     */
    private function generateSeats(Bus $bus, SeatLayout $layout, ?int $seatTypeId): void
    {
        $seats = [];

        foreach ($layout->layout_map ?? [] as $row) {
            foreach ($row as $seatData) {
                // The layout_map may come from SeatLayoutSeeder (keys: seat/type)
                // or from SeatLayout::rebuildJsonCache() (keys: seat_label/cell_type).
                $cellType = $seatData['cell_type'] ?? $seatData['type'] ?? 'seat';
                if ($cellType === 'aisle') {
                    continue; // don't create seat rows for aisle cells
                }

                $seatNumber = $seatData['seat'] ?? $seatData['seat_label'] ?? null;
                if (! $seatNumber) {
                    continue;
                }

                $seats[] = [
                    'bus_id'        => $bus->id,
                    'seat_number'   => $seatNumber,
                    'seat_type'     => $bus->default_seat_type,
                    'seat_type_id'  => $seatTypeId,
                    'status'        => 'available',
                    'created_at'    => now(),
                    'updated_at'    => now(),
                ];
            }
        }

        foreach (array_chunk($seats, 50) as $chunk) {
            Seat::insert($chunk);
        }
    }
}