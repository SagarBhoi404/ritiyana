@extends('layouts.app')

@section('title', 'My Profile - Shree Samagri')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header with Breadcrumbs -->
    {{-- <div class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Breadcrumb -->
            <nav class="flex py-3 text-sm text-gray-500">
                <a href="{{ route('dashboard') }}" class="hover:text-vibrant-pink transition-colors">Dashboard</a>
                <i data-lucide="chevron-right" class="w-4 h-4 mx-2"></i>
                <span class="text-gray-900">My Profile</span>
            </nav>
            
            <div class="flex justify-between items-center py-6">
                <div class="flex items-center space-x-4">
                    <!-- Profile Avatar -->
                    <div class="w-16 h-16 bg-gradient-to-r from-vibrant-pink to-pink-600 rounded-full flex items-center justify-center text-white text-xl font-bold">
                        {{ strtoupper(substr($user->first_name, 0, 1) . substr($user->last_name, 0, 1)) }}
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">{{ $user->first_name }} {{ $user->last_name }}</h1>
                        <p class="text-gray-600 flex items-center">
                            <i data-lucide="mail" class="w-4 h-4 mr-2"></i>
                            {{ $user->email }}
                        </p>
                    </div>
                </div>
                <div class="flex items-center space-x-3">
                    <!-- Quick Actions -->
                    <a href="{{ route('profile.edit') }}" 
                       class="inline-flex items-center px-4 py-2 bg-vibrant-pink hover:bg-vibrant-pink-dark text-white rounded-lg transition-colors">
                        <i data-lucide="edit-2" class="w-4 h-4 mr-2"></i>
                        Edit Profile
                    </a>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg transition-colors">
                            <i data-lucide="log-out" class="w-4 h-4 mr-2"></i>
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div> --}}

    <!-- Main Content -->
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Customer Navigation Component -->
        <x-customer-navigation />

        <!-- Flash Messages -->
        @if(session('success'))
            <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6 flex items-center animate-fade-in">
                <div class="flex">
                    <i data-lucide="check-circle" class="w-5 h-5 text-green-400 mr-3 mt-0.5"></i>
                    <div>
                        <h4 class="text-green-800 font-medium">Success!</h4>
                        <p class="text-green-700 text-sm">{{ session('success') }}</p>
                    </div>
                </div>
                <button onclick="this.parentElement.style.display='none'" class="ml-auto text-green-400 hover:text-green-600">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6 flex items-center animate-fade-in">
                <div class="flex">
                    <i data-lucide="x-circle" class="w-5 h-5 text-red-400 mr-3 mt-0.5"></i>
                    <div>
                        <h4 class="text-red-800 font-medium">Error!</h4>
                        <p class="text-red-700 text-sm">{{ session('error') }}</p>
                    </div>
                </div>
                <button onclick="this.parentElement.style.display='none'" class="ml-auto text-red-400 hover:text-red-600">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>
        @endif
        <!-- Profile Cards Grid -->
        <div class="grid gap-6 lg:grid-cols-3">
            <!-- Personal Information Card -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                            <i data-lucide="user" class="w-5 h-5 mr-2 text-vibrant-pink"></i>
                            Personal Information
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- First Name -->
                            <div class="group">
                                <div class="flex items-start space-x-3 p-4 rounded-lg border border-gray-100 group-hover:border-gray-200 group-hover:bg-gray-50 transition-all">
                                    <div class="flex-shrink-0">
                                        <i data-lucide="user" class="w-5 h-5 text-vibrant-pink mt-0.5"></i>
                                    </div>
                                    <div class="flex-grow min-w-0">
                                        <p class="text-sm font-medium text-gray-500 mb-1">First Name</p>
                                        <p class="text-base font-semibold text-gray-900 break-words">
                                            {{ $user->first_name ?: 'Not provided' }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Last Name -->
                            <div class="group">
                                <div class="flex items-start space-x-3 p-4 rounded-lg border border-gray-100 group-hover:border-gray-200 group-hover:bg-gray-50 transition-all">
                                    <div class="flex-shrink-0">
                                        <i data-lucide="user" class="w-5 h-5 text-vibrant-pink mt-0.5"></i>
                                    </div>
                                    <div class="flex-grow min-w-0">
                                        <p class="text-sm font-medium text-gray-500 mb-1">Last Name</p>
                                        <p class="text-base font-semibold text-gray-900 break-words">
                                            {{ $user->last_name ?: 'Not provided' }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Email -->
                            <div class="group">
                                <div class="flex items-start space-x-3 p-4 rounded-lg border border-gray-100 group-hover:border-gray-200 group-hover:bg-gray-50 transition-all">
                                    <div class="flex-shrink-0">
                                        <i data-lucide="mail" class="w-5 h-5 text-vibrant-pink mt-0.5"></i>
                                    </div>
                                    <div class="flex-grow min-w-0">
                                        <p class="text-sm font-medium text-gray-500 mb-1">Email</p>
                                        <p class="text-base font-semibold text-gray-900 break-words">
                                            {{ $user->email ?: 'Not provided' }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Phone -->
                            <div class="group">
                                <div class="flex items-start space-x-3 p-4 rounded-lg border border-gray-100 group-hover:border-gray-200 group-hover:bg-gray-50 transition-all">
                                    <div class="flex-shrink-0">
                                        <i data-lucide="phone" class="w-5 h-5 text-vibrant-pink mt-0.5"></i>
                                    </div>
                                    <div class="flex-grow min-w-0">
                                        <p class="text-sm font-medium text-gray-500 mb-1">Phone</p>
                                        <p class="text-base font-semibold text-gray-900 break-words">
                                            {{ $user->phone ?: 'Not provided' }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Gender -->
                            <div class="group">
                                <div class="flex items-start space-x-3 p-4 rounded-lg border border-gray-100 group-hover:border-gray-200 group-hover:bg-gray-50 transition-all">
                                    <div class="flex-shrink-0">
                                        <i data-lucide="users" class="w-5 h-5 text-vibrant-pink mt-0.5"></i>
                                    </div>
                                    <div class="flex-grow min-w-0">
                                        <p class="text-sm font-medium text-gray-500 mb-1">Gender</p>
                                        <p class="text-base font-semibold text-gray-900 break-words">
                                            {{ $user->gender ? ucfirst($user->gender) : 'Not specified' }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Date of Birth -->
                            <div class="group">
                                <div class="flex items-start space-x-3 p-4 rounded-lg border border-gray-100 group-hover:border-gray-200 group-hover:bg-gray-50 transition-all">
                                    <div class="flex-shrink-0">
                                        <i data-lucide="calendar" class="w-5 h-5 text-vibrant-pink mt-0.5"></i>
                                    </div>
                                    <div class="flex-grow min-w-0">
                                        <p class="text-sm font-medium text-gray-500 mb-1">Date of Birth</p>
                                        <p class="text-base font-semibold text-gray-900 break-words">
                                            {{ $user->date_of_birth ? $user->date_of_birth->format('M d, Y') : 'Not provided' }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Stats & Actions Sidebar -->
            <div class="space-y-6">
                <!-- Account Status Card -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h4 class="font-semibold text-gray-900 mb-4 flex items-center">
                        <i data-lucide="shield-check" class="w-5 h-5 mr-2 text-green-500"></i>
                        Account Status
                    </h4>
                    <div class="space-y-3">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Status</span>
                            <span class="px-2 py-1 rounded-full text-xs font-medium text-white {{ $user->status === 'active' ? 'bg-green-500' : 'bg-gray-500' }}">
                                {{ ucfirst($user->status) }}
                            </span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-gray-600">Member Since</span>
                            <span class="text-sm font-medium text-gray-900">{{ $user->created_at->format('M Y') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions Card -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                    <h4 class="font-semibold text-gray-900 mb-4 flex items-center">
                        <i data-lucide="zap" class="w-5 h-5 mr-2 text-vibrant-pink"></i>
                        Quick Actions
                    </h4>
                    <div class="space-y-3">
                        <a href="{{ route('profile.edit') }}" 
                           class="flex items-center p-3 rounded-lg border border-gray-200 hover:border-vibrant-pink hover:bg-pink-50 transition-all group">
                        <i data-lucide="edit-2" class="w-4 h-4 mr-2"></i>
                        Edit Profile
                    </a>
                        <a href="{{ route('addresses.index') }}" 
                           class="flex items-center p-3 rounded-lg border border-gray-200 hover:border-vibrant-pink hover:bg-pink-50 transition-all group">
                            <i data-lucide="map-pin" class="w-4 h-4 mr-3 text-gray-500 group-hover:text-vibrant-pink"></i>
                            <span class="text-sm font-medium text-gray-700 group-hover:text-vibrant-pink">Manage Addresses</span>
                        </a>
                        <a href="{{ route('orders.index') }}" 
                           class="flex items-center p-3 rounded-lg border border-gray-200 hover:border-vibrant-pink hover:bg-pink-50 transition-all group">
                            <i data-lucide="package" class="w-4 h-4 mr-3 text-gray-500 group-hover:text-vibrant-pink"></i>
                            <span class="text-sm font-medium text-gray-700 group-hover:text-vibrant-pink">Order History</span>
                        </a>
                        {{-- <a href="{{ route('wishlist.index') }}"  --}}
                        {{-- <a href=""
                           class="flex items-center p-3 rounded-lg border border-gray-200 hover:border-vibrant-pink hover:bg-pink-50 transition-all group">
                            <i data-lucide="heart" class="w-4 h-4 mr-3 text-gray-500 group-hover:text-vibrant-pink"></i>
                            <span class="text-sm font-medium text-gray-700 group-hover:text-vibrant-pink">Wishlist</span>
                        </a> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
