@extends('layouts.app')
@section('title', 'VoyagePH — Book Your Bus Trip')

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

        <h1 class="text-5xl sm:text-6xl font-extrabold text-white leading-[1.08] mb-5 tracking-tight">
          Travel the<br>Philippines<br>
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

        {{-- Quick search --}}
        <div class="bg-white rounded-2xl p-5 shadow-2xl">
          <h3 class="flex items-center gap-2 text-sm font-bold text-slate-800 mb-4">
            <i data-lucide="search" style="width:14px;height:14px;color:#ea580c"></i>
            Quick Search
          </h3>
          <form action="{{ route('landing.ticket_booking.search') }}" method="POST" class="space-y-3">
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
            <div class="flex items-center gap-1 text-xs text-slate-500">
              <i data-lucide="calendar" style="width:11px;height:11px"></i>
              {{ $route->upcoming_trips_count ?? 0 }} upcoming
            </div>
            <div class="text-right">
              <div class="text-[10px] text-slate-400">from</div>
              <div class="text-base font-extrabold text-primary-600">
                {{ $route->min_fare ? '₱'.number_format($route->min_fare, 0) : '—' }}
              </div>
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

{{-- ══════════════════════════════ REVIEWS ══════════════════════════════ --}}
@if(isset($reviews) && $reviews->isNotEmpty())
<section class="py-20 bg-white">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="text-center max-w-lg mx-auto mb-12">
      <p class="text-xs font-bold text-primary-600 uppercase tracking-widest mb-1">Passenger Reviews</p>
      <h2 class="text-3xl font-extrabold text-slate-900">Loved by <span class="text-primary-600">Travelers</span></h2>
      @if(isset($stats['avgRating']) && $stats['avgRating'])
        <p class="text-slate-500 text-sm mt-1.5">
          <span class="text-amber-500 font-bold">{{ number_format($stats['avgRating'], 1) }} ★</span> average from verified passengers
        </p>
      @endif
    </div>
    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-5">
      @foreach($reviews as $i => $review)
        @php
          $avCols = ['bg-primary-100 text-primary-700','bg-emerald-100 text-emerald-700','bg-violet-100 text-violet-700','bg-sky-100 text-sky-700','bg-pink-100 text-pink-700','bg-amber-100 text-amber-700'];
          $av = $avCols[$i % count($avCols)];
        @endphp
        <div class="{{ $i === 0 ? 'bg-slate-900 text-white' : 'bg-slate-50 border border-slate-200' }} rounded-2xl p-5
                    hover:-translate-y-0.5 transition-transform duration-200">
          <div class="flex gap-0.5 mb-3">
            @for($s = 1; $s <= 5; $s++)
              <i data-lucide="star" style="width:12px;height:12px;{{ $s <= $review->rating ? 'color:#f59e0b;fill:#f59e0b' : 'color:#d1d5db' }}"></i>
            @endfor
          </div>
          <p class="{{ $i === 0 ? 'text-slate-300' : 'text-slate-600' }} text-sm leading-relaxed mb-4 clamp-3">
            "{{ $review->comment }}"
          </p>
          <div class="flex items-center gap-3 border-t {{ $i === 0 ? 'border-white/10' : 'border-slate-200' }} pt-3.5">
            <div class="w-8 h-8 rounded-full {{ $av }} flex items-center justify-center text-xs font-bold shrink-0">
              {{ strtoupper(substr($review->user?->name ?? 'P', 0, 1)) }}
            </div>
            <div class="min-w-0">
              <div class="{{ $i === 0 ? 'text-white' : 'text-slate-800' }} text-sm font-semibold truncate">{{ $review->user?->name ?? 'Passenger' }}</div>
              <div class="{{ $i === 0 ? 'text-slate-500' : 'text-slate-400' }} text-xs">Verified · {{ ucfirst($review->type ?? 'general') }}</div>
            </div>
          </div>
        </div>
      @endforeach
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
</script>
@endpush