<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Seat;
use App\Models\Bus;

class SeatSeeder extends Seeder
{
  public function run(): void
  {
    // Loop through all buses
    $buses = Bus::all();

    foreach ($buses as $bus) {
      $capacity = $bus->seat_layout?->capacity ?? 40; // fallback to 40 seats if layout missing

      // Generate seats for this bus
      for ($i = 1; $i <= $capacity; $i++) {
        Seat::factory()->create([
          'bus_id' => $bus->id,
          'seat_number' => $bus->seat_layout
            ? "R" . ceil($i / $bus->seat_layout->total_columns) . "C" . (($i - 1) % $bus->seat_layout->total_columns + 1)
            : (string)$i,
          // Seat type inherited from bus by default
          'seat_type' => $bus->default_seat_type,
        ]);
      }
    }
  }
}
