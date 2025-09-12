<?php
// app/Models/VendorDocument.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VendorDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'vendor_id',
        'document_type',
        'document_name',
        'file_path',
        'file_type',
        'file_size',
        'verification_status',
        'verified_at',
        'verified_by',
        'rejection_reason',
        'expiry_date',
        'notes',
    ];

    protected $casts = [
        'verified_at' => 'datetime',
        'expiry_date' => 'date',
    ];

    // ===== RELATIONSHIPS =====

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'vendor_id');
    }

    public function verifiedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    // ===== SCOPES =====

    public function scopeVerified($query)
    {
        return $query->where('verification_status', 'verified');
    }

    public function scopePending($query)
    {
        return $query->where('verification_status', 'pending');
    }

    // ===== ACCESSORS =====

    public function getFileUrlAttribute()
    {
        return asset('storage/' . $this->file_path);
    }

    public function getFormattedSizeAttribute()
    {
        $bytes = $this->file_size;
        if ($bytes >= 1073741824) {
            return number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        } else {
            return $bytes . ' bytes';
        }
    }
}
