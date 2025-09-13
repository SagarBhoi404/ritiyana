<!-- resources/views/shopkeeper/pujas/create.blade.php -->
@extends('layouts.shopkeeper')

@section('title', 'Create Puja')
@section('breadcrumb', 'Pujas / Create')

@section('content')
    <div class="px-4 sm:px-6 lg:px-8 py-8">
        <!-- Page Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Create New Puja</h1>
                    <p class="mt-2 text-sm text-gray-700">Add a new puja ceremony to the system</p>
                </div>
                <a href="{{ route('vendor.pujas.index') }}"
                    class="inline-flex items-center px-4 py-2 bg-gray-100 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-200">
                    <i data-lucide="arrow-left" class="w-4 h-4 mr-2"></i>
                    Back to Pujas
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
            <form method="POST" action="{{ route('vendor.pujas.store') }}" enctype="multipart/form-data">
                @csrf

                <div class="p-8">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <!-- Left Column -->
                        <div class="space-y-6">
                            <!-- Basic Information -->
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                    <i data-lucide="flame" class="w-5 h-5 mr-2"></i>
                                    Basic Information
                                </h3>

                                <div class="space-y-4">
                                    <!-- Puja Name -->
                                    <div>
                                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                            Puja Name <span class="text-red-500">*</span>
                                        </label>
                                        <input type="text" name="name" id="name" value="{{ old('name') }}"
                                            placeholder="e.g., Ganesh Chaturthi, Diwali" required maxlength="255"
                                            class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                                        @error('name')
                                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Description -->
                                    <div>
                                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                                            Description
                                        </label>
                                        <textarea name="description" id="description" rows="4" placeholder="Brief description of the puja"
                                            class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">{{ old('description') }}</textarea>
                                        @error('description')
                                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Significance -->
                                    <div>
                                        <label for="significance" class="block text-sm font-medium text-gray-700 mb-2">
                                            Significance
                                        </label>
                                        <textarea name="significance" id="significance" rows="4" placeholder="Religious and cultural significance"
                                            class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">{{ old('significance') }}</textarea>
                                        @error('significance')
                                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Procedure -->
                                    <div>
                                        <label for="procedure" class="block text-sm font-medium text-gray-700 mb-2">
                                            Procedure
                                        </label>
                                        <textarea name="procedure" id="procedure" rows="6" placeholder="Step-by-step puja procedure"
                                            class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">{{ old('procedure') }}</textarea>
                                        @error('procedure')
                                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Right Column -->
                        <div class="space-y-6">
                            <!-- Image Upload -->
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                    <i data-lucide="image" class="w-5 h-5 mr-2"></i>
                                    Puja Image
                                </h3>

                                <div
                                    class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-purple-400 transition-colors">
                                    <i data-lucide="upload" class="w-12 h-12 mx-auto text-gray-400 mb-4"></i>
                                    <div class="text-sm text-gray-600 mb-2">
                                        <label for="image"
                                            class="relative cursor-pointer bg-white rounded-md font-medium text-purple-600 hover:text-purple-500">
                                            <span>Upload puja image</span>
                                            <input id="image" name="image" type="file" accept="image/*"
                                                class="sr-only" onchange="previewImage(this)">
                                        </label>
                                    </div>
                                    <p class="text-xs text-gray-500">PNG, JPG, WEBP up to 2MB</p>

                                    <!-- Image Preview -->
                                    <div id="imagePreview" class="mt-4 hidden">
                                        <img id="previewImg" src="" alt="Preview"
                                            class="mx-auto h-32 w-32 rounded-lg object-cover border-4 border-gray-200">
                                        <button type="button" onclick="removeImage()"
                                            class="mt-2 text-red-500 text-sm hover:text-red-700">Remove image</button>
                                    </div>
                                </div>
                                @error('image')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Auspicious Days -->
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                    <i data-lucide="calendar" class="w-5 h-5 mr-2"></i>
                                    Auspicious Days
                                </h3>

                                <div id="auspicious-days-container">
                                    <div class="auspicious-day-input mb-2">
                                        <input type="date" name="auspicious_days[]"
                                            value="{{ old('auspicious_days.0') }}"
                                            class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                                    </div>
                                </div>

                                <button type="button" onclick="addAuspiciousDay()"
                                    class="mt-2 text-purple-600 text-sm hover:text-purple-700">
                                    + Add Another Date
                                </button>
                                @error('auspicious_days')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Required Items -->
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                    <i data-lucide="list" class="w-5 h-5 mr-2"></i>
                                    Required Items
                                </h3>

                                <textarea name="required_items_input" id="required_items_input" rows="6"
                                    placeholder="Enter required items (one per line)&#10;Example:&#10;Diya&#10;Incense sticks&#10;Flowers&#10;Prasad"
                                    class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">{{ old('required_items_input') ? (is_array(old('required_items_input')) ? implode("\n", old('required_items_input')) : old('required_items_input')) : '' }}</textarea>
                                <p class="text-xs text-gray-500 mt-1">Enter each item on a new line</p>
                                @error('required_items')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                                @error('required_items_input')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>


                            <!-- Status -->
                            <div class="bg-gray-50 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                    <i data-lucide="settings" class="w-5 h-5 mr-2"></i>
                                    Status
                                </h3>

                                <div class="flex items-center">
                                    <input id="is_active" name="is_active" type="checkbox" value="1"
                                        {{ old('is_active', true) ? 'checked' : '' }}
                                        class="h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300 rounded">
                                    <label for="is_active" class="ml-2 block text-sm text-gray-900">
                                        Puja is active
                                    </label>
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
                            <a href="{{ route('vendor.pujas.index') }}"
                                class="px-6 py-3 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2">
                                Cancel
                            </a>
                            <button type="submit"
                                class="inline-flex items-center px-6 py-3 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-purple-600 hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2">
                                <i data-lucide="plus" class="w-4 h-4 mr-2"></i>
                                Create Puja
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Image preview
        function previewImage(input) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.getElementById('imagePreview');
                    const img = document.getElementById('previewImg');
                    img.src = e.target.result;
                    preview.classList.remove('hidden');
                }
                reader.readAsDataURL(input.files[0]);
            }
        }

        function removeImage() {
            const input = document.getElementById('image');
            const preview = document.getElementById('imagePreview');
            input.value = '';
            preview.classList.add('hidden');
        }

        // Add auspicious days
        function addAuspiciousDay() {
            const container = document.getElementById('auspicious-days-container');
            const div = document.createElement('div');
            div.className = 'auspicious-day-input mb-2 flex items-center';
            div.innerHTML = `
        <input type="date" name="auspicious_days[]" class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
        <button type="button" onclick="removeAuspiciousDay(this)" class="ml-2 text-red-500 hover:text-red-700">
            <i data-lucide="x" class="w-4 h-4"></i>
        </button>
    `;
            container.appendChild(div);
        }

        function removeAuspiciousDay(button) {
            button.closest('.auspicious-day-input').remove();
        }

        // Convert textarea to array on form submit
        document.querySelector('form').addEventListener('submit', function(e) {
            const itemsTextarea = document.getElementById('required_items_input');
            const itemsText = itemsTextarea.value.trim();

            // Remove existing hidden inputs to avoid duplicates
            const existingInputs = this.querySelectorAll('input[name^="required_items["]');
            existingInputs.forEach(input => input.remove());

            if (itemsText) {
                const itemsArray = itemsText.split('\n')
                    .map(item => item.trim())
                    .filter(item => item !== '');

                itemsArray.forEach((item, index) => {
                    const hiddenInput = document.createElement('input');
                    hiddenInput.type = 'hidden';
                    hiddenInput.name = `required_items[${index}]`;
                    hiddenInput.value = item;
                    this.appendChild(hiddenInput);
                });
            }

            // Disable the textarea so it doesn't get sent
            itemsTextarea.disabled = true;
        });
    </script>
@endsection
