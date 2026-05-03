@extends('layouts.admin')

@section('title', 'Manage Promotions')

@section('content')
<div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
    <div>
        <h1 class="text-2xl font-bold text-slate-800 dark:text-white">Promotions</h1>
        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Manage promo codes, discounts, and marketing campaigns.</p>
    </div>
</div>

<!-- Filters -->
<div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 p-4 mb-6">
    <form method="GET" action="{{ route('admin.promotions.index') }}" class="flex flex-col sm:flex-row gap-4 items-end">
        <div class="flex-1">
            <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 mb-1">Search Promotions</label>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Promo Code or Name..." 
                   class="w-full px-4 py-2 rounded-xl border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-white text-sm focus:ring-primary-500 focus:border-primary-500">
        </div>
        <div class="w-full sm:w-48">
            <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 mb-1">Status</label>
            <select name="status" class="w-full px-4 py-2 rounded-xl border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-white text-sm focus:ring-primary-500 focus:border-primary-500">
                <option value="">All Statuses</option>
                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
            </select>
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
@endsection
