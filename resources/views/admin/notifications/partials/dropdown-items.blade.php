@forelse($unreadNotifs as $notif)
  <a href="{{ route('admin.notifications.index') }}" class="block px-4 py-3 hover:bg-slate-50 dark:hover:bg-slate-700 border-b border-slate-50 dark:border-slate-700/50 transition-colors bg-slate-50/50 dark:bg-slate-700/20">
      <div class="flex items-start gap-3">
          <div class="w-8 h-8 rounded-full bg-blue-100 dark:bg-blue-900/30 text-blue-600 flex items-center justify-center shrink-0">
              <i class="fa-solid fa-bell text-xs"></i>
          </div>
          <div>
              <p class="text-sm text-slate-800 dark:text-slate-200 font-medium">{{ $notif->title }}</p>
              <p class="text-xs text-slate-500 mt-0.5">{{ Str::limit($notif->message, 50) }}</p>
              <p class="text-[10px] text-slate-400 mt-1">{{ $notif->created_at->diffForHumans() }}</p>
          </div>
      </div>
  </a>
@empty
  <div class="px-4 py-6 text-center">
    <i class="fa-regular fa-bell text-slate-300 dark:text-slate-600 text-3xl mb-2"></i>
    <p class="text-sm text-slate-500 dark:text-slate-400">No new notifications</p>
  </div>
@endforelse
