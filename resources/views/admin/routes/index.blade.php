@extends('layouts.admin')

@section('title', 'Manage Routes')

@section('content')
<div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
    <div>
        <h1 class="text-2xl font-bold text-slate-800 dark:text-white">Routes</h1>
        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Manage the available bus routes and their configurations.</p>
    </div>
    <a href="{{ route('admin.routes.create') }}" class="px-5 py-2.5 bg-primary-600 hover:bg-primary-700 text-white text-sm font-semibold rounded-xl transition-colors inline-flex items-center justify-center gap-2 shadow-sm">
        <i class="fa-solid fa-plus"></i> Add Route
    </a>
</div>

<!-- Filters -->
<div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 p-4 mb-6">
    <form method="GET" action="{{ route('admin.routes.index') }}" class="flex flex-col sm:flex-row gap-4 items-end">
        <div class="flex-1">
            <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 mb-1">Search Routes</label>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by route name, origin, or destination..." 
                   class="w-full rounded-xl border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-white text-sm focus:ring-primary-500 focus:border-primary-500">
        </div>
        <div class="w-full sm:w-48">
            <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 mb-1">Status</label>
            <select name="status" class="w-full rounded-xl border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-white text-sm focus:ring-primary-500 focus:border-primary-500">
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
                <a href="{{ route('admin.routes.index') }}" class="ml-2 text-sm text-primary-600 hover:text-primary-700 dark:text-primary-400">Clear</a>
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
                    <th class="p-4">Route Name / Path</th>
                    <th class="p-4">Terminals</th>
                    <th class="p-4">Distance & Duration</th>
                    <th class="p-4">Status</th>
                    <th class="p-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                @forelse($routes as $route)
                <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors">
                    <td class="p-4">
                        <div class="font-bold text-slate-800 dark:text-slate-200 mb-1">{{ $route->route_name }}</div>
                        <div class="flex items-center gap-2 text-sm text-slate-500 dark:text-slate-400">
                            <span class="truncate max-w-[120px]">{{ $route->originCity->name ?? 'Unknown' }}</span>
                            <i class="fa-solid fa-arrow-right text-[10px] text-slate-300"></i>
                            <span class="truncate max-w-[120px]">{{ $route->destinationCity->name ?? 'Unknown' }}</span>
                        </div>
                    </td>
                    <td class="p-4">
                        <div class="text-sm font-medium text-slate-800 dark:text-slate-200"><i class="fa-solid fa-location-dot text-emerald-500 w-4"></i> {{ $route->originTerminal->name ?? 'N/A' }}</div>
                        <div class="text-sm text-slate-500 mt-1"><i class="fa-solid fa-flag-checkered text-red-500 w-4"></i> {{ $route->destinationTerminal->name ?? 'N/A' }}</div>
                    </td>
                    <td class="p-4">
                        <div class="font-medium text-slate-800 dark:text-slate-200">{{ number_format($route->distance_km, 1) }} km</div>
                        <div class="text-sm text-slate-500 mt-0.5">
                            @php
                                $hours = floor($route->estimated_duration_minutes / 60);
                                $mins = $route->estimated_duration_minutes % 60;
                            @endphp
                            <i class="fa-regular fa-clock w-4"></i> {{ $hours > 0 ? $hours.'h ' : '' }}{{ $mins }}m
                        </div>
                    </td>
                    <td class="p-4">
                        @if($route->status === 'active')
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
                            <a href="{{ route('admin.routes.show', $route) }}" class="w-8 h-8 rounded-lg bg-slate-100 hover:bg-slate-200 dark:bg-slate-700 dark:hover:bg-slate-600 text-slate-600 dark:text-slate-300 flex items-center justify-center transition-colors">
                                <i class="fa-solid fa-eye text-sm"></i>
                            </a>
                            <a href="{{ route('admin.routes.edit', $route) }}" class="w-8 h-8 rounded-lg bg-slate-100 hover:bg-slate-200 dark:bg-slate-700 dark:hover:bg-slate-600 text-slate-600 dark:text-slate-300 flex items-center justify-center transition-colors">
                                <i class="fa-solid fa-pen-to-square text-sm"></i>
                            </a>
                            <form action="{{ route('admin.routes.destroy', $route) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this route?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-8 h-8 rounded-lg bg-red-50 hover:bg-red-100 dark:bg-red-900/20 dark:hover:bg-red-900/40 text-red-600 dark:text-red-400 flex items-center justify-center transition-colors">
                                    <i class="fa-solid fa-trash-can text-sm"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="p-8 text-center text-slate-500 dark:text-slate-400">
                        <div class="text-4xl mb-2"><i class="fa-solid fa-map-location-dot text-slate-300 dark:text-slate-600"></i></div>
                        <p>No routes found.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($routes->hasPages())
    <div class="p-4 border-t border-slate-100 dark:border-slate-700">
        {{ $routes->links() }}
    </div>
    @endif
</div>
@endsection
