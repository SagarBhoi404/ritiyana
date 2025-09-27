@extends('layouts.app')

@section('title', 'Complete Payment - Shree Samagri')

@section('head')
<!-- Cashfree Checkout SDK -->
<script src="https://sdk.cashfree.com/js/v3/cashfree.js"></script>
@endsection

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

            <!-- Order Summary -->
            <div class="bg-gray-50 rounded-lg p-6 mb-6">
                <div class="flex justify-between items-center mb-2">
                    <span class="text-gray-600">Order Number:</span>
                    <span class="font-semibold">{{ $order->order_number }}</span>
                </div>
                <div class="flex justify-between items-center mb-2">
                    <span class="text-gray-600">Amount:</span>
                    <span class="text-2xl font-bold text-vibrant-pink">₹{{ number_format($order->total_amount, 2) }}</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Customer:</span>
                    <span class="font-medium">{{ Auth::user()->full_name }}</span>
                </div>
            </div>

            <!-- Payment Button -->
            <div class="text-center">
                <button id="pay-button" 
                        class="w-full bg-vibrant-pink text-white py-4 px-8 rounded-lg hover:bg-vibrant-pink-dark transition-colors text-lg font-semibold">
                    <i data-lucide="lock" class="w-5 h-5 inline mr-2"></i>
                    Pay Securely
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
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const payButton = document.getElementById('pay-button');
    const loadingDiv = document.getElementById('payment-loading');
    const errorDiv = document.getElementById('payment-error');
    const errorMessage = document.getElementById('error-message');
    
    // Cashfree configuration
    const cashfree = Cashfree({
        mode: '{{ config("cashfree.mode") }}' // sandbox or production
    });

    const checkoutOptions = {
        paymentSessionId: '{{ $cashfreeData["payment_session_id"] }}',
        redirectTarget: '_self' // Use _self to redirect in same tab
    };

    payButton.addEventListener('click', function() {
        initiatePayment();
    });

    function initiatePayment() {
        try {
            // Show loading state
            payButton.style.display = 'none';
            loadingDiv.classList.remove('hidden');
            errorDiv.classList.add('hidden');
            
            console.log('Initializing Cashfree checkout with options:', checkoutOptions);
            
            // Initialize Cashfree checkout
            cashfree.checkout(checkoutOptions).then(function(result) {
                console.log('Cashfree checkout result:', result);
                
                if (result.error) {
                    console.error('Payment failed:', result.error);
                    showError('Payment failed: ' + result.error.message);
                    
                } else if (result.redirect) {
                    console.log('Payment successful, redirecting...');
                    // Cashfree will handle the redirect
                    
                } else if (result.paymentDetails) {
                    console.log('Payment completed:', result.paymentDetails);
                    // Handle successful payment
                    window.location.href = '{{ route("payment.success", ["order_id" => $order->order_number]) }}';
                } else {
                    console.log('Payment result:', result);
                    // Handle other cases
                }
                
            }).catch(function(error) {
                console.error('Cashfree checkout error:', error);
                showError('Payment initialization failed. Please try again.');
            });
            
        } catch (error) {
            console.error('Payment error:', error);
            showError('Payment failed. Please try again.');
        }
    }

    function showError(message) {
        payButton.style.display = 'block';
        loadingDiv.classList.add('hidden');
        errorMessage.textContent = message;
        errorDiv.classList.remove('hidden');
    }

    window.retryPayment = function() {
        errorDiv.classList.add('hidden');
        initiatePayment();
    };
});
</script>
@endsection
