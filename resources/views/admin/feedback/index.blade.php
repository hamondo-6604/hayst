@extends('layouts.admin')

@section('title', 'Manage Feedback')

@section('content')
<div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
    <div>
        <h1 class="text-2xl font-bold text-slate-800 dark:text-white">Customer Feedback</h1>
        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Review ratings, comments, and respond to customers.</p>
    </div>
</div>

<!-- Filters -->
<div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 p-4 mb-6">
    <form method="GET" action="{{ route('admin.feedback.index') }}" class="flex flex-col sm:flex-row gap-4 items-end">
        <div class="w-full sm:w-48">
            <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 mb-1">Rating</label>
            <select name="rating" class="w-full rounded-xl border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-white text-sm focus:ring-primary-500 focus:border-primary-500">
                <option value="">All Ratings</option>
                <option value="5" {{ request('rating') == '5' ? 'selected' : '' }}>5 Stars</option>
                <option value="4" {{ request('rating') == '4' ? 'selected' : '' }}>4 Stars</option>
                <option value="3" {{ request('rating') == '3' ? 'selected' : '' }}>3 Stars</option>
                <option value="2" {{ request('rating') == '2' ? 'selected' : '' }}>2 Stars</option>
                <option value="1" {{ request('rating') == '1' ? 'selected' : '' }}>1 Star</option>
            </select>
        </div>
        <div class="w-full sm:w-48">
            <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 mb-1">Status</label>
            <select name="status" class="w-full rounded-xl border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-white text-sm focus:ring-primary-500 focus:border-primary-500">
                <option value="">All Statuses</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending Review</option>
                <option value="reviewed" {{ request('status') === 'reviewed' ? 'selected' : '' }}>Reviewed</option>
            </select>
        </div>
        <div>
            <button type="submit" class="px-5 py-2.5 bg-slate-800 hover:bg-slate-900 dark:bg-slate-700 dark:hover:bg-slate-600 text-white text-sm font-semibold rounded-xl transition-colors">
                Filter
            </button>
            @if(request()->hasAny(['rating', 'status']))
                <a href="{{ route('admin.feedback.index') }}" class="ml-2 text-sm text-primary-600 hover:text-primary-700 dark:text-primary-400">Clear</a>
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
                    <th class="p-4">Customer</th>
                    <th class="p-4">Trip Details</th>
                    <th class="p-4">Rating & Feedback</th>
                    <th class="p-4">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                @forelse($feedbacks as $feedback)
                <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors">
                    <td class="p-4">
                        <div class="font-bold text-slate-800 dark:text-slate-200">{{ $feedback->user->name ?? 'Anonymous' }}</div>
                        <div class="text-xs text-slate-500 mt-0.5">{{ $feedback->created_at->format('M d, Y') }}</div>
                    </td>
                    <td class="p-4">
                        @if($feedback->trip)
                            <div class="text-sm font-medium text-primary-600 dark:text-primary-400 hover:underline">
                                <a href="{{ route('admin.trips.show', $feedback->trip) }}">Trip #{{ $feedback->trip->id }}</a>
                            </div>
                        @else
                            <span class="text-slate-400 italic">General Feedback</span>
                        @endif
                    </td>
                    <td class="p-4 max-w-md">
                        <div class="text-amber-400 text-sm mb-1 tracking-widest">{{ $feedback->stars }}</div>
                        <div class="font-semibold text-slate-800 dark:text-slate-200 text-sm">{{ $feedback->subject ?? 'No Subject' }}</div>
                        <p class="text-sm text-slate-600 dark:text-slate-400 mt-1 line-clamp-2">{{ $feedback->comment }}</p>
                    </td>
                    <td class="p-4">
                        @if($feedback->status === 'reviewed')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400">
                                Reviewed
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400">
                                Pending
                            </span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="p-8 text-center text-slate-500 dark:text-slate-400">
                        <div class="text-4xl mb-2"><i class="fa-solid fa-comments text-slate-300 dark:text-slate-600"></i></div>
                        <p>No feedback found matching your criteria.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($feedbacks->hasPages())
    <div class="p-4 border-t border-slate-100 dark:border-slate-700">
        {{ $feedbacks->links() }}
    </div>
    @endif
</div>
@endsection
