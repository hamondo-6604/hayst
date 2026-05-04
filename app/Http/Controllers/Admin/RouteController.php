<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BusRoute;
use App\Models\City;
use App\Models\Terminal;
use Illuminate\Http\Request;

class RouteController extends Controller
{
    public function index(Request $request)
    {
        $query = BusRoute::with(['originCity', 'destinationCity', 'originTerminal', 'destinationTerminal']);

        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }
        
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where('route_name', 'like', "%{$search}%")
                  ->orWhereHas('originCity', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  })
                  ->orWhereHas('destinationCity', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
        }

        $routes = $query->paginate(15)->withQueryString();
        $cities = City::active()->orderBy('name')->get();
        $terminals = Terminal::active()->orderBy('name')->get();

        return view('admin.routes.index', compact('routes', 'cities', 'terminals'));
    }

    public function create()
    {
        $cities = City::active()->orderBy('name')->get();
        $terminals = Terminal::active()->orderBy('name')->get();
        return view('admin.routes.form', ['route' => new BusRoute(), 'cities' => $cities, 'terminals' => $terminals]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'route_name' => 'required|string|max:255',
            'origin_city_id' => 'required|exists:cities,id',
            'destination_city_id' => 'required|exists:cities,id|different:origin_city_id',
            'origin_terminal_id' => 'nullable|exists:terminals,id',
            'destination_terminal_id' => 'nullable|exists:terminals,id',
            'distance_km' => 'nullable|numeric|min:0',
            'estimated_duration_minutes' => 'nullable|integer|min:1',
            'status' => 'required|in:active,inactive',
            'description' => 'nullable|string|max:1000',
        ]);

        BusRoute::create($validated);

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Route created successfully.']);
        }

        return redirect()->route('admin.routes.index')->with('success', 'Route created successfully.');
    }

    public function show(BusRoute $route)
    {
        $route->load(['originCity', 'destinationCity', 'originTerminal', 'destinationTerminal', 'stops']);
        return view('admin.routes.show', compact('route'));
    }

    public function edit(BusRoute $route)
    {
        $cities = City::active()->orderBy('name')->get();
        $terminals = Terminal::active()->orderBy('name')->get();
        return view('admin.routes.form', compact('route', 'cities', 'terminals'));
    }

    public function update(Request $request, BusRoute $route)
    {
        $validated = $request->validate([
            'route_name' => 'required|string|max:255',
            'origin_city_id' => 'required|exists:cities,id',
            'destination_city_id' => 'required|exists:cities,id|different:origin_city_id',
            'origin_terminal_id' => 'nullable|exists:terminals,id',
            'destination_terminal_id' => 'nullable|exists:terminals,id',
            'distance_km' => 'nullable|numeric|min:0',
            'estimated_duration_minutes' => 'nullable|integer|min:1',
            'status' => 'required|in:active,inactive',
            'description' => 'nullable|string|max:1000',
        ]);

        $route->update($validated);

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Route updated successfully.']);
        }

        return redirect()->route('admin.routes.index')->with('success', 'Route updated successfully.');
    }

    public function destroy(Request $request, BusRoute $route)
    {
        if ($route->trips()->exists()) {
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Cannot delete route because it is assigned to existing trips.'], 400);
            }
            return back()->with('error', 'Cannot delete route because it is assigned to existing trips.');
        }

        $route->delete();
        
        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Route deleted successfully.']);
        }
        
        return redirect()->route('admin.routes.index')->with('success', 'Route deleted successfully.');
    }
}
