<!-- resources/views/admin/users/show.blade.php -->
@extends('layouts.admin')

@section('title', 'View User')
@section('breadcrumb', 'Users / View User')

@section('content')
<div class="px-4 sm:px-6 lg:px-8 py-8">
    <!-- Page Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">User Details</h1>
                <p class="mt-2 text-sm text-gray-700">View complete user information and profile</p>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('admin.users.edit', $user) }}" class="inline-flex items-center px-4 py-2 bg-purple-600 border border-transparent rounded-lg text-sm font-medium text-white hover:bg-purple-700">
                    <i data-lucide="edit" class="w-4 h-4 mr-2"></i>
                    Edit User
                </a>
                <a href="{{ route('admin.users.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-200">
                    <i data-lucide="arrow-left" class="w-4 h-4 mr-2"></i>
                    Back to List
                </a>
            </div>
        </div>
    </div>

    <!-- User Profile Card -->
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm mb-8">
        <div class="p-8">
            <div class="flex flex-col sm:flex-row items-start sm:items-center space-y-4 sm:space-y-0 sm:space-x-6">
                <!-- Profile Image -->
                <div class="flex-shrink-0">
                    @if($user->profile_image)
                        <img class="h-24 w-24 rounded-full object-cover border-4 border-white shadow-lg" 
                             src="{{ asset('storage/' . $user->profile_image) }}" 
                             alt="{{ $user->full_name }}">
                    @else
                        <div class="h-24 w-24 rounded-full bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center shadow-lg">
                            <span class="text-white font-bold text-2xl">
                                {{ strtoupper(substr($user->first_name, 0, 1) . substr($user->last_name, 0, 1)) }}
                            </span>
                        </div>
                    @endif
                </div>

                <!-- User Info -->
                <div class="flex-1">
                    <h2 class="text-2xl font-bold text-gray-900">{{ $user->full_name }}</h2>
                    <p class="text-lg text-gray-600 mb-2">{{ $user->email }}</p>
                    
                    <!-- Status & Role Badges -->
                    <div class="flex items-center space-x-3 mb-3">
                        <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full 
                            @if($user->status == 'active') bg-green-100 text-green-800
                            @elseif($user->status == 'inactive') bg-gray-100 text-gray-800
                            @else bg-red-100 text-red-800
                            @endif">
                            <i data-lucide="
                                @if($user->status == 'active') check-circle
                                @elseif($user->status == 'inactive') pause-circle
                                @else x-circle
                                @endif" class="w-4 h-4 mr-1"></i>
                            {{ ucfirst($user->status) }}
                        </span>
                        
                        @foreach($user->roles as $role)
                            <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full 
                                @if($role->name == 'admin') bg-red-100 text-red-800
                                @elseif($role->name == 'shopkeeper') bg-blue-100 text-blue-800
                                @else bg-purple-100 text-purple-800
                                @endif">
                                <i data-lucide="
                                    @if($role->name == 'admin') shield
                                    @elseif($role->name == 'shopkeeper') store
                                    @else user
                                    @endif" class="w-4 h-4 mr-1"></i>
                                {{ ucfirst($role->name) }}
                            </span>
                        @endforeach
                    </div>

                    <!-- Quick Stats -->
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                        <div>
                            <p class="text-gray-500">Phone</p>
                            <p class="font-semibold">{{ $user->phone ?? 'Not provided' }}</p>
                        </div>
                        <div>
                            <p class="text-gray-500">Email Verified</p>
                            <p class="font-semibold">
                                @if($user->email_verified_at)
                                    <span class="text-green-600">Yes</span>
                                @else
                                    <span class="text-red-600">No</span>
                                @endif
                            </p>
                        </div>
                        <div>
                            <p class="text-gray-500">Last Login</p>
                            <p class="font-semibold">{{ $user->last_login_at ? $user->last_login_at->diffForHumans() : 'Never' }}</p>
                        </div>
                        <div>
                            <p class="text-gray-500">Member Since</p>
                            <p class="font-semibold">{{ $user->created_at->format('M Y') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Detailed Information -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Personal Information -->
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                    <i data-lucide="user" class="w-5 h-5 mr-2"></i>
                    Personal Information
                </h3>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                        <span class="text-sm font-medium text-gray-600">First Name</span>
                        <span class="text-sm text-gray-900">{{ $user->first_name }}</span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                        <span class="text-sm font-medium text-gray-600">Last Name</span>
                        <span class="text-sm text-gray-900">{{ $user->last_name }}</span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                        <span class="text-sm font-medium text-gray-600">Date of Birth</span>
                        <span class="text-sm text-gray-900">{{ $user->date_of_birth ? $user->date_of_birth->format('d M, Y') : 'Not provided' }}</span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                        <span class="text-sm font-medium text-gray-600">Gender</span>
                        <span class="text-sm text-gray-900">{{ ucfirst($user->gender ?? 'Not specified') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Account Information -->
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                    <i data-lucide="settings" class="w-5 h-5 mr-2"></i>
                    Account Information
                </h3>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                        <span class="text-sm font-medium text-gray-600">User ID</span>
                        <span class="text-sm text-gray-900 font-mono">#{{ $user->id }}</span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                        <span class="text-sm font-medium text-gray-600">Account Created</span>
                        <span class="text-sm text-gray-900">{{ $user->created_at->format('d M, Y \a\t H:i') }}</span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                        <span class="text-sm font-medium text-gray-600">Last Updated</span>
                        <span class="text-sm text-gray-900">{{ $user->updated_at->format('d M, Y \a\t H:i') }}</span>
                    </div>
                    <div class="flex justify-between items-start py-2">
                        <span class="text-sm font-medium text-gray-600">Notes</span>
                        <span class="text-sm text-gray-900 text-right max-w-xs">{{ $user->notes ?? 'No notes available' }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Actions -->
    <div class="mt-8 flex justify-center space-x-4">
        <a href="{{ route('admin.users.edit', $user) }}" class="inline-flex items-center px-6 py-3 bg-purple-600 border border-transparent rounded-lg text-sm font-medium text-white hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2">
            <i data-lucide="edit" class="w-4 h-4 mr-2"></i>
            Edit User
        </a>
        @if($user->id !== auth()->id())
        <button onclick="confirmDelete()" class="inline-flex items-center px-6 py-3 bg-red-600 border border-transparent rounded-lg text-sm font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
            <i data-lucide="trash-2" class="w-4 h-4 mr-2"></i>
            Delete User
        </button>
        @endif
    </div>
</div>

@if($user->id !== auth()->id())
<form id="deleteForm" action="{{ route('admin.users.destroy', $user) }}" method="POST" class="hidden">
    @csrf
    @method('DELETE')
</form>
@endif

<script>
function confirmDelete() {
    if (confirm('Are you sure you want to delete this user? This action cannot be undone.')) {
        document.getElementById('deleteForm').submit();
    }
}
</script>
@endsection
