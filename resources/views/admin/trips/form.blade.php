@extends('layouts.admin')

@section('title', $trip->exists ? 'Edit Trip' : 'Add Trip')

@section('content')
<div class="mb-6 flex items-center gap-4">
    <a href="{{ route('admin.trips.index') }}" class="w-10 h-10 rounded-xl bg-white dark:bg-slate-800 shadow-sm border border-slate-100 dark:border-slate-700 flex items-center justify-center text-slate-500 hover:text-primary-600 transition-colors">
        <i class="fa-solid fa-arrow-left"></i>
    </a>
    <div>
        <h1 class="text-2xl font-bold text-slate-800 dark:text-white">{{ $trip->exists ? 'Edit Trip' : 'Add New Trip' }}</h1>
    </div>
</div>

<div class="max-w-4xl bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 p-6">
    <form action="{{ $trip->exists ? route('admin.trips.update', $trip) : route('admin.trips.store') }}" method="POST">
        @csrf
        @if($trip->exists)
            @method('PUT')
        @endif

        <div class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Trip Code <span class="text-red-500">*</span></label>
                    <input type="text" name="trip_code" value="{{ old('trip_code', $trip->trip_code ?? strtoupper(Str::random(6))) }}" required
                           class="w-full rounded-xl border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-white focus:ring-primary-500 focus:border-primary-500 font-mono text-sm uppercase">
                    @error('trip_code')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div class="flex items-center pt-8">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $trip->is_active ?? true) ? 'checked' : '' }}
                               class="w-5 h-5 rounded border-slate-300 text-primary-600 focus:ring-primary-500">
                        <span class="text-sm font-semibold text-slate-700 dark:text-slate-300">Is Active (Visible to Customers)</span>
                    </label>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Route <span class="text-red-500">*</span></label>
                    <select name="route_id" required class="w-full rounded-xl border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-white focus:ring-primary-500 focus:border-primary-500">
                        <option value="">-- Select Route --</option>
                        @foreach($routes as $route)
                            <option value="{{ $route->id }}" {{ old('route_id', $trip->route_id) == $route->id ? 'selected' : '' }}>
                                {{ $route->route_name }} ({{ $route->originCity->name ?? '?' }} → {{ $route->destinationCity->name ?? '?' }})
                            </option>
                        @endforeach
                    </select>
                    @error('route_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Dep. Terminal</label>
                        <select name="departure_terminal_id" class="w-full rounded-xl border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-white focus:ring-primary-500 focus:border-primary-500 text-sm">
                            <option value="">-- Auto --</option>
                            @foreach($terminals as $terminal)
                                <option value="{{ $terminal->id }}" {{ old('departure_terminal_id', $trip->departure_terminal_id) == $terminal->id ? 'selected' : '' }}>{{ $terminal->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Arr. Terminal</label>
                        <select name="arrival_terminal_id" class="w-full rounded-xl border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-white focus:ring-primary-500 focus:border-primary-500 text-sm">
                            <option value="">-- Auto --</option>
                            @foreach($terminals as $terminal)
                                <option value="{{ $terminal->id }}" {{ old('arrival_terminal_id', $trip->arrival_terminal_id) == $terminal->id ? 'selected' : '' }}>{{ $terminal->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Bus Assignment <span class="text-red-500">*</span></label>
                    <select name="bus_id" required id="bus_select" class="w-full rounded-xl border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-white focus:ring-primary-500 focus:border-primary-500">
                        <option value="">-- Select Bus --</option>
                        @foreach($buses as $bus)
                            <option value="{{ $bus->id }}" data-seats="{{ $bus->total_seats }}" {{ old('bus_id', $trip->bus_id) == $bus->id ? 'selected' : '' }}>
                                #{{ $bus->bus_number }} - {{ $bus->type->type_name ?? 'Standard' }} ({{ $bus->total_seats }} Seats)
                            </option>
                        @endforeach
                    </select>
                    @error('bus_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Driver Assignment <span class="text-red-500">*</span></label>
                    <select name="driver_id" required class="w-full rounded-xl border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-white focus:ring-primary-500 focus:border-primary-500">
                        <option value="">-- Select Driver --</option>
                        @foreach($drivers as $driver)
                            <option value="{{ $driver->id }}" {{ old('driver_id', $trip->driver_id) == $driver->id ? 'selected' : '' }}>
                                {{ $driver->user->name ?? 'Unknown' }} (Lic: {{ $driver->license_number }})
                            </option>
                        @endforeach
                    </select>
                    @error('driver_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Trip Date <span class="text-red-500">*</span></label>
                    <input type="date" name="trip_date" value="{{ old('trip_date', $trip->trip_date ? $trip->trip_date->format('Y-m-d') : '') }}" required
                           class="w-full rounded-xl border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-white focus:ring-primary-500 focus:border-primary-500">
                    @error('trip_date')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Departure Time <span class="text-red-500">*</span></label>
                    <input type="datetime-local" name="departure_time" value="{{ old('departure_time', $trip->departure_time ? $trip->departure_time->format('Y-m-d\TH:i') : '') }}" required
                           class="w-full rounded-xl border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-white focus:ring-primary-500 focus:border-primary-500">
                    @error('departure_time')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Arrival Time <span class="text-red-500">*</span></label>
                    <input type="datetime-local" name="arrival_time" value="{{ old('arrival_time', $trip->arrival_time ? $trip->arrival_time->format('Y-m-d\TH:i') : '') }}" required
                           class="w-full rounded-xl border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-white focus:ring-primary-500 focus:border-primary-500">
                    @error('arrival_time')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Available Seats <span class="text-red-500">*</span></label>
                    <input type="number" name="available_seats" id="available_seats" value="{{ old('available_seats', $trip->available_seats) }}" required min="0"
                           class="w-full rounded-xl border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-white focus:ring-primary-500 focus:border-primary-500">
                    @error('available_seats')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Fare (₱) <span class="text-red-500">*</span></label>
                    <input type="number" step="0.01" name="fare" value="{{ old('fare', $trip->fare) }}" required min="0"
                           class="w-full rounded-xl border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-white focus:ring-primary-500 focus:border-primary-500">
                    @error('fare')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Status <span class="text-red-500">*</span></label>
                    <select name="status" required class="w-full rounded-xl border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-white focus:ring-primary-500 focus:border-primary-500">
                        <option value="scheduled" {{ old('status', $trip->status) === 'scheduled' ? 'selected' : '' }}>Scheduled</option>
                        <option value="boarding" {{ old('status', $trip->status) === 'boarding' ? 'selected' : '' }}>Boarding</option>
                        <option value="in_transit" {{ old('status', $trip->status) === 'in_transit' ? 'selected' : '' }}>In Transit</option>
                        <option value="completed" {{ old('status', $trip->status) === 'completed' ? 'selected' : '' }}>Completed</option>
                        <option value="delayed" {{ old('status', $trip->status) === 'delayed' ? 'selected' : '' }}>Delayed</option>
                        <option value="cancelled" {{ old('status', $trip->status) === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                    @error('status')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Notes</label>
                <textarea name="notes" rows="2"
                          class="w-full rounded-xl border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-white focus:ring-primary-500 focus:border-primary-500">{{ old('notes', $trip->notes) }}</textarea>
                @error('notes')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
        </div>

        <div class="mt-8 pt-6 border-t border-slate-100 dark:border-slate-700 flex justify-end gap-3">
            <a href="{{ route('admin.trips.index') }}" class="px-5 py-2.5 rounded-xl border border-slate-200 dark:border-slate-600 text-slate-600 dark:text-slate-300 font-semibold hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors">
                Cancel
            </a>
            <button type="submit" class="px-5 py-2.5 rounded-xl bg-primary-600 text-white font-semibold hover:bg-primary-700 transition-colors">
                {{ $trip->exists ? 'Save Changes' : 'Create Trip' }}
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
    document.getElementById('bus_select').addEventListener('change', function() {
        if (!{{ $trip->exists ? 'true' : 'false' }}) {
            const option = this.options[this.selectedIndex];
            const seats = option.getAttribute('data-seats');
            if (seats) {
                document.getElementById('available_seats').value = seats;
            }
        }
    });
</script>
@endpush
@endsection
