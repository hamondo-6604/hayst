<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Promotion;
use Illuminate\Http\Request;

class PromotionController extends Controller
{
    public function index(Request $request)
    {
        $query = Promotion::query();
        
        if ($request->has('status') && $request->status != '') {
            $query->where('is_active', $request->status === 'active');
        }
        
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where('code', 'like', "%{$search}%")
                  ->orWhere('name', 'like', "%{$search}%");
        }
        
        $promotions = $query->latest()->paginate(15)->withQueryString();
        
        return view('admin.promotions.index', compact('promotions'));
    }
}
