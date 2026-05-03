<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\Feedback;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $query = Notification::with('user');
        
        if ($request->has('status') && $request->status != '') {
            $query->where('is_read', $request->status === 'read');
        }
        
        $notifications = $query->latest()->paginate(20)->withQueryString();
        
        return view('admin.notifications.index', compact('notifications'));
    }

    public function markAllRead()
    {
        auth()->user()->appNotifications()->unread()->update([
            'is_read' => true,
            'read_at' => now()
        ]);
        
        return back()->with('success', 'All notifications marked as read.');
    }

    public function poll()
    {
        $unreadNotifs = auth()->user()->appNotifications()->unread()->latest()->take(5)->get();
        $unreadCount = auth()->user()->appNotifications()->unread()->count();
        
        $newBookingsCount = auth()->user()->appNotifications()
            ->unread()
            ->where('type', 'booking_confirmed')
            ->count();
            
        $newFeedbackCount = auth()->user()->appNotifications()
            ->unread()
            ->where('type', 'new_feedback')
            ->count();
        
        $html = view('admin.notifications.partials.dropdown-items', compact('unreadNotifs'))->render();
        
        return response()->json([
            'count' => $unreadCount,
            'html' => $html,
            'new_bookings_count' => $newBookingsCount,
            'pending_feedback_count' => $newFeedbackCount
        ]);
    }
}
