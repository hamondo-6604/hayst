<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard') - Mindanao Express</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
      tailwind.config = {
        darkMode: 'class',
        theme: {
          extend: {
            fontFamily: { sans: ['Inter', 'sans-serif'] },
            colors: {
              primary: {"50":"#f0f9ff","100":"#e0f2fe","200":"#bae6fd","300":"#7dd3fc","400":"#38bdf8","500":"#0ea5e9","600":"#0284c7","700":"#0369a1","800":"#075985","900":"#0c4a6e"}
            }
          }
        }
      }
    </script>

    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f8fafc; }
        
        /* Sidebar Styling */
        .admin-sidebar {
            width: 260px;
            background: #ffffff;
            border-right: 1px solid #e2e8f0;
            display: flex;
            flex-direction: column;
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            z-index: 40;
        }

        .sidebar-brand {
            height: 64px;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            padding: 0 1.5rem;
            border-bottom: 1px solid #e2e8f0;
            font-weight: 800;
            font-size: 1.125rem;
            color: #0f172a;
        }

        .sidebar-nav {
            padding: 1rem 0;
            flex: 1;
            overflow-y: auto;
            scrollbar-width: none; /* Firefox */
            -ms-overflow-style: none; /* IE and Edge */
        }
        .sidebar-nav::-webkit-scrollbar {
            display: none; /* Chrome, Safari and Opera */
        }

        .nav-group-label {
            padding: 1.5rem 1.5rem 0.5rem;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            font-weight: 700;
            color: #94a3b8;
        }

        .nav-item {
            display: flex;
            align-items: center;
            padding: 0.625rem 1.5rem;
            color: #475569;
            font-weight: 500;
            font-size: 0.875rem;
            text-decoration: none;
            transition: all 0.2s;
            position: relative;
        }

        .nav-item:hover {
            background: #f1f5f9;
            color: #0f172a;
        }

        .nav-item.active {
            background: #e0f2fe;
            color: #0284c7;
            font-weight: 600;
        }

        .nav-item.active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 4px;
            background: #0ea5e9;
        }

        .nav-icon {
            width: 24px;
            font-size: 1rem;
            color: #94a3b8;
            margin-right: 0.75rem;
            text-align: center;
        }

        .nav-item:hover .nav-icon {
            color: #64748b;
        }

        .nav-item.active .nav-icon {
            color: #0ea5e9;
        }

        .nav-badge {
            margin-left: auto;
            background: #e2e8f0;
            color: #475569;
            font-size: 0.7rem;
            padding: 0.125rem 0.375rem;
            border-radius: 999px;
            font-weight: 700;
        }

        .nav-badge.gold {
            background: #fef08a;
            color: #854d0e;
        }

        /* Top Header */
        .admin-header {
            height: 64px;
            background: #ffffff;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: flex-end;
            padding: 0 2rem;
            position: fixed;
            top: 0;
            right: 0;
            left: 260px;
            z-index: 30;
        }

        /* Main Content */
        .admin-main {
            margin-left: 260px;
            margin-top: 64px;
            padding: 2rem;
            min-height: calc(100vh - 64px);
        }
        /* Dark Mode Overrides */
        .dark body { background-color: #0f172a; color: #f8fafc; }
        .dark .admin-sidebar { background: #1e293b; border-right-color: #334155; }
        .dark .sidebar-brand { border-bottom-color: #334155; color: #f8fafc; }
        .dark .nav-group-label { color: #64748b; }
        .dark .nav-item { color: #cbd5e1; }
        .dark .nav-item:hover { background: #334155; color: #f8fafc; }
        .dark .nav-item.active { background: rgba(14, 165, 233, 0.15); color: #38bdf8; }
        .dark .nav-icon { color: #64748b; }
        .dark .nav-item:hover .nav-icon { color: #94a3b8; }
        .dark .nav-item.active .nav-icon { color: #38bdf8; }
        .dark .nav-badge { background: #334155; color: #cbd5e1; }
        .dark .nav-badge.gold { background: rgba(133, 77, 14, 0.4); color: #fef08a; }
    </style>
</head>
<body>

    <!-- Sidebar -->
    <aside class="admin-sidebar shadow-sm">
        <a href="{{ route('landing.home') }}" class="sidebar-brand text-primary-600 hover:text-primary-700 transition">
            <i class="fa-solid fa-bus mr-2"></i>
            Mindanao Express
        </a>
        <nav class="sidebar-nav" aria-label="Admin navigation">
            {{-- Overview --}}
            <div class="nav-group-label">Overview</div>
            <a href="{{ route('admin.dashboard') }}" class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <span class="nav-icon"><i class="fa-solid fa-chart-pie"></i></span> Dashboard
            </a>
            <a href="{{ route('admin.analytics') }}" class="nav-item {{ request()->routeIs('admin.analytics') ? 'active' : '' }}">
                <span class="nav-icon"><i class="fa-solid fa-chart-line"></i></span> Analytics
            </a>

            {{-- Operations --}}
            <div class="nav-group-label">Operations</div>
            <a href="{{ route('admin.bookings.index') }}" class="nav-item {{ request()->routeIs('admin.bookings.*') ? 'active' : '' }}">
                <span class="nav-icon"><i class="fa-solid fa-ticket"></i></span> Bookings
                <span class="nav-badge">12</span>
            </a>
            <a href="{{ route('admin.trips.index') }}" class="nav-item {{ request()->routeIs('admin.trips.*') ? 'active' : '' }}">
                <span class="nav-icon"><i class="fa-solid fa-location-dot"></i></span> Trips &amp; Schedules
            </a>
            <a href="{{ route('admin.routes.index') }}" class="nav-item {{ request()->routeIs('admin.routes.*') ? 'active' : '' }}">
                <span class="nav-icon"><i class="fa-solid fa-route"></i></span> Routes
            </a>
            <a href="{{ route('admin.payments.index') }}" class="nav-item {{ request()->routeIs('admin.payments.*') ? 'active' : '' }}">
                <span class="nav-icon"><i class="fa-solid fa-credit-card"></i></span> Payments
            </a>
            <a href="{{ route('admin.promotions.index') }}" class="nav-item {{ request()->routeIs('admin.promotions.*') ? 'active' : '' }}">
                <span class="nav-icon"><i class="fa-solid fa-tag"></i></span> Promotions
                <span class="nav-badge gold">3</span>
            </a>

            {{-- Fleet --}}
            <div class="nav-group-label">Fleet</div>
            <a href="{{ route('admin.buses.index') }}" class="nav-item {{ request()->routeIs('admin.buses.*') ? 'active' : '' }}">
                <span class="nav-icon"><i class="fa-solid fa-bus"></i></span> Buses
            </a>
            <a href="{{ route('admin.bus-types.index') }}" class="nav-item {{ request()->routeIs('admin.bus-types.*') ? 'active' : '' }}">
                <span class="nav-icon"><i class="fa-solid fa-couch"></i></span> Bus Types
            </a>
            <a href="{{ route('admin.seats.index') }}" class="nav-item {{ request()->routeIs('admin.seats.*') ? 'active' : '' }}">
                <span class="nav-icon"><i class="fa-solid fa-chair"></i></span> Seats &amp; Layouts
            </a>
            <a href="{{ route('admin.maintenance.index') }}" class="nav-item {{ request()->routeIs('admin.maintenance.*') ? 'active' : '' }}">
                <span class="nav-icon"><i class="fa-solid fa-wrench"></i></span> Maintenance
                <span class="nav-badge">2</span>
            </a>

            {{-- People --}}
            <div class="nav-group-label">People</div>
            <a href="{{ route('admin.users.index') }}" class="nav-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                <span class="nav-icon"><i class="fa-solid fa-users"></i></span> Users
            </a>
            <a href="{{ route('admin.drivers.index') }}" class="nav-item {{ request()->routeIs('admin.drivers.*') ? 'active' : '' }}">
                <span class="nav-icon"><i class="fa-solid fa-id-badge"></i></span> Drivers
            </a>
            <a href="{{ route('admin.feedback.index') }}" class="nav-item {{ request()->routeIs('admin.feedback.*') ? 'active' : '' }}">
                <span class="nav-icon"><i class="fa-solid fa-comments"></i></span> Feedback
                <span class="nav-badge">5</span>
            </a>

            {{-- System --}}
            <div class="nav-group-label">System</div>
            <a href="{{ route('admin.roles.index') }}" class="nav-item {{ request()->routeIs('admin.roles.*') ? 'active' : '' }}">
                <span class="nav-icon"><i class="fa-solid fa-shield-halved"></i></span> Roles &amp; Permissions
            </a>
            <a href="{{ route('admin.notifications.index') }}" class="nav-item {{ request()->routeIs('admin.notifications.*') ? 'active' : '' }}">
                <span class="nav-icon"><i class="fa-solid fa-bell"></i></span> Notifications
            </a>
            <a href="{{ route('admin.cities.index') }}" class="nav-item {{ request()->routeIs('admin.cities.*') ? 'active' : '' }}">
                <span class="nav-icon"><i class="fa-solid fa-city"></i></span> Cities
            </a>
        </nav>
    </aside>

    <!-- Header -->
    <header class="admin-header shadow-sm dark:bg-slate-900 dark:border-slate-800 transition-colors">
        <div class="flex items-center gap-4">
          {{-- User dropdown --}}
          <div class="relative" data-dd>
            <button onclick="document.getElementById('user-dd').classList.toggle('hidden')"
                    class="flex items-center gap-2 pl-2 pr-3 py-1.5 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors border border-transparent hover:border-slate-200 dark:hover:border-slate-700">
              <div class="w-7 h-7 rounded-full bg-primary-100 dark:bg-slate-800 text-primary-700 dark:text-primary-400 text-xs font-bold flex items-center justify-center">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
              </div>
              <span class="text-sm font-medium text-slate-700 dark:text-slate-200 max-w-[110px] truncate">{{ auth()->user()->name }}</span>
              <i class="fa-solid fa-chevron-down text-[10px] text-slate-400"></i>
            </button>

            <div id="user-dd"
                 class="hidden absolute right-0 top-full mt-2 w-56 bg-white dark:bg-slate-800 rounded-2xl shadow-xl border border-slate-100 dark:border-slate-700 py-1.5 z-50">

              {{-- User info --}}
              <div class="px-4 py-3 border-b border-slate-100 dark:border-slate-700">
                <p class="text-xs text-slate-400 dark:text-slate-500 mb-0.5">Signed in as Admin</p>
                <p class="text-sm font-semibold text-slate-800 dark:text-white truncate">{{ auth()->user()->name }}</p>
              </div>

              <a href="{{ route('landing.home') }}" class="flex items-center gap-2.5 px-4 py-2.5 text-sm text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors">
                <i class="fa-solid fa-house w-4 text-center text-primary-500"></i> Back to Site
              </a>
              
              {{-- Theme Toggle --}}
              <button onclick="toggleAdminTheme(); document.getElementById('user-dd').classList.add('hidden')" 
                      class="w-full flex items-center gap-2.5 px-4 py-2.5 text-sm text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors text-left">
                <div id="admin-theme-icon" class="flex items-center justify-center w-4 text-center">
                  <i class="fa-solid fa-moon text-slate-500"></i>
                </div>
                <span id="admin-theme-text">Switch to Dark Mode</span>
              </button>
              
              <div class="border-t border-slate-100 dark:border-slate-700 mt-1.5 pt-1">
                <form method="POST" action="{{ route('logout') }}">
                  @csrf
                  <button type="submit" class="w-full flex items-center gap-2.5 px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors">
                    <i class="fa-solid fa-arrow-right-from-bracket w-4 text-center"></i> Sign Out
                  </button>
                </form>
              </div>
            </div>
          </div>
        </div>
    </header>

    <!-- Content -->
    <main class="admin-main transition-colors dark:bg-slate-900 dark:text-slate-200">
        @yield('content')
    </main>

    <script>
      // Close dropdown on outside click
      document.addEventListener('click', e => {
        const dd = document.getElementById('user-dd');
        if (dd && !dd.closest('[data-dd]')?.contains(e.target)) dd.classList.add('hidden');
      });

      // Admin Theme Management
      function getAdminTheme() {
        return localStorage.getItem('admin_theme') || (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
      }

      function setAdminTheme(theme) {
        if (theme === 'dark') {
          document.documentElement.classList.add('dark');
        } else {
          document.documentElement.classList.remove('dark');
        }
        localStorage.setItem('admin_theme', theme);
        updateAdminThemeToggle(theme);
      }

      function toggleAdminTheme() {
        const currentTheme = document.documentElement.classList.contains('dark') ? 'dark' : 'light';
        setAdminTheme(currentTheme === 'light' ? 'dark' : 'light');
      }

      function updateAdminThemeToggle(theme) {
        const iconContainer = document.getElementById('admin-theme-icon');
        const textContainer = document.getElementById('admin-theme-text');
        
        if (iconContainer && textContainer) {
          if (theme === 'dark') {
            iconContainer.innerHTML = '<i class="fa-solid fa-sun text-amber-500"></i>';
            textContainer.textContent = 'Switch to Light Mode';
          } else {
            iconContainer.innerHTML = '<i class="fa-solid fa-moon text-slate-500"></i>';
            textContainer.textContent = 'Switch to Dark Mode';
          }
        }
      }

      // Initialize Theme
      document.addEventListener('DOMContentLoaded', () => {
        setAdminTheme(getAdminTheme());
      });
    </script>
</body>
</html>