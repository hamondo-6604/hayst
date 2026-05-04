<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Trip;
use App\Models\BusRoute;
use App\Models\Bus;
use App\Models\Driver;
use App\Models\Terminal;
use Illuminate\Http\Request;

class TripController extends Controller
{
    public function index(Request $request)
    {
        $query = Trip::with(['route.originCity', 'route.destinationCity', 'bus', 'driver'])
            ->orderBy('trip_date', 'desc')
            ->orderBy('departure_time', 'desc');

        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }
        
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where('trip_code', 'like', "%{$search}%")
                  ->orWhereHas('route.originCity', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  })
                  ->orWhereHas('route.destinationCity', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
        }

        $trips = $query->paginate(15)->withQueryString();

        $routes = BusRoute::active()->get();
        $buses = Bus::active()->get();
        $drivers = Driver::where('status', 'active')->get();
        $terminals = class_exists(Terminal::class) ? Terminal::active()->get() : collect();

        return view('admin.trips.index', compact('trips', 'routes', 'buses', 'drivers', 'terminals'));
    }

    public function create()
    {
        $routes = BusRoute::active()->get();
        $buses = Bus::active()->get();
        $drivers = Driver::where('status', 'active')->get();
        $terminals = class_exists(Terminal::class) ? Terminal::active()->get() : collect();
        
        return view('admin.trips.form', [
            'trip' => new Trip(), 
            'routes' => $routes, 
            'buses' => $buses, 
            'drivers' => $drivers,
            'terminals' => $terminals
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'trip_code' => 'required|string|max:255|unique:trips,trip_code',
            'route_id' => 'required|exists:routes,id',
            'bus_id' => 'required|exists:buses,id',
            'driver_id' => 'required|exists:drivers,id',
            'departure_terminal_id' => 'nullable|exists:terminals,id',
            'arrival_terminal_id' => 'nullable|exists:terminals,id',
            'trip_date' => 'required|date',
            'departure_time' => 'required|date',
            'arrival_time' => 'required|date|after:departure_time',
            'available_seats' => 'required|integer|min:0',
            'fare' => 'required|numeric|min:0',
            'status' => 'required|in:scheduled,boarding,in_transit,completed,cancelled,delayed',
            'notes' => 'nullable|string',
        ]);
        
        $validated['is_active'] = $request->has('is_active');

        Trip::create($validated);

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Trip created successfully.']);
        }

        return redirect()->route('admin.trips.index')->with('success', 'Trip created successfully.');
    }

    public function show(Trip $trip)
    {
        $trip->load([
            'route.originCity', 
            'route.destinationCity', 
            'bus', 
            'driver',
            'bookings' => function($q) {
                $q->whereIn('status', ['pending', 'confirmed', 'completed'])
                  ->with(['user', 'bookingSeats']);
            }
        ]);

        return view('admin.trips.show', compact('trip'));
    }

    public function edit(Trip $trip)
    {
        $routes = BusRoute::active()->get();
        $buses = Bus::active()->get();
        $drivers = Driver::where('status', 'active')->get();
        $terminals = class_exists(Terminal::class) ? Terminal::active()->get() : collect();
        
        return view('admin.trips.form', compact('trip', 'routes', 'buses', 'drivers', 'terminals'));
    }

    public function update(Request $request, Trip $trip)
    {
        $validated = $request->validate([
            'trip_code' => 'required|string|max:255|unique:trips,trip_code,' . $trip->id,
            'route_id' => 'required|exists:routes,id',
            'bus_id' => 'required|exists:buses,id',
            'driver_id' => 'required|exists:drivers,id',
            'departure_terminal_id' => 'nullable|exists:terminals,id',
            'arrival_terminal_id' => 'nullable|exists:terminals,id',
            'trip_date' => 'required|date',
            'departure_time' => 'required|date',
            'arrival_time' => 'required|date|after:departure_time',
            'available_seats' => 'required|integer|min:0',
            'fare' => 'required|numeric|min:0',
            'status' => 'required|in:scheduled,boarding,in_transit,completed,cancelled,delayed',
            'notes' => 'nullable|string',
        ]);
        
        $validated['is_active'] = $request->has('is_active');

        $trip->update($validated);

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Trip updated successfully.']);
        }

        return redirect()->route('admin.trips.index')->with('success', 'Trip updated successfully.');
    }

    public function destroy(Request $request, Trip $trip)
    {
        if ($trip->bookings()->exists()) {
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Cannot delete trip because it has existing bookings.'], 400);
            }
            return back()->with('error', 'Cannot delete trip because it has existing bookings.');
        }

        $trip->delete();

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Trip deleted successfully.']);
        }

        return redirect()->route('admin.trips.index')->with('success', 'Trip deleted successfully.');
    }
}
