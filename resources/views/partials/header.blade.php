<header class="bg-white border-b border-gray-200 sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4">
        <!-- Top Bar -->
        <div class="flex items-center justify-between py-3 border-b border-gray-100">
            <div class="flex items-center gap-2 text-sm text-gray-600">
                <i data-lucide="map-pin" class="w-4 h-4"></i>
                <span>Delivering to</span>
                <button class="font-medium text-vibrant-pink hover:underline">
                    Select Location
                </button>
            </div>

            <div class="flex items-center gap-4">
                <a href="{{ route('login') }}"
                    @auth
<a href="{{ route('dashboard') }}"
                        class="text-sm text-gray-600 hover:text-vibrant-pink flex items-center gap-1">
                        <i data-lucide="user" class="w-4 h-4"></i>
                        Dashboard
                    </a>
                    <!-- Optional: Show user name -->
                    <span class="text-sm text-gray-500">
                        Hello, {{ Auth::user()->first_name ?? Auth::user()->name }}!
                    </span>
                @else
                    <!-- User is not logged in - Show Login -->
                    <a href="{{ route('login') }}"
                        class="text-sm text-gray-600 hover:text-vibrant-pink flex items-center gap-1">
                        <i data-lucide="user" class="w-4 h-4"></i>
                        Login
                    </a> @endauth
                    <button onclick="toggleCart()" class="relative p-2">
                    <i data-lucide="shopping-cart" class="w-5 h-5"></i>
                    <span id="cart-count"
                        class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center hidden">0</span>
                    </button>
                    <!-- Mobile Menu Button -->
                    {{-- <button
                    onclick="document.getElementById('mobile-menu').classList.toggle('hidden'); document.getElementById('menu-icon').classList.toggle('hidden'); document.getElementById('close-icon').classList.toggle('hidden');"
                    class="md:hidden p-2">
                    <i data-lucide="menu" class="w-5 h-5" id="menu-icon"></i>
                    <i data-lucide="x" class="w-5 h-5 hidden" id="close-icon"></i>
                </button> --}}
            </div>
        </div>

        <!-- Main Header -->
        <div class="flex items-center gap-4 py-4">
            <div class="flex items-center gap-3">
                <a href="{{ route('home') }}" class="flex items-center gap-2 md:gap-3">
                    <img src="{{ asset('images/logo.png') }}" alt="PujaKit Logo" class="h-8 md:h-12 w-auto">
                    <span class="text-xl md:text-2xl font-bold text-vibrant-pink">Shree Samagri</span>
                </a>
            </div>



            <!-- Search Bar -->
            <div class="flex-1 max-w-md">
                {{-- <div class="relative">
                    <input type="text" placeholder="Search for puja items..."
                        class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-vibrant-pink focus:border-transparent">
                    <i data-lucide="search" class="w-5 h-5 text-gray-400 absolute left-3 top-2.5"></i>
                </div> --}}
            </div>

            <!-- Desktop Navigation -->
            <nav class="hidden md:flex items-center gap-6">
                <a href="{{ route('home') }}" class="text-gray-700 hover:text-vibrant-pink flex items-center gap-1">
                    <i data-lucide="home" class="w-4 h-4"></i>
                    Home
                </a>
                <a href="{{ route('products.index') }}"
                    class="text-gray-700 hover:text-vibrant-pink flex items-center gap-1">
                    <i data-lucide="box" class="w-4 h-4"></i>
                    Products
                </a>

                <a href="{{ route('puja-kits.index') }}"
                    class="text-gray-700 hover:text-vibrant-pink flex items-center gap-1">
                    <i data-lucide="shopping-bag" class="w-4 h-4"></i>
                    Puja Kits
                </a>
                <a href="{{ route('upcoming-pujas') }}"
                    class="text-gray-700 hover:text-vibrant-pink flex items-center gap-1">
                    <i data-lucide="calendar" class="w-4 h-4"></i>
                    Upcoming Pujas
                </a>
                {{-- <a href="{{ route('consult') }}" class="text-gray-700 hover:text-vibrant-pink flex items-center gap-1">
                    <i data-lucide="user-check" class="w-4 h-4"></i>
                    Consult
                </a> --}}
                <a href="{{ route('about') }}" class="text-gray-700 hover:text-vibrant-pink flex items-center gap-1">
                    <i data-lucide="info" class="w-4 h-4"></i>
                    About
                </a>
                <a href="{{ route('contact') }}" class="text-gray-700 hover:text-vibrant-pink flex items-center gap-1">
                    <i data-lucide="phone" class="w-4 h-4"></i>
                    Contact
                </a>
                
            </nav>

        </div>

        <!-- Mobile Menu -->
        <!-- Mobile Menu - Hide/Show on scroll -->
        <div id="mobile-nav"
            class="md:hidden bg-white border-t border-gray-200 shadow-lg fixed bottom-0 left-0 right-0 z-40 transition-all duration-300 ease-in-out transform translate-y-0">
            <nav class="py-2">
                <div class="flex gap-1 overflow-x-auto px-2 scrollbar-hide">
                    <a href="{{ route('home') }}"
                        class="flex-shrink-0 flex flex-col items-center justify-center p-2 text-gray-700 hover:text-vibrant-pink rounded-lg min-w-[70px]">
                        <div class="bg-gray-100 p-2 rounded-lg mb-1">
                            <i data-lucide="home" class="w-4 h-4"></i>
                        </div>
                        <span class="text-xs font-medium">Home</span>
                    </a>
                    <a href="{{ route('products.index') }}"
                        class="flex-shrink-0 flex flex-col items-center justify-center p-2 text-gray-700 hover:text-vibrant-pink rounded-lg min-w-[70px]">
                        <div class="bg-gray-100 p-2 rounded-lg mb-1">
                            <i data-lucide="box" class="w-4 h-4"></i>
                        </div>
                        <span class="text-xs font-medium">Products</span>
                    </a>
                    <a href="{{ route('puja-kits.index') }}"
                        class="flex-shrink-0 flex flex-col items-center justify-center p-2 text-gray-700 hover:text-vibrant-pink rounded-lg min-w-[70px]">
                        <div class="bg-gray-100 p-2 rounded-lg mb-1">
                            <i data-lucide="shopping-bag" class="w-4 h-4"></i>
                        </div>
                        <span class="text-xs font-medium">Puja Kits</span>
                    </a>
                    <a href="{{ route('upcoming-pujas') }}"
                        class="flex-shrink-0 flex flex-col items-center justify-center p-2 text-gray-700 hover:text-vibrant-pink rounded-lg min-w-[70px]">
                        <div class="bg-gray-100 p-2 rounded-lg mb-1">
                            <i data-lucide="calendar" class="w-4 h-4"></i>
                        </div>
                        <span class="text-xs font-medium">Pujas</span>
                    </a>
                    {{-- <a href="{{ route('consult') }}"
                        class="flex-shrink-0 flex flex-col items-center justify-center p-2 text-gray-700 hover:text-vibrant-pink rounded-lg min-w-[70px]">
                        <div class="bg-gray-100 p-2 rounded-lg mb-1">
                            <i data-lucide="user-check" class="w-4 h-4"></i>
                        </div>
                        <span class="text-xs font-medium">Consult</span>
                    </a> --}}
                    <a href="{{ route('about') }}"
                        class="flex-shrink-0 flex flex-col items-center justify-center p-2 text-gray-700 hover:text-vibrant-pink rounded-lg min-w-[70px]">
                        <div class="bg-gray-100 p-2 rounded-lg mb-1">
                            <i data-lucide="info" class="w-4 h-4"></i>
                        </div>
                        <span class="text-xs font-medium">About</span>
                    </a>
                    <a href="{{ route('contact') }}"
                        class="flex-shrink-0 flex flex-col items-center justify-center p-2 text-gray-700 hover:text-vibrant-pink rounded-lg min-w-[70px]">
                        <div class="bg-gray-100 p-2 rounded-lg mb-1">
                            <i data-lucide="phone" class="w-4 h-4"></i>
                        </div>
                        <span class="text-xs font-medium">Contact</span>
                    </a>

                    @auth
                        <a href="{{ route('dashboard') }}"
                            class="flex-shrink-0 flex flex-col items-center justify-center p-2 text-gray-700 hover:text-vibrant-pink rounded-lg min-w-[70px]">
                            <div class="bg-gray-100 p-2 rounded-lg mb-1">
                                <i data-lucide="user" class="w-4 h-4"></i>
                            </div>
                            <span class="text-xs font-medium">Dashboard</span>
                        </a>
                    @else
                        <a href="{{ route('login') }}"
                            class="flex-shrink-0 flex flex-col items-center justify-center p-2 text-gray-700 hover:text-vibrant-pink rounded-lg min-w-[70px]">
                            <div class="bg-gray-100 p-2 rounded-lg mb-1">
                                <i data-lucide="user" class="w-4 h-4"></i>
                            </div>
                            <span class="text-xs font-medium">Login</span>
                        </a>
                    @endauth
                </div>
            </nav>
        </div>



    </div>
</header>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const mobileNav = document.getElementById('mobile-nav');
        let lastScrollY = window.scrollY;
        let isScrolling = false;

        function handleScroll() {
            const currentScrollY = window.scrollY;

            // Show when at top
            if (currentScrollY <= 50) {
                mobileNav.style.transform = 'translateY(0)';
            }
            // Hide when scrolling down
            else if (currentScrollY > lastScrollY && currentScrollY > 100) {
                mobileNav.style.transform = 'translateY(100%)';
            }
            // Show when scrolling up
            else if (currentScrollY < lastScrollY) {
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
</script>
