<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use App\Models\Cart;
use App\Services\CashfreeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    private $cashfreeService;

    public function __construct(CashfreeService $cashfreeService)
    {
        $this->cashfreeService = $cashfreeService;
    }

    public function process(Request $request)
    {
        try {
            $orderNumber = $request->order_id ?? $request->input('order_id');
            
            Log::info('Payment process initiated', [
                'order_number' => $orderNumber,
                'request_data' => $request->all()
            ]);
            
            if (!$orderNumber) {
                Log::warning('No order ID provided in payment process');
                return redirect()->route('home')->with('error', 'Invalid payment request');
            }

            $order = Order::with(['orderItems', 'payments'])
                ->where('order_number', $orderNumber)
                ->first();
            
            if (!$order) {
                Log::warning('Order not found for payment processing', ['order_number' => $orderNumber]);
                return redirect()->route('home')->with('error', 'Order not found');
            }

            Log::info('Order found for payment processing', [
                'order_number' => $order->order_number,
                'order_total' => $order->total_amount,
                'order_status' => $order->status,
                'payment_status' => $order->payment_status
            ]);

            if ($order->payment_status === 'paid') {
                Log::info('Order already paid, redirecting to success', ['order_number' => $orderNumber]);
                return redirect()->route('checkout.success', $order->order_number)
                    ->with('success', 'Order already completed successfully');
            }

            $payment = $order->payments()
                ->where('status', 'pending')
                ->latest()
                ->first();
            
            if (!$payment) {
                Log::warning('No pending payment found for order', ['order_number' => $orderNumber]);
                return redirect()->route('home')->with('error', 'Payment session not found');
            }

            if (!$payment->gateway_response || !isset($payment->gateway_response['payment_session_id'])) {
                Log::warning('Invalid payment gateway response', [
                    'order_number' => $orderNumber,
                    'payment_id' => $payment->id
                ]);
                return redirect()->route('home')->with('error', 'Payment session invalid');
            }

            $cashfreeData = [
                'payment_session_id' => $payment->gateway_response['payment_session_id'],
                'cf_order_id' => $payment->gateway_response['cf_order_id'] ?? null,
                'order_id' => $order->order_number,
                'amount' => $order->total_amount,
                'currency' => $order->currency ?? 'INR',
                'customer_name' => Auth::user()->full_name ?? Auth::user()->name ?? 'Customer',
                'customer_email' => Auth::user()->email,
                'customer_phone' => Auth::user()->phone ?? '9999999999',
            ];

            Log::info('Cashfree data prepared for payment view', [
                'order_number' => $order->order_number,
                'amount' => $cashfreeData['amount'],
                'payment_session_id_length' => strlen($cashfreeData['payment_session_id']),
                'has_cf_order_id' => !is_null($cashfreeData['cf_order_id'])
            ]);
            
            return view('payment.cashfree', compact('order', 'payment', 'cashfreeData'));
            
        } catch (\Exception $e) {
            Log::error('Payment process error: ' . $e->getMessage(), [
                'order_number' => $orderNumber ?? 'unknown',
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->route('home')->with('error', 'Payment processing failed');
        }
    }

    public function success(Request $request)
    {
        try {
            $orderNumber = $request->query('order_id');
            
            Log::info('=== PAYMENT SUCCESS CALLBACK ===', [
                'order_number' => $orderNumber,
                'query_params' => $request->query(),
                'all_params' => $request->all()
            ]);
            
            if (!$orderNumber) {
                Log::warning('Payment success callback: Missing order_id');
                return redirect()->route('home')->with('error', 'Invalid payment response');
            }

            $order = Order::with(['orderItems', 'payments'])
                ->where('order_number', $orderNumber)
                ->first();
            
            if (!$order) {
                Log::warning('Payment success callback: Order not found', ['order_number' => $orderNumber]);
                return redirect()->route('home')->with('error', 'Order not found');
            }

            Log::info('Order found in success callback', [
                'order_number' => $order->order_number,
                'current_payment_status' => $order->payment_status,
                'current_order_status' => $order->status
            ]);

            // Check if already processed
            if ($order->payment_status === 'paid') {
                Log::info('Order already marked as paid, redirecting to success');
                return redirect()->route('checkout.success', $order->order_number)
                    ->with('success', 'Payment already processed successfully');
            }

            $payment = $order->payments()
                ->where('status', 'pending')
                ->whereNotNull('gateway_order_id')
                ->latest()
                ->first();
            
            Log::info('Payment lookup result', [
                'payment_found' => !is_null($payment),
                'payment_id' => $payment ? $payment->id : null,
                'gateway_order_id' => $payment ? $payment->gateway_order_id : null
            ]);
            
            if (!$payment) {
                Log::warning('No valid payment with gateway_order_id found', [
                    'order_number' => $orderNumber
                ]);
                
                return redirect()->route('orders.show', $order->order_number)
                    ->with('error', 'Payment verification failed. Please contact support if amount was deducted.');
            }

            Log::info('Payment record found, verifying with Cashfree', [
                'payment_id' => $payment->id,
                'gateway_order_id' => $payment->gateway_order_id
            ]);

            // **DEVELOPMENT MODE: Skip Cashfree verification for testing**
            if (config('app.env') === 'local' && $request->has('test_success')) {
                Log::info('DEVELOPMENT MODE: Skipping Cashfree verification, marking as paid');
                
                DB::beginTransaction();
                
                try {
                    // Update payment record
                    $payment->update([
                        'status' => 'completed',
                        'gateway_transaction_id' => 'TEST_' . uniqid(),
                        'paid_at' => now(),
                    ]);
                    
                    // Update order status
                    $order->update([
                        'status' => 'processing',
                        'payment_status' => 'paid'
                    ]);

                    Log::info('Order status updated in test mode', [
                        'order_number' => $order->order_number,
                        'new_status' => 'processing',
                        'new_payment_status' => 'paid'
                    ]);

                    // Clear cart
                    if (Auth::check()) {
                        Cart::clearCart();
                        Log::info('Cart cleared for user', ['user_id' => Auth::id()]);
                    }
                    
                    DB::commit();

                    Log::info('=== TEST PAYMENT SUCCESSFULLY PROCESSED ===', [
                        'order_number' => $orderNumber,
                    ]);

                    return redirect()->route('checkout.success', $order->order_number)
                        ->with('success', 'Payment successful! Your order has been placed.');
                        
                } catch (\Exception $e) {
                    DB::rollback();
                    Log::error('Error updating test payment status: ' . $e->getMessage());
                    throw $e;
                }
            }

            // **PRODUCTION MODE: Verify with Cashfree API**
            $cashfreeResponse = $this->cashfreeService->getOrderDetails($payment->gateway_order_id);
            
            Log::info('Cashfree verification response', [
                'order_number' => $orderNumber,
                'cf_order_id' => $payment->gateway_order_id,
                'verification_success' => $cashfreeResponse['success'],
                'response_data' => $cashfreeResponse
            ]);

            if ($cashfreeResponse['success']) {
                $orderDetails = $cashfreeResponse['order_details'];
                $orderStatus = $orderDetails['order_status'] ?? 'UNKNOWN';
                
                Log::info('Cashfree order status', [
                    'order_number' => $orderNumber,
                    'cf_order_status' => $orderStatus,
                    'order_amount' => $orderDetails['order_amount'] ?? 'unknown'
                ]);

                if ($orderStatus === 'PAID') {
                    Log::info('Payment confirmed as PAID by Cashfree, updating database');
                    
                    DB::beginTransaction();
                    
                    try {
                        $paymentDetails = $orderDetails['payments'][0] ?? [];
                        $cfPaymentId = $paymentDetails['cf_payment_id'] ?? null;
                        
                        $payment->update([
                            'status' => 'completed',
                            'gateway_transaction_id' => $cfPaymentId,
                            'gateway_response' => array_merge($payment->gateway_response ?? [], $orderDetails),
                            'paid_at' => now(),
                        ]);
                        
                        $order->update([
                            'status' => 'processing',
                            'payment_status' => 'paid'
                        ]);

                        if (Auth::check()) {
                            Cart::clearCart();
                        }
                        
                        DB::commit();

                        return redirect()->route('checkout.success', $order->order_number)
                            ->with('success', 'Payment successful! Your order has been placed.');
                            
                    } catch (\Exception $e) {
                        DB::rollback();
                        Log::error('Error updating payment status: ' . $e->getMessage());
                        throw $e;
                    }
                } else {
                    Log::warning('Payment not successful according to Cashfree', [
                        'order_number' => $orderNumber,
                        'cf_order_status' => $orderStatus
                    ]);
                    
                    return redirect()->route('orders.show', $order->order_number)
                        ->with('error', 'Payment failed. Status: ' . $orderStatus);
                }
            } else {
                Log::error('Cashfree API verification failed', [
                    'order_number' => $orderNumber,
                    'error' => $cashfreeResponse['error'] ?? 'Unknown error'
                ]);
                
                return redirect()->route('orders.show', $order->order_number)
                    ->with('error', 'Payment verification failed. Please contact support if amount was deducted.');
            }

        } catch (\Exception $e) {
            Log::error('Payment success callback error: ' . $e->getMessage(), [
                'order_id' => $orderNumber ?? 'unknown',
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->route('home')
                ->with('error', 'Payment verification failed. Please contact support.');
        }
    }

    public function webhook(Request $request)
    {
        try {
            $rawBody = $request->getContent();
            Log::info('=== CASHFREE WEBHOOK RECEIVED ===', [
                'body_length' => strlen($rawBody)
            ]);

            $webhookData = json_decode($rawBody, true);
            
            if (!$webhookData || !isset($webhookData['data'])) {
                Log::warning('Invalid webhook data received');
                return response()->json(['status' => 'error', 'message' => 'Invalid data'], 400);
            }

            Log::info('Processing Cashfree webhook', [
                'event_type' => $webhookData['type'] ?? 'unknown'
            ]);

            return response()->json(['status' => 'ok']);

        } catch (\Exception $e) {
            Log::error('Cashfree webhook processing error: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => 'Webhook processing failed'], 500);
        }
    }
}
