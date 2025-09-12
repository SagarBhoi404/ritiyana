<!-- resources/views/admin/products/show.blade.php -->
@extends('layouts.admin')

@section('title', 'Product Details')
@section('breadcrumb', 'Products / View Product')

@section('content')
    <div class="px-4 sm:px-6 lg:px-8 py-8">
        <!-- Page Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Product Details</h1>
                    <p class="mt-2 text-sm text-gray-700">Complete information about this product</p>
                </div>
                <div class="flex items-center space-x-3">
                    <a href="{{ route('vendor.products.edit', $product) }}"
                        class="inline-flex items-center px-4 py-2 bg-purple-600 border border-transparent rounded-lg text-sm font-medium text-white hover:bg-purple-700">
                        <i data-lucide="edit" class="w-4 h-4 mr-2"></i>
                        Edit Product
                    </a>
                    <a href="{{ route('vendor.products.index') }}"
                        class="inline-flex items-center px-4 py-2 bg-gray-100 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-200">
                        <i data-lucide="arrow-left" class="w-4 h-4 mr-2"></i>
                        Back to Products
                    </a>
                </div>
            </div>
        </div>

        <!-- Product Profile Card -->
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm mb-8">
            <div class="p-8">
                <div class="flex flex-col lg:flex-row items-start lg:items-center space-y-6 lg:space-y-0 lg:space-x-8">
                    <!-- Product Image -->
                    <div class="flex-shrink-0">
                        @if ($product->featured_image)
                            <img class="h-48 w-48 rounded-xl object-cover border-4 border-white shadow-lg"
                                src="{{ asset('storage/' . $product->featured_image) }}" alt="{{ $product->name }}">
                        @else
                            <div
                                class="h-48 w-48 rounded-xl bg-gradient-to-br from-purple-500 to-pink-500 flex items-center justify-center shadow-lg">
                                <i data-lucide="package" class="w-24 h-24 text-white"></i>
                            </div>
                        @endif
                    </div>

                    <!-- Product Info -->
                    <div class="flex-1">
                        <h2 class="text-2xl font-bold text-gray-900">{{ $product->name }}</h2>
                        <p class="text-lg text-gray-600 mb-2">SKU: {{ $product->sku }}</p>

                        <!-- Price Information -->
                        <div class="mb-4">
                            <div class="flex items-center space-x-3">
                                <span
                                    class="text-3xl font-bold text-green-600">₹{{ number_format($product->price, 2) }}</span>
                                @if ($product->sale_price && $product->sale_price < $product->price)
                                    <span
                                        class="text-lg text-gray-500 line-through">₹{{ number_format($product->sale_price, 2) }}</span>
                                    <span
                                        class="inline-flex px-3 py-1 text-sm font-semibold rounded-full bg-red-100 text-red-800">
                                        {{ round((($product->price - $product->sale_price) / $product->price) * 100) }}% OFF
                                    </span>
                                @endif
                            </div>
                            @if ($product->cost_price)
                                <p class="text-sm text-gray-500 mt-1">Cost: ₹{{ number_format($product->cost_price, 2) }}
                                </p>
                            @endif
                        </div>

                        <!-- Status & Type -->
                        <div class="flex items-center space-x-3 mb-4">
                            <span
                                class="inline-flex px-3 py-1 text-sm font-semibold rounded-full 
                            {{ $product->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                <i data-lucide="{{ $product->is_active ? 'check-circle' : 'x-circle' }}"
                                    class="w-4 h-4 mr-1"></i>
                                {{ $product->is_active ? 'Active' : 'Inactive' }}
                            </span>

                            <span
                                class="inline-flex px-3 py-1 text-sm font-semibold rounded-full bg-blue-100 text-blue-800">
                                <i data-lucide="tag" class="w-4 h-4 mr-1"></i>
                                {{ ucfirst($product->type) }}
                            </span>

                            @if ($product->is_featured)
                                <span
                                    class="inline-flex px-3 py-1 text-sm font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                    <i data-lucide="star" class="w-4 h-4 mr-1"></i>
                                    Featured
                                </span>
                            @endif

                            @if ($product->is_vendor_product)
                                <span
                                    class="inline-flex px-3 py-1 text-sm font-semibold rounded-full 
                                {{ $product->approval_status === 'approved'
                                    ? 'bg-green-100 text-green-800'
                                    : ($product->approval_status === 'pending'
                                        ? 'bg-yellow-100 text-yellow-800'
                                        : 'bg-red-100 text-red-800') }}">
                                    <i data-lucide="user-check" class="w-4 h-4 mr-1"></i>
                                    {{ ucfirst($product->approval_status) }}
                                </span>
                            @else
                                <span
                                    class="inline-flex px-3 py-1 text-sm font-semibold rounded-full bg-blue-100 text-blue-800">
                                    <i data-lucide="building-2" class="w-4 h-4 mr-1"></i>
                                    Platform Product
                                </span>
                            @endif
                        </div>

                        <!-- Categories -->
                        <div class="mb-4">
                            <h4 class="text-sm font-medium text-gray-700 mb-2">Categories:</h4>
                            <div class="flex flex-wrap gap-2">
                                @foreach ($product->categories as $category)
                                    <span
                                        class="inline-flex px-3 py-1 text-sm font-semibold rounded-full bg-purple-100 text-purple-800">
                                        {{ $category->name }}
                                    </span>
                                @endforeach
                            </div>
                        </div>

                        <!-- Quick Stats -->
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-sm">
                            <div>
                                <p class="text-gray-500">Stock Quantity</p>
                                <p
                                    class="font-semibold text-lg {{ $product->stock_quantity < 10 ? 'text-red-600' : 'text-green-600' }}">
                                    {{ $product->stock_quantity }}
                                </p>
                            </div>
                            <div>
                                <p class="text-gray-500">Stock Status</p>
                                <p class="font-semibold text-lg">
                                    {{ ucfirst(str_replace('_', ' ', $product->stock_status)) }}</p>
                            </div>
                            <div>
                                <p class="text-gray-500">Weight</p>
                                <p class="font-semibold text-lg">{{ $product->weight ? $product->weight . ' kg' : 'N/A' }}
                                </p>
                            </div>
                            <div>
                                <p class="text-gray-500">Created</p>
                                <p class="font-semibold text-lg">{{ $product->created_at->format('M Y') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Product Details -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Description -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-2xl border border-gray-200 shadow-sm">
                    <div class="p-6 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                            <i data-lucide="file-text" class="w-5 h-5 mr-2"></i>
                            Product Description
                        </h3>
                    </div>
                    <div class="p-6">
                        @if ($product->short_description)
                            <div class="mb-4">
                                <h4 class="font-medium text-gray-900 mb-2">Short Description</h4>
                                <p class="text-gray-700">{{ $product->short_description }}</p>
                            </div>
                        @endif

                        @if ($product->description)
                            <div>
                                <h4 class="font-medium text-gray-900 mb-2">Full Description</h4>
                                <div class="prose max-w-none text-gray-700">
                                    {!! nl2br(e($product->description)) !!}
                                </div>
                            </div>
                        @else
                            <p class="text-gray-500 text-center py-8">No description available</p>
                        @endif
                    </div>
                </div>

                <!-- Gallery Images -->
                @if ($product->gallery_images && count($product->gallery_images) > 0)
                    <div class="mt-8 bg-white rounded-2xl border border-gray-200 shadow-sm">
                        <div class="p-6 border-b border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                                <i data-lucide="images" class="w-5 h-5 mr-2"></i>
                                Product Gallery ({{ count($product->gallery_images) }})
                            </h3>
                        </div>
                        <div class="p-6">
                            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                                @foreach ($product->gallery_images as $image)
                                    <img src="{{ asset('storage/' . $image) }}" alt="Gallery image"
                                        class="w-full h-32 rounded-lg object-cover cursor-pointer hover:opacity-75 transition-opacity"
                                        onclick="openImageModal('{{ asset('storage/' . $image) }}')">
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="space-y-8">
                <!-- Product Specifications -->
                <div class="bg-white rounded-2xl border border-gray-200 shadow-sm">
                    <div class="p-6 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                            <i data-lucide="info" class="w-5 h-5 mr-2"></i>
                            Specifications
                        </h3>
                    </div>
                    <div class="p-6">
                        <div class="space-y-4">
                            <div class="flex justify-between items-center py-2 border-b border-gray-100">
                                <span class="text-sm font-medium text-gray-600">Product ID</span>
                                <span class="text-sm text-gray-900 font-mono">#{{ $product->id }}</span>
                            </div>
                            <div class="flex justify-between items-center py-2 border-b border-gray-100">
                                <span class="text-sm font-medium text-gray-600">SKU</span>
                                <span class="text-sm text-gray-900 font-mono">{{ $product->sku }}</span>
                            </div>
                            <div class="flex justify-between items-center py-2 border-b border-gray-100">
                                <span class="text-sm font-medium text-gray-600">Type</span>
                                <span class="text-sm text-gray-900">{{ ucfirst($product->type) }}</span>
                            </div>
                            @if ($product->weight)
                                <div class="flex justify-between items-center py-2 border-b border-gray-100">
                                    <span class="text-sm font-medium text-gray-600">Weight</span>
                                    <span class="text-sm text-gray-900">{{ $product->weight }} kg</span>
                                </div>
                            @endif
                            @if ($product->dimensions)
                                <div class="flex justify-between items-center py-2 border-b border-gray-100">
                                    <span class="text-sm font-medium text-gray-600">Dimensions</span>
                                    <span class="text-sm text-gray-900">{{ $product->dimensions }}</span>
                                </div>
                            @endif
                            <div class="flex justify-between items-center py-2 border-b border-gray-100">
                                <span class="text-sm font-medium text-gray-600">Manage Stock</span>
                                <span class="text-sm text-gray-900">{{ $product->manage_stock ? 'Yes' : 'No' }}</span>
                            </div>
                            <div class="flex justify-between items-center py-2">
                                <span class="text-sm font-medium text-gray-600">Created By</span>
                                <span
                                    class="text-sm text-gray-900">{{ $product->creator ? $product->creator->full_name : 'N/A' }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Product Attributes -->
                @if ($product->attributes && count($product->attributes) > 0)
                    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm">
                        <div class="p-6 border-b border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                                <i data-lucide="layers" class="w-5 h-5 mr-2"></i>
                                Attributes
                            </h3>
                        </div>
                        <div class="p-6">
                            <div class="space-y-3">
                                @foreach ($product->attributes as $key => $value)
                                    <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg">
                                        <span class="text-sm font-medium text-gray-600">{{ ucfirst($key) }}</span>
                                        <span
                                            class="text-sm text-gray-900">{{ is_array($value) ? implode(', ', $value) : $value }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

                <!-- SEO Information -->
                @if ($product->meta_title || $product->meta_description)
                    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm">
                        <div class="p-6 border-b border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                                <i data-lucide="search" class="w-5 h-5 mr-2"></i>
                                SEO Information
                            </h3>
                        </div>
                        <div class="p-6">
                            @if ($product->meta_title)
                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Meta Title</label>
                                    <p class="text-gray-900 bg-gray-50 p-3 rounded-lg text-sm">{{ $product->meta_title }}
                                    </p>
                                </div>
                            @endif

                            @if ($product->meta_description)
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Meta Description</label>
                                    <p class="text-gray-900 bg-gray-50 p-3 rounded-lg text-sm">
                                        {{ $product->meta_description }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Actions -->
        <div class="mt-8 flex justify-center space-x-4">
            <a href="{{ route('vendor.products.edit', $product) }}"
                class="inline-flex items-center px-6 py-3 bg-purple-600 border border-transparent rounded-lg text-sm font-medium text-white hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2">
                <i data-lucide="edit" class="w-4 h-4 mr-2"></i>
                Edit Product
            </a>
            <button onclick="confirmDelete()"
                class="inline-flex items-center px-6 py-3 bg-red-600 border border-transparent rounded-lg text-sm font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                <i data-lucide="trash-2" class="w-4 h-4 mr-2"></i>
                Delete Product
            </button>
        </div>
    </div>

    <!-- Image Modal -->
    <div id="imageModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center">
            <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" onclick="closeImageModal()"></div>
            <div class="relative inline-block max-w-4xl mx-auto">
                <img id="modalImage" src="" alt="Product image" class="rounded-lg shadow-2xl max-h-screen">
                <button onclick="closeImageModal()"
                    class="absolute top-4 right-4 text-white bg-black bg-opacity-50 rounded-full p-2 hover:bg-opacity-75">
                    <i data-lucide="x" class="w-6 h-6"></i>
                </button>
            </div>
        </div>
    </div>

    <form id="deleteForm" action="{{ route('vendor.products.destroy', $product) }}" method="POST" class="hidden">
        @csrf
        @method('DELETE')
    </form>

    <script>
        function openImageModal(imageSrc) {
            document.getElementById('modalImage').src = imageSrc;
            document.getElementById('imageModal').classList.remove('hidden');
        }

        function closeImageModal() {
            document.getElementById('imageModal').classList.add('hidden');
        }

        function confirmDelete() {
            if (confirm('Are you sure you want to delete this product? This action cannot be undone.')) {
                document.getElementById('deleteForm').submit();
            }
        }

        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeImageModal();
            }
        });
    </script>
@endsection
