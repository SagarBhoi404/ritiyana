@extends('layouts.app')

@section('content')
    <div class="max-w-7xl mx-auto px-4 py-8">
        <!-- Back Button -->
        <div class="mb-6">
            <a href="{{ route('all-kits') }}" class="inline-flex items-center gap-2 text-gray-600 hover:text-vibrant-pink">
                <i data-lucide="arrow-left" class="w-4 h-4"></i>
                Back to Products
            </a>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
            <!-- Product Images -->
            <div class="space-y-4">
                <!-- Main Image Display -->
                <div class="relative">
                    <img id="mainImage" src="{{ asset('images/daily-puja-kit.jpg') }}" alt="Daily Puja Essentials Kit"
                        class="w-full h-96 object-cover rounded-2xl">
                    <div class="absolute top-4 left-4 bg-red-500 text-white text-sm font-medium px-3 py-1 rounded-full">
                        25% OFF
                    </div>
                </div>

                <!-- Image Thumbnails -->
                <div class="grid grid-cols-4 gap-2">
                    <div class="image-thumbnail cursor-pointer border-2 border-vibrant-pink rounded-lg overflow-hidden"
                        onclick="changeMainImage('{{ asset('images/daily-puja-kit.jpg') }}', this)">
                        <img src="{{ asset('images/daily-puja-kit.jpg') }}" alt="Main Kit" class="w-full h-20 object-cover">
                    </div>
                    <div class="image-thumbnail cursor-pointer border-2 border-gray-200 hover:border-vibrant-pink rounded-lg overflow-hidden"
                        onclick="changeMainImage('{{ asset('images/puja-items-1.jpg') }}', this)">
                        <img src="{{ asset('images/puja-items-1.jpg') }}" alt="Diya Set" class="w-full h-20 object-cover">
                    </div>
                    <div class="image-thumbnail cursor-pointer border-2 border-gray-200 hover:border-vibrant-pink rounded-lg overflow-hidden"
                        onclick="changeMainImage('{{ asset('images/puja-items-2.jpg') }}', this)">
                        <img src="{{ asset('images/puja-items-2.jpg') }}" alt="Incense & Thali"
                            class="w-full h-20 object-cover">
                    </div>
                    <div class="image-thumbnail cursor-pointer border-2 border-gray-200 hover:border-vibrant-pink rounded-lg overflow-hidden"
                        onclick="changeMainImage('{{ asset('images/puja-items-3.jpg') }}', this)">
                        <img src="{{ asset('images/puja-items-3.jpg') }}" alt="Complete Set"
                            class="w-full h-20 object-cover">
                    </div>
                </div>

                <!-- Product Features -->
                <div class="bg-gray-50 rounded-2xl p-6">
                    <h3 class="font-semibold mb-4 flex items-center gap-2">
                        <i data-lucide="star" class="w-5 h-5 text-yellow-500"></i>
                        Product Features
                    </h3>
                    <ul class="space-y-2 text-gray-600">
                        <li class="flex items-center gap-2">
                            <i data-lucide="check" class="w-4 h-4 text-green-600"></i>
                            Authentic and blessed items
                        </li>
                        <li class="flex items-center gap-2">
                            <i data-lucide="check" class="w-4 h-4 text-green-600"></i>
                            Delivered in 10 minutes
                        </li>
                        <li class="flex items-center gap-2">
                            <i data-lucide="check" class="w-4 h-4 text-green-600"></i>
                            Premium quality guaranteed
                        </li>
                        <li class="flex items-center gap-2">
                            <i data-lucide="check" class="w-4 h-4 text-green-600"></i>
                            Eco-friendly packaging
                        </li>
                    </ul>
                </div>

                <!-- Video Guide Section - Full Width -->
                <div class="bg-gradient-to-r from-orange-50 to-red-50 rounded-2xl p-6">
                    <div class="text-center mb-4">
                        <h3 class="text-lg font-bold mb-2">How to Use This Kit</h3>
                        <p class="text-gray-600 text-sm">Watch our step-by-step guide</p>
                    </div>

                    <div class="relative w-full h-64 md:h-80 lg:h-96 rounded-xl overflow-hidden shadow-md">
                        <iframe class="absolute inset-0 w-full h-full"
                            src="https://www.youtube.com/embed/DAYszemgPxc?si=-h1TeDBWcXpWgwn8"
                            title="Daily Puja Kit - How to Use Guide" frameborder="0"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                            referrerpolicy="strict-origin-when-cross-origin" allowfullscreen>
                        </iframe>
                    </div>

                    <div class="mt-3 text-center">
                        <p class="text-sm text-gray-600">Duration: 5:30 | Expert guidance</p>
                    </div>
                </div>

            </div>

            <!-- Product Details -->
            <div class="space-y-6">
                <!-- Product Info -->
                <div>
                    <span class="text-sm text-gray-500 bg-gray-100 px-3 py-1 rounded-full">Festival Celebrations</span>
                    <h1 class="text-3xl font-bold mt-4 mb-2">Daily Puja Essentials Kit</h1>
                    <div class="flex items-center gap-2 mb-4">
                        <div class="flex items-center gap-1 text-yellow-500">
                            <i data-lucide="star" class="w-4 h-4 fill-current"></i>
                            <i data-lucide="star" class="w-4 h-4 fill-current"></i>
                            <i data-lucide="star" class="w-4 h-4 fill-current"></i>
                            <i data-lucide="star" class="w-4 h-4 fill-current"></i>
                            <i data-lucide="star" class="w-4 h-4 fill-current"></i>
                        </div>
                        <span class="text-gray-600">4.8 (156 reviews)</span>
                    </div>
                    <p class="text-gray-600 mb-6">Complete kit for your daily spiritual practice with incense, diyas, and
                        essential items.</p>
                </div>

                <!-- Pricing -->
                <div class="border-t border-gray-200 pt-6">
                    <div class="flex items-center gap-3 mb-2">
                        <span class="text-3xl font-bold text-vibrant-pink">₹299</span>
                        <span class="text-xl text-gray-500 line-through">₹399</span>
                        <span class="bg-green-100 text-green-800 text-sm font-medium px-2 py-1 rounded">Save ₹100</span>
                    </div>
                    <div class="flex items-center gap-2 text-sm text-gray-600">
                        <i data-lucide="truck" class="w-4 h-4"></i>
                        <span>Delivery in <strong>45 minutes</strong></span>
                    </div>
                </div>

                <!-- Add to Cart -->
                <div class="border-t border-gray-200 pt-6">
                    <div class="flex items-center gap-4 mb-6">
                        <div class="flex items-center border border-gray-300 rounded-lg">
                            <button onclick="updateQuantity(-1)" class="p-3 hover:bg-gray-100 rounded-l-lg">
                                <i data-lucide="minus" class="w-4 h-4"></i>
                            </button>
                            <span id="quantity" class="px-4 py-3 font-medium">1</span>
                            <button onclick="updateQuantity(1)" class="p-3 hover:bg-gray-100 rounded-r-lg">
                                <i data-lucide="plus" class="w-4 h-4"></i>
                            </button>
                        </div>
                        <button onclick="addToCartFromDetail()"
                            class="flex-1 bg-vibrant-pink hover:bg-vibrant-pink-dark text-white font-medium py-3 rounded-lg transition-colors">
                            Add to Cart
                        </button>
                        <button class="p-3 border border-gray-300 rounded-lg hover:bg-gray-100">
                            <i data-lucide="heart" class="w-5 h-5"></i>
                        </button>
                        <button class="p-3 border border-gray-300 rounded-lg hover:bg-gray-100">
                            <i data-lucide="share-2" class="w-5 h-5"></i>
                        </button>
                    </div>

                    <!-- Service Icons -->
                    <div class="grid grid-cols-3 gap-4 text-center">
                        <div class="flex flex-col items-center gap-2">
                            <div class="bg-blue-100 p-3 rounded-full">
                                <i data-lucide="truck" class="w-6 h-6 text-blue-600"></i>
                            </div>
                            <span class="text-sm text-gray-600">Fast Delivery</span>
                        </div>
                        <div class="flex flex-col items-center gap-2">
                            <div class="bg-green-100 p-3 rounded-full">
                                <i data-lucide="shield" class="w-6 h-6 text-green-600"></i>
                            </div>
                            <span class="text-sm text-gray-600">Quality Assured</span>
                        </div>
                        <div class="flex flex-col items-center gap-2">
                            <div class="bg-purple-100 p-3 rounded-full">
                                <i data-lucide="headphones" class="w-6 h-6 text-purple-600"></i>
                            </div>
                            <span class="text-sm text-gray-600">24/7 Support</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Product Description -->
        <div class="mt-16">
            <div class="max-w-4xl">
                <h2 class="text-2xl font-bold mb-6">Product Description</h2>

                <div class="prose prose-gray max-w-none">
                    <p class="text-gray-600 mb-4">
                        Personalize your puja kit according to your specific needs and preferences.
                    </p>

                    <p class="text-gray-600 mb-6">
                        This complete daily puja kit includes all traditional elements needed for your spiritual practice.
                        Carefully curated by our spiritual experts, each item carries deep significance and helps create a
                        sacred atmosphere for your prayers. The kit follows traditional Vedic practices and includes
                        detailed instructions for the complete ritual.
                    </p>

                    <h3 class="text-lg font-semibold mb-3">What's Included:</h3>
                    <ul class="space-y-2 text-gray-600 mb-6">
                        <li class="flex items-center gap-2">
                            <i data-lucide="check" class="w-4 h-4 text-green-600"></i>
                            Premium brass diya set (5 pieces)
                        </li>
                        <li class="flex items-center gap-2">
                            <i data-lucide="check" class="w-4 h-4 text-green-600"></i>
                            Authentic sandalwood incense sticks
                        </li>
                        <li class="flex items-center gap-2">
                            <i data-lucide="check" class="w-4 h-4 text-green-600"></i>
                            Sacred red cloth and flowers
                        </li>
                        <li class="flex items-center gap-2">
                            <i data-lucide="check" class="w-4 h-4 text-green-600"></i>
                            Traditional puja thali
                        </li>
                        <li class="flex items-center gap-2">
                            <i data-lucide="check" class="w-4 h-4 text-green-600"></i>
                            Detailed puja instruction guide
                        </li>
                    </ul>

                    <h3 class="text-lg font-semibold mb-3">Special Features:</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="bg-orange-50 p-4 rounded-lg">
                            <h4 class="font-medium text-orange-800 mb-2">Removes Obstacles</h4>
                            <p class="text-orange-700 text-sm">Brings prosperity and removes obstacles from your path</p>
                        </div>
                        <div class="bg-blue-50 p-4 rounded-lg">
                            <h4 class="font-medium text-blue-800 mb-2">Enhances Wisdom</h4>
                            <p class="text-blue-700 text-sm">Increases knowledge and spiritual understanding</p>
                        </div>
                        <div class="bg-green-50 p-4 rounded-lg">
                            <h4 class="font-medium text-green-800 mb-2">Good Fortune</h4>
                            <p class="text-green-700 text-sm">Attracts good luck and positive energy</p>
                        </div>
                        <div class="bg-purple-50 p-4 rounded-lg">
                            <h4 class="font-medium text-purple-800 mb-2">Protection</h4>
                            <p class="text-purple-700 text-sm">Shields from negative energies and influences</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        let currentQuantity = 1;

        function updateQuantity(change) {
            currentQuantity = Math.max(1, currentQuantity + change);
            document.getElementById('quantity').textContent = currentQuantity;
        }

        function addToCartFromDetail() {
            const product = {
                id: '{{ $id ?? '1' }}',
                name: 'Daily Puja Essentials Kit',
                price: 299,
                originalPrice: 399,
                image: '{{ asset('images/daily-puja-kit.jpg') }}',
                description: 'Complete kit for your daily spiritual practice',
                discount: '25% OFF'
            };

            for (let i = 0; i < currentQuantity; i++) {
                addToCart(product);
            }

            alert(`${currentQuantity} item(s) added to cart!`);
        }

        // Image gallery functionality
        function changeMainImage(imageSrc, thumbnailElement) {
            // Update main image
            document.getElementById('mainImage').src = imageSrc;

            // Update thumbnail borders
            document.querySelectorAll('.image-thumbnail').forEach(thumb => {
                thumb.classList.remove('border-vibrant-pink');
                thumb.classList.add('border-gray-200');
            });

            // Highlight selected thumbnail
            thumbnailElement.classList.remove('border-gray-200');
            thumbnailElement.classList.add('border-vibrant-pink');
        }
    </script>

    <style>
        .aspect-video {
            aspect-ratio: 16 / 9;
        }

        .image-thumbnail {
            transition: border-color 0.2s ease;
        }

        .image-thumbnail:hover {
            border-color: #8B5CF6;
        }

        @media (max-width: 768px) {
            .aspect-video {
                min-height: 180px;
            }

            .grid.grid-cols-4 {
                grid-template-columns: repeat(4, 1fr);
                gap: 0.5rem;
            }
        }
    </style>
@endsection
