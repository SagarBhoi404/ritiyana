<!-- resources/views/admin/inventory/index.blade.php -->
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
                    <p class="text-2xl font-bold text-gray-900 mt-1">1,247</p>
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
                    <p class="text-2xl font-bold text-green-600 mt-1">1,192</p>
                    <p class="text-xs text-gray-500 mt-1">95.6% of items</p>
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
                    <p class="text-2xl font-bold text-yellow-600 mt-1">39</p>
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
                    <p class="text-2xl font-bold text-red-600 mt-1">16</p>
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
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Search Products</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i data-lucide="search" class="w-4 h-4 text-gray-400"></i>
                    </div>
                    <input type="text" placeholder="Search by name or SKU..." class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500 text-sm">
                </div>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                <select class="block w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-purple-500 focus:border-purple-500 text-sm">
                    <option>All Categories</option>
                    <option>Puja Diyas</option>
                    <option>Incense Sticks</option>
                    <option>Flowers & Garlands</option>
                    <option>Puja Kits</option>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Stock Status</label>
                <select class="block w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-purple-500 focus:border-purple-500 text-sm">
                    <option>All Status</option>
                    <option>In Stock</option>
                    <option>Low Stock</option>
                    <option>Out of Stock</option>
                </select>
            </div>
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Supplier</label>
                <select class="block w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-purple-500 focus:border-purple-500 text-sm">
                    <option>All Suppliers</option>
                    <option>Shri Suppliers</option>
                    <option>Puja Supplies Co.</option>
                    <option>Divine Items Ltd.</option>
                </select>
            </div>
            
            <div class="flex items-end">
                <button class="w-full px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 text-sm font-medium">
                    Apply Filters
                </button>
            </div>
        </div>
    </div>

    <!-- Inventory Table -->
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <div class="flex items-center">
                                <input type="checkbox" class="rounded border-gray-300 text-purple-600 focus:ring-purple-500">
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
                    <!-- Product Row 1 - In Stock -->
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <input type="checkbox" class="rounded border-gray-300 text-purple-600 focus:ring-purple-500">
                                <div class="ml-4 flex items-center">
                                    <div class="h-10 w-10 rounded-lg overflow-hidden bg-gray-100">
                                        <img src="https://images.unsplash.com/photo-1578662996442-48f60103fc96?w=100&h=100&fit=crop" alt="Golden Brass Diya Set" class="h-full w-full object-cover">
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">Golden Brass Diya Set</div>
                                        <div class="text-sm text-gray-500">Puja Diyas</div>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm font-mono text-gray-900 bg-gray-100 px-2 py-1 rounded">PD-001</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <span class="text-sm font-bold text-green-600">45</span>
                                <span class="text-sm text-gray-500 ml-1">units</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">10</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Shri Suppliers</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                <i data-lucide="check-circle" class="w-3 h-3 mr-1"></i>
                                In Stock
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">2 hours ago</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex items-center space-x-2">
                                <button onclick="updateStock('PD-001')" class="text-purple-600 hover:text-purple-900 p-1 rounded">
                                    <i data-lucide="edit" class="w-4 h-4"></i>
                                </button>
                                <button onclick="viewHistory('PD-001')" class="text-blue-600 hover:text-blue-900 p-1 rounded">
                                    <i data-lucide="history" class="w-4 h-4"></i>
                                </button>
                            </div>
                        </td>
                    </tr>

                    <!-- Product Row 2 - Low Stock -->
                    <tr class="hover:bg-gray-50 bg-yellow-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <input type="checkbox" class="rounded border-gray-300 text-purple-600 focus:ring-purple-500">
                                <div class="ml-4 flex items-center">
                                    <div class="h-10 w-10 rounded-lg overflow-hidden bg-gray-100">
                                        <img src="https://images.unsplash.com/photo-1603048719539-9ecb4aa395c3?w=100&h=100&fit=crop" alt="Sandalwood Incense" class="h-full w-full object-cover">
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">Sandalwood Incense Sticks</div>
                                        <div class="text-sm text-gray-500">Incense & Fragrances</div>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm font-mono text-gray-900 bg-gray-100 px-2 py-1 rounded">IN-002</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <span class="text-sm font-bold text-yellow-600">8</span>
                                <span class="text-sm text-gray-500 ml-1">units</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">15</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Puja Supplies Co.</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                <i data-lucide="alert-triangle" class="w-3 h-3 mr-1"></i>
                                Low Stock
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">1 day ago</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex items-center space-x-2">
                                <button onclick="updateStock('IN-002')" class="text-purple-600 hover:text-purple-900 p-1 rounded">
                                    <i data-lucide="edit" class="w-4 h-4"></i>
                                </button>
                                <button onclick="reorderNow('IN-002')" class="text-green-600 hover:text-green-900 p-1 rounded">
                                    <i data-lucide="shopping-cart" class="w-4 h-4"></i>
                                </button>
                                <button onclick="viewHistory('IN-002')" class="text-blue-600 hover:text-blue-900 p-1 rounded">
                                    <i data-lucide="history" class="w-4 h-4"></i>
                                </button>
                            </div>
                        </td>
                    </tr>

                    <!-- Product Row 3 - Out of Stock -->
                    <tr class="hover:bg-gray-50 bg-red-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <input type="checkbox" class="rounded border-gray-300 text-purple-600 focus:ring-purple-500">
                                <div class="ml-4 flex items-center">
                                    <div class="h-10 w-10 rounded-lg overflow-hidden bg-gray-100">
                                        <img src="https://images.unsplash.com/photo-1544928147-79a2dbc1f389?w=100&h=100&fit=crop" alt="Marigold Garland" class="h-full w-full object-cover">
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">Fresh Marigold Garland</div>
                                        <div class="text-sm text-gray-500">Flowers & Garlands</div>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm font-mono text-gray-900 bg-gray-100 px-2 py-1 rounded">FG-003</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <span class="text-sm font-bold text-red-600">0</span>
                                <span class="text-sm text-gray-500 ml-1">units</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">20</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Divine Items Ltd.</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                <i data-lucide="x-circle" class="w-3 h-3 mr-1"></i>
                                Out of Stock
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">3 days ago</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex items-center space-x-2">
                                <button onclick="updateStock('FG-003')" class="text-purple-600 hover:text-purple-900 p-1 rounded">
                                    <i data-lucide="edit" class="w-4 h-4"></i>
                                </button>
                                <button onclick="reorderNow('FG-003')" class="text-red-600 hover:text-red-900 p-1 rounded">
                                    <i data-lucide="alert-circle" class="w-4 h-4"></i>
                                </button>
                                <button onclick="viewHistory('FG-003')" class="text-blue-600 hover:text-blue-900 p-1 rounded">
                                    <i data-lucide="history" class="w-4 h-4"></i>
                                </button>
                            </div>
                        </td>
                    </tr>

                    <!-- Product Row 4 - In Stock -->
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <input type="checkbox" class="rounded border-gray-300 text-purple-600 focus:ring-purple-500">
                                <div class="ml-4 flex items-center">
                                    <div class="h-10 w-10 rounded-lg overflow-hidden bg-gradient-to-br from-purple-100 to-pink-100 flex items-center justify-center">
                                        <span class="text-2xl">🎁</span>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">Ganesh Puja Complete Kit</div>
                                        <div class="text-sm text-gray-500">Puja Kits</div>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm font-mono text-gray-900 bg-gray-100 px-2 py-1 rounded">PK-004</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <span class="text-sm font-bold text-green-600">23</span>
                                <span class="text-sm text-gray-500 ml-1">kits</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">5</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Shri Suppliers</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                <i data-lucide="check-circle" class="w-3 h-3 mr-1"></i>
                                In Stock
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">5 hours ago</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex items-center space-x-2">
                                <button onclick="updateStock('PK-004')" class="text-purple-600 hover:text-purple-900 p-1 rounded">
                                    <i data-lucide="edit" class="w-4 h-4"></i>
                                </button>
                                <button onclick="viewHistory('PK-004')" class="text-blue-600 hover:text-blue-900 p-1 rounded">
                                    <i data-lucide="history" class="w-4 h-4"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="px-6 py-3 border-t border-gray-200 bg-gray-50">
            <div class="flex items-center justify-between">
                <div class="text-sm text-gray-700">
                    Showing <span class="font-medium">1</span> to <span class="font-medium">10</span> of <span class="font-medium">1,247</span> products
                </div>
                <div class="flex items-center space-x-2">
                    <button class="px-3 py-1 text-sm border border-gray-300 rounded-md hover:bg-gray-100 text-gray-600">Previous</button>
                    <button class="px-3 py-1 text-sm bg-purple-600 text-white rounded-md">1</button>
                    <button class="px-3 py-1 text-sm border border-gray-300 rounded-md hover:bg-gray-100 text-gray-600">2</button>
                    <button class="px-3 py-1 text-sm border border-gray-300 rounded-md hover:bg-gray-100 text-gray-600">3</button>
                    <button class="px-3 py-1 text-sm border border-gray-300 rounded-md hover:bg-gray-100 text-gray-600">Next</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function updateStock(sku) {
    console.log('Update stock for:', sku);
    // Implementation for updating stock
}

function reorderNow(sku) {
    console.log('Reorder product:', sku);
    // Implementation for reordering
}

function viewHistory(sku) {
    console.log('View history for:', sku);
    // Implementation for viewing stock history
}
</script>
@endsection
