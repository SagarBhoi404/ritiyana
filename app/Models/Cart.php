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
        'quantity',
        'price',
        'product_options'
    ];

    protected $casts = [
        'product_options' => 'array',
        'price' => 'decimal:2'
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

    // Scopes - Support both authenticated users and guests
    public function scopeForCurrentUser($query)
    {
        if (Auth::check()) {
            return $query->where('user_id', Auth::id());
        } else {
            return $query->where('session_id', Session::getId());
        }
    }

    public function scopeWithProducts($query)
    {
        return $query->whereHas('product');
    }

    // Accessors
    public function getSubtotalAttribute()
    {
        return $this->price * $this->quantity;
    }

    public function getFormattedPriceAttribute()
    {
        return '₹' . number_format($this->price, 2);
    }

    public function getFormattedSubtotalAttribute()
    {
        return '₹' . number_format($this->subtotal, 2);
    }

    // Static methods for cart operations
    public static function getCartItems()
    {
        return self::forCurrentUser()
            ->withProducts()
            ->with(['product' => function($query) {
                $query->select('id', 'name', 'slug', 'featured_image', 'stock_quantity', 'price');
            }])
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public static function getCartCount()
    {
        return self::forCurrentUser()->withProducts()->sum('quantity');
    }

    public static function getCartTotal()
    {
        return self::forCurrentUser()
            ->withProducts()
            ->get()
            ->sum('subtotal');
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
            'quantity' => $quantity,
            'price' => $product->final_price,
            'product_options' => $options
        ];

        // Set user_id or session_id based on authentication
        if (Auth::check()) {
            $cartData['user_id'] = Auth::id();
        } else {
            $cartData['session_id'] = Session::getId();
        }

        // Check if item already exists in cart
        $existingItem = self::where('product_id', $productId)
            ->when(Auth::check(), function($query) {
                return $query->where('user_id', Auth::id());
            }, function($query) {
                return $query->where('session_id', Session::getId());
            })
            ->when($options, function($query) use ($options) {
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

    public static function updateQuantity($cartId, $quantity)
    {
        $cartItem = self::forCurrentUser()->findOrFail($cartId);
        
        if ($quantity <= 0) {
            $cartItem->delete();
            return ['success' => true, 'message' => 'Item removed from cart'];
        }

        // Check stock
        if (isset($cartItem->product->stock_quantity) && $cartItem->product->stock_quantity < $quantity) {
            return ['success' => false, 'message' => 'Insufficient stock available'];
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
                    ->where('product_id', $guestItem->product_id)
                    ->where('product_options', json_encode($guestItem->product_options))
                    ->first();

                if ($existingItem) {
                    $existingItem->increment('quantity', $guestItem->quantity);
                } else {
                    $guestItem->update([
                        'user_id' => Auth::id(),
                        'session_id' => null
                    ]);
                }
            }

            // Clean up any remaining guest items
            self::where('session_id', $sessionId)->delete();
        }
    }
}
