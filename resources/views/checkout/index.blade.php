@extends('layouts.app')

@section('title', 'Checkout - Ritiyana')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="lg:grid lg:grid-cols-12 lg:gap-8">
            <!-- Order Summary -->
            <div class="lg:col-span-5 lg:order-2">
                <div class="bg-white rounded-lg shadow-md p-6 sticky top-8">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">Order Summary</h2>
                    
                    <!-- Amount Limit Warning -->
                    @if(isset($amountExceeded) && $amountExceeded)
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                        <div class="flex">
                            <i data-lucide="alert-triangle" class="w-5 h-5 text-yellow-400 mr-2 mt-0.5"></i>
                            <div>
                                <h3 class="text-sm font-medium text-yellow-800">Payment Limit Exceeded</h3>
                                <p class="text-sm text-yellow-700 mt-1">
                                    Your order total of ₹{{ number_format($totalAmount, 2) }} exceeds the maximum allowed limit of 
                                    ₹{{ number_format($maxAllowed, 2) }} for {{ config('cashfree.mode') }} environment.
                                </p>
                                <div class="mt-3">
                                    <p class="text-sm text-yellow-700">
                                        <strong>Options:</strong>
                                    </p>
                                    <ul class="text-sm text-yellow-700 mt-1 ml-4 list-disc">
                                        <li>Use <strong>Cash on Delivery</strong> (no limits)</li>
                                        <li>Remove some items to reduce total</li>
                                        @if(config('cashfree.mode') === 'sandbox')
                                        <li>Contact support to enable production mode</li>
                                        @endif
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                    
                    <!-- Cart Items -->
                    <div class="space-y-4 mb-6">
                        @foreach($cartItems as $item)
                        <div class="flex items-center space-x-3">
                            <img src="{{ $item->display_image ?? asset('images/placeholder.jpg') }}" 
                                 alt="{{ $item->display_name ?? 'Product' }}" 
                                 class="w-16 h-16 object-cover rounded-lg">
                            <div class="flex-1">
                                <h3 class="text-sm font-medium text-gray-900">{{ $item->display_name ?? 'Unknown Product' }}</h3>
                                <p class="text-xs text-gray-600">Qty: {{ $item->quantity }}</p>
                                <p class="text-sm font-semibold text-gray-900">₹{{ number_format($item->subtotal, 2) }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    <!-- Totals -->
                    <div class="border-t border-gray-200 pt-4">
                        <div class="space-y-2">
                            <div class="flex justify-between text-sm">
                                <span>Subtotal</span>
                                <span>₹{{ number_format($cartTotal, 2) }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span>Tax (18% GST)</span>
                                <span>₹{{ number_format($taxAmount, 2) }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span>Shipping</span>
                                <span>
                                    @if($shippingAmount > 0)
                                        ₹{{ number_format($shippingAmount, 2) }}
                                    @else
                                        <span class="text-green-600">Free</span>
                                    @endif
                                </span>
                            </div>
                            <div class="flex justify-between font-bold text-lg pt-2 border-t border-gray-200">
                                <span>Total</span>
                                <span class="text-vibrant-pink">₹{{ number_format($totalAmount, 2) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Checkout Form -->
            <div class="lg:col-span-7">
                <form id="checkout-form">
                    @csrf
                    
                    <!-- Addresses -->
                    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Shipping Information</h2>
                        
                        @if($addresses && $addresses->count() > 0)
                            <div class="space-y-4">
                                <div>
                                    <label for="billing_address_id" class="block text-sm font-medium text-gray-700 mb-2">Billing Address</label>
                                    <select id="billing_address_id" name="billing_address_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-vibrant-pink" required>
                                        <option value="">Select billing address</option>
                                        @foreach($addresses as $address)
                                        <option value="{{ $address->id }}" {{ $defaultAddress && $defaultAddress->id == $address->id ? 'selected' : '' }}>
                                            {{ $address->full_name }}, {{ $address->street_address }}, {{ $address->city }}, {{ $address->state }} - {{ $address->postal_code }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                                
                                <div>
                                    <label for="shipping_address_id" class="block text-sm font-medium text-gray-700 mb-2">Shipping Address</label>
                                    <select id="shipping_address_id" name="shipping_address_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-vibrant-pink" required>
                                        <option value="">Select shipping address</option>
                                        @foreach($addresses as $address)
                                        <option value="{{ $address->id }}" {{ $defaultAddress && $defaultAddress->id == $address->id ? 'selected' : '' }}>
                                            {{ $address->full_name }}, {{ $address->street_address }}, {{ $address->city }}, {{ $address->state }} - {{ $address->postal_code }}
                                        </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        @else
                            <div class="text-center py-8">
                                <p class="text-gray-600 mb-4">You need to add an address to proceed with checkout.</p>
                                <a href="{{ route('addresses.create') }}" class="inline-flex items-center px-4 py-2 bg-vibrant-pink text-white rounded-lg hover:bg-vibrant-pink-dark">
                                    <i data-lucide="plus" class="w-4 h-4 mr-2"></i>
                                    Add Address
                                </a>
                            </div>
                        @endif
                    </div>

                    <!-- Payment Method -->
                    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Payment Method</h2>
                        
                        <div class="space-y-4">
                            <!-- Cash on Delivery - Always Available -->
                            <div class="flex items-center p-4 border border-gray-200 rounded-lg">
                                <input type="radio" id="cod" name="payment_method" value="cod" 
                                       class="h-4 w-4 text-vibrant-pink focus:ring-vibrant-pink border-gray-300"
                                       {{ (isset($amountExceeded) && $amountExceeded) ? 'checked' : '' }}>
                                <label for="cod" class="ml-3 flex-1">
                                    <div class="font-medium text-gray-900">Cash on Delivery</div>
                                    <div class="text-sm text-gray-600">Pay when your order is delivered (No amount limits)</div>
                                </label>
                                <i data-lucide="banknote" class="w-6 h-6 text-gray-400"></i>
                            </div>
                            
                            <!-- Online Payment - May be disabled -->
                            <div class="flex items-center p-4 border border-gray-200 rounded-lg {{ (isset($amountExceeded) && $amountExceeded) ? 'opacity-50' : '' }}">
                                <input type="radio" id="online" name="payment_method" value="online" 
                                       class="h-4 w-4 text-vibrant-pink focus:ring-vibrant-pink border-gray-300"
                                       {{ (isset($amountExceeded) && $amountExceeded) ? 'disabled' : (!(isset($amountExceeded) && $amountExceeded) ? 'checked' : '') }}>
                                <label for="online" class="ml-3 flex-1 {{ (isset($amountExceeded) && $amountExceeded) ? 'cursor-not-allowed' : '' }}">
                                    <div class="font-medium text-gray-900">Online Payment</div>
                                    @if(isset($amountExceeded) && $amountExceeded)
                                        <div class="text-sm text-red-600">Disabled - Amount exceeds limit</div>
                                    @else
                                        <div class="text-sm text-gray-600">Pay securely with Card, UPI, Net Banking</div>
                                    @endif
                                </label>
                                <i data-lucide="credit-card" class="w-6 h-6 text-gray-400"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Order Notes -->
                    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                        <h2 class="text-lg font-semibold text-gray-900 mb-4">Order Notes (Optional)</h2>
                        <textarea name="notes" id="notes" rows="3" 
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-vibrant-pink"
                                  placeholder="Any special instructions for your order..."></textarea>
                    </div>

                    <!-- Place Order Button -->
                    @if($addresses && $addresses->count() > 0)
                    <button type="submit" id="place-order-btn" 
                            class="w-full bg-vibrant-pink text-white py-4 px-6 rounded-lg text-lg font-semibold hover:bg-vibrant-pink-dark disabled:opacity-50 disabled:cursor-not-allowed">
                        <span id="btn-text">Place Order</span>
                        <span id="btn-loading" class="hidden">
                            <i data-lucide="loader-2" class="w-5 h-5 animate-spin inline mr-2"></i>
                            Processing...
                        </span>
                    </button>
                    @endif
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Payment Gateway Modal -->
<div id="payment-modal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-1/2 lg:w-1/3 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="text-center">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Complete Your Payment</h3>
                <div id="payment-details" class="mb-4"></div>
                <div class="flex justify-center space-x-4">
                    <button id="proceed-payment" class="bg-vibrant-pink text-white px-6 py-2 rounded-lg hover:bg-vibrant-pink-dark">
                        Proceed to Payment
                    </button>
                    <button id="cancel-payment" class="bg-gray-300 text-gray-700 px-6 py-2 rounded-lg hover:bg-gray-400">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('checkout-form');
    const submitBtn = document.getElementById('place-order-btn');
    const btnText = document.getElementById('btn-text');
    const btnLoading = document.getElementById('btn-loading');
    const paymentModal = document.getElementById('payment-modal');
    
    let currentPaymentData = null;

    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Disable button and show loading
        submitBtn.disabled = true;
        btnText.classList.add('hidden');
        btnLoading.classList.remove('hidden');
        
        const formData = new FormData(form);
        
        fetch('{{ route("checkout.process") }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                if (data.show_payment_gateway && data.payment_data) {
                    // Show payment gateway
                    currentPaymentData = data.payment_data;
                    showPaymentModal(data.payment_data);
                } else if (data.redirect_url) {
                    // Redirect for COD orders
                    window.location.href = data.redirect_url;
                }
            } else {
                alert('Error: ' + data.message);
                resetButton();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred. Please try again.');
            resetButton();
        });
    });
    
    function showPaymentModal(paymentData) {
        document.getElementById('payment-details').innerHTML = `
            <p><strong>Order ID:</strong> ${paymentData.order_id}</p>
            <p><strong>Amount:</strong> ₹${paymentData.amount}</p>
            <p><strong>Customer:</strong> ${paymentData.customer_name}</p>
        `;
        paymentModal.classList.remove('hidden');
    }
    
    document.getElementById('proceed-payment').addEventListener('click', function() {
        if (currentPaymentData && currentPaymentData.payment_session_id) {
            // Redirect to payment gateway
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route("payment.process") }}';
            
            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            form.appendChild(csrfToken);
            
            const orderIdInput = document.createElement('input');
            orderIdInput.type = 'hidden';
            orderIdInput.name = 'order_id';
            orderIdInput.value = currentPaymentData.order_id;
            form.appendChild(orderIdInput);
            
            const paymentIdInput = document.createElement('input');
            paymentIdInput.type = 'hidden';
            paymentIdInput.name = 'payment_id';
            paymentIdInput.value = currentPaymentData.cf_order_id;
            form.appendChild(paymentIdInput);
            
            document.body.appendChild(form);
            form.submit();
        }
    });
    
    document.getElementById('cancel-payment').addEventListener('click', function() {
        paymentModal.classList.add('hidden');
        resetButton();
    });
    
    function resetButton() {
        submitBtn.disabled = false;
        btnText.classList.remove('hidden');
        btnLoading.classList.add('hidden');
    }
});
</script>
@endsection
