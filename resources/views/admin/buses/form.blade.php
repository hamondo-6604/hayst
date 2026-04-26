@extends('layouts.admin')

@section('title', $bus->exists ? 'Edit Bus' : 'Add Bus')

@section('content')
<div class="mb-6 flex items-center gap-4">
    <a href="{{ route('admin.buses.index') }}" class="w-10 h-10 rounded-xl bg-white dark:bg-slate-800 shadow-sm border border-slate-100 dark:border-slate-700 flex items-center justify-center text-slate-500 hover:text-primary-600 transition-colors">
        <i class="fa-solid fa-arrow-left"></i>
    </a>
    <div>
        <h1 class="text-2xl font-bold text-slate-800 dark:text-white">{{ $bus->exists ? 'Edit Bus' : 'Add New Bus' }}</h1>
    </div>
</div>

<div class="max-w-2xl bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 p-6">
    <form action="{{ $bus->exists ? route('admin.buses.update', $bus) : route('admin.buses.store') }}" method="POST">
        @csrf
        @if($bus->exists)
            @method('PUT')
        @endif

        <div class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Bus Number / Plate <span class="text-red-500">*</span></label>
                    <input type="text" name="bus_number" value="{{ old('bus_number', $bus->bus_number) }}" required
                           class="w-full rounded-xl border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-white focus:ring-primary-500 focus:border-primary-500">
                    @error('bus_number')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Bus Name / Alias</label>
                    <input type="text" name="bus_name" value="{{ old('bus_name', $bus->bus_name) }}"
                           class="w-full rounded-xl border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-white focus:ring-primary-500 focus:border-primary-500">
                    @error('bus_name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Bus Type <span class="text-red-500">*</span></label>
                    <select name="bus_type_id" required class="w-full rounded-xl border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-white focus:ring-primary-500 focus:border-primary-500">
                        <option value="">-- Select Type --</option>
                        @foreach($busTypes as $type)
                            <option value="{{ $type->id }}" {{ old('bus_type_id', $bus->bus_type_id) == $type->id ? 'selected' : '' }}>
                                {{ $type->type_name }}
                            </option>
                        @endforeach
                    </select>
                    @error('bus_type_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Seat Layout <span class="text-red-500">*</span></label>
                    <select name="seat_layout_id" required class="w-full rounded-xl border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-white focus:ring-primary-500 focus:border-primary-500">
                        <option value="">-- Select Layout --</option>
                        @foreach($seatLayouts as $layout)
                            <option value="{{ $layout->id }}" data-capacity="{{ $layout->capacity }}" {{ old('seat_layout_id', $bus->seat_layout_id) == $layout->id ? 'selected' : '' }}>
                                {{ $layout->layout_name }} ({{ $layout->capacity }} Seats)
                            </option>
                        @endforeach
                    </select>
                    @error('seat_layout_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Total Seats <span class="text-red-500">*</span></label>
                    <input type="number" name="total_seats" value="{{ old('total_seats', $bus->total_seats) }}" required min="1" id="total_seats_input"
                           class="w-full rounded-xl border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-white focus:ring-primary-500 focus:border-primary-500">
                    <p class="text-xs text-slate-500 mt-1">Usually matches the layout capacity.</p>
                    @error('total_seats')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Status <span class="text-red-500">*</span></label>
                    <select name="status" required class="w-full rounded-xl border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-white focus:ring-primary-500 focus:border-primary-500">
                        <option value="active" {{ old('status', $bus->status) === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="maintenance" {{ old('status', $bus->status) === 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                        <option value="inactive" {{ old('status', $bus->status) === 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                    @error('status')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Description / Notes</label>
                <textarea name="description" rows="3"
                          class="w-full rounded-xl border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-white focus:ring-primary-500 focus:border-primary-500">{{ old('description', $bus->description) }}</textarea>
                @error('description')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
        </div>

        <div class="mt-8 pt-6 border-t border-slate-100 dark:border-slate-700 flex justify-end gap-3">
            <a href="{{ route('admin.buses.index') }}" class="px-5 py-2.5 rounded-xl border border-slate-200 dark:border-slate-600 text-slate-600 dark:text-slate-300 font-semibold hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors">
                Cancel
            </a>
            <button type="submit" class="px-5 py-2.5 rounded-xl bg-primary-600 text-white font-semibold hover:bg-primary-700 transition-colors">
                {{ $bus->exists ? 'Save Changes' : 'Create Bus' }}
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
    // Auto-fill total seats when a layout is selected
    document.querySelector('select[name="seat_layout_id"]').addEventListener('change', function() {
        if(this.value) {
            const option = this.options[this.selectedIndex];
            const capacity = option.getAttribute('data-capacity');
            if(capacity) {
                document.getElementById('total_seats_input').value = capacity;
            }
        }
    });
</script>
@endpush
@endsection
