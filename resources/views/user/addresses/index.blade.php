@extends('layouts.app')

@section('title', 'My Addresses - Ritiyana')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">My Addresses</h1>
                <p class="text-gray-600 mt-1">Manage your delivery addresses</p>
            </div>
            <a href="{{ route('addresses.create') }}" 
               class="inline-flex items-center px-4 py-2 bg-vibrant-pink text-white rounded-lg hover:bg-vibrant-pink-dark transition-colors">
                <i data-lucide="plus" class="w-4 h-4 mr-2"></i>
                Add New Address
            </a>
        </div>

        @if(session('success'))
            <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
                <div class="flex">
                    <i data-lucide="check-circle" class="w-5 h-5 text-green-400 mr-2 mt-0.5"></i>
                    <span class="text-green-700">{{ session('success') }}</span>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-6">
                <div class="flex">
                    <i data-lucide="x-circle" class="w-5 h-5 text-red-400 mr-2 mt-0.5"></i>
                    <span class="text-red-700">{{ session('error') }}</span>
                </div>
            </div>
        @endif

        <!-- Addresses List -->
        @if($addresses->isEmpty())
            <div class="bg-white rounded-lg shadow-sm p-12 text-center">
                <i data-lucide="map-pin" class="w-16 h-16 text-gray-300 mx-auto mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No addresses found</h3>
                <p class="text-gray-500 mb-6">You haven't added any addresses yet. Add your first address to get started.</p>
                <a href="{{ route('addresses.create') }}" 
                   class="inline-flex items-center px-4 py-2 bg-vibrant-pink text-white rounded-lg hover:bg-vibrant-pink-dark">
                    <i data-lucide="plus" class="w-4 h-4 mr-2"></i>
                    Add Address
                </a>
            </div>
        @else
            <div class="grid gap-6 md:grid-cols-2">
                @foreach($addresses as $address)
                <div class="bg-white rounded-lg shadow-sm border {{ $address->is_default ? 'border-vibrant-pink' : 'border-gray-200' }} p-6 relative">
                    <!-- Default Badge -->
                    @if($address->is_default)
                        <div class="absolute top-4 right-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-vibrant-pink text-white">
                                <i data-lucide="star" class="w-3 h-3 mr-1"></i>
                                Default
                            </span>
                        </div>
                    @endif

                    <!-- Address Type Badge -->
                    <div class="flex items-center justify-between mb-4">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                   {{ $address->type === 'billing' ? 'bg-blue-100 text-blue-800' : 
                                      ($address->type === 'shipping' ? 'bg-green-100 text-green-800' : 'bg-purple-100 text-purple-800') }}">
                            <i data-lucide="{{ $address->type === 'billing' ? 'credit-card' : ($address->type === 'shipping' ? 'truck' : 'package') }}" 
                               class="w-3 h-3 mr-1"></i>
                            {{ ucfirst($address->type) }}
                        </span>
                    </div>

                    <!-- Address Details -->
                    <div class="space-y-2 mb-6">
                        <h3 class="font-semibold text-gray-900">{{ $address->full_name }}</h3>
                        <p class="text-gray-600 text-sm leading-relaxed">{{ $address->formatted_address }}</p>
                        <p class="text-gray-500 text-sm">
                            <i data-lucide="phone" class="w-4 h-4 inline mr-1"></i>
                            {{ $address->phone }}
                        </p>
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center justify-between">
                        <div class="flex space-x-2">
                            <a href="{{ route('addresses.edit', $address) }}" 
                               class="text-sm text-vibrant-pink hover:text-vibrant-pink-dark font-medium">
                                <i data-lucide="edit-2" class="w-4 h-4 inline mr-1"></i>
                                Edit
                            </a>
                            
                            @if(!$address->is_default)
                                <form action="{{ route('addresses.set-default', $address) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" 
                                            class="text-sm text-gray-600 hover:text-gray-800 font-medium">
                                        <i data-lucide="star" class="w-4 h-4 inline mr-1"></i>
                                        Set Default
                                    </button>
                                </form>
                            @endif
                        </div>

                        <form action="{{ route('addresses.destroy', $address) }}" method="POST" 
                              onsubmit="return confirm('Are you sure you want to delete this address?')" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="text-sm text-red-600 hover:text-red-800 font-medium">
                                <i data-lucide="trash-2" class="w-4 h-4 inline mr-1"></i>
                                Delete
                            </button>
                        </form>
                    </div>
                </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection
