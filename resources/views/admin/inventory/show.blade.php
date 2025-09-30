@extends('layouts.admin')

@section('title', 'Product Inventory Details - ' . $product->name)
@section('breadcrumb')
<nav class="flex mb-6" aria-label="Breadcrumb">
    <ol class="flex items-center space-x-2 text-sm">
        <li><a href="{{ route('admin.dashboard') }}" class="text-gray-500 hover:text-blue-600">Dashboard</a></li>
        <li><span class="text-gray-400">/</span></li>
        <li><a href="{{ route('admin.inventory.index') }}" class="text-gray-500 hover:text-blue-600">Inventory</a></li>
        <li><span class="text-gray-400">/</span></li>
        <li class="text-gray-900 font-medium">{{ Str::limit($product->name, 30) }}</li>
    </ol>
</nav>
@endsection

@section('content')
<div class="p-6">
    <!-- Product Header Card -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-8">
        <div class="p-6">
            <div class="flex items-start justify-between">
                <div class="flex items-center space-x-6">
                    <!-- Product Image -->
                    <div class="w-20 h-20 bg-gray-200 rounded-xl overflow-hidden flex-shrink-0">
                        @if($product->featured_image)
                        <img src="{{ Storage::url($product->featured_image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                        @else
                        <div class="w-full h-full flex items-center justify-center text-gray-400">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        @endif
                    </div>
                    
                    <!-- Product Info -->
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900 mb-2">{{ $product->name }}</h1>
                        <div class="flex items-center space-x-4 text-sm text-gray-500 mb-3">
                            <span class="bg-gray-100 px-2 py-1 rounded font-mono">SKU: {{ $product->sku ?: 'N/A' }}</span>
                            @if($product->categories->isNotEmpty())
                            <span>{{ $product->categories->pluck('name')->join(', ') }}</span>
                            @endif
                        </div>
                        <div class="flex items-center space-x-4">
                            <span class="px-3 py-1 text-sm font-medium rounded-full 
                                {{ $product->stock_quantity > 10 ? 'bg-green-100 text-green-800' : 
                                   ($product->stock_quantity > 0 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                {{ $product->stock_quantity > 10 ? 'In Stock' : 
                                   ($product->stock_quantity > 0 ? 'Low Stock' : 'Out of Stock') }}
                            </span>
                            @if($product->vendor)
                            <span class="text-sm text-gray-600">
                                Vendor: {{ $product->vendor->first_name }} {{ $product->vendor->last_name }}
                            </span>
                            @endif
                        </div>
                    </div>
                </div>
                
                <!-- Stock Display -->
                <div class="text-right">
                    <div class="text-4xl font-bold {{ $isLowStock ? 'text-red-600' : 'text-green-600' }}">
                        {{ $product->stock_quantity }}
                    </div>
                    <p class="text-sm text-gray-500 mt-1">Current Stock</p>
                    @if($isLowStock && $product->stock_quantity > 0)
                    <div class="mt-2">
                        <span class="inline-flex items-center px-2 py-1 text-xs font-medium bg-red-100 text-red-800 rounded-full">
                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                            </svg>
                            Low Stock Alert
                        </span>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Alert for Low Stock -->
    @if($isLowStock)
    <div class="bg-gradient-to-r from-red-50 to-orange-50 border-l-4 border-red-400 p-6 mb-8 rounded-r-xl">
        <div class="flex items-center">
            <svg class="w-6 h-6 text-red-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
            </svg>
            <div>
                <h3 class="text-lg font-medium text-red-800">Stock Alert</h3>
                <p class="text-red-700 mt-1">
                    Current stock ({{ $product->stock_quantity }}) is {{ $product->stock_quantity == 0 ? 'out of stock' : 'below reorder level (' . $lowStockThreshold . ')' }}. 
                    {{ $product->stock_quantity == 0 ? 'Immediate restocking required!' : 'Consider restocking soon.' }}
                </p>
            </div>
        </div>
    </div>
    @endif

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
        <!-- Left Column: Stats & Quick Actions -->
        <div class="xl:col-span-1 space-y-6">
            <!-- Stock Statistics -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-6 flex items-center">
                    <svg class="w-5 h-5 text-blue-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                    Stock Statistics
                </h3>
                <div class="space-y-4">
                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                        <span class="text-gray-600 font-medium">Current Stock</span>
                        <span class="text-xl font-bold text-gray-900">{{ $product->stock_quantity }} units</span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                        <span class="text-gray-600 font-medium">Reorder Level</span>
                        <span class="font-semibold text-gray-900">{{ $lowStockThreshold }} units</span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                        <span class="text-gray-600 font-medium">Total Added</span>
                        <span class="font-semibold text-green-600">+{{ $inventoryStats['total_added'] }}</span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                        <span class="text-gray-600 font-medium">Total Removed</span>
                        <span class="font-semibold text-red-600">-{{ $inventoryStats['total_removed'] }}</span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-gray-100">
                        <span class="text-gray-600 font-medium">Stock Value</span>
                        <span class="font-semibold text-gray-900">₹{{ number_format(($product->cost_price ?? 0) * $product->stock_quantity, 2) }}</span>
                    </div>
                    @if($inventoryStats['last_updated'])
                    <div class="flex justify-between items-center py-2">
                        <span class="text-gray-600 font-medium">Last Updated</span>
                        <span class="text-sm text-blue-600 font-medium">{{ $inventoryStats['last_updated']->diffForHumans() }}</span>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Quick Stock Update -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-6 flex items-center">
                    <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Quick Stock Update
                </h3>
                <form action="{{ route('admin.inventory.update-stock', $product) }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Transaction Type</label>
                        <select name="type" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                            <option value="">Select Type</option>
                            <option value="purchase">Stock Purchase (+)</option>
                            <option value="sale">Product Sale (-)</option>
                            <option value="return">Customer Return (+)</option>
                            <option value="adjustment">Stock Adjustment (±)</option>
                            <option value="damage">Damaged Stock (-)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Quantity</label>
                        <input type="number" name="quantity" required min="1" 
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                               placeholder="Enter quantity">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Notes <span class="text-gray-400">(Optional)</span></label>
                        <textarea name="notes" rows="3" 
                                  class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors resize-none"
                                  placeholder="Add notes about this transaction..."></textarea>
                    </div>
                    <button type="submit" class="w-full bg-gradient-to-r from-blue-600 to-blue-700 text-white py-2 px-4 rounded-lg hover:from-blue-700 hover:to-blue-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all font-medium">
                        Update Stock
                    </button>
                </form>
            </div>
        </div>

        <!-- Right Column: Inventory History -->
        <div class="xl:col-span-2">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <svg class="w-5 h-5 text-purple-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                        </svg>
                        Inventory Transaction History
                    </h3>
                    <div class="text-sm text-gray-500 bg-gray-100 px-3 py-1 rounded-full">
                        {{ $inventoryStats['total_logs'] }} total transactions
                    </div>
                </div>

                @if($recentLogs->isNotEmpty())
                <div class="space-y-4 max-h-96 overflow-y-auto">
                    @foreach($recentLogs as $log)
                    <div class="flex items-center justify-between p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                        <div class="flex items-center space-x-4">
                            <div class="w-12 h-12 rounded-full flex items-center justify-center
                                {{ $log->quantity_changed > 0 ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600' }}">
                                @if($log->quantity_changed > 0)
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12"></path>
                                    </svg>
                                @else
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 13l-5 5m0 0l-5-5m5 5V6"></path>
                                    </svg>
                                @endif
                            </div>
                            <div>
                                <div class="flex items-center space-x-3 mb-1">
                                    <span class="font-medium text-gray-900 capitalize">{{ str_replace('_', ' ', $log->type) }}</span>
                                    <span class="px-2 py-1 text-xs font-medium rounded-full
                                        {{ $log->quantity_changed > 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $log->quantity_changed > 0 ? '+' : '' }}{{ $log->quantity_changed }}
                                    </span>
                                </div>
                                <div class="text-sm text-gray-500">
                                    {{ $log->created_at->format('M j, Y \a\t g:i A') }}
                                    @if($log->creator)
                                    • by {{ $log->creator->first_name }} {{ $log->creator->last_name }}
                                    @endif
                                </div>
                                @if($log->notes)
                                <div class="text-sm text-gray-600 mt-1 italic">{{ $log->notes }}</div>
                                @endif
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="text-sm font-medium text-gray-900">
                                {{ $log->quantity_before }} → {{ $log->quantity_after }}
                            </div>
                            <div class="text-xs text-gray-500">Before → After</div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="text-center py-12">
                    <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                    </svg>
                    <h4 class="text-lg font-medium text-gray-500 mb-2">No Transaction History</h4>
                    <p class="text-gray-400">No inventory transactions have been recorded for this product yet.</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

@if(session('success'))
<div id="success-toast" class="fixed top-4 right-4 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50">
    {{ session('success') }}
</div>
<script>
    setTimeout(() => {
        document.getElementById('success-toast')?.remove();
    }, 5000);
</script>
@endif

@if($errors->any())
<div id="error-toast" class="fixed top-4 right-4 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg z-50">
    {{ $errors->first() }}
</div>
<script>
    setTimeout(() => {
        document.getElementById('error-toast')?.remove();
    }, 8000);
</script>
@endif
@endsection
