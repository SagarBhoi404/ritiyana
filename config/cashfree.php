<?php

return [
    'app_id' => env('CASHFREE_APP_ID'),
    'secret_key' => env('CASHFREE_SECRET_KEY'),
    'mode' => env('CASHFREE_MODE', 'sandbox'), // sandbox or production
    'api_version' => env('CASHFREE_API_VERSION', '2023-08-01'),
    
    'endpoints' => [
        'sandbox' => 'https://sandbox.cashfree.com/pg',
        'production' => 'https://api.cashfree.com/pg',
    ],
    
    'webhook_secret' => env('CASHFREE_WEBHOOK_SECRET'),
    
    // Payment configuration - Fixed payment methods
    'payment_methods' => 'cc,dc,upi,nb,app,paylater', // Removed 'wallet', added 'paylater'
    'order_expiry_hours' => 24,
    'auto_capture' => true,

    // Payment method mapping for display
    'payment_method_names' => [
        'cc' => 'Credit Card',
        'dc' => 'Debit Card', 
        'upi' => 'UPI',
        'nb' => 'Net Banking',
        'app' => 'Mobile Apps',
        'paylater' => 'Pay Later',
        'ppc' => 'Prepaid Card',
        'ccc' => 'Corporate Credit Card',
        'emi' => 'EMI',
        'paypal' => 'PayPal',
    ],
];
