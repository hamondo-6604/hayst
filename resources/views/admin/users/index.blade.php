@extends('layouts.admin')

@section('title', 'Manage Users')

@section('content')
<div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
    <div>
        <h1 class="text-2xl font-bold text-slate-800 dark:text-white">Users</h1>
        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Manage system administrators, drivers, and customers.</p>
    </div>
</div>

<!-- Filters -->
<div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 p-4 mb-6">
    <form method="GET" action="{{ route('admin.users.index') }}" class="flex flex-col sm:flex-row gap-4 items-end">
        <div class="flex-1">
            <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 mb-1">Search Users</label>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name, email, or phone..." 
                   class="w-full px-4 py-2 rounded-xl border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-white text-sm focus:ring-primary-500 focus:border-primary-500">
        </div>
        <div class="w-full sm:w-48">
            <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 mb-1">Role</label>
            <select name="role" class="w-full px-4 py-2 rounded-xl border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-white text-sm focus:ring-primary-500 focus:border-primary-500">
                <option value="">All Roles</option>
                <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                <option value="driver" {{ request('role') === 'driver' ? 'selected' : '' }}>Driver</option>
                <option value="customer" {{ request('role') === 'customer' ? 'selected' : '' }}>Customer</option>
            </select>
        </div>
        <div class="w-full sm:w-48">
            <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 mb-1">Status</label>
            <select name="status" class="w-full px-4 py-2 rounded-xl border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-white text-sm focus:ring-primary-500 focus:border-primary-500">
                <option value="">All Statuses</option>
                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                <option value="suspended" {{ request('status') === 'suspended' ? 'selected' : '' }}>Suspended</option>
            </select>
        </div>
        <div>
            <button type="submit" class="px-5 py-2.5 bg-slate-800 hover:bg-slate-900 dark:bg-slate-700 dark:hover:bg-slate-600 text-white text-sm font-semibold rounded-xl transition-colors">
                Filter
            </button>
            @if(request()->hasAny(['search', 'role', 'status']))
                <a href="{{ route('admin.users.index') }}" class="ml-2 text-sm text-primary-600 hover:text-primary-700 dark:text-primary-400">Clear</a>
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
                    <th class="p-4">User</th>
                    <th class="p-4">Contact Info</th>
                    <th class="p-4">Role & Type</th>
                    <th class="p-4">Joined Date</th>
                    <th class="p-4">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                @forelse($users as $user)
                <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors">
                    <td class="p-4 flex items-center gap-3">
                        @if($user->image_url)
                            <img src="{{ asset('storage/' . $user->image_url) }}" alt="{{ $user->name }}" class="w-10 h-10 rounded-full object-cover border border-slate-200 dark:border-slate-700">
                        @else
                            <div class="w-10 h-10 rounded-full bg-primary-100 dark:bg-slate-700 text-primary-700 dark:text-primary-400 flex items-center justify-center font-bold text-sm">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                        @endif
                        <div class="font-bold text-slate-800 dark:text-slate-200">{{ $user->name }}</div>
                    </td>
                    <td class="p-4">
                        <div class="text-sm font-medium text-slate-800 dark:text-slate-200">{{ $user->email }}</div>
                        <div class="text-xs text-slate-500 mt-0.5">{{ $user->phone ?? '—' }}</div>
                    </td>
                    <td class="p-4">
                        <div class="text-sm font-bold text-slate-800 dark:text-slate-200 capitalize">{{ $user->role }}</div>
                        @if($user->discountType)
                            <div class="text-xs text-primary-600 dark:text-primary-400 mt-0.5"><i class="fa-solid fa-tag text-[10px]"></i> {{ $user->discountType->display_name }}</div>
                        @elseif($user->userType)
                            <div class="text-xs text-slate-500 mt-0.5">{{ $user->userType->display_name }}</div>
                        @endif
                    </td>
                    <td class="p-4">
                        <div class="text-sm text-slate-800 dark:text-slate-200">{{ $user->created_at->format('M d, Y') }}</div>
                        <div class="text-xs text-slate-500 mt-0.5">{{ $user->created_at->diffForHumans() }}</div>
                    </td>
                    <td class="p-4">
                        @php
                            $badgeColors = [
                                'active' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400',
                                'inactive' => 'bg-slate-100 text-slate-700 dark:bg-slate-700 dark:text-slate-300',
                                'suspended' => 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400',
                            ];
                            $colorClass = $badgeColors[$user->status] ?? 'bg-slate-100 text-slate-700';
                        @endphp
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $colorClass }}">
                            {{ ucfirst($user->status) }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="p-8 text-center text-slate-500 dark:text-slate-400">
                        <div class="text-4xl mb-2"><i class="fa-solid fa-users text-slate-300 dark:text-slate-600"></i></div>
                        <p>No users found matching your criteria.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($users->hasPages())
    <div class="p-4 border-t border-slate-100 dark:border-slate-700">
        {{ $users->links() }}
    </div>
    @endif
</div>
@endsection
