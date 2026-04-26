{{-- Theme Toggle Button --}}
<button id="theme-toggle" 
        onclick="toggleTheme()" 
        class="fixed top-4 right-4 z-50 w-10 h-10 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 rounded-full shadow-lg hover:shadow-xl transition-all duration-300 flex items-center justify-center group"
        aria-label="Toggle dark mode">
  
  {{-- Sun Icon (Light Mode) --}}
  <svg id="sun-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-amber-500 dark:text-slate-400 transition-all duration-300">
    <circle cx="12" cy="12" r="5"></circle>
    <line x1="12" y1="1" x2="12" y2="3"></line>
    <line x1="12" y1="21" x2="12" y2="23"></line>
    <line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line>
    <line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line>
    <line x1="1" y1="12" x2="3" y2="12"></line>
    <line x1="21" y1="12" x2="23" y2="12"></line>
    <line x1="4.22" y1="19.78" x2="5.64" y2="18.36"></line>
    <line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line>
  </svg>
  
  {{-- Moon Icon (Dark Mode) --}}
  <svg id="moon-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-slate-700 dark:text-amber-400 transition-all duration-300 absolute opacity-0 dark:opacity-100">
    <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path>
  </svg>
  
  <!-- Tooltip -->
  <div class="absolute right-full mr-2 top-1/2 -translate-y-1/2 bg-slate-900 dark:bg-slate-100 text-white dark:text-slate-900 text-xs px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap pointer-events-none">
    <span id="theme-tooltip">Switch to dark mode</span>
  </div>
</button>

<style>
/* Theme Toggle Animations */
#theme-toggle {
  animation: fadeIn 0.5s ease-out;
}

@keyframes fadeIn {
  from { opacity: 0; transform: scale(0.9); }
  to { opacity: 1; transform: scale(1); }
}

#sun-icon, #moon-icon {
  transition: all 0.3s ease;
}

#theme-toggle:hover #sun-icon {
  transform: rotate(180deg);
}

#theme-toggle:hover #moon-icon {
  transform: rotate(-180deg);
}
</style>
