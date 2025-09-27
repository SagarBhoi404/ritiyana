@extends('layouts.app')

@section('content')
    <style>
        /* Line clamping utility */
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        /* Banner Swiper Styles */
        .banner-swiper {
            position: relative;
            padding-bottom: 3rem;
        }

        .banner-image {
            max-height: 232px;
            /* Default height for desktop */
        }

        .banner-swiper .swiper-pagination {
            position: absolute;
            bottom: 1rem !important;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 8px;
            width: auto !important;
        }

        .banner-swiper .swiper-pagination-bullet {
            width: 12px !important;
            height: 12px !important;
            background-color: #9CA3AF !important;
            /* Gray-400 for inactive dots */
            opacity: 1 !important;
            border-radius: 50%;
            margin: 0 !important;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .banner-swiper .swiper-pagination-bullet-active {
            background-color: #4C007D !important;
            /* Your vibrant-pink for active dot */
            transform: scale(1.2);
            box-shadow: 0 2px 6px rgba(76, 0, 125, 0.3);
        }

        /* Mobile specific adjustments */
        @media (max-width: 640px) {
            .banner-image {
                max-height: 280px !important;
                /* Increased height on mobile */
            }

            .banner-swiper .swiper-pagination-bullet {
                width: 8px !important;
                height: 8px !important;
                /* Smaller dots on mobile */
                background-color: #9CA3AF !important;
                /* Same gray for mobile */
            }

            .banner-swiper .swiper-pagination-bullet-active {
                background-color: #4C007D !important;
                /* Same purple for mobile */
                transform: scale(1.3);
            }

            .banner-swiper .swiper-pagination {
                gap: 4px;
                /* Tighter spacing between smaller dots */
            }
        }

        /* Tablet adjustments */
        @media (min-width: 641px) and (max-width: 1024px) {
            .banner-image {
                max-height: 250px;
            }
        }
    </style>


    <!-- Top Banner Slider -->
    {{-- <section class="max-w-7xl mx-auto px-4 pt-4">
        <div class="banner-swiper relative pb-12">
            <div class="swiper-wrapper">
                <div class="swiper-slide">
                    <img src="{{ asset('images/banner1.png') }}" alt="Banner 1"
                        class="w-full h-auto rounded-2xl shadow-sm object-cover banner-image">
                </div>
                <div class="swiper-slide">
                    <img src="{{ asset('images/banner2.png') }}" alt="Banner 2"
                        class="w-full h-auto rounded-2xl shadow-sm object-cover banner-image">
                </div>
                <div class="swiper-slide">
                    <img src="{{ asset('images/banner3.png') }}" alt="Banner 3"
                        class="w-full h-auto rounded-2xl shadow-sm object-cover banner-image">
                </div>
            </div>

            <!-- Pagination Dots -->
            <div class="swiper-pagination"></div>
        </div>
    </section> --}}


    <!-- Top Banner Slider -->
    <section class="max-w-7xl mx-auto px-4 pt-4">
        <div class="banner-swiper relative pb-12">
            <div class="swiper-wrapper">
                @forelse($banners as $banner)
                    <div class="swiper-slide">
                        @if ($banner->hasLink())
                            <a href="{{ $banner->link_url }}" target="_blank" rel="noopener noreferrer">
                                <img src="{{ $banner->image_url }}" alt="{{ $banner->alt_text }}"
                                    class="w-full h-auto rounded-2xl shadow-sm object-cover banner-image">
                            </a>
                        @else
                            <img src="{{ $banner->image_url }}" alt="{{ $banner->alt_text }}"
                                class="w-full h-auto rounded-2xl shadow-sm object-cover banner-image">
                        @endif
                    </div>
                @empty
                    <div class="swiper-slide">
                        <div class="w-full h-64 bg-gray-200 rounded-2xl shadow-sm flex items-center justify-center">
                            <p class="text-gray-500">No banners available</p>
                        </div>
                    </div>
                @endforelse
            </div>

            <!-- Pagination Dots -->
            <div class="swiper-pagination"></div>
        </div>
    </section>



    <!-- Hero Section -->
    {{-- <section class="max-w-7xl mx-auto px-4 py-8">
        <div class="rounded-2xl p-8 bg-gradient-to-r from-green-400 to-green-500 text-white relative overflow-hidden">
            <div class="relative z-10">
                <h1 class="text-3xl md:text-5xl font-bold mb-4">
                    Sacred Essentials<br>
                    <span class="text-green-200">Delivered Fast</span>
                </h1>
                <p class="text-xl mb-6 text-green-100">
                    Get authentic puja items, fresh flowers & sacred accessories delivered in 10 minutes
                </p>
                <a href="{{ route('all-kits') }}"
                    class="inline-block bg-white text-green-600 font-semibold px-8 py-3 rounded-lg hover:bg-green-50 transition-colors">
                    Shop Now
                </a>
            </div>
        </div>
    </section> --}}

    <!-- Categories Section -->
    {{-- <section class="max-w-7xl mx-auto px-4 py-12">
        <h2 class="text-2xl font-bold mb-8">Shop by Category</h2>
        <div class="category-scroll flex gap-3 sm:gap-4 md:gap-6 overflow-x-auto pb-2">
            @foreach ([['name' => 'Daily Puja', 'image' => 'daily-puja-kit.jpg', 'link' => '/all-kits?filter=daily'], ['name' => 'Festival', 'image' => 'festival-kit.jpg', 'link' => '/all-kits?filter=festival'], ['name' => 'Custom', 'image' => 'custom-kit.jpg', 'link' => '/all-kits?filter=custom'], ['name' => 'Eco-friendly', 'image' => 'eco-friendly.jpg', 'link' => '/all-kits?filter=eco'], ['name' => 'Accessories', 'image' => 'accessories.jpg', 'link' => '/all-kits?filter=accessories'], ['name' => 'Subscription', 'image' => 'subscription.jpg', 'link' => '/all-kits?filter=subscription']] as $category)
                <a href="{{ $category['link'] }}" class="flex-shrink-0 group">
                    <div class="text-center cursor-pointer w-20 sm:w-24 md:w-32">
                        <!-- Circular Image -->
                        <div
                            class="w-16 h-16 sm:w-20 sm:h-20 md:w-28 md:h-28 mx-auto mb-2 md:mb-3 rounded-full border-2 border-gray-200 group-hover:border-vibrant-pink transition-all duration-300 overflow-hidden bg-white shadow-sm group-hover:shadow-md">
                            <img src="{{ asset('images/' . $category['image']) }}" alt="{{ $category['name'] }}"
                                class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                        </div>
                        <!-- Category Name -->
                        <h3
                            class="font-medium text-xs sm:text-sm md:text-base text-gray-800 group-hover:text-vibrant-pink transition-colors leading-tight">
                            {{ $category['name'] }}
                        </h3>
                    </div>
                </a>
            @endforeach
        </div>
    </section> --}}

    <!-- Categories Section -->
    <section class="max-w-7xl mx-auto px-4 py-12">
        <h2 class="text-2xl font-bold mb-8">Shop by Category</h2>
        <div class="category-scroll flex gap-3 sm:gap-4 md:gap-6 overflow-x-auto pb-2">
            @forelse ($categories as $category)
                <a href="{{ route('category.show', $category->slug) }}" class="flex-shrink-0 group">
                    <div class="text-center cursor-pointer w-20 sm:w-24 md:w-32">
                        <!-- Circular Image -->
                        <div
                            class="w-16 h-16 sm:w-20 sm:h-20 md:w-28 md:h-28 mx-auto mb-2 md:mb-3 rounded-full border-2 border-gray-200 group-hover:border-vibrant-pink transition-all duration-300 overflow-hidden bg-white shadow-sm group-hover:shadow-md">
                            <img src="{{ $category->getImageUrlAttribute() }}" alt="{{ $category->name }}"
                                class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300"
                                >
                        </div>

                        <!-- Category Name -->
                        <h3
                            class="font-medium text-xs sm:text-sm md:text-base text-gray-800 group-hover:text-vibrant-pink transition-colors leading-tight">
                            {{ $category->name }}
                        </h3>

                        <!-- Product Count (Optional) -->
                        @if ($category->products_count > 0)
                            <p class="text-xs text-gray-500 mt-1">
                                {{ $category->products_count }} {{ Str::plural('item', $category->products_count) }}
                            </p>
                        @endif
                    </div>
                </a>
            @empty
             
            @endforelse
        </div>

        <!-- Subcategories (Optional - only show for first category as example) -->
        @if ($categories->isNotEmpty() && $categories->first()->children->count() > 0)
            <div class="mt-8">
                <h3 class="text-lg font-semibold mb-4 text-gray-700">
                    {{ $categories->first()->name }} Subcategories
                </h3>
                <div class="flex flex-wrap gap-2">
                    @foreach ($categories->first()->children->take(5) as $subcategory)
                        <a href="{{ route('category.show', $subcategory->slug) }}"
                            class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gray-100 text-gray-800 hover:bg-vibrant-pink hover:text-white transition-colors">
                            {{ $subcategory->name }}
                            @if ($subcategory->products_count > 0)
                                <span class="ml-1 text-xs">({{ $subcategory->products_count }})</span>
                            @endif
                        </a>
                    @endforeach
                </div>
            </div>
        @endif
    </section>


    <!-- Secondary Banners -->
    <section class="max-w-7xl mx-auto px-4 py-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Ganesh Chaturthi Banner -->
            <div class="relative bg-orange-100 rounded-2xl overflow-hidden hover:shadow-lg transition-shadow">
                <a href="/all-kits?filter=ganesh" class="block">
                    <img src="{{ asset('images/diwali-small.jpg') }}" alt="Ganesh Chaturthi Collection"
                        class="w-full h-auto object-cover" style="aspect-ratio: 352/124; height: 124px;">
                    <!-- Overlay Content -->
                    {{-- <div class="absolute inset-0 bg-black bg-opacity-20 flex items-center justify-center">
                    <div class="text-center text-white p-4">
                        <h3 class="text-lg font-semibold mb-2">Ganesh Chaturthi</h3>
                        <p class="text-sm mb-3">Complete essentials for celebration</p>
                        <span class="bg-white text-orange-600 font-medium px-4 py-1 rounded-full text-sm hover:bg-orange-50 transition-colors inline-block">
                            Shop Collection
                        </span>
                    </div>
                </div> --}}
                </a>
            </div>

            <!-- Navratri Banner -->
            <div class="relative bg-purple-100 rounded-2xl overflow-hidden hover:shadow-lg transition-shadow">
                <a href="/all-kits?filter=navratri" class="block">
                    <img src="{{ asset('images/78248595_18867.jpg') }}" alt="Navratri Special Collection"
                        class="w-full h-auto object-cover" style="aspect-ratio: 352/124; height: 124px;">
                    <!-- Overlay Content -->
                    {{-- <div class="absolute inset-0 bg-black bg-opacity-20 flex items-center justify-center">
                    <div class="text-center text-white p-4">
                        <h3 class="text-lg font-semibold mb-2">Navratri Special</h3>
                        <p class="text-sm mb-3">Sacred items for 9 days of celebration</p>
                        <span class="bg-white text-purple-600 font-medium px-4 py-1 rounded-full text-sm hover:bg-purple-50 transition-colors inline-block">
                            Shop Collection
                        </span>
                    </div>
                </div> --}}
                </a>
            </div>

            <!-- Diwali Banner -->
            <div class="relative bg-yellow-100 rounded-2xl overflow-hidden hover:shadow-lg transition-shadow">
                <a href="/all-kits?filter=diwali" class="block">
                    <img src="{{ asset('images/SL-092921-45900-40.jpg') }}" alt="Diwali Collection"
                        class="w-full h-auto object-cover" style="aspect-ratio: 352/124; height: 124px;">
                    <!-- Overlay Content -->
                    {{-- <div class="absolute inset-0 bg-black bg-opacity-20 flex items-center justify-center">
                    <div class="text-center text-white p-4">
                        <h3 class="text-lg font-semibold mb-2">Diwali Collection</h3>
                        <p class="text-sm mb-3">Premium Diwali collection</p>
                        <span class="bg-white text-yellow-600 font-medium px-4 py-1 rounded-full text-sm hover:bg-yellow-50 transition-colors inline-block">
                            Shop Collection
                        </span>
                    </div>
                </div> --}}
                </a>
            </div>
        </div>
    </section>

    <!-- Original Text-Only Secondary Banners (Alternative/Fallback) -->
    {{-- <section class="max-w-7xl mx-auto px-4 py-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-orange-100 rounded-2xl p-6 text-center hover:shadow-md transition-shadow">
                <h3 class="text-lg font-semibold mb-2 text-orange-800">Ganesh Chaturthi</h3>
                <p class="text-orange-700 mb-4">Complete essentials for Ganesh Chaturthi celebration</p>
                <a href="/all-kits?filter=ganesh" class="text-orange-600 font-medium hover:underline">Shop Collection</a>
            </div>

            <div class="bg-purple-100 rounded-2xl p-6 text-center hover:shadow-md transition-shadow">
                <h3 class="text-lg font-semibold mb-2 text-purple-800">Navratri Special</h3>
                <p class="text-purple-700 mb-4">Sacred items for 9 days of divine celebration</p>
                <a href="/all-kits?filter=navratri" class="text-purple-600 font-medium hover:underline">Shop Collection</a>
            </div>

            <div class="bg-yellow-100 rounded-2xl p-6 text-center hover:shadow-md transition-shadow">
                <h3 class="text-lg font-semibold mb-2 text-yellow-800">Diwali Collection</h3>
                <p class="text-yellow-700 mb-4">Illuminate your home with our premium Diwali collection</p>
                <a href="/all-kits?filter=diwali" class="text-yellow-600 font-medium hover:underline">Shop Collection</a>
            </div>
        </div>
    </section> --}}


    <!-- Products Grid -->
    {{-- <section class="w-full bg-[#FFFBF0] py-12">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex items-center justify-between mb-8">
                <h2 class="text-2xl font-bold">Popular Products</h2>
                <a href="{{ route('all-kits') }}" class="text-vibrant-pink font-medium hover:underline">View All</a>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4 lg:gap-6">
                @foreach ([
            ['id' => '1', 'name' => 'Complete Daily Puja Kit', 'price' => 299, 'originalPrice' => 399, 'image' => 'daily-puja-kit.jpg', 'description' => 'Everything you need for daily worship', 'discount' => '25% OFF'],
            ['id' => '2', 'name' => 'Ganesh Chaturthi Special Kit', 'price' => 599, 'originalPrice' => 799, 'image' => 'festival-kit.jpg', 'description' => 'Authentic items for Ganesh Puja', 'discount' => '25% OFF'],
            ['id' => '3', 'name' => 'Eco-Friendly Puja Set', 'price' => 399, 'originalPrice' => 499, 'image' => 'eco-friendly.jpg', 'description' => 'Sustainable bamboo & brass items', 'discount' => '20% OFF'],
            ['id' => '4', 'name' => 'Premium Brass Accessories', 'price' => 899, 'originalPrice' => 1199, 'image' => 'accessories.jpg', 'description' => 'Hand-crafted brass puja items', 'discount' => '25% OFF'],
            ['id' => '5', 'name' => 'Custom Puja Kit', 'price' => 799, 'originalPrice' => 999, 'image' => 'custom-kit.jpg', 'description' => 'Personalized religious items', 'discount' => '20% OFF'],
        ] as $product)
                    <div class="group bg-white rounded-2xl border border-gray-200 overflow-hidden hover:shadow-lg hover:border-gray-300 transition-all duration-300 cursor-pointer flex flex-col h-full"
                        onclick="window.location.href='/product/{{ $product['id'] }}'">

                        <!-- Image Section -->
                        <div class="relative overflow-hidden">
                            <img src="{{ asset('images/' . $product['image']) }}" alt="{{ $product['name'] }}"
                                class="w-full h-40 sm:h-48 object-cover group-hover:scale-105 transition-transform duration-300">
                            @if ($product['discount'])
                                <div
                                    class="absolute top-2 left-2 bg-red-500 text-white text-xs font-semibold px-2 py-1 rounded-full shadow-sm">
                                    {{ $product['discount'] }}
                                </div>
                            @endif
                        </div>

                        <!-- Content Section -->
                        <div class="p-3 lg:p-4 flex flex-col flex-1">
                            <!-- Product Title -->
                            <h3
                                class="font-semibold mb-2 text-xs sm:text-sm lg:text-base text-gray-900 line-clamp-2 min-h-[2rem] sm:min-h-[2.5rem] lg:min-h-[3rem] leading-tight">
                                {{ $product['name'] }}
                            </h3>

                            <!-- Description -->
                            <p
                                class="text-xs text-gray-600 mb-3 line-clamp-2 min-h-[1.5rem] sm:min-h-[2rem] leading-relaxed">
                                {{ $product['description'] ?? 'Premium quality puja items for your spiritual needs' }}
                            </p>

                            <!-- Spacer -->
                            <div class="flex-1"></div>

                            <!-- Price Section -->
                            <div class="flex items-center gap-1 sm:gap-2 mb-3">
                                <span
                                    class="text-sm sm:text-base lg:text-lg font-bold text-gray-900">₹{{ number_format($product['price']) }}</span>
                                @if ($product['originalPrice'])
                                    <span
                                        class="text-xs lg:text-sm text-gray-500 line-through">₹{{ number_format($product['originalPrice']) }}</span>
                                @endif
                            </div>

                            <!-- Add to Cart Button -->
                            <button onclick="event.stopPropagation(); addToCart({{ json_encode($product) }})"
                                class="w-full bg-vibrant-pink hover:bg-vibrant-pink-dark text-white font-medium py-2 px-2 sm:px-3 rounded-lg transition-colors text-xs sm:text-sm group-hover:shadow-md">
                                <i data-lucide="shopping-cart" class="w-3 h-3 sm:w-4 sm:h-4 inline mr-1 sm:mr-2"></i>
                                Add to Cart
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section> --}}





    <!-- Products Grid -->
    <section class="w-full bg-[#FFFBF0] py-12">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex items-center justify-between mb-8">
                <h2 class="text-2xl font-bold">Featured Products</h2>
                <a href="{{ route('puja-kits.index') }}" class="text-vibrant-pink font-medium hover:underline">View All</a>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4 lg:gap-6">
                @forelse ($products as $product)
                    <div class="group bg-white rounded-2xl border border-gray-200 overflow-hidden hover:shadow-lg hover:border-gray-300 transition-all duration-300 cursor-pointer flex flex-col h-full"
                        onclick="window.location.href='{{ route('product.show', $product->slug ?? $product->id) }}'">

                        <!-- Image Section -->
                        <div class="relative overflow-hidden">
                            <img src="{{ $product->featured_image_url }}" alt="{{ $product->name }}"
                                class="w-full h-40 sm:h-48 object-cover group-hover:scale-105 transition-transform duration-300">

                            @if ($product->discount_percentage > 0)
                                <div
                                    class="absolute top-2 left-2 bg-red-500 text-white text-xs font-semibold px-2 py-1 rounded-full shadow-sm">
                                    {{ $product->discount_percentage }}% OFF
                                </div>
                            @endif

                            @if ($product->is_vendor_product)
                                <div
                                    class="absolute top-2 right-2 bg-blue-500 text-white text-xs font-semibold px-2 py-1 rounded-full shadow-sm">
                                    Vendor
                                </div>
                            @endif
                        </div>

                        <!-- Content Section -->
                        <div class="p-3 lg:p-4 flex flex-col flex-1">
                            <!-- Product Title -->
                            <h3
                                class="font-semibold mb-2 text-xs sm:text-sm lg:text-base text-gray-900 line-clamp-2 min-h-[2rem] sm:min-h-[2.5rem] lg:min-h-[3rem] leading-tight">
                                {{ $product->name }}
                            </h3>

                            <!-- Description -->
                            <p
                                class="text-xs text-gray-600 mb-3 line-clamp-2 min-h-[1.5rem] sm:min-h-[2rem] leading-relaxed">
                                {{ Str::limit($product->description ?? 'Premium quality puja items for your spiritual needs', 100) }}
                            </p>

                            <!-- Category -->
                            @if ($product->categories->count() > 0)
                                <div class="mb-2">
                                    <span class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded-full">
                                        {{ $product->categories->first()->name }}
                                    </span>
                                </div>
                            @endif

                            <!-- Spacer -->
                            <div class="flex-1"></div>

                            <!-- Price Section -->
                            <div class="flex items-center gap-1 sm:gap-2 mb-3">
                                <span class="text-sm sm:text-base lg:text-lg font-bold text-gray-900">
                                    ₹{{ number_format($product->final_price, 2) }}
                                </span>
                                @if ($product->sale_price && $product->price > $product->sale_price)
                                    <span class="text-xs lg:text-sm text-gray-500 line-through">
                                        ₹{{ number_format($product->price, 2) }}
                                    </span>
                                @endif
                            </div>

                            <!-- Stock Status -->
                            @if ($product->manage_stock)
                                <div class="text-xs mb-2">
                                    @if ($product->stock_quantity > 10)
                                        <span class="text-green-600">In Stock</span>
                                    @elseif ($product->stock_quantity > 0)
                                        <span class="text-orange-600">Low Stock ({{ $product->stock_quantity }})</span>
                                    @else
                                        <span class="text-red-600">Out of Stock</span>
                                    @endif
                                </div>
                            @endif

                            <!-- Add to Cart Button -->
                            <button onclick="event.stopPropagation(); addToCart({{ $product->id }}, 1)"
                                class="w-full bg-vibrant-pink hover:bg-vibrant-pink-dark text-white font-medium py-2 px-2 sm:px-3 rounded-lg transition-colors text-xs sm:text-sm group-hover:shadow-md {{ $product->stock_quantity == 0 ? 'opacity-50 cursor-not-allowed' : '' }}"
                                {{ $product->stock_quantity == 0 ? 'disabled' : '' }}>
                                <i data-lucide="shopping-cart" class="w-3 h-3 sm:w-4 sm:h-4 inline mr-1 sm:mr-2"></i>
                                {{ $product->stock_quantity == 0 ? 'Out of Stock' : 'Add to Cart' }}
                            </button>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-12">
                        <div class="text-gray-500 mb-4">
                            <i data-lucide="package" class="w-16 h-16 mx-auto mb-4"></i>
                            <p class="text-lg font-medium">No products available</p>
                            <p class="text-sm">Check back later for new arrivals!</p>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    @if ($pujaKits->count() > 0)
        <!-- Puja Kits Section -->
        <section class="w-full bg-white py-12">
            <div class="max-w-7xl mx-auto px-4">
                <div class="flex items-center justify-between mb-8">
                    <h2 class="text-2xl font-bold">Popular Puja Kits</h2>
                    <a href="{{ route('puja-kits.index') }}" class="text-vibrant-pink font-medium hover:underline">View
                        All Kits</a>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-6">
                    @foreach ($pujaKits as $kit)
                        <div class="group bg-white rounded-2xl border border-gray-200 overflow-hidden hover:shadow-lg hover:border-gray-300 transition-all duration-300 cursor-pointer"
                            onclick="window.location.href='{{ route('puja-kits.show', $kit->slug) }}'">

                            <!-- Kit Image -->
                            <div class="relative overflow-hidden">
                                <img src="{{ $kit->image ? asset('storage/' . $kit->image) : asset('images/default-kit.png') }}"
                                    alt="{{ $kit->kit_name }}"
                                    class="w-full h-48 object-cover group-hover:scale-105 transition-transform duration-300">

                                @if ($kit->discount_percentage > 0)
                                    <div
                                        class="absolute top-2 left-2 bg-red-500 text-white text-xs font-semibold px-2 py-1 rounded-full">
                                        {{ $kit->discount_percentage }}% OFF
                                    </div>
                                @endif
                            </div>

                            <!-- Kit Content -->
                            <div class="p-4">
                                <h3 class="font-semibold text-lg text-gray-900 mb-2 line-clamp-2">
                                    {{ $kit->kit_name }}
                                </h3>

                                <p class="text-sm text-gray-600 mb-3 line-clamp-2">
                                    {{ Str::limit($kit->description, 80) }}
                                </p>

                                <!-- Puja Names -->
                                @if ($kit->pujas->count() > 0)
                                    <p class="text-xs text-gray-500 mb-3">
                                        <strong>For:</strong> {{ $kit->puja_names }}
                                    </p>
                                @endif

                                <!-- Products count -->
                                <p class="text-xs text-gray-500 mb-3">
                                    {{ $kit->products->count() }} items included
                                </p>

                                <!-- Price -->
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        <span class="text-lg font-bold text-gray-900">
                                            ₹{{ number_format($kit->total_price, 2) }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

@endsection
