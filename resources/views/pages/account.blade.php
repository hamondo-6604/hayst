@extends('layouts.app')
@section('title', 'Account & Support — Mindanao Express')

@section('content')

{{-- ── PAGE HEADER ────────────────────────────────────────────────── --}}
<div class="bg-slate-900 py-10">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <p class="text-xs font-bold text-primary-400 uppercase tracking-widest mb-1">Settings</p>
    <h1 class="text-3xl font-extrabold text-white">Account &amp; <span class="text-primary-400">Support</span></h1>
    <p class="text-slate-400 text-sm mt-1">Manage your profile, view your discount, and get help anytime.</p>
  </div>
</div>

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
  <div class="flex flex-col lg:flex-row gap-8">

    {{-- ── SIDEBAR NAV ────────────────────────────────────────────── --}}
    <aside class="w-full lg:w-60 shrink-0">
      <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden sticky top-24">
        @foreach([
          ['#profile',      'user',       'My Profile'],
          ['#discount',     'badge-percent','Discount & ID'],
          ['#security',     'lock',       'Security'],
          ['#faq',          'help-circle','FAQ'],
          ['#contact',      'life-buoy',  'Contact Us'],
          ['#cancellation', 'file-text',  'Cancellation Policy'],
          ['#baggage',      'package',    'Baggage Policy'],
        ] as [$href,$icon,$label])
          <a href="{{ $href }}"
             class="flex items-center gap-3 px-4 py-3 text-sm text-slate-700 hover:bg-primary-50 hover:text-primary-700
                    border-b border-slate-100 last:border-0 transition-colors group">
            <i data-lucide="{{ $icon }}" style="width:15px;height:15px;color:#94a3b8" class="group-hover:text-primary-600"></i>
            {{ $label }}
          </a>
        @endforeach
      </div>
    </aside>

    {{-- ── MAIN CONTENT ────────────────────────────────────────────── --}}
    <div class="flex-1 min-w-0 space-y-8">

      {{-- ══ PROFILE SECTION ══ --}}
      <section id="profile" class="bg-white border border-slate-200 rounded-2xl overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center gap-2">
          <i data-lucide="user" style="width:16px;height:16px;color:#ea580c"></i>
          <h2 class="text-sm font-extrabold text-slate-900">My Profile</h2>
        </div>

        <form method="POST" action="{{ route('settings.updateProfile') }}" enctype="multipart/form-data" class="p-6 space-y-5">
          @csrf

          {{-- Avatar + name row --}}
          <div class="flex items-center gap-4">
            <div class="relative group">
              @if(auth()->user()->image_url)
                <img src="{{ asset('storage/' . auth()->user()->image_url) }}" alt="Profile Photo" class="w-16 h-16 rounded-2xl object-cover shrink-0 border border-slate-200">
              @else
                <div class="w-16 h-16 rounded-2xl bg-primary-100 text-primary-700 text-2xl font-extrabold flex items-center justify-center shrink-0">
                  {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
              @endif
              <label for="image_url" class="absolute inset-0 bg-slate-900/50 rounded-2xl flex items-center justify-center opacity-0 group-hover:opacity-100 cursor-pointer transition-opacity">
                <i data-lucide="camera" style="width:20px;height:20px;color:white"></i>
              </label>
              <input type="file" id="image_url" name="image_url" accept="image/*" class="hidden" onchange="previewImage(event)">
            </div>
            <div class="min-w-0">
              <div class="text-base font-extrabold text-slate-900">{{ auth()->user()->name }}</div>
              <div class="text-xs text-slate-500 mt-0.5">{{ auth()->user()->email }}</div>
              @if(auth()->user()->userType)
                <span class="inline-flex items-center gap-1 mt-1 text-[10px] font-bold px-2 py-0.5 rounded-full bg-slate-100 text-slate-600">
                  <i data-lucide="shield" style="width:9px;height:9px"></i>
                  {{ auth()->user()->userType->display_name }}
                </span>
              @endif
            </div>
          </div>

          <div class="grid sm:grid-cols-2 gap-4">
            {{-- Name --}}
            <div>
              <label class="block text-xs font-semibold text-slate-700 mb-1.5">Full Name</label>
              <div class="relative">
                <i data-lucide="user" style="width:13px;height:13px;position:absolute;left:10px;top:50%;transform:translateY(-50%);color:#94a3b8"></i>
                <input type="text" name="name" value="{{ auth()->user()->name }}" required
                       class="w-full pl-8 pr-4 py-2.5 text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500">
              </div>
            </div>

            {{-- Phone --}}
            <div>
              <label class="block text-xs font-semibold text-slate-700 mb-1.5">Phone Number</label>
              <div class="relative">
                <i data-lucide="phone" style="width:13px;height:13px;position:absolute;left:10px;top:50%;transform:translateY(-50%);color:#94a3b8"></i>
                <input type="tel" name="phone" value="{{ auth()->user()->phone }}" placeholder="09XXXXXXXXX"
                       class="w-full pl-8 pr-4 py-2.5 text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500">
              </div>
            </div>

            {{-- Email (read-only) --}}
            <div class="sm:col-span-2">
              <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                Email Address <span class="font-normal text-slate-400">(cannot be changed)</span>
              </label>
              <div class="relative">
                <i data-lucide="mail" style="width:13px;height:13px;position:absolute;left:10px;top:50%;transform:translateY(-50%);color:#94a3b8"></i>
                <input type="email" value="{{ auth()->user()->email }}" readonly
                       class="w-full pl-8 pr-4 py-2.5 text-sm border border-slate-100 rounded-xl bg-slate-50 text-slate-400 cursor-not-allowed">
              </div>
            </div>
          </div>

          @if($errors->any())
            <div class="flex items-start gap-2 bg-red-50 border border-red-200 rounded-xl p-3 text-xs text-red-700">
              <i data-lucide="alert-circle" style="width:13px;height:13px;margin-top:1px;flex-shrink:0"></i>
              <span>{{ $errors->first() }}</span>
            </div>
          @endif

          <div class="flex justify-end">
            <button type="submit"
                    class="flex items-center gap-2 px-5 py-2.5 bg-primary-600 hover:bg-primary-700 text-white text-sm font-bold rounded-xl transition-colors">
              <i data-lucide="save" style="width:14px;height:14px"></i> Save Changes
            </button>
          </div>
        </form>
      </section>

      {{-- ══ DISCOUNT & ID SECTION ══ --}}
      <section id="discount" class="bg-white border border-slate-200 rounded-2xl overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center gap-2">
          <i data-lucide="badge-percent" style="width:16px;height:16px;color:#ea580c"></i>
          <h2 class="text-sm font-extrabold text-slate-900">Discount &amp; ID Type</h2>
        </div>
        <div class="p-6">
          @if(auth()->user()->discountType && auth()->user()->discountType->percentage > 0)
            {{-- Has a discount --}}
            <div class="flex items-start gap-4 p-4 bg-amber-50 border border-amber-200 rounded-2xl mb-5">
              <div class="w-10 h-10 bg-amber-100 rounded-xl flex items-center justify-center shrink-0">
                <i data-lucide="badge-percent" style="width:18px;height:18px;color:#d97706"></i>
              </div>
              <div>
                <div class="text-sm font-extrabold text-amber-800">
                  {{ auth()->user()->discountType->display_name }}
                  — {{ number_format(auth()->user()->discountType->percentage * 100, 0) }}% Off
                </div>
                <p class="text-xs text-amber-700 mt-0.5">{{ auth()->user()->discountType->description }}</p>
                @if(auth()->user()->discountType->requires_id ?? false)
                  <div class="flex items-center gap-1.5 mt-2 text-xs text-amber-700 font-semibold">
                    <i data-lucide="id-card" style="width:12px;height:12px"></i>
                    Present your ID at boarding for the discount to apply.
                  </div>
                @endif
              </div>
            </div>
          @else
            <div class="flex items-center gap-3 p-4 bg-slate-50 border border-slate-200 rounded-2xl mb-5">
              <i data-lucide="info" style="width:18px;height:18px;color:#64748b"></i>
              <p class="text-sm text-slate-600">
                No discount currently applied to your account. You're on the standard fare.
              </p>
            </div>
          @endif

          {{-- Available discounts info --}}
          <p class="text-xs font-bold text-slate-700 mb-3">Government Discounts Available</p>
          <div class="space-y-2">
            @foreach($discountTypes as $dt)
              @if($dt->percentage > 0)
                <div class="flex items-center justify-between p-3 border border-slate-200 rounded-xl
                            {{ auth()->user()->discount_type_id === $dt->id ? 'bg-amber-50 border-amber-300' : '' }}">
                  <div>
                    <div class="text-xs font-bold text-slate-800">{{ $dt->display_name }}</div>
                    <div class="text-xs text-slate-500 mt-0.5">{{ $dt->description }}</div>
                  </div>
                  <span class="text-sm font-extrabold text-primary-600 shrink-0 ml-3">
                    {{ number_format($dt->percentage * 100, 0) }}% off
                  </span>
                </div>
              @endif
            @endforeach
          </div>
          <p class="text-xs text-slate-400 mt-3 flex items-start gap-1.5">
            <i data-lucide="info" style="width:11px;height:11px;margin-top:1px;flex-shrink:0"></i>
            To update your discount type, please contact our support team with a valid government-issued ID.
          </p>
        </div>
      </section>

      {{-- ══ SECURITY SECTION ══ --}}
      <section id="security" class="bg-white border border-slate-200 rounded-2xl overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center gap-2">
          <i data-lucide="lock" style="width:16px;height:16px;color:#ea580c"></i>
          <h2 class="text-sm font-extrabold text-slate-900">Security</h2>
        </div>
        <form method="POST" action="{{ route('settings.updatePassword') }}" class="p-6 space-y-4">
          @csrf
          <div class="grid sm:grid-cols-2 gap-4">
            <div>
              <label class="block text-xs font-semibold text-slate-700 mb-1.5">Current Password</label>
              <div class="relative">
                <i data-lucide="lock" style="width:13px;height:13px;position:absolute;left:10px;top:50%;transform:translateY(-50%);color:#94a3b8"></i>
                <input type="password" name="current_password" required placeholder="••••••••"
                       class="w-full pl-8 pr-4 py-2.5 text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500">
              </div>
            </div>
            <div></div>
            <div>
              <label class="block text-xs font-semibold text-slate-700 mb-1.5">New Password</label>
              <div class="relative">
                <i data-lucide="lock" style="width:13px;height:13px;position:absolute;left:10px;top:50%;transform:translateY(-50%);color:#94a3b8"></i>
                <input type="password" name="password" required placeholder="Min. 6 characters"
                       class="w-full pl-8 pr-4 py-2.5 text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500">
              </div>
            </div>
            <div>
              <label class="block text-xs font-semibold text-slate-700 mb-1.5">Confirm New Password</label>
              <div class="relative">
                <i data-lucide="lock" style="width:13px;height:13px;position:absolute;left:10px;top:50%;transform:translateY(-50%);color:#94a3b8"></i>
                <input type="password" name="password_confirmation" required placeholder="••••••••"
                       class="w-full pl-8 pr-4 py-2.5 text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500">
              </div>
            </div>
          </div>
          <div class="flex justify-end">
            <button type="submit"
                    class="flex items-center gap-2 px-5 py-2.5 bg-slate-800 hover:bg-slate-900 text-white text-sm font-bold rounded-xl transition-colors">
              <i data-lucide="key" style="width:14px;height:14px"></i> Update Password
            </button>
          </div>
        </form>
      </section>

      {{-- ══ FAQ SECTION ══ --}}
      <section id="faq" class="bg-white border border-slate-200 rounded-2xl overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center gap-2">
          <i data-lucide="help-circle" style="width:16px;height:16px;color:#ea580c"></i>
          <h2 class="text-sm font-extrabold text-slate-900">Frequently Asked Questions</h2>
        </div>
        <div class="p-6 space-y-2">
          @foreach([
            ['How do I book a bus ticket?',
             'Go to Search Trips, select your origin and destination, choose a date, pick a trip and seat, then complete payment. Your e-ticket is sent instantly via email.'],
            ['Can I cancel my booking?',
             'Yes. Go to My Bookings, find the booking, and click Cancel. Cancellations are allowed up to 2 hours before departure. Refund processing takes 3–5 business days.'],
            ['How do I get my e-ticket?',
             'After payment is confirmed, your QR e-ticket is sent to your registered email. You can also download it from My Bookings at any time.'],
            ['Are government discounts (senior, PWD, student) applied automatically?',
             'Yes, if your account has a discount type assigned, it is applied at checkout. You must present a valid government-issued ID at the terminal for the discount to be honored.'],
            ['What payment methods are accepted?',
             'We accept GCash, Maya, credit and debit cards, and over-the-counter (OTC) payments at partner outlets. All transactions are SSL encrypted.'],
            ['Can I book for multiple passengers?',
             'Yes. During seat selection you can choose multiple seats and enter the passenger details for each seat in one booking.'],
            ['What is the baggage allowance?',
             'Economy: 1 carry-on bag up to 7 kg. Business and Sleeper: 1 carry-on + 1 checked bag up to 15 kg. Excess baggage fees apply.'],
            ['What if my trip is cancelled by the operator?',
             'You will be notified immediately via email and SMS. A full refund is processed automatically within 3–5 business days.'],
          ] as $i => [$q,$a])
            <div class="border border-slate-200 rounded-xl overflow-hidden">
              <button onclick="toggleFaq({{ $i }})"
                      class="w-full flex items-center justify-between px-4 py-3.5 text-left text-sm font-semibold text-slate-800 hover:bg-slate-50 transition-colors">
                <span class="flex items-center gap-2">
                  <i data-lucide="circle-help" style="width:14px;height:14px;color:#ea580c;flex-shrink:0"></i>
                  {{ $q }}
                </span>
                <i data-lucide="chevron-down" id="faq-icon-{{ $i }}"
                   style="width:15px;height:15px;color:#94a3b8;flex-shrink:0;transition:transform .2s"></i>
              </button>
              <div id="faq-ans-{{ $i }}" class="hidden px-4 pb-4 text-sm text-slate-600 leading-relaxed border-t border-slate-100 pt-3">
                {{ $a }}
              </div>
            </div>
          @endforeach
        </div>
      </section>

      {{-- ══ CONTACT SECTION ══ --}}
      <section id="contact" class="bg-white border border-slate-200 rounded-2xl overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center gap-2">
          <i data-lucide="life-buoy" style="width:16px;height:16px;color:#ea580c"></i>
          <h2 class="text-sm font-extrabold text-slate-900">Contact Us</h2>
        </div>
        <div class="p-6">
          <div class="grid sm:grid-cols-3 gap-4 mb-7">
            @foreach([
              ['phone',    'Hotline',           '+63 (2) 8888-0000', 'Mon – Sat · 6 AM – 10 PM'],
              ['mail',     'Email Support',      'support@voyageph.com', 'Reply within 24 hours'],
              ['message-circle','Live Chat',     'Available in-app',  'Mon – Fri · 8 AM – 8 PM'],
            ] as [$icon,$label,$val,$sub])
              <div class="flex flex-col items-center text-center p-4 bg-slate-50 border border-slate-200 rounded-2xl">
                <div class="w-10 h-10 bg-primary-50 rounded-xl flex items-center justify-center mb-3">
                  <i data-lucide="{{ $icon }}" style="width:18px;height:18px;color:#ea580c"></i>
                </div>
                <div class="text-xs font-bold text-slate-700 mb-0.5">{{ $label }}</div>
                <div class="text-sm font-semibold text-slate-900">{{ $val }}</div>
                <div class="text-xs text-slate-400 mt-0.5">{{ $sub }}</div>
              </div>
            @endforeach
          </div>

          {{-- Contact form --}}
          <form class="space-y-4" onsubmit="submitContact(event)">
            <div class="grid sm:grid-cols-2 gap-4">
              <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Your Name</label>
                <div class="relative">
                  <i data-lucide="user" style="width:13px;height:13px;position:absolute;left:10px;top:50%;transform:translateY(-50%);color:#94a3b8"></i>
                  <input type="text" value="{{ auth()->user()->name }}" required
                         class="w-full pl-8 pr-4 py-2.5 text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500">
                </div>
              </div>
              <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Email Address</label>
                <div class="relative">
                  <i data-lucide="mail" style="width:13px;height:13px;position:absolute;left:10px;top:50%;transform:translateY(-50%);color:#94a3b8"></i>
                  <input type="email" value="{{ auth()->user()->email }}" required
                         class="w-full pl-8 pr-4 py-2.5 text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500">
                </div>
              </div>
            </div>
            <div>
              <label class="block text-xs font-semibold text-slate-700 mb-1.5">Subject</label>
              <div class="relative">
                <i data-lucide="tag" style="width:13px;height:13px;position:absolute;left:10px;top:50%;transform:translateY(-50%);color:#94a3b8"></i>
                <select class="w-full pl-8 pr-4 py-2.5 text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500 bg-white appearance-none">
                  <option>Booking Issue</option>
                  <option>Payment Problem</option>
                  <option>Refund Request</option>
                  <option>Discount / ID Update</option>
                  <option>Trip Feedback</option>
                  <option>Other</option>
                </select>
              </div>
            </div>
            <div>
              <label class="block text-xs font-semibold text-slate-700 mb-1.5">Message</label>
              <textarea rows="4" required placeholder="Describe your concern in detail…"
                        class="w-full px-4 py-2.5 text-sm border border-slate-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-primary-500 resize-none"></textarea>
            </div>
            <div class="flex justify-end">
              <button type="submit"
                      class="flex items-center gap-2 px-6 py-2.5 bg-primary-600 hover:bg-primary-700 text-white text-sm font-bold rounded-xl transition-colors">
                <i data-lucide="send" style="width:14px;height:14px"></i> Send Message
              </button>
            </div>
          </form>
        </div>
      </section>

      {{-- ══ CANCELLATION POLICY ══ --}}
      <section id="cancellation" class="bg-white border border-slate-200 rounded-2xl overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center gap-2">
          <i data-lucide="file-text" style="width:16px;height:16px;color:#ea580c"></i>
          <h2 class="text-sm font-extrabold text-slate-900">Cancellation Policy</h2>
        </div>
        <div class="p-6 space-y-4">
          @foreach([
            ['check-circle','text-emerald-600','bg-emerald-50 border-emerald-200',
             'More than 24 hours before departure',
             'Full refund to original payment method. Processed within 3–5 business days.'],
            ['alert-circle','text-amber-600','bg-amber-50 border-amber-200',
             '2 – 24 hours before departure',
             '50% refund to original payment method. Processing time: 3–5 business days.'],
            ['x-circle','text-red-600','bg-red-50 border-red-200',
             'Less than 2 hours before departure',
             'No refund. Booking is non-refundable at this stage.'],
            ['info','text-blue-600','bg-blue-50 border-blue-200',
             'Operator-initiated cancellations',
             'Full refund guaranteed regardless of when the cancellation occurs.'],
          ] as [$icon,$iconCls,$boxCls,$title,$desc])
            <div class="flex items-start gap-3 p-4 border rounded-xl {{ $boxCls }}">
              <i data-lucide="{{ $icon }}" style="width:16px;height:16px;margin-top:1px;flex-shrink:0" class="{{ $iconCls }}"></i>
              <div>
                <div class="text-xs font-extrabold text-slate-800 mb-0.5">{{ $title }}</div>
                <p class="text-xs text-slate-600 leading-relaxed">{{ $desc }}</p>
              </div>
            </div>
          @endforeach
        </div>
      </section>

      {{-- ══ BAGGAGE POLICY ══ --}}
      <section id="baggage" class="bg-white border border-slate-200 rounded-2xl overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center gap-2">
          <i data-lucide="package" style="width:16px;height:16px;color:#ea580c"></i>
          <h2 class="text-sm font-extrabold text-slate-900">Baggage Policy</h2>
        </div>
        <div class="p-6">
          <div class="grid sm:grid-cols-3 gap-4 mb-5">
            @foreach([
              ['Economy',  '1 carry-on bag',     'Up to 7 kg', 'emerald'],
              ['Business', '1 carry-on + 1 checked', 'Up to 15 kg total', 'amber'],
              ['Sleeper',  '1 carry-on + 1 checked', 'Up to 20 kg total', 'violet'],
            ] as [$cls,$bags,$weight,$color])
              @php
                $colors = [
                  'emerald' => ['bg-emerald-50 border-emerald-200','text-emerald-700','bg-emerald-100'],
                  'amber'   => ['bg-amber-50 border-amber-200',    'text-amber-700',  'bg-amber-100'],
                  'violet'  => ['bg-violet-50 border-violet-200',  'text-violet-700', 'bg-violet-100'],
                ];
                [$box,$text,$av] = $colors[$color];
              @endphp
              <div class="p-4 border rounded-2xl {{ $box }} text-center">
                <div class="w-9 h-9 rounded-xl {{ $av }} flex items-center justify-center mx-auto mb-3">
                  <i data-lucide="package" style="width:16px;height:16px" class="{{ $text }}"></i>
                </div>
                <div class="text-sm font-extrabold {{ $text }} mb-1">{{ $cls }}</div>
                <div class="text-xs text-slate-700 font-semibold">{{ $bags }}</div>
                <div class="text-xs text-slate-500 mt-0.5">{{ $weight }}</div>
              </div>
            @endforeach
          </div>
          <div class="space-y-2">
            @foreach([
              ['Oversized or fragile items must be declared at booking and may be subject to additional handling fees.'],
              ['Live animals, hazardous materials, and illegal substances are strictly prohibited.'],
              ['Excess baggage is charged at ₱50 per kg at the terminal.'],
              ['Mindanao Express is not liable for valuables left in checked baggage. Keep them in your carry-on.'],
            ] as [$note])
              <div class="flex items-start gap-2 text-xs text-slate-600">
                <i data-lucide="dot" style="width:12px;height:12px;color:#ea580c;flex-shrink:0;margin-top:1px"></i>
                {{ $note }}
              </div>
            @endforeach
          </div>
        </div>
      </section>

    </div>
  </div>
</div>

@endsection

@push('scripts')
<script>
  // ── FAQ accordion ─────────────────────────────────────────────
  function toggleFaq(i) {
    const ans  = document.getElementById('faq-ans-' + i);
    const icon = document.getElementById('faq-icon-' + i);
    const open = !ans.classList.contains('hidden');
    ans.classList.toggle('hidden', open);
    icon.style.transform = open ? '' : 'rotate(180deg)';
  }

  // ── Preview profile photo ─────────────────────────────────────
  function previewImage(event) {
    const input = event.target;
    if (input.files && input.files[0]) {
      const reader = new FileReader();
      reader.onload = function(e) {
        // Find the image container or the img tag
        const container = input.parentElement;
        let img = container.querySelector('img');
        if (!img) {
          // Replace the initials div with an img
          const initials = container.querySelector('.bg-primary-100');
          if (initials) initials.remove();
          
          img = document.createElement('img');
          img.className = 'w-16 h-16 rounded-2xl object-cover shrink-0 border border-slate-200';
          img.alt = 'Profile Photo';
          // Insert before the label
          const label = container.querySelector('label');
          container.insertBefore(img, label);
        }
        img.src = e.target.result;
      }
      reader.readAsDataURL(input.files[0]);
    }
  }

  // ── Contact form placeholder ──────────────────────────────────
  function submitContact(e) {
    e.preventDefault();
    toast('Message sent! We\'ll reply within 24 hours.', 'success');
    e.target.reset();
  }

  // ── Smooth scroll for sidebar links ──────────────────────────
  document.querySelectorAll('aside a[href^="#"]').forEach(a => {
    a.addEventListener('click', e => {
      e.preventDefault();
      const id = a.getAttribute('href').slice(1);
      document.getElementById(id)?.scrollIntoView({ behavior: 'smooth', block: 'start' });
    });
  });
</script>
@endpush