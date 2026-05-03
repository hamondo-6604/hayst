@extends('layouts.admin')

@section('title', 'Manage Trips & Schedules')

@section('content')
<div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
    <div>
        <h1 class="text-2xl font-bold text-slate-800 dark:text-white">Trips & Schedules</h1>
        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Manage scheduled bus trips and assignments.</p>
    </div>
    <a href="{{ route('admin.trips.create') }}" class="px-5 py-2.5 bg-primary-600 hover:bg-primary-700 text-white text-sm font-semibold rounded-xl transition-colors inline-flex items-center justify-center gap-2 shadow-sm">
        <i class="fa-solid fa-plus"></i> Add Trip
    </a>
</div>

<!-- Filters -->
<div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 p-4 mb-6">
    <form method="GET" action="{{ route('admin.trips.index') }}" class="flex flex-col sm:flex-row gap-4 items-end">
        <div class="flex-1">
            <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 mb-1">Search</label>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Trip Code, Origin, or Destination..." 
                   class="w-full px-4 py-2 rounded-xl border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-white text-sm focus:ring-primary-500 focus:border-primary-500">
        </div>
        <div class="w-full sm:w-48">
            <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 mb-1">Status</label>
            <select name="status" class="w-full px-4 py-2 rounded-xl border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-white text-sm focus:ring-primary-500 focus:border-primary-500">
                <option value="">All Statuses</option>
                <option value="scheduled" {{ request('status') === 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
            </select>
        </div>
        <div>
            <button type="submit" class="px-5 py-2.5 bg-slate-800 hover:bg-slate-900 dark:bg-slate-700 dark:hover:bg-slate-600 text-white text-sm font-semibold rounded-xl transition-colors">
                Filter
            </button>
            @if(request()->hasAny(['search', 'status']))
                <a href="{{ route('admin.trips.index') }}" class="ml-2 text-sm text-primary-600 hover:text-primary-700 dark:text-primary-400">Clear</a>
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
                    <th class="p-4">Trip Info</th>
                    <th class="p-4">Route</th>
                    <th class="p-4">Schedule</th>
                    <th class="p-4">Assignments</th>
                    <th class="p-4">Seats</th>
                    <th class="p-4">Status</th>
                    <th class="p-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                @forelse($trips as $trip)
                <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors">
                    <td class="p-4">
                        <span class="font-semibold text-slate-800 dark:text-slate-200">{{ $trip->trip_code }}</span>
                        @if(!$trip->is_active)
                            <span class="ml-2 inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-bold bg-slate-100 text-slate-600 dark:bg-slate-700 dark:text-slate-400">INACTIVE</span>
                        @endif
                    </td>
                    <td class="p-4">
                        @if($trip->route)
                            <div class="font-medium text-slate-800 dark:text-slate-200">{{ $trip->route->originCity->name ?? 'Unknown' }}</div>
                            <div class="text-xs text-slate-500">&rarr; {{ $trip->route->destinationCity->name ?? 'Unknown' }}</div>
                        @else
                            <span class="text-slate-400 italic">Route removed</span>
                        @endif
                    </td>
                    <td class="p-4">
                        <div class="font-medium text-slate-800 dark:text-slate-200">{{ $trip->trip_date ? $trip->trip_date->format('M d, Y') : '—' }}</div>
                        <div class="text-xs text-slate-500">{{ $trip->departure_time ? $trip->departure_time->format('h:i A') : '—' }}</div>
                    </td>
                    <td class="p-4">
                        <div class="text-sm text-slate-800 dark:text-slate-200"><i class="fa-solid fa-bus text-slate-400 w-4"></i> {{ $trip->bus->name ?? 'Unassigned' }}</div>
                        <div class="text-xs text-slate-500 mt-0.5"><i class="fa-solid fa-user-tie text-slate-400 w-4"></i> {{ $trip->driver->user->name ?? 'Unassigned' }}</div>
                    </td>
                    <td class="p-4">
                        @php
                            $totalSeats = $trip->bus ? $trip->bus->total_seats : 0;
                            $bookedSeats = $totalSeats - $trip->available_seats;
                            $percent = $totalSeats > 0 ? round(($bookedSeats / $totalSeats) * 100) : 0;
                        @endphp
                        <div class="font-medium text-slate-800 dark:text-slate-200">{{ $trip->available_seats }} <span class="text-xs font-normal text-slate-500">avail</span></div>
                        <div class="w-full bg-slate-100 dark:bg-slate-700 h-1.5 rounded-full mt-1.5 overflow-hidden">
                            <div class="bg-primary-500 h-full rounded-full" style="width: {{ $percent }}%"></div>
                        </div>
                    </td>
                    <td class="p-4">
                        @php
                            $badgeColors = [
                                'scheduled' => 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400',
                                'in_progress' => 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400',
                                'completed' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400',
                                'cancelled' => 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400',
                            ];
                            $colorClass = $badgeColors[$trip->status] ?? 'bg-slate-100 text-slate-700';
                        @endphp
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $colorClass }}">
                            {{ ucfirst(str_replace('_', ' ', $trip->status)) }}
                        </span>
                    </td>
                    <td class="p-4 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('admin.trips.show', $trip) }}" class="w-8 h-8 rounded-lg bg-slate-100 hover:bg-slate-200 dark:bg-slate-700 dark:hover:bg-slate-600 text-slate-600 dark:text-slate-300 flex items-center justify-center transition-colors" title="View Manifest">
                                <i class="fa-solid fa-list-check text-sm"></i>
                            </a>
                            <a href="{{ route('admin.trips.edit', $trip) }}" class="w-8 h-8 rounded-lg bg-slate-100 hover:bg-slate-200 dark:bg-slate-700 dark:hover:bg-slate-600 text-slate-600 dark:text-slate-300 flex items-center justify-center transition-colors">
                                <i class="fa-solid fa-pen-to-square text-sm"></i>
                            </a>
                            <form action="{{ route('admin.trips.destroy', $trip) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this trip?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-8 h-8 rounded-lg bg-red-50 hover:bg-red-100 dark:bg-red-900/20 dark:hover:bg-red-900/40 text-red-600 dark:text-red-400 flex items-center justify-center transition-colors">
                                    <i class="fa-solid fa-trash-can text-sm"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="p-8 text-center text-slate-500 dark:text-slate-400">
                        <div class="text-4xl mb-2"><i class="fa-solid fa-location-dot text-slate-300 dark:text-slate-600"></i></div>
                        <p>No trips found.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($trips->hasPages())
    <div class="p-4 border-t border-slate-100 dark:border-slate-700">
        {{ $trips->links() }}
    </div>
    @endif
</div>
@endsection
