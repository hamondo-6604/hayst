@extends('layouts.admin')

@section('title', 'Manage Buses')

@section('content')
<div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
    <div>
        <h1 class="text-2xl font-bold text-slate-800 dark:text-white">Buses</h1>
        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Manage the fleet, view bus status, and assign layouts.</p>
    </div>
    <button onclick="openAdminModal('create-bus-modal')" class="px-5 py-2.5 bg-primary-600 hover:bg-primary-700 text-white text-sm font-semibold rounded-xl transition-colors inline-flex items-center justify-center gap-2 shadow-sm">
        <i class="fa-solid fa-plus"></i> Add Bus
    </button>
</div>

<div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 p-4 mb-6">
    <form method="GET" action="{{ route('admin.buses.index') }}" class="flex flex-col sm:flex-row gap-4 items-end">
        <div class="flex-1">
            <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 mb-1">Search Buses</label>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Bus Number or Name..." 
                   class="w-full px-4 py-2 rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-white text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none transition-colors">
        </div>
        <div class="w-full sm:w-48 relative" data-custom-select>
            <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 mb-1">Status</label>
            <input type="hidden" name="status" value="{{ request('status') }}" class="custom-select-input">
            
            <button type="button" onclick="this.nextElementSibling.classList.toggle('hidden')" class="w-full px-4 py-2 flex items-center justify-between rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-white text-sm focus:ring-primary-500 focus:border-primary-500 transition-colors cursor-pointer">
                <span class="custom-select-text">
                    @if(request('status') === 'active') Active
                    @elseif(request('status') === 'maintenance') Maintenance
                    @elseif(request('status') === 'inactive') Inactive
                    @else All Statuses
                    @endif
                </span>
                <i class="fa-solid fa-chevron-down text-[10px] text-slate-400"></i>
            </button>
            
            <div class="custom-select-menu hidden absolute left-0 right-0 top-full mt-2 bg-white dark:bg-slate-800 rounded-xl shadow-xl border border-slate-100 dark:border-slate-700 py-1.5 z-50 overflow-hidden">
                <div class="px-4 py-2.5 text-sm font-medium text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-700 cursor-pointer transition-colors" data-value="" onclick="selectCustomOption(this)">All Statuses</div>
                <div class="px-4 py-2.5 text-sm font-medium text-emerald-700 bg-emerald-50 dark:bg-emerald-900/20 dark:text-emerald-400 hover:bg-emerald-100 dark:hover:bg-emerald-900/40 cursor-pointer transition-colors" data-value="active" onclick="selectCustomOption(this)">Active</div>
                <div class="px-4 py-2.5 text-sm font-medium text-amber-700 bg-amber-50 dark:bg-amber-900/20 dark:text-amber-400 hover:bg-amber-100 dark:hover:bg-amber-900/40 cursor-pointer transition-colors" data-value="maintenance" onclick="selectCustomOption(this)">Maintenance</div>
                <div class="px-4 py-2.5 text-sm font-medium text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-700 cursor-pointer transition-colors" data-value="inactive" onclick="selectCustomOption(this)">Inactive</div>
            </div>
        </div>
        <div>
            <button type="submit" class="px-5 py-2.5 bg-slate-800 hover:bg-slate-900 dark:bg-slate-700 dark:hover:bg-slate-600 text-white text-sm font-semibold rounded-xl transition-colors">
                Filter
            </button>
            @if(request()->hasAny(['search', 'status']))
                <a href="{{ route('admin.buses.index') }}" class="ml-2 text-sm text-primary-600 hover:text-primary-700 dark:text-primary-400">Clear</a>
            @endif
        </div>
    </form>
</div>

<div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 dark:bg-slate-700/50 border-b border-slate-100 dark:border-slate-700 text-xs uppercase tracking-wider text-slate-500 dark:text-slate-400 font-semibold">
                    <th class="p-4">Bus Identifier</th>
                    <th class="p-4">Type & Layout</th>
                    <th class="p-4">Capacity</th>
                    <th class="p-4">Status</th>
                    <th class="p-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                @forelse($buses as $bus)
                <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors">
                    <td class="p-4 flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-primary-100 dark:bg-slate-700 text-primary-700 dark:text-primary-400 flex items-center justify-center text-lg">
                            <i class="fa-solid fa-bus-simple"></i>
                        </div>
                        <div>
                            <div class="font-bold text-slate-800 dark:text-slate-200">#{{ $bus->bus_number }}</div>
                            <div class="text-xs text-slate-500 mt-0.5">{{ $bus->bus_name ?? '—' }}</div>
                        </div>
                    </td>
                    <td class="p-4">
                        <div class="text-sm font-bold text-slate-800 dark:text-slate-200">{{ $bus->type->type_name ?? 'Standard' }}</div>
                        <div class="text-xs text-slate-500 mt-0.5">{{ $bus->seatLayout->layout_name ?? 'Default Layout' }}</div>
                    </td>
                    <td class="p-4">
                        <div class="text-sm text-slate-800 dark:text-slate-200">{{ $bus->total_seats }} Seats</div>
                    </td>
                    <td class="p-4">
                        @php
                            $badgeColors = [
                                'active' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400',
                                'maintenance' => 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400',
                                'inactive' => 'bg-slate-100 text-slate-700 dark:bg-slate-700 dark:text-slate-300',
                            ];
                            $colorClass = $badgeColors[$bus->status] ?? 'bg-slate-100 text-slate-700';
                        @endphp
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $colorClass }}">
                            {{ ucfirst($bus->status) }}
                        </span>
                    </td>
                    <td class="p-4 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <button onclick="openAdminModal('edit-bus-modal-{{ $bus->id }}')" class="w-8 h-8 rounded-lg bg-slate-100 hover:bg-slate-200 dark:bg-slate-700 dark:hover:bg-slate-600 text-slate-600 dark:text-slate-300 flex items-center justify-center transition-colors">
                                <i class="fa-solid fa-pen-to-square text-sm"></i>
                            </button>
                            <button onclick="openAdminModal('delete-bus-modal-{{ $bus->id }}')" class="w-8 h-8 rounded-lg bg-red-50 hover:bg-red-100 dark:bg-red-900/20 dark:hover:bg-red-900/40 text-red-600 dark:text-red-400 flex items-center justify-center transition-colors">
                                <i class="fa-solid fa-trash-can text-sm"></i>
                            </button>
                        </div>
                    </td>
                </tr>

                <!-- Edit Modal -->
                <x-modal id="edit-bus-modal-{{ $bus->id }}" title="Edit Bus" size="lg">
                    <form action="{{ route('admin.buses.update', $bus) }}" method="POST" onsubmit="handleAjaxForm(this, 'edit-bus-modal-{{ $bus->id }}', () => window.location.reload())">
                        @csrf
                        @method('PUT')
                        <div class="space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1">Bus Number / Plate <span class="text-red-500">*</span></label>
                                    <input type="text" name="bus_number" value="{{ $bus->bus_number }}" required class="w-full px-4 py-2 text-sm bg-white dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-xl text-slate-800 dark:text-white outline-none focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1">Bus Name / Alias</label>
                                    <input type="text" name="bus_name" value="{{ $bus->bus_name }}" class="w-full px-4 py-2 text-sm bg-white dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-xl text-slate-800 dark:text-white outline-none focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors">
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1">Bus Type <span class="text-red-500">*</span></label>
                                    <select name="bus_type_id" required class="w-full px-4 py-2 pr-8 text-sm bg-white dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-xl text-slate-800 dark:text-white outline-none focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors cursor-pointer">
                                        <option value="">-- Select Type --</option>
                                        @foreach($busTypes as $type)
                                            <option value="{{ $type->id }}" {{ $bus->bus_type_id == $type->id ? 'selected' : '' }}>{{ $type->type_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1">Seat Layout <span class="text-red-500">*</span></label>
                                    <select name="seat_layout_id" required onchange="const c = this.options[this.selectedIndex].getAttribute('data-capacity'); if(c) this.form.querySelector('[name=total_seats]').value = c;" class="w-full px-4 py-2 pr-8 text-sm bg-white dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-xl text-slate-800 dark:text-white outline-none focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors cursor-pointer">
                                        <option value="">-- Select Layout --</option>
                                        @foreach($seatLayouts as $layout)
                                            <option value="{{ $layout->id }}" data-capacity="{{ $layout->capacity }}" {{ $bus->seat_layout_id == $layout->id ? 'selected' : '' }}>{{ $layout->layout_name }} ({{ $layout->capacity }} Seats)</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1">Total Seats <span class="text-red-500">*</span></label>
                                    <input type="number" name="total_seats" value="{{ $bus->total_seats }}" required min="1" class="w-full px-4 py-2 text-sm bg-white dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-xl text-slate-800 dark:text-white outline-none focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1">Status <span class="text-red-500">*</span></label>
                                    <div class="relative" data-custom-select>
                                        <input type="hidden" name="status" value="{{ $bus->status }}" class="custom-select-input" required>
                                        <button type="button" onclick="this.nextElementSibling.classList.toggle('hidden')" class="w-full flex items-center justify-between px-4 py-2 text-sm bg-white dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-xl text-slate-800 dark:text-white outline-none focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors cursor-pointer">
                                            <span class="custom-select-text">{{ ucfirst($bus->status) }}</span>
                                            <i class="fa-solid fa-chevron-down text-[10px] text-slate-400"></i>
                                        </button>
                                        <div class="custom-select-menu hidden mt-2 bg-slate-100 dark:bg-slate-700/50 rounded-xl p-1 flex flex-col gap-1 overflow-hidden">
                                            <div class="w-full text-left px-4 py-2 text-sm font-semibold text-emerald-700 hover:bg-emerald-100 dark:hover:bg-emerald-900/40 dark:text-emerald-400 rounded-lg cursor-pointer transition-colors shadow-sm" data-value="active" onclick="selectCustomOption(this)">Active</div>
                                            <div class="w-full text-left px-4 py-2 text-sm font-semibold text-amber-700 hover:bg-amber-100 dark:hover:bg-amber-900/40 dark:text-amber-400 rounded-lg cursor-pointer transition-colors shadow-sm" data-value="maintenance" onclick="selectCustomOption(this)">Maintenance</div>
                                            <div class="w-full text-left px-4 py-2 text-sm font-semibold text-slate-600 hover:bg-white dark:hover:bg-slate-800 dark:text-slate-300 rounded-lg cursor-pointer transition-colors" data-value="inactive" onclick="selectCustomOption(this)">Inactive</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div>
                                <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1">Description</label>
                                <textarea name="description" rows="2" class="w-full px-4 py-2 text-sm bg-white dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-xl text-slate-800 dark:text-white outline-none focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors">{{ $bus->description }}</textarea>
                            </div>
                        </div>
                        <x-slot:footer>
                            <button type="button" onclick="closeAdminModal('edit-bus-modal-{{ $bus->id }}')" class="px-4 py-2 rounded-xl text-slate-600 hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-700 transition-colors font-semibold text-sm">Cancel</button>
                            <button type="submit" class="px-4 py-2 rounded-xl bg-primary-600 text-white font-semibold text-sm hover:bg-primary-700 transition-colors">Save Changes</button>
                        </x-slot:footer>
                    </form>
                </x-modal>

                <!-- Delete Modal -->
                <x-modal id="delete-bus-modal-{{ $bus->id }}" title="Delete Bus" size="sm">
                    <form action="{{ route('admin.buses.destroy', $bus) }}" method="POST" onsubmit="handleAjaxForm(this, 'delete-bus-modal-{{ $bus->id }}', () => window.location.reload())">
                        @csrf
                        @method('DELETE')
                        <div class="text-center py-4">
                            <div class="w-16 h-16 rounded-full bg-red-100 dark:bg-red-900/30 text-red-600 flex items-center justify-center text-3xl mx-auto mb-4">
                                <i class="fa-solid fa-triangle-exclamation"></i>
                            </div>
                            <h4 class="text-lg font-bold text-slate-800 dark:text-white mb-2">Are you sure?</h4>
                            <p class="text-sm text-slate-500 dark:text-slate-400">Do you really want to delete bus <strong>#{{ $bus->bus_number }}</strong>? This action cannot be undone if no trips exist for it.</p>
                        </div>
                        <x-slot:footer>
                            <button type="button" onclick="closeAdminModal('delete-bus-modal-{{ $bus->id }}')" class="px-4 py-2 rounded-xl text-slate-600 hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-700 transition-colors font-semibold text-sm">Cancel</button>
                            <button type="submit" class="px-4 py-2 rounded-xl bg-red-600 text-white font-semibold text-sm hover:bg-red-700 transition-colors">Yes, Delete</button>
                        </x-slot:footer>
                    </form>
                </x-modal>

                @empty
                <tr>
                    <td colspan="5" class="p-8 text-center text-slate-500 dark:text-slate-400">
                        <div class="text-4xl mb-2"><i class="fa-solid fa-bus text-slate-300 dark:text-slate-600"></i></div>
                        <p>No buses found matching your criteria.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($buses->hasPages())
    <div class="p-4 border-t border-slate-100 dark:border-slate-700">
        {{ $buses->links() }}
    </div>
    @endif
</div>

<!-- Create Modal -->
<x-modal id="create-bus-modal" title="Add New Bus" size="lg">
    <form action="{{ route('admin.buses.store') }}" method="POST" onsubmit="handleAjaxForm(this, 'create-bus-modal', () => window.location.reload())">
        @csrf
        <div class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1">Bus Number / Plate <span class="text-red-500">*</span></label>
                    <input type="text" name="bus_number" required class="w-full px-4 py-2 text-sm bg-white dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-xl text-slate-800 dark:text-white outline-none focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1">Bus Name / Alias</label>
                    <input type="text" name="bus_name" class="w-full px-4 py-2 text-sm bg-white dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-xl text-slate-800 dark:text-white outline-none focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1">Bus Type <span class="text-red-500">*</span></label>
                    <select name="bus_type_id" required class="w-full px-4 py-2 pr-8 text-sm bg-white dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-xl text-slate-800 dark:text-white outline-none focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors cursor-pointer">
                        <option value="">-- Select Type --</option>
                        @foreach($busTypes as $type)
                            <option value="{{ $type->id }}">{{ $type->type_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1">Seat Layout <span class="text-red-500">*</span></label>
                    <select name="seat_layout_id" required onchange="const c = this.options[this.selectedIndex].getAttribute('data-capacity'); if(c) this.form.querySelector('[name=total_seats]').value = c;" class="w-full px-4 py-2 pr-8 text-sm bg-white dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-xl text-slate-800 dark:text-white outline-none focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors cursor-pointer">
                        <option value="">-- Select Layout --</option>
                        @foreach($seatLayouts as $layout)
                            <option value="{{ $layout->id }}" data-capacity="{{ $layout->capacity }}">{{ $layout->layout_name }} ({{ $layout->capacity }} Seats)</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1">Total Seats <span class="text-red-500">*</span></label>
                    <input type="number" name="total_seats" required min="1" class="w-full px-4 py-2 text-sm bg-white dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-xl text-slate-800 dark:text-white outline-none focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1">Status <span class="text-red-500">*</span></label>
                    <div class="relative" data-custom-select>
                        <input type="hidden" name="status" value="active" class="custom-select-input" required>
                        <button type="button" onclick="this.nextElementSibling.classList.toggle('hidden')" class="w-full flex items-center justify-between px-4 py-2 text-sm bg-white dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-xl text-slate-800 dark:text-white outline-none focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors cursor-pointer">
                            <span class="custom-select-text">Active</span>
                            <i class="fa-solid fa-chevron-down text-[10px] text-slate-400"></i>
                        </button>
                        <div class="custom-select-menu hidden mt-2 bg-slate-100 dark:bg-slate-700/50 rounded-xl p-1 flex flex-col gap-1 overflow-hidden">
                            <div class="w-full text-left px-4 py-2 text-sm font-semibold text-emerald-700 hover:bg-emerald-100 dark:hover:bg-emerald-900/40 dark:text-emerald-400 rounded-lg cursor-pointer transition-colors shadow-sm" data-value="active" onclick="selectCustomOption(this)">Active</div>
                            <div class="w-full text-left px-4 py-2 text-sm font-semibold text-amber-700 hover:bg-amber-100 dark:hover:bg-amber-900/40 dark:text-amber-400 rounded-lg cursor-pointer transition-colors shadow-sm" data-value="maintenance" onclick="selectCustomOption(this)">Maintenance</div>
                            <div class="w-full text-left px-4 py-2 text-sm font-semibold text-slate-600 hover:bg-white dark:hover:bg-slate-800 dark:text-slate-300 rounded-lg cursor-pointer transition-colors" data-value="inactive" onclick="selectCustomOption(this)">Inactive</div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div>
                <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1">Description</label>
                <textarea name="description" rows="2" class="w-full px-4 py-2 text-sm bg-white dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-xl text-slate-800 dark:text-white outline-none focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors"></textarea>
            </div>
        </div>
        <x-slot:footer>
            <button type="button" onclick="closeAdminModal('create-bus-modal')" class="px-4 py-2 rounded-xl text-slate-600 hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-700 transition-colors font-semibold text-sm">Cancel</button>
            <button type="submit" class="px-4 py-2 rounded-xl bg-primary-600 text-white font-semibold text-sm hover:bg-primary-700 transition-colors">Create Bus</button>
        </x-slot:footer>
    </form>
</x-modal>
@endsection
