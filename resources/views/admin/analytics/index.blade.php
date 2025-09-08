<!-- resources/views/admin/analytics/index.blade.php -->
@extends('layouts.admin')

@section('title', 'Analytics')
@section('breadcrumb', 'Analytics')

@section('content')
<div class="px-4 sm:px-6 lg:px-8 py-8">
    <!-- Page Header -->
    <div class="mb-8">
        <div class="sm:flex sm:items-center sm:justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Analytics Dashboard</h1>
                <p class="mt-2 text-sm text-gray-700">Monitor your store performance and customer insights</p>
            </div>
            <div class="mt-4 sm:mt-0 sm:flex sm:items-center sm:space-x-3">
                <select class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:ring-purple-500 focus:border-purple-500">
                    <option>Last 7 days</option>
                    <option>Last 30 days</option>
                    <option>Last 3 months</option>
                    <option>Last 12 months</option>
                </select>
                <button class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50">
                    <i data-lucide="download" class="w-4 h-4 mr-2"></i>
                    Export Report
                </button>
            </div>
        </div>
    </div>

    <!-- Key Metrics -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Revenue -->
        <div class="bg-white rounded-xl p-6 border border-gray-200 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Revenue</p>
                    <p class="text-3xl font-bold text-gray-900 mt-1">₹12,34,567</p>
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
                    <p class="text-sm font-medium text-gray-600">Total Orders</p>
                    <p class="text-3xl font-bold text-gray-900 mt-1">8,912</p>
                    <div class="flex items-center mt-2">
                        <div class="flex items-center text-red-600 text-sm font-medium">
                            <i data-lucide="trending-down" class="h-4 w-4 mr-1"></i>
                            -5.2%
                        </div>
                        <span class="text-gray-500 text-sm ml-2">vs last month</span>
                    </div>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                    <i data-lucide="shopping-cart" class="w-6 h-6 text-blue-600"></i>
                </div>
            </div>
        </div>

        <!-- Visitors -->
        <div class="bg-white rounded-xl p-6 border border-gray-200 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Unique Visitors</p>
                    <p class="text-3xl font-bold text-gray-900 mt-1">56,789</p>
                    <div class="flex items-center mt-2">
                        <div class="flex items-center text-green-600 text-sm font-medium">
                            <i data-lucide="trending-up" class="h-4 w-4 mr-1"></i>
                            +22.1%
                        </div>
                        <span class="text-gray-500 text-sm ml-2">vs last month</span>
                    </div>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                    <i data-lucide="users" class="w-6 h-6 text-purple-600"></i>
                </div>
            </div>
        </div>

        <!-- Conversion Rate -->
        <div class="bg-white rounded-xl p-6 border border-gray-200 shadow-sm">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Conversion Rate</p>
                    <p class="text-3xl font-bold text-gray-900 mt-1">3.24%</p>
                    <div class="flex items-center mt-2">
                        <div class="flex items-center text-green-600 text-sm font-medium">
                            <i data-lucide="trending-up" class="h-4 w-4 mr-1"></i>
                            +0.8%
                        </div>
                        <span class="text-gray-500 text-sm ml-2">vs last month</span>
                    </div>
                </div>
                <div class="w-12 h-12 bg-orange-100 rounded-xl flex items-center justify-center">
                    <i data-lucide="target" class="w-6 h-6 text-orange-600"></i>
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
                    <h3 class="text-lg font-semibold text-gray-900">Revenue Trend</h3>
                    <p class="text-sm text-gray-500">Monthly revenue performance</p>
                </div>
                <div class="flex items-center space-x-2">
                    <button class="px-3 py-1 text-xs bg-purple-100 text-purple-700 rounded-full">Revenue</button>
                    <button class="px-3 py-1 text-xs bg-gray-100 text-gray-600 rounded-full">Orders</button>
                </div>
            </div>
            <div id="revenueChart" class="w-full" style="height: 300px;"></div>
        </div>

        <!-- Top Products -->
        <div class="bg-white rounded-xl p-6 border border-gray-200 shadow-sm">
            <h3 class="text-lg font-semibold text-gray-900 mb-6">Top Selling Products</h3>
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                            <span class="text-purple-600 font-semibold text-sm">🪔</span>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900 text-sm">Golden Brass Diya Set</p>
                            <p class="text-xs text-gray-500">248 sold</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="font-semibold text-gray-900 text-sm">₹1,11,600</p>
                        <div class="w-20 bg-gray-200 rounded-full h-1.5 mt-1">
                            <div class="bg-purple-600 h-1.5 rounded-full" style="width: 95%"></div>
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
                            <p class="text-xs text-gray-500">186 sold</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="font-semibold text-gray-900 text-sm">₹22,320</p>
                        <div class="w-20 bg-gray-200 rounded-full h-1.5 mt-1">
                            <div class="bg-green-600 h-1.5 rounded-full" style="width: 75%"></div>
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
                            <p class="text-xs text-gray-500">142 sold</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="font-semibold text-gray-900 text-sm">₹25,560</p>
                        <div class="w-20 bg-gray-200 rounded-full h-1.5 mt-1">
                            <div class="bg-orange-600 h-1.5 rounded-full" style="width: 57%"></div>
                        </div>
                    </div>
                </div>
                
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-indigo-100 rounded-lg flex items-center justify-center">
                            <span class="text-indigo-600 font-semibold text-sm">🎁</span>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900 text-sm">Ganesh Puja Kit</p>
                            <p class="text-xs text-gray-500">89 sold</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="font-semibold text-gray-900 text-sm">₹2,22,500</p>
                        <div class="w-20 bg-gray-200 rounded-full h-1.5 mt-1">
                            <div class="bg-indigo-600 h-1.5 rounded-full" style="width: 36%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Customer Analytics -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Customer Acquisition -->
        <div class="bg-white rounded-xl p-6 border border-gray-200 shadow-sm">
            <h3 class="text-lg font-semibold text-gray-900 mb-6">Customer Acquisition</h3>
            <div id="customerChart" style="height: 250px;"></div>
        </div>

        <!-- Traffic Sources -->
        <div class="bg-white rounded-xl p-6 border border-gray-200 shadow-sm">
            <h3 class="text-lg font-semibold text-gray-900 mb-6">Traffic Sources</h3>
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="w-3 h-3 bg-blue-500 rounded-full"></div>
                        <span class="text-sm font-medium text-gray-700">Organic Search</span>
                    </div>
                    <div class="text-right">
                        <span class="text-sm font-bold text-gray-900">42.5%</span>
                        <div class="text-xs text-gray-500">24,156 visits</div>
                    </div>
                </div>
                
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                        <span class="text-sm font-medium text-gray-700">Direct</span>
                    </div>
                    <div class="text-right">
                        <span class="text-sm font-bold text-gray-900">28.7%</span>
                        <div class="text-xs text-gray-500">16,298 visits</div>
                    </div>
                </div>
                
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="w-3 h-3 bg-purple-500 rounded-full"></div>
                        <span class="text-sm font-medium text-gray-700">Social Media</span>
                    </div>
                    <div class="text-right">
                        <span class="text-sm font-bold text-gray-900">18.9%</span>
                        <div class="text-xs text-gray-500">10,735 visits</div>
                    </div>
                </div>
                
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="w-3 h-3 bg-orange-500 rounded-full"></div>
                        <span class="text-sm font-medium text-gray-700">Email Marketing</span>
                    </div>
                    <div class="text-right">
                        <span class="text-sm font-bold text-gray-900">6.4%</span>
                        <div class="text-xs text-gray-500">3,634 visits</div>
                    </div>
                </div>
                
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="w-3 h-3 bg-red-500 rounded-full"></div>
                        <span class="text-sm font-medium text-gray-700">Paid Ads</span>
                    </div>
                    <div class="text-right">
                        <span class="text-sm font-bold text-gray-900">3.5%</span>
                        <div class="text-xs text-gray-500">1,989 visits</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="bg-white rounded-xl p-6 border border-gray-200 shadow-sm">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-semibold text-gray-900">Recent Activity</h3>
            <button class="text-purple-600 hover:text-purple-700 text-sm font-medium">View All</button>
        </div>
        
        <div class="flow-root">
            <ul class="divide-y divide-gray-200">
                <li class="py-4">
                    <div class="flex items-center space-x-4">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                <i data-lucide="shopping-cart" class="w-4 h-4 text-green-600"></i>
                            </div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900">New order placed</p>
                            <p class="text-sm text-gray-500">Order #1847 for ₹1,250 by Rajesh Kumar</p>
                        </div>
                        <div class="text-sm text-gray-500">2 min ago</div>
                    </div>
                </li>
                
                <li class="py-4">
                    <div class="flex items-center space-x-4">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                <i data-lucide="user-plus" class="w-4 h-4 text-blue-600"></i>
                            </div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900">New customer registered</p>
                            <p class="text-sm text-gray-500">Priya Sharma created an account</p>
                        </div>
                        <div class="text-sm text-gray-500">15 min ago</div>
                    </div>
                </li>
                
                <li class="py-4">
                    <div class="flex items-center space-x-4">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-purple-100 rounded-full flex items-center justify-center">
                                <i data-lucide="star" class="w-4 h-4 text-purple-600"></i>
                            </div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900">Product review received</p>
                            <p class="text-sm text-gray-500">5-star review for Golden Brass Diya Set</p>
                        </div>
                        <div class="text-sm text-gray-500">1 hour ago</div>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Revenue Chart
    const revenueOptions = {
        chart: {
            type: 'area',
            height: 300,
            toolbar: { show: false },
            animations: { enabled: true }
        },
        series: [{
            name: 'Revenue',
            data: [310000, 400000, 350000, 500000, 490000, 600000, 700000, 650000, 800000, 750000, 900000, 1234567]
        }],
        xaxis: {
            categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
        },
        yaxis: {
            labels: {
                formatter: function(val) {
                    return '₹' + (val/100000).toFixed(0) + 'L';
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
        colors: ['#7c3aed'],
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
            strokeDashArray: 4,
            borderColor: '#f1f5f9'
        }
    };

    // Customer Acquisition Chart
    const customerOptions = {
        chart: {
            type: 'donut',
            height: 250,
            toolbar: { show: false }
        },
        series: [42.5, 28.7, 18.9, 6.4, 3.5],
        labels: ['Organic Search', 'Direct', 'Social Media', 'Email', 'Paid Ads'],
        colors: ['#3b82f6', '#10b981', '#8b5cf6', '#f59e0b', '#ef4444'],
        plotOptions: {
            pie: {
                donut: {
                    size: '60%'
                }
            }
        },
        legend: {
            show: false
        }
    };

    // Render charts if ApexCharts is available
    if (typeof ApexCharts !== 'undefined') {
        const revenueChart = new ApexCharts(document.querySelector('#revenueChart'), revenueOptions);
        revenueChart.render();
        
        const customerChart = new ApexCharts(document.querySelector('#customerChart'), customerOptions);
        customerChart.render();
    }
});
</script>
@endsection
