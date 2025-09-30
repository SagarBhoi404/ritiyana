<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\InventoryLog;
use App\Models\User;
use App\Models\Category;
use Illuminate\Http\Request;
use Carbon\Carbon;

class InventoryController extends Controller
{
    public function index(Request $request)
    {
        // Get filter parameters
        $status = $request->get('status', 'all');
        $category = $request->get('category', 'all');
        $vendor = $request->get('vendor', 'all');
        $search = $request->get('search');
        $perPage = $request->get('per_page', 25);

        // Build base query with relationships
        $query = Product::with(['categories', 'vendor', 'inventoryLogs' => function($q) {
            $q->latest()->limit(1);
        }])->where('is_active', true);

        // Apply search filter
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                  ->orWhere('sku', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%");
            });
        }

        // Apply status filter
        switch ($status) {
            case 'in_stock':
                $query->where('stock_quantity', '>', 10);
                break;
            case 'low_stock':
                $query->where('stock_quantity', '<=', 10)
                      ->where('stock_quantity', '>', 0);
                break;
            case 'out_of_stock':
                $query->where('stock_quantity', '=', 0);
                break;
        }

        // Apply category filter
        if ($category !== 'all') {
            $query->whereHas('categories', function($q) use ($category) {
                $q->where('categories.id', $category);
            });
        }

        // Apply vendor filter
        if ($vendor !== 'all') {
            $query->where('vendor_id', $vendor);
        }

        // Get paginated products
        $products = $query->orderBy('name')->paginate($perPage);

        // Calculate inventory statistics
        $stats = $this->getInventoryStats();

        // Get filter options
        $categories = Category::orderBy('name')->get();
        
        // Get vendors (users with shopkeeper role)
        $vendors = User::whereHas('roles', function($query) {
            $query->where('name', 'shopkeeper');
        })->orderBy('first_name')->get();

        return view('admin.inventory.index', compact(
            'products', 
            'stats', 
            'categories', 
            'vendors',
            'status',
            'category',
            'vendor',
            'search'
        ));
    }

    public function show(Product $product)
    {
        // Load product with all necessary relationships
        $product->load([
            'categories',
            'vendor',
            'inventoryLogs' => function($query) {
                $query->with('creator')->latest();
            }
        ]);

        // Get inventory statistics for this product
        $inventoryStats = [
            'total_added' => $product->inventoryLogs()->increases()->sum('quantity_changed'),
            'total_removed' => abs($product->inventoryLogs()->decreases()->sum('quantity_changed')),
            'total_logs' => $product->inventoryLogs()->count(),
            'last_updated' => $product->inventoryLogs()->latest()->first()?->created_at,
        ];

        // Get recent activity (last 30 days)
        $recentLogs = $product->inventoryLogs()
            ->with('creator')
            ->recentLogs(30)
            ->latest()
            ->limit(20)
            ->get();

        // Get stock movement chart data (last 30 days)
        $chartData = $this->getStockMovementData($product);

        // Get low stock alert threshold
        $lowStockThreshold = $product->low_stock_threshold ?? 10;
        $isLowStock = $product->stock_quantity <= $lowStockThreshold;

        return view('admin.inventory.show', compact(
            'product',
            'inventoryStats',
            'recentLogs',
            'chartData',
            'lowStockThreshold',
            'isLowStock'
        ));
    }

    private function getInventoryStats()
    {
        $totalProducts = Product::where('is_active', true)->count();
        $inStock = Product::where('is_active', true)->where('stock_quantity', '>', 10)->count();
        $lowStock = Product::where('is_active', true)
                          ->where('stock_quantity', '<=', 10)
                          ->where('stock_quantity', '>', 0)->count();
        $outOfStock = Product::where('is_active', true)->where('stock_quantity', '=', 0)->count();

        $totalValue = Product::where('is_active', true)
                            ->selectRaw('SUM(stock_quantity * cost_price) as total')
                            ->first()->total ?? 0;

        return [
            'total_products' => $totalProducts,
            'in_stock' => $inStock,
            'low_stock' => $lowStock,
            'out_of_stock' => $outOfStock,
            'in_stock_percentage' => $totalProducts > 0 ? round(($inStock / $totalProducts) * 100, 1) : 0,
            'total_value' => $totalValue,
        ];
    }

    private function getStockMovementData(Product $product)
    {
        $logs = $product->inventoryLogs()
            ->recentLogs(30)
            ->orderBy('created_at')
            ->get();

        $data = [];
        $runningStock = $product->stock_quantity;

        // Work backwards to calculate historical stock levels
        foreach ($logs->reverse() as $log) {
            $runningStock -= $log->quantity_changed;
        }

        // Build chart data
        foreach ($logs as $log) {
            $runningStock += $log->quantity_changed;
            $data[] = [
                'date' => $log->created_at->format('Y-m-d'),
                'stock' => $runningStock,
                'change' => $log->quantity_changed,
                'type' => $log->type,
            ];
        }

        return $data;
    }

    public function updateStock(Request $request, Product $product)
    {
        $request->validate([
            'quantity' => 'required|integer',
            'type' => 'required|in:purchase,sale,return,adjustment,damage',
            'notes' => 'nullable|string|max:500',
        ]);

        $oldStock = $product->stock_quantity;
        $quantityChange = $request->quantity;
        
        // For sales, damage, etc., make it negative
        if (in_array($request->type, ['sale', 'damage'])) {
            $quantityChange = -abs($quantityChange);
        }

        $newStock = $oldStock + $quantityChange;

        // Prevent negative stock
        if ($newStock < 0) {
            return back()->withErrors(['quantity' => 'Stock cannot go below zero.']);
        }

        // Update product stock
        $product->update(['stock_quantity' => $newStock]);

        // Create inventory log
        InventoryLog::create([
            'product_id' => $product->id,
            'type' => $request->type,
            'quantity_changed' => $quantityChange,
            'quantity_before' => $oldStock,
            'quantity_after' => $newStock,
            'notes' => $request->notes,
            'created_by' => auth()->id(),
        ]);

        return back()->with('success', 'Stock updated successfully!');
    }
}
