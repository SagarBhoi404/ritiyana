<?php
// app/Models/VendorPayout.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VendorPayout extends Model
{
    use HasFactory;

    protected $fillable = [
        'payout_id',
        'vendor_id',
        'amount',
        'payout_method',
        'period_start',
        'period_end',
        'total_orders',
        'total_sales',
        'total_commission',
        'status',
        'processed_at',
        'transaction_id',
        'failure_reason',
        'bank_details',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'total_sales' => 'decimal:2',
        'total_commission' => 'decimal:2',
        'period_start' => 'date',
        'period_end' => 'date',
        'processed_at' => 'datetime',
        'bank_details' => 'array',
    ];

    // ===== RELATIONSHIPS =====

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'vendor_id');
    }

    // ===== SCOPES =====

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    // ===== ACCESSORS =====

    public function getFormattedAmountAttribute()
    {
        return '₹' . number_format($this->amount, 2);
    }

    public function getPeriodTextAttribute()
    {
        return $this->period_start->format('M j') . ' - ' . $this->period_end->format('M j, Y');
    }
}
