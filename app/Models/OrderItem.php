<?php
// app/Models/OrderItem.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_id',
        'product_name',
        'product_sku',
        'quantity',
        'price',
        'total',
        'product_options',
        'vendor_id',
        'vendor_commission',
        'vendor_earning',
    ];

    protected $casts = [
        'product_options' => 'array',
        'quantity' => 'integer',
        'price' => 'decimal:2',
        'total' => 'decimal:2',
        'vendor_commission' => 'decimal:2',
        'vendor_earning' => 'decimal:2',
    ];

    // ===== RELATIONSHIPS =====

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'vendor_id');
    }

    // ===== SCOPES =====

    public function scopeForOrder($query, $orderId)
    {
        return $query->where('order_id', $orderId);
    }

    public function scopeForProduct($query, $productId)
    {
        return $query->where('product_id', $productId);
    }

    public function scopeForVendor($query, $vendorId)
    {
        return $query->where('vendor_id', $vendorId);
    }

    public function scopeVendorItems($query)
    {
        return $query->whereNotNull('vendor_id');
    }

    public function scopeHighValue($query, $amount = 1000)
    {
        return $query->where('total', '>=', $amount);
    }

    // ===== ACCESSORS =====

    public function getFormattedPriceAttribute()
    {
        return '₹' . number_format($this->price, 2);
    }

    public function getFormattedTotalAttribute()
    {
        return '₹' . number_format($this->total, 2);
    }

    public function getFormattedCommissionAttribute()
    {
        return '₹' . number_format($this->vendor_commission, 2);
    }

    public function getFormattedEarningAttribute()
    {
        return '₹' . number_format($this->vendor_earning, 2);
    }

    public function getProductImageAttribute()
    {
        return $this->product?->featured_image_url ?? asset('images/default-product.png');
    }

    public function getVendorNameAttribute()
    {
        return $this->vendor?->first_name . ' ' . $this->vendor?->last_name ?? 'Platform';
    }

    public function getCommissionRateAttribute()
    {
        if ($this->total > 0 && $this->vendor_commission > 0) {
            return round(($this->vendor_commission / $this->total) * 100, 2);
        }
        return 0;
    }

    // ===== HELPER METHODS =====

    public function isVendorItem(): bool
    {
        return !is_null($this->vendor_id);
    }

    public function isPlatformItem(): bool
    {
        return is_null($this->vendor_id);
    }

    public function hasOptions(): bool
    {
        return !empty($this->product_options);
    }

    public function calculateCommission($rate = null): float
    {
        $commissionRate = $rate ?? $this->product?->vendor?->vendorProfile?->commission_rate ?? 8.00;
        return ($this->total * $commissionRate) / 100;
    }

    public function updateCommission(): bool
    {
        if ($this->isVendorItem()) {
            $commission = $this->calculateCommission();
            return $this->update([
                'vendor_commission' => $commission,
                'vendor_earning' => $this->total - $commission,
            ]);
        }
        return false;
    }

    // ===== BOOT METHOD =====

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($orderItem) {
            // Auto-calculate commission when creating
            if ($orderItem->vendor_id && $orderItem->total > 0) {
                $commission = $orderItem->calculateCommission();
                $orderItem->vendor_commission = $commission;
                $orderItem->vendor_earning = $orderItem->total - $commission;
            }
        });

        static::created(function ($orderItem) {
            // Update product sales statistics
            if ($orderItem->product) {
                $orderItem->product->increment('total_sales', $orderItem->quantity);
                $orderItem->product->increment('total_revenue', $orderItem->total);
            }

            // Log inventory change
            InventoryLog::create([
                'product_id' => $orderItem->product_id,
                'type' => 'sale',
                'quantity_changed' => -$orderItem->quantity,
                'quantity_before' => $orderItem->product->stock_quantity + $orderItem->quantity,
                'quantity_after' => $orderItem->product->stock_quantity,
                'reference_type' => Order::class,
                'reference_id' => $orderItem->order_id,
                'notes' => "Sale from order #{$orderItem->order->order_number}",
                'created_by' => $orderItem->order->user_id,
            ]);
        });
    }
}
