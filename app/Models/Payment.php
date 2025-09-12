<?php
// app/Models/Payment.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'payment_id',
        'gateway',
        'gateway_transaction_id',
        'method',
        'status',
        'amount',
        'currency',
        'gateway_response',
        'paid_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'gateway_response' => 'array',
        'paid_at' => 'datetime',
    ];

    // ===== RELATIONSHIPS =====

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // For vendor payouts tracking
    public function vendorPayouts(): HasMany
    {
        return $this->hasMany(VendorPayout::class, 'transaction_id', 'gateway_transaction_id');
    }

    // ===== SCOPES =====

    public function scopeSuccessful($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    public function scopeRefunded($query)
    {
        return $query->where('status', 'refunded');
    }

    public function scopeByGateway($query, $gateway)
    {
        return $query->where('gateway', $gateway);
    }

    public function scopeByMethod($query, $method)
    {
        return $query->where('method', $method);
    }

    public function scopeRecent($query, $days = 30)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    public function scopeForOrder($query, $orderId)
    {
        return $query->where('order_id', $orderId);
    }

    // ===== ACCESSORS =====

    public function getFormattedAmountAttribute()
    {
        return '₹' . number_format($this->amount, 2);
    }

    public function getGatewayLabelAttribute()
    {
        $gateways = [
            'razorpay' => 'Razorpay',
            'paytm' => 'Paytm',
            'stripe' => 'Stripe',
            'paypal' => 'PayPal',
            'phonepe' => 'PhonePe',
            'gpay' => 'Google Pay',
            'cod' => 'Cash on Delivery',
        ];

        return $gateways[$this->gateway] ?? ucfirst($this->gateway);
    }

    public function getMethodLabelAttribute()
    {
        $methods = [
            'card' => 'Credit/Debit Card',
            'netbanking' => 'Net Banking',
            'upi' => 'UPI',
            'wallet' => 'Digital Wallet',
            'cod' => 'Cash on Delivery',
            'emi' => 'EMI',
        ];

        return $methods[$this->method] ?? ucfirst($this->method);
    }

    public function getStatusBadgeAttribute()
    {
        $badges = [
            'pending' => 'bg-yellow-100 text-yellow-800',
            'processing' => 'bg-blue-100 text-blue-800',
            'completed' => 'bg-green-100 text-green-800',
            'failed' => 'bg-red-100 text-red-800',
            'cancelled' => 'bg-gray-100 text-gray-800',
            'refunded' => 'bg-orange-100 text-orange-800',
        ];

        return $badges[$this->status] ?? 'bg-gray-100 text-gray-800';
    }

    public function getPaymentDurationAttribute()
    {
        if ($this->paid_at && $this->created_at) {
            return $this->created_at->diffInMinutes($this->paid_at);
        }
        return null;
    }

    public function getGatewayFeeAttribute()
    {
        // Calculate gateway fees (customize based on your gateway)
        $feeRates = [
            'razorpay' => 0.02, // 2%
            'paytm' => 0.018,   // 1.8%
            'stripe' => 0.029,  // 2.9%
            'paypal' => 0.035,  // 3.5%
        ];

        $rate = $feeRates[$this->gateway] ?? 0.02;
        return $this->amount * $rate;
    }

    // ===== HELPER METHODS =====

    public function isSuccessful(): bool
    {
        return $this->status === 'completed';
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isProcessing(): bool
    {
        return $this->status === 'processing';
    }

    public function isFailed(): bool
    {
        return $this->status === 'failed';
    }

    public function isRefunded(): bool
    {
        return $this->status === 'refunded';
    }

    public function isCancelled(): bool
    {
        return $this->status === 'cancelled';
    }

    public function canBeRefunded(): bool
    {
        return $this->isSuccessful() && $this->gateway !== 'cod';
    }

    public function canBeRetried(): bool
    {
        return in_array($this->status, ['failed', 'cancelled']);
    }

    public function isOnlinePayment(): bool
    {
        return $this->method !== 'cod';
    }

    public function isCashOnDelivery(): bool
    {
        return $this->method === 'cod';
    }

    // ===== VENDOR-SPECIFIC METHODS =====

    public function calculateVendorSplit(): array
    {
        if (!$this->order) {
            return [];
        }

        $vendorSplits = [];
        $vendorItems = $this->order->orderItems->groupBy('vendor_id')->filter(function ($items, $vendorId) {
            return !is_null($vendorId);
        });

        foreach ($vendorItems as $vendorId => $items) {
            $vendorTotal = $items->sum('total');
            $vendorCommission = $items->sum('vendor_commission');
            $vendorEarning = $items->sum('vendor_earning');

            $vendorSplits[$vendorId] = [
                'vendor_id' => $vendorId,
                'total_amount' => $vendorTotal,
                'commission_amount' => $vendorCommission,
                'vendor_earning' => $vendorEarning,
                'percentage_of_order' => ($vendorTotal / $this->amount) * 100,
            ];
        }

        return $vendorSplits;
    }

    public function processVendorPayouts(): void
    {
        if (!$this->isSuccessful()) {
            return;
        }

        $vendorSplits = $this->calculateVendorSplit();

        foreach ($vendorSplits as $split) {
            // Create payout record for each vendor
            VendorPayout::create([
                'payout_id' => 'PAY-' . strtoupper(Str::random(8)),
                'vendor_id' => $split['vendor_id'],
                'amount' => $split['vendor_earning'],
                'period_start' => now()->startOfDay(),
                'period_end' => now()->endOfDay(),
                'total_orders' => 1,
                'total_sales' => $split['total_amount'],
                'total_commission' => $split['commission_amount'],
                'status' => 'pending',
            ]);
        }
    }

    // ===== PAYMENT OPERATIONS =====

    public function markAsCompleted(array $gatewayResponse = []): bool
    {
        return $this->update([
            'status' => 'completed',
            'paid_at' => now(),
            'gateway_response' => array_merge($this->gateway_response ?? [], $gatewayResponse),
        ]);
    }

    public function markAsFailed(string $reason = null, array $gatewayResponse = []): bool
    {
        return $this->update([
            'status' => 'failed',
            'gateway_response' => array_merge($this->gateway_response ?? [], [
                'failure_reason' => $reason,
                ...$gatewayResponse
            ]),
        ]);
    }

    public function markAsRefunded(float $refundAmount = null, string $refundId = null): bool
    {
        $refundAmount = $refundAmount ?? $this->amount;
        
        return $this->update([
            'status' => $refundAmount >= $this->amount ? 'refunded' : 'partially_refunded',
            'gateway_response' => array_merge($this->gateway_response ?? [], [
                'refund_amount' => $refundAmount,
                'refund_id' => $refundId,
                'refunded_at' => now()->toISOString(),
            ]),
        ]);
    }

    // ===== STATIC METHODS =====

    public static function generatePaymentId(): string
    {
        return 'PAY_' . strtoupper(Str::random(12));
    }

    public static function createForOrder(Order $order, array $paymentData): self
    {
        return self::create([
            'order_id' => $order->id,
            'payment_id' => self::generatePaymentId(),
            'amount' => $order->total_amount,
            'currency' => $order->currency,
            'status' => 'pending',
            ...$paymentData
        ]);
    }

    // ===== BOOT METHOD =====

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($payment) {
            if (empty($payment->payment_id)) {
                $payment->payment_id = self::generatePaymentId();
            }

            if (empty($payment->currency)) {
                $payment->currency = 'INR';
            }
        });

        static::updated(function ($payment) {
            // Update order payment status when payment status changes
            if ($payment->wasChanged('status') && $payment->order) {
                $newPaymentStatus = match ($payment->status) {
                    'completed' => 'paid',
                    'failed', 'cancelled' => 'failed',
                    'refunded' => 'refunded',
                    'partially_refunded' => 'partially_refunded',
                    default => 'pending'
                };

                $payment->order->update(['payment_status' => $newPaymentStatus]);

                // Process vendor payouts if payment is successful
                if ($payment->status === 'completed') {
                    $payment->processVendorPayouts();
                }
            }
        });
    }
}
