<!-- resources/views/partials/admin/sidebar.blade.php -->
<div class="flex flex-col h-full bg-white border-r border-gray-200">
    <!-- Logo -->
    <div class="flex items-center justify-center h-16 px-4 bg-gradient-to-r from-purple-600 to-pink-600">
        <!-- Replace the current div with this -->
        <div class="flex items-center space-x-3">
            <a href="{{ url('/') }}" class="flex items-center">
                <img src="{{ asset('images/logo.png') }}" alt="Ritiyana Logo" class="h-16 w-auto">
            </a>
            {{-- <h1 class="text-xl font-bold text-white">Ritiyana</h1> --}}
        </div>
    </div>

    <!-- Navigation -->
    <nav class="mt-8 flex-1 px-4 space-y-2 custom-scrollbar overflow-y-auto">
        <!-- Dashboard -->
        <a href="{{ route('admin.dashboard') }}"
            class="group flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 {{ request()->routeIs('admin.dashboard') ? 'bg-gradient-to-r from-purple-500 to-pink-500 text-white shadow-lg' : 'text-gray-700 hover:bg-gray-50' }}">
            <i data-lucide="layout-dashboard"
                class="mr-3 h-5 w-5 {{ request()->routeIs('admin.dashboard') ? 'text-white' : 'text-gray-400 group-hover:text-gray-600' }}"></i>
            Dashboard
        </a>

        <!-- Users Management -->
        <div x-data="{ open: {{ request()->routeIs('admin.users*') ? 'true' : 'false' }} }">
            <button @click="open = !open"
                class="w-full group flex items-center justify-between px-4 py-3 text-sm font-medium text-gray-700 rounded-xl hover:bg-gray-50 transition-all duration-200 {{ request()->routeIs('admin.users*') ? 'bg-purple-50 text-purple-700' : '' }}">
                <div class="flex items-center">
                    <i data-lucide="users"
                        class="mr-3 h-5 w-5 {{ request()->routeIs('admin.users*') ? 'text-purple-600' : 'text-gray-400 group-hover:text-gray-600' }}"></i>
                    Users
                </div>
                <i data-lucide="chevron-down" class="h-4 w-4 transition-transform duration-200"
                    :class="open ? 'rotate-180' : ''"></i>
            </button>
            <div x-show="open" x-transition class="mt-2 pl-9 space-y-1">
                <a href="{{ route('admin.users.index') }}"
                    class="block px-4 py-2 text-sm {{ request()->routeIs('admin.users.index') ? 'text-purple-600 bg-purple-50' : 'text-gray-600' }} rounded-lg hover:bg-gray-50 transition-colors">All
                    Users</a>
                <a href="{{ route('admin.users.create') }}"
                    class="block px-4 py-2 text-sm {{ request()->routeIs('admin.users.create') ? 'text-purple-600 bg-purple-50' : 'text-gray-600' }} rounded-lg hover:bg-gray-50 transition-colors">Add
                    User</a>
                <a href="{{ route('admin.roles.index') }}"
                    class="block px-4 py-2 text-sm {{ request()->routeIs('admin.roles*') ? 'text-purple-600 bg-purple-50' : 'text-gray-600' }} rounded-lg hover:bg-gray-50 transition-colors">Roles</a>
            </div>
        </div>

        <!-- Products -->
        <div x-data="{ open: {{ request()->routeIs('admin.products*') || request()->routeIs('admin.categories*') ? 'true' : 'false' }} }">
            <button @click="open = !open"
                class="w-full group flex items-center justify-between px-4 py-3 text-sm font-medium text-gray-700 rounded-xl hover:bg-gray-50 transition-all duration-200 {{ request()->routeIs('admin.products*') || request()->routeIs('admin.categories*') ? 'bg-green-50 text-green-700' : '' }}">
                <div class="flex items-center">
                    <i data-lucide="package"
                        class="mr-3 h-5 w-5 {{ request()->routeIs('admin.products*') || request()->routeIs('admin.categories*') ? 'text-green-600' : 'text-gray-400 group-hover:text-gray-600' }}"></i>
                    Products
                </div>
                <i data-lucide="chevron-down" class="h-4 w-4 transition-transform duration-200"
                    :class="open ? 'rotate-180' : ''"></i>
            </button>
            <div x-show="open" x-transition class="mt-2 pl-9 space-y-1">
                <a href="{{ route('admin.products.index') }}"
                    class="block px-4 py-2 text-sm {{ request()->routeIs('admin.products.index') ? 'text-green-600 bg-green-50' : 'text-gray-600' }} rounded-lg hover:bg-gray-50 transition-colors">All
                    Products</a>
                <a href="{{ route('admin.products.create') }}"
                    class="block px-4 py-2 text-sm {{ request()->routeIs('admin.products.create') ? 'text-green-600 bg-green-50' : 'text-gray-600' }} rounded-lg hover:bg-gray-50 transition-colors">Add
                    Product</a>
                <a href="{{ route('admin.categories.index') }}"
                    class="block px-4 py-2 text-sm {{ request()->routeIs('admin.categories*') ? 'text-green-600 bg-green-50' : 'text-gray-600' }} rounded-lg hover:bg-gray-50 transition-colors">Categories</a>
                <a href="{{ route('admin.inventory.index') }}"
                    class="block px-4 py-2 text-sm text-gray-600 rounded-lg hover:bg-gray-50 transition-colors">Inventory</a>
            </div>
        </div>

        <!-- Orders -->
        <a href="{{ route('admin.orders.index') }}"
            class="group flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 {{ request()->routeIs('admin.orders*') ? 'bg-orange-50 text-orange-700' : 'text-gray-700 hover:bg-gray-50' }}">
            <i data-lucide="shopping-cart"
                class="mr-3 h-5 w-5 {{ request()->routeIs('admin.orders*') ? 'text-orange-600' : 'text-gray-400 group-hover:text-gray-600' }}"></i>
            Orders
        </a>

        <!-- Pujas & Kits Management -->
        <div x-data="{ open: {{ request()->routeIs('admin.pujas*') || request()->routeIs('admin.puja-kits*') ? 'true' : 'false' }} }">
            <button @click="open = !open"
                class="w-full group flex items-center justify-between px-4 py-3 text-sm font-medium text-gray-700 rounded-xl hover:bg-gray-50 transition-all duration-200 {{ request()->routeIs('admin.pujas*') || request()->routeIs('admin.puja-kits*') ? 'bg-indigo-50 text-indigo-700' : '' }}">
                <div class="flex items-center">
                    <i data-lucide="flame"
                        class="mr-3 h-5 w-5 {{ request()->routeIs('admin.pujas*') || request()->routeIs('admin.puja-kits*') ? 'text-indigo-600' : 'text-gray-400 group-hover:text-gray-600' }}"></i>
                    Pujas & Kits
                </div>
                <i data-lucide="chevron-down" class="h-4 w-4 transition-transform duration-200"
                    :class="open ? 'rotate-180' : ''"></i>
            </button>
            <div x-show="open" x-transition class="mt-2 pl-9 space-y-1">
                <a href="{{ route('admin.pujas.index') }}"
                    class="block px-4 py-2 text-sm {{ request()->routeIs('admin.pujas.index') ? 'text-indigo-600 bg-indigo-50' : 'text-gray-600' }} rounded-lg hover:bg-gray-50 transition-colors">All
                    Pujas</a>
                <a href="{{ route('admin.pujas.create') }}"
                    class="block px-4 py-2 text-sm {{ request()->routeIs('admin.pujas.create') ? 'text-indigo-600 bg-indigo-50' : 'text-gray-600' }} rounded-lg hover:bg-gray-50 transition-colors">Add
                    Puja</a>
                <a href="{{ route('admin.puja-kits.index') }}"
                    class="block px-4 py-2 text-sm {{ request()->routeIs('admin.puja-kits.index') ? 'text-indigo-600 bg-indigo-50' : 'text-gray-600' }} rounded-lg hover:bg-gray-50 transition-colors">Puja
                    Kits</a>
                <a href="{{ route('admin.puja-kits.create') }}"
                    class="block px-4 py-2 text-sm {{ request()->routeIs('admin.puja-kits.create') ? 'text-indigo-600 bg-indigo-50' : 'text-gray-600' }} rounded-lg hover:bg-gray-50 transition-colors">Create
                    Kit</a>
            </div>
        </div>

        <!-- Banners Management -->
        <div x-data="{ open: {{ request()->routeIs('admin.banners*') ? 'true' : 'false' }} }">
            <button @click="open = !open"
                class="w-full group flex items-center justify-between px-4 py-3 text-sm font-medium text-gray-700 rounded-xl hover:bg-gray-50 transition-all duration-200 {{ request()->routeIs('admin.banners*') ? 'bg-indigo-50 text-indigo-700' : '' }}">
                <div class="flex items-center">
                    <i data-lucide="image"
                        class="mr-3 h-5 w-5 {{ request()->routeIs('admin.banners*') ? 'text-indigo-600' : 'text-gray-400 group-hover:text-gray-600' }}"></i>
                    Banners
                </div>
                <i data-lucide="chevron-down" class="h-4 w-4 transition-transform duration-200"
                    :class="open ? 'rotate-180' : ''"></i>
            </button>
            <div x-show="open" x-transition class="mt-2 pl-9 space-y-1">
                <a href="{{ route('admin.banners.index') }}"
                    class="block px-4 py-2 text-sm {{ request()->routeIs('admin.banners.index') ? 'text-indigo-600 bg-indigo-50' : 'text-gray-600' }} rounded-lg hover:bg-gray-50 transition-colors">All
                    Banners</a>
                <a href="{{ route('admin.banners.create') }}"
                    class="block px-4 py-2 text-sm {{ request()->routeIs('admin.banners.create') ? 'text-indigo-600 bg-indigo-50' : 'text-gray-600' }} rounded-lg hover:bg-gray-50 transition-colors">Add
                    Banner</a>
                {{-- <a href="{{ route('admin.top-banners.index') }}"
                    class="block px-4 py-2 text-sm {{ request()->routeIs('admin.top-banners.index') ? 'text-indigo-600 bg-indigo-50' : 'text-gray-600' }} rounded-lg hover:bg-gray-50 transition-colors">Top
                    Banners</a>
                <a href="{{ route('admin.top-banners.create') }}"
                    class="block px-4 py-2 text-sm {{ request()->routeIs('admin.top-banners.create') ? 'text-indigo-600 bg-indigo-50' : 'text-gray-600' }} rounded-lg hover:bg-gray-50 transition-colors">Create
                    Top Banner</a> --}}
            </div>
        </div>


        <!-- Analytics -->
        <a href="{{ route('admin.analytics.index') }}"
            class="group flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 {{ request()->routeIs('admin.analytics*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
            <i data-lucide="bar-chart-3"
                class="mr-3 h-5 w-5 {{ request()->routeIs('admin.analytics*') ? 'text-blue-600' : 'text-gray-400 group-hover:text-gray-600' }}"></i>
            Analytics
        </a>
        <a href="{{ route('admin.contacts.index') }}"
            class="group flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 {{ request()->routeIs('admin.contacts*') ? 'bg-blue-50 text-blue-700' : 'text-gray-700 hover:bg-gray-50' }}">
            <i data-lucide="contact"
                class="mr-3 h-5 w-5 {{ request()->routeIs('admin.contacts*') ? 'text-blue-600' : 'text-gray-400 group-hover:text-gray-600' }}"></i>
            Contact
        </a>

        <!-- Settings -->
        <a href="{{ route('admin.settings.index') }}"
            class="group flex items-center px-4 py-3 text-sm font-medium rounded-xl transition-all duration-200 {{ request()->routeIs('admin.settings*') ? 'bg-gray-100 text-gray-700' : 'text-gray-700 hover:bg-gray-50' }}">
            <i data-lucide="settings"
                class="mr-3 h-5 w-5 {{ request()->routeIs('admin.settings*') ? 'text-gray-600' : 'text-gray-400 group-hover:text-gray-600' }}"></i>
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
                <p class="text-xs text-gray-500">Administrator</p>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.logout') }}">
            @csrf
            <button type="submit"
                class="w-full flex items-center justify-center px-4 py-2 text-sm font-medium text-red-600 bg-red-50 rounded-lg hover:bg-red-100 transition-colors duration-200">
                <i data-lucide="log-out" class="mr-2 h-4 w-4"></i>
                Logout
            </button>
        </form>
    </div>
</div>
