<?php
// app/Http/Controllers/Vendor/OrderController.php
namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\VendorOrder;
use Illuminate\Http\Request;

class VendorOrderController extends Controller
{
    public function __construct()
    {
    }

    public function index(Request $request)
    {
        $query = VendorOrder::where('vendor_id', auth()->id())
            ->with(['customer', 'order']);

        // Filter by status
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Date range filter
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $orders = $query->latest()->paginate(15)->withQueryString();

        $stats = [
            'total' => VendorOrder::where('vendor_id', auth()->id())->count(),
            'pending' => VendorOrder::where('vendor_id', auth()->id())->where('status', 'pending')->count(),
            'processing' => VendorOrder::where('vendor_id', auth()->id())->where('status', 'processing')->count(),
            'shipped' => VendorOrder::where('vendor_id', auth()->id())->where('status', 'shipped')->count(),
            'delivered' => VendorOrder::where('vendor_id', auth()->id())->where('status', 'delivered')->count(),
        ];

        return view('vendor.orders.index', compact('orders', 'stats'));
    }

    public function show(VendorOrder $vendorOrder)
    {
        $this->authorize('view', $vendorOrder);
        $vendorOrder->load(['customer', 'order']);
        return view('vendor.orders.show', compact('vendorOrder'));
    }

    public function updateStatus(Request $request, VendorOrder $vendorOrder)
    {
        $this->authorize('update', $vendorOrder);

        $request->validate([
            'status' => 'required|in:pending,confirmed,processing,shipped,delivered,cancelled',
            'tracking_number' => 'nullable|string|max:255',
            'vendor_notes' => 'nullable|string|max:500',
        ]);

        $updateData = [
            'status' => $request->status,
            'vendor_notes' => $request->vendor_notes,
        ];

        if ($request->status === 'shipped' && $request->tracking_number) {
            $updateData['tracking_number'] = $request->tracking_number;
            $updateData['shipped_at'] = now();
        }

        if ($request->status === 'delivered') {
            $updateData['delivered_at'] = now();
        }

        $vendorOrder->update($updateData);

        return redirect()->back()->with('success', 'Order status updated successfully!');
    }
}
