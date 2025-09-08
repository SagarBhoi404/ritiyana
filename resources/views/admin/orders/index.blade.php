<!-- resources/views/admin/orders/index.blade.php -->
@extends('layouts.admin')

@section('title', 'Orders Management')
@section('breadcrumb', 'Orders')

@section('content')
<div class="p-6">
    <!-- Page Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Orders Management</h1>
                <p class="text-gray-600 mt-1">Track and manage all customer orders</p>
            </div>
            <div class="flex items-center space-x-3">
                <button class="flex items-center px-4 py-2 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                    <i data-lucide="filter" class="w-4 h-4 mr-2"></i>
                    Filter
                </button>
                <button class="flex items-center px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700">
                    <i data-lucide="download" class="w-4 h-4 mr-2"></i>
                    Export
                </button>
            </div>
        </div>
    </div>

    <!-- Order Stats -->
    <div class="grid grid-cols-1 md:grid-cols-5 gap-6 mb-8">
        <div class="bg-white rounded-xl p-6 border border-gray-200">
            <div class="text-center">
                <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center mx-auto mb-3">
                    <i data-lucide="shopping-cart" class="w-6 h-6 text-blue-600"></i>
                </div>
                <p class="text-2xl font-bold text-gray-900">892</p>
                <p class="text-sm text-gray-600">Total Orders</p>
            </div>
        </div>
        
        <div class="bg-white rounded-xl p-6 border border-gray-200">
            <div class="text-center">
                <div class="w-12 h-12 bg-yellow-100 rounded-xl flex items-center justify-center mx-auto mb-3">
                    <i data-lucide="clock" class="w-6 h-6 text-yellow-600"></i>
                </div>
                <p class="text-2xl font-bold text-yellow-600">23</p>
                <p class="text-sm text-gray-600">Pending</p>
            </div>
        </div>
        
        <div class="bg-white rounded-xl p-6 border border-gray-200">
            <div class="text-center">
                <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center mx-auto mb-3">
                    <i data-lucide="package" class="w-6 h-6 text-purple-600"></i>
                </div>
                <p class="text-2xl font-bold text-purple-600">145</p>
                <p class="text-sm text-gray-600">Processing</p>
            </div>
        </div>
        
        <div class="bg-white rounded-xl p-6 border border-gray-200">
            <div class="text-center">
                <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center mx-auto mb-3">
                    <i data-lucide="check-circle" class="w-6 h-6 text-green-600"></i>
                </div>
                <p class="text-2xl font-bold text-green-600">698</p>
                <p class="text-sm text-gray-600">Delivered</p>
            </div>
        </div>
        
        <div class="bg-white rounded-xl p-6 border border-gray-200">
            <div class="text-center">
                <div class="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center mx-auto mb-3">
                    <i data-lucide="x-circle" class="w-6 h-6 text-red-600"></i>
                </div>
                <p class="text-2xl font-bold text-red-600">26</p>
                <p class="text-sm text-gray-600">Cancelled</p>
            </div>
        </div>
    </div>

    <!-- Orders List -->
    <div class="bg-white rounded-xl border border-gray-200">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Recent Orders</h3>
        </div>
        
        <div class="divide-y divide-gray-200">
            <!-- Order Item -->
            <div class="p-6 hover:bg-gray-50">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                            <span class="text-purple-600 font-semibold text-sm">#1847</span>
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-900">Ganesh Puja Kit</h4>
                            <p class="text-sm text-gray-500">Ordered by Rajesh Kumar • 2 hours ago</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-6">
                        <div class="text-right">
                            <p class="font-semibold text-gray-900">₹1,250</p>
                            <p class="text-sm text-gray-500">2 items</p>
                        </div>
                        <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full bg-green-100 text-green-800">
                            Delivered
                        </span>
                        <div class="flex items-center space-x-2">
                            <button class="p-2 text-gray-400 hover:text-gray-600 rounded-lg hover:bg-gray-100">
                                <i data-lucide="eye" class="w-4 h-4"></i>
                            </button>
                            <button class="p-2 text-gray-400 hover:text-gray-600 rounded-lg hover:bg-gray-100">
                                <i data-lucide="printer" class="w-4 h-4"></i>
                            </button>
                            <button class="p-2 text-gray-400 hover:text-gray-600 rounded-lg hover:bg-gray-100">
                                <i data-lucide="more-horizontal" class="w-4 h-4"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- More order items... -->
        </div>
    </div>
</div>
@endsection
