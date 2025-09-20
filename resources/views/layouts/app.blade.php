<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? 'Ritiyana' }}</title>

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- jQuery - Load before other scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Google Fonts -->
    {{-- <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet"> --}}

    <link rel="shortcut icon" href="{{ asset('favicon/favicon.ico') }}">
    <link rel="manifest" href="{{ asset('favicon/site.webmanifest') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('favicon/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon/favicon-16x16.png') }}">

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

        /* Loading spinner */
        .spinner {
            border: 2px solid #f3f3f3;
            border-top: 2px solid #ff3c65;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        /* Toast notifications */
        .toast {
            transform: translateX(100%);
            transition: transform 0.3s ease-in-out;
        }

        .toast.show {
            transform: translateX(0);
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

    <!-- Toast Container -->
    <div id="toast-container" class="fixed top-4 right-4 z-50 space-y-2"></div>

    <script>
        // Initialize cart variable first
        let cart = {
            isOpen: false,
            loading: false
        };

        // Set up CSRF token for AJAX requests (jQuery is already loaded)
        $(document).ready(function() {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
        });

        // Initialize Lucide icons after page loads
        document.addEventListener('DOMContentLoaded', function() {
            lucide.createIcons();
            loadCartData(); // Load cart data on page load

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

        // Show toast notification
        function showToast(message, type = 'success') {
            const toastContainer = document.getElementById('toast-container');
            const toastId = 'toast-' + Date.now();

            const bgColor = type === 'success' ? 'bg-green-500' : type === 'error' ? 'bg-red-500' : 'bg-blue-500';

            const toast = document.createElement('div');
            toast.id = toastId;
            toast.className = `toast ${bgColor} text-white px-6 py-3 rounded-lg shadow-lg flex items-center gap-2 min-w-72`;
            toast.innerHTML = `
                <i data-lucide="${type === 'success' ? 'check-circle' : type === 'error' ? 'x-circle' : 'info'}" class="w-5 h-5"></i>
                <span>${message}</span>
                <button onclick="removeToast('${toastId}')" class="ml-auto">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            `;

            toastContainer.appendChild(toast);
            lucide.createIcons();

            // Show toast
            setTimeout(() => {
                toast.classList.add('show');
            }, 100);

            // Auto remove after 5 seconds
            setTimeout(() => {
                removeToast(toastId);
            }, 5000);
        }

        function removeToast(toastId) {
            const toast = document.getElementById(toastId);
            if (toast) {
                toast.classList.remove('show');
                setTimeout(() => {
                    toast.remove();
                }, 300);
            }
        }

        // Load cart data from server
        function loadCartData() {
            fetch('{{ route('cart.mini') }}')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        updateCartBadge(data.count);
                        updateCartSidebar(data.items, data.total, data.count);
                    }
                })
                .catch(error => {
                    console.error('Error loading cart:', error);
                });
        }

        function toggleCart() {
            cart.isOpen = !cart.isOpen;
            const sidebar = document.getElementById('cart-sidebar');
            const overlay = document.getElementById('cart-overlay');

            if (cart.isOpen) {
                sidebar.classList.remove('translate-x-full');
                overlay.classList.remove('hidden');
                loadCartData(); // Refresh cart data when opening
            } else {
                sidebar.classList.add('translate-x-full');
                overlay.classList.add('hidden');
            }
        }

        // Add product to cart
        function addToCart(productId, quantity = 1, options = null) {
            if (cart.loading) return;

            cart.loading = true;
            const button = event.target;
            const originalText = button.innerHTML;

            button.innerHTML = '<div class="spinner mx-auto"></div>';
            button.disabled = true;

            fetch('{{ route('cart.add') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({
                        product_id: productId,
                        quantity: quantity,
                        options: options
                    })
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        showToast(data.message, 'success');
                        updateCartBadge(data.cart_count);
                        if (cart.isOpen) {
                            loadCartData();
                        }
                    } else {
                        showToast(data.message, 'error');
                    }
                })
                .catch(error => {
                    console.error('Error adding to cart:', error);
                    showToast('Failed to add item to cart', 'error');
                })
                .finally(() => {
                    cart.loading = false;
                    button.innerHTML = originalText;
                    button.disabled = false;
                });
        }


        // Add puja kit to cart
        function addPujaKitToCart(pujaKitId, quantity = 1) {
            if (cart.loading) return;

            cart.loading = true;
            const button = event.target;
            const originalText = button.innerHTML;

            // Show loading state
            button.innerHTML = '<div class="spinner mx-auto"></div>';
            button.disabled = true;

            // Use FormData for better compatibility
            const formData = new FormData();
            formData.append('puja_kit_id', pujaKitId);
            formData.append('quantity', quantity);

            fetch('/cart/add-puja-kit', {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json', // This is crucial
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: formData
                })
                .then(response => {
                    console.log('Response status:', response.status);

                    if (response.status === 302) {
                        throw new Error('Redirected - possible authentication or validation issue');
                    }

                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        showToast(data.message, 'success');
                        updateCartBadge(data.cart_count);
                        // Refresh cart sidebar if open
                        if (cart.isOpen) {
                            loadCartData();
                        }
                    } else {
                        showToast(data.message, 'error');
                    }
                })
                .catch(error => {
                    console.error('Error adding puja kit to cart:', error);
                    showToast('Failed to add puja kit to cart', 'error');
                })
                .finally(() => {
                    cart.loading = false;
                    button.innerHTML = originalText;
                    button.disabled = false;
                });
        }

        // Update quantity
        function updateQuantity(cartId, quantity) {
            if (cart.loading) return;

            cart.loading = true;

            const formData = new FormData();
            formData.append('_method', 'PUT');
            formData.append('quantity', quantity);
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

            fetch(`/cart/${cartId}`, {
                    method: 'POST', // Use POST with method override
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: formData
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        updateCartBadge(data.cart_count);
                        loadCartData(); // Refresh cart display
                        if (quantity === 0) {
                            showToast('Item removed from cart', 'success');
                        }
                    } else {
                        showToast(data.message, 'error');
                    }
                })
                .catch(error => {
                    console.error('Error updating quantity:', error);
                    showToast('Failed to update quantity', 'error');
                })
                .finally(() => {
                    cart.loading = false;
                });
        }

        // Remove from cart
        function removeFromCart(cartId) {
            if (cart.loading) return;

            cart.loading = true;

            // Use FormData with _method override for DELETE
            const formData = new FormData();
            formData.append('_method', 'DELETE');
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

            fetch(`/cart/${cartId}`, {
                    method: 'POST', // Use POST with method override
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: formData
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        showToast(data.message, 'success');
                        updateCartBadge(data.cart_count);
                        loadCartData(); // Refresh cart display
                    } else {
                        showToast(data.message, 'error');
                    }
                })
                .catch(error => {
                    console.error('Error removing from cart:', error);
                    showToast('Failed to remove item', 'error');
                })
                .finally(() => {
                    cart.loading = false;
                });
        }

        // Clear entire cart
        function clearCart() {
            if (cart.loading) return;

            if (!confirm('Are you sure you want to clear your cart?')) {
                return;
            }

            cart.loading = true;

            const formData = new FormData();
            formData.append('_method', 'DELETE');
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));

            fetch('/cart', {
                    method: 'POST', // Use POST with method override
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: formData
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        showToast(data.message, 'success');
                        updateCartBadge(0);
                        loadCartData();
                    } else {
                        showToast(data.message, 'error');
                    }
                })
                .catch(error => {
                    console.error('Error clearing cart:', error);
                    showToast('Failed to clear cart', 'error');
                })
                .finally(() => {
                    cart.loading = false;
                });
        }

        // Update cart badge
        function updateCartBadge(count) {
            const cartCountElement = document.getElementById('cart-count');
            if (cartCountElement) {
                cartCountElement.textContent = count;
                cartCountElement.style.display = count > 0 ? 'block' : 'none';
            }
        }

        // Update cart sidebar
        function updateCartSidebar(items, total, count) {
            const cartItems = document.getElementById('cart-items');
            const cartTotal = document.getElementById('cart-total');
            const cartCount = document.getElementById('cart-sidebar-count');

            if (cartCount) cartCount.textContent = count;
            if (cartTotal) cartTotal.textContent = total;

            if (cartItems) {
                if (!items || items.length === 0) {
                    cartItems.innerHTML = `
                <div class="flex flex-col items-center justify-center h-64 text-gray-500">
                    <i data-lucide="shopping-bag" class="w-16 h-16 mb-4"></i>
                    <h3 class="text-lg font-medium mb-2">Your cart is empty</h3>
                    <p class="text-sm">Add some puja items to get started</p>
                </div>
            `;
                } else {
                    cartItems.innerHTML = items.map(item => `
                <div class="flex items-center gap-3 p-4 bg-white rounded-lg border">
                    <img src="${item.display_image || '/images/placeholder.jpg'}" alt="${item.display_name}" class="w-16 h-16 object-cover rounded-lg">
                    <div class="flex-1">
                        <h3 class="font-medium text-sm">${item.display_name}</h3>
                        <div class="flex items-center gap-2 mt-1">
                            <span class="font-semibold">${item.formatted_price}</span>
                            ${item.item_type === 'puja_kit' ? '<span class="text-xs bg-orange-100 text-orange-600 px-2 py-1 rounded">Puja Kit</span>' : ''}
                        </div>
                        <div class="flex items-center gap-2 mt-2">
                            <button onclick="updateQuantity(${item.id}, ${item.quantity - 1})" class="p-1 hover:bg-gray-200 rounded" ${cart.loading ? 'disabled' : ''}>
                                <i data-lucide="minus" class="w-4 h-4"></i>
                            </button>
                            <span class="px-2 min-w-8 text-center">${item.quantity}</span>
                            <button onclick="updateQuantity(${item.id}, ${item.quantity + 1})" class="p-1 hover:bg-gray-200 rounded" ${cart.loading ? 'disabled' : ''}>
                                <i data-lucide="plus" class="w-4 h-4"></i>
                            </button>
                        </div>
                        <div class="text-sm font-medium text-gray-900 mt-1">
                            Subtotal: ${item.formatted_subtotal}
                        </div>
                    </div>
                    <button onclick="removeFromCart(${item.id})" class="text-red-500 hover:text-red-700 p-1" ${cart.loading ? 'disabled' : ''}>
                        <i data-lucide="x" class="w-4 h-4"></i>
                    </button>
                </div>
            `).join('');
                }

                lucide.createIcons();
            }
        }


        function proceedToCheckout() {
            // Check if cart has items first
            fetch('{{ route('cart.count') }}')
                .then(response => response.json())
                .then(data => {
                    if (data.count === 0) {
                        showToast('Your cart is empty', 'error');
                        return;
                    }

                    // Redirect to checkout page
                    window.location.href = "{{ route('checkout') ?? '#' }}";
                })
                .catch(error => {
                    console.error('Error checking cart:', error);
                    showToast('Failed to proceed to checkout', 'error');
                });
        }
    </script>

    <!-- Swiper JS -->
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
