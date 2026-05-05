@extends('layouts.admin')

@section('title', 'Maintenance Logs')

@section('content')
<div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
    <div>
        <h1 class="text-2xl font-bold text-slate-800 dark:text-white">Maintenance</h1>
        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Track bus repairs, costs, and scheduled maintenance.</p>
    </div>
    <button onclick="openAdminModal('create-maintenance-modal')" class="px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white text-sm font-bold rounded-xl shadow-sm transition-colors flex items-center gap-2">
        <i class="fa-solid fa-plus"></i> Log Maintenance
    </button>
</div>

<div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 p-4 mb-6">
    <form method="GET" action="{{ route('admin.maintenance.index') }}" class="flex flex-col sm:flex-row gap-4 items-end">
        <div class="flex-1">
            <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 mb-1">Search Logs</label>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Log Title or Bus Number..." 
                   class="w-full px-4 py-2 rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-white text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none transition-colors">
        </div>
        <div class="w-full sm:w-48 relative" data-custom-select>
            <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 mb-1">Status</label>
            <input type="hidden" name="status" value="{{ request('status') }}" class="custom-select-input">
            
            <button type="button" onclick="this.nextElementSibling.classList.toggle('hidden')" class="w-full px-4 py-2 flex items-center justify-between rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-white text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors cursor-pointer">
                <span class="custom-select-text">
                    @if(request('status') === 'scheduled') Scheduled
                    @elseif(request('status') === 'in_progress') In Progress
                    @elseif(request('status') === 'completed') Completed
                    @elseif(request('status') === 'cancelled') Cancelled
                    @else All Statuses
                    @endif
                </span>
                <i class="fa-solid fa-chevron-down text-[10px] text-slate-400"></i>
            </button>
            
            <div class="custom-select-menu hidden absolute left-0 right-0 top-full mt-2 bg-white dark:bg-slate-800 rounded-xl shadow-xl border border-slate-100 dark:border-slate-700 py-1.5 z-50 overflow-hidden">
                <div class="px-4 py-2.5 text-sm font-medium text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-700 cursor-pointer transition-colors" data-value="" onclick="selectCustomOption(this)">All Statuses</div>
                <div class="px-4 py-2.5 text-sm font-medium text-amber-700 bg-amber-50 dark:bg-amber-900/20 dark:text-amber-400 hover:bg-amber-100 dark:hover:bg-amber-900/40 cursor-pointer transition-colors" data-value="scheduled" onclick="selectCustomOption(this)">Scheduled</div>
                <div class="px-4 py-2.5 text-sm font-medium text-blue-700 bg-blue-50 dark:bg-blue-900/20 dark:text-blue-400 hover:bg-blue-100 dark:hover:bg-blue-900/40 cursor-pointer transition-colors" data-value="in_progress" onclick="selectCustomOption(this)">In Progress</div>
                <div class="px-4 py-2.5 text-sm font-medium text-emerald-700 bg-emerald-50 dark:bg-emerald-900/20 dark:text-emerald-400 hover:bg-emerald-100 dark:hover:bg-emerald-900/40 cursor-pointer transition-colors" data-value="completed" onclick="selectCustomOption(this)">Completed</div>
                <div class="px-4 py-2.5 text-sm font-medium text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-700 cursor-pointer transition-colors" data-value="cancelled" onclick="selectCustomOption(this)">Cancelled</div>
            </div>
        </div>
        <div>
            <button type="submit" class="px-5 py-2.5 bg-slate-800 hover:bg-slate-900 dark:bg-slate-700 dark:hover:bg-slate-600 text-white text-sm font-semibold rounded-xl transition-colors">
                Filter
            </button>
            @if(request()->hasAny(['search', 'status']))
                <a href="{{ route('admin.maintenance.index') }}" class="ml-2 text-sm text-primary-600 hover:text-primary-700 dark:text-primary-400">Clear</a>
            @endif
        </div>
    </form>
</div>

<div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 dark:bg-slate-700/50 border-b border-slate-100 dark:border-slate-700 text-xs uppercase tracking-wider text-slate-500 dark:text-slate-400 font-semibold">
                    <th class="p-4">Bus</th>
                    <th class="p-4">Task</th>
                    <th class="p-4 text-right">Cost</th>
                    <th class="p-4">Status</th>
                    <th class="p-4">Date</th>
                    <th class="p-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                @forelse($logs as $log)
                <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors">
                    <td class="p-4">
                        <div class="font-bold text-primary-600 dark:text-primary-400">#{{ $log->bus->bus_number ?? 'Unknown' }}</div>
                    </td>
                    <td class="p-4">
                        <div class="font-bold text-slate-800 dark:text-slate-200">{{ $log->title }}</div>
                        <div class="text-xs text-slate-500 mt-0.5 capitalize">{{ str_replace('_', ' ', $log->type) }}</div>
                    </td>
                    <td class="p-4 text-right font-bold text-slate-800 dark:text-slate-200">
                        {{ $log->formatted_cost }}
                    </td>
                    <td class="p-4">
                        @php
                            $badgeColors = [
                                'completed' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400',
                                'in_progress' => 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-400',
                                'scheduled' => 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400',
                                'cancelled' => 'bg-slate-100 text-slate-700 dark:bg-slate-700 dark:text-slate-300',
                            ];
                            $colorClass = $badgeColors[$log->status] ?? 'bg-slate-100 text-slate-700';
                        @endphp
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $colorClass }}">
                            {{ ucfirst(str_replace('_', ' ', $log->status)) }}
                        </span>
                    </td>
                    <td class="p-4">
                        <div class="text-sm text-slate-800 dark:text-slate-200">{{ $log->maintenance_date ? $log->maintenance_date->format('M d, Y') : '—' }}</div>
                        @if($log->isOverdue() && $log->status !== 'completed')
                            <div class="text-xs text-red-500 font-bold mt-0.5"><i class="fa-solid fa-triangle-exclamation"></i> Overdue</div>
                        @endif
                    </td>
                    <td class="p-4 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <button type="button" onclick="openEditMaintenanceModal({{ json_encode($log) }})" class="w-8 h-8 rounded-lg bg-blue-50 hover:bg-blue-100 dark:bg-blue-900/20 dark:hover:bg-blue-900/40 text-blue-600 dark:text-blue-400 flex items-center justify-center transition-colors" title="Edit">
                                <i class="fa-solid fa-pen text-sm"></i>
                            </button>
                            <form action="{{ route('admin.maintenance.destroy', $log) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this maintenance log?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-8 h-8 rounded-lg bg-red-50 hover:bg-red-100 dark:bg-red-900/20 dark:hover:bg-red-900/40 text-red-600 dark:text-red-400 flex items-center justify-center transition-colors" title="Delete">
                                    <i class="fa-solid fa-trash-can text-sm"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="p-8 text-center text-slate-500 dark:text-slate-400">
                        <div class="text-4xl mb-2"><i class="fa-solid fa-wrench text-slate-300 dark:text-slate-600"></i></div>
                        <p>No maintenance logs found matching your criteria.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($logs->hasPages())
    <div class="p-4 border-t border-slate-100 dark:border-slate-700">
        {{ $logs->links() }}
    </div>
    @endif
</div>

<!-- Create Maintenance Modal -->
<div id="create-maintenance-modal" class="hidden fixed inset-0 z-[100] items-center justify-center p-4 sm:p-6 opacity-0 transition-opacity duration-300">
    <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm" onclick="closeAdminModal('create-maintenance-modal')"></div>
    <div class="admin-modal-panel relative w-full max-w-2xl bg-white dark:bg-slate-800 rounded-2xl shadow-2xl overflow-hidden ring-1 ring-slate-200 dark:ring-slate-700 transform scale-95 transition-transform duration-300 flex flex-col max-h-[90vh]">
        
        <!-- Header -->
        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100 dark:border-slate-700 shrink-0">
            <h3 class="text-lg font-bold text-slate-800 dark:text-white">Log Maintenance Task</h3>
            <button onclick="closeAdminModal('create-maintenance-modal')" class="text-slate-400 hover:text-slate-500 dark:hover:text-slate-300 transition-colors">
                <i class="fa-solid fa-xmark text-xl"></i>
            </button>
        </div>

        <!-- Body -->
        <div class="p-6 overflow-y-auto">
            <form id="create-maintenance-form" action="{{ route('admin.maintenance.store') }}" method="POST" onsubmit="handleAjaxForm(this, 'create-maintenance-modal')">
                @csrf
                
                <div class="mb-6">
                    <div class="relative" data-custom-select>
                        <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1">Select Bus <span class="text-red-500">*</span></label>
                        <input type="hidden" name="bus_id" value="" class="custom-select-input" required>
                        <button type="button" onclick="this.nextElementSibling.classList.toggle('hidden')" class="w-full flex items-center justify-between px-4 py-2 text-sm bg-white dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-xl text-slate-800 dark:text-white outline-none focus:ring-2 focus:ring-primary-500 cursor-pointer">
                            <span class="custom-select-text">Select a bus...</span>
                            <i class="fa-solid fa-chevron-down text-[10px] text-slate-400"></i>
                        </button>
                        <div class="custom-select-menu hidden mt-2 bg-slate-100 dark:bg-slate-700/50 rounded-xl p-1 flex flex-col gap-1 z-50 absolute left-0 right-0 max-h-60 overflow-y-auto">
                            @foreach($buses as $bus)
                            <div class="px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-200 dark:text-slate-200 dark:hover:bg-slate-600 rounded-lg cursor-pointer transition-colors" data-value="{{ $bus->id }}" onclick="selectCustomOption(this)">Bus #{{ $bus->bus_number }} ({{ $bus->plate_number }})</div>
                            @endforeach
                            @if($buses->isEmpty())
                            <div class="px-4 py-2 text-sm text-slate-500">No buses found.</div>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1">Task Title <span class="text-red-500">*</span></label>
                        <input type="text" name="title" required class="w-full px-4 py-2 text-sm bg-white dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-xl text-slate-800 dark:text-white outline-none focus:ring-2 focus:ring-primary-500" placeholder="e.g. Oil Change, Brake Repair">
                    </div>
                    <div class="relative" data-custom-select>
                        <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1">Maintenance Type <span class="text-red-500">*</span></label>
                        <input type="hidden" name="type" value="routine" class="custom-select-input" required>
                        <button type="button" onclick="this.nextElementSibling.classList.toggle('hidden')" class="w-full flex items-center justify-between px-4 py-2 text-sm bg-white dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-xl text-slate-800 dark:text-white outline-none focus:ring-2 focus:ring-primary-500 cursor-pointer">
                            <span class="custom-select-text">Routine</span>
                            <i class="fa-solid fa-chevron-down text-[10px] text-slate-400"></i>
                        </button>
                        <div class="custom-select-menu hidden mt-2 bg-slate-100 dark:bg-slate-700/50 rounded-xl p-1 flex flex-col gap-1 z-50 absolute left-0 right-0">
                            <div class="px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-200 dark:text-slate-200 dark:hover:bg-slate-600 rounded-lg cursor-pointer transition-colors" data-value="routine" onclick="selectCustomOption(this)">Routine</div>
                            <div class="px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-200 dark:text-slate-200 dark:hover:bg-slate-600 rounded-lg cursor-pointer transition-colors" data-value="repair" onclick="selectCustomOption(this)">Repair</div>
                            <div class="px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-200 dark:text-slate-200 dark:hover:bg-slate-600 rounded-lg cursor-pointer transition-colors" data-value="inspection" onclick="selectCustomOption(this)">Inspection</div>
                            <div class="px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-200 dark:text-slate-200 dark:hover:bg-slate-600 rounded-lg cursor-pointer transition-colors" data-value="emergency" onclick="selectCustomOption(this)">Emergency</div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1">Scheduled/Start Date <span class="text-red-500">*</span></label>
                        <input type="date" name="maintenance_date" required class="w-full px-4 py-2 text-sm bg-white dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-xl text-slate-800 dark:text-white outline-none focus:ring-2 focus:ring-primary-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1">Completed Date</label>
                        <input type="date" name="completed_date" class="w-full px-4 py-2 text-sm bg-white dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-xl text-slate-800 dark:text-white outline-none focus:ring-2 focus:ring-primary-500">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <div class="relative" data-custom-select>
                        <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1">Status <span class="text-red-500">*</span></label>
                        <input type="hidden" name="status" value="scheduled" class="custom-select-input" required>
                        <button type="button" onclick="this.nextElementSibling.classList.toggle('hidden')" class="w-full flex items-center justify-between px-4 py-2 text-sm bg-white dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-xl text-slate-800 dark:text-white outline-none focus:ring-2 focus:ring-primary-500 cursor-pointer">
                            <span class="custom-select-text">Scheduled</span>
                            <i class="fa-solid fa-chevron-down text-[10px] text-slate-400"></i>
                        </button>
                        <div class="custom-select-menu hidden mt-2 bg-slate-100 dark:bg-slate-700/50 rounded-xl p-1 flex flex-col gap-1 z-50 absolute left-0 right-0">
                            <div class="px-4 py-2 text-sm font-semibold text-amber-700 hover:bg-amber-100 dark:text-amber-400 dark:hover:bg-amber-900/40 rounded-lg cursor-pointer transition-colors" data-value="scheduled" onclick="selectCustomOption(this)">Scheduled</div>
                            <div class="px-4 py-2 text-sm font-semibold text-blue-700 hover:bg-blue-100 dark:text-blue-400 dark:hover:bg-blue-900/40 rounded-lg cursor-pointer transition-colors" data-value="in_progress" onclick="selectCustomOption(this)">In Progress</div>
                            <div class="px-4 py-2 text-sm font-semibold text-emerald-700 hover:bg-emerald-100 dark:text-emerald-400 dark:hover:bg-emerald-900/40 rounded-lg cursor-pointer transition-colors" data-value="completed" onclick="selectCustomOption(this)">Completed</div>
                            <div class="px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-200 dark:text-slate-200 dark:hover:bg-slate-600 rounded-lg cursor-pointer transition-colors" data-value="cancelled" onclick="selectCustomOption(this)">Cancelled</div>
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1">Cost (PHP)</label>
                        <input type="number" name="cost" min="0" step="0.01" class="w-full px-4 py-2 text-sm bg-white dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-xl text-slate-800 dark:text-white outline-none focus:ring-2 focus:ring-primary-500" placeholder="0.00">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1">Next Due Date</label>
                        <input type="date" name="next_maintenance_due" class="w-full px-4 py-2 text-sm bg-white dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-xl text-slate-800 dark:text-white outline-none focus:ring-2 focus:ring-primary-500">
                    </div>
                </div>

                <div class="mb-6">
                    <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1">Performed By (Mechanic/Shop)</label>
                    <input type="text" name="performed_by" class="w-full px-4 py-2 text-sm bg-white dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-xl text-slate-800 dark:text-white outline-none focus:ring-2 focus:ring-primary-500">
                </div>

                <div class="mb-6">
                    <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1">Parts Replaced</label>
                    <textarea name="parts_replaced" rows="2" class="w-full px-4 py-2 text-sm bg-white dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-xl text-slate-800 dark:text-white outline-none focus:ring-2 focus:ring-primary-500" placeholder="List any parts that were changed..."></textarea>
                </div>

                <div class="mb-6">
                    <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1">Description / Notes</label>
                    <textarea name="description" rows="3" class="w-full px-4 py-2 text-sm bg-white dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-xl text-slate-800 dark:text-white outline-none focus:ring-2 focus:ring-primary-500"></textarea>
                </div>
                
                <div class="mt-8 flex items-center justify-end gap-3 pt-6 border-t border-slate-100 dark:border-slate-700">
                    <button type="button" onclick="closeAdminModal('create-maintenance-modal')" class="px-5 py-2.5 text-sm font-bold text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-700 rounded-xl transition-colors">
                        Cancel
                    </button>
                    <button type="submit" class="px-5 py-2.5 text-sm font-bold text-white bg-primary-600 hover:bg-primary-700 rounded-xl shadow-sm transition-colors flex items-center gap-2">
                        <i class="fa-solid fa-save"></i> Save Log
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Maintenance Modal -->
<div id="edit-maintenance-modal" class="hidden fixed inset-0 z-[100] items-center justify-center p-4 sm:p-6 opacity-0 transition-opacity duration-300">
    <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm" onclick="closeAdminModal('edit-maintenance-modal')"></div>
    <div class="admin-modal-panel relative w-full max-w-2xl bg-white dark:bg-slate-800 rounded-2xl shadow-2xl overflow-hidden ring-1 ring-slate-200 dark:ring-slate-700 transform scale-95 transition-transform duration-300 flex flex-col max-h-[90vh]">
        
        <!-- Header -->
        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100 dark:border-slate-700 shrink-0">
            <h3 class="text-lg font-bold text-slate-800 dark:text-white">Edit Maintenance Log</h3>
            <button onclick="closeAdminModal('edit-maintenance-modal')" class="text-slate-400 hover:text-slate-500 dark:hover:text-slate-300 transition-colors">
                <i class="fa-solid fa-xmark text-xl"></i>
            </button>
        </div>

        <!-- Body -->
        <div class="p-6 overflow-y-auto">
            <form id="edit-maintenance-form" method="POST" onsubmit="handleAjaxForm(this, 'edit-maintenance-modal')">
                @csrf
                @method('PUT')
                
                <div class="mb-6">
                    <div class="relative" data-custom-select>
                        <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1">Select Bus <span class="text-red-500">*</span></label>
                        <input type="hidden" id="edit_bus_id" name="bus_id" class="custom-select-input" required>
                        <button type="button" onclick="this.nextElementSibling.classList.toggle('hidden')" class="w-full flex items-center justify-between px-4 py-2 text-sm bg-white dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-xl text-slate-800 dark:text-white outline-none focus:ring-2 focus:ring-primary-500 cursor-pointer">
                            <span class="custom-select-text" id="edit_bus_text">Select a bus...</span>
                            <i class="fa-solid fa-chevron-down text-[10px] text-slate-400"></i>
                        </button>
                        <div class="custom-select-menu hidden mt-2 bg-slate-100 dark:bg-slate-700/50 rounded-xl p-1 flex flex-col gap-1 z-50 absolute left-0 right-0 max-h-60 overflow-y-auto">
                            @foreach($buses as $bus)
                            <div class="px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-200 dark:text-slate-200 dark:hover:bg-slate-600 rounded-lg cursor-pointer transition-colors" data-value="{{ $bus->id }}" onclick="selectCustomOption(this)">Bus #{{ $bus->bus_number }} ({{ $bus->plate_number }})</div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1">Task Title <span class="text-red-500">*</span></label>
                        <input type="text" id="edit_title" name="title" required class="w-full px-4 py-2 text-sm bg-white dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-xl text-slate-800 dark:text-white outline-none focus:ring-2 focus:ring-primary-500">
                    </div>
                    <div class="relative" data-custom-select>
                        <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1">Maintenance Type <span class="text-red-500">*</span></label>
                        <input type="hidden" id="edit_type" name="type" class="custom-select-input" required>
                        <button type="button" onclick="this.nextElementSibling.classList.toggle('hidden')" class="w-full flex items-center justify-between px-4 py-2 text-sm bg-white dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-xl text-slate-800 dark:text-white outline-none focus:ring-2 focus:ring-primary-500 cursor-pointer">
                            <span class="custom-select-text capitalize" id="edit_type_text">Routine</span>
                            <i class="fa-solid fa-chevron-down text-[10px] text-slate-400"></i>
                        </button>
                        <div class="custom-select-menu hidden mt-2 bg-slate-100 dark:bg-slate-700/50 rounded-xl p-1 flex flex-col gap-1 z-50 absolute left-0 right-0">
                            <div class="px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-200 dark:text-slate-200 dark:hover:bg-slate-600 rounded-lg cursor-pointer transition-colors" data-value="routine" onclick="selectCustomOption(this)">Routine</div>
                            <div class="px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-200 dark:text-slate-200 dark:hover:bg-slate-600 rounded-lg cursor-pointer transition-colors" data-value="repair" onclick="selectCustomOption(this)">Repair</div>
                            <div class="px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-200 dark:text-slate-200 dark:hover:bg-slate-600 rounded-lg cursor-pointer transition-colors" data-value="inspection" onclick="selectCustomOption(this)">Inspection</div>
                            <div class="px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-200 dark:text-slate-200 dark:hover:bg-slate-600 rounded-lg cursor-pointer transition-colors" data-value="emergency" onclick="selectCustomOption(this)">Emergency</div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1">Scheduled/Start Date <span class="text-red-500">*</span></label>
                        <input type="date" id="edit_maintenance_date" name="maintenance_date" required class="w-full px-4 py-2 text-sm bg-white dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-xl text-slate-800 dark:text-white outline-none focus:ring-2 focus:ring-primary-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1">Completed Date</label>
                        <input type="date" id="edit_completed_date" name="completed_date" class="w-full px-4 py-2 text-sm bg-white dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-xl text-slate-800 dark:text-white outline-none focus:ring-2 focus:ring-primary-500">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <div class="relative" data-custom-select>
                        <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1">Status <span class="text-red-500">*</span></label>
                        <input type="hidden" id="edit_status" name="status" class="custom-select-input" required>
                        <button type="button" onclick="this.nextElementSibling.classList.toggle('hidden')" class="w-full flex items-center justify-between px-4 py-2 text-sm bg-white dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-xl text-slate-800 dark:text-white outline-none focus:ring-2 focus:ring-primary-500 cursor-pointer">
                            <span class="custom-select-text capitalize" id="edit_status_text">Scheduled</span>
                            <i class="fa-solid fa-chevron-down text-[10px] text-slate-400"></i>
                        </button>
                        <div class="custom-select-menu hidden mt-2 bg-slate-100 dark:bg-slate-700/50 rounded-xl p-1 flex flex-col gap-1 z-50 absolute left-0 right-0">
                            <div class="px-4 py-2 text-sm font-semibold text-amber-700 hover:bg-amber-100 dark:text-amber-400 dark:hover:bg-amber-900/40 rounded-lg cursor-pointer transition-colors" data-value="scheduled" onclick="selectCustomOption(this)">Scheduled</div>
                            <div class="px-4 py-2 text-sm font-semibold text-blue-700 hover:bg-blue-100 dark:text-blue-400 dark:hover:bg-blue-900/40 rounded-lg cursor-pointer transition-colors" data-value="in_progress" onclick="selectCustomOption(this)">In Progress</div>
                            <div class="px-4 py-2 text-sm font-semibold text-emerald-700 hover:bg-emerald-100 dark:text-emerald-400 dark:hover:bg-emerald-900/40 rounded-lg cursor-pointer transition-colors" data-value="completed" onclick="selectCustomOption(this)">Completed</div>
                            <div class="px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-200 dark:text-slate-200 dark:hover:bg-slate-600 rounded-lg cursor-pointer transition-colors" data-value="cancelled" onclick="selectCustomOption(this)">Cancelled</div>
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1">Cost (PHP)</label>
                        <input type="number" id="edit_cost" name="cost" min="0" step="0.01" class="w-full px-4 py-2 text-sm bg-white dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-xl text-slate-800 dark:text-white outline-none focus:ring-2 focus:ring-primary-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1">Next Due Date</label>
                        <input type="date" id="edit_next_maintenance_due" name="next_maintenance_due" class="w-full px-4 py-2 text-sm bg-white dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-xl text-slate-800 dark:text-white outline-none focus:ring-2 focus:ring-primary-500">
                    </div>
                </div>

                <div class="mb-6">
                    <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1">Performed By (Mechanic/Shop)</label>
                    <input type="text" id="edit_performed_by" name="performed_by" class="w-full px-4 py-2 text-sm bg-white dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-xl text-slate-800 dark:text-white outline-none focus:ring-2 focus:ring-primary-500">
                </div>

                <div class="mb-6">
                    <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1">Parts Replaced</label>
                    <textarea id="edit_parts_replaced" name="parts_replaced" rows="2" class="w-full px-4 py-2 text-sm bg-white dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-xl text-slate-800 dark:text-white outline-none focus:ring-2 focus:ring-primary-500"></textarea>
                </div>

                <div class="mb-6">
                    <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1">Description / Notes</label>
                    <textarea id="edit_description" name="description" rows="3" class="w-full px-4 py-2 text-sm bg-white dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-xl text-slate-800 dark:text-white outline-none focus:ring-2 focus:ring-primary-500"></textarea>
                </div>
                
                <div class="mt-8 flex items-center justify-end gap-3 pt-6 border-t border-slate-100 dark:border-slate-700">
                    <button type="button" onclick="closeAdminModal('edit-maintenance-modal')" class="px-5 py-2.5 text-sm font-bold text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-700 rounded-xl transition-colors">
                        Cancel
                    </button>
                    <button type="submit" class="px-5 py-2.5 text-sm font-bold text-white bg-primary-600 hover:bg-primary-700 rounded-xl shadow-sm transition-colors flex items-center gap-2">
                        <i class="fa-solid fa-save"></i> Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openEditMaintenanceModal(log) {
    const form = document.getElementById('edit-maintenance-form');
    form.action = `/admin/maintenance/${log.id}`;
    
    document.getElementById('edit_bus_id').value = log.bus_id;
    document.getElementById('edit_bus_text').textContent = log.bus ? `Bus #${log.bus.bus_number} (${log.bus.plate_number})` : 'Unknown Bus';
    
    document.getElementById('edit_title').value = log.title;
    
    document.getElementById('edit_type').value = log.type;
    document.getElementById('edit_type_text').textContent = log.type.replace('_', ' ');
    
    document.getElementById('edit_status').value = log.status;
    document.getElementById('edit_status_text').textContent = log.status.replace('_', ' ');
    
    document.getElementById('edit_maintenance_date').value = log.maintenance_date ? log.maintenance_date.slice(0, 10) : '';
    document.getElementById('edit_completed_date').value = log.completed_date ? log.completed_date.slice(0, 10) : '';
    document.getElementById('edit_next_maintenance_due').value = log.next_maintenance_due ? log.next_maintenance_due.slice(0, 10) : '';
    
    document.getElementById('edit_cost').value = log.cost || '';
    document.getElementById('edit_performed_by').value = log.performed_by || '';
    document.getElementById('edit_parts_replaced').value = log.parts_replaced || '';
    document.getElementById('edit_description').value = log.description || '';
    
    openAdminModal('edit-maintenance-modal');
}
</script>
@endsection
