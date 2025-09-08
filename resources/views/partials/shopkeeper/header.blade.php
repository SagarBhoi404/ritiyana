<!-- resources/views/partials/shopkeeper/header.blade.php -->
<div class="flex justify-between items-center px-4">
    <button @click="sidebarOpen = true" class="px-4 border-r border-gray-200 text-gray-500 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-purple-500 lg:hidden">
        <i data-lucide="menu" class="h-6 w-6"></i>
    </button>
    
    <div class="flex-1 px-4 flex justify-between items-center">
        <!-- Left: Breadcrumbs -->
        <div class="flex items-center space-x-4">
            <nav class="flex" aria-label="Breadcrumb">
                <ol class="flex items-center space-x-2">
                    <li>
                        <div>
                            <a href="{{ route('shopkeeper.dashboard') }}" class="text-gray-400 hover:text-gray-500">
                                <i data-lucide="home" class="h-4 w-4"></i>
                            </a>
                        </div>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <i data-lucide="chevron-right" class="h-4 w-4 text-gray-400"></i>
                            <span class="ml-2 text-sm font-medium text-gray-500">@yield('breadcrumb', 'Dashboard')</span>
                        </div>
                    </li>
                </ol>
            </nav>
        </div>

        <!-- Right: Notifications + Profile -->
        <div class="ml-4 flex items-center space-x-4">
            <!-- Notifications -->
            <div class="relative">
                <button class="bg-white p-2 rounded-lg text-gray-400 hover:text-gray-500 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500">
                    <i data-lucide="bell" class="h-5 w-5"></i>
                    <span class="absolute -top-1 -right-1 h-4 w-4 bg-red-500 rounded-full flex items-center justify-center">
                        <span class="text-xs text-white font-medium">2</span>
                    </span>
                </button>
            </div>

            <!-- Current time -->
            <div class="hidden sm:block text-sm text-gray-500" id="currentTime"></div>
        </div>
    </div>
</div>
