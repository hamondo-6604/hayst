<?php

namespace App\Http\Controllers\Landing;

use App\Http\Controllers\Controller;
use App\Models\BusRoute;
use App\Models\City;
use App\Models\Trip;
use Illuminate\Http\Request;

class TicketBookingController extends Controller
{
    public function index()
    {
        [$originCities, $destinationCities] = $this->dropdowns();

        // Pre-fill from query string (from home search widget)
        $prefill = [
            'from' => request('from'),
            'to'   => request('to'),
            'date' => request('date', today()->toDateString()),
        ];

        $trips = collect();
        if ($prefill['from'] && $prefill['to']) {
            $trips = $this->searchTrips($prefill['from'], $prefill['to'], $prefill['date']);
        }

        return view('pages.ticket_booking', compact('originCities', 'destinationCities', 'prefill', 'trips'));
    }

    public function search(Request $request)
    {
        $request->validate([
            'from' => 'required|string',
            'to'   => 'required|string|different:from',
            'date' => 'required|date|after_or_equal:today',
        ]);

        [$originCities, $destinationCities] = $this->dropdowns();

        $prefill = $request->only('from', 'to', 'date');
        $trips   = $this->searchTrips($prefill['from'], $prefill['to'], $prefill['date']);

        return view('pages.ticket_booking', compact('originCities', 'destinationCities', 'prefill', 'trips'));
    }

    // ------------------------------------------------------------------

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
            $q->whereHas('originCity',      fn ($c) => $c->where('name', $from))
              ->whereHas('destinationCity', fn ($c) => $c->where('name', $to))
        )
        ->whereDate('trip_date', $date)
        ->where('status', 'scheduled')
        ->where('is_active', true)
        ->where('available_seats', '>', 0)
        ->orderBy('departure_time')
        ->get();
    }

    private function dropdowns(): array
    {
        $origin = BusRoute::with('originCity')->where('status', 'active')
            ->get()->pluck('originCity')->filter()->unique('id')->sortBy('name')->values();

        $dest = BusRoute::with('destinationCity')->where('status', 'active')
            ->get()->pluck('destinationCity')->filter()->unique('id')->sortBy('name')->values();

        return [$origin, $dest];
    }
}