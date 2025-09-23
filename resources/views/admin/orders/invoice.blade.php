<!-- resources/views/admin/orders/invoice.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice - {{ $order->order_number }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            .no-print { display: none !important; }
            body { print-color-adjust: exact; }
        }
    </style>
</head>
<body class="bg-white">
    <div class="max-w-4xl mx-auto p-8">
        <!-- Print Button -->
        <div class="no-print mb-6 text-right">
            <button onclick="window.print()" class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700">
                Print Invoice
            </button>
        </div>

        <!-- Invoice Header -->
        <div class="border-b-2 border-gray-200 pb-8 mb-8">
            <div class="flex justify-between items-start">
                <div>
                    <h1 class="text-4xl font-bold text-purple-600 mb-2">RITIYANA</h1>
                    <p class="text-gray-600">Premium Puja Kits & Spiritual Items</p>
                    <div class="mt-4 text-sm text-gray-600">
                        <p>GST: 27XXXXX1234X1XX</p>
                        <p>Email: orders@ritiyana.com</p>
                        <p>Phone: +91 XXXXX XXXXX</p>
                    </div>
                </div>
                <div class="text-right">
                    <h2 class="text-2xl font-bold text-gray-900 mb-2">INVOICE</h2>
                    <p class="text-lg font-semibold text-gray-700">{{ $order->order_number }}</p>
                    <p class="text-sm text-gray-600">Date: {{ $order->created_at->format('F j, Y') }}</p>
                    <p class="text-sm text-gray-600">Due Date: {{ $order->created_at->addDays(30)->format('F j, Y') }}</p>
                </div>
            </div>
        </div>

        <!-- Bill To & Ship To -->
        <div class="grid grid-cols-2 gap-8 mb-8">
            <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-3">Bill To:</h3>
                <div class="text-sm text-gray-700">
                    <p class="font-semibold">{{ $order->customer_name }}</p>
                    <p>{{ $order->user->email }}</p>
                    @if($order->user->phone)
                    <p>{{ $order->user->phone }}</p>
                    @endif
                    @if($order->billing_address)
                        <div class="mt-2">
                            <p>{{ $order->billing_address['address_line_1'] ?? '' }}</p>
                            @if(!empty($order->billing_address['address_line_2']))
                            <p>{{ $order->billing_address['address_line_2'] }}</p>
                            @endif
                            <p>{{ $order->billing_address['city'] ?? '' }}, {{ $order->billing_address['state'] ?? '' }} {{ $order->billing_address['postal_code'] ?? '' }}</p>
                        </div>
                    @endif
                </div>
            </div>
            
            <div>
                <h3 class="text-lg font-semibold text-gray-900 mb-3">Ship To:</h3>
                <div class="text-sm text-gray-700">
                    @if($order->shipping_address)
                        <p class="font-semibold">{{ $order->shipping_address['first_name'] ?? '' }} {{ $order->shipping_address['last_name'] ?? '' }}</p>
                        <p>{{ $order->shipping_address['address_line_1'] ?? '' }}</p>
                        @if(!empty($order->shipping_address['address_line_2']))
                        <p>{{ $order->shipping_address['address_line_2'] }}</p>
                        @endif
                        <p>{{ $order->shipping_address['city'] ?? '' }}, {{ $order->shipping_address['state'] ?? '' }} {{ $order->shipping_address['postal_code'] ?? '' }}</p>
                        @if(!empty($order->shipping_address['phone']))
                        <p class="mt-2">Phone: {{ $order->shipping_address['phone'] }}</p>
                        @endif
                    @else
                        <p class="text-gray-500">Same as billing address</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Order Details -->
        <div class="mb-8">
            <div class="grid grid-cols-4 gap-4 text-sm text-gray-600 mb-4">
                <div>
                    <p><span class="font-semibold">Order Date:</span> {{ $order->created_at->format('M j, Y') }}</p>
                    <p><span class="font-semibold">Payment Status:</span> 
                        <span class="px-2 py-1 text-xs rounded {{ $order->payment_status_badge }}">
                            {{ ucfirst(str_replace('_', ' ', $order->payment_status)) }}
                        </span>
                    </p>
                </div>
                <div>
                    <p><span class="font-semibold">Order Status:</span>
                        <span class="px-2 py-1 text-xs rounded {{ $order->status_badge }}">
                            {{ ucfirst($order->status) }}
                        </span>
                    </p>
                    @if($order->shipped_at)
                    <p><span class="font-semibold">Shipped:</span> {{ $order->shipped_at->format('M j, Y') }}</p>
                    @endif
                </div>
                <div>
                    @if($order->payments->count() > 0)
                        @php $payment = $order->payments->first() @endphp
                        <p><span class="font-semibold">Payment Method:</span> {{ $payment->method_label }}</p>
                        @if($payment->gateway_transaction_id)
                        <p><span class="font-semibold">Transaction ID:</span> {{ $payment->gateway_transaction_id }}</p>
                        @endif
                    @endif
                </div>
                <div>
                    @if($order->delivered_at)
                    <p><span class="font-semibold">Delivered:</span> {{ $order->delivered_at->format('M j, Y') }}</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Items Table -->
        <div class="mb-8">
            <table class="w-full border-collapse border border-gray-300">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="border border-gray-300 px-4 py-3 text-left font-semibold">Item</th>
                        <th class="border border-gray-300 px-4 py-3 text-center font-semibold">Qty</th>
                        <th class="border border-gray-300 px-4 py-3 text-right font-semibold">Unit Price</th>
                        <th class="border border-gray-300 px-4 py-3 text-right font-semibold">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->orderItems as $item)
                    <tr>
                        <td class="border border-gray-300 px-4 py-3">
                            <div>
                                <p class="font-semibold">{{ $item->product->name ?? $item->pujaKit->name ?? 'Unknown Product' }}</p>
                                @if($item->product && $item->product->sku)
                                <p class="text-sm text-gray-600">SKU: {{ $item->product->sku }}</p>
                                @endif
                                @if($item->vendor)
                                <p class="text-sm text-gray-600">Vendor: {{ $item->vendor->first_name }} {{ $item->vendor->last_name }}</p>
                                @endif
                            </div>
                        </td>
                        <td class="border border-gray-300 px-4 py-3 text-center">{{ $item->quantity }}</td>
                        <td class="border border-gray-300 px-4 py-3 text-right">₹{{ number_format($item->price, 2) }}</td>
                        <td class="border border-gray-300 px-4 py-3 text-right font-semibold">₹{{ number_format($item->total, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Totals -->
        <div class="flex justify-end mb-8">
            <div class="w-80">
                <table class="w-full">
                    <tr>
                        <td class="py-2 text-right text-gray-700">Subtotal:</td>
                        <td class="py-2 text-right font-semibold">{{ $order->formatted_subtotal }}</td>
                    </tr>
                    @if($order->tax_amount > 0)
                    <tr>
                        <td class="py-2 text-right text-gray-700">Tax (18% GST):</td>
                        <td class="py-2 text-right font-semibold">₹{{ number_format($order->tax_amount, 2) }}</td>
                    </tr>
                    @endif
                    @if($order->shipping_amount > 0)
                    <tr>
                        <td class="py-2 text-right text-gray-700">Shipping:</td>
                        <td class="py-2 text-right font-semibold">₹{{ number_format($order->shipping_amount, 2) }}</td>
                    </tr>
                    @endif
                    @if($order->discount_amount > 0)
                    <tr>
                        <td class="py-2 text-right text-gray-700 text-green-600">Discount:</td>
                        <td class="py-2 text-right font-semibold text-green-600">-₹{{ number_format($order->discount_amount, 2) }}</td>
                    </tr>
                    @endif
                    <tr class="border-t-2 border-gray-300">
                        <td class="py-3 text-right text-lg font-bold text-gray-900">Total:</td>
                        <td class="py-3 text-right text-lg font-bold text-gray-900">{{ $order->formatted_total }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Payment Information -->
        @if($order->payments->count() > 0)
        <div class="mb-8 p-4 bg-gray-50 rounded-lg">
            <h3 class="text-lg font-semibold text-gray-900 mb-3">Payment Information</h3>
            @foreach($order->payments as $payment)
            <div class="flex justify-between items-center {{ !$loop->last ? 'mb-2' : '' }}">
                <div>
                    <span class="font-semibold">{{ $payment->gateway_label }}</span>
                    <span class="text-gray-600">({{ $payment->method_label }})</span>
                    @if($payment->gateway_transaction_id)
                    <br><span class="text-sm text-gray-500">Transaction: {{ $payment->gateway_transaction_id }}</span>
                    @endif
                </div>
                <div class="text-right">
                    <span class="font-semibold">{{ $payment->formatted_amount }}</span>
                    <br><span class="text-sm px-2 py-1 rounded {{ $payment->status_badge }}">{{ ucfirst($payment->status) }}</span>
                </div>
            </div>
            @endforeach
        </div>
        @endif

        <!-- Terms & Conditions -->
        <div class="border-t border-gray-200 pt-8">
            <h3 class="text-lg font-semibold text-gray-900 mb-3">Terms & Conditions</h3>
            <div class="text-sm text-gray-600 space-y-2">
                <p>1. All sales are final. Returns are only accepted for damaged or defective items within 7 days of delivery.</p>
                <p>2. Spiritual items are blessed and consecrated. Please handle with respect and care.</p>
                <p>3. For any queries regarding your order, please contact us at orders@ritiyana.com</p>
                <p>4. This invoice is generated electronically and does not require a signature.</p>
            </div>
        </div>

        <!-- Footer -->
        <div class="mt-8 pt-6 border-t border-gray-200 text-center text-sm text-gray-500">
            <p>Thank you for choosing Ritiyana for your spiritual needs!</p>
            <p class="mt-2">Generated on {{ now()->format('F j, Y \a\t g:i A') }}</p>
        </div>
    </div>

    <script>
        // Auto-print if opened with print parameter
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('print') === 'true') {
            window.onload = function() {
                window.print();
            };
        }
    </script>
</body>
</html>
