<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\City;
use Illuminate\Http\Request;

class CityController extends Controller
{
    public function index(Request $request)
    {
        $query = City::query();

        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('province', 'like', "%{$search}%")
                  ->orWhere('region', 'like', "%{$search}%");
        }

        $cities = $query->orderBy('name')->paginate(15)->withQueryString();

        return view('admin.cities.index', compact('cities'));
    }

    public function create()
    {
        return view('admin.cities.form', ['city' => new City()]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'province' => 'nullable|string|max:255',
            'region' => 'nullable|string|max:255',
            'status' => 'required|in:active,inactive',
        ]);

        $city = City::create($validated);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['success' => true, 'message' => 'City created successfully.', 'data' => $city]);
        }

        return redirect()->route('admin.cities.index')->with('success', 'City created successfully.');
    }

    public function show(City $city)
    {
        return redirect()->route('admin.cities.edit', $city);
    }

    public function edit(City $city)
    {
        return view('admin.cities.form', compact('city'));
    }

    public function update(Request $request, City $city)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'province' => 'nullable|string|max:255',
            'region' => 'nullable|string|max:255',
            'status' => 'required|in:active,inactive',
        ]);

        $city->update($validated);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['success' => true, 'message' => 'City updated successfully.', 'data' => $city]);
        }

        return redirect()->route('admin.cities.index')->with('success', 'City updated successfully.');
    }

    public function destroy(City $city)
    {
        if ($city->originRoutes()->exists() || $city->destinationRoutes()->exists()) {
            if (request()->wantsJson() || request()->ajax()) {
                return response()->json(['success' => false, 'message' => 'Cannot delete city because it is assigned to existing routes.'], 400);
            }
            return back()->with('error', 'Cannot delete city because it is assigned to existing routes.');
        }

        $city->delete();
        
        if (request()->wantsJson() || request()->ajax()) {
            return response()->json(['success' => true, 'message' => 'City deleted successfully.']);
        }
        
        return redirect()->route('admin.cities.index')->with('success', 'City deleted successfully.');
    }
}
