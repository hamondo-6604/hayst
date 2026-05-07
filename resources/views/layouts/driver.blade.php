<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Driver Dashboard') - Mindanao Express</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Prevent FOUC (Flash of Unstyled Content) -->
    <script>
      (function() {
        try {
          var theme = localStorage.getItem('admin_theme');
          if (!theme) {
            theme = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
          }
          if (theme === 'dark') {
            document.documentElement.classList.add('dark');
          }
        } catch (e) {}
      })();
    </script>

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
            background: transparent;
        }

        .nav-item:hover {
            color: #0f172a;
            background: transparent;
        }

        .nav-item.active {
            color: #0284c7;
            font-weight: 600;
            background: transparent;
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

        /* Top Header */
        .admin-header {
            height: 64px;
            background: #ffffff;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 1.5rem;
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
        .dark .nav-item { color: #cbd5e1; background: transparent; }
        .dark .nav-item:hover { color: #f8fafc; background: transparent; }
        .dark .nav-item.active { color: #38bdf8; background: transparent; }
        .dark .nav-icon { color: #64748b; }
        .dark .nav-item:hover .nav-icon { color: #94a3b8; }
        .dark .nav-item.active .nav-icon { color: #38bdf8; }
    </style>
</head>
<body>

    <!-- Sidebar -->
    <aside class="admin-sidebar shadow-sm">
        <a href="{{ route('landing.home') }}" class="sidebar-brand text-primary-600 hover:text-primary-700 transition">
            <i class="fa-solid fa-bus mr-2"></i>
            Driver Portal
        </a>
        <nav class="sidebar-nav" aria-label="Driver navigation">
            {{-- Overview --}}
            <div class="nav-group-label">Overview</div>
            <a href="{{ route('driver.dashboard') }}" class="nav-item {{ request()->routeIs('driver.dashboard') ? 'active' : '' }}">
                <span class="nav-icon"><i class="fa-solid fa-chart-pie"></i></span> Dashboard
            </a>

            {{-- Operations --}}
            <div class="nav-group-label">Operations</div>
            <a href="{{ route('driver.trips.index') }}" class="nav-item {{ request()->routeIs('driver.trips.index') ? 'active' : '' }}">
                <span class="nav-icon"><i class="fa-solid fa-location-dot"></i></span> My Trips
            </a>
            <a href="{{ route('driver.trips.history') }}" class="nav-item {{ request()->routeIs('driver.trips.history') ? 'active' : '' }}">
                <span class="nav-icon"><i class="fa-solid fa-clock-rotate-left"></i></span> Trip History
            </a>
            <a href="{{ route('driver.maintenance.index') }}" class="nav-item {{ request()->routeIs('driver.maintenance.index') ? 'active' : '' }}">
                <span class="nav-icon"><i class="fa-solid fa-wrench"></i></span> Maintenance Requests
            </a>
        </nav>
    </aside>

    <!-- Header -->
    <header class="admin-header shadow-sm dark:bg-slate-900 dark:border-slate-800 transition-colors">
        
        {{-- Left: Empty for now --}}
        <div class="flex-1">
        </div>

        {{-- Right: Actions & Profile --}}
        <div class="flex items-center gap-2 sm:gap-4 shrink-0">
          
          {{-- Notification Dropdown --}}
          @php
            $unreadNotifs = auth()->user() ? auth()->user()->appNotifications()->unread()->latest()->take(5)->get() : collect();
            $unreadCount = auth()->user() ? auth()->user()->appNotifications()->unread()->count() : 0;
          @endphp
          <div class="relative" data-dd>
            <button onclick="document.getElementById('notif-dd').classList.toggle('hidden')" class="relative p-2 text-slate-500 hover:bg-slate-100 dark:text-slate-400 dark:hover:bg-slate-800 rounded-xl transition-colors">
                <i class="fa-regular fa-bell text-[1.1rem]"></i>
                <span id="notif-badge" class="{{ $unreadCount > 0 ? '' : 'hidden' }} absolute top-1.5 right-1.5 w-2 h-2 bg-red-500 rounded-full border-2 border-white dark:border-slate-900 transition-opacity"></span>
            </button>
            <div id="notif-dd" class="hidden absolute right-0 top-full mt-2 w-72 bg-white dark:bg-slate-800 rounded-2xl shadow-xl border border-slate-100 dark:border-slate-700 py-2 z-50">
                <div class="px-4 py-2 border-b border-slate-100 dark:border-slate-700 flex justify-between items-center">
                    <span class="font-bold text-sm text-slate-800 dark:text-white">Notifications</span>
                </div>
                <div class="max-h-64 overflow-y-auto" id="notif-list" data-count="{{ $unreadCount }}">
                    @if($unreadNotifs->isEmpty())
                        <div class="p-4 text-center text-sm text-slate-500 dark:text-slate-400">
                            No new notifications
                        </div>
                    @else
                        @foreach($unreadNotifs as $notif)
                            <div class="px-4 py-3 border-b border-slate-50 dark:border-slate-700/50 hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors">
                                <p class="text-sm font-medium text-slate-800 dark:text-slate-200">{{ $notif->title }}</p>
                                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">{{ $notif->message }}</p>
                                <p class="text-[10px] text-slate-400 mt-2">{{ $notif->created_at->diffForHumans() }}</p>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
          </div>

          <div class="w-px h-6 bg-slate-200 dark:bg-slate-700 hidden sm:block mx-1"></div>

          {{-- User dropdown --}}
          <div class="relative" data-dd>
            <button onclick="document.getElementById('user-dd').classList.toggle('hidden')"
                    class="flex items-center gap-2 pl-2 pr-3 py-1.5 rounded-xl hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors border border-transparent hover:border-slate-200 dark:hover:border-slate-700">
              @if(auth()->user() && auth()->user()->image_url)
                <img src="{{ asset('storage/' . auth()->user()->image_url) }}" alt="Profile" class="w-7 h-7 rounded-full object-cover border border-slate-200 dark:border-slate-700">
              @else
                <div class="w-7 h-7 rounded-full bg-primary-100 dark:bg-slate-800 text-primary-700 dark:text-primary-400 text-xs font-bold flex items-center justify-center">
                  {{ auth()->user() ? strtoupper(substr(auth()->user()->name, 0, 1)) : 'D' }}
                </div>
              @endif
              <span class="text-sm font-medium text-slate-700 dark:text-slate-200 max-w-[110px] truncate">{{ auth()->user() ? auth()->user()->name : 'Driver' }}</span>
              <i class="fa-solid fa-chevron-down text-[10px] text-slate-400"></i>
            </button>

            <div id="user-dd"
                 class="hidden absolute right-0 top-full mt-2 w-56 bg-white dark:bg-slate-800 rounded-2xl shadow-xl border border-slate-100 dark:border-slate-700 py-1.5 z-50">

              {{-- User info --}}
              <div class="px-4 py-3 border-b border-slate-100 dark:border-slate-700">
                <p class="text-xs text-slate-400 dark:text-slate-500 mb-0.5">Signed in as Driver</p>
                <p class="text-sm font-semibold text-slate-800 dark:text-white truncate">{{ auth()->user() ? auth()->user()->name : 'Driver' }}</p>
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
      // Close dropdowns on outside click
      document.addEventListener('click', e => {
        const dd = document.getElementById('user-dd');
        if (dd && !dd.closest('[data-dd]')?.contains(e.target)) dd.classList.add('hidden');
        
        const notifDd = document.getElementById('notif-dd');
        if (notifDd && !notifDd.closest('[data-dd]')?.contains(e.target)) notifDd.classList.add('hidden');
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
    @stack('scripts')
</body>
</html>
