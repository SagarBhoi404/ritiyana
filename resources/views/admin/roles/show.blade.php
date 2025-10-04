@extends('layouts.admin')

@section('title', 'View Role')
@section('breadcrumb', 'Roles / ' . $role->display_name)

@section('content')
<div class="px-4 sm:px-6 lg:px-8 py-8">
    <!-- Page Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">{{ $role->display_name }}</h1>
                <p class="mt-2 text-sm text-gray-700">Role details and user assignments</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('admin.roles.edit', $role) }}" 
                   class="inline-flex items-center px-4 py-2 bg-purple-100 border border-purple-300 rounded-lg text-sm font-medium text-purple-700 hover:bg-purple-200">
                    <i data-lucide="edit" class="w-4 h-4 mr-2"></i>
                    Edit Role
                </a>
                <a href="{{ route('admin.roles.index') }}" 
                   class="inline-flex items-center px-4 py-2 bg-gray-100 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-200">
                    <i data-lucide="arrow-left" class="w-4 h-4 mr-2"></i>
                    Back to Roles
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Role Information -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Basic Information -->
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <i data-lucide="shield-check" class="w-5 h-5 mr-2"></i>
                    Role Information
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Role Name</label>
                        <p class="text-lg font-semibold text-gray-900">{{ $role->name }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Display Name</label>
                        <p class="text-lg font-semibold text-gray-900">{{ $role->display_name }}</p>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-500 mb-1">Description</label>
                        <p class="text-gray-900">{{ $role->description ?? 'No description provided.' }}</p>
                    </div>
                </div>
            </div>

            <!-- Assigned Users -->
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <i data-lucide="users" class="w-5 h-5 mr-2"></i>
                        Assigned Users ({{ $role->users->count() }})
                    </h3>
                    @if($role->users->count() > 0)
                        <span class="text-sm text-gray-500">Total: {{ $role->users->count() }} users</span>
                    @endif
                </div>

                @if($role->users->count() > 0)
                    <div class="overflow-hidden">
                        <div class="space-y-3">
                            @foreach($role->users->take(10) as $user)
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                    <div class="flex items-center">
                                        @if($user->profile_image)
                                            <img src="{{ asset('storage/' . $user->profile_image) }}" 
                                                 alt="{{ $user->first_name }}" 
                                                 class="w-10 h-10 rounded-full object-cover mr-3">
                                        @else
                                            <div class="w-10 h-10 rounded-full bg-gray-300 flex items-center justify-center mr-3">
                                                <i data-lucide="user" class="w-5 h-5 text-gray-600"></i>
                                            </div>
                                        @endif
                                        <div>
                                            <p class="text-sm font-medium text-gray-900">
                                                {{ $user->first_name }} {{ $user->last_name }}
                                            </p>
                                            <p class="text-xs text-gray-500">{{ $user->email }}</p>
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-2">
                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                            @if($user->status === 'active') bg-green-100 text-green-800
                                            @elseif($user->status === 'inactive') bg-gray-100 text-gray-800
                                            @else bg-red-100 text-red-800 @endif">
                                            {{ ucfirst($user->status) }}
                                        </span>
                                        <a href="{{ route('admin.users.show', $user) }}" 
                                           class="text-purple-600 hover:text-purple-900">
                                            <i data-lucide="external-link" class="w-4 h-4"></i>
                                        </a>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        @if($role->users->count() > 10)
                            <div class="mt-4 text-center">
                                <a href="{{ route('admin.users.index', ['role' => $role->name]) }}" 
                                   class="text-purple-600 hover:text-purple-900 text-sm font-medium">
                                    View all {{ $role->users->count() }} users →
                                </a>
                            </div>
                        @endif
                    </div>
                @else
                    <div class="text-center py-8">
                        <i data-lucide="users" class="w-12 h-12 text-gray-400 mx-auto mb-4"></i>
                        <p class="text-gray-500">No users assigned to this role yet.</p>
                        <a href="{{ route('admin.users.create') }}" 
                           class="mt-2 text-purple-600 hover:text-purple-900 text-sm font-medium">
                            Create New User
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Status Card -->
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <i data-lucide="activity" class="w-5 h-5 mr-2"></i>
                    Status
                </h3>
                
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Current Status:</span>
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                            {{ $role->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $role->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm text-gray-600">Users Count:</span>
                        <span class="text-sm font-semibold text-gray-900">{{ $role->users->count() }}</span>
                    </div>
                </div>
            </div>

            <!-- Timestamps -->
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <i data-lucide="clock" class="w-5 h-5 mr-2"></i>
                    Timestamps
                </h3>
                
                <div class="space-y-3">
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Created</label>
                        <p class="text-sm text-gray-900">{{ $role->created_at->format('M d, Y \a\t h:i A') }}</p>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Last Updated</label>
                        <p class="text-sm text-gray-900">{{ $role->updated_at->format('M d, Y \a\t h:i A') }}</p>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <i data-lucide="settings" class="w-5 h-5 mr-2"></i>
                    Quick Actions
                </h3>
                
                <div class="space-y-3">
                    <button type="button" onclick="toggleRoleStatus({{ $role->id }})"
                            class="w-full inline-flex items-center justify-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50">
                        <i data-lucide="{{ $role->is_active ? 'eye-off' : 'eye' }}" class="w-4 h-4 mr-2"></i>
                        {{ $role->is_active ? 'Deactivate' : 'Activate' }} Role
                    </button>
                    
                    @if($role->users->count() === 0)
                        <button type="button" onclick="confirmDelete({{ $role->id }})"
                                class="w-full inline-flex items-center justify-center px-4 py-2 border border-red-300 rounded-lg text-sm font-medium text-red-700 hover:bg-red-50">
                            <i data-lucide="trash-2" class="w-4 h-4 mr-2"></i>
                            Delete Role
                        </button>
                    @else
                        <div class="text-xs text-gray-500 p-2 bg-gray-50 rounded">
                            Cannot delete role with assigned users
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function toggleRoleStatus(roleId) {
    if (confirm('Are you sure you want to change this role\'s status?')) {
        fetch(`/admin/roles/${roleId}/toggle-status`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json',
                'Content-Type': 'application/json',
            },
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Error updating role status');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error updating role status');
        });
    }
}

function confirmDelete(roleId) {
    if (confirm('Are you sure you want to delete this role? This action cannot be undone.')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/admin/roles/${roleId}`;
        
        const methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        methodInput.value = 'DELETE';
        
        const tokenInput = document.createElement('input');
        tokenInput.type = 'hidden';
        tokenInput.name = '_token';
        tokenInput.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        form.appendChild(methodInput);
        form.appendChild(tokenInput);
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
@endsection
