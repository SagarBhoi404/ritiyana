<!-- resources/views/auth/login.blade.php -->
@extends('layouts.app')

@section('content')
<div class="max-w-md mx-auto px-4 py-12">
    <div class="bg-white rounded-2xl border border-gray-200 p-8">
        <!-- Header -->
        <div class="text-center mb-8">
            <a href="{{ route('home') }}" class="inline-flex items-center gap-2 text-gray-600 hover:text-vibrant-pink mb-4">
                <i data-lucide="arrow-left" class="w-4 h-4"></i>
                Back to Home
            </a>
            <h1 class="text-2xl font-bold mb-2">Welcome to Shree Samagri</h1>
            <p class="text-gray-600">Enter your email to continue</p>
        </div>

        <!-- Display Errors -->
        @if ($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6">
                @foreach ($errors->all() as $error)
                    <p class="text-sm">{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <!-- Display Success Messages -->
        @if (session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6">
                <p class="text-sm">{{ session('success') }}</p>
            </div>
        @endif

        <!-- Email Form -->
        <div id="email-form">
            <form method="POST" action="{{ route('auth.send-otp') }}">
                @csrf

                
                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                    <input type="email" 
                           id="email" 
                           name="email" 
                           value="{{ old('email') }}"
                           placeholder="Enter your email address" 
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-vibrant-pink focus:border-transparent"
                           required>
                </div>
                <button type="submit" class="w-full bg-vibrant-pink hover:bg-vibrant-pink-dark text-white font-medium py-3 rounded-lg transition-colors">
                    Send OTP
                </button>
            </form>
        </div>

        <!-- Terms -->
        <p class="text-xs text-gray-500 text-center mt-6">
            By continuing, you agree to our 
            <a href="#" class="text-vibrant-pink hover:underline">Terms of Service</a> 
            and 
            <a href="#" class="text-vibrant-pink hover:underline">Privacy Policy</a>
        </p>
    </div>

    <!-- Benefits Section -->
    <div class="mt-12 bg-gray-50 rounded-2xl p-6">
        <h3 class="text-lg font-semibold mb-4 text-center">Benefits of Creating Account</h3>
        <div class="space-y-3">
            <div class="flex items-center gap-3">
                <div class="bg-green-100 w-8 h-8 rounded-full flex items-center justify-center">
                    <i data-lucide="check" class="w-4 h-4 text-green-600"></i>
                </div>
                <span class="text-gray-700">Track your puja orders in real-time</span>
            </div>
            <div class="flex items-center gap-3">
                <div class="bg-green-100 w-8 h-8 rounded-full flex items-center justify-center">
                    <i data-lucide="check" class="w-4 h-4 text-green-600"></i>
                </div>
                <span class="text-gray-700">Save addresses for faster checkout</span>
            </div>
            <div class="flex items-center gap-3">
                <div class="bg-green-100 w-8 h-8 rounded-full flex items-center justify-center">
                    <i data-lucide="check" class="w-4 h-4 text-green-600"></i>
                </div>
                <span class="text-gray-700">Get exclusive puja kit offers</span>
            </div>
            <div class="flex items-center gap-3">
                <div class="bg-green-100 w-8 h-8 rounded-full flex items-center justify-center">
                    <i data-lucide="check" class="w-4 h-4 text-green-600"></i>
                </div>
                <span class="text-gray-700">Book pandit services easily</span>
            </div>
        </div>
    </div>
</div>
@endsection
