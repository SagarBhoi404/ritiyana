<?php

// app/Models/Banner.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'image_path',
        'alt_text',
        'link_url',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    // Scope for active banners
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Scope for ordered banners
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order', 'asc')->orderBy('created_at', 'desc');
    }

    // Get full image URL
    public function getImageUrlAttribute()
    {
        if (filter_var($this->image_path, FILTER_VALIDATE_URL)) {
            return $this->image_path;
        }

        // Check if running in production environment
        if (app()->environment('production')) {
            return url('storage/app/public/'.$this->image_path);
        } else {
            return asset('storage/'.$this->image_path);
        }
    }

    // Check if banner has external link
    public function hasLink()
    {
        return ! empty($this->link_url);
    }
}
