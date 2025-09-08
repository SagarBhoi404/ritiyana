<!-- resources/views/shopkeeper/analytics/index.blade.php -->
@extends('layouts.shopkeeper')

@section('title', 'Analytics')
@section('breadcrumb', 'Analytics')

@section('content')
<div class="px-4 sm:px-6 lg:px-8 py-8">
    <!-- Page Header -->
    <div class="mb-8">
        <div class="sm:flex sm:items-center sm:justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Analytics Dashboard</h1>
                <p class="mt-2 text-sm text-gray-700">Monitor your store performance and sales insights</p>
            </div>
            <div class="mt-4 sm:mt-0">
                <select class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-purple-500 focus:border-purple-500">
                    <option>Last 7 days</option>
                    <option>Last 30 days</option>
                    <option>Last 3 months</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Analytics Stats -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Revenue -->
        <div class="bg-white rounded-xl p-6 border border-gray-200 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Your Revenue</p>
                    <p class="text-3xl font-bold text-gray-900 mt-1">₹1,25,430</p>
                    <div class="flex items-center mt-2">
                        <div class="flex items-center text-green-600 text-sm font-medium">
                            <i data-lucide="trending-up" class="h-4 w-4 mr-1"></i>
                            +15.3%
                        </div>
                        <span class="text-gray-500 text-sm ml-2">vs last month</span>
                    </div>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                    <i data-lucide="indian-rupee" class="w-6 h-6 text-green-600"></i>
                </div>
            </div>
        </div>

        <!-- Orders -->
        <div class="bg-white rounded-xl p-6 border border-gray-200 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Orders</p>
                    <p class="text-3xl font-bold text-gray-900 mt-1">450</p>
                    <div class="flex items-center mt-2">
                        <div class="flex items-center text-green-600 text-sm font-medium">
                            <i data-lucide="trending-up" class="h-4 w-4 mr-1"></i>
                            +8.2%
                        </div>
                        <span class="text-gray-500 text-sm ml-2">vs last month</span>
                    </div>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                    <i data-lucide="shopping-cart" class="w-6 h-6 text-blue-600"></i>
                </div>
            </div>
        </div>

        <!-- Products Sold -->
        <div class="bg-white rounded-xl p-6 border border-gray-200 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Products Sold</p>
                    <p class="text-3xl font-bold text-gray-900 mt-1">1,247</p>
                    <div class="flex items-center mt-2">
                        <div class="flex items-center text-red-600 text-sm font-medium">
                            <i data-lucide="trending-down" class="h-4 w-4 mr-1"></i>
                            -2.1%
                        </div>
                        <span class="text-gray-500 text-sm ml-2">vs last month</span>
                    </div>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                    <i data-lucide="package" class="w-6 h-6 text-purple-600"></i>
                </div>
            </div>
        </div>

        <!-- Store Visitors -->
        <div class="bg-white rounded-xl p-6 border border-gray-200 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Store Visitors</p>
                    <p class="text-3xl font-bold text-gray-900 mt-1">8,921</p>
                    <div class="flex items-center mt-2">
                        <div class="flex items-center text-green-600 text-sm font-medium">
                            <i data-lucide="trending-up" class="h-4 w-4 mr-1"></i>
                            +12.4%
                        </div>
                        <span class="text-gray-500 text-sm ml-2">vs last month</span>
                    </div>
                </div>
                <div class="w-12 h-12 bg-orange-100 rounded-xl flex items-center justify-center">
                    <i data-lucide="users" class="w-6 h-6 text-orange-600"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <!-- Sales Chart -->
        <div class="lg:col-span-2 bg-white rounded-xl p-6 border border-gray-200 shadow-sm">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Sales Performance</h3>
                    <p class="text-sm text-gray-500">Daily sales over the last week</p>
                </div>
                <div class="flex items-center space-x-2">
                    <button class="px-3 py-1 text-xs bg-purple-100 text-purple-700 rounded-full">Sales</button>
                    <button class="px-3 py-1 text-xs bg-gray-100 text-gray-600 rounded-full">Orders</button>
                </div>
            </div>
            <div id="shopkeeperAnalyticsChart" class="w-full" style="height: 300px;"></div>
        </div>

        <!-- Top Products -->
        <div class="bg-white rounded-xl p-6 border border-gray-200 shadow-sm">
            <h3 class="text-lg font-semibold text-gray-900 mb-6">Top Products</h3>
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                            <span class="text-purple-600 font-semibold text-sm">🪔</span>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900 text-sm">Golden Brass Diya</p>
                            <p class="text-xs text-gray-500">45 sold</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="font-semibold text-gray-900 text-sm">₹20,250</p>
                        <div class="w-16 bg-gray-200 rounded-full h-1.5 mt-1">
                            <div class="bg-purple-600 h-1.5 rounded-full" style="width: 90%"></div>
                        </div>
                    </div>
                </div>
                
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                            <span class="text-green-600 font-semibold text-sm">🌺</span>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900 text-sm">Marigold Garland</p>
                            <p class="text-xs text-gray-500">32 sold</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="font-semibold text-gray-900 text-sm">₹3,840</p>
                        <div class="w-16 bg-gray-200 rounded-full h-1.5 mt-1">
                            <div class="bg-green-600 h-1.5 rounded-full" style="width: 70%"></div>
                        </div>
                    </div>
                </div>
                
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center">
                            <span class="text-orange-600 font-semibold text-sm">🕉️</span>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900 text-sm">Sandalwood Incense</p>
                            <p class="text-xs text-gray-500">28 sold</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="font-semibold text-gray-900 text-sm">₹5,040</p>
                        <div class="w-16 bg-gray-200 rounded-full h-1.5 mt-1">
                            <div class="bg-orange-600 h-1.5 rounded-full" style="width: 60%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="bg-white rounded-xl p-6 border border-gray-200 shadow-sm">
        <h3 class="text-lg font-semibold text-gray-900 mb-6">Recent Activity</h3>
        <div class="space-y-4">
            <div class="flex items-center space-x-4">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                        <i data-lucide="shopping-cart" class="w-4 h-4 text-green-600"></i>
                    </div>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-900">New order received</p>
                    <p class="text-sm text-gray-500">Order #1847 for ₹1,250 by Rajesh Kumar</p>
                </div>
                <div class="text-sm text-gray-500">5 min ago</div>
            </div>
            
            <div class="flex items-center space-x-4">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                        <i data-lucide="package" class="w-4 h-4 text-blue-600"></i>
                    </div>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-900">Product stock updated</p>
                    <p class="text-sm text-gray-500">Golden Brass Diya Set - Added 20 units</p>
                </div>
                <div class="text-sm text-gray-500">1 hour ago</div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Analytics Chart
    const analyticsChartElement = document.getElementById('shopkeeperAnalyticsChart');
    if (analyticsChartElement && typeof ApexCharts !== 'undefined') {
        const options = {
            chart: {
                type: 'area',
                height: 300,
                fontFamily: 'Inter, ui-sans-serif, system-ui',
                toolbar: { show: false }
            },
            series: [{
                name: 'Daily Sales (₹)',
                data: [15000, 18000, 22000, 19000, 25000, 28000, 32000]
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

        const chart = new ApexCharts(analyticsChartElement, options);
        chart.render();
    }
});
</script>
@endsection
