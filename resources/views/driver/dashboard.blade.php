@extends('layouts.driver')

@section('header', 'Driver Dashboard')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
        <div class="p-6 text-gray-900 dark:text-gray-100">
            {{ __("Welcome back, ") }} {{ $driver->name }}! You are logged in as a driver.
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <!-- Stat Card 1 -->
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
            <div class="text-gray-500 dark:text-gray-400 text-sm font-medium">Upcoming Trips</div>
            <div class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">0</div>
        </div>
        
        <!-- Stat Card 2 -->
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
            <div class="text-gray-500 dark:text-gray-400 text-sm font-medium">Completed Trips</div>
            <div class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">0</div>
        </div>

        <!-- Stat Card 3 -->
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
            <div class="text-gray-500 dark:text-gray-400 text-sm font-medium">Performance Rating</div>
            <div class="mt-2 text-3xl font-bold text-gray-900 dark:text-white">N/A</div>
        </div>
    </div>

    <!-- Additional Content Area -->
    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6">
            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Recent Activity</h3>
            <p class="text-gray-500 dark:text-gray-400">No recent activity to show.</p>
        </div>
    </div>
</div>
@endsection
