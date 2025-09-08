<?php
// app/Models/User.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
// use Laravel\Sanctum\HasApiTokens;

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

}
