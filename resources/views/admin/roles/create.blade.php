@extends('layouts.admin')

@section('title', 'Add Role')
@section('breadcrumb', 'Roles / Add Role')

@section('content')
<div class="px-4 sm:px-6 lg:px-8 py-8">
    <!-- Page Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Add New Role</h1>
                <p class="mt-2 text-sm text-gray-700">Create a new role for your Shree Samagri platform</p>
            </div>
            <a href="{{ route('admin.roles.index') }}" 
               class="inline-flex items-center px-4 py-2 bg-gray-100 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-200">
                <i data-lucide="arrow-left" class="w-4 h-4 mr-2"></i>
                Back to Roles
            </a>
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
        <form method="POST" action="{{ route('admin.roles.store') }}">
            @csrf

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
                                       value="{{ old('name') }}" 
                                       placeholder="e.g., moderator, editor, viewer" required maxlength="255"
                                       class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                                <p class="text-xs text-gray-500 mt-1">Unique identifier for the role (lowercase, no spaces)</p>
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
                                       value="{{ old('display_name') }}" 
                                       placeholder="e.g., Moderator, Content Editor, Viewer" required maxlength="255"
                                       class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                                <p class="text-xs text-gray-500 mt-1">Human-readable name shown in the interface</p>
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
                                          placeholder="Describe the role's purpose, responsibilities, and permissions..."
                                          class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">{{ old('description') }}</textarea>
                                <p class="text-xs text-gray-500 mt-1">Optional description of the role's purpose</p>
                                @error('description')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="space-y-6">
                        <!-- Role Settings -->
                        <div class="bg-gray-50 rounded-lg p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                <i data-lucide="settings" class="w-5 h-5 mr-2"></i>
                                Role Settings
                            </h3>

                            <!-- Status -->
                            <div class="mb-4">
                                <label for="is_active" class="flex items-center cursor-pointer">
                                    <input type="checkbox" name="is_active" id="is_active" value="1"
                                           {{ old('is_active', true) ? 'checked' : '' }}
                                           class="h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300 rounded">
                                    <span class="ml-2 text-sm text-gray-900">Active Role</span>
                                </label>
                                <p class="text-xs text-gray-500 mt-1">Inactive roles cannot be assigned to users</p>
                                @error('is_active')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Role Guidelines -->
                        <div class="bg-blue-50 rounded-lg p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                <i data-lucide="info" class="w-5 h-5 mr-2"></i>
                                Role Guidelines
                            </h3>

                            <div class="space-y-3 text-sm text-gray-700">
                                <div class="flex items-start">
                                    <i data-lucide="check-circle" class="w-4 h-4 text-green-500 mr-2 mt-0.5 flex-shrink-0"></i>
                                    <p>Use descriptive names that clearly indicate the role's purpose</p>
                                </div>
                                <div class="flex items-start">
                                    <i data-lucide="check-circle" class="w-4 h-4 text-green-500 mr-2 mt-0.5 flex-shrink-0"></i>
                                    <p>Role names should be unique and lowercase</p>
                                </div>
                                <div class="flex items-start">
                                    <i data-lucide="check-circle" class="w-4 h-4 text-green-500 mr-2 mt-0.5 flex-shrink-0"></i>
                                    <p>Display names can contain spaces and mixed case</p>
                                </div>
                                <div class="flex items-start">
                                    <i data-lucide="check-circle" class="w-4 h-4 text-green-500 mr-2 mt-0.5 flex-shrink-0"></i>
                                    <p>Provide clear descriptions for better role management</p>
                                </div>
                            </div>
                        </div>

                        <!-- Existing Roles Preview -->
                        <div class="bg-yellow-50 rounded-lg p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                <i data-lucide="eye" class="w-5 h-5 mr-2"></i>
                                Current System Roles
                            </h3>

                            <div class="space-y-2">
                                <div class="flex items-center justify-between p-2 bg-white rounded text-xs">
                                    <span class="font-medium">admin</span>
                                    <span class="text-gray-500">System Administrator</span>
                                </div>
                                <div class="flex items-center justify-between p-2 bg-white rounded text-xs">
                                    <span class="font-medium">shopkeeper</span>
                                    <span class="text-gray-500">Store Manager</span>
                                </div>
                                <div class="flex items-center justify-between p-2 bg-white rounded text-xs">
                                    <span class="font-medium">user</span>
                                    <span class="text-gray-500">Regular Customer</span>
                                </div>
                            </div>
                            <p class="text-xs text-gray-600 mt-3">Ensure your new role doesn't conflict with existing ones</p>
                        </div>
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
                        <a href="{{ route('admin.roles.index') }}" 
                           class="px-6 py-3 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2">
                            Cancel
                        </a>
                        <button type="submit"
                                class="inline-flex items-center px-6 py-3 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-purple-600 hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2">
                            <i data-lucide="plus" class="w-4 h-4 mr-2"></i>
                            Create Role
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-generate role name from display name
    const displayNameInput = document.getElementById('display_name');
    const nameInput = document.getElementById('name');

    displayNameInput.addEventListener('input', function() {
        if (!nameInput.dataset.manuallyEdited) {
            const slug = this.value
                .toLowerCase()
                .replace(/[^a-z0-9\s-]/g, '') // Remove special characters
                .replace(/\s+/g, '_') // Replace spaces with underscores
                .replace(/-+/g, '_') // Replace hyphens with underscores
                .replace(/_{2,}/g, '_') // Replace multiple underscores with single
                .replace(/^_|_$/g, ''); // Remove leading/trailing underscores
            
            nameInput.value = slug;
        }
    });

    // Mark name as manually edited if user types in it
    nameInput.addEventListener('input', function() {
        nameInput.dataset.manuallyEdited = 'true';
    });

    // Form validation
    const form = document.querySelector('form');
    form.addEventListener('submit', function(e) {
        const name = nameInput.value.trim();
        const displayName = displayNameInput.value.trim();

        if (!name || !displayName) {
            e.preventDefault();
            alert('Please fill in all required fields.');
            return false;
        }

        // Check if role name contains only allowed characters
        if (!/^[a-z0-9_]+$/.test(name)) {
            e.preventDefault();
            alert('Role name can only contain lowercase letters, numbers, and underscores.');
            nameInput.focus();
            return false;
        }
    });
});
</script>
@endsection
