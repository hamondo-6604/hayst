@extends('layouts.admin')

@section('title', 'Manage Trips & Schedules')

@section('content')
<div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
    <div>
        <h1 class="text-2xl font-bold text-slate-800 dark:text-white">Trips & Schedules</h1>
        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Manage scheduled bus trips and assignments.</p>
    </div>
    <button onclick="openAdminModal('create-trip-modal')" class="px-5 py-2.5 bg-primary-600 hover:bg-primary-700 text-white text-sm font-semibold rounded-xl transition-colors inline-flex items-center justify-center gap-2 shadow-sm">
        <i class="fa-solid fa-plus"></i> Add Trip
    </button>
</div>

<!-- Filters -->
<div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 p-4 mb-6">
    <form method="GET" action="{{ route('admin.trips.index') }}" class="flex flex-col sm:flex-row gap-4 items-end">
        <div class="flex-1">
            <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 mb-1">Search</label>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Trip Code, Origin, or Destination..." 
                   class="w-full px-4 py-2 rounded-xl border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-white text-sm focus:ring-primary-500 focus:border-primary-500">
        </div>
        <div class="w-full sm:w-48 relative" data-custom-select>
            <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 mb-1">Status</label>
            <input type="hidden" name="status" value="{{ request('status') }}" class="custom-select-input">
            
            <button type="button" onclick="this.nextElementSibling.classList.toggle('hidden')" class="w-full px-4 py-2 flex items-center justify-between rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-white text-sm focus:ring-primary-500 focus:border-primary-500 transition-colors cursor-pointer">
                <span class="custom-select-text">
                    @if(request('status') === 'scheduled') Scheduled
                    @elseif(request('status') === 'boarding') Boarding
                    @elseif(request('status') === 'in_transit') In Transit
                    @elseif(request('status') === 'completed') Completed
                    @elseif(request('status') === 'delayed') Delayed
                    @elseif(request('status') === 'cancelled') Cancelled
                    @else All Statuses
                    @endif
                </span>
                <i class="fa-solid fa-chevron-down text-[10px] text-slate-400"></i>
            </button>
            
            <div class="custom-select-menu hidden absolute left-0 right-0 top-full mt-2 bg-white dark:bg-slate-800 rounded-xl shadow-xl border border-slate-100 dark:border-slate-700 py-1.5 z-50 overflow-hidden">
                <div class="px-4 py-2.5 text-sm font-medium text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-700 cursor-pointer transition-colors" data-value="" onclick="selectCustomOption(this)">All Statuses</div>
                <div class="px-4 py-2.5 text-sm font-medium text-amber-700 bg-amber-50 dark:bg-amber-900/20 dark:text-amber-400 hover:bg-amber-100 dark:hover:bg-amber-900/40 cursor-pointer transition-colors" data-value="scheduled" onclick="selectCustomOption(this)">Scheduled</div>
                <div class="px-4 py-2.5 text-sm font-medium text-blue-700 bg-blue-50 dark:bg-blue-900/20 dark:text-blue-400 hover:bg-blue-100 dark:hover:bg-blue-900/40 cursor-pointer transition-colors" data-value="boarding" onclick="selectCustomOption(this)">Boarding</div>
                <div class="px-4 py-2.5 text-sm font-medium text-purple-700 bg-purple-50 dark:bg-purple-900/20 dark:text-purple-400 hover:bg-purple-100 dark:hover:bg-purple-900/40 cursor-pointer transition-colors" data-value="in_transit" onclick="selectCustomOption(this)">In Transit</div>
                <div class="px-4 py-2.5 text-sm font-medium text-emerald-700 bg-emerald-50 dark:bg-emerald-900/20 dark:text-emerald-400 hover:bg-emerald-100 dark:hover:bg-emerald-900/40 cursor-pointer transition-colors" data-value="completed" onclick="selectCustomOption(this)">Completed</div>
                <div class="px-4 py-2.5 text-sm font-medium text-orange-700 bg-orange-50 dark:bg-orange-900/20 dark:text-orange-400 hover:bg-orange-100 dark:hover:bg-orange-900/40 cursor-pointer transition-colors" data-value="delayed" onclick="selectCustomOption(this)">Delayed</div>
                <div class="px-4 py-2.5 text-sm font-medium text-red-700 bg-red-50 dark:bg-red-900/20 dark:text-red-400 hover:bg-red-100 dark:hover:bg-red-900/40 cursor-pointer transition-colors" data-value="cancelled" onclick="selectCustomOption(this)">Cancelled</div>
            </div>
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
                        <div class="text-sm text-slate-800 dark:text-slate-200"><i class="fa-solid fa-bus text-slate-400 w-4"></i> {{ $trip->bus->bus_number ?? 'Unassigned' }}</div>
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
                                'boarding' => 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400',
                                'in_transit' => 'bg-purple-100 text-purple-700 dark:bg-purple-900/30 dark:text-purple-400',
                                'completed' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400',
                                'delayed' => 'bg-orange-100 text-orange-700 dark:bg-orange-900/30 dark:text-orange-400',
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
                            <button onclick="openAdminModal('edit-trip-modal-{{ $trip->id }}')" class="w-8 h-8 rounded-lg bg-slate-100 hover:bg-slate-200 dark:bg-slate-700 dark:hover:bg-slate-600 text-slate-600 dark:text-slate-300 flex items-center justify-center transition-colors">
                                <i class="fa-solid fa-pen-to-square text-sm"></i>
                            </button>
                            <button onclick="openAdminModal('delete-trip-modal-{{ $trip->id }}')" class="w-8 h-8 rounded-lg bg-red-50 hover:bg-red-100 dark:bg-red-900/20 dark:hover:bg-red-900/40 text-red-600 dark:text-red-400 flex items-center justify-center transition-colors">
                                <i class="fa-solid fa-trash-can text-sm"></i>
                            </button>
                        </div>
                    </td>
                </tr>

                <!-- Edit Modal -->
                <x-modal id="edit-trip-modal-{{ $trip->id }}" title="Edit Trip" size="xl">
                    <form action="{{ route('admin.trips.update', $trip) }}" method="POST" onsubmit="handleAjaxForm(this, 'edit-trip-modal-{{ $trip->id }}', () => window.location.reload())">
                        @csrf
                        @method('PUT')
                        <div class="space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1">Trip Code <span class="text-red-500">*</span></label>
                                    <input type="text" name="trip_code" value="{{ $trip->trip_code }}" required class="w-full p-0 pl-3 pb-2 text-base bg-transparent border-0 border-b-2 border-slate-300 hover:border-slate-300 dark:border-slate-600 dark:hover:border-slate-600 text-slate-800 dark:text-white outline-none focus:outline-none focus:ring-0 focus:border-primary-500 font-mono uppercase transition-colors">
                                </div>
                                <div class="flex items-center pt-5">
                                    <label class="flex items-center gap-3 cursor-pointer">
                                        <input type="checkbox" name="is_active" value="1" {{ $trip->is_active ? 'checked' : '' }} class="w-5 h-5 rounded border-slate-300 text-primary-600 focus:ring-primary-500">
                                        <span class="text-sm font-semibold text-slate-700 dark:text-slate-300">Is Active (Visible to Customers)</span>
                                    </label>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1">Route <span class="text-red-500">*</span></label>
                                    <select name="route_id" required class="w-full p-0 pl-3 pr-8 pb-2 text-base bg-transparent border-0 border-b-2 border-slate-300 hover:border-slate-300 dark:border-slate-600 dark:hover:border-slate-600 text-slate-800 dark:text-white outline-none focus:outline-none focus:ring-0 focus:border-primary-500 transition-colors cursor-pointer">
                                        <option value="">-- Select Route --</option>
                                        @foreach($routes as $route)
                                            <option value="{{ $route->id }}" {{ $trip->route_id == $route->id ? 'selected' : '' }}>{{ $route->route_name }} ({{ $route->originCity->name ?? '?' }} → {{ $route->destinationCity->name ?? '?' }})</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1">Dep. Terminal</label>
                                        <select name="departure_terminal_id" class="w-full p-0 pl-3 pr-8 pb-2 text-base bg-transparent border-0 border-b-2 border-slate-300 hover:border-slate-300 dark:border-slate-600 dark:hover:border-slate-600 text-slate-800 dark:text-white outline-none focus:outline-none focus:ring-0 focus:border-primary-500 transition-colors cursor-pointer">
                                            <option value="">-- Auto --</option>
                                            @foreach($terminals as $terminal)
                                                <option value="{{ $terminal->id }}" {{ $trip->departure_terminal_id == $terminal->id ? 'selected' : '' }}>{{ $terminal->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1">Arr. Terminal</label>
                                        <select name="arrival_terminal_id" class="w-full p-0 pl-3 pr-8 pb-2 text-base bg-transparent border-0 border-b-2 border-slate-300 hover:border-slate-300 dark:border-slate-600 dark:hover:border-slate-600 text-slate-800 dark:text-white outline-none focus:outline-none focus:ring-0 focus:border-primary-500 transition-colors cursor-pointer">
                                            <option value="">-- Auto --</option>
                                            @foreach($terminals as $terminal)
                                                <option value="{{ $terminal->id }}" {{ $trip->arrival_terminal_id == $terminal->id ? 'selected' : '' }}>{{ $terminal->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1">Bus Assignment <span class="text-red-500">*</span></label>
                                    <select name="bus_id" required onchange="const s = this.options[this.selectedIndex].getAttribute('data-seats'); if(s) this.form.querySelector('[name=available_seats]').value = s;" class="w-full p-0 pl-3 pr-8 pb-2 text-base bg-transparent border-0 border-b-2 border-slate-300 hover:border-slate-300 dark:border-slate-600 dark:hover:border-slate-600 text-slate-800 dark:text-white outline-none focus:outline-none focus:ring-0 focus:border-primary-500 transition-colors cursor-pointer">
                                        <option value="">-- Select Bus --</option>
                                        @foreach($buses as $bus)
                                            <option value="{{ $bus->id }}" data-seats="{{ $bus->total_seats }}" {{ $trip->bus_id == $bus->id ? 'selected' : '' }}>#{{ $bus->bus_number }} - {{ $bus->type->type_name ?? 'Standard' }} ({{ $bus->total_seats }} Seats)</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1">Driver Assignment <span class="text-red-500">*</span></label>
                                    <select name="driver_id" required class="w-full p-0 pl-3 pr-8 pb-2 text-base bg-transparent border-0 border-b-2 border-slate-300 hover:border-slate-300 dark:border-slate-600 dark:hover:border-slate-600 text-slate-800 dark:text-white outline-none focus:outline-none focus:ring-0 focus:border-primary-500 transition-colors cursor-pointer">
                                        <option value="">-- Select Driver --</option>
                                        @foreach($drivers as $driver)
                                            <option value="{{ $driver->id }}" {{ $trip->driver_id == $driver->id ? 'selected' : '' }}>{{ $driver->user->name ?? 'Unknown' }} (Lic: {{ $driver->license_number }})</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1">Trip Date <span class="text-red-500">*</span></label>
                                    <input type="date" name="trip_date" value="{{ $trip->trip_date ? $trip->trip_date->format('Y-m-d') : '' }}" required class="w-full p-0 pl-3 pb-2 text-base bg-transparent border-0 border-b-2 border-slate-300 hover:border-slate-300 dark:border-slate-600 dark:hover:border-slate-600 text-slate-800 dark:text-white outline-none focus:outline-none focus:ring-0 focus:border-primary-500 transition-colors cursor-pointer">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1">Departure Time <span class="text-red-500">*</span></label>
                                    <input type="datetime-local" name="departure_time" value="{{ $trip->departure_time ? $trip->departure_time->format('Y-m-d\TH:i') : '' }}" required class="w-full p-0 pl-3 pb-2 text-base bg-transparent border-0 border-b-2 border-slate-300 hover:border-slate-300 dark:border-slate-600 dark:hover:border-slate-600 text-slate-800 dark:text-white outline-none focus:outline-none focus:ring-0 focus:border-primary-500 transition-colors cursor-pointer">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1">Arrival Time <span class="text-red-500">*</span></label>
                                    <input type="datetime-local" name="arrival_time" value="{{ $trip->arrival_time ? $trip->arrival_time->format('Y-m-d\TH:i') : '' }}" required class="w-full p-0 pl-3 pb-2 text-base bg-transparent border-0 border-b-2 border-slate-300 hover:border-slate-300 dark:border-slate-600 dark:hover:border-slate-600 text-slate-800 dark:text-white outline-none focus:outline-none focus:ring-0 focus:border-primary-500 transition-colors cursor-pointer">
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1">Available Seats <span class="text-red-500">*</span></label>
                                    <input type="number" name="available_seats" value="{{ $trip->available_seats }}" required min="0" class="w-full p-0 pl-3 pb-2 text-base bg-transparent border-0 border-b-2 border-slate-300 hover:border-slate-300 dark:border-slate-600 dark:hover:border-slate-600 text-slate-800 dark:text-white outline-none focus:outline-none focus:ring-0 focus:border-primary-500 transition-colors">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1">Fare (₱) <span class="text-red-500">*</span></label>
                                    <input type="number" step="0.01" name="fare" value="{{ $trip->fare }}" required min="0" class="w-full p-0 pl-3 pb-2 text-base bg-transparent border-0 border-b-2 border-slate-300 hover:border-slate-300 dark:border-slate-600 dark:hover:border-slate-600 text-slate-800 dark:text-white outline-none focus:outline-none focus:ring-0 focus:border-primary-500 transition-colors">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1">Status <span class="text-red-500">*</span></label>
                                    <div class="relative" data-custom-select>
                                        <input type="hidden" name="status" value="{{ $trip->status }}" class="custom-select-input" required>
                                        <button type="button" onclick="this.nextElementSibling.classList.toggle('hidden')" class="w-full flex items-center justify-between p-0 pl-3 pb-2 text-base bg-transparent border-0 border-b-2 border-slate-300 hover:border-slate-300 dark:border-slate-600 dark:hover:border-slate-600 text-slate-800 dark:text-white outline-none focus:outline-none transition-colors cursor-pointer">
                                            <span class="custom-select-text">{{ ucfirst(str_replace('_', ' ', $trip->status)) }}</span>
                                            <i class="fa-solid fa-chevron-down text-[10px] text-slate-400"></i>
                                        </button>
                                        <div class="custom-select-menu hidden mt-2 bg-slate-100 dark:bg-slate-700/50 rounded-xl p-1 flex flex-col gap-1 overflow-hidden z-50 absolute left-0 right-0">
                                            <div class="w-full text-left px-4 py-2 text-sm font-semibold text-amber-700 hover:bg-amber-100 dark:hover:bg-amber-900/40 dark:text-amber-400 rounded-lg cursor-pointer transition-colors shadow-sm" data-value="scheduled" onclick="selectCustomOption(this)">Scheduled</div>
                                            <div class="w-full text-left px-4 py-2 text-sm font-semibold text-blue-700 hover:bg-blue-100 dark:hover:bg-blue-900/40 dark:text-blue-400 rounded-lg cursor-pointer transition-colors shadow-sm" data-value="boarding" onclick="selectCustomOption(this)">Boarding</div>
                                            <div class="w-full text-left px-4 py-2 text-sm font-semibold text-purple-700 hover:bg-purple-100 dark:hover:bg-purple-900/40 dark:text-purple-400 rounded-lg cursor-pointer transition-colors shadow-sm" data-value="in_transit" onclick="selectCustomOption(this)">In Transit</div>
                                            <div class="w-full text-left px-4 py-2 text-sm font-semibold text-emerald-700 hover:bg-emerald-100 dark:hover:bg-emerald-900/40 dark:text-emerald-400 rounded-lg cursor-pointer transition-colors shadow-sm" data-value="completed" onclick="selectCustomOption(this)">Completed</div>
                                            <div class="w-full text-left px-4 py-2 text-sm font-semibold text-orange-700 hover:bg-orange-100 dark:hover:bg-orange-900/40 dark:text-orange-400 rounded-lg cursor-pointer transition-colors shadow-sm" data-value="delayed" onclick="selectCustomOption(this)">Delayed</div>
                                            <div class="w-full text-left px-4 py-2 text-sm font-semibold text-red-700 hover:bg-red-100 dark:hover:bg-red-900/40 dark:text-red-400 rounded-lg cursor-pointer transition-colors shadow-sm" data-value="cancelled" onclick="selectCustomOption(this)">Cancelled</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div>
                                <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1">Notes</label>
                                <textarea name="notes" rows="2" class="w-full p-0 pl-3 pb-2 text-base bg-transparent border-0 border-b-2 border-slate-300 hover:border-slate-300 dark:border-slate-600 dark:hover:border-slate-600 text-slate-800 dark:text-white outline-none focus:outline-none focus:ring-0 focus:border-primary-500 transition-colors">{{ $trip->notes }}</textarea>
                            </div>
                        </div>
                        <x-slot:footer>
                            <button type="button" onclick="closeAdminModal('edit-trip-modal-{{ $trip->id }}')" class="px-4 py-2 rounded-xl text-slate-600 hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-700 transition-colors font-semibold text-sm">Cancel</button>
                            <button type="submit" class="px-4 py-2 rounded-xl bg-primary-600 text-white font-semibold text-sm hover:bg-primary-700 transition-colors">Save Changes</button>
                        </x-slot:footer>
                    </form>
                </x-modal>

                <!-- Delete Modal -->
                <x-modal id="delete-trip-modal-{{ $trip->id }}" title="Delete Trip" size="sm">
                    <form action="{{ route('admin.trips.destroy', $trip) }}" method="POST" onsubmit="handleAjaxForm(this, 'delete-trip-modal-{{ $trip->id }}', () => window.location.reload())">
                        @csrf
                        @method('DELETE')
                        <div class="text-center py-4">
                            <div class="w-16 h-16 rounded-full bg-red-100 dark:bg-red-900/30 text-red-600 flex items-center justify-center text-3xl mx-auto mb-4">
                                <i class="fa-solid fa-triangle-exclamation"></i>
                            </div>
                            <h4 class="text-lg font-bold text-slate-800 dark:text-white mb-2">Are you sure?</h4>
                            <p class="text-sm text-slate-500 dark:text-slate-400">Do you really want to delete trip <strong>{{ $trip->trip_code }}</strong>? This cannot be undone.</p>
                        </div>
                        <x-slot:footer>
                            <button type="button" onclick="closeAdminModal('delete-trip-modal-{{ $trip->id }}')" class="px-4 py-2 rounded-xl text-slate-600 hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-700 transition-colors font-semibold text-sm">Cancel</button>
                            <button type="submit" class="px-4 py-2 rounded-xl bg-red-600 text-white font-semibold text-sm hover:bg-red-700 transition-colors">Yes, Delete</button>
                        </x-slot:footer>
                    </form>
                </x-modal>
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

<!-- Create Modal -->
<x-modal id="create-trip-modal" title="Add New Trip" size="xl">
    <form action="{{ route('admin.trips.store') }}" method="POST" onsubmit="handleAjaxForm(this, 'create-trip-modal', () => window.location.reload())">
        @csrf
        <div class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1">Trip Code <span class="text-red-500">*</span></label>
                    <input type="text" name="trip_code" value="{{ strtoupper(Str::random(6)) }}" required class="w-full p-0 pl-3 pb-2 text-base bg-transparent border-0 border-b-2 border-slate-300 hover:border-slate-300 dark:border-slate-600 dark:hover:border-slate-600 text-slate-800 dark:text-white outline-none focus:outline-none focus:ring-0 focus:border-primary-500 font-mono uppercase transition-colors">
                </div>
                <div class="flex items-center pt-5">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" checked class="w-5 h-5 rounded border-slate-300 text-primary-600 focus:ring-primary-500">
                        <span class="text-sm font-semibold text-slate-700 dark:text-slate-300">Is Active (Visible to Customers)</span>
                    </label>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1">Route <span class="text-red-500">*</span></label>
                    <select name="route_id" required class="w-full p-0 pl-3 pr-8 pb-2 text-base bg-transparent border-0 border-b-2 border-slate-300 hover:border-slate-300 dark:border-slate-600 dark:hover:border-slate-600 text-slate-800 dark:text-white outline-none focus:outline-none focus:ring-0 focus:border-primary-500 transition-colors cursor-pointer">
                        <option value="">-- Select Route --</option>
                        @foreach($routes as $route)
                            <option value="{{ $route->id }}">{{ $route->route_name }} ({{ $route->originCity->name ?? '?' }} → {{ $route->destinationCity->name ?? '?' }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1">Dep. Terminal</label>
                        <select name="departure_terminal_id" class="w-full p-0 pl-3 pr-8 pb-2 text-base bg-transparent border-0 border-b-2 border-slate-300 hover:border-slate-300 dark:border-slate-600 dark:hover:border-slate-600 text-slate-800 dark:text-white outline-none focus:outline-none focus:ring-0 focus:border-primary-500 transition-colors cursor-pointer">
                            <option value="">-- Auto --</option>
                            @foreach($terminals as $terminal)
                                <option value="{{ $terminal->id }}">{{ $terminal->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1">Arr. Terminal</label>
                        <select name="arrival_terminal_id" class="w-full p-0 pl-3 pr-8 pb-2 text-base bg-transparent border-0 border-b-2 border-slate-300 hover:border-slate-300 dark:border-slate-600 dark:hover:border-slate-600 text-slate-800 dark:text-white outline-none focus:outline-none focus:ring-0 focus:border-primary-500 transition-colors cursor-pointer">
                            <option value="">-- Auto --</option>
                            @foreach($terminals as $terminal)
                                <option value="{{ $terminal->id }}">{{ $terminal->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1">Bus Assignment <span class="text-red-500">*</span></label>
                    <select name="bus_id" required onchange="const s = this.options[this.selectedIndex].getAttribute('data-seats'); if(s) this.form.querySelector('[name=available_seats]').value = s;" class="w-full p-0 pl-3 pr-8 pb-2 text-base bg-transparent border-0 border-b-2 border-slate-300 hover:border-slate-300 dark:border-slate-600 dark:hover:border-slate-600 text-slate-800 dark:text-white outline-none focus:outline-none focus:ring-0 focus:border-primary-500 transition-colors cursor-pointer">
                        <option value="">-- Select Bus --</option>
                        @foreach($buses as $bus)
                            <option value="{{ $bus->id }}" data-seats="{{ $bus->total_seats }}">#{{ $bus->bus_number }} - {{ $bus->type->type_name ?? 'Standard' }} ({{ $bus->total_seats }} Seats)</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1">Driver Assignment <span class="text-red-500">*</span></label>
                    <select name="driver_id" required class="w-full p-0 pl-3 pr-8 pb-2 text-base bg-transparent border-0 border-b-2 border-slate-300 hover:border-slate-300 dark:border-slate-600 dark:hover:border-slate-600 text-slate-800 dark:text-white outline-none focus:outline-none focus:ring-0 focus:border-primary-500 transition-colors cursor-pointer">
                        <option value="">-- Select Driver --</option>
                        @foreach($drivers as $driver)
                            <option value="{{ $driver->id }}">{{ $driver->user->name ?? 'Unknown' }} (Lic: {{ $driver->license_number }})</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1">Trip Date <span class="text-red-500">*</span></label>
                    <input type="date" name="trip_date" required class="w-full p-0 pl-3 pb-2 text-base bg-transparent border-0 border-b-2 border-slate-300 hover:border-slate-300 dark:border-slate-600 dark:hover:border-slate-600 text-slate-800 dark:text-white outline-none focus:outline-none focus:ring-0 focus:border-primary-500 transition-colors cursor-pointer">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1">Departure Time <span class="text-red-500">*</span></label>
                    <input type="datetime-local" name="departure_time" required class="w-full p-0 pl-3 pb-2 text-base bg-transparent border-0 border-b-2 border-slate-300 hover:border-slate-300 dark:border-slate-600 dark:hover:border-slate-600 text-slate-800 dark:text-white outline-none focus:outline-none focus:ring-0 focus:border-primary-500 transition-colors cursor-pointer">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1">Arrival Time <span class="text-red-500">*</span></label>
                    <input type="datetime-local" name="arrival_time" required class="w-full p-0 pl-3 pb-2 text-base bg-transparent border-0 border-b-2 border-slate-300 hover:border-slate-300 dark:border-slate-600 dark:hover:border-slate-600 text-slate-800 dark:text-white outline-none focus:outline-none focus:ring-0 focus:border-primary-500 transition-colors cursor-pointer">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1">Available Seats <span class="text-red-500">*</span></label>
                    <input type="number" name="available_seats" required min="0" class="w-full p-0 pl-3 pb-2 text-base bg-transparent border-0 border-b-2 border-slate-300 hover:border-slate-300 dark:border-slate-600 dark:hover:border-slate-600 text-slate-800 dark:text-white outline-none focus:outline-none focus:ring-0 focus:border-primary-500 transition-colors">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1">Fare (₱) <span class="text-red-500">*</span></label>
                    <input type="number" step="0.01" name="fare" required min="0" class="w-full p-0 pl-3 pb-2 text-base bg-transparent border-0 border-b-2 border-slate-300 hover:border-slate-300 dark:border-slate-600 dark:hover:border-slate-600 text-slate-800 dark:text-white outline-none focus:outline-none focus:ring-0 focus:border-primary-500 transition-colors">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1">Status <span class="text-red-500">*</span></label>
                    <div class="relative" data-custom-select>
                        <input type="hidden" name="status" value="scheduled" class="custom-select-input" required>
                        <button type="button" onclick="this.nextElementSibling.classList.toggle('hidden')" class="w-full flex items-center justify-between p-0 pl-3 pb-2 text-base bg-transparent border-0 border-b-2 border-slate-300 hover:border-slate-300 dark:border-slate-600 dark:hover:border-slate-600 text-slate-800 dark:text-white outline-none focus:outline-none transition-colors cursor-pointer">
                            <span class="custom-select-text">Scheduled</span>
                            <i class="fa-solid fa-chevron-down text-[10px] text-slate-400"></i>
                        </button>
                        <div class="custom-select-menu hidden mt-2 bg-slate-100 dark:bg-slate-700/50 rounded-xl p-1 flex flex-col gap-1 overflow-hidden z-50 absolute left-0 right-0">
                            <div class="w-full text-left px-4 py-2 text-sm font-semibold text-amber-700 hover:bg-amber-100 dark:hover:bg-amber-900/40 dark:text-amber-400 rounded-lg cursor-pointer transition-colors shadow-sm" data-value="scheduled" onclick="selectCustomOption(this)">Scheduled</div>
                            <div class="w-full text-left px-4 py-2 text-sm font-semibold text-blue-700 hover:bg-blue-100 dark:hover:bg-blue-900/40 dark:text-blue-400 rounded-lg cursor-pointer transition-colors shadow-sm" data-value="boarding" onclick="selectCustomOption(this)">Boarding</div>
                            <div class="w-full text-left px-4 py-2 text-sm font-semibold text-purple-700 hover:bg-purple-100 dark:hover:bg-purple-900/40 dark:text-purple-400 rounded-lg cursor-pointer transition-colors shadow-sm" data-value="in_transit" onclick="selectCustomOption(this)">In Transit</div>
                            <div class="w-full text-left px-4 py-2 text-sm font-semibold text-emerald-700 hover:bg-emerald-100 dark:hover:bg-emerald-900/40 dark:text-emerald-400 rounded-lg cursor-pointer transition-colors shadow-sm" data-value="completed" onclick="selectCustomOption(this)">Completed</div>
                            <div class="w-full text-left px-4 py-2 text-sm font-semibold text-orange-700 hover:bg-orange-100 dark:hover:bg-orange-900/40 dark:text-orange-400 rounded-lg cursor-pointer transition-colors shadow-sm" data-value="delayed" onclick="selectCustomOption(this)">Delayed</div>
                            <div class="w-full text-left px-4 py-2 text-sm font-semibold text-red-700 hover:bg-red-100 dark:hover:bg-red-900/40 dark:text-red-400 rounded-lg cursor-pointer transition-colors shadow-sm" data-value="cancelled" onclick="selectCustomOption(this)">Cancelled</div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div>
                <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1">Notes</label>
                <textarea name="notes" rows="2" class="w-full p-0 pl-3 pb-2 text-base bg-transparent border-0 border-b-2 border-slate-300 hover:border-slate-300 dark:border-slate-600 dark:hover:border-slate-600 text-slate-800 dark:text-white outline-none focus:outline-none focus:ring-0 focus:border-primary-500 transition-colors"></textarea>
            </div>
        </div>
        <x-slot:footer>
            <button type="button" onclick="closeAdminModal('create-trip-modal')" class="px-4 py-2 rounded-xl text-slate-600 hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-700 transition-colors font-semibold text-sm">Cancel</button>
            <button type="submit" class="px-4 py-2 rounded-xl bg-primary-600 text-white font-semibold text-sm hover:bg-primary-700 transition-colors">Create Trip</button>
        </x-slot:footer>
    </form>
</x-modal>
@endsection
