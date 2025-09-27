<?php
// app/Http/Controllers/Vendor/DashboardController.php
namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\VendorOrder;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $vendorId = auth()->id();
        $currentMonth = now()->month;
        $currentYear = now()->year;
        
        // Enhanced statistics with real data
        $stats = [
            'totalSales' => VendorOrder::where('vendor_id', $vendorId)
                ->where('status', 'delivered')
                ->sum('vendor_earning'),
            'monthlySales' => VendorOrder::where('vendor_id', $vendorId)
                ->whereMonth('created_at', $currentMonth)
                ->whereYear('created_at', $currentYear)
                ->sum('vendor_earning'),
            'totalOrders' => VendorOrder::where('vendor_id', $vendorId)->count(),
            'monthlyOrders' => VendorOrder::where('vendor_id', $vendorId)
                ->whereMonth('created_at', $currentMonth)
                ->whereYear('created_at', $currentYear)
                ->count(),
            'totalProducts' => Product::where('vendor_id', $vendorId)->count(),
            'activeProducts' => Product::where('vendor_id', $vendorId)
                ->where('is_active', true)
                ->count(),
            'totalCustomers' => VendorOrder::where('vendor_id', $vendorId)
                ->distinct('customer_id')
                ->count('customer_id'),
            'monthlyCustomers' => VendorOrder::where('vendor_id', $vendorId)
                ->whereMonth('created_at', $currentMonth)
                ->whereYear('created_at', $currentYear)
                ->distinct('customer_id')
                ->count('customer_id'),
        ];

        // Calculate percentage changes
        $lastMonth = now()->subMonth();
        $previousStats = [
            'lastMonthSales' => VendorOrder::where('vendor_id', $vendorId)
                ->whereMonth('created_at', $lastMonth->month)
                ->whereYear('created_at', $lastMonth->year)
                ->sum('vendor_earning'),
            'lastMonthOrders' => VendorOrder::where('vendor_id', $vendorId)
                ->whereMonth('created_at', $lastMonth->month)
                ->whereYear('created_at', $lastMonth->year)
                ->count(),
            'lastMonthProducts' => Product::where('vendor_id', $vendorId)
                ->whereMonth('created_at', $lastMonth->month)
                ->whereYear('created_at', $lastMonth->year)
                ->count(),
            'lastMonthCustomers' => VendorOrder::where('vendor_id', $vendorId)
                ->whereMonth('created_at', $lastMonth->month)
                ->whereYear('created_at', $lastMonth->year)
                ->distinct('customer_id')
                ->count('customer_id'),
        ];

        // Calculate growth percentages
        $growthPercentages = [
            'sales' => $this->calculateGrowthPercentage($stats['monthlySales'], $previousStats['lastMonthSales']),
            'orders' => $this->calculateGrowthPercentage($stats['monthlyOrders'], $previousStats['lastMonthOrders']),
            'products' => $this->calculateGrowthPercentage($stats['activeProducts'], $previousStats['lastMonthProducts']),
            'customers' => $this->calculateGrowthPercentage($stats['monthlyCustomers'], $previousStats['lastMonthCustomers']),
        ];

        // Recent orders with customer details
        $recentOrders = VendorOrder::where('vendor_id', $vendorId)
            ->with(['customer', 'product'])
            ->latest()
            ->take(5)
            ->get();

        // Weekly sales data for chart (last 7 days)
        $weeklySales = VendorOrder::where('vendor_id', $vendorId)
            ->where('created_at', '>=', Carbon::now()->subDays(6))
            ->selectRaw('DATE(created_at) as date, SUM(vendor_earning) as daily_sales')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Prepare chart data
        $chartData = $this->prepareChartData($weeklySales);

        return view('shopkeeper.dashboard', compact(
            'stats', 
            'growthPercentages', 
            'recentOrders', 
            'chartData'
        ));
    }

    private function calculateGrowthPercentage($current, $previous)
    {
        if ($previous == 0) {
            return $current > 0 ? 100 : 0;
        }
        return round((($current - $previous) / $previous) * 100, 1);
    }

    private function prepareChartData($weeklySales)
    {
        $dates = collect();
        $sales = collect();
        
        // Create array for last 7 days
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $dateString = $date->format('Y-m-d');
            $dayName = $date->format('D');
            
            $dayData = $weeklySales->firstWhere('date', $dateString);
            $dailySales = $dayData ? $dayData->daily_sales : 0;
            
            $dates->push($dayName);
            $sales->push($dailySales);
        }

        return [
            'categories' => $dates->toArray(),
            'data' => $sales->toArray()
        ];
    }

    public function getChartData(Request $request)
    {
        $vendorId = auth()->id();
        $period = $request->get('period', 'week');
        
        switch ($period) {
            case 'week':
                $data = VendorOrder::where('vendor_id', $vendorId)
                    ->where('created_at', '>=', Carbon::now()->subDays(6))
                    ->selectRaw('DATE(created_at) as date, SUM(vendor_earning) as daily_sales')
                    ->groupBy('date')
                    ->orderBy('date')
                    ->get();
                break;
            case 'month':
                $data = VendorOrder::where('vendor_id', $vendorId)
                    ->where('created_at', '>=', Carbon::now()->subDays(29))
                    ->selectRaw('DATE(created_at) as date, SUM(vendor_earning) as daily_sales')
                    ->groupBy('date')
                    ->orderBy('date')
                    ->get();
                break;
            case 'quarter':
                $data = VendorOrder::where('vendor_id', $vendorId)
                    ->where('created_at', '>=', Carbon::now()->subMonths(3))
                    ->selectRaw('WEEK(created_at) as week, SUM(vendor_earning) as weekly_sales')
                    ->groupBy('week')
                    ->orderBy('week')
                    ->get();
                break;
        }

        return response()->json($this->prepareChartData($data));
    }
}
