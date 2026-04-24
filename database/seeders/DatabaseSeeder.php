<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * Strict dependency order — each seeder depends only on what runs above it.
     *
     * ╔══════════════════════════════════════════════════════════════╗
     * ║  LAYER 1 — LOOKUP / REFERENCE DATA (no FK dependencies)     ║
     * ╠══════════════════════════════════════════════════════════════╣
     *  1.  PermissionSeeder     permissions
     *  2.  RoleSeeder           roles, role_permission, role_user   (needs permissions)
     *  3.  SeatTypeSeeder       seat_types
     *  4.  UserTypeSeeder       user_types
     *  5.  CitySeeder           cities
     *  6.  AmenitySeeder        amenities  [partial — bus sync runs after BusSeeder]
     *
     * ╠══════════════════════════════════════════════════════════════╣
     * ║  LAYER 2 — FLEET                                             ║
     * ╠══════════════════════════════════════════════════════════════╣
     *  7.  SeatLayoutSeeder     seat_layouts
     *  8.  BusTypeSeeder        bus_types                           (needs seat_layouts)
     *  9.  BusSeeder            buses, seats, layout_maps           (needs bus_types, seat_layouts, seat_types)
     * 10.  AmenitySeeder [2nd]  bus_amenities                       (needs buses, amenities)
     *                           → AmenitySeeder is idempotent; re-running it after
     *                             BusSeeder assigns amenities to buses.
     *
     * ╠══════════════════════════════════════════════════════════════╣
     * ║  LAYER 3 — PEOPLE                                            ║
     * ╠══════════════════════════════════════════════════════════════╣
     * 11.  UserSeeder           users                               (needs user_types)
     * 12.  DriverSeeder         drivers                             (needs users with role=driver)
     *
     * ╠══════════════════════════════════════════════════════════════╣
     * ║  LAYER 4 — NETWORK                                           ║
     * ╠══════════════════════════════════════════════════════════════╣
     * 13.  TerminalSeeder       terminals                           (needs cities)
     * 14.  BusRouteSeeder       routes                              (needs cities, terminals)
     * 15.  StopSeeder           stops, route_stops                  (needs cities, terminals, routes)
     * 16.  ScheduleSeeder       schedules                           (needs routes, buses, drivers)
     *
     * ╠══════════════════════════════════════════════════════════════╣
     * ║  LAYER 5 — TRANSACTIONS                                      ║
     * ╠══════════════════════════════════════════════════════════════╣
     * 17.  PromotionSeeder      promotions
     * 18.  TripSeeder           trips                               (needs routes, buses, drivers, schedules, terminals)
     * 19.  BookingSeeder        bookings, booking_seats, payments   (needs trips, seats, users, promotions)
     *
     * ╠══════════════════════════════════════════════════════════════╣
     * ║  LAYER 6 — SUPPORT / ENGAGEMENT DATA                        ║
     * ╠══════════════════════════════════════════════════════════════╣
     * 20.  MaintenanceLogSeeder  maintenance_logs                   (needs buses, users)
     * 21.  FeedbackSeeder        feedback                           (needs bookings, users)
     * 22.  NotificationSeeder    notifications                      (needs bookings, users)
     * ╚══════════════════════════════════════════════════════════════╝
     */
    public function run(): void
    {
        $this->call([
            // Layer 1 — Reference data
            DiscountTypeSeeder::class,
            SeatTypeSeeder::class,
            UserTypeSeeder::class,
            CitySeeder::class,

            // Layer 2 — Fleet
            SeatLayoutSeeder::class,
            BusTypeSeeder::class,
            BusSeeder::class,       // also creates Seats and LayoutMap rows
            AmenitySeeder::class,   // creates amenities + assigns them to buses

            // Layer 3 — People
            UserSeeder::class,
            DriverSeeder::class,

            // Layer 4 — Network
            TerminalSeeder::class,
            BusRouteSeeder::class,
            StopSeeder::class,
            ScheduleSeeder::class,

            // Layer 5 — Transactions
            PromotionSeeder::class,
            TripSeeder::class,
            BookingSeeder::class,   // also creates BookingSeats and Payments

            // Layer 6 — Support data
            MaintenanceLogSeeder::class,
            FeedbackSeeder::class,
            NotificationSeeder::class,
        ]);
    }
}