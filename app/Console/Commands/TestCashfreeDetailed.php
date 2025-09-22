<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\CashfreeService;

class TestCashfreeDetailed extends Command
{
    protected $signature = 'cashfree:test-detailed';
    protected $description = 'Detailed test of Cashfree payment gateway integration';

    public function handle()
    {
        $this->info('🧪 Running detailed Cashfree integration test...');
        $this->newLine();
        
        // First, check configuration
        $this->info('📋 Checking configuration...');
        $this->checkConfiguration();
        $this->newLine();
        
        try {
            $cashfreeService = new CashfreeService();
            
            // Test 1: Basic connectivity
            $this->info('1️⃣ Testing basic connectivity...');
            $result = $cashfreeService->testConnection();
            
            if ($result['success']) {
                $this->info('   ✅ Connection successful!');
                
                // Display all available data from response
                if (isset($result['cf_order_id'])) {
                    $this->info('   📦 Cashfree Order ID: ' . $result['cf_order_id']);
                }
                
                if (isset($result['order_status'])) {
                    $this->info('   📅 Order Status: ' . $result['order_status']);
                }
                
                if (isset($result['order_token'])) {
                    $this->info('   🔑 Order Token: ' . substr($result['order_token'], 0, 20) . '...');
                } else {
                    $this->warn('   ⚠️ Order Token not received (this might be normal for some API versions)');
                }
                
                if (isset($result['payment_session_id'])) {
                    $this->info('   🎫 Payment Session ID: ' . substr($result['payment_session_id'], 0, 20) . '...');
                } else {
                    $this->warn('   ⚠️ Payment Session ID not received');
                }

                // Show full response structure for debugging
                if (isset($result['full_response'])) {
                    $this->info('   📄 Full API Response Structure:');
                    foreach ($result['full_response'] as $key => $value) {
                        $displayValue = is_string($value) && strlen($value) > 50 
                            ? substr($value, 0, 50) . '...' 
                            : (is_array($value) ? '[array]' : $value);
                        $this->line('      • ' . $key . ': ' . $displayValue);
                    }
                }
                
                $this->newLine();
                
                // Test 2: Get order details if we have a cf_order_id
                if (isset($result['cf_order_id'])) {
                    $this->info('2️⃣ Testing order details retrieval...');
                    $orderDetails = $cashfreeService->getOrderDetails($result['cf_order_id']);
                    
                    if ($orderDetails['success']) {
                        $this->info('   ✅ Order details retrieved successfully!');
                        $details = $orderDetails['order_details'];
                        $this->info('   💰 Amount: ₹' . ($details['order_amount'] ?? 'N/A'));
                        $this->info('   🔄 Status: ' . ($details['order_status'] ?? 'N/A'));
                        $this->info('   🕐 Created: ' . ($details['created_at'] ?? 'N/A'));
                    } else {
                        $this->warn('   ⚠️ Could not retrieve order details: ' . $orderDetails['error']);
                    }
                } else {
                    $this->warn('2️⃣ Skipping order details test - no cf_order_id received');
                }
                
            } else {
                $this->error('   ❌ Connection failed');
                $this->error('   Error: ' . $result['error']);
                
                $this->newLine();
                $this->warn('🔧 Troubleshooting checklist:');
                $this->line('   □ Check CASHFREE_APP_ID in .env file');
                $this->line('   □ Check CASHFREE_SECRET_KEY in .env file');
                $this->line('   □ Ensure CASHFREE_MODE is "sandbox" for testing');
                $this->line('   □ Verify credentials in Cashfree dashboard');
                $this->line('   □ Check if your IP is whitelisted (if required)');
                $this->line('   □ Check Laravel logs for detailed error information');
                
                return Command::FAILURE;
            }
            
            $this->newLine();
            $this->info('🎉 Basic tests completed successfully!');
            $this->info('💡 Your Cashfree integration is working correctly.');
            
            return Command::SUCCESS;
            
        } catch (\Exception $e) {
            $this->error('❌ Exception occurred: ' . $e->getMessage());
            $this->error('📍 File: ' . $e->getFile() . ':' . $e->getLine());
            
            $this->newLine();
            $this->warn('🔧 Common issues and solutions:');
            $this->line('   • Invalid credentials: Check your Cashfree dashboard');
            $this->line('   • Network issues: Check internet connection');
            $this->line('   • API version mismatch: Ensure you\'re using 2023-08-01');
            $this->line('   • Environment mismatch: Use "sandbox" for testing');
            $this->line('   • Check Laravel log files for detailed error information');
            
            return Command::FAILURE;
        }
    }

    private function checkConfiguration()
    {
        $appId = config('cashfree.app_id');
        $secretKey = config('cashfree.secret_key');
        $mode = config('cashfree.mode');
        $apiVersion = config('cashfree.api_version');

        $this->line('   App ID: ' . ($appId ? '✅ Set (' . substr($appId, 0, 8) . '...)' : '❌ Missing'));
        $this->line('   Secret Key: ' . ($secretKey ? '✅ Set (' . substr($secretKey, 0, 8) . '...)' : '❌ Missing'));
        $this->line('   Mode: ' . ($mode ?: 'Not set') . ($mode === 'sandbox' ? ' ✅' : ' ⚠️'));
        $this->line('   API Version: ' . ($apiVersion ?: 'Not set'));
        $this->line('   Base URL: ' . config('cashfree.endpoints.' . $mode));
    }
}
