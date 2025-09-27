<!-- resources/views/auth/shopkeeper-login.blade.php -->
@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-green-50 to-emerald-100 flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <div class="bg-white rounded-2xl border border-gray-200 p-8 shadow-lg">
            <!-- Header -->
            <div class="text-center mb-8">
                <div class="mx-auto h-12 w-12 bg-green-100 rounded-full flex items-center justify-center mb-4">
                    <i data-lucide="store" class="h-6 w-6 text-green-600"></i>
                </div>
                <h1 class="text-2xl font-bold text-gray-900 mb-2">Shopkeeper Login</h1>
                <p class="text-gray-600">Access Your Shree Samagri Store</p>
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

            <!-- Login Form -->
            <form method="POST" action="{{ route('shopkeeper.login') }}" class="space-y-6">
                @csrf
                
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                        Shopkeeper Email Address
                    </label>
                    <input type="email" 
                           id="email" 
                           name="email" 
                           value="{{ old('email') }}"
                           placeholder="Enter your shopkeeper email" 
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
                           required>
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                        Password
                    </label>
                    <input type="password" 
                           id="password" 
                           name="password" 
                           placeholder="Enter your password" 
                           class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent"
                           required>
                </div>

                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input type="checkbox" 
                               id="remember" 
                               name="remember" 
                               class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded">
                        <label for="remember" class="ml-2 block text-sm text-gray-900">
                            Remember me
                        </label>
                    </div>
                </div>

                <button type="submit" 
                        class="w-full bg-green-600 hover:bg-green-700 text-white font-medium py-3 rounded-lg transition-colors">
                    Login as Shopkeeper
                </button>
            </form>

            <!-- Links -->
            <div class="mt-6 text-center space-y-3">
                <a href="{{ route('admin.login') }}" 
                   class="text-sm text-gray-600 hover:text-green-600">
                    Login as Admin
                </a>
                <div class="text-sm text-gray-500">or</div>
                <a href="{{ route('login') }}" 
                   class="text-sm text-vibrant-pink hover:underline">
                    Customer Login (OTP)
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
