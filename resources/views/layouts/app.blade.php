<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="csrf-token" content="{{ csrf_token() }}" />
  <title>@yield('title', 'VoyagePH') — Bus Booking</title>

  {{-- Tailwind CDN --}}
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            primary: {
              50: '#fff7ed', 100: '#ffedd5', 200: '#fed7aa',
              300: '#fdba74', 400: '#fb923c', 500: '#f97316',
              600: '#ea580c', 700: '#c2410c', 800: '#9a3412', 900: '#7c2d12',
            },
          },
          fontFamily: { sans: ['Inter', 'ui-sans-serif', 'system-ui'] },
          animation: {
            'fade-in':    'fadeIn .35s ease both',
            'slide-up':   'slideUp .4s ease both',
            'spin-slow':  'spin 2s linear infinite',
          },
          keyframes: {
            fadeIn:  { from: { opacity: 0 }, to: { opacity: 1 } },
            slideUp: { from: { opacity: 0, transform: 'translateY(14px)' }, to: { opacity: 1, transform: 'none' } },
          },
        }
      }
    }
  </script>

  {{-- Inter font --}}
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet" />

  {{-- Lucide icons --}}
  <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.min.js"></script>

  <style>
    *, *::before, *::after { font-family: 'Inter', sans-serif; box-sizing: border-box; }

    /* thin scrollbar */
    ::-webkit-scrollbar { width: 5px; height: 5px; }
    ::-webkit-scrollbar-track { background: #f8fafc; }
    ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 99px; }

    /* glassmorphism nav */
    .glass { background: rgba(255,255,255,.92); backdrop-filter: blur(14px); -webkit-backdrop-filter: blur(14px); }

    /* modal backdrop */
    .modal-bg { background: rgba(15,23,42,.65); backdrop-filter: blur(3px); }

    /* toast slide-in */
    @keyframes toastIn  { from { transform: translateX(110%); opacity: 0; } to { transform: translateX(0); opacity: 1; } }
    @keyframes toastOut { from { opacity: 1; } to { opacity: 0; } }
    .toast-enter { animation: toastIn .3s ease forwards; }
    .toast-leave { animation: toastOut .25s ease forwards; }

    /* line-clamp utility */
    .clamp-2 { display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
    .clamp-3 { display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden; }
  </style>

  @stack('head')
</head>
<body class="bg-slate-50 text-slate-900 antialiased">

  {{-- NAV --}}
  @include('components.navbar')

  {{-- FLASH --}}
  @include('components.flash')

  {{-- MAIN --}}
  <main class="animate-fade-in">
    @yield('content')
  </main>

  {{-- FOOTER --}}
  @include('components.footer')

  {{-- AUTH MODAL --}}
  @include('auth.modal')

  {{-- GLOBAL JS --}}
  <script>
    // ── icons ─────────────────────────────────────────────────
    lucide.createIcons();

    // ── csrf helper ───────────────────────────────────────────
    const csrf = () => document.querySelector('meta[name="csrf-token"]')?.content ?? '';

    // ── toast ─────────────────────────────────────────────────
    function toast(msg, type = 'success') {
      const map = {
        success: ['bg-emerald-500', 'check-circle'],
        error:   ['bg-red-500',     'x-circle'],
        warning: ['bg-amber-500',   'alert-triangle'],
        info:    ['bg-blue-500',    'info'],
      };
      const [bg, icon] = map[type] ?? map.info;
      const el = Object.assign(document.createElement('div'), {
        className: `toast-enter fixed top-4 right-4 z-[9999] flex items-center gap-3 ${bg} text-white text-sm font-medium px-4 py-3 rounded-2xl shadow-xl max-w-xs`,
        innerHTML: `<i data-lucide="${icon}" style="width:16px;height:16px;flex-shrink:0"></i><span>${msg}</span>`,
      });
      document.body.appendChild(el);
      lucide.createIcons();
      setTimeout(() => { el.classList.replace('toast-enter','toast-leave'); setTimeout(()=>el.remove(), 280); }, 3400);
    }

    // ── modal helpers ─────────────────────────────────────────
    function openModal(id)  { const m=document.getElementById(id); m?.classList.remove('hidden'); m?.classList.add('flex');   document.body.style.overflow='hidden'; }
    function closeModal(id) { const m=document.getElementById(id); m?.classList.add('hidden');    m?.classList.remove('flex'); document.body.style.overflow=''; }

    // ── auth tabs ─────────────────────────────────────────────
    function authTab(name) {
      ['login','register','forgot'].forEach(t => {
        document.getElementById(`at-${t}`)?.classList.add('hidden');
        const btn = document.querySelector(`[data-tab="${t}"]`);
        if (btn) { btn.classList.remove('border-primary-600','text-primary-700'); btn.classList.add('border-transparent','text-slate-500'); }
      });
      document.getElementById(`at-${name}`)?.classList.remove('hidden');
      const active = document.querySelector(`[data-tab="${name}"]`);
      if (active) { active.classList.add('border-primary-600','text-primary-700'); active.classList.remove('border-transparent','text-slate-500'); }
    }

    // ── require auth guard ────────────────────────────────────
    function requireAuth(url) {
      @auth window.location.href = url;
      @else openModal('auth-modal'); authTab('login');
      @endauth
    }

    // ── toggle password visibility ────────────────────────────
    function togglePwd(id) {
      const inp = document.getElementById(id);
      inp.type = inp.type === 'password' ? 'text' : 'password';
    }

    // ── mobile nav ────────────────────────────────────────────
    document.addEventListener('DOMContentLoaded', () => {
      lucide.createIcons();

      // close dropdown on outside click
      document.addEventListener('click', e => {
        const dd = document.getElementById('user-dd');
        if (dd && !dd.closest('[data-dd]')?.contains(e.target)) dd.classList.add('hidden');
      });

      // mobile toggle
      document.getElementById('mob-btn')?.addEventListener('click', () => {
        document.getElementById('mob-menu')?.classList.toggle('hidden');
      });
    });
  </script>

  @stack('scripts')
</body>
</html>