<footer class="bg-slate-900 text-slate-400 mt-24">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-14 pb-8">
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-10 pb-10 border-b border-slate-800">

      {{-- Brand --}}
      <div>
        <div class="flex items-center gap-2 mb-4">
          <div class="w-8 h-8 bg-primary-600 rounded-xl flex items-center justify-center">
            <i data-lucide="bus" style="width:15px;height:15px;color:#fff"></i>
          </div>
          <span class="text-base font-extrabold text-white">Voyage<span class="text-primary-400">PH</span></span>
        </div>
        <p class="text-sm leading-relaxed text-slate-500 mb-5">
          The Philippines' most reliable intercity bus booking platform. Safe, on-time, affordable.
        </p>
        <div class="flex gap-2">
          @foreach([['facebook','Facebook'],['instagram','Instagram'],['twitter','Twitter']] as [$icon,$label])
            <a href="#" aria-label="{{ $label }}"
               class="w-8 h-8 rounded-xl bg-slate-800 flex items-center justify-center hover:bg-primary-600 transition-colors">
              <i data-lucide="{{ $icon }}" style="width:13px;height:13px"></i>
            </a>
          @endforeach
        </div>
      </div>

      {{-- Quick links --}}
      <div>
        <p class="text-xs font-bold text-white uppercase tracking-widest mb-4">Quick Links</p>
        <ul class="space-y-2.5 text-sm">
          @foreach([
            [route('landing.home'),           'Home'],
            [route('landing.ticket_booking'), 'Search Trips'],
            [route('landing.booking_routes'), 'Routes & Terminals'],
            [route('landing.promos'),         'Promos & Deals'],
          ] as [$href,$label])
            <li>
              <a href="{{ $href }}" class="flex items-center gap-1.5 hover:text-primary-400 transition-colors">
                <i data-lucide="chevron-right" style="width:11px;height:11px;color:#ea580c"></i>
                {{ $label }}
              </a>
            </li>
          @endforeach
        </ul>
      </div>

      {{-- Support --}}
      <div>
        <p class="text-xs font-bold text-white uppercase tracking-widest mb-4">Support</p>
        <ul class="space-y-2.5 text-sm">
          @foreach([
            ['#faq',         'FAQ'],
            ['#contact',     'Contact Us'],
            ['#cancellation','Cancellation Policy'],
            ['#baggage',     'Baggage Policy'],
          ] as [$hash,$label])
            <li>
              <a href="{{ route('landing.account') }}{{ $hash }}" class="flex items-center gap-1.5 hover:text-primary-400 transition-colors">
                <i data-lucide="chevron-right" style="width:11px;height:11px;color:#ea580c"></i>
                {{ $label }}
              </a>
            </li>
          @endforeach
        </ul>
      </div>

      {{-- Contact --}}
      <div>
        <p class="text-xs font-bold text-white uppercase tracking-widest mb-4">Contact Us</p>
        <ul class="space-y-3 text-sm">
          @foreach([
            ['phone',   '+63 (2) 8888-0000',        'Mon – Sat, 6 AM – 10 PM'],
            ['mail',    'support@voyageph.com',       ''],
            ['map-pin', 'Cubao, Quezon City',         'Metro Manila, Philippines'],
          ] as [$icon,$val,$sub])
            <li class="flex items-start gap-2.5">
              <i data-lucide="{{ $icon }}" style="width:14px;height:14px;color:#f97316;margin-top:2px;flex-shrink:0"></i>
              <span>{{ $val }}@if($sub)<br><span class="text-slate-600 text-xs">{{ $sub }}</span>@endif</span>
            </li>
          @endforeach
        </ul>
      </div>

    </div>

    <div class="flex flex-col sm:flex-row items-center justify-between gap-4 pt-6 text-xs text-slate-600">
      <span>© {{ date('Y') }} VoyagePH. All rights reserved.</span>
      <div class="flex items-center gap-1.5 text-emerald-400">
        <i data-lucide="shield-check" style="width:12px;height:12px"></i>
        <span>LTO &amp; LTFRB Accredited Operators</span>
      </div>
      <div class="flex gap-4">
        <a href="#" class="hover:text-slate-400 transition-colors">Privacy Policy</a>
        <a href="#" class="hover:text-slate-400 transition-colors">Terms of Service</a>
      </div>
    </div>
  </div>
</footer>