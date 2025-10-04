<!-- resources/views/admin/roles/index.blade.php -->
@extends('layouts.admin')

@section('title', 'Role Management')
@section('breadcrumb', 'Roles')

@section('content')
<div class="px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8">
        <div class="sm:flex sm:items-center sm:justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Role Management</h1>
                <p class="mt-2 text-sm text-gray-700">Manage system roles and permissions</p>
            </div>
            <a href="{{ route('admin.roles.create') }}" class="inline-flex items-center px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700">
                <i data-lucide="plus" class="w-4 h-4 mr-2"></i>
                Add New Role
            </a>
        </div>
    </div>

    <!-- Dynamic Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-xl p-6 border shadow-sm">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                    <i data-lucide="shield-check" class="w-6 h-6 text-purple-600"></i>
                </div>
                <div class="ml-4">
                    <div class="text-sm font-medium text-gray-600">Total Roles</div>
                    <div class="text-2xl font-bold text-gray-900">{{ $totalRoles }}</div>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl p-6 border shadow-sm">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                    <i data-lucide="users" class="w-6 h-6 text-blue-600"></i>
                </div>
                <div class="ml-4">
                    <div class="text-sm font-medium text-gray-600">Users Assigned</div>
                    <div class="text-2xl font-bold text-gray-900">{{ $totalUsersAssigned }}</div>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl p-6 border shadow-sm">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                    <i data-lucide="key" class="w-6 h-6 text-green-600"></i>
                </div>
                <div class="ml-4">
                    <div class="text-sm font-medium text-gray-600">Permissions</div>
                    <div class="text-2xl font-bold text-gray-900">{{ $totalPermissions }}</div>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl p-6 border shadow-sm">
            <div class="flex items-center">
                <div class="w-12 h-12 bg-orange-100 rounded-xl flex items-center justify-center">
                    <i data-lucide="crown" class="w-6 h-6 text-orange-600"></i>
                </div>
                <div class="ml-4">
                    <div class="text-sm font-medium text-gray-600">Admins</div>
                    <div class="text-2xl font-bold text-gray-900">{{ $adminCount }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Roles Table -->
    <div class="bg-white rounded-xl border overflow-hidden shadow-sm">
        <div class="p-6 border-b">
            <h3 class="text-lg font-semibold text-gray-900">System Roles</h3>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Role</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Description</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Users</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($roles as $role)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-purple-100 rounded-xl flex items-center justify-center">
                                    <i data-lucide="shield" class="w-5 h-5 text-purple-600"></i>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $role->display_name }}</div>
                                    <div class="text-sm text-gray-500">{{ $role->name }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900">{{ $role->description ?? 'No description' }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm font-medium text-gray-900">{{ $role->users_count }}</div>
                            <div class="text-sm text-gray-500">users assigned</div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                {{ $role->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $role->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center space-x-3">
                                <!-- VIEW ICON ADDED -->
                                <a href="{{ route('admin.roles.show', $role) }}" 
                                   class="text-blue-600 hover:text-blue-900 transition-colors"
                                   title="View Role">
                                    <i data-lucide="eye" class="w-4 h-4"></i>
                                </a>
                                
                                <!-- EDIT ICON -->
                                <a href="{{ route('admin.roles.edit', $role) }}" 
                                   class="text-purple-600 hover:text-purple-900 transition-colors"
                                   title="Edit Role">
                                    <i data-lucide="edit" class="w-4 h-4"></i>
                                </a>
                                
                                <!-- DELETE ICON (only if no users assigned) -->
                                @if($role->users_count == 0)
                                    <form action="{{ route('admin.roles.destroy', $role) }}" method="POST" class="inline" onsubmit="return confirm('Delete this role?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="text-red-600 hover:text-red-900 transition-colors"
                                                title="Delete Role">
                                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                                        </button>
                                    </form>
                                @else
                                    <!-- Show disabled delete icon when users are assigned -->
                                    <span class="text-gray-400 cursor-not-allowed" 
                                          title="Cannot delete role with assigned users">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    </span>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                            <div class="flex flex-col items-center">
                                <i data-lucide="shield-off" class="w-12 h-12 text-gray-400 mb-4"></i>
                                <p class="text-lg font-medium text-gray-900">No roles found</p>
                                <p class="text-sm text-gray-500 mb-4">Create your first role to get started.</p>
                                <a href="{{ route('admin.roles.create') }}" 
                                   class="inline-flex items-center px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700">
                                    <i data-lucide="plus" class="w-4 h-4 mr-2"></i>
                                    Add New Role
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
