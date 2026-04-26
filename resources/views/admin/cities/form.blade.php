@extends('layouts.admin')

@section('title', $city->exists ? 'Edit City' : 'Add City')

@section('content')
<div class="mb-6 flex items-center gap-4">
    <a href="{{ route('admin.cities.index') }}" class="w-10 h-10 rounded-xl bg-white dark:bg-slate-800 shadow-sm border border-slate-100 dark:border-slate-700 flex items-center justify-center text-slate-500 hover:text-primary-600 transition-colors">
        <i class="fa-solid fa-arrow-left"></i>
    </a>
    <div>
        <h1 class="text-2xl font-bold text-slate-800 dark:text-white">{{ $city->exists ? 'Edit City' : 'Add New City' }}</h1>
    </div>
</div>

<div class="max-w-2xl bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 p-6">
    <form action="{{ $city->exists ? route('admin.cities.update', $city) : route('admin.cities.store') }}" method="POST">
        @csrf
        @if($city->exists)
            @method('PUT')
        @endif

        <div class="space-y-6">
            <div>
                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">City Name <span class="text-red-500">*</span></label>
                <input type="text" name="name" value="{{ old('name', $city->name) }}" required
                       class="w-full rounded-xl border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-white focus:ring-primary-500 focus:border-primary-500">
                @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Province</label>
                    <input type="text" name="province" value="{{ old('province', $city->province) }}"
                           class="w-full rounded-xl border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-white focus:ring-primary-500 focus:border-primary-500">
                    @error('province')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Region</label>
                    <input type="text" name="region" value="{{ old('region', $city->region) }}"
                           class="w-full rounded-xl border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-white focus:ring-primary-500 focus:border-primary-500">
                    @error('region')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Status <span class="text-red-500">*</span></label>
                <select name="status" required class="w-full rounded-xl border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-white focus:ring-primary-500 focus:border-primary-500">
                    <option value="active" {{ old('status', $city->status) === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ old('status', $city->status) === 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
                @error('status')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
        </div>

        <div class="mt-8 pt-6 border-t border-slate-100 dark:border-slate-700 flex justify-end gap-3">
            <a href="{{ route('admin.cities.index') }}" class="px-5 py-2.5 rounded-xl border border-slate-200 dark:border-slate-600 text-slate-600 dark:text-slate-300 font-semibold hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors">
                Cancel
            </a>
            <button type="submit" class="px-5 py-2.5 rounded-xl bg-primary-600 text-white font-semibold hover:bg-primary-700 transition-colors">
                {{ $city->exists ? 'Save Changes' : 'Create City' }}
            </button>
        </div>
    </form>
</div>
@endsection
