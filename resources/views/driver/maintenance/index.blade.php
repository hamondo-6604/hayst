@extends('layouts.driver')

@section('title', 'Maintenance Requests')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="mb-6 flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Maintenance Requests</h2>
            <p class="text-gray-500 dark:text-gray-400 mt-1">Report issues or view past maintenance logs for your bus.</p>
        </div>
        <div>
            <button class="bg-primary-600 hover:bg-primary-700 text-white px-4 py-2 rounded-xl text-sm font-semibold shadow-sm flex items-center gap-2">
                <i class="fa-solid fa-plus"></i> New Request
            </button>
        </div>
    </div>

    <!-- Maintenance Records -->
    <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-xl overflow-hidden border border-gray-200 dark:border-gray-700">
        <div class="p-6 text-center text-gray-500 dark:text-gray-400">
            <i class="fa-solid fa-clipboard-check text-4xl mb-3 opacity-50"></i>
            <p>You have no maintenance requests logged.</p>
        </div>
    </div>
</div>
@endsection
