@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold mb-4">My Orders</h1>
        <p class="text-gray-600">Track and manage your puja kit orders</p>
    </div>

    <!-- Order Tabs -->
    <div class="mb-8">
        <div class="flex flex-wrap gap-2 border-b">
            <button onclick="filterOrders('all')" class="order-tab px-6 py-3 font-medium border-b-2 border-vibrant-pink text-vibrant-pink" data-status="all">
                All Orders
            </button>
            <button onclick="filterOrders('pending')" class="order-tab px-6 py-3 font-medium border-b-2 border-transparent text-gray-500 hover:text-gray-700" data-status="pending">
                Pending
            </button>
            <button onclick="filterOrders('delivered')" class="order-tab px-6 py-3 font-medium border-b-2 border-transparent text-gray-500 hover:text-gray-700" data-status="delivered">
                Delivered
            </button>
            <button onclick="filterOrders('cancelled')" class="order-tab px-6 py-3 font-medium border-b-2 border-transparent text-gray-500 hover:text-gray-700" data-status="cancelled">
                Cancelled
            </button>
        </div>
    </div>

    <!-- Orders List -->
    <div id="orders-container" class="space-y-6">
        <!-- Delivered Order -->
        <div class="order-item bg-white rounded-2xl border border-gray-200 p-6" data-status="delivered">
            <div class="flex items-start justify-between mb-4">
                <div>
                    <div class="flex items-center gap-2 mb-2">
                        <h3 class="font-semibold">Order #RIT001234</h3>
                        <span class="bg-green-100 text-green-800 text-xs font-medium px-2 py-1 rounded-full flex items-center gap-1">
                            <i data-lucide="check-circle" class="w-3 h-3"></i>
                            Delivered
                        </span>
                    </div>
                    <p class="text-gray-600 text-sm">Aug 25, 2024</p>
                    <p class="text-green-600 text-sm font-medium">✓ Delivered in 8 mins</p>
                </div>
                <div class="text-right">
                    <p class="text-lg font-semibold">₹497</p>
                    <button class="text-vibrant-pink text-sm hover:underline mt-1">Reorder</button>
                </div>
            </div>
            
            <div class="space-y-3">
                <div class="flex items-center gap-3">
                    <img src="{{ asset('images/daily-puja-kit.jpg') }}" alt="Complete Daily Puja Kit" class="w-12 h-12 object-cover rounded-lg">
                    <div class="flex-1">
                        <h4 class="font-medium">Complete Daily Puja Kit</h4>
                        <p class="text-gray-600 text-sm">Qty: 1 • ₹299</p>
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    <img src="{{ asset('images/daily-puja-kit.jpg') }}" alt="Incense Sticks Pack" class="w-12 h-12 object-cover rounded-lg">
                    <div class="flex-1">
                        <h4 class="font-medium">Incense Sticks Pack</h4>
                        <p class="text-gray-600 text-sm">Qty: 2 • ₹99</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pending Order -->
        <div class="order-item bg-white rounded-2xl border border-gray-200 p-6" data-status="pending">
            <div class="flex items-start justify-between mb-4">
                <div>
                    <div class="flex items-center gap-2 mb-2">
                        <h3 class="font-semibold">Order #RIT001235</h3>
                        <span class="bg-yellow-100 text-yellow-800 text-xs font-medium px-2 py-1 rounded-full flex items-center gap-1">
                            <i data-lucide="clock" class="w-3 h-3"></i>
                            Pending
                        </span>
                    </div>
                    <p class="text-gray-600 text-sm">Aug 26, 2024</p>
                    <p class="text-yellow-600 text-sm font-medium">🚚 Estimated delivery in 12 mins</p>
                </div>
                <div class="text-right">
                    <p class="text-lg font-semibold">₹599</p>
                    <button class="text-red-500 text-sm hover:underline mt-1">Cancel Order</button>
                </div>
            </div>
            
            <div class="space-y-3">
                <div class="flex items-center gap-3">
                    <img src="{{ asset('images/festival-kit.jpg') }}" alt="Ganesh Chaturthi Special Kit" class="w-12 h-12 object-cover rounded-lg">
                    <div class="flex-1">
                        <h4 class="font-medium">Ganesh Chaturthi Special Kit</h4>
                        <p class="text-gray-600 text-sm">Qty: 1 • ₹599</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Cancelled Order -->
        <div class="order-item bg-white rounded-2xl border border-gray-200 p-6" data-status="cancelled">
            <div class="flex items-start justify-between mb-4">
                <div>
                    <div class="flex items-center gap-2 mb-2">
                        <h3 class="font-semibold">Order #RIT001236</h3>
                        <span class="bg-red-100 text-red-800 text-xs font-medium px-2 py-1 rounded-full flex items-center gap-1">
                            <i data-lucide="x-circle" class="w-3 h-3"></i>
                            Cancelled
                        </span>
                    </div>
                    <p class="text-gray-600 text-sm">Aug 20, 2024</p>
                    <p class="text-red-600 text-sm">❌ Cancelled by user</p>
                </div>
                <div class="text-right">
                    <p class="text-lg font-semibold">₹799</p>
                    <button class="text-vibrant-pink text-sm hover:underline mt-1">Reorder</button>
                </div>
            </div>
            
            <div class="space-y-3">
                <div class="flex items-center gap-3">
                    <img src="{{ asset('images/custom-kit.jpg') }}" alt="Custom Puja Kit" class="w-12 h-12 object-cover rounded-lg">
                    <div class="flex-1">
                        <h4 class="font-medium">Custom Puja Kit</h4>
                        <p class="text-gray-600 text-sm">Qty: 1 • ₹799</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Another Delivered Order -->
        <div class="order-item bg-white rounded-2xl border border-gray-200 p-6" data-status="delivered">
            <div class="flex items-start justify-between mb-4">
                <div>
                    <div class="flex items-center gap-2 mb-2">
                        <h3 class="font-semibold">Order #RIT001237</h3>
                        <span class="bg-green-100 text-green-800 text-xs font-medium px-2 py-1 rounded-full flex items-center gap-1">
                            <i data-lucide="check-circle" class="w-3 h-3"></i>
                            Delivered
                        </span>
                    </div>
                    <p class="text-gray-600 text-sm">Aug 22, 2024</p>
                    <p class="text-green-600 text-sm font-medium">✓ Delivered in 6 mins</p>
                </div>
                <div class="text-right">
                    <p class="text-lg font-semibold">₹399</p>
                    <button class="text-vibrant-pink text-sm hover:underline mt-1">Reorder</button>
                </div>
            </div>
            
            <div class="space-y-3">
                <div class="flex items-center gap-3">
                    <img src="{{ asset('images/eco-friendly.jpg') }}" alt="Eco-Friendly Puja Set" class="w-12 h-12 object-cover rounded-lg">
                    <div class="flex-1">
                        <h4 class="font-medium">Eco-Friendly Puja Set</h4>
                        <p class="text-gray-600 text-sm">Qty: 1 • ₹399</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Empty State -->
    <div id="empty-state" class="text-center py-12 hidden">
        <div class="text-gray-400 mb-4">
            <i data-lucide="package" class="w-16 h-16 mx-auto"></i>
        </div>
        <h3 class="text-lg font-medium mb-2">No orders found</h3>
        <p class="text-gray-500 mb-6">Orders matching this status will appear here</p>
        <a href="{{ route('all-kits') }}" class="inline-block bg-vibrant-pink hover:bg-vibrant-pink-dark text-white font-medium px-6 py-3 rounded-lg transition-colors">
            Start Shopping
        </a>
    </div>
</div>

<script>
function filterOrders(status) {
    const orders = document.querySelectorAll('.order-item');
    const tabs = document.querySelectorAll('.order-tab');
    const emptyState = document.getElementById('empty-state');
    
    let visibleCount = 0;
    
    // Reset tab styles
    tabs.forEach(tab => {
        tab.classList.remove('border-vibrant-pink', 'text-vibrant-pink');
        tab.classList.add('border-transparent', 'text-gray-500');
    });
    
    // Highlight active tab
    const activeTab = document.querySelector(`[data-status="${status}"]`);
    activeTab.classList.remove('border-transparent', 'text-gray-500');
    activeTab.classList.add('border-vibrant-pink', 'text-vibrant-pink');
    
    // Filter orders
    orders.forEach(order => {
        if (status === 'all' || order.dataset.status === status) {
            order.style.display = 'block';
            visibleCount++;
        } else {
            order.style.display = 'none';
        }
    });
    
    // Show/hide empty state
    if (visibleCount === 0) {
        emptyState.classList.remove('hidden');
    } else {
        emptyState.classList.add('hidden');
    }
}
</script>
@endsection
