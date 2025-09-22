<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

class CashfreeService
{
    private $appId;
    private $secretKey;
    private $baseUrl;
    private $apiVersion;

    public function __construct()
    {
        $this->appId = config('cashfree.app_id');
        $this->secretKey = config('cashfree.secret_key');
        $this->apiVersion = config('cashfree.api_version');
        
        $mode = config('cashfree.mode', 'sandbox');
        $this->baseUrl = config('cashfree.endpoints.' . $mode);
        
        if (!$this->appId || !$this->secretKey) {
            throw new Exception('Cashfree credentials not configured');
        }
    }

    /**
     * Get common headers for API requests
     */
    private function getHeaders()
    {
        return [
            'x-client-id' => $this->appId,
            'x-client-secret' => $this->secretKey,
            'x-api-version' => $this->apiVersion,
            'Content-Type' => 'application/json',
            'Accept' => 'application/json',
        ];
    }

    /**
     * Create a payment order with Cashfree
     */
    public function createOrder($orderData)
    {
        try {
            $payload = [
                "order_id" => $orderData['order_id'],
                "order_amount" => (float) $orderData['amount'],
                "order_currency" => $orderData['currency'] ?? 'INR',
                "customer_details" => [
                    "customer_id" => $orderData['customer_id'],
                    "customer_name" => $orderData['customer_name'],
                    "customer_email" => $orderData['customer_email'],
                    "customer_phone" => $orderData['customer_phone'],
                ],
                "order_meta" => [
                    "return_url" => $orderData['return_url'],
                ]
            ];

            // Only add notify_url if webhook_url is provided
            if (isset($orderData['webhook_url'])) {
                $payload["order_meta"]["notify_url"] = $orderData['webhook_url'];
            }

            // Add payment_methods if provided
            if (isset($orderData['payment_methods'])) {
                $payload["order_meta"]["payment_methods"] = $orderData['payment_methods'];
            }

            // Add optional fields
            if (isset($orderData['order_note'])) {
                $payload['order_note'] = $orderData['order_note'];
            }

            if (isset($orderData['order_tags']) && is_array($orderData['order_tags'])) {
                $payload['order_tags'] = $orderData['order_tags'];
            }

            Log::info('Cashfree Create Order Request', [
                'url' => $this->baseUrl . '/orders',
                'payload' => $payload,
                'headers' => $this->getHeaders()
            ]);

            $response = Http::withHeaders($this->getHeaders())
                ->timeout(30)
                ->post($this->baseUrl . '/orders', $payload);

            Log::info('Cashfree Create Order Response', [
                'status' => $response->status(),
                'body' => $response->body(),
                'json' => $response->json(),
                'order_id' => $orderData['order_id']
            ]);

            if ($response->successful()) {
                $responseData = $response->json();
                
                // More defensive array access
                $result = [
                    'success' => true,
                    'cf_order_id' => $responseData['cf_order_id'] ?? null,
                    'order_status' => $responseData['order_status'] ?? null,
                ];

                // Add optional fields if they exist
                if (isset($responseData['order_token'])) {
                    $result['order_token'] = $responseData['order_token'];
                }

                if (isset($responseData['payment_session_id'])) {
                    $result['payment_session_id'] = $responseData['payment_session_id'];
                }

                if (isset($responseData['order_expiry_time'])) {
                    $result['order_expiry_time'] = $responseData['order_expiry_time'];
                }

                // Add the full response for debugging
                $result['full_response'] = $responseData;

                return $result;
                
            } else {
                $errorData = $response->json();
                $errorMessage = 'Cashfree API Error';
                
                if (isset($errorData['message'])) {
                    $errorMessage .= ': ' . $errorData['message'];
                } elseif (isset($errorData['error_description'])) {
                    $errorMessage .= ': ' . $errorData['error_description'];
                } else {
                    $errorMessage .= ': HTTP ' . $response->status();
                }

                throw new Exception($errorMessage);
            }

        } catch (Exception $e) {
            Log::error('Cashfree Create Order Error', [
                'error' => $e->getMessage(),
                'order_id' => $orderData['order_id'] ?? 'unknown',
                'trace' => $e->getTraceAsString()
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Get payment details from Cashfree
     */
    public function getOrderDetails($cfOrderId)
    {
        try {
            $response = Http::withHeaders($this->getHeaders())
                ->timeout(30)
                ->get($this->baseUrl . '/orders/' . $cfOrderId);

            Log::info('Cashfree Get Order Details', [
                'cf_order_id' => $cfOrderId,
                'status' => $response->status(),
                'response' => $response->json()
            ]);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'order_details' => $response->json(),
                ];
            } else {
                $errorData = $response->json();
                throw new Exception('Cashfree API Error: ' . ($errorData['message'] ?? 'Unknown error'));
            }

        } catch (Exception $e) {
            Log::error('Cashfree Get Order Details Error', [
                'error' => $e->getMessage(),
                'cf_order_id' => $cfOrderId
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Test API connectivity with simplified approach
     */
    public function testConnection()
    {
        try {
            // Create a minimal test order to verify credentials
            $testOrderData = [
                'order_id' => 'TEST_' . uniqid(),
                'amount' => 1.00,
                'currency' => 'INR',
                'customer_id' => 'test_customer_' . time(),
                'customer_name' => 'Test Customer',
                'customer_email' => 'test@ritiyana.com',
                'customer_phone' => '9999999999',
                'return_url' => 'https://ritiyana.com/payment/return',
            ];

            $result = $this->createOrder($testOrderData);
            
            Log::info('Cashfree Connection Test Result', [
                'success' => $result['success'],
                'error' => $result['error'] ?? null,
                'full_result' => $result
            ]);

            return $result;

        } catch (Exception $e) {
            Log::error('Cashfree Connection Test Failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Verify webhook signature
     */
    public function verifyWebhook($rawBody, $signature, $timestamp)
    {
        try {
            $webhookSecret = config('cashfree.webhook_secret');
            
            if (!$webhookSecret) {
                Log::warning('Cashfree webhook secret not configured');
                return false;
            }

            $signedPayload = $timestamp . $rawBody;
            $expectedSignature = base64_encode(hash_hmac('sha256', $signedPayload, $webhookSecret, true));

            $isValid = hash_equals($expectedSignature, $signature);
            
            Log::info('Cashfree Webhook Verification', [
                'signature_valid' => $isValid,
                'expected' => $expectedSignature,
                'received' => $signature
            ]);

            return $isValid;

        } catch (Exception $e) {
            Log::error('Cashfree Webhook Verification Error', [
                'error' => $e->getMessage()
            ]);

            return false;
        }
    }

    /**
     * Process refund
     */
    public function processRefund($cfOrderId, $refundAmount, $refundNote = '')
    {
        try {
            $payload = [
                "refund_amount" => (float) $refundAmount,
                "refund_id" => 'refund_' . uniqid(),
                "refund_note" => $refundNote,
            ];

            Log::info('Cashfree Refund Request', [
                'cf_order_id' => $cfOrderId,
                'payload' => $payload
            ]);

            $response = Http::withHeaders($this->getHeaders())
                ->timeout(30)
                ->post($this->baseUrl . '/orders/' . $cfOrderId . '/refunds', $payload);

            Log::info('Cashfree Refund Response', [
                'cf_order_id' => $cfOrderId,
                'status' => $response->status(),
                'response' => $response->json()
            ]);

            if ($response->successful()) {
                return [
                    'success' => true,
                    'refund_details' => $response->json(),
                ];
            } else {
                $errorData = $response->json();
                throw new Exception('Cashfree API Error: ' . ($errorData['message'] ?? 'Unknown error'));
            }

        } catch (Exception $e) {
            Log::error('Cashfree Refund Error', [
                'error' => $e->getMessage(),
                'cf_order_id' => $cfOrderId
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }
}
