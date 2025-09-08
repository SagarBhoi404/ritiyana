<!-- resources/views/shopkeeper/dashboard.blade.php -->
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
                            <p class="text-2xl font-bold text-gray-900">₹1,25,000</p>
                        </div>
                    </div>
                    <div class="mt-4 flex items-center">
                        <div class="flex items-center text-green-600 text-sm font-medium">
                            <i data-lucide="trending-up" class="h-4 w-4 mr-1"></i>
                            +12%
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
                            <p class="text-2xl font-bold text-gray-900">450</p>
                        </div>
                    </div>
                    <div class="mt-4 flex items-center">
                        <div class="flex items-center text-green-600 text-sm font-medium">
                            <i data-lucide="trending-up" class="h-4 w-4 mr-1"></i>
                            +8%
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
                            <p class="text-2xl font-bold text-gray-900">150</p>
                        </div>
                    </div>
                    <div class="mt-4 flex items-center">
                        <div class="flex items-center text-green-600 text-sm font-medium">
                            <i data-lucide="trending-up" class="h-4 w-4 mr-1"></i>
                            +5%
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
                            <p class="text-2xl font-bold text-gray-900">1,245</p>
                        </div>
                    </div>
                    <div class="mt-4 flex items-center">
                        <div class="flex items-center text-green-600 text-sm font-medium">
                            <i data-lucide="trending-up" class="h-4 w-4 mr-1"></i>
                            +18%
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
                <select class="text-sm border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                    <option>Last 7 days</option>
                    <option>Last 30 days</option>
                    <option>Last 3 months</option>
                </select>
            </div>
            <div id="shopkeeperSalesChart" class="w-full"></div>
        </div>

        <!-- Recent Orders -->
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
            <h3 class="text-lg font-semibold text-gray-900 mb-6">Recent Orders</h3>
            <div class="space-y-4">
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors cursor-pointer">
                    <div class="flex items-center space-x-4">
                        <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                            <span class="text-blue-600 font-semibold text-sm">#1847</span>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900">Ganesh Puja Kit</p>
                            <p class="text-sm text-gray-500 flex items-center">
                                <i data-lucide="user" class="h-3 w-3 mr-1"></i>
                                Rajesh Kumar
                            </p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="font-semibold text-gray-900">₹1,250</p>
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                            Completed
                        </span>
                    </div>
                </div>
                
                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors cursor-pointer">
                    <div class="flex items-center space-x-4">
                        <div class="w-12 h-12 bg-yellow-100 rounded-xl flex items-center justify-center">
                            <span class="text-yellow-600 font-semibold text-sm">#1848</span>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900">Diwali Special Kit</p>
                            <p class="text-sm text-gray-500 flex items-center">
                                <i data-lucide="user" class="h-3 w-3 mr-1"></i>
                                Priya Sharma
                            </p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="font-semibold text-gray-900">₹2,100</p>
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                            Processing
                        </span>
                    </div>
                </div>

                <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors cursor-pointer">
                    <div class="flex items-center space-x-4">
                        <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                            <span class="text-green-600 font-semibold text-sm">#1849</span>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900">Navratri Puja Set</p>
                            <p class="text-sm text-gray-500 flex items-center">
                                <i data-lucide="user" class="h-3 w-3 mr-1"></i>
                                Amit Kumar
                            </p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="font-semibold text-gray-900">₹850</p>
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                            Shipped
                        </span>
                    </div>
                </div>
            </div>

            <div class="mt-6">
                <a href="#" class="w-full flex items-center justify-center px-4 py-2 text-sm font-medium text-purple-600 bg-purple-50 rounded-lg hover:bg-purple-100 transition-colors">
                    <i data-lucide="arrow-right" class="w-4 h-4 mr-2"></i>
                    View All Orders
                </a>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100">
        <h3 class="text-lg font-semibold text-gray-900 mb-6">Quick Actions</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <a href="#" class="flex items-center p-4 rounded-xl hover:bg-green-50 transition-colors group">
                <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center mr-4 group-hover:scale-110 transition-transform">
                    <i data-lucide="plus" class="h-5 w-5 text-green-600"></i>
                </div>
                <div>
                    <p class="font-medium text-gray-900">Add Product</p>
                    <p class="text-sm text-gray-500">Create new listing</p>
                </div>
            </a>
            
            <a href="#" class="flex items-center p-4 rounded-xl hover:bg-blue-50 transition-colors group">
                <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center mr-4 group-hover:scale-110 transition-transform">
                    <i data-lucide="shopping-cart" class="h-5 w-5 text-blue-600"></i>
                </div>
                <div>
                    <p class="font-medium text-gray-900">View Orders</p>
                    <p class="text-sm text-gray-500">Manage orders</p>
                </div>
            </a>
            
            <a href="#" class="flex items-center p-4 rounded-xl hover:bg-purple-50 transition-colors group">
                <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center mr-4 group-hover:scale-110 transition-transform">
                    <i data-lucide="package" class="h-5 w-5 text-purple-600"></i>
                </div>
                <div>
                    <p class="font-medium text-gray-900">Inventory</p>
                    <p class="text-sm text-gray-500">Check stock levels</p>
                </div>
            </a>
            
            <a href="#" class="flex items-center p-4 rounded-xl hover:bg-orange-50 transition-colors group">
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Sales Chart for Shopkeeper
    const salesChartElement = document.getElementById('shopkeeperSalesChart');
    if (salesChartElement && typeof ApexCharts !== 'undefined') {
        const options = {
            chart: {
                type: 'area',
                height: 300,
                fontFamily: 'Inter, ui-sans-serif, system-ui',
                toolbar: { show: false }
            },
            series: [{
                name: 'Sales (₹)',
                data: [15000, 20000, 18000, 25000, 22000, 30000, 28000]
            }],
            xaxis: {
                categories: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun']
            },
            yaxis: {
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

        const chart = new ApexCharts(salesChartElement, options);
        chart.render();
    }
});
</script>
@endsection
