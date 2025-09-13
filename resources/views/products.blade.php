@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto px-4 py-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold mb-4">All Products</h1>
            <p class="text-gray-600">Discover our complete collection of authentic puja items and kits</p>
        </div>

        <!-- Mobile Filter Toggle Button -->
        <div class="lg:hidden mb-6">
            <button onclick="toggleMobileFilters()"
                class="w-full bg-white border border-gray-300 rounded-lg p-3 flex items-center justify-between">
                <span class="flex items-center gap-2">
                    <i data-lucide="filter" class="w-5 h-5"></i>
                    <span class="font-medium">Filters & Categories</span>
                </span>
                <i data-lucide="chevron-down" class="w-5 h-5 transform transition-transform" id="filter-chevron"></i>
            </button>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            <!-- Left Sidebar - Categories (Mobile Collapsible) -->
            <div class="lg:col-span-1">
                <div id="mobile-filters"
                    class="lg:block hidden bg-white rounded-2xl border border-gray-200 p-6 lg:sticky lg:top-24">
                    
                    <!-- Search Filter -->
                    <div class="mb-6">
                        <h3 class="font-semibold mb-4 flex items-center gap-2">
                            <i data-lucide="search" class="w-4 h-4"></i>
                            Search Products
                        </h3>
                        <form method="GET" action="{{ route('products.index') }}">
                            @foreach(request()->except('search', 'page') as $key => $value)
                                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                            @endforeach
                            <div class="relative">
                                <input type="text" 
                                       name="search" 
                                       value="{{ request('search') }}"
                                       placeholder="Search products..."
                                       class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-vibrant-pink text-sm">
                                <i data-lucide="search" class="w-4 h-4 absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                            </div>
                            <button type="submit" class="w-full mt-2 bg-vibrant-pink text-white py-2 rounded-lg text-sm hover:bg-vibrant-pink-dark transition-colors">
                                Search
                            </button>
                        </form>
                    </div>

                    <!-- Categories Section -->
                    <div class="mb-8 border-t border-gray-200 pt-6">
                        <h2 class="text-xl font-semibold mb-6 flex items-center gap-2">
                            <i data-lucide="grid-3x3" class="w-5 h-5"></i>
                            Categories
                        </h2>

                        <div class="space-y-2">
                            <a href="{{ route('products.index') }}"
                               class="block p-3 rounded-lg font-medium transition-colors {{ !request('category') ? 'bg-vibrant-pink text-white' : 'bg-gray-100 hover:bg-gray-200 text-gray-700' }}">
                                <div class="flex items-center justify-between">
                                    <span class="text-sm lg:text-base">All Products</span>
                                    <span class="text-xs {{ !request('category') ? 'bg-white bg-opacity-20' : 'bg-gray-200' }} px-2 py-1 rounded">
                                        {{ $totalProducts }}
                                    </span>
                                </div>
                            </a>

                            @foreach ($categories as $category)
                                <a href="{{ route('products.index', ['category' => $category->slug] + request()->except('category', 'page')) }}"
                                   class="block p-3 rounded-lg font-medium transition-colors {{ request('category') == $category->slug ? 'bg-vibrant-pink text-white' : 'bg-gray-100 hover:bg-gray-200 text-gray-700' }}">
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm lg:text-base">{{ $category->name }}</span>
                                        <span class="text-xs {{ request('category') == $category->slug ? 'bg-white bg-opacity-20' : 'bg-gray-200' }} px-2 py-1 rounded">
                                            {{ $category->products_count }}
                                        </span>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>

                    <!-- Price Filter -->
                    <div class="mb-6 pt-6 border-t border-gray-200">
                        <h3 class="font-semibold mb-4 flex items-center gap-2">
                            <i data-lucide="dollar-sign" class="w-4 h-4"></i>
                            Price Range
                        </h3>
                        <form method="GET" action="{{ route('products.index') }}">
                            @foreach(request()->except('min_price', 'max_price', 'page') as $key => $value)
                                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                            @endforeach
                            
                            <div class="space-y-3">
                                <div>
                                    <label class="text-sm text-gray-600">Min Price</label>
                                    <input type="number" 
                                           name="min_price" 
                                           value="{{ request('min_price') }}"
                                           placeholder="Min"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-vibrant-pink">
                                </div>
                                <div>
                                    <label class="text-sm text-gray-600">Max Price</label>
                                    <input type="number" 
                                           name="max_price" 
                                           value="{{ request('max_price') }}"
                                           placeholder="Max"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-vibrant-pink">
                                </div>
                                <button type="submit" class="w-full bg-vibrant-pink text-white py-2 rounded-lg text-sm hover:bg-vibrant-pink-dark transition-colors">
                                    Apply Price Filter
                                </button>
                            </div>
                        </form>

                        <!-- Quick Price Filters -->
                        <div class="mt-4 space-y-2">
                            <a href="{{ route('products.index', ['min_price' => 0, 'max_price' => 300] + request()->except('min_price', 'max_price', 'page')) }}"
                               class="block text-sm text-gray-600 hover:text-vibrant-pink">Under ₹300</a>
                            <a href="{{ route('products.index', ['min_price' => 300, 'max_price' => 600] + request()->except('min_price', 'max_price', 'page')) }}"
                               class="block text-sm text-gray-600 hover:text-vibrant-pink">₹300 - ₹600</a>
                            <a href="{{ route('products.index', ['min_price' => 600, 'max_price' => 1000] + request()->except('min_price', 'max_price', 'page')) }}"
                               class="block text-sm text-gray-600 hover:text-vibrant-pink">₹600 - ₹1000</a>
                            <a href="{{ route('products.index', ['min_price' => 1000] + request()->except('min_price', 'max_price', 'page')) }}"
                               class="block text-sm text-gray-600 hover:text-vibrant-pink">Above ₹1000</a>
                        </div>
                    </div>

                    <!-- Clear Filters -->
                    <a href="{{ route('products.index') }}"
                       class="block w-full text-center text-vibrant-pink hover:bg-vibrant-pink hover:text-white border border-vibrant-pink font-medium py-3 px-4 rounded-lg transition-colors">
                        <i data-lucide="refresh-ccw" class="w-4 h-4 inline mr-2"></i>
                        Clear All Filters
                    </a>
                </div>
            </div>

            <!-- Right Content - Products -->
            <div class="lg:col-span-3">
                <!-- Products Count and Sort -->
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
                    <p class="text-gray-600">
                        Showing <span class="font-medium">{{ $products->count() }}</span> of 
                        <span class="font-medium">{{ $products->total() }}</span> products
                        @if(request('search'))
                            for "<strong>{{ request('search') }}</strong>"
                        @endif
                        @if(request('category'))
                            in <strong>{{ $categories->where('slug', request('category'))->first()?->name ?? 'Category' }}</strong>
                        @endif
                    </p>

                    <!-- Sort Options -->
                    <div class="flex items-center gap-2">
                        <label class="text-sm text-gray-600">Sort by:</label>
                        <form method="GET" action="{{ route('products.index') }}">
                            @foreach(request()->except('sort', 'page') as $key => $value)
                                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                            @endforeach
                            <select name="sort" 
                                    onchange="this.form.submit()"
                                    class="px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-vibrant-pink text-sm">
                                <option value="featured" {{ request('sort') == 'featured' ? 'selected' : '' }}>Featured</option>
                                <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Price: Low to High</option>
                                <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Price: High to Low</option>
                                <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Name: A to Z</option>
                                <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Newest First</option>
                                <option value="discount" {{ request('sort') == 'discount' ? 'selected' : '' }}>Highest Discount</option>
                            </select>
                        </form>
                    </div>
                </div>

                <!-- Active Filters Display -->
                @if(request()->hasAny(['search', 'category', 'min_price', 'max_price']))
                    <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                        <div class="flex flex-wrap items-center gap-2">
                            <span class="text-sm font-medium text-gray-600">Active filters:</span>
                            
                            @if(request('search'))
                                <span class="inline-flex items-center gap-1 px-3 py-1 bg-vibrant-pink text-white text-sm rounded-full">
                                    Search: "{{ request('search') }}"
                                    <a href="{{ route('products.index', request()->except('search', 'page')) }}" class="text-white hover:text-gray-200">
                                        <i data-lucide="x" class="w-3 h-3"></i>
                                    </a>
                                </span>
                            @endif

                            @if(request('category'))
                                <span class="inline-flex items-center gap-1 px-3 py-1 bg-blue-500 text-white text-sm rounded-full">
                                    {{ $categories->where('slug', request('category'))->first()?->name ?? 'Category' }}
                                    <a href="{{ route('products.index', request()->except('category', 'page')) }}" class="text-white hover:text-gray-200">
                                        <i data-lucide="x" class="w-3 h-3"></i>
                                    </a>
                                </span>
                            @endif

                            @if(request('min_price') || request('max_price'))
                                <span class="inline-flex items-center gap-1 px-3 py-1 bg-green-500 text-white text-sm rounded-full">
                                    Price: ₹{{ request('min_price', '0') }} - ₹{{ request('max_price', '∞') }}
                                    <a href="{{ route('products.index', request()->except(['min_price', 'max_price'], 'page')) }}" class="text-white hover:text-gray-200">
                                        <i data-lucide="x" class="w-3 h-3"></i>
                                    </a>
                                </span>
                            @endif
                        </div>
                    </div>
                @endif

                <!-- Products Grid -->
                <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-3 gap-4 lg:gap-6">
                    @forelse ($products as $product)
                        <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden hover:shadow-md transition-shadow cursor-pointer"
                             onclick="window.location.href='{{ route('product.show', $product->slug) }}'">
                            
                            <div class="relative">
                                <img src="{{ $product->featured_image_url }}" 
                                     alt="{{ $product->name }}"
                                     class="w-full h-48 object-cover">
                                
                                @if ($product->discount_percentage > 0)
                                    <div class="absolute top-2 left-2 bg-red-500 text-white text-xs font-medium px-2 py-1 rounded">
                                        {{ $product->discount_percentage }}% OFF
                                    </div>
                                @endif

                                @if ($product->is_vendor_product)
                                    <div class="absolute top-2 right-2 bg-blue-500 text-white text-xs font-medium px-2 py-1 rounded">
                                        Vendor
                                    </div>
                                @endif

                                @if ($product->manage_stock && $product->stock_quantity <= 5 && $product->stock_quantity > 0)
                                    <div class="absolute bottom-2 left-2 bg-orange-500 text-white text-xs font-medium px-2 py-1 rounded">
                                        Only {{ $product->stock_quantity }} left
                                    </div>
                                @endif
                            </div>
                            
                            <div class="p-4">
                                <h3 class="font-medium mb-2 line-clamp-2">{{ $product->name }}</h3>
                                <p class="text-sm text-gray-600 mb-3 line-clamp-2">
                                    {{ Str::limit($product->description, 80) }}
                                </p>
                                
                                <!-- Categories -->
                                @if ($product->categories->count() > 0)
                                    <div class="mb-2">
                                        <span class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded-full">
                                            {{ $product->categories->first()->name }}
                                        </span>
                                    </div>
                                @endif

                                <div class="flex items-center gap-2 mb-3">
                                    <span class="text-lg font-semibold text-vibrant-pink">₹{{ number_format($product->final_price, 2) }}</span>
                                    @if ($product->sale_price && $product->price > $product->sale_price)
                                        <span class="text-sm text-gray-500 line-through">₹{{ number_format($product->price, 2) }}</span>
                                    @endif
                                </div>
                                
                                <button onclick="event.stopPropagation(); addToCart({{ $product->id }})"
                                        class="w-full bg-vibrant-pink hover:bg-vibrant-pink-dark text-white font-medium py-2 rounded-lg transition-colors {{ $product->stock_quantity == 0 ? 'opacity-50 cursor-not-allowed' : '' }}"
                                        {{ $product->stock_quantity == 0 ? 'disabled' : '' }}>
                                    {{ $product->stock_quantity == 0 ? 'Out of Stock' : 'Add to Cart' }}
                                </button>
                            </div>
                        </div>
                    @empty
                        <!-- No Products Message -->
                        <div class="col-span-full text-center py-12">
                            <div class="text-gray-400 mb-4">
                                <i data-lucide="package" class="w-16 h-16 mx-auto"></i>
                            </div>
                            <h3 class="text-lg font-medium mb-2">No products found</h3>
                            <p class="text-gray-500 mb-4">
                                @if(request()->hasAny(['search', 'category', 'min_price', 'max_price']))
                                    Try adjusting your filters or 
                                    <a href="{{ route('products.index') }}" class="text-vibrant-pink hover:underline">browse all products</a>
                                @else
                                    No products are currently available.
                                @endif
                            </p>
                        </div>
                    @endforelse
                </div>

                <!-- Pagination -->
                @if ($products->hasPages())
                    <div class="mt-8">
                        {{ $products->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script>
        // Mobile filter toggle
        function toggleMobileFilters() {
            const mobileFilters = document.getElementById('mobile-filters');
            const chevron = document.getElementById('filter-chevron');

            mobileFilters.classList.toggle('hidden');
            chevron.classList.toggle('rotate-180');

            // Re-initialize Lucide icons
            lucide.createIcons();
        }

        function addToCart(productId) {
            // Add your cart functionality here
            console.log('Adding product to cart:', productId);
            alert('Product added to cart!');
        }

        // Initialize Lucide icons on page load
        document.addEventListener('DOMContentLoaded', function() {
            lucide.createIcons();
        });
    </script>
@endsection
