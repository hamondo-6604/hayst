<footer class="bg-slate-900 text-slate-400 mt-24">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-14 pb-8">
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-10 pb-10 border-b border-slate-800">

      {{-- Brand --}}
      <div>
        <div class="flex items-center gap-2 mb-4">
          <div class="w-8 h-8 bg-primary-600 rounded-xl flex items-center justify-center">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M8 6v6"></path>
              <path d="M16 6v6"></path>
              <path d="M8 14v.01"></path>
              <path d="M16 14v.01"></path>
              <rect x="4" y="6" width="16" height="12" rx="2"></rect>
            </svg>
          </div>
          <span class="text-base font-extrabold text-white">Mindanao<span class="text-primary-400">Express</span></span>
        </div>
        <p class="text-sm leading-relaxed text-slate-500 mb-5">
          Mindanao's most reliable intercity bus booking platform. Safe, on-time, affordable.
        </p>
        <div class="flex gap-2">
          <a href="#" aria-label="Facebook"
             class="w-8 h-8 rounded-xl bg-slate-800 flex items-center justify-center hover:bg-primary-600 transition-colors">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="currentColor">
              <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
            </svg>
          </a>
          <a href="#" aria-label="Instagram"
             class="w-8 h-8 rounded-xl bg-slate-800 flex items-center justify-center hover:bg-primary-600 transition-colors">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="currentColor">
              <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zM5.838 12a6.162 6.162 0 1 1 12.324 0 6.162 6.162 0 0 1-12.324 0zM12 16a4 4 0 1 1 0-8 4 4 0 0 1 0 8zm4.965-10.405a1.44 1.44 0 1 1 2.881.001 1.44 1.44 0 0 1-2.881-.001z"/>
            </svg>
          </a>
          <a href="#" aria-label="Twitter"
             class="w-8 h-8 rounded-xl bg-slate-800 flex items-center justify-center hover:bg-primary-600 transition-colors">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="currentColor">
              <path d="M23.953 4.57a10 10 0 0 1-2.825.775 4.958 4.958 0 0 0 2.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 0 0-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 0 0-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 0 1-2.228-.616v.06a4.923 4.923 0 0 0 3.946 4.827 4.996 4.996 0 0 1-2.212.085 4.936 4.936 0 0 0 4.604 3.417 9.867 9.867 0 0 1-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 0 0 7.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0 0 24 4.59z"/>
            </svg>
          </a>
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
                <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color:#ea580c">
                  <polyline points="9 18 15 12 9 6"></polyline>
                </svg>
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
                <svg width="11" height="11" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color:#ea580c">
                  <polyline points="9 18 15 12 9 6"></polyline>
                </svg>
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
            ['phone',   '+63 (82) 322-0000',          'Mon – Sat, 6 AM – 10 PM'],
            ['mail',    'support@mindanaoexpress.com', ''],
            ['map-pin', 'Davao City',                 'Davao del Sur, Mindanao'],
          ] as [$icon,$val,$sub])
            <li class="flex items-start gap-2.5">
              @if($icon === 'phone')
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color:#f97316;margin-top:2px;flex-shrink:0">
                  <path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"></path>
                </svg>
              @elseif($icon === 'mail')
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color:#f97316;margin-top:2px;flex-shrink:0">
                  <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                  <polyline points="22,6 12,13 2,6"></polyline>
                </svg>
              @else
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="color:#f97316;margin-top:2px;flex-shrink:0">
                  <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                  <circle cx="12" cy="10" r="3"></circle>
                </svg>
              @endif
              <span>{{ $val }}@if($sub)<br><span class="text-slate-600 text-xs">{{ $sub }}</span>@endif</span>
            </li>
          @endforeach
        </ul>
      </div>

    </div>

    <div class="flex flex-col sm:flex-row items-center justify-between gap-4 pt-6 text-xs text-slate-600">
      <span>© {{ date('Y') }} Mindanao Express. All rights reserved.</span>
      <div class="flex items-center gap-1.5 text-emerald-400">
        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
          <path d="M9 12l2 2 4-4"></path>
        </svg>
        <span>LTO &amp; LTFRB Accredited Operators</span>
      </div>
      <div class="flex gap-4">
        <a href="#" class="hover:text-slate-400 transition-colors">Privacy Policy</a>
        <a href="#" class="hover:text-slate-400 transition-colors">Terms of Service</a>
      </div>
    </div>
  </div>
</footer>
