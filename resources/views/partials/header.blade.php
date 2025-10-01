<header class="bg-white shadow-sm sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Top Bar - Compact Info Section -->
        <div class="flex items-center justify-between py-2.5 border-b border-gray-100">
            <!-- Logo -->
            <a href="{{ route('home') }}" class="flex items-center gap-2 group">
                <img src="{{ asset('images/logo.png') }}" alt="Shree Samagri Logo" class="h-8 w-auto">
                <span class="text-lg sm:text-xl font-bold text-vibrant-pink group-hover:text-pink-600 transition-colors">
                    Shree Samagri
                </span>
            </a>

            <!-- Delivery Info - Hidden on mobile -->
            {{-- <div class="hidden md:flex items-center gap-2 text-sm text-gray-600 bg-pink-50 px-3 py-1.5 rounded-full">
                <i data-lucide="map-pin" class="w-4 h-4 text-vibrant-pink"></i>
                <span class="text-gray-600">Delivery only in</span>
                <span class="font-semibold text-vibrant-pink">Maharashtra</span>
            </div> --}}

            <!-- User Actions -->
            <div class="flex items-center gap-2 sm:gap-3">
                @auth
                    <a href="{{ route('dashboard') }}"
                        class="flex items-center gap-1.5 px-3 py-1.5 text-xs sm:text-sm text-gray-700 hover:text-vibrant-pink hover:bg-pink-50 rounded-lg transition-all">
                        <i data-lucide="user" class="w-4 h-4"></i>
                        <span class="hidden sm:inline font-medium">Dashboard</span>
                    </a>
                    <span class="hidden lg:inline text-sm text-gray-500 px-2">
                        Hi, {{ Auth::user()->first_name ?? Auth::user()->name }}!
                    </span>
                @else
                    <a href="{{ route('login') }}"
                        class="flex items-center gap-1.5 px-3 py-1.5 text-xs sm:text-sm text-gray-700 hover:text-vibrant-pink hover:bg-pink-50 rounded-lg transition-all">
                        <i data-lucide="user" class="w-4 h-4"></i>
                        <span class="hidden sm:inline font-medium">Login</span>
                    </a>
                @endauth
                
                <!-- Cart Button with Badge -->
                <button onclick="toggleCart()" class="relative p-2 hover:bg-pink-50 rounded-lg transition-all group">
                    <i data-lucide="shopping-cart" class="w-5 h-5 text-gray-700 group-hover:text-vibrant-pink transition-colors"></i>
                    <span id="cart-count"
                        class="absolute -top-0.5 -right-0.5 bg-vibrant-pink text-white text-xs rounded-full w-5 h-5 flex items-center justify-center font-semibold hidden shadow-md">0</span>
                </button>
            </div>
        </div>

        <!-- Desktop Navigation -->
        <nav class="hidden md:block py-3">
            <div class="flex items-center justify-center gap-1">
                <a href="{{ route('home') }}" 
                    class="px-4 py-2 text-sm font-medium text-gray-700 hover:text-vibrant-pink hover:bg-pink-50 rounded-lg transition-all flex items-center gap-2 group">
                    <i data-lucide="home" class="w-4 h-4 group-hover:scale-110 transition-transform"></i>
                    Home
                </a>
                <a href="{{ route('products.index') }}"
                    class="px-4 py-2 text-sm font-medium text-gray-700 hover:text-vibrant-pink hover:bg-pink-50 rounded-lg transition-all flex items-center gap-2 group">
                    <i data-lucide="box" class="w-4 h-4 group-hover:scale-110 transition-transform"></i>
                    Products
                </a>
                <a href="{{ route('puja-kits.index') }}"
                    class="px-4 py-2 text-sm font-medium text-gray-700 hover:text-vibrant-pink hover:bg-pink-50 rounded-lg transition-all flex items-center gap-2 group">
                    <i data-lucide="shopping-bag" class="w-4 h-4 group-hover:scale-110 transition-transform"></i>
                    Puja Kits
                </a>
                <a href="{{ route('upcoming-pujas') }}"
                    class="px-4 py-2 text-sm font-medium text-gray-700 hover:text-vibrant-pink hover:bg-pink-50 rounded-lg transition-all flex items-center gap-2 group">
                    <i data-lucide="calendar" class="w-4 h-4 group-hover:scale-110 transition-transform"></i>
                    Upcoming Pujas
                </a>
                <a href="{{ route('about') }}" 
                    class="px-4 py-2 text-sm font-medium text-gray-700 hover:text-vibrant-pink hover:bg-pink-50 rounded-lg transition-all flex items-center gap-2 group">
                    <i data-lucide="info" class="w-4 h-4 group-hover:scale-110 transition-transform"></i>
                    About
                </a>
                <a href="{{ route('contact') }}" 
                    class="px-4 py-2 text-sm font-medium text-gray-700 hover:text-vibrant-pink hover:bg-pink-50 rounded-lg transition-all flex items-center gap-2 group">
                    <i data-lucide="phone" class="w-4 h-4 group-hover:scale-110 transition-transform"></i>
                    Contact
                </a>
            </div>
        </nav>
    </div>

    <!-- Mobile Bottom Navigation -->
    <div id="mobile-nav"
        class="md:hidden bg-white border-t border-gray-200 shadow-lg fixed bottom-0 left-0 right-0 z-50 transition-transform duration-300 ease-in-out">
        <nav class="safe-bottom">
            <div class="flex justify-around items-center px-2 py-2">
                <a href="{{ route('home') }}"
                    class="flex flex-col items-center justify-center p-2 text-gray-600 hover:text-vibrant-pink active:scale-95 transition-all min-w-[60px] group">
                    <div class="relative">
                        <i data-lucide="home" class="w-5 h-5 mb-1"></i>
                    </div>
                    <span class="text-[10px] font-medium">Home</span>
                </a>
                
                <a href="{{ route('products.index') }}"
                    class="flex flex-col items-center justify-center p-2 text-gray-600 hover:text-vibrant-pink active:scale-95 transition-all min-w-[60px] group">
                    <div class="relative">
                        <i data-lucide="box" class="w-5 h-5 mb-1"></i>
                    </div>
                    <span class="text-[10px] font-medium">Products</span>
                </a>
                
                <a href="{{ route('puja-kits.index') }}"
                    class="flex flex-col items-center justify-center p-2 text-gray-600 hover:text-vibrant-pink active:scale-95 transition-all min-w-[60px] group">
                    <div class="relative">
                        <i data-lucide="shopping-bag" class="w-5 h-5 mb-1"></i>
                    </div>
                    <span class="text-[10px] font-medium">Puja Kits</span>
                </a>
                
                <a href="{{ route('upcoming-pujas') }}"
                    class="flex flex-col items-center justify-center p-2 text-gray-600 hover:text-vibrant-pink active:scale-95 transition-all min-w-[60px] group">
                    <div class="relative">
                        <i data-lucide="calendar" class="w-5 h-5 mb-1"></i>
                    </div>
                    <span class="text-[10px] font-medium">Pujas</span>
                </a>
                
                <button onclick="toggleMoreMenu()"
                    class="flex flex-col items-center justify-center p-2 text-gray-600 hover:text-vibrant-pink active:scale-95 transition-all min-w-[60px] group">
                    <div class="relative">
                        <i data-lucide="menu" class="w-5 h-5 mb-1"></i>
                    </div>
                    <span class="text-[10px] font-medium">More</span>
                </button>
            </div>
        </nav>
    </div>

    <!-- More Menu Modal (Mobile) -->
    <div id="more-menu" class="hidden md:hidden fixed inset-0 bg-black bg-opacity-50 z-50 transition-opacity" onclick="toggleMoreMenu()">
        <div class="absolute bottom-0 left-0 right-0 bg-white rounded-t-2xl shadow-2xl animate-slide-up" onclick="event.stopPropagation()">
            <div class="p-4">
                <div class="w-12 h-1 bg-gray-300 rounded-full mx-auto mb-4"></div>
                <h3 class="text-lg font-bold text-gray-800 mb-4">More Options</h3>
                
                <div class="space-y-2">
                    <a href="{{ route('about') }}" 
                        class="flex items-center gap-3 p-3 hover:bg-pink-50 rounded-lg transition-all">
                        <i data-lucide="info" class="w-5 h-5 text-vibrant-pink"></i>
                        <span class="font-medium text-gray-700">About Us</span>
                    </a>
                    
                    <a href="{{ route('contact') }}" 
                        class="flex items-center gap-3 p-3 hover:bg-pink-50 rounded-lg transition-all">
                        <i data-lucide="phone" class="w-5 h-5 text-vibrant-pink"></i>
                        <span class="font-medium text-gray-700">Contact</span>
                    </a>
                    
                    @auth
                        <a href="{{ route('dashboard') }}"
                            class="flex items-center gap-3 p-3 hover:bg-pink-50 rounded-lg transition-all">
                            <i data-lucide="user" class="w-5 h-5 text-vibrant-pink"></i>
                            <span class="font-medium text-gray-700">Dashboard</span>
                        </a>
                    @else
                        <a href="{{ route('login') }}"
                            class="flex items-center gap-3 p-3 hover:bg-pink-50 rounded-lg transition-all">
                            <i data-lucide="user" class="w-5 h-5 text-vibrant-pink"></i>
                            <span class="font-medium text-gray-700">Login</span>
                        </a>
                    @endauth
                </div>
                
                <button onclick="toggleMoreMenu()" 
                    class="w-full mt-4 py-3 bg-gray-100 hover:bg-gray-200 rounded-lg font-medium text-gray-700 transition-all">
                    Close
                </button>
            </div>
        </div>
    </div>
</header>

<style>
    @keyframes slide-up {
        from {
            transform: translateY(100%);
        }
        to {
            transform: translateY(0);
        }
    }
    
    .animate-slide-up {
        animation: slide-up 0.3s ease-out;
    }
    
    .safe-bottom {
        padding-bottom: env(safe-area-inset-bottom);
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const mobileNav = document.getElementById('mobile-nav');
        let lastScrollY = window.scrollY;
        let isScrolling = false;

        function handleScroll() {
            const currentScrollY = window.scrollY;

            if (currentScrollY <= 50) {
                mobileNav.style.transform = 'translateY(0)';
            } else if (currentScrollY > lastScrollY && currentScrollY > 100) {
                mobileNav.style.transform = 'translateY(100%)';
            } else if (currentScrollY < lastScrollY) {
                mobileNav.style.transform = 'translateY(0)';
            }

            lastScrollY = currentScrollY;
            isScrolling = false;
        }

        window.addEventListener('scroll', function() {
            if (!isScrolling) {
                window.requestAnimationFrame(handleScroll);
                isScrolling = true;
            }
        });
    });

    function toggleMoreMenu() {
        const moreMenu = document.getElementById('more-menu');
        moreMenu.classList.toggle('hidden');
    }
</script>
