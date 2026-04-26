<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SeatLayout;
use Illuminate\Http\Request;

class SeatController extends Controller
{
    public function index(Request $request)
    {
        $query = SeatLayout::withCount(['buses', 'busTypes']);
        
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }
        
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where('layout_name', 'like', "%{$search}%");
        }
        
        $layouts = $query->latest()->paginate(15)->withQueryString();
        
        return view('admin.seats.index', compact('layouts'));
    }
}
