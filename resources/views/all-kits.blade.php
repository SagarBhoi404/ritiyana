@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto px-4 py-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold mb-4">All Puja Kits</h1>
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
                    <!-- Categories Section -->
                    <div class="mb-8">
                        <h2 class="text-xl font-semibold mb-6 flex items-center gap-2">
                            <i data-lucide="grid-3x3" class="w-5 h-5"></i>
                            Categories
                        </h2>

                        <div class="grid grid-cols-2 lg:grid-cols-1 gap-2">
                            <button onclick="filterProducts('all')"
                                class="filter-btn text-left p-3 rounded-lg font-medium bg-vibrant-pink text-white"
                                data-filter="all">
                                <div class="flex items-center justify-between">
                                    <span class="text-sm lg:text-base">All Kits</span>
                                    <span class="text-xs bg-white bg-opacity-20 px-2 py-1 rounded">10</span>
                                </div>
                            </button>

                            <button onclick="filterProducts('daily')"
                                class="filter-btn text-left p-3 rounded-lg font-medium bg-gray-100 hover:bg-gray-200 text-gray-700"
                                data-filter="daily">
                                <div class="flex items-center justify-between">
                                    <span class="text-sm lg:text-base">Daily Kits</span>
                                    <span class="text-xs bg-gray-200 px-2 py-1 rounded">2</span>
                                </div>
                            </button>

                            <button onclick="filterProducts('festival')"
                                class="filter-btn text-left p-3 rounded-lg font-medium bg-gray-100 hover:bg-gray-200 text-gray-700"
                                data-filter="festival">
                                <div class="flex items-center justify-between">
                                    <span class="text-sm lg:text-base">Festival</span>
                                    <span class="text-xs bg-gray-200 px-2 py-1 rounded">3</span>
                                </div>
                            </button>

                            <button onclick="filterProducts('eco')"
                                class="filter-btn text-left p-3 rounded-lg font-medium bg-gray-100 hover:bg-gray-200 text-gray-700"
                                data-filter="eco">
                                <div class="flex items-center justify-between">
                                    <span class="text-sm lg:text-base">Eco-friendly</span>
                                    <span class="text-xs bg-gray-200 px-2 py-1 rounded">2</span>
                                </div>
                            </button>

                            <button onclick="filterProducts('accessories')"
                                class="filter-btn text-left p-3 rounded-lg font-medium bg-gray-100 hover:bg-gray-200 text-gray-700"
                                data-filter="accessories">
                                <div class="flex items-center justify-between">
                                    <span class="text-sm lg:text-base">Accessories</span>
                                    <span class="text-xs bg-gray-200 px-2 py-1 rounded">1</span>
                                </div>
                            </button>

                            <button onclick="filterProducts('custom')"
                                class="filter-btn text-left p-3 rounded-lg font-medium bg-gray-100 hover:bg-gray-200 text-gray-700"
                                data-filter="custom">
                                <div class="flex items-center justify-between">
                                    <span class="text-sm lg:text-base">Custom</span>
                                    <span class="text-xs bg-gray-200 px-2 py-1 rounded">1</span>
                                </div>
                            </button>
                        </div>
                    </div>

                    <!-- Price Filter -->
                    <div class="mb-6 pt-6 border-t border-gray-200">
                        <h3 class="font-semibold mb-4 flex items-center gap-2">
                            <i data-lucide="dollar-sign" class="w-4 h-4"></i>
                            Price Range
                        </h3>
                        <div class="grid grid-cols-2 lg:grid-cols-1 gap-2">
                            <label class="flex items-center gap-2 p-2 hover:bg-gray-50 rounded">
                                <input type="checkbox" class="price-filter" data-min="0" data-max="300">
                                <span class="text-sm text-gray-700">Under ₹300</span>
                            </label>
                            <label class="flex items-center gap-2 p-2 hover:bg-gray-50 rounded">
                                <input type="checkbox" class="price-filter" data-min="300" data-max="600">
                                <span class="text-sm text-gray-700">₹300 - ₹600</span>
                            </label>
                            <label class="flex items-center gap-2 p-2 hover:bg-gray-50 rounded">
                                <input type="checkbox" class="price-filter" data-min="600" data-max="1000">
                                <span class="text-sm text-gray-700">₹600 - ₹1000</span>
                            </label>
                            <label class="flex items-center gap-2 p-2 hover:bg-gray-50 rounded">
                                <input type="checkbox" class="price-filter" data-min="1000" data-max="999999">
                                <span class="text-sm text-gray-700">Above ₹1000</span>
                            </label>
                        </div>
                    </div>

                    <!-- Clear Filters -->
                    <button onclick="clearFilters()"
                        class="w-full text-vibrant-pink hover:bg-vibrant-pink hover:text-white border border-vibrant-pink font-medium py-3 px-4 rounded-lg transition-colors">
                        <i data-lucide="refresh-ccw" class="w-4 h-4 inline mr-2"></i>
                        Clear All Filters
                    </button>
                </div>
            </div>

            <!-- Right Content - Products -->
            <div class="lg:col-span-3">
                <!-- Products Count and Sort -->
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
                    <p class="text-gray-600" id="product-count">
                        Showing <span id="count-number" class="font-medium">10</span> products
                    </p>

                    <!-- Sort Options -->
                    <div class="flex items-center gap-2">
                        <label class="text-sm text-gray-600">Sort by:</label>
                        <select id="sort-select" onchange="sortProducts(this.value)"
                            class="px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-vibrant-pink text-sm">
                            <option value="default">Default</option>
                            <option value="price-low">Price: Low to High</option>
                            <option value="price-high">Price: High to Low</option>
                            <option value="name">Name: A to Z</option>
                            <option value="discount">Discount: High to Low</option>
                        </select>
                    </div>
                </div>

                <!-- Active Filters Display (Mobile) -->
                <div id="active-filters" class="lg:hidden mb-4 hidden">
                    <div class="flex flex-wrap gap-2">
                        <span class="text-sm text-gray-600">Active filters:</span>
                    </div>
                </div>

                <!-- Products Grid -->
                <div id="products-grid" class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-3 gap-4 lg:gap-6">
                    <!-- Daily Products -->
                    <div class="product-item bg-white rounded-2xl border border-gray-200 overflow-hidden hover:shadow-md transition-shadow cursor-pointer"
                        data-category="daily" data-price="299" data-discount="25"
                        onclick="window.location.href='/product/1'">
                        <div class="relative">
                            <img src="{{ asset('images/daily-puja-kit.jpg') }}" alt="Complete Daily Puja Kit"
                                class="w-full h-48 object-cover">
                            <div class="absolute top-2 left-2 bg-red-500 text-white text-xs font-medium px-2 py-1 rounded">
                                25% OFF</div>
                        </div>
                        <div class="p-4">
                            <h3 class="font-medium mb-2">Complete Daily Puja Kit</h3>
                            <p class="text-sm text-gray-600 mb-3">Everything you need for daily worship</p>
                            <div class="flex items-center gap-2 mb-3">
                                <span class="text-lg font-semibold">₹299</span>
                                <span class="text-sm text-gray-500 line-through">₹399</span>
                            </div>
                            <button
                                onclick="event.stopPropagation(); addToCart({id: '1', name: 'Complete Daily Puja Kit', price: 299, originalPrice: 399, image: '{{ asset('images/daily-puja-kit.jpg') }}', description: 'Everything you need for daily worship', discount: '25% OFF'})"
                                class="w-full bg-vibrant-pink hover:bg-vibrant-pink-dark text-white font-medium py-2 rounded-lg transition-colors">
                                Add to Cart
                            </button>
                        </div>
                    </div>

                    <div class="product-item bg-white rounded-2xl border border-gray-200 overflow-hidden hover:shadow-md transition-shadow cursor-pointer"
                        data-category="daily" data-price="199" data-discount="20"
                        onclick="window.location.href='/product/8'">
                        <div class="relative">
                            <img src="{{ asset('images/daily-puja-kit.jpg') }}" alt="Basic Starter Kit"
                                class="w-full h-48 object-cover">
                            <div class="absolute top-2 left-2 bg-red-500 text-white text-xs font-medium px-2 py-1 rounded">
                                20% OFF</div>
                        </div>
                        <div class="p-4">
                            <h3 class="font-medium mb-2">Basic Starter Kit</h3>
                            <p class="text-sm text-gray-600 mb-3">Perfect for beginners</p>
                            <div class="flex items-center gap-2 mb-3">
                                <span class="text-lg font-semibold">₹199</span>
                                <span class="text-sm text-gray-500 line-through">₹249</span>
                            </div>
                            <button
                                onclick="event.stopPropagation(); addToCart({id: '8', name: 'Basic Starter Kit', price: 199, originalPrice: 249, image: '{{ asset('images/daily-puja-kit.jpg') }}', description: 'Perfect for beginners', discount: '20% OFF'})"
                                class="w-full bg-vibrant-pink hover:bg-vibrant-pink-dark text-white font-medium py-2 rounded-lg transition-colors">
                                Add to Cart
                            </button>
                        </div>
                    </div>

                    <!-- Festival Products -->
                    <div class="product-item bg-white rounded-2xl border border-gray-200 overflow-hidden hover:shadow-md transition-shadow cursor-pointer"
                        data-category="festival" data-price="599" data-discount="25"
                        onclick="window.location.href='/product/2'">
                        <div class="relative">
                            <img src="{{ asset('images/festival-kit.jpg') }}" alt="Ganesh Chaturthi Special Kit"
                                class="w-full h-48 object-cover">
                            <div class="absolute top-2 left-2 bg-red-500 text-white text-xs font-medium px-2 py-1 rounded">
                                25% OFF</div>
                        </div>
                        <div class="p-4">
                            <h3 class="font-medium mb-2">Ganesh Chaturthi Special Kit</h3>
                            <p class="text-sm text-gray-600 mb-3">Authentic items for Ganesh Puja</p>
                            <div class="flex items-center gap-2 mb-3">
                                <span class="text-lg font-semibold">₹599</span>
                                <span class="text-sm text-gray-500 line-through">₹799</span>
                            </div>
                            <button
                                onclick="event.stopPropagation(); addToCart({id: '2', name: 'Ganesh Chaturthi Special Kit', price: 599, originalPrice: 799, image: '{{ asset('images/festival-kit.jpg') }}', description: 'Authentic items for Ganesh Puja', discount: '25% OFF'})"
                                class="w-full bg-vibrant-pink hover:bg-vibrant-pink-dark text-white font-medium py-2 rounded-lg transition-colors">
                                Add to Cart
                            </button>
                        </div>
                    </div>

                    <div class="product-item bg-white rounded-2xl border border-gray-200 overflow-hidden hover:shadow-md transition-shadow cursor-pointer"
                        data-category="festival" data-price="1299" data-discount="19"
                        onclick="window.location.href='/product/7'">
                        <div class="relative">
                            <img src="{{ asset('images/festival-kit.jpg') }}" alt="Diwali Mega Kit"
                                class="w-full h-48 object-cover">
                            <div class="absolute top-2 left-2 bg-red-500 text-white text-xs font-medium px-2 py-1 rounded">
                                19% OFF</div>
                        </div>
                        <div class="p-4">
                            <h3 class="font-medium mb-2">Diwali Mega Kit</h3>
                            <p class="text-sm text-gray-600 mb-3">Complete set for Diwali celebrations</p>
                            <div class="flex items-center gap-2 mb-3">
                                <span class="text-lg font-semibold">₹1299</span>
                                <span class="text-sm text-gray-500 line-through">₹1599</span>
                            </div>
                            <button
                                onclick="event.stopPropagation(); addToCart({id: '7', name: 'Diwali Mega Kit', price: 1299, originalPrice: 1599, image: '{{ asset('images/festival-kit.jpg') }}', description: 'Complete set for Diwali celebrations', discount: '19% OFF'})"
                                class="w-full bg-vibrant-pink hover:bg-vibrant-pink-dark text-white font-medium py-2 rounded-lg transition-colors">
                                Add to Cart
                            </button>
                        </div>
                    </div>

                    <div class="product-item bg-white rounded-2xl border border-gray-200 overflow-hidden hover:shadow-md transition-shadow cursor-pointer"
                        data-category="festival" data-price="899" data-discount="18"
                        onclick="window.location.href='/product/9'">
                        <div class="relative">
                            <img src="{{ asset('images/festival-kit.jpg') }}" alt="Navratri Special Collection"
                                class="w-full h-48 object-cover">
                            <div class="absolute top-2 left-2 bg-red-500 text-white text-xs font-medium px-2 py-1 rounded">
                                18% OFF</div>
                        </div>
                        <div class="p-4">
                            <h3 class="font-medium mb-2">Navratri Special Collection</h3>
                            <p class="text-sm text-gray-600 mb-3">9 days of divine celebration</p>
                            <div class="flex items-center gap-2 mb-3">
                                <span class="text-lg font-semibold">₹899</span>
                                <span class="text-sm text-gray-500 line-through">₹1099</span>
                            </div>
                            <button
                                onclick="event.stopPropagation(); addToCart({id: '9', name: 'Navratri Special Collection', price: 899, originalPrice: 1099, image: '{{ asset('images/festival-kit.jpg') }}', description: '9 days of divine celebration', discount: '18% OFF'})"
                                class="w-full bg-vibrant-pink hover:bg-vibrant-pink-dark text-white font-medium py-2 rounded-lg transition-colors">
                                Add to Cart
                            </button>
                        </div>
                    </div>

                    <!-- Eco Products -->
                    <div class="product-item bg-white rounded-2xl border border-gray-200 overflow-hidden hover:shadow-md transition-shadow cursor-pointer"
                        data-category="eco" data-price="399" data-discount="20"
                        onclick="window.location.href='/product/3'">
                        <div class="relative">
                            <img src="{{ asset('images/eco-friendly.jpg') }}" alt="Eco-Friendly Puja Set"
                                class="w-full h-48 object-cover">
                            <div class="absolute top-2 left-2 bg-red-500 text-white text-xs font-medium px-2 py-1 rounded">
                                20% OFF</div>
                        </div>
                        <div class="p-4">
                            <h3 class="font-medium mb-2">Eco-Friendly Puja Set</h3>
                            <p class="text-sm text-gray-600 mb-3">Sustainable bamboo & brass items</p>
                            <div class="flex items-center gap-2 mb-3">
                                <span class="text-lg font-semibold">₹399</span>
                                <span class="text-sm text-gray-500 line-through">₹499</span>
                            </div>
                            <button
                                onclick="event.stopPropagation(); addToCart({id: '3', name: 'Eco-Friendly Puja Set', price: 399, originalPrice: 499, image: '{{ asset('images/eco-friendly.jpg') }}', description: 'Sustainable bamboo & brass items', discount: '20% OFF'})"
                                class="w-full bg-vibrant-pink hover:bg-vibrant-pink-dark text-white font-medium py-2 rounded-lg transition-colors">
                                Add to Cart
                            </button>
                        </div>
                    </div>

                    <div class="product-item bg-white rounded-2xl border border-gray-200 overflow-hidden hover:shadow-md transition-shadow cursor-pointer"
                        data-category="eco" data-price="449" data-discount="18"
                        onclick="window.location.href='/product/10'">
                        <div class="relative">
                            <img src="{{ asset('images/eco-friendly.jpg') }}" alt="Bamboo Eco Kit"
                                class="w-full h-48 object-cover">
                            <div class="absolute top-2 left-2 bg-red-500 text-white text-xs font-medium px-2 py-1 rounded">
                                18% OFF</div>
                        </div>
                        <div class="p-4">
                            <h3 class="font-medium mb-2">Bamboo Eco Kit</h3>
                            <p class="text-sm text-gray-600 mb-3">100% sustainable materials</p>
                            <div class="flex items-center gap-2 mb-3">
                                <span class="text-lg font-semibold">₹449</span>
                                <span class="text-sm text-gray-500 line-through">₹549</span>
                            </div>
                            <button
                                onclick="event.stopPropagation(); addToCart({id: '10', name: 'Bamboo Eco Kit', price: 449, originalPrice: 549, image: '{{ asset('images/eco-friendly.jpg') }}', description: '100% sustainable materials', discount: '18% OFF'})"
                                class="w-full bg-vibrant-pink hover:bg-vibrant-pink-dark text-white font-medium py-2 rounded-lg transition-colors">
                                Add to Cart
                            </button>
                        </div>
                    </div>

                    <!-- Accessories -->
                    <div class="product-item bg-white rounded-2xl border border-gray-200 overflow-hidden hover:shadow-md transition-shadow cursor-pointer"
                        data-category="accessories" data-price="899" data-discount="25"
                        onclick="window.location.href='/product/4'">
                        <div class="relative">
                            <img src="{{ asset('images/accessories.jpg') }}" alt="Premium Brass Accessories"
                                class="w-full h-48 object-cover">
                            <div class="absolute top-2 left-2 bg-red-500 text-white text-xs font-medium px-2 py-1 rounded">
                                25% OFF</div>
                        </div>
                        <div class="p-4">
                            <h3 class="font-medium mb-2">Premium Brass Accessories</h3>
                            <p class="text-sm text-gray-600 mb-3">Hand-crafted brass puja items</p>
                            <div class="flex items-center gap-2 mb-3">
                                <span class="text-lg font-semibold">₹899</span>
                                <span class="text-sm text-gray-500 line-through">₹1199</span>
                            </div>
                            <button
                                onclick="event.stopPropagation(); addToCart({id: '4', name: 'Premium Brass Accessories', price: 899, originalPrice: 1199, image: '{{ asset('images/accessories.jpg') }}', description: 'Hand-crafted brass puja items', discount: '25% OFF'})"
                                class="w-full bg-vibrant-pink hover:bg-vibrant-pink-dark text-white font-medium py-2 rounded-lg transition-colors">
                                Add to Cart
                            </button>
                        </div>
                    </div>

                    <!-- Custom -->
                    <div class="product-item bg-white rounded-2xl border border-gray-200 overflow-hidden hover:shadow-md transition-shadow cursor-pointer"
                        data-category="custom" data-price="799" data-discount="20"
                        onclick="window.location.href='/product/5'">
                        <div class="relative">
                            <img src="{{ asset('images/custom-kit.jpg') }}" alt="Custom Puja Kit"
                                class="w-full h-48 object-cover">
                            <div class="absolute top-2 left-2 bg-red-500 text-white text-xs font-medium px-2 py-1 rounded">
                                20% OFF</div>
                        </div>
                        <div class="p-4">
                            <h3 class="font-medium mb-2">Custom Puja Kit</h3>
                            <p class="text-sm text-gray-600 mb-3">Personalized religious items</p>
                            <div class="flex items-center gap-2 mb-3">
                                <span class="text-lg font-semibold">₹799</span>
                                <span class="text-sm text-gray-500 line-through">₹999</span>
                            </div>
                            <button
                                onclick="event.stopPropagation(); addToCart({id: '5', name: 'Custom Puja Kit', price: 799, originalPrice: 999, image: '{{ asset('images/custom-kit.jpg') }}', description: 'Personalized religious items', discount: '20% OFF'})"
                                class="w-full bg-vibrant-pink hover:bg-vibrant-pink-dark text-white font-medium py-2 rounded-lg transition-colors">
                                Add to Cart
                            </button>
                        </div>
                    </div>

                    <!-- Subscription -->
                    <div class="product-item bg-white rounded-2xl border border-gray-200 overflow-hidden hover:shadow-md transition-shadow cursor-pointer"
                        data-category="subscription" data-price="499" data-discount="17"
                        onclick="window.location.href='/product/6'">
                        <div class="relative">
                            <img src="{{ asset('images/subscription.jpg') }}" alt="Monthly Subscription Box"
                                class="w-full h-48 object-cover">
                            <div class="absolute top-2 left-2 bg-red-500 text-white text-xs font-medium px-2 py-1 rounded">
                                17% OFF</div>
                        </div>
                        <div class="p-4">
                            <h3 class="font-medium mb-2">Monthly Subscription Box</h3>
                            <p class="text-sm text-gray-600 mb-3">Fresh puja items every month</p>
                            <div class="flex items-center gap-2 mb-3">
                                <span class="text-lg font-semibold">₹499</span>
                                <span class="text-sm text-gray-500 line-through">₹599</span>
                            </div>
                            <button
                                onclick="event.stopPropagation(); addToCart({id: '6', name: 'Monthly Subscription Box', price: 499, originalPrice: 599, image: '{{ asset('images/subscription.jpg') }}', description: 'Fresh puja items every month', discount: '17% OFF'})"
                                class="w-full bg-vibrant-pink hover:bg-vibrant-pink-dark text-white font-medium py-2 rounded-lg transition-colors">
                                Add to Cart
                            </button>
                        </div>
                    </div>
                </div>

                <!-- No Products Message -->
                <div id="no-products" class="text-center py-12 hidden">
                    <div class="text-gray-400 mb-4">
                        <i data-lucide="package" class="w-16 h-16 mx-auto"></i>
                    </div>
                    <h3 class="text-lg font-medium mb-2">No products found</h3>
                    <p class="text-gray-500">Try adjusting your filters or browse all kits</p>
                </div>
            </div>
        </div>
    </div>

    <script>
        let currentSort = 'default';
        let allProducts = [];

        function initializeProducts() {
            allProducts = Array.from(document.querySelectorAll('.product-item'));
        }

        // Mobile filter toggle
        function toggleMobileFilters() {
            const mobileFilters = document.getElementById('mobile-filters');
            const chevron = document.getElementById('filter-chevron');

            mobileFilters.classList.toggle('hidden');
            chevron.classList.toggle('rotate-180');

            // Re-initialize Lucide icons
            lucide.createIcons();
        }

        function filterProducts(category) {
            const products = document.querySelectorAll('.product-item');
            const filterBtns = document.querySelectorAll('.filter-btn');
            const countElement = document.getElementById('count-number');
            const noProductsMsg = document.getElementById('no-products');

            let visibleCount = 0;

            // Reset filter buttons
            filterBtns.forEach(btn => {
                btn.classList.remove('bg-vibrant-pink', 'text-white');
                btn.classList.add('bg-gray-100', 'hover:bg-gray-200', 'text-gray-700');
            });

            // Highlight active filter
            const activeBtn = document.querySelector(`[data-filter="${category}"]`);
            if (activeBtn) {
                activeBtn.classList.remove('bg-gray-100', 'hover:bg-gray-200', 'text-gray-700');
                activeBtn.classList.add('bg-vibrant-pink', 'text-white');
            }

            // Filter products
            products.forEach(product => {
                if (category === 'all' || product.dataset.category === category) {
                    if (isPriceInRange(product)) {
                        product.style.display = 'block';
                        visibleCount++;
                    } else {
                        product.style.display = 'none';
                    }
                } else {
                    product.style.display = 'none';
                }
            });

            // Update count
            countElement.textContent = visibleCount;

            // Show/hide no products message
            if (visibleCount === 0) {
                noProductsMsg.classList.remove('hidden');
                document.getElementById('products-grid').classList.add('hidden');
            } else {
                noProductsMsg.classList.add('hidden');
                document.getElementById('products-grid').classList.remove('hidden');
            }

            // Auto-close mobile filters after selection
            if (window.innerWidth < 1024) {
                const mobileFilters = document.getElementById('mobile-filters');
                const chevron = document.getElementById('filter-chevron');
                mobileFilters.classList.add('hidden');
                chevron.classList.remove('rotate-180');
            }

            // Re-apply current sort
            if (currentSort !== 'default') {
                sortProducts(currentSort);
            }
        }

        function isPriceInRange(product) {
            const checkedPriceFilters = document.querySelectorAll('.price-filter:checked');
            if (checkedPriceFilters.length === 0) return true;

            const price = parseInt(product.dataset.price);

            for (let filter of checkedPriceFilters) {
                const min = parseInt(filter.dataset.min);
                const max = parseInt(filter.dataset.max);
                if (price >= min && price <= max) {
                    return true;
                }
            }
            return false;
        }

        function sortProducts(sortBy) {
            currentSort = sortBy;
            const grid = document.getElementById('products-grid');
            const products = Array.from(grid.querySelectorAll('.product-item:not([style*="display: none"])'));

            products.sort((a, b) => {
                switch (sortBy) {
                    case 'price-low':
                        return parseInt(a.dataset.price) - parseInt(b.dataset.price);
                    case 'price-high':
                        return parseInt(b.dataset.price) - parseInt(a.dataset.price);
                    case 'name':
                        return a.querySelector('h3').textContent.localeCompare(b.querySelector('h3').textContent);
                    case 'discount':
                        return parseInt(b.dataset.discount) - parseInt(a.dataset.discount);
                    default:
                        return 0;
                }
            });

            // Re-append sorted products
            products.forEach(product => grid.appendChild(product));
        }

        function clearFilters() {
            // Reset category filter
            filterProducts('all');

            // Reset price filters
            document.querySelectorAll('.price-filter').forEach(checkbox => {
                checkbox.checked = false;
            });

            // Reset sort
            document.getElementById('sort-select').value = 'default';
            currentSort = 'default';

            // Re-filter to show all products
            filterProducts('all');
        }

        // Initialize price filter listeners
        document.addEventListener('DOMContentLoaded', function() {
            initializeProducts();
            lucide.createIcons();

            document.querySelectorAll('.price-filter').forEach(checkbox => {
                checkbox.addEventListener('change', function() {
                    // Get current active category
                    const activeBtn = document.querySelector('.filter-btn.bg-vibrant-pink');
                    if (activeBtn) {
                        const activeCategory = activeBtn.dataset.filter;
                        filterProducts(activeCategory);
                    }
                });
            });
        });
    </script>
@endsection
