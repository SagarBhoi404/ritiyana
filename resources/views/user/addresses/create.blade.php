@extends('layouts.app')

@section('title', 'Add New Address - Shree Samagri')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center space-x-4 mb-4">
                <a href="{{ route('addresses.index') }}" 
                   class="inline-flex items-center text-gray-600 hover:text-gray-800">
                    <i data-lucide="arrow-left" class="w-4 h-4 mr-2"></i>
                    Back to Addresses
                </a>
            </div>
            <h1 class="text-2xl font-bold text-gray-900">Add New Address</h1>
            <p class="text-gray-600 mt-1">Add a new delivery or billing address</p>
        </div>

        @if(session('error'))
            <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
                <div class="flex">
                    <i data-lucide="x-circle" class="w-5 h-5 text-red-400 mr-2 mt-0.5"></i>
                    <span class="text-red-700">{{ session('error') }}</span>
                </div>
            </div>
        @endif

        <!-- Address Form -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <form action="{{ route('addresses.store') }}" method="POST">
                @csrf

                <!-- Personal Information -->
                <div class="mb-8">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Personal Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="first_name" class="block text-sm font-medium text-gray-700 mb-2">
                                First Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="first_name" id="first_name" 
                                   value="{{ old('first_name') }}" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-vibrant-pink focus:border-transparent @error('first_name') border-red-500 @enderror">
                            @error('first_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="last_name" class="block text-sm font-medium text-gray-700 mb-2">
                                Last Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="last_name" id="last_name" 
                                   value="{{ old('last_name') }}" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-vibrant-pink focus:border-transparent @error('last_name') border-red-500 @enderror">
                            @error('last_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                                Phone Number <span class="text-red-500">*</span>
                            </label>
                            <input type="tel" name="phone" id="phone" 
                                   value="{{ old('phone') }}" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-vibrant-pink focus:border-transparent @error('phone') border-red-500 @enderror">
                            @error('phone')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Address Information -->
                <div class="mb-8">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Address Information</h3>
                    <div class="space-y-6">
                        <div>
                            <label for="address_line_1" class="block text-sm font-medium text-gray-700 mb-2">
                                Address Line 1 <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="address_line_1" id="address_line_1" 
                                   value="{{ old('address_line_1') }}" required
                                   placeholder="House/Flat number, Building name, Street"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-vibrant-pink focus:border-transparent @error('address_line_1') border-red-500 @enderror">
                            @error('address_line_1')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="address_line_2" class="block text-sm font-medium text-gray-700 mb-2">
                                Address Line 2 (Optional)
                            </label>
                            <input type="text" name="address_line_2" id="address_line_2" 
                                   value="{{ old('address_line_2') }}"
                                   placeholder="Area, Colony, Sector"
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-vibrant-pink focus:border-transparent @error('address_line_2') border-red-500 @enderror">
                            @error('address_line_2')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="landmark" class="block text-sm font-medium text-gray-700 mb-2">
                                Landmark (Optional)
                            </label>
                            <input type="text" name="landmark" id="landmark" 
                                   value="{{ old('landmark') }}"
                                   placeholder="Near famous landmark, mall, etc."
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-vibrant-pink focus:border-transparent @error('landmark') border-red-500 @enderror">
                            @error('landmark')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label for="city" class="block text-sm font-medium text-gray-700 mb-2">
                                    City <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="city" id="city" 
                                       value="{{ old('city') }}" required
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-vibrant-pink focus:border-transparent @error('city') border-red-500 @enderror">
                                @error('city')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="state" class="block text-sm font-medium text-gray-700 mb-2">
                                    State <span class="text-red-500">*</span>
                                </label>
                                <select name="state" id="state" required
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-vibrant-pink focus:border-transparent @error('state') border-red-500 @enderror">
                                    <option value="">Select State</option>
                                    <option value="Maharashtra" {{ old('state') == 'Maharashtra' ? 'selected' : '' }}>Maharashtra</option>
                                    <option value="Gujarat" {{ old('state') == 'Gujarat' ? 'selected' : '' }}>Gujarat</option>
                                    <option value="Karnataka" {{ old('state') == 'Karnataka' ? 'selected' : '' }}>Karnataka</option>
                                    <option value="Tamil Nadu" {{ old('state') == 'Tamil Nadu' ? 'selected' : '' }}>Tamil Nadu</option>
                                    <option value="Uttar Pradesh" {{ old('state') == 'Uttar Pradesh' ? 'selected' : '' }}>Uttar Pradesh</option>
                                    <option value="West Bengal" {{ old('state') == 'West Bengal' ? 'selected' : '' }}>West Bengal</option>
                                    <option value="Rajasthan" {{ old('state') == 'Rajasthan' ? 'selected' : '' }}>Rajasthan</option>
                                    <option value="Punjab" {{ old('state') == 'Punjab' ? 'selected' : '' }}>Punjab</option>
                                    <option value="Haryana" {{ old('state') == 'Haryana' ? 'selected' : '' }}>Haryana</option>
                                    <option value="Delhi" {{ old('state') == 'Delhi' ? 'selected' : '' }}>Delhi</option>
                                    <!-- Add more states as needed -->
                                </select>
                                @error('state')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="postal_code" class="block text-sm font-medium text-gray-700 mb-2">
                                    Postal Code <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="postal_code" id="postal_code" 
                                       value="{{ old('postal_code') }}" required
                                       pattern="[0-9]{6}" maxlength="6"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-vibrant-pink focus:border-transparent @error('postal_code') border-red-500 @enderror">
                                @error('postal_code')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <label for="country" class="block text-sm font-medium text-gray-700 mb-2">
                                Country <span class="text-red-500">*</span>
                            </label>
                            <select name="country" id="country" required
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-vibrant-pink focus:border-transparent @error('country') border-red-500 @enderror">
                                <option value="India" {{ old('country', 'India') == 'India' ? 'selected' : '' }}>India</option>
                            </select>
                            @error('country')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Address Type and Options -->
                <div class="mb-8">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Address Options</h3>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-3">
                                Address Type <span class="text-red-500">*</span>
                            </label>
                            <div class="space-y-2">
                                <label class="flex items-center">
                                    <input type="radio" name="type" value="both" 
                                           {{ old('type', 'both') == 'both' ? 'checked' : '' }}
                                           class="text-vibrant-pink focus:ring-vibrant-pink">
                                    <span class="ml-2 text-sm text-gray-700">Billing & Shipping (Both)</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" name="type" value="shipping" 
                                           {{ old('type') == 'shipping' ? 'checked' : '' }}
                                           class="text-vibrant-pink focus:ring-vibrant-pink">
                                    <span class="ml-2 text-sm text-gray-700">Shipping Only</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" name="type" value="billing" 
                                           {{ old('type') == 'billing' ? 'checked' : '' }}
                                           class="text-vibrant-pink focus:ring-vibrant-pink">
                                    <span class="ml-2 text-sm text-gray-700">Billing Only</span>
                                </label>
                            </div>
                            @error('type')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="flex items-center">
                                <input type="checkbox" name="is_default" value="1" 
                                       {{ old('is_default') ? 'checked' : '' }}
                                       class="text-vibrant-pink focus:ring-vibrant-pink">
                                <span class="ml-2 text-sm text-gray-700">Set as default address</span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                    <a href="{{ route('addresses.index') }}" 
                       class="px-4 py-2 text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg transition-colors">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="px-6 py-2 bg-vibrant-pink text-white rounded-lg hover:bg-vibrant-pink-dark transition-colors">
                        Save Address
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
