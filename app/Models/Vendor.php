<?php
// app/Models/Vendor.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Vendor extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'business_name',
        'business_type',
        'business_address',
        'business_phone',
        'business_email',
        'tax_id',
        'pan_number',
        'gst_number',
        'bank_name',
        'account_number',
        'ifsc_code',
        'account_holder_name',
        'commission_rate',
        'store_logo',
        'store_description',
        'business_hours',
        'status',
        'approved_at',
        'approved_by',
        'rejection_reason',
        'is_featured',
        'priority_order',
        'social_links',
    ];

    protected $casts = [
        'business_hours' => 'array',
        'social_links' => 'array',
        'commission_rate' => 'decimal:2',
        'is_featured' => 'boolean',
        'approved_at' => 'datetime',
    ];

    // ===== RELATIONSHIPS =====

    // Vendor belongs to User
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Vendor has many products
    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'vendor_id', 'user_id');
    }

    // Vendor has many orders
    public function orders(): HasMany
    {
        return $this->hasMany(VendorOrder::class, 'vendor_id', 'user_id');
    }

    // Vendor has many payouts
    public function payouts(): HasMany
    {
        return $this->hasMany(VendorPayout::class, 'vendor_id', 'user_id');
    }

    // Vendor has many documents
    public function documents(): HasMany
    {
        return $this->hasMany(VendorDocument::class, 'vendor_id', 'user_id');
    }

    // Vendor has many analytics records
    public function analytics(): HasMany
    {
        return $this->hasMany(VendorAnalytics::class, 'vendor_id', 'user_id');
    }

    // Approved by admin
    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    // ===== SCOPES =====

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    // ===== ACCESSORS =====

    public function getFullBusinessNameAttribute()
    {
        return $this->business_name . ' (' . $this->business_type . ')';
    }

    public function getTotalEarningsAttribute()
    {
        return $this->orders()->where('status', 'delivered')->sum('vendor_earning');
    }

    public function getActiveProductsCountAttribute()
    {
        return $this->products()->where('is_active', true)->count();
    }


    public static function getCommissionRateForCurrentUser()
    {
        $user = auth()->user();

        if (!$user) {
            return null; // no logged in user
        }

        $vendor = self::where('user_id', $user->id)->first();

        return $vendor ? $vendor->commission_rate : null;
    }
}
