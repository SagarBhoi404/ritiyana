<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #{{ $order->order_number }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            .no-print { display: none; }
            body { print-color-adjust: exact; -webkit-print-color-adjust: exact; }
        }
    </style>
</head>
<body class="bg-gray-50">
    <div class="max-w-4xl mx-auto p-8">
        <!-- Print Button -->
        <div class="mb-4 no-print">
            <button onclick="window.print()" 
                class="px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700">
                Print Invoice
            </button>
            <button onclick="window.close()" 
                class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 ml-2">
                Close
            </button>
        </div>

        <!-- Invoice -->
        <div class="bg-white rounded-lg shadow-lg p-8">
            <!-- Header -->
            <div class="flex justify-between items-start mb-8">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">INVOICE</h1>
                    <p class="text-sm text-gray-600 mt-2">Invoice #{{ $order->order_number }}</p>
                    <p class="text-sm text-gray-600">Date: {{ $order->created_at->format('F d, Y') }}</p>
                </div>
                <div class="text-right">
                    @if(auth()->user()->vendorProfile && auth()->user()->vendorProfile->store_logo)
                        <img src="{{ asset('storage/' . auth()->user()->vendorProfile->store_logo) }}" 
                            alt="Logo" class="h-16 mb-2">
                    @endif
                    <h2 class="text-lg font-bold text-gray-900">
                        {{ auth()->user()->vendorProfile->business_name ?? auth()->user()->full_name }}
                    </h2>
                    @if(auth()->user()->vendorProfile)
                        <p class="text-sm text-gray-600">{{ auth()->user()->vendorProfile->business_address }}</p>
                        <p class="text-sm text-gray-600">{{ auth()->user()->vendorProfile->business_email }}</p>
                        <p class="text-sm text-gray-600">{{ auth()->user()->vendorProfile->business_phone }}</p>
                    @endif
                </div>
            </div>

            <!-- Customer & Order Info -->
            <div class="grid grid-cols-2 gap-8 mb-8">
                <div>
                    <h3 class="text-sm font-semibold text-gray-700 mb-2">BILL TO:</h3>
                    <p class="text-sm font-medium text-gray-900">{{ $order->customer->full_name ?? 'Unknown' }}</p>
                    <p class="text-sm text-gray-600">{{ $order->customer->email ?? '' }}</p>
                    <p class="text-sm text-gray-600">{{ $order->customer->phone ?? '' }}</p>
                </div>
                <div>
                    <h3 class="text-sm font-semibold text-gray-700 mb-2">SHIP TO:</h3>
                    @if($order->shipping_address)
                        <p class="text-sm text-gray-600">{{ $order->shipping_address['full_name'] ?? '' }}</p>
                        <p class="text-sm text-gray-600">{{ $order->shipping_address['address_line1'] ?? '' }}</p>
                        @if(isset($order->shipping_address['address_line2']))
                            <p class="text-sm text-gray-600">{{ $order->shipping_address['address_line2'] }}</p>
                        @endif
                        <p class="text-sm text-gray-600">
                            {{ $order->shipping_address['city'] ?? '' }}, {{ $order->shipping_address['state'] ?? '' }} {{ $order->shipping_address['pincode'] ?? '' }}
                        </p>
                    @endif
                </div>
            </div>

            <!-- Items Table -->
            <table class="w-full mb-8">
                <thead>
                    <tr class="border-b-2 border-gray-300">
                        <th class="text-left py-3 text-sm font-semibold text-gray-700">ITEM</th>
                        <th class="text-center py-3 text-sm font-semibold text-gray-700">QTY</th>
                        <th class="text-right py-3 text-sm font-semibold text-gray-700">PRICE</th>
                        <th class="text-right py-3 text-sm font-semibold text-gray-700">TOTAL</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($vendorItems as $item)
                        <tr class="border-b border-gray-200">
                            <td class="py-3">
                                <p class="text-sm font-medium text-gray-900">{{ $item->product_name }}</p>
                                <p class="text-xs text-gray-500">SKU: {{ $item->product_sku ?? 'N/A' }}</p>
                            </td>
                            <td class="text-center py-3 text-sm text-gray-600">{{ $item->quantity }}</td>
                            <td class="text-right py-3 text-sm text-gray-600">₹{{ number_format($item->price, 2) }}</td>
                            <td class="text-right py-3 text-sm font-medium text-gray-900">₹{{ number_format($item->total, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- Totals -->
            <div class="flex justify-end mb-8">
                <div class="w-64">
                    <div class="flex justify-between py-2 border-b border-gray-200">
                        <span class="text-sm text-gray-600">Subtotal:</span>
                        <span class="text-sm font-medium text-gray-900">₹{{ number_format($vendorSubtotal, 2) }}</span>
                    </div>
                    <div class="flex justify-between py-2 border-b border-gray-200">
                        <span class="text-sm text-gray-600">Commission:</span>
                        <span class="text-sm font-medium text-red-600">-₹{{ number_format($vendorCommission, 2) }}</span>
                    </div>
                    <div class="flex justify-between py-3 border-t-2 border-gray-300">
                        <span class="text-base font-semibold text-gray-900">Your Earning:</span>
                        <span class="text-base font-bold text-green-600">₹{{ number_format($vendorEarning, 2) }}</span>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="border-t border-gray-200 pt-4">
                <p class="text-xs text-gray-500 text-center">
                    Thank you for your business!
                </p>
                <p class="text-xs text-gray-400 text-center mt-1">
                    This is a vendor copy. For questions, contact {{ config('app.name') }} support.
                </p>
            </div>
        </div>
    </div>
</body>
</html>
