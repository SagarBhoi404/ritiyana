@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto px-4 py-16 text-center">
    <div class="mb-8">
        <div class="text-9xl font-bold text-vibrant-pink mb-4">404</div>
        <h1 class="text-3xl font-bold text-gray-800 mb-4">Oops! Page not found</h1>
        <p class="text-gray-600 mb-8">The page you're looking for doesn't exist or has been moved.</p>
    </div>
    
    <div class="space-y-4">
        <a href="{{ route('home') }}" class="inline-block bg-vibrant-pink hover:bg-vibrant-pink-dark text-white font-medium px-8 py-3 rounded-lg transition-colors">
            Return to Home
        </a>
        <div>
            <a href="{{ route('all-kits') }}" class="text-vibrant-pink hover:underline mr-6">Browse Products</a>
            <a href="{{ route('contact') }}" class="text-vibrant-pink hover:underline">Contact Support</a>
        </div>
    </div>
</div>
@endsection
