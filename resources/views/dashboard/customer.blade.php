@extends('layouts.app')

@section('content')
    <div class="min-h-screen bg-gray-50">
        <!-- Header -->
        <!-- Header -->
        <div class="bg-gradient-to-r from-purple-600 to-pink-600 text-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center py-6 space-y-4 sm:space-y-0">
                    <!-- Left Section: Welcome Message -->
                    <div class="text-center sm:text-left">
                        <h1 class="text-2xl sm:text-3xl font-bold">Welcome back, {{ $user->first_name }}!</h1>
                        <p class="text-purple-100 mt-1 text-sm sm:text-base">Explore our puja samagri collection</p>
                    </div>

                    <!-- Right Section: User Info & Logout -->
                    <div class="flex flex-col sm:flex-row items-center space-y-3 sm:space-y-0 sm:space-x-4">
                        <span class="text-xs sm:text-sm text-purple-100 truncate max-w-[200px] sm:max-w-none">
                            {{ $user->email }}
                        </span>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit"
                                class="bg-white text-purple-600 hover:bg-purple-50 px-4 py-2 rounded-lg text-sm font-medium transition-colors w-full sm:w-auto">
                                <i data-lucide="log-out" class="w-4 h-4 inline mr-1"></i>
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Customer Navigation Component -->
            <x-customer-navigation />

            <!-- Quick Stats -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8 mt-6">
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
                    <div class="flex items-center">
                        <div class="p-3 bg-blue-100 rounded-lg">
                            <i data-lucide="shopping-bag" class="w-6 h-6 text-blue-600"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Total Orders</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $totalOrders }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
                    <div class="flex items-center">
                        <div class="p-3 bg-yellow-100 rounded-lg">
                            <i data-lucide="clock" class="w-6 h-6 text-yellow-600"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Pending Orders</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $pendingOrders }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
                    <div class="flex items-center">
                        <div class="p-3 bg-green-100 rounded-lg">
                            <i data-lucide="check-circle" class="w-6 h-6 text-green-600"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Completed</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $completedOrders }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 hover:shadow-md transition-shadow">
                    <div class="flex items-center">
                        <div class="p-3 bg-purple-100 rounded-lg">
                            <i data-lucide="wallet" class="w-6 h-6 text-purple-600"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Total Spent</p>
                            <p class="text-2xl font-bold text-gray-900">₹{{ number_format($totalSpent, 0) }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <a href="{{ route('products.index') }}"
                    class="bg-gradient-to-br from-pink-500 to-purple-600 rounded-xl shadow-md p-6 text-white hover:shadow-lg transition-all transform hover:-translate-y-1">
                    <div class="flex items-center mb-3">
                        <i data-lucide="shopping-cart" class="w-8 h-8"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-2">Shop Puja Samagri</h3>
                    <p class="text-purple-100 text-sm">Browse our collection of authentic puja items</p>
                </a>

                <a href="{{ route('puja-kits.index') }}"
                    class="bg-gradient-to-br from-orange-500 to-pink-600 rounded-xl shadow-md p-6 text-white hover:shadow-lg transition-all transform hover:-translate-y-1">
                    <div class="flex items-center mb-3">
                        <i data-lucide="gift" class="w-8 h-8"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-2">Puja Kits</h3>
                    <p class="text-orange-100 text-sm">Complete puja packages for different occasions</p>
                </a>

                <a href="{{ route('orders.index') }}"
                    class="bg-gradient-to-br from-blue-500 to-purple-600 rounded-xl shadow-md p-6 text-white hover:shadow-lg transition-all transform hover:-translate-y-1">
                    <div class="flex items-center mb-3">
                        <i data-lucide="package" class="w-8 h-8"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-2">My Orders</h3>
                    <p class="text-blue-100 text-sm">Track and manage your orders</p>
                </a>
            </div>

            <!-- Recent Orders -->
            @if ($recentOrders->count() > 0)
                <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-8">
                    <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                        <h3 class="text-lg font-semibold text-gray-900">Recent Orders</h3>
                        <a href="{{ route('orders.index') }}"
                            class="text-sm text-purple-600 hover:text-purple-700 font-medium">
                            View All →
                        </a>
                    </div>
                    <div class="divide-y divide-gray-200">
                        @foreach ($recentOrders as $order)
                            <div class="p-6 hover:bg-gray-50 transition-colors">
                                <div class="flex items-center justify-between mb-3">
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">Order #{{ $order->order_number }}</p>
                                        <p class="text-xs text-gray-500 mt-1">
                                            {{ $order->created_at->format('M d, Y h:i A') }}</p>
                                    </div>
                                    <div class="text-right">
                                        <p class="text-sm font-semibold text-gray-900">
                                            ₹{{ number_format($order->total_amount, 2) }}</p>
                                        <span
                                            class="inline-flex mt-1 px-2 py-1 text-xs font-semibold rounded-full {{ $order->status_badge }}">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-2 text-sm text-gray-600">
                                    <i data-lucide="package" class="w-4 h-4"></i>
                                    <span>{{ $order->orderItems->count() }} item(s)</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Featured Products -->
            @if ($featuredProducts->count() > 0)
                <div class="mb-8">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-2xl font-bold text-gray-900">Featured Products</h3>
                        <a href="{{ route('products.index') }}" class="text-purple-600 hover:text-purple-700 font-medium">
                            View All →
                        </a>
                    </div>
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-6">
                        @foreach ($featuredProducts as $product)
                            <a href="{{ route('product.show', $product->slug) }}"
                                class="bg-white rounded-xl border border-gray-200 overflow-hidden hover:shadow-lg transition-all transform hover:-translate-y-1">
                                @if ($product->featured_image)
                                    <img src="{{ asset('storage/' . $product->featured_image) }}"
                                        alt="{{ $product->name }}" class="w-full h-40 object-cover">
                                @else
                                    <div class="w-full h-40 bg-gray-200 flex items-center justify-center">
                                        <i data-lucide="image" class="w-12 h-12 text-gray-400"></i>
                                    </div>
                                @endif
                                <div class="p-4">
                                    <h4 class="text-sm font-semibold text-gray-900 mb-2 line-clamp-2">{{ $product->name }}
                                    </h4>
                                    <div class="flex items-center justify-between">
                                        <span
                                            class="text-lg font-bold text-purple-600">₹{{ number_format($product->sale_price ?? $product->price, 0) }}</span>
                                        @if ($product->sale_price)
                                            <span
                                                class="text-xs text-gray-500 line-through">₹{{ number_format($product->price, 0) }}</span>
                                        @endif
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Popular Puja Kits -->
            @if ($popularKits->count() > 0)
                <div class="mb-8">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-2xl font-bold text-gray-900">Popular Puja Kits</h3>
                        <a href="{{ route('puja-kits.index') }}"
                            class="text-purple-600 hover:text-purple-700 font-medium">
                            View All →
                        </a>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach ($popularKits as $kit)
                            <a href="{{ route('puja-kits.show', $kit->slug) }}"
                                class="bg-white rounded-xl border border-gray-200 overflow-hidden hover:shadow-lg transition-all">
                                @if ($kit->featured_image)
                                    <img src="{{ asset('storage/' . $kit->featured_image) }}" alt="{{ $kit->name }}"
                                        class="w-full h-48 object-cover">
                                @else
                                    <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                                        <i data-lucide="gift" class="w-12 h-12 text-gray-400"></i>
                                    </div>
                                @endif
                                <div class="p-4">
                                    <h4 class="text-base font-semibold text-gray-900 mb-2">{{ $kit->name }}</h4>
                                    <p class="text-sm text-gray-600 mb-3 line-clamp-2">{{ $kit->short_description }}</p>
                                    <div class="flex items-center justify-between">
                                        <span
                                            class="text-xl font-bold text-purple-600">₹{{ number_format($kit->sale_price ?? $kit->price, 0) }}</span>
                                        <span class="text-xs bg-purple-100 text-purple-600 px-2 py-1 rounded-full">
                                            {{ $kit->kitItems ? $kit->kitItems->count() : 0 }} items
                                        </span>

                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Categories -->
            @if ($categories->count() > 0)
                <div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-6">Shop by Category</h3>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        @foreach ($categories as $category)
                            <a href="{{ route('products.index', ['category' => $category->id]) }}"
                                class="bg-white rounded-xl border border-gray-200 p-6 text-center hover:shadow-lg transition-all transform hover:-translate-y-1">
                                @if ($category->image)
                                    <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}"
                                        class="w-16 h-16 mx-auto mb-3 rounded-full object-cover">
                                @else
                                    <div
                                        class="w-16 h-16 mx-auto mb-3 bg-purple-100 rounded-full flex items-center justify-center">
                                        <i data-lucide="tag" class="w-8 h-8 text-purple-600"></i>
                                    </div>
                                @endif
                                <h4 class="text-sm font-semibold text-gray-900 mb-1">{{ $category->name }}</h4>
                                <p class="text-xs text-gray-500">{{ $category->products_count }} products</p>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
