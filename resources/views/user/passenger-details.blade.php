@extends('layouts.app')
@section('title', 'Passenger Details — Mindanao Express')

@section('content')

{{-- ── PAGE HEADER ─────────────────────────────────────────────── --}}
<div class="bg-slate-900 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <a href="{{ route('landing.ticket_booking') }}" class="inline-flex items-center gap-2 text-slate-400 hover:text-white transition-colors text-sm mb-4">
            <i data-lucide="arrow-left" style="width:16px;height:16px"></i> Start over
        </a>
        <h1 class="text-3xl font-extrabold text-white">
            Passenger <span class="text-primary-400">Details</span>
        </h1>
    </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
    <form action="{{ route('user.booking.store_details', $booking->id) }}" method="POST" id="passenger-form">
        @csrf

        <div class="flex flex-col lg:flex-row gap-10">
            
            {{-- ── LEFT PANEL: PASSENGER FORMS ──────────────────────────────── --}}
            <div class="flex-1 space-y-6">
                
                <div class="bg-white border border-slate-200 rounded-2xl p-8 shadow-sm">
                    <h2 class="text-lg font-bold text-slate-900 mb-6 flex items-center gap-2">
                        <i data-lucide="users" style="width:20px;height:20px;color:#ea580c"></i>
                        Who's traveling?
                    </h2>

                    <div class="space-y-8">
                        @foreach($booking->bookingSeats as $index => $seat)
                            <div class="p-6 bg-slate-50 border border-slate-200 rounded-xl">
                                <div class="flex items-center gap-3 mb-5 pb-4 border-b border-slate-200">
                                    <div class="w-10 h-10 bg-primary-100 text-primary-700 font-bold flex items-center justify-center rounded-lg">
                                        {{ $seat->seat_number }}
                                    </div>
                                    <div>
                                        <h3 class="font-bold text-slate-800">Passenger {{ $index + 1 }}</h3>
                                        <div class="text-xs text-slate-500">Seat {{ $seat->seat_number }}</div>
                                    </div>
                                    <div class="ml-auto text-right">
                                        <div class="text-sm font-bold text-slate-900">₱{{ number_format($seat->fare, 2) }}</div>
                                        <div class="text-[10px] text-slate-400 uppercase tracking-widest">Base Fare</div>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                                    {{-- Full Name --}}
                                    <div>
                                        <label class="block text-xs font-semibold text-slate-700 mb-1">Full Name</label>
                                        <div class="relative">
                                            <i data-lucide="user" style="width:16px;height:16px;position:absolute;left:12px;top:50%;transform:translateY(-50%);color:#94a3b8"></i>
                                            <input type="text" name="passengers[{{ $seat->id }}][name]" required
                                                   value="{{ old('passengers.'.$seat->id.'.name', $seat->passenger_name) }}"
                                                   placeholder="e.g. Juan Dela Cruz"
                                                   class="w-full pl-9 pr-4 py-3 text-sm border border-slate-300 rounded-xl
                                                          focus:outline-none focus:ring-2 focus:ring-primary-500 bg-white transition-shadow">
                                        </div>
                                    </div>

                                    {{-- Discount Type --}}
                                    <div>
                                        <label class="block text-xs font-semibold text-slate-700 mb-1">Passenger Type</label>
                                        <div class="relative">
                                            <i data-lucide="tag" style="width:16px;height:16px;position:absolute;left:12px;top:50%;transform:translateY(-50%);color:#94a3b8"></i>
                                            <select name="passengers[{{ $seat->id }}][discount_type_id]" 
                                                    class="passenger-type-select w-full pl-9 pr-4 py-3 text-sm border border-slate-300 rounded-xl
                                                           focus:outline-none focus:ring-2 focus:ring-primary-500 bg-white appearance-none transition-shadow"
                                                    data-fare="{{ $seat->fare }}">
                                                <option value="" data-pct="0">Regular (No Discount)</option>
                                                @foreach($discountTypes as $discount)
                                                    <option value="{{ $discount->id }}" data-pct="{{ $discount->percentage }}"
                                                            {{ old('passengers.'.$seat->id.'.discount_type_id') == $discount->id ? 'selected' : '' }}>
                                                        {{ $discount->display_name }} ({{ floatval($discount->percentage) * 100 }}% Off)
                                                    </option>
                                                @endforeach
                                            </select>
                                            <i data-lucide="chevron-down" style="width:14px;height:14px;position:absolute;right:14px;top:50%;transform:translateY(-50%);color:#94a3b8;pointer-events:none;"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="bg-primary-50 border border-primary-200 rounded-2xl p-6 flex items-start gap-4">
                    <i data-lucide="info" style="width:24px;height:24px;color:#ea580c;flex-shrink:0;margin-top:2px;"></i>
                    <p class="text-sm text-primary-800 leading-relaxed">
                        <strong>Note:</strong> If you apply a Senior Citizen, PWD, or Student discount, you will be required to present a valid ID upon boarding. Failure to present a valid ID will result in the forfeiture of the discount and the difference must be paid in full.
                    </p>
                </div>

            </div>

            {{-- ── RIGHT PANEL: SUMMARY & CHECKOUT ───────────────────── --}}
            <div class="w-full lg:w-[400px] shrink-0">
                <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden sticky top-24">
                    
                    {{-- Trip Summary Header --}}
                    <div class="p-6 bg-slate-50 border-b border-slate-200">
                        <div class="flex items-center gap-2 text-sm text-slate-500 mb-2">
                            <i data-lucide="calendar" style="width:14px;height:14px"></i>
                            {{ \Carbon\Carbon::parse($booking->trip->trip_date)->format('D, M j, Y') }}
                        </div>
                        <div class="flex justify-between items-center">
                            <div>
                                <div class="text-xl font-extrabold text-slate-900">{{ \Carbon\Carbon::parse($booking->trip->departure_time)->format('h:i A') }}</div>
                                <div class="text-xs text-slate-500 mt-1">{{ $booking->trip->route?->originCity?->name }}</div>
                            </div>
                            <div class="flex-1 px-4 flex items-center">
                                <div class="h-px bg-slate-300 flex-1"></div>
                                <i data-lucide="bus" style="width:16px;height:16px;color:#ea580c;margin:0 8px"></i>
                                <div class="h-px bg-slate-300 flex-1"></div>
                            </div>
                            <div class="text-right">
                                <div class="text-xl font-extrabold text-slate-900">{{ $booking->trip->arrival_time ? \Carbon\Carbon::parse($booking->trip->arrival_time)->format('h:i A') : '—' }}</div>
                                <div class="text-xs text-slate-500 mt-1">{{ $booking->trip->route?->destinationCity?->name }}</div>
                            </div>
                        </div>
                    </div>

                    {{-- Fare Summary --}}
                    <div class="p-6">
                        <h3 class="text-sm font-bold text-slate-800 mb-4 uppercase tracking-wider">Fare Summary</h3>
                        
                        <div class="space-y-3 mb-6 pb-6 border-b border-slate-200 border-dashed">
                            <div class="flex justify-between text-sm text-slate-600">
                                <span>Base Fare ({{ $booking->bookingSeats->count() }} Seat{{ $booking->bookingSeats->count() > 1 ? 's' : '' }})</span>
                                <span class="font-semibold text-slate-900">₱{{ number_format($booking->base_fare, 2) }}</span>
                            </div>
                            <div class="flex justify-between text-sm text-emerald-600 font-medium">
                                <span>Total Discounts</span>
                                <span id="summary-discount">- ₱0.00</span>
                            </div>
                        </div>

                        <div class="flex justify-between items-end mb-6">
                            <div class="text-sm font-bold text-slate-800">Total Payable</div>
                            <div class="text-3xl font-extrabold text-primary-600">
                                ₱<span id="summary-total">{{ number_format($booking->base_fare, 2) }}</span>
                            </div>
                        </div>

                        <button type="submit" class="w-full py-4 rounded-xl font-bold text-white transition-all bg-primary-600 hover:bg-primary-700 shadow-lg shadow-primary-500/30">
                            Proceed to Payment →
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </form>
</div>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const selects = document.querySelectorAll('.passenger-type-select');
        const discountSpan = document.getElementById('summary-discount');
        const totalSpan = document.getElementById('summary-total');
        const baseFare = {{ $booking->base_fare }};

        function calculateTotals() {
            let totalDiscount = 0;

            selects.forEach(select => {
                const selectedOption = select.options[select.selectedIndex];
                const pct = parseFloat(selectedOption.dataset.pct || 0);
                const fare = parseFloat(select.dataset.fare || 0);
                
                totalDiscount += (fare * pct);
            });

            const finalTotal = baseFare - totalDiscount;

            discountSpan.textContent = `- ₱${totalDiscount.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2})}`;
            totalSpan.textContent = finalTotal.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2});
        }

        selects.forEach(select => {
            select.addEventListener('change', calculateTotals);
        });

        // Initial calculation in case of old inputs
        calculateTotals();
    });
</script>
@endpush
