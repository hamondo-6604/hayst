<?php

namespace App\Http\Controllers\Landing;

use App\Http\Controllers\Controller;
use App\Models\BusRoute;
use App\Models\City;
use App\Models\Terminal;
use App\Models\Trip;
use Illuminate\Http\Request;

class BookingRoutesController extends Controller
{
    public function index(Request $request)
    {
        $query = BusRoute::with([
            'originCity', 
            'destinationCity', 
            'originTerminal', 
            'destinationTerminal',
            'trips' => fn ($q) => $q->where('status', 'scheduled')
                                    ->where('trip_date', '>=', today())
                                    ->with('bus.amenities')
        ])
            ->where('status', 'active')
            ->withCount(['trips as upcoming_trips_count' => fn ($q) =>
                $q->where('status', 'scheduled')->where('trip_date', '>=', today())
            ])
            ->withMin('trips as min_fare', 'fare');

        // Search
        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(fn ($q) =>
                $q->where('route_name', 'like', "%{$s}%")
                  ->orWhereHas('originCity',      fn ($c) => $c->where('name', 'like', "%{$s}%"))
                  ->orWhereHas('destinationCity', fn ($c) => $c->where('name', 'like', "%{$s}%"))
            );
        }

        // Sort
        match ($request->get('sort', 'popular')) {
            'fare_asc'  => $query->orderBy('min_fare'),
            'distance'  => $query->orderBy('distance_km'),
            default     => $query->orderByDesc('upcoming_trips_count'),
        };

        $routes    = $query->paginate(12)->withQueryString();
        $terminals = Terminal::with('city')->where('status', 'active')->orderBy('name')->get();
        $regions   = City::whereNotNull('region')->distinct()->orderBy('region')->pluck('region');
        $amenities = \App\Models\Amenity::where('is_active', true)->orderBy('display_name')->get();

        $stats = [
            'totalRoutes'    => BusRoute::where('status', 'active')->count(),
            'totalCities'    => City::where('status', 'active')->count(),
            'totalTerminals' => Terminal::where('status', 'active')->count(),
            'lowestFare'     => Trip::where('status', 'scheduled')
                                    ->where('trip_date', '>=', today())
                                    ->min('fare') ?? 0,
        ];

        return view('pages.booking_routes', compact('routes', 'terminals', 'regions', 'amenities', 'stats'));
    }
}