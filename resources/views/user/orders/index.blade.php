@extends('layouts.app')

@section('title', 'My Orders - Shree Samagri')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
           <!-- Customer Navigation Component -->
        <x-customer-navigation />
        
        <!-- Header -->
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-8">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">My Orders</h1>
                <p class="text-gray-600 mt-1">Track and manage your orders</p>
            </div>
            
            <!-- Search and Filters -->
            <div class="mt-4 sm:mt-0 flex flex-col sm:flex-row gap-3">
                <form method="GET" class="flex gap-2">
                    <input type="text" name="search" value="{{ request('search') }}" 
                           placeholder="Search orders..."
                           class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-vibrant-pink focus:border-transparent text-sm">
                    <button type="submit" 
                            class="px-4 py-2 bg-vibrant-pink text-white rounded-lg hover:bg-vibrant-pink-dark text-sm">
                        <i data-lucide="search" class="w-4 h-4"></i>
                    </button>
                </form>
            </div>
        </div>

        <!-- Status Filter Tabs -->
        <div class="bg-white rounded-lg shadow-sm mb-6">
            <div class="border-b border-gray-200">
                <nav class="flex space-x-8 px-6" aria-label="Tabs">
                    <a href="{{ route('orders.index') }}" 
                       class="border-b-2 py-4 px-1 text-sm font-medium {{ !request('status') ? 'border-vibrant-pink text-vibrant-pink' : 'border-transparent text-gray-500 hover:text-gray-700' }}">
                        All Orders
                        <span class="ml-2 bg-gray-100 text-gray-600 py-0.5 px-2.5 rounded-full text-xs">
                            {{ $statusCounts['all'] }}
                        </span>
                    </a>
                    
                    <a href="{{ route('orders.index', ['status' => 'pending']) }}" 
                       class="border-b-2 py-4 px-1 text-sm font-medium {{ request('status') === 'pending' ? 'border-vibrant-pink text-vibrant-pink' : 'border-transparent text-gray-500 hover:text-gray-700' }}">
                        Pending
                        <span class="ml-2 bg-yellow-100 text-yellow-600 py-0.5 px-2.5 rounded-full text-xs">
                            {{ $statusCounts['pending'] }}
                        </span>
                    </a>
                    
                    <a href="{{ route('orders.index', ['status' => 'processing']) }}" 
                       class="border-b-2 py-4 px-1 text-sm font-medium {{ request('status') === 'processing' ? 'border-vibrant-pink text-vibrant-pink' : 'border-transparent text-gray-500 hover:text-gray-700' }}">
                        Processing
                        <span class="ml-2 bg-blue-100 text-blue-600 py-0.5 px-2.5 rounded-full text-xs">
                            {{ $statusCounts['processing'] }}
                        </span>
                    </a>
                    
                    <a href="{{ route('orders.index', ['status' => 'shipped']) }}" 
                       class="border-b-2 py-4 px-1 text-sm font-medium {{ request('status') === 'shipped' ? 'border-vibrant-pink text-vibrant-pink' : 'border-transparent text-gray-500 hover:text-gray-700' }}">
                        Shipped
                        <span class="ml-2 bg-purple-100 text-purple-600 py-0.5 px-2.5 rounded-full text-xs">
                            {{ $statusCounts['shipped'] }}
                        </span>
                    </a>
                    
                    <a href="{{ route('orders.index', ['status' => 'delivered']) }}" 
                       class="border-b-2 py-4 px-1 text-sm font-medium {{ request('status') === 'delivered' ? 'border-vibrant-pink text-vibrant-pink' : 'border-transparent text-gray-500 hover:text-gray-700' }}">
                        Delivered
                        <span class="ml-2 bg-green-100 text-green-600 py-0.5 px-2.5 rounded-full text-xs">
                            {{ $statusCounts['delivered'] }}
                        </span>
                    </a>
                </nav>
            </div>
        </div>

        @if(session('success'))
            <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
                <div class="flex">
                    <i data-lucide="check-circle" class="w-5 h-5 text-green-400 mr-2 mt-0.5"></i>
                    <span class="text-green-700">{{ session('success') }}</span>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
                <div class="flex">
                    <i data-lucide="x-circle" class="w-5 h-5 text-red-400 mr-2 mt-0.5"></i>
                    <span class="text-red-700">{{ session('error') }}</span>
                </div>
            </div>
        @endif

        <!-- Orders List -->
        @if($orders->isEmpty())
            <div class="bg-white rounded-lg shadow-sm p-12 text-center">
                <i data-lucide="package" class="w-16 h-16 text-gray-300 mx-auto mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No orders found</h3>
                <p class="text-gray-500 mb-6">
                    @if(request('status') || request('search'))
                        No orders match your current filters.
                    @else
                        You haven't placed any orders yet. Start shopping to see your orders here.
                    @endif
                </p>
                <a href="{{ route('home') }}" 
                   class="inline-flex items-center px-4 py-2 bg-vibrant-pink text-white rounded-lg hover:bg-vibrant-pink-dark">
                    <i data-lucide="shopping-bag" class="w-4 h-4 mr-2"></i>
                    Continue Shopping
                </a>
            </div>
        @else
            <div class="space-y-6">
                @foreach($orders as $order)
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                    <!-- Order Header -->
                    <div class="p-6 border-b border-gray-200">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between">
                            <div class="flex-1">
                                <div class="flex items-center space-x-4 mb-2">
                                    <h3 class="text-lg font-semibold text-gray-900">
                                        Order {{ $order->order_number }}
                                    </h3>
                                    
                                    <!-- Order Status Badge -->
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        {{ $order->status === 'pending' ? 'bg-yellow-100 text-yellow-800' :
                                           ($order->status === 'processing' ? 'bg-blue-100 text-blue-800' :
                                           ($order->status === 'shipped' ? 'bg-purple-100 text-purple-800' :
                                           ($order->status === 'delivered' ? 'bg-green-100 text-green-800' :
                                           ($order->status === 'cancelled' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800')))) }}">
                                        <i data-lucide="{{ $order->status === 'pending' ? 'clock' : 
                                                         ($order->status === 'processing' ? 'package' :
                                                         ($order->status === 'shipped' ? 'truck' :
                                                         ($order->status === 'delivered' ? 'check-circle' :
                                                         ($order->status === 'cancelled' ? 'x-circle' : 'help-circle')))) }}" 
                                           class="w-3 h-3 mr-1"></i>
                                        {{ ucfirst($order->status) }}
                                    </span>
                                    
                                    <!-- Payment Status Badge -->
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        {{ $order->payment_status === 'paid' ? 'bg-green-100 text-green-800' :
                                           ($order->payment_status === 'pending' ? 'bg-yellow-100 text-yellow-800' :
                                           ($order->payment_status === 'failed' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800')) }}">
                                        <i data-lucide="{{ $order->payment_status === 'paid' ? 'check' : 
                                                         ($order->payment_status === 'pending' ? 'clock' : 'x') }}" 
                                           class="w-3 h-3 mr-1"></i>
                                        Payment {{ ucfirst($order->payment_status) }}
                                    </span>
                                </div>
                                
                                <div class="flex flex-col sm:flex-row sm:items-center text-sm text-gray-600 space-y-1 sm:space-y-0 sm:space-x-6">
                                    <span>
                                        <i data-lucide="calendar" class="w-4 h-4 inline mr-1"></i>
                                        Ordered on {{ $order->created_at->format('d M Y, h:i A') }}
                                    </span>
                                    <span>
                                        <i data-lucide="package" class="w-4 h-4 inline mr-1"></i>
                                        {{ $order->orderItems->count() }} item(s)
                                    </span>
                                    <span class="font-semibold text-gray-900">
                                        <i data-lucide="currency-rupee" class="w-4 h-4 inline mr-1"></i>
                                        ₹{{ number_format($order->total_amount, 2) }}
                                    </span>
                                </div>
                            </div>
                            
                            <!-- Order Actions -->
                            <div class="mt-4 sm:mt-0 flex flex-col sm:flex-row gap-2">
                                <a href="{{ route('orders.show', $order->order_number) }}" 
                                   class="inline-flex items-center justify-center px-3 py-2 text-sm font-medium text-vibrant-pink bg-vibrant-pink-light bg-opacity-10 rounded-lg hover:bg-opacity-20 transition-colors">
                                    <i data-lucide="eye" class="w-4 h-4 mr-1"></i>
                                    View Details
                                </a>
                                
                                @if(in_array($order->status, ['processing', 'shipped']))
                                    <a href="{{ route('orders.track', $order->order_number) }}" 
                                       class="inline-flex items-center justify-center px-3 py-2 text-sm font-medium text-blue-600 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors">
                                        <i data-lucide="map-pin" class="w-4 h-4 mr-1"></i>
                                        Track Order
                                    </a>
                                @endif
                                
                                @if($order->payment_status === 'paid' && $order->status === 'delivered')
                                    <a href="{{ route('orders.invoice', $order->order_number) }}" 
                                       class="inline-flex items-center justify-center px-3 py-2 text-sm font-medium text-gray-600 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                                        <i data-lucide="download" class="w-4 h-4 mr-1"></i>
                                        Invoice
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    <!-- Order Items Preview -->
                    <div class="p-6">
                        <div class="flex items-center space-x-4">
                            @foreach($order->orderItems->take(3) as $item)
                                <div class="flex items-center space-x-3">
                                    <img src="{{ $item->product_image ?: '/images/placeholder.jpg' }}" 
                                         alt="{{ $item->product_name }}" 
                                         class="w-12 h-12 object-cover rounded-lg border border-gray-200">
                                    <div>
                                        <p class="text-sm font-medium text-gray-900 line-clamp-1">{{ $item->product_name }}</p>
                                        <p class="text-xs text-gray-500">Qty: {{ $item->quantity }}</p>
                                    </div>
                                </div>
                            @endforeach
                            
                            @if($order->orderItems->count() > 3)
                                <div class="flex items-center justify-center w-12 h-12 bg-gray-100 rounded-lg border border-gray-200">
                                    <span class="text-xs font-medium text-gray-600">
                                        +{{ $order->orderItems->count() - 3 }}
                                    </span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            
            <!-- Pagination -->
            <div class="mt-8">
                {{ $orders->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
