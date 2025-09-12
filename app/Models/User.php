<?php
// app/Models/User.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
// use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable
{
    use  HasFactory, Notifiable;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'date_of_birth',
        'gender',
        'password',
        'avatar',
        'status',
        'profile_image',
        'last_login_at',
        'email_verified_at',
        'notes',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'date_of_birth' => 'date',
        'last_login_at' => 'datetime',
        'password' => 'hashed',
    ];

    // Relationships
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'user_roles');
    }

    public function addresses()
    {
        return $this->hasMany(Address::class);
    }

    public function defaultAddress()
    {
        return $this->hasOne(Address::class)->where('is_default', true);
    }

    public function billingAddresses()
    {
        return $this->hasMany(Address::class)->whereIn('type', ['billing', 'both']);
    }

    public function shippingAddresses()
    {
        return $this->hasMany(Address::class)->whereIn('type', ['shipping', 'both']);
    }

    // Helper methods
    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function hasRole($role)
    {
        if (is_string($role)) {
            return $this->roles->contains('name', $role);
        }
        return $this->roles->contains('id', $role);
    }

    public function hasAnyRole($roles)
    {
        return $this->roles->whereIn('name', $roles)->count() > 0;
    }

    public function assignRole($role)
    {
        if (is_string($role)) {
            $role = Role::where('name', $role)->first();
        }
        
        if ($role && !$this->hasRole($role->id)) {
            $this->roles()->attach($role->id);
        }
        
        return $this;
    }

    public function removeRole($role)
    {
        if (is_string($role)) {
            $role = Role::where('name', $role)->first();
        }
        
        if ($role) {
            $this->roles()->detach($role->id);
        }
        
        return $this;
    }

    public function isAdmin()
    {
        return $this->hasRole('admin');
    }

    public function isShopkeeper()
    {
        return $this->hasRole('shopkeeper');
    }

    public function isCustomer()
    {
        return $this->hasRole('user');
    }

    public function isActive()
    {
        return $this->status === 'active';
    }

     // Accessor for profile image URL
    public function getProfileImageUrlAttribute()
    {
        if ($this->profile_image) {
            return asset('storage/' . $this->profile_image);
        }
        
        // Return default avatar if no image
        return asset('images/default-avatar.png');
    }


     // ===== VENDOR RELATIONSHIPS =====

    // User has one vendor profile
    public function vendorProfile(): HasOne
    {
        return $this->hasOne(Vendor::class);
    }

    // User has many products (as vendor)
    public function vendorProducts(): HasMany
    {
        return $this->hasMany(Product::class, 'vendor_id');
    }

    // User has many vendor orders
    public function vendorOrders(): HasMany
    {
        return $this->hasMany(VendorOrder::class, 'vendor_id');
    }

    // User has many vendor payouts
    public function vendorPayouts(): HasMany
    {
        return $this->hasMany(VendorPayout::class, 'vendor_id');
    }

    // User has many vendor documents
    public function vendorDocuments(): HasMany
    {
        return $this->hasMany(VendorDocument::class, 'vendor_id');
    }

    // User has many vendor analytics
    public function vendorAnalytics(): HasMany
    {
        return $this->hasMany(VendorAnalytics::class, 'vendor_id');
    }


    // ===== HELPER METHODS =====

    public function isVendor(): bool
    {
        return $this->roles()->where('name', 'shopkeeper')->exists();
    }

 
}
