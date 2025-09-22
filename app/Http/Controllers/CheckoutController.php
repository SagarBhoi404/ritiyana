<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Services\CashfreeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class CheckoutController extends Controller
{
    private $cashfreeService;

    // Cashfree limits
    private $maxSandboxAmount = 50000; // ₹50,000 for sandbox

    private $maxProductionAmount = 5000000; // ₹50,00,000 for production

    public function __construct(CashfreeService $cashfreeService)
    {
        $this->cashfreeService = $cashfreeService;
    }

    public function index()
    {
        try {
            $cartItems = Cart::getCartItems();

            if ($cartItems->isEmpty()) {
                return redirect()->route('cart.index')->with('error', 'Your cart is empty');
            }

            $user = Auth::user();
            $addresses = $user->addresses ?? collect();
            $defaultAddress = $user->defaultAddress ?? null;

            $cartTotal = Cart::getCartTotal();
            $taxAmount = $cartTotal * 0.18; // 18% GST
            $shippingAmount = $cartTotal >= 500 ? 0 : 50; // Free shipping above ₹500
            $totalAmount = $cartTotal + $taxAmount + $shippingAmount;

            // Check amount limits
            $maxAllowed = config('cashfree.mode') === 'sandbox' ? $this->maxSandboxAmount : $this->maxProductionAmount;
            $amountExceeded = $totalAmount > $maxAllowed;

            return view('checkout.index', compact(
                'cartItems',
                'addresses',
                'defaultAddress',
                'cartTotal',
                'taxAmount',
                'shippingAmount',
                'totalAmount',
                'maxAllowed',
                'amountExceeded'
            ));
        } catch (\Exception $e) {
            Log::error('Checkout page error: '.$e->getMessage());

            return redirect()->route('cart.index')->with('error', 'Unable to load checkout page');
        }
    }

    public function processCheckout(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'billing_address_id' => 'required|exists:addresses,id',
            'shipping_address_id' => 'required|exists:addresses,id',
            'payment_method' => 'required|in:online,cod',
            'notes' => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            DB::beginTransaction();

            Log::info('=== STARTING CHECKOUT PROCESS ===', [
                'user_id' => Auth::id(),
                'payment_method' => $request->payment_method,
            ]);

            // Get cart items with proper relationships
            $cartItems = Cart::forCurrentUser()
                ->withItems()
                ->with(['product', 'pujaKit'])
                ->get();

            if ($cartItems->isEmpty()) {
                Log::warning('Empty cart for user: '.Auth::id());

                return response()->json([
                    'success' => false,
                    'message' => 'Your cart is empty',
                ], 400);
            }

            Log::info('Cart items loaded', [
                'items_count' => $cartItems->count(),
                'items_detail' => $cartItems->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'item_type' => $item->item_type,
                        'stored_price' => $item->price,
                        'quantity' => $item->quantity,
                        'product_id' => $item->product_id,
                        'puja_kit_id' => $item->puja_kit_id,
                    ];
                })->toArray(),
            ]);

            $user = Auth::user();

            // Verify addresses belong to user
            $billingAddress = $user->addresses()
                ->where('id', $request->billing_address_id)
                ->first();

            $shippingAddress = $user->addresses()
                ->where('id', $request->shipping_address_id)
                ->first();

            if (! $billingAddress || ! $shippingAddress) {
                Log::warning('Invalid addresses for user: '.Auth::id());

                return response()->json([
                    'success' => false,
                    'message' => 'Invalid address selected',
                ], 400);
            }

            // **CRITICAL FIX: Calculate prices correctly**
            $subtotal = 0;
            $calculatedItems = [];

            foreach ($cartItems as $cartItem) {
                Log::info('Processing cart item', [
                    'cart_id' => $cartItem->id,
                    'item_type' => $cartItem->item_type,
                    'stored_price' => $cartItem->price,
                    'quantity' => $cartItem->quantity,
                ]);

                $unitPrice = 0;
                $itemName = 'Unknown Item';

                // **FIX: Get prices directly from relationships or fallback to stored price**
                if ($cartItem->item_type === 'puja_kit') {
                    if ($cartItem->pujaKit) {
                        // Try different price fields for puja kit
                        $unitPrice = $cartItem->pujaKit->final_price ??
                                   $cartItem->pujaKit->total_price ??
                                   $cartItem->price ?? 0;
                        $itemName = $cartItem->pujaKit->kit_name;

                        Log::info('PujaKit pricing', [
                            'kit_id' => $cartItem->puja_kit_id,
                            'kit_name' => $itemName,
                            'kit_final_price' => $cartItem->pujaKit->final_price,
                            'kit_total_price' => $cartItem->pujaKit->total_price,
                            'stored_price' => $cartItem->price,
                            'used_price' => $unitPrice,
                        ]);
                    } else {
                        // Fallback to stored price
                        $unitPrice = $cartItem->price ?? 0;
                        $itemName = $cartItem->item_name ?? 'Unknown Puja Kit';

                        Log::warning('PujaKit not found, using stored price', [
                            'cart_id' => $cartItem->id,
                            'puja_kit_id' => $cartItem->puja_kit_id,
                            'stored_price' => $unitPrice,
                        ]);
                    }
                } else {
                    // Product
                    if ($cartItem->product) {
                        // Try different price fields for product
                        $unitPrice = $cartItem->product->sale_price ??
                                   $cartItem->product->price ??
                                   $cartItem->price ?? 0;
                        $itemName = $cartItem->product->name;

                        Log::info('Product pricing', [
                            'product_id' => $cartItem->product_id,
                            'product_name' => $itemName,
                            'product_price' => $cartItem->product->price,
                            'product_sale_price' => $cartItem->product->sale_price,
                            'stored_price' => $cartItem->price,
                            'used_price' => $unitPrice,
                        ]);
                    } else {
                        // Fallback to stored price
                        $unitPrice = $cartItem->price ?? 0;
                        $itemName = $cartItem->item_name ?? 'Unknown Product';

                        Log::warning('Product not found, using stored price', [
                            'cart_id' => $cartItem->id,
                            'product_id' => $cartItem->product_id,
                            'stored_price' => $unitPrice,
                        ]);
                    }
                }

                // **CRITICAL: Ensure we have a valid price**
                if ($unitPrice <= 0) {
                    // Try to get price from stored cart price as last resort
                    $unitPrice = floatval($cartItem->price ?? 0);

                    Log::error('Zero or negative price detected!', [
                        'cart_id' => $cartItem->id,
                        'item_type' => $cartItem->item_type,
                        'final_unit_price' => $unitPrice,
                        'item_name' => $itemName,
                    ]);

                    if ($unitPrice <= 0) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Invalid price for item: '.$itemName.'. Please remove and re-add this item to your cart.',
                        ], 400);
                    }
                }

                $itemSubtotal = $unitPrice * $cartItem->quantity;
                $subtotal += $itemSubtotal;

                $calculatedItems[] = [
                    'cart_item' => $cartItem,
                    'unit_price' => $unitPrice,
                    'subtotal' => $itemSubtotal,
                    'item_name' => $itemName,
                ];

                Log::info('Item calculation completed', [
                    'item_name' => $itemName,
                    'unit_price' => $unitPrice,
                    'quantity' => $cartItem->quantity,
                    'item_subtotal' => $itemSubtotal,
                    'running_subtotal' => $subtotal,
                ]);
            }

            // Final validation
            if ($subtotal <= 0) {
                Log::error('CRITICAL: Final subtotal is zero!', [
                    'calculated_subtotal' => $subtotal,
                    'items_count' => count($calculatedItems),
                    'user_id' => Auth::id(),
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Unable to calculate order total. Please clear your cart and try again.',
                ], 400);
            }

            // Calculate final amounts
            $taxAmount = round($subtotal * 0.18, 2); // 18% GST
            $shippingAmount = $subtotal >= 500 ? 0 : 50;
            $totalAmount = $subtotal + $taxAmount + $shippingAmount;

            Log::info('=== FINAL CALCULATIONS ===', [
                'subtotal' => $subtotal,
                'tax_amount' => $taxAmount,
                'shipping_amount' => $shippingAmount,
                'total_amount' => $totalAmount,
            ]);

            // Check amount limits before processing
            $maxAllowed = config('cashfree.mode') === 'sandbox' ? $this->maxSandboxAmount : $this->maxProductionAmount;

            if ($request->payment_method === 'online' && $totalAmount > $maxAllowed) {
                Log::warning('Amount exceeds limit', [
                    'total_amount' => $totalAmount,
                    'max_allowed' => $maxAllowed,
                    'mode' => config('cashfree.mode'),
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Order amount ₹'.number_format($totalAmount, 2).' exceeds the maximum allowed limit of ₹'.number_format($maxAllowed, 2).' for '.config('cashfree.mode').' environment. Please try Cash on Delivery or reduce cart items.',
                ], 400);
            }

            // **CRITICAL: Create order with CORRECT values**
            $order = Order::create([
                'user_id' => $user->id,
                'order_number' => 'ORD-'.strtoupper(uniqid()),
                'status' => 'pending',
                'payment_status' => 'pending',
                'subtotal' => $subtotal, // This should now be correct
                'tax_amount' => $taxAmount,
                'shipping_amount' => $shippingAmount,
                'total_amount' => $totalAmount,
                'currency' => 'INR',
                'billing_address' => $billingAddress->toArray(),
                'shipping_address' => $shippingAddress->toArray(),
                'notes' => $request->notes,
            ]);

            Log::info('=== ORDER CREATED ===', [
                'order_number' => $order->order_number,
                'order_subtotal' => $order->subtotal,
                'order_tax' => $order->tax_amount,
                'order_shipping' => $order->shipping_amount,
                'order_total' => $order->total_amount,
            ]);

            // Create order items with validated prices
            foreach ($calculatedItems as $calculatedItem) {
                $cartItem = $calculatedItem['cart_item'];

                $orderItemData = [
                    'order_id' => $order->id,
                    'product_id' => $cartItem->product_id,
                    'puja_kit_id' => $cartItem->puja_kit_id,
                    'product_name' => $calculatedItem['item_name'],
                    'product_sku' => $this->generateSKU($cartItem),
                    'product_image' => $cartItem->display_image ?? null,
                    'quantity' => $cartItem->quantity,
                    'price' => $calculatedItem['unit_price'],
                    'total' => $calculatedItem['subtotal'],
                    'vendor_id' => $this->getVendorId($cartItem),
                    'product_options' => $this->getProductOptions($cartItem),
                ];

                $orderItem = OrderItem::create($orderItemData);

                Log::info('Order item created', [
                    'order_item_id' => $orderItem->id,
                    'product_name' => $calculatedItem['item_name'],
                    'quantity' => $cartItem->quantity,
                    'unit_price' => $calculatedItem['unit_price'],
                    'item_total' => $calculatedItem['subtotal'],
                ]);
            }

            // Verify order items were created correctly
            $orderItemsTotal = $order->orderItems()->sum('total');
            Log::info('Order verification', [
                'order_subtotal' => $order->subtotal,
                'order_items_total' => $orderItemsTotal,
                'match' => abs($order->subtotal - $orderItemsTotal) < 0.01,
            ]);

            // Handle payment methods
            if ($request->payment_method === 'cod') {
                // Cash on Delivery
                $this->processCODPayment($order, $user);
                Cart::clearCart();
                DB::commit();

                Log::info('COD order completed: '.$order->order_number);

                return response()->json([
                    'success' => true,
                    'message' => 'Order placed successfully!',
                    'redirect_url' => route('checkout.success', $order->order_number),
                ]);

            } else {
                // Online Payment - Create Cashfree order
                $payment = $this->createPendingPayment($order, $user);

                // Create Cashfree order with correct amount
                $cashfreeOrderData = [
                    'order_id' => $order->order_number,
                    'amount' => $totalAmount,
                    'currency' => 'INR',
                    'customer_id' => 'customer_'.$user->id,
                    'customer_name' => $user->full_name ?? ($user->first_name.' '.$user->last_name) ?? $user->name ?? 'Customer',
                    'customer_email' => $user->email,
                    'customer_phone' => $user->phone ?? '9999999999',
                    'return_url' => url('/payment/success?order_id='.$order->order_number),
                    'order_note' => 'Order from Ritiyana - '.$order->order_number,
                    'payment_methods' => 'cc,dc,upi,nb',
                ];

                try {
                    $webhookUrl = route('payment.webhook');
                    $cashfreeOrderData['webhook_url'] = $webhookUrl;
                } catch (\Exception $e) {
                    // Webhook route not available
                }

                Log::info('Creating Cashfree order', $cashfreeOrderData);

                $cashfreeResponse = $this->cashfreeService->createOrder($cashfreeOrderData);

                // **IMPROVED: Handle different Cashfree response structures**
                if (! $cashfreeResponse['success']) {
                    DB::rollback();
                    Log::error('Cashfree order creation failed: '.$cashfreeResponse['error']);

                    return response()->json([
                        'success' => false,
                        'message' => 'Payment gateway error: '.$cashfreeResponse['error'],
                    ], 500);
                }

                // Extract cf_order_id from various possible locations in response
                $cfOrderId = $cashfreeResponse['cf_order_id'] ??
                             $cashfreeResponse['full_response']['cf_order_id'] ??
                             $cashfreeResponse['order_details']['cf_order_id'] ??
                             null;

                $paymentSessionId = $cashfreeResponse['payment_session_id'] ??
                                   $cashfreeResponse['full_response']['payment_session_id'] ??
                                   null;

                Log::info('Cashfree response parsed', [
                    'cf_order_id' => $cfOrderId,
                    'payment_session_id' => $paymentSessionId ? substr($paymentSessionId, 0, 20).'...' : 'missing',
                ]);

                if (! $cfOrderId) {
                    DB::rollback();
                    Log::error('Could not extract cf_order_id from Cashfree response', [
                        'response_keys' => array_keys($cashfreeResponse),
                        'full_response' => $cashfreeResponse,
                    ]);

                    return response()->json([
                        'success' => false,
                        'message' => 'Payment gateway setup failed - missing order ID',
                    ], 500);
                }

                // Update payment with Cashfree order details
                $payment->update([
                    'gateway_order_id' => $cfOrderId,
                    'gateway_response' => $cashfreeResponse,
                ]);

                Log::info('Payment record updated', [
                    'payment_id' => $payment->id,
                    'gateway_order_id' => $cfOrderId,
                ]);
                DB::commit();

                Log::info('Online payment setup completed for order: '.$order->order_number);

                return response()->json([
                    'success' => true,
                    'message' => 'Order created successfully!',
                    'show_payment_gateway' => true,
                    'payment_data' => [
                        'cf_order_id' => $cashfreeResponse['cf_order_id'],
                        'payment_session_id' => $cashfreeResponse['payment_session_id'] ?? null,
                        'order_id' => $order->order_number,
                        'amount' => $totalAmount,
                        'customer_name' => $user->full_name ?? ($user->first_name.' '.$user->last_name) ?? $user->name ?? 'Customer',
                        'customer_email' => $user->email,
                        'customer_phone' => $user->phone ?? '9999999999',
                    ],
                ]);
            }

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Checkout process failed: '.$e->getMessage());
            Log::error('Stack trace: '.$e->getTraceAsString());

            return response()->json([
                'success' => false,
                'message' => 'Checkout failed. Please try again.',
                'error_details' => config('app.debug') ? $e->getMessage() : 'Please try again',
            ], 500);
        }
    }

    public function success($orderNumber)
    {
        try {
            $user = Auth::user();
            $order = $user->orders()
                ->with(['orderItems.product', 'orderItems.pujaKit', 'payments'])
                ->where('order_number', $orderNumber)
                ->firstOrFail();

            return view('checkout.success', compact('order'));

        } catch (\Exception $e) {
            Log::error('Checkout success page error: '.$e->getMessage());

            return redirect()->route('home')
                ->with('error', 'Unable to load order confirmation');
        }
    }

    // Helper methods
    private function generateSKU($cartItem)
    {
        if ($cartItem->item_type === 'puja_kit') {
            return 'KIT-'.($cartItem->puja_kit_id ?? 'UNKNOWN');
        }

        if ($cartItem->product) {
            return $cartItem->product->sku ?? 'SKU-'.$cartItem->product->id;
        }

        return 'SKU-'.($cartItem->product_id ?? 'UNKNOWN');
    }

    private function getVendorId($cartItem)
    {
        if ($cartItem->item_type === 'product' && $cartItem->product) {
            return $cartItem->product->vendor_id ?? null;
        }

        return null;
    }

    private function getProductOptions($cartItem)
    {
        if ($cartItem->item_type === 'puja_kit') {
            return json_encode([
                'item_type' => 'puja_kit',
                'puja_kit_id' => $cartItem->puja_kit_id,
                'kit_name' => $cartItem->pujaKit?->kit_name ?? $cartItem->item_name,
            ]);
        }

        return $cartItem->product_options;
    }

    private function processCODPayment(Order $order, $user)
    {
        Payment::create([
            'order_id' => $order->id,
            'user_id' => $user->id,
            'payment_id' => 'PAY-COD-'.strtoupper(uniqid()),
            'amount' => $order->total_amount,
            'currency' => 'INR',
            'gateway' => 'cod',
            'method' => 'cod',
            'status' => 'completed',
            'paid_at' => now(),
        ]);

        $order->update(['payment_status' => 'paid']);
    }

    private function createPendingPayment(Order $order, $user)
    {
        return Payment::create([
            'order_id' => $order->id,
            'user_id' => $user->id,
            'payment_id' => 'PAY-'.strtoupper(uniqid()),
            'amount' => $order->total_amount,
            'currency' => 'INR',
            'gateway' => 'cashfree',
            'method' => 'card',
            'status' => 'pending',
        ]);
    }
}
