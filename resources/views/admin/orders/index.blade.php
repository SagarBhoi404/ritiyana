<!-- resources/views/admin/orders/index.blade.php -->
@extends('layouts.admin')

@section('title', 'Orders Management')
@section('breadcrumb', 'Orders')

@section('content')
<div class="p-6">
    <!-- Page Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Orders Management</h1>
                <p class="text-gray-600 mt-1">Track and manage all customer orders</p>
            </div>
            <div class="flex items-center space-x-3">
                <button id="filterBtn" class="flex items-center px-4 py-2 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                    <i data-lucide="filter" class="w-4 h-4 mr-2"></i>
                    Filter
                </button>
                <a href="{{ route('admin.orders.export') }}" class="flex items-center px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700">
                    <i data-lucide="download" class="w-4 h-4 mr-2"></i>
                    Export
                </a>
            </div>
        </div>
    </div>

    <!-- Filters Panel (Initially Hidden) -->
    <div id="filtersPanel" class="mb-6 p-4 bg-white rounded-xl border border-gray-200 hidden">
        <form method="GET" action="{{ route('admin.orders.index') }}" class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="Order number, customer name..." 
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select name="status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                    <option value="">All Status</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Processing</option>
                    <option value="shipped" {{ request('status') == 'shipped' ? 'selected' : '' }}>Shipped</option>
                    <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>Delivered</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Payment Status</label>
                <select name="payment_status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                    <option value="">All Payments</option>
                    <option value="pending" {{ request('payment_status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="paid" {{ request('payment_status') == 'paid' ? 'selected' : '' }}>Paid</option>
                    <option value="failed" {{ request('payment_status') == 'failed' ? 'selected' : '' }}>Failed</option>
                    <option value="refunded" {{ request('payment_status') == 'refunded' ? 'selected' : '' }}>Refunded</option>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Date From</label>
                <input type="date" name="date_from" value="{{ request('date_from') }}" 
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
            </div>
            
            <div class="flex items-end">
                <button type="submit" class="w-full px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700">
                    Apply Filters
                </button>
            </div>
        </form>
    </div>

    <!-- Order Stats -->
    <div class="grid grid-cols-1 md:grid-cols-5 gap-6 mb-8">
        <div class="bg-white rounded-xl p-6 border border-gray-200">
            <div class="text-center">
                <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center mx-auto mb-3">
                    <i data-lucide="shopping-cart" class="w-6 h-6 text-blue-600"></i>
                </div>
                <p class="text-2xl font-bold text-gray-900">{{ number_format($stats['total']) }}</p>
                <p class="text-sm text-gray-600">Total Orders</p>
            </div>
        </div>
        
        <div class="bg-white rounded-xl p-6 border border-gray-200">
            <div class="text-center">
                <div class="w-12 h-12 bg-yellow-100 rounded-xl flex items-center justify-center mx-auto mb-3">
                    <i data-lucide="clock" class="w-6 h-6 text-yellow-600"></i>
                </div>
                <p class="text-2xl font-bold text-yellow-600">{{ number_format($stats['pending']) }}</p>
                <p class="text-sm text-gray-600">Pending</p>
            </div>
        </div>
        
        <div class="bg-white rounded-xl p-6 border border-gray-200">
            <div class="text-center">
                <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center mx-auto mb-3">
                    <i data-lucide="package" class="w-6 h-6 text-purple-600"></i>
                </div>
                <p class="text-2xl font-bold text-purple-600">{{ number_format($stats['processing']) }}</p>
                <p class="text-sm text-gray-600">Processing</p>
            </div>
        </div>
        
        <div class="bg-white rounded-xl p-6 border border-gray-200">
            <div class="text-center">
                <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center mx-auto mb-3">
                    <i data-lucide="check-circle" class="w-6 h-6 text-green-600"></i>
                </div>
                <p class="text-2xl font-bold text-green-600">{{ number_format($stats['delivered']) }}</p>
                <p class="text-sm text-gray-600">Delivered</p>
            </div>
        </div>
        
        <div class="bg-white rounded-xl p-6 border border-gray-200">
            <div class="text-center">
                <div class="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center mx-auto mb-3">
                    <i data-lucide="x-circle" class="w-6 h-6 text-red-600"></i>
                </div>
                <p class="text-2xl font-bold text-red-600">{{ number_format($stats['cancelled']) }}</p>
                <p class="text-sm text-gray-600">Cancelled</p>
            </div>
        </div>
    </div>

    <!-- Orders List -->
    <div class="bg-white rounded-xl border border-gray-200">
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900">Recent Orders</h3>
                <div class="text-sm text-gray-500">
                    Showing {{ $orders->firstItem() ?? 0 }} to {{ $orders->lastItem() ?? 0 }} of {{ $orders->total() }} orders
                </div>
            </div>
        </div>
        
        @if($orders->count() > 0)
        <div class="divide-y divide-gray-200">
            @foreach($orders as $order)
            <!-- Order Item -->
            <div class="p-6 hover:bg-gray-50">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                            <span class="text-purple-600 font-semibold text-xs">#{{ substr($order->order_number, -4) }}</span>
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-900">
                                @if($order->orderItems->count() == 1)
                                    {{ $order->orderItems->first()->product?->name ?? $order->orderItems->first()->pujaKit?->name ?? 'Unknown Product' }}
                                @else
                                    {{ $order->orderItems->count() }} items order
                                @endif
                            </h4>
                            <p class="text-sm text-gray-500">
                                Ordered by {{ $order->customer_name }} • {{ $order->created_at->diffForHumans() }}
                            </p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-6">
                        <div class="text-right">
                            <p class="font-semibold text-gray-900">{{ $order->formatted_total }}</p>
                            <p class="text-sm text-gray-500">{{ $order->total_items }} items</p>
                        </div>
                        <div class="flex flex-col space-y-1">
                            <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full {{ $order->status_badge }}">
                                {{ ucfirst($order->status) }}
                            </span>
                            @if($order->payment_status != 'paid')
                            <span class="inline-flex px-3 py-1 text-xs font-medium rounded-full {{ $order->payment_status_badge }}">
                                {{ ucfirst($order->payment_status) }}
                            </span>
                            @endif
                        </div>
                        <div class="flex items-center space-x-2">
                            <a href="{{ route('admin.orders.show', $order) }}" class="p-2 text-gray-400 hover:text-gray-600 rounded-lg hover:bg-gray-100" title="View Details">
                                <i data-lucide="eye" class="w-4 h-4"></i>
                            </a>
                            <a href="{{ route('admin.orders.invoice', $order) }}" target="_blank" class="p-2 text-gray-400 hover:text-gray-600 rounded-lg hover:bg-gray-100" title="Print Invoice">
                                <i data-lucide="printer" class="w-4 h-4"></i>
                            </a>
                            <div class="relative">
                                <button class="p-2 text-gray-400 hover:text-gray-600 rounded-lg hover:bg-gray-100 order-menu-btn" data-order-id="{{ $order->id }}">
                                    <i data-lucide="more-horizontal" class="w-4 h-4"></i>
                                </button>
                                <!-- Dropdown menu would go here -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        
        <!-- Pagination -->
        @if($orders->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $orders->links() }}
        </div>
        @endif
        
        @else
        <div class="p-8 text-center">
            <div class="w-16 h-16 bg-gray-100 rounded-xl flex items-center justify-center mx-auto mb-4">
                <i data-lucide="shopping-cart" class="w-8 h-8 text-gray-400"></i>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-2">No orders found</h3>
            <p class="text-gray-500">No orders match your current filters.</p>
        </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Filter toggle
    const filterBtn = document.getElementById('filterBtn');
    const filtersPanel = document.getElementById('filtersPanel');
    
    filterBtn.addEventListener('click', function() {
        filtersPanel.classList.toggle('hidden');
    });
});
</script>
@endpush
@endsection
