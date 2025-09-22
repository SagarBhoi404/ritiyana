<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class PujaKit extends Model
{
    use HasFactory;

    protected $fillable = [
        'kit_name',
        'slug',
        'description',
        'image',
        'vendor_id',
        'discount_percentage',
        'is_active',
        'meta_title',
        'meta_description',
        'meta_keywords'
    ];

    protected $casts = [
        'meta_keywords' => 'array',
        'discount_percentage' => 'float',
        'is_active' => 'boolean',
    ];

    // Automatically generate slug when kit_name is set
    public function setKitNameAttribute($value)
    {
        $this->attributes['kit_name'] = $value;
        if (empty($this->attributes['slug'])) {
            $this->attributes['slug'] = Str::slug($value);
        }
    }

    // Use slug as the route key
    public function getRouteKeyName()
    {
        return 'slug';
    }

    // Many-to-many relationship with pujas
    public function pujas()
    {
        return $this->belongsToMany(Puja::class, 'puja_kit_puja')
            ->withTimestamps();
    }

    // Many-to-many relationship with products
    public function products()
    {
        return $this->belongsToMany(Product::class, 'puja_kit_product')
            ->withPivot('quantity', 'price')
            ->withTimestamps();
    }

    // Calculate total price of all products in kit
    public function getTotalPriceAttribute()
    {
        $totalPrice = $this->products->sum(function ($product) {
            $price = $product->pivot->price ?? $product->price;
            return $price * $product->pivot->quantity;
        });

        return $totalPrice;
    }

    // Calculate final price with discount applied
    public function getFinalPriceAttribute()
    {
        $totalPrice = $this->total_price;
        
        if ($this->discount_percentage && $this->discount_percentage > 0) {
            $discountAmount = $totalPrice * ($this->discount_percentage / 100);
            return $totalPrice - $discountAmount;
        }

        return $totalPrice;
    }

    // Calculate discount amount
    public function getDiscountAmountAttribute()
    {
        if ($this->discount_percentage && $this->discount_percentage > 0) {
            return $this->total_price * ($this->discount_percentage / 100);
        }
        return 0;
    }

    // Get formatted final price
    public function getFormattedFinalPriceAttribute()
    {
        return '₹' . number_format($this->final_price, 2);
    }

    // Get formatted total price
    public function getFormattedTotalPriceAttribute()
    {
        return '₹' . number_format($this->total_price, 2);
    }

    // Get all puja names as string
    public function getPujaNamesAttribute()
    {
        return $this->pujas->pluck('name')->implode(', ');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Get first product name safely
    public function getFirstProductNameAttribute()
    {
        return optional($this->products->first())->name ?? 'No Product';
    }

    public function vendor()
    {
        return $this->belongsTo(User::class, 'vendor_id');
    }

    // Get image URL with fallback
    public function getImageUrlAttribute()
    {
        if ($this->image) {
            return asset('storage/' . $this->image);
        }
        
        return asset('images/default-puja-kit.jpg'); // Add a default image
    }

    // Check if kit has discount
    public function hasDiscount()
    {
        return $this->discount_percentage && $this->discount_percentage > 0;
    }

    
}
