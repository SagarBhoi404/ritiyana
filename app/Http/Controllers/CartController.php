<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CartController extends Controller
{
    public function index()
    {
        $cartItems = Cart::getCartItems();

        return view('cart.index', compact('cartItems'));
    }

    public function add(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1|max:10',
            'options' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid data provided',
                'errors' => $validator->errors(),
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
                'cart_total' => '₹'.number_format(Cart::getCartTotal(), 2),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to add item to cart',
            ], 500);
        }
    }

    public function addPujaKit(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'puja_kit_id' => 'required|exists:puja_kits,id',
            'quantity' => 'integer|min:1|max:10',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid data provided',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $result = Cart::addPujaKitToCart(
                $request->puja_kit_id,
                $request->quantity ?? 1
            );

            return response()->json([
                'success' => $result['success'],
                'message' => $result['message'],
                'cart_count' => Cart::getCartCount(),
                'cart_total' => '₹'.number_format(Cart::getCartTotal(), 2),
            ]);
        } catch (\Exception $e) {
            \Log::error('Add puja kit to cart error: '.$e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to add puja kit to cart: '.$e->getMessage(),
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'quantity' => 'required|integer|min:0|max:10',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid quantity provided',
            ], 422);
        }

        try {
            $result = Cart::updateQuantity($id, $request->quantity);

            return response()->json([
                'success' => $result['success'],
                'message' => $result['message'],
                'cart_count' => Cart::getCartCount(),
                'cart_total' => '₹'.number_format(Cart::getCartTotal(), 2),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update cart',
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $result = Cart::removeItem($id);

            return response()->json([
                'success' => $result['success'],
                'message' => $result['message'],
                'cart_count' => Cart::getCartCount(),
                'cart_total' => '₹'.number_format(Cart::getCartTotal(), 2),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to remove item',
            ], 500);
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
                'cart_total' => '₹0.00',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to clear cart',
            ], 500);
        }
    }

    public function miniCart()
    {
        try {
            \Log::info('Mini cart request started');

            $items = Cart::getCartItems();
            \Log::info('Cart items loaded: '.$items->count());

            $count = Cart::getCartCount();
            \Log::info('Cart count: '.$count);

            $total = '₹'.number_format(Cart::getCartTotal(), 2);
            \Log::info('Cart total: '.$total);

            // Format items for frontend with better error handling
            $formattedItems = $items->map(function ($item) {
                try {
                    \Log::info('Processing item ID: '.$item->id.' Type: '.$item->item_type);

                    return [
                        'id' => $item->id,
                        'quantity' => $item->quantity,
                        'formatted_price' => $item->formatted_price,
                        'formatted_subtotal' => $item->formatted_subtotal,
                        'display_name' => $item->display_name,
                        'display_image' => $item->display_image,
                        'item_type' => $item->item_type,
                        'options' => $item->product_options,
                        // Keep backward compatibility
                        'product' => [
                            'name' => $item->display_name,
                            'image' => $item->display_image,
                        ],
                    ];
                } catch (\Exception $e) {
                    \Log::error('Error processing cart item '.$item->id.': '.$e->getMessage());

                    // Return a safe default
                    return [
                        'id' => $item->id,
                        'quantity' => $item->quantity ?? 1,
                        'formatted_price' => '₹0.00',
                        'formatted_subtotal' => '₹0.00',
                        'display_name' => 'Unknown Item',
                        'display_image' => '/images/placeholder.jpg',
                        'item_type' => $item->item_type ?? 'product',
                        'options' => null,
                        'product' => [
                            'name' => 'Unknown Item',
                            'image' => '/images/placeholder.jpg',
                        ],
                    ];
                }
            });

            \Log::info('Items formatted successfully');

            return response()->json([
                'success' => true,
                'items' => $formattedItems,
                'count' => $count,
                'total' => $total,
            ]);
        } catch (\Exception $e) {
            \Log::error('Mini cart error: '.$e->getMessage());
            \Log::error('Stack trace: '.$e->getTraceAsString());

            return response()->json([
                'success' => false,
                'message' => 'Failed to load cart data: '.$e->getMessage(),
            ], 500);
        }
    }

    public function count()
    {
        try {
            return response()->json([
                'count' => Cart::getCartCount(),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'count' => 0,
            ]);
        }
    }
}
