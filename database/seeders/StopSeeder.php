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
            ['city' => 'Quezon City',    'terminal_code' => 'CBT',  'name' => 'Cubao Bus Stop',          'code' => 'CBT-STOP',  'type' => 'terminal'],
            ['city' => 'Quezon City',    'terminal_code' => null,   'name' => 'Balintawak Stop',          'code' => 'BAL-STOP',  'type' => 'pickup'],
            ['city' => 'Quezon City',    'terminal_code' => null,   'name' => 'Monumento Stop',           'code' => 'MON-STOP',  'type' => 'pickup'],
            ['city' => 'Baguio',         'terminal_code' => 'BAG',  'name' => 'Baguio Terminal Stop',     'code' => 'BAG-STOP',  'type' => 'terminal'],
            ['city' => 'Baguio',         'terminal_code' => null,   'name' => 'La Trinidad Stop',        'code' => 'LTR-STOP',  'type' => 'pickup'],
            ['city' => 'Laoag',          'terminal_code' => 'LAO',  'name' => 'Laoag Terminal Stop',      'code' => 'LAO-STOP',  'type' => 'terminal'],
            ['city' => 'Vigan',          'terminal_code' => 'VIG',  'name' => 'Vigan Terminal Stop',      'code' => 'VIG-STOP',  'type' => 'terminal'],
            ['city' => 'San Fernando',   'terminal_code' => null,   'name' => 'San Fernando (La Union)',  'code' => 'SFU-STOP',  'type' => 'pickup'],
            ['city' => 'Cebu City',      'terminal_code' => 'CSBT', 'name' => 'Cebu South Terminal Stop','code' => 'CSBT-STOP', 'type' => 'terminal'],
            ['city' => 'Davao City',     'terminal_code' => 'DVO',  'name' => 'Davao Ecoland Stop',       'code' => 'DVO-STOP',  'type' => 'terminal'],
            ['city' => 'Cagayan de Oro', 'terminal_code' => 'CDO',  'name' => 'CDO Agora Stop',           'code' => 'CDO-STOP',  'type' => 'terminal'],
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

        // ── Seed route_stops pivot for the Manila → Baguio route ─────────
        $this->seedManilaToBAguioStops();
        $this->seedManilaToLaoagStops();
    }

    private function seedManilaToBAguioStops(): void
    {
        $route = BusRoute::whereHas('originCity', fn ($q) => $q->where('name', 'Quezon City'))
            ->whereHas('destinationCity', fn ($q) => $q->where('name', 'Baguio'))
            ->first();

        if (! $route) {
            return;
        }

        $stopData = [
            ['code' => 'BAL-STOP',  'order' => 1, 'minutes' => 20,  'fare' => 50.00,  'board' => true,  'alight' => false],
            ['code' => 'MON-STOP',  'order' => 2, 'minutes' => 35,  'fare' => 80.00,  'board' => true,  'alight' => false],
            ['code' => 'SFU-STOP',  'order' => 3, 'minutes' => 180, 'fare' => 180.00, 'board' => true,  'alight' => true],
            ['code' => 'LTR-STOP',  'order' => 4, 'minutes' => 340, 'fare' => 300.00, 'board' => false, 'alight' => true],
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

    private function seedManilaToLaoagStops(): void
    {
        $route = BusRoute::whereHas('originCity', fn ($q) => $q->where('name', 'Manila'))
            ->whereHas('destinationCity', fn ($q) => $q->where('name', 'Laoag'))
            ->first();

        if (! $route) {
            return;
        }

        $stopData = [
            ['code' => 'BAL-STOP',  'order' => 1, 'minutes' => 25,  'fare' => 60.00,  'board' => true,  'alight' => false],
            ['code' => 'SFU-STOP',  'order' => 2, 'minutes' => 200, 'fare' => 200.00, 'board' => true,  'alight' => true],
            ['code' => 'VIG-STOP',  'order' => 3, 'minutes' => 390, 'fare' => 380.00, 'board' => false, 'alight' => true],
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