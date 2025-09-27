<!-- resources/views/vendor/puja-kits/create.blade.php -->
@extends('layouts.shopkeeper')

@section('title', 'Create Puja Kit')
@section('breadcrumb', 'Puja Kits / Create')

@section('content')
<div class="px-4 sm:px-6 lg:px-8 py-8">
    <!-- Page Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Create New Puja Kit</h1>
                <p class="mt-2 text-sm text-gray-700">Create a curated kit for multiple puja ceremonies</p>
            </div>
            <a href="{{ route('vendor.puja-kits.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-200">
                <i data-lucide="arrow-left" class="w-4 h-4 mr-2"></i>
                Back to Kits
            </a>
        </div>
    </div>

    <!-- Display Validation Errors -->
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
        <form method="POST" action="{{ route('vendor.puja-kits.store') }}" id="kitForm" enctype="multipart/form-data">
            @csrf
            
            <div class="p-8">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

                    <!-- Kit Name Field -->
                <div class="mb-6">
                    <label for="kit_name" class="block text-sm font-medium text-gray-700 mb-2">
                        Kit Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('kit_name') border-red-500 @enderror" 
                           id="kit_name" 
                           name="kit_name" 
                           value="{{ old('kit_name') }}" 
                           required>
                    @error('kit_name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Slug Field -->
                <div class="mb-6">
                    <label for="slug" class="block text-sm font-medium text-gray-700 mb-2">
                        Slug (URL friendly name)
                    </label>
                    <input type="text" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('slug') border-red-500 @enderror" 
                           id="slug" 
                           name="slug" 
                           value="{{ old('slug') }}" 
                           placeholder="Auto-generated from kit name if left empty">
                    @error('slug')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Image Upload Field -->
                <div class="mb-6">
                    <label for="image" class="block text-sm font-medium text-gray-700 mb-2">
                        Kit Image
                    </label>
                    <div class="space-y-4">
                        <!-- Image Preview -->
                        <div class="flex justify-center">
                            <img id="image-preview" 
                                 src="{{ asset('images/default-puja-kit.jpg') }}" 
                                 alt="Kit Image Preview" 
                                 class="w-40 h-40 object-cover rounded-lg border-2 border-dashed border-gray-300 bg-gray-50">
                        </div>
                        
                        <!-- File Input -->
                        <div>
                            <input type="file" 
                                   id="image" 
                                   name="image" 
                                   accept="image/jpeg,image/png,image/jpg,image/gif"
                                   class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-purple-50 file:text-purple-700 hover:file:bg-purple-100 @error('image') border-red-500 @enderror">
                            <p class="text-xs text-gray-500 mt-1">Max size: 2MB. Formats: JPEG, PNG, JPG, GIF</p>
                        </div>
                    </div>
                    @error('image')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                
                    <!-- Left Column -->
                    <div class="space-y-6">
                        <!-- Pujas Selection -->
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                <i data-lucide="flame" class="w-5 h-5 mr-2"></i>
                                Select Pujas
                            </h3>
                            
                            <div class="space-y-4">
                                <div>
                                    <label for="pujas" class="block text-sm font-medium text-gray-700 mb-2">
                                        Choose Pujas for this Kit <span class="text-red-500">*</span>
                                    </label>
                                    <div class="space-y-2 max-h-60 overflow-y-auto border border-gray-300 rounded-lg p-4">
                                        @foreach($pujas as $puja)
                                            <label class="flex items-center p-2 hover:bg-gray-50 rounded">
                                                <input type="checkbox" 
                                                       name="pujas[]" 
                                                       value="{{ $puja->id }}"
                                                       {{ in_array($puja->id, old('pujas', [])) ? 'checked' : '' }}
                                                       class="h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300 rounded">
                                                <div class="ml-3 flex-1">
                                                    <div class="flex items-center">
                                                        @if($puja->image)
                                                            <img src="{{ asset('storage/' . $puja->image) }}" alt="{{ $puja->name }}" class="w-8 h-8 rounded object-cover mr-3">
                                                        @else
                                                            <div class="w-8 h-8 bg-orange-100 rounded flex items-center justify-center mr-3">
                                                                <i data-lucide="flame" class="w-4 h-4 text-orange-600"></i>
                                                            </div>
                                                        @endif
                                                        <div>
                                                            <p class="text-sm font-medium text-gray-900">{{ $puja->name }}</p>
                                                            @if($puja->description)
                                                                <p class="text-xs text-gray-500">{{ Str::limit($puja->description, 50) }}</p>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </label>
                                        @endforeach
                                    </div>
                                    <p class="text-xs text-gray-500 mt-1">Select one or more pujas for this kit</p>
                                    @error('pujas')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Kit Description -->
                                <div>
                                    <label for="kit_description" class="block text-sm font-medium text-gray-700 mb-2">
                                        Kit Description
                                    </label>
                                    <textarea name="kit_description" 
                                              id="kit_description" 
                                              rows="4" 
                                              placeholder="Describe this comprehensive puja kit"
                                              class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">{{ old('kit_description') }}</textarea>
                                    @error('kit_description')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="space-y-6">
                        <!-- Products Selection -->
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                <i data-lucide="package" class="w-5 h-5 mr-2"></i>
                                Select Products
                            </h3>
                            
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Choose Products for this Kit <span class="text-red-500">*</span>
                                    </label>
                                    <div class="space-y-2 max-h-60 overflow-y-auto border border-gray-300 rounded-lg p-4">
                                        @foreach($products as $index => $product)
                                            <div class="border border-gray-200 rounded-lg p-3">
                                                <label class="flex items-start">
                                                    <input type="checkbox" 
                                                           name="products[]" 
                                                           value="{{ $product->id }}"
                                                           {{ in_array($product->id, old('products', [])) ? 'checked' : '' }}
                                                           class="h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300 rounded mt-1"
                                                           onchange="toggleProductOptions(this, {{ $index }})">
                                                    <div class="ml-3 flex-1">
                                                        <div class="flex items-center justify-between">
                                                            <div class="flex items-center">
                                                                @if($product->featured_image)
                                                                    <img src="{{ asset('storage/' . $product->featured_image) }}" alt="{{ $product->name }}" class="w-10 h-10 rounded object-cover mr-3">
                                                                @else
                                                                    <div class="w-10 h-10 bg-purple-100 rounded flex items-center justify-center mr-3">
                                                                        <i data-lucide="package" class="w-5 h-5 text-purple-600"></i>
                                                                    </div>
                                                                @endif
                                                                <div>
                                                                    <p class="text-sm font-medium text-gray-900">{{ $product->name }}</p>
                                                                    <p class="text-xs text-gray-500">SKU: {{ $product->sku }}</p>
                                                                </div>
                                                            </div>
                                                            <span class="text-sm font-semibold text-green-600">₹{{ number_format($product->price, 2) }}</span>
                                                        </div>
                                                        
                                                        <!-- Product Options (Quantity & Custom Price) -->
                                                        <div id="product-options-{{ $index }}" class="mt-3 grid grid-cols-2 gap-3 hidden">
                                                            <div>
                                                                <label class="block text-xs font-medium text-gray-600 mb-1">Quantity</label>
                                                                <input type="number" 
                                                                       name="product_quantities[{{ $index }}]" 
                                                                       value="{{ old('product_quantities.' . $index, 1) }}"
                                                                       min="1"
                                                                       class="block w-full px-2 py-1 text-sm border border-gray-300 rounded focus:ring-1 focus:ring-purple-500 focus:border-transparent">
                                                            </div>
                                                            <div>
                                                                <label class="block text-xs font-medium text-gray-600 mb-1">Custom Price</label>
                                                                <input type="number" 
                                                                       name="product_prices[{{ $index }}]" 
                                                                       value="{{ old('product_prices.' . $index) }}"
                                                                       placeholder="{{ $product->price }}"
                                                                       step="0.01"
                                                                       min="0"
                                                                       class="block w-full px-2 py-1 text-sm border border-gray-300 rounded focus:ring-1 focus:ring-purple-500 focus:border-transparent">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </label>
                                            </div>
                                        @endforeach
                                    </div>
                                    <p class="text-xs text-gray-500 mt-1">Select products and optionally customize quantities and prices</p>
                                    @error('products')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Pricing & Settings -->
                        <div class="bg-gray-50 rounded-lg p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                <i data-lucide="tag" class="w-5 h-5 mr-2"></i>
                                Kit Settings
                            </h3>
                            
                            <div class="space-y-4">
                                <!-- Discount Percentage -->
                                <div>
                                    <label for="discount_percentage" class="block text-sm font-medium text-gray-700 mb-2">
                                        Discount Percentage (%)
                                    </label>
                                    <input type="number" 
                                           name="discount_percentage" 
                                           id="discount_percentage" 
                                           value="{{ old('discount_percentage', 0) }}"
                                           min="0"
                                           max="100"
                                           step="0.01"
                                           class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                                    @error('discount_percentage')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Active Status -->
                                <div class="flex items-center">
                                    <input id="is_active" 
                                           name="is_active" 
                                           type="checkbox" 
                                           value="1"
                                           {{ old('is_active', true) ? 'checked' : '' }}
                                           class="h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300 rounded">
                                    <label for="is_active" class="ml-2 block text-sm text-gray-900">
                                        Kit is active
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Included Items -->
                <div class="mt-8">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <i data-lucide="list" class="w-5 h-5 mr-2"></i>
                        Included Items
                    </h3>
                    
                    <div>
                        <label for="included_items_input" class="block text-sm font-medium text-gray-700 mb-2">
                            Additional Items (one per line)
                        </label>
                        <textarea name="included_items_input" 
                                  id="included_items_input" 
                                  rows="6" 
                                  placeholder="Enter additional items included in the kit&#10;Example:&#10;Prayer Instructions&#10;Sacred Thread&#10;Kumkum & Turmeric"
                                  class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">{{ old('included_items_input') ? implode("\n", old('included_items_input')) : '' }}</textarea>
                        <p class="text-xs text-gray-500 mt-1">Enter each additional item on a new line (products are automatically included)</p>
                        @error('included_items')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
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
                        <a href="{{ route('vendor.puja-kits.index') }}" class="px-6 py-3 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2">
                            Cancel
                        </a>
                        <button type="submit" class="inline-flex items-center px-6 py-3 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-purple-600 hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2">
                            <i data-lucide="plus" class="w-4 h-4 mr-2"></i>
                            Create Kit
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
// Image preview functionality
document.getElementById('image').addEventListener('change', function(e) {
    const file = e.target.files[0];
    const preview = document.getElementById('image-preview');
    
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.classList.remove('border-dashed');
            preview.classList.add('border-solid');
        }
        reader.readAsDataURL(file);
    } else {
        preview.src = "{{ asset('images/default-puja-kit.jpg') }}";
        preview.classList.remove('border-solid');
        preview.classList.add('border-dashed');
    }
});

// Toggle product options visibility
function toggleProductOptions(checkbox, index) {
    const optionsDiv = document.getElementById(`product-options-${index}`);
    if (checkbox.checked) {
        optionsDiv.classList.remove('hidden');
    } else {
        optionsDiv.classList.add('hidden');
    }
}

// Convert textarea items to array on form submit
document.querySelector('form').addEventListener('submit', function(e) {
    const itemsTextarea = document.getElementById('included_items_input');
    const itemsText = itemsTextarea.value.trim();
    
    if (itemsText) {
        const itemsArray = itemsText.split('\n').filter(item => item.trim() !== '');
        
        // Create hidden inputs for the array
        itemsArray.forEach((item, index) => {
            const hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.name = `included_items[${index}]`;
            hiddenInput.value = item.trim();
            this.appendChild(hiddenInput);
        });
    }
    
    // Remove the textarea from submission
    itemsTextarea.name = '';
});

// Initialize product options for checked items on page load
document.addEventListener('DOMContentLoaded', function() {
    const productCheckboxes = document.querySelectorAll('input[name="products[]"]');
    productCheckboxes.forEach((checkbox, index) => {
        if (checkbox.checked) {
            toggleProductOptions(checkbox, index);
        }
    });
});
</script>
@endsection
