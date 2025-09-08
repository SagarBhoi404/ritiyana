<!-- resources/views/admin/products/edit.blade.php -->
@extends('layouts.admin')

@section('title', 'Edit Product')
@section('breadcrumb', 'Products / Edit Product')

@section('content')
<div class="px-4 sm:px-6 lg:px-8 py-8">
    <!-- Page Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Edit Product</h1>
                <p class="mt-2 text-sm text-gray-700">Update product information and settings</p>
            </div>
            <a href="{{ route('admin.products.show', $product) }}" class="inline-flex items-center px-4 py-2 bg-gray-100 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-200">
                <i data-lucide="arrow-left" class="w-4 h-4 mr-2"></i>
                Back to Product
            </a>
        </div>
    </div>

    <!-- Display Errors -->
    @if ($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6">
            <div class="flex">
                <i data-lucide="alert-circle" class="w-5 h-5 mr-2 mt-0.5"></i>
                <div>
                    <h4 class="font-medium">Please fix the following errors:</h4>
                    <ul class="mt-2 text-sm list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @endif

    <!-- Form -->
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm">
        <form method="POST" action="{{ route('admin.products.update', $product) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="p-8">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Left Column -->
                    <div class="space-y-6">
                        <!-- Product Information -->
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                <i data-lucide="package" class="w-5 h-5 mr-2"></i>
                                Product Information
                            </h3>
                            
                            <div class="space-y-4">
                                <!-- Product Name -->
                                <div>
                                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                        Product Name <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" 
                                           name="name" 
                                           id="name" 
                                           value="{{ old('name', $product->name) }}"
                                           required 
                                           maxlength="255"
                                           class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                                    @error('name')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- SKU -->
                                <div>
                                    <label for="sku" class="block text-sm font-medium text-gray-700 mb-2">
                                        SKU (Stock Keeping Unit) <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" 
                                           name="sku" 
                                           id="sku" 
                                           value="{{ old('sku', $product->sku) }}"
                                           required 
                                           maxlength="255"
                                           class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                                    @error('sku')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Product Type -->
                                <div>
                                    <label for="type" class="block text-sm font-medium text-gray-700 mb-2">
                                        Product Type <span class="text-red-500">*</span>
                                    </label>
                                    <select name="type" 
                                            id="type" 
                                            required
                                            class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                                        <option value="">Select product type</option>
                                        <option value="simple" {{ old('type', $product->type) == 'simple' ? 'selected' : '' }}>Simple Product</option>
                                        <option value="kit" {{ old('type', $product->type) == 'kit' ? 'selected' : '' }}>Puja Kit</option>
                                        <option value="variable" {{ old('type', $product->type) == 'variable' ? 'selected' : '' }}>Variable Product</option>
                                    </select>
                                    @error('type')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Categories -->
                                <div>
                                    <label for="categories" class="block text-sm font-medium text-gray-700 mb-2">
                                        Categories <span class="text-red-500">*</span>
                                    </label>
                                    <select name="categories[]" 
                                            id="categories" 
                                            multiple
                                            required
                                            class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" 
                                                    {{ in_array($category->id, old('categories', $product->categories->pluck('id')->toArray())) ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <p class="text-xs text-gray-500 mt-1">Hold Ctrl/Cmd to select multiple categories</p>
                                    @error('categories')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Pricing -->
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                <i data-lucide="indian-rupee" class="w-5 h-5 mr-2"></i>
                                Pricing
                            </h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <!-- Regular Price -->
                                <div>
                                    <label for="price" class="block text-sm font-medium text-gray-700 mb-2">
                                        Regular Price <span class="text-red-500">*</span>
                                    </label>
                                    <input type="number" 
                                           name="price" 
                                           id="price" 
                                           value="{{ old('price', $product->price) }}"
                                           min="0"
                                           step="0.01"
                                           required
                                           class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                                    @error('price')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Sale Price -->
                                <div>
                                    <label for="sale_price" class="block text-sm font-medium text-gray-700 mb-2">
                                        Sale Price
                                    </label>
                                    <input type="number" 
                                           name="sale_price" 
                                           id="sale_price" 
                                           value="{{ old('sale_price', $product->sale_price) }}"
                                           min="0"
                                           step="0.01"
                                           class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                                    @error('sale_price')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Cost Price -->
                                <div>
                                    <label for="cost_price" class="block text-sm font-medium text-gray-700 mb-2">
                                        Cost Price
                                    </label>
                                    <input type="number" 
                                           name="cost_price" 
                                           id="cost_price" 
                                           value="{{ old('cost_price', $product->cost_price) }}"
                                           min="0"
                                           step="0.01"
                                           class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                                    @error('cost_price')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Physical Properties -->
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                <i data-lucide="ruler" class="w-5 h-5 mr-2"></i>
                                Physical Properties
                            </h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <!-- Weight -->
                                <div>
                                    <label for="weight" class="block text-sm font-medium text-gray-700 mb-2">
                                        Weight (kg)
                                    </label>
                                    <input type="number" 
                                           name="weight" 
                                           id="weight" 
                                           value="{{ old('weight', $product->weight) }}"
                                           min="0"
                                           step="0.01"
                                           class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                                    @error('weight')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Dimensions -->
                                <div>
                                    <label for="dimensions" class="block text-sm font-medium text-gray-700 mb-2">
                                        Dimensions
                                    </label>
                                    <input type="text" 
                                           name="dimensions" 
                                           id="dimensions" 
                                           value="{{ old('dimensions', $product->dimensions) }}"
                                           placeholder="L x W x H"
                                           class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                                    @error('dimensions')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="space-y-6">
                        <!-- Inventory -->
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                <i data-lucide="warehouse" class="w-5 h-5 mr-2"></i>
                                Inventory
                            </h3>
                            
                            <div class="space-y-4">
                                <!-- Stock Quantity -->
                                <div>
                                    <label for="stock_quantity" class="block text-sm font-medium text-gray-700 mb-2">
                                        Stock Quantity <span class="text-red-500">*</span>
                                    </label>
                                    <input type="number" 
                                           name="stock_quantity" 
                                           id="stock_quantity" 
                                           value="{{ old('stock_quantity', $product->stock_quantity) }}"
                                           min="0"
                                           required
                                           class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                                    @error('stock_quantity')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Stock Status -->
                                <div>
                                    <label for="stock_status" class="block text-sm font-medium text-gray-700 mb-2">
                                        Stock Status <span class="text-red-500">*</span>
                                    </label>
                                    <select name="stock_status" 
                                            id="stock_status" 
                                            required
                                            class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                                        <option value="">Select stock status</option>
                                        <option value="in_stock" {{ old('stock_status', $product->stock_status) == 'in_stock' ? 'selected' : '' }}>In Stock</option>
                                        <option value="out_of_stock" {{ old('stock_status', $product->stock_status) == 'out_of_stock' ? 'selected' : '' }}>Out of Stock</option>
                                        <option value="on_backorder" {{ old('stock_status', $product->stock_status) == 'on_backorder' ? 'selected' : '' }}>On Backorder</option>
                                    </select>
                                    @error('stock_status')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Manage Stock -->
                                <div class="flex items-center">
                                    <input id="manage_stock" 
                                           name="manage_stock" 
                                           type="checkbox" 
                                           value="1"
                                           {{ old('manage_stock', $product->manage_stock) ? 'checked' : '' }}
                                           class="h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300 rounded">
                                    <label for="manage_stock" class="ml-2 block text-sm text-gray-900">
                                        Manage stock quantity
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Images -->
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                <i data-lucide="image" class="w-5 h-5 mr-2"></i>
                                Product Images
                            </h3>
                            
                            <!-- Featured Image -->
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Featured Image
                                </label>
                                
                                <!-- Current Image -->
                                @if($product->featured_image)
                                    <div class="mb-4">
                                        <img src="{{ asset('storage/' . $product->featured_image) }}" 
                                             alt="Current featured image" 
                                             class="w-20 h-20 rounded object-cover border-4 border-gray-200">
                                        <p class="text-xs text-gray-500 mt-1">Current featured image</p>
                                    </div>
                                @endif
                                
                                <!-- File Upload -->
                                <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-purple-400 transition-colors">
                                    <i data-lucide="upload" class="w-12 h-12 mx-auto text-gray-400 mb-4"></i>
                                    <div class="text-sm text-gray-600 mb-2">
                                        <label for="featured_image" class="relative cursor-pointer bg-white rounded-md font-medium text-purple-600 hover:text-purple-500">
                                            <span>Upload new featured image</span>
                                            <input id="featured_image" name="featured_image" type="file" accept="image/*" class="sr-only" onchange="previewImage(this, 'featuredPreview')">
                                        </label>
                                    </div>
                                    <p class="text-xs text-gray-500">PNG, JPG, WEBP up to 2MB</p>
                                    
                                    <!-- New Image Preview -->
                                    <div id="featuredPreview" class="mt-4 hidden">
                                        <img id="featuredPreviewImg" src="" alt="Preview" class="mx-auto h-20 w-20 rounded object-cover">
                                        <button type="button" onclick="removeImage('featuredPreview', 'featured_image')" class="mt-2 text-red-500 text-sm hover:text-red-700">Remove new image</button>
                                    </div>
                                </div>
                                @error('featured_image')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Gallery Images -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Gallery Images
                                </label>
                                
                                <!-- Current Gallery -->
                                @if($product->gallery_images && count($product->gallery_images) > 0)
                                    <div class="mb-4">
                                        <p class="text-xs text-gray-500 mb-2">Current gallery images</p>
                                        <div class="grid grid-cols-4 gap-2">
                                            @foreach($product->gallery_images as $image)
                                                <img src="{{ asset('storage/' . $image) }}" 
                                                     alt="Gallery image" 
                                                     class="w-16 h-16 rounded object-cover border-2 border-gray-200">
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                                
                                <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-purple-400 transition-colors">
                                    <i data-lucide="images" class="w-12 h-12 mx-auto text-gray-400 mb-4"></i>
                                    <div class="text-sm text-gray-600 mb-2">
                                        <label for="gallery_images" class="relative cursor-pointer bg-white rounded-md font-medium text-purple-600 hover:text-purple-500">
                                            <span>Upload new gallery images</span>
                                            <input id="gallery_images" name="gallery_images[]" type="file" accept="image/*" multiple class="sr-only">
                                        </label>
                                    </div>
                                    <p class="text-xs text-gray-500">PNG, JPG, WEBP up to 2MB each</p>
                                </div>
                                @error('gallery_images.*')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Product Status -->
                        <div class="bg-gray-50 rounded-lg p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                <i data-lucide="settings" class="w-5 h-5 mr-2"></i>
                                Product Status
                            </h3>
                            
                            <div class="space-y-3">
                                <div class="flex items-center">
                                    <input id="is_featured" 
                                           name="is_featured" 
                                           type="checkbox" 
                                           value="1"
                                           {{ old('is_featured', $product->is_featured) ? 'checked' : '' }}
                                           class="h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300 rounded">
                                    <label for="is_featured" class="ml-2 block text-sm text-gray-900">
                                        Featured product
                                    </label>
                                </div>
                                
                                <div class="flex items-center">
                                    <input id="is_active" 
                                           name="is_active" 
                                           type="checkbox" 
                                           value="1"
                                           {{ old('is_active', $product->is_active) ? 'checked' : '' }}
                                           class="h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300 rounded">
                                    <label for="is_active" class="ml-2 block text-sm text-gray-900">
                                        Product is active
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Descriptions -->
                <div class="mt-8">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <i data-lucide="file-text" class="w-5 h-5 mr-2"></i>
                        Product Description
                    </h3>
                    
                    <div class="grid grid-cols-1 gap-6">
                        <!-- Short Description -->
                        <div>
                            <label for="short_description" class="block text-sm font-medium text-gray-700 mb-2">
                                Short Description
                            </label>
                            <textarea name="short_description" 
                                      id="short_description" 
                                      rows="3" 
                                      maxlength="500"
                                      class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">{{ old('short_description', $product->short_description) }}</textarea>
                            @error('short_description')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Full Description -->
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                                Full Description
                            </label>
                            <textarea name="description" 
                                      id="description" 
                                      rows="6" 
                                      class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">{{ old('description', $product->description) }}</textarea>
                            @error('description')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- SEO & Meta -->
                <div class="mt-8">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <i data-lucide="search" class="w-5 h-5 mr-2"></i>
                        SEO & Meta Information
                    </h3>
                    
                    <div class="grid grid-cols-1 gap-6">
                        <!-- Meta Title -->
                        <div>
                            <label for="meta_title" class="block text-sm font-medium text-gray-700 mb-2">
                                Meta Title
                            </label>
                            <input type="text" 
                                   name="meta_title" 
                                   id="meta_title" 
                                   value="{{ old('meta_title', $product->meta_title) }}"
                                   maxlength="255"
                                   class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                            @error('meta_title')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Meta Description -->
                        <div>
                            <label for="meta_description" class="block text-sm font-medium text-gray-700 mb-2">
                                Meta Description
                            </label>
                            <textarea name="meta_description" 
                                      id="meta_description" 
                                      rows="3" 
                                      maxlength="500"
                                      class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">{{ old('meta_description', $product->meta_description) }}</textarea>
                            @error('meta_description')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="border-t border-gray-200 px-8 py-6 bg-gray-50 rounded-b-2xl">
                <div class="flex items-center justify-between">
                    <div class="text-sm text-gray-500">
                        <span class="text-red-500">*</span> Required fields
                    </div>
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('admin.products.show', $product) }}" class="px-6 py-3 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2">
                            Cancel
                        </a>
                        <button type="submit" class="inline-flex items-center px-6 py-3 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-purple-600 hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2">
                            <i data-lucide="save" class="w-4 h-4 mr-2"></i>
                            Update Product
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
// Auto-generate slug from name
document.getElementById('name').addEventListener('input', function() {
    const slug = this.value
        .toLowerCase()
        .replace(/[^a-z0-9\s-]/g, '')
        .replace(/\s+/g, '-')
        .trim();
    // If you have a slug field, uncomment the next line
    // document.getElementById('slug').value = slug;
});

// Image preview functionality
function previewImage(input, previewId) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const imagePreview = document.getElementById(previewId);
            const previewImg = document.getElementById(previewId + 'Img');
            previewImg.src = e.target.result;
            imagePreview.classList.remove('hidden');
        }
        reader.readAsDataURL(input.files[0]);
    }
}

function removeImage(previewId, inputId) {
    const input = document.getElementById(inputId);
    const imagePreview = document.getElementById(previewId);
    input.value = '';
    imagePreview.classList.add('hidden');
}
</script>
@endsection
