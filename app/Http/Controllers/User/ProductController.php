<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductController extends Controller
{
      public function index(Request $request)
    {
        $query = Product::active()
            ->approved()
            ->with(['categories', 'vendor']);

        // Filter by category
        if ($request->has('category') && $request->category) {
            $query->whereHas('categories', function($q) use ($request) {
                $q->where('slug', $request->category)
                  ->orWhere('name', 'like', '%' . $request->category . '%');
            });
        }

        // Filter by search
        if ($request->has('search') && $request->search) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%')
                  ->orWhereHas('categories', function($categoryQuery) use ($request) {
                      $categoryQuery->where('name', 'like', '%' . $request->search . '%');
                  });
            });
        }

        // Filter by price range
        if ($request->has('min_price') && $request->min_price) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->has('max_price') && $request->max_price) {
            $query->where('price', '<=', $request->max_price);
        }

        // Apply sorting
        $sort = $request->get('sort', 'featured');
        switch ($sort) {
            case 'price_low':
                $query->orderByRaw('COALESCE(sale_price, price) ASC');
                break;
            case 'price_high':
                $query->orderByRaw('COALESCE(sale_price, price) DESC');
                break;
            case 'newest':
                $query->orderBy('created_at', 'desc');
                break;
            case 'name':
                $query->orderBy('name', 'asc');
                break;
            case 'discount':
                $query->orderByRaw('CASE WHEN sale_price IS NOT NULL THEN ((price - sale_price) / price * 100) ELSE 0 END DESC');
                break;
            case 'featured':
            default:
                $query->orderBy('is_featured', 'desc')
                      ->orderBy('created_at', 'desc');
                break;
        }

        $products = $query->paginate(12)->withQueryString();

        // Get categories for filter sidebar with product counts
        $categories = Category::active()
            ->whereHas('products', function($q) {
                $q->active()->approved();
            })
            ->withCount(['products' => function($q) {
                $q->active()->approved();
            }])
            ->orderBy('products_count', 'desc')
            ->get();

        // Get price range for filters
        $priceRange = Product::active()
            ->approved()
            ->selectRaw('MIN(COALESCE(sale_price, price)) as min_price, MAX(COALESCE(sale_price, price)) as max_price')
            ->first();

        // Get total products count
        $totalProducts = Product::active()->approved()->count();

        return view('products', compact(
            'products', 
            'categories', 
            'priceRange', 
            'totalProducts'
        ));
    }

    /**
     * Display the specified product
     */
    public function show(Product $product)
    {
        // Product is already resolved and validated by route model binding
        // Load relationships
        $product->load([
            'categories', 
            'vendor', 
            'reviews.user', 
            'pujaKits'
        ]);

        // Get related products from same categories
        $relatedProducts = Product::active()
            ->approved()
            ->where('id', '!=', $product->id)
            ->whereHas('categories', function($query) use ($product) {
                $query->whereIn('categories.id', $product->categories->pluck('id'));
            })
            ->with(['categories', 'vendor'])
            ->limit(8)
            ->get();

        // Get recently viewed products
        $recentlyViewed = $this->getRecentlyViewedProducts($product->id);

        // Get product images (if you have a separate images table)
        $productImages = $this->getProductImages($product);

        return view('product-detail', compact(
            'product', 
            'relatedProducts', 
            'recentlyViewed',
            'productImages'
        ));
    }

    

    private function getProductImages($product)
    {
        // If you have a separate product_images table, use this
        // return $product->images()->orderBy('sort_order')->get();
        
        // For now, return a default set using the featured image
        $images = [];
        if ($product->featured_image) {
            $images[] = [
                'url' => asset('storage/' . $product->featured_image),
                'alt' => $product->name,
                'is_featured' => true
            ];
        }
        
        // Add default images for demo
        $defaultImages = [
            ['url' => asset('images/puja-items-1.jpg'), 'alt' => 'Product view 1'],
            ['url' => asset('images/puja-items-2.jpg'), 'alt' => 'Product view 2'],
            ['url' => asset('images/puja-items-3.jpg'), 'alt' => 'Product view 3'],
        ];
        
        return array_merge($images, $defaultImages);
    }

    /**
     * Get recently viewed products from session
     */
    private function getRecentlyViewedProducts($currentProductId)
    {
        $recentlyViewed = session()->get('recently_viewed', []);
        
        // Add current product to recently viewed
        $recentlyViewed = array_filter($recentlyViewed, function($id) use ($currentProductId) {
            return $id != $currentProductId;
        });
        
        array_unshift($recentlyViewed, $currentProductId);
        $recentlyViewed = array_slice($recentlyViewed, 0, 10);
        
        session()->put('recently_viewed', $recentlyViewed);

        return Product::active()
            ->approved()
            ->whereIn('id', array_slice($recentlyViewed, 1))
            ->with(['categories', 'vendor'])
            ->limit(6)
            ->get();
    }
}
