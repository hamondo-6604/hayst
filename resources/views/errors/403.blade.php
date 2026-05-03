@extends('layouts.app')
@section('title', 'Access Denied — Mindanao Express')

@section('content')
<div class="min-h-[70vh] flex flex-col items-center justify-center px-4 text-center">
    <div class="w-24 h-24 bg-red-50 dark:bg-red-900/20 text-red-500 rounded-3xl flex items-center justify-center mb-6 shadow-sm border border-red-100 dark:border-red-900/30">
        <i data-lucide="shield-alert" style="width: 48px; height: 48px;"></i>
    </div>
    <h1 class="text-4xl font-extrabold text-slate-900 dark:text-white mb-3 tracking-tight">Access Denied</h1>
    <p class="text-slate-500 dark:text-slate-400 max-w-md mx-auto mb-8 leading-relaxed">
        Oops! It looks like you don't have permission to view this page. This usually happens if you're trying to access an admin or staff area without the correct role.
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
