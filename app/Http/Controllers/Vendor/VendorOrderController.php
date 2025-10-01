<?php
// app/Http/Controllers/Vendor/OrderController.php
namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\VendorOrder;
use Illuminate\Http\Request;

class VendorOrderController extends Controller
{
     public function index(Request $request)
    {
        $vendorId = auth()->id();
        
        // Get all order items for this vendor
        $query = OrderItem::with(['order.customer', 'product', 'pujaKit'])
            ->where('vendor_id', $vendorId);
        
        // Filter by status
        if ($request->filled('status') && $request->status !== 'all') {
            $query->whereHas('order', function ($q) use ($request) {
                $q->where('status', $request->status);
            });
        }
        
        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('order', function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhereHas('customer', function ($cq) use ($search) {
                      $cq->where('first_name', 'like', "%{$search}%")
                         ->orWhere('last_name', 'like', "%{$search}%")
                         ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }
        
        // Date range filter
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        
        // Group by order_id and get unique orders
        $orderItems = $query->latest()->get();
        
        // Group order items by order
        $groupedOrders = $orderItems->groupBy('order_id')->map(function ($items) {
            $firstItem = $items->first();
            $order = $firstItem->order;
            
            // Calculate vendor totals for this order
            $vendorSubtotal = $items->sum('total');
            $vendorCommission = $items->sum('vendor_commission');
            $vendorEarning = $items->sum('vendor_earning');
            $itemCount = $items->count();
            
            return (object) [
                'id' => $order->id,
                'order_number' => $order->order_number,
                'order' => $order,
                'customer' => $order->customer,
                'status' => $order->status,
                'created_at' => $order->created_at,
                'total_amount' => $vendorSubtotal,
                'commission_amount' => $vendorCommission,
                'vendor_earning' => $vendorEarning,
                'commission_rate' => $vendorSubtotal > 0 ? ($vendorCommission / $vendorSubtotal) * 100 : 0,
                'item_count' => $itemCount,
                'items' => $items,
                'status_badge' => $this->getStatusBadge($order->status),
            ];
        })->values();
        
        // Paginate manually
        $page = $request->get('page', 1);
        $perPage = 15;
        $total = $groupedOrders->count();
        $orders = new \Illuminate\Pagination\LengthAwarePaginator(
            $groupedOrders->forPage($page, $perPage),
            $total,
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );
        
        // Calculate stats
        $allVendorItems = OrderItem::where('vendor_id', $vendorId)->get();
        $allOrderIds = $allVendorItems->pluck('order_id')->unique();
        
        $stats = [
            'total' => $allOrderIds->count(),
            'pending' => Order::whereIn('id', $allOrderIds)->where('status', 'pending')->count(),
            'processing' => Order::whereIn('id', $allOrderIds)->where('status', 'processing')->count(),
            'shipped' => Order::whereIn('id', $allOrderIds)->where('status', 'shipped')->count(),
            'delivered' => Order::whereIn('id', $allOrderIds)->where('status', 'delivered')->count(),
        ];
        
        return view('shopkeeper.orders.index', compact('orders', 'stats'));
    }
    
    public function show($orderId)
    {
        $vendorId = auth()->id();
        
        // Get order with vendor items
        $order = Order::with(['customer', 'orderItems.product', 'orderItems.pujaKit'])
            ->findOrFail($orderId);
        
        // Get only this vendor's items
        $vendorItems = $order->orderItems->where('vendor_id', $vendorId);
        
        if ($vendorItems->isEmpty()) {
            abort(403, 'Unauthorized access');
        }
        
        // Calculate vendor totals
        $vendorSubtotal = $vendorItems->sum('total');
        $vendorCommission = $vendorItems->sum('vendor_commission');
        $vendorEarning = $vendorItems->sum('vendor_earning');
        
        $orderData = (object) [
            'order' => $order,
            'items' => $vendorItems,
            'subtotal' => $vendorSubtotal,
            'commission' => $vendorCommission,
            'earning' => $vendorEarning,
            'commission_rate' => $vendorSubtotal > 0 ? ($vendorCommission / $vendorSubtotal) * 100 : 0,
        ];
        
        return view('shopkeeper.orders.show', compact('orderData', 'order'));
    }
    
    public function updateStatus(Request $request, $orderId)
    {
        $request->validate([
            'status' => 'required|in:confirmed,processing,shipped,delivered',
            'tracking_number' => 'nullable|string|max:255',
            'vendor_notes' => 'nullable|string|max:500',
        ]);
        
        $vendorId = auth()->id();
        $order = Order::findOrFail($orderId);
        
        // Verify vendor has items in this order
        $vendorItems = $order->orderItems->where('vendor_id', $vendorId);
        if ($vendorItems->isEmpty()) {
            abort(403, 'Unauthorized access');
        }
        
        // Update order status
        $order->update(['status' => $request->status]);
        
        // If shipped, add tracking info to notes
        if ($request->status === 'shipped' && $request->tracking_number) {
            $order->update([
                'shipped_at' => now(),
                'notes' => ($order->notes ?? '') . "\nVendor Tracking: {$request->tracking_number}"
            ]);
        }
        
        if ($request->status === 'delivered') {
            $order->update(['delivered_at' => now()]);
        }
        
        return redirect()->back()->with('success', 'Order status updated successfully!');
    }
    
    public function invoice($orderId)
    {
        $vendorId = auth()->id();
        $order = Order::with(['customer', 'orderItems.product', 'orderItems.pujaKit'])
            ->findOrFail($orderId);
        
        $vendorItems = $order->orderItems->where('vendor_id', $vendorId);
        
        if ($vendorItems->isEmpty()) {
            abort(403, 'Unauthorized access');
        }
        
        $vendorSubtotal = $vendorItems->sum('total');
        $vendorCommission = $vendorItems->sum('vendor_commission');
        $vendorEarning = $vendorItems->sum('vendor_earning');
        
        return view('shopkeeper.orders.invoice', compact('order', 'vendorItems', 'vendorSubtotal', 'vendorCommission', 'vendorEarning'));
    }
    
    private function getStatusBadge($status)
    {
        $badges = [
            'pending' => 'bg-yellow-100 text-yellow-800',
            'confirmed' => 'bg-blue-100 text-blue-800',
            'processing' => 'bg-blue-100 text-blue-800',
            'shipped' => 'bg-purple-100 text-purple-800',
            'delivered' => 'bg-green-100 text-green-800',
            'cancelled' => 'bg-red-100 text-red-800',
        ];
        
        return $badges[$status] ?? 'bg-gray-100 text-gray-800';
    }
}

