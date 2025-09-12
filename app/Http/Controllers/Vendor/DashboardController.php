<?php
// app/Http/Controllers/Vendor/DashboardController.php
namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\VendorOrder;
use App\Models\VendorAnalytics;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct()
    {
    }

    public function index()
    {
        $vendorId = auth()->id();
        
        $stats = [
            'totalProducts' => Product::where('vendor_id', $vendorId)->count(),
            'activeProducts' => Product::where('vendor_id', $vendorId)->where('is_active', true)->count(),
            'pendingProducts' => Product::where('vendor_id', $vendorId)->where('approval_status', 'pending')->count(),
            'totalOrders' => VendorOrder::where('vendor_id', $vendorId)->count(),
            'pendingOrders' => VendorOrder::where('vendor_id', $vendorId)->where('status', 'pending')->count(),
            'monthlyEarnings' => VendorOrder::where('vendor_id', $vendorId)
                ->whereMonth('created_at', now()->month)
                ->sum('vendor_earning'),
            'totalEarnings' => VendorOrder::where('vendor_id', $vendorId)
                ->where('status', 'delivered')
                ->sum('vendor_earning'),
            'lowStockProducts' => Product::where('vendor_id', $vendorId)
                ->where('stock_quantity', '<', 10)
                ->count(),
        ];

        $recentProducts = Product::where('vendor_id', $vendorId)
            ->latest()
            ->take(5)
            ->get();

        $recentOrders = VendorOrder::where('vendor_id', $vendorId)
            ->with('customer')
            ->latest()
            ->take(10)
            ->get();

        // Monthly sales chart data
        $monthlySales = VendorOrder::where('vendor_id', $vendorId)
            ->selectRaw('MONTH(created_at) as month, SUM(vendor_earning) as earnings, COUNT(*) as orders')
            ->whereYear('created_at', now()->year)
            ->groupBy('month')
            ->get()
            ->keyBy('month');

        return view('shopkeeper.dashboard', compact('stats', 'recentProducts', 'recentOrders', 'monthlySales'));
    }
}
