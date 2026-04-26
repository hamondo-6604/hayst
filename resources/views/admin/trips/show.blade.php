@extends('layouts.admin')

@section('title', 'Trip Details & Manifest')

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.trips.index') }}" class="text-sm font-medium text-slate-500 hover:text-primary-600 dark:hover:text-primary-400 transition-colors flex items-center gap-1 mb-2">
        <i class="fa-solid fa-arrow-left"></i> Back to Trips
    </a>
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800 dark:text-white flex items-center gap-3">
                Trip {{ $trip->trip_code }}
                @php
                    $badgeColors = [
                        'scheduled' => 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400',
                        'in_progress' => 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400',
                        'completed' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400',
                        'cancelled' => 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400',
                    ];
                    $colorClass = $badgeColors[$trip->status] ?? 'bg-slate-100 text-slate-700';
                @endphp
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $colorClass }}">
                    {{ ucfirst(str_replace('_', ' ', $trip->status)) }}
                </span>
            </h1>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">{{ $trip->trip_date ? $trip->trip_date->format('l, F j, Y') : 'No date set' }}</p>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Left Column: Manifest -->
    <div class="lg:col-span-2 space-y-6">
        
        <!-- Passenger Manifest -->
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 overflow-hidden">
            <div class="p-5 border-b border-slate-100 dark:border-slate-700 flex justify-between items-center">
                <h2 class="text-lg font-bold text-slate-800 dark:text-white flex items-center gap-2">
                    <i class="fa-solid fa-users text-primary-500"></i> Passenger Manifest
                </h2>
                <div class="text-sm text-slate-500 font-semibold">
                    {{ $trip->bus ? $trip->bus->total_seats - $trip->available_seats : 0 }} Booked
                </div>
            </div>
            
            <div class="p-0">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 dark:bg-slate-700/50 border-b border-slate-100 dark:border-slate-700 text-xs uppercase tracking-wider text-slate-500 dark:text-slate-400 font-semibold">
                            <th class="p-4">Seat</th>
                            <th class="p-4">Passenger Name</th>
                            <th class="p-4">Booking Ref</th>
                            <th class="p-4">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                        @php
                            // Flatten booking seats
                            $manifest = collect();
                            foreach($trip->bookings as $booking) {
                                if ($booking->bookingSeats->count() > 0) {
                                    foreach($booking->bookingSeats as $bSeat) {
                                        $manifest->push((object)[
                                            'seat' => $bSeat->seat_number,
                                            'name' => $bSeat->passenger_name ?? $booking->user->name ?? 'Guest',
                                            'ref' => $booking->booking_reference,
                                            'booking_id' => $booking->id,
                                            'status' => $booking->status
                                        ]);
                                    }
                                } else {
                                    // Fallback for old single-seat bookings
                                    $manifest->push((object)[
                                        'seat' => $booking->seat_number ?? '—',
                                        'name' => $booking->user->name ?? 'Guest',
                                        'ref' => $booking->booking_reference,
                                        'booking_id' => $booking->id,
                                        'status' => $booking->status
                                    ]);
                                }
                            }
                            
                            // Sort by seat number
                            $manifest = $manifest->sortBy('seat')->values();
                        @endphp
                        
                        @forelse($manifest as $item)
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50">
                                <td class="p-4 font-bold text-slate-800 dark:text-slate-200">{{ $item->seat }}</td>
                                <td class="p-4 font-medium text-slate-800 dark:text-slate-200">{{ $item->name }}</td>
                                <td class="p-4 text-sm"><a href="{{ route('admin.bookings.show', $item->booking_id) }}" class="text-primary-600 hover:text-primary-700">{{ $item->ref }}</a></td>
                                <td class="p-4">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-semibold {{ $item->status === 'confirmed' || $item->status === 'completed' ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400' : 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400' }}">
                                        {{ strtoupper($item->status) }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="p-8 text-center text-slate-500 dark:text-slate-400">
                                    No passengers booked for this trip yet.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Right Column: Info & Assignments -->
    <div class="space-y-6">
        
        <!-- Trip Summary -->
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 overflow-hidden">
            <div class="p-5 border-b border-slate-100 dark:border-slate-700">
                <h2 class="text-lg font-bold text-slate-800 dark:text-white flex items-center gap-2">
                    <i class="fa-solid fa-route text-primary-500"></i> Route Info
                </h2>
            </div>
            <div class="p-5 space-y-4">
                <div>
                    <p class="text-xs text-slate-500 dark:text-slate-400 uppercase tracking-wide font-semibold mb-1">Origin</p>
                    <p class="text-base font-bold text-slate-800 dark:text-white">{{ $trip->route->originCity->name ?? 'Unknown' }}</p>
                    <p class="text-sm text-slate-500">{{ $trip->departure_time ? $trip->departure_time->format('h:i A') : 'TBA' }}</p>
                </div>
                
                <div class="w-full flex items-center text-slate-300 dark:text-slate-600">
                    <div class="w-2 h-2 rounded-full border-2 border-current"></div>
                    <div class="flex-1 border-t-2 border-dashed border-current mx-2"></div>
                    <div class="w-2 h-2 rounded-full border-2 border-current bg-current"></div>
                </div>

                <div>
                    <p class="text-xs text-slate-500 dark:text-slate-400 uppercase tracking-wide font-semibold mb-1">Destination</p>
                    <p class="text-base font-bold text-slate-800 dark:text-white">{{ $trip->route->destinationCity->name ?? 'Unknown' }}</p>
                    <p class="text-sm text-slate-500">{{ $trip->arrival_time ? $trip->arrival_time->format('h:i A') : 'TBA' }}</p>
                </div>
            </div>
        </div>

        <!-- Assignments -->
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 overflow-hidden">
            <div class="p-5 border-b border-slate-100 dark:border-slate-700">
                <h2 class="text-lg font-bold text-slate-800 dark:text-white flex items-center gap-2">
                    <i class="fa-solid fa-clipboard-check text-primary-500"></i> Assignments
                </h2>
            </div>
            <div class="p-5 space-y-4">
                <div>
                    <p class="text-xs text-slate-500 dark:text-slate-400 uppercase tracking-wide font-semibold mb-1">Bus Assigned</p>
                    <p class="text-base font-medium text-slate-800 dark:text-white">{{ $trip->bus->name ?? 'Unassigned' }}</p>
                    <p class="text-sm text-slate-500">{{ $trip->bus->class ?? '' }} - {{ $trip->bus->total_seats ?? 0 }} Seats Capacity</p>
                </div>
                
                <div>
                    <p class="text-xs text-slate-500 dark:text-slate-400 uppercase tracking-wide font-semibold mb-1">Driver Assigned</p>
                    <p class="text-base font-medium text-slate-800 dark:text-white">{{ $trip->driver->user->name ?? 'Unassigned' }}</p>
                    <p class="text-sm text-slate-500">License: {{ $trip->driver->license_number ?? 'N/A' }}</p>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
