@extends('layouts.admin')

@section('title', 'Coming Soon')

@section('content')
<div class="mb-8 flex items-center justify-between">
    <div>
        <h1 class="text-2xl font-bold text-slate-800">Module Under Construction</h1>
        <p class="text-sm text-slate-500 mt-1">This section is currently being built.</p>
    </div>
    <a href="{{ route('admin.dashboard') }}" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-semibold rounded-xl transition-colors">
        <i class="fa-solid fa-arrow-left mr-2"></i> Back to Dashboard
    </a>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-12 text-center max-w-2xl mx-auto mt-12">
    <div class="w-24 h-24 bg-primary-50 rounded-full flex items-center justify-center mx-auto mb-6 text-primary-500 text-4xl">
        <i class="fa-solid fa-person-digging"></i>
    </div>
    <h2 class="text-2xl font-bold text-slate-800 mb-3">Work in Progress</h2>
    <p class="text-slate-500 mb-8 leading-relaxed">
        The route you are trying to access has been registered, but the user interface and logic for this specific module are currently under development. Please check back later.
    </p>
    
    <a href="{{ route('admin.dashboard') }}" class="px-6 py-3 bg-primary-600 hover:bg-primary-700 text-white font-semibold rounded-xl transition-colors shadow-sm inline-flex items-center">
        <i class="fa-solid fa-house mr-2"></i> Return to Dashboard
    </a>
</div>
@endsection
