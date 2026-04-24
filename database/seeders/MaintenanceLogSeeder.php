<?php

namespace Database\Seeders;

use App\Models\Bus;
use App\Models\MaintenanceLog;
use App\Models\User;
use Illuminate\Database\Seeder;

class MaintenanceLogSeeder extends Seeder
{
    public function run(): void
    {
        $buses = Bus::all();
        $admin = User::where('role', 'admin')->first();

        $records = [
            ['title' => 'Oil Change',              'type' => 'preventive', 'cost' => 1500.00],
            ['title' => 'Brake Pad Replacement',   'type' => 'corrective', 'cost' => 4500.00],
            ['title' => 'Air Filter Replacement',  'type' => 'preventive', 'cost' => 800.00],
            ['title' => 'Tire Rotation',            'type' => 'preventive', 'cost' => 600.00],
            ['title' => 'Engine Diagnostic Check',  'type' => 'preventive', 'cost' => 2000.00],
            ['title' => 'Emergency Breakdown',      'type' => 'emergency',  'cost' => 12000.00],
            ['title' => 'Battery Replacement',      'type' => 'corrective', 'cost' => 3500.00],
            ['title' => 'Coolant Flush',            'type' => 'preventive', 'cost' => 1200.00],
        ];

        foreach ($buses as $bus) {
            // 2 past completed logs per bus
            foreach (array_slice($records, 0, 2) as $record) {
                $maintDate = now()->subDays(rand(10, 90));

                MaintenanceLog::create([
                    'bus_id'               => $bus->id,
                    'logged_by'            => $admin?->id,
                    'title'                => $record['title'],
                    'description'          => 'Routine ' . strtolower($record['title']) . ' performed.',
                    'type'                 => $record['type'],
                    'status'               => 'completed',
                    'maintenance_date'     => $maintDate->toDateString(),
                    'completed_date'       => $maintDate->addDays(1)->toDateString(),
                    'cost'                 => $record['cost'],
                    'performed_by'         => 'AutoCare PH',
                    'parts_replaced'       => null,
                    'next_maintenance_due' => now()->addMonths(3)->toDateString(),
                ]);
            }

            // 1 upcoming scheduled log per bus
            MaintenanceLog::create([
                'bus_id'               => $bus->id,
                'logged_by'            => $admin?->id,
                'title'                => 'Scheduled Full Inspection',
                'description'          => 'Quarterly full inspection of all mechanical components.',
                'type'                 => 'preventive',
                'status'               => 'scheduled',
                'maintenance_date'     => now()->addDays(rand(5, 30))->toDateString(),
                'completed_date'       => null,
                'cost'                 => null,
                'performed_by'         => null,
                'parts_replaced'       => null,
                'next_maintenance_due' => now()->addMonths(6)->toDateString(),
            ]);
        }
    }
}