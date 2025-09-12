<!-- resources/views/admin/puja-kits/show.blade.php -->
@extends('layouts.admin')

@section('title', 'Puja Kit Details')
@section('breadcrumb', 'Puja Kits / View')

@section('content')
<div class="px-4 sm:px-6 lg:px-8 py-8">
    <!-- Page Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Puja Kit Details</h1>
                <p class="mt-2 text-sm text-gray-700">Complete information about this puja kit</p>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('admin.puja-kits.edit', $pujaKit) }}" class="inline-flex items-center px-4 py-2 bg-purple-600 border border-transparent rounded-lg text-sm font-medium text-white hover:bg-purple-700">
                    <i data-lucide="edit" class="w-4 h-4 mr-2"></i>
                    Edit Kit
                </a>
                <a href="{{ route('admin.puja-kits.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-200">
                    <i data-lucide="arrow-left" class="w-4 h-4 mr-2"></i>
                    Back to Kits
                </a>
            </div>
        </div>
    </div>

    <!-- Kit Profile Card -->
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm mb-8">
        <div class="p-8">
            <div class="flex flex-col lg:flex-row items-start lg:items-center space-y-6 lg:space-y-0 lg:space-x-8">
                <!-- Product Image -->
                <div class="flex-shrink-0">
                    @php
                        $firstProduct = $pujaKit->products->first();
                    @endphp
                    @if($firstProduct && $firstProduct->featured_image)
                        <img class="h-48 w-48 rounded-xl object-cover border-4 border-white shadow-lg" 
                             src="{{ asset('storage/' . $firstProduct->featured_image) }}" 
                             alt="{{ $firstProduct->name }}">
                    @else
                        <div class="h-48 w-48 rounded-xl bg-gradient-to-br from-indigo-500 to-purple-500 flex items-center justify-center shadow-lg">
                            <i data-lucide="package" class="w-24 h-24 text-white"></i>
                        </div>
                    @endif
                </div>

                <!-- Kit Info -->
                <div class="flex-1">
                    <h2 class="text-2xl font-bold text-gray-900">
                        {{ $pujaKit->kit_name ?? 'Puja Kit #' . $pujaKit->id }}
                    </h2>
                    <p class="text-lg text-gray-600 mb-2">
        Slug: {{ $pujaKit->slug }}
    </p>
                    <p class="text-lg text-gray-600 mb-2">
                        For {{ $pujaKit->pujas->pluck('name')->implode(', ') ?: 'Multiple Pujas' }}
                    </p>
                    
                    <!-- Price Information -->
                    @if($pujaKit->products->count() > 0)
                        <div class="mb-4">
                            <div class="flex items-center space-x-3">
                                <span class="text-3xl font-bold text-green-600">₹{{ number_format($pujaKit->total_price, 2) }}</span>
                                @if($pujaKit->discount_percentage > 0)
                                    @php
                                        $originalPrice = $pujaKit->products->sum(function($product) {
                                            return ($product->pivot->price ?? $product->price) * $product->pivot->quantity;
                                        });
                                    @endphp
                                    <span class="text-lg text-gray-500 line-through">₹{{ number_format($originalPrice, 2) }}</span>
                                    <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full bg-red-100 text-red-800">
                                        {{ $pujaKit->discount_percentage }}% OFF
                                    </span>
                                @endif
                            </div>
                        </div>
                    @endif
                    
                    <!-- Status -->
                    <div class="flex items-center space-x-3 mb-4">
                        <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full 
                            {{ $pujaKit->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            <i data-lucide="{{ $pujaKit->is_active ? 'check-circle' : 'x-circle' }}" class="w-4 h-4 mr-1"></i>
                            {{ $pujaKit->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>

                    <!-- Description -->
                    @if($pujaKit->kit_description)
                        <div class="mb-4">
                            <h4 class="font-medium text-gray-900 mb-2">Kit Description</h4>
                            <p class="text-gray-700">{{ $pujaKit->kit_description }}</p>
                        </div>
                    @endif

                    <!-- Quick Stats -->
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4 text-sm">
                        <div>
                            <p class="text-gray-500">Discount</p>
                            <p class="font-semibold text-lg">{{ $pujaKit->discount_percentage }}%</p>
                        </div>
                        <div>
                            <p class="text-gray-500">Created</p>
                            <p class="font-semibold text-lg">{{ $pujaKit->created_at->format('M Y') }}</p>
                        </div>
                        <div>
                            <p class="text-gray-500">Updated</p>
                            <p class="font-semibold text-lg">{{ $pujaKit->updated_at->format('M j') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Detailed Information -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Associated Pujas -->
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                    <i data-lucide="flame" class="w-5 h-5 mr-2"></i>
                    Associated Pujas ({{ $pujaKit->pujas->count() }})
                </h3>
            </div>
            <div class="p-6">
                @if($pujaKit->pujas->count() > 0)
                    <div class="space-y-4">
                        @foreach($pujaKit->pujas as $puja)
                            <div class="flex items-start space-x-4 p-4 bg-orange-50 rounded-lg">
                                @if($puja->image)
                                    <img class="h-12 w-12 rounded-lg object-cover" src="{{ asset('storage/' . $puja->image) }}" alt="{{ $puja->name }}">
                                @else
                                    <div class="h-12 w-12 bg-orange-100 rounded-lg flex items-center justify-center">
                                        <i data-lucide="flame" class="w-6 h-6 text-orange-600"></i>
                                    </div>
                                @endif
                                <div class="flex-1">
                                    <h4 class="font-medium text-gray-900">{{ $puja->name }}</h4>
                                    @if($puja->description)
                                        <p class="text-sm text-gray-600 mt-1">{{ Str::limit($puja->description, 80) }}</p>
                                    @endif
                                    <div class="mt-2">
                                        <a href="{{ route('admin.pujas.show', $puja) }}" class="text-purple-600 text-sm hover:text-purple-700">
                                            View Details →
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <i data-lucide="flame" class="w-12 h-12 mx-auto text-gray-300 mb-4"></i>
                        <p class="text-gray-500">No associated pujas found</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Associated Products -->
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm">
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                    <i data-lucide="box" class="w-5 h-5 mr-2"></i>
                    Associated Products ({{ $pujaKit->products->count() }})
                </h3>
            </div>
            <div class="p-6">
                @if($pujaKit->products->count() > 0)
                    <div class="space-y-4">
                        @foreach($pujaKit->products as $product)
                            <div class="flex items-start space-x-4 p-4 bg-blue-50 rounded-lg">
                                @if($product->featured_image)
                                    <img class="h-12 w-12 rounded-lg object-cover" src="{{ asset('storage/' . $product->featured_image) }}" alt="{{ $product->name }}">
                                @else
                                    <div class="h-12 w-12 bg-blue-100 rounded-lg flex items-center justify-center">
                                        <i data-lucide="package" class="w-6 h-6 text-blue-600"></i>
                                    </div>
                                @endif
                                <div class="flex-1">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <h4 class="font-medium text-gray-900">{{ $product->name }}</h4>
                                            <p class="text-sm text-gray-500">SKU: {{ $product->sku }}</p>
                                            <p class="text-sm text-gray-600">Quantity: {{ $product->pivot->quantity }}</p>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-sm font-semibold text-gray-900">
                                                ₹{{ number_format(($product->pivot->price ?? $product->price) * $product->pivot->quantity, 2) }}
                                            </p>
                                            <p class="text-xs text-gray-500">
                                                ₹{{ number_format($product->pivot->price ?? $product->price, 2) }} each
                                            </p>
                                        </div>
                                    </div>
                                    <div class="mt-2">
                                        <a href="{{ route('admin.products.show', $product) }}" class="text-purple-600 text-sm hover:text-purple-700">
                                            View Product →
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <i data-lucide="box" class="w-12 h-12 mx-auto text-gray-300 mb-4"></i>
                        <p class="text-gray-500">No associated products found</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Included Items -->
    <div class="mt-8 bg-white rounded-2xl border border-gray-200 shadow-sm">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                <i data-lucide="list" class="w-5 h-5 mr-2"></i>
                Additional Included Items
            </h3>
        </div>
        <div class="p-6">
            @php
                // Safe handling of included_items JSON field
                $includedItems = $pujaKit->included_items;
                if (is_string($includedItems)) {
                    $includedItems = json_decode($includedItems, true) ?: [];
                }
                $includedItems = is_array($includedItems) ? $includedItems : [];
            @endphp
            
            @if(count($includedItems) > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                    @foreach($includedItems as $item)
                        <div class="flex items-center p-3 bg-indigo-50 rounded-lg">
                            <i data-lucide="check" class="w-4 h-4 text-indigo-600 mr-3 flex-shrink-0"></i>
                            <span class="text-gray-900 text-sm">{{ $item }}</span>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8">
                    <i data-lucide="list" class="w-12 h-12 mx-auto text-gray-300 mb-4"></i>
                    <p class="text-gray-500">No additional items specified for this kit</p>
                    <p class="text-xs text-gray-400 mt-1">Only the products listed above are included</p>
                </div>
            @endif
        </div>
    </div>


    {{-- Add this vendor section after your existing kit information --}}
@if($pujaKit->vendor_id && $pujaKit->vendor)
<div class="mt-6 p-6 bg-white border border-gray-200 rounded-lg">
    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
        <i data-lucide="user" class="w-5 h-5 mr-2"></i>
        Vendor Information
    </h3>
    
    <div class="flex items-start space-x-4">
        <div class="flex-shrink-0">
            @if($pujaKit->vendor->vendorProfile && $pujaKit->vendor->vendorProfile->store_logo)
                <img class="h-16 w-16 rounded-lg object-cover" src="{{ asset('storage/' . $pujaKit->vendor->vendorProfile->store_logo) }}" alt="">
            @else
                <div class="h-16 w-16 bg-indigo-100 rounded-lg flex items-center justify-center">
                    <i data-lucide="user" class="w-8 h-8 text-indigo-600"></i>
                </div>
            @endif
        </div>
        
        <div class="flex-1">
            <h4 class="font-medium text-gray-900">
                {{ $pujaKit->vendor->vendorProfile->business_name ?? ($pujaKit->vendor->first_name . ' ' . $pujaKit->vendor->last_name) }}
            </h4>
            <p class="text-sm text-gray-600 mb-2">{{ $pujaKit->vendor->email }}</p>
            
            @if($pujaKit->vendor->vendorProfile)
                <div class="space-y-2">
                    <div class="flex items-center text-sm">
                        <span class="text-gray-500 w-20">Status:</span>
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                            {{ $pujaKit->vendor->vendorProfile->status === 'approved' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                            {{ ucfirst($pujaKit->vendor->vendorProfile->status) }}
                        </span>
                    </div>
                    
                    <div class="flex items-center text-sm">
                        <span class="text-gray-500 w-20">Type:</span>
                        <span class="text-gray-900">{{ ucfirst($pujaKit->vendor->vendorProfile->business_type ?? 'Individual') }}</span>
                    </div>
                    
                    @if($pujaKit->vendor->vendorProfile->business_phone)
                    <div class="flex items-center text-sm">
                        <span class="text-gray-500 w-20">Phone:</span>
                        <span class="text-gray-900">{{ $pujaKit->vendor->vendorProfile->business_phone }}</span>
                    </div>
                    @endif

                    <div class="flex items-center text-sm">
                        <span class="text-gray-500 w-20">Commission:</span>
                        <span class="text-gray-900">{{ $pujaKit->vendor->vendorProfile->commission_rate ?? 8 }}%</span>
                    </div>
                </div>
            @endif
            
            <div class="mt-4">
                <a href="{{ route('admin.vendors.show', $pujaKit->vendor) }}" 
                   class="inline-flex items-center text-indigo-600 hover:text-indigo-700">
                    <i data-lucide="external-link" class="w-4 h-4 mr-1"></i>
                    View Vendor Profile
                </a>
            </div>
        </div>
    </div>
</div>
@endif
</div>
@endsection
