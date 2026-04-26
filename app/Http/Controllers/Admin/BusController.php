<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bus;
use App\Models\BusType;
use App\Models\SeatLayout;
use Illuminate\Http\Request;

class BusController extends Controller
{
    public function index(Request $request)
    {
        $query = Bus::with(['type', 'seatLayout']);
        
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }
        
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where('bus_number', 'like', "%{$search}%")
                  ->orWhere('bus_name', 'like', "%{$search}%");
        }
        
        $buses = $query->latest()->paginate(15)->withQueryString();
        
        return view('admin.buses.index', compact('buses'));
    }

    public function create()
    {
        $busTypes = BusType::active()->get();
        $seatLayouts = SeatLayout::active()->get();
        return view('admin.buses.form', ['bus' => new Bus(), 'busTypes' => $busTypes, 'seatLayouts' => $seatLayouts]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'bus_number' => 'required|string|max:255|unique:buses,bus_number',
            'bus_name' => 'nullable|string|max:255',
            'bus_type_id' => 'required|exists:bus_types,id',
            'seat_layout_id' => 'required|exists:seat_layouts,id',
            'total_seats' => 'required|integer|min:1',
            'status' => 'required|in:active,maintenance,inactive',
            'description' => 'nullable|string|max:500',
        ]);

        Bus::create($validated);

        return redirect()->route('admin.buses.index')->with('success', 'Bus created successfully.');
    }

    public function show(Bus $bus)
    {
        return redirect()->route('admin.buses.edit', $bus);
    }

    public function edit(Bus $bus)
    {
        $busTypes = BusType::active()->get();
        $seatLayouts = SeatLayout::active()->get();
        return view('admin.buses.form', compact('bus', 'busTypes', 'seatLayouts'));
    }

    public function update(Request $request, Bus $bus)
    {
        $validated = $request->validate([
            'bus_number' => 'required|string|max:255|unique:buses,bus_number,' . $bus->id,
            'bus_name' => 'nullable|string|max:255',
            'bus_type_id' => 'required|exists:bus_types,id',
            'seat_layout_id' => 'required|exists:seat_layouts,id',
            'total_seats' => 'required|integer|min:1',
            'status' => 'required|in:active,maintenance,inactive',
            'description' => 'nullable|string|max:500',
        ]);

        $bus->update($validated);

        return redirect()->route('admin.buses.index')->with('success', 'Bus updated successfully.');
    }

    public function destroy(Bus $bus)
    {
        if ($bus->trips()->exists()) {
            return back()->with('error', 'Cannot delete bus because it is assigned to existing trips.');
        }

        $bus->delete();
        return redirect()->route('admin.buses.index')->with('success', 'Bus deleted successfully.');
    }
}
