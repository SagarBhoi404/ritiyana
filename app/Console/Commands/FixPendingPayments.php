<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;
use App\Services\CashfreeService;
use Illuminate\Support\Facades\DB;

class FixPendingPayments extends Command
{
    protected $signature = 'fix:pending-payments {order_number?}';
    protected $description = 'Fix orders with pending payment status by checking with Cashfree';

    public function handle()
    {
        $orderNumber = $this->argument('order_number');
        
        if ($orderNumber) {
            $this->fixSingleOrder($orderNumber);
        } else {
            $this->fixAllPendingOrders();
        }
    }
    
    private function fixSingleOrder($orderNumber)
    {
        $order = Order::with('payments')->where('order_number', $orderNumber)->first();
        
        if (!$order) {
            $this->error('Order not found: ' . $orderNumber);
            return;
        }
        
        $payment = $order->payments()->where('status', 'pending')->first();
        
        if (!$payment || !$payment->gateway_order_id) {
            $this->error('No pending payment found for order: ' . $orderNumber);
            return;
        }
        
        $this->info('Checking payment status with Cashfree...');
        
        $cashfreeService = app(CashfreeService::class);
        $response = $cashfreeService->getOrderDetails($payment->gateway_order_id);
        
        if ($response['success']) {
            $orderDetails = $response['order_details'];
            $status = $orderDetails['order_status'];
            
            $this->info('Cashfree Status: ' . $status);
            
            if ($status === 'PAID') {
                DB::beginTransaction();
                
                $payment->update([
                    'status' => 'completed',
                    'gateway_transaction_id' => $orderDetails['payments'][0]['cf_payment_id'] ?? null,
                    'paid_at' => now(),
                ]);
                
                $order->update([
                    'status' => 'processing',
                    'payment_status' => 'paid'
                ]);
                
                DB::commit();
                
                $this->info('✅ Order fixed: ' . $orderNumber);
            } else {
                $this->warn('Order is not paid according to Cashfree: ' . $status);
            }
        } else {
            $this->error('Failed to verify with Cashfree: ' . $response['error']);
        }
    }
}
