<!-- resources/views/shopkeeper/products/index.blade.php -->
@extends('layouts.shopkeeper')

@section('title', 'Products')
@section('breadcrumb', 'Products')

@section('content')
    <div class="px-4 sm:px-6 lg:px-8 py-8">
        <!-- Page Header -->
        <div class="mb-8">
            <div class="sm:flex sm:items-center sm:justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Your Products</h1>
                    <p class="mt-2 text-sm text-gray-700">Manage your product inventory and listings</p>
                </div>
                <div class="mt-4 sm:mt-0 sm:flex sm:items-center sm:space-x-3">
                    <button id="toggleFilters"
                        class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50">
                        <i data-lucide="filter" class="w-4 h-4 mr-2"></i>
                        <span id="filterToggleText">Show Filters</span>
                    </button>
                    <a href="{{ route('vendor.products.create') }}"
                        class="inline-flex items-center px-4 py-2 bg-purple-600 border border-transparent rounded-lg text-sm font-medium text-white hover:bg-purple-700">
                        <i data-lucide="plus" class="w-4 h-4 mr-2"></i>
                        Add Product
                    </a>
                </div>
            </div>
        </div>

        <!-- Products Stats -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-xl p-6 border border-gray-200 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Total Products</p>
                        <p class="text-2xl font-bold text-gray-900 mt-1" id="totalCount">{{ $stats['total'] }}</p>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                        <i data-lucide="package" class="w-6 h-6 text-blue-600"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl p-6 border border-gray-200 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Approved Products</p>
                        <p class="text-2xl font-bold text-green-600 mt-1" id="approvedCount">{{ $stats['approved'] }}</p>
                    </div>
                    <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                        <i data-lucide="check-circle" class="w-6 h-6 text-green-600"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl p-6 border border-gray-200 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Pending Products</p>
                        <p class="text-2xl font-bold text-yellow-600 mt-1" id="pendingCount">{{ $stats['pending'] }}</p>
                    </div>
                    <div class="w-12 h-12 bg-yellow-100 rounded-xl flex items-center justify-center">
                        <i data-lucide="alert-triangle" class="w-6 h-6 text-yellow-600"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl p-6 border border-gray-200 shadow-sm">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Rejected Products</p>
                        <p class="text-2xl font-bold text-red-600 mt-1" id="rejectedCount">{{ $stats['rejected'] }}</p>
                    </div>
                    <div class="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center">
                        <i data-lucide="x-circle" class="w-6 h-6 text-red-600"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter Panel -->
        <div id="filterPanel" class="bg-white rounded-xl border border-gray-200 shadow-sm mb-6 hidden">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Filter Products</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <!-- Search Input -->
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i data-lucide="search" class="w-4 h-4 text-gray-400"></i>
                        </div>
                        <input type="text" id="searchInput" placeholder="Search products, SKU..."
                            class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-purple-500 focus:border-purple-500">
                    </div>

                    <!-- Category Filter -->
                    <select id="categoryFilter" class="text-sm border border-gray-300 rounded-lg px-3 py-2 focus:ring-purple-500 focus:border-purple-500">
                        <option value="">All Categories</option>
                        @foreach($categories ?? [] as $category)
                            <option value="{{ $category->name }}">{{ $category->name }}</option>
                        @endforeach
                    </select>

                    <!-- Status Filter -->
                    <select id="statusFilter" class="text-sm border border-gray-300 rounded-lg px-3 py-2 focus:ring-purple-500 focus:border-purple-500">
                        <option value="">All Status</option>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>

                    <!-- Approval Filter -->
                    <select id="approvalFilter" class="text-sm border border-gray-300 rounded-lg px-3 py-2 focus:ring-purple-500 focus:border-purple-500">
                        <option value="">All Approval</option>
                        <option value="approved">Approved</option>
                        <option value="pending">Pending</option>
                        <option value="rejected">Rejected</option>
                    </select>

                    <!-- Stock Filter -->
                    <select id="stockFilter" class="text-sm border border-gray-300 rounded-lg px-3 py-2 focus:ring-purple-500 focus:border-purple-500">
                        <option value="">All Stock</option>
                        <option value="in-stock">In Stock</option>
                        <option value="low-stock">Low Stock</option>
                        <option value="out-of-stock">Out of Stock</option>
                    </select>

                    <!-- Price Range -->
                    <div class="flex items-center space-x-2">
                        <input type="number" id="minPrice" placeholder="Min Price" 
                            class="w-full text-sm border border-gray-300 rounded-lg px-2 py-2 focus:ring-purple-500 focus:border-purple-500">
                        <span class="text-gray-500">-</span>
                        <input type="number" id="maxPrice" placeholder="Max Price"
                            class="w-full text-sm border border-gray-300 rounded-lg px-2 py-2 focus:ring-purple-500 focus:border-purple-500">
                    </div>

                    <!-- Featured Filter -->
                    <select id="featuredFilter" class="text-sm border border-gray-300 rounded-lg px-3 py-2 focus:ring-purple-500 focus:border-purple-500">
                        <option value="">All Products</option>
                        <option value="featured">Featured Only</option>
                        <option value="non-featured">Non-Featured</option>
                    </select>

                    <!-- Sort Options -->
                    <select id="sortFilter" class="text-sm border border-gray-300 rounded-lg px-3 py-2 focus:ring-purple-500 focus:border-purple-500">
                        <option value="name-asc">Name (A-Z)</option>
                        <option value="name-desc">Name (Z-A)</option>
                        <option value="price-asc">Price (Low to High)</option>
                        <option value="price-desc">Price (High to Low)</option>
                        <option value="stock-asc">Stock (Low to High)</option>
                        <option value="stock-desc">Stock (High to Low)</option>
                    </select>
                </div>

                <!-- Filter Actions -->
                <div class="flex items-center justify-between mt-4">
                    <button id="clearFilters" 
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                        Clear All Filters
                    </button>
                    <div class="text-sm text-gray-600">
                        Showing <span id="visibleCount">{{ $products->count() }}</span> of <span id="totalProductCount">{{ $products->count() }}</span> products
                    </div>
                </div>
            </div>
        </div>

        <!-- Products Table -->
        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden shadow-sm">
            <div class="p-6 border-b border-gray-200">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold text-gray-900">Product Inventory</h3>
                    <div class="flex items-center space-x-3">
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i data-lucide="search" class="w-4 h-4 text-gray-400"></i>
                            </div>
                            <input type="text" id="quickSearch" placeholder="Quick search..."
                                class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-purple-500 focus:border-purple-500">
                        </div>
                    </div>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200" id="productsTable">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <div class="flex items-center space-x-1 cursor-pointer" data-sort="name">
                                    <span>Product</span>
                                    <i data-lucide="chevrons-up-down" class="w-3 h-3 text-gray-400"></i>
                                </div>
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Categories
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <div class="flex items-center space-x-1 cursor-pointer" data-sort="price">
                                    <span>Price</span>
                                    <i data-lucide="chevrons-up-down" class="w-3 h-3 text-gray-400"></i>
                                </div>
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <div class="flex items-center space-x-1 cursor-pointer" data-sort="stock">
                                    <span>Stock</span>
                                    <i data-lucide="chevrons-up-down" class="w-3 h-3 text-gray-400"></i>
                                </div>
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200" id="productTableBody">
                        @forelse($products as $product)
                            <tr class="hover:bg-gray-50 product-row" 
                                data-name="{{ strtolower($product->name) }}"
                                data-sku="{{ strtolower($product->sku) }}"
                                data-categories="{{ strtolower($product->categories->pluck('name')->implode(' ')) }}"
                                data-price="{{ $product->price }}"
                                data-stock="{{ $product->stock_quantity }}"
                                data-status="{{ $product->is_active ? 'active' : 'inactive' }}"
                                data-approval="{{ $product->approval_status }}"
                                data-featured="{{ $product->is_featured ? 'featured' : 'non-featured' }}">
                                
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-12 w-12">
                                            @if ($product->featured_image)
                                                <img class="h-12 w-12 rounded-lg object-cover"
                                                    src="{{ asset('storage/' . $product->featured_image) }}"
                                                    alt="{{ $product->name }}">
                                            @else
                                                <div class="h-12 w-12 rounded-lg bg-gray-200 flex items-center justify-center">
                                                    <i data-lucide="package" class="w-6 h-6 text-gray-400"></i>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $product->name }}</div>
                                            <div class="text-sm text-gray-500">SKU: {{ $product->sku }}</div>
                                        </div>
                                    </div>
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex flex-wrap gap-1">
                                        @foreach ($product->categories as $category)
                                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                                {{ $category->name }}
                                            </span>
                                        @endforeach
                                    </div>
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">₹{{ number_format($product->price, 2) }}</div>
                                    @if ($product->sale_price)
                                        <div class="text-xs text-green-600 font-medium">Sale: ₹{{ number_format($product->sale_price, 2) }}</div>
                                    @endif
                                    @if ($product->is_vendor_product && $product->vendor_commission_rate)
                                        <div class="text-xs text-purple-600">Commission: {{ $product->vendor_commission_rate }}%</div>
                                    @endif
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $product->stock_quantity }}</div>
                                    <div class="text-xs 
                                        @if($product->stock_quantity <= 0) text-red-500 stock-status-out-of-stock
                                        @elseif($product->stock_quantity <= 10) text-yellow-500 stock-status-low-stock
                                        @else text-green-500 stock-status-in-stock
                                        @endif">
                                        @if($product->stock_quantity <= 0)
                                            Out of Stock
                                        @elseif($product->stock_quantity <= 10)
                                            Low Stock
                                        @else
                                            In Stock
                                        @endif
                                    </div>
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex flex-col space-y-1">
                                        <!-- Product Status -->
                                        <div class="flex items-center space-x-2">
                                            <span class="text-xs font-medium text-gray-600">Status:</span>
                                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $product->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                {{ $product->is_active ? 'Active' : 'Inactive' }}
                                            </span>
                                        </div>

                                        <!-- Featured Status -->
                                        @if ($product->is_featured)
                                            <div class="flex items-center space-x-2">
                                                <span class="text-xs font-medium text-gray-600">Featured:</span>
                                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-purple-100 text-purple-800">
                                                    Featured
                                                </span>
                                            </div>
                                        @endif

                                        <!-- Vendor Approval Status -->
                                        @if ($product->is_vendor_product)
                                            <div class="flex items-center space-x-2">
                                                <span class="text-xs font-medium text-gray-600">Approval:</span>
                                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                                    {{ $product->approval_status === 'approved' ? 'bg-green-100 text-green-800' 
                                                    : ($product->approval_status === 'pending' ? 'bg-yellow-100 text-yellow-800' 
                                                    : 'bg-red-100 text-red-800') }}">
                                                    {{ ucfirst($product->approval_status) }}
                                                </span>
                                            </div>
                                        @endif
                                    </div>
                                </td>

                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex items-center space-x-2">
                                        <a href="{{ route('vendor.products.show', $product) }}"
                                            class="text-indigo-600 hover:text-indigo-900" title="View">
                                            <i data-lucide="eye" class="w-4 h-4"></i>
                                        </a>
                                        <a href="{{ route('vendor.products.edit', $product) }}"
                                            class="text-purple-600 hover:text-purple-900" title="Edit">
                                            <i data-lucide="edit" class="w-4 h-4"></i>
                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr id="noProductsRow">
                                <td colspan="6" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center">
                                        <i data-lucide="package" class="w-12 h-12 text-gray-400 mb-4"></i>
                                        <h3 class="text-sm font-medium text-gray-900 mb-2">No products found</h3>
                                        <p class="text-sm text-gray-500 mb-4">Create your first product to get started.</p>
                                        <a href="{{ route('vendor.products.create') }}"
                                            class="inline-flex items-center px-4 py-2 bg-purple-600 border border-transparent rounded-md text-sm font-medium text-white hover:bg-purple-700">
                                            <i data-lucide="plus" class="w-4 h-4 mr-2"></i>
                                            Add Product
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- No Results Message -->
            <div id="noResultsMessage" class="hidden p-12 text-center">
                <div class="flex flex-col items-center">
                    <i data-lucide="search-x" class="w-12 h-12 text-gray-400 mb-4"></i>
                    <h3 class="text-sm font-medium text-gray-900 mb-2">No products match your filters</h3>
                    <p class="text-sm text-gray-500 mb-4">Try adjusting your search criteria or clear filters.</p>
                    <button id="clearFiltersFromNoResults" 
                        class="inline-flex items-center px-4 py-2 bg-purple-600 border border-transparent rounded-md text-sm font-medium text-white hover:bg-purple-700">
                        Clear All Filters
                    </button>
                </div>
            </div>

            <!-- Pagination (will be hidden when filtering) -->
            @if ($products->hasPages())
                <div class="px-6 py-4 border-t border-gray-200" id="paginationContainer">
                    {{ $products->links() }}
                </div>
            @endif
        </div>
    </div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Get all filter elements
    const toggleFiltersBtn = document.getElementById('toggleFilters');
    const filterPanel = document.getElementById('filterPanel');
    const filterToggleText = document.getElementById('filterToggleText');
    const searchInput = document.getElementById('searchInput');
    const quickSearch = document.getElementById('quickSearch');
    const categoryFilter = document.getElementById('categoryFilter');
    const statusFilter = document.getElementById('statusFilter');
    const approvalFilter = document.getElementById('approvalFilter');
    const stockFilter = document.getElementById('stockFilter');
    const minPrice = document.getElementById('minPrice');
    const maxPrice = document.getElementById('maxPrice');
    const featuredFilter = document.getElementById('featuredFilter');
    const sortFilter = document.getElementById('sortFilter');
    const clearFiltersBtn = document.getElementById('clearFilters');
    const clearFiltersFromNoResults = document.getElementById('clearFiltersFromNoResults');
    
    // Get table elements
    const productRows = document.querySelectorAll('.product-row');
    const noResultsMessage = document.getElementById('noResultsMessage');
    const productsTable = document.getElementById('productsTable');
    const paginationContainer = document.getElementById('paginationContainer');
    
    // Counter elements
    const visibleCount = document.getElementById('visibleCount');
    const totalProductCount = document.getElementById('totalProductCount');
    
    let filtersVisible = false;
    let debounceTimer;

    // Toggle filter panel
    toggleFiltersBtn.addEventListener('click', function() {
        filtersVisible = !filtersVisible;
        if (filtersVisible) {
            filterPanel.classList.remove('hidden');
            filterToggleText.textContent = 'Hide Filters';
        } else {
            filterPanel.classList.add('hidden');
            filterToggleText.textContent = 'Show Filters';
        }
    });

    // Sync quick search with main search
    quickSearch.addEventListener('input', function() {
        searchInput.value = this.value;
        debounceFilter();
    });

    searchInput.addEventListener('input', function() {
        quickSearch.value = this.value;
        debounceFilter();
    });

    // Add event listeners to all filter elements
    [categoryFilter, statusFilter, approvalFilter, stockFilter, featuredFilter, sortFilter].forEach(element => {
        element.addEventListener('change', applyFilters);
    });

    [minPrice, maxPrice].forEach(element => {
        element.addEventListener('input', debounceFilter);
    });

    // Clear filters functionality
    [clearFiltersBtn, clearFiltersFromNoResults].forEach(btn => {
        btn.addEventListener('click', clearAllFilters);
    });

    // Column sorting
    document.querySelectorAll('[data-sort]').forEach(header => {
        header.addEventListener('click', function() {
            const sortBy = this.dataset.sort;
            sortTable(sortBy);
        });
    });

    function debounceFilter() {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(applyFilters, 300);
    }

    function applyFilters() {
        const filters = {
            search: searchInput.value.toLowerCase(),
            category: categoryFilter.value.toLowerCase(),
            status: statusFilter.value,
            approval: approvalFilter.value,
            stock: stockFilter.value,
            minPrice: minPrice.value ? parseFloat(minPrice.value) : null,
            maxPrice: maxPrice.value ? parseFloat(maxPrice.value) : null,
            featured: featuredFilter.value
        };

        let visibleRowsCount = 0;
        let hasResults = false;

        productRows.forEach(row => {
            let visible = true;

            // Search filter
            if (filters.search) {
                const name = row.dataset.name;
                const sku = row.dataset.sku;
                const categories = row.dataset.categories;
                
                if (!name.includes(filters.search) && 
                    !sku.includes(filters.search) && 
                    !categories.includes(filters.search)) {
                    visible = false;
                }
            }

            // Category filter
            if (filters.category && visible) {
                if (!row.dataset.categories.includes(filters.category)) {
                    visible = false;
                }
            }

            // Status filter
            if (filters.status && visible) {
                if (row.dataset.status !== filters.status) {
                    visible = false;
                }
            }

            // Approval filter
            if (filters.approval && visible) {
                if (row.dataset.approval !== filters.approval) {
                    visible = false;
                }
            }

            // Stock filter
            if (filters.stock && visible) {
                const stockQty = parseInt(row.dataset.stock);
                switch (filters.stock) {
                    case 'in-stock':
                        if (stockQty <= 10) visible = false;
                        break;
                    case 'low-stock':
                        if (stockQty <= 0 || stockQty > 10) visible = false;
                        break;
                    case 'out-of-stock':
                        if (stockQty > 0) visible = false;
                        break;
                }
            }

            // Price range filter
            if ((filters.minPrice !== null || filters.maxPrice !== null) && visible) {
                const price = parseFloat(row.dataset.price);
                if (filters.minPrice !== null && price < filters.minPrice) visible = false;
                if (filters.maxPrice !== null && price > filters.maxPrice) visible = false;
            }

            // Featured filter
            if (filters.featured && visible) {
                if (row.dataset.featured !== filters.featured) {
                    visible = false;
                }
            }

            // Show/hide row
            if (visible) {
                row.style.display = '';
                visibleRowsCount++;
                hasResults = true;
            } else {
                row.style.display = 'none';
            }
        });

        // Update counters
        visibleCount.textContent = visibleRowsCount;
        updateStats(visibleRowsCount);

        // Show/hide no results message
        if (!hasResults && productRows.length > 0) {
            productsTable.style.display = 'none';
            noResultsMessage.classList.remove('hidden');
            if (paginationContainer) paginationContainer.style.display = 'none';
        } else {
            productsTable.style.display = '';
            noResultsMessage.classList.add('hidden');
            if (paginationContainer) paginationContainer.style.display = '';
        }

        // Apply sorting after filtering
        if (sortFilter.value) {
            applySorting(sortFilter.value);
        }
    }

    function sortTable(column) {
        const tbody = document.getElementById('productTableBody');
        const rows = Array.from(productRows).filter(row => row.style.display !== 'none');
        
        let sortOrder = 'asc';
        const currentSort = sortFilter.value;
        if (currentSort === `${column}-asc`) {
            sortOrder = 'desc';
        }
        
        sortFilter.value = `${column}-${sortOrder}`;
        applySorting(`${column}-${sortOrder}`);
    }

    function applySorting(sortValue) {
        const tbody = document.getElementById('productTableBody');
        const rows = Array.from(productRows);
        
        const [column, order] = sortValue.split('-');
        
        rows.sort((a, b) => {
            let aVal, bVal;
            
            switch (column) {
                case 'name':
                    aVal = a.dataset.name;
                    bVal = b.dataset.name;
                    break;
                case 'price':
                    aVal = parseFloat(a.dataset.price);
                    bVal = parseFloat(b.dataset.price);
                    break;
                case 'stock':
                    aVal = parseInt(a.dataset.stock);
                    bVal = parseInt(b.dataset.stock);
                    break;
                default:
                    return 0;
            }
            
            if (typeof aVal === 'string') {
                return order === 'asc' ? aVal.localeCompare(bVal) : bVal.localeCompare(aVal);
            } else {
                return order === 'asc' ? aVal - bVal : bVal - aVal;
            }
        });
        
        // Re-append sorted rows
        rows.forEach(row => tbody.appendChild(row));
    }

    function clearAllFilters() {
        searchInput.value = '';
        quickSearch.value = '';
        categoryFilter.value = '';
        statusFilter.value = '';
        approvalFilter.value = '';
        stockFilter.value = '';
        minPrice.value = '';
        maxPrice.value = '';
        featuredFilter.value = '';
        sortFilter.value = 'name-asc';
        
        applyFilters();
    }

    function updateStats(visibleCount) {
        // You can add more dynamic stats updates here
        // For now, just update the visible count
        document.getElementById('visibleCount').textContent = visibleCount;
    }

    // Initialize
    totalProductCount.textContent = productRows.length;
    visibleCount.textContent = productRows.length;
});
</script>
@endsection
