<?php
// app/Models/Review.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'user_id',
        'order_id',
        'rating',
        'title',
        'review',
        'images',
        'is_verified_purchase',
        'is_approved',
        'approved_at',
        'approved_by',
    ];

    protected $casts = [
        'images' => 'array',
        'is_verified_purchase' => 'boolean',
        'is_approved' => 'boolean',
        'approved_at' => 'datetime',
        'rating' => 'integer',
    ];

    // ===== RELATIONSHIPS =====

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    // ===== SCOPES =====

    public function scopeApproved($query)
    {
        return $query->where('is_approved', true);
    }

    public function scopeVerifiedPurchase($query)
    {
        return $query->where('is_verified_purchase', true);
    }

    public function scopeByRating($query, $rating)
    {
        return $query->where('rating', $rating);
    }

    public function scopeHighRated($query)
    {
        return $query->where('rating', '>=', 4);
    }

    public function scopeForProduct($query, $productId)
    {
        return $query->where('product_id', $productId);
    }

    // ===== ACCESSORS =====

    public function getStarRatingAttribute()
    {
        return str_repeat('★', $this->rating) . str_repeat('☆', 5 - $this->rating);
    }

    public function getRatingTextAttribute()
    {
        $ratings = [
            1 => 'Very Poor',
            2 => 'Poor',
            3 => 'Average',
            4 => 'Good',
            5 => 'Excellent'
        ];

        return $ratings[$this->rating] ?? 'No Rating';
    }

    public function getImageUrlsAttribute()
    {
        if (!$this->images) {
            return [];
        }

        return collect($this->images)->map(function ($image) {
            return asset('storage/' . $image);
        })->toArray();
    }

    // ===== HELPER METHODS =====

    public function isHighRated(): bool
    {
        return $this->rating >= 4;
    }

    public function hasImages(): bool
    {
        return !empty($this->images);
    }
}
