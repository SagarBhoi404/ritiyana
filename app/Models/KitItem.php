<?php
// app/Models/KitItem.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KitItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'kit_id',
        'product_id',
        'quantity',
        'price',
    ];

    // Relationships
    public function kit()
    {
        return $this->belongsTo(PujaKit::class, 'kit_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Accessors
    public function getTotalPriceAttribute()
    {
        return $this->quantity * ($this->price ?: $this->product->price);
    }
}
