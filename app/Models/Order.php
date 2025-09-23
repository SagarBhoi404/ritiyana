<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'user_id',
        'status',
        'subtotal',
        'tax_amount',
        'shipping_amount',
        'discount_amount',
        'total_amount',
        'currency',
        'billing_address',
        'shipping_address',
        'payment_method',
        'payment_status',
        'notes',
        'shipped_at',
        'delivered_at',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'shipping_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'billing_address' => 'array',
        'shipping_address' => 'array',
        'shipped_at' => 'datetime',
        'delivered_at' => 'datetime',
    ];

    // ===== RELATIONSHIPS =====
    
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function vendorOrders(): HasMany
    {
        return $this->hasMany(VendorOrder::class);
    }

    // Get vendor-specific items
    public function vendorItems(): HasMany
    {
        return $this->hasMany(OrderItem::class)->whereNotNull('vendor_id');
    }

    // Get platform items (non-vendor)
    public function platformItems(): HasMany
    {
        return $this->hasMany(OrderItem::class)->whereNull('vendor_id');
    }

    // ===== SCOPES =====
    
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeProcessing($query)
    {
        return $query->where('status', 'processing');
    }

    public function scopeShipped($query)
    {
        return $query->where('status', 'shipped');
    }

    public function scopeDelivered($query)
    {
        return $query->where('status', 'delivered');
    }

    public function scopeCancelled($query)
    {
        return $query->where('status', 'cancelled');
    }

    public function scopePaid($query)
    {
        return $query->where('payment_status', 'paid');
    }

    public function scopeUnpaid($query)
    {
        return $query->where('payment_status', 'pending');
    }

    public function scopeRecent($query, $days = 30)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    public function scopeByCustomer($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    // ===== ACCESSORS =====
    
    public function getFormattedTotalAttribute()
    {
        return '₹' . number_format($this->total_amount, 2);
    }

    public function getFormattedSubtotalAttribute()
    {
        return '₹' . number_format($this->subtotal, 2);
    }

    public function getStatusBadgeAttribute()
    {
        $badges = [
            'pending' => 'bg-yellow-100 text-yellow-800',
            'processing' => 'bg-blue-100 text-blue-800',
            'shipped' => 'bg-purple-100 text-purple-800',
            'delivered' => 'bg-green-100 text-green-800',
            'cancelled' => 'bg-red-100 text-red-800',
            'refunded' => 'bg-orange-100 text-orange-800',
        ];

        return $badges[$this->status] ?? 'bg-gray-100 text-gray-800';
    }

    public function getPaymentStatusBadgeAttribute()
    {
        $badges = [
            'pending' => 'bg-yellow-100 text-yellow-800',
            'paid' => 'bg-green-100 text-green-800',
            'failed' => 'bg-red-100 text-red-800',
            'refunded' => 'bg-orange-100 text-orange-800',
            'partially_refunded' => 'bg-orange-100 text-orange-800',
        ];

        return $badges[$this->payment_status] ?? 'bg-gray-100 text-gray-800';
    }

    public function getTotalItemsAttribute()
    {
        return $this->orderItems->sum('quantity');
    }

    public function getUniqueProductsAttribute()
    {
        return $this->orderItems->count();
    }

    public function getCustomerNameAttribute()
    {
        return $this->user->first_name . ' ' . $this->user->last_name;
    }

    public function getShippingAddressTextAttribute()
    {
        if (!$this->shipping_address) {
            return 'No address provided';
        }

        $addr = $this->shipping_address;
        return $addr['address_line_1'] . ', ' . $addr['city'] . ', ' . $addr['state'] . ' - ' . $addr['postal_code'];
    }

    // ===== VENDOR-SPECIFIC ACCESSORS =====
    
    public function getTotalVendorCommissionAttribute()
    {
        return $this->orderItems->sum('vendor_commission');
    }

    public function getTotalVendorEarningsAttribute()
    {
        return $this->orderItems->sum('vendor_earning');
    }

    public function getPlatformEarningsAttribute()
    {
        return $this->total_amount - $this->total_vendor_earnings;
    }

    public function getVendorCountAttribute()
    {
        return $this->orderItems->where('vendor_id', '!=', null)->pluck('vendor_id')->unique()->count();
    }

    public function getHasVendorItemsAttribute()
    {
        return $this->orderItems->where('vendor_id', '!=', null)->count() > 0;
    }

    // ===== HELPER METHODS =====
    
    public function isPaid(): bool
    {
        return $this->payment_status === 'paid';
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isProcessing(): bool
    {
        return $this->status === 'processing';
    }

    public function isShipped(): bool
    {
        return $this->status === 'shipped';
    }

    public function isDelivered(): bool
    {
        return $this->status === 'delivered';
    }

    public function isCancelled(): bool
    {
        return $this->status === 'cancelled';
    }

    public function canBeCancelled(): bool
    {
        return in_array($this->status, ['pending', 'processing']);
    }

    public function canBeShipped(): bool
    {
        return $this->status === 'processing' && $this->isPaid();
    }

    public function hasMultipleVendors(): bool
    {
        return $this->vendor_count > 1;
    }

    // ===== CALCULATION METHODS =====
    
    /**
     * **FIXED: Only recalculate if explicitly called, not in boot**
     */
    public function recalculateTotals(): self
    {
        $this->load('orderItems');
        
        // Calculate subtotal from order items
        $subtotal = $this->orderItems->sum(function ($item) {
            return $item->price * $item->quantity;
        });

        // Calculate tax (you can customize this logic)
        $taxRate = 0.18; // 18% GST
        $taxAmount = $subtotal * $taxRate;

        // Calculate shipping (you can customize this logic)
        $shippingAmount = $this->calculateShippingCost($subtotal);

        // Apply discounts (you can customize this logic)
        $discountAmount = $this->calculateDiscounts($subtotal);

        $total = $subtotal + $taxAmount + $shippingAmount - $discountAmount;

        $this->update([
            'subtotal' => $subtotal,
            'tax_amount' => $taxAmount,
            'shipping_amount' => $shippingAmount,
            'discount_amount' => $discountAmount,
            'total_amount' => $total,
        ]);

        return $this;
    }

    public function calculateVendorCommissions(): void
    {
        $this->orderItems->each(function ($item) {
            if ($item->vendor_id && $item->product) {
                $commission = $item->calculateCommission();
                $item->update([
                    'vendor_commission' => $commission['commission_amount'],
                    'vendor_earning' => $commission['vendor_earning'],
                ]);
            }
        });
    }

    private function calculateShippingCost(float $subtotal): float
    {
        // Basic shipping calculation - customize as needed
        if ($subtotal >= 500) {
            return 0; // Free shipping above ₹500
        }
        return 50; // Flat ₹50 shipping
    }

    private function calculateDiscounts(float $subtotal): float
    {
        // Apply any applicable discounts - customize as needed
        return 0;
    }

    // ===== ORDER STATUS UPDATES =====
    
    public function markAsProcessing(): bool
    {
        return $this->update(['status' => 'processing']);
    }

    public function markAsShipped(string $trackingNumber = null): bool
    {
        return $this->update([
            'status' => 'shipped',
            'shipped_at' => now(),
            'notes' => $trackingNumber ? "Tracking: $trackingNumber\n" . $this->notes : $this->notes,
        ]);
    }

    public function markAsDelivered(): bool
    {
        return $this->update([
            'status' => 'delivered',
            'delivered_at' => now(),
        ]);
    }

    public function markAsCancelled(string $reason = null): bool
    {
        return $this->update([
            'status' => 'cancelled',
            'notes' => $reason ? "Cancelled: $reason\n" . $this->notes : $this->notes,
        ]);
    }

    // ===== VENDOR ORDER MANAGEMENT =====
    
    public function createVendorOrders(): void
    {
        $vendorItems = $this->orderItems->groupBy('vendor_id')->filter(function ($items, $vendorId) {
            return !is_null($vendorId);
        });

        foreach ($vendorItems as $vendorId => $items) {
            $vendorTotal = $items->sum('total');
            $vendorCommission = $items->sum('vendor_commission');
            $vendorEarning = $items->sum('vendor_earning');

            VendorOrder::create([
                'vendor_order_number' => 'VO-' . $this->order_number . '-' . $vendorId,
                'vendor_id' => $vendorId,
                'order_id' => $this->id,
                'customer_id' => $this->user_id,
                'subtotal' => $vendorTotal,
                'total_amount' => $vendorTotal,
                'commission_rate' => $vendorCommission > 0 ? ($vendorCommission / $vendorTotal) * 100 : 0,
                'commission_amount' => $vendorCommission,
                'vendor_earning' => $vendorEarning,
                'status' => 'pending',
                'order_items' => $items->toArray(),
            ]);
        }
    }

    // ===== FIXED BOOT METHOD =====
    
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($order) {
            if (empty($order->order_number)) {
                $order->order_number = 'ORD-' . strtoupper(\Str::random(8));
            }
            if (empty($order->currency)) {
                $order->currency = 'INR';
            }
        });

        // **REMOVED THE PROBLEMATIC AUTO-CALCULATION**
        // Don't auto-calculate totals - let CheckoutController handle it
        
        static::updated(function ($order) {
            // Update vendor order statuses when main order status changes
            if ($order->wasChanged('status')) {
                $order->vendorOrders()->update(['status' => $order->status]);
            }
        });
    }




    
}
