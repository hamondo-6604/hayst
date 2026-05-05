@extends('layouts.driver')

@section('title', 'Trip History')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Trip History</h2>
            <p class="text-gray-500 dark:text-gray-400 mt-1">Review your past completed trips.</p>
        </div>
    </div>

    <!-- Past Trips -->
    <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-xl overflow-hidden border border-gray-200 dark:border-gray-700">
        <div class="p-6 text-center text-gray-500 dark:text-gray-400">
            <i class="fa-solid fa-clock-rotate-left text-4xl mb-3 opacity-50"></i>
            <p>You haven't completed any trips yet.</p>
        </div>
    </div>
</div>
@endsection
