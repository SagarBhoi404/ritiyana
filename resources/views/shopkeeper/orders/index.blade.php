<!-- resources/views/shopkeeper/orders/index.blade.php -->
@extends('layouts.shopkeeper')

@section('title', 'Orders')
@section('breadcrumb', 'Orders')

@section('content')
<div class="px-4 sm:px-6 lg:px-8 py-8">
    <!-- Page Header -->
    <div class="mb-8">
        <div class="sm:flex sm:items-center sm:justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Order Management</h1>
                <p class="mt-2 text-sm text-gray-700">Track and manage your customer orders</p>
            </div>
            <div class="mt-4 sm:mt-0">
                <form method="GET" action="{{ route('vendor.orders.index') }}" class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i data-lucide="search" class="w-4 h-4 text-gray-400"></i>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}" 
                        placeholder="Search orders..." 
                        class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-purple-500 focus:border-purple-500">
                </form>
            </div>
        </div>
    </div>

    <!-- Order Stats -->
    <div class="grid grid-cols-1 md:grid-cols-5 gap-6 mb-8">
        <div class="bg-white rounded-xl p-6 border border-gray-200 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Orders</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ $stats['total'] ?? 0 }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                    <i data-lucide="shopping-cart" class="w-6 h-6 text-blue-600"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl p-6 border border-gray-200 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Pending</p>
                    <p class="text-2xl font-bold text-yellow-600 mt-1">{{ $stats['pending'] ?? 0 }}</p>
                </div>
                <div class="w-12 h-12 bg-yellow-100 rounded-xl flex items-center justify-center">
                    <i data-lucide="clock" class="w-6 h-6 text-yellow-600"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl p-6 border border-gray-200 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Processing</p>
                    <p class="text-2xl font-bold text-blue-600 mt-1">{{ $stats['processing'] ?? 0 }}</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                    <i data-lucide="package" class="w-6 h-6 text-blue-600"></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl p-6 border border-gray-200 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Shipped</p>
                    <p class="text-2xl font-bold text-purple-600 mt-1">{{ $stats['shipped'] ?? 0 }}</p>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                    <i data-lucide="truck" class="w-6 h-6 text-purple-600"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl p-6 border border-gray-200 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Delivered</p>
                    <p class="text-2xl font-bold text-green-600 mt-1">{{ $stats['delivered'] ?? 0 }}</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                    <i data-lucide="check-circle" class="w-6 h-6 text-green-600"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-xl border border-gray-200 p-4 mb-6">
        <form method="GET" action="{{ route('vendor.orders.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Status Filter</label>
                <select name="status" class="block w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-purple-500 focus:border-purple-500">
                    <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>All Orders</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                    <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Processing</option>
                    <option value="shipped" {{ request('status') == 'shipped' ? 'selected' : '' }}>Shipped</option>
                    <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>Delivered</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">From Date</label>
                <input type="date" name="date_from" value="{{ request('date_from') }}" 
                    class="block w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-purple-500 focus:border-purple-500">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">To Date</label>
                <input type="date" name="date_to" value="{{ request('date_to') }}" 
                    class="block w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-purple-500 focus:border-purple-500">
            </div>
            
            <div class="flex items-end">
                <button type="submit" class="w-full px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 text-sm font-medium">
                    Apply Filters
                </button>
            </div>
        </form>
    </div>

    <!-- Orders Table -->
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden shadow-sm">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Recent Orders</h3>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order ID</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Earning</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
    @forelse ($orders as $orderData)
        <tr class="hover:bg-gray-50 {{ $orderData->status === 'pending' ? 'bg-yellow-50' : '' }}">
            <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm font-medium text-gray-900">#{{ $orderData->order_number }}</div>
                <div class="text-xs text-gray-500">{{ $orderData->item_count }} item(s)</div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
                <div class="flex items-center">
                    @if($orderData->customer && $orderData->customer->profile_image)
                        <img class="w-8 h-8 rounded-full object-cover" 
                            src="{{ asset('storage/' . $orderData->customer->profile_image) }}" 
                            alt="{{ $orderData->customer->full_name }}">
                    @else
                        <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center">
                            <span class="text-purple-600 font-semibold text-sm">
                                {{ $orderData->customer ? substr($orderData->customer->first_name, 0, 1) . substr($orderData->customer->last_name ?? 'N', 0, 1) : 'NA' }}
                            </span>
                        </div>
                    @endif
                    <div class="ml-3">
                        <div class="text-sm font-medium text-gray-900">
                            {{ $orderData->customer ? $orderData->customer->full_name : 'Unknown' }}
                        </div>
                        <div class="text-sm text-gray-500">{{ $orderData->customer->email ?? 'N/A' }}</div>
                    </div>
                </div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm text-gray-900">{{ $orderData->created_at->format('M d, Y') }}</div>
                <div class="text-sm text-gray-500">{{ $orderData->created_at->format('h:i A') }}</div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $orderData->status_badge }}">
                    {{ ucfirst($orderData->status) }}
                </span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm font-semibold text-gray-900">₹{{ number_format($orderData->total_amount, 2) }}</div>
                @if($orderData->commission_amount > 0)
                    <div class="text-xs text-gray-500">Commission: ₹{{ number_format($orderData->commission_amount, 2) }}</div>
                @endif
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm font-semibold text-green-600">₹{{ number_format($orderData->vendor_earning, 2) }}</div>
                @if($orderData->commission_rate > 0)
                    <div class="text-xs text-gray-500">{{ number_format($orderData->commission_rate, 1) }}% rate</div>
                @endif
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                <div class="flex items-center space-x-2">
                    <a href="{{ route('vendor.orders.show', $orderData->id) }}" 
                        class="text-purple-600 hover:text-purple-900 p-1 rounded" title="View Order">
                        <i data-lucide="eye" class="w-4 h-4"></i>
                    </a>
                    
                    @if($orderData->status === 'pending')
                        <button onclick="updateOrderStatus({{ $orderData->id }}, 'confirmed')" 
                            class="text-green-600 hover:text-green-900 p-1 rounded" title="Confirm Order">
                            <i data-lucide="check" class="w-4 h-4"></i>
                        </button>
                    @endif
                    
                    @if(in_array($orderData->status, ['confirmed', 'processing']))
                        <button onclick="openShippingModal({{ $orderData->id }})" 
                            class="text-blue-600 hover:text-blue-900 p-1 rounded" title="Mark as Shipped">
                            <i data-lucide="truck" class="w-4 h-4"></i>
                        </button>
                    @endif
                    
                    {{-- <a href="{{ route('vendor.orders.invoice', $orderData->id) }}" 
                        class="text-gray-600 hover:text-gray-900 p-1 rounded" title="Print Invoice" target="_blank">
                        <i data-lucide="printer" class="w-4 h-4"></i>
                    </a> --}}
                </div>
            </td>
        </tr>
    @empty
        <tr>
            <td colspan="7" class="px-6 py-12 text-center">
                <div class="text-gray-500">
                    <i data-lucide="package" class="w-12 h-12 mx-auto mb-4 text-gray-300"></i>
                    <p class="text-lg font-medium mb-2">No orders found</p>
                    <p class="text-sm">Orders from customers will appear here.</p>
                </div>
            </td>
        </tr>
    @endforelse
</tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="px-6 py-3 border-t border-gray-200 bg-gray-50">
            <div class="flex items-center justify-between">
                <div class="text-sm text-gray-700">
                    Showing <span class="font-medium">{{ $orders->firstItem() ?? 0 }}</span> to 
                    <span class="font-medium">{{ $orders->lastItem() ?? 0 }}</span> of 
                    <span class="font-medium">{{ $orders->total() }}</span> orders
                </div>
                <div>
                    {{ $orders->withQueryString()->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Shipping Modal -->
<div id="shippingModal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-full max-w-md shadow-lg rounded-xl bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900">Mark Order as Shipped</h3>
                <button onclick="closeShippingModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>

            <form id="shippingForm" method="POST">
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
                    <button type="button" onclick="closeShippingModal()" 
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

@if (session('error'))
    <div class="fixed top-4 right-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded z-50">
        {{ session('error') }}
    </div>
@endif

<script>
    function openShippingModal(orderId) {
        const modal = document.getElementById('shippingModal');
        const form = document.getElementById('shippingForm');
        form.action = `/shopkeeper/orders/${orderId}/update-status`;
        modal.classList.remove('hidden');
    }

    function closeShippingModal() {
        const modal = document.getElementById('shippingModal');
        modal.classList.add('hidden');
        document.getElementById('tracking_number').value = '';
        document.getElementById('vendor_notes').value = '';
    }

    function updateOrderStatus(orderId, status) {
        if (confirm(`Are you sure you want to ${status} this order?`)) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/shopkeeper/orders/${orderId}/update-status`;
            
            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = '{{ csrf_token() }}';
            
            const methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            methodInput.value = 'PATCH';
            
            const statusInput = document.createElement('input');
            statusInput.type = 'hidden';
            statusInput.name = 'status';
            statusInput.value = status;
            
            form.appendChild(csrfToken);
            form.appendChild(methodInput);
            form.appendChild(statusInput);
            document.body.appendChild(form);
            form.submit();
        }
    }

    // Close modal when clicking outside
    document.getElementById('shippingModal')?.addEventListener('click', function(e) {
        if (e.target === this) {
            closeShippingModal();
        }
    });

    // Auto-hide alerts
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
