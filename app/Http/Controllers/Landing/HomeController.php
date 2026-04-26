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

class HomeController extends Controller
{
    public function index()
    {
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
        $discountTypes = DiscountType::active()->where('percentage', '>', 0)->get();

        // Stats
        $stats = [
            'totalCities'  => City::where('status', 'active')->count(),
            'totalRoutes'  => BusRoute::where('status', 'active')->count(),
            'activeBuses'  => Bus::where('status', 'active')->count(),
            'todayTrips'   => Trip::whereDate('trip_date', today())->count(),
            'avgRating'    => round(Feedback::where('rating', '>=', 1)->avg('rating'), 1),
        ];

        return view('pages.home', compact(
            'heroTrip',
            'featuredRoutes',
            'originCities',
            'destinationCities',
            'promotions',
            'reviews',
            'discountTypes',
            'stats'
        ));
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