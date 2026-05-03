@extends('layouts.app')
@section('title', 'Mindanao Express — Book Your Bus Trip')

@section('content')

{{-- ══════════════════════════════════ HERO ══════════════════════════════════ --}}
<section class="relative bg-slate-900 overflow-hidden min-h-[88vh] flex items-center">

  {{-- decorative blobs --}}
  <div class="absolute inset-0 pointer-events-none overflow-hidden">
    <div class="absolute -top-20 -right-20 w-[500px] h-[500px] bg-primary-500/10 rounded-full blur-3xl"></div>
    <div class="absolute bottom-0 left-0 w-[400px] h-[400px] bg-blue-500/5 rounded-full blur-3xl"></div>
    <div class="absolute inset-0 opacity-[.04]"
         style="background-image:url(\"data:image/svg+xml,%3Csvg width='40' height='40' viewBox='0 0 40 40' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M 40 0 L 0 0 0 40' fill='none' stroke='white' stroke-width='1'/%3E%3C/svg%3E\")"></div>
  </div>

  <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full py-20">
    <div class="grid lg:grid-cols-2 gap-14 items-center">

      {{-- Copy --}}
      <div>
        <span class="inline-flex items-center gap-2 bg-primary-500/20 text-primary-300 text-xs font-bold px-3 py-1.5 rounded-full mb-6">
          <i data-lucide="zap" style="width:11px;height:11px"></i>
          Instant e-ticket confirmation
        </span>

        {{-- Live Trip Counter --}}
        <div class="inline-flex items-center gap-4 bg-white/10 backdrop-blur-sm border border-white/20 rounded-2xl px-6 py-4 mb-6">
          <div class="flex items-center gap-2">
            <div class="w-3 h-3 bg-emerald-400 rounded-full animate-pulse"></div>
            <span class="text-white font-semibold">
              <span class="text-2xl font-bold text-emerald-300">{{ $liveStats['trips_today'] }}</span>
              <span class="text-sm text-slate-300"> trips today</span>
            </span>
          </div>
          <div class="w-px h-8 bg-white/20"></div>
          <div class="flex items-center gap-2">
            <i data-lucide="users" style="width:16px;height:16px;color:#fbbf24"></i>
            <span class="text-white font-semibold">
              <span class="text-lg font-bold text-amber-300">{{ $liveStats['seats_available_today'] }}</span>
              <span class="text-sm text-slate-300"> seats available</span>
            </span>
          </div>
        </div>

        <h1 class="text-5xl sm:text-6xl font-extrabold text-white leading-[1.08] mb-5 tracking-tight">
          Travel<br>Mindanao<br>
          <span class="text-primary-400">Your Way</span>
        </h1>

        <p class="text-slate-400 text-lg leading-relaxed mb-8 max-w-md">
          Book intercity bus trips in seconds. Pick your seat, pay securely, and receive your QR e-ticket instantly via SMS and email.
        </p>

        <div class="flex flex-wrap gap-3 mb-8">
          <a href="{{ route('landing.ticket_booking') }}"
             class="inline-flex items-center gap-2 px-6 py-3 bg-primary-600 hover:bg-primary-700 text-white font-bold text-sm rounded-xl transition-colors shadow-lg shadow-primary-900/40">
            <i data-lucide="search" style="width:15px;height:15px"></i> Search Trips
          </a>
          <a href="{{ route('landing.booking_routes') }}"
             class="inline-flex items-center gap-2 px-6 py-3 bg-white/10 hover:bg-white/20 border border-white/20 text-white font-semibold text-sm rounded-xl transition-colors">
            <i data-lucide="map" style="width:15px;height:15px"></i> Browse Routes
          </a>
        </div>

        <div class="flex flex-wrap gap-5">
          @foreach([
            ['shield-check', 'LTO Accredited'],
            ['clock',        'On-Time Guarantee'],
            ['credit-card',  'Secure Payment'],
            ['smartphone',   'QR E-Ticket'],
          ] as [$icon,$label])
            <div class="flex items-center gap-1.5 text-slate-500 text-xs">
              <i data-lucide="{{ $icon }}" style="width:12px;height:12px;color:#f97316"></i>
              {{ $label }}
            </div>
          @endforeach
        </div>
      </div>

      {{-- Right: next trip card + quick search --}}
      <div class="space-y-4">

        {{-- Next departure card --}}
        @if(isset($heroTrip) && $heroTrip)
          <div class="bg-white/10 border border-white/20 backdrop-blur-sm rounded-2xl p-5">
            <div class="flex items-center gap-2 mb-4">
              <span class="w-2 h-2 bg-emerald-400 rounded-full animate-pulse"></span>
              <span class="text-emerald-400 text-xs font-bold tracking-wide">NEXT DEPARTURE</span>
            </div>
            <div class="flex items-center gap-4 mb-4">
              <div class="text-center min-w-0">
                <div class="text-2xl font-extrabold text-white">{{ $heroTrip->departure_time->format('H:i') }}</div>
                <div class="text-xs text-slate-400 truncate">{{ $heroTrip->route?->originCity?->name }}</div>
              </div>
              <div class="flex-1 flex flex-col items-center gap-1">
                @php
                  $m = $heroTrip->route?->estimated_duration_minutes;
                  $dur = $m ? floor($m/60).'h '.str_pad($m%60,2,'0',STR_PAD_LEFT).'m' : '—';
                @endphp
                <span class="text-xs text-slate-500">{{ $dur }}</span>
                <div class="w-full flex items-center gap-1">
                  <div class="w-2 h-2 rounded-full border-2 border-primary-400"></div>
                  <div class="flex-1 h-px bg-gradient-to-r from-primary-400/60 to-slate-600"></div>
                  <i data-lucide="bus" style="width:14px;height:14px;color:#fb923c"></i>
                  <div class="flex-1 h-px bg-gradient-to-r from-slate-600 to-emerald-400/60"></div>
                  <div class="w-2 h-2 rounded-full border-2 border-emerald-400"></div>
                </div>
                <span class="text-xs text-slate-500">Direct</span>
              </div>
              <div class="text-center min-w-0">
                <div class="text-2xl font-extrabold text-white">{{ $heroTrip->arrival_time?->format('H:i') ?? '—' }}</div>
                <div class="text-xs text-slate-400 truncate">{{ $heroTrip->route?->destinationCity?->name }}</div>
              </div>
            </div>
            <div class="flex items-center justify-between border-t border-white/10 pt-3">
              <div class="flex items-center gap-3 text-xs text-slate-400">
                <span class="flex items-center gap-1"><i data-lucide="users" style="width:12px;height:12px"></i>{{ $heroTrip->available_seats }} seats</span>
                <span class="flex items-center gap-1"><i data-lucide="tag" style="width:12px;height:12px"></i>{{ $heroTrip->bus?->type?->type_name }}</span>
              </div>
              <span class="text-primary-400 font-extrabold">₱{{ number_format($heroTrip->fare, 0) }}</span>
            </div>
          </div>
        @endif

        {{-- Tabs for Search & Track --}}
        <div class="bg-white rounded-2xl p-5 shadow-2xl">
          <div class="flex gap-4 border-b border-slate-100 mb-4">
            <button onclick="switchHeroTab('search')" id="tab-btn-search" class="pb-2 text-sm font-bold border-b-2 border-primary-600 text-primary-700 transition-colors">
              <i data-lucide="search" style="width:14px;height:14px;display:inline;margin-right:4px"></i>Quick Search
            </button>
            <button onclick="switchHeroTab('track')" id="tab-btn-track" class="pb-2 text-sm font-bold border-b-2 border-transparent text-slate-400 hover:text-slate-600 transition-colors">
              <i data-lucide="map" style="width:14px;height:14px;display:inline;margin-right:4px"></i>Track Trip
            </button>
          </div>

          {{-- Quick Search Form --}}
          <form action="{{ route('landing.ticket_booking.search') }}" method="POST" id="hero-search-form" class="space-y-3">
            @csrf
            <div class="grid grid-cols-2 gap-3">
              <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1">From</label>
                <div class="relative">
                  <i data-lucide="map-pin" style="width:12px;height:12px;position:absolute;left:10px;top:50%;transform:translateY(-50%);color:#94a3b8"></i>
                  <select name="from" required class="w-full pl-7 pr-2 py-2.5 text-xs border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500 bg-white appearance-none">
                    <option value="">Origin city</option>
                    @foreach($originCities ?? [] as $city)
                      <option value="{{ $city->name }}">{{ $city->name }}</option>
                    @endforeach
                  </select>
                </div>
              </div>
              <div>
                <label class="block text-xs font-semibold text-slate-600 mb-1">To</label>
                <div class="relative">
                  <i data-lucide="map-pin" style="width:12px;height:12px;position:absolute;left:10px;top:50%;transform:translateY(-50%);color:#94a3b8"></i>
                  <select name="to" required class="w-full pl-7 pr-2 py-2.5 text-xs border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500 bg-white appearance-none">
                    <option value="">Destination</option>
                    @foreach($destinationCities ?? [] as $city)
                      <option value="{{ $city->name }}">{{ $city->name }}</option>
                    @endforeach
                  </select>
                </div>
              </div>
            </div>
            <div>
              <label class="block text-xs font-semibold text-slate-600 mb-1">Travel Date</label>
              <div class="relative">
                <i data-lucide="calendar" style="width:12px;height:12px;position:absolute;left:10px;top:50%;transform:translateY(-50%);color:#94a3b8"></i>
                <input type="date" name="date" required
                       min="{{ today()->toDateString() }}" value="{{ today()->toDateString() }}"
                       class="w-full pl-7 pr-4 py-2.5 text-xs border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500">
              </div>
            </div>
            <button type="submit"
                    class="w-full py-2.5 bg-primary-600 hover:bg-primary-700 text-white text-sm font-bold rounded-xl transition-colors flex items-center justify-center gap-2">
              <i data-lucide="search" style="width:14px;height:14px"></i> Find Trips
            </button>
          </form>

          {{-- Track Trip Form --}}
          <div id="hero-track-form" class="hidden space-y-3">
            <div>
              <label class="block text-xs font-semibold text-slate-600 mb-1">Trip Code</label>
              <div class="relative">
                <i data-lucide="hash" style="width:12px;height:12px;position:absolute;left:10px;top:50%;transform:translateY(-50%);color:#94a3b8"></i>
                <input type="text" id="track-trip-code" placeholder="e.g. TR-A1B2C3"
                       class="w-full pl-7 pr-4 py-2.5 text-xs border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500 uppercase">
              </div>
            </div>
            <button onclick="trackTripCode()" id="btn-track-trip"
                    class="w-full py-2.5 bg-slate-800 hover:bg-slate-900 text-white text-sm font-bold rounded-xl transition-colors flex items-center justify-center gap-2">
              <i data-lucide="crosshair" style="width:14px;height:14px"></i> Check Status
            </button>
            <div id="track-result" class="hidden mt-3 p-3 bg-slate-50 border border-slate-100 rounded-xl text-xs space-y-1.5">
              <!-- Result goes here -->
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>
</section>

{{-- ══════════════════════════════════ STATS ══════════════════════════════════ --}}
<section class="bg-white border-b border-slate-100">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="grid grid-cols-2 md:grid-cols-4 divide-x divide-slate-100">
      @foreach([
        ['map-pin',   $stats['totalCities']  ?? 0, 'Cities Served'],
        ['route',     $stats['totalRoutes']  ?? 0, 'Active Routes'],
        ['bus',       $stats['activeBuses']  ?? 0, 'Active Buses'],
        ['calendar',  $stats['todayTrips']   ?? 0, "Today's Trips"],
      ] as [$icon,$val,$label])
        <div class="flex flex-col items-center py-8 px-4 text-center hover:-translate-y-0.5 transition-transform">
          <div class="w-10 h-10 bg-primary-50 rounded-xl flex items-center justify-center mb-3">
            <i data-lucide="{{ $icon }}" style="width:18px;height:18px;color:#ea580c"></i>
          </div>
          <div class="text-2xl font-extrabold text-slate-900">{{ number_format($val) }}</div>
          <div class="text-xs text-slate-500 mt-0.5">{{ $label }}</div>
        </div>
      @endforeach
    </div>
  </div>
</section>

{{-- ══════════════════════════════ POPULAR ROUTES ══════════════════════════════ --}}
<section class="py-20 bg-slate-50">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex items-end justify-between mb-10">
      <div>
        <p class="text-xs font-bold text-primary-600 uppercase tracking-widest mb-1">Popular Destinations</p>
        <h2 class="text-3xl font-extrabold text-slate-900">Popular <span class="text-primary-600">Routes</span></h2>
        <p class="text-slate-500 text-sm mt-1.5">Our most-booked intercity routes — reserve early for the best fares.</p>
      </div>
      <a href="{{ route('landing.booking_routes') }}"
         class="hidden sm:flex items-center gap-1.5 text-sm font-semibold text-primary-600 hover:text-primary-700 transition-colors">
        View all <i data-lucide="arrow-right" style="width:14px;height:14px"></i>
      </a>
    </div>

    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-5">
      @forelse($featuredRoutes ?? [] as $i => $route)
        @php
          $dur   = $route->estimated_duration_minutes
            ? floor($route->estimated_duration_minutes/60).'h '.str_pad($route->estimated_duration_minutes%60,2,'0',STR_PAD_LEFT).'m'
            : '—';
          $badges = [
            ['bg-red-100 text-red-700',          'flame',       'Hot'],
            ['bg-primary-100 text-primary-700',   'star',        'Popular'],
            ['bg-emerald-100 text-emerald-700',   'trending-up', 'Trending'],
          ];
          [$bcls,$bicon,$blabel] = $badges[$i % 3];
          
          // Get live stats for this route
          $tripsToday = \App\Models\Trip::where('route_id', $route->id)
            ->whereDate('trip_date', today())
            ->where('status', 'scheduled')
            ->where('is_active', true)
            ->count();
          $seatsToday = \App\Models\Trip::where('route_id', $route->id)
            ->whereDate('trip_date', today())
            ->where('status', 'scheduled')
            ->where('is_active', true)
            ->sum('available_seats');
        @endphp
        <div class="group bg-white rounded-2xl p-5 border border-slate-200 cursor-pointer
                    hover:shadow-lg hover:-translate-y-1 transition-all duration-200"
             onclick="location.href='{{ route('landing.ticket_booking') }}?from={{ urlencode($route->originCity?->name ?? '') }}&to={{ urlencode($route->destinationCity?->name ?? '') }}&date={{ today()->toDateString() }}'">

          <div class="flex items-start justify-between mb-4">
            <span class="inline-flex items-center gap-1.5 text-xs font-bold px-2.5 py-1 rounded-full {{ $bcls }}">
              <i data-lucide="{{ $bicon }}" style="width:10px;height:10px"></i>{{ $blabel }}
            </span>
            <span class="text-xs text-slate-400">{{ $route->distance_km ? $route->distance_km.' km' : '' }}</span>
          </div>

          <div class="flex items-center gap-3 mb-4">
            <div class="text-center shrink-0">
              <div class="text-sm font-bold text-slate-900 leading-tight">{{ $route->originCity?->name }}</div>
              <div class="text-[10px] text-slate-400">{{ $route->originCity?->province }}</div>
            </div>
            <div class="flex-1 flex flex-col items-center gap-0.5">
              <span class="text-[10px] text-slate-400">{{ $dur }}</span>
              <div class="w-full flex items-center">
                <div class="w-1.5 h-1.5 rounded-full bg-primary-500"></div>
                <div class="flex-1 h-px bg-slate-200"></div>
                <i data-lucide="bus" style="width:13px;height:13px;color:#ea580c"></i>
                <div class="flex-1 h-px bg-slate-200"></div>
                <div class="w-1.5 h-1.5 rounded-full bg-emerald-500"></div>
              </div>
            </div>
            <div class="text-center shrink-0">
              <div class="text-sm font-bold text-slate-900 leading-tight">{{ $route->destinationCity?->name }}</div>
              <div class="text-[10px] text-slate-400">{{ $route->destinationCity?->province }}</div>
            </div>
          </div>

          <div class="flex items-center justify-between pt-3.5 border-t border-slate-100">
            <div class="flex items-center gap-3">
              <div class="flex items-center gap-1 text-xs text-slate-500">
                <i data-lucide="calendar" style="width:11px;height:11px"></i>
                {{ $route->upcoming_trips_count ?? 0 }} upcoming
              </div>
              @if($tripsToday > 0)
                <div class="flex items-center gap-1 text-xs text-emerald-600 font-semibold">
                  <div class="w-2 h-2 bg-emerald-500 rounded-full animate-pulse"></div>
                  {{ $tripsToday }} today
                </div>
              @endif
            </div>
            <div class="text-right">
              <div class="text-[10px] text-slate-400">from</div>
              <div class="text-base font-extrabold text-primary-600">
                {{ $route->min_fare ? '₱'.number_format($route->min_fare, 0) : '—' }}
              </div>
              @if($seatsToday > 0)
                <div class="text-[10px] text-emerald-600 font-semibold">{{ $seatsToday }} seats</div>
              @endif
            </div>
          </div>
        </div>
      @empty
        <div class="col-span-3 py-16 text-center text-slate-400">
          <i data-lucide="route" style="width:36px;height:36px;margin:0 auto 10px;opacity:.3"></i>
          <p class="text-sm">No active routes yet. Check back soon!</p>
        </div>
      @endforelse
    </div>

    <div class="sm:hidden text-center mt-6">
      <a href="{{ route('landing.booking_routes') }}" class="inline-flex items-center gap-1.5 text-sm font-semibold text-primary-600">
        View all routes <i data-lucide="arrow-right" style="width:14px;height:14px"></i>
      </a>
    </div>
  </div>
</section>

{{-- ══════════════════════════════ HOW IT WORKS ══════════════════════════════ --}}
<section class="py-20 bg-white">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="text-center max-w-lg mx-auto mb-12">
      <p class="text-xs font-bold text-primary-600 uppercase tracking-widest mb-1">Simple Process</p>
      <h2 class="text-3xl font-extrabold text-slate-900">Booked in <span class="text-primary-600">4 Steps</span></h2>
      <p class="text-slate-500 text-sm mt-1.5">From search to seat confirmation in under 2 minutes.</p>
    </div>
    <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-5">
      @foreach([
        ['search',      '1', 'Search Your Trip',  'Enter your origin, destination, and travel date. See all available buses instantly.'],
        ['armchair',    '2', 'Pick Your Seat',     'View the live seat map. Choose economy, business, or sleeper — window or aisle.'],
        ['credit-card', '3', 'Pay Securely',       'GCash, Maya, card, or OTC. 256-bit SSL encrypted. Zero hidden fees.'],
        ['ticket',      '4', 'Board & Ride',       'Show your QR e-ticket at the terminal gate. Sit back and enjoy the journey.'],
      ] as [$icon,$num,$title,$desc])
        <div class="relative bg-slate-50 border border-slate-200 rounded-2xl p-5">
          <div class="absolute -top-3 -left-3 w-7 h-7 bg-primary-600 text-white text-xs font-extrabold rounded-full flex items-center justify-center shadow-md shadow-primary-200">{{ $num }}</div>
          <div class="w-10 h-10 bg-primary-100 rounded-xl flex items-center justify-center mb-3.5">
            <i data-lucide="{{ $icon }}" style="width:19px;height:19px;color:#ea580c"></i>
          </div>
          <h3 class="text-sm font-bold text-slate-900 mb-1">{{ $title }}</h3>
          <p class="text-xs text-slate-500 leading-relaxed">{{ $desc }}</p>
        </div>
      @endforeach
    </div>
  </div>
</section>

{{-- ══════════════════════════════ QUICK TIMETABLES ══════════════════════════════ --}}
@if(isset($topSchedules) && count($topSchedules) > 0)
<section class="py-20 bg-slate-900 border-t border-slate-800">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="text-center max-w-lg mx-auto mb-12">
      <p class="text-xs font-bold text-primary-400 uppercase tracking-widest mb-1">Daily Departures</p>
      <h2 class="text-3xl font-extrabold text-white">Quick <span class="text-primary-400">Timetables</span></h2>
      <p class="text-slate-400 text-sm mt-1.5">View our daily schedule for top routes across Mindanao.</p>
    </div>

    <div class="grid lg:grid-cols-2 gap-6">
      @foreach(array_slice($topSchedules, 0, 4) as $item)
        <div class="bg-white/5 border border-white/10 rounded-2xl p-6">
          <div class="flex items-center gap-3 mb-4 border-b border-white/10 pb-4">
            <div class="w-10 h-10 rounded-full bg-primary-500/20 flex items-center justify-center text-primary-400 shrink-0">
              <i data-lucide="route" style="width:20px;height:20px"></i>
            </div>
            <div>
              <h3 class="text-lg font-bold text-white">{{ $item['route']->originCity->name }} to {{ $item['route']->destinationCity->name }}</h3>
              <p class="text-xs text-slate-400">{{ $item['route']->distance_km ? $item['route']->distance_km.' km' : 'Direct Route' }}</p>
            </div>
          </div>
          <div class="flex flex-wrap gap-2">
            @foreach($item['schedules']->take(8) as $sched)
              <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-slate-800 text-slate-300 text-xs font-semibold rounded-lg border border-slate-700">
                <i data-lucide="clock" style="width:12px;height:12px;color:#94a3b8"></i>
                {{ \Carbon\Carbon::parse($sched->departure_time)->format('h:i A') }}
              </span>
            @endforeach
            @if($item['schedules']->count() > 8)
              <span class="inline-flex items-center px-3 py-1.5 text-slate-400 text-xs font-semibold">
                +{{ $item['schedules']->count() - 8 }} more
              </span>
            @endif
          </div>
        </div>
      @endforeach
    </div>
  </div>
</section>
@endif

{{-- ══════════════════════════════ INTERACTIVE ROUTE MAP (VISUAL) ══════════════════════════════ --}}
<section class="py-20 bg-slate-50 border-t border-slate-200 overflow-hidden relative">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
    <div class="text-center max-w-lg mx-auto mb-12">
      <p class="text-xs font-bold text-primary-600 uppercase tracking-widest mb-1">Our Network</p>
      <h2 class="text-3xl font-extrabold text-slate-900">Connecting <span class="text-primary-600">Mindanao</span></h2>
      <p class="text-slate-500 text-sm mt-1.5">Seamless travel across major cities and terminals.</p>
    </div>
    
    <div class="relative w-full max-w-4xl mx-auto h-[400px] bg-white rounded-3xl border border-slate-200 shadow-xl overflow-hidden flex items-center justify-center">
      {{-- Stylized SVG Network Map --}}
      <svg class="absolute inset-0 w-full h-full text-slate-100" fill="none" viewBox="0 0 800 400" preserveAspectRatio="xMidYMid slice">
        <path d="M100 100 Q 250 50 400 150 T 700 200" stroke="currentColor" stroke-width="4" stroke-dasharray="8 8" />
        <path d="M400 150 Q 500 300 600 250" stroke="currentColor" stroke-width="4" stroke-dasharray="8 8" />
        <path d="M150 250 Q 250 350 400 150" stroke="currentColor" stroke-width="4" stroke-dasharray="8 8" />
      </svg>
      
      {{-- Nodes --}}
      <div class="absolute top-[20%] left-[15%] group">
        <div class="w-4 h-4 bg-primary-500 rounded-full shadow-[0_0_0_4px_rgba(234,88,12,0.2)] animate-pulse"></div>
        <div class="absolute top-6 left-1/2 -translate-x-1/2 bg-slate-900 text-white text-[10px] font-bold px-2 py-1 rounded-md opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap">Zamboanga</div>
      </div>
      <div class="absolute top-[35%] left-[45%] group">
        <div class="w-5 h-5 bg-emerald-500 rounded-full shadow-[0_0_0_4px_rgba(16,185,129,0.2)]"></div>
        <div class="absolute top-7 left-1/2 -translate-x-1/2 bg-slate-900 text-white text-[10px] font-bold px-2 py-1 rounded-md opacity-100 whitespace-nowrap">Cagayan de Oro</div>
      </div>
      <div class="absolute top-[60%] left-[25%] group">
        <div class="w-4 h-4 bg-primary-500 rounded-full shadow-[0_0_0_4px_rgba(234,88,12,0.2)]"></div>
        <div class="absolute top-6 left-1/2 -translate-x-1/2 bg-slate-900 text-white text-[10px] font-bold px-2 py-1 rounded-md opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap">Pagadian</div>
      </div>
      <div class="absolute top-[45%] left-[85%] group">
        <div class="w-6 h-6 bg-primary-600 rounded-full shadow-[0_0_0_6px_rgba(234,88,12,0.2)]"></div>
        <div class="absolute top-8 left-1/2 -translate-x-1/2 bg-slate-900 text-white text-[10px] font-bold px-2 py-1 rounded-md opacity-100 whitespace-nowrap">Davao City</div>
      </div>
      <div class="absolute top-[60%] left-[70%] group">
        <div class="w-4 h-4 bg-primary-500 rounded-full shadow-[0_0_0_4px_rgba(234,88,12,0.2)] animate-pulse"></div>
        <div class="absolute top-6 left-1/2 -translate-x-1/2 bg-slate-900 text-white text-[10px] font-bold px-2 py-1 rounded-md opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap">General Santos</div>
      </div>
      <div class="absolute top-[25%] left-[65%] group">
        <div class="w-4 h-4 bg-primary-500 rounded-full shadow-[0_0_0_4px_rgba(234,88,12,0.2)]"></div>
        <div class="absolute top-6 left-1/2 -translate-x-1/2 bg-slate-900 text-white text-[10px] font-bold px-2 py-1 rounded-md opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap">Butuan</div>
      </div>
      
      <div class="absolute bottom-6 right-6 bg-white/90 backdrop-blur text-xs font-bold text-slate-500 px-3 py-1.5 rounded-lg shadow-sm border border-slate-100">
        Interactive Map Illustration
      </div>
    </div>
  </div>
</section>

{{-- ══════════════════════════════ FLEET SHOWCASE ══════════════════════════════ --}}
@if(isset($busTypes) && $busTypes->isNotEmpty())
<section class="py-20 bg-white border-t border-slate-100">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="text-center max-w-lg mx-auto mb-12">
      <p class="text-xs font-bold text-primary-600 uppercase tracking-widest mb-1">Our Fleet</p>
      <h2 class="text-3xl font-extrabold text-slate-900">Travel in <span class="text-primary-600">Comfort</span></h2>
      <p class="text-slate-500 text-sm mt-1.5">Choose the class that fits your budget and style.</p>
    </div>

    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
      @foreach($busTypes as $type)
        @php
          $icon = match(strtolower($type->type_name)) {
            'deluxe' => 'star',
            'express' => 'zap',
            'aircon' => 'wind',
            'mini bus' => 'truck',
            default => 'bus'
          };
          $color = match(strtolower($type->type_name)) {
            'deluxe' => 'text-amber-600 bg-amber-50 border-amber-200',
            'express' => 'text-red-600 bg-red-50 border-red-200',
            'aircon' => 'text-blue-600 bg-blue-50 border-blue-200',
            default => 'text-slate-600 bg-slate-50 border-slate-200'
          };
        @endphp
        <div class="border border-slate-200 rounded-2xl p-6 hover:shadow-xl transition-shadow relative overflow-hidden group">
          <div class="absolute -right-6 -top-6 w-24 h-24 {{ explode(' ', $color)[1] }} rounded-full opacity-50 group-hover:scale-150 transition-transform duration-500"></div>
          
          <div class="w-12 h-12 {{ $color }} rounded-xl flex items-center justify-center mb-5 relative z-10 border">
            <i data-lucide="{{ $icon }}" style="width:24px;height:24px"></i>
          </div>
          
          <h3 class="text-xl font-bold text-slate-900 mb-2 relative z-10">{{ $type->type_name }}</h3>
          <p class="text-sm text-slate-500 mb-5 relative z-10 line-clamp-2 min-h-[40px]">{{ $type->description }}</p>
          
          <ul class="space-y-2 relative z-10">
            <li class="flex items-center gap-2 text-xs text-slate-600 font-medium">
              <i data-lucide="check" style="width:14px;height:14px;color:#10b981"></i> Spacious Seating
            </li>
            @if(in_array(strtolower($type->type_name), ['aircon', 'express', 'deluxe']))
              <li class="flex items-center gap-2 text-xs text-slate-600 font-medium">
                <i data-lucide="check" style="width:14px;height:14px;color:#10b981"></i> Full Air-Conditioning
              </li>
            @endif
            @if(strtolower($type->type_name) === 'deluxe')
              <li class="flex items-center gap-2 text-xs text-slate-600 font-medium">
                <i data-lucide="check" style="width:14px;height:14px;color:#10b981"></i> Extra Legroom & USB Ports
              </li>
            @endif
          </ul>
        </div>
      @endforeach
    </div>
  </div>
</section>
@endif

{{-- ══════════════════════════════ TRAVEL FAQ ══════════════════════════════ --}}
<section class="py-20 bg-slate-50 border-t border-slate-200">
  <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="text-center mb-12">
      <p class="text-xs font-bold text-primary-600 uppercase tracking-widest mb-1">Got Questions?</p>
      <h2 class="text-3xl font-extrabold text-slate-900">Travel <span class="text-primary-600">FAQ</span></h2>
    </div>

    <div class="space-y-4">
      @foreach([
        ['What is your luggage policy?', 'Each passenger is allowed 1 hand-carry bag (up to 7kg) and 1 checked bag (up to 15kg). Excess luggage is subject to additional fees at the terminal.'],
        ['Are pets allowed on the bus?', 'Yes, small pets are allowed but they must be placed in a secure, leak-proof pet carrier. They can be placed under the seat or in the cargo hold depending on the bus type.'],
        ['Can I cancel or reschedule my ticket?', 'Tickets can be rescheduled at least 24 hours before departure with a minor rebooking fee. Cancellations are non-refundable but can be converted to travel credits.'],
        ['Do children or infants travel for free?', 'Infants under 2 years old travel for free provided they sit on an adult\'s lap. Children 3 years and above require a regular ticket. Student discounts apply.'],
        ['How do I claim my Senior Citizen or PWD discount?', 'You can select the discount type during checkout. However, you MUST present your valid Senior Citizen or PWD ID to the bus conductor before boarding. Failure to do so will require you to pay the fare difference.']
      ] as $i => [$q, $a])
        <div class="border border-slate-200 rounded-2xl overflow-hidden bg-white">
          <button onclick="toggleFaq({{ $i }})" class="w-full flex items-center justify-between p-5 text-left bg-white hover:bg-slate-50 transition-colors focus:outline-none">
            <span class="font-bold text-slate-900 text-sm">{{ $q }}</span>
            <i data-lucide="chevron-down" id="faq-icon-{{ $i }}" style="width:16px;height:16px;color:#64748b;transition:transform 0.3s"></i>
          </button>
          <div id="faq-content-{{ $i }}" class="hidden px-5 pb-5 pt-2 text-sm text-slate-600 leading-relaxed bg-white">
            {{ $a }}
          </div>
        </div>
      @endforeach
    </div>
  </div>
</section>

{{-- ══════════════════════════════ DISCOUNT TYPES STRIP ══════════════════════════════ --}}
@if(isset($discountTypes) && $discountTypes->isNotEmpty())
<section class="bg-amber-50 border-y border-amber-100 py-6">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex flex-wrap items-center gap-4 justify-center">
      <div class="flex items-center gap-2 text-amber-700 mr-2">
        <i data-lucide="badge-percent" style="width:18px;height:18px"></i>
        <span class="text-sm font-bold">Government Discounts Available:</span>
      </div>
      @foreach($discountTypes as $dt)
        @if($dt->percentage > 0)
          <div class="flex items-center gap-2 bg-white border border-amber-200 rounded-xl px-3 py-1.5">
            <span class="text-xs font-bold text-amber-700">{{ number_format($dt->percentage * 100, 0) }}% OFF</span>
            <span class="text-xs text-slate-600">· {{ $dt->display_name }}</span>
          </div>
        @endif
      @endforeach
      <span class="text-xs text-amber-600">Valid ID required at boarding.</span>
    </div>
  </div>
</section>
@endif

{{-- ══════════════════════════════ TRUST BADGES ══════════════════════════════ --}}
<section class="py-16 bg-white border-y border-slate-100">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    
    {{-- Section Header --}}
    <div class="text-center mb-12">
      <h2 class="text-2xl font-extrabold text-slate-900 mb-4">
        Travel with <span class="text-primary-600">Confidence</span>
      </h2>
      <p class="text-slate-600 max-w-2xl mx-auto">
        We're accredited, insured, and committed to your safety and satisfaction.
      </p>
    </div>

    {{-- Trust Badges Grid --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
      
      {{-- LTO Accredited --}}
      <div class="text-center group">
        <div class="w-16 h-16 bg-emerald-100 rounded-2xl flex items-center justify-center mx-auto mb-4 group-hover:bg-emerald-200 transition-colors">
          <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color:#059669">
            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
            <path d="M9 12l2 2 4-4"></path>
          </svg>
        </div>
        <h3 class="font-bold text-slate-900 mb-2">LTO Accredited</h3>
        <p class="text-sm text-slate-600">Officially licensed by Land Transportation Office</p>
      </div>

      {{-- LTFRB Certified --}}
      <div class="text-center group">
        <div class="w-16 h-16 bg-blue-100 rounded-2xl flex items-center justify-center mx-auto mb-4 group-hover:bg-blue-200 transition-colors">
          <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color:#2563eb">
            <path d="M9 12l2 2 4-4"></path>
            <path d="M21 12c.552 0 1-.448 1-1V5c0-.552-.448-1-1-1H3c-.552 0-1 .448-1 1v6c0 .552.448 1 1 1h18z"></path>
            <path d="M3 5v18h18"></path>
          </svg>
        </div>
        <h3 class="font-bold text-slate-900 mb-2">LTFRB Certified</h3>
        <p class="text-sm text-slate-600">Regulated by Land Transportation Franchising Board</p>
      </div>

      {{-- Secure Payments --}}
      <div class="text-center group">
        <div class="w-16 h-16 bg-amber-100 rounded-2xl flex items-center justify-center mx-auto mb-4 group-hover:bg-amber-200 transition-colors">
          <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color:#d97706">
            <rect x="1" y="4" width="22" height="16" rx="2" ry="2"></rect>
            <line x1="1" y1="10" x2="23" y2="10"></line>
          </svg>
        </div>
        <h3 class="font-bold text-slate-900 mb-2">Secure Payments</h3>
        <p class="text-sm text-slate-600">Encrypted transactions with multiple payment options</p>
      </div>

      {{-- 24/7 Support --}}
      <div class="text-center group">
        <div class="w-16 h-16 bg-primary-100 rounded-2xl flex items-center justify-center mx-auto mb-4 group-hover:bg-primary-200 transition-colors">
          <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color:#ea580c">
            <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
          </svg>
        </div>
        <h3 class="font-bold text-slate-900 mb-2">24/7 Support</h3>
        <p class="text-sm text-slate-600">Round-the-clock customer assistance</p>
      </div>

    </div>

    {{-- Additional Trust Indicators --}}
    <div class="mt-12 pt-8 border-t border-slate-200">
      <div class="flex flex-wrap items-center justify-center gap-8">
        
        {{-- Insurance Coverage --}}
        <div class="flex items-center gap-2 text-sm text-slate-600">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path>
          </svg>
          <span>Passenger Insurance Coverage</span>
        </div>

        {{-- COVID-19 Safety --}}
        <div class="flex items-center gap-2 text-sm text-slate-600">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M22 12h-4l-3 9L9 3l-3 9H2"></path>
          </svg>
          <span>Health & Safety Protocols</span>
        </div>

        {{-- On-Time Guarantee --}}
        <div class="flex items-center gap-2 text-sm text-slate-600">
          <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="12" cy="12" r="10"></circle>
            <polyline points="12 6 12 12 16 14"></polyline>
          </svg>
          <span>95% On-Time Performance</span>
        </div>

      </div>
    </div>

  </div>
</section>

{{-- ══════════════════════════════ PROMOS ══════════════════════════════ --}}
@if(isset($promotions) && $promotions->isNotEmpty())
<section class="py-20 bg-slate-900">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex items-end justify-between mb-10">
      <div>
        <p class="text-xs font-bold text-primary-400 uppercase tracking-widest mb-1">Limited Time</p>
        <h2 class="text-3xl font-extrabold text-white">Promos &amp; <span class="text-primary-400">Deals</span></h2>
        <p class="text-slate-400 text-sm mt-1.5">Book before they run out.</p>
      </div>
      <a href="{{ route('landing.promos') }}"
         class="hidden sm:flex items-center gap-1.5 text-sm font-semibold text-primary-400 hover:text-primary-300 transition-colors">
        All promos <i data-lucide="arrow-right" style="width:14px;height:14px"></i>
      </a>
    </div>
    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-5">
      @foreach($promotions as $promo)
        @php
          $disc = $promo->discount_type === 'percent'
            ? number_format($promo->discount_value, 0).'% OFF'
            : '₱'.number_format($promo->discount_value, 0).' OFF';
        @endphp
        <div class="group bg-white/10 border border-white/20 rounded-2xl p-5 cursor-pointer
                    hover:-translate-y-1 hover:bg-white/15 transition-all duration-200"
             onclick="copyCode('{{ $promo->code }}')">
          <div class="flex items-start justify-between mb-3">
            <span class="text-xs font-bold text-primary-400 bg-primary-400/10 px-2.5 py-1 rounded-full">
              {{ $promo->discount_type === 'percent' ? 'Percent Discount' : 'Fixed Discount' }}
            </span>
            <span class="text-xl font-extrabold text-white">{{ $disc }}</span>
          </div>
          <h3 class="text-sm font-bold text-white mb-1">{{ $promo->name }}</h3>
          <p class="text-xs text-slate-400 clamp-2 mb-4">{{ $promo->description }}</p>
          <div class="flex items-center justify-between border-t border-white/10 pt-3">
            <div class="flex items-center gap-2 bg-white/5 border border-white/10 px-3 py-1.5 rounded-xl group-hover:bg-white/10 transition-colors">
              <i data-lucide="ticket" style="width:12px;height:12px;color:#fb923c"></i>
              <span class="text-xs font-mono font-bold text-white tracking-widest">{{ $promo->code }}</span>
            </div>
            @if($promo->expires_at)
              <span class="text-xs text-slate-500 flex items-center gap-1">
                <i data-lucide="clock" style="width:11px;height:11px"></i> Until {{ $promo->expires_at->format('M d') }}
              </span>
            @endif
          </div>
        </div>
      @endforeach
    </div>
  </div>
</section>
@endif

{{-- ══════════════════════════════ TESTIMONIALS CAROUSEL ══════════════════════════════ --}}
@if(isset($reviews) && $reviews->isNotEmpty())
<section class="py-20 bg-gradient-to-br from-slate-50 to-white">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    
    {{-- Section Header --}}
    <div class="text-center max-w-lg mx-auto mb-12">
      <p class="text-xs font-bold text-primary-600 uppercase tracking-widest mb-1">Passenger Stories</p>
      <h2 class="text-3xl font-extrabold text-slate-900">Loved by <span class="text-primary-600">Travelers</span></h2>
      @if(isset($stats['avgRating']) && $stats['avgRating'])
        <p class="text-slate-500 text-sm mt-1.5">
          <span class="text-amber-500 font-bold">{{ number_format($stats['avgRating'], 1) }} ★</span> average from {{ $reviews->count() }}+ verified passengers
        </p>
      @endif
    </div>

    {{-- Carousel Container --}}
    <div class="relative">
      
      <!-- Featured Review (Large) -->
      <div id="featured-review" class="bg-gradient-to-br from-primary-600 to-primary-700 text-white rounded-3xl p-8 shadow-2xl mb-8 transition-all duration-500">
        @if($reviews->isNotEmpty())
          @php
            $featured = $reviews->first();
            $featuredName = $featured->user?->name ?? 'Happy Passenger';
            $featuredRating = $featured->rating ?? 5;
            $featuredComment = $featured->comment ?? 'Great experience!';
            $featuredType = ucfirst($featured->type ?? 'General');
            $featuredDate = $featured->created_at->format('M Y');
          @endphp
          <div class="flex items-start gap-4 mb-6">
            <div class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-full flex items-center justify-center">
              <i data-lucide="user" style="width:20px;height:20px"></i>
            </div>
            <div class="flex-1">
              <h3 class="font-bold text-lg mb-1">{{ $featuredName }}</h3>
              <div class="flex items-center gap-2 text-sm text-primary-200">
                <span class="flex gap-0.5">
                  @for($s = 1; $s <= 5; $s++)
                    <i data-lucide="star" style="width:14px;height:14px;{{ $s <= $featuredRating ? 'color:#fbbf24;fill:#fbbf24' : 'color:#64748b' }}"></i>
                  @endfor
                </span>
                <span>·</span>
                <span>Verified Traveler</span>
              </div>
            </div>
          </div>
          <blockquote class="text-xl leading-relaxed mb-6 italic">
            "{{ $featuredComment }}"
          </blockquote>
          <div class="flex items-center justify-between">
            <div class="text-sm text-primary-200">
              {{ $featuredType }} · {{ $featuredDate }}
            </div>
            <div class="flex gap-2">
              @for($i = 0; $i < min(3, $reviews->count()); $i++)
                <button onclick="showReview({{ $i }})" 
                        class="w-2 h-2 rounded-full transition-all {{ $i === 0 ? 'bg-white w-8' : 'bg-white/40 hover:bg-white/60' }}"
                        data-review="{{ $i }}"></button>
              @endfor
            </div>
          </div>
        @else
          <div class="text-center py-8">
            <i data-lucide="message-circle" style="width:48px;height:48px;margin:0 auto 16px;opacity:0.5"></i>
            <h3 class="text-xl font-semibold mb-2">No reviews yet</h3>
            <p class="text-primary-200">Be the first to share your travel experience!</p>
          </div>
        @endif
      </div>

      <!-- Review Cards Grid -->
      <div class="grid md:grid-cols-3 gap-6">
        @foreach($reviews->take(6) as $i => $review)
          @php
            $avCols = ['bg-blue-100 text-blue-700','bg-emerald-100 text-emerald-700','bg-violet-100 text-violet-700','bg-sky-100 text-sky-700','bg-pink-100 text-pink-700','bg-amber-100 text-amber-700'];
            $av = $avCols[$i % count($avCols)];
          @endphp
          <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm hover:shadow-lg transition-all duration-300 cursor-pointer group"
               onclick="showReview({{ $i }})"
               data-review-card="{{ $i }}">
            
            <!-- Rating Stars -->
            <div class="flex gap-0.5 mb-3">
              @for($s = 1; $s <= 5; $s++)
                <i data-lucide="star" style="width:12px;height:12px;{{ $s <= $review->rating ? 'color:#f59e0b;fill:#f59e0b' : 'color:#d1d5db' }}"></i>
              @endfor
            </div>

            <!-- Review Text -->
            <p class="text-slate-600 text-sm leading-relaxed mb-4 line-clamp-3">
              "{{ $review->comment }}"
            </p>

            <!-- Reviewer Info -->
            <div class="flex items-center gap-3 pt-3 border-t border-slate-100">
              <div class="w-10 h-10 rounded-full {{ $av }} flex items-center justify-center text-sm font-bold shrink-0">
                {{ strtoupper(substr($review->user?->name ?? 'P', 0, 1)) }}
              </div>
              <div class="min-w-0 flex-1">
                <div class="text-slate-800 text-sm font-semibold truncate">{{ $review->user?->name ?? 'Passenger' }}</div>
                <div class="text-slate-400 text-xs flex items-center gap-1">
                  <i data-lucide="check-circle" style="width:10px;height:10px;color:#10b981"></i>
                  Verified · {{ ucfirst($review->type ?? 'general') }}
                </div>
              </div>
            </div>
          </div>
        @endforeach
      </div>

      <!-- Navigation Arrows -->
      <button onclick="previousReview()" 
              class="absolute left-0 top-1/2 -translate-y-1/2 -translate-x-4 w-10 h-10 bg-white rounded-full shadow-lg flex items-center justify-center hover:bg-primary-50 transition-colors">
        <i data-lucide="chevron-left" style="width:20px;height:20px;color:#ea580c"></i>
      </button>
      <button onclick="nextReview()" 
              class="absolute right-0 top-1/2 -translate-y-1/2 translate-x-4 w-10 h-10 bg-white rounded-full shadow-lg flex items-center justify-center hover:bg-primary-50 transition-colors">
        <i data-lucide="chevron-right" style="width:20px;height:20px;color:#ea580c"></i>
      </button>
    </div>
  </div>
</section>
@endif

{{-- ══════════════════════════════ CTA BANNER ══════════════════════════════ --}}
<section class="py-16 bg-primary-600">
  <div class="max-w-2xl mx-auto px-4 text-center">
    <i data-lucide="bus" style="width:36px;height:36px;color:rgba(255,255,255,.7);margin:0 auto 14px"></i>
    <h2 class="text-3xl font-extrabold text-white mb-3">Ready to ride?</h2>
    <p class="text-primary-100 text-base mb-7">
      Search from {{ $stats['totalRoutes'] ?? 0 }}+ routes and book your seat in under 2 minutes.
    </p>
    <a href="{{ route('landing.ticket_booking') }}"
       class="inline-flex items-center gap-2 px-8 py-3.5 bg-white text-primary-700 font-extrabold rounded-2xl hover:bg-primary-50 transition-colors shadow-lg">
      <i data-lucide="search" style="width:15px;height:15px"></i> Search Trips Now
    </a>
  </div>
</section>

@endsection

@push('scripts')
<script>
  function copyCode(code) {
    navigator.clipboard.writeText(code)
      .then(() => toast('Code "' + code + '" copied to clipboard!', 'success'))
      .catch(() => toast('Could not copy code.', 'error'));
  }

  // Hero Tabs Logic
  function switchHeroTab(tab) {
    document.getElementById('hero-search-form').classList.add('hidden');
    document.getElementById('hero-track-form').classList.add('hidden');
    
    document.getElementById('tab-btn-search').className = 'pb-2 text-sm font-bold border-b-2 border-transparent text-slate-400 hover:text-slate-600 transition-colors';
    document.getElementById('tab-btn-track').className = 'pb-2 text-sm font-bold border-b-2 border-transparent text-slate-400 hover:text-slate-600 transition-colors';
    
    document.getElementById(`hero-${tab}-form`).classList.remove('hidden');
    document.getElementById(`tab-btn-${tab}`).className = 'pb-2 text-sm font-bold border-b-2 border-primary-600 text-primary-700 transition-colors';
  }

  // Track Trip AJAX
  async function trackTripCode() {
    const code = document.getElementById('track-trip-code').value.trim();
    const resultBox = document.getElementById('track-result');
    const btn = document.getElementById('btn-track-trip');
    const orig = btn.innerHTML;

    if (!code) return toast('Please enter a trip code.', 'error');

    btn.innerHTML = `<i data-lucide="loader-2" class="animate-spin" style="width:14px;height:14px"></i> Tracking...`;
    lucide.createIcons();
    
    try {
      const res = await fetch(`{{ route('landing.track_trip') }}?trip_code=${code}`);
      const j = await res.json();
      
      resultBox.classList.remove('hidden');
      if (j.success) {
        const t = j.trip;
        const color = t.status === 'Completed' ? 'text-slate-500' : (t.status === 'Ongoing' ? 'text-blue-500' : 'text-emerald-500');
        resultBox.innerHTML = `
          <div class="flex justify-between items-center mb-2 pb-2 border-b border-slate-200">
            <span class="font-bold text-slate-800">${t.code}</span>
            <span class="font-bold ${color}">${t.status}</span>
          </div>
          <div class="flex items-center gap-1"><i data-lucide="map-pin" style="width:12px;height:12px"></i> ${t.origin} <i data-lucide="arrow-right" style="width:10px;height:10px"></i> ${t.destination}</div>
          <div class="flex items-center gap-1"><i data-lucide="clock" style="width:12px;height:12px"></i> Departs: <span class="font-semibold text-slate-700">${t.departure}</span></div>
          <div class="flex items-center gap-1"><i data-lucide="bus" style="width:12px;height:12px"></i> Class: ${t.bus_type}</div>
        `;
      } else {
        resultBox.innerHTML = `<div class="text-red-500 font-semibold">${j.message}</div>`;
      }
      lucide.createIcons();
    } catch (e) {
      toast('Error tracking trip.', 'error');
    } finally {
      btn.innerHTML = orig;
      lucide.createIcons();
    }
  }

  // Testimonials Carousel
  const reviews = @json($reviews->take(6));
  let currentReview = 0;

  // Only initialize carousel if there are reviews
  if (reviews.length === 0) {
    // Don't initialize carousel functionality when no reviews
    console.log('No reviews available for carousel');
  } else {

  function showReview(index) {
    currentReview = index;
    const review = reviews[index];
    const featuredDiv = document.getElementById('featured-review');
    
    // Update featured review content
    featuredDiv.innerHTML = `
      <div class="flex items-start gap-4 mb-6">
        <div class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-full flex items-center justify-center">
          <i data-lucide="user" style="width:20px;height:20px"></i>
        </div>
        <div class="flex-1">
          <h3 class="font-bold text-lg mb-1">${review.user?.name || 'Happy Passenger'}</h3>
          <div class="flex items-center gap-2 text-sm text-primary-200">
            <span class="flex gap-0.5">
              ${generateStars(review.rating)}
            </span>
            <span>·</span>
            <span>Verified Traveler</span>
          </div>
        </div>
      </div>
      <blockquote class="text-xl leading-relaxed mb-6 italic">
        "${review.comment}"
      </blockquote>
      <div class="flex items-center justify-between">
        <div class="text-sm text-primary-200">
          ${review.type ? review.type.charAt(0).toUpperCase() + review.type.slice(1) : 'General'} · ${new Date(review.created_at).toLocaleDateString('en-US', { month: 'short', year: 'numeric' })}
        </div>
        <div class="flex gap-2">
          ${generateDots(index)}
        </div>
      </div>
    `;
    
    // Reinitialize icons
    lucide.createIcons();
    
    // Update dots
    updateDots(index);
    
    // Update card highlights
    updateCardHighlights(index);
  }

  function generateStars(rating) {
    let stars = '';
    for (let i = 1; i <= 5; i++) {
      const filled = i <= rating ? 'color:#fbbf24;fill:#fbbf24' : 'color:#64748b';
      stars += `<i data-lucide="star" style="width:14px;height:14px;${filled}"></i>`;
    }
    return stars;
  }

  function generateDots(activeIndex) {
    let dots = '';
    for (let i = 0; i < Math.min(3, reviews.length); i++) {
      const active = i === activeIndex;
      const width = active ? 'w-8' : 'w-2';
      const bg = active ? 'bg-white' : 'bg-white/40 hover:bg-white/60';
      dots += `<button onclick="showReview(${i})" 
                      class="w-2 h-2 rounded-full transition-all ${bg} ${width}"
                      data-review="${i}"></button>`;
    }
    return dots;
  }

  function updateDots(activeIndex) {
    document.querySelectorAll('[data-review]').forEach((dot, index) => {
      if (index === activeIndex) {
        dot.classList.add('bg-white', 'w-8');
        dot.classList.remove('bg-white/40', 'hover:bg-white/60', 'w-2');
      } else {
        dot.classList.remove('bg-white', 'w-8');
        dot.classList.add('bg-white/40', 'hover:bg-white/60', 'w-2');
      }
    });
  }

  function updateCardHighlights(activeIndex) {
    document.querySelectorAll('[data-review-card]').forEach((card, index) => {
      if (index === activeIndex) {
        card.classList.add('ring-2', 'ring-primary-600', 'transform', 'scale-105');
      } else {
        card.classList.remove('ring-2', 'ring-primary-600', 'transform', 'scale-105');
      }
    });
  }

  function nextReview() {
    currentReview = (currentReview + 1) % reviews.length;
    showReview(currentReview);
  }

  function previousReview() {
    currentReview = (currentReview - 1 + reviews.length) % reviews.length;
    showReview(currentReview);
  }

  // Travel FAQ Logic
  function toggleFaq(index) {
    const content = document.getElementById(`faq-content-${index}`);
    const icon = document.getElementById(`faq-icon-${index}`);
    
    if (content.classList.contains('hidden')) {
      content.classList.remove('hidden');
      icon.style.transform = 'rotate(180deg)';
    } else {
      content.classList.add('hidden');
      icon.style.transform = 'rotate(0deg)';
    }
  }

  // Auto-rotate carousel
  setInterval(nextReview, 5000);
  }

  // Travel FAQ Logic
  function toggleFaq(index) {
    const content = document.getElementById(`faq-content-${index}`);
    const icon = document.getElementById(`faq-icon-${index}`);
    
    if (content.classList.contains('hidden')) {
      content.classList.remove('hidden');
      icon.style.transform = 'rotate(180deg)';
    } else {
      content.classList.add('hidden');
      icon.style.transform = 'rotate(0deg)';
    }
  }
</script>
@endpush