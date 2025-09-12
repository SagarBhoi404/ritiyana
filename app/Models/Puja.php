<?php
// app/Models/Puja.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Puja extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'significance',
        'procedure',
        'image',
        'auspicious_days',
        'required_items',
        'vendor_id', 
        'is_active'
    ];

    protected $casts = [
        'auspicious_days' => 'array',  // This ensures JSON is cast to array
        'required_items' => 'array',   // This ensures JSON is cast to array
        'is_active' => 'boolean',
    ];

    // Change from hasMany to belongsToMany for many-to-many relationship
    public function pujaKits()
    {
        return $this->belongsToMany(PujaKit::class, 'puja_kit_puja')
            ->withTimestamps();
    }

     public function kits()
    {
        return $this->belongsToMany(PujaKit::class, 'puja_puja_kit', 'puja_id', 'puja_kit_id');
    }

    // Mutators
    public function setNameAttribute($value)
    {
        $this->attributes['name'] = $value;
        $this->attributes['slug'] = Str::slug($value);
    }

    // Accessors
    public function getImageUrlAttribute()
    {
        return $this->image ? asset('storage/' . $this->image) : asset('images/default-puja.png');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }


    public function vendor()
    {
        return $this->belongsTo(User::class, 'vendor_id');
    }
}
