<?php

namespace App\Http\Controllers\Driver;

use App\Http\Controllers\Controller;
use App\Models\Trip;
use Illuminate\Http\Request;

class TripController extends Controller
{
    /**
     * Display upcoming and active trips.
     */
    public function index(Request $request)
    {
        $driver = $request->user();

        $upcomingTrips = Trip::with(['route.originCity', 'route.destinationCity', 'bus'])
            ->where('driver_id', $driver->driver?->id)
            ->whereIn('status', ['scheduled', 'ongoing'])
            ->orderBy('trip_date')
            ->orderBy('departure_time')
            ->limit(10)
            ->get();

        return view('driver.trips.index', [
            'driver' => $driver,
            'upcomingTrips' => $upcomingTrips,
        ]);
    }

    /**
     * Display past/completed trips.
     */
    public function history(Request $request)
    {
        $driver = $request->user();

        $pastTrips = Trip::with(['route.originCity', 'route.destinationCity', 'bus'])
            ->where('driver_id', $driver->driver?->id)
            ->whereIn('status', ['completed', 'cancelled'])
            ->orderByDesc('trip_date')
            ->orderByDesc('departure_time')
            ->paginate(10);

        return view('driver.trips.history', [
            'driver' => $driver,
            'pastTrips' => $pastTrips,
        ]);
    }

    /**
     * Accept live driver GPS update for a specific trip.
     */
    public function updateLocation(Request $request, Trip $trip)
    {
        $validated = $request->validate([
            'lat' => 'required|numeric|between:-90,90',
            'lng' => 'required|numeric|between:-180,180',
        ]);

        $driverId = $request->user()->driver?->id;
        abort_unless($driverId && $trip->driver_id === $driverId, 403);

        $trip->update([
            'current_lat' => (float) $validated['lat'],
            'current_lng' => (float) $validated['lng'],
            'last_location_updated_at' => now(),
        ]);

        return response()->json([
            'success' => true,
            'trip_id' => $trip->id,
            'lat' => (float) $trip->current_lat,
            'lng' => (float) $trip->current_lng,
            'last_updated_at' => $trip->last_location_updated_at?->toIso8601String(),
        ]);
    }
}
