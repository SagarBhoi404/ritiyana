<?php
// app/Models/PujaKit.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PujaKit extends Model
{
    use HasFactory;

    protected $fillable = [
        'kit_description',
        'included_items',
        'discount_percentage',
        'is_active',
    ];

    protected $casts = [
        'included_items' => 'array',
        'discount_percentage' => 'float',
        'is_active' => 'boolean',
    ];

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

        $discountAmount = $totalPrice * ($this->discount_percentage / 100);
        return $totalPrice - $discountAmount;
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
}
