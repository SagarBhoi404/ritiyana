<?php
// app/Http/Controllers/Vendor/AnalyticsController.php
namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\VendorOrder;
use App\Models\Product;
use Illuminate\Http\Request;
use Carbon\Carbon;

class VendorAnalyticsController extends Controller
{
    public function __construct()
    {
    }

    public function index()
    {
        $vendorId = auth()->id();

        // Sales analytics
        $salesData = $this->getSalesAnalytics($vendorId);
        $productData = $this->getProductAnalytics($vendorId);
        $monthlyData = $this->getMonthlyAnalytics($vendorId);

        return view('vendor.analytics.index', compact('salesData', 'productData', 'monthlyData'));
    }

    private function getSalesAnalytics($vendorId)
    {
        return [
            'today_sales' => VendorOrder::where('vendor_id', $vendorId)
                ->whereDate('created_at', today())
                ->sum('vendor_earning'),
            'weekly_sales' => VendorOrder::where('vendor_id', $vendorId)
                ->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])
                ->sum('vendor_earning'),
            'monthly_sales' => VendorOrder::where('vendor_id', $vendorId)
                ->whereMonth('created_at', now()->month)
                ->sum('vendor_earning'),
            'total_sales' => VendorOrder::where('vendor_id', $vendorId)
                ->where('status', 'delivered')
                ->sum('vendor_earning'),
        ];
    }

    private function getProductAnalytics($vendorId)
    {
        $topProducts = Product::where('vendor_id', $vendorId)
            ->orderBy('total_sales', 'desc')
            ->take(5)
            ->get();

        return [
            'top_products' => $topProducts,
            'total_products' => Product::where('vendor_id', $vendorId)->count(),
            'active_products' => Product::where('vendor_id', $vendorId)->where('is_active', true)->count(),
        ];
    }

    private function getMonthlyAnalytics($vendorId)
    {
        return VendorOrder::where('vendor_id', $vendorId)
            ->selectRaw('MONTH(created_at) as month, SUM(vendor_earning) as earnings, COUNT(*) as orders')
            ->whereYear('created_at', now()->year)
            ->groupBy('month')
            ->get();
    }
}
