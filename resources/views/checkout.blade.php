@extends('layouts.app')

@section('title', 'Checkout - Ritiyana')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-8">
    <!-- Breadcrumb -->
    <div class="flex items-center space-x-2 text-sm text-gray-500 mb-6">
        <a href="{{ route('home') }}" class="hover:text-vibrant-pink">Home</a>
        <i data-lucide="chevron-right" class="w-4 h-4"></i>
        <span class="text-gray-900">Checkout</span>
    </div>

    <div class="grid lg:grid-cols-2 gap-8">
        <!-- Checkout Form -->
        <div class="space-y-6">
            <div class="bg-white rounded-lg shadow-sm border p-6">
                <h2 class="text-xl font-semibold mb-4 flex items-center">
                    <i data-lucide="user" class="w-5 h-5 mr-2"></i>
                    Contact Information
                </h2>
                <form id="checkout-form" class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">First Name *</label>
                            <input type="text" name="first_name" required 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-vibrant-pink focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Last Name *</label>
                            <input type="text" name="last_name" required 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-vibrant-pink focus:border-transparent">
                        </div>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email Address *</label>
                        <input type="email" name="email" required 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-vibrant-pink focus:border-transparent">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Phone Number *</label>
                        <input type="tel" name="phone" required 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-vibrant-pink focus:border-transparent">
                    </div>
                </form>
            </div>

            <!-- Shipping Address -->
            <div class="bg-white rounded-lg shadow-sm border p-6">
                <h2 class="text-xl font-semibold mb-4 flex items-center">
                    <i data-lucide="truck" class="w-5 h-5 mr-2"></i>
                    Shipping Address
                </h2>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Address Line 1 *</label>
                        <input type="text" name="address_line_1" required 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-vibrant-pink focus:border-transparent"
                               placeholder="Street address, P.O. box, etc.">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Address Line 2</label>
                        <input type="text" name="address_line_2" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-vibrant-pink focus:border-transparent"
                               placeholder="Apartment, suite, etc. (optional)">
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">City *</label>
                            <input type="text" name="city" required 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-vibrant-pink focus:border-transparent">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">State *</label>
                            <select name="state" required 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-vibrant-pink focus:border-transparent">
                                <option value="">Select State</option>
                                <option value="andhra-pradesh">Andhra Pradesh</option>
                                <option value="karnataka">Karnataka</option>
                                <option value="tamil-nadu">Tamil Nadu</option>
                                <option value="telangana">Telangana</option>
                                <!-- Add more states -->
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">PIN Code *</label>
                            <input type="text" name="pin_code" required 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-vibrant-pink focus:border-transparent">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payment Method -->
            <div class="bg-white rounded-lg shadow-sm border p-6">
                <h2 class="text-xl font-semibold mb-4 flex items-center">
                    <i data-lucide="credit-card" class="w-5 h-5 mr-2"></i>
                    Payment Method
                </h2>
                <div class="space-y-3">
                    <label class="flex items-center p-3 border rounded-lg cursor-pointer hover:bg-gray-50">
                        <input type="radio" name="payment_method" value="cod" checked class="mr-3">
                        <i data-lucide="banknote" class="w-5 h-5 mr-2 text-green-600"></i>
                        <span>Cash on Delivery</span>
                    </label>
                    <label class="flex items-center p-3 border rounded-lg cursor-pointer hover:bg-gray-50">
                        <input type="radio" name="payment_method" value="online" class="mr-3">
                        <i data-lucide="smartphone" class="w-5 h-5 mr-2 text-blue-600"></i>
                        <span>Online Payment (UPI/Card)</span>
                    </label>
                </div>
            </div>
        </div>

        <!-- Order Summary -->
        <div class="lg:sticky lg:top-8">
            <div class="bg-white rounded-lg shadow-sm border p-6">
                <h2 class="text-xl font-semibold mb-4">Order Summary</h2>
                
                <!-- Cart Items -->
                <div id="checkout-cart-items" class="space-y-4 mb-6">
                    <!-- Items will be populated by JavaScript -->
                </div>
                
                <!-- Order Totals -->
                <div class="border-t pt-4 space-y-2">
                    <div class="flex justify-between">
                        <span>Subtotal</span>
                        <span id="subtotal">₹0</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Delivery Charges</span>
                        <span id="delivery-charges">₹50</span>
                    </div>
                    <div class="flex justify-between font-semibold text-lg border-t pt-2">
                        <span>Total</span>
                        <span id="final-total">₹50</span>
                    </div>
                </div>
                
                <!-- Place Order Button -->
                <button onclick="placeOrder()" 
                        class="w-full mt-6 bg-vibrant-pink hover:bg-vibrant-pink-dark text-white py-3 px-6 rounded-lg font-medium transition duration-200 flex items-center justify-center">
                    <i data-lucide="check-circle" class="w-5 h-5 mr-2"></i>
                    Place Order
                </button>
                
                <!-- Continue Shopping -->
                <a href="{{ route('home') }}" 
                   class="block w-full mt-3 text-center py-2 text-vibrant-pink hover:text-vibrant-pink-dark font-medium">
                    Continue Shopping
                </a>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    lucide.createIcons();
    loadCheckoutData();
});

function loadCheckoutData() {
    // Get cart data (you can modify this based on how you store cart data)
    const cartData = JSON.parse(localStorage.getItem('cart')) || { items: [], total: 0 };
    
    if (cartData.items.length === 0) {
        window.location.href = "{{ route('home') }}";
        return;
    }
    
    displayCheckoutItems(cartData);
    calculateTotals(cartData);
}

function displayCheckoutItems(cartData) {
    const container = document.getElementById('checkout-cart-items');
    
    container.innerHTML = cartData.items.map(item => `
        <div class="flex items-center space-x-4 p-3 border rounded-lg">
            <img src="${item.image}" alt="${item.name}" class="w-16 h-16 object-cover rounded-lg">
            <div class="flex-1">
                <h3 class="font-medium">${item.name}</h3>
                <p class="text-sm text-gray-600">Quantity: ${item.quantity}</p>
                <p class="font-semibold text-vibrant-pink">₹${item.price} each</p>
            </div>
            <div class="text-right">
                <p class="font-semibold">₹${item.price * item.quantity}</p>
            </div>
        </div>
    `).join('');
}

function calculateTotals(cartData) {
    const subtotal = cartData.total;
    const deliveryCharges = subtotal > 500 ? 0 : 50;
    const finalTotal = subtotal + deliveryCharges;
    
    document.getElementById('subtotal').textContent = `₹${subtotal}`;
    document.getElementById('delivery-charges').textContent = deliveryCharges === 0 ? 'FREE' : `₹${deliveryCharges}`;
    document.getElementById('final-total').textContent = `₹${finalTotal}`;
}

function placeOrder() {
    const form = document.getElementById('checkout-form');
    const formData = new FormData(form);
    
    // Basic validation
    if (!form.checkValidity()) {
        form.reportValidity();
        return;
    }
    
    // Get cart data
    const cartData = JSON.parse(localStorage.getItem('cart')) || { items: [], total: 0 };
    
    // Prepare order data
    const orderData = {
        customer: Object.fromEntries(formData),
        items: cartData.items,
        total: cartData.total,
        delivery_charges: cartData.total > 500 ? 0 : 50,
        final_total: cartData.total + (cartData.total > 500 ? 0 : 50),
        payment_method: formData.get('payment_method')
    };
    
    // Submit order (replace with your actual API endpoint)
    fetch("{{ route('test') }}", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify(orderData)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Clear cart
            localStorage.removeItem('cart');
            // Redirect to order success page
            window.location.href = `{{ route('test') }}?order_id=${data.order_id}`;
        } else {
            alert('Order placement failed. Please try again.');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred. Please try again.');
    });
}
</script>
@endsection
