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
                            Puja Types
                        </h2>

                        <div class="grid grid-cols-2 lg:grid-cols-1 gap-2">
                            <a href="{{ route('puja-kits.index') }}" 
                                class="filter-btn text-left p-3 rounded-lg font-medium {{ !request('puja') ? 'bg-vibrant-pink text-white' : 'bg-gray-100 hover:bg-gray-200 text-gray-700' }}">
                                <div class="flex items-center justify-between">
                                    <span class="text-sm lg:text-base">All Kits</span>
                                    <span class="text-xs {{ !request('puja') ? 'bg-white bg-opacity-20' : 'bg-gray-200' }} px-2 py-1 rounded">{{ $totalKits }}</span>
                                </div>
                            </a>

                            @foreach($pujas as $puja)
                            <a href="{{ route('puja-kits.index', ['puja' => $puja->slug]) }}" 
                                class="filter-btn text-left p-3 rounded-lg font-medium {{ request('puja') == $puja->slug ? 'bg-vibrant-pink text-white' : 'bg-gray-100 hover:bg-gray-200 text-gray-700' }}">
                                <div class="flex items-center justify-between">
                                    <span class="text-sm lg:text-base">{{ $puja->name }}</span>
                                    <span class="text-xs {{ request('puja') == $puja->slug ? 'bg-white bg-opacity-20' : 'bg-gray-200' }} px-2 py-1 rounded">{{ $puja->puja_kits_count }}</span>
                                </div>
                            </a>
                            @endforeach
                        </div>
                    </div>

                    <!-- Search Filter -->
                    <div class="mb-6 pt-6 border-t border-gray-200">
                        <h3 class="font-semibold mb-4 flex items-center gap-2">
                            <i data-lucide="search" class="w-4 h-4"></i>
                            Search Kits
                        </h3>
                        <form action="{{ route('puja-kits.index') }}" method="GET" class="relative">
                            @if(request('puja'))
                                <input type="hidden" name="puja" value="{{ request('puja') }}">
                            @endif
                            <input 
                                type="text" 
                                name="search" 
                                value="{{ request('search') }}"
                                placeholder="Search kits..."
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-vibrant-pink text-sm"
                            >
                            <button type="submit" class="absolute right-2 top-2 text-gray-400 hover:text-vibrant-pink">
                                <i data-lucide="search" class="w-4 h-4"></i>
                            </button>
                        </form>
                    </div>

                    <!-- Clear Filters -->
                    <a href="{{ route('puja-kits.index') }}"
                        class="w-full text-vibrant-pink hover:bg-vibrant-pink hover:text-white border border-vibrant-pink font-medium py-3 px-4 rounded-lg transition-colors block text-center">
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
                        Showing <span class="font-medium">{{ $pujaKits->count() }}</span> of <span class="font-medium">{{ $pujaKits->total() }}</span> products
                        @if(request('search'))
                            for "<span class="font-medium">{{ request('search') }}</span>"
                        @endif
                        @if(request('puja'))
                            in "<span class="font-medium">{{ $pujas->where('slug', request('puja'))->first()?->name ?? 'Unknown' }}</span>"
                        @endif
                    </p>

                    <!-- Sort Options -->
                    <form action="{{ route('puja-kits.index') }}" method="GET" class="flex items-center gap-2">
                        @if(request('puja'))
                            <input type="hidden" name="puja" value="{{ request('puja') }}">
                        @endif
                        @if(request('search'))
                            <input type="hidden" name="search" value="{{ request('search') }}">
                        @endif
                        <label class="text-sm text-gray-600">Sort by:</label>
                        <select name="sort" onchange="this.form.submit()"
                            class="px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-vibrant-pink text-sm">
                            <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Newest First</option>
                            <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Name: A to Z</option>
                            <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>Popular</option>
                        </select>
                    </form>
                </div>

                <!-- Active Filters Display -->
                @if(request()->hasAny(['puja', 'search']))
                <div class="mb-4">
                    <div class="flex flex-wrap gap-2 items-center">
                        <span class="text-sm text-gray-600">Active filters:</span>
                        @if(request('puja'))
                            <span class="inline-flex items-center gap-1 bg-vibrant-pink text-white text-xs px-2 py-1 rounded-full">
                                {{ $pujas->where('slug', request('puja'))->first()?->name ?? 'Unknown' }}
                                <a href="{{ route('puja-kits.index', array_merge(request()->except('puja'))) }}" class="hover:bg-white hover:bg-opacity-20 rounded-full p-0.5">
                                    <i data-lucide="x" class="w-3 h-3"></i>
                                </a>
                            </span>
                        @endif
                        @if(request('search'))
                            <span class="inline-flex items-center gap-1 bg-blue-500 text-white text-xs px-2 py-1 rounded-full">
                                "{{ request('search') }}"
                                <a href="{{ route('puja-kits.index', array_merge(request()->except('search'))) }}" class="hover:bg-white hover:bg-opacity-20 rounded-full p-0.5">
                                    <i data-lucide="x" class="w-3 h-3"></i>
                                </a>
                            </span>
                        @endif
                    </div>
                </div>
                @endif

                <!-- Products Grid -->
                @if($pujaKits->count() > 0)
                <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-3 gap-4 lg:gap-6 mb-8">
                    @foreach($pujaKits as $kit)
                    <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden hover:shadow-md transition-shadow cursor-pointer"
                        onclick="window.location.href='{{ route('puja-kits.show', $kit->slug) }}'">
                        <div class="relative">
                            <img src="{{ $kit->image_url }}" 
                                 alt="{{ $kit->kit_name }}"
                                 class="w-full h-48 object-cover">
                            @if($kit->discount_percentage > 0)
                            <div class="absolute top-2 left-2 bg-red-500 text-white text-xs font-medium px-2 py-1 rounded">
                                {{ number_format($kit->discount_percentage, 0) }}% OFF
                            </div>
                            @endif
                        </div>
                        <div class="p-4">
                            <h3 class="font-medium mb-2">{{ $kit->kit_name }}</h3>
                            <p class="text-sm text-gray-600 mb-2">{{ Str::limit($kit->kit_description, 50) }}</p>
                            
                            @if($kit->puja_names)
                            <p class="text-xs text-vibrant-pink mb-3">For: {{ Str::limit($kit->puja_names, 30) }}</p>
                            @endif
                            
                            <div class="flex items-center gap-2 mb-3">
                                <span class="text-lg font-semibold">₹{{ number_format($kit->total_price, 0) }}</span>
                                @if($kit->discount_percentage > 0)
                                    @php
                                        $originalPrice = $kit->total_price / (1 - $kit->discount_percentage / 100);
                                    @endphp
                                    <span class="text-sm text-gray-500 line-through">₹{{ number_format($originalPrice, 0) }}</span>
                                @endif
                            </div>
                            
                            <div class="text-xs text-gray-500 mb-3">
                                {{ $kit->products->count() }} items • {{ $kit->products->sum('pivot.quantity') }} pieces
                            </div>
                            
                            <button
                                onclick="event.stopPropagation(); addToCart({{ json_encode([
                                    'id' => $kit->id,
                                    'name' => $kit->kit_name,
                                    'price' => $kit->total_price,
                                    'image' => $kit->image_url,
                                    'description' => $kit->kit_description,
                                    'discount' => $kit->discount_percentage > 0 ? number_format($kit->discount_percentage, 0) . '% OFF' : null
                                ]) }})"
                                class="w-full bg-vibrant-pink hover:bg-vibrant-pink-dark text-white font-medium py-2 rounded-lg transition-colors">
                                Add to Cart
                            </button>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="flex justify-center">
                    {{ $pujaKits->withQueryString()->links() }}
                </div>

                @else
                <!-- No Products Message -->
                <div class="text-center py-12">
                    <div class="text-gray-400 mb-4">
                        <i data-lucide="package" class="w-16 h-16 mx-auto"></i>
                    </div>
                    <h3 class="text-lg font-medium mb-2">No puja kits found</h3>
                    <p class="text-gray-500 mb-4">
                        @if(request()->hasAny(['puja', 'search']))
                            Try adjusting your filters or browse all kits
                        @else
                            No puja kits are currently available
                        @endif
                    </p>
                    @if(request()->hasAny(['puja', 'search']))
                    <a href="{{ route('puja-kits.index') }}" 
                       class="inline-flex items-center gap-2 text-vibrant-pink hover:underline">
                        <i data-lucide="arrow-left" class="w-4 h-4"></i>
                        View all kits
                    </a>
                    @endif
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

        // Add to cart function (placeholder - implement according to your cart system)
        function addToCart(product) {
            console.log('Adding to cart:', product);
            // Implement your cart functionality here
            alert(`Added "${product.name}" to cart!`);
        }

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            lucide.createIcons();
        });
    </script>
@endsection
