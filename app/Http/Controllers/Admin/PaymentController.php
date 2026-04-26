<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $query = Payment::with(['booking.user']);
        
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }
        
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where('transaction_id', 'like', "%{$search}%")
                  ->orWhereHas('booking', function($q) use ($search) {
                      $q->where('booking_reference', 'like', "%{$search}%")
                        ->orWhereHas('user', function($q2) use ($search) {
                            $q2->where('name', 'like', "%{$search}%");
                        });
                  });
        }
        
        $payments = $query->latest()->paginate(15)->withQueryString();
        
        return view('admin.payments.index', compact('payments'));
    }
}
