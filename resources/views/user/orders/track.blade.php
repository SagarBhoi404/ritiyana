@extends('layouts.app')

@section('title', 'Track Order - Shree Samagri')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="flex items-center space-x-4 mb-8">
            <a href="{{ route('orders.show', $order->order_number) }}" 
               class="inline-flex items-center text-gray-600 hover:text-gray-800">
                <i data-lucide="arrow-left" class="w-4 h-4 mr-2"></i>
                Back to Order Details
            </a>
        </div>

        <!-- Order Info Header -->
        <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
            <div class="flex flex-col md:flex-row md:items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 mb-2">Track Your Order</h1>
                    <div class="flex flex-col sm:flex-row sm:items-center text-sm text-gray-600 space-y-1 sm:space-y-0 sm:space-x-6">
                        <span class="font-medium">Order {{ $order->order_number }}</span>
                        <span>Placed on {{ $order->created_at->format('d M Y') }}</span>
                        <span>{{ $order->orderItems->count() }} item(s)</span>
                    </div>
                </div>
                
                <div class="mt-4 md:mt-0 text-right">
                    <div class="text-2xl font-bold text-vibrant-pink">₹{{ number_format($order->total_amount, 2) }}</div>
                    <div class="text-sm text-gray-600">Total Amount</div>
                </div>
            </div>
        </div>

        <!-- Order Tracking Timeline -->
        <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-6">Order Progress</h2>
            
            <div class="relative">
                @foreach($timeline as $index => $step)
                <div class="flex items-start mb-8 last:mb-0">
                    <!-- Timeline Icon -->
                    <div class="flex-shrink-0 relative">
                        <div class="w-10 h-10 rounded-full flex items-center justify-center
                            {{ $step['completed'] ? 'bg-green-500 text-white' : 'bg-gray-200 text-gray-500' }}">
                            <i data-lucide="{{ $step['icon'] }}" class="w-5 h-5"></i>
                        </div>
                        
                        @if(!$loop->last)
                        <div class="absolute top-10 left-1/2 transform -translate-x-1/2 w-0.5 h-8 
                            {{ $step['completed'] ? 'bg-green-500' : 'bg-gray-200' }}"></div>
                        @endif
                    </div>
                    
                    <!-- Timeline Content -->
                    <div class="ml-4 flex-1">
                        <div class="flex flex-col md:flex-row md:items-center justify-between">
                            <div>
                                <h3 class="text-lg font-medium {{ $step['completed'] ? 'text-gray-900' : 'text-gray-500' }}">
                                    {{ $step['title'] }}
                                </h3>
                                <p class="text-sm text-gray-600 mt-1">{{ $step['description'] }}</p>
                            </div>
                            
                            @if($step['timestamp'])
                            <div class="mt-2 md:mt-0 text-right">
                                <div class="text-sm font-medium text-gray-900">
                                    {{ $step['timestamp']->format('d M Y') }}
                                </div>
                                <div class="text-xs text-gray-500">
                                    {{ $step['timestamp']->format('h:i A') }}
                                </div>
                            </div>
                            @endif
                        </div>
                        
                        @if($step['status'] === 'shipped' && $step['completed'] && $order->tracking_number)
                        <div class="mt-3 p-3 bg-blue-50 rounded-lg">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-sm font-medium text-blue-900">Tracking Number</p>
                                    <p class="text-sm text-blue-700 font-mono">{{ $order->tracking_number }}</p>
                                </div>
                                @if($order->shipping_partner)
                                <a href="#" class="text-sm text-blue-600 hover:text-blue-800 font-medium">
                                    Track with {{ $order->shipping_partner }}
                                    <i data-lucide="external-link" class="w-3 h-3 inline ml-1"></i>
                                </a>
                                @endif
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Order Items -->
        <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Items in this Order</h2>
            
            <div class="space-y-4">
                @foreach($order->orderItems as $item)
                <div class="flex items-center space-x-4 p-4 border border-gray-200 rounded-lg">
                    <img src="{{ $item->product_image ?: '/images/placeholder.jpg' }}" 
                         alt="{{ $item->product_name }}" 
                         class="w-16 h-16 object-cover rounded-lg border border-gray-200 flex-shrink-0">
                    
                    <div class="flex-1">
                        <h4 class="font-medium text-gray-900">{{ $item->product_name }}</h4>
                        <div class="flex items-center text-sm text-gray-600 mt-1">
                            <span>Qty: {{ $item->quantity }}</span>
                            <span class="mx-2">•</span>
                            <span>₹{{ number_format($item->price, 2) }} each</span>
                        </div>
                    </div>
                    
                    <div class="text-right">
                        <div class="font-semibold text-gray-900">₹{{ number_format($item->total, 2) }}</div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Shipping Address -->
        @if($order->shipping_address)
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Delivery Address</h2>
            
            <div class="flex items-start space-x-4">
                <div class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center flex-shrink-0">
                    <i data-lucide="map-pin" class="w-5 h-5 text-gray-600"></i>
                </div>
                
                <div class="flex-1">
                    <p class="font-medium text-gray-900">{{ $order->shipping_address['first_name'] }} {{ $order->shipping_address['last_name'] }}</p>
                    <p class="text-gray-600 mt-1">{{ $order->shipping_address['address_line_1'] }}</p>
                    @if($order->shipping_address['address_line_2'])
                        <p class="text-gray-600">{{ $order->shipping_address['address_line_2'] }}</p>
                    @endif
                    <p class="text-gray-600">{{ $order->shipping_address['city'] }}, {{ $order->shipping_address['state'] }} - {{ $order->shipping_address['postal_code'] }}</p>
                    @if($order->shipping_address['phone'])
                        <p class="text-gray-600 mt-2">
                            <i data-lucide="phone" class="w-4 h-4 inline mr-1"></i>
                            {{ $order->shipping_address['phone'] }}
                        </p>
                    @endif
                </div>
            </div>
        </div>
        @endif
        
        <!-- Help Section -->
        <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mt-6">
            <div class="flex items-start space-x-3">
                <i data-lucide="help-circle" class="w-6 h-6 text-blue-600 flex-shrink-0 mt-0.5"></i>
                <div>
                    <h3 class="text-lg font-medium text-blue-900 mb-2">Need Help?</h3>
                    <p class="text-blue-700 text-sm mb-4">
                        If you have any questions about your order or need assistance, we're here to help!
                    </p>
                    <div class="flex flex-col sm:flex-row gap-3">
                        <a href="tel:+919579809188" 
                           class="inline-flex items-center px-4 py-2 text-sm font-medium text-blue-600 bg-white rounded-lg hover:bg-blue-50 border border-blue-200">
                            <i data-lucide="phone" class="w-4 h-4 mr-2"></i>
                            Call Support
                        </a>
                        <a href="mailto:support@shreesamagri.com" 
                           class="inline-flex items-center px-4 py-2 text-sm font-medium text-blue-600 bg-white rounded-lg hover:bg-blue-50 border border-blue-200">
                            <i data-lucide="mail" class="w-4 h-4 mr-2"></i>
                            Email Us
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
