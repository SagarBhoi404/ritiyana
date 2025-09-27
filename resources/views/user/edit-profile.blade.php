@extends('layouts.app')

@section('title', 'Edit Profile - Shree Samagri')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <nav class="flex py-3 text-sm text-gray-500 mb-4">
                <a href="{{ route('dashboard') }}" class="hover:text-vibrant-pink transition-colors">Dashboard</a>
                <i data-lucide="chevron-right" class="w-4 h-4 mx-2"></i>
                <a href="{{ route('profile') }}" class="hover:text-vibrant-pink transition-colors">Profile</a>
                <i data-lucide="chevron-right" class="w-4 h-4 mx-2"></i>
                <span class="text-gray-900">Edit</span>
            </nav>
            <h1 class="text-2xl font-bold text-gray-900 flex items-center">
                <i data-lucide="edit-2" class="w-6 h-6 mr-3 text-vibrant-pink"></i>
                Edit Profile
            </h1>
            <p class="text-gray-600 mt-1">Update your personal information and preferences</p>
        </div>

        <!-- Customer Navigation Component -->
        <x-customer-navigation />

        <!-- Edit Form -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <!-- Form Header -->
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h3 class="text-lg font-semibold text-gray-900">Personal Information</h3>
                <p class="text-sm text-gray-600 mt-1">Keep your profile information up to date</p>
            </div>

            <!-- Form Body -->
            <form action="{{ route('profile.update') }}" method="POST" class="p-6" id="profileForm">
                @csrf
                
                <!-- Form Progress Indicator -->
                <div class="mb-8">
                    <div class="flex items-center justify-between text-sm text-gray-500 mb-2">
                        <span>Profile Completion</span>
                        <span id="completionPercentage">0%</span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-vibrant-pink h-2 rounded-full transition-all duration-300" 
                             id="progressBar" style="width: 0%"></div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- First Name -->
                    <div class="form-group">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            First Name <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="text" 
                                   name="first_name" 
                                   value="{{ old('first_name', $user->first_name) }}" 
                                   class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-vibrant-pink focus:border-vibrant-pink transition-all @error('first_name') border-red-500 @enderror"
                                   required>
                            <i data-lucide="user" class="w-4 h-4 absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        </div>
                        @error('first_name') 
                            <p class="text-red-500 text-sm mt-1 flex items-center">
                                <i data-lucide="alert-circle" class="w-4 h-4 mr-1"></i>
                                {{ $message }}
                            </p> 
                        @enderror
                    </div>

                    <!-- Last Name -->
                    <div class="form-group">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Last Name <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input type="text" 
                                   name="last_name" 
                                   value="{{ old('last_name', $user->last_name) }}" 
                                   class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-vibrant-pink focus:border-vibrant-pink transition-all @error('last_name') border-red-500 @enderror"
                                   required>
                            <i data-lucide="user" class="w-4 h-4 absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        </div>
                        @error('last_name') 
                            <p class="text-red-500 text-sm mt-1 flex items-center">
                                <i data-lucide="alert-circle" class="w-4 h-4 mr-1"></i>
                                {{ $message }}
                            </p> 
                        @enderror
                    </div>

                    <!-- Phone -->
                    <div class="form-group">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                        <div class="relative">
                            <input type="tel" 
                                   name="phone" 
                                   value="{{ old('phone', $user->phone) }}" 
                                   class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-vibrant-pink focus:border-vibrant-pink transition-all @error('phone') border-red-500 @enderror"
                                   placeholder="+91 XXXXX XXXXX">
                            <i data-lucide="phone" class="w-4 h-4 absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        </div>
                        @error('phone') 
                            <p class="text-red-500 text-sm mt-1 flex items-center">
                                <i data-lucide="alert-circle" class="w-4 h-4 mr-1"></i>
                                {{ $message }}
                            </p> 
                        @enderror
                    </div>

                    <!-- Gender -->
                    <div class="form-group">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Gender</label>
                        <div class="relative">
                            <select name="gender" 
                                    class="block w-full pl-10 pr-8 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-vibrant-pink focus:border-vibrant-pink transition-all @error('gender') border-red-500 @enderror">
                                <option value="">Select Gender</option>
                                <option value="male" {{ old('gender', $user->gender) === 'male' ? 'selected' : '' }}>Male</option>
                                <option value="female" {{ old('gender', $user->gender) === 'female' ? 'selected' : '' }}>Female</option>
                                <option value="other" {{ old('gender', $user->gender) === 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                            <i data-lucide="users" class="w-4 h-4 absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        </div>
                        @error('gender') 
                            <p class="text-red-500 text-sm mt-1 flex items-center">
                                <i data-lucide="alert-circle" class="w-4 h-4 mr-1"></i>
                                {{ $message }}
                            </p> 
                        @enderror
                    </div>

                    <!-- Date of Birth -->
                    <div class="form-group md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Date of Birth</label>
                        <div class="relative max-w-md">
                            <input type="date" 
                                   name="date_of_birth" 
                                   value="{{ old('date_of_birth', $user->date_of_birth) }}" 
                                   class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-2 focus:ring-vibrant-pink focus:border-vibrant-pink transition-all @error('date_of_birth') border-red-500 @enderror">
                            <i data-lucide="calendar" class="w-4 h-4 absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        </div>
                        @error('date_of_birth') 
                            <p class="text-red-500 text-sm mt-1 flex items-center">
                                <i data-lucide="alert-circle" class="w-4 h-4 mr-1"></i>
                                {{ $message }}
                            </p> 
                        @enderror
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex items-center justify-between pt-6 mt-6 border-t border-gray-200">
                    <a href="{{ route('profile') }}" 
                       class="inline-flex items-center px-6 py-3 border border-gray-300 rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-all">
                        <i data-lucide="x" class="w-4 h-4 mr-2"></i>
                        Cancel
                    </a>
                    <button type="submit" 
                            class="inline-flex items-center px-6 py-3 bg-vibrant-pink hover:bg-vibrant-pink-dark text-white rounded-lg focus:ring-2 focus:ring-offset-2 focus:ring-vibrant-pink transition-all disabled:opacity-50"
                            id="submitButton">
                        <i data-lucide="save" class="w-4 h-4 mr-2"></i>
                        <span id="submitText">Update Profile</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('profileForm');
    const progressBar = document.getElementById('progressBar');
    const completionPercentage = document.getElementById('completionPercentage');
    const submitButton = document.getElementById('submitButton');
    const submitText = document.getElementById('submitText');
    
    // Calculate form completion
    function updateProgress() {
        const inputs = form.querySelectorAll('input[required], select[required]');
        let filled = 0;
        inputs.forEach(input => {
            if (input.value.trim() !== '') filled++;
        });
        
        const percentage = Math.round((filled / inputs.length) * 100);
        progressBar.style.width = percentage + '%';
        completionPercentage.textContent = percentage + '%';
    }
    
    // Add event listeners to form inputs
    form.querySelectorAll('input, select').forEach(input => {
        input.addEventListener('input', updateProgress);
        input.addEventListener('change', updateProgress);
    });
    
    // Initial progress calculation
    updateProgress();
    
    // Form submission handling
    form.addEventListener('submit', function() {
        submitButton.disabled = true;
        submitText.textContent = 'Updating...';
    });
});
</script>
@endsection
