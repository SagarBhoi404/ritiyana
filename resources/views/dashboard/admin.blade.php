<!-- resources/views/dashboard/admin.blade.php -->
@extends('layouts.admin')

@section('title', 'Admin Dashboard')
@section('breadcrumb', 'Dashboard')

@section('content')
<div class="px-4 sm:px-6 lg:px-8 py-8">
    <!-- Welcome Section -->
    <div class="bg-gradient-to-br from-purple-600 via-purple-700 to-pink-600 rounded-2xl p-8 mb-8 text-white relative overflow-hidden">
        <!-- Content as before, but update calendar icon -->
        <div class="relative">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h1 class="text-3xl font-bold mb-2">Welcome back, {{ $user->first_name }}! 🙏</h1>
                    <p class="text-purple-100 text-lg">Manage your Ritiyana puja store with ease</p>
                    <div class="flex items-center mt-4 text-purple-100">
                        <i data-lucide="calendar" class="h-4 w-4 mr-2"></i>
                        <span>{{ now()->format('l, F j, Y') }}</span>
                    </div>
                </div>
                <div class="mt-6 sm:mt-0">
                    <div class="flex items-center space-x-4">
                        <div class="text-center">
                            <div class="text-2xl font-bold" id="currentHour">{{ now()->format('H') }}</div>
                            <div class="text-sm text-purple-200">Hours</div>
                        </div>
                        <div class="text-purple-300">:</div>
                        <div class="text-center">
                            <div class="text-2xl font-bold" id="currentMinute">{{ now()->format('i') }}</div>
                            <div class="text-sm text-purple-200">Minutes</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Users -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-lg transition-all duration-300 group">
            <div class="flex items-center">
                <div class="flex-1">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                            <i data-lucide="users" class="h-6 w-6 text-blue-600"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Total Users</p>
                            <p class="text-2xl font-bold text-gray-900">2,543</p>
                        </div>
                    </div>
                    <div class="mt-4 flex items-center">
                        <div class="flex items-center text-green-600 text-sm font-medium">
                            <i data-lucide="trending-up" class="h-4 w-4 mr-1"></i>
                            +12%
                        </div>
                        <span class="text-gray-500 text-sm ml-2">vs last month</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Products -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-lg transition-all duration-300 group">
            <div class="flex items-center">
                <div class="flex-1">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                            <i data-lucide="package" class="h-6 w-6 text-green-600"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Products</p>
                            <p class="text-2xl font-bold text-gray-900">1,247</p>
                        </div>
                    </div>
                    <div class="mt-4 flex items-center">
                        <div class="flex items-center text-green-600 text-sm font-medium">
                            <i data-lucide="trending-up" class="h-4 w-4 mr-1"></i>
                            +8%
                        </div>
                        <span class="text-gray-500 text-sm ml-2">vs last month</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Orders -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-lg transition-all duration-300 group">
            <div class="flex items-center">
                <div class="flex-1">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                            <i data-lucide="shopping-cart" class="h-6 w-6 text-purple-600"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Orders</p>
                            <p class="text-2xl font-bold text-gray-900">892</p>
                        </div>
                    </div>
                    <div class="mt-4 flex items-center">
                        <div class="flex items-center text-red-600 text-sm font-medium">
                            <i data-lucide="trending-down" class="h-4 w-4 mr-1"></i>
                            -3%
                        </div>
                        <span class="text-gray-500 text-sm ml-2">vs last month</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Revenue -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-lg transition-all duration-300 group">
            <div class="flex items-center">
                <div class="flex-1">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-orange-100 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                            <i data-lucide="indian-rupee" class="h-6 w-6 text-orange-600"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Revenue</p>
                            <p class="text-2xl font-bold text-gray-900">₹2,43,890</p>
                        </div>
                    </div>
                    <div class="mt-4 flex items-center">
                        <div class="flex items-center text-green-600 text-sm font-medium">
                            <i data-lucide="trending-up" class="h-4 w-4 mr-1"></i>
                            +15%
                        </div>
                        <span class="text-gray-500 text-sm ml-2">vs last month</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Top Categories with Lucide Icons -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <!-- Sales Chart -->
        <div class="lg:col-span-2 bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Sales Analytics</h3>
                    <p class="text-sm text-gray-500">Weekly revenue overview</p>
                </div>
                <select class="text-sm border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                    <option>Last 7 days</option>
                    <option>Last 30 days</option>
                    <option>Last 3 months</option>
                </select>
            </div>
            <div id="salesChart" class="w-full"></div>
        </div>

        <!-- Top Categories -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
            <h3 class="text-lg font-semibold text-gray-900 mb-6">Top Categories</h3>
            <div class="space-y-4">
                <div class="flex items-center justify-between p-4 bg-purple-50 rounded-xl hover:bg-purple-100 transition-colors">
                    <div class="flex items-center space-x-3">
                        <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                            <span class="text-2xl">🪔</span>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900">Puja Diyas</p>
                            <p class="text-sm text-gray-500">245 products</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="font-semibold text-gray-900">₹45,230</p>
                        <p class="text-xs text-green-600 flex items-center">
                            <i data-lucide="arrow-up" class="h-3 w-3 mr-1"></i>
                            +12%
                        </p>
                    </div>
                </div>
                
                <div class="flex items-center justify-between p-4 bg-green-50 rounded-xl hover:bg-green-100 transition-colors">
                    <div class="flex items-center space-x-3">
                        <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                            <span class="text-2xl">🌺</span>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900">Flowers</p>
                            <p class="text-sm text-gray-500">189 products</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="font-semibold text-gray-900">₹32,450</p>
                        <p class="text-xs text-green-600 flex items-center">
                            <i data-lucide="arrow-up" class="h-3 w-3 mr-1"></i>
                            +8%
                        </p>
                    </div>
                </div>
                
                <div class="flex items-center justify-between p-4 bg-orange-50 rounded-xl hover:bg-orange-100 transition-colors">
                    <div class="flex items-center space-x-3">
                        <div class="w-12 h-12 bg-orange-100 rounded-xl flex items-center justify-center">
                            <span class="text-2xl">🕉️</span>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900">Incense</p>
                            <p class="text-sm text-gray-500">156 products</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="font-semibold text-gray-900">₹28,670</p>
                        <p class="text-xs text-red-600 flex items-center">
                            <i data-lucide="arrow-down" class="h-3 w-3 mr-1"></i>
                            -2%
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Orders and Quick Actions -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Recent Orders -->
        <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100">
            <div class="p-6 border-b border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Recent Orders</h3>
                        <p class="text-sm text-gray-500">Latest customer orders</p>
                    </div>
                    <a href="#" class="text-purple-600 hover:text-purple-700 text-sm font-medium flex items-center group">
                        View all
                        <i data-lucide="arrow-right" class="ml-1 h-4 w-4 group-hover:translate-x-1 transition-transform"></i>
                    </a>
                </div>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors cursor-pointer">
                        <div class="flex items-center space-x-4">
                            <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                                <span class="text-blue-600 font-semibold text-sm">#1847</span>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900">Ganesh Puja Kit</p>
                                <p class="text-sm text-gray-500 flex items-center">
                                    <i data-lucide="user" class="h-3 w-3 mr-1"></i>
                                    Rajesh Kumar • 2 hours ago
                                </p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="font-semibold text-gray-900">₹1,250</p>
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                Completed
                            </span>
                        </div>
                    </div>
                    
                    <!-- More order items here... -->
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
            <h3 class="text-lg font-semibold text-gray-900 mb-6">Quick Actions</h3>
            <div class="space-y-3">
                <a href="#" class="flex items-center p-4 rounded-xl hover:bg-blue-50 transition-colors group">
                    <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center mr-4 group-hover:scale-110 transition-transform">
                        <i data-lucide="user-plus" class="h-5 w-5 text-blue-600"></i>
                    </div>
                    <div>
                        <p class="font-medium text-gray-900">Add User</p>
                        <p class="text-sm text-gray-500">Create new account</p>
                    </div>
                    <i data-lucide="chevron-right" class="ml-auto h-4 w-4 text-gray-400 group-hover:text-gray-600"></i>
                </a>
                
                <a href="#" class="flex items-center p-4 rounded-xl hover:bg-green-50 transition-colors group">
                    <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center mr-4 group-hover:scale-110 transition-transform">
                        <i data-lucide="package-plus" class="h-5 w-5 text-green-600"></i>
                    </div>
                    <div>
                        <p class="font-medium text-gray-900">Add Product</p>
                        <p class="text-sm text-gray-500">New puja item</p>
                    </div>
                    <i data-lucide="chevron-right" class="ml-auto h-4 w-4 text-gray-400 group-hover:text-gray-600"></i>
                </a>
                
                <a href="#" class="flex items-center p-4 rounded-xl hover:bg-purple-50 transition-colors group">
                    <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center mr-4 group-hover:scale-110 transition-transform">
                        <i data-lucide="gift" class="h-5 w-5 text-purple-600"></i>
                    </div>
                    <div>
                        <p class="font-medium text-gray-900">Create Kit</p>
                        <p class="text-sm text-gray-500">Puja kit bundle</p>
                    </div>
                    <i data-lucide="chevron-right" class="ml-auto h-4 w-4 text-gray-400 group-hover:text-gray-600"></i>
                </a>
                
                <a href="#" class="flex items-center p-4 rounded-xl hover:bg-orange-50 transition-colors group">
                    <div class="w-12 h-12 bg-orange-100 rounded-xl flex items-center justify-center mr-4 group-hover:scale-110 transition-transform">
                        <i data-lucide="settings" class="h-5 w-5 text-orange-600"></i>
                    </div>
                    <div>
                        <p class="font-medium text-gray-900">Settings</p>
                        <p class="text-sm text-gray-500">Store configuration</p>
                    </div>
                    <i data-lucide="chevron-right" class="ml-auto h-4 w-4 text-gray-400 group-hover:text-gray-600"></i>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
