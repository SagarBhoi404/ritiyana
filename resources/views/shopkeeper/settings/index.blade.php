<!-- resources/views/shopkeeper/settings/index.blade.php -->
@extends('layouts.shopkeeper')

@section('title', 'Settings')
@section('breadcrumb', 'Settings')

@section('content')
<div class="px-4 sm:px-6 lg:px-8 py-8">
    <!-- Page Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Store Settings</h1>
        <p class="mt-2 text-sm text-gray-700">Manage your store configuration and preferences</p>
    </div>

    <form method="POST" action="#">
        @csrf
        @method('PUT')

        <!-- Store Information -->
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm mb-8">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-900 flex items-center">
                    <i data-lucide="store" class="w-5 h-5 mr-2"></i>
                    Store Information
                </h2>
                <p class="text-sm text-gray-500 mt-1">Basic information about your store</p>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="store_name" class="block text-sm font-medium text-gray-700 mb-2">Store Name</label>
                        <input type="text" name="store_name" id="store_name" 
                               value="{{ old('store_name', $store->name ?? 'My Puja Store') }}" 
                               class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                    </div>
                    
                    <div>
                        <label for="contact_email" class="block text-sm font-medium text-gray-700 mb-2">Contact Email</label>
                        <input type="email" name="contact_email" id="contact_email" 
                               value="{{ old('contact_email', $store->email ?? Auth::user()->email) }}" 
                               class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                    </div>
                    
                    <div>
                        <label for="contact_phone" class="block text-sm font-medium text-gray-700 mb-2">Contact Phone</label>
                        <input type="text" name="contact_phone" id="contact_phone" 
                               value="{{ old('contact_phone', $store->phone ?? '') }}" 
                               placeholder="+91 9876543210"
                               class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                    </div>
                    
                    <div>
                        <label for="store_description" class="block text-sm font-medium text-gray-700 mb-2">Store Description</label>
                        <input type="text" name="store_description" id="store_description" 
                               value="{{ old('store_description', $store->description ?? '') }}" 
                               placeholder="Brief description of your store"
                               class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                    </div>
                    
                    <div class="md:col-span-2">
                        <label for="address" class="block text-sm font-medium text-gray-700 mb-2">Store Address</label>
                        <textarea name="address" id="address" rows="3" 
                                  placeholder="Enter complete store address"
                                  class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">{{ old('address', $store->address ?? '') }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <!-- Payment Settings -->
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm mb-8">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-900 flex items-center">
                    <i data-lucide="credit-card" class="w-5 h-5 mr-2"></i>
                    Payment Preferences
                </h2>
                <p class="text-sm text-gray-500 mt-1">Configure payment gateway and currency settings</p>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="payment_gateway" class="block text-sm font-medium text-gray-700 mb-2">Payment Gateway</label>
                        <select name="payment_gateway" id="payment_gateway" 
                                class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                            <option value="razorpay" {{ (old('payment_gateway', $settings->payment_gateway ?? 'razorpay') == 'razorpay') ? 'selected' : '' }}>Razorpay</option>
                            <option value="paypal" {{ (old('payment_gateway', $settings->payment_gateway ?? '') == 'paypal') ? 'selected' : '' }}>PayPal</option>
                            <option value="stripe" {{ (old('payment_gateway', $settings->payment_gateway ?? '') == 'stripe') ? 'selected' : '' }}>Stripe</option>
                            <option value="paytm" {{ (old('payment_gateway', $settings->payment_gateway ?? '') == 'paytm') ? 'selected' : '' }}>Paytm</option>
                        </select>
                    </div>
                    
                    <div>
                        <label for="currency" class="block text-sm font-medium text-gray-700 mb-2">Currency Symbol</label>
                        <input type="text" name="currency" id="currency" 
                               value="{{ old('currency', $settings->currency ?? '₹') }}" 
                               maxlength="5"
                               class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                    </div>
                </div>
            </div>
        </div>

        <!-- Shipping Settings -->
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm mb-8">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-900 flex items-center">
                    <i data-lucide="truck" class="w-5 h-5 mr-2"></i>
                    Shipping Preferences
                </h2>
                <p class="text-sm text-gray-500 mt-1">Configure shipping costs and delivery options</p>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="free_shipping_threshold" class="block text-sm font-medium text-gray-700 mb-2">Free Shipping Above (₹)</label>
                        <input type="number" name="free_shipping_threshold" id="free_shipping_threshold" 
                               value="{{ old('free_shipping_threshold', $settings->free_shipping_threshold ?? 500) }}" 
                               min="0"
                               class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                    </div>
                    
                    <div>
                        <label for="shipping_cost" class="block text-sm font-medium text-gray-700 mb-2">Standard Shipping Cost (₹)</label>
                        <input type="number" name="shipping_cost" id="shipping_cost" 
                               value="{{ old('shipping_cost', $settings->shipping_cost ?? 50) }}" 
                               min="0"
                               class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                    </div>
                    
                    <div>
                        <label for="delivery_time" class="block text-sm font-medium text-gray-700 mb-2">Standard Delivery Time</label>
                        <input type="text" name="delivery_time" id="delivery_time" 
                               value="{{ old('delivery_time', $settings->delivery_time ?? '3-5 business days') }}" 
                               placeholder="e.g., 3-5 business days"
                               class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                    </div>
                    
                    <div>
                        <label for="express_delivery_cost" class="block text-sm font-medium text-gray-700 mb-2">Express Delivery Cost (₹)</label>
                        <input type="number" name="express_delivery_cost" id="express_delivery_cost" 
                               value="{{ old('express_delivery_cost', $settings->express_delivery_cost ?? 100) }}" 
                               min="0"
                               class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                    </div>
                </div>
            </div>
        </div>

        <!-- General Preferences -->
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm mb-8">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-900 flex items-center">
                    <i data-lucide="settings" class="w-5 h-5 mr-2"></i>
                    General Preferences
                </h2>
                <p class="text-sm text-gray-500 mt-1">Basic system preferences for your store</p>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="timezone" class="block text-sm font-medium text-gray-700 mb-2">Timezone</label>
                        <select name="timezone" id="timezone" 
                                class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                            <option value="Asia/Kolkata" {{ (old('timezone', $settings->timezone ?? 'Asia/Kolkata') == 'Asia/Kolkata') ? 'selected' : '' }}>Asia/Kolkata (India Standard Time)</option>
                            <option value="UTC" {{ (old('timezone', $settings->timezone ?? '') == 'UTC') ? 'selected' : '' }}>UTC (Coordinated Universal Time)</option>
                            <option value="America/New_York" {{ (old('timezone', $settings->timezone ?? '') == 'America/New_York') ? 'selected' : '' }}>America/New_York (Eastern Time)</option>
                        </select>
                    </div>
                    
                    <div>
                        <label for="language" class="block text-sm font-medium text-gray-700 mb-2">Default Language</label>
                        <select name="language" id="language" 
                                class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                            <option value="en" {{ (old('language', $settings->language ?? 'en') == 'en') ? 'selected' : '' }}>English</option>
                            <option value="hi" {{ (old('language', $settings->language ?? '') == 'hi') ? 'selected' : '' }}>Hindi</option>
                            <option value="bn" {{ (old('language', $settings->language ?? '') == 'bn') ? 'selected' : '' }}>Bengali</option>
                        </select>
                    </div>
                    
                    <div>
                        <label for="products_per_page" class="block text-sm font-medium text-gray-700 mb-2">Products Per Page</label>
                        <select name="products_per_page" id="products_per_page" 
                                class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                            <option value="12" {{ (old('products_per_page', $settings->products_per_page ?? '12') == '12') ? 'selected' : '' }}>12 products</option>
                            <option value="24" {{ (old('products_per_page', $settings->products_per_page ?? '') == '24') ? 'selected' : '' }}>24 products</option>
                            <option value="36" {{ (old('products_per_page', $settings->products_per_page ?? '') == '36') ? 'selected' : '' }}>36 products</option>
                        </select>
                    </div>
                    
                    <div>
                        <label for="order_notification" class="block text-sm font-medium text-gray-700 mb-2">Order Notifications</label>
                        <select name="order_notification" id="order_notification" 
                                class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                            <option value="1" {{ (old('order_notification', $settings->order_notification ?? '1') == '1') ? 'selected' : '' }}>Email + SMS</option>
                            <option value="2" {{ (old('order_notification', $settings->order_notification ?? '') == '2') ? 'selected' : '' }}>Email Only</option>
                            <option value="3" {{ (old('order_notification', $settings->order_notification ?? '') == '3') ? 'selected' : '' }}>SMS Only</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Actions -->
        <div class="flex items-center justify-between">
            <div class="text-sm text-gray-500">
                Settings are auto-saved when you click save
            </div>
            <div class="flex items-center space-x-4">
                <button type="button" class="px-6 py-3 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2">
                    Reset to Default
                </button>
                <button type="submit" class="inline-flex items-center px-6 py-3 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-purple-600 hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2">
                    <i data-lucide="save" class="w-4 h-4 mr-2"></i>
                    Save Settings
                </button>
            </div>
        </div>
    </form>
</div>
@endsection
