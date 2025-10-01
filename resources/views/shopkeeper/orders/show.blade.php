@extends('layouts.shopkeeper')

@section('title', 'Order Details')
@section('breadcrumb', 'Orders / View')

@section('content')
<div class="px-4 sm:px-6 lg:px-8 py-8">
    <!-- Back Button -->
    <div class="mb-6">
        <a href="{{ route('vendor.orders.index') }}" class="inline-flex items-center text-sm text-gray-600 hover:text-gray-900">
            <i data-lucide="arrow-left" class="w-4 h-4 mr-2"></i>
            Back to Orders
        </a>
    </div>

    <!-- Order Header -->
    <div class="bg-white rounded-xl border border-gray-200 p-6 mb-6">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Order #{{ $order->order_number }}</h1>
                <p class="text-sm text-gray-600 mt-1">Placed on {{ $order->created_at->format('F d, Y \a\t h:i A') }}</p>
            </div>
            <div class="flex items-center space-x-3">
                <span class="inline-flex px-3 py-1.5 text-sm font-semibold rounded-full {{ $order->status_badge }}">
                    {{ ucfirst($order->status) }}
                </span>
                <a href="{{ route('vendor.orders.invoice', $order->id) }}" target="_blank"
                    class="inline-flex items-center px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 text-sm font-medium">
                    <i data-lucide="printer" class="w-4 h-4 mr-2"></i>
                    Print Invoice
                </a>
            </div>
        </div>

        <!-- Order Stats -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-gray-50 rounded-lg p-4">
                <div class="text-xs text-gray-600 mb-1">Subtotal</div>
                <div class="text-lg font-bold text-gray-900">₹{{ number_format($orderData->subtotal, 2) }}</div>
            </div>
            <div class="bg-gray-50 rounded-lg p-4">
                <div class="text-xs text-gray-600 mb-1">Commission ({{ number_format($orderData->commission_rate, 1) }}%)</div>
                <div class="text-lg font-bold text-red-600">-₹{{ number_format($orderData->commission, 2) }}</div>
            </div>
            <div class="bg-gray-50 rounded-lg p-4">
                <div class="text-xs text-gray-600 mb-1">Your Earning</div>
                <div class="text-lg font-bold text-green-600">₹{{ number_format($orderData->earning, 2) }}</div>
            </div>
            <div class="bg-gray-50 rounded-lg p-4">
                <div class="text-xs text-gray-600 mb-1">Total Items</div>
                <div class="text-lg font-bold text-gray-900">{{ $orderData->items->count() }}</div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Order Items -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Your Items in This Order</h3>
                </div>
                <div class="divide-y divide-gray-200">
                    @foreach($orderData->items as $item)
                        <div class="p-6 hover:bg-gray-50">
                            <div class="flex items-start space-x-4">
                                @if($item->product && $item->product->featured_image)
                                    <img class="w-20 h-20 rounded-lg object-cover" 
                                        src="{{ asset('storage/' . $item->product->featured_image) }}" 
                                        alt="{{ $item->product_name }}">
                                @elseif($item->pujaKit && $item->pujaKit->featured_image)
                                    <img class="w-20 h-20 rounded-lg object-cover" 
                                        src="{{ asset('storage/' . $item->pujaKit->featured_image) }}" 
                                        alt="{{ $item->product_name }}">
                                @else
                                    <div class="w-20 h-20 rounded-lg bg-gray-200 flex items-center justify-center">
                                        <i data-lucide="package" class="w-8 h-8 text-gray-400"></i>
                                    </div>
                                @endif
                                
                                <div class="flex-1">
                                    <h4 class="text-sm font-semibold text-gray-900">{{ $item->product_name }}</h4>
                                    <p class="text-xs text-gray-500 mt-1">SKU: {{ $item->product_sku ?? 'N/A' }}</p>
                                    <div class="flex items-center mt-2 space-x-4">
                                        <span class="text-sm text-gray-600">Qty: {{ $item->quantity }}</span>
                                        <span class="text-sm text-gray-600">Price: ₹{{ number_format($item->price, 2) }}</span>
                                    </div>
                                </div>
                                
                                <div class="text-right">
                                    <div class="text-sm font-semibold text-gray-900">₹{{ number_format($item->total, 2) }}</div>
                                    @if($item->vendor_commission > 0)
                                        <div class="text-xs text-red-600 mt-1">-₹{{ number_format($item->vendor_commission, 2) }} comm.</div>
                                    @endif
                                    <div class="text-xs text-green-600 font-semibold mt-1">₹{{ number_format($item->vendor_earning, 2) }} earning</div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Customer Information -->
            <div class="bg-white rounded-xl border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Customer Information</h3>
                <div class="space-y-3">
                    <div class="flex items-center space-x-3">
                        @if($order->customer && $order->customer->profile_image)
                            <img class="w-10 h-10 rounded-full" 
                                src="{{ asset('storage/' . $order->customer->profile_image) }}" 
                                alt="{{ $order->customer->full_name }}">
                        @else
                            <div class="w-10 h-10 rounded-full bg-purple-100 flex items-center justify-center">
                                <span class="text-purple-600 font-semibold">
                                    {{ $order->customer ? substr($order->customer->first_name, 0, 1) : 'N' }}
                                </span>
                            </div>
                        @endif
                        <div>
                            <div class="text-sm font-medium text-gray-900">{{ $order->customer->full_name ?? 'Unknown' }}</div>
                            <div class="text-xs text-gray-500">{{ $order->customer->email ?? 'N/A' }}</div>
                        </div>
                    </div>
                    @if($order->customer && $order->customer->phone)
                        <div class="text-sm text-gray-600">
                            <i data-lucide="phone" class="w-4 h-4 inline mr-2"></i>
                            {{ $order->customer->phone }}
                        </div>
                    @endif
                </div>
            </div>

            <!-- Shipping Address -->
            <div class="bg-white rounded-xl border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Shipping Address</h3>
                @if($order->shipping_address)
                    <div class="text-sm text-gray-600 space-y-1">
                        <p class="font-medium text-gray-900">{{ $order->shipping_address['full_name'] ?? '' }}</p>
                        <p>{{ $order->shipping_address['address_line1'] ?? '' }}</p>
                        @if(isset($order->shipping_address['address_line2']))
                            <p>{{ $order->shipping_address['address_line2'] }}</p>
                        @endif
                        <p>{{ $order->shipping_address['city'] ?? '' }}, {{ $order->shipping_address['state'] ?? '' }} {{ $order->shipping_address['pincode'] ?? '' }}</p>
                        @if(isset($order->shipping_address['phone']))
                            <p class="mt-2">
                                <i data-lucide="phone" class="w-4 h-4 inline mr-1"></i>
                                {{ $order->shipping_address['phone'] }}
                            </p>
                        @endif
                    </div>
                @else
                    <p class="text-sm text-gray-500">No shipping address available</p>
                @endif
            </div>

            <!-- Order Actions -->
            <div class="bg-white rounded-xl border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Order Actions</h3>
                <div class="space-y-3">
                    @if($order->status === 'pending')
                        <form action="{{ route('vendor.orders.update-status', $order->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status" value="confirmed">
                            <button type="submit" class="w-full px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 text-sm font-medium">
                                <i data-lucide="check" class="w-4 h-4 inline mr-2"></i>
                                Confirm Order
                            </button>
                        </form>
                    @endif
                    
                    @if(in_array($order->status, ['confirmed', 'processing']))
                        <button onclick="document.getElementById('shippingModal').classList.remove('hidden')" 
                            class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 text-sm font-medium">
                            <i data-lucide="truck" class="w-4 h-4 inline mr-2"></i>
                            Mark as Shipped
                        </button>
                    @endif
                    
                    <a href="{{ route('vendor.orders.invoice', $order->id) }}" target="_blank"
                        class="block w-full px-4 py-2 bg-gray-600 text-white text-center rounded-lg hover:bg-gray-700 text-sm font-medium">
                        <i data-lucide="download" class="w-4 h-4 inline mr-2"></i>
                        Download Invoice
                    </a>
                </div>
            </div>

            <!-- Order Timeline -->
            <div class="bg-white rounded-xl border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Order Timeline</h3>
                <div class="space-y-4">
                    <div class="flex items-start space-x-3">
                        <div class="w-2 h-2 mt-2 rounded-full bg-green-500"></div>
                        <div>
                            <div class="text-sm font-medium text-gray-900">Order Placed</div>
                            <div class="text-xs text-gray-500">{{ $order->created_at->format('M d, Y h:i A') }}</div>
                        </div>
                    </div>
                    
                    @if($order->shipped_at)
                        <div class="flex items-start space-x-3">
                            <div class="w-2 h-2 mt-2 rounded-full bg-blue-500"></div>
                            <div>
                                <div class="text-sm font-medium text-gray-900">Shipped</div>
                                <div class="text-xs text-gray-500">{{ $order->shipped_at->format('M d, Y h:i A') }}</div>
                            </div>
                        </div>
                    @endif
                    
                    @if($order->delivered_at)
                        <div class="flex items-start space-x-3">
                            <div class="w-2 h-2 mt-2 rounded-full bg-purple-500"></div>
                            <div>
                                <div class="text-sm font-medium text-gray-900">Delivered</div>
                                <div class="text-xs text-gray-500">{{ $order->delivered_at->format('M d, Y h:i A') }}</div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Shipping Modal (same as index page) -->
<div id="shippingModal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-full max-w-md shadow-lg rounded-xl bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Mark Order as Shipped</h3>
                <button onclick="document.getElementById('shippingModal').classList.add('hidden')" 
                    class="text-gray-400 hover:text-gray-600 transition-colors">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>

            <form action="{{ route('vendor.orders.update-status', $order->id) }}" method="POST">
                @csrf
                @method('PATCH')
                
                <div class="mb-4">
                    <label for="tracking_number" class="block text-sm font-medium text-gray-700 mb-2">
                        Tracking Number (Optional)
                    </label>
                    <input type="text" name="tracking_number" id="tracking_number" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent text-sm"
                        placeholder="Enter tracking number">
                </div>

                <div class="mb-4">
                    <label for="vendor_notes" class="block text-sm font-medium text-gray-700 mb-2">
                        Notes (Optional)
                    </label>
                    <textarea name="vendor_notes" id="vendor_notes" rows="3" 
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent text-sm"
                        placeholder="Add any shipping notes..."></textarea>
                </div>

                <input type="hidden" name="status" value="shipped">

                <div class="flex justify-end space-x-3 mt-6">
                    <button type="button" 
                        onclick="document.getElementById('shippingModal').classList.add('hidden')" 
                        class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors text-sm font-medium">
                        Cancel
                    </button>
                    <button type="submit" 
                        class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors text-sm font-medium">
                        Mark as Shipped
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@if (session('success'))
    <div class="fixed top-4 right-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded z-50">
        {{ session('success') }}
    </div>
@endif

<script>
    setTimeout(function() {
        const alerts = document.querySelectorAll('[class*="fixed top-4 right-4"]');
        alerts.forEach(alert => {
            alert.style.transition = 'opacity 0.5s';
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 500);
        });
    }, 5000);
</script>
@endsection
