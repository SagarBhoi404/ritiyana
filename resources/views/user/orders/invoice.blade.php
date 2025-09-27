<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice - {{ $order->order_number }}</title>
    <style>
        /* Single font enforcement */
        * { font-family: helvetica !important; }

        /* Page settings */
        @page { margin: 20px; }
        body { margin: 0; padding: 0; color: #1f2937; font-size: 12px; line-height: 1.4; }

        /* Colors */
        .brand { color: #0f172a; font-weight: bold; }
        .accent { color: #7c3aed; font-weight: bold; }
        .muted { color: #6b7280; }
        
        /* Layout tables */
        .layout-table { width: 100%; border-collapse: collapse; }
        .layout-td { padding: 8px; vertical-align: top; }
        .w-50 { width: 50%; }
        
        /* Content blocks */
        .card { border: 1px solid #ddd; padding: 12px; margin: 6px 0; }
        .section-title { font-size: 13px; font-weight: bold; margin-bottom: 8px; }
        
        /* Typography */
        h1 { font-size: 20px; margin: 0 0 4px 0; font-weight: bold; }
        h2 { font-size: 16px; margin: 0 0 4px 0; font-weight: bold; }
        p { margin: 2px 0; }
        small { font-size: 11px; }
        
        /* Data table */
        .data-table { width: 100%; border-collapse: collapse; margin: 12px 0; }
        .data-table th { background: #f5f5f5; padding: 8px; text-align: left; font-weight: bold; border-bottom: 2px solid #ddd; }
        .data-table td { padding: 8px; border-bottom: 1px solid #eee; }
        .text-right { text-align: right; }
        .text-left { text-align: left; }
        
        /* Badges - simplified for PDF */
        .badge { padding: 2px 6px; border: 1px solid; font-size: 10px; }
        .badge-paid { background: #e6f7e6; color: #2d5a2d; border-color: #4a7c4a; }
        .badge-pending { background: #fff3cd; color: #7d5e00; border-color: #b8860b; }
        
        /* Footer */
        .footer { text-align: center; margin-top: 24px; font-size: 11px; color: #666; }
        
        /* Spacing utilities */
        .mb-0 { margin-bottom: 0; }
        .mt-1 { margin-top: 6px; }
        .separator { height: 2px; background: #eee; margin: 12px 0; }
    </style>
</head>
<body>

<!-- Header Section -->
<table class="layout-table">
    <tr>
        <td class="layout-td w-50">
            <h1 class="brand">Shree Samagri</h1>
            <p class="muted mb-0">Premium Puja Samagri & Kits</p>
            <small class="muted">support@shreesamagri.com</small><br>
            <small class="muted">www.shreesamagri.com</small>
        </td>
        <td class="layout-td w-50 text-right">
            <h2>Invoice</h2>
            <p><strong>No:</strong> {{ $order->order_number }}</p>
            <p><strong>Date:</strong> {{ $order->created_at->format('d M, Y') }}</p>
            <p>
                <strong>Status:</strong>
                @if($order->payment_status === 'paid')
                    <span class="badge badge-paid">Paid</span>
                @else
                    <span class="badge badge-pending">{{ ucfirst($order->payment_status) }}</span>
                @endif
            </p>
        </td>
    </tr>
</table>

<div class="separator"></div>

<!-- Address Section -->
<table class="layout-table">
    <tr>
        <td class="layout-td w-50">
            <div class="card">
                <div class="section-title accent">Billed To</div>
                @php $b = $order->billing_address ?? []; @endphp
                @if(!empty($b))
                    <p class="mb-0"><strong>{{ $b['first_name'] ?? '' }} {{ $b['last_name'] ?? '' }}</strong></p>
                    <p class="muted mb-0">{{ $b['address_line_1'] ?? '' }}</p>
                    @if(!empty($b['address_line_2'])) <p class="muted mb-0">{{ $b['address_line_2'] }}</p> @endif
                    <p class="muted mb-0">{{ $b['city'] ?? '' }}, {{ $b['state'] ?? '' }} - {{ $b['postal_code'] ?? '' }}</p>
                    @if(!empty($b['phone'])) <p class="muted mb-0">Phone: {{ $b['phone'] }}</p> @endif
                @else
                    <p class="muted">No billing address provided</p>
                @endif
            </div>
        </td>
        <td class="layout-td w-50">
            <div class="card">
                <div class="section-title accent">Shipped To</div>
                @php $s = $order->shipping_address ?? []; @endphp
                @if(!empty($s))
                    <p class="mb-0"><strong>{{ $s['first_name'] ?? '' }} {{ $s['last_name'] ?? '' }}</strong></p>
                    <p class="muted mb-0">{{ $s['address_line_1'] ?? '' }}</p>
                    @if(!empty($s['address_line_2'])) <p class="muted mb-0">{{ $s['address_line_2'] }}</p> @endif
                    <p class="muted mb-0">{{ $s['city'] ?? '' }}, {{ $s['state'] ?? '' }} - {{ $s['postal_code'] ?? '' }}</p>
                    @if(!empty($s['phone'])) <p class="muted mb-0">Phone: {{ $s['phone'] }}</p> @endif
                @else
                    <p class="muted">Same as billing address</p>
                @endif
            </div>
        </td>
    </tr>
</table>

<!-- Items Table -->
<table class="data-table">
    <thead>
        <tr>
            <th style="width: 45%;">Item Description</th>
            <th style="width: 15%;">SKU</th>
            <th style="width: 10%;" class="text-right">Qty</th>
            <th style="width: 15%;" class="text-right">Unit Price</th>
            <th style="width: 15%;" class="text-right">Total</th>
        </tr>
    </thead>
    <tbody>
        @foreach($order->orderItems as $item)
            @php $opt = $item->product_options ?? []; @endphp
            <tr>
                <td>
                    <strong>{{ $item->product_name }}</strong>
                    @if(!empty($opt))
                        <br><small class="muted">
                            @foreach($opt as $k => $v)
                                {{ ucfirst($k) }}: {{ $v }}@if(!$loop->last), @endif
                            @endforeach
                        </small>
                    @endif
                </td>
                <td>{{ $item->product_sku ?? 'N/A' }}</td>
                <td class="text-right">{{ $item->quantity }}</td>
                <td class="text-right">INR {{ number_format($item->price, 2) }}</td>
                <td class="text-right"><strong>INR {{ number_format($item->total, 2) }}</strong></td>
            </tr>
        @endforeach
    </tbody>
</table>

<!-- Payment & Totals Section -->
<table class="layout-table mt-1">
    <tr>
        <td class="layout-td w-50">
            <div class="card">
                <div class="section-title accent">Payment Details</div>
                @if($order->payments->isNotEmpty())
                    @php $p = $order->payments->first(); @endphp
                    <p><strong>Method:</strong> {{ $p->method_label }}</p>
                    <p><strong>Gateway:</strong> {{ $p->gateway_label }}</p>
                    <p><strong>Transaction ID:</strong> {{ $p->gateway_transaction_id ?? 'Pending' }}</p>
                    @if($p->paid_at)
                        <p><strong>Paid At:</strong> {{ $p->paid_at->format('d M, Y H:i') }}</p>
                    @endif
                @else
                    <p class="muted">No payment information available</p>
                @endif
            </div>
        </td>
        <td class="layout-td w-50">
            <table style="width: 100%; margin-left: auto;">
                <tr>
                    <td class="text-right" style="padding: 4px 8px; color: #6b7280;">Subtotal:</td>
                    <td class="text-right" style="padding: 4px 8px;"><strong>INR {{ number_format($order->subtotal, 2) }}</strong></td>
                </tr>
                <tr>
                    <td class="text-right" style="padding: 4px 8px; color: #6b7280;">Tax (GST):</td>
                    <td class="text-right" style="padding: 4px 8px;"><strong>INR {{ number_format($order->tax_amount, 2) }}</strong></td>
                </tr>
                <tr>
                    <td class="text-right" style="padding: 4px 8px; color: #6b7280;">Shipping:</td>
                    <td class="text-right" style="padding: 4px 8px;"><strong>INR {{ number_format($order->shipping_amount, 2) }}</strong></td>
                </tr>
                @if($order->discount_amount > 0)
                <tr>
                    <td class="text-right" style="padding: 4px 8px; color: #6b7280;">Discount:</td>
                    <td class="text-right" style="padding: 4px 8px; color: #dc2626;"><strong>-INR {{ number_format($order->discount_amount, 2) }}</strong></td>
                </tr>
                @endif
                <tr style="border-top: 2px solid #ddd;">
                    <td class="text-right" style="padding: 8px; font-size: 14px;"><strong>Grand Total:</strong></td>
                    <td class="text-right" style="padding: 8px; font-size: 14px;"><strong>INR {{ number_format($order->total_amount, 2) }}</strong></td>
                </tr>
            </table>
        </td>
    </tr>
</table>

<!-- Notes -->
<div class="card mt-1">
    <div class="section-title accent">Important Notes</div>
    <p class="muted mb-0">• This is a computer generated invoice and does not require signature.</p>
    <p class="muted mb-0">• Please retain this invoice for your records.</p>
    <p class="muted mb-0">• For any queries, contact us at support@shreesamagri.com</p>
</div>

<!-- Footer -->
<div class="footer">
    <div class="separator"></div>
    <p><strong>Thank you for choosing Shree Samagri!</strong></p>
    <p>May divine blessings bring peace and prosperity to your life.</p>
</div>

</body>
</html>
