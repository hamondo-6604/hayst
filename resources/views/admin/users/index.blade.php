@extends('layouts.admin')

@section('title', 'Manage Users')

@section('content')
<div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
    <div>
        <h1 class="text-2xl font-bold text-slate-800 dark:text-white">Users</h1>
        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Manage system administrators, drivers, and customers.</p>
    </div>
    <button onclick="openAdminModal('create-user-modal')" class="px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white text-sm font-bold rounded-xl shadow-sm transition-colors flex items-center gap-2">
        <i class="fa-solid fa-plus"></i> Add User
    </button>
</div>

<!-- Filters -->
<div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 p-4 mb-6">
    <form method="GET" action="{{ route('admin.users.index') }}" class="flex flex-col sm:flex-row gap-4 items-end">
        <div class="flex-1">
            <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 mb-1">Search Users</label>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name, email, or phone..." 
                   class="w-full px-4 py-2 rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-white text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 outline-none transition-colors">
        </div>
        <div class="w-full sm:w-48 relative" data-custom-select>
            <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 mb-1">Role</label>
            <input type="hidden" name="role" value="{{ request('role') }}" class="custom-select-input">
            
            <button type="button" onclick="this.nextElementSibling.classList.toggle('hidden')" class="w-full px-4 py-2 flex items-center justify-between rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-white text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors cursor-pointer">
                <span class="custom-select-text">
                    @if(request('role') === 'admin') Admin
                    @elseif(request('role') === 'driver') Driver
                    @elseif(request('role') === 'customer') Customer
                    @else All Roles
                    @endif
                </span>
                <i class="fa-solid fa-chevron-down text-[10px] text-slate-400"></i>
            </button>
            
            <div class="custom-select-menu hidden absolute left-0 right-0 top-full mt-2 bg-white dark:bg-slate-800 rounded-xl shadow-xl border border-slate-100 dark:border-slate-700 py-1.5 z-50 overflow-hidden">
                <div class="px-4 py-2.5 text-sm font-medium text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-700 cursor-pointer transition-colors" data-value="" onclick="selectCustomOption(this)">All Roles</div>
                <div class="px-4 py-2.5 text-sm font-medium text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-700 cursor-pointer transition-colors" data-value="admin" onclick="selectCustomOption(this)">Admin</div>
                <div class="px-4 py-2.5 text-sm font-medium text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-700 cursor-pointer transition-colors" data-value="driver" onclick="selectCustomOption(this)">Driver</div>
                <div class="px-4 py-2.5 text-sm font-medium text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-700 cursor-pointer transition-colors" data-value="customer" onclick="selectCustomOption(this)">Customer</div>
            </div>
        </div>
        <div class="w-full sm:w-48 relative" data-custom-select>
            <label class="block text-xs font-semibold text-slate-500 dark:text-slate-400 mb-1">Status</label>
            <input type="hidden" name="status" value="{{ request('status') }}" class="custom-select-input">
            
            <button type="button" onclick="this.nextElementSibling.classList.toggle('hidden')" class="w-full px-4 py-2 flex items-center justify-between rounded-xl border border-slate-200 dark:border-slate-600 bg-white dark:bg-slate-700 text-slate-800 dark:text-white text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-colors cursor-pointer">
                <span class="custom-select-text">
                    @if(request('status') === 'active') Active
                    @elseif(request('status') === 'inactive') Inactive
                    @elseif(request('status') === 'suspended') Suspended
                    @else All Statuses
                    @endif
                </span>
                <i class="fa-solid fa-chevron-down text-[10px] text-slate-400"></i>
            </button>
            
            <div class="custom-select-menu hidden absolute left-0 right-0 top-full mt-2 bg-white dark:bg-slate-800 rounded-xl shadow-xl border border-slate-100 dark:border-slate-700 py-1.5 z-50 overflow-hidden">
                <div class="px-4 py-2.5 text-sm font-medium text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-700 cursor-pointer transition-colors" data-value="" onclick="selectCustomOption(this)">All Statuses</div>
                <div class="px-4 py-2.5 text-sm font-medium text-emerald-700 bg-emerald-50 dark:bg-emerald-900/20 dark:text-emerald-400 hover:bg-emerald-100 dark:hover:bg-emerald-900/40 cursor-pointer transition-colors" data-value="active" onclick="selectCustomOption(this)">Active</div>
                <div class="px-4 py-2.5 text-sm font-medium text-slate-700 dark:text-slate-200 hover:bg-slate-50 dark:hover:bg-slate-700 cursor-pointer transition-colors" data-value="inactive" onclick="selectCustomOption(this)">Inactive</div>
                <div class="px-4 py-2.5 text-sm font-medium text-red-700 bg-red-50 dark:bg-red-900/20 dark:text-red-400 hover:bg-red-100 dark:hover:bg-red-900/40 cursor-pointer transition-colors" data-value="suspended" onclick="selectCustomOption(this)">Suspended</div>
            </div>
        </div>
        <div>
            <button type="submit" class="px-5 py-2.5 bg-slate-800 hover:bg-slate-900 dark:bg-slate-700 dark:hover:bg-slate-600 text-white text-sm font-semibold rounded-xl transition-colors">
                Filter
            </button>
            @if(request()->hasAny(['search', 'role', 'status']))
                <a href="{{ route('admin.users.index') }}" class="ml-2 text-sm text-primary-600 hover:text-primary-700 dark:text-primary-400">Clear</a>
            @endif
        </div>
    </form>
</div>

<!-- Table -->
<div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-100 dark:border-slate-700 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 dark:bg-slate-700/50 border-b border-slate-100 dark:border-slate-700 text-xs uppercase tracking-wider text-slate-500 dark:text-slate-400 font-semibold">
                    <th class="p-4">User</th>
                    <th class="p-4">Contact Info</th>
                    <th class="p-4">Role & Type</th>
                    <th class="p-4">Joined Date</th>
                    <th class="p-4">Status</th>
                    <th class="p-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                @forelse($users as $user)
                <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors">
                    <td class="p-4 flex items-center gap-3">
                        @if($user->image_url)
                            <img src="{{ asset('storage/' . $user->image_url) }}" alt="{{ $user->name }}" class="w-10 h-10 rounded-full object-cover border border-slate-200 dark:border-slate-700">
                        @else
                            <div class="w-10 h-10 rounded-full bg-primary-100 dark:bg-slate-700 text-primary-700 dark:text-primary-400 flex items-center justify-center font-bold text-sm">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                        @endif
                        <div class="font-bold text-slate-800 dark:text-slate-200">{{ $user->name }}</div>
                    </td>
                    <td class="p-4">
                        <div class="text-sm font-medium text-slate-800 dark:text-slate-200">{{ $user->email }}</div>
                        <div class="text-xs text-slate-500 mt-0.5">{{ $user->phone ?? '—' }}</div>
                    </td>
                    <td class="p-4">
                        <div class="text-sm font-bold text-slate-800 dark:text-slate-200 capitalize">{{ $user->role }}</div>
                        @if($user->discountType)
                            <div class="text-xs text-primary-600 dark:text-primary-400 mt-0.5"><i class="fa-solid fa-tag text-[10px]"></i> {{ $user->discountType->display_name }}</div>
                        @elseif($user->userType)
                            <div class="text-xs text-slate-500 mt-0.5">{{ $user->userType->display_name }}</div>
                        @endif
                    </td>
                    <td class="p-4">
                        <div class="text-sm text-slate-800 dark:text-slate-200">{{ $user->created_at->format('M d, Y') }}</div>
                        <div class="text-xs text-slate-500 mt-0.5">{{ $user->created_at->diffForHumans() }}</div>
                    </td>
                    <td class="p-4">
                        @php
                            $badgeColors = [
                                'active' => 'bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400',
                                'inactive' => 'bg-slate-100 text-slate-700 dark:bg-slate-700 dark:text-slate-300',
                                'suspended' => 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-400',
                            ];
                            $colorClass = $badgeColors[$user->status] ?? 'bg-slate-100 text-slate-700';
                        @endphp
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $colorClass }}">
                            {{ ucfirst($user->status) }}
                        </span>
                    </td>
                    <td class="p-4 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <button type="button" onclick="openEditUserModal({{ json_encode($user) }})" class="w-8 h-8 rounded-lg bg-blue-50 hover:bg-blue-100 dark:bg-blue-900/20 dark:hover:bg-blue-900/40 text-blue-600 dark:text-blue-400 flex items-center justify-center transition-colors" title="Edit">
                                <i class="fa-solid fa-pen text-sm"></i>
                            </button>
                            <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this user?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="w-8 h-8 rounded-lg bg-red-50 hover:bg-red-100 dark:bg-red-900/20 dark:hover:bg-red-900/40 text-red-600 dark:text-red-400 flex items-center justify-center transition-colors" title="Delete">
                                    <i class="fa-solid fa-trash-can text-sm"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="p-8 text-center text-slate-500 dark:text-slate-400">
                        <div class="text-4xl mb-2"><i class="fa-solid fa-users text-slate-300 dark:text-slate-600"></i></div>
                        <p>No users found matching your criteria.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($users->hasPages())
    <div class="p-4 border-t border-slate-100 dark:border-slate-700">
        {{ $users->links() }}
    </div>
    @endif
</div>

<!-- Create User Modal -->
<div id="create-user-modal" class="hidden fixed inset-0 z-[100] items-center justify-center p-4 sm:p-6 opacity-0 transition-opacity duration-300">
    <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm" onclick="closeAdminModal('create-user-modal')"></div>
    <div class="admin-modal-panel relative w-full max-w-2xl bg-white dark:bg-slate-800 rounded-2xl shadow-2xl overflow-hidden ring-1 ring-slate-200 dark:ring-slate-700 transform scale-95 transition-transform duration-300 flex flex-col max-h-[90vh]">
        
        <!-- Header -->
        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100 dark:border-slate-700 shrink-0">
            <h3 class="text-lg font-bold text-slate-800 dark:text-white">Add New User</h3>
            <button onclick="closeAdminModal('create-user-modal')" class="text-slate-400 hover:text-slate-500 dark:hover:text-slate-300 transition-colors">
                <i class="fa-solid fa-xmark text-xl"></i>
            </button>
        </div>

        <!-- Body -->
        <div class="p-6 overflow-y-auto">
            <form id="create-user-form" action="{{ route('admin.users.store') }}" method="POST" onsubmit="handleAjaxForm(this, 'create-user-modal', null, event)">
                @csrf
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1">Full Name <span class="text-red-500">*</span></label>
                        <input type="text" name="name" required class="w-full px-4 py-2 text-sm bg-white dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-xl text-slate-800 dark:text-white outline-none focus:ring-2 focus:ring-primary-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1">Email <span class="text-red-500">*</span></label>
                        <input type="email" name="email" required class="w-full px-4 py-2 text-sm bg-white dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-xl text-slate-800 dark:text-white outline-none focus:ring-2 focus:ring-primary-500">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1">Phone Number</label>
                        <input type="text" name="phone" class="w-full px-4 py-2 text-sm bg-white dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-xl text-slate-800 dark:text-white outline-none focus:ring-2 focus:ring-primary-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1">Password <span class="text-red-500">*</span></label>
                        <input type="password" name="password" required minlength="8" class="w-full px-4 py-2 text-sm bg-white dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-xl text-slate-800 dark:text-white outline-none focus:ring-2 focus:ring-primary-500">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div class="relative" data-custom-select>
                        <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1">Role <span class="text-red-500">*</span></label>
                        <input type="hidden" name="role" value="customer" class="custom-select-input" required>
                        <button type="button" onclick="this.nextElementSibling.classList.toggle('hidden')" class="w-full flex items-center justify-between px-4 py-2 text-sm bg-white dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-xl text-slate-800 dark:text-white outline-none focus:ring-2 focus:ring-primary-500 cursor-pointer">
                            <span class="custom-select-text">Customer</span>
                            <i class="fa-solid fa-chevron-down text-[10px] text-slate-400"></i>
                        </button>
                        <div class="custom-select-menu hidden mt-2 bg-slate-100 dark:bg-slate-700/50 rounded-xl p-1 flex flex-col gap-1 z-50 absolute left-0 right-0">
                            <div class="px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-200 dark:text-slate-200 dark:hover:bg-slate-600 rounded-lg cursor-pointer transition-colors" data-value="customer" onclick="selectCustomOption(this)">Customer</div>
                            <div class="px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-200 dark:text-slate-200 dark:hover:bg-slate-600 rounded-lg cursor-pointer transition-colors" data-value="driver" onclick="selectCustomOption(this)">Driver</div>
                            <div class="px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-200 dark:text-slate-200 dark:hover:bg-slate-600 rounded-lg cursor-pointer transition-colors" data-value="admin" onclick="selectCustomOption(this)">Admin</div>
                        </div>
                    </div>
                    <div class="relative" data-custom-select>
                        <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1">Status <span class="text-red-500">*</span></label>
                        <input type="hidden" name="status" value="active" class="custom-select-input" required>
                        <button type="button" onclick="this.nextElementSibling.classList.toggle('hidden')" class="w-full flex items-center justify-between px-4 py-2 text-sm bg-white dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-xl text-slate-800 dark:text-white outline-none focus:ring-2 focus:ring-primary-500 cursor-pointer">
                            <span class="custom-select-text">Active</span>
                            <i class="fa-solid fa-chevron-down text-[10px] text-slate-400"></i>
                        </button>
                        <div class="custom-select-menu hidden mt-2 bg-slate-100 dark:bg-slate-700/50 rounded-xl p-1 flex flex-col gap-1 z-50 absolute left-0 right-0">
                            <div class="px-4 py-2 text-sm font-semibold text-emerald-700 hover:bg-emerald-100 dark:text-emerald-400 dark:hover:bg-emerald-900/40 rounded-lg cursor-pointer transition-colors" data-value="active" onclick="selectCustomOption(this)">Active</div>
                            <div class="px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-200 dark:text-slate-200 dark:hover:bg-slate-600 rounded-lg cursor-pointer transition-colors" data-value="inactive" onclick="selectCustomOption(this)">Inactive</div>
                            <div class="px-4 py-2 text-sm font-semibold text-red-700 hover:bg-red-100 dark:text-red-400 dark:hover:bg-red-900/40 rounded-lg cursor-pointer transition-colors" data-value="suspended" onclick="selectCustomOption(this)">Suspended</div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div class="relative" data-custom-select>
                        <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1">User Type</label>
                        <input type="hidden" name="user_type_id" value="" class="custom-select-input">
                        <button type="button" onclick="this.nextElementSibling.classList.toggle('hidden')" class="w-full flex items-center justify-between px-4 py-2 text-sm bg-white dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-xl text-slate-800 dark:text-white outline-none focus:ring-2 focus:ring-primary-500 cursor-pointer">
                            <span class="custom-select-text">None</span>
                            <i class="fa-solid fa-chevron-down text-[10px] text-slate-400"></i>
                        </button>
                        <div class="custom-select-menu hidden mt-2 bg-slate-100 dark:bg-slate-700/50 rounded-xl p-1 flex flex-col gap-1 z-50 absolute left-0 right-0">
                            <div class="px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-200 dark:text-slate-200 dark:hover:bg-slate-600 rounded-lg cursor-pointer transition-colors" data-value="" onclick="selectCustomOption(this)">None</div>
                            @foreach($userTypes as $type)
                            <div class="px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-200 dark:text-slate-200 dark:hover:bg-slate-600 rounded-lg cursor-pointer transition-colors" data-value="{{ $type->id }}" onclick="selectCustomOption(this)">{{ $type->display_name }}</div>
                            @endforeach
                        </div>
                    </div>
                    <div class="relative" data-custom-select>
                        <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1">Discount Type</label>
                        <input type="hidden" name="discount_type_id" value="" class="custom-select-input">
                        <button type="button" onclick="this.nextElementSibling.classList.toggle('hidden')" class="w-full flex items-center justify-between px-4 py-2 text-sm bg-white dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-xl text-slate-800 dark:text-white outline-none focus:ring-2 focus:ring-primary-500 cursor-pointer">
                            <span class="custom-select-text">None</span>
                            <i class="fa-solid fa-chevron-down text-[10px] text-slate-400"></i>
                        </button>
                        <div class="custom-select-menu hidden mt-2 bg-slate-100 dark:bg-slate-700/50 rounded-xl p-1 flex flex-col gap-1 z-50 absolute left-0 right-0">
                            <div class="px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-200 dark:text-slate-200 dark:hover:bg-slate-600 rounded-lg cursor-pointer transition-colors" data-value="" onclick="selectCustomOption(this)">None</div>
                            @foreach($discountTypes as $dtype)
                            <div class="px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-200 dark:text-slate-200 dark:hover:bg-slate-600 rounded-lg cursor-pointer transition-colors" data-value="{{ $dtype->id }}" onclick="selectCustomOption(this)">{{ $dtype->display_name }} ({{ round($dtype->percentage * 100) }}%)</div>
                            @endforeach
                        </div>
                    </div>
                </div>
                
                <div class="mt-8 flex items-center justify-end gap-3 pt-6 border-t border-slate-100 dark:border-slate-700">
                    <button type="button" onclick="closeAdminModal('create-user-modal')" class="px-5 py-2.5 text-sm font-bold text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-700 rounded-xl transition-colors">
                        Cancel
                    </button>
                    <button type="submit" class="px-5 py-2.5 text-sm font-bold text-white bg-primary-600 hover:bg-primary-700 rounded-xl shadow-sm transition-colors flex items-center gap-2">
                        <i class="fa-solid fa-save"></i> Save User
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit User Modal -->
<div id="edit-user-modal" class="hidden fixed inset-0 z-[100] items-center justify-center p-4 sm:p-6 opacity-0 transition-opacity duration-300">
    <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm" onclick="closeAdminModal('edit-user-modal')"></div>
    <div class="admin-modal-panel relative w-full max-w-2xl bg-white dark:bg-slate-800 rounded-2xl shadow-2xl overflow-hidden ring-1 ring-slate-200 dark:ring-slate-700 transform scale-95 transition-transform duration-300 flex flex-col max-h-[90vh]">
        
        <!-- Header -->
        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100 dark:border-slate-700 shrink-0">
            <h3 class="text-lg font-bold text-slate-800 dark:text-white">Edit User</h3>
            <button onclick="closeAdminModal('edit-user-modal')" class="text-slate-400 hover:text-slate-500 dark:hover:text-slate-300 transition-colors">
                <i class="fa-solid fa-xmark text-xl"></i>
            </button>
        </div>

        <!-- Body -->
        <div class="p-6 overflow-y-auto">
            <form id="edit-user-form" method="POST" onsubmit="handleAjaxForm(this, 'edit-user-modal', null, event)">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1">Full Name <span class="text-red-500">*</span></label>
                        <input type="text" id="edit_name" name="name" required class="w-full px-4 py-2 text-sm bg-white dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-xl text-slate-800 dark:text-white outline-none focus:ring-2 focus:ring-primary-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1">Email <span class="text-red-500">*</span></label>
                        <input type="email" id="edit_email" name="email" required class="w-full px-4 py-2 text-sm bg-white dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-xl text-slate-800 dark:text-white outline-none focus:ring-2 focus:ring-primary-500">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1">Phone Number</label>
                        <input type="text" id="edit_phone" name="phone" class="w-full px-4 py-2 text-sm bg-white dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-xl text-slate-800 dark:text-white outline-none focus:ring-2 focus:ring-primary-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1">Password <span class="text-xs font-normal text-slate-400">(Leave blank to keep current)</span></label>
                        <input type="password" name="password" minlength="8" class="w-full px-4 py-2 text-sm bg-white dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-xl text-slate-800 dark:text-white outline-none focus:ring-2 focus:ring-primary-500">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div class="relative" data-custom-select>
                        <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1">Role <span class="text-red-500">*</span></label>
                        <input type="hidden" id="edit_role" name="role" class="custom-select-input" required>
                        <button type="button" onclick="this.nextElementSibling.classList.toggle('hidden')" class="w-full flex items-center justify-between px-4 py-2 text-sm bg-white dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-xl text-slate-800 dark:text-white outline-none focus:ring-2 focus:ring-primary-500 cursor-pointer">
                            <span class="custom-select-text capitalize" id="edit_role_text"></span>
                            <i class="fa-solid fa-chevron-down text-[10px] text-slate-400"></i>
                        </button>
                        <div class="custom-select-menu hidden mt-2 bg-slate-100 dark:bg-slate-700/50 rounded-xl p-1 flex flex-col gap-1 z-50 absolute left-0 right-0">
                            <div class="px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-200 dark:text-slate-200 dark:hover:bg-slate-600 rounded-lg cursor-pointer transition-colors" data-value="customer" onclick="selectCustomOption(this)">Customer</div>
                            <div class="px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-200 dark:text-slate-200 dark:hover:bg-slate-600 rounded-lg cursor-pointer transition-colors" data-value="driver" onclick="selectCustomOption(this)">Driver</div>
                            <div class="px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-200 dark:text-slate-200 dark:hover:bg-slate-600 rounded-lg cursor-pointer transition-colors" data-value="admin" onclick="selectCustomOption(this)">Admin</div>
                        </div>
                    </div>
                    <div class="relative" data-custom-select>
                        <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1">Status <span class="text-red-500">*</span></label>
                        <input type="hidden" id="edit_status" name="status" class="custom-select-input" required>
                        <button type="button" onclick="this.nextElementSibling.classList.toggle('hidden')" class="w-full flex items-center justify-between px-4 py-2 text-sm bg-white dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-xl text-slate-800 dark:text-white outline-none focus:ring-2 focus:ring-primary-500 cursor-pointer">
                            <span class="custom-select-text capitalize" id="edit_status_text"></span>
                            <i class="fa-solid fa-chevron-down text-[10px] text-slate-400"></i>
                        </button>
                        <div class="custom-select-menu hidden mt-2 bg-slate-100 dark:bg-slate-700/50 rounded-xl p-1 flex flex-col gap-1 z-50 absolute left-0 right-0">
                            <div class="px-4 py-2 text-sm font-semibold text-emerald-700 hover:bg-emerald-100 dark:text-emerald-400 dark:hover:bg-emerald-900/40 rounded-lg cursor-pointer transition-colors" data-value="active" onclick="selectCustomOption(this)">Active</div>
                            <div class="px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-200 dark:text-slate-200 dark:hover:bg-slate-600 rounded-lg cursor-pointer transition-colors" data-value="inactive" onclick="selectCustomOption(this)">Inactive</div>
                            <div class="px-4 py-2 text-sm font-semibold text-red-700 hover:bg-red-100 dark:text-red-400 dark:hover:bg-red-900/40 rounded-lg cursor-pointer transition-colors" data-value="suspended" onclick="selectCustomOption(this)">Suspended</div>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div class="relative" data-custom-select>
                        <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1">User Type</label>
                        <input type="hidden" id="edit_user_type_id" name="user_type_id" class="custom-select-input">
                        <button type="button" onclick="this.nextElementSibling.classList.toggle('hidden')" class="w-full flex items-center justify-between px-4 py-2 text-sm bg-white dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-xl text-slate-800 dark:text-white outline-none focus:ring-2 focus:ring-primary-500 cursor-pointer">
                            <span class="custom-select-text" id="edit_user_type_text">None</span>
                            <i class="fa-solid fa-chevron-down text-[10px] text-slate-400"></i>
                        </button>
                        <div class="custom-select-menu hidden mt-2 bg-slate-100 dark:bg-slate-700/50 rounded-xl p-1 flex flex-col gap-1 z-50 absolute left-0 right-0">
                            <div class="px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-200 dark:text-slate-200 dark:hover:bg-slate-600 rounded-lg cursor-pointer transition-colors" data-value="" onclick="selectCustomOption(this)">None</div>
                            @foreach($userTypes as $type)
                            <div class="px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-200 dark:text-slate-200 dark:hover:bg-slate-600 rounded-lg cursor-pointer transition-colors" data-value="{{ $type->id }}" onclick="selectCustomOption(this)">{{ $type->display_name }}</div>
                            @endforeach
                        </div>
                    </div>
                    <div class="relative" data-custom-select>
                        <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1">Discount Type</label>
                        <input type="hidden" id="edit_discount_type_id" name="discount_type_id" class="custom-select-input">
                        <button type="button" onclick="this.nextElementSibling.classList.toggle('hidden')" class="w-full flex items-center justify-between px-4 py-2 text-sm bg-white dark:bg-slate-700 border border-slate-200 dark:border-slate-600 rounded-xl text-slate-800 dark:text-white outline-none focus:ring-2 focus:ring-primary-500 cursor-pointer">
                            <span class="custom-select-text" id="edit_discount_type_text">None</span>
                            <i class="fa-solid fa-chevron-down text-[10px] text-slate-400"></i>
                        </button>
                        <div class="custom-select-menu hidden mt-2 bg-slate-100 dark:bg-slate-700/50 rounded-xl p-1 flex flex-col gap-1 z-50 absolute left-0 right-0">
                            <div class="px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-200 dark:text-slate-200 dark:hover:bg-slate-600 rounded-lg cursor-pointer transition-colors" data-value="" onclick="selectCustomOption(this)">None</div>
                            @foreach($discountTypes as $dtype)
                            <div class="px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-200 dark:text-slate-200 dark:hover:bg-slate-600 rounded-lg cursor-pointer transition-colors" data-value="{{ $dtype->id }}" onclick="selectCustomOption(this)">{{ $dtype->display_name }} ({{ round($dtype->percentage * 100) }}%)</div>
                            @endforeach
                        </div>
                    </div>
                </div>
                
                <div class="mt-8 flex items-center justify-end gap-3 pt-6 border-t border-slate-100 dark:border-slate-700">
                    <button type="button" onclick="closeAdminModal('edit-user-modal')" class="px-5 py-2.5 text-sm font-bold text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-700 rounded-xl transition-colors">
                        Cancel
                    </button>
                    <button type="submit" class="px-5 py-2.5 text-sm font-bold text-white bg-primary-600 hover:bg-primary-700 rounded-xl shadow-sm transition-colors flex items-center gap-2">
                        <i class="fa-solid fa-save"></i> Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openEditUserModal(user) {
    const form = document.getElementById('edit-user-form');
    form.action = `/admin/users/${user.id}`;
    
    document.getElementById('edit_name').value = user.name;
    document.getElementById('edit_email').value = user.email;
    document.getElementById('edit_phone').value = user.phone || '';
    
    document.getElementById('edit_role').value = user.role;
    document.getElementById('edit_role_text').textContent = user.role;
    
    document.getElementById('edit_status').value = user.status;
    document.getElementById('edit_status_text').textContent = user.status;
    
    document.getElementById('edit_user_type_id').value = user.user_type_id || '';
    document.getElementById('edit_user_type_text').textContent = user.user_type ? user.user_type.display_name : 'None';
    
    document.getElementById('edit_discount_type_id').value = user.discount_type_id || '';
    document.getElementById('edit_discount_type_text').textContent = user.discount_type ? `${user.discount_type.display_name} (${Math.round(user.discount_type.percentage * 100)}%)` : 'None';
    
    openAdminModal('edit-user-modal');
}
</script>
@endsection
