<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use App\Models\PujaKit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class CartController extends Controller
{
    public function index()
    {
        $cartItems = Cart::getCartItems();
        $cartTotal = Cart::getCartTotal();
        $cartCount = Cart::getCartCount();

        return view('cart.index', compact('cartItems', 'cartTotal', 'cartCount'));
    }

    public function add(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1|max:10',
            'options' => 'nullable|array'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid data provided',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $result = Cart::addToCart(
                $request->product_id,
                $request->quantity,
                $request->options
            );

            return response()->json([
                'success' => $result['success'],
                'message' => $result['message'],
                'cart_count' => Cart::getCartCount(),
                'cart_total' => '₹' . number_format(Cart::getCartTotal(), 2)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to add item to cart'
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $validator = \Validator::make($request->all(), [
            'quantity' => 'required|integer|min:0|max:10'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid quantity provided',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $result = Cart::updateQuantity($id, $request->quantity);
            
            return response()->json([
                'success' => $result['success'],
                'message' => $result['message'],
                'cart_count' => Cart::getCartCount(),
                'cart_total' => '₹' . number_format(Cart::getCartTotal(), 2)
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Cart item not found'
            ], 404);
        }
    }


     public function remove($id)
    {
        try {
            $result = Cart::removeItem($id);
            
            return response()->json([
                'success' => $result['success'],
                'message' => $result['message'],
                'cart_count' => Cart::getCartCount(),
                'cart_total' => '₹' . number_format(Cart::getCartTotal(), 2)
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Cart item not found'
            ], 404);
        }
    }


    public function clear()
    {
        try {
            $result = Cart::clearCart();
            
            return response()->json([
                'success' => $result['success'],
                'message' => $result['message'],
                'cart_count' => 0,
                'cart_total' => '₹0.00'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to clear cart'
            ], 500);
        }
    }

    public function count()
    {
        return response()->json([
            'count' => Cart::getCartCount()
        ]);
    }

    public function mini()
    {
        $cartItems = Cart::getCartItems()->take(5);
        $cartTotal = Cart::getCartTotal();
        $cartCount = Cart::getCartCount();

        return response()->json([
            'success' => true,
            'items' => $cartItems->map(function ($item) {
                return [
                    'id' => $item->id,
                    'product' => [
                        'name' => $item->product->name,
                        'slug' => $item->product->slug,
                        'image' => $item->product->featured_image_url // Use the accessor from your Product model
                    ],
                    'quantity' => $item->quantity,
                    'formatted_price' => $item->formatted_price,
                    'formatted_subtotal' => $item->formatted_subtotal,
                    'options' => $item->product_options
                ];
            }),
            'total' => '₹' . number_format($cartTotal, 2),
            'count' => $cartCount
        ]);
    }


     public function addPujaKit(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'puja_kit_id' => 'required|exists:puja_kits,id',
            'quantity' => 'required|integer|min:1|max:5'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid data provided',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $pujaKit = PujaKit::with('products')->findOrFail($request->puja_kit_id);
            $addedItems = [];
            $failedItems = [];

            foreach ($pujaKit->products as $product) {
                $kitQuantity = $product->pivot->quantity * $request->quantity;
                $result = Cart::addToCart($product->id, $kitQuantity, [
                    'puja_kit_id' => $pujaKit->id,
                    'puja_kit_name' => $pujaKit->name
                ]);

                if ($result['success']) {
                    $addedItems[] = $product->name;
                } else {
                    $failedItems[] = $product->name . ' (' . $result['message'] . ')';
                }
            }

            $message = 'Puja kit added to cart successfully';
            if (!empty($failedItems)) {
                $message .= '. Some items could not be added: ' . implode(', ', $failedItems);
            }

            return response()->json([
                'success' => true,
                'message' => $message,
                'cart_count' => Cart::getCartCount(),
                'cart_total' => '₹' . number_format(Cart::getCartTotal(), 2)
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Puja kit not found'
            ], 404);
        }
    }
}
