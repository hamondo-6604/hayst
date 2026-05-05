@extends('layouts.admin')

@section('title', 'Manage Promotions')

@section('content')
<div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
    <div>
        <h1 class="text-2xl font-bold text-slate-800 dark:text-white">Promotions</h1>
        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Manage promo codes, discounts, and marketing campaigns.</p>
    </div>
    <button onclick="openAdminModal('create-promo-modal')" class="px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white text-sm font-bold rounded-xl shadow-sm transition-colors flex items-center gap-2">
        <i class="fa-solid fa-plus"></i> Add Promotion
    </button>
</div>

<!-- Filters -->
<div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 p-4 mb-6">
    <form method="GET" action="{{ route('admin.promotions.index') }}" class="flex flex-col sm:flex-row gap-4 items-end">
        <div class="flex-1">
            <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 mb-1">Search Promotions</label>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Promo Code or Name..." 
                   class="w-full px-4 py-2 rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-white text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none transition-colors">
        </div>
        <div class="w-full sm:w-48 relative" data-custom-select>
            <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 mb-1">Status</label>
            <input type="hidden" name="status" value="{{ request('status') }}" class="custom-select-input">
            
            <button type="button" onclick="this.nextElementSibling.classList.toggle('hidden')" class="w-full px-4 py-2 flex items-center justify-between rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-white text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors cursor-pointer">
                <span class="custom-select-text">
                    @if(request('status') === 'active') Active
                    @elseif(request('status') === 'inactive') Inactive
                    @else All Statuses
                    @endif
                </span>
                <i class="fa-solid fa-chevron-down text-[10px] text-slate-400"></i>
            </button>
            
            <div class="custom-select-menu hidden absolute left-0 right-0 top-full mt-2 bg-white dark:bg-slate-800 rounded-xl shadow-xl border border-slate-100 dark:border-slate-700 py-1.5 z-50 overflow-hidden">
                <div class="px-4 py-2.5 text-sm font-medium text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-700 cursor-pointer transition-colors" data-value="" onclick="selectCustomOption(this)">All Statuses</div>
                <div class="px-4 py-2.5 text-sm font-medium text-emerald-700 bg-emerald-50 dark:bg-emerald-900/20 dark:text-emerald-400 hover:bg-emerald-100 dark:hover:bg-emerald-900/40 cursor-pointer transition-colors" data-value="active" onclick="selectCustomOption(this)">Active</div>
                <div class="px-4 py-2.5 text-sm font-medium text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-700 cursor-pointer transition-colors" data-value="inactive" onclick="selectCustomOption(this)">Inactive</div>
            </div>
        </div>
        <div>
            <button type="submit" class="px-5 py-2.5 bg-slate-800 hover:bg-slate-900 dark:bg-slate-700 dark:hover:bg-slate-600 text-white text-sm font-semibold rounded-xl transition-colors">
                Filter
            </button>
            @if(request()->hasAny(['search', 'status']))
                <a href="{{ route('admin.promotions.index') }}" class="ml-2 text-sm text-primary-600 hover:text-primary-700 dark:text-primary-400">Clear</a>
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
                    <th class="p-4">Promo Code & Name</th>
                    <th class="p-4">Discount</th>
                    <th class="p-4">Usage</th>
                    <th class="p-4">Validity</th>
                    <th class="p-4">Status</th>
                    <th class="p-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                @forelse($promotions as $promo)
                <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors">
                    <td class="p-4">
                        <div class="font-bold text-primary-600 dark:text-primary-400 font-mono tracking-wide bg-primary-50 dark:bg-primary-900/20 px-2 py-0.5 rounded inline-block mb-1">
                            {{ $promo->code }}
                        </div>
                        <div class="text-sm text-slate-800 dark:text-slate-200">{{ $promo->name }}</div>
                    </td>
                    <td class="p-4 font-bold text-slate-800 dark:text-slate-200">
                        @if($promo->discount_type === 'percent')
                            {{ round($promo->discount_value) }}% OFF
                        @else
                            ₱{{ number_format($promo->discount_value, 2) }} OFF
                        @endif
                    </td>
                    <td class="p-4">
                        <div class="text-sm font-medium text-slate-800 dark:text-slate-200">
                            {{ $promo->used_count }} / {{ $promo->max_uses ?? '∞' }}
                        </div>
                    </td>
                    <td class="p-4">
                        <div class="text-xs text-slate-500">
                            @if($promo->starts_at)
                                Starts: {{ $promo->starts_at->format('M d, Y') }}
                            @endif
                        </div>
                        <div class="text-xs text-slate-500 mt-0.5">
                            @if($promo->expires_at)
                                <span class="{{ $promo->expires_at->isPast() ? 'text-red-500 font-semibold' : '' }}">
                                    Ends: {{ $promo->expires_at->format('M d, Y') }}
                                </span>
                            @else
                                Never Expires
                            @endif
                        </div>
                    </td>
                    <td class="p-4">
                        @if($promo->is_active && (!$promo->expires_at || $promo->expires_at->isFuture()))
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
                            <button type="button" onclick="openEditPromoModal({{ json_encode($promo) }})" class="w-8 h-8 rounded-lg bg-blue-50 hover:bg-blue-100 dark:bg-blue-900/20 dark:hover:bg-blue-900/40 text-blue-600 dark:text-blue-400 flex items-center justify-center transition-colors" title="Edit">
                                <i class="fa-solid fa-pen text-sm"></i>
                            </button>
                            <form action="{{ route('admin.promotions.destroy', $promo) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this promotion?');">
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
                        <div class="text-4xl mb-2"><i class="fa-solid fa-tag text-slate-300 dark:text-slate-600"></i></div>
                        <p>No promotions found matching your criteria.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($promotions->hasPages())
    <div class="p-4 border-t border-slate-100 dark:border-slate-700">
        {{ $promotions->links() }}
    </div>
    @endif
</div>

<!-- Create Promotion Modal -->
<div id="create-promo-modal" class="hidden fixed inset-0 z-[100] items-center justify-center p-4 sm:p-6 opacity-0 transition-opacity duration-300">
    <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm" onclick="closeAdminModal('create-promo-modal')"></div>
    <div class="admin-modal-panel relative w-full max-w-2xl bg-white dark:bg-slate-800 rounded-2xl shadow-2xl overflow-hidden ring-1 ring-slate-200 dark:ring-slate-700 transform scale-95 transition-transform duration-300 flex flex-col max-h-[90vh]">
        
        <!-- Header -->
        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100 dark:border-slate-700 shrink-0">
            <h3 class="text-lg font-bold text-slate-800 dark:text-white">Add New Promotion</h3>
            <button onclick="closeAdminModal('create-promo-modal')" class="text-slate-400 hover:text-slate-500 dark:hover:text-slate-300 transition-colors">
                <i class="fa-solid fa-xmark text-xl"></i>
            </button>
        </div>

        <!-- Body -->
        <div class="p-6 overflow-y-auto">
            <form id="create-promo-form" action="{{ route('admin.promotions.store') }}" method="POST" onsubmit="handleAjaxForm(this, 'create-promo-modal', null, event)">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1">Promo Code <span class="text-red-500">*</span></label>
                        <input type="text" name="code" required class="w-full px-4 py-2 text-sm bg-white dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-xl text-slate-800 dark:text-white outline-none focus:ring-2 focus:ring-primary-500 font-mono uppercase">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1">Campaign Name <span class="text-red-500">*</span></label>
                        <input type="text" name="name" required class="w-full px-4 py-2 text-sm bg-white dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-xl text-slate-800 dark:text-white outline-none focus:ring-2 focus:ring-primary-500">
                    </div>
                </div>

                <div class="mb-6">
                    <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1">Description</label>
                    <textarea name="description" rows="2" class="w-full px-4 py-2 text-sm bg-white dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-xl text-slate-800 dark:text-white outline-none focus:ring-2 focus:ring-primary-500"></textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div class="relative" data-custom-select>
                        <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1">Discount Type <span class="text-red-500">*</span></label>
                        <input type="hidden" name="discount_type" value="percent" class="custom-select-input" required>
                        <button type="button" onclick="this.nextElementSibling.classList.toggle('hidden')" class="w-full flex items-center justify-between px-4 py-2 text-sm bg-white dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-xl text-slate-800 dark:text-white outline-none focus:ring-2 focus:ring-primary-500 cursor-pointer">
                            <span class="custom-select-text">Percentage (%)</span>
                            <i class="fa-solid fa-chevron-down text-[10px] text-slate-400"></i>
                        </button>
                        <div class="custom-select-menu hidden mt-2 bg-slate-100 dark:bg-slate-700/50 rounded-xl p-1 flex flex-col gap-1 z-50 absolute left-0 right-0">
                            <div class="px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-200 dark:text-slate-200 dark:hover:bg-slate-600 rounded-lg cursor-pointer transition-colors" data-value="percent" onclick="selectCustomOption(this)">Percentage (%)</div>
                            <div class="px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-200 dark:text-slate-200 dark:hover:bg-slate-600 rounded-lg cursor-pointer transition-colors" data-value="fixed" onclick="selectCustomOption(this)">Fixed Amount (₱)</div>
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1">Discount Value <span class="text-red-500">*</span></label>
                        <input type="number" name="discount_value" step="0.01" min="0" required class="w-full px-4 py-2 text-sm bg-white dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-xl text-slate-800 dark:text-white outline-none focus:ring-2 focus:ring-primary-500">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1">Min. Fare (₱)</label>
                        <input type="number" name="minimum_fare" step="0.01" min="0" class="w-full px-4 py-2 text-sm bg-white dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-xl text-slate-800 dark:text-white outline-none focus:ring-2 focus:ring-primary-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1">Max. Discount Cap (₱)</label>
                        <input type="number" name="maximum_discount" step="0.01" min="0" class="w-full px-4 py-2 text-sm bg-white dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-xl text-slate-800 dark:text-white outline-none focus:ring-2 focus:ring-primary-500">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1">Max Total Uses</label>
                        <input type="number" name="max_uses" min="1" placeholder="Leave empty for unlimited" class="w-full px-4 py-2 text-sm bg-white dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-xl text-slate-800 dark:text-white outline-none focus:ring-2 focus:ring-primary-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1">Max Uses Per User <span class="text-red-500">*</span></label>
                        <input type="number" name="max_uses_per_user" value="1" min="1" required class="w-full px-4 py-2 text-sm bg-white dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-xl text-slate-800 dark:text-white outline-none focus:ring-2 focus:ring-primary-500">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1">Starts At</label>
                        <input type="datetime-local" name="starts_at" class="w-full px-4 py-2 text-sm bg-white dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-xl text-slate-800 dark:text-white outline-none focus:ring-2 focus:ring-primary-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1">Expires At</label>
                        <input type="datetime-local" name="expires_at" class="w-full px-4 py-2 text-sm bg-white dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-xl text-slate-800 dark:text-white outline-none focus:ring-2 focus:ring-primary-500">
                    </div>
                </div>

                <div class="flex items-center">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <div class="relative">
                            <input type="checkbox" name="is_active" value="1" checked class="sr-only peer">
                            <div class="w-10 h-6 bg-slate-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-primary-500 rounded-full peer dark:bg-slate-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-primary-500"></div>
                        </div>
                        <span class="text-sm font-bold text-slate-700 dark:text-slate-300">Active</span>
                    </label>
                </div>
                
                <div class="mt-8 flex items-center justify-end gap-3 pt-6 border-t border-slate-100 dark:border-slate-700">
                    <button type="button" onclick="closeAdminModal('create-promo-modal')" class="px-5 py-2.5 text-sm font-bold text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-700 rounded-xl transition-colors">
                        Cancel
                    </button>
                    <button type="submit" class="px-5 py-2.5 text-sm font-bold text-white bg-primary-600 hover:bg-primary-700 rounded-xl shadow-sm transition-colors flex items-center gap-2">
                        <i class="fa-solid fa-save"></i> Save Promotion
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Promotion Modal -->
<div id="edit-promo-modal" class="hidden fixed inset-0 z-[100] items-center justify-center p-4 sm:p-6 opacity-0 transition-opacity duration-300">
    <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm" onclick="closeAdminModal('edit-promo-modal')"></div>
    <div class="admin-modal-panel relative w-full max-w-2xl bg-white dark:bg-slate-800 rounded-2xl shadow-2xl overflow-hidden ring-1 ring-slate-200 dark:ring-slate-700 transform scale-95 transition-transform duration-300 flex flex-col max-h-[90vh]">
        
        <!-- Header -->
        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100 dark:border-slate-700 shrink-0">
            <h3 class="text-lg font-bold text-slate-800 dark:text-white">Edit Promotion</h3>
            <button onclick="closeAdminModal('edit-promo-modal')" class="text-slate-400 hover:text-slate-500 dark:hover:text-slate-300 transition-colors">
                <i class="fa-solid fa-xmark text-xl"></i>
            </button>
        </div>

        <!-- Body -->
        <div class="p-6 overflow-y-auto">
            <form id="edit-promo-form" method="POST" onsubmit="handleAjaxForm(this, 'edit-promo-modal', null, event)">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1">Promo Code <span class="text-red-500">*</span></label>
                        <input type="text" id="edit_code" name="code" required class="w-full px-4 py-2 text-sm bg-white dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-xl text-slate-800 dark:text-white outline-none focus:ring-2 focus:ring-primary-500 font-mono uppercase">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1">Campaign Name <span class="text-red-500">*</span></label>
                        <input type="text" id="edit_name" name="name" required class="w-full px-4 py-2 text-sm bg-white dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-xl text-slate-800 dark:text-white outline-none focus:ring-2 focus:ring-primary-500">
                    </div>
                </div>

                <div class="mb-6">
                    <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1">Description</label>
                    <textarea id="edit_description" name="description" rows="2" class="w-full px-4 py-2 text-sm bg-white dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-xl text-slate-800 dark:text-white outline-none focus:ring-2 focus:ring-primary-500"></textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div class="relative" data-custom-select>
                        <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1">Discount Type <span class="text-red-500">*</span></label>
                        <input type="hidden" id="edit_discount_type" name="discount_type" class="custom-select-input" required>
                        <button type="button" onclick="this.nextElementSibling.classList.toggle('hidden')" class="w-full flex items-center justify-between px-4 py-2 text-sm bg-white dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-xl text-slate-800 dark:text-white outline-none focus:ring-2 focus:ring-primary-500 cursor-pointer">
                            <span class="custom-select-text" id="edit_discount_type_text"></span>
                            <i class="fa-solid fa-chevron-down text-[10px] text-slate-400"></i>
                        </button>
                        <div class="custom-select-menu hidden mt-2 bg-slate-100 dark:bg-slate-700/50 rounded-xl p-1 flex flex-col gap-1 z-50 absolute left-0 right-0">
                            <div class="px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-200 dark:text-slate-200 dark:hover:bg-slate-600 rounded-lg cursor-pointer transition-colors" data-value="percent" onclick="selectCustomOption(this)">Percentage (%)</div>
                            <div class="px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-200 dark:text-slate-200 dark:hover:bg-slate-600 rounded-lg cursor-pointer transition-colors" data-value="fixed" onclick="selectCustomOption(this)">Fixed Amount (₱)</div>
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1">Discount Value <span class="text-red-500">*</span></label>
                        <input type="number" id="edit_discount_value" name="discount_value" step="0.01" min="0" required class="w-full px-4 py-2 text-sm bg-white dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-xl text-slate-800 dark:text-white outline-none focus:ring-2 focus:ring-primary-500">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1">Min. Fare (₱)</label>
                        <input type="number" id="edit_minimum_fare" name="minimum_fare" step="0.01" min="0" class="w-full px-4 py-2 text-sm bg-white dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-xl text-slate-800 dark:text-white outline-none focus:ring-2 focus:ring-primary-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1">Max. Discount Cap (₱)</label>
                        <input type="number" id="edit_maximum_discount" name="maximum_discount" step="0.01" min="0" class="w-full px-4 py-2 text-sm bg-white dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-xl text-slate-800 dark:text-white outline-none focus:ring-2 focus:ring-primary-500">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1">Max Total Uses</label>
                        <input type="number" id="edit_max_uses" name="max_uses" min="1" placeholder="Leave empty for unlimited" class="w-full px-4 py-2 text-sm bg-white dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-xl text-slate-800 dark:text-white outline-none focus:ring-2 focus:ring-primary-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1">Max Uses Per User <span class="text-red-500">*</span></label>
                        <input type="number" id="edit_max_uses_per_user" name="max_uses_per_user" min="1" required class="w-full px-4 py-2 text-sm bg-white dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-xl text-slate-800 dark:text-white outline-none focus:ring-2 focus:ring-primary-500">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1">Starts At</label>
                        <input type="datetime-local" id="edit_starts_at" name="starts_at" class="w-full px-4 py-2 text-sm bg-white dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-xl text-slate-800 dark:text-white outline-none focus:ring-2 focus:ring-primary-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1">Expires At</label>
                        <input type="datetime-local" id="edit_expires_at" name="expires_at" class="w-full px-4 py-2 text-sm bg-white dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-xl text-slate-800 dark:text-white outline-none focus:ring-2 focus:ring-primary-500">
                    </div>
                </div>

                <div class="flex items-center">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <div class="relative">
                            <input type="checkbox" id="edit_is_active" name="is_active" value="1" class="sr-only peer">
                            <div class="w-10 h-6 bg-slate-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-primary-500 rounded-full peer dark:bg-slate-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-primary-500"></div>
                        </div>
                        <span class="text-sm font-bold text-slate-700 dark:text-slate-300">Active</span>
                    </label>
                </div>
                
                <div class="mt-8 flex items-center justify-end gap-3 pt-6 border-t border-slate-100 dark:border-slate-700">
                    <button type="button" onclick="closeAdminModal('edit-promo-modal')" class="px-5 py-2.5 text-sm font-bold text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-700 rounded-xl transition-colors">
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
function openEditPromoModal(promo) {
    const form = document.getElementById('edit-promo-form');
    form.action = `/admin/promotions/${promo.id}`;
    
    document.getElementById('edit_code').value = promo.code;
    document.getElementById('edit_name').value = promo.name;
    document.getElementById('edit_description').value = promo.description || '';
    
    document.getElementById('edit_discount_type').value = promo.discount_type;
    document.getElementById('edit_discount_type_text').textContent = promo.discount_type === 'percent' ? 'Percentage (%)' : 'Fixed Amount (₱)';
    
    document.getElementById('edit_discount_value').value = parseFloat(promo.discount_value).toFixed(2);
    document.getElementById('edit_minimum_fare').value = promo.minimum_fare ? parseFloat(promo.minimum_fare).toFixed(2) : '';
    document.getElementById('edit_maximum_discount').value = promo.maximum_discount ? parseFloat(promo.maximum_discount).toFixed(2) : '';
    
    document.getElementById('edit_max_uses').value = promo.max_uses || '';
    document.getElementById('edit_max_uses_per_user').value = promo.max_uses_per_user || 1;
    
    document.getElementById('edit_starts_at').value = promo.starts_at ? promo.starts_at.slice(0, 16) : '';
    document.getElementById('edit_expires_at').value = promo.expires_at ? promo.expires_at.slice(0, 16) : '';
    
    document.getElementById('edit_is_active').checked = promo.is_active;
    
    openAdminModal('edit-promo-modal');
}
</script>
@endsection
