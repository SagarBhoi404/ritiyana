@extends('layouts.admin')

@section('title', 'Products Management')
@section('breadcrumb', 'Products')

@section('content')
    <div class="px-4 sm:px-6 lg:px-8 py-8">
        <!-- Page Header -->
        <div class="mb-8">
            <div class="sm:flex sm:items-center sm:justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Products Management</h1>
                    <p class="mt-2 text-sm text-gray-700">Manage puja items, kits, and inventory</p>
                </div>
                <div class="mt-4 sm:mt-0">
                    <a href="{{ route('admin.products.create') }}"
                        class="inline-flex items-center px-4 py-2 bg-purple-600 border border-transparent rounded-lg text-sm font-medium text-white hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2">
                        <i data-lucide="plus" class="w-4 h-4 mr-2"></i>
                        Add Product
                    </a>
                </div>
            </div>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-7 gap-4 mb-8">
            <div class="bg-white rounded-xl p-4 border border-gray-200 shadow-sm">
                <div class="text-xs font-medium text-gray-600 mb-1">Total Products</div>
                <div class="text-2xl font-bold text-gray-900">{{ $totalProducts }}</div>
            </div>
            <div class="bg-white rounded-xl p-4 border border-gray-200 shadow-sm">
                <div class="text-xs font-medium text-gray-600 mb-1">Active Products</div>
                <div class="text-2xl font-bold text-green-600">{{ $activeProducts }}</div>
            </div>
            <div class="bg-white rounded-xl p-4 border border-gray-200 shadow-sm">
                <div class="text-xs font-medium text-gray-600 mb-1">Featured</div>
                <div class="text-2xl font-bold text-purple-600">{{ $featuredProducts }}</div>
            </div>
            <div class="bg-white rounded-xl p-4 border border-gray-200 shadow-sm">
                <div class="text-xs font-medium text-gray-600 mb-1">Low Stock</div>
                <div class="text-2xl font-bold text-orange-600">{{ $lowStockProducts }}</div>
            </div>
            <div class="bg-white rounded-xl p-4 border border-gray-200 shadow-sm">
                <div class="text-xs font-medium text-gray-600 mb-1">Out of Stock</div>
                <div class="text-2xl font-bold text-red-600">{{ $outOfStockProducts }}</div>
            </div>
            <div class="bg-white rounded-xl p-4 border border-gray-200 shadow-sm">
                <div class="text-xs font-medium text-gray-600 mb-1">Draft</div>
                <div class="text-2xl font-bold text-gray-600">{{ $draftProducts }}</div>
            </div>
            <div class="bg-white rounded-xl p-4 border border-gray-200 shadow-sm">
                <div class="text-xs font-medium text-gray-600 mb-1">Vendor Products</div>
                <div class="text-2xl font-bold text-blue-600">{{ \App\Models\Product::where('is_vendor_product', true)->count() }}</div>
            </div>
        </div>

        <!-- Products Table -->
        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden shadow-sm">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Vendor</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Categories</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stock</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($products as $product)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        @if ($product->featured_image)
                                            <img class="h-12 w-12 rounded-lg object-cover" 
                                                src="{{ asset('storage/' . $product->featured_image) }}" 
                                                alt="{{ $product->name }}">
                                        @else
                                            <div class="h-12 w-12 rounded-lg bg-gray-200 flex items-center justify-center">
                                                <i data-lucide="package" class="w-6 h-6 text-gray-400"></i>
                                            </div>
                                        @endif
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $product->name }}</div>
                                            <div class="text-xs text-gray-500">SKU: {{ $product->sku }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    @if ($product->is_vendor_product && $product->vendor)
                                        <div class="flex items-center">
                                            @if ($product->vendor->vendorProfile && $product->vendor->vendorProfile->store_logo)
                                                <img class="h-8 w-8 rounded-full" 
                                                    src="{{ asset('storage/' . $product->vendor->vendorProfile->store_logo) }}" 
                                                    alt="Store">
                                            @else
                                                <div class="h-8 w-8 rounded-full bg-purple-100 flex items-center justify-center">
                                                    <i data-lucide="store" class="w-4 h-4 text-purple-600"></i>
                                                </div>
                                            @endif
                                            <div class="ml-2">
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ $product->vendor->vendorProfile->business_name ?? $product->vendor->first_name . ' ' . $product->vendor->last_name }}
                                                </div>
                                                @if ($product->vendor->vendorProfile)
                                                    <span class="inline-flex px-2 py-0.5 text-xs font-semibold rounded-full 
                                                        {{ $product->vendor->vendorProfile->status === 'approved' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                                        {{ ucfirst($product->vendor->vendorProfile->status) }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    @else
                                        <span class="text-sm text-gray-500">Platform Product</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-wrap gap-1">
                                        @foreach ($product->categories as $category)
                                            <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full bg-blue-100 text-blue-800">
                                                {{ $category->name }}
                                            </span>
                                        @endforeach
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm">
                                        <div class="font-medium text-gray-900">₹{{ number_format($product->price, 2) }}</div>
                                        @if ($product->sale_price)
                                            <div class="text-xs text-green-600">Sale: ₹{{ number_format($product->sale_price, 2) }}</div>
                                        @endif
                                        @if ($product->is_vendor_product && $product->vendor_commission_rate)
                                            <div class="text-xs text-gray-500">Commission: {{ $product->vendor_commission_rate }}%</div>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm">
                                        <div class="font-medium text-gray-900">{{ $product->stock_quantity }}</div>
                                        <span class="inline-flex px-2 py-0.5 text-xs font-semibold rounded-full 
                                            @if($product->stock_status === 'in_stock') bg-green-100 text-green-800
                                            @elseif($product->stock_status === 'out_of_stock') bg-red-100 text-red-800
                                            @else bg-yellow-100 text-yellow-800 @endif">
                                            {{ ucfirst(str_replace('_', ' ', $product->stock_status)) }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="space-y-1">
                                        <div>
                                            <span class="text-xs text-gray-600">Status:</span>
                                            <span class="inline-flex px-2 py-0.5 text-xs font-semibold rounded-full 
                                                {{ $product->is_active ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800' }}">
                                                {{ $product->is_active ? 'Active' : 'Inactive' }}
                                            </span>
                                        </div>
                                        @if ($product->is_featured)
                                            <div>
                                                <span class="text-xs text-gray-600">Featured:</span>
                                                <span class="inline-flex px-2 py-0.5 text-xs font-semibold rounded-full bg-purple-100 text-purple-800">
                                                    Featured
                                                </span>
                                            </div>
                                        @endif
                                        @if ($product->is_vendor_product)
                                            <div class="flex items-center space-x-1">
                                                <span class="text-xs text-gray-600">Approval:</span>
                                                <span class="inline-flex px-2 py-0.5 text-xs font-semibold rounded-full {{ $product->approval_status_badge }}">
                                                    {{ ucfirst($product->approval_status) }}
                                                </span>
                                                @if($product->approval_status === 'pending')
                                                    <div class="flex space-x-1 ml-1">
                                                        <button 
                                                            onclick="openProductApprovalModal({{ $product->id }}, 'approve')"
                                                            class="inline-flex items-center px-1.5 py-0.5 bg-green-600 hover:bg-green-700 text-white text-xs rounded transition-colors"
                                                            title="Approve Product">
                                                            <i data-lucide="check" class="w-3 h-3"></i>
                                                        </button>
                                                        <button 
                                                            onclick="openProductApprovalModal({{ $product->id }}, 'reject')"
                                                            class="inline-flex items-center px-1.5 py-0.5 bg-red-600 hover:bg-red-700 text-white text-xs rounded transition-colors"
                                                            title="Reject Product">
                                                            <i data-lucide="x" class="w-3 h-3"></i>
                                                        </button>
                                                    </div>
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex items-center space-x-2">
                                        <a href="{{ route('admin.products.show', $product) }}"
                                            class="text-purple-600 hover:text-purple-900 p-1" title="View">
                                            <i data-lucide="eye" class="w-4 h-4"></i>
                                        </a>
                                        <a href="{{ route('admin.products.edit', $product) }}"
                                            class="text-blue-600 hover:text-blue-900 p-1" title="Edit">
                                            <i data-lucide="edit" class="w-4 h-4"></i>
                                        </a>
                                        <form action="{{ route('admin.products.destroy', $product) }}" method="POST"
                                            class="inline"
                                            onsubmit="return confirm('Are you sure you want to delete this product?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900 p-1"
                                                title="Delete">
                                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center">
                                    <div class="text-gray-500">
                                        <i data-lucide="package" class="w-12 h-12 mx-auto mb-4 text-gray-300"></i>
                                        <p class="text-lg font-medium mb-2">No products found</p>
                                        <p class="text-sm mb-4">Create your first product to get started.</p>
                                        <a href="{{ route('admin.products.create') }}"
                                            class="inline-flex items-center px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 text-sm font-medium">
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

            <!-- Pagination -->
            <div class="px-6 py-3 border-t border-gray-200 bg-gray-50">
                <div class="flex items-center justify-between">
                    <div class="text-sm text-gray-700">
                        Showing <span class="font-medium">{{ $products->firstItem() ?? 0 }}</span> to
                        <span class="font-medium">{{ $products->lastItem() ?? 0 }}</span> of
                        <span class="font-medium">{{ $products->total() }}</span> results
                    </div>
                    <div>
                        {{ $products->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Product Approval Modal -->
    <div id="productApprovalModal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-full max-w-md shadow-lg rounded-xl bg-white">
            <div class="mt-3">
                <!-- Modal Header -->
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900" id="productModalTitle">Approve Product</h3>
                    <button onclick="closeProductApprovalModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                        <i data-lucide="x" class="w-5 h-5"></i>
                    </button>
                </div>

                <!-- Modal Form -->
                <form id="productApprovalForm" method="POST">
                    @csrf
                    @method('PATCH')
                    
                    <div class="mb-4">
                        <p class="text-sm text-gray-600" id="productModalMessage">
                            Are you sure you want to approve this product? It will become visible to customers on the website.
                        </p>
                    </div>

                    <!-- Rejection Reason (hidden by default) -->
                    <div id="productRejectionReasonDiv" class="mb-4 hidden">
                        <label for="rejection_reason" class="block text-sm font-medium text-gray-700 mb-2">
                            Rejection Reason <span class="text-red-500">*</span>
                        </label>
                        <textarea 
                            name="rejection_reason" 
                            id="product_rejection_reason" 
                            rows="4" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent text-sm"
                            placeholder="Please provide a reason for rejection..."></textarea>
                    </div>

                    <!-- Modal Actions -->
                    <div class="flex justify-end space-x-3 mt-6">
                        <button 
                            type="button" 
                            onclick="closeProductApprovalModal()" 
                            class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors text-sm font-medium">
                            Cancel
                        </button>
                        <button 
                            type="submit" 
                            id="productSubmitBtn"
                            class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors text-sm font-medium">
                            Confirm Approval
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
        // Product Approval Modal Functions
        function openProductApprovalModal(productId, action) {
            const modal = document.getElementById('productApprovalModal');
            const form = document.getElementById('productApprovalForm');
            const modalTitle = document.getElementById('productModalTitle');
            const modalMessage = document.getElementById('productModalMessage');
            const submitBtn = document.getElementById('productSubmitBtn');
            const rejectionDiv = document.getElementById('productRejectionReasonDiv');
            const rejectionTextarea = document.getElementById('product_rejection_reason');

            // Reset form
            rejectionDiv.classList.add('hidden');
            rejectionTextarea.removeAttribute('required');
            rejectionTextarea.value = '';

            // Configure based on action
            if (action === 'approve') {
                form.action = `/admin/products/${productId}/approve`;
                modalTitle.textContent = 'Approve Product';
                modalMessage.textContent = 'Are you sure you want to approve this product? It will become visible to customers on the website.';
                submitBtn.textContent = 'Confirm Approval';
                submitBtn.className = 'px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors text-sm font-medium';
            } else if (action === 'reject') {
                form.action = `/admin/products/${productId}/reject`;
                modalTitle.textContent = 'Reject Product';
                modalMessage.textContent = 'Please provide a reason for rejecting this product.';
                submitBtn.textContent = 'Confirm Rejection';
                submitBtn.className = 'px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors text-sm font-medium';
                rejectionDiv.classList.remove('hidden');
                rejectionTextarea.setAttribute('required', 'required');
            }

            modal.classList.remove('hidden');
        }

        function closeProductApprovalModal() {
            const modal = document.getElementById('productApprovalModal');
            modal.classList.add('hidden');
        }

        // Close modal when clicking outside
        document.getElementById('productApprovalModal')?.addEventListener('click', function(e) {
            if (e.target === this) {
                closeProductApprovalModal();
            }
        });

        // Auto-hide alerts after 5 seconds
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
