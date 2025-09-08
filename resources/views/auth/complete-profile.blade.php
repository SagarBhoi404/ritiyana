<!-- resources/views/auth/complete-profile.blade.php -->
@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto px-4 py-12">
    <div class="bg-white rounded-2xl border border-gray-200 p-8">
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-2xl font-bold mb-2">Complete Your Profile</h1>
            <p class="text-gray-600">Tell us a bit about yourself</p>
        </div>

        <!-- Display Errors -->
        @if ($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6">
                @foreach ($errors->all() as $error)
                    <p class="text-sm">{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <!-- Profile Form -->
        <form method="POST" action="{{ route('auth.complete-profile') }}">
            @csrf
            <div class="space-y-4">
                <div>
                    <label for="first_name" class="block text-sm font-medium text-gray-700 mb-2">First Name *</label>
                    <input type="text" 
                           id="first_name" 
                           name="first_name" 
                           value="{{ old('first_name') }}"
                           placeholder="Enter your first name" 
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-vibrant-pink focus:border-transparent"
                           required>
                </div>

                <div>
                    <label for="last_name" class="block text-sm font-medium text-gray-700 mb-2">Last Name *</label>
                    <input type="text" 
                           id="last_name" 
                           name="last_name" 
                           value="{{ old('last_name') }}"
                           placeholder="Enter your last name" 
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-vibrant-pink focus:border-transparent"
                           required>
                </div>

                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                    <div class="flex">
                        <div class="flex items-center px-3 border border-r-0 border-gray-300 rounded-l-lg bg-gray-50">
                            <span class="text-gray-600">+91</span>
                        </div>
                        <input type="tel" 
                               id="phone" 
                               name="phone" 
                               value="{{ old('phone') }}"
                               placeholder="Enter phone number" 
                               class="flex-1 px-4 py-3 border border-gray-300 rounded-r-lg focus:outline-none focus:ring-2 focus:ring-vibrant-pink focus:border-transparent">
                    </div>
                </div>

                <div>
                    <label for="date_of_birth" class="block text-sm font-medium text-gray-700 mb-2">Date of Birth</label>
                    <input type="date" 
                           id="date_of_birth" 
                           name="date_of_birth" 
                           value="{{ old('date_of_birth') }}"
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-vibrant-pink focus:border-transparent">
                </div>

                <div>
                    <label for="gender" class="block text-sm font-medium text-gray-700 mb-2">Gender</label>
                    <select id="gender" 
                            name="gender" 
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-vibrant-pink focus:border-transparent">
                        <option value="">Select Gender</option>
                        <option value="male" {{ old('gender') == 'male' ? 'selected' : '' }}>Male</option>
                        <option value="female" {{ old('gender') == 'female' ? 'selected' : '' }}>Female</option>
                        <option value="other" {{ old('gender') == 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                </div>

                <button type="submit" class="w-full bg-vibrant-pink hover:bg-vibrant-pink-dark text-white font-medium py-3 rounded-lg transition-colors">
                    Complete Registration
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
