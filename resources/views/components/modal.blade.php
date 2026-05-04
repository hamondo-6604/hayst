@props(['id', 'title' => '', 'size' => 'md'])

@php
    $sizeClasses = match($size) {
        'sm' => 'max-w-md',
        'md' => 'max-w-lg',
        'lg' => 'max-w-2xl',
        'xl' => 'max-w-4xl',
        default => 'max-w-lg',
    };
@endphp

<div id="{{ $id }}" class="hidden fixed inset-0 z-[110] items-center justify-center p-4 sm:p-6 opacity-0 transition-opacity duration-300 admin-modal" role="dialog" aria-modal="true">
    <!-- Backdrop -->
    <div class="fixed inset-0 bg-slate-900/50 dark:bg-slate-900/80 backdrop-blur-sm transition-opacity" onclick="closeAdminModal('{{ $id }}')"></div>
    
    <!-- Modal Panel -->
    <div class="relative w-full {{ $sizeClasses }} bg-white dark:bg-slate-800 rounded-2xl shadow-2xl overflow-visible ring-1 ring-slate-200 dark:ring-slate-700 transform scale-95 transition-all duration-300 admin-modal-panel flex flex-col max-h-[90vh]">
        
        <!-- Header -->
        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100 dark:border-slate-700 shrink-0">
            <h3 class="text-lg font-bold text-slate-800 dark:text-white" id="{{ $id }}-title">{{ $title }}</h3>
            <button type="button" onclick="closeAdminModal('{{ $id }}')" class="text-slate-400 hover:text-slate-500 dark:hover:text-slate-300 focus:outline-none transition-colors">
                <i class="fa-solid fa-xmark text-xl"></i>
            </button>
        </div>

        <!-- Body -->
        <div class="px-6 py-4 overflow-visible flex-1 admin-modal-body">
            {{ $slot }}
        </div>

        <!-- Footer -->
        @if(isset($footer))
        <div class="px-6 py-4 border-t border-slate-100 dark:border-slate-700 bg-slate-50 dark:bg-slate-800/50 flex justify-end gap-3 shrink-0 rounded-b-2xl">
            {{ $footer }}
        </div>
        @endif
    </div>
</div>
