<!-- resources/views/shopkeeper/products/create.blade.php -->
@extends('layouts.shopkeeper')

@section('title', 'Add Product')
@section('breadcrumb', 'Products / Add Product')

@section('content')
<div class="px-4 sm:px-6 lg:px-8 py-8">
    <!-- Page Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Add New Product</h1>
                <p class="mt-2 text-sm text-gray-700">Create a new product listing for your store</p>
            </div>
            <a href="{{ route('shopkeeper.products.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-200">
                <i data-lucide="arrow-left" class="w-4 h-4 mr-2"></i>
                Back to Products
            </a>
        </div>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm">
        <form method="POST" action="#" enctype="multipart/form-data">
            @csrf
            
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
                                           value="{{ old('name') }}"
                                           placeholder="Enter product name"
                                           required 
                                           maxlength="255"
                                           class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                                </div>

                                <!-- Category -->
                                <div>
                                    <label for="category_id" class="block text-sm font-medium text-gray-700 mb-2">
                                        Category <span class="text-red-500">*</span>
                                    </label>
                                    <select name="category_id" 
                                            id="category_id" 
                                            required
                                            class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                                        <option value="">Select a category</option>
                                        <option value="1" {{ old('category_id') == '1' ? 'selected' : '' }}>Puja Diyas</option>
                                        <option value="2" {{ old('category_id') == '2' ? 'selected' : '' }}>Incense Sticks</option>
                                        <option value="3" {{ old('category_id') == '3' ? 'selected' : '' }}>Flowers & Garlands</option>
                                        <option value="4" {{ old('category_id') == '4' ? 'selected' : '' }}>Puja Kits</option>
                                        <option value="5" {{ old('category_id') == '5' ? 'selected' : '' }}>Religious Books</option>
                                        <option value="6" {{ old('category_id') == '6' ? 'selected' : '' }}>Idols & Statues</option>
                                    </select>
                                </div>

                                <!-- SKU -->
                                <div>
                                    <label for="sku" class="block text-sm font-medium text-gray-700 mb-2">
                                        SKU (Stock Keeping Unit)
                                    </label>
                                    <input type="text" 
                                           name="sku" 
                                           id="sku" 
                                           value="{{ old('sku') }}"
                                           placeholder="e.g., PD-001, IN-002"
                                           maxlength="50"
                                           class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                                    <p class="text-xs text-gray-500 mt-1">Leave blank to auto-generate</p>
                                </div>
                            </div>
                        </div>

                        <!-- Description -->
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                                Product Description <span class="text-red-500">*</span>
                            </label>
                            <textarea name="description" 
                                      id="description" 
                                      rows="6" 
                                      required
                                      placeholder="Describe your product in detail"
                                      class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">{{ old('description') }}</textarea>
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="space-y-6">
                        <!-- Pricing -->
                        <div class="bg-gray-50 rounded-lg p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                <i data-lucide="indian-rupee" class="w-5 h-5 mr-2"></i>
                                Pricing
                            </h3>
                            <div class="space-y-4">
                                <div>
                                    <label for="price" class="block text-sm font-medium text-gray-700 mb-2">
                                        Price <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500">₹</span>
                                        <input type="number" 
                                               name="price" 
                                               id="price" 
                                               value="{{ old('price') }}"
                                               placeholder="0.00"
                                               min="0" 
                                               step="0.01" 
                                               required
                                               class="block w-full pl-8 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                                    </div>
                                </div>
                                
                                <div>
                                    <label for="discount_price" class="block text-sm font-medium text-gray-700 mb-2">
                                        Discount Price (Optional)
                                    </label>
                                    <div class="relative">
                                        <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500">₹</span>
                                        <input type="number" 
                                               name="discount_price" 
                                               id="discount_price" 
                                               value="{{ old('discount_price') }}"
                                               placeholder="0.00"
                                               min="0" 
                                               step="0.01"
                                               class="block w-full pl-8 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Inventory -->
                        <div class="bg-gray-50 rounded-lg p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                <i data-lucide="package" class="w-5 h-5 mr-2"></i>
                                Inventory
                            </h3>
                            <div class="space-y-4">
                                <div>
                                    <label for="stock_quantity" class="block text-sm font-medium text-gray-700 mb-2">
                                        Stock Quantity <span class="text-red-500">*</span>
                                    </label>
                                    <input type="number" 
                                           name="stock_quantity" 
                                           id="stock_quantity" 
                                           value="{{ old('stock_quantity') }}"
                                           placeholder="0"
                                           min="0" 
                                           required
                                           class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                                </div>
                                
                                <div>
                                    <label for="reorder_level" class="block text-sm font-medium text-gray-700 mb-2">
                                        Reorder Level
                                    </label>
                                    <input type="number" 
                                           name="reorder_level" 
                                           id="reorder_level" 
                                           value="{{ old('reorder_level', '5') }}"
                                           placeholder="5"
                                           min="0"
                                           class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                                    <p class="text-xs text-gray-500 mt-1">Alert when stock reaches this level</p>
                                </div>
                            </div>
                        </div>

                        <!-- Product Image -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Product Image
                            </label>
                            <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-purple-400 transition-colors">
                                <i data-lucide="upload" class="w-12 h-12 mx-auto text-gray-400 mb-4"></i>
                                <div class="text-sm text-gray-600 mb-2">
                                    <label for="image" class="relative cursor-pointer bg-white rounded-md font-medium text-purple-600 hover:text-purple-500">
                                        <span>Upload a file</span>
                                        <input id="image" name="image" type="file" accept="image/*" class="sr-only">
                                    </label>
                                    <span class="pl-1">or drag and drop</span>
                                </div>
                                <p class="text-xs text-gray-500">PNG, JPG, WEBP up to 2MB</p>
                            </div>
                        </div>

                        <!-- Status -->
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                                Status
                            </label>
                            <select name="status" 
                                    id="status"
                                    class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                                <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                            </select>
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
                        <button type="button" class="px-6 py-3 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2">
                            Save as Draft
                        </button>
                        <button type="submit" class="inline-flex items-center px-6 py-3 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-purple-600 hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2">
                            <i data-lucide="plus" class="w-4 h-4 mr-2"></i>
                            Create Product
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
