@extends('layouts.admin')

@section('title', 'Manage Notifications')

@section('content')
<div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
    <div>
        <h1 class="text-2xl font-bold text-slate-800 dark:text-white">System Notifications</h1>
        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Monitor system alerts, booking updates, and user communications.</p>
    </div>
</div>

<!-- Filters -->
<div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 p-4 mb-6">
    <form method="GET" action="{{ route('admin.notifications.index') }}" class="flex flex-col sm:flex-row gap-4 items-end">
        <div class="w-full sm:w-48">
            <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 mb-1">Status</label>
            <select name="status" class="w-full rounded-xl border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-white text-sm focus:ring-primary-500 focus:border-primary-500">
                <option value="">All Statuses</option>
                <option value="read" {{ request('status') === 'read' ? 'selected' : '' }}>Read</option>
                <option value="unread" {{ request('status') === 'unread' ? 'selected' : '' }}>Unread</option>
            </select>
        </div>
        <div>
            <button type="submit" class="px-5 py-2.5 bg-slate-800 hover:bg-slate-900 dark:bg-slate-700 dark:hover:bg-slate-600 text-white text-sm font-semibold rounded-xl transition-colors">
                Filter
            </button>
            @if(request()->hasAny(['status']))
                <a href="{{ route('admin.notifications.index') }}" class="ml-2 text-sm text-primary-600 hover:text-primary-700 dark:text-primary-400">Clear</a>
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
                    <th class="p-4 w-12 text-center">Status</th>
                    <th class="p-4">Notification Content</th>
                    <th class="p-4">Recipient</th>
                    <th class="p-4 text-right">Date</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                @forelse($notifications as $notification)
                <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors {{ !$notification->is_read ? 'bg-primary-50/50 dark:bg-primary-900/10' : '' }}">
                    <td class="p-4 text-center">
                        @if(!$notification->is_read)
                            <div class="w-3 h-3 rounded-full bg-primary-500 mx-auto shadow-[0_0_8px_rgba(59,130,246,0.6)]"></div>
                        @else
                            <div class="w-3 h-3 rounded-full bg-slate-300 dark:bg-slate-600 mx-auto"></div>
                        @endif
                    </td>
                    <td class="p-4">
                        <div class="font-bold text-slate-800 dark:text-slate-200">{{ $notification->title }}</div>
                        <p class="text-sm text-slate-600 dark:text-slate-400 mt-0.5">{{ $notification->message }}</p>
                    </td>
                    <td class="p-4">
                        <div class="text-sm font-medium text-slate-800 dark:text-slate-200">{{ $notification->user->name ?? 'System' }}</div>
                        <div class="text-xs text-slate-500">{{ $notification->type }}</div>
                    </td>
                    <td class="p-4 text-right">
                        <div class="text-sm text-slate-800 dark:text-slate-200">{{ $notification->created_at->format('M d, Y') }}</div>
                        <div class="text-xs text-slate-500 mt-0.5">{{ $notification->created_at->diffForHumans() }}</div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="p-8 text-center text-slate-500 dark:text-slate-400">
                        <div class="text-4xl mb-2"><i class="fa-solid fa-bell text-slate-300 dark:text-slate-600"></i></div>
                        <p>No notifications found matching your criteria.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($notifications->hasPages())
    <div class="p-4 border-t border-slate-100 dark:border-slate-700">
        {{ $notifications->links() }}
    </div>
    @endif
</div>
@endsection
