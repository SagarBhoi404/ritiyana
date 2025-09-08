<!-- resources/views/admin/users/create.blade.php -->
@extends('layouts.admin')

@section('title', 'Add User')
@section('breadcrumb', 'Users / Add User')

@section('content')
    <div class="px-4 sm:px-6 lg:px-8 py-8">
        <!-- Page Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Add New User</h1>
                    <p class="mt-2 text-sm text-gray-700">Create a new user account for your Ritiyana platform</p>
                </div>
                <a href="{{ route('admin.users.index') }}"
                    class="inline-flex items-center px-4 py-2 bg-gray-100 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-200">
                    <i data-lucide="arrow-left" class="w-4 h-4 mr-2"></i>
                    Back to Users
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
            <form method="POST" action="{{ route('admin.users.store') }}" enctype="multipart/form-data">
                @csrf

                <div class="p-8">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        <!-- Left Column -->
                        <div class="space-y-6">
                            <!-- Personal Information -->
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                    <i data-lucide="user" class="w-5 h-5 mr-2"></i>
                                    Personal Information
                                </h3>

                                <div class="grid grid-cols-2 gap-4">
                                    <!-- First Name -->
                                    <div>
                                        <label for="first_name" class="block text-sm font-medium text-gray-700 mb-2">
                                            First Name <span class="text-red-500">*</span>
                                        </label>
                                        <input type="text" name="first_name" id="first_name"
                                            value="{{ old('first_name') }}" placeholder="Enter first name" required
                                            maxlength="255"
                                            class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                                        @error('first_name')
                                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Last Name -->
                                    <div>
                                        <label for="last_name" class="block text-sm font-medium text-gray-700 mb-2">
                                            Last Name <span class="text-red-500">*</span>
                                        </label>
                                        <input type="text" name="last_name" id="last_name" value="{{ old('last_name') }}"
                                            placeholder="Enter last name" required maxlength="255"
                                            class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                                        @error('last_name')
                                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <!-- ADD THIS: Date of Birth Field -->
                                <div class="mt-4">
                                    <label for="date_of_birth" class="block text-sm font-medium text-gray-700 mb-2">
                                        Date of Birth
                                    </label>
                                    <input type="date" name="date_of_birth" id="date_of_birth"
                                        value="{{ old('date_of_birth') }}"
                                        class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                                    @error('date_of_birth')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- ADD THIS: Gender Field -->
                                <div class="mt-4">
                                    <label for="gender" class="block text-sm font-medium text-gray-700 mb-2">
                                        Gender
                                    </label>
                                    <select name="gender" id="gender"
                                        class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                                        <option value="">Select gender</option>
                                        <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                                        <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female
                                        </option>
                                        <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Other
                                        </option>
                                    </select>
                                    @error('gender')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Contact Information -->
                            <div>
                                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                    <i data-lucide="mail" class="w-5 h-5 mr-2"></i>
                                    Contact Information
                                </h3>

                                <!-- Email -->
                                <div class="mb-4">
                                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                        Email Address <span class="text-red-500">*</span>
                                    </label>
                                    <input type="email" name="email" id="email" value="{{ old('email') }}"
                                        placeholder="user@example.com" required
                                        class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                                    @error('email')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Phone -->
                                <div>
                                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                                        Phone Number
                                    </label>
                                    <input type="tel" name="phone" id="phone" value="{{ old('phone') }}"
                                        placeholder="+91 9876543210"
                                        class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                                    @error('phone')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Profile Image Upload -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Profile Image
                                </label>
                                <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-purple-400 transition-colors"
                                    x-data="{ dragOver: false }" @dragover.prevent="dragOver = true"
                                    @dragleave.prevent="dragOver = false"
                                    @drop.prevent="dragOver = false; handleFileUpload($event)">
                                    <i data-lucide="upload" class="w-12 h-12 mx-auto text-gray-400 mb-4"></i>
                                    <div class="text-sm text-gray-600 mb-2">
                                        <label for="profile_image"
                                            class="relative cursor-pointer bg-white rounded-md font-medium text-purple-600 hover:text-purple-500">
                                            <span>Upload a file</span>
                                            <input id="profile_image" name="profile_image" type="file"
                                                accept="image/jpeg,image/jpg,image/png,image/webp" class="sr-only"
                                                onchange="previewImage(this)">
                                        </label>
                                        <span class="pl-1">or drag and drop</span>
                                    </div>
                                    <p class="text-xs text-gray-500">PNG, JPG, WEBP up to 2MB</p>

                                    <!-- Image Preview -->
                                    <div id="imagePreview" class="mt-4 hidden">
                                        <img id="previewImg" src="" alt="Preview"
                                            class="mx-auto h-20 w-20 rounded-full object-cover">
                                        <button type="button" onclick="removeImage()"
                                            class="mt-2 text-red-500 text-sm hover:text-red-700">Remove</button>
                                    </div>
                                </div>
                                @error('profile_image')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Right Column -->
                        <div class="space-y-6">
                            <!-- Security -->
                            <div class="bg-gray-50 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                    <i data-lucide="shield" class="w-5 h-5 mr-2"></i>
                                    Security & Access
                                </h3>

                                <!-- Password -->
                                <div class="mb-4">
                                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                                        Password <span class="text-red-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <input type="password" name="password" id="password"
                                            placeholder="Enter secure password" required minlength="8"
                                            class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent pr-12">
                                        <button type="button" onclick="togglePassword()"
                                            class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                            <i data-lucide="eye" class="h-5 w-5 text-gray-400 hover:text-gray-600"
                                                id="password-toggle-icon"></i>
                                        </button>
                                    </div>
                                    <p class="text-xs text-gray-500 mt-1">Must be at least 8 characters long</p>
                                    @error('password')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Confirm Password -->
                                <div class="mb-4">
                                    <label for="password_confirmation"
                                        class="block text-sm font-medium text-gray-700 mb-2">
                                        Confirm Password <span class="text-red-500">*</span>
                                    </label>
                                    <input type="password" name="password_confirmation" id="password_confirmation"
                                        placeholder="Confirm password" required
                                        class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                                </div>

                                <!-- Role - Fixed field name -->
                                <div>
                                    <label for="role" class="block text-sm font-medium text-gray-700 mb-2">
                                        User Role <span class="text-red-500">*</span>
                                    </label>
                                    <select name="role" id="role" required
                                        class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                                        <option value="">Select a role</option>
                                        <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>
                                            Administrator</option>
                                        <option value="shopkeeper" {{ old('role') == 'shopkeeper' ? 'selected' : '' }}>
                                            Shopkeeper</option>
                                        <option value="user" {{ old('role') == 'user' ? 'selected' : '' }}>Customer
                                        </option>
                                    </select>
                                    <p class="text-xs text-gray-500 mt-1">This determines what the user can access</p>
                                    @error('role')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Account Status -->
                            <div class="bg-gray-50 rounded-lg p-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                                    <i data-lucide="settings" class="w-5 h-5 mr-2"></i>
                                    Account Settings
                                </h3>

                                <!-- Status -->
                                <div class="mb-4">
                                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                                        Account Status
                                    </label>
                                    <select name="status" id="status"
                                        class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                                        <option value="active"
                                            {{ old('status', 'active') == 'active' ? 'selected' : '' }}>Active</option>
                                        <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>
                                            Inactive</option>
                                        <option value="suspended" {{ old('status') == 'suspended' ? 'selected' : '' }}>
                                            Suspended</option>
                                    </select>
                                </div>

                                <!-- Email Verification -->
                                <div class="flex items-center">
                                    <input id="email_verified" name="email_verified" type="checkbox" value="1"
                                        {{ old('email_verified') ? 'checked' : '' }}
                                        class="h-4 w-4 text-purple-600 focus:ring-purple-500 border-gray-300 rounded">
                                    <label for="email_verified" class="ml-2 block text-sm text-gray-900">
                                        Mark email as verified
                                    </label>
                                </div>
                            </div>

                            <!-- Additional Information -->
                            <div>
                                <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                                    Notes (Optional)
                                </label>
                                <textarea name="notes" id="notes" rows="4" placeholder="Any additional notes about this user..."
                                    maxlength="1000"
                                    class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">{{ old('notes') }}</textarea>
                                @error('notes')
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
                            <button type="button"
                                class="px-6 py-3 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2">
                                Save as Draft
                            </button>
                            <button type="submit"
                                class="inline-flex items-center px-6 py-3 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-purple-600 hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2">
                                <i data-lucide="user-plus" class="w-4 h-4 mr-2"></i>
                                Create User
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        function togglePassword() {
            const passwordField = document.getElementById('password');
            const toggleIcon = document.getElementById('password-toggle-icon');

            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                toggleIcon.setAttribute('data-lucide', 'eye-off');
            } else {
                passwordField.type = 'password';
                toggleIcon.setAttribute('data-lucide', 'eye');
            }

            // Re-initialize icons after change
            lucide.createIcons();
        }

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
            const input = document.getElementById('profile_image');
            const imagePreview = document.getElementById('imagePreview');

            input.value = '';
            imagePreview.classList.add('hidden');
        }

        function handleFileUpload(event) {
            const files = event.dataTransfer.files;
            if (files.length > 0) {
                const input = document.getElementById('profile_image');
                input.files = files;
                previewImage(input);
            }
        }
    </script>
@endsection
