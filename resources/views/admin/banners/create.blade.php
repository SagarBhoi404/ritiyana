<!-- resources/views/admin/banners/create.blade.php -->
@extends('layouts.admin')

@section('title', 'Add Banner')

@section('breadcrumb', 'Banners / Add Banner')

@section('content')
    <div class="px-4 sm:px-6 lg:px-8 py-8">
        <!-- Page Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Add New Banner</h1>
                    <p class="mt-2 text-sm text-gray-700">Create a new banner for your website slider</p>
                </div>
                <a href="{{ route('admin.banners.index') }}"
                    class="inline-flex items-center px-4 py-2 bg-gray-100 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-200">
                    <i data-lucide="arrow-left" class="w-4 h-4 mr-2"></i>
                    Back to Banners
                </a>
            </div>
        </div>

        <!-- Validation Errors -->
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
            <form method="POST" action="{{ route('admin.banners.store') }}" enctype="multipart/form-data">
                @csrf

                <div class="p-8">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <!-- Left Column -->
                        <div class="space-y-6">
                            <!-- Basic Information -->
                            <div class="bg-gray-50 rounded-xl p-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">
                                    Basic Information
                                </h3>

                                <!-- Title -->
                                <div class="mb-6">
                                    <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                                        Banner Title *
                                    </label>
                                    <input type="text" id="title" name="title" value="{{ old('title') }}" required
                                        class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent {{ $errors->has('title') ? 'border-red-300' : '' }}"
                                        placeholder="Enter banner title">
                                </div>

                                <!-- Description -->
                                <div class="mb-6">
                                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                                        Description
                                    </label>
                                    <textarea name="description" id="description" rows="3"
                                        class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                                        placeholder="Enter banner description">{{ old('description') }}</textarea>
                                </div>

                                <!-- Alt Text -->
                                <div class="mb-6">
                                    <label for="alt_text" class="block text-sm font-medium text-gray-700 mb-2">
                                        Alt Text *
                                    </label>
                                    <input type="text" id="alt_text" name="alt_text" value="{{ old('alt_text') }}"
                                        required
                                        class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                                        placeholder="Image alt text for SEO">
                                </div>

                                <!-- Link URL -->
                                <div class="mb-6">
                                    <label for="link_url" class="block text-sm font-medium text-gray-700 mb-2">
                                        Link URL
                                    </label>
                                    <input type="url" id="link_url" name="link_url" value="{{ old('link_url') }}"
                                        class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                                        placeholder="https://example.com">
                                    <p class="mt-1 text-xs text-gray-500">Optional: URL to redirect when banner is clicked
                                    </p>
                                </div>

                                <!-- Sort Order -->
                                <div>
                                    <label for="sort_order" class="block text-sm font-medium text-gray-700 mb-2">
                                        Sort Order *
                                    </label>
                                    <input type="number" id="sort_order" name="sort_order"
                                        value="{{ old('sort_order', 0) }}" min="0" required
                                        class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                                        placeholder="0">
                                    <p class="mt-1 text-xs text-gray-500">Lower numbers appear first</p>
                                </div>
                            </div>
                        </div>

                        <!-- Right Column -->
                        <div class="space-y-6">
                            <!-- Banner Image -->
                            <div class="bg-gray-50 rounded-xl p-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">
                                    Banner Image
                                </h3>

                                <!-- Size Requirements Notice -->
                                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4">
                                    <div class="flex">
                                        <i data-lucide="info" class="w-5 h-5 text-blue-400 mr-2 mt-0.5"></i>
                                        <div>
                                            <p class="text-sm text-blue-800 font-medium">Banner Size Requirements</p>
                                            <p class="text-sm text-blue-700 mt-1">Image must be exactly <strong>1184 x 232
                                                    pixels</strong> for optimal display in the banner slider.</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="space-y-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">
                                        Upload Image *
                                    </label>
                                    <div
                                        class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-purple-400 transition-colors">
                                        <div class="space-y-1 text-center">
                                            <div id="image-preview" class="hidden">
                                                <img id="preview-img" src="" alt="Preview"
                                                    class="mx-auto h-32 w-auto rounded-lg">
                                            </div>
                                            <div id="upload-placeholder">
                                                <i data-lucide="image" class="mx-auto h-12 w-12 text-gray-400"></i>
                                                <div class="flex text-sm text-gray-600">
                                                    <label for="image"
                                                        class="relative cursor-pointer bg-white rounded-md font-medium text-purple-600 hover:text-purple-500">
                                                        <span>Upload a file</span>
                                                        <input id="image" name="image" type="file" accept="image/*"
                                                            class="sr-only" onchange="previewImage(this)" required>
                                                    </label>
                                                    <p class="pl-1">or drag and drop</p>
                                                </div>
                                                <p class="text-xs text-gray-500">PNG, JPG, WEBP up to 2MB</p>
                                            </div>
                                            <button type="button" onclick="removeImage()"
                                                class="mt-2 text-red-500 text-sm hover:text-red-700 hidden"
                                                id="remove-btn">Remove</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Settings -->
                            <div class="bg-gray-50 rounded-xl p-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">
                                    Settings
                                </h3>

                                <!-- Active Status -->
                                <div class="flex items-center">
                                    <input id="is_active" name="is_active" type="checkbox"
                                        {{ old('is_active', true) ? 'checked' : '' }}
                                        class="h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300 rounded">
                                    <label for="is_active" class="ml-2 block text-sm text-gray-900">
                                        Active Banner
                                    </label>
                                </div>
                                <p class="mt-1 text-xs text-gray-500">Inactive banners will not be displayed</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="px-8 py-6 bg-gray-50 border-t border-gray-200 rounded-b-2xl">
                    <div class="flex justify-end space-x-3">
                        <a href="{{ route('admin.banners.index') }}"
                            class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                            Cancel
                        </a>
                        <button type="submit"
                            class="inline-flex items-center px-4 py-2 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-purple-600 hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500">
                            <i data-lucide="plus" class="w-4 h-4 mr-2"></i>
                            Create Banner
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        function previewImage(input) {
            if (input.files && input.files) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('preview-img').src = e.target.result;
                    document.getElementById('image-preview').classList.remove('hidden');
                    document.getElementById('upload-placeholder').classList.add('hidden');
                    document.getElementById('remove-btn').classList.remove('hidden');
                };
                reader.readAsDataURL(input.files);
            }
        }

        function removeImage() {
            document.getElementById('image').value = '';
            document.getElementById('image-preview').classList.add('hidden');
            document.getElementById('upload-placeholder').classList.remove('hidden');
            document.getElementById('remove-btn').classList.add('hidden');
        }
    </script>
@endsection
