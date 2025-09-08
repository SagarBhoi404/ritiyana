<?php
// app/Models/Address.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
        'title',
        'first_name',
        'last_name',
        'company',
        'address_line_1',
        'address_line_2',
        'city',
        'state',
        'postal_code',
        'country',
        'phone',
        'is_default',
    ];

    protected $casts = [
        'is_default' => 'boolean',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Scopes
    public function scopeDefault($query)
    {
        return $query->where('is_default', true);
    }

    public function scopeBilling($query)
    {
        return $query->whereIn('type', ['billing', 'both']);
    }

    public function scopeShipping($query)
    {
        return $query->whereIn('type', ['shipping', 'both']);
    }

    // Helper methods
    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function getFormattedAddressAttribute()
    {
        $address = $this->address_line_1;
        
        if ($this->address_line_2) {
            $address .= ', ' . $this->address_line_2;
        }
        
        $address .= ', ' . $this->city . ', ' . $this->state . ' - ' . $this->postal_code;
        
        if ($this->country !== 'India') {
            $address .= ', ' . $this->country;
        }
        
        return $address;
    }

    // Events
    protected static function boot()
    {
        parent::boot();

        // Ensure only one default address per user per type
        static::saving(function ($address) {
            if ($address->is_default) {
                static::where('user_id', $address->user_id)
                    ->where('type', $address->type)
                    ->where('id', '!=', $address->id)
                    ->update(['is_default' => false]);
            }
        });
    }
}
