<?php
// app/Http/Controllers/Admin/ProductController.php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller; 
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with(['categories', 'creator', 'vendor.vendorProfile'])
            ->latest()
            ->paginate(15);
            
        $totalProducts = Product::count();
        $activeProducts = Product::where('is_active', true)->count();
        $featuredProducts = Product::where('is_featured', true)->count();
        $lowStockProducts = Product::where('stock_quantity', '<', 10)->count();
        $outOfStockProducts = Product::where('stock_status', 'out_of_stock')->count();
        $draftProducts = Product::where('is_active', false)->count();

        return view('admin.products.index', compact(
            'products',
            'totalProducts',
            'activeProducts', 
            'featuredProducts',
            'lowStockProducts',
            'outOfStockProducts',
            'draftProducts'
        ));
    }

    public function create()
    {
        $categories = Category::where('is_active', true)->get();
        return view('admin.products.create', compact('categories'));
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
            'is_featured' => 'boolean',
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

        // Set additional fields
        $validated['created_by'] = auth()->id();
        $validated['slug'] = Str::slug($validated['name']);
        $validated['manage_stock'] = $request->boolean('manage_stock');
        $validated['is_featured'] = $request->boolean('is_featured');
        $validated['is_active'] = $request->boolean('is_active');

        $product = Product::create($validated);

        // Attach categories
        $product->categories()->attach($validated['categories']);

        return redirect()->route('admin.products.index')
            ->with('success', 'Product created successfully');
    }


    public function show(Product $product)
    {
        $product->load(['categories', 'creator', 'vendor.vendorProfile']);
        return view('admin.products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        $categories = Category::where('is_active', true)->get();
        $product->load('categories');
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'short_description' => 'nullable|string|max:500',
            'description' => 'nullable|string',
            'sku' => ['required', 'string', 'max:255', Rule::unique('products')->ignore($product->id)],
            'type' => 'required|in:simple,kit,variable',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0|lt:price',
            'cost_price' => 'nullable|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
            'manage_stock' => 'boolean',
            'stock_status' => 'required|in:in_stock,out_of_stock,on_backorder',
            'weight' => 'nullable|numeric|min:0',
            'dimensions' => 'nullable|string|max:255',
            'featured_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'gallery_images.*' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'categories' => 'required|array|min:1',
            'categories.*' => 'exists:categories,id',
        ]);

        // Handle featured image upload
        if ($request->hasFile('featured_image')) {
            // Delete old image
            if ($product->featured_image) {
                \Storage::disk('public')->delete($product->featured_image);
            }
            $validated['featured_image'] = $request->file('featured_image')->store('products', 'public');
        }

        // Handle gallery images upload
        if ($request->hasFile('gallery_images')) {
            // Delete old gallery images
            if ($product->gallery_images) {
                foreach ($product->gallery_images as $image) {
                    \Storage::disk('public')->delete($image);
                }
            }
            $galleryImages = [];
            foreach ($request->file('gallery_images') as $image) {
                $galleryImages[] = $image->store('products', 'public');
            }
            $validated['gallery_images'] = $galleryImages;
        }

        $product->update($validated);

        // Sync categories
        $product->categories()->sync($validated['categories']);

        return redirect()->route('admin.products.index')
            ->with('success', 'Product updated successfully');
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

        // Detach categories
        $product->categories()->detach();

        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', 'Product deleted successfully');
    }

    public function vendorProducts()
{
    $products = Product::where('is_vendor_product', true)
        ->with(['vendor.vendorProfile', 'categories'])
        ->latest()
        ->paginate(15);

    return view('admin.products.vendor-products', compact('products'));
}

public function approveVendorProduct(Request $request, $id)
{
    $product = Product::findOrFail($id);
    
    // Check if product is vendor product
    if (!$product->is_vendor_product) {
        return redirect()->back()->with('error', 'This is not a vendor product!');
    }
    
    $product->update([
        'approval_status' => 'approved',
        'approved_at' => now(),
        'approved_by' => auth()->id(),
        'is_active' => true,
    ]);

    // Optional: Notify vendor
    // event(new ProductApproved($product));

    return redirect()->back()->with('success', 'Vendor product approved successfully!');
}

public function rejectVendorProduct(Request $request, $id)
{
    $product = Product::findOrFail($id);
    
    // Check if product is vendor product
    if (!$product->is_vendor_product) {
        return redirect()->back()->with('error', 'This is not a vendor product!');
    }
    
    $validated = $request->validate([
        'rejection_reason' => 'required|string|max:500',
    ]);
    
    $product->update([
        'approval_status' => 'rejected',
        'rejection_reason' => $validated['rejection_reason'],
        'rejected_at' => now(),
        'rejected_by' => auth()->id(),
    ]);

    // Optional: Notify vendor
    // event(new ProductRejected($product));

    return redirect()->back()->with('success', 'Vendor product rejected successfully!');
}

}
