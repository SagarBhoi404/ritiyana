<!-- resources/views/admin/settings/index.blade.php -->
@extends('layouts.admin')

@section('title', 'Settings')
@section('breadcrumb', 'Settings')

@section('content')
<div class="px-4 sm:px-6 lg:px-8 py-8">
    <!-- Page Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Settings</h1>
                <p class="mt-2 text-sm text-gray-700">Manage your store configuration and preferences</p>
            </div>
        </div>
    </div>

    <!-- Settings Form -->
    <form method="POST" action="#" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <!-- General Settings -->
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm mb-8">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-900 flex items-center">
                    <i data-lucide="store" class="w-5 h-5 mr-2"></i>
                    General Settings
                </h2>
                <p class="text-sm text-gray-500 mt-1">Basic store information and branding</p>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Store Name -->
                    <div>
                        <label for="store_name" class="block text-sm font-medium text-gray-700 mb-2">
                            Store Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" 
                               name="store_name" 
                               id="store_name" 
                               value="{{ old('store_name', $settings->store_name ?? 'Ritiyana') }}" 
                               required
                               placeholder="Enter store name"
                               class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                    </div>

                    <!-- Store Tagline -->
                    <div>
                        <label for="store_tagline" class="block text-sm font-medium text-gray-700 mb-2">
                            Store Tagline
                        </label>
                        <input type="text" 
                               name="store_tagline" 
                               id="store_tagline" 
                               value="{{ old('store_tagline', $settings->store_tagline ?? 'Your Spiritual Shopping Destination') }}"
                               placeholder="Enter store tagline"
                               class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                    </div>

                    <!-- Store Email -->
                    <div>
                        <label for="store_email" class="block text-sm font-medium text-gray-700 mb-2">
                            Store Email <span class="text-red-500">*</span>
                        </label>
                        <input type="email" 
                               name="store_email" 
                               id="store_email" 
                               value="{{ old('store_email', $settings->store_email ?? 'admin@ritiyana.com') }}" 
                               required
                               placeholder="store@example.com"
                               class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                    </div>

                    <!-- Store Phone -->
                    <div>
                        <label for="store_phone" class="block text-sm font-medium text-gray-700 mb-2">
                            Store Phone
                        </label>
                        <input type="tel" 
                               name="store_phone" 
                               id="store_phone" 
                               value="{{ old('store_phone', $settings->store_phone ?? '+91 9876543210') }}"
                               placeholder="+91 9876543210"
                               class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                    </div>

                    <!-- Store Address -->
                    <div class="md:col-span-2">
                        <label for="store_address" class="block text-sm font-medium text-gray-700 mb-2">
                            Store Address
                        </label>
                        <textarea name="store_address" 
                                  id="store_address" 
                                  rows="3"
                                  placeholder="Enter complete store address"
                                  class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">{{ old('store_address', $settings->store_address ?? '123 Main Street, New Delhi, India 110001') }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <!-- Business & Tax Settings -->
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm mb-8">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-900 flex items-center">
                    <i data-lucide="receipt" class="w-5 h-5 mr-2"></i>
                    Business & Tax Settings
                </h2>
                <p class="text-sm text-gray-500 mt-1">GST, tax rates, and business registration details</p>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- GST Number -->
                    <div>
                        <label for="gst_number" class="block text-sm font-medium text-gray-700 mb-2">
                            GST Number
                        </label>
                        <input type="text" 
                               name="gst_number" 
                               id="gst_number" 
                               value="{{ old('gst_number', $settings->gst_number ?? '') }}"
                               placeholder="e.g., 22AAAAA0000A1Z5"
                               class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                    </div>

                    <!-- Tax Rate -->
                    <div>
                        <label for="tax_rate" class="block text-sm font-medium text-gray-700 mb-2">
                            Default Tax Rate (%)
                        </label>
                        <input type="number" 
                               name="tax_rate" 
                               id="tax_rate" 
                               value="{{ old('tax_rate', $settings->tax_rate ?? '18') }}"
                               step="0.01" 
                               min="0" 
                               max="100"
                               placeholder="18.00"
                               class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                    </div>

                    <!-- Business Registration -->
                    <div>
                        <label for="business_reg" class="block text-sm font-medium text-gray-700 mb-2">
                            Business Registration Number
                        </label>
                        <input type="text" 
                               name="business_reg" 
                               id="business_reg" 
                               value="{{ old('business_reg', $settings->business_reg ?? '') }}"
                               placeholder="Enter business registration number"
                               class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                    </div>

                    <!-- PAN Number -->
                    <div>
                        <label for="pan_number" class="block text-sm font-medium text-gray-700 mb-2">
                            PAN Number
                        </label>
                        <input type="text" 
                               name="pan_number" 
                               id="pan_number" 
                               value="{{ old('pan_number', $settings->pan_number ?? '') }}"
                               placeholder="ABCDE1234F"
                               class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                    </div>
                </div>
            </div>
        </div>

        <!-- Payment Settings -->
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm mb-8">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-900 flex items-center">
                    <i data-lucide="credit-card" class="w-5 h-5 mr-2"></i>
                    Payment Settings
                </h2>
                <p class="text-sm text-gray-500 mt-1">Configure payment gateways and currency preferences</p>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Currency -->
                    <div>
                        <label for="currency" class="block text-sm font-medium text-gray-700 mb-2">
                            Currency Symbol
                        </label>
                        <input type="text" 
                               name="currency" 
                               id="currency" 
                               value="{{ old('currency', $settings->currency ?? '₹') }}"
                               maxlength="5"
                               placeholder="₹"
                               class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                    </div>

                    <!-- Currency Code -->
                    <div>
                        <label for="currency_code" class="block text-sm font-medium text-gray-700 mb-2">
                            Currency Code
                        </label>
                        <select name="currency_code" 
                                id="currency_code"
                                class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                            <option value="INR" {{ (old('currency_code', $settings->currency_code ?? '') == 'INR') ? 'selected' : '' }}>INR - Indian Rupee</option>
                            <option value="USD" {{ (old('currency_code', $settings->currency_code ?? '') == 'USD') ? 'selected' : '' }}>USD - US Dollar</option>
                            <option value="EUR" {{ (old('currency_code', $settings->currency_code ?? '') == 'EUR') ? 'selected' : '' }}>EUR - Euro</option>
                        </select>
                    </div>

                    <!-- Payment Gateway -->
                    <div>
                        <label for="payment_gateway" class="block text-sm font-medium text-gray-700 mb-2">
                            Primary Payment Gateway
                        </label>
                        <select name="payment_gateway" 
                                id="payment_gateway"
                                class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                            <option value="razorpay" {{ (old('payment_gateway', $settings->payment_gateway ?? '') == 'razorpay') ? 'selected' : '' }}>Razorpay</option>
                            <option value="paypal" {{ (old('payment_gateway', $settings->payment_gateway ?? '') == 'paypal') ? 'selected' : '' }}>PayPal</option>
                            <option value="stripe" {{ (old('payment_gateway', $settings->payment_gateway ?? '') == 'stripe') ? 'selected' : '' }}>Stripe</option>
                            <option value="paytm" {{ (old('payment_gateway', $settings->payment_gateway ?? '') == 'paytm') ? 'selected' : '' }}>Paytm</option>
                        </select>
                    </div>

                    <!-- Minimum Order Amount -->
                    <div>
                        <label for="min_order_amount" class="block text-sm font-medium text-gray-700 mb-2">
                            Minimum Order Amount
                        </label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500">₹</span>
                            <input type="number" 
                                   name="min_order_amount" 
                                   id="min_order_amount" 
                                   value="{{ old('min_order_amount', $settings->min_order_amount ?? '100') }}"
                                   min="0"
                                   placeholder="100"
                                   class="block w-full pl-8 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Shipping & Delivery Settings -->
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm mb-8">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-900 flex items-center">
                    <i data-lucide="truck" class="w-5 h-5 mr-2"></i>
                    Shipping & Delivery Settings
                </h2>
                <p class="text-sm text-gray-500 mt-1">Configure shipping options and delivery preferences</p>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Free Shipping Threshold -->
                    <div>
                        <label for="free_shipping_threshold" class="block text-sm font-medium text-gray-700 mb-2">
                            Free Shipping Above
                        </label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500">₹</span>
                            <input type="number" 
                                   name="free_shipping_threshold" 
                                   id="free_shipping_threshold" 
                                   value="{{ old('free_shipping_threshold', $settings->free_shipping_threshold ?? '500') }}"
                                   min="0"
                                   placeholder="500"
                                   class="block w-full pl-8 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                        </div>
                    </div>

                    <!-- Standard Shipping Cost -->
                    <div>
                        <label for="shipping_cost" class="block text-sm font-medium text-gray-700 mb-2">
                            Standard Shipping Cost
                        </label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500">₹</span>
                            <input type="number" 
                                   name="shipping_cost" 
                                   id="shipping_cost" 
                                   value="{{ old('shipping_cost', $settings->shipping_cost ?? '50') }}"
                                   min="0"
                                   placeholder="50"
                                   class="block w-full pl-8 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                        </div>
                    </div>

                    <!-- Delivery Time -->
                    <div>
                        <label for="delivery_time" class="block text-sm font-medium text-gray-700 mb-2">
                            Standard Delivery Time
                        </label>
                        <input type="text" 
                               name="delivery_time" 
                               id="delivery_time" 
                               value="{{ old('delivery_time', $settings->delivery_time ?? '3-5 business days') }}"
                               placeholder="3-5 business days"
                               class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                    </div>

                    <!-- Express Delivery -->
                    <div>
                        <label for="express_delivery_cost" class="block text-sm font-medium text-gray-700 mb-2">
                            Express Delivery Cost
                        </label>
                        <div class="relative">
                            <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500">₹</span>
                            <input type="number" 
                                   name="express_delivery_cost" 
                                   id="express_delivery_cost" 
                                   value="{{ old('express_delivery_cost', $settings->express_delivery_cost ?? '100') }}"
                                   min="0"
                                   placeholder="100"
                                   class="block w-full pl-8 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Store Preferences -->
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm mb-8">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-900 flex items-center">
                    <i data-lucide="settings" class="w-5 h-5 mr-2"></i>
                    Store Preferences
                </h2>
                <p class="text-sm text-gray-500 mt-1">Configure timezone, language, and other preferences</p>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Timezone -->
                    <div>
                        <label for="timezone" class="block text-sm font-medium text-gray-700 mb-2">
                            Timezone
                        </label>
                        <select name="timezone" 
                                id="timezone"
                                class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                            <option value="Asia/Kolkata" {{ (old('timezone', $settings->timezone ?? '') == 'Asia/Kolkata') ? 'selected' : '' }}>Asia/Kolkata (India Standard Time)</option>
                            <option value="UTC" {{ (old('timezone', $settings->timezone ?? '') == 'UTC') ? 'selected' : '' }}>UTC (Coordinated Universal Time)</option>
                            <option value="America/New_York" {{ (old('timezone', $settings->timezone ?? '') == 'America/New_York') ? 'selected' : '' }}>America/New_York (Eastern Time)</option>
                            <option value="Europe/London" {{ (old('timezone', $settings->timezone ?? '') == 'Europe/London') ? 'selected' : '' }}>Europe/London (GMT)</option>
                        </select>
                    </div>

                    <!-- Language -->
                    <div>
                        <label for="default_language" class="block text-sm font-medium text-gray-700 mb-2">
                            Default Language
                        </label>
                        <select name="default_language" 
                                id="default_language"
                                class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                            <option value="en" {{ (old('default_language', $settings->default_language ?? '') == 'en') ? 'selected' : '' }}>English</option>
                            <option value="hi" {{ (old('default_language', $settings->default_language ?? '') == 'hi') ? 'selected' : '' }}>Hindi</option>
                            <option value="bn" {{ (old('default_language', $settings->default_language ?? '') == 'bn') ? 'selected' : '' }}>Bengali</option>
                            <option value="ta" {{ (old('default_language', $settings->default_language ?? '') == 'ta') ? 'selected' : '' }}>Tamil</option>
                        </select>
                    </div>

                    <!-- Products Per Page -->
                    <div>
                        <label for="products_per_page" class="block text-sm font-medium text-gray-700 mb-2">
                            Products Per Page
                        </label>
                        <select name="products_per_page" 
                                id="products_per_page"
                                class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                            <option value="12" {{ (old('products_per_page', $settings->products_per_page ?? '') == '12') ? 'selected' : '' }}>12 products</option>
                            <option value="24" {{ (old('products_per_page', $settings->products_per_page ?? '') == '24') ? 'selected' : '' }}>24 products</option>
                            <option value="36" {{ (old('products_per_page', $settings->products_per_page ?? '') == '36') ? 'selected' : '' }}>36 products</option>
                            <option value="48" {{ (old('products_per_page', $settings->products_per_page ?? '') == '48') ? 'selected' : '' }}>48 products</option>
                        </select>
                    </div>

                    <!-- Store Status -->
                    <div>
                        <label for="store_status" class="block text-sm font-medium text-gray-700 mb-2">
                            Store Status
                        </label>
                        <select name="store_status" 
                                id="store_status"
                                class="block w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                            <option value="open" {{ (old('store_status', $settings->store_status ?? '') == 'open') ? 'selected' : '' }}>Open for Business</option>
                            <option value="maintenance" {{ (old('store_status', $settings->store_status ?? '') == 'maintenance') ? 'selected' : '' }}>Maintenance Mode</option>
                            <option value="closed" {{ (old('store_status', $settings->store_status ?? '') == 'closed') ? 'selected' : '' }}>Temporarily Closed</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Actions -->
        <div class="flex items-center justify-between">
            <div class="text-sm text-gray-500">
                <span class="text-red-500">*</span> Required fields
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
