<?php

namespace Database\Seeders;

use App\Models\BusRoute;
use App\Models\City;
use App\Models\Stop;
use App\Models\Terminal;
use Illuminate\Database\Seeder;

class StopSeeder extends Seeder
{
    public function run(): void
    {
        // ── Seed named stops ─────────────────────────────────────────────
        $stops = [
            ['city' => 'Davao City',     'terminal_code' => 'DVO',  'name' => 'Davao Ecoland Stop',       'code' => 'DVO-STOP',  'type' => 'terminal'],
            ['city' => 'Davao City',     'terminal_code' => null,   'name' => 'Panacan Stop',             'code' => 'PAN-STOP',  'type' => 'pickup'],
            ['city' => 'Cagayan de Oro', 'terminal_code' => 'CDO',  'name' => 'CDO Agora Stop',           'code' => 'CDO-STOP',  'type' => 'terminal'],
            ['city' => 'Cagayan de Oro', 'terminal_code' => null,   'name' => 'Puerto Stop',              'code' => 'PUE-STOP',  'type' => 'pickup'],
            ['city' => 'General Santos', 'terminal_code' => 'GEN',  'name' => 'GenSan Terminal Stop',     'code' => 'GEN-STOP',  'type' => 'terminal'],
            ['city' => 'General Santos', 'terminal_code' => null,   'name' => 'Polomolok Stop',           'code' => 'POL-STOP',  'type' => 'pickup'],
            ['city' => 'Tagum',          'terminal_code' => 'TGM',  'name' => 'Tagum Terminal Stop',      'code' => 'TGM-STOP',  'type' => 'terminal'],
            ['city' => 'Butuan',         'terminal_code' => 'BXU',  'name' => 'Butuan Terminal Stop',     'code' => 'BXU-STOP',  'type' => 'terminal'],
            ['city' => 'Iligan',         'terminal_code' => 'ILI',  'name' => 'Iligan Terminal Stop',     'code' => 'ILI-STOP',  'type' => 'terminal'],
            ['city' => 'Zamboanga',      'terminal_code' => 'ZAM',  'name' => 'Zamboanga Terminal Stop',  'code' => 'ZAM-STOP',  'type' => 'terminal'],
        ];

        foreach ($stops as $data) {
            $city     = City::where('name', $data['city'])->first();
            $terminal = $data['terminal_code']
                ? Terminal::where('code', $data['terminal_code'])->first()
                : null;

            Stop::updateOrCreate(
                ['code' => $data['code']],
                [
                    'name'        => $data['name'],
                    'city_id'     => $city?->id,
                    'terminal_id' => $terminal?->id,
                    'type'        => $data['type'],
                    'status'      => 'active',
                ]
            );
        }

        // ── Seed route_stops pivot for some Mindanao routes ─────────
        $this->seedDavaoToCdoStops();
        $this->seedDavaoToGensanStops();
    }

    private function seedDavaoToCdoStops(): void
    {
        $route = BusRoute::whereHas('originCity', fn ($q) => $q->where('name', 'Davao City'))
            ->whereHas('destinationCity', fn ($q) => $q->where('name', 'Cagayan de Oro'))
            ->first();

        if (! $route) {
            return;
        }

        $stopData = [
            ['code' => 'PAN-STOP',  'order' => 1, 'minutes' => 30,  'fare' => 50.00,  'board' => true,  'alight' => false],
            ['code' => 'TGM-STOP',  'order' => 2, 'minutes' => 80,  'fare' => 100.00, 'board' => true,  'alight' => true],
            ['code' => 'BXU-STOP',  'order' => 3, 'minutes' => 240, 'fare' => 300.00, 'board' => true,  'alight' => true],
            ['code' => 'PUE-STOP',  'order' => 4, 'minutes' => 340, 'fare' => 400.00, 'board' => false, 'alight' => true],
        ];

        foreach ($stopData as $data) {
            $stop = Stop::where('code', $data['code'])->first();
            if (! $stop) {
                continue;
            }

            $route->stops()->syncWithoutDetaching([
                $stop->id => [
                    'stop_order'         => $data['order'],
                    'minutes_from_origin'=> $data['minutes'],
                    'fare_from_origin'   => $data['fare'],
                    'allows_boarding'    => $data['board'],
                    'allows_alighting'   => $data['alight'],
                ],
            ]);
        }
    }

    private function seedDavaoToGensanStops(): void
    {
        $route = BusRoute::whereHas('originCity', fn ($q) => $q->where('name', 'Davao City'))
            ->whereHas('destinationCity', fn ($q) => $q->where('name', 'General Santos'))
            ->first();

        if (! $route) {
            return;
        }

        $stopData = [
            ['code' => 'POL-STOP',  'order' => 1, 'minutes' => 100, 'fare' => 150.00, 'board' => true,  'alight' => true],
        ];

        foreach ($stopData as $data) {
            $stop = Stop::where('code', $data['code'])->first();
            if (! $stop) {
                continue;
            }

            $route->stops()->syncWithoutDetaching([
                $stop->id => [
                    'stop_order'         => $data['order'],
                    'minutes_from_origin'=> $data['minutes'],
                    'fare_from_origin'   => $data['fare'],
                    'allows_boarding'    => $data['board'],
                    'allows_alighting'   => $data['alight'],
                ],
            ]);
        }
    }
}