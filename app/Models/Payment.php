<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Log;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'payment_id',
        'gateway_order_id', // FIXED: Added this
        'gateway',
        'gateway_transaction_id',
        'method',
        'status',
        'amount',
        'currency',
        'gateway_response',
        'failure_reason', // FIXED: Added this
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
            'cashfree' => 'Cashfree', // FIXED: Added Cashfree
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

    // ===== HELPER METHODS =====
    
    public function isSuccessful(): bool
    {
        return $this->status === 'completed';
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isFailed(): bool
    {
        return $this->status === 'failed';
    }

    public function canBeRefunded(): bool
    {
        return $this->isSuccessful() && $this->gateway !== 'cod';
    }

    public function isOnlinePayment(): bool
    {
        return $this->method !== 'cod';
    }

    public function isCashOnDelivery(): bool
    {
        return $this->method === 'cod';
    }

    // ===== VENDOR-SPECIFIC METHODS (FIXED) =====
    
    public function calculateVendorSplit(): array
    {
        if (!$this->order) {
            return [];
        }

        $vendorSplits = [];
        
        // **FIXED: Only process items that have vendor_id**
        $vendorItems = $this->order->orderItems()
            ->whereNotNull('vendor_id')
            ->get()
            ->groupBy('vendor_id');

        if ($vendorItems->isEmpty()) {
            return []; // No vendor items, return empty array
        }

        foreach ($vendorItems as $vendorId => $items) {
            if (!$vendorId) continue; // Skip if vendor_id is null/empty
            
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
        
        // **FIXED: Only process if there are actual vendor splits**
        if (empty($vendorSplits)) {
            Log::info('No vendor items found for payment, skipping vendor payouts', [
                'payment_id' => $this->id,
                'order_id' => $this->order_id
            ]);
            return;
        }

        foreach ($vendorSplits as $split) {
            // **FIXED: Validate vendor_id before creating payout**
            if (empty($split['vendor_id'])) {
                Log::warning('Empty vendor_id in split, skipping', [
                    'payment_id' => $this->id,
                    'split' => $split
                ]);
                continue;
            }

            try {
                VendorPayout::create([
                    'payout_id' => 'PAY-' . strtoupper(\Str::random(8)),
                    'vendor_id' => $split['vendor_id'],
                    'amount' => $split['vendor_earning'],
                    'period_start' => now()->startOfDay(),
                    'period_end' => now()->endOfDay(),
                    'total_orders' => 1,
                    'total_sales' => $split['total_amount'],
                    'total_commission' => $split['commission_amount'],
                    'status' => 'pending',
                ]);
                
                Log::info('Vendor payout created', [
                    'payment_id' => $this->id,
                    'vendor_id' => $split['vendor_id'],
                    'amount' => $split['vendor_earning']
                ]);
                
            } catch (\Exception $e) {
                Log::error('Failed to create vendor payout', [
                    'payment_id' => $this->id,
                    'vendor_id' => $split['vendor_id'],
                    'error' => $e->getMessage()
                ]);
            }
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
            'failure_reason' => $reason,
            'gateway_response' => array_merge($this->gateway_response ?? [], $gatewayResponse),
        ]);
    }

    // ===== STATIC METHODS =====
    
    public static function generatePaymentId(): string
    {
        return 'PAY-' . strtoupper(\Str::random(12));
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

    // ===== BOOT METHOD (FIXED) =====
    
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
                    'refunded', 'partially_refunded' => 'refunded',
                    default => 'pending'
                };

                $payment->order->update(['payment_status' => $newPaymentStatus]);
                
                Log::info('Order payment status updated', [
                    'order_id' => $payment->order_id,
                    'payment_status' => $newPaymentStatus
                ]);
            }

            // **FIXED: Process vendor payouts only if payment is successful AND order has vendor items**
            if ($payment->status === 'completed' && $payment->wasChanged('status')) {
                try {
                    $payment->processVendorPayouts();
                } catch (\Exception $e) {
                    Log::error('Vendor payout processing failed', [
                        'payment_id' => $payment->id,
                        'error' => $e->getMessage()
                    ]);
                    // Don't throw the exception - let payment completion succeed
                }
            }
        });
    }
}
