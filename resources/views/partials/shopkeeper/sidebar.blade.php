<!-- resources/views/partials/shopkeeper/sidebar.blade.php -->
<div class="flex flex-col h-full bg-white border-r border-gray-200">
    <!-- Logo -->
    <div class="flex items-center justify-center h-16 px-4 bg-gradient-to-r from-purple-600 to-pink-600">
        <div class="flex items-center space-x-3">
            <div class="w-10 h-10 bg-white bg-opacity-20 rounded-lg flex items-center justify-center p-1">
                <img src="{{ asset('images/logo.png') }}" alt="Shree Samagri Logo" class="w-full h-full object-contain">
            </div>
            <h1 class="text-xl font-bold text-white">Shree Samagri</h1>
        </div>
    </div>

    <!-- Navigation -->
    <nav class="mt-8 flex-1 px-4 space-y-2 overflow-y-auto">
        <!-- Dashboard -->
        <a href="{{ route('vendor.dashboard') }}"
            class="group flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 {{ request()->routeIs('vendor.dashboard') ? 'bg-gradient-to-r from-purple-500 to-pink-500 text-white shadow-lg' : 'text-gray-700 hover:bg-gray-50' }}">
            <i data-lucide="layout-dashboard"
                class="mr-3 h-5 w-5 {{ request()->routeIs('vendor.dashboard') ? 'text-white' : 'text-gray-400 group-hover:text-gray-600' }}"></i>
            Dashboard
        </a>

        <!-- Products -->
        <div x-data="{ open: {{ request()->routeIs('vendor.products*') ? 'true' : 'false' }} }">
            <button @click="open = !open"
                class="w-full group flex items-center justify-between px-4 py-3 text-sm font-medium text-gray-700 rounded-xl hover:bg-gray-50 transition-all duration-200 {{ request()->routeIs('vendor.products*') ? 'bg-green-50 text-green-700' : '' }}">
                <div class="flex items-center">
                    <i data-lucide="package"
                        class="mr-3 h-5 w-5 {{ request()->routeIs('vendor.products*') ? 'text-green-600' : 'text-gray-400 group-hover:text-gray-600' }}"></i>
                    Products
                </div>
                <i data-lucide="chevron-down" class="h-4 w-4 transition-transform duration-200"
                    :class="open ? 'rotate-180' : ''"></i>
            </button>
            <div x-show="open" x-transition class="mt-2 pl-9 space-y-1">
                <a href="{{ route('vendor.products.index') }}"
                    class="block px-4 py-2 text-sm {{ request()->routeIs('vendor.products.index') ? 'text-green-600 bg-green-50' : 'text-gray-600' }} rounded-lg hover:bg-gray-50 transition-colors">All
                    Products</a>
                <a href="{{ route('vendor.products.create') }}"
                    class="block px-4 py-2 text-sm {{ request()->routeIs('vendor.products.create') ? 'text-green-600 bg-green-50' : 'text-gray-600' }} rounded-lg hover:bg-gray-50 transition-colors">Add
                    Product</a>
                <a href="{{ route('vendor.inventory.index') }}"
                    class="block px-4 py-2 text-sm text-gray-600 rounded-lg hover:bg-gray-50 transition-colors">Inventory</a>
            </div>
        </div>

        <!-- Pujas & Kits Management -->
        <div x-data="{ open: {{ request()->routeIs('vendor.pujas*') || request()->routeIs('vendor.puja-kits*') ? 'true' : 'false' }} }">
            <button @click="open = !open"
                class="w-full group flex items-center justify-between px-4 py-3 text-sm font-medium text-gray-700 rounded-xl hover:bg-gray-50 transition-all duration-200 {{ request()->routeIs('vendor.pujas*') || request()->routeIs('vendor.puja-kits*') ? 'bg-indigo-50 text-indigo-700' : '' }}">
                <div class="flex items-center">
                    <i data-lucide="flame"
                        class="mr-3 h-5 w-5 {{ request()->routeIs('vendor.pujas*') || request()->routeIs('vendor.puja-kits*') ? 'text-indigo-600' : 'text-gray-400 group-hover:text-gray-600' }}"></i>
                    Pujas & Kits
                </div>
                <i data-lucide="chevron-down" class="h-4 w-4 transition-transform duration-200"
                    :class="open ? 'rotate-180' : ''"></i>
            </button>
            <div x-show="open" x-transition class="mt-2 pl-9 space-y-1">
                <a href="{{ route('vendor.pujas.index') }}"
                    class="block px-4 py-2 text-sm {{ request()->routeIs('vendor.pujas.index') ? 'text-indigo-600 bg-indigo-50' : 'text-gray-600' }} rounded-lg hover:bg-gray-50 transition-colors">All
                    Pujas</a>
                <a href="{{ route('vendor.pujas.create') }}"
                    class="block px-4 py-2 text-sm {{ request()->routeIs('vendor.pujas.create') ? 'text-indigo-600 bg-indigo-50' : 'text-gray-600' }} rounded-lg hover:bg-gray-50 transition-colors">Add
                    Puja</a>
                <a href="{{ route('vendor.puja-kits.index') }}"
                    class="block px-4 py-2 text-sm {{ request()->routeIs('vendor.puja-kits.index') ? 'text-indigo-600 bg-indigo-50' : 'text-gray-600' }} rounded-lg hover:bg-gray-50 transition-colors">Puja
                    Kits</a>
                <a href="{{ route('vendor.puja-kits.create') }}"
                    class="block px-4 py-2 text-sm {{ request()->routeIs('vendor.puja-kits.create') ? 'text-indigo-600 bg-indigo-50' : 'text-gray-600' }} rounded-lg hover:bg-gray-50 transition-colors">Create
                    Kit</a>
            </div>
        </div>

        <!-- Orders -->
        <a href="{{ route('vendor.orders.index') }}"
            class="group flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 {{ request()->routeIs('vendor.orders*') ? 'bg-orange-50 text-orange-700' : 'text-gray-700 hover:bg-gray-50' }}">
            <i data-lucide="shopping-cart"
                class="mr-3 h-5 w-5 {{ request()->routeIs('vendor.orders*') ? 'text-orange-600' : 'text-gray-400 group-hover:text-gray-600' }}"></i>
            Orders
        </a>

        <!-- Analytics -->
        <a href="{{ route('vendor.analytics.index') }}"
            class="group flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 {{ request()->routeIs('vendor.analytics*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
            <i data-lucide="bar-chart-3"
                class="mr-3 h-5 w-5 {{ request()->routeIs('vendor.analytics*') ? 'text-blue-600' : 'text-gray-400 group-hover:text-gray-600' }}"></i>
            Analytics
        </a>

        <!-- Settings -->
        <a href="{{ route('vendor.settings.index') }}"
            class="group flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 {{ request()->routeIs('vendor.settings*') ? 'bg-gray-100 text-gray-700' : 'text-gray-700 hover:bg-gray-50' }}">
            <i data-lucide="settings"
                class="mr-3 h-5 w-5 {{ request()->routeIs('vendor.settings*') ? 'text-gray-600' : 'text-gray-400 group-hover:text-gray-600' }}"></i>
            Settings
        </a>
    </nav>

    <!-- User Profile & Logout -->
    <div class="p-4 border-t border-gray-200">
        <div class="flex items-center space-x-3 mb-4">
            <div
                class="w-10 h-10 bg-gradient-to-r from-purple-500 to-pink-500 rounded-full flex items-center justify-center">
                <span class="text-white text-sm font-semibold">{{ substr(Auth::user()->first_name, 0, 1) }}</span>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-medium text-gray-900 truncate">{{ Auth::user()->first_name }}</p>
                <p class="text-xs text-gray-500">Shopkeeper</p>
            </div>
        </div>

        <form method="POST" action="{{ route('vendor.logout') }}">
            @csrf
            <button type="submit"
                class="w-full flex items-center justify-center px-4 py-2 text-sm font-medium text-red-600 bg-red-50 rounded-lg hover:bg-red-100 transition-colors duration-200">
                <i data-lucide="log-out" class="mr-2 h-4 w-4"></i>
                Logout
            </button>
        </form>
    </div>
</div>
