<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Category;
use App\Models\Product;
use App\Models\PujaKit;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function home()
    {
        // Fetch popular products (featured or random selection)
        $products = Product::active()
            ->approved()
            ->featured()
            ->with(['categories', 'vendor'])
            ->limit(10)
            ->get();

        // If no featured products, get active products
        if ($products->isEmpty()) {
            $products = Product::active()
                ->approved()
                ->with(['categories', 'vendor'])
                ->inRandomOrder()
                ->limit(10)
                ->get();
        }

        // Fetch popular puja kits
        $pujaKits = PujaKit::active()
            ->with(['pujas', 'products', 'vendor'])
            ->limit(5)
            ->get();

        // Fetch main categories for navigation (parent categories only)
        $categories = Category::active()
            ->whereNull('parent_id') // Only parent categories
            ->with(['children' => function ($query) {
                $query->active()->limit(5); // Limit subcategories
            }])
            ->withCount(['products' => function ($query) {
                $query->active()->approved();
            }])
            ->orderBy('products_count', 'desc')
            ->limit(8) // Show max 8 categories
            ->get();

        // dd($categories);

        $banners = Banner::active()->ordered()->get();

        return view('home', compact('products', 'pujaKits', 'categories', 'banners'));
    }
}
