@extends('layouts.admin')

@section('title', 'Inventory Management')
@section('breadcrumb', 'Inventory')

@section('content')
<div class="px-4 sm:px-6 lg:px-8 py-8">
    <!-- Page Header -->
    <div class="mb-8">
        <div class="sm:flex sm:items-center sm:justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Inventory Management</h1>
                <p class="mt-2 text-sm text-gray-700">Monitor stock levels and manage inventory across all products</p>
            </div>
            <div class="mt-4 sm:mt-0 sm:flex sm:items-center sm:space-x-3">
                <button class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50">
                    <i data-lucide="download" class="w-4 h-4 mr-2"></i>
                    Export CSV
                </button>
                <button class="inline-flex items-center px-4 py-2 bg-purple-600 border border-transparent rounded-lg text-sm font-medium text-white hover:bg-purple-700">
                    <i data-lucide="plus" class="w-4 h-4 mr-2"></i>
                    Bulk Update
                </button>
            </div>
        </div>
    </div>

    <!-- Inventory Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-xl p-6 border border-gray-200 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Items</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ number_format($stats['total_products']) }}</p>
                    <p class="text-xs text-gray-500 mt-1">across all categories</p>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                    <i data-lucide="package" class="w-6 h-6 text-blue-600"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl p-6 border border-gray-200 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">In Stock</p>
                    <p class="text-2xl font-bold text-green-600 mt-1">{{ number_format($stats['in_stock']) }}</p>
                    <p class="text-xs text-gray-500 mt-1">{{ $stats['in_stock_percentage'] }}% of items</p>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                    <i data-lucide="check-circle" class="w-6 h-6 text-green-600"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl p-6 border border-gray-200 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Low Stock</p>
                    <p class="text-2xl font-bold text-yellow-600 mt-1">{{ number_format($stats['low_stock']) }}</p>
                    <p class="text-xs text-gray-500 mt-1">need reordering</p>
                </div>
                <div class="w-12 h-12 bg-yellow-100 rounded-xl flex items-center justify-center">
                    <i data-lucide="alert-triangle" class="w-6 h-6 text-yellow-600"></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl p-6 border border-gray-200 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Out of Stock</p>
                    <p class="text-2xl font-bold text-red-600 mt-1">{{ number_format($stats['out_of_stock']) }}</p>
                    <p class="text-xs text-gray-500 mt-1">urgent attention</p>
                </div>
                <div class="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center">
                    <i data-lucide="x-circle" class="w-6 h-6 text-red-600"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-xl border border-gray-200 p-6 mb-6">
        <form method="GET" action="{{ route('admin.inventory.index') }}">
            <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Search Products</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i data-lucide="search" class="w-4 h-4 text-gray-400"></i>
                        </div>
                        <input type="text" name="search" value="{{ $search }}" placeholder="Search by name or SKU..." 
                               class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500 text-sm">
                    </div>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                    <select name="category" class="block w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-purple-500 focus:border-purple-500 text-sm">
                        <option value="all">All Categories</option>
                        @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ $category == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Stock Status</label>
                    <select name="status" class="block w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-purple-500 focus:border-purple-500 text-sm">
                        <option value="all" {{ $status === 'all' ? 'selected' : '' }}>All Status</option>
                        <option value="in_stock" {{ $status === 'in_stock' ? 'selected' : '' }}>In Stock</option>
                        <option value="low_stock" {{ $status === 'low_stock' ? 'selected' : '' }}>Low Stock</option>
                        <option value="out_of_stock" {{ $status === 'out_of_stock' ? 'selected' : '' }}>Out of Stock</option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Supplier</label>
                    <select name="vendor" class="block w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-purple-500 focus:border-purple-500 text-sm">
                        <option value="all">All Suppliers</option>
                        @foreach($vendors as $vendorOption)
                        <option value="{{ $vendorOption->id }}" {{ $vendor == $vendorOption->id ? 'selected' : '' }}>
                            {{ $vendorOption->first_name }} {{ $vendorOption->last_name }}
                        </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="flex items-end space-x-2">
                    <button type="submit" class="flex-1 px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 text-sm font-medium">
                        Apply Filters
                    </button>
                    <a href="{{ route('admin.inventory.index') }}" class="px-3 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 text-sm">
                        <i data-lucide="x" class="w-4 h-4"></i>
                    </a>
                </div>
            </div>
        </form>
    </div>

    <!-- Inventory Table -->
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <div class="flex items-center">
                                <input type="checkbox" class="rounded border-gray-300 text-purple-600 focus:ring-purple-500" id="select-all">
                                <span class="ml-2">Product</span>
                            </div>
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">SKU</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Current Stock</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reorder Level</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Supplier</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Last Updated</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($products as $product)
                    @php
                        $lowStockThreshold = $product->low_stock_threshold ?? 10;
                        $stockStatus = $product->stock_quantity > $lowStockThreshold ? 'in_stock' : 
                                      ($product->stock_quantity > 0 ? 'low_stock' : 'out_of_stock');
                        $lastLog = $product->inventoryLogs->first();
                        $rowClass = $stockStatus === 'low_stock' ? 'bg-yellow-50' : ($stockStatus === 'out_of_stock' ? 'bg-red-50' : '');
                    @endphp
                    <tr class="hover:bg-gray-50 {{ $rowClass }}">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <input type="checkbox" class="product-checkbox rounded border-gray-300 text-purple-600 focus:ring-purple-500" value="{{ $product->id }}">
                                <div class="ml-4 flex items-center">
                                    <div class="h-10 w-10 rounded-lg overflow-hidden bg-gray-100">
                                        @if($product->featured_image)
                                        <img src="{{ Storage::url($product->featured_image) }}" alt="{{ $product->name }}" class="h-full w-full object-cover">
                                        @else
                                        <div class="h-full w-full bg-gradient-to-br from-purple-100 to-pink-100 flex items-center justify-center">
                                            <i data-lucide="image" class="w-5 h-5 text-gray-400"></i>
                                        </div>
                                        @endif
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ Str::limit($product->name, 30) }}</div>
                                        <div class="text-sm text-gray-500">{{ $product->categories->pluck('name')->join(', ') ?: 'No Category' }}</div>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm font-mono text-gray-900 bg-gray-100 px-2 py-1 rounded">{{ $product->sku ?: 'N/A' }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <span class="text-sm font-bold {{ $stockStatus === 'out_of_stock' ? 'text-red-600' : ($stockStatus === 'low_stock' ? 'text-yellow-600' : 'text-green-600') }}">
                                    {{ $product->stock_quantity }}
                                </span>
                                <span class="text-sm text-gray-500 ml-1">units</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $lowStockThreshold }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $product->vendor ? $product->vendor->first_name . ' ' . $product->vendor->last_name : 'Admin' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($stockStatus === 'in_stock')
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                <i data-lucide="check-circle" class="w-3 h-3 mr-1"></i>
                                In Stock
                            </span>
                            @elseif($stockStatus === 'low_stock')
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                <i data-lucide="alert-triangle" class="w-3 h-3 mr-1"></i>
                                Low Stock
                            </span>
                            @else
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                <i data-lucide="x-circle" class="w-3 h-3 mr-1"></i>
                                Out of Stock
                            </span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $lastLog ? $lastLog->created_at->diffForHumans() : 'Never' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex items-center space-x-2">
                                <button onclick="updateStock('{{ $product->sku }}')" class="text-purple-600 hover:text-purple-900 p-1 rounded" title="Update Stock">
                                    <i data-lucide="edit" class="w-4 h-4"></i>
                                </button>
                                @if($stockStatus !== 'in_stock')
                                <button onclick="reorderNow('{{ $product->sku }}')" class="text-green-600 hover:text-green-900 p-1 rounded" title="Reorder Now">
                                    <i data-lucide="shopping-cart" class="w-4 h-4"></i>
                                </button>
                                @endif
                                <a href="{{ route('admin.inventory.show', $product) }}" class="text-blue-600 hover:text-blue-900 p-1 rounded" title="View History">
                                    <i data-lucide="history" class="w-4 h-4"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center">
                                <i data-lucide="package-x" class="w-12 h-12 text-gray-400 mb-4"></i>
                                <p class="text-gray-500 text-lg font-medium">No products found</p>
                                <p class="text-gray-400 text-sm mt-1">Try adjusting your search or filter criteria</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if($products->hasPages())
        <div class="px-6 py-3 border-t border-gray-200 bg-gray-50">
            <div class="flex items-center justify-between">
                <div class="text-sm text-gray-700">
                    Showing <span class="font-medium">{{ $products->firstItem() ?? 0 }}</span> to <span class="font-medium">{{ $products->lastItem() ?? 0 }}</span> of <span class="font-medium">{{ $products->total() }}</span> products
                </div>
                <div class="flex items-center space-x-2">
                    {{-- Previous Page Link --}}
                    @if($products->onFirstPage())
                    <span class="px-3 py-1 text-sm border border-gray-300 rounded-md text-gray-400 cursor-not-allowed">Previous</span>
                    @else
                    <a href="{{ $products->appends(request()->query())->previousPageUrl() }}" class="px-3 py-1 text-sm border border-gray-300 rounded-md hover:bg-gray-100 text-gray-600">Previous</a>
                    @endif

                    {{-- Pagination Elements --}}
                    @foreach($products->appends(request()->query())->getUrlRange(1, $products->lastPage()) as $page => $url)
                        @if($page == $products->currentPage())
                        <span class="px-3 py-1 text-sm bg-purple-600 text-white rounded-md">{{ $page }}</span>
                        @else
                        <a href="{{ $url }}" class="px-3 py-1 text-sm border border-gray-300 rounded-md hover:bg-gray-100 text-gray-600">{{ $page }}</a>
                        @endif
                    @endforeach

                    {{-- Next Page Link --}}
                    @if($products->hasMorePages())
                    <a href="{{ $products->appends(request()->query())->nextPageUrl() }}" class="px-3 py-1 text-sm border border-gray-300 rounded-md hover:bg-gray-100 text-gray-600">Next</a>
                    @else
                    <span class="px-3 py-1 text-sm border border-gray-300 rounded-md text-gray-400 cursor-not-allowed">Next</span>
                    @endif
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Stock Update Modal -->
<div id="stockUpdateModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900" id="modalTitle">Update Stock</h3>
                <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>
            <form id="stockUpdateForm">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Transaction Type</label>
                    <select id="transactionType" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                        <option value="">Select Type</option>
                        <option value="purchase">Stock Purchase (+)</option>
                        <option value="sale">Product Sale (-)</option>
                        <option value="return">Customer Return (+)</option>
                        <option value="adjustment">Stock Adjustment (±)</option>
                        <option value="damage">Damaged Stock (-)</option>
                    </select>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Quantity</label>
                    <input type="number" id="quantity" min="1" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500" placeholder="Enter quantity">
                </div>
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Notes (Optional)</label>
                    <textarea id="notes" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 resize-none" placeholder="Add notes..."></textarea>
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeModal()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700">
                        Update Stock
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
let currentProductSku = null;

function updateStock(sku) {
    currentProductSku = sku;
    document.getElementById('modalTitle').textContent = `Update Stock - ${sku}`;
    document.getElementById('stockUpdateModal').classList.remove('hidden');
}

function reorderNow(sku) {
    if(confirm(`Are you sure you want to reorder product ${sku}?`)) {
        // Implementation for reordering
        console.log('Reorder product:', sku);
        // You can redirect to reorder page or show another modal
    }
}

function closeModal() {
    document.getElementById('stockUpdateModal').classList.add('hidden');
    document.getElementById('stockUpdateForm').reset();
}

// Handle stock update form submission
document.getElementById('stockUpdateForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = {
        sku: currentProductSku,
        type: document.getElementById('transactionType').value,
        quantity: document.getElementById('quantity').value,
        notes: document.getElementById('notes').value,
        _token: '{{ csrf_token() }}'
    };
    
    if(!formData.type || !formData.quantity) {
        alert('Please fill in all required fields.');
        return;
    }
    
    // Here you would send the data to your controller
    console.log('Updating stock:', formData);
    
    // For demo purposes, we'll just close the modal
    closeModal();
    
    // Show success message
    showToast('Stock updated successfully!', 'success');
});

// Select all functionality
document.getElementById('select-all').addEventListener('change', function() {
    const checkboxes = document.querySelectorAll('.product-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.checked = this.checked;
    });
});

// Toast notification function
function showToast(message, type = 'info') {
    const toast = document.createElement('div');
    const bgColor = type === 'success' ? 'bg-green-500' : type === 'error' ? 'bg-red-500' : 'bg-blue-500';
    
    toast.className = `fixed top-4 right-4 ${bgColor} text-white px-6 py-3 rounded-lg shadow-lg z-50 transition-opacity duration-500`;
    toast.textContent = message;
    
    document.body.appendChild(toast);
    
    setTimeout(() => {
        toast.style.opacity = '0';
        setTimeout(() => {
            document.body.removeChild(toast);
        }, 500);
    }, 3000);
}

// Initialize Lucide icons
document.addEventListener('DOMContentLoaded', function() {
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }
});
</script>
@endsection
