<?php

// app/Http/Controllers/Admin/ProductController.php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

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
        
        // Validate separate dimension inputs
        'length' => 'nullable|numeric|min:0',
        'width' => 'nullable|numeric|min:0',
        'height' => 'nullable|numeric|min:0',
        
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
    $featuredImagePath = null;
    if ($request->hasFile('featured_image')) {
        $featuredImagePath = $request->file('featured_image')->store('products/featured', 'public');
    }

    // Handle gallery images upload
    $galleryImages = [];
    if ($request->hasFile('gallery_images')) {
        foreach ($request->file('gallery_images') as $image) {
            $galleryImages[] = $image->store('products/gallery', 'public');
        }
    }

    // Combine L, W, H into single dimensions string
    $dimensions = $this->combineDimensions($request->length, $request->width, $request->height);

    // Create product with combined data
    $product = Product::create([
        'name' => $validated['name'],
        'sku' => $validated['sku'],
        'type' => $validated['type'],
        'price' => $validated['price'],
        'sale_price' => $validated['sale_price'],
        'cost_price' => $validated['cost_price'],
        'stock_quantity' => $validated['stock_quantity'],
        'manage_stock' => $request->boolean('manage_stock'),
        'stock_status' => $validated['stock_status'],
        'short_description' => $validated['short_description'],
        'description' => $validated['description'],
        'weight' => $validated['weight'],
        'dimensions' => $dimensions, // Store as single column
        'featured_image' => $featuredImagePath,
        'gallery_images' => $galleryImages,
        'is_featured' => $request->boolean('is_featured'),
        'is_active' => $request->boolean('is_active'),
        'meta_title' => $validated['meta_title'],
        'meta_description' => $validated['meta_description'],
        'created_by' => auth()->id(),
        'slug' => Str::slug($validated['name']),
    ]);

    // Attach categories
    $product->categories()->attach($validated['categories']);

    return redirect()->route('admin.products.index')
        ->with('success', 'Product created successfully');
}

/**
 * Combine separate L, W, H values into single dimensions string
 */
private function combineDimensions($length, $width, $height)
{
    // Filter out empty/null values and convert to float
    $dimensions = [];
    
    if (!is_null($length) && $length !== '' && $length > 0) {
        $dimensions[] = floatval($length);
    }
    if (!is_null($width) && $width !== '' && $width > 0) {
        $dimensions[] = floatval($width);
    }
    if (!is_null($height) && $height !== '' && $height > 0) {
        $dimensions[] = floatval($height);
    }

    if (empty($dimensions)) {
        return null;
    }

    // Format as "L x W x H cm"
    if (count($dimensions) == 3) {
        return "{$dimensions[0]} x {$dimensions[1]} x {$dimensions[2]} cm";
    } else {
        return implode(' x ', $dimensions) . ' cm';
    }
}


  public function show(Product $product)
{
    $product->load(['categories', 'creator', 'vendor.vendorProfile']);
    
    // Add helper method to product for image URLs
    $product->image_url_helper = function($imagePath) {
        if (app()->environment('production')) {
            return url('public/storage/' . $imagePath);
        } else {
            return asset('storage/' . $imagePath);
        }
    };
    
    return view('admin.products.show', compact('product'));
}


   public function edit(Product $product)
{
    $categories = Category::where('is_active', true)->orderBy('name')->get();
    
    // Parse dimensions for form display
    $dimensionsArray = $product->parseDimensions();
    
    return view('admin.products.edit', [
        'product' => $product,
        'categories' => $categories,
        'lengthValue' => $dimensionsArray['length'],
        'widthValue' => $dimensionsArray['width'], 
        'heightValue' => $dimensionsArray['height'],
    ]);
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
        
        // REPLACE 'dimensions' with separate inputs
        'length' => 'nullable|numeric|min:0',
        'width' => 'nullable|numeric|min:0',
        'height' => 'nullable|numeric|min:0',
        
        'featured_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        'gallery_images.*' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
        'meta_title' => 'nullable|string|max:255',
        'meta_description' => 'nullable|string|max:500',
        'categories' => 'required|array|min:1',
        'categories.*' => 'exists:categories,id',
    ]);

    // Prepare update data
    $updateData = [
        'name' => $validated['name'],
        'short_description' => $validated['short_description'],
        'description' => $validated['description'],
        'sku' => $validated['sku'],
        'type' => $validated['type'],
        'price' => $validated['price'],
        'sale_price' => $validated['sale_price'],
        'cost_price' => $validated['cost_price'],
        'stock_quantity' => $validated['stock_quantity'],
        'manage_stock' => $request->boolean('manage_stock'),
        'stock_status' => $validated['stock_status'],
        'weight' => $validated['weight'],
        'is_featured' => $request->boolean('is_featured'),
        'is_active' => $request->boolean('is_active'),
        'meta_title' => $validated['meta_title'],
        'meta_description' => $validated['meta_description'],
    ];

    // Combine L, W, H into single dimensions string
    $dimensions = $this->combineDimensions($request->length, $request->width, $request->height);
    $updateData['dimensions'] = $dimensions;

    // Handle featured image upload
    if ($request->hasFile('featured_image')) {
        // Delete old image
        if ($product->featured_image && \Storage::disk('public')->exists($product->featured_image)) {
            \Storage::disk('public')->delete($product->featured_image);
        }
        $updateData['featured_image'] = $request->file('featured_image')->store('products/featured', 'public');
    }

    // Handle gallery images upload
    if ($request->hasFile('gallery_images')) {
        // Delete old gallery images
        if ($product->gallery_images && is_array($product->gallery_images)) {
            foreach ($product->gallery_images as $image) {
                if (\Storage::disk('public')->exists($image)) {
                    \Storage::disk('public')->delete($image);
                }
            }
        }
        $galleryImages = [];
        foreach ($request->file('gallery_images') as $image) {
            $galleryImages[] = $image->store('products/gallery', 'public');
        }
        $updateData['gallery_images'] = $galleryImages;
    }

    // Update product
    $product->update($updateData);

    // Sync categories
    $product->categories()->sync($validated['categories']);

    return redirect()->route('admin.products.index')
        ->with('success', 'Product updated successfully');
}

/**
 * Combine separate L, W, H values into single dimensions string
 */
// private function combineDimensions($length, $width, $height)
// {
//     // Filter out empty/null values and convert to float
//     $dimensions = [];
    
//     if (!is_null($length) && $length !== '' && $length > 0) {
//         $dimensions[] = floatval($length);
//     }
//     if (!is_null($width) && $width !== '' && $width > 0) {
//         $dimensions[] = floatval($width);
//     }
//     if (!is_null($height) && $height !== '' && $height > 0) {
//         $dimensions[] = floatval($height);
//     }

//     if (empty($dimensions)) {
//         return null;
//     }

//     // Format as "L x W x H cm"
//     if (count($dimensions) == 3) {
//         return "{$dimensions[0]} x {$dimensions[1]} x {$dimensions[2]} cm";
//     } else {
//         return implode(' x ', $dimensions) . ' cm';
//     }
// }

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
        if (! $product->is_vendor_product) {
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
        if (! $product->is_vendor_product) {
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
