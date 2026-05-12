@extends('layouts.app')
@section('title', 'Routes & Terminals — Mindanao Express')

@section('content')

{{-- ── PAGE HEADER ─────────────────────────────────────────────── --}}
<div class="bg-slate-900 py-10">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <p class="text-xs font-bold text-primary-400 uppercase tracking-widest mb-1">Coverage</p>
    <h1 class="text-3xl font-extrabold text-white">Routes &amp; <span class="text-primary-400">Terminals</span></h1>
    <p class="text-slate-400 text-sm mt-1">Explore all active routes and terminal locations across Mindanao.</p>
  </div>
</div>

{{-- ── STATS STRIP ─────────────────────────────────────────────── --}}
<div class="bg-white border-b border-slate-200">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="grid grid-cols-2 sm:grid-cols-4 divide-x divide-slate-100">
      @foreach([
        ['route',     $stats['totalRoutes']    ?? 0, 'Active Routes'],
        ['map-pin',   $stats['totalCities']    ?? 0, 'Cities Served'],
        ['building-2',$stats['totalTerminals'] ?? 0, 'Terminals'],
        ['tag',       $stats['lowestFare']     ?? 0, 'Lowest Fare'],
      ] as [$icon, $val, $label])
        <div class="flex flex-col items-center py-6 px-4 text-center">
          <div class="w-9 h-9 bg-primary-50 rounded-xl flex items-center justify-center mb-2">
            <i data-lucide="{{ $icon }}" style="width:16px;height:16px;color:#ea580c"></i>
          </div>
          <div class="text-xl font-extrabold text-slate-900">
            {{ $icon === 'tag' ? '₱'.number_format($val, 0) : number_format($val) }}
          </div>
          <div class="text-xs text-slate-500">{{ $label }}</div>
        </div>
      @endforeach
    </div>
  </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
  <div class="flex flex-col lg:flex-row gap-8">

    {{-- ── SIDEBAR ─────────────────────────────────────────────── --}}
    <aside class="w-full lg:w-60 shrink-0">
      <div class="bg-white border border-slate-200 rounded-2xl p-5 sticky top-24 space-y-5">

        {{-- Search --}}
        <div>
          <label class="block text-xs font-bold text-slate-700 mb-2">Search Routes</label>
          <div class="relative">
            <i data-lucide="search"
               style="width:13px;height:13px;position:absolute;left:10px;top:50%;transform:translateY(-50%);color:#94a3b8"></i>
            <input type="text" id="route-search" placeholder="City or route name…"
                   oninput="applyFilters()"
                   value="{{ request('search') }}"
                   class="w-full pl-8 pr-4 py-2 text-sm border border-slate-200 rounded-xl
                          focus:outline-none focus:ring-2 focus:ring-primary-500">
          </div>
        </div>

        {{-- Region filter --}}
        @if($regions->isNotEmpty())
          <div>
            <button type="button" onclick="toggleDropdown('region-dropdown')" 
                    class="w-full flex items-center justify-between py-2 px-3 text-sm border border-slate-200 rounded-xl
                           focus:outline-none focus:ring-2 focus:ring-primary-500 bg-white hover:bg-slate-50">
              <span class="text-slate-700">Region</span>
              <i data-lucide="chevron-down" class="w-4 h-4 text-slate-400 transition-transform" id="region-chevron"></i>
            </button>
            <div id="region-dropdown" class="hidden mt-2 p-3 border border-slate-200 rounded-xl bg-white max-h-48 overflow-y-auto">
              <div class="space-y-2">
                <label class="flex items-center gap-2 text-sm text-slate-600 cursor-pointer hover:text-slate-900">
                  <input type="radio" name="region-filter" value="" onchange="applyFilters()" checked
                         class="accent-primary-600"> All Regions
                </label>
                @foreach($regions as $region)
                  <label class="flex items-center gap-2 text-sm text-slate-600 cursor-pointer hover:text-slate-900">
                    <input type="radio" name="region-filter" value="{{ strtolower($region) }}"
                           onchange="applyFilters()" class="accent-primary-600">
                    {{ $region }}
                  </label>
                @endforeach
              </div>
            </div>
          </div>
        @endif

        {{-- Amenity Filter --}}
        @if($amenities->isNotEmpty())
          <div>
            <button type="button" onclick="toggleDropdown('amenities-dropdown')" 
                    class="w-full flex items-center justify-between py-2 px-3 text-sm border border-slate-200 rounded-xl
                           focus:outline-none focus:ring-2 focus:ring-primary-500 bg-white hover:bg-slate-50">
              <span class="text-slate-700">Amenities</span>
              <i data-lucide="chevron-down" class="w-4 h-4 text-slate-400 transition-transform" id="amenities-chevron"></i>
            </button>
            <div id="amenities-dropdown" class="hidden mt-2 p-3 border border-slate-200 rounded-xl bg-white max-h-48 overflow-y-auto">
              <div class="space-y-2">
                @foreach($amenities as $amenity)
                  <label class="flex items-center gap-2 text-sm text-slate-600 cursor-pointer hover:text-slate-900">
                    <input type="checkbox" name="amenity-filter" value="{{ $amenity->id }}"
                           onchange="applyFilters()" class="accent-primary-600 rounded">
                    {{ $amenity->display_name }}
                  </label>
                @endforeach
              </div>
            </div>
          </div>
        @endif

        {{-- Bus Type Filter --}}
        @if($busTypes->isNotEmpty())
          <div>
            <button type="button" onclick="toggleDropdown('bus-types-dropdown')" 
                    class="w-full flex items-center justify-between py-2 px-3 text-sm border border-slate-200 rounded-xl
                           focus:outline-none focus:ring-2 focus:ring-primary-500 bg-white hover:bg-slate-50">
              <span class="text-slate-700">Bus Type</span>
              <i data-lucide="chevron-down" class="w-4 h-4 text-slate-400 transition-transform" id="bus-types-chevron"></i>
            </button>
            <div id="bus-types-dropdown" class="hidden mt-2 p-3 border border-slate-200 rounded-xl bg-white max-h-48 overflow-y-auto">
              <div class="space-y-2">
                @foreach($busTypes as $busType)
                  <label class="flex items-center gap-2 text-sm text-slate-600 cursor-pointer hover:text-slate-900">
                    <input type="checkbox" name="bus-type-filter" value="{{ $busType->id }}"
                           onchange="applyFilters()" class="accent-primary-600 rounded">
                    {{ $busType->type_name }}
                  </label>
                @endforeach
              </div>
            </div>
          </div>
        @endif

        
        {{-- CTA --}}
        <a href="{{ route('landing.ticket_booking') }}"
           class="flex items-center justify-center gap-2 py-2.5 bg-primary-600 hover:bg-primary-700
                  text-white text-sm font-bold rounded-xl transition-colors">
          <i data-lucide="search" style="width:14px;height:14px"></i> Search Trips
        </a>
      </div>
    </aside>

    {{-- ── MAIN ────────────────────────────────────────────────── --}}
    <div class="flex-1 min-w-0">

      {{-- Route count and sort bar --}}
      <div class="flex items-center justify-between px-4 py-3 mb-5 bg-white border border-slate-200 rounded-xl">
        <div class="flex items-center gap-2">
          <i data-lucide="route" style="width:16px;height:16px;color:#ea580c"></i>
          <span class="text-sm font-medium text-slate-700">
            {{ $routes->total() }} route{{ $routes->total() !== 1 ? 's' : '' }} found
          </span>
        </div>
        
        <div class="flex items-center gap-4">
          <span class="text-sm text-slate-600">Sort by:</span>
          <button id="popular-sort-button" onclick="togglePopularSort()" 
                  class="flex items-center gap-1 text-sm text-slate-700 hover:text-primary-600 font-medium transition-colors">
            Most Popular <i id="popular-sort-icon" data-lucide="arrow-down" style="width:14px;height:14px;color:#64748b;display:none"></i>
          </button>
          <button id="price-sort-button" onclick="togglePriceSort()" 
                  class="flex items-center gap-1 text-sm text-slate-700 hover:text-primary-600 font-medium transition-colors">
            Price <i id="price-sort-icon" data-lucide="arrow-down" style="width:14px;height:14px;color:#64748b;display:none"></i>
          </button>
          <button id="distance-sort-button" onclick="toggleDistanceSort()" 
                  class="flex items-center gap-1 text-sm text-slate-700 hover:text-primary-600 font-medium transition-colors">
            Distance <i id="distance-sort-icon" data-lucide="arrow-down" style="width:14px;height:14px;color:#64748b;display:none"></i>
          </button>
        </div>
      </div>

      {{-- ── ROUTE CARDS ──────────────────────────────────────── --}}
      <div id="routes-grid" class="grid sm:grid-cols-2 gap-4 mb-8">
        @forelse($routes as $route)
          @php
            $dur = $route->estimated_duration_minutes
              ? floor($route->estimated_duration_minutes / 60).'h '
                .str_pad($route->estimated_duration_minutes % 60, 2, '0', STR_PAD_LEFT).'m'
              : '—';
            $region         = strtolower($route->originCity?->region ?? '');
            $upcomingCount  = $route->upcoming_trips_count ?? 0;
            
            // Extract unique amenity IDs from trips
            $amenityIds = $route->trips->flatMap(fn($t) => $t->bus?->amenities ?? collect())
                                       ->pluck('id')
                                       ->unique()
                                       ->implode(',');

            // Extract unique bus type IDs from trips
            $busTypeIds = $route->trips->flatMap(fn($t) => $t->bus?->type ? collect([$t->bus->type->id]) : collect())
                                      ->unique()
                                      ->implode(',');

            // ── KEY FIX ─────────────────────────────────────────────
            // Do NOT pass today's date — let TicketBookingController
            // find the nearest available trip date automatically.
            // Only pass from + to; the controller does the rest.
            $searchUrl = route('landing.ticket_booking')
              .'?from='.urlencode($route->originCity?->name ?? '')
              .'&to='.urlencode($route->destinationCity?->name ?? '');
          @endphp

          <div class="route-row bg-white border border-slate-200 rounded-2xl p-4 cursor-pointer
                      hover:shadow-md hover:-translate-y-0.5 transition-all duration-200 group"
               data-name="{{ strtolower($route->route_name) }}"
               data-region="{{ $region }}"
               data-fare="{{ $route->min_fare ?? 9999 }}"
               data-trips="{{ $upcomingCount }}"
               data-distance="{{ $route->distance_km ?? 9999 }}"
               data-amenities="{{ $amenityIds }}"
               data-bus-types="{{ $busTypeIds }}"
               onclick="location.href='{{ $searchUrl }}'">

            {{-- Header row --}}
            <div class="flex items-center gap-3 mb-3">
              <div class="w-8 h-8 bg-primary-50 rounded-xl flex items-center justify-center shrink-0
                          group-hover:bg-primary-100 transition-colors">
                <i data-lucide="bus" style="width:14px;height:14px;color:#ea580c"></i>
              </div>
              <div class="min-w-0">
                <div class="text-sm font-bold text-slate-900 truncate">{{ $route->route_name }}</div>
                <div class="text-xs text-slate-400">{{ $route->originCity?->region }}</div>
              </div>

              {{-- Availability badge --}}
              @if($upcomingCount > 0)
                <span class="ml-auto shrink-0 inline-flex items-center gap-1 text-[10px] font-bold
                             px-2 py-0.5 rounded-full bg-emerald-50 text-emerald-700">
                  <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span>
                  {{ $upcomingCount }} trip{{ $upcomingCount !== 1 ? 's' : '' }}
                </span>
              @else
                <span class="ml-auto shrink-0 inline-flex items-center gap-1 text-[10px] font-bold
                             px-2 py-0.5 rounded-full bg-slate-100 text-slate-400">
                  No trips
                </span>
              @endif
            </div>

            {{-- Route line --}}
            <div class="flex items-center gap-2 mb-3">
              <div class="flex-1 flex items-center gap-1.5">
                <div class="w-1.5 h-1.5 rounded-full bg-primary-500 shrink-0"></div>
                <span class="text-xs text-slate-700 font-medium truncate">
                  {{ $route->originCity?->name }}
                </span>
              </div>
              <i data-lucide="arrow-right" style="width:12px;height:12px;color:#94a3b8;flex-shrink:0"></i>
              <div class="flex-1 flex items-center gap-1.5 justify-end">
                <span class="text-xs text-slate-700 font-medium truncate text-right">
                  {{ $route->destinationCity?->name }}
                </span>
                <div class="w-1.5 h-1.5 rounded-full bg-emerald-500 shrink-0"></div>
              </div>
            </div>

            {{-- Stats footer --}}
            <div class="grid grid-cols-3 gap-2 pt-3 border-t border-slate-100 text-center">
              <div>
                <div class="text-xs font-bold text-slate-800">{{ $dur }}</div>
                <div class="text-[10px] text-slate-400">Duration</div>
              </div>
              <div>
                <div class="text-xs font-bold text-slate-800">
                  {{ $route->distance_km ? $route->distance_km.' km' : '—' }}
                </div>
                <div class="text-[10px] text-slate-400">Distance</div>
              </div>
              <div>
                <div class="text-xs font-bold text-primary-600">
                  {{ $route->min_fare ? '₱'.number_format($route->min_fare, 0) : '—' }}
                </div>
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
        <div class="flex items-center justify-center mt-6">
          {{ $routes->withQueryString()->links() }}
        </div>
      @endif

      {{-- ── TERMINALS ────────────────────────────────────────── --}}
      @if($terminals->isNotEmpty())
        <h2 class="text-base font-extrabold text-slate-900 mb-5 mt-10 flex items-center gap-2">
          <i data-lucide="building-2" style="width:16px;height:16px;color:#ea580c"></i>
          Bus Terminals
        </h2>
        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4">
          @foreach($terminals as $terminal)
            <div class="bg-white border border-slate-200 rounded-2xl p-4
                        hover:shadow-md hover:-translate-y-0.5 transition-all duration-200">
              <div class="flex items-start gap-3 mb-3">
                <div class="w-9 h-9 bg-slate-50 border border-slate-100 rounded-xl
                            flex items-center justify-center shrink-0">
                  <i data-lucide="building-2" style="width:16px;height:16px;color:#64748b"></i>
                </div>
                <div class="min-w-0 flex-1">
                  <div class="text-sm font-bold text-slate-900 leading-tight">{{ $terminal->name }}</div>
                  <div class="text-xs text-slate-400 mt-0.5">
                    {{ $terminal->city?->name }}, {{ $terminal->city?->province }}
                  </div>
                </div>
                @if($terminal->status === 'active')
                  <span class="shrink-0 flex items-center gap-1 text-[10px] font-bold
                               text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-full">
                    <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full animate-pulse"></span>
                    Open
                  </span>
                @endif
              </div>

              @if($terminal->address)
                <div class="flex items-start gap-1.5 text-xs text-slate-500 mb-1.5">
                  <i data-lucide="map-pin"
                     style="width:11px;height:11px;margin-top:1px;color:#94a3b8;flex-shrink:0"></i>
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

    </div>{{-- end main --}}
  </div>
</div>

@endsection

@push('scripts')
<script>
  // ── Toggle dropdown ─────────────────────────────────────────────────────
  function toggleDropdown(dropdownId) {
    const dropdown = document.getElementById(dropdownId);
    const chevron = document.getElementById(dropdownId.replace('-dropdown', '-chevron'));
    
    if (dropdown.classList.contains('hidden')) {
      dropdown.classList.remove('hidden');
      chevron.style.transform = 'rotate(180deg)';
    } else {
      dropdown.classList.add('hidden');
      chevron.style.transform = 'rotate(0deg)';
    }
  }

  // ── Combined filter (search + region + amenities + bus types) ───────────────────────────
  function applyFilters() {
    const q      = (document.getElementById('route-search')?.value ?? '').toLowerCase();
    const region = document.querySelector('input[name="region-filter"]:checked')?.value ?? '';
    const checkedAmenities = Array.from(document.querySelectorAll('input[name="amenity-filter"]:checked')).map(cb => cb.value);
    const checkedBusTypes = Array.from(document.querySelectorAll('input[name="bus-type-filter"]:checked')).map(cb => cb.value);
    
    const rows   = document.querySelectorAll('.route-row');
    let visible  = 0;

    rows.forEach(row => {
      const nameMatch   = row.dataset.name.includes(q);
      const regionMatch = !region || row.dataset.region.includes(region);
      
      const routeAmenities = row.dataset.amenities ? row.dataset.amenities.split(',') : [];
      const amenityMatch = checkedAmenities.every(id => routeAmenities.includes(id));

      const routeBusTypes = row.dataset.busTypes ? row.dataset.busTypes.split(',') : [];
      const busTypeMatch = checkedBusTypes.every(id => routeBusTypes.includes(id));

      const show        = nameMatch && regionMatch && amenityMatch && busTypeMatch;
      row.style.display = show ? '' : 'none';
      if (show) visible++;
    });

    const cnt = document.getElementById('route-count');
    if (cnt) cnt.textContent = visible;
  }

  // ── Most Popular sort toggle ───────────────────────────────────────────────
  let popularSortState = 'desc'; // Start with descending (most to least)
  let popularFirstClick = true; // Track if this is the first click
  
  function togglePopularSort() {
    const icon = document.getElementById('popular-sort-icon');
    
    // Hide other arrows
    document.getElementById('price-sort-icon').style.display = 'none';
    document.getElementById('distance-sort-icon').style.display = 'none';
    
    // Show icon on first click
    if (popularFirstClick) {
      icon.style.display = 'inline';
      popularFirstClick = false;
    } else {
      icon.style.display = 'inline';
    }
    
    if (popularSortState === 'desc') {
      popularSortState = 'asc';
      icon.setAttribute('data-lucide', 'arrow-up');
      applySort('popular_asc');
    } else {
      popularSortState = 'desc';
      icon.setAttribute('data-lucide', 'arrow-down');
      applySort('popular');
    }
    
    // Re-initialize lucide icons
    if (window.lucide) {
      lucide.createIcons();
    }
  }

  // ── Price sort toggle ─────────────────────────────────────────────────────
  let priceSortState = 'desc'; // Start with descending (high to low)
  let priceFirstClick = true; // Track if this is the first click
  
  function togglePriceSort() {
    const icon = document.getElementById('price-sort-icon');
    
    // Hide other arrows
    document.getElementById('popular-sort-icon').style.display = 'none';
    document.getElementById('distance-sort-icon').style.display = 'none';
    
    // Show icon on first click
    if (priceFirstClick) {
      icon.style.display = 'inline';
      priceFirstClick = false;
    } else {
      icon.style.display = 'inline';
    }
    
    if (priceSortState === 'desc') {
      priceSortState = 'asc';
      icon.setAttribute('data-lucide', 'arrow-up');
      applySort('fare_asc');
    } else {
      priceSortState = 'desc';
      icon.setAttribute('data-lucide', 'arrow-down');
      applySort('fare_desc');
    }
    
    // Re-initialize lucide icons
    if (window.lucide) {
      lucide.createIcons();
    }
  }

  // ── Distance sort toggle ───────────────────────────────────────────────────
  let distanceSortState = 'desc'; // Start with descending (longest to shortest)
  let distanceFirstClick = true; // Track if this is the first click
  
  function toggleDistanceSort() {
    const icon = document.getElementById('distance-sort-icon');
    
    // Hide other arrows
    document.getElementById('popular-sort-icon').style.display = 'none';
    document.getElementById('price-sort-icon').style.display = 'none';
    
    // Show icon on first click
    if (distanceFirstClick) {
      icon.style.display = 'inline';
      distanceFirstClick = false;
    } else {
      icon.style.display = 'inline';
    }
    
    if (distanceSortState === 'desc') {
      distanceSortState = 'asc';
      icon.setAttribute('data-lucide', 'arrow-up');
      applySort('distance_asc');
    } else {
      distanceSortState = 'desc';
      icon.setAttribute('data-lucide', 'arrow-down');
      applySort('distance');
    }
    
    // Re-initialize lucide icons
    if (window.lucide) {
      lucide.createIcons();
    }
  }

  // ── Sort ─────────────────────────────────────────────────────────
  function applySort(key) {
    const grid = document.getElementById('routes-grid');
    const rows = [...grid.querySelectorAll('.route-row')];
    const getVal = {
      popular:  r => -parseInt(r.dataset.trips  ?? 0),
      popular_asc: r =>  parseInt(r.dataset.trips  ?? 0),
      fare_asc: r =>  parseFloat(r.dataset.fare ?? 9999),
      fare_desc: r => -parseFloat(r.dataset.fare ?? 9999),
      distance: r =>  parseInt(r.dataset.distance ?? 9999),
      distance_asc: r => -parseInt(r.dataset.distance ?? 9999),
    };
    rows.sort((a, b) => getVal[key](a) - getVal[key](b));
    rows.forEach(r => grid.appendChild(r));
  }
</script>
@endpush