<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice - {{ $order->order_number }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
        }
        .company-name {
            font-size: 24px;
            font-weight: bold;
            color: #333;
            margin-bottom: 5px;
        }
        .invoice-title {
            font-size: 18px;
            color: #666;
            margin-top: 10px;
        }
        .invoice-details {
            display: table;
            width: 100%;
            margin-bottom: 30px;
        }
        .invoice-left, .invoice-right {
            display: table-cell;
            width: 50%;
            vertical-align: top;
        }
        .invoice-right {
            text-align: right;
        }
        .section-title {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 10px;
            color: #333;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .items-table th,
        .items-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        .items-table th {
            background-color: #f5f5f5;
            font-weight: bold;
        }
        .text-right {
            text-align: right;
        }
        .totals {
            margin-top: 20px;
            text-align: right;
        }
        .totals table {
            margin-left: auto;
            border-collapse: collapse;
        }
        .totals td {
            padding: 5px 10px;
            border: none;
        }
        .total-line {
            font-weight: bold;
            font-size: 14px;
            border-top: 2px solid #333;
        }
        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 20px;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <div class="company-name">Ritiyana</div>
        <div>Your Trusted Partner for Spiritual Products</div>
        <div class="invoice-title">TAX INVOICE</div>
    </div>

    <!-- Invoice Details -->
    <div class="invoice-details">
        <div class="invoice-left">
            <div class="section-title">Bill To:</div>
            @php $billingAddress = json_decode($order->billing_address, true); @endphp
            <div>{{ $billingAddress['first_name'] }} {{ $billingAddress['last_name'] }}</div>
            @if($billingAddress['company'])
                <div>{{ $billingAddress['company'] }}</div>
            @endif
            <div>{{ $billingAddress['address_line_1'] }}</div>
            @if($billingAddress['address_line_2'])
                <div>{{ $billingAddress['address_line_2'] }}</div>
            @endif
            <div>{{ $billingAddress['city'] }}, {{ $billingAddress['state'] }} {{ $billingAddress['postal_code'] }}</div>
            <div>{{ $billingAddress['country'] }}</div>
            @if($billingAddress['phone'])
                <div>Phone: {{ $billingAddress['phone'] }}</div>
            @endif
        </div>
        
        <div class="invoice-right">
            <div class="section-title">Invoice Details:</div>
            <div><strong>Invoice #:</strong> {{ $order->order_number }}</div>
            <div><strong>Date:</strong> {{ $order->created_at->format('d/m/Y') }}</div>
            <div><strong>Payment Status:</strong> {{ ucfirst($order->payment_status) }}</div>
            <div><strong>Order Status:</strong> {{ ucfirst($order->status) }}</div>
        </div>
    </div>

    <!-- Items Table -->
    <table class="items-table">
        <thead>
            <tr>
                <th style="width: 50%;">Item</th>
                <th style="width: 15%;" class="text-right">Qty</th>
                <th style="width: 15%;" class="text-right">Rate</th>
                <th style="width: 20%;" class="text-right">Amount</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->orderItems as $item)
            <tr>
                <td>
                    @if($item->product)
                        {{ $item->product->name }}
                    @elseif($item->puja_kit)
                        {{ $item->puja_kit->name }} (Puja Kit)
                    @else
                        {{ $item->name }}
                    @endif
                </td>
                <td class="text-right">{{ $item->quantity }}</td>
                <td class="text-right">₹{{ number_format($item->unit_price, 2) }}</td>
                <td class="text-right">₹{{ number_format($item->total, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Totals -->
    <div class="totals">
        <table>
            <tr>
                <td>Subtotal:</td>
                <td class="text-right">₹{{ number_format($order->subtotal, 2) }}</td>
            </tr>
            @if($order->discount_amount > 0)
            <tr>
                <td>Discount:</td>
                <td class="text-right">-₹{{ number_format($order->discount_amount, 2) }}</td>
            </tr>
            @endif
            @if($order->tax_amount > 0)
            <tr>
                <td>Tax:</td>
                <td class="text-right">₹{{ number_format($order->tax_amount, 2) }}</td>
            </tr>
            @endif
            @if($order->shipping_amount > 0)
            <tr>
                <td>Shipping:</td>
                <td class="text-right">₹{{ number_format($order->shipping_amount, 2) }}</td>
            </tr>
            @endif
            <tr class="total-line">
                <td><strong>Total:</strong></td>
                <td class="text-right"><strong>₹{{ number_format($order->total_amount, 2) }}</strong></td>
            </tr>
        </table>
    </div>

    <!-- Payment Info -->
    @if($order->payments->where('status', 'completed')->first())
    @php $payment = $order->payments->where('status', 'completed')->first(); @endphp
    <div style="margin-top: 30px;">
        <div class="section-title">Payment Information:</div>
        <div><strong>Payment Method:</strong> {{ $payment->gateway_label }}</div>
        <div><strong>Transaction ID:</strong> {{ $payment->gateway_transaction_id ?? 'N/A' }}</div>
        <div><strong>Payment Date:</strong> {{ $payment->paid_at ? $payment->paid_at->format('d/m/Y H:i') : 'N/A' }}</div>
    </div>
    @endif

    <!-- Footer -->
    <div class="footer">
        <div><strong>Thank you for choosing Ritiyana!</strong></div>
        <div>For any queries, contact us at support@ritiyana.com</div>
        <div>This is a computer-generated invoice and does not require a signature.</div>
    </div>
</body>
</html>
