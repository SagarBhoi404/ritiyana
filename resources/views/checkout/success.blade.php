@extends('layouts.app')

@section('title', 'Order Confirmation - Ritiyana')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Success Header -->
        <div class="bg-white rounded-lg shadow-sm p-8 text-center mb-6">
            <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i data-lucide="check-circle" class="w-8 h-8 text-green-600"></i>
            </div>
            
            <h1 class="text-2xl font-bold text-gray-900 mb-2">Order Placed Successfully!</h1>
            <p class="text-gray-600 mb-4">
                Thank you for your order. We'll send you updates as your order progresses.
            </p>
            
            <div class="bg-gray-50 rounded-lg p-4 mb-6">
                <div class="text-sm text-gray-600 mb-1">Order Number</div>
                <div class="text-xl font-bold text-vibrant-pink">{{ $order->order_number }}</div>
            </div>
            
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('orders.show', $order->order_number) }}" 
                   class="inline-flex items-center justify-center px-6 py-3 bg-vibrant-pink text-white rounded-lg hover:bg-vibrant-pink-dark">
                    <i data-lucide="eye" class="w-4 h-4 mr-2"></i>
                    View Order Details
                </a>
                
                <a href="{{ route('home') }}" 
                   class="inline-flex items-center justify-center px-6 py-3 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200">
                    <i data-lucide="home" class="w-4 h-4 mr-2"></i>
                    Continue Shopping
                </a>
            </div>
        </div>

        <!-- Order Summary -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Order Summary</h3>
            
            <!-- Order Items -->
            <div class="space-y-4 mb-6">
                @foreach($order->orderItems as $item)
                <div class="flex items-center space-x-4 p-4 border border-gray-200 rounded-lg">
                    <img src="{{ $item->product_image ?: '/images/placeholder.jpg' }}" 
                         alt="{{ $item->product_name }}" 
                         class="w-16 h-16 object-cover rounded-lg">
                    
                    <div class="flex-1">
                        <h4 class="font-medium text-gray-900">{{ $item->product_name }}</h4>
                        <p class="text-sm text-gray-600">Qty: {{ $item->quantity }} × ₹{{ number_format($item->price, 2) }}</p>
                    </div>
                    
                    <div class="font-semibold text-gray-900">
                        ₹{{ number_format($item->total, 2) }}
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Totals -->
            <div class="border-t border-gray-200 pt-4">
                <div class="space-y-2">
                    <div class="flex justify-between text-sm">
                        <span>Subtotal</span>
                        <span>₹{{ number_format($order->subtotal, 2) }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span>Tax (18% GST)</span>
                        <span>₹{{ number_format($order->tax_amount, 2) }}</span>
                    </div>
                    <div class="flex justify-between text-sm">
                        <span>Shipping</span>
                        <span>
                            @if($order->shipping_amount > 0)
                                ₹{{ number_format($order->shipping_amount, 2) }}
                            @else
                                <span class="text-green-600">Free</span>
                            @endif
                        </span>
                    </div>
                    <div class="flex justify-between font-bold text-lg pt-2 border-t border-gray-200">
                        <span>Total</span>
                        <span class="text-vibrant-pink">₹{{ number_format($order->total_amount, 2) }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
