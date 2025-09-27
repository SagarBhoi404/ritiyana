<!-- resources/views/admin/categories/show.blade.php -->
@extends('layouts.admin')

@section('title', 'Category Details')
@section('breadcrumb', 'Categories / View Category')

@section('content')
    <div class="px-4 sm:px-6 lg:px-8 py-8">
        <!-- Page Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Category Details</h1>
                    <p class="mt-2 text-sm text-gray-700">Complete information about this category</p>
                </div>
                <div class="flex items-center space-x-3">
                    <a href="{{ route('admin.categories.edit', $category) }}"
                        class="inline-flex items-center px-4 py-2 bg-purple-600 border border-transparent rounded-lg text-sm font-medium text-white hover:bg-purple-700">
                        <i data-lucide="edit" class="w-4 h-4 mr-2"></i>
                        Edit Category
                    </a>
                    <a href="{{ route('admin.categories.index') }}"
                        class="inline-flex items-center px-4 py-2 bg-gray-100 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-200">
                        <i data-lucide="arrow-left" class="w-4 h-4 mr-2"></i>
                        Back to Categories
                    </a>
                </div>
            </div>
        </div>

        <!-- Category Profile Card -->
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm mb-8">
            <div class="p-8">
                <div class="flex flex-col sm:flex-row items-start sm:items-center space-y-4 sm:space-y-0 sm:space-x-6">
                    <!-- Category Image -->
                    <div class="flex-shrink-0">
                        @if ($category->image)
                            <img class="h-24 w-24 rounded-xl object-cover border-4 border-white shadow-lg"
                                src="{{ $category->getImageUrlAttribute() }}" alt="{{ $category->name }}">
                        @else
                            <div
                                class="h-24 w-24 rounded-xl bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center shadow-lg">
                                @if ($category->icon)
                                    <i data-lucide="{{ $category->icon }}" class="w-12 h-12 text-white"></i>
                                @else
                                    <i data-lucide="folder" class="w-12 h-12 text-white"></i>
                                @endif
                            </div>
                        @endif
                    </div>

                    <!-- Category Info -->
                    <div class="flex-1">
                        <h2 class="text-2xl font-bold text-gray-900">{{ $category->name }}</h2>
                        <p class="text-lg text-gray-600 mb-2">{{ $category->slug }}</p>

                        <!-- Status & Parent -->
                        <div class="flex items-center space-x-3 mb-3">
                            <span
                                class="inline-flex px-3 py-1 text-sm font-semibold rounded-full 
                            {{ $category->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                <i data-lucide="{{ $category->is_active ? 'check-circle' : 'x-circle' }}"
                                    class="w-4 h-4 mr-1"></i>
                                {{ $category->is_active ? 'Active' : 'Inactive' }}
                            </span>

                            @if ($category->parent)
                                <span
                                    class="inline-flex px-3 py-1 text-sm font-semibold rounded-full bg-blue-100 text-blue-800">
                                    <i data-lucide="folder-tree" class="w-4 h-4 mr-1"></i>
                                    Child of {{ $category->parent->name }}
                                </span>
                            @else
                                <span
                                    class="inline-flex px-3 py-1 text-sm font-semibold rounded-full bg-purple-100 text-purple-800">
                                    <i data-lucide="folder" class="w-4 h-4 mr-1"></i>
                                    Root Category
                                </span>
                            @endif
                        </div>

                        <!-- Description -->
                        @if ($category->description)
                            <p class="text-gray-700 mb-4">{{ $category->description }}</p>
                        @endif

                        <!-- Quick Stats -->
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                            <div>
                                <p class="text-gray-500">Products</p>
                                <p class="font-semibold text-lg">{{ $category->products->count() }}</p>
                            </div>
                            <div>
                                <p class="text-gray-500">Subcategories</p>
                                <p class="font-semibold text-lg">{{ $category->children->count() }}</p>
                            </div>
                            <div>
                                <p class="text-gray-500">Sort Order</p>
                                <p class="font-semibold text-lg">{{ $category->sort_order }}</p>
                            </div>
                            <div>
                                <p class="text-gray-500">Created</p>
                                <p class="font-semibold text-lg">{{ $category->created_at->format('M Y') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Detailed Information -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Subcategories -->
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <i data-lucide="folder-tree" class="w-5 h-5 mr-2"></i>
                        Subcategories ({{ $category->children->count() }})
                    </h3>
                </div>
                <div class="p-6">
                    @if ($category->children->count() > 0)
                        <div class="space-y-3">
                            @foreach ($category->children as $child)
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                    <div class="flex items-center">
                                        @if ($child->image)
                                            <img class="h-8 w-8 rounded object-cover mr-3"
                                                src="{{ asset('storage/' . $child->image) }}" alt="{{ $child->name }}">
                                        @else
                                            <div class="h-8 w-8 bg-gray-200 rounded flex items-center justify-center mr-3">
                                                <i data-lucide="folder" class="w-4 h-4 text-gray-400"></i>
                                            </div>
                                        @endif
                                        <div>
                                            <p class="font-medium text-gray-900">{{ $child->name }}</p>
                                            <p class="text-xs text-gray-500">{{ $child->products->count() }} products</p>
                                        </div>
                                    </div>
                                    <span
                                        class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $child->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $child->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8">
                            <i data-lucide="folder" class="w-12 h-12 mx-auto text-gray-300 mb-4"></i>
                            <p class="text-gray-500">No subcategories found</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Products -->
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <i data-lucide="package" class="w-5 h-5 mr-2"></i>
                        Products ({{ $category->products->count() }})
                    </h3>
                </div>
                <div class="p-6">
                    @if ($category->products->count() > 0)
                        <div class="space-y-3">
                            @foreach ($category->products->take(10) as $product)
                                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                    <div class="flex items-center">
                                        @if ($product->featured_image)
                                            <img class="h-8 w-8 rounded object-cover mr-3"
                                                src="{{ asset('storage/' . $product->featured_image) }}"
                                                alt="{{ $product->name }}">
                                        @else
                                            <div class="h-8 w-8 bg-gray-200 rounded flex items-center justify-center mr-3">
                                                <i data-lucide="package" class="w-4 h-4 text-gray-400"></i>
                                            </div>
                                        @endif
                                        <div>
                                            <p class="font-medium text-gray-900">{{ $product->name }}</p>
                                            <p class="text-xs text-gray-500">SKU: {{ $product->sku }}</p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <p class="font-semibold text-gray-900">₹{{ number_format($product->price, 2) }}</p>
                                        <span
                                            class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $product->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                            {{ $product->is_active ? 'Active' : 'Inactive' }}
                                        </span>
                                    </div>
                                </div>
                            @endforeach

                            @if ($category->products->count() > 10)
                                <div class="text-center pt-4">
                                    <p class="text-sm text-gray-500">And {{ $category->products->count() - 10 }} more
                                        products...</p>
                                </div>
                            @endif
                        </div>
                    @else
                        <div class="text-center py-8">
                            <i data-lucide="package" class="w-12 h-12 mx-auto text-gray-300 mb-4"></i>
                            <p class="text-gray-500">No products assigned to this category</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Add this vendor info section in your existing show.blade.php --}}
        @if ($product->is_vendor_product && $product->vendor)
            <div class="mt-6 p-6 bg-white border border-gray-200 rounded">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Vendor Information</h3>

                <div class="space-y-3">
                    <div>
                        <strong>Vendor Name:</strong>
                        <span>{{ $product->vendor->first_name }} {{ $product->vendor->last_name }}</span>
                    </div>

                    <div>
                        <strong>Business Name:</strong>
                        <span>{{ $product->vendor->vendorProfile->business_name ?? 'N/A' }}</span>
                    </div>

                    <div>
                        <strong>Email:</strong>
                        <span>{{ $product->vendor->email }}</span>
                    </div>

                    <div>
                        <strong>Business Type:</strong>
                        <span>{{ ucfirst($product->vendor->vendorProfile->business_type ?? 'Individual') }}</span>
                    </div>

                    <div>
                        <strong>Vendor Status:</strong>
                        <span
                            class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                {{ $product->vendor->vendorProfile && $product->vendor->vendorProfile->status === 'approved' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                            {{ ucfirst($product->vendor->vendorProfile->status ?? 'Unknown') }}
                        </span>
                    </div>

                    <div>
                        <strong>Commission Rate:</strong>
                        <span>{{ $product->vendor_commission_rate ?? ($product->vendor->vendorProfile->commission_rate ?? 'N/A') }}%</span>
                    </div>

                    <div>
                        <strong>Product Status:</strong>
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
                </div>

                <div class="mt-4">
                    <a href="{{ route('admin.vendors.show', $product->vendor) }}"
                        class="inline-flex items-center text-indigo-600 hover:text-indigo-900">
                        <i data-lucide="external-link" class="w-4 h-4 mr-1"></i>
                        View Vendor Profile
                    </a>
                </div>
            </div>
        @endif


        <!-- SEO Information -->
        @if ($category->meta_title || $category->meta_description)
            <div class="mt-8 bg-white rounded-2xl border border-gray-200 shadow-sm">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <i data-lucide="search" class="w-5 h-5 mr-2"></i>
                        SEO Information
                    </h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @if ($category->meta_title)
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Meta Title</label>
                                <p class="text-gray-900 bg-gray-50 p-3 rounded-lg">{{ $category->meta_title }}</p>
                            </div>
                        @endif

                        @if ($category->meta_description)
                            <div class="{{ $category->meta_title ? '' : 'md:col-span-2' }}">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Meta Description</label>
                                <p class="text-gray-900 bg-gray-50 p-3 rounded-lg">{{ $category->meta_description }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endif

        <!-- Actions -->
        <div class="mt-8 flex justify-center space-x-4">
            <a href="{{ route('admin.categories.edit', $category) }}"
                class="inline-flex items-center px-6 py-3 bg-purple-600 border border-transparent rounded-lg text-sm font-medium text-white hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2">
                <i data-lucide="edit" class="w-4 h-4 mr-2"></i>
                Edit Category
            </a>
            @if ($category->products->count() == 0 && $category->children->count() == 0)
                <button onclick="confirmDelete()"
                    class="inline-flex items-center px-6 py-3 bg-red-600 border border-transparent rounded-lg text-sm font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                    <i data-lucide="trash-2" class="w-4 h-4 mr-2"></i>
                    Delete Category
                </button>
            @endif
        </div>
    </div>

    @if ($category->products->count() == 0 && $category->children->count() == 0)
        <form id="deleteForm" action="{{ route('admin.categories.destroy', $category) }}" method="POST"
            class="hidden">
            @csrf
            @method('DELETE')
        </form>
    @endif

    <script>
        function confirmDelete() {
            if (confirm('Are you sure you want to delete this category? This action cannot be undone.')) {
                document.getElementById('deleteForm').submit();
            }
        }
    </script>
@endsection
