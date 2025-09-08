<?php
// app/Models/Product.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
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
    ];

    protected $casts = [
        'gallery_images' => 'array',
        'attributes' => 'array',
        'manage_stock' => 'boolean',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
    ];

    // Relationships
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'product_categories');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Scopes
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

    // Mutators
    public function setNameAttribute($value)
    {
        $this->attributes['name'] = $value;
        $this->attributes['slug'] = Str::slug($value);
    }

    // Accessors
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

    public function pujaKits()
    {
        return $this->belongsToMany(PujaKit::class, 'puja_kit_product')
            ->withPivot('quantity', 'price')
            ->withTimestamps();
    }
}
