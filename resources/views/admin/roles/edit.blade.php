@extends('layouts.admin')

@section('title', 'Edit Role')
@section('breadcrumb', 'Roles / Edit Role')

@section('content')
<div class="px-4 sm:px-6 lg:px-8 py-8">
    <!-- Page Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Edit Role</h1>
                <p class="mt-2 text-sm text-gray-700">Update role information and permissions</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('admin.roles.show', $role) }}" 
                   class="inline-flex items-center px-4 py-2 bg-blue-100 border border-blue-300 rounded-lg text-sm font-medium text-blue-700 hover:bg-blue-200">
                    <i data-lucide="eye" class="w-4 h-4 mr-2"></i>
                    View Role
                </a>
                <a href="{{ route('admin.roles.index') }}" 
                   class="inline-flex items-center px-4 py-2 bg-gray-100 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-200">
                    <i data-lucide="arrow-left" class="w-4 h-4 mr-2"></i>
                    Back to Roles
                </a>
            </div>
        </div>
    </div>

    <!-- Display Errors -->
    @if ($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6">
            <div class="flex">
                <i data-lucide="alert-circle" class="w-5 h-5 mr-2 mt-0.5"></i>
                <div>
                    <h4 class="font-medium">Please fix the following errors:</h4>
                    <ul class="mt-2 text-sm list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <!-- Form -->
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm">
        <form method="POST" action="{{ route('admin.roles.update', $role) }}">
            @csrf
            @method('PUT')

            <div class="p-8">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Left Column -->
                    <div class="space-y-6">
                        <!-- Role Information -->
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                <i data-lucide="shield-check" class="w-5 h-5 mr-2"></i>
                                Role Information
                            </h3>

                            <!-- Role Name -->
                            <div class="mb-4">
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                    Role Name <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="name" id="name" 
                                       value="{{ old('name', $role->name) }}" 
                                       placeholder="Enter role name" required maxlength="255"
                                       class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                                @error('name')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Display Name -->
                            <div class="mb-4">
                                <label for="display_name" class="block text-sm font-medium text-gray-700 mb-2">
                                    Display Name <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="display_name" id="display_name" 
                                       value="{{ old('display_name', $role->display_name) }}" 
                                       placeholder="Enter display name" required maxlength="255"
                                       class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                                @error('display_name')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Description -->
                            <div>
                                <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                                    Description
                                </label>
                                <textarea name="description" id="description" rows="4" 
                                          placeholder="Describe the role's purpose and responsibilities..."
                                          class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">{{ old('description', $role->description) }}</textarea>
                                @error('description')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="space-y-6">
                        <!-- Status -->
                        <div class="bg-gray-50 rounded-lg p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                <i data-lucide="settings" class="w-5 h-5 mr-2"></i>
                                Role Settings
                            </h3>

                            <!-- Status -->
                            <div class="mb-4">
                                <label for="is_active" class="flex items-center">
                                    <input type="checkbox" name="is_active" id="is_active" value="1"
                                           {{ old('is_active', $role->is_active) ? 'checked' : '' }}
                                           class="h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300 rounded">
                                    <span class="ml-2 text-sm text-gray-900">Active Role</span>
                                </label>
                                <p class="text-xs text-gray-500 mt-1">Inactive roles cannot be assigned to users</p>
                                @error('is_active')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Role Statistics -->
                        <div class="bg-blue-50 rounded-lg p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                <i data-lucide="bar-chart" class="w-5 h-5 mr-2"></i>
                                Role Statistics
                            </h3>

                            <div class="space-y-3">
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-gray-600">Users Assigned:</span>
                                    <span class="text-sm font-semibold text-gray-900">{{ $role->users_count ?? 0 }}</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-gray-600">Created:</span>
                                    <span class="text-sm font-semibold text-gray-900">{{ $role->created_at->format('M d, Y') }}</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-gray-600">Last Updated:</span>
                                    <span class="text-sm font-semibold text-gray-900">{{ $role->updated_at->format('M d, Y') }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Warning Box for System Roles -->
                        @if(in_array($role->name, ['admin', 'shopkeeper', 'user']))
                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                            <div class="flex">
                                <i data-lucide="alert-triangle" class="w-5 h-5 text-yellow-400 mr-2 mt-0.5"></i>
                                <div>
                                    <h4 class="text-sm font-medium text-yellow-800">System Role</h4>
                                    <p class="text-sm text-yellow-700 mt-1">
                                        This is a system role. Be careful when modifying its settings as it may affect application functionality.
                                    </p>
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="border-t border-gray-200 px-8 py-6 bg-gray-50 rounded-b-2xl">
                <div class="flex items-center justify-between">
                    <div class="text-sm text-gray-500">
                        <span class="text-red-500">*</span> Required fields
                    </div>
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('admin.roles.show', $role) }}" 
                           class="px-6 py-3 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2">
                            Cancel
                        </a>
                        <button type="submit"
                                class="inline-flex items-center px-6 py-3 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-purple-600 hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2">
                            <i data-lucide="save" class="w-4 h-4 mr-2"></i>
                            Update Role
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
