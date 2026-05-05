@extends('layouts.app')
@section('title', 'My Bookings — Mindanao Express')

@section('content')

{{-- ── PAGE HEADER ────────────────────────────────────────────────── --}}
<div class="bg-slate-900 py-10">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-wrap items-end justify-between gap-4">
    <div>
      <p class="text-xs font-bold text-primary-400 uppercase tracking-widest mb-1">Dashboard</p>
      <h1 class="text-3xl font-extrabold text-white">My <span class="text-primary-400">Bookings</span></h1>
      <p class="text-slate-400 text-sm mt-1">Welcome back, {{ auth()->user()->name }}!</p>
    </div>
    <a href="{{ route('landing.ticket_booking') }}"
       class="inline-flex items-center gap-2 px-5 py-2.5 bg-primary-600 hover:bg-primary-700 text-white text-sm font-bold rounded-xl transition-colors">
      <i data-lucide="plus" style="width:14px;height:14px"></i> Book New Trip
    </a>
  </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
  <div class="flex flex-col lg:flex-row gap-8">

    {{-- ── SIDEBAR ─────────────────────────────────────────────────── --}}
    <aside class="w-full lg:w-64 shrink-0 space-y-4">

      {{-- User card --}}
      <div class="bg-white border border-slate-200 rounded-2xl p-5 text-center">
        <div class="w-14 h-14 rounded-2xl bg-primary-100 text-primary-700 text-xl font-extrabold flex items-center justify-center mx-auto mb-3">
          {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
        </div>
        <div class="text-sm font-bold text-slate-900">{{ auth()->user()->name }}</div>
        <div class="text-xs text-slate-500 mt-0.5 truncate">{{ auth()->user()->email }}</div>

        @if(auth()->user()->discountType && auth()->user()->discountType->percentage > 0)
          <div class="mt-2 inline-flex items-center gap-1.5 bg-amber-50 border border-amber-200 text-amber-700 text-xs font-bold px-2.5 py-1 rounded-full">
            <i data-lucide="badge-percent" style="width:11px;height:11px"></i>
            {{ auth()->user()->discountType->display_name }}
            · {{ number_format(auth()->user()->discountType->percentage * 100, 0) }}% off
          </div>
        @endif

        <div class="grid grid-cols-3 gap-2 mt-4 pt-4 border-t border-slate-100 text-center">
          <div>
            <div class="text-base font-extrabold text-slate-900">{{ $counts->total }}</div>
            <div class="text-[10px] text-slate-400">Total</div>
          </div>
          <div>
            <div class="text-base font-extrabold text-emerald-600">{{ $counts->completed }}</div>
            <div class="text-[10px] text-slate-400">Trips</div>
          </div>
          <div>
            <div class="text-base font-extrabold text-primary-600">₱{{ number_format($profileStats['totalSpent']/1000,1) }}k</div>
            <div class="text-[10px] text-slate-400">Spent</div>
          </div>
        </div>
      </div>

      {{-- Next trip --}}
      @if($nextTrip)
        <div class="bg-primary-600 rounded-2xl p-4 text-white">
          <div class="flex items-center gap-2 mb-3">
            <i data-lucide="clock" style="width:14px;height:14px"></i>
            <span class="text-xs font-bold uppercase tracking-wide">Next Trip</span>
          </div>
          <div class="text-xs text-primary-200 mb-1">{{ $nextTrip->booking_reference }}</div>
          <div class="text-sm font-bold">{{ $nextTrip->trip?->route?->originCity?->name }} → {{ $nextTrip->trip?->route?->destinationCity?->name }}</div>
          <div class="text-xs text-primary-200 mt-1 flex items-center gap-1">
            <i data-lucide="calendar" style="width:11px;height:11px"></i>
            {{ $nextTrip->trip?->departure_time?->format('M j, Y · g:i A') }}
          </div>
          <div class="mt-3 flex items-center justify-between">
            <span class="text-xs font-bold bg-white/20 px-2 py-0.5 rounded-full">Seat {{ $nextTrip->seat_number }}</span>
            <span class="text-xs font-extrabold">₱{{ number_format($nextTrip->amount_paid, 0) }}</span>
          </div>
        </div>
      @endif

      {{-- Quick nav --}}
      <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden">
        @foreach([
          ['manage.bookings',  'all',       'all',       'All Bookings',   'ticket',    $counts->total],
          ['manage.bookings',  'confirmed', 'confirmed', 'Upcoming',       'clock',     $counts->confirmed],
          ['manage.bookings',  'completed', 'completed', 'Completed',      'check-circle', $counts->completed],
          ['manage.bookings',  'cancelled', 'cancelled', 'Cancelled',      'x-circle',  $counts->cancelled],
          ['manage.bookings',  'pending',   'pending',   'Pending',        'hourglass', $counts->pending],
        ] as [$r,$q,$cur,$lbl,$icon,$cnt])
          <a href="{{ route($r) }}?status={{ $q }}"
             class="flex items-center justify-between px-4 py-3 text-sm hover:bg-slate-50 transition-colors border-b border-slate-100 last:border-0
                    {{ ($status ?? 'all') === $cur ? 'bg-primary-50 text-primary-700 font-semibold' : 'text-slate-700' }}">
            <div class="flex items-center gap-2.5">
              <i data-lucide="{{ $icon }}" style="width:14px;height:14px"></i> {{ $lbl }}
            </div>
            <span class="text-xs font-bold bg-slate-100 text-slate-500 px-2 py-0.5 rounded-full">{{ $cnt }}</span>
          </a>
        @endforeach
      </div>

      {{-- Notifications --}}
      @if($unreadCount > 0)
        <div class="bg-amber-50 border border-amber-200 rounded-2xl p-4">
          <div class="flex items-center gap-2 mb-2">
            <i data-lucide="bell" style="width:14px;height:14px;color:#d97706"></i>
            <span class="text-xs font-bold text-amber-700">{{ $unreadCount }} Unread</span>
          </div>
          <form method="POST" action="{{ route('manage.bookings.notifications.read') }}">
            @csrf
            <button type="submit" class="text-xs text-amber-600 hover:text-amber-700 font-semibold">
              Mark all as read →
            </button>
          </form>
        </div>
      @endif

    </aside>

    {{-- ── MAIN ────────────────────────────────────────────────────── --}}
    <div class="flex-1 min-w-0">

      {{-- Search + sort bar --}}
      <div class="flex flex-wrap items-center gap-3 mb-6">
        <div class="relative flex-1 min-w-[200px]">
          <i data-lucide="search" style="width:13px;height:13px;position:absolute;left:10px;top:50%;transform:translateY(-50%);color:#94a3b8"></i>
          <input type="text" placeholder="Search by reference or route…"
                 oninput="filterBookings(this.value)"
                 class="w-full pl-8 pr-4 py-2.5 text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500">
        </div>
        <select onchange="location.href='?status={{ $status ?? 'all' }}&sort='+this.value"
                class="py-2.5 px-3 text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500 bg-white">
          <option value="newest"    {{ ($sort??'newest')==='newest'    ? 'selected' : '' }}>Newest First</option>
          <option value="oldest"    {{ ($sort??'')==='oldest'    ? 'selected' : '' }}>Oldest First</option>
          <option value="departure" {{ ($sort??'')==='departure' ? 'selected' : '' }}>By Departure</option>
        </select>
      </div>

      {{-- Booking cards --}}
      <div id="bookings-list" class="space-y-4">
        @forelse($bookings as $booking)
          @php
            $origin  = $booking->trip?->route?->originCity?->name ?? '—';
            $dest    = $booking->trip?->route?->destinationCity?->name ?? '—';
            $dep     = $booking->trip?->departure_time;
            $isPast  = $dep?->isPast();
            $statusMap = [
              'confirmed' => ['bg-blue-100 text-blue-700',   'clock',        'Confirmed'],
              'pending'   => ['bg-amber-100 text-amber-700', 'hourglass',    'Pending'],
              'completed' => ['bg-emerald-100 text-emerald-700','check-circle','Completed'],
              'cancelled' => ['bg-red-100 text-red-700',     'x-circle',     'Cancelled'],
            ];
            [$sbg,$sicon,$slabel] = $statusMap[$booking->status] ?? ['bg-slate-100 text-slate-600','circle','Unknown'];
          @endphp
          <div class="booking-card bg-white border border-slate-200 rounded-2xl overflow-hidden
                      hover:shadow-md transition-shadow"
               data-ref="{{ strtolower($booking->booking_reference) }}"
               data-route="{{ strtolower($origin.' '.$dest) }}">

            {{-- Card header --}}
            <div class="flex items-center justify-between px-5 py-3 border-b border-slate-100 bg-slate-50">
              <div class="flex items-center gap-2">
                <span class="text-xs font-mono font-bold text-slate-600">{{ $booking->booking_reference }}</span>
                <span class="inline-flex items-center gap-1 text-xs font-semibold px-2 py-0.5 rounded-full {{ $sbg }}">
                  <i data-lucide="{{ $sicon }}" style="width:10px;height:10px"></i>
                  {{ $slabel }}
                </span>
              </div>
              <span class="text-xs text-slate-400">{{ $booking->created_at->format('M j, Y') }}</span>
            </div>

            {{-- Card body --}}
            <div class="p-5 flex flex-wrap gap-5 items-start">

              {{-- Route --}}
              <div class="flex items-center gap-3 flex-1 min-w-[200px]">
                <div class="w-9 h-9 bg-primary-50 rounded-xl flex items-center justify-center shrink-0">
                  <i data-lucide="bus" style="width:16px;height:16px;color:#ea580c"></i>
                </div>
                <div class="min-w-0">
                  <div class="text-sm font-bold text-slate-900 truncate">{{ $origin }} → {{ $dest }}</div>
                  <div class="text-xs text-slate-400 mt-0.5 flex items-center gap-1">
                    <i data-lucide="calendar" style="width:10px;height:10px"></i>
                    {{ $dep?->format('D, M j Y · g:i A') ?? '—' }}
                  </div>
                  <div class="text-xs text-slate-400 flex items-center gap-1 mt-0.5">
                    <i data-lucide="armchair" style="width:10px;height:10px"></i>
                    Seat {{ $booking->seat_list }}
                    @if($booking->seat_count > 1)
                      <span class="text-primary-600 font-semibold">({{ $booking->seat_count }} seats)</span>
                    @endif
                  </div>
                </div>
              </div>

              {{-- Payment --}}
              <div class="text-right shrink-0">
                <div class="text-xs text-slate-400">Amount Paid</div>
                <div class="text-xl font-extrabold text-slate-900">{{ $booking->formatted_amount_paid }}</div>
                @if($booking->discount_amount > 0)
                  <div class="text-xs text-emerald-600 font-semibold flex items-center gap-1 justify-end">
                    <i data-lucide="badge-percent" style="width:10px;height:10px"></i>
                    Saved ₱{{ number_format($booking->discount_amount, 2) }}
                  </div>
                @endif
                <span class="text-xs font-semibold {{ $booking->payment_status === 'paid' ? 'text-emerald-600' : 'text-amber-600' }}">
                  {{ ucfirst($booking->payment_status) }}
                </span>
              </div>

            </div>

            {{-- Card footer --}}
            <div class="flex flex-wrap items-center justify-between gap-3 px-5 py-3 border-t border-slate-100 bg-slate-50">
              <div class="flex flex-wrap gap-2">
                {{-- Download ticket --}}
                @if(in_array($booking->status, ['confirmed','completed']))
                  <a href="#"
                     class="inline-flex items-center gap-1.5 text-xs font-semibold text-primary-600 hover:text-primary-700">
                    <i data-lucide="download" style="width:12px;height:12px"></i> E-Ticket
                  </a>
                @endif
                {{-- View details --}}
                <button onclick="openBookingDetail({{ $booking->id }})"
                        class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-600 hover:text-slate-800">
                  <i data-lucide="eye" style="width:12px;height:12px"></i> Details
                </button>
              </div>

              {{-- Cancel --}}
              @if(in_array($booking->status, ['pending','confirmed']) && !$isPast)
                <button onclick="confirmCancel({{ $booking->id }}, '{{ $booking->booking_reference }}')"
                        class="inline-flex items-center gap-1.5 text-xs font-semibold text-red-500 hover:text-red-600">
                  <i data-lucide="x-circle" style="width:12px;height:12px"></i> Cancel Booking
                </button>
              @endif
            </div>

          </div>
        @empty
          <div class="text-center py-20">
            <div class="w-16 h-16 bg-slate-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
              <i data-lucide="ticket" style="width:26px;height:26px;color:#94a3b8"></i>
            </div>
            <h3 class="text-base font-bold text-slate-800 mb-2">No bookings yet</h3>
            <p class="text-sm text-slate-500 mb-5">You haven't booked any trips yet. Let's change that!</p>
            <a href="{{ route('landing.ticket_booking') }}"
               class="inline-flex items-center gap-2 px-5 py-2.5 bg-primary-600 text-white text-sm font-bold rounded-xl hover:bg-primary-700 transition-colors">
              <i data-lucide="search" style="width:14px;height:14px"></i> Find a Trip
            </a>
          </div>
        @endforelse
      </div>

      {{-- Pagination --}}
      @if($bookings->hasPages())
        <div class="mt-8">{{ $bookings->withQueryString()->links() }}</div>
      @endif

    </div>
  </div>
</div>

{{-- Cancel confirmation modal --}}
<div id="cancel-modal"
     class="hidden fixed inset-0 z-[9999] items-center justify-center modal-bg"
     onclick="if(event.target===this)closeModal('cancel-modal')">
  <div class="bg-white rounded-2xl shadow-2xl w-full max-w-sm mx-4 p-6 animate-slide-up">
    <div class="w-12 h-12 bg-red-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
      <i data-lucide="alert-triangle" style="width:22px;height:22px;color:#ef4444"></i>
    </div>
    <h3 class="text-base font-extrabold text-slate-900 text-center mb-1">Cancel Booking?</h3>
    <p id="cancel-ref" class="text-sm text-slate-500 text-center mb-5"></p>
    <div class="mb-4">
      <label class="block text-xs font-semibold text-slate-700 mb-1.5">Reason (optional)</label>
      <select id="cancel-reason" class="w-full py-2.5 px-3 text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500">
        <option value="Customer request">Customer request</option>
        <option value="Change of plans">Change of plans</option>
        <option value="Duplicate booking">Duplicate booking</option>
        <option value="Other">Other</option>
      </select>
    </div>
    <div class="flex gap-3">
      <button onclick="closeModal('cancel-modal')"
              class="flex-1 py-2.5 border border-slate-200 text-sm font-semibold rounded-xl text-slate-700 hover:bg-slate-50 transition-colors">
        Keep Booking
      </button>
      <form id="cancel-form" method="POST" class="flex-1">
        @csrf
        <input type="hidden" name="reason" id="cancel-reason-input">
        <button type="submit"
                class="w-full py-2.5 bg-red-500 hover:bg-red-600 text-white text-sm font-bold rounded-xl transition-colors">
          Yes, Cancel
        </button>
      </form>
    </div>
  </div>
{{-- Detail Modal --}}
<div id="detail-modal"
     class="hidden fixed inset-0 z-[9999] items-center justify-center modal-bg"
     onclick="if(event.target===this)closeModal('detail-modal')">
  <div class="bg-white rounded-2xl shadow-2xl w-full max-w-lg mx-4 overflow-hidden animate-slide-up flex flex-col max-h-[90vh]">
    <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50 shrink-0">
      <h3 class="font-bold text-slate-800 flex items-center gap-2">
        <i data-lucide="ticket" style="width:18px;height:18px;color:#ea580c"></i>
        Booking Details
      </h3>
      <button onclick="closeModal('detail-modal')" class="p-1 text-slate-400 hover:text-slate-600 transition-colors">
        <i data-lucide="x" style="width:20px;height:20px"></i>
      </button>
    </div>
    
    <div class="p-6 overflow-y-auto">
      <div class="flex items-center justify-between mb-6">
        <div>
          <p class="text-xs text-slate-400 uppercase tracking-wider font-bold mb-1">Reference No.</p>
          <p id="dt-ref" class="text-lg font-mono font-extrabold text-slate-900"></p>
        </div>
        <div id="dt-status-badge" class="px-3 py-1 rounded-full text-xs font-bold inline-flex items-center gap-1.5">
          <!-- populated via js -->
        </div>
      </div>

      <div class="bg-slate-50 border border-slate-100 rounded-xl p-4 mb-6">
        <div class="grid grid-cols-2 gap-4">
          <div>
            <p class="text-xs text-slate-500 mb-1">Route</p>
            <p id="dt-route" class="text-sm font-bold text-slate-800"></p>
          </div>
          <div>
            <p class="text-xs text-slate-500 mb-1">Departure</p>
            <p id="dt-dep" class="text-sm font-bold text-slate-800"></p>
          </div>
          <div>
            <p class="text-xs text-slate-500 mb-1">Seat(s)</p>
            <p id="dt-seats" class="text-sm font-bold text-slate-800"></p>
          </div>
          <div>
            <p class="text-xs text-slate-500 mb-1">Bus Type</p>
            <p id="dt-bus" class="text-sm font-bold text-slate-800"></p>
          </div>
        </div>
      </div>

      <div>
        <h4 class="text-sm font-bold text-slate-800 mb-3 border-b border-slate-100 pb-2">Payment Summary</h4>
        <div class="space-y-2 text-sm">
          <div class="flex justify-between">
            <span class="text-slate-500">Base Fare</span>
            <span id="dt-base" class="font-medium text-slate-800"></span>
          </div>
          <div id="dt-discount-row" class="flex justify-between text-emerald-600 hidden">
            <span>Discount</span>
            <span id="dt-discount" class="font-medium"></span>
          </div>
          <div class="flex justify-between border-t border-slate-100 pt-2 mt-2">
            <span class="font-bold text-slate-800">Total Paid</span>
            <span id="dt-total" class="font-extrabold text-slate-900"></span>
          </div>
        </div>
      </div>
    </div>
    
    <div class="p-4 border-t border-slate-100 bg-slate-50 shrink-0">
      <button onclick="closeModal('detail-modal')" class="w-full py-2.5 bg-slate-200 hover:bg-slate-300 text-slate-800 text-sm font-bold rounded-xl transition-colors">
        Close
      </button>
    </div>
  </div>
</div>

@endsection

@push('scripts')
<script>
  const bookingsData = @json($bookings->items());

  function filterBookings(q) {
    q = q.toLowerCase();
    document.querySelectorAll('.booking-card').forEach(card => {
      const match = card.dataset.ref.includes(q) || card.dataset.route.includes(q);
      card.style.display = match ? '' : 'none';
    });
  }

  function confirmCancel(id, ref) {
    document.getElementById('cancel-ref').textContent = 'Booking ' + ref + ' will be cancelled. This cannot be undone.';
    document.getElementById('cancel-form').action = '/my-bookings/' + id + '/cancel';
    openModal('cancel-modal');
  }

  document.getElementById('cancel-form')?.addEventListener('submit', function() {
    document.getElementById('cancel-reason-input').value = document.getElementById('cancel-reason').value;
  });

  function openBookingDetail(id) {
    const booking = bookingsData.find(b => b.id === id);
    if(!booking) return;

    document.getElementById('dt-ref').textContent = booking.booking_reference;
    
    // Status
    const sMap = {
      'confirmed': { bg: 'bg-blue-100', text: 'text-blue-700', icon: 'clock', label: 'Confirmed' },
      'pending':   { bg: 'bg-amber-100', text: 'text-amber-700', icon: 'hourglass', label: 'Pending' },
      'completed': { bg: 'bg-emerald-100', text: 'text-emerald-700', icon: 'check-circle', label: 'Completed' },
      'cancelled': { bg: 'bg-red-100', text: 'text-red-700', icon: 'x-circle', label: 'Cancelled' }
    };
    const s = sMap[booking.status] || { bg: 'bg-slate-100', text: 'text-slate-600', icon: 'circle', label: booking.status };
    
    document.getElementById('dt-status-badge').className = `px-3 py-1 rounded-full text-xs font-bold inline-flex items-center gap-1.5 ${s.bg} ${s.text}`;
    document.getElementById('dt-status-badge').innerHTML = `<i data-lucide="${s.icon}" style="width:12px;height:12px"></i> ${s.label}`;
    
    // Route & Bus
    const origin = booking.trip?.route?.origin_city?.name || '—';
    const dest = booking.trip?.route?.destination_city?.name || '—';
    document.getElementById('dt-route').textContent = `${origin} → ${dest}`;
    
    document.getElementById('dt-bus').textContent = booking.trip?.bus?.type?.name || 'Standard Bus';
    
    // Dates
    if(booking.trip?.departure_time) {
      const d = new Date(booking.trip.departure_time);
      document.getElementById('dt-dep').textContent = d.toLocaleString('en-US', { weekday: 'short', month: 'short', day: 'numeric', year: 'numeric', hour: 'numeric', minute: '2-digit', hour12: true });
    } else {
      document.getElementById('dt-dep').textContent = '—';
    }

    // Seats
    let seats = '—';
    if(booking.booking_seats && booking.booking_seats.length > 0) {
      seats = booking.booking_seats.map(s => s.seat_number).join(', ');
    } else if (booking.seat_number) {
      seats = booking.seat_number;
    }
    document.getElementById('dt-seats').textContent = seats;

    // Payment
    document.getElementById('dt-base').textContent = '₱' + parseFloat(booking.base_fare || 0).toLocaleString('en-US', {minimumFractionDigits: 2});
    document.getElementById('dt-total').textContent = '₱' + parseFloat(booking.amount_paid || 0).toLocaleString('en-US', {minimumFractionDigits: 2});
    
    const disc = parseFloat(booking.discount_amount || 0);
    const discRow = document.getElementById('dt-discount-row');
    if(disc > 0) {
      discRow.classList.remove('hidden');
      document.getElementById('dt-discount').textContent = '-₱' + disc.toLocaleString('en-US', {minimumFractionDigits: 2});
    } else {
      discRow.classList.add('hidden');
    }

    lucide.createIcons();
    openModal('detail-modal');
  }
</script>
@endpush