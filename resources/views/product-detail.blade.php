@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto px-4 py-8">
        <!-- Back Button -->
        <div class="mb-6">
            <a href="{{ route('products.index') }}" class="inline-flex items-center gap-2 text-gray-600 hover:text-vibrant-pink">
                <i data-lucide="arrow-left" class="w-4 h-4"></i>
                Back to Products
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
            <!-- Product Images -->
            <div class="space-y-4">
                <!-- Main Image Display -->
                <div class="relative">
                    <img id="mainImage" 
                         src="{{ $product->featured_image_url }}" 
                         alt="{{ $product->name }}"
                         class="w-full h-96 object-cover rounded-2xl">
                    
                    @if ($product->discount_percentage > 0)
                        <div class="absolute top-4 left-4 bg-red-500 text-white text-sm font-medium px-3 py-1 rounded-full">
                            {{ $product->discount_percentage }}% OFF
                        </div>
                    @endif
                    
                    @if ($product->is_vendor_product)
                        <div class="absolute top-4 right-4 bg-blue-500 text-white text-sm font-medium px-3 py-1 rounded-full">
                            Vendor Product
                        </div>
                    @endif
                </div>

                <!-- Image Thumbnails -->
                <div class="grid grid-cols-4 gap-2">
                    @if (isset($productImages))
                        @foreach ($productImages as $index => $image)
                            <div class="image-thumbnail cursor-pointer border-2 {{ $index === 0 ? 'border-vibrant-pink' : 'border-gray-200' }} hover:border-vibrant-pink rounded-lg overflow-hidden"
                                 onclick="changeMainImage('{{ $image['url'] }}', this)">
                                <img src="{{ $image['url'] }}" alt="{{ $image['alt'] ?? $product->name }}" class="w-full h-20 object-cover">
                            </div>
                        @endforeach
                    @endif
                </div>

                <!-- Product Features -->
                <div class="bg-gray-50 rounded-2xl p-6">
                    <h3 class="font-semibold mb-4 flex items-center gap-2">
                        <i data-lucide="star" class="w-5 h-5 text-yellow-500"></i>
                        Product Features
                    </h3>
                    <ul class="space-y-2 text-gray-600">
                        <li class="flex items-center gap-2">
                            <i data-lucide="check" class="w-4 h-4 text-green-600"></i>
                            Authentic and blessed items
                        </li>
                        <li class="flex items-center gap-2">
                            <i data-lucide="check" class="w-4 h-4 text-green-600"></i>
                            Premium quality guaranteed
                        </li>
                        @if ($product->manage_stock && $product->stock_quantity > 0)
                            <li class="flex items-center gap-2">
                                <i data-lucide="check" class="w-4 h-4 text-green-600"></i>
                                In Stock ({{ $product->stock_quantity }} available)
                            </li>
                        @endif
                        @if ($product->is_vendor_product && $product->vendor)
                            <li class="flex items-center gap-2">
                                <i data-lucide="check" class="w-4 h-4 text-blue-600"></i>
                                Sold by {{ $product->vendor->name }}
                            </li>
                        @endif
                    </ul>
                </div>
            </div>

            <!-- Product Details -->
            <div class="space-y-6">
                <!-- Product Info -->
                <div>
                    @if ($product->categories->count() > 0)
                        <span class="text-sm text-gray-500 bg-gray-100 px-3 py-1 rounded-full">
                            {{ $product->categories->first()->name }}
                        </span>
                    @endif
                    
                    <h1 class="text-3xl font-bold mt-4 mb-2">{{ $product->name }}</h1>
                    
                    <div class="flex items-center gap-2 mb-4">
                        <div class="flex items-center gap-1 text-yellow-500">
                            @for ($i = 1; $i <= 5; $i++)
                                <i data-lucide="star" class="w-4 h-4 {{ $i <= 4 ? 'fill-current' : '' }}"></i>
                            @endfor
                        </div>
                        <span class="text-gray-600">4.8 ({{ $product->reviews->count() }} reviews)</span>
                    </div>
                    
                    <p class="text-gray-600 mb-6">
                        {{ $product->description ?: 'Complete kit for your daily spiritual practice with authentic items.' }}
                    </p>
                </div>

                <!-- Pricing -->
                <div class="border-t border-gray-200 pt-6">
                    <div class="flex items-center gap-3 mb-2">
                        <span class="text-3xl font-bold text-vibrant-pink">₹{{ number_format($product->final_price, 2) }}</span>
                        @if ($product->sale_price && $product->price > $product->sale_price)
                            <span class="text-xl text-gray-500 line-through">₹{{ number_format($product->price, 2) }}</span>
                            <span class="bg-green-100 text-green-800 text-sm font-medium px-2 py-1 rounded">
                                Save ₹{{ number_format($product->price - $product->sale_price, 2) }}
                            </span>
                        @endif
                    </div>
                    <div class="flex items-center gap-2 text-sm text-gray-600">
                        <i data-lucide="truck" class="w-4 h-4"></i>
                        <span>Fast delivery available</span>
                    </div>
                </div>

                <!-- Add to Cart -->
                <div class="border-t border-gray-200 pt-6">
                    <div class="flex items-center gap-4 mb-6">
                        <div class="flex items-center border border-gray-300 rounded-lg">
                            <button onclick="updateProductQuantity(-1)" class="p-3 hover:bg-gray-100 rounded-l-lg">
                                <i data-lucide="minus" class="w-4 h-4"></i>
                            </button>
                            <span id="quantity" class="px-4 py-3 font-medium">1</span>
                            <button onclick="updateProductQuantity(1)" class="p-3 hover:bg-gray-100 rounded-r-lg">
                                <i data-lucide="plus" class="w-4 h-4"></i>
                            </button>
                        </div>
                        
                        <button id="addToCartBtn" onclick="addToCartFromDetail()"
                                class="flex-1 bg-vibrant-pink hover:bg-vibrant-pink-dark text-white font-medium py-3 rounded-lg transition-colors {{ $product->stock_quantity == 0 ? 'opacity-50 cursor-not-allowed' : '' }}"
                                {{ $product->stock_quantity == 0 ? 'disabled' : '' }}>
                            <span class="button-text">{{ $product->stock_quantity == 0 ? 'Out of Stock' : 'Add to Cart' }}</span>
                        </button>
                        
                        <button class="p-3 border border-gray-300 rounded-lg hover:bg-gray-100">
                            <i data-lucide="heart" class="w-5 h-5"></i>
                        </button>
                        <button class="p-3 border border-gray-300 rounded-lg hover:bg-gray-100">
                            <i data-lucide="share-2" class="w-5 h-5"></i>
                        </button>
                    </div>

                    <!-- Service Icons -->
                    <div class="grid grid-cols-3 gap-4 text-center">
                        <div class="flex flex-col items-center gap-2">
                            <div class="bg-blue-100 p-3 rounded-full">
                                <i data-lucide="truck" class="w-6 h-6 text-blue-600"></i>
                            </div>
                            <span class="text-sm text-gray-600">Fast Delivery</span>
                        </div>
                        <div class="flex flex-col items-center gap-2">
                            <div class="bg-green-100 p-3 rounded-full">
                                <i data-lucide="shield" class="w-6 h-6 text-green-600"></i>
                            </div>
                            <span class="text-sm text-gray-600">Quality Assured</span>
                        </div>
                        <div class="flex flex-col items-center gap-2">
                            <div class="bg-purple-100 p-3 rounded-full">
                                <i data-lucide="headphones" class="w-6 h-6 text-purple-600"></i>
                            </div>
                            <span class="text-sm text-gray-600">24/7 Support</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Product Description -->
        <div class="mt-16">
            <div class="max-w-4xl">
                <h2 class="text-2xl font-bold mb-6">Product Description</h2>
                
                <div class="prose prose-gray max-w-none">
                    <p class="text-gray-600 mb-6">
                        {{ $product->long_description ?: $product->description ?: 'This complete puja kit includes all traditional elements needed for your spiritual practice. Carefully curated by our spiritual experts, each item carries deep significance and helps create a sacred atmosphere for your prayers.' }}
                    </p>

                    @if ($product->attributes && count($product->attributes) > 0)
                        <h3 class="text-lg font-semibold mb-3">Specifications:</h3>
                        <ul class="space-y-2 text-gray-600 mb-6">
                            @foreach ($product->attributes as $key => $value)
                                <li class="flex items-center gap-2">
                                    <i data-lucide="check" class="w-4 h-4 text-green-600"></i>
                                    <strong>{{ ucfirst($key) }}:</strong> {{ $value }}
                                </li>
                            @endforeach
                        </ul>
                    @endif

                    <!-- Categories -->
                    @if ($product->categories->count() > 0)
                        <h3 class="text-lg font-semibold mb-3">Categories:</h3>
                        <div class="flex flex-wrap gap-2 mb-6">
                            @foreach ($product->categories as $category)
                                <a href="{{ route('category.show', $category->slug) }}" 
                                   class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800 hover:bg-vibrant-pink hover:text-white transition-colors">
                                    {{ $category->name }}
                                </a>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Related Products -->
        @if ($relatedProducts->count() > 0)
            <div class="mt-16">
                <h2 class="text-2xl font-bold mb-6">Related Products</h2>
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
                    @foreach ($relatedProducts as $relatedProduct)
                        <div class="bg-white rounded-lg border border-gray-200 overflow-hidden hover:shadow-lg transition-shadow">
                            <a href="{{ route('product.show', $relatedProduct->slug) }}">
                                <img src="{{ $relatedProduct->featured_image_url }}" 
                                     alt="{{ $relatedProduct->name }}" 
                                     class="w-full h-32 object-cover">
                                <div class="p-3">
                                    <h3 class="font-medium text-sm line-clamp-2 mb-2">{{ $relatedProduct->name }}</h3>
                                    <p class="text-vibrant-pink font-bold text-sm">₹{{ number_format($relatedProduct->final_price, 2) }}</p>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    <!-- FIXED: JavaScript functions defined in window scope -->
    <script>
        // Define global variables
        window.currentQuantity = 1;
        window.maxStock = {{ $product->stock_quantity ?: 999 }};

        // Define functions in global window scope
        window.updateProductQuantity = function(change) {
            const newQuantity = window.currentQuantity + change;
            
            if (newQuantity >= 1 && newQuantity <= Math.min(10, window.maxStock)) {
                window.currentQuantity = newQuantity;
                const quantityElement = document.getElementById('quantity');
                if (quantityElement) {
                    quantityElement.textContent = window.currentQuantity;
                }
            }
            
            // Update button states
            updateQuantityButtonStates();
        };

        // Update quantity button states
        window.updateQuantityButtonStates = function() {
            const minusBtn = document.querySelector('button[onclick="updateProductQuantity(-1)"]');
            const plusBtn = document.querySelector('button[onclick="updateProductQuantity(1)"]');
            
            if (minusBtn && plusBtn) {
                minusBtn.disabled = window.currentQuantity <= 1;
                plusBtn.disabled = window.currentQuantity >= Math.min(10, window.maxStock);
                
                minusBtn.classList.toggle('opacity-50', window.currentQuantity <= 1);
                plusBtn.classList.toggle('opacity-50', window.currentQuantity >= Math.min(10, window.maxStock));
            }
        };

        // Add to cart from product detail page
        window.addToCartFromDetail = function() {
            // Check if global cart system exists
            if (typeof window.cart !== 'undefined' && window.cart.loading) return;
            
            const button = document.getElementById('addToCartBtn');
            if (!button || button.disabled) return;
            
            // Check if global addToCart function exists
            if (typeof window.addToCart === 'function') {
                // Create synthetic event for global function
                const originalEvent = window.event;
                window.event = { target: button };
                
                try {
                    // Call global addToCart function
                    window.addToCart({{ $product->id }}, window.currentQuantity, null);
                } catch (error) {
                    console.error('Error adding to cart:', error);
                    if (typeof window.showToast === 'function') {
                        window.showToast('Failed to add item to cart', 'error');
                    } else {
                        alert('Failed to add item to cart');
                    }
                } finally {
                    window.event = originalEvent;
                }
            } else {
                console.error('Global addToCart function not found');
                alert('Cart system not available. Please refresh the page.');
            }
        };

        // Change main image function
        window.changeMainImage = function(imageSrc, thumbnailElement) {
            const mainImage = document.getElementById('mainImage');
            if (mainImage) {
                mainImage.src = imageSrc;
            }
            
            document.querySelectorAll('.image-thumbnail').forEach(thumb => {
                thumb.classList.remove('border-vibrant-pink');
                thumb.classList.add('border-gray-200');
            });
            
            if (thumbnailElement) {
                thumbnailElement.classList.remove('border-gray-200');
                thumbnailElement.classList.add('border-vibrant-pink');
            }
        };

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize button states
            if (typeof window.updateQuantityButtonStates === 'function') {
                window.updateQuantityButtonStates();
            }
            
            // Initialize Lucide icons if available
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        });
    </script>

    <style>
        .image-thumbnail {
            transition: border-color 0.2s ease;
        }
        .image-thumbnail:hover {
            border-color: #ff3c65;
        }
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
    </style>
@endsection
