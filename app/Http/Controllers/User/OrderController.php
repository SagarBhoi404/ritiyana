<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use App\Services\CashfreeService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    protected $cashfreeService;

    public function __construct(CashfreeService $cashfreeService)
    {
        $this->cashfreeService = $cashfreeService;
    }

    /**
     * Display a listing of user's orders
     */
    public function index(Request $request)
    {
        try {
            $query = Auth::user()->orders()->with([
                'orderItems.product',
                'orderItems.pujaKit', // Load pujaKit relationship
                'orderItems.vendor', 
                'payments'
            ]);

            // **FIX: Enhanced filtering with validation**
            if ($request->filled('status') && in_array($request->status, ['pending', 'processing', 'shipped', 'delivered', 'cancelled', 'return_requested'])) {
                $query->where('status', $request->status);
            }

            if ($request->filled('payment_status') && in_array($request->payment_status, ['pending', 'paid', 'failed', 'refunded'])) {
                $query->where('payment_status', $request->payment_status);
            }

            // **FIX: Improved search with sanitization**
            if ($request->filled('search')) {
                $searchTerm = trim($request->search);
                if (strlen($searchTerm) >= 3) { // Minimum 3 characters for search
                    $query->where('order_number', 'like', '%' . $searchTerm . '%');
                }
            }

            // **FIX: Date range filter with validation**
            if ($request->filled('from_date')) {
                $fromDate = $request->from_date;
                if (strtotime($fromDate)) {
                    $query->whereDate('created_at', '>=', $fromDate);
                }
            }
            if ($request->filled('to_date')) {
                $toDate = $request->to_date;
                if (strtotime($toDate)) {
                    $query->whereDate('created_at', '<=', $toDate);
                }
            }

            $orders = $query->orderBy('created_at', 'desc')
                ->paginate(10)
                ->withQueryString();

            // **FIX: Enhanced status counts with caching**
            $statusCounts = $this->getOrderStatusCounts();

            return view('user.orders.index', compact('orders', 'statusCounts'));

        } catch (\Exception $e) {
            Log::error('Orders index page error: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
                'filters' => $request->all(),
            ]);

            return redirect()->back()->with('error', 'Unable to load orders');
        }
    }

    /**
     * Display the specified order
     */
    public function show($orderNumber)
    {
        try {
            $order = Auth::user()->orders()
                ->with([
                    'orderItems.product',
                    'orderItems.pujaKit',
                    'payments' => function ($query) {
                        $query->orderBy('created_at', 'desc');
                    },
                ])
                ->where('order_number', $orderNumber)
                ->first();

            if (!$order) {
                Log::warning('Order not found', [
                    'order_number' => $orderNumber,
                    'user_id' => Auth::id(),
                ]);

                return redirect()->route('orders.index')
                    ->with('error', 'Order not found');
            }

            return view('user.orders.show', compact('order'));

        } catch (\Exception $e) {
            Log::error('Order show page error: ' . $e->getMessage(), [
                'order_number' => $orderNumber,
                'user_id' => Auth::id(),
            ]);

            return redirect()->route('orders.index')
                ->with('error', 'Unable to load order details');
        }
    }

    /**
     * Cancel an order
     */
    public function cancel(Request $request, $orderNumber)
    {
        $validator = Validator::make($request->all(), [
            'cancellation_reason' => 'required|string|max:500',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $order = Auth::user()->orders()
                ->with(['orderItems.product', 'orderItems.pujaKit', 'payments'])
                ->where('order_number', $orderNumber)
                ->first();

            if (!$order) {
                return redirect()->back()
                    ->with('error', 'Order not found');
            }

            // **FIX: Enhanced cancellation validation**
            if (!in_array($order->status, ['pending', 'processing'])) {
                Log::warning('Order cancellation attempt for invalid status', [
                    'order_number' => $orderNumber,
                    'current_status' => $order->status,
                    'user_id' => Auth::id(),
                ]);

                return redirect()->back()
                    ->with('error', 'This order cannot be cancelled. Current status: ' . ucfirst($order->status));
            }

            DB::beginTransaction();

            Log::info('Starting order cancellation', [
                'order_number' => $orderNumber,
                'user_id' => Auth::id(),
                'reason' => $request->cancellation_reason,
            ]);

            // Update order status
            $order->update([
                'status' => 'cancelled',
                'cancelled_at' => now(),
                'cancellation_reason' => $request->cancellation_reason,
                'cancelled_by' => 'customer',
            ]);

            // **FIX: Improved refund processing**
            if ($order->payment_status === 'paid') {
                $this->processRefund($order, $request->cancellation_reason);
            }

            // **FIX: Enhanced stock restoration with better error handling**
            $this->restoreStock($order);

            DB::commit();

            Log::info('Order cancellation completed', [
                'order_number' => $orderNumber,
                'user_id' => Auth::id(),
            ]);

            return redirect()->route('orders.show', $orderNumber)
                ->with('success', 'Order cancelled successfully. Refund will be processed within 5-7 business days.');

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Order cancellation failed', [
                'error' => $e->getMessage(),
                'order_number' => $orderNumber,
                'user_id' => Auth::id(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->back()
                ->with('error', 'Failed to cancel order. Please contact support.');
        }
    }

    /**
     * Request return for delivered order
     */
    public function requestReturn(Request $request, $orderNumber)
    {
        $validator = Validator::make($request->all(), [
            'return_reason' => 'required|string|max:500',
            'return_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $order = Auth::user()->orders()
                ->where('order_number', $orderNumber)
                ->first();

            if (!$order) {
                return redirect()->back()
                    ->with('error', 'Order not found');
            }

            // **FIX: Enhanced return eligibility validation**
            if ($order->status !== 'delivered' || !$order->delivered_at) {
                Log::warning('Return request for non-delivered order', [
                    'order_number' => $orderNumber,
                    'status' => $order->status,
                    'delivered_at' => $order->delivered_at,
                    'user_id' => Auth::id(),
                ]);

                return redirect()->back()
                    ->with('error', 'This order cannot be returned. Only delivered orders are eligible for returns.');
            }

            $daysSinceDelivery = now()->diffInDays($order->delivered_at);
            if ($daysSinceDelivery > 7) {
                return redirect()->back()
                    ->with('error', 'Return period has expired. Orders can only be returned within 7 days of delivery.');
            }

            // **FIX: Improved file upload handling with better validation**
            $images = [];
            if ($request->hasFile('return_images')) {
                foreach ($request->file('return_images') as $image) {
                    if ($image->isValid()) {
                        try {
                            $filename = 'return_' . $orderNumber . '_' . time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                            $path = $image->storeAs('returns', $filename, 'public');
                            $images[] = $path;
                        } catch (\Exception $e) {
                            Log::error('Failed to upload return image', [
                                'error' => $e->getMessage(),
                                'order_number' => $orderNumber,
                            ]);
                            // Continue with other images
                        }
                    }
                }
            }

            $order->update([
                'status' => 'return_requested',
                'return_reason' => $request->return_reason,
                'return_images' => json_encode($images), // **FIX: Properly encode as JSON**
                'return_requested_at' => now(),
            ]);

            Log::info('Return request submitted', [
                'order_number' => $orderNumber,
                'user_id' => Auth::id(),
                'images_count' => count($images),
            ]);

            return redirect()->route('orders.show', $orderNumber)
                ->with('success', 'Return request submitted successfully. Our team will review and contact you soon.');

        } catch (\Exception $e) {
            Log::error('Return request failed', [
                'error' => $e->getMessage(),
                'order_number' => $orderNumber,
                'user_id' => Auth::id(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->back()
                ->with('error', 'Failed to submit return request. Please try again.');
        }
    }

    /**
     * Download order invoice
     */
    public function downloadInvoice(string $orderNumber)
    {
        // **FIX: Enhanced authentication and validation**
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please login to view invoices.');
        }

        try {
            // Fetch order with better error handling
            $order = Auth::user()->orders()
                ->with(['orderItems.product', 'orderItems.pujaKit', 'payments'])
                ->where('order_number', $orderNumber)
                ->first();

            if (!$order) {
                Log::warning('Invoice download attempt for non-existent order', [
                    'order_number' => $orderNumber,
                    'user_id' => Auth::id(),
                ]);

                return redirect()->back()->with('error', 'Order not found.');
            }

            // **FIX: Allow invoices for both paid and COD orders**
            if (!in_array($order->payment_status, ['paid', 'completed'])) {
                return redirect()->back()->with('error', 'Invoice is not available for unpaid orders.');
            }

            // **FIX: Enhanced view existence check**
            if (!view()->exists('user.orders.invoice')) {
                Log::error('Invoice view missing: user.orders.invoice', [
                    'order_number' => $order->order_number
                ]);
                return redirect()->back()->with('error', 'Invoice template missing. Please contact support.');
            }

            // **FIX: Enhanced PDF generation with better error handling and configuration**
            $pdf = Pdf::setOptions([
                'defaultFont' => 'helvetica',
                'isRemoteEnabled' => true,
                'enable_html5_parser' => true,
                'enable_php' => false, // Security improvement
                'debugCss' => false,
                'debugKeepTemp' => false,
                'tempDir' => storage_path('app/temp'), // Ensure temp directory exists
            ])
            ->loadView('user.orders.invoice', compact('order'))
            ->setPaper('a4', 'portrait');

            // **FIX: Better filename with sanitization**
            $sanitizedOrderNumber = preg_replace('/[^a-zA-Z0-9-_]/', '', $order->order_number);
            $fileName = "invoice-{$sanitizedOrderNumber}-" . date('Y-m-d') . ".pdf";

            Log::info('Invoice generated successfully', [
                'order_number' => $order->order_number,
                'user_id' => Auth::id(),
                'filename' => $fileName,
            ]);

            return $pdf->download($fileName);

        } catch (\Throwable $e) {
            Log::error('Invoice generation failed', [
                'order_number' => $orderNumber,
                'user_id' => Auth::id(),
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->back()->with('error', 'Failed to generate invoice. Please try again or contact support.');
        }
    }

    /**
     * Track order status
     */
    public function track($orderNumber)
    {
        try {
            $order = Auth::user()->orders()
                ->where('order_number', $orderNumber)
                ->first();

            if (!$order) {
                return redirect()->route('orders.index')
                    ->with('error', 'Order not found');
            }

            $timeline = $this->getOrderTimeline($order);

            return view('user.orders.track', compact('order', 'timeline'));

        } catch (\Exception $e) {
            Log::error('Order tracking error: ' . $e->getMessage(), [
                'order_number' => $orderNumber,
                'user_id' => Auth::id(),
            ]);

            return redirect()->route('orders.index')
                ->with('error', 'Unable to load tracking information');
        }
    }

    /**
     * Retry payment for failed orders
     */
    public function retryPayment($orderNumber)
    {
        try {
            $order = Auth::user()->orders()
                ->where('order_number', $orderNumber)
                ->first();

            if (!$order) {
                return response()->json([
                    'success' => false,
                    'message' => 'Order not found.',
                ], 404);
            }

            // **FIX: Enhanced payment retry validation**
            if (!in_array($order->payment_status, ['pending', 'failed'])) {
                Log::warning('Payment retry attempt for invalid status', [
                    'order_number' => $orderNumber,
                    'payment_status' => $order->payment_status,
                    'user_id' => Auth::id(),
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Payment cannot be retried for this order. Current status: ' . ucfirst($order->payment_status),
                ], 400);
            }

            // **FIX: Check for existing pending payments**
            $existingPendingPayment = $order->payments()
                ->where('status', 'pending')
                ->where('created_at', '>', now()->subMinutes(10)) // Within last 10 minutes
                ->first();

            if ($existingPendingPayment) {
                return response()->json([
                    'success' => false,
                    'message' => 'A payment is already in progress. Please wait before retrying.',
                ], 429);
            }

            // Create new payment record
            $payment = Payment::create([
                'order_id' => $order->id,
                'user_id' => $order->user_id,
                'payment_id' => 'PAY-RETRY-' . strtoupper(uniqid()),
                'amount' => $order->total_amount,
                'currency' => $order->currency,
                'gateway' => 'cashfree',
                'method' => 'online',
                'status' => 'pending',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $cashfreeResponse = $this->createCashfreePayment($order, $payment);

            if ($cashfreeResponse['success']) {
                Log::info('Payment retry initiated', [
                    'order_number' => $orderNumber,
                    'payment_id' => $payment->payment_id,
                    'user_id' => Auth::id(),
                ]);

                return response()->json([
                    'success' => true,
                    'payment_session_id' => $cashfreeResponse['payment_session_id'],
                    'order_id' => $order->order_number,
                    'payment_id' => $payment->payment_id,
                ]);
            } else {
                // **FIX: Clean up failed payment record**
                $payment->update(['status' => 'failed']);

                return response()->json([
                    'success' => false,
                    'message' => 'Failed to create payment: ' . ($cashfreeResponse['message'] ?? 'Unknown error'),
                ], 500);
            }

        } catch (\Exception $e) {
            Log::error('Payment retry failed', [
                'error' => $e->getMessage(),
                'order_number' => $orderNumber,
                'user_id' => Auth::id(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to retry payment. Please try again.',
            ], 500);
        }
    }

    /**
     * **NEW: Enhanced stock restoration method**
     */
    private function restoreStock(Order $order)
    {
        foreach ($order->orderItems as $item) {
            try {
                if ($item->product_id && $item->product) {
                    // **FIX: Use DB transaction for stock updates**
                    $item->product->increment('stock_quantity', $item->quantity);
                    
                    Log::info('Stock restored for product', [
                        'product_id' => $item->product_id,
                        'quantity_restored' => $item->quantity,
                        'order_number' => $order->order_number,
                    ]);
                } elseif ($item->puja_kit_id && $item->pujaKit) {
                    // Restore stock for puja kit products
                    foreach ($item->pujaKit->products as $product) {
                        $requiredQuantity = $product->pivot->quantity * $item->quantity;
                        $product->increment('stock_quantity', $requiredQuantity);
                        
                        Log::info('Stock restored for puja kit product', [
                            'product_id' => $product->id,
                            'quantity_restored' => $requiredQuantity,
                            'puja_kit_id' => $item->puja_kit_id,
                            'order_number' => $order->order_number,
                        ]);
                    }
                }
            } catch (\Exception $e) {
                Log::error('Failed to restore stock for item', [
                    'item_id' => $item->id,
                    'error' => $e->getMessage(),
                    'order_number' => $order->order_number,
                ]);
                // Continue with other items
            }
        }
    }

    /**
     * **ENHANCED: Process refund for cancelled order**
     */
    private function processRefund(Order $order, $reason)
    {
        $successfulPayment = $order->payments()
            ->where('status', 'completed')
            ->orderBy('created_at', 'desc')
            ->first();

        if (!$successfulPayment) {
            Log::warning('No successful payment found for refund', [
                'order_number' => $order->order_number,
            ]);
            return false;
        }

        try {
            $refundId = 'REF-' . strtoupper(uniqid());

            Log::info('Processing refund', [
                'order_number' => $order->order_number,
                'refund_id' => $refundId,
                'amount' => $order->total_amount,
                'gateway_payment_id' => $successfulPayment->gateway_payment_id,
            ]);

            $refundResponse = $this->cashfreeService->refundPayment(
                $successfulPayment->gateway_payment_id,
                $order->total_amount,
                $refundId
            );

            if ($refundResponse && ($refundResponse['success'] ?? false)) {
                // Create refund payment record
                Payment::create([
                    'order_id' => $order->id,
                    'user_id' => $order->user_id,
                    'payment_id' => $refundId,
                    'amount' => -$order->total_amount, // Negative amount for refund
                    'currency' => $order->currency,
                    'gateway' => 'cashfree',
                    'method' => 'refund',
                    'status' => 'completed',
                    'gateway_payment_id' => $refundResponse['cf_refund_id'] ?? null,
                    'gateway_response' => $refundResponse,
                    'paid_at' => now(),
                    'notes' => "Refund for cancelled order: {$reason}",
                ]);

                $order->update(['payment_status' => 'refunded']);

                Log::info('Refund processed successfully', [
                    'order_number' => $order->order_number,
                    'refund_id' => $refundId,
                ]);

                return true;
            } else {
                Log::error('Refund failed', [
                    'order_number' => $order->order_number,
                    'refund_id' => $refundId,
                    'response' => $refundResponse,
                ]);
                return false;
            }

        } catch (\Exception $e) {
            Log::error('Refund processing failed', [
                'error' => $e->getMessage(),
                'order_number' => $order->order_number,
                'trace' => $e->getTraceAsString(),
            ]);
            return false;
        }
    }

    /**
     * **ENHANCED: Create Cashfree payment session with better error handling**
     */
    private function createCashfreePayment(Order $order, Payment $payment)
    {
        try {
            $user = $order->user;
            
            // **FIX: Better customer name handling**
            $customerName = $user->full_name 
                ?? ($user->first_name && $user->last_name ? $user->first_name . ' ' . $user->last_name : null)
                ?? $user->name 
                ?? 'Customer';

            $paymentData = [
                'order_id' => $order->order_number . '-retry-' . time(), // Unique order ID for retry
                'amount' => $order->total_amount,
                'currency' => 'INR',
                'customer_id' => 'customer_' . $user->id,
                'customer_name' => $customerName,
                'customer_email' => $user->email,
                'customer_phone' => $user->phone ?: '9999999999',
                'return_url' => url('/payment/success?order_id=' . $order->order_number),
                'order_note' => 'Payment retry for Order #' . $order->order_number,
            ];

            // **FIX: Better webhook URL handling**
            try {
                $webhookUrl = route('payment.webhook');
                $paymentData['webhook_url'] = $webhookUrl;
            } catch (\Exception $e) {
                Log::warning('Webhook route not available for payment retry: ' . $e->getMessage());
            }

            $response = $this->cashfreeService->createOrder($paymentData);

            if ($response && ($response['success'] ?? false)) {
                $cfOrderId = $response['cf_order_id'] 
                    ?? $response['full_response']['cf_order_id'] 
                    ?? $response['data']['cf_order_id']
                    ?? null;

                $paymentSessionId = $response['payment_session_id'] 
                    ?? $response['full_response']['payment_session_id'] 
                    ?? $response['data']['payment_session_id']
                    ?? null;

                if ($cfOrderId) {
                    $payment->update([
                        'gateway_order_id' => $cfOrderId,
                        'gateway_response' => $response,
                    ]);

                    return [
                        'success' => true,
                        'payment_session_id' => $paymentSessionId,
                        'cf_order_id' => $cfOrderId,
                    ];
                }
            }

            return [
                'success' => false,
                'message' => $response['error'] ?? 'Failed to create Cashfree payment session',
            ];

        } catch (\Exception $e) {
            Log::error('Cashfree payment creation failed', [
                'error' => $e->getMessage(),
                'order_number' => $order->order_number,
                'trace' => $e->getTraceAsString(),
            ]);

            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    /**
     * **ENHANCED: Get order timeline for tracking**
     */
    private function getOrderTimeline(Order $order)
    {
        $timeline = [];

        // Order placed
        $timeline[] = [
            'status' => 'placed',
            'title' => 'Order Placed',
            'description' => 'Your order has been placed successfully.',
            'timestamp' => $order->created_at,
            'completed' => true,
            'icon' => 'shopping-cart',
            'color' => 'success',
        ];

        // Payment
        if ($order->payment_status === 'paid') {
            $paymentTimestamp = $order->payments()
                ->where('status', 'completed')
                ->orderBy('created_at', 'desc')
                ->first()?->paid_at;

            $timeline[] = [
                'status' => 'paid',
                'title' => 'Payment Confirmed',
                'description' => 'Your payment has been confirmed.',
                'timestamp' => $paymentTimestamp,
                'completed' => true,
                'icon' => 'credit-card',
                'color' => 'success',
            ];
        }

        // Processing
        $timeline[] = [
            'status' => 'processing',
            'title' => 'Order Processing',
            'description' => 'Your order is being prepared for shipment.',
            'timestamp' => in_array($order->status, ['processing', 'shipped', 'delivered']) ? $order->updated_at : null,
            'completed' => in_array($order->status, ['processing', 'shipped', 'delivered']),
            'icon' => 'package',
            'color' => in_array($order->status, ['processing', 'shipped', 'delivered']) ? 'success' : 'pending',
        ];

        // Shipped
        $timeline[] = [
            'status' => 'shipped',
            'title' => 'Order Shipped',
            'description' => 'Your order has been shipped and is on the way.',
            'timestamp' => $order->shipped_at,
            'completed' => in_array($order->status, ['shipped', 'delivered']),
            'icon' => 'truck',
            'color' => in_array($order->status, ['shipped', 'delivered']) ? 'success' : 'pending',
        ];

        // Delivered
        $timeline[] = [
            'status' => 'delivered',
            'title' => 'Order Delivered',
            'description' => 'Your order has been delivered successfully.',
            'timestamp' => $order->delivered_at,
            'completed' => $order->status === 'delivered',
            'icon' => 'check-circle',
            'color' => $order->status === 'delivered' ? 'success' : 'pending',
        ];

        // **FIX: Handle cancelled orders**
        if ($order->status === 'cancelled') {
            $timeline[] = [
                'status' => 'cancelled',
                'title' => 'Order Cancelled',
                'description' => 'Your order has been cancelled. ' . ($order->cancellation_reason ?? ''),
                'timestamp' => $order->cancelled_at,
                'completed' => true,
                'icon' => 'x-circle',
                'color' => 'danger',
            ];
        }

        // **FIX: Handle return requests**
        if ($order->status === 'return_requested') {
            $timeline[] = [
                'status' => 'return_requested',
                'title' => 'Return Requested',
                'description' => 'Return request submitted and under review.',
                'timestamp' => $order->return_requested_at,
                'completed' => true,
                'icon' => 'arrow-left',
                'color' => 'warning',
            ];
        }

        return $timeline;
    }

    /**
     * **NEW: Get cached order status counts**
     */
    private function getOrderStatusCounts()
    {
        $userId = Auth::id();
        
        return cache()->remember("order_status_counts_{$userId}", 300, function () {
            $baseQuery = Auth::user()->orders();
            
            return [
                'all' => $baseQuery->count(),
                'pending' => $baseQuery->where('status', 'pending')->count(),
                'processing' => $baseQuery->where('status', 'processing')->count(),
                'shipped' => $baseQuery->where('status', 'shipped')->count(),
                'delivered' => $baseQuery->where('status', 'delivered')->count(),
                'cancelled' => $baseQuery->where('status', 'cancelled')->count(),
                'return_requested' => $baseQuery->where('status', 'return_requested')->count(),
            ];
        });
    }
}
