<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\CashfreeService;

class TestCashfree extends Command
{
    protected $signature = 'cashfree:test';
    protected $description = 'Test Cashfree payment gateway integration';

    public function handle()
    {
        $this->info('Testing Cashfree integration...');
        
        try {
            $cashfreeService = new CashfreeService();
            
            $result = $cashfreeService->testConnection();
            
            if ($result['success']) {
                $this->info('✅ Cashfree integration is working correctly!');
                $this->info('Order ID: ' . ($result['cf_order_id'] ?? 'N/A'));
                $this->info('Order Status: ' . ($result['order_status'] ?? 'N/A'));
            } else {
                $this->error('❌ Cashfree integration failed');
                $this->error('Error: ' . $result['error']);
                
                // Common troubleshooting tips
                $this->warn('Troubleshooting tips:');
                $this->line('1. Check your CASHFREE_APP_ID in .env file');
                $this->line('2. Check your CASHFREE_SECRET_KEY in .env file');
                $this->line('3. Ensure CASHFREE_MODE is set to "sandbox" for testing');
                $this->line('4. Verify your Cashfree dashboard credentials');
            }
            
        } catch (\Exception $e) {
            $this->error('❌ Exception: ' . $e->getMessage());
            $this->error('Please check your Cashfree configuration');
        }
    }
}
