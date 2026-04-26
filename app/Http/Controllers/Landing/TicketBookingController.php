<?php

namespace App\Http\Controllers\Landing;

use App\Http\Controllers\Controller;
use App\Models\BusRoute;
use App\Models\Trip;
use App\Models\Bus;
use App\Models\Seat;
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
        $upcomingTrips   = collect();

        if ($from && $to) {
            $trips = $this->searchTrips($from, $to, $date);

            // If no trips found, get comprehensive upcoming trips for this route
            if ($trips->isEmpty()) {
                $alternativeDates = $this->findAlternativeDates($from, $to, $date);
                $upcomingTrips = $this->getUpcomingTripsForRoute($from, $to, $date);
            }
        }

        return view('pages.ticket_booking', compact(
            'originCities', 'destinationCities',
            'prefill', 'trips', 'alternativeDates', 'upcomingTrips'
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
        $upcomingTrips   = collect();
        if ($trips->isEmpty()) {
            $alternativeDates = $this->findAlternativeDates(
                $prefill['from'], $prefill['to'], $prefill['date']
            );
            $upcomingTrips = $this->getUpcomingTripsForRoute(
                $prefill['from'], $prefill['to'], $prefill['date']
            );
        }

        return view('pages.ticket_booking', compact(
            'originCities', 'destinationCities',
            'prefill', 'trips', 'alternativeDates', 'upcomingTrips'
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
     * Get detailed upcoming trips for a specific route when no trips are available for the selected date.
     * Returns comprehensive trip information grouped by date.
     */
    private function getUpcomingTripsForRoute(string $from, string $to, string $excludeDate): \Illuminate\Support\Collection
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
        ->where('status', 'scheduled')
        ->where('is_active', true)
        ->where('available_seats', '>', 0)
        ->where('trip_date', '>=', today()->toDateString())
        ->where('trip_date', '!=', $excludeDate)
        ->orderBy('trip_date')
        ->orderBy('departure_time')
        ->limit(20) // Show up to 20 upcoming trips
        ->get()
        ->groupBy('trip_date'); // Group trips by date for better organization
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

    // ------------------------------------------------------------------
    // GET /select-seats/{trip_id} - Show seat selection page
    // ------------------------------------------------------------------
    public function selectSeats($trip_id)
    {
        $trip = Trip::with([
            'route.originCity',
            'route.destinationCity',
            'bus.type',
            'bus.seatLayout',
            'departureTerminal',
        ])->findOrFail($trip_id);

        // Generate seat map based on bus seat layout
        $seatMap = $this->generateSeatMap($trip);

        return view('user.select-seats', compact('trip', 'seatMap'));
    }

    // ------------------------------------------------------------------
    // POST /select-seats/{trip_id} - Process seat booking
    // ------------------------------------------------------------------
    public function bookSeats(Request $request, $trip_id)
    {
        $request->validate([
            'selected_seats' => 'required|array|min:1',
            'selected_seats.*' => 'string',
        ]);

        $trip = Trip::findOrFail($trip_id);
        $selectedSeats = json_decode($request->selected_seats, true);

        // Validate seats are available
        $this->validateSeatAvailability($trip, $selectedSeats);

        // Here you would typically:
        // 1. Create booking record
        // 2. Mark seats as booked
        // 3. Process payment
        // 4. Send confirmation

        return redirect()->route('manage.bookings')
            ->with('success', 'Seats booked successfully! Booking reference: MEX-' . str_pad($trip_id, 6, '0', STR_PAD_LEFT));
    }

    // ------------------------------------------------------------------
    // Generate seat map for the trip
    // ------------------------------------------------------------------
    private function generateSeatMap($trip)
    {
        $bus = $trip->bus;
        $seatLayout = $bus?->seatLayout;

        if (!$seatLayout) {
            return [];
        }

        $seatMap = [];
        $layout = json_decode($seatLayout->layout, true) ?? [];
        $bookedSeats = $this->getBookedSeats($trip->id);

        // Generate seat grid based on layout
        for ($row = 1; $row <= $seatLayout->rows; $row++) {
            $seatRow = [];
            
            for ($col = 1; $col <= $seatLayout->columns; $col++) {
                $seatNumber = $this->generateSeatNumber($row, $col, $seatLayout->columns);
                
                // Check if this position is an aisle
                if ($this->isAislePosition($col, $seatLayout->columns, $seatLayout->aisle_positions ?? [])) {
                    $seatRow[] = ['type' => 'aisle', 'seat_number' => '', 'status' => 'aisle'];
                } else {
                    $status = in_array($seatNumber, $bookedSeats) ? 'booked' : 'available';
                    $seatRow[] = [
                        'type' => 'seat',
                        'seat_number' => $seatNumber,
                        'status' => $status
                    ];
                }
            }
            
            $seatMap[] = $seatRow;
        }

        return $seatMap;
    }

    // ------------------------------------------------------------------
    // Generate seat number based on row and column
    // ------------------------------------------------------------------
    private function generateSeatNumber($row, $col, $totalCols)
    {
        // Simple seat numbering: A1, A2, B1, B2, etc.
        $letter = chr(64 + $row); // A, B, C, etc.
        return $letter . $col;
    }

    // ------------------------------------------------------------------
    // Check if position is an aisle
    // ------------------------------------------------------------------
    private function isAislePosition($col, $totalCols, $aislePositions = [])
    {
        // Default aisle position in the middle for even number of columns
        if (empty($aislePositions) && $totalCols % 2 === 0) {
            return $col === ($totalCols / 2) || $col === ($totalCols / 2 + 1);
        }
        
        return in_array($col, $aislePositions);
    }

    // ------------------------------------------------------------------
    // Get already booked seats for a trip
    // ------------------------------------------------------------------
    private function getBookedSeats($tripId)
    {
        // This would typically query your bookings table
        // For now, return empty array (all seats available)
        return [];
    }

    // ------------------------------------------------------------------
    // Validate that selected seats are available
    // ------------------------------------------------------------------
    private function validateSeatAvailability($trip, $selectedSeats)
    {
        $bookedSeats = $this->getBookedSeats($trip->id);
        $unavailableSeats = array_intersect($selectedSeats, $bookedSeats);
        
        if (!empty($unavailableSeats)) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'selected_seats' => 'The following seats are already booked: ' . implode(', ', $unavailableSeats)
            ]);
        }

        // Check if enough seats are available
        if (count($selectedSeats) > $trip->available_seats) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'selected_seats' => 'Only ' . $trip->available_seats . ' seats are available for this trip.'
            ]);
        }
    }
}