<!-- resources/views/shopkeeper/orders/index.blade.php -->
@extends('layouts.shopkeeper')

@section('title', 'Orders')
@section('breadcrumb', 'Orders')

@section('content')
<div class="px-4 sm:px-6 lg:px-8 py-8">
    <!-- Page Header -->
    <div class="mb-8">
        <div class="sm:flex sm:items-center sm:justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Order Management</h1>
                <p class="mt-2 text-sm text-gray-700">Track and manage your customer orders</p>
            </div>
            <div class="mt-4 sm:mt-0">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i data-lucide="search" class="w-4 h-4 text-gray-400"></i>
                    </div>
                    <input type="text" placeholder="Search orders..." class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-purple-500 focus:border-purple-500">
                </div>
            </div>
        </div>
    </div>

    <!-- Order Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-xl p-6 border border-gray-200 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Orders</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">450</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                    <i data-lucide="shopping-cart" class="w-6 h-6 text-blue-600"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl p-6 border border-gray-200 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Completed</p>
                    <p class="text-2xl font-bold text-green-600 mt-1">387</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                    <i data-lucide="check-circle" class="w-6 h-6 text-green-600"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl p-6 border border-gray-200 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Processing</p>
                    <p class="text-2xl font-bold text-yellow-600 mt-1">42</p>
                </div>
                <div class="w-12 h-12 bg-yellow-100 rounded-xl flex items-center justify-center">
                    <i data-lucide="clock" class="w-6 h-6 text-yellow-600"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl p-6 border border-gray-200 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Cancelled</p>
                    <p class="text-2xl font-bold text-red-600 mt-1">21</p>
                </div>
                <div class="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center">
                    <i data-lucide="x-circle" class="w-6 h-6 text-red-600"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Orders Table -->
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden shadow-sm">
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900">Recent Orders</h3>
                <div class="flex items-center space-x-3">
                    <select class="border border-gray-300 rounded-lg px-3 py-1.5 text-sm focus:ring-purple-500 focus:border-purple-500">
                        <option>All Orders</option>
                        <option>Completed</option>
                        <option>Processing</option>
                        <option>Cancelled</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order ID</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <!-- Sample Order Rows -->
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">#12345</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center">
                                    <span class="text-purple-600 font-semibold text-sm">RK</span>
                                </div>
                                <div class="ml-3">
                                    <div class="text-sm font-medium text-gray-900">Rajesh Kumar</div>
                                    <div class="text-sm text-gray-500">rajesh@email.com</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">Sep 1, 2025</div>
                            <div class="text-sm text-gray-500">10:30 AM</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                Completed
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-semibold text-gray-900">₹5,000.00</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex items-center space-x-2">
                                <button class="text-purple-600 hover:text-purple-900 p-1 rounded">
                                    <i data-lucide="eye" class="w-4 h-4"></i>
                                </button>
                                <button class="text-blue-600 hover:text-blue-900 p-1 rounded">
                                    <i data-lucide="edit" class="w-4 h-4"></i>
                                </button>
                                <button class="text-green-600 hover:text-green-900 p-1 rounded">
                                    <i data-lucide="printer" class="w-4 h-4"></i>
                                </button>
                            </div>
                        </td>
                    </tr>

                    <!-- Processing Order -->
                    <tr class="hover:bg-gray-50 bg-yellow-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">#12346</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center">
                                    <span class="text-purple-600 font-semibold text-sm">PS</span>
                                </div>
                                <div class="ml-3">
                                    <div class="text-sm font-medium text-gray-900">Priya Sharma</div>
                                    <div class="text-sm text-gray-500">priya@email.com</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm text-gray-900">Sep 2, 2025</div>
                            <div class="text-sm text-gray-500">2:15 PM</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                Processing
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-semibold text-gray-900">₹2,150.00</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex items-center space-x-2">
                                <button class="text-purple-600 hover:text-purple-900 p-1 rounded">
                                    <i data-lucide="eye" class="w-4 h-4"></i>
                                </button>
                                <button class="text-green-600 hover:text-green-900 p-1 rounded">
                                    <i data-lucide="check" class="w-4 h-4"></i>
                                </button>
                                <button class="text-blue-600 hover:text-blue-900 p-1 rounded">
                                    <i data-lucide="truck" class="w-4 h-4"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="px-6 py-3 border-t border-gray-200 bg-gray-50">
            <div class="flex items-center justify-between">
                <div class="text-sm text-gray-700">
                    Showing <span class="font-medium">1</span> to <span class="font-medium">10</span> of <span class="font-medium">450</span> orders
                </div>
                <div class="flex items-center space-x-2">
                    <button class="px-3 py-1 text-sm border border-gray-300 rounded-md hover:bg-gray-100 text-gray-600">Previous</button>
                    <button class="px-3 py-1 text-sm bg-purple-600 text-white rounded-md">1</button>
                    <button class="px-3 py-1 text-sm border border-gray-300 rounded-md hover:bg-gray-100 text-gray-600">2</button>
                    <button class="px-3 py-1 text-sm border border-gray-300 rounded-md hover:bg-gray-100 text-gray-600">3</button>
                    <button class="px-3 py-1 text-sm border border-gray-300 rounded-md hover:bg-gray-100 text-gray-600">Next</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
