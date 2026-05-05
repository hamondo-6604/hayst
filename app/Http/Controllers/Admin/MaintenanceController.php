<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MaintenanceLog;
use App\Models\Bus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MaintenanceController extends Controller
{
    public function index(Request $request)
    {
        $query = MaintenanceLog::with(['bus', 'loggedBy']);
        
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }
        
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where('title', 'like', "%{$search}%")
                  ->orWhereHas('bus', function($q) use ($search) {
                      $q->where('bus_number', 'like', "%{$search}%");
                  });
        }
        
        $logs = $query->latest('maintenance_date')->paginate(15)->withQueryString();
        
        $buses = Bus::all();
        
        return view('admin.maintenance.index', compact('logs', 'buses'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'bus_id' => 'required|exists:buses,id',
            'title' => 'required|string|max:255',
            'type' => 'required|in:routine,repair,inspection,emergency',
            'status' => 'required|in:scheduled,in_progress,completed,cancelled',
            'maintenance_date' => 'required|date',
            'completed_date' => 'nullable|date|after_or_equal:maintenance_date',
            'cost' => 'nullable|numeric|min:0',
            'performed_by' => 'nullable|string|max:255',
            'parts_replaced' => 'nullable|string',
            'next_maintenance_due' => 'nullable|date|after:maintenance_date',
            'description' => 'nullable|string',
        ]);

        $validated['logged_by'] = Auth::id();

        MaintenanceLog::create($validated);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Maintenance log created successfully.']);
        }

        return redirect()->route('admin.maintenance.index')->with('success', 'Maintenance log created successfully.');
    }

    public function update(Request $request, MaintenanceLog $maintenance)
    {
        $validated = $request->validate([
            'bus_id' => 'required|exists:buses,id',
            'title' => 'required|string|max:255',
            'type' => 'required|in:routine,repair,inspection,emergency',
            'status' => 'required|in:scheduled,in_progress,completed,cancelled',
            'maintenance_date' => 'required|date',
            'completed_date' => 'nullable|date|after_or_equal:maintenance_date',
            'cost' => 'nullable|numeric|min:0',
            'performed_by' => 'nullable|string|max:255',
            'parts_replaced' => 'nullable|string',
            'next_maintenance_due' => 'nullable|date|after:maintenance_date',
            'description' => 'nullable|string',
        ]);

        $maintenance->update($validated);

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Maintenance log updated successfully.']);
        }

        return redirect()->route('admin.maintenance.index')->with('success', 'Maintenance log updated successfully.');
    }

    public function destroy(MaintenanceLog $maintenance)
    {
        $maintenance->delete();

        if (request()->wantsJson() || request()->ajax()) {
            return response()->json(['success' => true, 'message' => 'Maintenance log deleted successfully.']);
        }

        return redirect()->route('admin.maintenance.index')->with('success', 'Maintenance log deleted successfully.');
    }
}
