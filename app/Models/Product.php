<?php
// app/Models/Product.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'short_description',
        'description',
        'sku',
        'type',
        'price',
        'sale_price',
        'cost_price',
        'stock_quantity',
        'manage_stock',
        'stock_status',
        'weight',
        'dimensions',
        'featured_image',
        'gallery_images',
        'is_featured',
        'is_active',
        'meta_title',
        'meta_description',
        'attributes',
        'created_by',
        // ===== VENDOR FIELDS ADDED =====
        'vendor_id',
        'approval_status',
        'approved_at',
        'approved_by',
        'is_vendor_product',
        'vendor_commission_rate',
        'total_sales',
        'total_revenue',
    ];

    protected $casts = [
        'gallery_images' => 'array',
        'attributes' => 'array',
        'manage_stock' => 'boolean',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
        // ===== VENDOR CASTS ADDED =====
        'is_vendor_product' => 'boolean',
        'vendor_commission_rate' => 'decimal:2',
        'total_revenue' => 'decimal:2',
        'approved_at' => 'datetime',
        'price' => 'decimal:2',
        'sale_price' => 'decimal:2',
        'cost_price' => 'decimal:2',
    ];

    // ===== EXISTING RELATIONSHIPS =====

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'product_categories');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function pujaKits()
    {
        return $this->belongsToMany(PujaKit::class, 'puja_kit_product')
            ->withPivot('quantity', 'price')
            ->withTimestamps();
    }

    // ===== NEW VENDOR RELATIONSHIPS =====

    // Product belongs to vendor (user)
    public function vendor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'vendor_id');
    }

    // Product approved by admin
    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    // Product has many reviews
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    // Product has many inventory logs
    public function inventoryLogs(): HasMany
    {
        return $this->hasMany(InventoryLog::class);
    }

    // Product has many order items
    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    // ===== EXISTING SCOPES =====

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeLowStock($query)
    {
        return $query->where('stock_quantity', '<', 10);
    }

    // ===== NEW VENDOR SCOPES =====

    public function scopeApproved($query)
    {
        return $query->where('approval_status', 'approved');
    }

    public function scopeVendorProducts($query)
    {
        return $query->where('is_vendor_product', true);
    }

    public function scopeByVendor($query, $vendorId)
    {
        return $query->where('vendor_id', $vendorId);
    }

    public function scopePendingApproval($query)
    {
        return $query->where('approval_status', 'pending');
    }

    // ===== EXISTING MUTATORS =====

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = $value;
        if (empty($this->attributes['slug'])) {
            $this->attributes['slug'] = Str::slug($value);
        }
    }

    // ===== EXISTING ACCESSORS =====

    public function getFeaturedImageUrlAttribute()
    {
        return $this->featured_image ? asset('storage/' . $this->featured_image) : asset('images/default-product.png');
    }

    public function getDiscountPercentageAttribute()
    {
        if ($this->sale_price && $this->price > $this->sale_price) {
            return round((($this->price - $this->sale_price) / $this->price) * 100);
        }
        return 0;
    }

    // ===== NEW VENDOR ACCESSORS =====

    public function getFinalPriceAttribute()
    {
        return $this->sale_price ?? $this->price;
    }

    public function getVendorCommissionAttribute()
    {
        if (!$this->is_vendor_product || !$this->vendor_id) {
            return 0;
        }

        $rate = $this->vendor_commission_rate ?? $this->vendor?->vendorProfile?->commission_rate ?? 8.00;
        return ($this->final_price * $rate) / 100;
    }

    public function getVendorEarningAttribute()
    {
        if (!$this->is_vendor_product || !$this->vendor_id) {
            return $this->final_price;
        }

        return $this->final_price - $this->vendor_commission;
    }

    public function getPlatformEarningAttribute()
    {
        return $this->vendor_commission;
    }

    public function getApprovalStatusBadgeAttribute()
    {
        $badges = [
            'pending' => 'bg-yellow-100 text-yellow-800',
            'approved' => 'bg-green-100 text-green-800',
            'rejected' => 'bg-red-100 text-red-800',
        ];

        return $badges[$this->approval_status] ?? 'bg-gray-100 text-gray-800';
    }

    // ===== NEW VENDOR HELPER METHODS =====

    public function isVendorProduct(): bool
    {
        return $this->is_vendor_product && $this->vendor_id;
    }

    public function isApproved(): bool
    {
        return $this->approval_status === 'approved';
    }

    public function isPending(): bool
    {
        return $this->approval_status === 'pending';
    }

    public function canBeEdited(): bool
    {
        return $this->approval_status !== 'rejected' && $this->is_active;
    }

    public function calculateCommission($quantity = 1)
    {
        $totalPrice = $this->final_price * $quantity;
        $commission = ($totalPrice * $this->getCommissionRate()) / 100;

        return [
            'total_price' => $totalPrice,
            'commission_amount' => $commission,
            'vendor_earning' => $totalPrice - $commission,
            'platform_earning' => $commission,
        ];
    }

    private function getCommissionRate(): float
    {
        return $this->vendor_commission_rate ?? $this->vendor?->vendorProfile?->commission_rate ?? 8.00;
    }

    // ===== BOOT METHOD FOR AUTO-SETTING VENDOR FIELDS =====

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($product) {
            // Auto-set vendor fields when creating
            if (auth()->check() && auth()->user()->hasRole('shopkeeper')) {
                $product->vendor_id = auth()->id();
                $product->is_vendor_product = true;
                $product->approval_status = 'pending';
            }
        });
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    
}
