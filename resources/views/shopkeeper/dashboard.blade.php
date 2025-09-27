<!-- resources/views/vendor/dashboard.blade.php -->
@extends('layouts.shopkeeper')

@section('title', 'Dashboard')
@section('breadcrumb', 'Dashboard')

@section('content')
<div class="px-4 sm:px-6 lg:px-8 py-8">
    <!-- Welcome Section -->
    <div class="bg-gradient-to-br from-purple-600 via-purple-700 to-pink-600 rounded-2xl p-8 mb-8 text-white relative overflow-hidden">
        <div class="relative">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h1 class="text-3xl font-bold mb-2">Welcome back, {{ Auth::user()->first_name }}! 🛍️</h1>
                    <p class="text-purple-100 text-lg">Manage your store and products with ease</p>
                    <div class="flex items-center mt-4 text-purple-100">
                        <i data-lucide="calendar" class="h-4 w-4 mr-2"></i>
                        <span>{{ now()->format('l, F j, Y') }}</span>
                    </div>
                </div>
                <div class="mt-6 sm:mt-0">
                    <div class="flex items-center space-x-4">
                        <div class="text-center">
                            <div class="text-2xl font-bold" id="currentHour">{{ now()->format('H') }}</div>
                            <div class="text-sm text-purple-200">Hours</div>
                        </div>
                        <div class="text-purple-300">:</div>
                        <div class="text-center">
                            <div class="text-2xl font-bold" id="currentMinute">{{ now()->format('i') }}</div>
                            <div class="text-sm text-purple-200">Minutes</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Sales -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-lg transition-all duration-300 group">
            <div class="flex items-center">
                <div class="flex-1">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                            <i data-lucide="indian-rupee" class="h-6 w-6 text-green-600"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Your Sales</p>
                            <p class="text-2xl font-bold text-gray-900">₹{{ number_format($stats['totalSales'], 0) }}</p>
                        </div>
                    </div>
                    <div class="mt-4 flex items-center">
                        <div class="flex items-center {{ $growthPercentages['sales'] >= 0 ? 'text-green-600' : 'text-red-600' }} text-sm font-medium">
                            <i data-lucide="{{ $growthPercentages['sales'] >= 0 ? 'trending-up' : 'trending-down' }}" class="h-4 w-4 mr-1"></i>
                            {{ $growthPercentages['sales'] >= 0 ? '+' : '' }}{{ $growthPercentages['sales'] }}%
                        </div>
                        <span class="text-gray-500 text-sm ml-2">vs last month</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Orders -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-lg transition-all duration-300 group">
            <div class="flex items-center">
                <div class="flex-1">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                            <i data-lucide="shopping-cart" class="h-6 w-6 text-blue-600"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Orders</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $stats['totalOrders'] }}</p>
                        </div>
                    </div>
                    <div class="mt-4 flex items-center">
                        <div class="flex items-center {{ $growthPercentages['orders'] >= 0 ? 'text-green-600' : 'text-red-600' }} text-sm font-medium">
                            <i data-lucide="{{ $growthPercentages['orders'] >= 0 ? 'trending-up' : 'trending-down' }}" class="h-4 w-4 mr-1"></i>
                            {{ $growthPercentages['orders'] >= 0 ? '+' : '' }}{{ $growthPercentages['orders'] }}%
                        </div>
                        <span class="text-gray-500 text-sm ml-2">vs last month</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Products -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-lg transition-all duration-300 group">
            <div class="flex items-center">
                <div class="flex-1">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                            <i data-lucide="package" class="h-6 w-6 text-purple-600"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Products</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $stats['totalProducts'] }}</p>
                        </div>
                    </div>
                    <div class="mt-4 flex items-center">
                        <div class="flex items-center {{ $growthPercentages['products'] >= 0 ? 'text-green-600' : 'text-red-600' }} text-sm font-medium">
                            <i data-lucide="{{ $growthPercentages['products'] >= 0 ? 'trending-up' : 'trending-down' }}" class="h-4 w-4 mr-1"></i>
                            {{ $growthPercentages['products'] >= 0 ? '+' : '' }}{{ $growthPercentages['products'] }}%
                        </div>
                        <span class="text-gray-500 text-sm ml-2">vs last month</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Customers -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 hover:shadow-lg transition-all duration-300 group">
            <div class="flex items-center">
                <div class="flex-1">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-orange-100 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                            <i data-lucide="users" class="h-6 w-6 text-orange-600"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm font-medium text-gray-600">Customers</p>
                            <p class="text-2xl font-bold text-gray-900">{{ $stats['totalCustomers'] }}</p>
                        </div>
                    </div>
                    <div class="mt-4 flex items-center">
                        <div class="flex items-center {{ $growthPercentages['customers'] >= 0 ? 'text-green-600' : 'text-red-600' }} text-sm font-medium">
                            <i data-lucide="{{ $growthPercentages['customers'] >= 0 ? 'trending-up' : 'trending-down' }}" class="h-4 w-4 mr-1"></i>
                            {{ $growthPercentages['customers'] >= 0 ? '+' : '' }}{{ $growthPercentages['customers'] }}%
                        </div>
                        <span class="text-gray-500 text-sm ml-2">vs last month</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts and Recent Orders -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <!-- Sales Chart -->
        <div class="lg:col-span-2 bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Sales Analytics</h3>
                    <p class="text-sm text-gray-500">Your weekly sales overview</p>
                </div>
                <select id="chartPeriod" class="text-sm border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                    <option value="week">Last 7 days</option>
                    <option value="month">Last 30 days</option>
                    <option value="quarter">Last 3 months</option>
                </select>
            </div>
            <div id="vendorSalesChart" class="w-full"></div>
        </div>

        <!-- Recent Orders -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
            <h3 class="text-lg font-semibold text-gray-900 mb-6">Recent Orders</h3>
            <div class="space-y-4">
                @forelse($recentOrders as $order)
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors cursor-pointer">
                    <div class="flex items-center space-x-4">
                        <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                            <span class="text-blue-600 font-semibold text-sm">#{{ $order->id }}</span>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900">{{ $order->product->name ?? 'Product' }}</p>
                            <p class="text-sm text-gray-500 flex items-center">
                                <i data-lucide="user" class="h-3 w-3 mr-1"></i>
                                {{ $order->customer->first_name ?? 'Customer' }} {{ $order->customer->last_name ?? '' }}
                            </p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="font-semibold text-gray-900">₹{{ number_format($order->vendor_earning, 0) }}</p>
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                            @switch($order->status)
                                @case('delivered')
                                    bg-green-100 text-green-800
                                    @break
                                @case('processing')
                                    bg-yellow-100 text-yellow-800
                                    @break
                                @case('shipped')
                                    bg-blue-100 text-blue-800
                                    @break
                                @default
                                    bg-gray-100 text-gray-800
                            @endswitch
                        ">
                            {{ ucfirst($order->status) }}
                        </span>
                    </div>
                </div>
                @empty
                <div class="text-center py-8">
                    <p class="text-gray-500">No recent orders found</p>
                </div>
                @endforelse
            </div>

            @if($recentOrders->count() > 0)
            <div class="mt-6">
                <a href="{{ route('vendor.orders') }}" class="w-full flex items-center justify-center px-4 py-2 text-sm font-medium text-purple-600 bg-purple-50 rounded-lg hover:bg-purple-100 transition-colors">
                    <i data-lucide="arrow-right" class="w-4 h-4 mr-2"></i>
                    View All Orders
                </a>
            </div>
            @endif
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
        <h3 class="text-lg font-semibold text-gray-900 mb-6">Quick Actions</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <a href="{{ route('vendor.products.create') }}" class="flex items-center p-4 rounded-xl hover:bg-green-50 transition-colors group">
                <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center mr-4 group-hover:scale-110 transition-transform">
                    <i data-lucide="plus" class="h-5 w-5 text-green-600"></i>
                </div>
                <div>
                    <p class="font-medium text-gray-900">Add Product</p>
                    <p class="text-sm text-gray-500">Create new listing</p>
                </div>
            </a>
            
            <a href="{{ route('vendor.orders.index') }}" class="flex items-center p-4 rounded-xl hover:bg-blue-50 transition-colors group">
                <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center mr-4 group-hover:scale-110 transition-transform">
                    <i data-lucide="shopping-cart" class="h-5 w-5 text-blue-600"></i>
                </div>
                <div>
                    <p class="font-medium text-gray-900">View Orders</p>
                    <p class="text-sm text-gray-500">Manage orders</p>
                </div>
            </a>
            
            <a href="{{ route('vendor.products.index') }}" class="flex items-center p-4 rounded-xl hover:bg-purple-50 transition-colors group">
                <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center mr-4 group-hover:scale-110 transition-transform">
                    <i data-lucide="package" class="h-5 w-5 text-purple-600"></i>
                </div>
                <div>
                    <p class="font-medium text-gray-900">Inventory</p>
                    <p class="text-sm text-gray-500">Check stock levels</p>
                </div>
            </a>
            
            <a href="{{ route('vendor.analytics.index') }}" class="flex items-center p-4 rounded-xl hover:bg-orange-50 transition-colors group">
                <div class="w-12 h-12 bg-orange-100 rounded-xl flex items-center justify-center mr-4 group-hover:scale-110 transition-transform">
                    <i data-lucide="bar-chart-3" class="h-5 w-5 text-orange-600"></i>
                </div>
                <div>
                    <p class="font-medium text-gray-900">Analytics</p>
                    <p class="text-sm text-gray-500">View reports</p>
                </div>
            </a>
        </div>
    </div>
</div>
{{-- 
<script>
document.addEventListener('DOMContentLoaded', function() {
    let salesChart;
    const chartElement = document.getElementById('vendorSalesChart');
    const periodSelect = document.getElementById('chartPeriod');
    
    // Initial chart data from Laravel
    const initialChartData = @json($chartData);
    
    // Initialize chart
    function initializeChart(chartData) {
        if (chartElement && typeof ApexCharts !== 'undefined') {
            const options = {
                chart: {
                    type: 'area',
                    height: 300,
                    fontFamily: 'Inter, ui-sans-serif, system-ui',
                    toolbar: { show: false }
                },
                series: [{
                    name: 'Sales (₹)',
                    data: chartData.data
                }],
                xaxis: {
                    categories: chartData.categories
                },
                yaxis: {
                    labels: {
                        formatter: function(val) {
                            return '₹' + (val >= 1000 ? (val/1000).toFixed(1) + 'K' : val);
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
                colors: ['#8B5CF6'],
                fill: {
                    type: 'gradient',
                    gradient: {
                        shadeIntensity: 1,
                        opacityFrom: 0.7,
                        opacityTo: 0.9,
                        stops: [0, 90, 100]
                    }
                },
                grid: {
                    borderColor: '#f1f5f9',
                    strokeDashArray: 4,
                },
                stroke: {
                    curve: 'smooth',
                    width: 3
                }
            };

            if (salesChart) {
                salesChart.destroy();
            }
            
            salesChart = new ApexCharts(chartElement, options);
            salesChart.render();
        }
    }
    
    // Initialize with default data
    initializeChart(initialChartData);
    
    // Handle period change
    periodSelect.addEventListener('change', function() {
        const selectedPeriod = this.value;
        
        // Show loading state
        chartElement.innerHTML = '<div class="flex items-center justify-center h-64"><div class="animate-spin rounded-full h-8 w-8 border-b-2 border-purple-600"></div></div>';
        
        // Fetch new data
        fetch(`{{ route('vendor.dashboard.chart-data') }}?period=${selectedPeriod}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => response.json())
        .then(data => {
            initializeChart(data);
        })
        .catch(error => {
            console.error('Error fetching chart data:', error);
            chartElement.innerHTML = '<div class="flex items-center justify-center h-64 text-gray-500">Error loading chart data</div>';
        });
    });

    // Update live time every minute
    function updateTime() {
        const now = new Date();
        document.getElementById('currentHour').textContent = now.getHours().toString().padStart(2, '0');
        document.getElementById('currentMinute').textContent = now.getMinutes().toString().padStart(2, '0');
    }
    
    setInterval(updateTime, 60000); // Update every minute
});
</script> --}}
@endsection
