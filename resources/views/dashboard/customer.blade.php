<!-- resources/views/dashboard/customer.blade.php -->
@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Welcome back, {{ $user->first_name }}!</h1>
                    <p class="text-gray-600">Explore our puja samgri collection</p>
                </div>
                <div class="flex items-center space-x-4">
                    <span class="text-sm text-gray-500">{{ $user->email }}</span>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg text-sm transition-colors">
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
       <!-- Customer Navigation Component -->
        <x-customer-navigation />

        <!-- Quick Stats -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-2 bg-blue-100 rounded-lg">
                        <i data-lucide="shopping-bag" class="w-6 h-6 text-blue-600"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Total Orders</p>
                        <p class="text-2xl font-semibold text-gray-900">0</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-2 bg-green-100 rounded-lg">
                        <i data-lucide="heart" class="w-6 h-6 text-green-600"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Wishlist Items</p>
                        <p class="text-2xl font-semibold text-gray-900">0</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-2 bg-purple-100 rounded-lg">
                        <i data-lucide="gift" class="w-6 h-6 text-purple-600"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Puja Kits</p>
                        <p class="text-2xl font-semibold text-gray-900">View All</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center">
                    <div class="p-2 bg-orange-100 rounded-lg">
                        <i data-lucide="user" class="w-6 h-6 text-orange-600"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Profile</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ ucfirst($user->status) }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold mb-4">🛍️ Shop Puja Samgri</h3>
                <p class="text-gray-600 mb-4">Browse our collection of authentic puja items</p>
                <a href="#" class="bg-vibrant-pink hover:bg-vibrant-pink-dark text-white px-4 py-2 rounded-lg transition-colors">
                    Shop Now
                </a>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold mb-4">🎁 Puja Kits</h3>
                <p class="text-gray-600 mb-4">Complete puja packages for different occasions</p>
                <a href="#" class="bg-vibrant-pink hover:bg-vibrant-pink-dark text-white px-4 py-2 rounded-lg transition-colors">
                    View Kits
                </a>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold mb-4">👨‍🦲 Book Pandit</h3>
                <p class="text-gray-600 mb-4">Book experienced pandits for your puja</p>
                <a href="#" class="bg-vibrant-pink hover:bg-vibrant-pink-dark text-white px-4 py-2 rounded-lg transition-colors">
                    Book Now
                </a>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold">Recent Activity</h3>
            </div>
            <div class="p-6">
                <div class="text-center py-8">
                    <i data-lucide="package" class="w-12 h-12 text-gray-400 mx-auto mb-4"></i>
                    <p class="text-gray-500">No recent activity</p>
                    <p class="text-sm text-gray-400">Start shopping to see your order history here</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
