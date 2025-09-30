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

            // **FIX: Use proper decimal calculations for financial calculations**
            $taxAmount = round($cartTotal * 0.18, 2); // 18% GST - properly rounded
            $shippingAmount = $cartTotal >= 500 ? 0 : 50; // Free shipping above ₹500
            $totalAmount = round($cartTotal + $taxAmount + $shippingAmount, 2);

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

            // **FIX: Better cart item loading with proper error handling**
            $cartItems = Cart::forCurrentUser()
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

            // **FIX: Validate addresses with better error handling**
            $billingAddress = $user->addresses()
                ->where('id', $request->billing_address_id)
                ->first();

            $shippingAddress = $user->addresses()
                ->where('id', $request->shipping_address_id)
                ->first();

            if (! $billingAddress || ! $shippingAddress) {
                Log::warning('Invalid addresses for user: '.Auth::id(), [
                    'billing_address_id' => $request->billing_address_id,
                    'shipping_address_id' => $request->shipping_address_id,
                    'billing_found' => (bool) $billingAddress,
                    'shipping_found' => (bool) $shippingAddress,
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Invalid address selected',
                ], 400);
            }

            // **CRITICAL FIX: Improved price calculation with proper decimal handling**
            $subtotal = 0.0;
            $calculatedItems = [];

            foreach ($cartItems as $cartItem) {
                Log::info('Processing cart item', [
                    'cart_id' => $cartItem->id,
                    'item_type' => $cartItem->item_type,
                    'stored_price' => $cartItem->price,
                    'quantity' => $cartItem->quantity,
                ]);

                $unitPrice = 0.0;
                $itemName = 'Unknown Item';

                // **FIX: Improved price extraction with better fallback logic**
                if ($cartItem->item_type === 'puja_kit') {
                    if ($cartItem->pujaKit) {
                        // Get the best available price for puja kit
                        $unitPrice = $cartItem->pujaKit->final_price
                                   ?? $cartItem->pujaKit->total_price
                                   ?? $cartItem->pujaKit->price
                                   ?? floatval($cartItem->price ?? 0);
                        $itemName = $cartItem->pujaKit->kit_name ?? 'Unknown Puja Kit';

                        Log::info('PujaKit pricing', [
                            'kit_id' => $cartItem->puja_kit_id,
                            'kit_name' => $itemName,
                            'kit_final_price' => $cartItem->pujaKit->final_price,
                            'kit_total_price' => $cartItem->pujaKit->total_price,
                            'kit_price' => $cartItem->pujaKit->price ?? null,
                            'stored_price' => $cartItem->price,
                            'used_price' => $unitPrice,
                        ]);
                    } else {
                        // Fallback to stored price
                        $unitPrice = floatval($cartItem->price ?? 0);
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
                        // Get the best available price for product
                        $unitPrice = $cartItem->product->sale_price
                                   ?? $cartItem->product->price
                                   ?? floatval($cartItem->price ?? 0);
                        $itemName = $cartItem->product->name ?? 'Unknown Product';

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
                        $unitPrice = floatval($cartItem->price ?? 0);
                        $itemName = $cartItem->item_name ?? 'Unknown Product';

                        Log::warning('Product not found, using stored price', [
                            'cart_id' => $cartItem->id,
                            'product_id' => $cartItem->product_id,
                            'stored_price' => $unitPrice,
                        ]);
                    }
                }

                // **CRITICAL: Ensure we have a valid price with proper validation**
                $unitPrice = floatval($unitPrice);

                if ($unitPrice <= 0) {
                    Log::error('Zero or negative price detected!', [
                        'cart_id' => $cartItem->id,
                        'item_type' => $cartItem->item_type,
                        'final_unit_price' => $unitPrice,
                        'item_name' => $itemName,
                        'original_stored_price' => $cartItem->price,
                    ]);

                    return response()->json([
                        'success' => false,
                        'message' => 'Invalid price for item: '.$itemName.'. Please remove and re-add this item to your cart.',
                    ], 400);
                }

                // **FIX: Proper decimal calculation with rounding**
                $quantity = intval($cartItem->quantity);
                if ($quantity <= 0) {
                    Log::error('Invalid quantity detected!', [
                        'cart_id' => $cartItem->id,
                        'quantity' => $cartItem->quantity,
                        'item_name' => $itemName,
                    ]);

                    return response()->json([
                        'success' => false,
                        'message' => 'Invalid quantity for item: '.$itemName,
                    ], 400);
                }

                $itemSubtotal = round($unitPrice * $quantity, 2);
                $subtotal = round($subtotal + $itemSubtotal, 2);

                $calculatedItems[] = [
                    'cart_item' => $cartItem,
                    'unit_price' => $unitPrice,
                    'subtotal' => $itemSubtotal,
                    'item_name' => $itemName,
                    'quantity' => $quantity,
                ];

                Log::info('Item calculation completed', [
                    'item_name' => $itemName,
                    'unit_price' => $unitPrice,
                    'quantity' => $quantity,
                    'item_subtotal' => $itemSubtotal,
                    'running_subtotal' => $subtotal,
                ]);
            }

            // Final validation
            if ($subtotal <= 0) {
                Log::error('CRITICAL: Final subtotal is zero or negative!', [
                    'calculated_subtotal' => $subtotal,
                    'items_count' => count($calculatedItems),
                    'user_id' => Auth::id(),
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Unable to calculate order total. Please clear your cart and try again.',
                ], 400);
            }

            // **FIX: Calculate final amounts with proper decimal handling**
            $taxAmount = round($subtotal * 0.18, 2); // 18% GST
            $shippingAmount = $subtotal >= 500 ? 0.00 : 50.00;
            $totalAmount = round($subtotal + $taxAmount + $shippingAmount, 2);

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

            // **FIX: Create order with validated and properly calculated values**
            $order = Order::create([
                'user_id' => $user->id,
                'order_number' => 'ORD-'.strtoupper(uniqid()),
                'status' => 'pending',
                'payment_status' => 'pending',
                'subtotal' => $subtotal,
                'tax_amount' => $taxAmount,
                'shipping_amount' => $shippingAmount,
                'total_amount' => $totalAmount,
                'currency' => 'INR',
                'billing_address' => $billingAddress->toArray(),
                'shipping_address' => $shippingAddress->toArray(),
                'notes' => $request->notes,
            ]);

            Log::info('=== ORDER CREATED ===', [
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'order_subtotal' => $order->subtotal,
                'order_tax' => $order->tax_amount,
                'order_shipping' => $order->shipping_amount,
                'order_total' => $order->total_amount,
            ]);

            // **FIX: Create order items with validated prices and better error handling**
            $createdOrderItems = [];
            foreach ($calculatedItems as $calculatedItem) {
                $cartItem = $calculatedItem['cart_item'];

                $orderItemData = [
                    'order_id' => $order->id,
                    'product_id' => $cartItem->product_id,
                    'puja_kit_id' => $cartItem->puja_kit_id,
                    'product_name' => $calculatedItem['item_name'],
                    'product_sku' => $this->generateSKU($cartItem),
                    'product_image' => $cartItem->display_image ?? null,
                    'quantity' => $calculatedItem['quantity'],
                    'price' => $calculatedItem['unit_price'],
                    'total' => $calculatedItem['subtotal'],
                    'vendor_id' => $this->getVendorId($cartItem),
                    'product_options' => $this->getProductOptions($cartItem),
                ];

                $orderItem = OrderItem::create($orderItemData);
                $createdOrderItems[] = $orderItem;

                Log::info('Order item created', [
                    'order_item_id' => $orderItem->id,
                    'product_name' => $calculatedItem['item_name'],
                    'quantity' => $calculatedItem['quantity'],
                    'unit_price' => $calculatedItem['unit_price'],
                    'item_total' => $calculatedItem['subtotal'],
                ]);
            }

            // **FIX: Verify order items were created correctly with tolerance for float precision**
            $orderItemsTotal = round(collect($createdOrderItems)->sum('total'), 2);
            $orderSubtotal = round($order->subtotal, 2);

            Log::info('Order verification', [
                'order_subtotal' => $orderSubtotal,
                'order_items_total' => $orderItemsTotal,
                'difference' => abs($orderSubtotal - $orderItemsTotal),
                'match' => abs($orderSubtotal - $orderItemsTotal) < 0.01,
            ]);

            $this->decreaseProductStock($order);

            if (abs($orderSubtotal - $orderItemsTotal) >= 0.01) {
                Log::error('Order items total mismatch!', [
                    'order_subtotal' => $orderSubtotal,
                    'order_items_total' => $orderItemsTotal,
                    'difference' => abs($orderSubtotal - $orderItemsTotal),
                ]);

                // This is a critical error - rollback
                DB::rollback();

                return response()->json([
                    'success' => false,
                    'message' => 'Order calculation error. Please try again.',
                ], 500);
            }

            // Handle payment methods
            if ($request->payment_method === 'cod') {
                // Cash on Delivery
                $this->processCODPayment($order, $user);

                // **FIX: Clear cart immediately after COD order with better error handling**
                try {
                    $cartClearResult = Cart::clearCart();
                    Log::info('Cart clear result for COD order', [
                        'order_number' => $order->order_number,
                        'clear_success' => $cartClearResult['success'] ?? true,
                        'clear_message' => $cartClearResult['message'] ?? 'Cart cleared',
                    ]);
                } catch (\Exception $e) {
                    Log::warning('Failed to clear cart after COD order', [
                        'order_number' => $order->order_number,
                        'error' => $e->getMessage(),
                    ]);
                    // Don't fail the order for cart clear failure
                }

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

                // **FIX: Better customer name handling**
                $customerName = $this->getCustomerName($user);
                $customerPhone = $user->phone ?? '9999999999';

                // Create Cashfree order with correct amount
                $cashfreeOrderData = [
                    'order_id' => $order->order_number,
                    'amount' => $totalAmount,
                    'currency' => 'INR',
                    'customer_id' => 'customer_'.$user->id,
                    'customer_name' => $customerName,
                    'customer_email' => $user->email,
                    'customer_phone' => $customerPhone,
                    'return_url' => url('/payment/success?order_id='.$order->order_number),
                    'order_note' => 'Order from Shree Samagri - '.$order->order_number,
                    'payment_methods' => 'cc,dc,upi,nb',
                ];

                // **FIX: Better webhook URL handling**
                try {
                    $webhookUrl = route('payment.webhook');
                    $cashfreeOrderData['webhook_url'] = $webhookUrl;
                } catch (\Exception $e) {
                    Log::warning('Webhook route not available: '.$e->getMessage());
                    // Continue without webhook
                }

                Log::info('Creating Cashfree order', [
                    'order_data' => array_merge($cashfreeOrderData, [
                        'customer_phone' => substr($customerPhone, 0, 4).'****', // Mask phone for logging
                    ]),
                ]);

                $cashfreeResponse = $this->cashfreeService->createOrder($cashfreeOrderData);

                // **FIX: Better Cashfree response handling**
                if (! $cashfreeResponse || ! ($cashfreeResponse['success'] ?? false)) {
                    DB::rollback();
                    $errorMessage = $cashfreeResponse['error'] ?? 'Unknown payment gateway error';

                    Log::error('Cashfree order creation failed', [
                        'error' => $errorMessage,
                        'response' => $cashfreeResponse,
                        'order_number' => $order->order_number,
                    ]);

                    return response()->json([
                        'success' => false,
                        'message' => 'Payment gateway error: '.$errorMessage,
                    ], 500);
                }

                // **FIX: More robust extraction of cf_order_id**
                $cfOrderId = $this->extractCfOrderId($cashfreeResponse);
                $paymentSessionId = $this->extractPaymentSessionId($cashfreeResponse);

                Log::info('Cashfree response parsed', [
                    'cf_order_id' => $cfOrderId,
                    'payment_session_id' => $paymentSessionId ? substr($paymentSessionId, 0, 20).'...' : 'missing',
                ]);

                if (! $cfOrderId) {
                    DB::rollback();
                    Log::error('Could not extract cf_order_id from Cashfree response', [
                        'response_structure' => $this->getResponseStructure($cashfreeResponse),
                        'order_number' => $order->order_number,
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
                        'cf_order_id' => $cfOrderId,
                        'payment_session_id' => $paymentSessionId,
                        'order_id' => $order->order_number,
                        'amount' => $totalAmount,
                        'customer_name' => $customerName,
                        'customer_email' => $user->email,
                        'customer_phone' => $customerPhone,
                    ],
                ]);
            }

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Checkout process failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'user_id' => Auth::id(),
                'request_data' => $request->all(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Checkout failed. Please try again.',
                'error_details' => config('app.debug') ? $e->getMessage() : 'Please contact support if the problem persists',
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
                ->first();

            if (! $order) {
                Log::warning('Order not found for success page', [
                    'order_number' => $orderNumber,
                    'user_id' => $user->id,
                ]);

                return redirect()->route('home')
                    ->with('error', 'Order not found');
            }

            return view('checkout.success', compact('order'));

        } catch (\Exception $e) {
            Log::error('Checkout success page error: '.$e->getMessage(), [
                'order_number' => $orderNumber,
                'user_id' => Auth::id(),
            ]);

            return redirect()->route('home')
                ->with('error', 'Unable to load order confirmation');
        }
    }

    // **FIX: Enhanced helper methods with better error handling**

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

    // **NEW: Additional helper methods for better code organization**

    private function getCustomerName($user)
    {
        return $user->full_name
            ?? ($user->first_name && $user->last_name ? $user->first_name.' '.$user->last_name : null)
            ?? $user->name
            ?? 'Customer';
    }

    private function extractCfOrderId($response)
    {
        return $response['cf_order_id']
            ?? $response['full_response']['cf_order_id']
            ?? $response['order_details']['cf_order_id']
            ?? $response['data']['cf_order_id']
            ?? null;
    }

    private function extractPaymentSessionId($response)
    {
        return $response['payment_session_id']
            ?? $response['full_response']['payment_session_id']
            ?? $response['data']['payment_session_id']
            ?? null;
    }

    private function getResponseStructure($response)
    {
        if (! is_array($response)) {
            return 'non-array response';
        }

        $structure = [];
        foreach ($response as $key => $value) {
            if (is_array($value)) {
                $structure[$key] = 'array['.implode(',', array_keys($value)).']';
            } else {
                $structure[$key] = gettype($value);
            }
        }

        return $structure;
    }

    /**
     * Decrease stock quantity for products when order is successful
     */
    private function decreaseProductStock(Order $order)
    {
        Log::info('Starting stock decrease for order', [
            'order_number' => $order->order_number,
            'items_count' => $order->orderItems->count(),
        ]);

        foreach ($order->orderItems as $orderItem) {
            try {
                if ($orderItem->product_id && $orderItem->product) {
                    $product = $orderItem->product;
                    $quantityToDecrease = $orderItem->quantity;

                    // Check if enough stock is available
                    if ($product->stock_quantity >= $quantityToDecrease) {
                        // Decrease stock quantity
                        $oldStock = $product->stock_quantity;
                        $product->decrement('stock_quantity', $quantityToDecrease);

                        // Refresh to get updated stock
                        $product->refresh();
                        $newStock = $product->stock_quantity;

                        Log::info('Product stock decreased', [
                            'product_id' => $product->id,
                            'product_name' => $product->name,
                            'old_stock' => $oldStock,
                            'quantity_decreased' => $quantityToDecrease,
                            'new_stock' => $newStock,
                            'order_number' => $order->order_number,
                        ]);

                        // Create inventory log for the stock decrease
                        \App\Models\InventoryLog::create([
                            'product_id' => $product->id,
                            'type' => 'decrease',
                            'quantity' => $quantityToDecrease,
                            'reason' => 'Order placed',
                            'reference_type' => 'order',
                            'reference_id' => $order->id,
                            'notes' => "Stock decreased for order #{$order->order_number}",
                            'previous_quantity' => $oldStock,
                            'new_quantity' => $newStock,
                            'created_by' => $order->user_id,
                        ]);

                    } else {
                        Log::warning('Insufficient stock for product', [
                            'product_id' => $product->id,
                            'product_name' => $product->name,
                            'available_stock' => $product->stock_quantity,
                            'required_quantity' => $quantityToDecrease,
                            'order_number' => $order->order_number,
                        ]);
                    }

                } elseif ($orderItem->puja_kit_id && $orderItem->pujaKit) {
                    // Handle puja kit stock decrease
                    $pujaKit = $orderItem->pujaKit;

                    Log::info('Processing puja kit stock decrease', [
                        'puja_kit_id' => $pujaKit->id,
                        'kit_name' => $pujaKit->kit_name,
                        'kit_quantity' => $orderItem->quantity,
                    ]);

                    foreach ($pujaKit->products as $kitProduct) {
                        $requiredQuantity = $kitProduct->pivot->quantity * $orderItem->quantity;

                        if ($kitProduct->stock_quantity >= $requiredQuantity) {
                            $oldStock = $kitProduct->stock_quantity;
                            $kitProduct->decrement('stock_quantity', $requiredQuantity);

                            // Refresh to get updated stock
                            $kitProduct->refresh();
                            $newStock = $kitProduct->stock_quantity;

                            Log::info('Puja kit product stock decreased', [
                                'product_id' => $kitProduct->id,
                                'product_name' => $kitProduct->name,
                                'old_stock' => $oldStock,
                                'quantity_decreased' => $requiredQuantity,
                                'new_stock' => $newStock,
                                'puja_kit_id' => $pujaKit->id,
                                'order_number' => $order->order_number,
                            ]);

                            // Create inventory log for puja kit product
                            \App\Models\InventoryLog::create([
                                'product_id' => $kitProduct->id,
                                'type' => 'decrease',
                                'quantity' => $requiredQuantity,
                                'reason' => 'Puja kit order placed',
                                'reference_type' => 'order',
                                'reference_id' => $order->id,
                                'notes' => "Stock decreased for puja kit in order #{$order->order_number}",
                                'previous_quantity' => $oldStock,
                                'new_quantity' => $newStock,
                                'created_by' => $order->user_id,
                            ]);

                        } else {
                            Log::warning('Insufficient stock for puja kit product', [
                                'product_id' => $kitProduct->id,
                                'product_name' => $kitProduct->name,
                                'available_stock' => $kitProduct->stock_quantity,
                                'required_quantity' => $requiredQuantity,
                                'puja_kit_id' => $pujaKit->id,
                                'order_number' => $order->order_number,
                            ]);
                        }
                    }
                }

            } catch (\Exception $e) {
                Log::error('Failed to decrease stock for order item', [
                    'order_item_id' => $orderItem->id,
                    'product_id' => $orderItem->product_id,
                    'puja_kit_id' => $orderItem->puja_kit_id,
                    'error' => $e->getMessage(),
                    'order_number' => $order->order_number,
                ]);
                // Continue with other items, don't fail the entire process
            }
        }

        Log::info('Stock decrease completed for order', [
            'order_number' => $order->order_number,
        ]);
    }
}
