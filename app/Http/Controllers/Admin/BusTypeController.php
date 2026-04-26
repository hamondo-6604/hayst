<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BusType;
use App\Models\SeatLayout;
use Illuminate\Http\Request;

class BusTypeController extends Controller
{
    public function index(Request $request)
    {
        $query = BusType::with(['seatLayout'])->withCount('buses');
        
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }
        
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where('type_name', 'like', "%{$search}%");
        }
        
        $busTypes = $query->latest()->paginate(15)->withQueryString();
        
        return view('admin.bus-types.index', compact('busTypes'));
    }

    public function create()
    {
        $seatLayouts = SeatLayout::active()->get();
        return view('admin.bus-types.form', ['busType' => new BusType(), 'seatLayouts' => $seatLayouts]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'type_name' => 'required|string|max:255',
            'seat_layout_id' => 'nullable|exists:seat_layouts,id',
            'status' => 'required|in:active,inactive',
            'description' => 'nullable|string|max:500',
        ]);

        BusType::create($validated);

        return redirect()->route('admin.bus-types.index')->with('success', 'Bus type created successfully.');
    }

    public function show(BusType $busType)
    {
        return redirect()->route('admin.bus-types.edit', $busType);
    }

    public function edit(BusType $busType)
    {
        $seatLayouts = SeatLayout::active()->get();
        return view('admin.bus-types.form', compact('busType', 'seatLayouts'));
    }

    public function update(Request $request, BusType $busType)
    {
        $validated = $request->validate([
            'type_name' => 'required|string|max:255',
            'seat_layout_id' => 'nullable|exists:seat_layouts,id',
            'status' => 'required|in:active,inactive',
            'description' => 'nullable|string|max:500',
        ]);

        $busType->update($validated);

        return redirect()->route('admin.bus-types.index')->with('success', 'Bus type updated successfully.');
    }

    public function destroy(BusType $busType)
    {
        if ($busType->buses()->exists()) {
            return back()->with('error', 'Cannot delete bus type because it is assigned to existing buses.');
        }

        $busType->delete();
        return redirect()->route('admin.bus-types.index')->with('success', 'Bus type deleted successfully.');
    }
}
