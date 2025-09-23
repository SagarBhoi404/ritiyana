<!-- resources/views/admin/orders/show.blade.php -->
@extends('layouts.admin')

@section('title', 'Order Details - ' . $order->order_number)
@section('breadcrumb')
<nav class="flex" aria-label="Breadcrumb">
    <ol class="flex items-center space-x-4">
        <li>
            <a href="{{ route('admin.orders.index') }}" class="text-gray-500 hover:text-gray-700">Orders</a>
        </li>
        <li>
            <div class="flex items-center">
                <i data-lucide="chevron-right" class="w-4 h-4 text-gray-400 mx-2"></i>
                <span class="text-gray-900 font-medium">{{ $order->order_number }}</span>
            </div>
        </li>
    </ol>
</nav>
@endsection

@section('content')
<div class="p-6 max-w-7xl mx-auto">
    <!-- Order Header -->
    <div class="bg-white rounded-xl border border-gray-200 mb-6">
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">{{ $order->order_number }}</h1>
                    <p class="text-gray-500 mt-1">
                        Placed on {{ $order->created_at->format('F j, Y \a\t g:i A') }}
                    </p>
                </div>
                <div class="flex items-center space-x-3">
                    <span class="inline-flex px-4 py-2 text-sm font-semibold rounded-full {{ $order->status_badge }}">
                        {{ ucfirst($order->status) }}
                    </span>
                    <span class="inline-flex px-4 py-2 text-sm font-semibold rounded-full {{ $order->payment_status_badge }}">
                        {{ ucfirst(str_replace('_', ' ', $order->payment_status)) }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="p-6 bg-gray-50 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <button id="updateStatusBtn" class="flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        <i data-lucide="edit" class="w-4 h-4 mr-2"></i>
                        Update Status
                    </button>
                    @if($order->payment_status !== 'paid')
                    <button id="updatePaymentBtn" class="flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                        <i data-lucide="credit-card" class="w-4 h-4 mr-2"></i>
                        Update Payment
                    </button>
                    @endif
                    <a href="{{ route('admin.orders.invoice', $order) }}" target="_blank" class="flex items-center px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700">
                        <i data-lucide="printer" class="w-4 h-4 mr-2"></i>
                        Print Invoice
                    </a>
                </div>
                <div class="text-right">
                    <p class="text-2xl font-bold text-gray-900">{{ $order->formatted_total }}</p>
                    <p class="text-sm text-gray-500">{{ $order->total_items }} items</p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Order Items -->
            <div class="bg-white rounded-xl border border-gray-200">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Order Items</h3>
                </div>
                <div class="divide-y divide-gray-200">
                    @foreach($order->orderItems as $item)
                    <div class="p-6 flex items-center space-x-4">
                        <div class="w-16 h-16 bg-gray-100 rounded-lg flex items-center justify-center">
                            @if($item->product && $item->product->images)
                                <img src="{{ $item->product->images[0] ?? '' }}" alt="{{ $item->product->name ?? 'Product' }}" class="w-full h-full object-cover rounded-lg">
                            @elseif($item->pujaKit && $item->pujaKit->image)
                                <img src="{{ $item->pujaKit->image }}" alt="{{ $item->pujaKit->name }}" class="w-full h-full object-cover rounded-lg">
                            @else
                                <i data-lucide="package" class="w-8 h-8 text-gray-400"></i>
                            @endif
                        </div>
                        <div class="flex-1">
                            <h4 class="font-semibold text-gray-900">
                                {{ $item->product->name ?? $item->pujaKit->name ?? 'Unknown Product' }}
                            </h4>
                            <p class="text-sm text-gray-500">
                                @if($item->vendor)
                                    Sold by {{ $item->vendor->first_name }} {{ $item->vendor->last_name }}
                                @else
                                    Sold by Ritiyana
                                @endif
                            </p>
                            @if($item->product && $item->product->sku)
                            <p class="text-xs text-gray-400">SKU: {{ $item->product->sku }}</p>
                            @endif
                        </div>
                        <div class="text-center">
                            <p class="font-semibold text-gray-900">₹{{ number_format($item->price, 2) }}</p>
                            <p class="text-sm text-gray-500">Qty: {{ $item->quantity }}</p>
                        </div>
                        <div class="text-right">
                            <p class="font-semibold text-gray-900">₹{{ number_format($item->total, 2) }}</p>
                            @if($item->vendor_commission > 0)
                            <p class="text-xs text-gray-500">Commission: ₹{{ number_format($item->vendor_commission, 2) }}</p>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
                
                <!-- Order Summary -->
                <div class="p-6 bg-gray-50 border-t border-gray-200">
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Subtotal</span>
                            <span class="font-semibold">{{ $order->formatted_subtotal }}</span>
                        </div>
                        @if($order->tax_amount > 0)
                        <div class="flex justify-between">
                            <span class="text-gray-600">Tax</span>
                            <span class="font-semibold">₹{{ number_format($order->tax_amount, 2) }}</span>
                        </div>
                        @endif
                        @if($order->shipping_amount > 0)
                        <div class="flex justify-between">
                            <span class="text-gray-600">Shipping</span>
                            <span class="font-semibold">₹{{ number_format($order->shipping_amount, 2) }}</span>
                        </div>
                        @endif
                        @if($order->discount_amount > 0)
                        <div class="flex justify-between text-green-600">
                            <span>Discount</span>
                            <span class="font-semibold">-₹{{ number_format($order->discount_amount, 2) }}</span>
                        </div>
                        @endif
                        <div class="flex justify-between pt-3 border-t border-gray-200">
                            <span class="text-lg font-semibold text-gray-900">Total</span>
                            <span class="text-lg font-bold text-gray-900">{{ $order->formatted_total }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Payment Information -->
            <div class="bg-white rounded-xl border border-gray-200">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Payment Information</h3>
                </div>
                <div class="p-6">
                    @if($order->payments->count() > 0)
                        @foreach($order->payments as $payment)
                        <div class="flex items-center justify-between py-3 {{ !$loop->last ? 'border-b border-gray-200' : '' }}">
                            <div>
                                <p class="font-semibold text-gray-900">{{ $payment->gateway_label }}</p>
                                <p class="text-sm text-gray-500">{{ $payment->method_label }}</p>
                                @if($payment->gateway_transaction_id)
                                <p class="text-xs text-gray-400">Transaction ID: {{ $payment->gateway_transaction_id }}</p>
                                @endif
                            </div>
                            <div class="text-right">
                                <p class="font-semibold text-gray-900">{{ $payment->formatted_amount }}</p>
                                <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full {{ $payment->status_badge }}">
                                    {{ ucfirst($payment->status) }}
                                </span>
                                @if($payment->paid_at)
                                <p class="text-xs text-gray-500 mt-1">{{ $payment->paid_at->format('M j, Y g:i A') }}</p>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    @else
                        <div class="text-center py-8">
                            <i data-lucide="credit-card" class="w-12 h-12 text-gray-400 mx-auto mb-3"></i>
                            <p class="text-gray-500">No payment information available</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Customer Information -->
            <div class="bg-white rounded-xl border border-gray-200">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Customer</h3>
                </div>
                <div class="p-6">
                    <div class="flex items-center space-x-3 mb-4">
                        <div class="w-10 h-10 bg-purple-100 rounded-full flex items-center justify-center">
                            <span class="text-purple-600 font-semibold text-sm">
                                {{ substr($order->user->first_name, 0, 1) }}{{ substr($order->user->last_name, 0, 1) }}
                            </span>
                        </div>
                        <div>
                            <p class="font-semibold text-gray-900">{{ $order->customer_name }}</p>
                            <p class="text-sm text-gray-500">{{ $order->user->email }}</p>
                        </div>
                    </div>
                    @if($order->user->phone)
                    <div class="flex items-center space-x-2 text-sm text-gray-600">
                        <i data-lucide="phone" class="w-4 h-4"></i>
                        <span>{{ $order->user->phone }}</span>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Shipping Address -->
            <div class="bg-white rounded-xl border border-gray-200">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Shipping Address</h3>
                </div>
                <div class="p-6">
                    @if($order->shipping_address)
                        <div class="text-sm text-gray-600 space-y-1">
                            <p class="font-semibold text-gray-900">
                                {{ $order->shipping_address['first_name'] ?? '' }} {{ $order->shipping_address['last_name'] ?? '' }}
                            </p>
                            <p>{{ $order->shipping_address['address_line_1'] ?? '' }}</p>
                            @if(!empty($order->shipping_address['address_line_2']))
                            <p>{{ $order->shipping_address['address_line_2'] }}</p>
                            @endif
                            <p>{{ $order->shipping_address['city'] ?? '' }}, {{ $order->shipping_address['state'] ?? '' }} {{ $order->shipping_address['postal_code'] ?? '' }}</p>
                            @if(!empty($order->shipping_address['phone']))
                            <p class="mt-2">
                                <i data-lucide="phone" class="w-4 h-4 inline mr-1"></i>
                                {{ $order->shipping_address['phone'] }}
                            </p>
                            @endif
                        </div>
                    @else
                        <p class="text-gray-500">No shipping address provided</p>
                    @endif
                </div>
            </div>

            <!-- Order Timeline -->
            <div class="bg-white rounded-xl border border-gray-200">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Order Timeline</h3>
                </div>
                <div class="p-6">
                    <div class="space-y-4">
                        <div class="flex items-start space-x-3">
                            <div class="w-2 h-2 bg-green-500 rounded-full mt-2"></div>
                            <div>
                                <p class="font-semibold text-gray-900">Order Placed</p>
                                <p class="text-sm text-gray-500">{{ $order->created_at->format('M j, Y g:i A') }}</p>
                            </div>
                        </div>
                        
                        @if($order->shipped_at)
                        <div class="flex items-start space-x-3">
                            <div class="w-2 h-2 bg-blue-500 rounded-full mt-2"></div>
                            <div>
                                <p class="font-semibold text-gray-900">Order Shipped</p>
                                <p class="text-sm text-gray-500">{{ $order->shipped_at->format('M j, Y g:i A') }}</p>
                            </div>
                        </div>
                        @endif
                        
                        @if($order->delivered_at)
                        <div class="flex items-start space-x-3">
                            <div class="w-2 h-2 bg-green-500 rounded-full mt-2"></div>
                            <div>
                                <p class="font-semibold text-gray-900">Order Delivered</p>
                                <p class="text-sm text-gray-500">{{ $order->delivered_at->format('M j, Y g:i A') }}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            @if($order->notes)
            <!-- Order Notes -->
            <div class="bg-white rounded-xl border border-gray-200">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Notes</h3>
                </div>
                <div class="p-6">
                    <p class="text-sm text-gray-600 whitespace-pre-line">{{ $order->notes }}</p>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Status Update Modal -->
<div id="statusModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-xl max-w-md w-full p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Update Order Status</h3>
            <form id="statusForm">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select name="status" id="statusSelect" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500">
                        <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Processing</option>
                        <option value="shipped" {{ $order->status == 'shipped' ? 'selected' : '' }}>Shipped</option>
                        <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>Delivered</option>
                        <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        <option value="refunded" {{ $order->status == 'refunded' ? 'selected' : '' }}>Refunded</option>
                    </select>
                </div>
                
                <div class="mb-4" id="trackingNumberField" style="display: none;">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tracking Number</label>
                    <input type="text" name="tracking_number" id="trackingNumber" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500" placeholder="Enter tracking number">
                </div>
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Notes (Optional)</label>
                    <textarea name="notes" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500" placeholder="Add any notes about this status change..."></textarea>
                </div>
                
                <div class="flex space-x-3">
                    <button type="submit" id="submitStatusBtn" class="flex-1 px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 disabled:opacity-50">
                        Update Status
                    </button>
                    <button type="button" id="closeStatusModal" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- @push('scripts') --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    const statusModal = document.getElementById('statusModal');
    const statusBtn = document.getElementById('updateStatusBtn');
    const closeBtn = document.getElementById('closeStatusModal');
    const statusForm = document.getElementById('statusForm');
    const statusSelect = document.getElementById('statusSelect');
    const trackingField = document.getElementById('trackingNumberField');
    const submitBtn = document.getElementById('submitStatusBtn');
    
    // Show/hide tracking number field based on status
    if (statusSelect) {
        statusSelect.addEventListener('change', function() {
            if (this.value === 'shipped') {
                trackingField.style.display = 'block';
            } else {
                trackingField.style.display = 'none';
            }
        });
    }
    
    // Open modal
    if (statusBtn) {
        statusBtn.addEventListener('click', function(e) {
            e.preventDefault();
            statusModal.classList.remove('hidden');
            if (statusSelect) {
                statusSelect.dispatchEvent(new Event('change'));
            }
        });
    }
    
    // Close modal
    if (closeBtn) {
        closeBtn.addEventListener('click', function() {
            statusModal.classList.add('hidden');
            statusForm.reset();
        });
    }
    
    // Close on outside click
    if (statusModal) {
        statusModal.addEventListener('click', function(e) {
            if (e.target === statusModal) {
                statusModal.classList.add('hidden');
                statusForm.reset();
            }
        });
    }
    
    // Handle form submission
    if (statusForm) {
        statusForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Get CSRF token
            const csrfToken = document.querySelector('meta[name="csrf-token"]');
            if (!csrfToken) {
                alert('CSRF token missing. Please refresh the page.');
                return;
            }
            
            // Disable submit button
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.textContent = 'Updating...';
            }
            
            // Get form data manually to ensure proper formatting
            const statusValue = statusSelect.value;
            const trackingValue = document.querySelector('input[name="tracking_number"]').value || '';
            const notesValue = document.querySelector('textarea[name="notes"]').value || '';
            
            console.log('Sending data:', {
                status: statusValue,
                tracking_number: trackingValue,
                notes: notesValue
            });
            
            // Create form data properly
            const formData = new FormData();
            formData.append('status', statusValue);
            formData.append('tracking_number', trackingValue);
            formData.append('notes', notesValue);
            formData.append('_token', csrfToken.getAttribute('content'));
            
            // Alternative: send as JSON
            const jsonData = {
                status: statusValue,
                tracking_number: trackingValue,
                notes: notesValue
            };
            
            fetch('{{ route("admin.orders.update-status", $order) }}', {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken.getAttribute('content'),
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify(jsonData)
            })
            .then(response => {
                console.log('Response status:', response.status);
                
                // Handle different response types
                const contentType = response.headers.get('content-type');
                if (contentType && contentType.includes('application/json')) {
                    return response.json();
                } else {
                    return response.text().then(text => {
                        console.error('Non-JSON response:', text);
                        throw new Error('Server returned non-JSON response');
                    });
                }
            })
            .then(data => {
                console.log('Response data:', data);
                
                if (data.success) {
                    alert(data.message || 'Order status updated successfully');
                    window.location.reload();
                } else {
                    // Handle validation errors
                    let errorMessage = data.message || 'Unknown error occurred';
                    if (data.errors) {
                        const errorList = Object.values(data.errors).flat();
                        errorMessage += '\n' + errorList.join('\n');
                    }
                    alert('Error: ' + errorMessage);
                }
            })
            .catch(error => {
                console.error('Fetch error:', error);
                alert('Error updating status: ' + error.message);
            })
            .finally(() => {
                // Re-enable submit button
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.textContent = 'Update Status';
                }
            });
        });
    }
});
</script>
{{-- @endpush --}}
@endsection
