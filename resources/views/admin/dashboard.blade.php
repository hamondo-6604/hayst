@extends('layouts.admin')

@section('title', 'Overview')

@section('content')
<div class="mb-8">
    <h1 class="text-2xl font-bold text-slate-800">Dashboard Overview</h1>
    <p class="text-sm text-slate-500 mt-1">Here is what's happening with Mindanao Express today.</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Stat Card: Users -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 flex items-center gap-4">
        <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center text-xl">
            <i class="fa-solid fa-users"></i>
        </div>
        <div>
            <p class="text-sm font-medium text-slate-500 mb-0.5">Total Customers</p>
            <h3 class="text-2xl font-bold text-slate-800">{{ number_format($stats['total_users']) }}</h3>
        </div>
    </div>

    <!-- Stat Card: Bookings -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 flex items-center gap-4">
        <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-xl flex items-center justify-center text-xl">
            <i class="fa-solid fa-ticket"></i>
        </div>
        <div>
            <p class="text-sm font-medium text-slate-500 mb-0.5">Today's Bookings</p>
            <h3 class="text-2xl font-bold text-slate-800">{{ number_format($stats['today_bookings']) }}</h3>
        </div>
    </div>

    <!-- Stat Card: Active Trips -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 flex items-center gap-4">
        <div class="w-12 h-12 bg-amber-50 text-amber-600 rounded-xl flex items-center justify-center text-xl">
            <i class="fa-solid fa-bus"></i>
        </div>
        <div>
            <p class="text-sm font-medium text-slate-500 mb-0.5">Active Trips</p>
            <h3 class="text-2xl font-bold text-slate-800">{{ number_format($stats['active_trips']) }}</h3>
        </div>
    </div>

    <!-- Stat Card: Revenue -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 flex items-center gap-4">
        <div class="w-12 h-12 bg-purple-50 text-purple-600 rounded-xl flex items-center justify-center text-xl">
            <i class="fa-solid fa-coins"></i>
        </div>
        <div>
            <p class="text-sm font-medium text-slate-500 mb-0.5">Today's Revenue</p>
            <h3 class="text-2xl font-bold text-slate-800">₱{{ number_format($stats['today_revenue'], 2) }}</h3>
        </div>
    </div>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-8 text-center">
    <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-300 text-3xl">
        <i class="fa-solid fa-chart-area"></i>
    </div>
    <h3 class="text-lg font-semibold text-slate-800 mb-2">More Analytics Coming Soon</h3>
    <p class="text-sm text-slate-500 max-w-sm mx-auto">Detailed charts and graphs for bookings, revenue trends, and route popularity are currently under development.</p>
</div>
@endsection
