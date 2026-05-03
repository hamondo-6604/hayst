@extends('layouts.app')
@section('title', 'Page Not Found — Mindanao Express')

@section('content')
<div class="min-h-[70vh] flex flex-col items-center justify-center px-4 text-center">
    <div class="w-24 h-24 bg-slate-100 dark:bg-slate-800 text-slate-400 rounded-3xl flex items-center justify-center mb-6 shadow-sm border border-slate-200 dark:border-slate-700">
        <i data-lucide="map-pin-off" style="width: 48px; height: 48px;"></i>
    </div>
    <h1 class="text-4xl font-extrabold text-slate-900 dark:text-white mb-3 tracking-tight">Lost your way?</h1>
    <p class="text-slate-500 dark:text-slate-400 max-w-md mx-auto mb-8 leading-relaxed">
        We can't seem to find the page you're looking for. It might have been moved, deleted, or the URL might be incorrect.
    </p>
    <div class="flex items-center gap-4">
        <button onclick="window.history.back()" class="px-6 py-3 bg-slate-100 hover:bg-slate-200 dark:bg-slate-800 dark:hover:bg-slate-700 text-slate-700 dark:text-slate-200 font-bold rounded-xl transition-colors">
            Go Back
        </button>
        <a href="{{ route('landing.home') }}" class="px-6 py-3 bg-primary-600 hover:bg-primary-700 text-white font-bold rounded-xl transition-colors shadow-lg shadow-primary-500/30">
            Return Home
        </a>
    </div>
</div>
@endsection
