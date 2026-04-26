@extends('layouts.admin')

@section('title', 'Manage Bookings')

@section('content')
<div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
    <div>
        <h1 class="text-2xl font-bold text-slate-800 dark:text-white">Bookings</h1>
        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">View and manage all customer bookings.</p>
    </div>
</div>

<!-- Filters -->
<div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 p-4 mb-6">
    <form method="GET" action="{{ route('admin.bookings.index') }}" class="flex flex-col sm:flex-row gap-4 items-end">
        <div class="flex-1">
            <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 mb-1">Search</label>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Booking Ref, Name, or Email..." 
                   class="w-full rounded-xl border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-white text-sm focus:ring-primary-500 focus:border-primary-500">
        </div>
        <div class="w-full sm:w-48">
            <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 mb-1">Status</label>
            <select name="status" class="w-full rounded-xl border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-white text-sm focus:ring-primary-500 focus:border-primary-500">
                <option value="">All Statuses</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="confirmed" {{ request('status') === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
            </select>
        </div>
        <div>
            <button type="submit" class="px-5 py-2.5 bg-slate-800 hover:bg-slate-900 dark:bg-slate-700 dark:hover:bg-slate-600 text-white text-sm font-semibold rounded-xl transition-colors">
                Filter
            </button>
            @if(request()->hasAny(['search', 'status']))
                <a href="{{ route('admin.bookings.index') }}" class="ml-2 text-sm text-primary-600 hover:text-primary-700 dark:text-primary-400">Clear</a>
            @endif
        </div>
    </form>
</div>

<!-- Table -->
<div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 dark:bg-slate-700/50 border-b border-slate-100 dark:border-slate-700 text-xs uppercase tracking-wider text-slate-500 dark:text-slate-400 font-semibold">
                    <th class="p-4">Reference</th>
                    <th class="p-4">Customer</th>
                    <th class="p-4">Trip</th>
                    <th class="p-4">Fare</th>
                    <th class="p-4">Status</th>
                    <th class="p-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                @forelse($bookings as $booking)
                <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors">
                    <td class="p-4">
                        <span class="font-semibold text-slate-800 dark:text-slate-200">{{ $booking->booking_reference }}</span>
                        <div class="text-xs text-slate-500 mt-0.5">{{ $booking->created_at->format('M d, Y h:i A') }}</div>
                    </td>
                    <td class="p-4">
                        <div class="font-medium text-slate-800 dark:text-slate-200">{{ $booking->user->name ?? 'Guest' }}</div>
                        <div class="text-xs text-slate-500">{{ $booking->user->email ?? '' }}</div>
                    </td>
                    <td class="p-4">
                        @if($booking->trip && $booking->trip->route)
                            <div class="text-sm font-medium text-slate-800 dark:text-slate-200">
                                {{ $booking->trip->route->originCity->name ?? 'Unknown' }} &rarr; {{ $booking->trip->route->destinationCity->name ?? 'Unknown' }}
                            </div>
                            <div class="text-xs text-slate-500 mt-0.5">
                                {{ $booking->trip->departure_time ? $booking->trip->departure_time->format('M d, Y h:i A') : 'TBA' }}
                            </div>
                        @else
                            <span class="text-slate-400 italic">Trip removed</span>
                        @endif
                    </td>
                    <td class="p-4">
                        <div class="font-semibold text-slate-800 dark:text-slate-200">{{ $booking->formatted_amount_paid }}</div>
                        @if($booking->seat_count > 1)
                            <div class="text-xs text-slate-500 mt-0.5">{{ $booking->seat_count }} seats</div>
                        @endif
                    </td>
                    <td class="p-4">
                        @php
                            $badgeColors = [
                                'pending' => 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400',
                                'confirmed' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400',
                                'completed' => 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400',
                                'cancelled' => 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400',
                            ];
                            $colorClass = $badgeColors[$booking->status] ?? 'bg-slate-100 text-slate-700';
                        @endphp
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $colorClass }}">
                            {{ ucfirst($booking->status) }}
                        </span>
                        <div class="text-xs mt-1 text-slate-500">
                            {{ ucfirst($booking->payment_status) }}
                        </div>
                    </td>
                    <td class="p-4 text-right">
                        <a href="{{ route('admin.bookings.show', $booking) }}" class="inline-flex items-center justify-center w-8 h-8 rounded-lg text-primary-600 hover:bg-primary-50 dark:hover:bg-primary-900/20 transition-colors" title="View Details">
                            <i class="fa-solid fa-eye"></i>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="p-8 text-center text-slate-500 dark:text-slate-400">
                        <div class="text-4xl mb-2"><i class="fa-solid fa-ticket text-slate-300 dark:text-slate-600"></i></div>
                        <p>No bookings found.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($bookings->hasPages())
    <div class="p-4 border-t border-slate-100 dark:border-slate-700">
        {{ $bookings->links() }}
    </div>
    @endif
</div>
@endsection
