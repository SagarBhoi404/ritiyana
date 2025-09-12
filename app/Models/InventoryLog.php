<?php
// app/Models/InventoryLog.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InventoryLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'type',
        'quantity_changed',
        'quantity_before',
        'quantity_after',
        'reference_type',
        'reference_id',
        'notes',
        'created_by',
    ];

    protected $casts = [
        'quantity_changed' => 'integer',
        'quantity_before' => 'integer',
        'quantity_after' => 'integer',
        'reference_id' => 'integer',
    ];

    // ===== RELATIONSHIPS =====

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Polymorphic relationship for reference
    public function reference()
    {
        return $this->morphTo();
    }

    // ===== SCOPES =====

    public function scopeForProduct($query, $productId)
    {
        return $query->where('product_id', $productId);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeIncreases($query)
    {
        return $query->where('quantity_changed', '>', 0);
    }

    public function scopeDecreases($query)
    {
        return $query->where('quantity_changed', '<', 0);
    }

    public function scopeRecentLogs($query, $days = 30)
    {
        return $query->where('created_at', '>=', now()->subDays($days));
    }

    // ===== ACCESSORS =====

    public function getTypeTextAttribute()
    {
        $types = [
            'purchase' => 'Stock Purchase',
            'sale' => 'Product Sale',
            'return' => 'Customer Return',
            'adjustment' => 'Stock Adjustment',
            'damage' => 'Damaged Stock'
        ];

        return $types[$this->type] ?? ucfirst($this->type);
    }

    public function getQuantityChangeTextAttribute()
    {
        $change = $this->quantity_changed;
        if ($change > 0) {
            return "+{$change} (Added)";
        } elseif ($change < 0) {
            return "{$change} (Removed)";
        }
        return "0 (No Change)";
    }

    public function getFormattedNotesAttribute()
    {
        return $this->notes ?: 'No additional notes';
    }

    // ===== HELPER METHODS =====

    public function isIncrease(): bool
    {
        return $this->quantity_changed > 0;
    }

    public function isDecrease(): bool
    {
        return $this->quantity_changed < 0;
    }

    public function getAbsoluteChange(): int
    {
        return abs($this->quantity_changed);
    }
}
