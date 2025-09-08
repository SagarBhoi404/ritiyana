<!-- Cart Sidebar Overlay -->
<div id="cart-overlay" class="fixed inset-0 bg-black bg-opacity-50 z-40 hidden" onclick="toggleCart()"></div>

<!-- Cart Sidebar -->
<div id="cart-sidebar" class="fixed right-0 top-0 h-full w-96 bg-white shadow-lg transform translate-x-full transition-transform duration-300 z-50">
    <div class="flex flex-col h-full">
        <!-- Header -->
        <div class="flex items-center justify-between p-4 border-b">
            <div class="flex items-center gap-2">
                <h2 class="text-lg font-semibold">Your Cart</h2>
                <span class="text-sm text-gray-500">(<span id="cart-sidebar-count">0</span> items)</span>
            </div>
            <div class="flex items-center gap-2">
                <button onclick="clearCart()" class="text-sm text-red-500 hover:text-red-700">Clear All</button>
                <button onclick="toggleCart()" class="p-1 hover:bg-gray-100 rounded">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>
        </div>
        
        <!-- Cart Items -->
        <div id="cart-items" class="flex-1 overflow-y-auto p-4 space-y-3">
            <div class="flex flex-col items-center justify-center h-64 text-gray-500">
                <i data-lucide="shopping-bag" class="w-16 h-16 mb-4"></i>
                <h3 class="text-lg font-medium mb-2">Your cart is empty</h3>
                <p class="text-sm">Add some puja items to get started</p>
            </div>
        </div>
        
        <!-- Cart Footer -->
        <div class="border-t p-4 bg-gray-50">
            <div class="flex justify-between text-sm mb-2">
                <span>Delivery in</span>
                <span class="font-medium text-green-600">10 minutes</span>
            </div>
            <div class="flex justify-between text-sm mb-4">
                <span>Delivery charge</span>
                <span class="font-medium text-green-600">FREE</span>
            </div>
            
            <div class="flex justify-between items-center mb-4 text-lg font-semibold">
                <span>Total</span>
                <span>₹<span id="cart-total">0</span></span>
            </div>
            
            <button onclick="proceedToCheckout()"  class="w-full bg-vibrant-pink hover:bg-vibrant-pink-dark text-white font-medium py-3 rounded-lg transition-colors mb-2">
                Proceed to Checkout
            </button>
            <p class="text-xs text-gray-500 text-center">Free delivery on orders above ₹199</p>
        </div>
    </div>
</div>
