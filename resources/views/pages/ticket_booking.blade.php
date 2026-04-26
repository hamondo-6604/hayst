@extends('layouts.app')
@section('title', 'Search Trips — VoyagePH')

@push('head')
<style>
  .trip-card { transition: box-shadow .2s, transform .2s; }
  .trip-card:hover { box-shadow: 0 12px 32px rgba(0,0,0,.09); transform: translateY(-2px); }
  .seat-chip { transition: background .15s, color .15s, border-color .15s; }
  select, input[type=date] { cursor: pointer; }
</style>
@endpush

@section('content')

{{-- ── PAGE HEADER ────────────────────────────────────────────────── --}}
<div class="bg-slate-900 py-10">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <p class="text-xs font-bold text-primary-400 uppercase tracking-widest mb-1">Bus Tickets</p>
    <h1 class="text-3xl font-extrabold text-white">Search <span class="text-primary-400">Trips</span></h1>
    <p class="text-slate-400 text-sm mt-1">Find available buses, compare seat types, and book instantly.</p>
  </div>
</div>

{{-- ── SEARCH FORM ────────────────────────────────────────────────── --}}
<div class="bg-white border-b border-slate-200 shadow-sm sticky top-16 z-30">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
    <form action="{{ route('landing.ticket_booking.search') }}" method="POST"
          class="flex flex-wrap gap-3 items-end">
      @csrf

      {{-- From --}}
      <div class="flex-1 min-w-[140px]">
        <label class="block text-xs font-semibold text-slate-600 mb-1">From</label>
        <div class="relative">
          <i data-lucide="map-pin" style="width:13px;height:13px;position:absolute;left:10px;top:50%;transform:translateY(-50%);color:#94a3b8"></i>
          <select name="from" required
                  class="w-full pl-8 pr-3 py-2.5 text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500 bg-white appearance-none">
            <option value="">Origin city</option>
            @foreach($originCities as $city)
              <option value="{{ $city->name }}" {{ ($prefill['from'] ?? '') === $city->name ? 'selected' : '' }}>
                {{ $city->name }}
              </option>
            @endforeach
          </select>
        </div>
      </div>

      {{-- Swap icon --}}
      <button type="button" onclick="swapCities()"
              class="self-end mb-0.5 p-2.5 rounded-xl border border-slate-200 hover:bg-slate-50 text-slate-500 transition-colors">
        <i data-lucide="arrow-left-right" style="width:15px;height:15px"></i>
      </button>

      {{-- To --}}
      <div class="flex-1 min-w-[140px]">
        <label class="block text-xs font-semibold text-slate-600 mb-1">To</label>
        <div class="relative">
          <i data-lucide="map-pin" style="width:13px;height:13px;position:absolute;left:10px;top:50%;transform:translateY(-50%);color:#94a3b8"></i>
          <select name="to" required
                  class="w-full pl-8 pr-3 py-2.5 text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500 bg-white appearance-none">
            <option value="">Destination</option>
            @foreach($destinationCities as $city)
              <option value="{{ $city->name }}" {{ ($prefill['to'] ?? '') === $city->name ? 'selected' : '' }}>
                {{ $city->name }}
              </option>
            @endforeach
          </select>
        </div>
      </div>

      {{-- Date --}}
      <div class="min-w-[150px]">
        <label class="block text-xs font-semibold text-slate-600 mb-1">Travel Date</label>
        <div class="relative">
          <i data-lucide="calendar" style="width:13px;height:13px;position:absolute;left:10px;top:50%;transform:translateY(-50%);color:#94a3b8"></i>
          <input type="date" name="date" required
                 min="{{ today()->toDateString() }}"
                 value="{{ $prefill['date'] ?? today()->toDateString() }}"
                 class="w-full pl-8 pr-4 py-2.5 text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500">
        </div>
      </div>

      {{-- Submit --}}
      <button type="submit"
              class="self-end flex items-center gap-2 px-6 py-2.5 bg-primary-600 hover:bg-primary-700 text-white text-sm font-bold rounded-xl transition-colors whitespace-nowrap">
        <i data-lucide="search" style="width:14px;height:14px"></i> Search
      </button>
    </form>
  </div>
</div>

{{-- ── RESULTS ────────────────────────────────────────────────────── --}}
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">

  @if($trips->isNotEmpty())
    {{-- Result meta --}}
    <div class="flex flex-wrap items-center justify-between gap-3 mb-6">
      <div>
        <h2 class="text-lg font-extrabold text-slate-900">
          {{ $trips->count() }} trip{{ $trips->count() !== 1 ? 's' : '' }} found
        </h2>
        <p class="text-xs text-slate-500 mt-0.5 flex items-center gap-1">
          <i data-lucide="map-pin" style="width:11px;height:11px"></i>
          {{ $prefill['from'] ?? '' }} → {{ $prefill['to'] ?? '' }}
          &nbsp;·&nbsp;
          <i data-lucide="calendar" style="width:11px;height:11px"></i>
          {{ isset($prefill['date']) ? \Carbon\Carbon::parse($prefill['date'])->format('D, M j Y') : '' }}
        </p>
      </div>

      {{-- Sort --}}
      <div class="flex items-center gap-2">
        <span class="text-xs text-slate-500">Sort:</span>
        <div class="flex gap-1">
          @foreach([['departure','Earliest'],['price','Lowest fare'],['seats','Most seats']] as [$val,$lbl])
            <button onclick="sortTrips('{{ $val }}')" data-sort="{{ $val }}"
                    class="px-3 py-1.5 text-xs font-semibold rounded-lg border transition-colors
                           {{ $val === 'departure' ? 'bg-primary-600 text-white border-primary-600' : 'bg-white text-slate-600 border-slate-200 hover:bg-slate-50' }}">
              {{ $lbl }}
            </button>
          @endforeach
        </div>
      </div>
    </div>

    {{-- Filter chips --}}
    <div class="flex flex-wrap gap-2 mb-6">
      @foreach(['all'=>'All Classes','economy'=>'Economy','business'=>'Business','sleeper'=>'Sleeper'] as $val => $lbl)
        <button onclick="filterClass('{{ $val }}')" data-class="{{ $val }}"
                class="px-3 py-1.5 text-xs font-semibold rounded-full border transition-colors
                       {{ $val === 'all' ? 'bg-slate-800 text-white border-slate-800' : 'bg-white text-slate-600 border-slate-200 hover:bg-slate-50' }}">
          {{ $lbl }}
        </button>
      @endforeach
    </div>

    {{-- Trip cards --}}
    <div id="trip-list" class="space-y-4">
      @foreach($trips as $trip)
        @php
          $dep    = $trip->departure_time;
          $arr    = $trip->arrival_time;
          $dur    = $trip->route?->estimated_duration_minutes;
          $durStr = $dur ? floor($dur/60).'h '.str_pad($dur%60,2,'0',STR_PAD_LEFT).'m' : '—';
          $type   = strtolower($trip->bus?->default_seat_type ?? 'economy');
          $typeBadge = match($type) {
            'business' => 'bg-amber-100 text-amber-700',
            'sleeper'  => 'bg-violet-100 text-violet-700',
            default    => 'bg-emerald-100 text-emerald-700',
          };
          $seatsLow = $trip->available_seats <= 5;
        @endphp
        <div class="trip-card bg-white border border-slate-200 rounded-2xl overflow-hidden"
             data-class="{{ $type }}"
             data-departure="{{ $dep->format('H:i') }}"
             data-price="{{ $trip->fare }}"
             data-seats="{{ $trip->available_seats }}">

          <div class="p-5">
            <div class="flex flex-wrap items-start gap-4">

              {{-- Operator info --}}
              <div class="flex items-center gap-3 min-w-0 flex-1">
                <div class="w-10 h-10 bg-slate-100 rounded-xl flex items-center justify-center shrink-0">
                  <i data-lucide="bus" style="width:18px;height:18px;color:#ea580c"></i>
                </div>
                <div class="min-w-0">
                  <div class="text-sm font-bold text-slate-900 truncate">{{ $trip->bus?->bus_name ?? 'VoyagePH Bus' }}</div>
                  <div class="flex flex-wrap items-center gap-2 mt-0.5">
                    <span class="text-xs px-2 py-0.5 rounded-full font-semibold {{ $typeBadge }}">{{ ucfirst($type) }}</span>
                    <span class="text-xs text-slate-400">{{ $trip->bus?->type?->type_name }}</span>
                    @if($trip->departureTerminal)
                      <span class="text-xs text-slate-400 flex items-center gap-0.5">
                        <i data-lucide="building-2" style="width:10px;height:10px"></i>
                        {{ $trip->departureTerminal->name }}
                      </span>
                    @endif
                  </div>
                </div>
              </div>

              {{-- Time + duration --}}
              <div class="flex items-center gap-4 shrink-0">
                <div class="text-center">
                  <div class="text-xl font-extrabold text-slate-900">{{ $dep->format('H:i') }}</div>
                  <div class="text-[10px] text-slate-400 mt-0.5">{{ $trip->route?->originCity?->name }}</div>
                </div>
                <div class="flex flex-col items-center gap-1 w-20">
                  <span class="text-[10px] text-slate-400">{{ $durStr }}</span>
                  <div class="w-full flex items-center">
                    <div class="w-1.5 h-1.5 rounded-full bg-primary-400"></div>
                    <div class="flex-1 h-px bg-slate-200"></div>
                    <i data-lucide="bus" style="width:11px;height:11px;color:#ea580c"></i>
                    <div class="flex-1 h-px bg-slate-200"></div>
                    <div class="w-1.5 h-1.5 rounded-full bg-emerald-400"></div>
                  </div>
                  <span class="text-[10px] text-slate-400">Direct</span>
                </div>
                <div class="text-center">
                  <div class="text-xl font-extrabold text-slate-900">{{ $arr?->format('H:i') ?? '—' }}</div>
                  <div class="text-[10px] text-slate-400 mt-0.5">{{ $trip->route?->destinationCity?->name }}</div>
                </div>
              </div>

              {{-- Fare + CTA --}}
              <div class="text-right shrink-0 ml-auto">
                <div class="text-xs text-slate-400">per person</div>
                <div class="text-2xl font-extrabold text-primary-600">₱{{ number_format($trip->fare, 0) }}</div>
                @auth
                  @php $discRate = auth()->user()->discountType?->percentage ?? 0; @endphp
                  @if($discRate > 0)
                    <div class="text-xs text-emerald-600 font-semibold">
                      You pay ₱{{ number_format($trip->fare * (1 - $discRate), 0) }}
                      <span class="text-slate-400 font-normal">({{ number_format($discRate*100,0) }}% off)</span>
                    </div>
                  @endif
                @endauth
                <button onclick="bookTrip({{ $trip->id }})"
                        class="mt-2 px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white text-xs font-bold rounded-xl transition-colors">
                  Select Seat →
                </button>
              </div>

            </div>

            {{-- Footer row --}}
            <div class="flex flex-wrap items-center gap-4 mt-4 pt-4 border-t border-slate-100">
              {{-- Amenities --}}
              <div class="flex flex-wrap gap-2">
                @foreach($trip->bus?->amenities ?? [] as $amenity)
                  <span class="flex items-center gap-1 text-[10px] text-slate-500 bg-slate-50 border border-slate-100 px-2 py-1 rounded-lg">
                    <i data-lucide="{{ $amenity->icon ?? 'check' }}" style="width:10px;height:10px;color:#ea580c"></i>
                    {{ $amenity->display_name }}
                  </span>
                @endforeach
              </div>

              {{-- Seats badge --}}
              <div class="ml-auto flex items-center gap-1.5 text-xs font-semibold
                          {{ $seatsLow ? 'text-red-600' : 'text-emerald-600' }}">
                <i data-lucide="{{ $seatsLow ? 'alert-circle' : 'check-circle' }}" style="width:13px;height:13px"></i>
                {{ $trip->available_seats }} seat{{ $trip->available_seats !== 1 ? 's' : '' }}
                {{ $seatsLow ? 'left — book fast!' : 'available' }}
              </div>
            </div>
          </div>
        </div>
      @endforeach
    </div>

  @elseif(request()->isMethod('POST') || !empty($prefill['from']))
    {{-- No results --}}
    <div class="text-center py-20">
      <div class="w-16 h-16 bg-slate-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
        <i data-lucide="search-x" style="width:28px;height:28px;color:#94a3b8"></i>
      </div>
      <h3 class="text-lg font-bold text-slate-800 mb-2">No trips found</h3>
      <p class="text-sm text-slate-500 max-w-xs mx-auto">
        No available trips for your selected route and date. Try a different date or check our
        <a href="{{ route('landing.booking_routes') }}" class="text-primary-600 font-semibold hover:underline">routes page</a>.
      </p>
    </div>

  @else
    {{-- Empty state (initial load) --}}
    <div class="text-center py-20">
      <div class="w-16 h-16 bg-primary-50 rounded-2xl flex items-center justify-center mx-auto mb-4">
        <i data-lucide="bus" style="width:28px;height:28px;color:#ea580c"></i>
      </div>
      <h3 class="text-lg font-bold text-slate-800 mb-2">Find your next trip</h3>
      <p class="text-sm text-slate-500">Select your origin, destination, and travel date above to see available buses.</p>
    </div>
  @endif

</div>

@endsection

@push('scripts')
<script>
  // ── Swap cities ─────────────────────────────────────────────────
  function swapCities() {
    const from = document.querySelector('select[name="from"]');
    const to   = document.querySelector('select[name="to"]');
    [from.value, to.value] = [to.value, from.value];
  }

  // ── Sort ─────────────────────────────────────────────────────────
  function sortTrips(key) {
    document.querySelectorAll('[data-sort]').forEach(b => {
      b.classList.toggle('bg-primary-600', b.dataset.sort === key);
      b.classList.toggle('text-white',     b.dataset.sort === key);
      b.classList.toggle('border-primary-600', b.dataset.sort === key);
      b.classList.toggle('bg-white',       b.dataset.sort !== key);
      b.classList.toggle('text-slate-600', b.dataset.sort !== key);
      b.classList.toggle('border-slate-200', b.dataset.sort !== key);
    });
    const cards = [...document.querySelectorAll('#trip-list .trip-card')];
    const get   = {
      departure: c => c.dataset.departure,
      price:     c => parseFloat(c.dataset.price),
      seats:     c => -parseInt(c.dataset.seats),
    };
    cards.sort((a, b) => {
      const va = get[key](a), vb = get[key](b);
      return va < vb ? -1 : va > vb ? 1 : 0;
    });
    const list = document.getElementById('trip-list');
    cards.forEach(c => list.appendChild(c));
  }

  // ── Filter by class ──────────────────────────────────────────────
  function filterClass(cls) {
    document.querySelectorAll('[data-class-btn]').forEach(b => {
      const active = b.dataset.classBtnVal ?? b.dataset.class;
    });
    document.querySelectorAll('[data-class]').forEach(b => {
      const isBtn = b.tagName === 'BUTTON';
      if (isBtn) {
        const match = b.dataset.class === cls;
        b.classList.toggle('bg-slate-800', match); b.classList.toggle('text-white', match); b.classList.toggle('border-slate-800', match);
        b.classList.toggle('bg-white', !match);    b.classList.toggle('text-slate-600', !match); b.classList.toggle('border-slate-200', !match);
      }
    });
    document.querySelectorAll('#trip-list .trip-card').forEach(card => {
      card.style.display = (cls === 'all' || card.dataset.class === cls) ? '' : 'none';
    });
    // update button styles cleanly
    document.querySelectorAll('[onclick^="filterClass"]').forEach(btn => {
      const v = btn.getAttribute('onclick').match(/'([^']+)'/)?.[1];
      const on = v === cls;
      btn.className = `px-3 py-1.5 text-xs font-semibold rounded-full border transition-colors ${on ? 'bg-slate-800 text-white border-slate-800' : 'bg-white text-slate-600 border-slate-200 hover:bg-slate-50'}`;
    });
  }

  // ── Book trip ────────────────────────────────────────────────────
  function bookTrip(tripId) {
    requireAuth('/user/bookings/select-seats/' + tripId);
  }
</script>
@endpush