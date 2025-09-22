<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

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
        'quantity' => 'integer',
    ];

    // ===== RELATIONSHIPS =====
    
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

    // ===== SCOPES =====
    
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

    // ===== ACCESSORS =====
    
    public function getSubtotalAttribute()
    {
        try {
            $price = $this->getActualPrice();
            $quantity = $this->quantity ?? 1;
            
            Log::info('Cart subtotal calculation', [
                'cart_id' => $this->id,
                'item_type' => $this->item_type,
                'stored_price' => $this->price,
                'actual_price' => $price,
                'quantity' => $quantity,
                'subtotal' => $price * $quantity
            ]);
            
            return $price * $quantity;
        } catch (\Exception $e) {
            Log::error('Error calculating subtotal for cart item ' . $this->id . ': ' . $e->getMessage());
            return 0;
        }
    }

    /**
     * Get the actual price for this cart item
     */
    public function getActualPrice()
    {
        try {
            if ($this->item_type === 'puja_kit' && $this->pujaKit) {
                // For puja kits, get final price
                $price = $this->pujaKit->final_price ?? $this->pujaKit->total_price ?? 0;
                Log::info('PujaKit price retrieved', [
                    'kit_id' => $this->puja_kit_id,
                    'final_price' => $this->pujaKit->final_price,
                    'total_price' => $this->pujaKit->total_price,
                    'used_price' => $price
                ]);
                return $price;
            } elseif ($this->item_type === 'product' && $this->product) {
                // For products, get final price (sale price or regular price)
                $price = $this->product->final_price ?? $this->product->price ?? 0;
                Log::info('Product price retrieved', [
                    'product_id' => $this->product_id,
                    'final_price' => $this->product->final_price,
                    'price' => $this->product->price,
                    'used_price' => $price
                ]);
                return $price;
            } else {
                // Fallback to stored price
                $price = $this->price ?? 0;
                Log::info('Using stored price', [
                    'cart_id' => $this->id,
                    'stored_price' => $price
                ]);
                return $price;
            }
        } catch (\Exception $e) {
            Log::error('Error getting actual price for cart item ' . $this->id . ': ' . $e->getMessage());
            return $this->price ?? 0;
        }
    }

    public function getFormattedPriceAttribute()
    {
        try {
            return '₹' . number_format($this->getActualPrice(), 2);
        } catch (\Exception $e) {
            Log::error('Error formatting price for cart item ' . $this->id . ': ' . $e->getMessage());
            return '₹0.00';
        }
    }

    public function getFormattedSubtotalAttribute()
    {
        try {
            return '₹' . number_format($this->subtotal, 2);
        } catch (\Exception $e) {
            Log::error('Error formatting subtotal for cart item ' . $this->id . ': ' . $e->getMessage());
            return '₹0.00';
        }
    }

    public function getDisplayNameAttribute()
    {
        try {
            if ($this->item_type === 'puja_kit') {
                return $this->item_name ?? $this->pujaKit?->kit_name ?? 'Unknown Puja Kit';
            }
            return $this->product?->name ?? $this->item_name ?? 'Unknown Product';
        } catch (\Exception $e) {
            Log::error('Error getting display name for cart item ' . $this->id . ': ' . $e->getMessage());
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
                    return asset('storage/' . $this->item_image);
                }
                return $this->pujaKit?->image ? asset('storage/' . $this->pujaKit->image) : asset('images/placeholder.jpg');
            }

            if ($this->product) {
                if ($this->product->featured_image) {
                    if (filter_var($this->product->featured_image, FILTER_VALIDATE_URL)) {
                        return $this->product->featured_image;
                    }
                    return asset('storage/' . $this->product->featured_image);
                }
            }
            return asset('images/placeholder.jpg');
        } catch (\Exception $e) {
            Log::error('Error getting display image for cart item ' . $this->id . ': ' . $e->getMessage());
            return asset('images/placeholder.jpg');
        }
    }

    // ===== STATIC METHODS FOR CART OPERATIONS =====
    
    public static function getCartItems()
    {
        try {
            return self::forCurrentUser()
                ->withItems()
                ->with([
                    'product' => function ($query) {
                        $query->select('id', 'name', 'slug', 'featured_image', 'stock_quantity', 'price', 'sale_price');
                    },
                    'pujaKit' => function ($query) {
                        $query->select('id', 'kit_name', 'slug', 'image');
                    }
                ])
                ->orderBy('created_at', 'desc')
                ->get();
        } catch (\Exception $e) {
            Log::error('Error getting cart items: ' . $e->getMessage());
            return collect(); // Return empty collection on error
        }
    }

    public static function getCartCount()
    {
        try {
            return self::forCurrentUser()->withItems()->sum('quantity') ?? 0;
        } catch (\Exception $e) {
            Log::error('Error getting cart count: ' . $e->getMessage());
            return 0;
        }
    }

    public static function getCartTotal()
    {
        try {
            $cartItems = self::getCartItems();
            $total = 0;
            
            Log::info('Calculating cart total', ['items_count' => $cartItems->count()]);
            
            foreach ($cartItems as $item) {
                $itemSubtotal = $item->subtotal;
                $total += $itemSubtotal;
                
                Log::info('Cart item calculation', [
                    'item_id' => $item->id,
                    'item_type' => $item->item_type,
                    'quantity' => $item->quantity,
                    'price' => $item->getActualPrice(),
                    'subtotal' => $itemSubtotal,
                    'running_total' => $total
                ]);
            }
            
            Log::info('Final cart total', ['total' => $total]);
            return $total;
        } catch (\Exception $e) {
            Log::error('Error getting cart total: ' . $e->getMessage());
            return 0;
        }
    }

    public static function addPujaKitToCart($pujaKitId, $quantity = 1)
    {
        try {
            $pujaKit = PujaKit::with('products')->findOrFail($pujaKitId);
            
            // Calculate final price correctly
            $finalPrice = $pujaKit->final_price ?? $pujaKit->total_price ?? 0;
            
            if ($finalPrice <= 0) {
                // Calculate manually if needed
                $totalPrice = 0;
                foreach ($pujaKit->products as $product) {
                    $price = $product->pivot->price ?? $product->price ?? 0;
                    $totalPrice += $price * $product->pivot->quantity;
                }
                
                if ($pujaKit->discount_percentage && $pujaKit->discount_percentage > 0) {
                    $discountAmount = ($totalPrice * $pujaKit->discount_percentage) / 100;
                    $finalPrice = $totalPrice - $discountAmount;
                } else {
                    $finalPrice = $totalPrice;
                }
            }
            
            Log::info('Adding PujaKit to cart', [
                'kit_id' => $pujaKitId,
                'calculated_price' => $finalPrice,
                'quantity' => $quantity
            ]);

            // Ensure we have a valid price
            if ($finalPrice <= 0) {
                return [
                    'success' => false,
                    'message' => 'Unable to calculate price for this puja kit. Please contact support.',
                ];
            }

            // Check stock for all products in kit
            foreach ($pujaKit->products as $product) {
                $requiredQuantity = $product->pivot->quantity * $quantity;
                if (isset($product->stock_quantity) && $product->stock_quantity < $requiredQuantity) {
                    return [
                        'success' => false,
                        'message' => 'Insufficient stock for ' . $product->name . ' in this kit.',
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
                            'message' => 'Cannot add more kits. Stock limit exceeded for ' . $product->name . '.',
                        ];
                    }
                }

                $existingItem->update([
                    'quantity' => $newQuantity,
                    'price' => $finalPrice // Update price in case it changed
                ]);
                
                return [
                    'success' => true,
                    'message' => 'Puja kit quantity updated successfully',
                    'item' => $existingItem,
                ];
            } else {
                $cartItem = self::create($cartData);
                
                return [
                    'success' => true,
                    'message' => 'Puja kit added to cart successfully',
                    'item' => $cartItem,
                ];
            }
        } catch (\Exception $e) {
            Log::error('Error adding puja kit to cart: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Failed to add puja kit to cart. Please try again.',
            ];
        }
    }

    public static function addToCart($productId, $quantity = 1, $options = null)
    {
        try {
            $product = Product::findOrFail($productId);

            // Check stock
            if (isset($product->stock_quantity) && $product->stock_quantity < $quantity) {
                return [
                    'success' => false,
                    'message' => 'Insufficient stock available',
                ];
            }

            $finalPrice = $product->final_price ?? $product->price ?? 0;
            
            if ($finalPrice <= 0) {
                return [
                    'success' => false,
                    'message' => 'Invalid product price',
                ];
            }

            Log::info('Adding Product to cart', [
                'product_id' => $productId,
                'price' => $finalPrice,
                'quantity' => $quantity
            ]);

            $cartData = [
                'product_id' => $productId,
                'puja_kit_id' => null, // Explicitly set to null for products
                'item_type' => 'product',
                'item_name' => $product->name,
                'item_image' => $product->featured_image,
                'quantity' => $quantity,
                'price' => $finalPrice,
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
                    return [
                        'success' => false,
                        'message' => 'Cannot add more items. Stock limit exceeded.',
                    ];
                }

                $existingItem->update([
                    'quantity' => $newQuantity,
                    'price' => $finalPrice // Update price in case it changed
                ]);

                return [
                    'success' => true,
                    'message' => 'Cart updated successfully',
                    'item' => $existingItem,
                ];
            } else {
                $cartItem = self::create($cartData);

                return [
                    'success' => true,
                    'message' => 'Item added to cart successfully',
                    'item' => $cartItem,
                ];
            }
        } catch (\Exception $e) {
            Log::error('Error adding product to cart: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Failed to add item to cart. Please try again.',
            ];
        }
    }

    public static function updateQuantity($cartId, $quantity)
    {
        try {
            $cartItem = self::forCurrentUser()->findOrFail($cartId);

            if ($quantity <= 0) {
                $cartItem->delete();
                return [
                    'success' => true,
                    'message' => 'Item removed from cart',
                ];
            }

            // Check stock based on item type
            if ($cartItem->item_type === 'product') {
                if (isset($cartItem->product->stock_quantity) && $cartItem->product->stock_quantity < $quantity) {
                    return [
                        'success' => false,
                        'message' => 'Insufficient stock available',
                    ];
                }
            } elseif ($cartItem->item_type === 'puja_kit') {
                $pujaKit = $cartItem->pujaKit;
                if ($pujaKit) {
                    foreach ($pujaKit->products as $product) {
                        $requiredQuantity = $product->pivot->quantity * $quantity;
                        if (isset($product->stock_quantity) && $product->stock_quantity < $requiredQuantity) {
                            return [
                                'success' => false,
                                'message' => 'Insufficient stock for ' . $product->name . ' in this kit.',
                            ];
                        }
                    }
                }
            }

            $cartItem->update(['quantity' => $quantity]);

            return [
                'success' => true,
                'message' => 'Cart updated successfully',
            ];
        } catch (\Exception $e) {
            Log::error('Error updating cart quantity: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Failed to update cart. Please try again.',
            ];
        }
    }

    public static function removeItem($cartId)
    {
        try {
            $cartItem = self::forCurrentUser()->findOrFail($cartId);
            $cartItem->delete();

            return [
                'success' => true,
                'message' => 'Item removed from cart',
            ];
        } catch (\Exception $e) {
            Log::error('Error removing cart item: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Failed to remove item. Please try again.',
            ];
        }
    }

    public static function clearCart()
    {
        try {
            self::forCurrentUser()->delete();

            return [
                'success' => true,
                'message' => 'Cart cleared successfully',
            ];
        } catch (\Exception $e) {
            Log::error('Error clearing cart: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Failed to clear cart. Please try again.',
            ];
        }
    }

    /**
     * Merge guest cart with user cart when user logs in
     */
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
