@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto px-4 py-12">
        <!-- Hero Section -->
        <div class="text-center mb-16">
            <h1 class="text-4xl md:text-5xl font-bold mb-6">
                About <span class="text-vibrant-pink">PujaKit</span>
            </h1>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                Bringing authentic puja samagri to your doorstep with the convenience and speed you deserve
            </p>
        </div>

        <!-- Story Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 mb-16">
            <div>
                <h2 class="text-3xl font-bold mb-6">Our Story</h2>
                <p class="text-gray-600 mb-4">
                    Founded with a deep reverence for Indian traditions and spiritual practices, PujaKit was born from the
                    vision of making authentic puja samagri accessible to everyone, everywhere.
                </p>
                <p class="text-gray-600 mb-4">
                    We understand that in today's fast-paced world, finding time to source quality puja items can be
                    challenging. That's why we've partnered with trusted artisans and suppliers across India to bring you
                    the finest religious items delivered in just Same Day.
                </p>
                <p class="text-gray-600">
                    From daily worship essentials to grand festival preparations, we ensure that your spiritual journey
                    never lacks the right materials.
                </p>
            </div>
            <div class="bg-gradient-to-r from-vibrant-pink to-purple-600 rounded-2xl p-8 text-white">
                <h3 class="text-2xl font-bold mb-4">Preserved & Delivered</h3>
                <p class="text-purple-100">
                    To make spiritual practice accessible by delivering authentic puja samagri at lightning speed,
                    preserving traditions in the modern world.
                </p>
            </div>
        </div>

        <!-- Values Section -->
        <div class="mb-16">
            <h2 class="text-3xl font-bold text-center mb-12">Our Values</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <div class="text-center">
                    <div class="bg-orange-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i data-lucide="check-circle" class="w-8 h-8 text-orange-600"></i>
                    </div>
                    <h3 class="font-semibold mb-2">Authenticity</h3>
                    <p class="text-gray-600 text-sm">Genuine products from trusted sources</p>
                </div>
                <div class="text-center">
                    <div class="bg-green-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i data-lucide="award" class="w-8 h-8 text-green-600"></i>
                    </div>
                    <h3 class="font-semibold mb-2">Quality</h3>
                    <p class="text-gray-600 text-sm">Premium grade materials and craftsmanship</p>
                </div>
                <div class="text-center">
                    <div class="bg-blue-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i data-lucide="zap" class="w-8 h-8 text-blue-600"></i>
                    </div>
                    <h3 class="font-semibold mb-2">Speed</h3>
                    <p class="text-gray-600 text-sm">Same Day delivery</p>
                </div>
                <div class="text-center">
                    <div class="bg-purple-100 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i data-lucide="heart" class="w-8 h-8 text-purple-600"></i>
                    </div>
                    <h3 class="font-semibold mb-2">Respect</h3>
                    <p class="text-gray-600 text-sm">Honor for spiritual traditions</p>
                </div>
            </div>
        </div>

        <!-- Sustainability Section -->
        <div class="bg-green-50 rounded-2xl p-8 mb-16">
            <div class="max-w-3xl mx-auto text-center">
                <h2 class="text-3xl font-bold mb-4">Committed to Sustainability</h2>
                <p class="text-gray-600 mb-6">
                    We promote eco-friendly puja items and sustainable practices to honor both tradition and nature.
                </p>
                <div class="flex justify-center">
                    <div class="bg-green-600 text-white px-6 py-3 rounded-lg">
                        <i data-lucide="leaf" class="w-5 h-5 inline mr-2"></i>
                        Eco-Friendly Products Available
                    </div>
                </div>
            </div>
        </div>

        <!-- Features Section -->
        <div class="mb-16">
            <h2 class="text-3xl font-bold text-center mb-12">Why Choose PujaKit?</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                <div class="text-center p-6 bg-white rounded-xl shadow-sm border">
                    <i data-lucide="clock" class="w-12 h-12 text-vibrant-pink mx-auto mb-4"></i>
                    <h3 class="font-semibold mb-2">Same Day Delivery</h3>
                    <p class="text-gray-600 text-sm">Lightning-fast delivery for urgent puja needs</p>
                </div>
                <div class="text-center p-6 bg-white rounded-xl shadow-sm border">
                    <i data-lucide="shield-check" class="w-12 h-12 text-green-600 mx-auto mb-4"></i>
                    <h3 class="font-semibold mb-2">Authentic Items</h3>
                    <p class="text-gray-600 text-sm">Sourced directly from trusted suppliers</p>
                </div>
                <div class="text-center p-6 bg-white rounded-xl shadow-sm border">
                    <i data-lucide="smartphone" class="w-12 h-12 text-blue-600 mx-auto mb-4"></i>
                    <h3 class="font-semibold mb-2">Easy Ordering</h3>
                    <p class="text-gray-600 text-sm">Simple app interface for quick purchases</p>
                </div>
                <div class="text-center p-6 bg-white rounded-xl shadow-sm border">
                    <i data-lucide="settings" class="w-12 h-12 text-orange-600 mx-auto mb-4"></i>
                    <h3 class="font-semibold mb-2">Custom Kits</h3>
                    <p class="text-gray-600 text-sm">Personalized puja sets for your needs</p>
                </div>
            </div>
        </div>

        <!-- CTA Section -->
        <div class="bg-vibrant-pink rounded-2xl p-8 text-white text-center">
            <h2 class="text-3xl font-bold mb-4">Ready to Start Your Spiritual Journey?</h2>
            <p class="text-purple-100 mb-6">Join thousands of satisfied customers who trust PujaKit for their spiritual
                needs</p>
            <a href="{{ route('puja-kits.index') }}"
                class="inline-block bg-white text-vibrant-pink font-semibold px-8 py-3 rounded-lg hover:bg-gray-100 transition-colors">
                Shop Now
            </a>
        </div>
    </div>
@endsection
