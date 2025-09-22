<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;

class FixOrderTotals extends Command
{
    protected $signature = 'fix:order-totals {order_number?}';
    protected $description = 'Fix order totals that were calculated incorrectly';

    public function handle()
    {
        $orderNumber = $this->argument('order_number');
        
        if ($orderNumber) {
            $this->fixSingleOrder($orderNumber);
        } else {
            $this->fixAllBrokenOrders();
        }
    }
    
    private function fixSingleOrder($orderNumber)
    {
        $order = Order::with('orderItems')->where('order_number', $orderNumber)->first();
        
        if (!$order) {
            $this->error('Order not found: ' . $orderNumber);
            return;
        }
        
        $this->info('Fixing order: ' . $orderNumber);
        
        // Calculate correct subtotal from order items
        $correctSubtotal = $order->orderItems->sum('total');
        $correctTax = $correctSubtotal * 0.18;
        $correctShipping = $correctSubtotal >= 500 ? 0 : 50;
        $correctTotal = $correctSubtotal + $correctTax + $correctShipping;
        
        $this->info('Current values:');
        $this->info('Subtotal: ₹' . $order->subtotal);
        $this->info('Tax: ₹' . $order->tax_amount);
        $this->info('Shipping: ₹' . $order->shipping_amount);
        $this->info('Total: ₹' . $order->total_amount);
        
        $this->info('Correct values:');
        $this->info('Subtotal: ₹' . $correctSubtotal);
        $this->info('Tax: ₹' . $correctTax);
        $this->info('Shipping: ₹' . $correctShipping);
        $this->info('Total: ₹' . $correctTotal);
        
        if ($this->confirm('Update this order?')) {
            $order->update([
                'subtotal' => $correctSubtotal,
                'tax_amount' => $correctTax,
                'shipping_amount' => $correctShipping,
                'total_amount' => $correctTotal,
            ]);
            
            // Also update payment amount if exists
            $order->payments()->update(['amount' => $correctTotal]);
            
            $this->info('✅ Order updated successfully!');
        }
    }
    
    private function fixAllBrokenOrders()
    {
        $brokenOrders = Order::with('orderItems')
            ->where('subtotal', '<=', 0)
            ->orWhereRaw('subtotal != (SELECT SUM(total) FROM order_items WHERE order_items.order_id = orders.id)')
            ->get();
            
        if ($brokenOrders->isEmpty()) {
            $this->info('No broken orders found.');
            return;
        }
        
        $this->info('Found ' . $brokenOrders->count() . ' broken orders');
        
        foreach ($brokenOrders as $order) {
            $this->fixSingleOrder($order->order_number);
        }
    }
}
