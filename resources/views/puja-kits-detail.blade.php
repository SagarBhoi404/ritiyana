@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto px-4 py-8">
        <!-- Back Button -->
        <div class="mb-6">
            <a href="{{ route('puja-kits.index') }}"
                class="inline-flex items-center gap-2 text-gray-600 hover:text-vibrant-pink">
                <i data-lucide="arrow-left" class="w-4 h-4"></i>
                Back to Puja Kits
            </a>
        </div>

        <!-- Breadcrumbs -->
        <nav class="flex mb-6" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('home') }}" class="text-gray-600 hover:text-vibrant-pink">Home</a>
                </li>
                <li>
                    <div class="flex items-center">
                        <i data-lucide="chevron-right" class="w-4 h-4 text-gray-400 mx-1"></i>
                        <a href="{{ route('puja-kits.index') }}" class="text-gray-600 hover:text-vibrant-pink">Puja Kits</a>
                    </div>
                </li>
                <li aria-current="page">
                    <div class="flex items-center">
                        <i data-lucide="chevron-right" class="w-4 h-4 text-gray-400 mx-1"></i>
                        <span class="text-gray-500">{{ $pujaKit->kit_name }}</span>
                    </div>
                </li>
            </ol>
        </nav>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
            <!-- Kit Images -->
            <div class="space-y-4">
                <!-- Main Image Display -->
                <div class="relative">
                    <img id="mainImage"
                        src="{{ $pujaKit->image ? asset('storage/' . $pujaKit->image) : asset('images/default-kit.png') }}"
                        alt="{{ $pujaKit->kit_name }}" class="w-full h-96 object-cover rounded-2xl">

                    @if ($pujaKit->discount_percentage > 0)
                        <div class="absolute top-4 left-4 bg-red-500 text-white text-sm font-medium px-3 py-1 rounded-full">
                            {{ $pujaKit->discount_percentage }}% OFF
                        </div>
                    @endif

                    @if ($pujaKit->vendor)
                        <div
                            class="absolute top-4 right-4 bg-blue-500 text-white text-sm font-medium px-3 py-1 rounded-full">
                            By {{ $pujaKit->vendor->name }}
                        </div>
                    @endif
                </div>

                <!-- Kit Features -->
                <div class="bg-gray-50 rounded-2xl p-6">
                    <h3 class="font-semibold mb-4 flex items-center gap-2">
                        <i data-lucide="package" class="w-5 h-5 text-vibrant-pink"></i>
                        Kit Includes
                    </h3>

                    @if ($pujaKit->products->count() > 0)
                        <div class="space-y-3">
                            @foreach ($pujaKit->products->take(5) as $product)
                                <div class="flex items-center justify-between py-2 border-b border-gray-200 last:border-0">
                                    <div class="flex items-center gap-3">
                                        <img src="{{ $product->featured_image_url }}" alt="{{ $product->name }}"
                                            class="w-12 h-12 object-cover rounded-lg">
                                        <div>
                                            <h4 class="font-medium text-sm">{{ $product->name }}</h4>
                                            <p class="text-xs text-gray-600">{{ Str::limit($product->description, 40) }}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-sm font-medium">Qty: {{ $product->pivot->quantity }}</p>
                                        <p class="text-xs text-gray-600">
                                            ₹{{ number_format($product->pivot->price ?? $product->price, 2) }}</p>
                                    </div>
                                </div>
                            @endforeach

                            @if ($pujaKit->products->count() > 5)
                                <p class="text-sm text-gray-600 text-center pt-2">
                                    + {{ $pujaKit->products->count() - 5 }} more items
                                </p>
                            @endif
                        </div>
                    @else
                        <p class="text-gray-600">No products configured for this kit.</p>
                    @endif
                </div>

                <!-- Kit Statistics -->
                <div class="bg-gradient-to-r from-orange-50 to-red-50 rounded-2xl p-6">
                    <h3 class="font-semibold mb-4 flex items-center gap-2">
                        <i data-lucide="bar-chart-3" class="w-5 h-5 text-orange-600"></i>
                        Kit Statistics
                    </h3>
                    <div class="grid grid-cols-3 gap-4">
                        <div class="text-center">
                            <div class="text-2xl font-bold text-orange-600">{{ $kitStats['total_products'] }}</div>
                            <div class="text-xs text-gray-600">Products</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-orange-600">{{ $kitStats['total_items'] }}</div>
                            <div class="text-xs text-gray-600">Total Items</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-green-600">
                                ₹{{ number_format($kitStats['total_savings'], 2) }}</div>
                            <div class="text-xs text-gray-600">You Save</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Kit Details -->
            <div class="space-y-6">
                <!-- Kit Info -->
                <div>
                    @if ($pujaKit->pujas->count() > 0)
                        <div class="flex flex-wrap gap-2 mb-4">
                            @foreach ($pujaKit->pujas as $puja)
                                <span class="text-sm text-orange-700 bg-orange-100 px-3 py-1 rounded-full">
                                    {{ $puja->name }}
                                </span>
                            @endforeach
                        </div>
                    @endif

                    <h1 class="text-3xl font-bold mt-4 mb-2">{{ $pujaKit->kit_name }}</h1>

                    <div class="flex items-center gap-2 mb-4">
                        <div class="flex items-center gap-1 text-yellow-500">
                            @for ($i = 1; $i <= 5; $i++)
                                <i data-lucide="star" class="w-4 h-4 {{ $i <= 4 ? 'fill-current' : '' }}"></i>
                            @endfor
                        </div>
                        <span class="text-gray-600">4.8 (Review system not implemented)</span>
                    </div>

                    <p class="text-gray-600 mb-6">
                        {{ $pujaKit->description ?: 'Complete kit for your spiritual practice with authentic items.' }}
                    </p>

                    <!-- Puja Information -->
                    @if ($pujaKit->pujas->count() > 0)
                        <div class="mb-6">
                            <h3 class="font-semibold mb-3">Perfect for:</h3>
                            <div class="space-y-2">
                                @foreach ($pujaKit->pujas as $puja)
                                    <div class="flex items-start gap-3 p-3 bg-orange-50 rounded-lg">
                                        <i data-lucide="star" class="w-5 h-5 text-orange-600 mt-0.5"></i>
                                        <div>
                                            <h4 class="font-medium text-orange-900">{{ $puja->name }}</h4>
                                            @if ($puja->description)
                                                <p class="text-sm text-orange-700">
                                                    {{ Str::limit($puja->description, 100) }}</p>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Pricing -->
                <div class="border-t border-gray-200 pt-6">
                    <div class="flex items-center gap-3 mb-2">
                        <span
                            class="text-3xl font-bold text-vibrant-pink">₹{{ number_format($pujaKit->total_price, 2) }}</span>
                        @if ($kitStats['total_savings'] > 0)
                            @php
                                $originalPrice = $pujaKit->total_price + $kitStats['total_savings'];
                            @endphp
                            <span class="text-xl text-gray-500 line-through">₹{{ number_format($originalPrice, 2) }}</span>
                            <span class="bg-green-100 text-green-800 text-sm font-medium px-2 py-1 rounded">
                                Save ₹{{ number_format($kitStats['total_savings'], 2) }}
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
                            <button onclick="updateKitQuantity(-1)" class="p-3 hover:bg-gray-100 rounded-l-lg">
                                <i data-lucide="minus" class="w-4 h-4"></i>
                            </button>
                            <span id="quantity" class="px-4 py-3 font-medium">1</span>
                            <button onclick="updateKitQuantity(1)" class="p-3 hover:bg-gray-100 rounded-r-lg">
                                <i data-lucide="plus" class="w-4 h-4"></i>
                            </button>
                        </div>

                        <button id="addKitBtn" onclick="addKitToCart()"
                            class="flex-1 bg-vibrant-pink hover:bg-vibrant-pink-dark text-white font-medium py-3 rounded-lg transition-colors">
                            <span class="button-text">Add Kit to Cart</span>
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
                            <span class="text-sm text-gray-600">Authentic Items</span>
                        </div>
                        <div class="flex flex-col items-center gap-2">
                            <div class="bg-purple-100 p-3 rounded-full">
                                <i data-lucide="package" class="w-6 h-6 text-purple-600"></i>
                            </div>
                            <span class="text-sm text-gray-600">Complete Kit</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Kit Contents Detail -->
        @if ($pujaKit->products->count() > 0)
            <div class="mt-16">
                <h2 class="text-2xl font-bold mb-6">Complete Kit Contents</h2>
                <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-4 text-left text-sm font-medium text-gray-900">Product</th>
                                    <th class="px-6 py-4 text-center text-sm font-medium text-gray-900">Quantity</th>
                                    <th class="px-6 py-4 text-right text-sm font-medium text-gray-900">Price</th>
                                    <th class="px-6 py-4 text-right text-sm font-medium text-gray-900">Total</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                @foreach ($pujaKit->products as $product)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-4">
                                                <img src="{{ $product->featured_image_url }}" alt="{{ $product->name }}"
                                                    class="w-16 h-16 object-cover rounded-lg">
                                                <div>
                                                    <h4 class="font-medium">{{ $product->name }}</h4>
                                                    <p class="text-sm text-gray-600">
                                                        {{ Str::limit($product->description, 60) }}</p>
                                                    @if ($product->categories->count() > 0)
                                                        <span
                                                            class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded mt-1 inline-block">
                                                            {{ $product->categories->first()->name }}
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-center font-medium">
                                            {{ $product->pivot->quantity }}
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            ₹{{ number_format($product->pivot->price ?? $product->price, 2) }}
                                        </td>
                                        <td class="px-6 py-4 text-right font-medium">
                                            ₹{{ number_format(($product->pivot->price ?? $product->price) * $product->pivot->quantity, 2) }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="bg-gray-50">
                                <tr>
                                    <td colspan="3" class="px-6 py-4 text-right font-medium text-gray-900">
                                        Kit Total:
                                    </td>
                                    <td class="px-6 py-4 text-right font-bold text-vibrant-pink text-lg">
                                        ₹{{ number_format($pujaKit->total_price, 2) }}
                                    </td>
                                </tr>
                                @if ($kitStats['total_savings'] > 0)
                                    <tr>
                                        <td colspan="3" class="px-6 py-2 text-right text-sm text-green-700">
                                            You Save:
                                        </td>
                                        <td class="px-6 py-2 text-right font-medium text-green-700">
                                            ₹{{ number_format($kitStats['total_savings'], 2) }}
                                        </td>
                                    </tr>
                                @endif
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        @endif

        <!-- Related Kits -->
        @if ($relatedKits->count() > 0)
            <div class="mt-16">
                <h2 class="text-2xl font-bold mb-6">Related Puja Kits</h2>
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
                    @foreach ($relatedKits as $relatedKit)
                        <div
                            class="bg-white rounded-lg border border-gray-200 overflow-hidden hover:shadow-lg transition-shadow">
                            <a href="{{ route('puja-kits.show', $relatedKit->slug) }}">
                                <img src="{{ $relatedKit->image ? asset('storage/' . $relatedKit->image) : asset('images/default-kit.png') }}"
                                    alt="{{ $relatedKit->kit_name }}" class="w-full h-32 object-cover">
                                <div class="p-3">
                                    <h3 class="font-medium text-sm line-clamp-2 mb-2">{{ $relatedKit->kit_name }}</h3>
                                    <p class="text-vibrant-pink font-bold text-sm">
                                        ₹{{ number_format($relatedKit->total_price, 2) }}</p>
                                    @if ($relatedKit->pujas->count() > 0)
                                        <p class="text-xs text-gray-500 mt-1">{{ $relatedKit->pujas->first()->name }}</p>
                                    @endif
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    <script>
        // Define variables in global scope
        window.currentKitQuantity = 1;
        window.maxKitQuantity = 5; // Reasonable limit for puja kits

        window.updateKitQuantity = function(change) {
            const newQuantity = window.currentKitQuantity + change;

            // Apply min/max limits
            if (newQuantity >= 1 && newQuantity <= window.maxKitQuantity) {
                window.currentKitQuantity = newQuantity;
                const quantityElement = document.getElementById('quantity');
                if (quantityElement) {
                    quantityElement.textContent = window.currentKitQuantity;
                }
            }

            // Update button states
            updateKitQuantityButtons();
        };

        window.updateKitQuantityButtons = function() {
            const minusBtn = document.querySelector('button[onclick="updateKitQuantity(-1)"]');
            const plusBtn = document.querySelector('button[onclick="updateKitQuantity(1)"]');

            if (minusBtn) {
                minusBtn.disabled = window.currentKitQuantity <= 1;
                minusBtn.classList.toggle('opacity-50', window.currentKitQuantity <= 1);
                minusBtn.classList.toggle('cursor-not-allowed', window.currentKitQuantity <= 1);
            }

            if (plusBtn) {
                plusBtn.disabled = window.currentKitQuantity >= window.maxKitQuantity;
                plusBtn.classList.toggle('opacity-50', window.currentKitQuantity >= window.maxKitQuantity);
                plusBtn.classList.toggle('cursor-not-allowed', window.currentKitQuantity >= window.maxKitQuantity);
            }
        };

        window.addKitToCart = function() {
            // Check if global cart system exists
            if (typeof window.cart !== 'undefined' && window.cart.loading) return;

            const button = document.getElementById('addKitBtn');
            if (!button) return;

            // Check if global addPujaKitToCart function exists
            if (typeof window.addPujaKitToCart === 'function') {
                // Create synthetic event for global function
                const originalEvent = window.event;
                window.event = {
                    target: button
                };

                try {
                    // Call the global addPujaKitToCart function with correct parameters
                    window.addPujaKitToCart({{ $pujaKit->id }}, window.currentKitQuantity);
                } catch (error) {
                    console.error('Error adding puja kit to cart:', error);
                    if (typeof window.showToast === 'function') {
                        window.showToast('Failed to add puja kit to cart', 'error');
                    } else {
                        alert('Failed to add puja kit to cart. Please try again.');
                    }
                } finally {
                    window.event = originalEvent;
                }
            } else {
                console.error('Global addPujaKitToCart function not found');
                alert('Cart system not available. Please refresh the page and try again.');
            }
        };

        // Initialize Lucide icons
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize button states
            if (typeof window.updateKitQuantityButtons === 'function') {
                window.updateKitQuantityButtons();
            }

            // Initialize Lucide icons if available
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        });
    </script>
@endsection
