<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\PujaKit;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Get customer statistics
        $totalOrders = Order::where('user_id', $user->id)->count();
        $pendingOrders = Order::where('user_id', $user->id)->where('status', 'pending')->count();
        $completedOrders = Order::where('user_id', $user->id)->where('status', 'delivered')->count();
        $totalSpent = Order::where('user_id', $user->id)
            ->where('payment_status', 'paid')
            ->sum('total_amount');
        
        // Get recent orders
        $recentOrders = Order::where('user_id', $user->id)
            ->with(['orderItems.product', 'orderItems.pujaKit'])
            ->latest()
            ->take(5)
            ->get();
        
        // Get featured products
        $featuredProducts = Product::where('is_active', true)
            ->where('is_featured', true)
            ->where('approval_status', 'approved')
            ->inRandomOrder()
            ->take(6)
            ->get();
        
        // Get popular puja kits (just get active kits, ordered by latest)
        $popularKits = PujaKit::where('is_active', true)
        ->with(['products', 'pujas']) // Eager load relationships
            ->latest()
            ->take(6)
            ->get();
        
        // Get categories
        $categories = Category::where('is_active', true)
            ->withCount('products')
            ->take(8)
            ->get();
        
        return view('dashboard.customer', compact(
            'user',
            'totalOrders',
            'pendingOrders',
            'completedOrders',
            'totalSpent',
            'recentOrders',
            'featuredProducts',
            'popularKits',
            'categories'
        ));
    }
}
