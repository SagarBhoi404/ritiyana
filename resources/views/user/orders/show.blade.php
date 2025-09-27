@extends('layouts.app')

@section('title', 'Order Details - Shree Samagri')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="flex items-center justify-between mb-8">
            <div class="flex items-center space-x-4">
                <a href="{{ route('orders.index') }}" 
                   class="inline-flex items-center text-gray-600 hover:text-gray-800">
                    <i data-lucide="arrow-left" class="w-4 h-4 mr-2"></i>
                    Back to Orders
                </a>
            </div>
            
            <div class="flex space-x-3">
                @if(in_array($order->status, ['processing', 'shipped']))
                    <a href="{{ route('orders.track', $order->order_number) }}" 
                       class="inline-flex items-center px-4 py-2 text-sm font-medium text-blue-600 bg-blue-50 rounded-lg hover:bg-blue-100">
                        <i data-lucide="map-pin" class="w-4 h-4 mr-2"></i>
                        Track Order
                    </a>
                @endif
                
                @if($order->payment_status === 'paid')
                    <a href="{{ route('orders.invoice', $order->order_number) }}" 
                       class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-600 bg-gray-100 rounded-lg hover:bg-gray-200">
                        <i data-lucide="download" class="w-4 h-4 mr-2"></i>
                        Download Invoice
                    </a>
                @endif
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

        <!-- Order Overview -->
        <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
            <div class="flex flex-col lg:flex-row lg:items-center justify-between mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 mb-2">Order {{ $order->order_number }}</h1>
                    <p class="text-gray-600">Placed on {{ $order->created_at->format('d M Y, h:i A') }}</p>
                </div>
                
                <div class="mt-4 lg:mt-0 flex flex-col sm:flex-row gap-4">
                    <!-- Order Status -->
                    <div class="text-center">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                            {{ $order->status === 'pending' ? 'bg-yellow-100 text-yellow-800' :
                               ($order->status === 'processing' ? 'bg-blue-100 text-blue-800' :
                               ($order->status === 'shipped' ? 'bg-purple-100 text-purple-800' :
                               ($order->status === 'delivered' ? 'bg-green-100 text-green-800' :
                               ($order->status === 'cancelled' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800')))) }}">
                            {{ ucfirst($order->status) }}
                        </span>
                        <p class="text-xs text-gray-500 mt-1">Order Status</p>
                    </div>
                    
                    <!-- Payment Status -->
                    <div class="text-center">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                            {{ $order->payment_status === 'paid' ? 'bg-green-100 text-green-800' :
                               ($order->payment_status === 'pending' ? 'bg-yellow-100 text-yellow-800' :
                               ($order->payment_status === 'failed' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800')) }}">
                            {{ ucfirst($order->payment_status) }}
                        </span>
                        <p class="text-xs text-gray-500 mt-1">Payment Status</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid lg:grid-cols-3 gap-6">
            <!-- Order Items -->
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Order Items</h3>
                    
                    <div class="space-y-4">
                        @foreach($order->orderItems as $item)
                        <div class="flex items-start space-x-4 p-4 border border-gray-200 rounded-lg">
                            <img src="{{ $item->product_image ?: '/images/placeholder.jpg' }}" 
                                 alt="{{ $item->product_name }}" 
                                 class="w-20 h-20 object-cover rounded-lg border border-gray-200 flex-shrink-0">
                            
                            <div class="flex-1">
                                <h4 class="font-medium text-gray-900 mb-1">{{ $item->product_name }}</h4>
                                <p class="text-sm text-gray-600 mb-2">SKU: {{ $item->product_sku }}</p>
                                
                                @if($item->product_options && is_array($item->product_options))
                                    <div class="text-xs text-gray-500 mb-2">
                                        @if(isset($item->product_options['from_puja_kit']) && $item->product_options['from_puja_kit'])
                                            <span class="inline-block bg-orange-100 text-orange-600 px-2 py-1 rounded">
                                                From Kit: {{ $item->product_options['puja_kit_name'] ?? 'Puja Kit' }}
                                            </span>
                                        @endif
                                    </div>
                                @endif
                                
                                <div class="flex items-center justify-between">
                                    <div class="text-sm text-gray-600">
                                        <span>Qty: {{ $item->quantity }}</span>
                                        <span class="mx-2">×</span>
                                        <span>₹{{ number_format($item->price, 2) }}</span>
                                    </div>
                                    <div class="font-semibold text-gray-900">
                                        ₹{{ number_format($item->total, 2) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            
            <!-- Order Summary & Addresses -->
            <div class="space-y-6">
                <!-- Order Summary -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Order Summary</h3>
                    
                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Subtotal</span>
                            <span>₹{{ number_format($order->subtotal, 2) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Tax (18% GST)</span>
                            <span>₹{{ number_format($order->tax_amount, 2) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Shipping</span>
                            <span>
                                @if($order->shipping_amount > 0)
                                    ₹{{ number_format($order->shipping_amount, 2) }}
                                @else
                                    <span class="text-green-600">Free</span>
                                @endif
                            </span>
                        </div>
                        <div class="border-t border-gray-200 pt-3">
                            <div class="flex justify-between items-center">
                                <span class="font-semibold text-gray-900">Total</span>
                                <span class="font-bold text-lg text-vibrant-pink">₹{{ number_format($order->total_amount, 2) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Payment Information -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Payment Information</h3>
                    
                    @foreach($order->payments as $payment)
                    <div class="space-y-2 text-sm {{ !$loop->last ? 'border-b border-gray-200 pb-3 mb-3' : '' }}">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Payment ID</span>
                            <span class="font-medium">{{ $payment->payment_id }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Gateway</span>
                            <span class="capitalize">{{ $payment->gateway }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Method</span>
                            <span class="capitalize">{{ $payment->method }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Amount</span>
                            <span>₹{{ number_format($payment->amount, 2) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Status</span>
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                {{ $payment->status === 'completed' ? 'bg-green-100 text-green-800' :
                                   ($payment->status === 'pending' ? 'bg-yellow-100 text-yellow-800' :
                                   ($payment->status === 'failed' ? 'bg-red-100 text-red-800' : 'bg-gray-100 text-gray-800')) }}">
                                {{ ucfirst($payment->status) }}
                            </span>
                        </div>
                        @if($payment->paid_at)
                        <div class="flex justify-between">
                            <span class="text-gray-600">Paid At</span>
                            <span>{{ $payment->paid_at->format('d M Y, h:i A') }}</span>
                        </div>
                        @endif
                    </div>
                    @endforeach
                </div>

                <!-- Shipping Address -->
                @if($order->shipping_address)
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Shipping Address</h3>
                    <div class="text-sm text-gray-600 space-y-1">
                        <p class="font-medium text-gray-900">{{ $order->shipping_address['first_name'] }} {{ $order->shipping_address['last_name'] }}</p>
                        <p>{{ $order->shipping_address['address_line_1'] }}</p>
                        @if($order->shipping_address['address_line_2'])
                            <p>{{ $order->shipping_address['address_line_2'] }}</p>
                        @endif
                        <p>{{ $order->shipping_address['city'] }}, {{ $order->shipping_address['state'] }} - {{ $order->shipping_address['postal_code'] }}</p>
                        @if($order->shipping_address['phone'])
                            <p class="pt-2">
                                <i data-lucide="phone" class="w-4 h-4 inline mr-1"></i>
                                {{ $order->shipping_address['phone'] }}
                            </p>
                        @endif
                    </div>
                </div>
                @endif
            </div>
        </div>
        
        <!-- Order Notes -->
        @if($order->notes)
        <div class="bg-white rounded-lg shadow-sm p-6 mt-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-3">Order Notes</h3>
            <p class="text-gray-600 text-sm">{{ $order->notes }}</p>
        </div>
        @endif
        
        <!-- Order Actions -->
        @if(in_array($order->status, ['pending', 'processing']))
        <div class="bg-white rounded-lg shadow-sm p-6 mt-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Order Actions</h3>
            
            <div class="flex flex-col sm:flex-row gap-4">
                @if($order->status === 'pending')
                    <button type="button" onclick="cancelOrder('{{ $order->order_number }}')"
                            class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-red-600 bg-red-50 rounded-lg hover:bg-red-100">
                        <i data-lucide="x-circle" class="w-4 h-4 mr-2"></i>
                        Cancel Order
                    </button>
                @endif
                
                @if($order->payment_status === 'failed')
                    <button type="button" onclick="retryPayment('{{ $order->order_number }}')"
                            class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-vibrant-pink rounded-lg hover:bg-vibrant-pink-dark">
                        <i data-lucide="credit-card" class="w-4 h-4 mr-2"></i>
                        Retry Payment
                    </button>
                @endif
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Cancel Order Modal -->
<div id="cancel-modal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg p-6 max-w-md w-mx-4">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Cancel Order</h3>
        <form id="cancel-form" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Reason for cancellation</label>
                <textarea name="cancellation_reason" rows="3" required
                          class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-vibrant-pink focus:border-transparent"
                          placeholder="Please let us know why you're cancelling this order..."></textarea>
            </div>
            <div class="flex space-x-3">
                <button type="button" onclick="closeCancelModal()"
                        class="flex-1 px-4 py-2 text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200">
                    Keep Order
                </button>
                <button type="submit"
                        class="flex-1 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                    Cancel Order
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function cancelOrder(orderNumber) {
    document.getElementById('cancel-form').action = `/orders/${orderNumber}/cancel`;
    document.getElementById('cancel-modal').classList.remove('hidden');
    document.getElementById('cancel-modal').classList.add('flex');
}

function closeCancelModal() {
    document.getElementById('cancel-modal').classList.add('hidden');
    document.getElementById('cancel-modal').classList.remove('flex');
}

function retryPayment(orderNumber) {
    if (confirm('Do you want to retry payment for this order?')) {
        fetch(`/orders/${orderNumber}/retry-payment`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Handle payment retry - integrate with your payment gateway
                alert('Redirecting to payment...');
                window.location.reload();
            } else {
                alert('Failed to retry payment: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while retrying payment.');
        });
    }
}
</script>
@endsection
