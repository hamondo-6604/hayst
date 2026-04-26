@extends('layouts.app')
@section('title', 'Select Seats — Mindanao Express')

@push('head')
<style>
  *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
  :root {
    --ink:#0e1117; --ink-mid:#1a2235; --ink-soft:#2e3a52;
    --gold:#b8912a; --gold-lt:#d4a843;
    --gold-bg:rgba(184,145,42,.08); --gold-line:rgba(184,145,42,.2);
    --red:#c0392b; --red-bg:rgba(192,57,43,.07);
    --bg:#f9f7f4; --bg-2:#f2ede6; --bg-3:#ffffff;
    --border:#e4ddd3; --border-dk:#ccc4b8;
    --muted:#7a7468; --muted-lt:#a09890; --text:#1a1612;
    --green:#059669; --green-bg:rgba(5,150,105,.08);
    --blue:#2563eb; --blue-bg:rgba(37,99,235,.07);
    --orange:#ea580c; --orange-bg:rgba(234,88,12,.08);
    --nav-h:70px; --radius:14px;
    --shadow-sm:0 2px 12px rgba(14,17,23,.06);
    --shadow-md:0 8px 32px rgba(14,17,23,.10);
    --shadow-lg:0 20px 60px rgba(14,17,23,.14);
  }
  html { scroll-behavior:smooth; }
  body { font-family:'Outfit',sans-serif; background:var(--bg); color:var(--text); overflow-x:hidden; }

  /* ══ NAVBAR ══ */
  #nav {
    position:fixed; top:0; left:0; right:0; height:var(--nav-h); z-index:900;
    background:rgba(249,247,244,.95); backdrop-filter:blur(18px) saturate(1.4);
    border-bottom:1px solid var(--border); box-shadow:var(--shadow-sm);
  }
  .nav-wrap { max-width:1260px; margin:0 auto; height:100%; display:flex; align-items:center; padding:0 32px; }
  .logo { display:flex; align-items:center; gap:10px; text-decoration:none; flex-shrink:0; margin-right:44px; cursor:pointer; }
  .logo-mark { width:38px; height:38px; border-radius:9px; background:var(--ink); display:flex; align-items:center; justify-content:center; }
  .logo-mark svg { width:20px; height:20px; fill:none; stroke:var(--gold-lt); stroke-width:1.8; stroke-linecap:round; }
  .logo-mark i { font-size:1.05rem; color:var(--gold-lt); }
  .logo-wordmark { font-family:'Playfair Display',serif; font-size:1.2rem; font-weight:800; color:var(--ink); letter-spacing:-.3px; }
  .logo-wordmark span { color:var(--gold); }

  /* ══ PAGE HEADER ══ */
  .page-header {
    padding-top:calc(var(--nav-h) + 52px); padding-bottom:48px; padding-left:32px; padding-right:32px;
    background:linear-gradient(160deg,#fff 0%,var(--bg) 100%); position:relative; overflow:hidden;
  }
  .page-header::before { content:''; position:absolute; top:0; right:0; width:55%; height:100%; background:radial-gradient(ellipse 70% 70% at 80% 40%,rgba(184,145,42,.07) 0%,transparent 65%); pointer-events:none; }
  .ph-inner { max-width:1260px; margin:0 auto; position:relative; z-index:1; display:flex; align-items:flex-end; justify-content:space-between; gap:24px; flex-wrap:wrap; }
  .breadcrumb { display:flex; align-items:center; gap:8px; font-size:.75rem; color:var(--muted-lt); margin-bottom:20px; }
  .breadcrumb a { color:var(--muted); text-decoration:none; font-weight:500; cursor:pointer; }
  .breadcrumb a:hover { color:var(--gold); }
  .breadcrumb .sep { color:var(--border-dk); }
  .breadcrumb .cur { color:var(--gold); font-weight:600; }
  .ph-eyebrow { display:inline-flex; align-items:center; gap:8px; font-size:.72rem; font-weight:700; letter-spacing:2.5px; text-transform:uppercase; color:var(--gold); margin-bottom:12px; }
  .ph-eyebrow::before { content:''; width:28px; height:1.5px; background:var(--gold); }
  .ph-heading { font-family:'Playfair Display',serif; font-size:clamp(2rem,3.5vw,2.8rem); font-weight:800; line-height:1.1; letter-spacing:-.3px; color:var(--ink); }
  .ph-heading em { font-style:italic; color:var(--gold); }
  .ph-sub { color:var(--muted); font-size:.95rem; line-height:1.7; margin-top:10px; max-width:480px; }

  /* ══ MAIN LAYOUT ══ */
  .page-body { max-width:1260px; margin:0 auto; padding:40px 32px 80px; display:grid; grid-template-columns:320px 1fr; gap:32px; align-items:start; }

  /* ══ SIDEBAR ══ */
  .sidebar { position:sticky; top:calc(var(--nav-h) + 20px); display:flex; flex-direction:column; gap:16px; }

  .trip-summary-card { background:var(--bg-3); border:1px solid var(--border); border-radius:18px; overflow:hidden; box-shadow:var(--shadow-sm); }
  .trip-card-head { background:var(--ink); padding:24px 20px; text-align:center; position:relative; }
  .trip-card-head::before { content:''; position:absolute; top:-30px; left:50%; transform:translateX(-50%); width:200px; height:200px; border-radius:50%; background:radial-gradient(circle,rgba(184,145,42,.18) 0%,transparent 65%); }
  .trip-avatar { width:64px; height:64px; border-radius:50%; background:var(--gold-bg); border:3px solid rgba(255,255,255,.15); display:flex; align-items:center; justify-content:center; font-family:'Playfair Display',serif; font-size:1.4rem; font-weight:800; color:var(--gold-lt); margin:0 auto 12px; position:relative; z-index:1; }
  .trip-name { font-family:'Playfair Display',serif; font-size:1rem; font-weight:800; color:#fff; margin-bottom:4px; }
  .trip-route { font-size:.75rem; color:rgba(255,255,255,.45); }
  .trip-verified { display:inline-flex; align-items:center; gap:5px; background:rgba(5,150,105,.18); border:1px solid rgba(5,150,105,.3); padding:3px 10px; border-radius:50px; font-size:.68rem; font-weight:700; color:#34d399; margin-top:8px; }
  .trip-card-body { padding:16px; }
  .trip-stat-row { display:grid; grid-template-columns:1fr 1fr; gap:8px; margin-bottom:12px; }
  .trip-stat { text-align:center; padding:10px 6px; background:var(--bg); border-radius:10px; border:1px solid var(--border); }
  .trip-stat-num { font-family:'Playfair Display',serif; font-size:1.1rem; font-weight:800; color:var(--ink); }
  .trip-stat-label { font-size:.62rem; color:var(--muted); margin-top:2px; text-transform:uppercase; letter-spacing:.5px; }

  .selected-seats-card { background:var(--ink); border-radius:14px; padding:20px; }
  .selected-seats-card h4 { font-family:'Playfair Display',serif; font-size:.95rem; font-weight:800; color:#fff; margin-bottom:6px; }
  .selected-seats-card p { font-size:.78rem; color:rgba(255,255,255,.45); margin-bottom:14px; line-height:1.5; }
  .selected-seats-list { background:var(--gold-bg); border:1px solid var(--gold-line); border-radius:10px; padding:14px; margin-bottom:16px; min-height:60px; }
  .selected-seats-list.empty { display:flex; align-items:center; justify-content:center; color:var(--muted); font-size:.82rem; font-style:italic; }
  .selected-seats-list.has-seats { color:var(--gold); font-weight:600; font-family:'Playfair Display',serif; font-size:.95rem; text-align:center; }
  .total-fare { text-align:center; margin-bottom:16px; }
  .total-fare .amount { font-family:'Playfair Display',serif; font-size:1.8rem; font-weight:800; color:var(--gold-lt); }
  .total-fare .label { font-size:.68rem; color:rgba(255,255,255,.45); text-transform:uppercase; letter-spacing:1px; }
  .quick-btn { width:100%; background:var(--gold); border:none; color:var(--ink); padding:10px; border-radius:8px; font-weight:700; font-size:.82rem; cursor:pointer; font-family:'Outfit',sans-serif; transition:all .18s; }
  .quick-btn:hover { background:var(--gold-lt); transform:translateY(-1px); }
  .quick-btn:disabled { background:var(--muted); cursor:not-allowed; transform:none; }

  /* ══ MAIN CONTENT ══ */
  .main-col { display:flex; flex-direction:column; gap:24px; }

  /* ── Seat Map Container ── */
  .seat-map-container { background:var(--bg-3); border:1px solid var(--border); border-radius:18px; overflow:hidden; box-shadow:var(--shadow-sm); }
  .seat-map-header { padding:22px 28px; border-bottom:1px solid var(--border); background:var(--bg); }
  .seat-map-header h3 { font-family:'Playfair Display',serif; font-size:1.2rem; font-weight:800; color:var(--ink); margin-bottom:4px; }
  .seat-map-header p { font-size:.8rem; color:var(--muted); }
  .seat-map-body { padding:28px; }
  
  .driver-area { background:linear-gradient(135deg, var(--ink) 0%, var(--ink-mid) 100%); color: white; padding:15px; border-radius:12px; text-align:center; margin-bottom:20px; font-weight:600; display:flex; align-items:center; justify-content:center; gap:8px; }
  
  .seat-legend { display:flex; gap:20px; justify-content:center; margin-bottom:20px; flex-wrap:wrap; }
  .legend-item { display:flex; align-items:center; gap:8px; font-size:14px; padding:8px 16px; background:var(--bg); border:1px solid var(--border); border-radius:8px; }
  .legend-seat { width:24px; height:24px; border-radius:4px; border:2px solid; }
  .legend-seat.available { background:#f0fdf4; border-color:#22c55e; }
  .legend-seat.selected { background:var(--orange); border-color:var(--orange); }
  .legend-seat.booked { background:#f8fafc; border-color:#94a3b8; }
  
  .seat-grid { background:var(--bg); border:1px solid var(--border); border-radius:14px; padding:20px; margin-bottom:20px; }
  .seat-row { display:flex; align-items:center; justify-content:center; margin-bottom:4px; gap:2px; }
  .seat { width:40px; height:40px; border:2px solid var(--border); border-radius:8px; cursor:pointer; display:flex; align-items:center; justify-content:center; font-size:11px; font-weight:600; transition:all 0.2s; background:white; }
  .seat:hover:not(.booked):not(.selected) { border-color:var(--orange); transform:scale(1.05); }
  .seat.available { background:#f0fdf4; border-color:#22c55e; color:#16a34a; }
  .seat.selected { background:var(--orange); border-color:var(--orange); color:white; transform:scale(1.05); }
  .seat.booked { background:#f8fafc; border-color:#94a3b8; color:#64748b; cursor:not-allowed; }
  .aisle { width:40px; height:40px; margin:2px; background:var(--bg-2); border-radius:4px; }
  .row-number { width:24px; text-align:center; font-size:11px; color:var(--muted); font-weight:600; }
  
  .action-bar { padding:20px 28px; border-top:1px solid var(--border); background:var(--bg); display:flex; gap:12px; justify-content:space-between; align-items:center; }
  .action-buttons { display:flex; gap:12px; }
  .btn { padding:10px 22px; border-radius:9px; font-size:.88rem; font-weight:700; cursor:pointer; font-family:'Outfit',sans-serif; transition:all .18s; border:none; display:inline-flex; align-items:center; gap:8px; text-decoration:none; }
  .btn-ghost { background:none; border:1.5px solid var(--border-dk); color:var(--ink); }
  .btn-ghost:hover { border-color:var(--ink); background:var(--bg-2); }
  .btn-primary { background:var(--ink); color:#fff; }
  .btn-primary:hover { background:var(--ink-mid); transform:translateY(-1px); box-shadow:0 6px 18px rgba(14,17,23,.18); }
  .btn-gold { background:var(--gold); color:var(--ink); }
  .btn-gold:hover { background:var(--gold-lt); transform:translateY(-1px); }
  .btn:disabled { background:var(--muted); cursor:not-allowed; transform:none; }

  /* ══ MODALS ══ */
  .modal-overlay { position:fixed; inset:0; background:rgba(14,17,23,.55); backdrop-filter:blur(6px); z-index:1000; display:flex; align-items:center; justify-content:center; opacity:0; pointer-events:none; transition:opacity .25s; padding:16px; }
  .modal-overlay.open { opacity:1; pointer-events:all; }
  .modal-box { background:var(--bg-3); border-radius:20px; width:100%; max-width:520px; box-shadow:var(--shadow-lg); transform:scale(.95) translateY(12px); transition:transform .28s cubic-bezier(.34,1.56,.64,1); position:relative; overflow:hidden; }
  .modal-overlay.open .modal-box { transform:scale(1) translateY(0); }
  .modal-head { padding:22px 28px; border-bottom:1px solid var(--border); display:flex; align-items:center; justify-content:space-between; gap:12px; }
  .modal-head h3 { font-family:'Playfair Display',serif; font-size:1.2rem; font-weight:800; color:var(--ink); }
  .modal-head p { font-size:.8rem; color:var(--muted); margin-top:3px; }
  .modal-close { background:none; border:none; font-size:1.1rem; cursor:pointer; color:var(--muted); width:32px; height:32px; border-radius:50%; display:flex; align-items:center; justify-content:center; transition:background .15s; flex-shrink:0; }
  .modal-close:hover { background:var(--bg-2); }
  .modal-body { padding:24px 28px; }
  .modal-foot { padding:18px 28px; border-top:1px solid var(--border); display:flex; gap:10px; justify-content:flex-end; }

  /* ══ TOAST ══ */
  .toast { position:fixed; bottom:32px; right:32px; z-index:9999; background:var(--ink); color:#fff; padding:14px 22px; border-radius:12px; font-size:.88rem; font-weight:600; box-shadow:var(--shadow-lg); display:flex; align-items:center; gap:10px; transform:translateY(80px); opacity:0; transition:all .3s cubic-bezier(.34,1.56,.64,1); pointer-events:none; }
  .toast.show { transform:translateY(0); opacity:1; }

  /* ══ RESPONSIVE ══ */
  @media (max-width:900px) { .page-body { grid-template-columns:1fr; } .sidebar { position:static; } }
  @media (max-width:640px) {
    .page-body { padding:24px 16px 60px; }
    .seat { width:32px; height:32px; font-size:9px; }
    .aisle { width:32px; height:32px; }
    .action-bar { flex-direction:column; gap:16px; }
    .action-buttons { width:100%; justify-content:stretch; }
    .btn { flex:1; justify-content:center; }
  }
</style>
@endpush

@section('content')
<!-- ══ PAGE HEADER ══ -->
<div class="page-header">
  <div class="ph-inner">
    <div>
      <nav class="breadcrumb">
        <a href="{{ route('landing.home') }}">Home</a>
        <span class="sep">/</span>
        <a href="{{ route('landing.ticket_booking') }}">Search Trips</a>
        <span class="sep">/</span>
        <span class="cur">Select Seats</span>
      </nav>
      <div class="ph-eyebrow">Seat Selection</div>
      <h1 class="ph-heading">Choose Your <em>Seats</em></h1>
      <p class="ph-sub">Select your preferred seats for a comfortable journey. Click on available seats to reserve them.</p>
    </div>
  </div>
</div>

<!-- ══ BODY ══ -->
<div class="page-body">

  <!-- ── SIDEBAR ── -->
  <aside class="sidebar">

    <!-- Trip Summary Card -->
    <div class="trip-summary-card">
      <div class="trip-card-head">
        <div class="trip-avatar">🚌</div>
        <div class="trip-name">{{ $trip->route->originCity->name }} → {{ $trip->route->destinationCity->name }}</div>
        <div class="trip-route">{{ $trip->trip_date->format('D, M j, Y') }} • {{ $trip->departure_time->format('H:i') }}</div>
        <div class="trip-verified">✓ Available Trip</div>
      </div>
      <div class="trip-card-body">
        <div class="trip-stat-row">
          <div class="trip-stat">
            <div class="trip-stat-num">₱{{ number_format($trip->fare, 0) }}</div>
            <div class="trip-stat-label">Per Seat</div>
          </div>
          <div class="trip-stat">
            <div class="trip-stat-num">{{ $trip->available_seats }}</div>
            <div class="trip-stat-label">Available</div>
          </div>
        </div>
        <div class="trip-stat-row">
          <div class="trip-stat" style="grid-column: 1 / -1;">
            <div class="trip-stat-num">{{ $trip->bus?->bus_name ?? 'Mindanao Express Bus' }}</div>
            <div class="trip-stat-label">{{ $trip->bus?->type?->type_name ?? 'Standard' }}</div>
          </div>
        </div>
      </div>
    </div>

    <!-- Selected Seats Card -->
    <div class="selected-seats-card">
      <h4>Selected Seats</h4>
      <p>Your chosen seats will appear here</p>
      <div class="selected-seats-list empty" id="selected-seats-display">
        No seats selected
      </div>
      <div class="total-fare">
        <div class="amount" id="total-fare">₱0</div>
        <div class="label">Total Fare</div>
      </div>
      <form id="seat-selection-form" action="{{ route('user.book.seats', $trip->id) }}" method="POST">
        @csrf
        <input type="hidden" name="selected_seats" id="selected-seats-input" value="">
        <button type="submit" id="book-button" disabled class="quick-btn">
          Continue to Payment
        </button>
      </form>
    </div>

  </aside>

  <!-- ── MAIN CONTENT ── -->
  <div class="main-col">

    <!-- Seat Map Container -->
    <div class="seat-map-container">
      <div class="seat-map-header">
        <h3>Seat Selection</h3>
        <p>Click on available seats to select them for your journey</p>
      </div>
      <div class="seat-map-body">
        
        <!-- Driver Area -->
        <div class="driver-area">
          <i data-lucide="steering-wheel" style="width:24px;height:24px"></i>
          DRIVER AREA
        </div>
        
        <!-- Seat Legend -->
        <div class="seat-legend">
          <div class="legend-item">
            <div class="legend-seat available"></div>
            <span>Available</span>
          </div>
          <div class="legend-item">
            <div class="legend-seat selected"></div>
            <span>Selected</span>
          </div>
          <div class="legend-item">
            <div class="legend-seat booked"></div>
            <span>Booked</span>
          </div>
        </div>
        
        <!-- Seat Grid -->
        <div class="seat-grid">
          @if(!empty($seatMap))
            @foreach($seatMap as $rowIndex => $row)
              <div class="seat-row">
                <div class="row-number">{{ $rowIndex + 1 }}</div>
                @foreach($row as $colIndex => $seat)
                  @if($seat['type'] === 'aisle')
                    <div class="aisle"></div>
                  @else
                    <div class="seat {{ $seat['status'] }}" 
                         data-seat="{{ $seat['seat_number'] }}"
                         data-status="{{ $seat['status'] }}"
                         onclick="toggleSeat(this)">
                      {{ $seat['seat_number'] }}
                    </div>
                  @endif
                @endforeach
                <div class="row-number">{{ $rowIndex + 1 }}</div>
              </div>
            @endforeach
          @else
            <div style="text-align:center; padding:40px; color:var(--muted);">
              <div style="font-size:3rem; margin-bottom:16px; opacity:.6;">🪑</div>
              <div style="font-family:'Playfair Display',serif; font-size:1.4rem; font-weight:800; color:var(--ink); margin-bottom:8px;">Seat Layout Unavailable</div>
              <div style="font-size:.9rem; color:var(--muted); max-width:340px; margin:0 auto; line-height:1.7;">The seat layout for this trip is not currently available. Please contact support for assistance.</div>
            </div>
          @endif
        </div>
      </div>
      
      <!-- Action Bar -->
      <div class="action-bar">
        <a href="{{ route('landing.ticket_booking') }}" class="btn btn-ghost">
          ← Back to Trips
        </a>
        <div class="action-buttons">
          <button type="button" onclick="clearSelection()" class="btn btn-ghost">
            Clear Selection
          </button>
          <button type="submit" form="seat-selection-form" id="action-book-button" disabled class="btn btn-primary">
            Continue to Payment →
          </button>
        </div>
      </div>
    </div>

  </div>
</div>

<!-- Toast -->
<div class="toast" id="toast"><span id="ti">✓</span>&nbsp;<span id="tm">Done!</span></div>

@endsection

@push('scripts')
<script>
let selectedSeats = [];
const farePerSeat = {{ $trip->fare }};

function toggleSeat(element) {
  const seatNumber = element.dataset.seat;
  const status = element.dataset.status;
  
  // Don't allow selecting booked seats
  if (status === 'booked') {
    showToast('⚠', 'This seat is already booked');
    return;
  }
  
  if (element.classList.contains('selected')) {
    // Deselect seat
    element.classList.remove('selected');
    element.classList.add('available');
    selectedSeats = selectedSeats.filter(seat => seat !== seatNumber);
  } else {
    // Select seat
    element.classList.remove('available');
    element.classList.add('selected');
    selectedSeats.push(seatNumber);
  }
  
  updateSelectedSeatsInfo();
}

function updateSelectedSeatsInfo() {
  const displayDiv = document.getElementById('selected-seats-display');
  const totalFare = document.getElementById('total-fare');
  const bookButton = document.getElementById('book-button');
  const actionBookButton = document.getElementById('action-book-button');
  const seatsInput = document.getElementById('selected-seats-input');
  
  if (selectedSeats.length > 0) {
    displayDiv.classList.remove('empty');
    displayDiv.classList.add('has-seats');
    displayDiv.textContent = selectedSeats.sort().join(', ');
    totalFare.textContent = '₱' + (farePerSeat * selectedSeats.length).toLocaleString();
    bookButton.disabled = false;
    actionBookButton.disabled = false;
    seatsInput.value = JSON.stringify(selectedSeats);
  } else {
    displayDiv.classList.add('empty');
    displayDiv.classList.remove('has-seats');
    displayDiv.textContent = 'No seats selected';
    totalFare.textContent = '₱0';
    bookButton.disabled = true;
    actionBookButton.disabled = true;
    seatsInput.value = '';
  }
}

function clearSelection() {
  selectedSeats = [];
  document.querySelectorAll('.seat.selected').forEach(seat => {
    seat.classList.remove('selected');
    seat.classList.add('available');
  });
  updateSelectedSeatsInfo();
  showToast('🔄', 'Selection cleared');
}

function showToast(icon, msg) {
  document.getElementById('ti').textContent = icon;
  document.getElementById('tm').textContent = msg;
  const t = document.getElementById('toast');
  t.classList.add('show');
  setTimeout(() => t.classList.remove('show'), 3400);
}

// Initialize Lucide icons
document.addEventListener('DOMContentLoaded', function() {
  lucide.createIcons();
});
</script>
@endpush
