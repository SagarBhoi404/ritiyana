<?php
// app/Models/VendorAnalytics.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VendorAnalytics extends Model
{
    use HasFactory;

    protected $fillable = [
        'vendor_id',
        'date',
        'total_orders',
        'total_revenue',
        'total_commission',
        'total_products_sold',
        'active_products',
        'pending_products',
        'product_views',
        'product_clicks',
        'new_customers',
        'returning_customers',
    ];

    protected $casts = [
        'date' => 'date',
        'total_revenue' => 'decimal:2',
        'total_commission' => 'decimal:2',
    ];

    // ===== RELATIONSHIPS =====

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'vendor_id');
    }

    // ===== SCOPES =====

    public function scopeForVendor($query, $vendorId)
    {
        return $query->where('vendor_id', $vendorId);
    }

    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('date', [$startDate, $endDate]);
    }

    public function scopeThisMonth($query)
    {
        return $query->whereMonth('date', now()->month)
                    ->whereYear('date', now()->year);
    }

    // ===== ACCESSORS =====

    public function getConversionRateAttribute()
    {
        if ($this->product_views > 0) {
            return round(($this->total_orders / $this->product_views) * 100, 2);
        }
        return 0;
    }

    public function getAverageOrderValueAttribute()
    {
        if ($this->total_orders > 0) {
            return $this->total_revenue / $this->total_orders;
        }
        return 0;
    }
}
