<?php

namespace App\Http\Controllers\Landing;

use App\Http\Controllers\Controller;
use App\Models\BusRoute;
use App\Models\Bus;
use App\Models\City;
use App\Models\DiscountType;
use App\Models\Feedback;
use App\Models\Promotion;
use App\Models\Trip;
use App\Models\BusType;
use App\Models\Schedule;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Live trip statistics
        $liveStats = $this->getLiveTripStats();

        // Hero: next departing scheduled trip
        $heroTrip = Trip::with(['route.originCity', 'route.destinationCity', 'bus.type'])
            ->where('status', 'scheduled')
            ->where('departure_time', '>', now())
            ->orderBy('departure_time')
            ->first();

        // Popular routes: active, with upcoming trips, ordered by trip count
        $featuredRoutes = BusRoute::with(['originCity', 'destinationCity'])
            ->where('status', 'active')
            ->withCount(['trips as upcoming_trips_count' => fn ($q) =>
                $q->where('status', 'scheduled')->where('trip_date', '>=', today())
            ])
            ->withMin('trips as min_fare', 'fare')
            ->having('upcoming_trips_count', '>', 0)
            ->orderByDesc('upcoming_trips_count')
            ->take(6)
            ->get();

        // Search dropdowns
        $originCities = BusRoute::with('originCity')->where('status', 'active')
            ->get()->pluck('originCity')->filter()->unique('id')->sortBy('name')->values();

        $destinationCities = BusRoute::with('destinationCity')->where('status', 'active')
            ->get()->pluck('destinationCity')->filter()->unique('id')->sortBy('name')->values();

        // Active promos (max 3 for home page)
        $promotions = Promotion::valid()->where('is_active', true)
            ->orderByDesc('discount_value')
            ->take(3)
            ->get();

        // Reviews: 4+ star, has a comment
        $reviews = Feedback::with('user')
            ->where('rating', '>=', 4)
            ->whereNotNull('comment')
            ->inRandomOrder()
            ->take(6)
            ->get();

        // Government discount types (for the strip)
        $discountTypes = DiscountType::active()->get();

        // Stats
        $stats = [
            'totalCities'  => City::where('status', 'active')->count(),
            'totalRoutes'  => BusRoute::where('status', 'active')->count(),
            'activeBuses'  => Bus::where('status', 'active')->count(),
            'todayTrips'   => Trip::whereDate('trip_date', today())->count(),
            'avgRating'    => round(Feedback::where('rating', '>=', 1)->avg('rating'), 1),
        ];

        // Fleet Showcase
        $busTypes = BusType::where('status', 'active')->get();

        // Top Schedules for Timetable Preview
        $topRoutes = BusRoute::with(['originCity', 'destinationCity'])
            ->where('status', 'active')
            ->orderByDesc('distance_km') // Arbitrary proxy for popular routes or just use specific ones
            ->take(4)
            ->get();

        $topSchedules = [];
        foreach ($topRoutes as $route) {
            $schedules = Schedule::where('route_id', $route->id)
                ->where('status', 'active')
                ->orderBy('departure_time')
                ->get();
            if ($schedules->isNotEmpty()) {
                $topSchedules[] = [
                    'route' => $route,
                    'schedules' => $schedules,
                ];
            }
        }

        return view('pages.home', compact(
            'heroTrip',
            'featuredRoutes',
            'promotions',
            'discountTypes',
            'stats',
            'liveStats',
            'reviews',
            'busTypes',
            'topSchedules'
        ));
    }

    public function trackTrip(Request $request)
    {
        $code = $request->input('trip_code');
        if (!$code) {
            return response()->json(['success' => false, 'message' => 'Please enter a Trip Code.']);
        }

        $trip = Trip::with(['route.originCity', 'route.destinationCity', 'bus.type'])
            ->where('trip_code', strtoupper($code))
            ->first();

        if (!$trip) {
            return response()->json(['success' => false, 'message' => 'Trip not found. Please check the code and try again.']);
        }

        return response()->json([
            'success' => true,
            'trip' => [
                'code' => $trip->trip_code,
                'status' => ucfirst($trip->status),
                'origin' => $trip->route->originCity->name,
                'destination' => $trip->route->destinationCity->name,
                'departure' => $trip->departure_time->format('M d, Y h:i A'),
                'arrival' => $trip->arrival_time ? $trip->arrival_time->format('M d, Y h:i A') : 'TBD',
                'bus_type' => $trip->bus->type->type_name ?? 'Standard',
            ]
        ]);
    }

    public function getLiveTripStats()
    {
        $today = today();
        
        return [
            'trips_today' => Trip::whereDate('trip_date', $today)
                ->where('status', 'scheduled')
                ->where('is_active', true)
                ->count(),
            'trips_departing_now' => Trip::whereDate('trip_date', $today)
                ->where('status', 'scheduled')
                ->where('is_active', true)
                ->where('departure_time', '>=', now()->subMinutes(30))
                ->where('departure_time', '<=', now()->addMinutes(30))
                ->count(),
            'seats_available_today' => Trip::whereDate('trip_date', $today)
                ->where('status', 'scheduled')
                ->where('is_active', true)
                ->sum('available_seats'),
            'popular_routes_today' => Trip::with(['route.originCity', 'route.destinationCity'])
                ->whereDate('trip_date', $today)
                ->where('status', 'scheduled')
                ->where('is_active', true)
                ->limit(3)
                ->get()
                ->map(fn($trip) => [
                    'route' => $trip->route->originCity->name . ' → ' . $trip->route->destinationCity->name,
                    'time' => $trip->departure_time->format('H:i'),
                    'available_seats' => $trip->available_seats
                ])
        ];
    }

    public function promos()
    {
        $promotions  = Promotion::valid()->where('is_active', true)->orderByDesc('discount_value')->get();
        $featuredPromo = $promotions->first();

        $stats = [
            'totalActive'  => $promotions->count(),
            'percentDeals' => $promotions->where('discount_type', 'percent')->count(),
            'fixedDeals'   => $promotions->where('discount_type', 'fixed')->count(),
        ];

        return view('pages.promos', compact('promotions', 'featuredPromo', 'stats'));
    }
}