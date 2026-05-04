@extends('layouts.admin')

@section('title', 'Manage Drivers')

@section('content')
<div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
    <div>
        <h1 class="text-2xl font-bold text-slate-800 dark:text-white">Drivers</h1>
        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Manage driver assignments, licenses, and availability.</p>
    </div>
</div>

<!-- Filters -->
<div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 p-4 mb-6">
    <form method="GET" action="{{ route('admin.drivers.index') }}" class="flex flex-col sm:flex-row gap-4 items-end">
        <div class="flex-1">
            <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 mb-1">Search Drivers</label>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name, email, or license..." 
                   class="w-full px-4 py-2 rounded-xl border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-white text-sm focus:ring-primary-500 focus:border-primary-500">
        </div>
        <div class="w-full sm:w-48 relative" data-custom-select>
            <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 mb-1">Status</label>
            <input type="hidden" name="status" value="{{ request('status') }}" class="custom-select-input">
            
            <button type="button" onclick="this.nextElementSibling.classList.toggle('hidden')" class="w-full px-4 py-2 flex items-center justify-between rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-white text-sm focus:ring-primary-500 focus:border-primary-500 transition-colors cursor-pointer">
                <span class="custom-select-text">
                    @if(request('status') === 'available') Available
                    @elseif(request('status') === 'on_trip') On Trip
                    @elseif(request('status') === 'off_duty') Off Duty
                    @elseif(request('status') === 'suspended') Suspended
                    @else All Statuses
                    @endif
                </span>
                <i class="fa-solid fa-chevron-down text-[10px] text-slate-400"></i>
            </button>
            
            <div class="custom-select-menu hidden absolute left-0 right-0 top-full mt-2 bg-white dark:bg-slate-800 rounded-xl shadow-xl border border-slate-100 dark:border-slate-700 py-1.5 z-50 overflow-hidden">
                <div class="px-4 py-2.5 text-sm font-medium text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-700 cursor-pointer transition-colors" data-value="" onclick="selectCustomOption(this)">All Statuses</div>
                <div class="px-4 py-2.5 text-sm font-medium text-emerald-700 bg-emerald-50 dark:bg-emerald-900/20 dark:text-emerald-400 hover:bg-emerald-100 dark:hover:bg-emerald-900/40 cursor-pointer transition-colors" data-value="available" onclick="selectCustomOption(this)">Available</div>
                <div class="px-4 py-2.5 text-sm font-medium text-blue-700 bg-blue-50 dark:bg-blue-900/20 dark:text-blue-400 hover:bg-blue-100 dark:hover:bg-blue-900/40 cursor-pointer transition-colors" data-value="on_trip" onclick="selectCustomOption(this)">On Trip</div>
                <div class="px-4 py-2.5 text-sm font-medium text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-700 cursor-pointer transition-colors" data-value="off_duty" onclick="selectCustomOption(this)">Off Duty</div>
                <div class="px-4 py-2.5 text-sm font-medium text-red-700 bg-red-50 dark:bg-red-900/20 dark:text-red-400 hover:bg-red-100 dark:hover:bg-red-900/40 cursor-pointer transition-colors" data-value="suspended" onclick="selectCustomOption(this)">Suspended</div>
            </div>
        </div>
        <div>
            <button type="submit" class="px-5 py-2.5 bg-slate-800 hover:bg-slate-900 dark:bg-slate-700 dark:hover:bg-slate-600 text-white text-sm font-semibold rounded-xl transition-colors">
                Filter
            </button>
            @if(request()->hasAny(['search', 'status']))
                <a href="{{ route('admin.drivers.index') }}" class="ml-2 text-sm text-primary-600 hover:text-primary-700 dark:text-primary-400">Clear</a>
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
                    <th class="p-4">Driver Profile</th>
                    <th class="p-4">License Details</th>
                    <th class="p-4">Experience</th>
                    <th class="p-4">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                @forelse($drivers as $driver)
                <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors">
                    <td class="p-4 flex items-center gap-3">
                        @if($driver->user && $driver->user->profile_photo)
                            <img src="{{ asset('storage/' . $driver->user->profile_photo) }}" alt="{{ $driver->name }}" class="w-10 h-10 rounded-full object-cover">
                        @else
                            <div class="w-10 h-10 rounded-full bg-primary-100 dark:bg-slate-700 text-primary-700 dark:text-primary-400 flex items-center justify-center font-bold text-sm">
                                {{ strtoupper(substr($driver->name, 0, 1)) }}
                            </div>
                        @endif
                        <div>
                            <div class="font-bold text-slate-800 dark:text-slate-200">{{ $driver->name }}</div>
                            <div class="text-xs text-slate-500 mt-0.5">{{ $driver->user->email ?? '—' }} | {{ $driver->contact_number ?? $driver->user->phone ?? '—' }}</div>
                        </div>
                    </td>
                    <td class="p-4">
                        <div class="text-sm font-medium text-slate-800 dark:text-slate-200 font-mono">{{ $driver->license_number ?? 'Not Set' }}</div>
                        @if($driver->license_expiry)
                            @if($driver->isLicenseExpired())
                                <div class="text-xs text-red-500 font-bold mt-0.5"><i class="fa-solid fa-triangle-exclamation"></i> Expired on {{ $driver->license_expiry->format('M d, Y') }}</div>
                            @else
                                <div class="text-xs text-slate-500 mt-0.5">Expires: {{ $driver->license_expiry->format('M d, Y') }}</div>
                            @endif
                        @else
                            <div class="text-xs text-slate-400 mt-0.5">No Expiry Data</div>
                        @endif
                    </td>
                    <td class="p-4">
                        <div class="text-sm font-medium text-slate-800 dark:text-slate-200">{{ $driver->experience_years ?? 0 }} Years</div>
                    </td>
                    <td class="p-4">
                        @php
                            $badgeColors = [
                                'available' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400',
                                'on_trip' => 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400',
                                'off_duty' => 'bg-slate-100 text-slate-700 dark:bg-slate-700 dark:text-slate-300',
                                'suspended' => 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400',
                            ];
                            $colorClass = $badgeColors[$driver->status] ?? 'bg-slate-100 text-slate-700';
                        @endphp
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $colorClass }}">
                            {{ ucfirst(str_replace('_', ' ', $driver->status)) }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="p-8 text-center text-slate-500 dark:text-slate-400">
                        <div class="text-4xl mb-2"><i class="fa-solid fa-id-badge text-slate-300 dark:text-slate-600"></i></div>
                        <p>No drivers found matching your criteria.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($drivers->hasPages())
    <div class="p-4 border-t border-slate-100 dark:border-slate-700">
        {{ $drivers->links() }}
    </div>
    @endif
</div>
@endsection
