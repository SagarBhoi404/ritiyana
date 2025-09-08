<!-- resources/views/layouts/shopkeeper.blade.php -->
<!DOCTYPE html>
<html lang="en" class="h-full bg-gray-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Shopkeeper Dashboard') - Ritiyana</title>
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Alpine.js CDN -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- ApexCharts CDN -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts@3.45.2/dist/apexcharts.min.js"></script>
    
    <!-- Lucide Icons CDN -->
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
</head>
<body class="h-full">
    <div x-data="{ sidebarOpen: false }" class="h-full">
        <!-- Sidebar -->
        <div x-show="sidebarOpen" class="fixed inset-0 flex z-40 lg:hidden" x-transition>
            <div @click="sidebarOpen = false" class="fixed inset-0 bg-gray-600 bg-opacity-75"></div>
            <div class="relative flex-1 flex flex-col max-w-xs w-full bg-white">
                @include('partials.shopkeeper.sidebar')
            </div>
        </div>

        <!-- Static sidebar for desktop -->
        <div class="hidden lg:flex lg:w-64 lg:flex-col lg:fixed lg:inset-y-0">
            @include('partials.shopkeeper.sidebar')
        </div>

        <!-- Main content -->
        <div class="lg:pl-64 flex flex-col flex-1">
            <!-- Top navigation -->
            <div class="sticky top-0 z-10 flex-shrink-0 flex h-16 bg-white border-b border-gray-200">
                @include('partials.shopkeeper.header')
            </div>

            <!-- Page content -->
            <main class="flex-1 relative overflow-y-auto focus:outline-none">
                @yield('content')
            </main>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        });
    </script>
</body>
</html>
