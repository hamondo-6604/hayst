@extends('layouts.app')
@section('title', 'Routes & Terminals — VoyagePH')

@section('content')

{{-- ── PAGE HEADER ────────────────────────────────────────────────── --}}
<div class="bg-slate-900 py-10">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <p class="text-xs font-bold text-primary-400 uppercase tracking-widest mb-1">Coverage</p>
    <h1 class="text-3xl font-extrabold text-white">Routes &amp; <span class="text-primary-400">Terminals</span></h1>
    <p class="text-slate-400 text-sm mt-1">Explore all active routes and terminal locations across the Philippines.</p>
  </div>
</div>

{{-- ── STATS STRIP ────────────────────────────────────────────────── --}}
<div class="bg-white border-b border-slate-200">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="grid grid-cols-2 sm:grid-cols-4 divide-x divide-slate-100">
      @foreach([
        ['route',       $stats['totalRoutes']   ?? 0,  'Active Routes'],
        ['map-pin',     $stats['totalCities']   ?? 0,  'Cities Served'],
        ['building-2',  $stats['totalTerminals']?? 0,  'Terminals'],
        ['philippine-peso', $stats['lowestFare']?? 0,  'Lowest Fare (₱)'],
      ] as [$icon,$val,$label])
        <div class="flex flex-col items-center py-6 px-4 text-center">
          <div class="w-9 h-9 bg-primary-50 rounded-xl flex items-center justify-center mb-2">
            <i data-lucide="{{ $icon }}" style="width:16px;height:16px;color:#ea580c"></i>
          </div>
          <div class="text-xl font-extrabold text-slate-900">{{ $icon === 'philippine-peso' ? '₱'.number_format($val,0) : number_format($val) }}</div>
          <div class="text-xs text-slate-500">{{ $label }}</div>
        </div>
      @endforeach
    </div>
  </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
  <div class="flex flex-col lg:flex-row gap-8">

    {{-- ── SIDEBAR FILTERS ─────────────────────────────────────────── --}}
    <aside class="w-full lg:w-64 shrink-0">
      <div class="bg-white border border-slate-200 rounded-2xl p-5 sticky top-24 space-y-6">

        {{-- Search --}}
        <div>
          <label class="block text-xs font-bold text-slate-700 mb-2">Search Routes</label>
          <div class="relative">
            <i data-lucide="search" style="width:13px;height:13px;position:absolute;left:10px;top:50%;transform:translateY(-50%);color:#94a3b8"></i>
            <input type="text" id="route-search" placeholder="City or route name…"
                   oninput="filterRoutes()"
                   value="{{ request('search') }}"
                   class="w-full pl-8 pr-4 py-2 text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500">
          </div>
        </div>

        {{-- Region filter --}}
        @if($regions->isNotEmpty())
          <div>
            <label class="block text-xs font-bold text-slate-700 mb-2">Region</label>
            <div class="space-y-1.5">
              <label class="flex items-center gap-2 text-sm text-slate-600 cursor-pointer hover:text-slate-900">
                <input type="radio" name="region-filter" value="" onchange="filterRoutes()" checked
                       class="accent-primary-600"> All Regions
              </label>
              @foreach($regions as $region)
                <label class="flex items-center gap-2 text-sm text-slate-600 cursor-pointer hover:text-slate-900">
                  <input type="radio" name="region-filter" value="{{ $region }}" onchange="filterRoutes()"
                         class="accent-primary-600"> {{ $region }}
                </label>
              @endforeach
            </div>
          </div>
        @endif

        {{-- Sort --}}
        <div>
          <label class="block text-xs font-bold text-slate-700 mb-2">Sort By</label>
          <select id="route-sort" onchange="sortRoutes(this.value)"
                  class="w-full py-2 px-3 text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500">
            <option value="popular">Most Popular</option>
            <option value="fare_asc">Lowest Fare</option>
            <option value="distance">Shortest Distance</option>
          </select>
        </div>

        {{-- CTA --}}
        <a href="{{ route('landing.ticket_booking') }}"
           class="flex items-center justify-center gap-2 py-2.5 bg-primary-600 hover:bg-primary-700 text-white text-sm font-bold rounded-xl transition-colors">
          <i data-lucide="search" style="width:14px;height:14px"></i> Search Trips
        </a>
      </div>
    </aside>

    {{-- ── MAIN CONTENT ─────────────────────────────────────────────── --}}
    <div class="flex-1 min-w-0">

      {{-- ── ROUTES GRID ─────────────────────────────────── --}}
      <h2 class="text-base font-extrabold text-slate-900 mb-5 flex items-center gap-2">
        <i data-lucide="route" style="width:16px;height:16px;color:#ea580c"></i>
        Active Routes
        <span id="route-count" class="text-xs font-semibold text-slate-400 bg-slate-100 px-2 py-0.5 rounded-full ml-1">
          {{ $routes->total() }}
        </span>
      </h2>

      <div id="routes-grid" class="grid sm:grid-cols-2 gap-4 mb-8">
        @forelse($routes as $route)
          @php
            $dur = $route->estimated_duration_minutes
              ? floor($route->estimated_duration_minutes/60).'h '.str_pad($route->estimated_duration_minutes%60,2,'0',STR_PAD_LEFT).'m'
              : '—';
            $region = $route->originCity?->region ?? '';
          @endphp
          <div class="route-row bg-white border border-slate-200 rounded-2xl p-4 cursor-pointer
                      hover:shadow-md hover:-translate-y-0.5 transition-all duration-200 group"
               data-name="{{ strtolower($route->route_name) }}"
               data-region="{{ strtolower($region) }}"
               data-fare="{{ $route->min_fare ?? 9999 }}"
               data-trips="{{ $route->upcoming_trips_count ?? 0 }}"
               data-distance="{{ $route->distance_km ?? 9999 }}"
               onclick="location.href='{{ route('landing.ticket_booking') }}?from={{ urlencode($route->originCity?->name ?? '') }}&to={{ urlencode($route->destinationCity?->name ?? '') }}&date={{ today()->toDateString() }}'">

            <div class="flex items-center gap-3 mb-3">
              <div class="w-8 h-8 bg-primary-50 rounded-xl flex items-center justify-center shrink-0">
                <i data-lucide="bus" style="width:14px;height:14px;color:#ea580c"></i>
              </div>
              <div class="min-w-0">
                <div class="text-sm font-bold text-slate-900 truncate">{{ $route->route_name }}</div>
                <div class="text-xs text-slate-400">{{ $region }}</div>
              </div>
            </div>

            <div class="flex items-center gap-2 mb-3">
              <div class="flex-1 flex items-center gap-1.5">
                <div class="w-1.5 h-1.5 rounded-full bg-primary-500 shrink-0"></div>
                <span class="text-xs text-slate-600 truncate">{{ $route->originCity?->name }}</span>
              </div>
              <i data-lucide="arrow-right" style="width:12px;height:12px;color:#94a3b8;flex-shrink:0"></i>
              <div class="flex-1 flex items-center gap-1.5 justify-end">
                <span class="text-xs text-slate-600 truncate text-right">{{ $route->destinationCity?->name }}</span>
                <div class="w-1.5 h-1.5 rounded-full bg-emerald-500 shrink-0"></div>
              </div>
            </div>

            <div class="grid grid-cols-3 gap-2 pt-3 border-t border-slate-100 text-center">
              <div>
                <div class="text-xs font-bold text-slate-800">{{ $dur }}</div>
                <div class="text-[10px] text-slate-400">Duration</div>
              </div>
              <div>
                <div class="text-xs font-bold text-slate-800">{{ $route->distance_km ? $route->distance_km.' km' : '—' }}</div>
                <div class="text-[10px] text-slate-400">Distance</div>
              </div>
              <div>
                <div class="text-xs font-bold text-primary-600">{{ $route->min_fare ? '₱'.number_format($route->min_fare,0) : '—' }}</div>
                <div class="text-[10px] text-slate-400">From</div>
              </div>
            </div>
          </div>
        @empty
          <div class="col-span-2 py-16 text-center text-slate-400">
            <i data-lucide="route" style="width:32px;height:32px;margin:0 auto 10px;opacity:.3"></i>
            <p class="text-sm">No routes found. Try a different search.</p>
          </div>
        @endforelse
      </div>

      {{-- Pagination --}}
      @if($routes->hasPages())
        <div class="flex items-center justify-center gap-2 mt-6">
          {{ $routes->withQueryString()->links() }}
        </div>
      @endif

      {{-- ── TERMINALS ───────────────────────────────────── --}}
      @if($terminals->isNotEmpty())
        <h2 class="text-base font-extrabold text-slate-900 mb-5 mt-10 flex items-center gap-2">
          <i data-lucide="building-2" style="width:16px;height:16px;color:#ea580c"></i>
          Bus Terminals
        </h2>
        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4">
          @foreach($terminals as $terminal)
            <div class="bg-white border border-slate-200 rounded-2xl p-4 hover:shadow-md hover:-translate-y-0.5 transition-all duration-200">
              <div class="flex items-start gap-3 mb-3">
                <div class="w-9 h-9 bg-slate-50 rounded-xl flex items-center justify-center shrink-0">
                  <i data-lucide="building-2" style="width:16px;height:16px;color:#64748b"></i>
                </div>
                <div class="min-w-0">
                  <div class="text-sm font-bold text-slate-900 leading-tight">{{ $terminal->name }}</div>
                  <div class="text-xs text-slate-400">{{ $terminal->city?->name }}, {{ $terminal->city?->province }}</div>
                </div>
                @if($terminal->status === 'active')
                  <span class="ml-auto shrink-0 flex items-center gap-1 text-[10px] font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-full">
                    <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span> Open
                  </span>
                @endif
              </div>
              @if($terminal->address)
                <div class="flex items-start gap-1.5 text-xs text-slate-500 mb-2">
                  <i data-lucide="map-pin" style="width:11px;height:11px;margin-top:1px;color:#94a3b8;flex-shrink:0"></i>
                  {{ $terminal->address }}
                </div>
              @endif
              @if($terminal->opening_time && $terminal->closing_time)
                <div class="flex items-center gap-1.5 text-xs text-slate-500">
                  <i data-lucide="clock" style="width:11px;height:11px;color:#94a3b8"></i>
                  {{ \Carbon\Carbon::parse($terminal->opening_time)->format('g:i A') }}
                  – {{ \Carbon\Carbon::parse($terminal->closing_time)->format('g:i A') }}
                </div>
              @endif
            </div>
          @endforeach
        </div>
      @endif

    </div>
  </div>
</div>

@endsection

@push('scripts')
<script>
  function filterRoutes() {
    const q      = document.getElementById('route-search')?.value.toLowerCase() ?? '';
    const region = document.querySelector('input[name="region-filter"]:checked')?.value.toLowerCase() ?? '';
    const rows   = document.querySelectorAll('.route-row');
    let visible  = 0;
    rows.forEach(r => {
      const nameOk   = r.dataset.name.includes(q);
      const regionOk = !region || r.dataset.region.includes(region);
      const show     = nameOk && regionOk;
      r.style.display = show ? '' : 'none';
      if (show) visible++;
    });
    const cnt = document.getElementById('route-count');
    if (cnt) cnt.textContent = visible;
  }

  function sortRoutes(key) {
    const grid = document.getElementById('routes-grid');
    const rows = [...grid.querySelectorAll('.route-row')];
    const getVal = {
      popular:  r => -parseInt(r.dataset.trips),
      fare_asc: r => parseFloat(r.dataset.fare),
      distance: r => parseInt(r.dataset.distance),
    };
    rows.sort((a, b) => getVal[key](a) - getVal[key](b));
    rows.forEach(r => grid.appendChild(r));
  }
</script>
@endpush