@extends('layouts.app')

@section('title', 'Complete Payment - Ritiyana')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-lg shadow-sm p-8">
            <div class="text-center mb-6">
                <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i data-lucide="credit-card" class="w-8 h-8 text-blue-600"></i>
                </div>
                <h1 class="text-2xl font-bold text-gray-900 mb-2">Complete Your Payment</h1>
                <p class="text-gray-600">Secure payment powered by Cashfree</p>
            </div>

            <!-- Debug Information (remove in production) -->
            @if(config('app.debug'))
            <div class="bg-gray-100 rounded-lg p-4 mb-6 text-sm">
                <strong>Debug Info:</strong><br>
                Order Amount: ₹{{ number_format($order->total_amount, 2) }}<br>
                Cashfree Amount: ₹{{ number_format($cashfreeData['amount'], 2) }}<br>
                Payment Session ID: {{ substr($cashfreeData['payment_session_id'] ?? 'Not found', 0, 20) }}...<br>
                Order Items Count: {{ $order->orderItems->count() }}<br>
                Order Items Total: ₹{{ number_format($order->orderItems->sum('total'), 2) }}
            </div>
            @endif

            <!-- Order Summary -->
            <div class="bg-gray-50 rounded-lg p-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Order Summary</h3>
                
                <!-- Order Details -->
                <div class="space-y-3 mb-4">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Order Number:</span>
                        <span class="font-semibold">{{ $order->order_number }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Subtotal:</span>
                        <span>₹{{ number_format($order->subtotal, 2) }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Tax (18% GST):</span>
                        <span>₹{{ number_format($order->tax_amount, 2) }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Shipping:</span>
                        <span>
                            @if($order->shipping_amount > 0)
                                ₹{{ number_format($order->shipping_amount, 2) }}
                            @else
                                <span class="text-green-600">Free</span>
                            @endif
                        </span>
                    </div>
                    @if($order->discount_amount > 0)
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Discount:</span>
                        <span class="text-green-600">-₹{{ number_format($order->discount_amount, 2) }}</span>
                    </div>
                    @endif
                </div>
                
                <!-- Total -->
                <div class="border-t pt-3">
                    <div class="flex justify-between items-center">
                        <span class="text-lg font-semibold text-gray-900">Total Amount:</span>
                        <span class="text-2xl font-bold text-vibrant-pink">₹{{ number_format($order->total_amount, 2) }}</span>
                    </div>
                </div>
                
                <!-- Customer Info -->
                <div class="mt-4 pt-4 border-t">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Customer:</span>
                        <span class="font-medium">{{ Auth::user()->full_name ?? Auth::user()->name }}</span>
                    </div>
                </div>
            </div>

            @if(isset($cashfreeData['payment_session_id']) && $cashfreeData['payment_session_id'])
            <!-- Payment Button -->
            <div class="text-center">
                <button id="pay-button" 
                        class="w-full bg-vibrant-pink text-white py-4 px-8 rounded-lg hover:bg-vibrant-pink-dark transition-colors text-lg font-semibold">
                    <i data-lucide="lock" class="w-5 h-5 inline mr-2"></i>
                    Pay ₹{{ number_format($order->total_amount, 2) }} Securely
                </button>
                
                <p class="text-xs text-gray-500 mt-4">
                    Your payment information is encrypted and secure
                </p>
            </div>

            <!-- Loading State -->
            <div id="payment-loading" class="hidden text-center py-8">
                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-vibrant-pink mx-auto mb-4"></div>
                <p class="text-gray-600">Processing payment...</p>
            </div>

            <!-- Error State -->
            <div id="payment-error" class="hidden bg-red-50 border border-red-200 rounded-lg p-4 mt-6">
                <div class="flex">
                    <i data-lucide="x-circle" class="w-5 h-5 text-red-400 mr-2 mt-0.5"></i>
                    <div>
                        <h3 class="text-sm font-medium text-red-800">Payment Failed</h3>
                        <p id="error-message" class="text-sm text-red-700 mt-1"></p>
                        <button onclick="retryPayment()" class="text-sm text-red-600 hover:text-red-800 font-medium mt-2">
                            Try Again
                        </button>
                    </div>
                </div>
            </div>

            <!-- SDK Loading Status -->
            <div id="sdk-status" class="text-center text-sm text-gray-500 mt-4">
                Loading payment gateway...
            </div>
            @else
            <!-- Error State -->
            <div class="bg-red-50 border border-red-200 rounded-lg p-6 text-center">
                <i data-lucide="x-circle" class="w-8 h-8 text-red-500 mx-auto mb-4"></i>
                <h3 class="text-lg font-semibold text-red-800 mb-2">Payment Session Error</h3>
                <p class="text-red-700 mb-4">Unable to initialize payment session. Payment session ID is missing.</p>
                <div class="space-y-2">
                    <a href="{{ route('checkout.index') }}" 
                       class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 mr-2">
                        <i data-lucide="arrow-left" class="w-4 h-4 mr-2"></i>
                        Back to Checkout
                    </a>
                    <a href="{{ route('orders.show', $order->order_number) }}" 
                       class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700">
                        <i data-lucide="eye" class="w-4 h-4 mr-2"></i>
                        View Order
                    </a>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

@if(isset($cashfreeData['payment_session_id']) && $cashfreeData['payment_session_id'])
<!-- Load Cashfree SDK -->
<script src="https://sdk.cashfree.com/js/v3/cashfree.js" id="cashfree-sdk"></script>

<script>
let cashfreeInstance = null;
let sdkLoaded = false;

// Configuration with correct order total
const CASHFREE_CONFIG = {
    mode: '{{ config("cashfree.mode", "sandbox") }}',
    paymentSessionId: '{{ $cashfreeData["payment_session_id"] }}',
    orderNumber: '{{ $order->order_number }}',
    amount: {{ $order->total_amount }}, // Use order's total amount
    displayAmount: '₹{{ number_format($order->total_amount, 2) }}' // Formatted display
};

function updateSDKStatus(message, isError = false) {
    const statusDiv = document.getElementById('sdk-status');
    if (statusDiv) {
        statusDiv.textContent = message;
        statusDiv.className = `text-center text-sm mt-4 ${isError ? 'text-red-600' : 'text-gray-500'}`;
    }
}

function initializeCashfree() {
    console.log('Initializing Cashfree with config:', CASHFREE_CONFIG);
    
    try {
        if (typeof Cashfree === 'undefined') {
            throw new Error('Cashfree SDK not loaded');
        }
        
        cashfreeInstance = Cashfree({
            mode: CASHFREE_CONFIG.mode
        });
        
        sdkLoaded = true;
        updateSDKStatus('Payment gateway ready');
        console.log('Cashfree SDK initialized successfully');
        
    } catch (error) {
        console.error('Failed to initialize Cashfree:', error);
        updateSDKStatus('Failed to load payment gateway', true);
        showError('Payment gateway initialization failed: ' + error.message);
    }
}

function initiatePayment() {
    console.log('Initiating payment for amount:', CASHFREE_CONFIG.displayAmount);
    
    if (!sdkLoaded || !cashfreeInstance) {
        showError('Payment gateway not ready. Please refresh and try again.');
        return;
    }

    const payButton = document.getElementById('pay-button');
    const loadingDiv = document.getElementById('payment-loading');
    const errorDiv = document.getElementById('payment-error');

    try {
        // Show loading state
        payButton.style.display = 'none';
        loadingDiv.classList.remove('hidden');
        errorDiv.classList.add('hidden');
        
        const checkoutOptions = {
            paymentSessionId: CASHFREE_CONFIG.paymentSessionId,
            redirectTarget: '_self'
        };

        console.log('Starting Cashfree checkout with options:', checkoutOptions);
        console.log('Expected payment amount:', CASHFREE_CONFIG.displayAmount);
        
        cashfreeInstance.checkout(checkoutOptions).then(function(result) {
            console.log('Cashfree checkout result:', result);
            
            if (result.error) {
                console.error('Payment failed:', result.error);
                showError('Payment failed: ' + result.error.message);
                
            } else if (result.redirect) {
                console.log('Payment successful, redirecting...');
                updateSDKStatus('Payment completed successfully!');
                // Cashfree will handle the redirect automatically
                
            } else if (result.paymentDetails) {
                console.log('Payment completed:', result.paymentDetails);
                updateSDKStatus('Payment completed! Redirecting...');
                window.location.href = '{{ route("payment.success", ["order_id" => $order->order_number]) }}';
                
            } else {
                console.log('Unexpected payment result:', result);
                updateSDKStatus('Processing payment result...');
                // Handle other cases - usually redirect happens automatically
            }
            
        }).catch(function(error) {
            console.error('Cashfree checkout error:', error);
            showError('Payment processing failed. Please try again.');
        });
        
    } catch (error) {
        console.error('Payment initiation error:', error);
        showError('Failed to start payment: ' + error.message);
    }
}

function showError(message) {
    const payButton = document.getElementById('pay-button');
    const loadingDiv = document.getElementById('payment-loading');
    const errorDiv = document.getElementById('payment-error');
    const errorMessage = document.getElementById('error-message');
    
    if (payButton) payButton.style.display = 'block';
    if (loadingDiv) loadingDiv.classList.add('hidden');
    if (errorMessage) errorMessage.textContent = message;
    if (errorDiv) errorDiv.classList.remove('hidden');
    
    updateSDKStatus('Payment failed', true);
}

function retryPayment() {
    const errorDiv = document.getElementById('payment-error');
    if (errorDiv) errorDiv.classList.add('hidden');
    
    if (sdkLoaded) {
        initiatePayment();
    } else {
        initializeCashfree();
        setTimeout(initiatePayment, 1000);
    }
}

// SDK Loading Detection
function checkSDKLoaded() {
    if (typeof Cashfree !== 'undefined') {
        updateSDKStatus('SDK loaded, initializing...');
        initializeCashfree();
    } else {
        console.log('Cashfree SDK still loading...');
        setTimeout(checkSDKLoaded, 100);
    }
}

// Main initialization
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM loaded, payment amount:', CASHFREE_CONFIG.displayAmount);
    updateSDKStatus('Loading payment gateway SDK...');
    
    // Start SDK loading check
    setTimeout(checkSDKLoaded, 500);

    // Add click handler
    const payButton = document.getElementById('pay-button');
    if (payButton) {
        payButton.addEventListener('click', function() {
            console.log('Pay button clicked for amount:', CASHFREE_CONFIG.displayAmount);
            initiatePayment();
        });
    }
});

// Global retry function
window.retryPayment = retryPayment;

// Debug info
console.log('Payment page loaded with correct config:', CASHFREE_CONFIG);
console.log('Order total amount from server:', {{ $order->total_amount }});
</script>
@endif
@endsection
