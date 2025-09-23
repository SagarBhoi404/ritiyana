<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    /**
     * Display a listing of orders
     */
    public function index(Request $request)
    {
        // Get order statistics
        $stats = [
            'total' => Order::count(),
            'pending' => Order::pending()->count(),
            'processing' => Order::processing()->count(),
            'delivered' => Order::delivered()->count(),
            'cancelled' => Order::cancelled()->count(),
        ];

        // Build orders query with filters
        $ordersQuery = Order::with(['user', 'orderItems.product', 'orderItems.pujaKit'])
            ->orderBy('created_at', 'desc');

        // Apply filters
        if ($request->has('status') && $request->status != '') {
            $ordersQuery->where('status', $request->status);
        }

        if ($request->has('payment_status') && $request->payment_status != '') {
            $ordersQuery->where('payment_status', $request->payment_status);
        }

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $ordersQuery->where(function ($query) use ($search) {
                $query->where('order_number', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery->where('first_name', 'like', "%{$search}%")
                            ->orWhere('last_name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    });
            });
        }

        if ($request->has('date_from') && $request->date_from != '') {
            $ordersQuery->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->has('date_to') && $request->date_to != '') {
            $ordersQuery->whereDate('created_at', '<=', $request->date_to);
        }

        // Paginate orders
        $orders = $ordersQuery->paginate(15)->withQueryString();

        return view('admin.orders.index', compact('orders', 'stats'));
    }

    /**
     * Show the order details
     */
    public function show(Order $order)
    {
        $order->load([
            'user',
            'orderItems.product',
            'orderItems.pujaKit',
            'orderItems.vendor',
            'payments',
        ]);

        return view('admin.orders.show', compact('order'));
    }

    /**
     * Update order status
     */
    public function updateStatus(Request $request, Order $order)
    {
        // Debug log to see what's being received
        Log::info('Status update request', [
            'order_id' => $order->id,
            'all_data' => $request->all(),
            'status' => $request->get('status'),
            'method' => $request->method(),
        ]);

        try {
            // Validate the request
            $validated = $request->validate([
                'status' => 'required|string|in:pending,processing,shipped,delivered,cancelled,refunded',
                'tracking_number' => 'nullable|string|max:100',
                'notes' => 'nullable|string|max:1000',
            ]);

            $oldStatus = $order->status;
            $newStatus = $validated['status'];

            // Prepare update data
            $updateData = ['status' => $newStatus];

            // Handle status-specific updates
            switch ($newStatus) {
                case 'processing':
                    // Just update status
                    break;

                case 'shipped':
                    $updateData['shipped_at'] = now();
                    if (! empty($validated['tracking_number'])) {
                        $currentNotes = $order->notes ?? '';
                        $updateData['notes'] = $currentNotes."\n[".now()->format('Y-m-d H:i').'] Tracking: '.$validated['tracking_number'];
                    }
                    break;

                case 'delivered':
                    $updateData['delivered_at'] = now();
                    if (! $order->shipped_at) {
                        $updateData['shipped_at'] = now();
                    }
                    break;

                case 'cancelled':
                case 'refunded':
                    if (! empty($validated['notes'])) {
                        $currentNotes = $order->notes ?? '';
                        $updateData['notes'] = $currentNotes."\n[".now()->format('Y-m-d H:i').'] '.ucfirst($newStatus).': '.$validated['notes'];
                    }
                    break;
            }

            // Add general notes if provided and not handled above
            if (! empty($validated['notes']) && ! in_array($newStatus, ['shipped', 'cancelled', 'refunded'])) {
                $currentNotes = $order->notes ?? '';
                $updateData['notes'] = $currentNotes."\n[".now()->format('Y-m-d H:i')."] Status changed from {$oldStatus} to {$newStatus}: ".$validated['notes'];
            }

            // Update the order
            $order->update($updateData);

            // Log success
            Log::info('Order status updated successfully', [
                'order_id' => $order->id,
                'old_status' => $oldStatus,
                'new_status' => $newStatus,
            ]);

            return response()->json([
                'success' => true,
                'message' => "Order status updated from {$oldStatus} to {$newStatus} successfully",
                'status' => $newStatus,
                'old_status' => $oldStatus,
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation failed for order status update', [
                'order_id' => $order->id,
                'errors' => $e->errors(),
                'request_data' => $request->all(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);

        } catch (\Exception $e) {
            Log::error('Error updating order status', [
                'order_id' => $order->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while updating the order status: '.$e->getMessage(),
            ], 500);
        }
    }

    /**
     * Update payment status
     */
   public function updatePaymentStatus(Request $request, Order $order)
{
    try {
        // Validate the request
        $validated = $request->validate([
            'payment_status' => 'required|string|in:pending,paid,failed,refunded,partially_refunded',
            'refund_amount' => 'nullable|numeric|min:0|max:' . $order->total_amount,
            'payment_notes' => 'nullable|string|max:1000'
        ]);

        $oldPaymentStatus = $order->payment_status;
        $newPaymentStatus = $validated['payment_status'];

        // Prepare update data
        $updateData = ['payment_status' => $newPaymentStatus];
        
        // Handle refund logic
        if (in_array($newPaymentStatus, ['refunded', 'partially_refunded'])) {
            if (!empty($validated['refund_amount'])) {
                // You might want to create a refund record here
                // For now, we'll just add it to notes
                $currentNotes = $order->notes ?? '';
                $updateData['notes'] = $currentNotes . "\n[" . now()->format('Y-m-d H:i') . "] Refund Amount: ₹" . number_format($validated['refund_amount'], 2);
            }
        }

        // Add payment notes if provided
        if (!empty($validated['payment_notes'])) {
            $currentNotes = $order->notes ?? '';
            $updateData['notes'] = ($updateData['notes'] ?? $currentNotes) . "\n[" . now()->format('Y-m-d H:i') . "] Payment Note: " . $validated['payment_notes'];
        }

        // Update the order
        $order->update($updateData);

        // Update related payments if they exist
        if ($order->payments()->exists()) {
            $paymentStatus = $newPaymentStatus === 'paid' ? 'completed' : 
                           ($newPaymentStatus === 'failed' ? 'failed' : 
                           ($newPaymentStatus === 'refunded' ? 'refunded' : 'pending'));
            
            $order->payments()->update(['status' => $paymentStatus]);
        }

        // Log success
        Log::info('Order payment status updated successfully', [
            'order_id' => $order->id,
            'old_payment_status' => $oldPaymentStatus,
            'new_payment_status' => $newPaymentStatus
        ]);

        return response()->json([
            'success' => true,
            'message' => "Payment status updated from {$oldPaymentStatus} to {$newPaymentStatus} successfully",
            'payment_status' => $newPaymentStatus,
            'old_payment_status' => $oldPaymentStatus
        ]);

    } catch (\Illuminate\Validation\ValidationException $e) {
        return response()->json([
            'success' => false,
            'message' => 'Validation failed',
            'errors' => $e->errors()
        ], 422);
        
    } catch (\Exception $e) {
        Log::error('Error updating payment status', [
            'order_id' => $order->id,
            'error' => $e->getMessage()
        ]);
        
        return response()->json([
            'success' => false,
            'message' => 'An error occurred while updating the payment status: ' . $e->getMessage()
        ], 500);
    }
}
    /**
     * Export orders to CSV
     */
    public function export(Request $request)
    {
        $ordersQuery = Order::with(['user', 'orderItems.product']);

        // Apply same filters as index
        if ($request->has('status') && $request->status != '') {
            $ordersQuery->where('status', $request->status);
        }

        if ($request->has('payment_status') && $request->payment_status != '') {
            $ordersQuery->where('payment_status', $request->payment_status);
        }

        if ($request->has('date_from') && $request->date_from != '') {
            $ordersQuery->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->has('date_to') && $request->date_to != '') {
            $ordersQuery->whereDate('created_at', '<=', $request->date_to);
        }

        $orders = $ordersQuery->get();

        $filename = 'orders_export_'.now()->format('Y_m_d_H_i_s').'.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function () use ($orders) {
            $file = fopen('php://output', 'w');

            // Add CSV headers
            fputcsv($file, [
                'Order Number',
                'Customer Name',
                'Customer Email',
                'Status',
                'Payment Status',
                'Total Amount',
                'Total Items',
                'Order Date',
                'Shipping Address',
            ]);

            // Add data rows
            foreach ($orders as $order) {
                fputcsv($file, [
                    $order->order_number,
                    $order->customer_name,
                    $order->user->email,
                    ucfirst($order->status),
                    ucfirst($order->payment_status),
                    $order->formatted_total,
                    $order->total_items,
                    $order->created_at->format('Y-m-d H:i:s'),
                    $order->shipping_address_text,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Get order analytics data for dashboard
     */
    public function analytics(Request $request)
    {
        $period = $request->get('period', '30'); // days
        $startDate = now()->subDays($period);

        // Revenue over time
        $revenueData = Order::selectRaw('DATE(created_at) as date, SUM(total_amount) as revenue')
            ->where('created_at', '>=', $startDate)
            ->where('payment_status', 'paid')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Orders by status
        $statusData = Order::selectRaw('status, COUNT(*) as count')
            ->where('created_at', '>=', $startDate)
            ->groupBy('status')
            ->get();

        // Top products
        $topProducts = OrderItem::with('product')
            ->selectRaw('product_id, SUM(quantity) as total_quantity, SUM(total) as total_revenue')
            ->whereHas('order', function ($query) use ($startDate) {
                $query->where('created_at', '>=', $startDate);
            })
            ->groupBy('product_id')
            ->orderByDesc('total_revenue')
            ->limit(10)
            ->get();

        return response()->json([
            'revenue_data' => $revenueData,
            'status_data' => $statusData,
            'top_products' => $topProducts,
        ]);
    }

    /**
     * Bulk actions on orders
     */
    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:update_status,export_selected,delete',
            'order_ids' => 'required|array',
            'order_ids.*' => 'exists:orders,id',
            'status' => 'required_if:action,update_status|in:pending,processing,shipped,delivered,cancelled',
        ]);

        $orders = Order::whereIn('id', $request->order_ids);
        $count = $orders->count();

        switch ($request->action) {
            case 'update_status':
                $orders->update(['status' => $request->status]);

                return response()->json([
                    'success' => true,
                    'message' => "{$count} orders updated to {$request->status} successfully",
                ]);

            case 'export_selected':
                // Handle export of selected orders
                return $this->exportSelected($request->order_ids);

            case 'delete':
                // Only allow deletion of cancelled orders
                $deletableOrders = $orders->where('status', 'cancelled');
                $deletedCount = $deletableOrders->count();
                $deletableOrders->delete();

                return response()->json([
                    'success' => true,
                    'message' => "{$deletedCount} cancelled orders deleted successfully",
                ]);
        }
    }

    /**
     * Print order invoice
     */
    public function printInvoice(Order $order)
    {
        $order->load([
            'user',
            'orderItems.product',
            'orderItems.pujaKit',
        ]);

        return view('admin.orders.invoice', compact('order'));
    }

    /**
     * Get recent orders for dashboard
     */
    public function recentOrders($limit = 5)
    {
        $orders = Order::with(['user', 'orderItems'])
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();

        return response()->json($orders);
    }
}
