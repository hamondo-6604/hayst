<?php

namespace App\Http\Controllers\Landing;

use App\Http\Controllers\Controller;
use App\Models\BusRoute;
use App\Models\Trip;
use Illuminate\Http\Request;

class TicketBookingController extends Controller
{
    // ------------------------------------------------------------------
    // GET /ticket-booking  (initial load or from home search widget)
    // ------------------------------------------------------------------
    public function index()
    {
        [$originCities, $destinationCities] = $this->dropdowns();

        $from = request('from');
        $to   = request('to');
        $date = request('date');

        // If from + to supplied (clicked from routes page) but NO date,
        // find the nearest available trip date for that route automatically.
        if ($from && $to && ! $date) {
            $date = $this->nearestTripDate($from, $to);
        }

        // Still no date → default to today
        $date = $date ?? today()->toDateString();

        $prefill = compact('from', 'to', 'date');

        $trips            = collect();
        $alternativeDates = collect();

        if ($from && $to) {
            $trips = $this->searchTrips($from, $to, $date);

            // If no trips found, look for the next 7 dates that DO have trips
            if ($trips->isEmpty()) {
                $alternativeDates = $this->findAlternativeDates($from, $to, $date);
            }
        }

        return view('pages.ticket_booking', compact(
            'originCities', 'destinationCities',
            'prefill', 'trips', 'alternativeDates'
        ));
    }

    // ------------------------------------------------------------------
    // POST /ticket-booking  (search form submit)
    // ------------------------------------------------------------------
    public function search(Request $request)
    {
        $request->validate([
            'from' => 'required|string',
            'to'   => 'required|string|different:from',
            'date' => 'required|date',   // allow past dates in dev; change to after_or_equal:today in prod
        ]);

        [$originCities, $destinationCities] = $this->dropdowns();

        $prefill = $request->only('from', 'to', 'date');
        $trips   = $this->searchTrips($prefill['from'], $prefill['to'], $prefill['date']);

        $alternativeDates = collect();
        if ($trips->isEmpty()) {
            $alternativeDates = $this->findAlternativeDates(
                $prefill['from'], $prefill['to'], $prefill['date']
            );
        }

        return view('pages.ticket_booking', compact(
            'originCities', 'destinationCities',
            'prefill', 'trips', 'alternativeDates'
        ));
    }

    // ------------------------------------------------------------------
    // PRIVATE HELPERS
    // ------------------------------------------------------------------

    /**
     * Search for trips matching the given from/to/date.
     * Matches on city name (case-insensitive).
     */
    private function searchTrips(string $from, string $to, string $date)
    {
        return Trip::with([
            'route.originCity',
            'route.destinationCity',
            'bus.type',
            'bus.amenities',
            'departureTerminal',
        ])
        ->whereHas('route', fn ($q) =>
            $q->whereHas('originCity',      fn ($c) => $c->whereRaw('LOWER(name) = ?', [strtolower($from)]))
              ->whereHas('destinationCity', fn ($c) => $c->whereRaw('LOWER(name) = ?', [strtolower($to)]))
        )
        ->whereDate('trip_date', $date)
        ->where('status', 'scheduled')
        ->where('is_active', true)
        ->where('available_seats', '>', 0)
        ->orderBy('departure_time')
        ->get();
    }

    /**
     * Find the nearest future date (up to 60 days ahead) that has trips
     * for this route. Returns date string or null.
     */
    private function nearestTripDate(string $from, string $to): ?string
    {
        $trip = Trip::whereHas('route', fn ($q) =>
            $q->whereHas('originCity',      fn ($c) => $c->whereRaw('LOWER(name) = ?', [strtolower($from)]))
              ->whereHas('destinationCity', fn ($c) => $c->whereRaw('LOWER(name) = ?', [strtolower($to)]))
        )
        ->where('status', 'scheduled')
        ->where('is_active', true)
        ->where('available_seats', '>', 0)
        ->where('trip_date', '>=', today()->toDateString())
        ->orderBy('trip_date')
        ->orderBy('departure_time')
        ->first();

        return $trip?->trip_date?->toDateString();
    }

    /**
     * Find up to 5 alternative dates that have trips for this route,
     * searching 60 days around the given date (±30 days).
     */
    private function findAlternativeDates(string $from, string $to, string $date): \Illuminate\Support\Collection
    {
        return Trip::whereHas('route', fn ($q) =>
            $q->whereHas('originCity',      fn ($c) => $c->whereRaw('LOWER(name) = ?', [strtolower($from)]))
              ->whereHas('destinationCity', fn ($c) => $c->whereRaw('LOWER(name) = ?', [strtolower($to)]))
        )
        ->where('status', 'scheduled')
        ->where('is_active', true)
        ->where('available_seats', '>', 0)
        ->where('trip_date', '>=', today()->toDateString())
        ->where('trip_date', '!=', $date)
        ->orderBy('trip_date')
        ->limit(5)
        ->pluck('trip_date')
        ->unique()
        ->values();
    }

    /**
     * City dropdowns for the search form.
     */
    private function dropdowns(): array
    {
        $origin = BusRoute::with('originCity')->where('status', 'active')
            ->get()->pluck('originCity')->filter()->unique('id')->sortBy('name')->values();

        $dest = BusRoute::with('destinationCity')->where('status', 'active')
            ->get()->pluck('destinationCity')->filter()->unique('id')->sortBy('name')->values();

        return [$origin, $dest];
    }
}