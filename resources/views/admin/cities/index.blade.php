@extends('layouts.admin')

@section('title', 'Manage Cities')

@section('content')
<div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
    <div>
        <h1 class="text-2xl font-bold text-slate-800 dark:text-white">Cities</h1>
        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Manage service areas, provinces, and regions.</p>
    </div>
    <button onclick="openAdminModal('create-city-modal')" class="px-5 py-2.5 bg-primary-600 hover:bg-primary-700 text-white text-sm font-semibold rounded-xl transition-colors inline-flex items-center justify-center gap-2 shadow-sm">
        <i class="fa-solid fa-plus"></i> Add City
    </button>
</div>

<!-- Filters -->
<div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 p-4 mb-6">
    <form method="GET" action="{{ route('admin.cities.index') }}" class="flex flex-col sm:flex-row gap-4 items-end">
        <div class="flex-1">
            <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 mb-1">Search Cities</label>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name, province, or region..." 
                   class="w-full px-4 py-2 rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-white text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none transition-colors">
        </div>
        <div class="w-full sm:w-48 relative" data-custom-select>
            <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 mb-1">Status</label>
            <input type="hidden" name="status" value="{{ request('status') }}" class="custom-select-input">
            
            <button type="button" onclick="this.nextElementSibling.classList.toggle('hidden')" class="w-full px-4 py-2 flex items-center justify-between rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-white text-sm focus:ring-primary-500 focus:border-primary-500 transition-colors cursor-pointer">
                <span class="custom-select-text">
                    @if(request('status') === 'active') Active
                    @elseif(request('status') === 'inactive') Inactive
                    @else All Statuses
                    @endif
                </span>
                <i class="fa-solid fa-chevron-down text-[10px] text-slate-400"></i>
            </button>
            
            <div class="custom-select-menu hidden absolute left-0 right-0 top-full mt-2 bg-white dark:bg-slate-800 rounded-xl shadow-xl border border-slate-100 dark:border-slate-700 py-1.5 z-50 overflow-hidden">
                <div class="px-4 py-2.5 text-sm font-medium text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-700 cursor-pointer transition-colors" data-value="" onclick="selectCustomOption(this)">
                    All Statuses
                </div>
                <div class="px-4 py-2.5 text-sm font-medium text-emerald-700 bg-emerald-50 dark:bg-emerald-900/20 dark:text-emerald-400 hover:bg-emerald-100 dark:hover:bg-emerald-900/40 cursor-pointer transition-colors" data-value="active" onclick="selectCustomOption(this)">
                    Active
                </div>
                <div class="px-4 py-2.5 text-sm font-medium text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-700 cursor-pointer transition-colors" data-value="inactive" onclick="selectCustomOption(this)">
                    Inactive
                </div>
            </div>
        </div>
        <div>
            <button type="submit" class="px-5 py-2.5 bg-slate-800 hover:bg-slate-900 dark:bg-slate-700 dark:hover:bg-slate-600 text-white text-sm font-semibold rounded-xl transition-colors">
                Filter
            </button>
            @if(request()->hasAny(['search', 'status']))
                <a href="{{ route('admin.cities.index') }}" class="ml-2 text-sm text-primary-600 hover:text-primary-700 dark:text-primary-400">Clear</a>
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
                    <th class="p-4">City Name</th>
                    <th class="p-4">Province</th>
                    <th class="p-4">Region</th>
                    <th class="p-4">Status</th>
                    <th class="p-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                @forelse($cities as $city)
                <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors">
                    <td class="p-4">
                        <div class="font-bold text-slate-800 dark:text-slate-200"><i class="fa-solid fa-city text-slate-400 mr-2 text-xs"></i>{{ $city->name }}</div>
                    </td>
                    <td class="p-4">
                        <div class="text-sm text-slate-800 dark:text-slate-200">{{ $city->province ?? '—' }}</div>
                    </td>
                    <td class="p-4">
                        <div class="text-sm text-slate-800 dark:text-slate-200">{{ $city->region ?? '—' }}</div>
                    </td>
                    <td class="p-4">
                        @if($city->status === 'active')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400">
                                Active
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-slate-100 text-slate-700 dark:bg-slate-700 dark:text-slate-300">
                                Inactive
                            </span>
                        @endif
                    </td>
                    <td class="p-4 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <button onclick="openAdminModal('edit-city-modal-{{ $city->id }}')" class="w-8 h-8 rounded-lg bg-slate-100 hover:bg-slate-200 dark:bg-slate-700 dark:hover:bg-slate-600 text-slate-600 dark:text-slate-300 flex items-center justify-center transition-colors">
                                <i class="fa-solid fa-pen-to-square text-sm"></i>
                            </button>
                            <button onclick="openAdminModal('delete-city-modal-{{ $city->id }}')" class="w-8 h-8 rounded-lg bg-red-50 hover:bg-red-100 dark:bg-red-900/20 dark:hover:bg-red-900/40 text-red-600 dark:text-red-400 flex items-center justify-center transition-colors">
                                <i class="fa-solid fa-trash-can text-sm"></i>
                            </button>
                        </div>
                    </td>
                </tr>

                <!-- Edit City Modal for {{ $city->name }} -->
                <x-modal id="edit-city-modal-{{ $city->id }}" title="Edit City" size="md">
                    <form id="edit-city-form-{{ $city->id }}" action="{{ route('admin.cities.update', $city) }}" method="POST" onsubmit="handleAjaxForm(this, 'edit-city-modal-{{ $city->id }}', () => window.location.reload(), event)">
                        @csrf
                        @method('PUT')
                        <div class="space-y-4">
                            <div>
                                <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1">City Name <span class="text-red-500">*</span></label>
                                <input type="text" name="name" value="{{ $city->name }}" required class="w-full px-4 py-2 text-sm bg-white dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-xl text-slate-800 dark:text-white outline-none focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors">
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1">Province</label>
                                    <input type="text" name="province" value="{{ $city->province }}" class="w-full px-4 py-2 text-sm bg-white dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-xl text-slate-800 dark:text-white outline-none focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors">
                                </div>
                                <div>
                                    <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1">Region</label>
                                    <input type="text" name="region" value="{{ $city->region }}" class="w-full px-4 py-2 text-sm bg-white dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-xl text-slate-800 dark:text-white outline-none focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors">
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1">Status <span class="text-red-500">*</span></label>
                                <div class="relative" data-custom-select>
                                    <input type="hidden" name="status" value="{{ $city->status }}" class="custom-select-input" required>
                                    <button type="button" onclick="this.nextElementSibling.classList.toggle('hidden')" class="w-full flex items-center justify-between px-4 py-2 text-sm bg-white dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-xl text-slate-800 dark:text-white outline-none focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors cursor-pointer">
                                        <span class="custom-select-text">{{ ucfirst($city->status) }}</span>
                                        <i class="fa-solid fa-chevron-down text-[10px] text-slate-400"></i>
                                    </button>
                                    <div class="custom-select-menu hidden mt-2 bg-slate-100 dark:bg-slate-700/50 rounded-xl p-1 flex gap-1 overflow-hidden">
                                        <div class="flex-1 text-center px-4 py-2 text-sm font-semibold text-emerald-700 bg-emerald-100 dark:bg-emerald-900/40 dark:text-emerald-400 rounded-lg cursor-pointer transition-colors shadow-sm" data-value="active" onclick="selectCustomOption(this)">
                                            Active
                                        </div>
                                        <div class="flex-1 text-center px-4 py-2 text-sm font-semibold text-slate-600 hover:bg-white dark:hover:bg-slate-800 dark:text-slate-300 rounded-lg cursor-pointer transition-colors" data-value="inactive" onclick="selectCustomOption(this)">
                                            Inactive
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <x-slot:footer>
                            <button type="button" onclick="closeAdminModal('edit-city-modal-{{ $city->id }}')" class="px-4 py-2 rounded-xl text-slate-600 hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-700 transition-colors font-semibold text-sm">Cancel</button>
                            <button type="submit" form="edit-city-form-{{ $city->id }}" class="px-4 py-2 rounded-xl bg-primary-600 text-white font-semibold text-sm hover:bg-primary-700 transition-colors">Save Changes</button>
                        </x-slot:footer>
                    </form>
                </x-modal>

                <!-- Delete City Modal for {{ $city->name }} -->
                <x-modal id="delete-city-modal-{{ $city->id }}" title="Delete City" size="sm">
                    <form id="delete-city-form-{{ $city->id }}" action="{{ route('admin.cities.destroy', $city) }}" method="POST" onsubmit="handleAjaxForm(this, 'delete-city-modal-{{ $city->id }}', () => window.location.reload(), event)">
                        @csrf
                        @method('DELETE')
                        <div class="text-center py-4">
                            <div class="w-16 h-16 rounded-full bg-red-100 dark:bg-red-900/30 text-red-600 flex items-center justify-center text-3xl mx-auto mb-4">
                                <i class="fa-solid fa-triangle-exclamation"></i>
                            </div>
                            <h4 class="text-lg font-bold text-slate-800 dark:text-white mb-2">Are you sure?</h4>
                            <p class="text-sm text-slate-500 dark:text-slate-400">Do you really want to delete <strong>{{ $city->name }}</strong>? This action cannot be undone.</p>
                        </div>
                        <x-slot:footer>
                            <button type="button" onclick="closeAdminModal('delete-city-modal-{{ $city->id }}')" class="px-4 py-2 rounded-xl text-slate-600 hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-700 transition-colors font-semibold text-sm">Cancel</button>
                            <button type="submit" form="delete-city-form-{{ $city->id }}" class="px-4 py-2 rounded-xl bg-red-600 text-white font-semibold text-sm hover:bg-red-700 transition-colors">Yes, Delete</button>
                        </x-slot:footer>
                    </form>
                </x-modal>

                @empty
                <tr>
                    <td colspan="5" class="p-8 text-center text-slate-500 dark:text-slate-400">
                        <div class="text-4xl mb-2"><i class="fa-solid fa-location-dot text-slate-300 dark:text-slate-600"></i></div>
                        <p>No cities found matching your criteria.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($cities->hasPages())
    <div class="p-4 border-t border-slate-100 dark:border-slate-700">
        {{ $cities->links() }}
    </div>
    @endif
</div>

<!-- Create City Modal -->
<x-modal id="create-city-modal" title="Add New City" size="md">
    <form id="create-city-form" action="{{ route('admin.cities.store') }}" method="POST" onsubmit="handleAjaxForm(this, 'create-city-modal', () => window.location.reload(), event)">
        @csrf
        <div class="space-y-4">
            <div>
                <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1">City Name <span class="text-red-500">*</span></label>
                <input type="text" name="name" required class="w-full px-4 py-2 text-sm bg-white dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-xl text-slate-800 dark:text-white outline-none focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors">
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1">Province</label>
                    <input type="text" name="province" class="w-full px-4 py-2 text-sm bg-white dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-xl text-slate-800 dark:text-white outline-none focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1">Region</label>
                    <input type="text" name="region" class="w-full px-4 py-2 text-sm bg-white dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-xl text-slate-800 dark:text-white outline-none focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors">
                </div>
            </div>
            <div>
                <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1">Status <span class="text-red-500">*</span></label>
                <div class="relative" data-custom-select>
                    <input type="hidden" name="status" value="active" class="custom-select-input" required>
                    <button type="button" onclick="this.nextElementSibling.classList.toggle('hidden')" class="w-full flex items-center justify-between px-4 py-2 text-sm bg-white dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-xl text-slate-800 dark:text-white outline-none focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors cursor-pointer">
                        <span class="custom-select-text">Active</span>
                        <i class="fa-solid fa-chevron-down text-[10px] text-slate-400"></i>
                    </button>
                    <div class="custom-select-menu hidden mt-2 bg-slate-100 dark:bg-slate-700/50 rounded-xl p-1 flex gap-1 overflow-hidden">
                        <div class="flex-1 text-center px-4 py-2 text-sm font-semibold text-emerald-700 bg-emerald-100 dark:bg-emerald-900/40 dark:text-emerald-400 rounded-lg cursor-pointer transition-colors shadow-sm" data-value="active" onclick="selectCustomOption(this)">
                            Active
                        </div>
                        <div class="flex-1 text-center px-4 py-2 text-sm font-semibold text-slate-600 hover:bg-white dark:hover:bg-slate-800 dark:text-slate-300 rounded-lg cursor-pointer transition-colors" data-value="inactive" onclick="selectCustomOption(this)">
                            Inactive
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <x-slot:footer>
            <button type="button" onclick="closeAdminModal('create-city-modal')" class="px-4 py-2 rounded-xl text-slate-600 hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-slate-700 transition-colors font-semibold text-sm">Cancel</button>
            <button type="submit" form="create-city-form" class="px-4 py-2 rounded-xl bg-primary-600 text-white font-semibold text-sm hover:bg-primary-700 transition-colors">Create City</button>
        </x-slot:footer>
    </form>
</x-modal>
@endsection
