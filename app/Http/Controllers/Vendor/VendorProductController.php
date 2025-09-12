<?php
// app/Http/Controllers/Vendor/ProductController.php
namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Pest\Support\NullClosure;

class VendorProductController extends Controller
{
    public function __construct() {}

    public function index(Request $request)
    {
        $query = Product::where('vendor_id', auth()->id());

        // Filter by status
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('approval_status', $request->status);
        }

        // Search
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $products = $query->with('categories')->latest()->paginate(15)->withQueryString();

        $stats = [
            'total' => Product::where('vendor_id', auth()->id())->count(),
            'approved' => Product::where('vendor_id', auth()->id())->where('approval_status', 'approved')->count(),
            'pending' => Product::where('vendor_id', auth()->id())->where('approval_status', 'pending')->count(),
            'rejected' => Product::where('vendor_id', auth()->id())->where('approval_status', 'rejected')->count(),
        ];



        return view('shopkeeper.products.index', compact('products', 'stats'));
    }

    public function create()
    {
        $categories = Category::where('is_active', true)->get();
        return view('shopkeeper.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required|string|unique:products,sku|max:255',
            'type' => 'required|in:simple,kit,variable',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0|lt:price',
            'cost_price' => 'nullable|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'manage_stock' => 'boolean',
            'stock_status' => 'required|in:in_stock,out_of_stock,on_backorder',
            'short_description' => 'nullable|string|max:500',
            'description' => 'nullable|string',
            'weight' => 'nullable|numeric|min:0',
            'dimensions' => 'nullable|string|max:255',
            'featured_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'gallery_images.*' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'is_active' => 'boolean',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'categories' => 'required|array|min:1',
            'categories.*' => 'exists:categories,id',
        ]);

        // Handle featured image upload
        if ($request->hasFile('featured_image')) {
            $validated['featured_image'] = $request->file('featured_image')->store('products', 'public');
        }

        // Handle gallery images upload
        if ($request->hasFile('gallery_images')) {
            $galleryImages = [];
            foreach ($request->file('gallery_images') as $image) {
                $galleryImages[] = $image->store('products', 'public');
            }
            $validated['gallery_images'] = $galleryImages;
        }

        $commissionRate = Vendor::getCommissionRateForCurrentUser();

        // Set additional fields
        $validated['created_by'] = auth()->id();
        $validated['vendor_id'] = auth()->id();
        $validated['slug'] = Str::slug($validated['name']);
        $validated['manage_stock'] = $request->boolean('manage_stock');
        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['is_active'] = $request->boolean('is_active');
        $validated['is_vendor_product'] = 1;
        $validated['vendor_commission_rate'] = $commissionRate ?? Null;

        $product = Product::create($validated);

        // Attach categories
        $product->categories()->attach($validated['categories']);

        return redirect()->route('vendor.products.index')
            ->with('success', 'Product created successfully');
    }

    public function show(Product $product)
    {
        $product->load('categories');
        return view('shopkeeper.products.show', compact('product'));
    }


    public function edit(Product $product)
    {
        $categories = Category::where('is_active', true)->get();
        $product->load('categories');
        return view('shopkeeper.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0|lt:price',
            'stock_quantity' => 'required|integer|min:0',
            'short_description' => 'nullable|string|max:500',
            'description' => 'nullable|string',
            'featured_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'categories' => 'required|array|min:1',
            'categories.*' => 'exists:categories,id',
        ]);

        // Handle featured image upload
        if ($request->hasFile('featured_image')) {
            if ($product->featured_image) {
                \Storage::disk('public')->delete($product->featured_image);
            }
            $validated['featured_image'] = $request->file('featured_image')->store('products', 'public');
        }

        $validated['stock_status'] = $validated['stock_quantity'] > 0 ? 'in_stock' : 'out_of_stock';

        // If product was rejected, set it back to pending
        if ($product->approval_status === 'rejected') {
            $validated['approval_status'] = 'pending';
        }

        $product->update($validated);
        $product->categories()->sync($validated['categories']);

        return redirect()->route('vendor.products.index')
            ->with('success', 'Product updated successfully!');
    }

    public function destroy(Product $product)
    {

        // Delete images
        if ($product->featured_image) {
            \Storage::disk('public')->delete($product->featured_image);
        }

        if ($product->gallery_images) {
            foreach ($product->gallery_images as $image) {
                \Storage::disk('public')->delete($image);
            }
        }

        $product->categories()->detach();
        $product->delete();

        return redirect()->route('vendor.products.index')
            ->with('success', 'Product deleted successfully!');
    }
}
