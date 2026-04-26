<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MaintenanceLog;
use Illuminate\Http\Request;

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
        
        return view('admin.maintenance.index', compact('logs'));
    }
}
