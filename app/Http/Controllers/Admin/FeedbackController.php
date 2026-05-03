<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Feedback;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{
    public function index(Request $request)
    {
        // Mark new feedback notifications as read
        if (auth()->check()) {
            auth()->user()->appNotifications()
                ->unread()
                ->where('type', 'new_feedback')
                ->update(['is_read' => true, 'read_at' => now()]);
        }

        $query = Feedback::with(['user', 'trip']);
        
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }
        
        if ($request->has('rating') && $request->rating != '') {
            $query->where('rating', $request->rating);
        }
        
        $feedbacks = $query->latest()->paginate(15)->withQueryString();
        
        return view('admin.feedback.index', compact('feedbacks'));
    }
}
