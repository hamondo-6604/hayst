@extends('layouts.admin')

@section('title', 'Manage Roles & Permissions')

@section('content')
<div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
    <div>
        <h1 class="text-2xl font-bold text-slate-800 dark:text-white">Roles & Discount Types</h1>
        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Manage system access roles and passenger discount categories.</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    
    <!-- User Types Table -->
    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 overflow-hidden">
        <div class="p-5 border-b border-slate-100 dark:border-slate-700">
            <h2 class="text-lg font-bold text-slate-800 dark:text-white">User Types</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 dark:bg-slate-700/50 border-b border-slate-100 dark:border-slate-700 text-xs uppercase tracking-wider text-slate-500 dark:text-slate-400 font-semibold">
                        <th class="p-4">Type Name</th>
                        <th class="p-4">System Key</th>
                        <th class="p-4">Assigned Users</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                    @forelse($userTypes as $type)
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors">
                        <td class="p-4">
                            <div class="font-bold text-slate-800 dark:text-slate-200">{{ $type->display_name }}</div>
                            <div class="text-xs text-slate-500 mt-0.5 truncate max-w-[200px]">{{ $type->description }}</div>
                        </td>
                        <td class="p-4">
                            <span class="font-mono text-xs text-primary-600 dark:text-primary-400 bg-primary-50 dark:bg-primary-900/20 px-2 py-0.5 rounded">{{ $type->name }}</span>
                        </td>
                        <td class="p-4 font-semibold text-slate-800 dark:text-slate-200">
                            {{ $type->users_count }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="p-4 text-center text-slate-500">No user types found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Discount Types Table -->
    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 overflow-hidden">
        <div class="p-5 border-b border-slate-100 dark:border-slate-700">
            <h2 class="text-lg font-bold text-slate-800 dark:text-white">Discount Categories</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 dark:bg-slate-700/50 border-b border-slate-100 dark:border-slate-700 text-xs uppercase tracking-wider text-slate-500 dark:text-slate-400 font-semibold">
                        <th class="p-4">Category</th>
                        <th class="p-4">Discount</th>
                        <th class="p-4">Assigned Users</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                    @forelse($discountTypes as $discount)
                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors">
                        <td class="p-4">
                            <div class="font-bold text-slate-800 dark:text-slate-200">{{ $discount->display_name }}</div>
                            <div class="text-xs text-slate-500 mt-0.5 truncate max-w-[200px]">{{ $discount->description }}</div>
                        </td>
                        <td class="p-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400">
                                {{ round($discount->percentage * 100) }}% OFF
                            </span>
                        </td>
                        <td class="p-4 font-semibold text-slate-800 dark:text-slate-200">
                            {{ $discount->users_count }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="p-4 text-center text-slate-500">No discount types found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
