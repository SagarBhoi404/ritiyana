<?php
// app/Models/VendorOrder.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VendorOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'vendor_order_number',
        'vendor_id',
        'order_id',
        'customer_id',
        'subtotal',
        'tax_amount',
        'shipping_cost',
        'total_amount',
        'commission_rate',
        'commission_amount',
        'vendor_earning',
        'status',
        'confirmed_at',
        'shipped_at',
        'delivered_at',
        'tracking_number',
        'shipping_address',
        'vendor_notes',
        'cancellation_reason',
        'order_items',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'shipping_cost' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'commission_rate' => 'decimal:2',
        'commission_amount' => 'decimal:2',
        'vendor_earning' => 'decimal:2',
        'confirmed_at' => 'datetime',
        'shipped_at' => 'datetime',
        'delivered_at' => 'datetime',
        'shipping_address' => 'array',
        'order_items' => 'array',
    ];

    // ===== RELATIONSHIPS =====

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'vendor_id');
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    // ===== SCOPES =====

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeDelivered($query)
    {
        return $query->where('status', 'delivered');
    }

    public function scopeByVendor($query, $vendorId)
    {
        return $query->where('vendor_id', $vendorId);
    }

    // ===== ACCESSORS =====

    public function getStatusBadgeAttribute()
    {
        $badges = [
            'pending' => 'bg-yellow-100 text-yellow-800',
            'confirmed' => 'bg-blue-100 text-blue-800',
            'processing' => 'bg-purple-100 text-purple-800',
            'shipped' => 'bg-indigo-100 text-indigo-800',
            'delivered' => 'bg-green-100 text-green-800',
            'cancelled' => 'bg-red-100 text-red-800',
        ];

        return $badges[$this->status] ?? 'bg-gray-100 text-gray-800';
    }
}
