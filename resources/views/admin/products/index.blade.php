<!-- resources/views/admin/products/index.blade.php -->
@extends('layouts.admin')

@section('title', 'Products Management')
@section('breadcrumb', 'Products')

@section('content')
<div class="px-4 sm:px-6 lg:px-8 py-8">
    <!-- Page Header -->
    <div class="mb-8">
        <div class="sm:flex sm:items-center sm:justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Products Management</h1>
                <p class="mt-2 text-sm text-gray-700">Manage puja items, kits, and inventory</p>
            </div>
            <div class="mt-4 sm:mt-0">
                <a href="{{ route('admin.products.create') }}" class="inline-flex items-center px-4 py-2 bg-purple-600 border border-transparent rounded-lg text-sm font-medium text-white hover:bg-purple-700">
                    <i data-lucide="plus" class="w-4 h-4 mr-2"></i>
                    Add Product
                </a>
            </div>
        </div>
    </div>

    <!-- Dynamic Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-xl p-6 border border-gray-200 shadow-sm">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                        <i data-lucide="package" class="w-6 h-6 text-blue-600"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <div class="text-sm font-medium text-gray-600">Total Products</div>
                    <div class="text-2xl font-bold text-gray-900">{{ number_format($totalProducts) }}</div>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl p-6 border border-gray-200 shadow-sm">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                        <i data-lucide="star" class="w-6 h-6 text-green-600"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <div class="text-sm font-medium text-gray-600">Featured Products</div>
                    <div class="text-2xl font-bold text-gray-900">{{ number_format($featuredProducts) }}</div>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl p-6 border border-gray-200 shadow-sm">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-yellow-100 rounded-xl flex items-center justify-center">
                        <i data-lucide="alert-triangle" class="w-6 h-6 text-yellow-600"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <div class="text-sm font-medium text-gray-600">Low Stock</div>
                    <div class="text-2xl font-bold text-gray-900">{{ number_format($lowStockProducts) }}</div>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl p-6 border border-gray-200 shadow-sm">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center">
                        <i data-lucide="file-text" class="w-6 h-6 text-red-600"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <div class="text-sm font-medium text-gray-600">Draft Products</div>
                    <div class="text-2xl font-bold text-gray-900">{{ number_format($draftProducts) }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Products Table -->
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Categories</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stock</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($products as $product)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                @if($product->featured_image)
                                    <img class="h-10 w-10 rounded object-cover" src="{{ asset('storage/' . $product->featured_image) }}" alt="{{ $product->name }}">
                                @else
                                    <div class="h-10 w-10 bg-gray-100 rounded flex items-center justify-center">
                                        <i data-lucide="package" class="w-5 h-5 text-gray-400"></i>
                                    </div>
                                @endif
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $product->name }}</div>
                                    <div class="text-sm text-gray-500">SKU: {{ $product->sku }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-wrap gap-1">
                                @foreach($product->categories as $category)
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                        {{ $category->name }}
                                    </span>
                                @endforeach
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">₹{{ number_format($product->price, 2) }}</div>
                            @if($product->sale_price)
                                <div class="text-sm text-gray-500 line-through">₹{{ number_format($product->sale_price, 2) }}</div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $product->stock_quantity }}</div>
                            <div class="text-sm text-gray-500">{{ ucfirst(str_replace('_', ' ', $product->stock_status)) }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex flex-col space-y-1">
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $product->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $product->is_active ? 'Active' : 'Inactive' }}
                                </span>
                                @if($product->is_featured)
                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                        Featured
                                    </span>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex items-center space-x-2">
                                <a href="{{ route('admin.products.show', $product) }}" class="text-blue-600 hover:text-blue-900">
                                    <i data-lucide="eye" class="w-4 h-4"></i>
                                </a>
                                <a href="{{ route('admin.products.edit', $product) }}" class="text-purple-600 hover:text-purple-900">
                                    <i data-lucide="edit" class="w-4 h-4"></i>
                                </a>
                                <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center">
                            <div class="text-gray-500">
                                <i data-lucide="package" class="w-12 h-12 mx-auto mb-4 text-gray-300"></i>
                                <p class="text-lg font-medium">No products found</p>
                                <p class="text-sm">Create your first product to get started.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="px-6 py-3 border-t border-gray-200 bg-gray-50">
            {{ $products->links() }}
        </div>
    </div>
</div>
@endsection
