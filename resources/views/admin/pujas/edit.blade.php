<!-- resources/views/admin/pujas/edit.blade.php -->
@extends('layouts.admin')

@section('title', 'Edit Puja')
@section('breadcrumb', 'Pujas / Edit')

@section('content')
<div class="px-4 sm:px-6 lg:px-8 py-8">
    <!-- Page Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Edit Puja</h1>
                <p class="mt-2 text-sm text-gray-700">Update puja information and settings</p>
            </div>
            <a href="{{ route('admin.pujas.show', $puja) }}" class="inline-flex items-center px-4 py-2 bg-gray-100 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-200">
                <i data-lucide="arrow-left" class="w-4 h-4 mr-2"></i>
                Back to Puja
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
        <form method="POST" action="{{ route('admin.pujas.update', $puja) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            
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
                                    <input type="text" 
                                           name="name" 
                                           id="name" 
                                           value="{{ old('name', $puja->name) }}"
                                           required 
                                           maxlength="255"
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
                                    <textarea name="description" 
                                              id="description" 
                                              rows="4" 
                                              class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">{{ old('description', $puja->description) }}</textarea>
                                    @error('description')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Significance -->
                                <div>
                                    <label for="significance" class="block text-sm font-medium text-gray-700 mb-2">
                                        Significance
                                    </label>
                                    <textarea name="significance" 
                                              id="significance" 
                                              rows="4" 
                                              class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">{{ old('significance', $puja->significance) }}</textarea>
                                    @error('significance')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Procedure -->
                                <div>
                                    <label for="procedure" class="block text-sm font-medium text-gray-700 mb-2">
                                        Procedure
                                    </label>
                                    <textarea name="procedure" 
                                              id="procedure" 
                                              rows="6" 
                                              class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">{{ old('procedure', $puja->procedure) }}</textarea>
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
                            
                            <!-- Current Image -->
                            @if($puja->image)
                                <div class="mb-4">
                                    <img src="{{ asset('storage/' . $puja->image) }}" 
                                         alt="Current puja image" 
                                         class="w-20 h-20 rounded object-cover border-4 border-gray-200">
                                    <p class="text-xs text-gray-500 mt-1">Current image</p>
                                </div>
                            @endif
                            
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

                        <!-- Auspicious Days -->
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                <i data-lucide="calendar" class="w-5 h-5 mr-2"></i>
                                Auspicious Days
                            </h3>
                            
                            <div id="auspicious-days-container">
                                @php
                                    // Safe handling of JSON field
                                    $auspiciousDays = old('auspicious_days', $puja->auspicious_days ?? []);
                                    
                                    // If it's a string (JSON), decode it
                                    if (is_string($auspiciousDays)) {
                                        $auspiciousDays = json_decode($auspiciousDays, true) ?: [];
                                    }
                                    
                                    // Ensure it's an array
                                    $auspiciousDays = is_array($auspiciousDays) ? $auspiciousDays : [];
                                @endphp
                                
                                @if(count($auspiciousDays) > 0)
                                    @foreach($auspiciousDays as $date)
                                        <div class="auspicious-day-input mb-2 flex items-center">
                                            <input type="date" 
                                                   name="auspicious_days[]" 
                                                   value="{{ $date }}"
                                                   class="block w-full px-4 py-3 mr-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                                            <button type="button" onclick="removeAuspiciousDay(this)" class="text-red-500 hover:text-red-700">
                                                <i data-lucide="x" class="w-4 h-4"></i>
                                            </button>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="auspicious-day-input mb-2">
                                        <input type="date" 
                                               name="auspicious_days[]" 
                                               class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                                    </div>
                                @endif
                            </div>
                            
                            <button type="button" onclick="addAuspiciousDay()" class="mt-2 text-purple-600 text-sm hover:text-purple-700">
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
                            
                            @php
                                // Safe handling of required_items JSON field
                                $requiredItems = old('required_items_input');
                                if (!$requiredItems) {
                                    $items = $puja->required_items;
                                    if (is_string($items)) {
                                        $items = json_decode($items, true) ?: [];
                                    }
                                    $requiredItems = is_array($items) ? implode("\n", $items) : '';
                                }
                            @endphp
                            
                            <textarea name="required_items_input" 
                                      id="required_items_input" 
                                      rows="6" 
                                      placeholder="Enter required items (one per line)"
                                      class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">{{ $requiredItems }}</textarea>
                            <p class="text-xs text-gray-500 mt-1">Enter each item on a new line</p>
                            @error('required_items')
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
                                <input id="is_active" 
                                       name="is_active" 
                                       type="checkbox" 
                                       value="1"
                                       {{ old('is_active', $puja->is_active) ? 'checked' : '' }}
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
                        <a href="{{ route('admin.pujas.show', $puja) }}" class="px-6 py-3 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2">
                            Cancel
                        </a>
                        <button type="submit" class="inline-flex items-center px-6 py-3 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-purple-600 hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2">
                            <i data-lucide="save" class="w-4 h-4 mr-2"></i>
                            Update Puja
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
        <input type="date" name="auspicious_days[]" class="block w-full px-4 py-3 mr-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
        <button type="button" onclick="removeAuspiciousDay(this)" class="text-red-500 hover:text-red-700">
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
    
    if (itemsText) {
        const itemsArray = itemsText.split('\n').filter(item => item.trim() !== '');
        
        itemsArray.forEach((item, index) => {
            const hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.name = `required_items[${index}]`;
            hiddenInput.value = item.trim();
            this.appendChild(hiddenInput);
        });
    }
    
    itemsTextarea.name = '';
});
</script>
@endsection
