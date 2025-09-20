<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'session_id',
        'product_id',
        'puja_kit_id',
        'item_type',
        'item_name',
        'item_image',
        'quantity',
        'price',
        'product_options',
    ];

    protected $casts = [
        'product_options' => 'array',
        'price' => 'decimal:2',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function pujaKit()
    {
        return $this->belongsTo(PujaKit::class, 'puja_kit_id');
    }

    // Scopes
    public function scopeForCurrentUser($query)
    {
        if (Auth::check()) {
            return $query->where('user_id', Auth::id());
        } else {
            return $query->where('session_id', Session::getId());
        }
    }

    public function scopeWithItems($query)
    {
        return $query->where(function ($q) {
            $q->where(function ($subQuery) {
                // Product items
                $subQuery->where('item_type', 'product')
                    ->whereHas('product');
            })->orWhere(function ($subQuery) {
                // Puja kit items
                $subQuery->where('item_type', 'puja_kit')
                    ->whereHas('pujaKit');
            });
        });
    }

    // Keep backward compatibility
    public function scopeWithProducts($query)
    {
        return $this->scopeWithItems($query);
    }

    // Accessors
    public function getSubtotalAttribute()
    {
        try {
            $price = $this->price ?? 0;
            $quantity = $this->quantity ?? 1;

            return $price * $quantity;
        } catch (\Exception $e) {
            \Log::error('Error calculating subtotal for cart item '.$this->id.': '.$e->getMessage());

            return 0;
        }
    }

    public function getFormattedPriceAttribute()
    {
        try {
            return '₹'.number_format($this->price ?? 0, 2);
        } catch (\Exception $e) {
            \Log::error('Error formatting price for cart item '.$this->id.': '.$e->getMessage());

            return '₹0.00';
        }
    }

    public function getFormattedSubtotalAttribute()
    {
        try {
            return '₹'.number_format($this->subtotal ?? 0, 2);
        } catch (\Exception $e) {
            \Log::error('Error formatting subtotal for cart item '.$this->id.': '.$e->getMessage());

            return '₹0.00';
        }
    }

    public function getDisplayNameAttribute()
    {
        try {
            if ($this->item_type === 'puja_kit') {
                return $this->item_name ?: ($this->pujaKit?->kit_name ?? 'Unknown Puja Kit');
            }

            return $this->product?->name ?? 'Unknown Product';
        } catch (\Exception $e) {
            \Log::error('Error getting display name for cart item '.$this->id.': '.$e->getMessage());

            return 'Unknown Item';
        }
    }

    public function getDisplayImageAttribute()
    {
        try {
            if ($this->item_type === 'puja_kit') {
                if ($this->item_image) {
                    // Check if it's a full URL or relative path
                    if (filter_var($this->item_image, FILTER_VALIDATE_URL)) {
                        return $this->item_image;
                    }

                    return asset('storage/'.$this->item_image);
                }

                return $this->pujaKit?->image ? asset('storage/'.$this->pujaKit->image) : '/images/placeholder.jpg';
            }

            if ($this->product && $this->product->featured_image) {
                if (filter_var($this->product->featured_image, FILTER_VALIDATE_URL)) {
                    return $this->product->featured_image;
                }

                return asset('storage/'.$this->product->featured_image);
            }

            return '/images/placeholder.jpg';
        } catch (\Exception $e) {
            \Log::error('Error getting display image for cart item '.$this->id.': '.$e->getMessage());

            return '/images/placeholder.jpg';
        }
    }

    // Static methods for cart operations
   public static function getCartItems()
{
    try {
        return self::forCurrentUser()
            ->withItems()
            ->with(['product' => function($query) {
                $query->select('id', 'name', 'slug', 'featured_image', 'stock_quantity', 'price');
            }, 'pujaKit' => function($query) {
                $query->select('id', 'kit_name', 'slug', 'image');
            }])
            ->orderBy('created_at', 'desc')
            ->get();
    } catch (\Exception $e) {
        \Log::error('Error getting cart items: ' . $e->getMessage());
        return collect([]); // Return empty collection on error
    }
}
    public static function getCartCount()
{
    try {
        return self::forCurrentUser()->withItems()->sum('quantity') ?? 0;
    } catch (\Exception $e) {
        \Log::error('Error getting cart count: ' . $e->getMessage());
        return 0;
    }
}

    public static function getCartTotal()
{
    try {
        return self::forCurrentUser()
            ->withItems()
            ->get()
            ->sum('subtotal') ?? 0;
    } catch (\Exception $e) {
        \Log::error('Error getting cart total: ' . $e->getMessage());
        return 0;
    }
}

    public static function addToCart($productId, $quantity = 1, $options = null)
    {
        $product = Product::findOrFail($productId);

        // Check stock
        if (isset($product->stock_quantity) && $product->stock_quantity < $quantity) {
            return ['success' => false, 'message' => 'Insufficient stock available'];
        }

        $cartData = [
            'product_id' => $productId,
            'puja_kit_id' => null, // Explicitly set to null for products
            'item_type' => 'product',
            'item_name' => null, // Will use product relationship
            'item_image' => null, // Will use product relationship
            'quantity' => $quantity,
            'price' => $product->final_price,
            'product_options' => $options,
        ];

        // Set user_id or session_id based on authentication
        if (Auth::check()) {
            $cartData['user_id'] = Auth::id();
            $cartData['session_id'] = null;
        } else {
            $cartData['user_id'] = null;
            $cartData['session_id'] = Session::getId();
        }

        // Check if item already exists in cart
        $existingItem = self::where('product_id', $productId)
            ->where('item_type', 'product')
            ->when(Auth::check(), function ($query) {
                return $query->where('user_id', Auth::id());
            }, function ($query) {
                return $query->where('session_id', Session::getId());
            })
            ->when($options, function ($query) use ($options) {
                return $query->where('product_options', json_encode($options));
            })
            ->first();

        if ($existingItem) {
            $newQuantity = $existingItem->quantity + $quantity;
            if (isset($product->stock_quantity) && $product->stock_quantity < $newQuantity) {
                return ['success' => false, 'message' => 'Cannot add more items. Stock limit exceeded'];
            }

            $existingItem->update(['quantity' => $newQuantity]);

            return ['success' => true, 'message' => 'Cart updated successfully', 'item' => $existingItem];
        } else {
            $cartItem = self::create($cartData);

            return ['success' => true, 'message' => 'Item added to cart successfully', 'item' => $cartItem];
        }
    }

    public static function addPujaKitToCart($pujaKitId, $quantity = 1)
    {
        $pujaKit = PujaKit::with(['products'])->findOrFail($pujaKitId);

        // Debug: Log the price to see what's happening
        \Log::info('PujaKit ID: '.$pujaKitId);
        \Log::info('PujaKit final_price: '.$pujaKit->final_price);
        \Log::info('PujaKit total_price: '.$pujaKit->total_price);
        \Log::info('Products count: '.$pujaKit->products->count());

        // Check if final price is valid
        $finalPrice = $pujaKit->final_price;
        if ($finalPrice === null || $finalPrice <= 0) {
            // Calculate manually if needed
            $totalPrice = 0;
            foreach ($pujaKit->products as $product) {
                $price = $product->pivot->price ?? $product->price ?? 0;
                $totalPrice += $price * $product->pivot->quantity;
            }

            if ($pujaKit->discount_percentage && $pujaKit->discount_percentage > 0) {
                $discountAmount = $totalPrice * ($pujaKit->discount_percentage / 100);
                $finalPrice = $totalPrice - $discountAmount;
            } else {
                $finalPrice = $totalPrice;
            }

            \Log::info('Calculated final_price: '.$finalPrice);
        }

        // Ensure we have a valid price
        if ($finalPrice === null || $finalPrice <= 0) {
            return [
                'success' => false,
                'message' => 'Unable to calculate price for this puja kit. Please contact support.',
            ];
        }

        // Check if all products in kit have sufficient stock
        foreach ($pujaKit->products as $product) {
            $requiredQuantity = $product->pivot->quantity * $quantity;
            if (isset($product->stock_quantity) && $product->stock_quantity < $requiredQuantity) {
                return [
                    'success' => false,
                    'message' => "Insufficient stock for {$product->name} in this kit",
                ];
            }
        }

        $cartData = [
            'product_id' => null, // Explicitly set to null for puja kits
            'puja_kit_id' => $pujaKitId,
            'item_type' => 'puja_kit',
            'item_name' => $pujaKit->kit_name,
            'item_image' => $pujaKit->image,
            'quantity' => $quantity,
            'price' => $finalPrice, // Use the calculated/validated price
            'product_options' => null,
        ];

        // Set user_id or session_id based on authentication
        if (Auth::check()) {
            $cartData['user_id'] = Auth::id();
            $cartData['session_id'] = null;
        } else {
            $cartData['user_id'] = null;
            $cartData['session_id'] = Session::getId();
        }

        // Check if kit already exists in cart
        $existingItem = self::where('puja_kit_id', $pujaKitId)
            ->where('item_type', 'puja_kit')
            ->when(Auth::check(), function ($query) {
                return $query->where('user_id', Auth::id());
            }, function ($query) {
                return $query->where('session_id', Session::getId());
            })
            ->first();

        if ($existingItem) {
            $newQuantity = $existingItem->quantity + $quantity;

            // Check stock for new quantity
            foreach ($pujaKit->products as $product) {
                $requiredQuantity = $product->pivot->quantity * $newQuantity;
                if (isset($product->stock_quantity) && $product->stock_quantity < $requiredQuantity) {
                    return [
                        'success' => false,
                        'message' => "Cannot add more kits. Stock limit exceeded for {$product->name}",
                    ];
                }
            }

            $existingItem->update(['quantity' => $newQuantity]);

            return ['success' => true, 'message' => 'Puja kit quantity updated successfully', 'item' => $existingItem];
        } else {
            $cartItem = self::create($cartData);

            return ['success' => true, 'message' => 'Puja kit added to cart successfully', 'item' => $cartItem];
        }
    }

    public static function updateQuantity($cartId, $quantity)
    {
        $cartItem = self::forCurrentUser()->findOrFail($cartId);

        if ($quantity <= 0) {
            $cartItem->delete();

            return ['success' => true, 'message' => 'Item removed from cart'];
        }

        // Check stock based on item type
        if ($cartItem->item_type === 'product') {
            if (isset($cartItem->product->stock_quantity) && $cartItem->product->stock_quantity < $quantity) {
                return ['success' => false, 'message' => 'Insufficient stock available'];
            }
        } elseif ($cartItem->item_type === 'puja_kit') {
            $pujaKit = $cartItem->pujaKit;
            if ($pujaKit) {
                foreach ($pujaKit->products as $product) {
                    $requiredQuantity = $product->pivot->quantity * $quantity;
                    if (isset($product->stock_quantity) && $product->stock_quantity < $requiredQuantity) {
                        return [
                            'success' => false,
                            'message' => "Insufficient stock for {$product->name} in this kit",
                        ];
                    }
                }
            }
        }

        $cartItem->update(['quantity' => $quantity]);

        return ['success' => true, 'message' => 'Cart updated successfully'];
    }

    public static function removeItem($cartId)
    {
        $cartItem = self::forCurrentUser()->findOrFail($cartId);
        $cartItem->delete();

        return ['success' => true, 'message' => 'Item removed from cart'];
    }

    public static function clearCart()
    {
        self::forCurrentUser()->delete();

        return ['success' => true, 'message' => 'Cart cleared successfully'];
    }

    // Merge guest cart with user cart when user logs in
    public static function mergeGuestCart($sessionId)
    {
        if (Auth::check()) {
            $guestItems = self::where('session_id', $sessionId)->get();

            foreach ($guestItems as $guestItem) {
                $existingItem = self::where('user_id', Auth::id())
                    ->where('item_type', $guestItem->item_type)
                    ->when($guestItem->item_type === 'product', function ($query) use ($guestItem) {
                        return $query->where('product_id', $guestItem->product_id)
                            ->where('product_options', json_encode($guestItem->product_options));
                    })
                    ->when($guestItem->item_type === 'puja_kit', function ($query) use ($guestItem) {
                        return $query->where('puja_kit_id', $guestItem->puja_kit_id);
                    })
                    ->first();

                if ($existingItem) {
                    $existingItem->increment('quantity', $guestItem->quantity);
                } else {
                    $guestItem->update([
                        'user_id' => Auth::id(),
                        'session_id' => null,
                    ]);
                }
            }

            // Clean up any remaining guest items
            self::where('session_id', $sessionId)->delete();
        }
    }
}
