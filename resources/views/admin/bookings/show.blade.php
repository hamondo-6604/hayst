@extends('layouts.admin')

@section('title', 'Booking Details')

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.bookings.index') }}" class="text-sm font-medium text-slate-500 hover:text-primary-600 dark:hover:text-primary-400 transition-colors flex items-center gap-1 mb-2">
        <i class="fa-solid fa-arrow-left"></i> Back to Bookings
    </a>
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800 dark:text-white flex items-center gap-3">
                Booking #{{ $booking->booking_reference }}
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
            </h1>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Placed on {{ $booking->created_at->format('M d, Y h:i A') }}</p>
        </div>
        <div class="flex gap-2">
            @if($booking->status !== 'cancelled' && $booking->status !== 'completed')
                <form method="POST" action="{{ route('admin.bookings.update-status', $booking) }}" onsubmit="return confirm('Are you sure you want to cancel this booking?');">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="status" value="cancelled">
                    <button type="submit" class="px-4 py-2 bg-white border border-red-200 text-red-600 hover:bg-red-50 hover:border-red-300 dark:bg-slate-800 dark:border-red-900/50 dark:hover:bg-red-900/20 text-sm font-semibold rounded-xl transition-colors">
                        Cancel Booking
                    </button>
                </form>
            @endif
            @if($booking->status === 'pending')
                <form method="POST" action="{{ route('admin.bookings.update-status', $booking) }}">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="status" value="confirmed">
                    <button type="submit" class="px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white text-sm font-semibold rounded-xl shadow-sm transition-colors">
                        Mark as Confirmed
                    </button>
                </form>
            @endif
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Left Column: Trip & Payment -->
    <div class="lg:col-span-2 space-y-6">
        
        <!-- Trip Summary -->
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 overflow-hidden">
            <div class="p-5 border-b border-slate-100 dark:border-slate-700">
                <h2 class="text-lg font-bold text-slate-800 dark:text-white flex items-center gap-2">
                    <i class="fa-solid fa-bus text-primary-500"></i> Trip Details
                </h2>
            </div>
            <div class="p-5">
                @if($booking->trip && $booking->trip->route)
                    <div class="flex items-center gap-6 mb-6">
                        <div class="flex-1">
                            <p class="text-xs text-slate-500 dark:text-slate-400 uppercase tracking-wide font-semibold mb-1">Origin</p>
                            <p class="text-lg font-bold text-slate-800 dark:text-white">{{ $booking->trip->route->originCity->name ?? 'Unknown' }}</p>
                            <p class="text-sm text-slate-500">{{ $booking->trip->departure_time ? $booking->trip->departure_time->format('M d, Y h:i A') : 'TBA' }}</p>
                        </div>
                        <div class="flex-shrink-0 text-slate-300 dark:text-slate-600 text-2xl">
                            <i class="fa-solid fa-arrow-right-long"></i>
                        </div>
                        <div class="flex-1 text-right">
                            <p class="text-xs text-slate-500 dark:text-slate-400 uppercase tracking-wide font-semibold mb-1">Destination</p>
                            <p class="text-lg font-bold text-slate-800 dark:text-white">{{ $booking->trip->route->destinationCity->name ?? 'Unknown' }}</p>
                            <p class="text-sm text-slate-500">{{ $booking->trip->arrival_time ? $booking->trip->arrival_time->format('M d, Y h:i A') : 'TBA' }}</p>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 p-4 bg-slate-50 dark:bg-slate-700/50 rounded-xl">
                        <div>
                            <p class="text-xs text-slate-500 dark:text-slate-400 font-semibold mb-1">Bus Class</p>
                            <p class="text-sm font-medium text-slate-800 dark:text-slate-200">{{ $booking->trip->bus->class ?? 'Economy' }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-slate-500 dark:text-slate-400 font-semibold mb-1">Bus Name/No.</p>
                            <p class="text-sm font-medium text-slate-800 dark:text-slate-200">{{ $booking->trip->bus->name ?? 'Not Assigned' }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-slate-500 dark:text-slate-400 font-semibold mb-1">Seats</p>
                            <p class="text-sm font-medium text-slate-800 dark:text-slate-200">{{ $booking->seat_list }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-slate-500 dark:text-slate-400 font-semibold mb-1">Total Passengers</p>
                            <p class="text-sm font-medium text-slate-800 dark:text-slate-200">{{ $booking->seat_count }}</p>
                        </div>
                    </div>
                @else
                    <div class="p-4 bg-amber-50 text-amber-700 rounded-xl border border-amber-200">
                        This trip or route is no longer available in the system.
                    </div>
                @endif
            </div>
        </div>

        <!-- Passengers list (bookingSeats) -->
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 overflow-hidden">
            <div class="p-5 border-b border-slate-100 dark:border-slate-700">
                <h2 class="text-lg font-bold text-slate-800 dark:text-white flex items-center gap-2">
                    <i class="fa-solid fa-users text-primary-500"></i> Passengers & Seats
                </h2>
            </div>
            <div class="p-0">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 dark:bg-slate-700/50 border-b border-slate-100 dark:border-slate-700 text-xs uppercase tracking-wider text-slate-500 dark:text-slate-400 font-semibold">
                            <th class="p-4">Seat No.</th>
                            <th class="p-4">Passenger Name</th>
                            <th class="p-4">Type</th>
                            <th class="p-4 text-right">Fare</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                        @if($booking->bookingSeats->count() > 0)
                            @foreach($booking->bookingSeats as $bSeat)
                                <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50">
                                    <td class="p-4 font-bold text-slate-800 dark:text-slate-200">{{ $bSeat->seat_number }}</td>
                                    <td class="p-4 font-medium text-slate-800 dark:text-slate-200">{{ $bSeat->passenger_name ?? 'Not Specified' }}</td>
                                    <td class="p-4 text-sm text-slate-500 capitalize">{{ $bSeat->passenger_type ?? 'Regular' }}</td>
                                    <td class="p-4 text-sm font-semibold text-slate-800 dark:text-slate-200 text-right">₱{{ number_format($bSeat->final_fare, 2) }}</td>
                                </tr>
                            @endforeach
                        @else
                            <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50">
                                <td class="p-4 font-bold text-slate-800 dark:text-slate-200">{{ $booking->seat_number ?? '—' }}</td>
                                <td class="p-4 font-medium text-slate-800 dark:text-slate-200">{{ $booking->user->name ?? 'Guest' }}</td>
                                <td class="p-4 text-sm text-slate-500">Regular</td>
                                <td class="p-4 text-sm font-semibold text-slate-800 dark:text-slate-200 text-right">₱{{ number_format($booking->amount_paid, 2) }}</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Right Column: Customer & Summary -->
    <div class="space-y-6">
        
        <!-- Customer details -->
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 overflow-hidden">
            <div class="p-5 border-b border-slate-100 dark:border-slate-700">
                <h2 class="text-lg font-bold text-slate-800 dark:text-white flex items-center gap-2">
                    <i class="fa-solid fa-user text-primary-500"></i> Customer
                </h2>
            </div>
            <div class="p-5">
                <div class="flex items-center gap-4 mb-4">
                    <div class="w-12 h-12 bg-primary-100 dark:bg-slate-700 text-primary-700 dark:text-primary-400 rounded-full flex items-center justify-center font-bold text-lg">
                        {{ strtoupper(substr($booking->user->name ?? 'G', 0, 1)) }}
                    </div>
                    <div>
                        <p class="font-bold text-slate-800 dark:text-white">{{ $booking->user->name ?? 'Guest' }}</p>
                        <p class="text-sm text-slate-500">{{ $booking->user->email ?? 'No email provided' }}</p>
                    </div>
                </div>
                <div class="space-y-3 pt-4 border-t border-slate-100 dark:border-slate-700">
                    <div>
                        <p class="text-xs text-slate-500 font-semibold mb-1">Phone Number</p>
                        <p class="text-sm font-medium text-slate-800 dark:text-slate-200">{{ $booking->user->phone ?? '—' }}</p>
                    </div>
                    <div>
                        <p class="text-xs text-slate-500 font-semibold mb-1">Joined Date</p>
                        <p class="text-sm font-medium text-slate-800 dark:text-slate-200">{{ $booking->user ? $booking->user->created_at->format('M d, Y') : '—' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Payment Summary -->
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 overflow-hidden">
            <div class="p-5 border-b border-slate-100 dark:border-slate-700 flex justify-between items-center">
                <h2 class="text-lg font-bold text-slate-800 dark:text-white flex items-center gap-2">
                    <i class="fa-solid fa-receipt text-primary-500"></i> Payment
                </h2>
                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold {{ $booking->payment_status === 'paid' ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400' : 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400' }}">
                    {{ strtoupper($booking->payment_status) }}
                </span>
            </div>
            <div class="p-5 space-y-3">
                <div class="flex justify-between text-sm">
                    <span class="text-slate-500">Base Fare</span>
                    <span class="font-medium text-slate-800 dark:text-slate-200">₱{{ number_format($booking->base_fare, 2) }}</span>
                </div>
                @if($booking->discount_amount > 0)
                <div class="flex justify-between text-sm">
                    <span class="text-slate-500">Discounts</span>
                    <span class="font-medium text-red-500">-₱{{ number_format($booking->discount_amount, 2) }}</span>
                </div>
                @endif
                <div class="pt-3 border-t border-slate-100 dark:border-slate-700 flex justify-between">
                    <span class="font-bold text-slate-800 dark:text-white">Total Amount</span>
                    <span class="font-bold text-primary-600 dark:text-primary-400 text-lg">₱{{ number_format($booking->amount_paid, 2) }}</span>
                </div>

                @if($booking->payment)
                    <div class="mt-4 pt-4 border-t border-slate-100 dark:border-slate-700 space-y-2">
                        <p class="text-xs text-slate-500 font-semibold">Payment Method</p>
                        <p class="text-sm font-medium text-slate-800 dark:text-slate-200 capitalize">{{ $booking->payment->payment_method ?? '—' }}</p>
                        
                        <p class="text-xs text-slate-500 font-semibold mt-2">Transaction Ref</p>
                        <p class="text-sm font-mono text-slate-600 dark:text-slate-400">{{ $booking->payment->transaction_reference ?? '—' }}</p>
                    </div>
                @endif
            </div>
        </div>

    </div>
</div>
@endsection
