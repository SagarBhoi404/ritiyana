<!-- resources/views/shopkeeper/products/index.blade.php -->
@extends('layouts.shopkeeper')

@section('title', 'Products')
@section('breadcrumb', 'Products')

@section('content')
    <div class="px-4 sm:px-6 lg:px-8 py-8">
        <!-- Page Header -->
        <div class="mb-8">
            <div class="sm:flex sm:items-center sm:justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Your Products</h1>
                    <p class="mt-2 text-sm text-gray-700">Manage your product inventory and listings</p>
                </div>
                <div class="mt-4 sm:mt-0 sm:flex sm:items-center sm:space-x-3">
                    <button
                        class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50">
                        <i data-lucide="filter" class="w-4 h-4 mr-2"></i>
                        Filter
                    </button>
                    <a href="#"
                        class="inline-flex items-center px-4 py-2 bg-purple-600 border border-transparent rounded-lg text-sm font-medium text-white hover:bg-purple-700">
                        <i data-lucide="plus" class="w-4 h-4 mr-2"></i>
                        Add Product
                    </a>
                </div>
            </div>
        </div>

        <!-- Products Stats -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-xl p-6 border border-gray-200 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Total Products</p>
                        <p class="text-2xl font-bold text-gray-900 mt-1">{{ $stats['total'] }}</p>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                        <i data-lucide="package" class="w-6 h-6 text-blue-600"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl p-6 border border-gray-200 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Approved Products</p>
                        <p class="text-2xl font-bold text-green-600 mt-1">{{ $stats['approved'] }}</p>
                    </div>
                    <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                        <i data-lucide="check-circle" class="w-6 h-6 text-green-600"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl p-6 border border-gray-200 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Pending Products</p>
                        <p class="text-2xl font-bold text-yellow-600 mt-1">{{ $stats['pending'] }}</p>
                    </div>
                    <div class="w-12 h-12 bg-yellow-100 rounded-xl flex items-center justify-center">
                        <i data-lucide="alert-triangle" class="w-6 h-6 text-yellow-600"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl p-6 border border-gray-200 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Rejected Products</p>
                        <p class="text-2xl font-bold text-red-600 mt-1">{{ $stats['rejected'] }}</p>
                    </div>
                    <div class="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center">
                        <i data-lucide="x-circle" class="w-6 h-6 text-red-600"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Products Table -->
        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden shadow-sm">
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900">Product Inventory</h3>
                    <div class="flex items-center space-x-3">
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i data-lucide="search" class="w-4 h-4 text-gray-400"></i>
                            </div>
                            <input type="text" placeholder="Search products..."
                                class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-purple-500 focus:border-purple-500">
                        </div>
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Product</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Categories
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stock
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($products as $product)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-12 w-12">
                                            @if ($product->featured_image)
                                                <img class="h-12 w-12 rounded-lg object-cover"
                                                    src="{{ asset('storage/' . $product->featured_image) }}"
                                                    alt="{{ $product->name }}">
                                            @else
                                                <div
                                                    class="h-12 w-12 rounded-lg bg-gray-200 flex items-center justify-center">
                                                    <i data-lucide="package" class="w-6 h-6 text-gray-400"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $product->name }}</div>
                                            <div class="text-sm text-gray-500">SKU: {{ $product->sku }}</div>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex flex-wrap gap-1">
                                        @foreach ($product->categories as $category)
                                            <span
                                                class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                                {{ $category->name }}
                                            </span>
                                        @endforeach
                                    </div>
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">₹{{ number_format($product->price, 2) }}</div>
                                    @if ($product->sale_price)
                                        <div class="text-xs text-green-600 font-medium">Sale:
                                            ₹{{ number_format($product->sale_price, 2) }}</div>
                                    @endif
                                    @if ($product->is_vendor_product && $product->vendor_commission_rate)
                                        <div class="text-xs text-purple-600">Commission:
                                            {{ $product->vendor_commission_rate }}%</div>
                                    @endif
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $product->stock_quantity }}</div>
                                    <div class="text-xs text-gray-500">
                                        {{ ucfirst(str_replace('_', ' ', $product->stock_status)) }}</div>
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex flex-col space-y-1">
                                        <!-- Product Status -->
                                        <div class="flex items-center space-x-2">
                                            <span class="text-xs font-medium text-gray-600">Status:</span>
                                            <span
                                                class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $product->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                {{ $product->is_active ? 'Active' : 'Inactive' }}
                                            </span>
                                        </div>

                                        <!-- Featured Status -->
                                        @if ($product->is_featured)
                                            <div class="flex items-center space-x-2">
                                                <span class="text-xs font-medium text-gray-600">Featured:</span>
                                                <span
                                                    class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-purple-100 text-purple-800">
                                                    Featured
                                                </span>
                                            </div>
                                        @endif

                                        <!-- Vendor Approval Status -->
                                        @if ($product->is_vendor_product)
                                            <div class="flex items-center space-x-2">
                                                <span class="text-xs font-medium text-gray-600">Approval:</span>
                                                <span
                                                    class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                    {{ $product->approval_status === 'approved'
                        ? 'bg-green-100 text-green-800'
                        : ($product->approval_status === 'pending'
                            ? 'bg-yellow-100 text-yellow-800'
                            : 'bg-red-100 text-red-800') }}">
                                                    {{ ucfirst($product->approval_status) }}
                                                </span>
                                            </div>
                                        @endif
                                    </div>
                                </td>


                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center space-x-2">
                                        <a href="{{ route('vendor.products.show', $product) }}"
                                            class="text-indigo-600 hover:text-indigo-900" title="View">
                                            <i data-lucide="eye" class="w-4 h-4"></i>
                                        </a>
                                        <a href="{{ route('vendor.products.edit', $product) }}"
                                            class="text-purple-600 hover:text-purple-900" title="Edit">
                                            <i data-lucide="edit" class="w-4 h-4"></i>
                                        </a>
                                        <form action="{{ route('vendor.products.destroy', $product) }}" method="POST"
                                            class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900"
                                                onclick="return confirm('Are you sure?')" title="Delete">
                                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center">
                                        <i data-lucide="package" class="w-12 h-12 text-gray-400 mb-4"></i>
                                        <h3 class="text-sm font-medium text-gray-900 mb-2">No products found</h3>
                                        <p class="text-sm text-gray-500 mb-4">Create your first product to get started.</p>
                                        <a href="{{ route('vendor.products.create') }}"
                                            class="inline-flex items-center px-4 py-2 bg-purple-600 border border-transparent rounded-md text-sm font-medium text-white hover:bg-purple-700">
                                            <i data-lucide="plus" class="w-4 h-4 mr-2"></i>
                                            Add Product
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if ($products->hasPages())
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $products->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection
