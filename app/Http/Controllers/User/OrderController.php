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
        $query = Auth::user()->orders()->with(['orderItems.product',
            'orderItems.pujaKit', // Load pujaKit relationship
            'orderItems.vendor', 'payments']);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by payment status
        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        // Search by order number
        if ($request->filled('search')) {
            $query->where('order_number', 'like', '%'.$request->search.'%');
        }

        // Date range filter
        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }
        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        $orders = $query->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        // Get status counts for filter tabs
        $statusCounts = [
            'all' => Auth::user()->orders()->count(),
            'pending' => Auth::user()->orders()->where('status', 'pending')->count(),
            'processing' => Auth::user()->orders()->where('status', 'processing')->count(),
            'shipped' => Auth::user()->orders()->where('status', 'shipped')->count(),
            'delivered' => Auth::user()->orders()->where('status', 'delivered')->count(),
            'cancelled' => Auth::user()->orders()->where('status', 'cancelled')->count(),
        ];

        return view('user.orders.index', compact('orders', 'statusCounts'));
    }

    /**
     * Display the specified order
     */
    public function show($orderNumber)
    {
        $order = Auth::user()->orders()
            ->with([
                'orderItems.product',
                'orderItems.pujaKit',
                'orderItems.pujaKit',
                'payments' => function ($query) {
                    $query->orderBy('created_at', 'desc');
                },
            ])
            ->where('order_number', $orderNumber)
            ->firstOrFail();

        return view('user.orders.show', compact('order'));
    }

    /**
     * Cancel an order
     */
    public function cancel(Request $request, $orderNumber)
    {
        $order = Auth::user()->orders()
            ->where('order_number', $orderNumber)
            ->firstOrFail();

        // Check if order can be cancelled
        if (! in_array($order->status, ['pending', 'processing'])) {
            return redirect()->back()
                ->with('error', 'This order cannot be cancelled.');
        }

        $validator = Validator::make($request->all(), [
            'cancellation_reason' => 'required|string|max:500',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            DB::beginTransaction();

            // Update order status
            $order->update([
                'status' => 'cancelled',
                'cancelled_at' => now(),
                'cancellation_reason' => $request->cancellation_reason,
                'cancelled_by' => 'customer',
            ]);

            // Process refund if payment was made
            if ($order->payment_status === 'paid') {
                $this->processRefund($order, $request->cancellation_reason);
            }

            // Restore stock for products (if needed)
            foreach ($order->orderItems as $item) {
                if ($item->product_id && $item->product) {
                    $item->product->increment('stock_quantity', $item->quantity);
                } elseif ($item->puja_kit_id && $item->pujaKit) {
                    // Restore stock for puja kit products
                    foreach ($item->pujaKit->products as $product) {
                        $requiredQuantity = $product->pivot->quantity * $item->quantity;
                        $product->increment('stock_quantity', $requiredQuantity);
                    }
                }
            }

            DB::commit();

            return redirect()->route('orders.show', $orderNumber)
                ->with('success', 'Order cancelled successfully. Refund will be processed within 5-7 business days.');

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Order cancellation failed: '.$e->getMessage());

            return redirect()->back()
                ->with('error', 'Failed to cancel order. Please contact support.');
        }
    }

    /**
     * Request return for delivered order
     */
    public function requestReturn(Request $request, $orderNumber)
    {
        $order = Auth::user()->orders()
            ->where('order_number', $orderNumber)
            ->firstOrFail();

        // Check if order can be returned (within 7 days of delivery)
        if ($order->status !== 'delivered' || ! $order->delivered_at) {
            return redirect()->back()
                ->with('error', 'This order cannot be returned.');
        }

        $daysSinceDelivery = now()->diffInDays($order->delivered_at);
        if ($daysSinceDelivery > 7) {
            return redirect()->back()
                ->with('error', 'Return period has expired. Orders can only be returned within 7 days of delivery.');
        }

        $validator = Validator::make($request->all(), [
            'return_reason' => 'required|string|max:500',
            'return_images.*' => 'nullable|image|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            // Handle file uploads
            $images = [];
            if ($request->hasFile('return_images')) {
                foreach ($request->file('return_images') as $image) {
                    $path = $image->store('returns', 'public');
                    $images[] = $path;
                }
            }

            $order->update([
                'status' => 'return_requested',
                'return_reason' => $request->return_reason,
                'return_images' => $images,
                'return_requested_at' => now(),
            ]);

            return redirect()->route('orders.show', $orderNumber)
                ->with('success', 'Return request submitted successfully. Our team will review and contact you soon.');

        } catch (\Exception $e) {
            Log::error('Return request failed: '.$e->getMessage());

            return redirect()->back()
                ->with('error', 'Failed to submit return request. Please try again.');
        }
    }

    /**
     * Download order invoice
     */
    public function downloadInvoice($orderNumber)
    {
        $order = Auth::user()->orders()
            ->with(['orderItems.product', 'orderItems.pujaKit', 'payments'])
            ->where('order_number', $orderNumber)
            ->firstOrFail();

        // Only allow invoice download for paid orders
        if ($order->payment_status !== 'paid') {
            return redirect()->back()->with('error', 'Invoice is not available for unpaid orders.');
        }

        try {
            // **FIXED: Use correct Pdf facade**
            $pdf = Pdf::loadView('user.orders.invoice', compact('order'));

            return $pdf->download("invoice-{$order->order_number}.pdf");

        } catch (\Exception $e) {
            Log::error('Invoice generation failed: '.$e->getMessage());

            return redirect()->back()->with('error', 'Failed to generate invoice. Please try again.');
        }
    }

    /**
     * Track order status
     */
    public function track($orderNumber)
    {
        $order = Auth::user()->orders()
            ->where('order_number', $orderNumber)
            ->firstOrFail();

        $timeline = $this->getOrderTimeline($order);

        return view('user.orders.track', compact('order', 'timeline'));
    }

    /**
     * Retry payment for failed orders
     */
    public function retryPayment($orderNumber)
    {
        $order = Auth::user()->orders()
            ->where('order_number', $orderNumber)
            ->firstOrFail();

        // Check if payment can be retried
        if ($order->payment_status !== 'pending' && $order->payment_status !== 'failed') {
            return redirect()->back()
                ->with('error', 'Payment cannot be retried for this order.');
        }

        try {
            // Create new payment record
            $payment = Payment::create([
                'order_id' => $order->id,
                'user_id' => $order->user_id,
                'payment_id' => 'PAY-'.strtoupper(uniqid()),
                'amount' => $order->total_amount,
                'currency' => $order->currency,
                'gateway' => 'cashfree',
                'status' => 'pending',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $cashfreeResponse = $this->createCashfreePayment($order, $payment);

            if ($cashfreeResponse['success']) {
                return response()->json([
                    'success' => true,
                    'payment_session_id' => $cashfreeResponse['payment_session_id'],
                    'order_id' => $order->order_number,
                    'payment_id' => $payment->payment_id,
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to create payment: '.$cashfreeResponse['message'],
                ], 500);
            }

        } catch (\Exception $e) {
            Log::error('Payment retry failed: '.$e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to retry payment. Please try again.',
            ], 500);
        }
    }

    /**
     * Process refund for cancelled order
     */
    private function processRefund(Order $order, $reason)
    {
        $successfulPayment = $order->payments()
            ->where('status', 'completed')
            ->first();

        if (! $successfulPayment) {
            return;
        }

        try {
            $refundId = 'REF-'.strtoupper(uniqid());

            $refundResponse = $this->cashfreeService->refundPayment(
                $successfulPayment->gateway_payment_id,
                $order->total_amount,
                $refundId
            );

            if ($refundResponse) {
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
            }

        } catch (\Exception $e) {
            Log::error('Refund processing failed: '.$e->getMessage());
            // Don't throw exception as order cancellation should still proceed
        }
    }

    /**
     * Create Cashfree payment session
     */
    private function createCashfreePayment(Order $order, Payment $payment)
    {
        try {
            $paymentData = [
                'order_id' => $order->order_number.'-'.time(), // Unique order ID for retry
                'order_amount' => $order->total_amount,
                'order_currency' => 'INR',
                'customer_details' => [
                    'customer_id' => $order->user->id,
                    'customer_name' => $order->user->full_name,
                    'customer_email' => $order->user->email,
                    'customer_phone' => $order->user->phone ?: '9999999999',
                ],
                'order_meta' => [
                    'return_url' => route('payment.success'),
                    'notify_url' => route('payment.webhook'),
                ],
            ];

            $response = $this->cashfreeService->createPaymentSession($paymentData);

            if ($response) {
                $payment->update([
                    'gateway_payment_id' => $response['order_id'],
                    'gateway_response' => $response,
                ]);

                return [
                    'success' => true,
                    'payment_session_id' => $response['payment_session_id'],
                ];
            }

            return [
                'success' => false,
                'message' => 'Failed to create Cashfree payment session',
            ];

        } catch (\Exception $e) {
            Log::error('Cashfree payment creation failed: '.$e->getMessage());

            return [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    /**
     * Get order timeline for tracking
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
        ];

        // Payment
        if ($order->payment_status === 'paid') {
            $timeline[] = [
                'status' => 'paid',
                'title' => 'Payment Confirmed',
                'description' => 'Your payment has been confirmed.',
                'timestamp' => $order->payments()->where('status', 'completed')->first()?->paid_at,
                'completed' => true,
                'icon' => 'credit-card',
            ];
        }

        // Processing
        $timeline[] = [
            'status' => 'processing',
            'title' => 'Order Processing',
            'description' => 'Your order is being prepared for shipment.',
            'timestamp' => $order->status === 'processing' ? $order->updated_at : null,
            'completed' => in_array($order->status, ['processing', 'shipped', 'delivered']),
            'icon' => 'package',
        ];

        // Shipped
        $timeline[] = [
            'status' => 'shipped',
            'title' => 'Order Shipped',
            'description' => 'Your order has been shipped and is on the way.',
            'timestamp' => $order->shipped_at,
            'completed' => in_array($order->status, ['shipped', 'delivered']),
            'icon' => 'truck',
        ];

        // Delivered
        $timeline[] = [
            'status' => 'delivered',
            'title' => 'Order Delivered',
            'description' => 'Your order has been delivered successfully.',
            'timestamp' => $order->delivered_at,
            'completed' => $order->status === 'delivered',
            'icon' => 'check-circle',
        ];

        return $timeline;
    }
}
