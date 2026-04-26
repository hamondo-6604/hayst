@extends('layouts.admin')

@section('title', 'Route Details')

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.routes.index') }}" class="text-sm font-medium text-slate-500 hover:text-primary-600 dark:hover:text-primary-400 transition-colors flex items-center gap-1 mb-2">
        <i class="fa-solid fa-arrow-left"></i> Back to Routes
    </a>
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-800 dark:text-white flex items-center gap-3">
                {{ $route->route_name }}
                @if($route->status === 'active')
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400">
                        Active
                    </span>
                @else
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-slate-100 text-slate-700 dark:bg-slate-700 dark:text-slate-300">
                        Inactive
                    </span>
                @endif
            </h1>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">{{ $route->description ?? 'No description provided.' }}</p>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Left Column: Intermediate Stops Timeline -->
    <div class="lg:col-span-2 space-y-6">
        
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 overflow-hidden">
            <div class="p-5 border-b border-slate-100 dark:border-slate-700 flex justify-between items-center">
                <h2 class="text-lg font-bold text-slate-800 dark:text-white flex items-center gap-2">
                    <i class="fa-solid fa-map text-primary-500"></i> Route Journey & Stops
                </h2>
                <div class="text-sm text-slate-500 font-semibold">
                    {{ number_format($route->distance_km, 1) }} km Total
                </div>
            </div>
            
            <div class="p-6">
                <div class="relative border-l-2 border-slate-200 dark:border-slate-700 ml-4 space-y-8 pb-4">
                    
                    <!-- Origin Node -->
                    <div class="relative pl-8">
                        <div class="absolute -left-[9px] top-1 w-4 h-4 rounded-full bg-emerald-500 ring-4 ring-emerald-50 dark:ring-emerald-900/20"></div>
                        <p class="text-xs text-slate-500 uppercase font-semibold mb-0.5">Origin</p>
                        <h3 class="text-lg font-bold text-slate-800 dark:text-white">{{ $route->originTerminal->name ?? $route->originCity->name ?? 'Unknown Origin' }}</h3>
                        <p class="text-sm text-slate-500 mt-1">
                            <i class="fa-solid fa-city w-4"></i> {{ $route->originCity->name ?? '' }}
                        </p>
                        <div class="mt-2 inline-flex items-center gap-2 text-xs font-medium text-slate-500 bg-slate-50 dark:bg-slate-700/50 px-2.5 py-1 rounded-lg">
                            <span><i class="fa-regular fa-clock text-slate-400"></i> 0 mins</span>
                            <span class="text-slate-300">&bull;</span>
                            <span>₱0.00 Base Fare</span>
                        </div>
                    </div>

                    <!-- Intermediate Stops -->
                    @forelse($route->stops as $stop)
                        <div class="relative pl-8">
                            <div class="absolute -left-[9px] top-1 w-4 h-4 rounded-full bg-slate-300 border-2 border-white dark:border-slate-800 dark:bg-slate-600"></div>
                            <p class="text-xs text-slate-500 uppercase font-semibold mb-0.5">Stop #{{ $stop->pivot->stop_order }}</p>
                            <h3 class="text-base font-bold text-slate-800 dark:text-white">{{ $stop->name }}</h3>
                            <p class="text-sm text-slate-500 mt-1">
                                <i class="fa-solid fa-location-dot w-4"></i> {{ $stop->location_description ?? 'No specific location details' }}
                            </p>
                            <div class="mt-2 flex flex-wrap items-center gap-2 text-xs font-medium text-slate-500">
                                <div class="bg-slate-50 dark:bg-slate-700/50 px-2.5 py-1 rounded-lg">
                                    <i class="fa-regular fa-clock text-slate-400"></i> +{{ $stop->pivot->minutes_from_origin }} mins
                                </div>
                                <div class="bg-slate-50 dark:bg-slate-700/50 px-2.5 py-1 rounded-lg">
                                    ₱{{ number_format($stop->pivot->fare_from_origin, 2) }} added
                                </div>
                                @if($stop->pivot->allows_boarding)
                                    <span class="bg-emerald-50 text-emerald-600 dark:bg-emerald-900/30 dark:text-emerald-400 px-2 py-1 rounded-lg">Allows Boarding</span>
                                @endif
                                @if($stop->pivot->allows_alighting)
                                    <span class="bg-amber-50 text-amber-600 dark:bg-amber-900/30 dark:text-amber-400 px-2 py-1 rounded-lg">Allows Alighting</span>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="relative pl-8 pb-4">
                            <p class="text-sm text-slate-400 italic">No intermediate stops configured for this route. Direct travel only.</p>
                        </div>
                    @endforelse

                    <!-- Destination Node -->
                    <div class="relative pl-8">
                        <div class="absolute -left-[9px] top-1 w-4 h-4 rounded-full bg-red-500 ring-4 ring-red-50 dark:ring-red-900/20"></div>
                        <p class="text-xs text-slate-500 uppercase font-semibold mb-0.5">Destination</p>
                        <h3 class="text-lg font-bold text-slate-800 dark:text-white">{{ $route->destinationTerminal->name ?? $route->destinationCity->name ?? 'Unknown Destination' }}</h3>
                        <p class="text-sm text-slate-500 mt-1">
                            <i class="fa-solid fa-city w-4"></i> {{ $route->destinationCity->name ?? '' }}
                        </p>
                        <div class="mt-2 inline-flex items-center gap-2 text-xs font-medium text-slate-500 bg-slate-50 dark:bg-slate-700/50 px-2.5 py-1 rounded-lg">
                            @php
                                $hours = floor($route->estimated_duration_minutes / 60);
                                $mins = $route->estimated_duration_minutes % 60;
                            @endphp
                            <span><i class="fa-regular fa-clock text-slate-400"></i> Total: {{ $hours > 0 ? $hours.'h ' : '' }}{{ $mins }}m</span>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- Right Column: Info Summaries -->
    <div class="space-y-6">
        
        <!-- Metrics -->
        <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 overflow-hidden">
            <div class="p-5 border-b border-slate-100 dark:border-slate-700">
                <h2 class="text-lg font-bold text-slate-800 dark:text-white flex items-center gap-2">
                    <i class="fa-solid fa-chart-bar text-primary-500"></i> Route Metrics
                </h2>
            </div>
            <div class="p-5 space-y-4">
                <div>
                    <p class="text-xs text-slate-500 dark:text-slate-400 uppercase tracking-wide font-semibold mb-1">Total Distance</p>
                    <p class="text-xl font-bold text-slate-800 dark:text-white">{{ number_format($route->distance_km, 1) }} km</p>
                </div>
                <div>
                    <p class="text-xs text-slate-500 dark:text-slate-400 uppercase tracking-wide font-semibold mb-1">Base Estimated Duration</p>
                    <p class="text-xl font-bold text-slate-800 dark:text-white">
                        @php
                            $hours = floor($route->estimated_duration_minutes / 60);
                            $mins = $route->estimated_duration_minutes % 60;
                        @endphp
                        {{ $hours > 0 ? $hours.' hours, ' : '' }}{{ $mins }} mins
                    </p>
                </div>
                <div>
                    <p class="text-xs text-slate-500 dark:text-slate-400 uppercase tracking-wide font-semibold mb-1">Intermediate Stops</p>
                    <p class="text-xl font-bold text-slate-800 dark:text-white">{{ $route->stops->count() }} stops</p>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
