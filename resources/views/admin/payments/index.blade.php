@extends('layouts.admin')

@section('title', 'Manage Payments')

@section('content')
<div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
    <div>
        <h1 class="text-2xl font-bold text-slate-800 dark:text-white">Payments</h1>
        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Review all transactions and payment statuses.</p>
    </div>
</div>

<!-- Filters -->
<div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 p-4 mb-6">
    <form method="GET" action="{{ route('admin.payments.index') }}" class="flex flex-col sm:flex-row gap-4 items-end">
        <div class="flex-1">
            <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 mb-1">Search Payments</label>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Transaction ID, Booking Ref, or Customer Name..." 
                   class="w-full px-4 py-2 rounded-xl border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-white text-sm focus:ring-primary-500 focus:border-primary-500">
        </div>
        <div class="w-full sm:w-48 relative" data-custom-select>
            <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 mb-1">Status</label>
            <input type="hidden" name="status" value="{{ request('status') }}" class="custom-select-input">
            
            <button type="button" onclick="this.nextElementSibling.classList.toggle('hidden')" class="w-full px-4 py-2 flex items-center justify-between rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-white text-sm focus:ring-primary-500 focus:border-primary-500 transition-colors cursor-pointer">
                <span class="custom-select-text">
                    @if(request('status') === 'paid') Paid
                    @elseif(request('status') === 'pending') Pending
                    @elseif(request('status') === 'failed') Failed
                    @elseif(request('status') === 'refunded') Refunded
                    @else All Statuses
                    @endif
                </span>
                <i class="fa-solid fa-chevron-down text-[10px] text-slate-400"></i>
            </button>
            
            <div class="custom-select-menu hidden absolute left-0 right-0 top-full mt-2 bg-white dark:bg-slate-800 rounded-xl shadow-xl border border-slate-100 dark:border-slate-700 py-1.5 z-50 overflow-hidden">
                <div class="px-4 py-2.5 text-sm font-medium text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-700 cursor-pointer transition-colors" data-value="" onclick="selectCustomOption(this)">All Statuses</div>
                <div class="px-4 py-2.5 text-sm font-medium text-emerald-700 bg-emerald-50 dark:bg-emerald-900/20 dark:text-emerald-400 hover:bg-emerald-100 dark:hover:bg-emerald-900/40 cursor-pointer transition-colors" data-value="paid" onclick="selectCustomOption(this)">Paid</div>
                <div class="px-4 py-2.5 text-sm font-medium text-amber-700 bg-amber-50 dark:bg-amber-900/20 dark:text-amber-400 hover:bg-amber-100 dark:hover:bg-amber-900/40 cursor-pointer transition-colors" data-value="pending" onclick="selectCustomOption(this)">Pending</div>
                <div class="px-4 py-2.5 text-sm font-medium text-red-700 bg-red-50 dark:bg-red-900/20 dark:text-red-400 hover:bg-red-100 dark:hover:bg-red-900/40 cursor-pointer transition-colors" data-value="failed" onclick="selectCustomOption(this)">Failed</div>
                <div class="px-4 py-2.5 text-sm font-medium text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-700 cursor-pointer transition-colors" data-value="refunded" onclick="selectCustomOption(this)">Refunded</div>
            </div>
        </div>
        <div>
            <button type="submit" class="px-5 py-2.5 bg-slate-800 hover:bg-slate-900 dark:bg-slate-700 dark:hover:bg-slate-600 text-white text-sm font-semibold rounded-xl transition-colors">
                Filter
            </button>
            @if(request()->hasAny(['search', 'status']))
                <a href="{{ route('admin.payments.index') }}" class="ml-2 text-sm text-primary-600 hover:text-primary-700 dark:text-primary-400">Clear</a>
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
                    <th class="p-4">Transaction ID</th>
                    <th class="p-4">Booking & Customer</th>
                    <th class="p-4">Method</th>
                    <th class="p-4 text-right">Amount</th>
                    <th class="p-4">Status</th>
                    <th class="p-4">Date</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                @forelse($payments as $payment)
                <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors">
                    <td class="p-4 font-mono text-sm text-slate-800 dark:text-slate-200">
                        {{ $payment->transaction_id ?? '—' }}
                    </td>
                    <td class="p-4">
                        <div class="font-bold text-primary-600 dark:text-primary-400 hover:underline">
                            @if($payment->booking)
                                <a href="{{ route('admin.bookings.show', $payment->booking) }}">{{ $payment->booking->booking_reference }}</a>
                            @else
                                <span class="text-slate-400 italic">No Booking</span>
                            @endif
                        </div>
                        <div class="text-xs text-slate-500 mt-0.5">{{ $payment->booking->user->name ?? 'Guest' }}</div>
                    </td>
                    <td class="p-4 text-sm text-slate-800 dark:text-slate-200 capitalize">
                        {{ $payment->payment_method ?? '—' }}
                    </td>
                    <td class="p-4 text-right font-bold text-slate-800 dark:text-slate-200">
                        {{ $payment->formatted_amount }}
                    </td>
                    <td class="p-4">
                        @php
                            $badgeColors = [
                                'paid' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400',
                                'pending' => 'bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400',
                                'failed' => 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400',
                                'refunded' => 'bg-slate-100 text-slate-700 dark:bg-slate-700 dark:text-slate-300',
                            ];
                            $colorClass = $badgeColors[$payment->status] ?? 'bg-slate-100 text-slate-700';
                        @endphp
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $colorClass }}">
                            {{ ucfirst($payment->status) }}
                        </span>
                    </td>
                    <td class="p-4 text-sm text-slate-500 dark:text-slate-400">
                        {{ $payment->created_at->format('M d, Y h:i A') }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="p-8 text-center text-slate-500 dark:text-slate-400">
                        <div class="text-4xl mb-2"><i class="fa-solid fa-receipt text-slate-300 dark:text-slate-600"></i></div>
                        <p>No payments found matching your criteria.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($payments->hasPages())
    <div class="p-4 border-t border-slate-100 dark:border-slate-700">
        {{ $payments->links() }}
    </div>
    @endif
</div>
@endsection
