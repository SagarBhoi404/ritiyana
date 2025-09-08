<!-- resources/views/layouts/admin.blade.php -->
<!DOCTYPE html>
<html lang="en" class="h-full bg-gray-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Dashboard') - Ritiyana</title>
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Alpine.js CDN -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- ApexCharts CDN -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts@3.45.2/dist/apexcharts.min.js"></script>
    
    <!-- Lucide Icons CDN -->
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
    
    <style>
        /* Custom scrollbar */
        .custom-scrollbar::-webkit-scrollbar {
            width: 6px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: #f1f5f9;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 3px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
        
        /* Prevent chart rerender loops */
        .apexcharts-canvas {
            position: relative !important;
        }
        
        /* Glass effect */
        .glass-effect {
            backdrop-filter: blur(16px) saturate(180%);
            background-color: rgba(255, 255, 255, 0.95);
            border: 1px solid rgba(209, 213, 219, 0.3);
        }
    </style>
</head>
<body class="h-full">
    <div x-data="{ sidebarOpen: false }" class="h-full">
        <!-- Sidebar -->
        <div x-show="sidebarOpen" 
             x-transition:enter="transition-opacity ease-linear duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-linear duration-300"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 flex z-40 lg:hidden">
            
            <div @click="sidebarOpen = false" class="fixed inset-0 bg-gray-900 bg-opacity-50"></div>
            
            <div x-show="sidebarOpen"
                 x-transition:enter="transition ease-in-out duration-300 transform"
                 x-transition:enter-start="-translate-x-full"
                 x-transition:enter-end="translate-x-0"
                 x-transition:leave="transition ease-in-out duration-300 transform"
                 x-transition:leave-start="translate-x-0"
                 x-transition:leave-end="-translate-x-full"
                 class="relative flex-1 flex flex-col max-w-xs w-full bg-white">
                @include('partials.admin.sidebar')
            </div>
        </div>

        <!-- Static sidebar for desktop -->
        <div class="hidden lg:flex lg:w-64 lg:flex-col lg:fixed lg:inset-y-0">
            @include('partials.admin.sidebar')
        </div>

        <!-- Main content -->
        <div class="lg:pl-64 flex flex-col flex-1 h-full">
            <!-- Top navigation -->
            <div class="sticky top-0 z-10 flex-shrink-0 flex h-16 bg-white border-b border-gray-200 glass-effect">
                @include('partials.admin.header')
            </div>

            <!-- Page content -->
            <main class="flex-1 overflow-y-auto custom-scrollbar focus:outline-none">
                <!-- Success/Error Messages -->
                @if(session('success'))
                    <div x-data="{ show: true }" 
                         x-show="show" 
                         x-transition
                         x-init="setTimeout(() => show = false, 5000)"
                         class="fixed top-4 right-4 z-50 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg">
                        <div class="flex items-center">
                            <i data-lucide="check-circle" class="w-4 h-4 mr-2"></i>
                            {{ session('success') }}
                        </div>
                    </div>
                @endif
                
                @if(session('error'))
                    <div x-data="{ show: true }" 
                         x-show="show" 
                         x-transition
                         x-init="setTimeout(() => show = false, 5000)"
                         class="fixed top-4 right-4 z-50 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg">
                        <div class="flex items-center">
                            <i data-lucide="x-circle" class="w-4 h-4 mr-2"></i>
                            {{ session('error') }}
                        </div>
                    </div>
                @endif

                @yield('content')
            </main>
        </div>
    </div>

    <script>
        // Chart instance tracker to prevent loops
        let chartInstances = {};

        // Initialize Lucide icons
        function initIcons() {
            if (typeof lucide !== 'undefined') {
                try {
                    lucide.createIcons();
                } catch (error) {
                    console.error('Error initializing Lucide icons:', error);
                }
            }
        }

        // Initialize clock
        function initClock() {
            function updateTime() {
                const now = new Date();
                const timeString = now.toLocaleTimeString([], { 
                    hour: '2-digit', 
                    minute: '2-digit',
                    hour12: true 
                });
                const clockElement = document.getElementById('currentTime');
                if (clockElement) {
                    clockElement.textContent = timeString;
                }
            }
            
            updateTime();
            setInterval(updateTime, 60000);
        }

        // Initialize charts with loop prevention
        function initCharts() {
            const salesChartElement = document.getElementById('salesChart');
            if (!salesChartElement || chartInstances.salesChart) return;

            const options = {
                chart: {
                    type: 'area',
                    height: 300,
                    fontFamily: 'Inter, ui-sans-serif, system-ui',
                    toolbar: { show: false },
                    animations: {
                        enabled: true,
                        easing: 'easeinout',
                        speed: 800
                    }
                },
                series: [{
                    name: 'Sales (₹)',
                    data: [31000, 40000, 35000, 50000, 49000, 60000, 70000]
                }],
                xaxis: {
                    categories: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                    axisBorder: { show: false },
                    axisTicks: { show: false }
                },
                yaxis: {
                    show: true,
                    labels: {
                        formatter: function(val) {
                            return '₹' + (val/1000) + 'K';
                        }
                    }
                },
                tooltip: {
                    y: {
                        formatter: function(val) {
                            return '₹' + val.toLocaleString();
                        }
                    }
                },
                fill: {
                    type: 'gradient',
                    gradient: {
                        shadeIntensity: 1,
                        opacityFrom: 0.7,
                        opacityTo: 0.9,
                        stops: [0, 90, 100]
                    }
                },
                colors: ['#8B5CF6'],
                grid: {
                    borderColor: '#f1f5f9',
                    strokeDashArray: 4,
                },
                stroke: {
                    curve: 'smooth',
                    width: 3
                }
            };

            try {
                chartInstances.salesChart = new ApexCharts(salesChartElement, options);
                chartInstances.salesChart.render();
            } catch (error) {
                console.error('Chart initialization failed:', error);
            }
        }

        // Main initialization function
        function initialize() {
            initIcons();
            initClock();
            setTimeout(initCharts, 100);
        }

        // Initialize when DOM and all scripts are ready
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', function() {
                setTimeout(initialize, 100);
            });
        } else {
            setTimeout(initialize, 100);
        }

        // Re-initialize icons when content changes
        document.addEventListener('DOMNodeInserted', function() {
            setTimeout(initIcons, 50);
        });
    </script>
</body>
</html>
