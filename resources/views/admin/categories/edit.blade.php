<!-- resources/views/admin/categories/edit.blade.php -->
@extends('layouts.admin')

@section('title', 'Edit Category')
@section('breadcrumb', 'Categories / Edit Category')

@section('content')
<div class="px-4 sm:px-6 lg:px-8 py-8">
    <!-- Page Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Edit Category</h1>
                <p class="mt-2 text-sm text-gray-700">Update category information and settings</p>
            </div>
            <a href="{{ route('admin.categories.show', $category) }}" class="inline-flex items-center px-4 py-2 bg-gray-100 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-200">
                <i data-lucide="arrow-left" class="w-4 h-4 mr-2"></i>
                Back to Category
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
        <form method="POST" action="{{ route('admin.categories.update', $category) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
            <div class="p-8">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <!-- Left Column -->
                    <div class="space-y-6">
                        <!-- Basic Information -->
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                <i data-lucide="folder" class="w-5 h-5 mr-2"></i>
                                Basic Information
                            </h3>
                            
                            <div class="space-y-4">
                                <!-- Category Name -->
                                <div>
                                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                        Category Name <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" 
                                           name="name" 
                                           id="name" 
                                           value="{{ old('name', $category->name) }}"
                                           required 
                                           maxlength="255"
                                           class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                                    @error('name')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Slug -->
                                <div>
                                    <label for="slug" class="block text-sm font-medium text-gray-700 mb-2">
                                        Slug
                                    </label>
                                    <input type="text" 
                                           name="slug" 
                                           id="slug" 
                                           value="{{ old('slug', $category->slug) }}"
                                           readonly
                                           class="block w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-50 text-gray-500">
                                    <p class="text-xs text-gray-500 mt-1">Auto-generated from category name</p>
                                </div>

                                <!-- Parent Category -->
                                <div>
                                    <label for="parent_id" class="block text-sm font-medium text-gray-700 mb-2">
                                        Parent Category
                                    </label>
                                    <select name="parent_id" 
                                            id="parent_id"
                                            class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                                        <option value="">Select parent category (optional)</option>
                                        @foreach($parentCategories as $parent)
                                            <option value="{{ $parent->id }}" {{ old('parent_id', $category->parent_id) == $parent->id ? 'selected' : '' }}>
                                                {{ $parent->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('parent_id')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Description -->
                                <div>
                                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                                        Description
                                    </label>
                                    <textarea name="description" 
                                              id="description" 
                                              rows="4" 
                                              class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">{{ old('description', $category->description) }}</textarea>
                                    @error('description')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="space-y-6">
                        <!-- Category Image -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Category Image
                            </label>
                            
                            <!-- Current Image -->
                            @if($category->image)
                                <div class="mb-4">
                                    <img src="{{ asset('storage/' . $category->image) }}" 
                                         alt="Current category image" 
                                         class="w-20 h-20 rounded object-cover border-4 border-gray-200">
                                    <p class="text-xs text-gray-500 mt-1">Current image</p>
                                </div>
                            @endif
                            
                            <!-- File Upload -->
                            <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-purple-400 transition-colors">
                                <i data-lucide="upload" class="w-12 h-12 mx-auto text-gray-400 mb-4"></i>
                                <div class="text-sm text-gray-600 mb-2">
                                    <label for="image" class="relative cursor-pointer bg-white rounded-md font-medium text-purple-600 hover:text-purple-500">
                                        <span>Upload new image</span>
                                        <input id="image" name="image" type="file" accept="image/*" class="sr-only" onchange="previewImage(this)">
                                    </label>
                                </div>
                                <p class="text-xs text-gray-500">PNG, JPG, WEBP up to 2MB</p>
                                
                                <!-- New Image Preview -->
                                <div id="imagePreview" class="mt-4 hidden">
                                    <img id="previewImg" src="" alt="Preview" class="mx-auto h-20 w-20 rounded object-cover">
                                    <button type="button" onclick="removeImage()" class="mt-2 text-red-500 text-sm hover:text-red-700">Remove new image</button>
                                </div>
                            </div>
                            @error('image')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Settings -->
                        <div class="bg-gray-50 rounded-lg p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                <i data-lucide="settings" class="w-5 h-5 mr-2"></i>
                                Category Settings
                            </h3>

                            <!-- Icon -->
                            <div class="mb-4">
                                <label for="icon" class="block text-sm font-medium text-gray-700 mb-2">
                                    Icon Class
                                </label>
                                <input type="text" 
                                       name="icon" 
                                       id="icon" 
                                       value="{{ old('icon', $category->icon) }}"
                                       placeholder="e.g., lucide-flame, lucide-flower"
                                       class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                                @error('icon')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Sort Order -->
                            <div class="mb-4">
                                <label for="sort_order" class="block text-sm font-medium text-gray-700 mb-2">
                                    Sort Order
                                </label>
                                <input type="number" 
                                       name="sort_order" 
                                       id="sort_order" 
                                       value="{{ old('sort_order', $category->sort_order) }}"
                                       min="0"
                                       class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                                @error('sort_order')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Active Status -->
                            <div class="flex items-center">
                                <input id="is_active" 
                                       name="is_active" 
                                       type="checkbox" 
                                       value="1"
                                       {{ old('is_active', $category->is_active) ? 'checked' : '' }}
                                       class="h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300 rounded">
                                <label for="is_active" class="ml-2 block text-sm text-gray-900">
                                    Category is active
                                </label>
                            </div>
                        </div>

                        <!-- SEO Settings -->
                        <div class="bg-gray-50 rounded-lg p-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                <i data-lucide="search" class="w-5 h-5 mr-2"></i>
                                SEO Settings
                            </h3>

                            <!-- Meta Title -->
                            <div class="mb-4">
                                <label for="meta_title" class="block text-sm font-medium text-gray-700 mb-2">
                                    Meta Title
                                </label>
                                <input type="text" 
                                       name="meta_title" 
                                       id="meta_title" 
                                       value="{{ old('meta_title', $category->meta_title) }}"
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
                                          class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">{{ old('meta_description', $category->meta_description) }}</textarea>
                                @error('meta_description')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
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
                        <a href="{{ route('admin.categories.show', $category) }}" class="px-6 py-3 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2">
                            Cancel
                        </a>
                        <button type="submit" class="inline-flex items-center px-6 py-3 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-purple-600 hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2">
                            <i data-lucide="save" class="w-4 h-4 mr-2"></i>
                            Update Category
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
    document.getElementById('slug').value = slug;
});

// Image preview functionality
function previewImage(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const imagePreview = document.getElementById('imagePreview');
            const previewImg = document.getElementById('previewImg');
            previewImg.src = e.target.result;
            imagePreview.classList.remove('hidden');
        }
        reader.readAsDataURL(input.files[0]);
    }
}

function removeImage() {
    const input = document.getElementById('image');
    const imagePreview = document.getElementById('imagePreview');
    input.value = '';
    imagePreview.classList.add('hidden');
}
</script>
@endsection
