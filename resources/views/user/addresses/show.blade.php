@extends('layouts.app')

@section('title', 'Address Details - Shree Samagri')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center space-x-4 mb-4">
                <a href="{{ route('addresses.index') }}" 
                   class="inline-flex items-center text-gray-600 hover:text-gray-800">
                    <i data-lucide="arrow-left" class="w-4 h-4 mr-2"></i>
                    Back to Addresses
                </a>
            </div>
            <h1 class="text-2xl font-bold text-gray-900">Address Details</h1>
            <p class="text-gray-600 mt-1">View address information</p>
        </div>

        <!-- Address Details -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <!-- Status Badges -->
            <div class="flex items-center space-x-3 mb-6 pb-6 border-b border-gray-200">
                @if($address->is_default)
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-vibrant-pink text-white">
                        <i data-lucide="star" class="w-3 h-3 mr-1"></i>
                        Default Address
                    </span>
                @endif
                
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium 
                           {{ $address->type === 'billing' ? 'bg-blue-100 text-blue-800' : 
                              ($address->type === 'shipping' ? 'bg-green-100 text-green-800' : 'bg-purple-100 text-purple-800') }}">
                    <i data-lucide="{{ $address->type === 'billing' ? 'credit-card' : ($address->type === 'shipping' ? 'truck' : 'package') }}" 
                       class="w-3 h-3 mr-1"></i>
                    {{ ucfirst($address->type) }} Address
                </span>
            </div>

            <!-- Personal Information -->
            <div class="mb-8">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Personal Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Full Name</label>
                        <p class="text-gray-900">{{ $address->full_name }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Phone Number</label>
                        <p class="text-gray-900">{{ $address->phone }}</p>
                    </div>
                </div>
            </div>

            <!-- Address Information -->
            <div class="mb-8">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Address Information</h3>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Complete Address</label>
                        <p class="text-gray-900 leading-relaxed">{{ $address->formatted_address }}</p>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">City</label>
                            <p class="text-gray-900">{{ $address->city }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">State</label>
                            <p class="text-gray-900">{{ $address->state }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Postal Code</label>
                            <p class="text-gray-900">{{ $address->postal_code }}</p>
                        </div>
                    </div>

                    @if($address->landmark)
                    <div>
                        <label class="block text-sm font-medium text-gray-500 mb-1">Landmark</label>
                        <p class="text-gray-900">{{ $address->landmark }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Actions -->
            <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                <div class="flex space-x-4">
                    <a href="{{ route('addresses.edit', $address) }}" 
                       class="inline-flex items-center px-4 py-2 bg-vibrant-pink text-white rounded-lg hover:bg-vibrant-pink-dark transition-colors">
                        <i data-lucide="edit-2" class="w-4 h-4 mr-2"></i>
                        Edit Address
                    </a>
                    
                    @if(!$address->is_default)
                        <form action="{{ route('addresses.set-default', $address) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" 
                                    class="inline-flex items-center px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors">
                                <i data-lucide="star" class="w-4 h-4 mr-2"></i>
                                Set as Default
                            </button>
                        </form>
                    @endif
                </div>

                <form action="{{ route('addresses.destroy', $address) }}" method="POST" 
                      onsubmit="return confirm('Are you sure you want to delete this address?')" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            class="inline-flex items-center px-4 py-2 text-red-600 hover:text-red-800 hover:bg-red-50 rounded-lg transition-colors">
                        <i data-lucide="trash-2" class="w-4 h-4 mr-2"></i>
                        Delete Address
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
