<!-- resources/views/shopkeeper/inventory/index.blade.php -->
@extends('layouts.shopkeeper')

@section('title', 'Inventory')
@section('breadcrumb', 'Inventory')

@section('content')
<div class="px-4 sm:px-6 lg:px-8 py-8">
    <!-- Page Header -->
    <div class="mb-8">
        <div class="sm:flex sm:items-center sm:justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Inventory Management</h1>
                <p class="mt-2 text-sm text-gray-700">Monitor and manage your product stock levels</p>
            </div>
            <div class="mt-4 sm:mt-0">
                <button class="inline-flex items-center px-4 py-2 bg-purple-600 border border-transparent rounded-lg text-sm font-medium text-white hover:bg-purple-700">
                    <i data-lucide="plus" class="w-4 h-4 mr-2"></i>
                    Add Stock
                </button>
            </div>
        </div>
    </div>

    <!-- Inventory Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-xl p-6 border border-gray-200 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Items</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">150</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                    <i data-lucide="package" class="w-6 h-6 text-blue-600"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl p-6 border border-gray-200 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">In Stock</p>
                    <p class="text-2xl font-bold text-green-600 mt-1">139</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                    <i data-lucide="check-circle" class="w-6 h-6 text-green-600"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl p-6 border border-gray-200 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Low Stock</p>
                    <p class="text-2xl font-bold text-yellow-600 mt-1">8</p>
                </div>
                <div class="w-12 h-12 bg-yellow-100 rounded-xl flex items-center justify-center">
                    <i data-lucide="alert-triangle" class="w-6 h-6 text-yellow-600"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl p-6 border border-gray-200 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Out of Stock</p>
                    <p class="text-2xl font-bold text-red-600 mt-1">3</p>
                </div>
                <div class="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center">
                    <i data-lucide="x-circle" class="w-6 h-6 text-red-600"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Inventory Table -->
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden shadow-sm">
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900">Stock Overview</h3>
                <div class="flex items-center space-x-3">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i data-lucide="search" class="w-4 h-4 text-gray-400"></i>
                        </div>
                        <input type="text" placeholder="Search inventory..." class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-purple-500 focus:border-purple-500">
                    </div>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">SKU</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Current Stock</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reorder Level</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <!-- Sample Inventory Rows -->
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="h-10 w-10 rounded-lg overflow-hidden bg-gray-100">
                                    <img src="https://images.unsplash.com/photo-1578662996442-48f60103fc96?w=100&h=100&fit=crop" alt="Product" class="h-full w-full object-cover">
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">Golden Brass Diya Set</div>
                                    <div class="text-sm text-gray-500">Puja Diyas</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm font-mono text-gray-900 bg-gray-100 px-2 py-1 rounded">PD-001</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-bold text-green-600">25 units</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">10</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                <i data-lucide="check-circle" class="w-3 h-3 mr-1"></i>
                                In Stock
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex items-center space-x-2">
                                <button class="text-purple-600 hover:text-purple-900 p-1 rounded">
                                    <i data-lucide="edit" class="w-4 h-4"></i>
                                </button>
                                <button class="text-blue-600 hover:text-blue-900 p-1 rounded">
                                    <i data-lucide="plus" class="w-4 h-4"></i>
                                </button>
                            </div>
                        </td>
                    </tr>

                    <!-- Low Stock Item -->
                    <tr class="hover:bg-gray-50 bg-yellow-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="h-10 w-10 rounded-lg overflow-hidden bg-gray-100">
                                    <img src="https://images.unsplash.com/photo-1603048719539-9ecb4aa395c3?w=100&h=100&fit=crop" alt="Product" class="h-full w-full object-cover">
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">Sandalwood Incense Sticks</div>
                                    <div class="text-sm text-gray-500">Incense & Fragrances</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm font-mono text-gray-900 bg-gray-100 px-2 py-1 rounded">IN-002</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-bold text-yellow-600">3 units</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">15</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                <i data-lucide="alert-triangle" class="w-3 h-3 mr-1"></i>
                                Low Stock
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex items-center space-x-2">
                                <button class="text-purple-600 hover:text-purple-900 p-1 rounded">
                                    <i data-lucide="edit" class="w-4 h-4"></i>
                                </button>
                                <button class="text-green-600 hover:text-green-900 p-1 rounded">
                                    <i data-lucide="shopping-cart" class="w-4 h-4"></i>
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
                    Showing <span class="font-medium">1</span> to <span class="font-medium">10</span> of <span class="font-medium">150</span> items
                </div>
                <div class="flex items-center space-x-2">
                    <button class="px-3 py-1 text-sm border border-gray-300 rounded-md hover:bg-gray-100 text-gray-600">Previous</button>
                    <button class="px-3 py-1 text-sm bg-purple-600 text-white rounded-md">1</button>
                    <button class="px-3 py-1 text-sm border border-gray-300 rounded-md hover:bg-gray-100 text-gray-600">2</button>
                    <button class="px-3 py-1 text-sm border border-gray-300 rounded-md hover:bg-gray-100 text-gray-600">Next</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
