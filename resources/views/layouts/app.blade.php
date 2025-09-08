<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $title ?? 'Ritiyana' }}</title>

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Google Fonts -->
    {{-- <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet"> --}}

   

    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>

    <link rel="stylesheet" href="https://unpkg.com/swiper@10/swiper-bundle.min.css">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        'inter': ['Inter', 'sans-serif'],
                    },
                    colors: {
                        'vibrant-pink': '#ff3c65',
                        'vibrant-pink-light': '#ff9aaf',
                        'vibrant-pink-dark': '#dc002e',
                    }
                }
            }
        }
    </script>

    <style>
        body {
            font-family: 'Inter', system-ui, sans-serif;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        .category-scroll::-webkit-scrollbar {
            display: none;
        }

        .category-scroll {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        /* Hide scrollbar */
        .scrollbar-hide {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        .scrollbar-hide::-webkit-scrollbar {
            display: none;
        }

        /* Mobile nav styles */
        #mobile-nav {
            will-change: transform;
        }

        /* Add bottom padding to body to prevent content hiding behind fixed nav */
        @media (max-width: 767px) {
            body {
                padding-bottom: 80px;
            }
        }
    </style>
</head>

<body class="bg-gray-50 text-gray-900">
    @include('partials.header')

    <main>
        @yield('content')
    </main>

    @include('partials.footer')

    <!-- Cart Sidebar -->
    @include('partials.cart-sidebar')

    <script>
        // Initialize Lucide icons after page loads
        document.addEventListener('DOMContentLoaded', function() {
            lucide.createIcons();

            // Close mobile menu when clicking on nav links
            const mobileMenuLinks = document.querySelectorAll('#mobile-menu a');
            mobileMenuLinks.forEach(link => {
                link.addEventListener('click', function() {
                    document.getElementById('mobile-menu').classList.add('hidden');
                    document.getElementById('menu-icon').classList.remove('hidden');
                    document.getElementById('close-icon').classList.add('hidden');
                });
            });
        });

        // Cart functionality
        let cart = {
            items: [],
            isOpen: false,
            total: 0,
            itemCount: 0
        };

        function toggleCart() {
            cart.isOpen = !cart.isOpen;
            const sidebar = document.getElementById('cart-sidebar');
            const overlay = document.getElementById('cart-overlay');

            if (cart.isOpen) {
                sidebar.classList.remove('translate-x-full');
                overlay.classList.remove('hidden');
            } else {
                sidebar.classList.add('translate-x-full');
                overlay.classList.add('hidden');
            }
        }

        function addToCart(product) {
            const existingItem = cart.items.find(item => item.id === product.id);
            if (existingItem) {
                existingItem.quantity += 1;
            } else {
                cart.items.push({
                    ...product,
                    quantity: 1
                });
            }
            updateCartDisplay();
        }

        function updateQuantity(id, quantity) {
            if (quantity <= 0) {
                cart.items = cart.items.filter(item => item.id !== id);
            } else {
                const item = cart.items.find(item => item.id === id);
                if (item) {
                    item.quantity = quantity;
                }
            }
            updateCartDisplay();
        }

        function removeFromCart(id) {
            cart.items = cart.items.filter(item => item.id !== id);
            updateCartDisplay();
        }

        function clearCart() {
            cart.items = [];
            updateCartDisplay();
        }

        function updateCartDisplay() {
            cart.itemCount = cart.items.reduce((sum, item) => sum + item.quantity, 0);
            cart.total = cart.items.reduce((sum, item) => sum + (item.price * item.quantity), 0);

            // Update cart badge
            const cartCountElement = document.getElementById('cart-count');
            if (cartCountElement) {
                cartCountElement.textContent = cart.itemCount;
                cartCountElement.style.display = cart.itemCount > 0 ? 'block' : 'none';
            }

            // Update cart sidebar
            updateCartSidebar();
        }

        function updateCartSidebar() {
            const cartItems = document.getElementById('cart-items');
            const cartTotal = document.getElementById('cart-total');
            const cartCount = document.getElementById('cart-sidebar-count');

            if (cartCount) cartCount.textContent = cart.itemCount;
            if (cartTotal) cartTotal.textContent = cart.total;

            if (cartItems) {
                if (cart.items.length === 0) {
                    cartItems.innerHTML = `
                    <div class="flex flex-col items-center justify-center h-64 text-gray-500">
                        <i data-lucide="shopping-bag" class="w-16 h-16 mb-4"></i>
                        <h3 class="text-lg font-medium mb-2">Your cart is empty</h3>
                        <p class="text-sm">Add some puja items to get started</p>
                    </div>
                `;
                } else {
                    cartItems.innerHTML = cart.items.map(item => `
                    <div class="flex items-center gap-3 p-4 bg-white rounded-lg border">
                        <img src="${item.image}" alt="${item.name}" class="w-16 h-16 object-cover rounded-lg">
                        <div class="flex-1">
                            <h3 class="font-medium text-sm">${item.name}</h3>
                            <div class="flex items-center gap-2 mt-1">
                                <span class="font-semibold">₹${item.price}</span>
                                ${item.originalPrice ? `<span class="text-xs text-gray-500 line-through">₹${item.originalPrice}</span>` : ''}
                            </div>
                            <div class="flex items-center gap-2 mt-2">
                                <button onclick="updateQuantity('${item.id}', ${item.quantity - 1})" class="p-1 hover:bg-gray-200 rounded">
                                    <i data-lucide="minus" class="w-4 h-4"></i>
                                </button>
                                <span class="px-2">${item.quantity}</span>
                                <button onclick="updateQuantity('${item.id}', ${item.quantity + 1})" class="p-1 hover:bg-gray-200 rounded">
                                    <i data-lucide="plus" class="w-4 h-4"></i>
                                </button>
                            </div>
                        </div>
                        <button onclick="removeFromCart('${item.id}')" class="text-red-500 hover:text-red-700 p-1">
                            <i data-lucide="x" class="w-4 h-4"></i>
                        </button>
                    </div>
                `).join('');
                }

                lucide.createIcons();
            }
        }

        function proceedToCheckout() {
            if (cart.items.length === 0) {
                alert('Your cart is empty');
                return;
            }

            // Store cart data in localStorage for checkout page
            localStorage.setItem('cart', JSON.stringify(cart));

            // Redirect to checkout page
            window.location.href = "{{ route('checkout') }}";
        }
    </script>

    <!-- Add before closing </body> tag -->
    <script src="https://unpkg.com/swiper@10/swiper-bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize banner slider
            const bannerSwiper = new Swiper('.banner-swiper', {
                loop: true,
                autoplay: {
                    delay: 4000,
                    disableOnInteraction: false,
                },
                effect: 'fade',
                fadeEffect: {
                    crossFade: true
                },
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true,
                    renderBullet: function(index, className) {
                        return '<span class="' + className + ' !bg-white !opacity-50"></span>';
                    },
                },
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                },
            });
        });
    </script>

</body>

</html>
