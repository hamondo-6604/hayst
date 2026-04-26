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
    // POST /select-seats/{trip_id} - Handle seat selection and proceed
    // ------------------------------------------------------------------
    public function bookSeats(Request $request, $trip_id)
    {
        $request->validate([
            'selected_seats' => 'required|array|min:1',
            'selected_seats.*' => 'string',
        ]);

        $trip = Trip::findOrFail($trip_id);

        // Check if any of the selected seats are already booked
        $alreadyBooked = \App\Models\BookingSeat::whereHas('booking', function ($q) use ($trip) {
            $q->where('trip_id', $trip->id)
              ->whereIn('status', ['confirmed', 'pending']);
        })->whereIn('seat_number', $request->selected_seats)->exists();

        if ($alreadyBooked) {
            return redirect()->back()->withErrors(['error' => 'One or more of your selected seats are no longer available. Please try again.']);
        }

        // Retrieve fare details from the seat map generator
        $grid = $this->generateSeatMap($trip);
        $seatFares = [];
        foreach ($grid as $row) {
            foreach ($row as $cell) {
                if (($cell['cell_type'] ?? '') === 'seat') {
                    $seatFares[$cell['seat_label']] = $cell['fare'] ?? $trip->fare;
                }
            }
        }

        $totalFare = 0;
        $bookingSeatsData = [];
        
        // Fetch the actual Seat models to get their IDs
        $seats = \App\Models\Seat::where('bus_id', $trip->bus_id ?? $trip->bus->id)
            ->whereIn('seat_number', $request->selected_seats)
            ->get()
            ->keyBy('seat_number');

        foreach ($request->selected_seats as $seatLabel) {
            $fare = $seatFares[$seatLabel] ?? $trip->fare;
            $totalFare += $fare;
            
            $seatModel = $seats->get($seatLabel);
            
            $bookingSeatsData[] = [
                'seat_id'      => $seatModel ? $seatModel->id : 0, // Fallback to 0 if not found, though it should exist
                'seat_type_id' => $seatModel ? $seatModel->seat_type_id : null,
                'seat_number'  => $seatLabel,
                'fare'         => $fare,
                'status'       => 'reserved', // Block the seat
            ];
        }

        // Create the pending Booking
        $booking = \App\Models\Booking::create([
            'user_id'        => auth()->id(),
            'trip_id'        => $trip->id,
            'seat_id'        => $bookingSeatsData[0]['seat_id'] ?? null, // Primary seat for BC
            'status'         => 'pending',
            'base_fare'      => $totalFare,
            'amount_paid'    => 0,
            'payment_status' => 'unpaid',
        ]);

        // Create the BookingSeats
        foreach ($bookingSeatsData as $data) {
            $data['booking_id'] = $booking->id;
            \App\Models\BookingSeat::create($data);
        }

        return redirect()->route('user.booking.details', $booking->id)
                         ->with('success', 'Seats successfully reserved. Please enter passenger details.');
    }

    // ------------------------------------------------------------------
    // GET /booking/{booking_id}/details
    // ------------------------------------------------------------------
    public function passengerDetails($booking_id)
    {
        $booking = \App\Models\Booking::with(['bookingSeats', 'trip.route.originCity', 'trip.route.destinationCity', 'trip.bus'])
            ->where('user_id', auth()->id())
            ->where('status', 'pending')
            ->findOrFail($booking_id);

        $discountTypes = \App\Models\DiscountType::active()->get();

        return view('user.passenger-details', compact('booking', 'discountTypes'));
    }

    // ------------------------------------------------------------------
    // POST /booking/{booking_id}/details
    // ------------------------------------------------------------------
    public function storePassengerDetails(Request $request, $booking_id)
    {
        $booking = \App\Models\Booking::with('bookingSeats')
            ->where('user_id', auth()->id())
            ->where('status', 'pending')
            ->findOrFail($booking_id);

        $request->validate([
            'passengers' => 'required|array',
            'passengers.*.name' => 'required|string|max:255',
            'passengers.*.discount_type_id' => 'nullable|exists:discount_types,id',
        ]);

        $totalDiscount = 0;

        foreach ($booking->bookingSeats as $seat) {
            $data = $request->passengers[$seat->id] ?? null;
            if (!$data) continue;

            $seat->passenger_name = $data['name'];
            
            if (!empty($data['discount_type_id'])) {
                $discount = \App\Models\DiscountType::find($data['discount_type_id']);
                if ($discount) {
                    $seat->passenger_type = $discount->name;
                    $discountAmt = $discount->discountAmount((float)$seat->fare);
                    $totalDiscount += $discountAmt;
                }
            } else {
                $seat->passenger_type = 'regular';
            }
            
            $seat->save();
        }

        $booking->discount_amount = $totalDiscount;
        $booking->save();

        return redirect()->route('user.booking.checkout', $booking->id)
                         ->with('success', 'Passenger details saved. Please proceed to checkout.');
    }

    // ------------------------------------------------------------------
    // GET /booking/{booking_id}/checkout
    // ------------------------------------------------------------------
    public function checkout($booking_id)
    {
        $booking = \App\Models\Booking::with(['bookingSeats', 'trip.route.originCity', 'trip.route.destinationCity', 'trip.bus'])
            ->where('user_id', auth()->id())
            ->where('status', 'pending')
            ->findOrFail($booking_id);

        return view('user.checkout', compact('booking'));
    }

    // ------------------------------------------------------------------
    // POST /booking/{booking_id}/pay
    // ------------------------------------------------------------------
    public function processPayment(Request $request, $booking_id)
    {
        $booking = \App\Models\Booking::where('user_id', auth()->id())
            ->where('status', 'pending')
            ->findOrFail($booking_id);

        $request->validate([
            'payment_method' => 'required|in:credit_card,gcash,paymaya,otc',
        ]);

        $finalAmount = (float) $booking->base_fare - (float) $booking->discount_amount;

        // Simulate creating a payment
        $payment = \App\Models\Payment::create([
            'booking_id' => $booking->id,
            'amount' => $finalAmount,
            'payment_method' => $request->payment_method,
            'status' => 'paid',
            'transaction_id' => 'SIM-' . strtoupper(uniqid()),
            'currency' => 'PHP',
            'paid_at' => now(),
            'gateway_response' => ['simulated' => true],
        ]);

        // Update Booking
        $booking->update([
            'status' => 'confirmed',
            'payment_status' => 'paid',
            'amount_paid' => $finalAmount,
        ]);

        // Update BookingSeats
        \App\Models\BookingSeat::where('booking_id', $booking->id)->update([
            'status' => 'confirmed',
        ]);

        return redirect()->route('user.booking.success', $booking->id);
    }

    // ------------------------------------------------------------------
    // GET /booking/{booking_id}/success
    // ------------------------------------------------------------------
    public function bookingSuccess($booking_id)
    {
        $booking = \App\Models\Booking::with(['bookingSeats', 'trip.route.originCity', 'trip.route.destinationCity', 'trip.bus'])
            ->where('user_id', auth()->id())
            ->where('status', 'confirmed')
            ->findOrFail($booking_id);

        return view('user.booking-success', compact('booking'));
    }

    /**
     * Generate the seat map array with availability status for the given trip.
     */
    private function generateSeatMap(Trip $trip): array
    {
        if (!$trip->bus || !$trip->bus->seatLayout) {
            return [];
        }

        // Get all booked or pending seats for this specific trip
        $bookedSeats = \App\Models\BookingSeat::whereHas('booking', function ($q) use ($trip) {
            $q->where('trip_id', $trip->id)
              ->whereIn('status', ['confirmed', 'pending']);
        })->pluck('seat_number')->toArray();

        // Get the structural grid
        $layoutGrid = $trip->bus->seatLayout->buildGrid();
        $baseFare = (float) $trip->fare;

        // Enhance grid with dynamic data (availability, exact fare)
        $enhancedGrid = [];
        foreach ($layoutGrid as $rowIdx => $row) {
            $enhancedRow = [];
            foreach ($row as $cell) {
                // If the cell is stored as a model/object, array-cast it if necessary.
                // Depending on buildGrid(), it might be arrays or LayoutMap models.
                $cellData = is_array($cell) ? $cell : $cell->toArray();

                if (($cellData['cell_type'] ?? '') === 'seat' && ($cellData['is_bookable'] ?? false)) {
                    $seatLabel = $cellData['seat_label'] ?? '';
                    $cellData['is_available'] = !in_array($seatLabel, $bookedSeats);
                    
                    // Optional: calculate dynamic fare if a seat_type_id is provided
                    $fare = $baseFare;
                    if (!empty($cellData['seat_type_id'])) {
                        $seatType = \App\Models\SeatType::find($cellData['seat_type_id']);
                        if ($seatType) {
                            $fare = $seatType->calculateFare($baseFare);
                        }
                    }
                    $cellData['fare'] = $fare;
                }

                $enhancedRow[] = $cellData;
            }
            $enhancedGrid[] = $enhancedRow;
        }

        return $enhancedGrid;
    }
}