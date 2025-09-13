<!-- resources/views/admin/puja-kits/index.blade.php -->
@extends('layouts.admin')

@section('title', 'Puja Kits')
@section('breadcrumb', 'Puja Kits')

@section('content')
<div class="px-4 sm:px-6 lg:px-8 py-8">
    <!-- Page Header -->
    <div class="mb-8">
        <div class="sm:flex sm:items-center sm:justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Puja Kits Management</h1>
                <p class="mt-2 text-sm text-gray-700">Create and manage curated puja kits for different occasions</p>
            </div>
            <div class="mt-4 sm:mt-0">
                <a href="{{ route('admin.puja-kits.create') }}" class="inline-flex items-center px-4 py-2 bg-purple-600 border border-transparent rounded-lg text-sm font-medium text-white hover:bg-purple-700">
                    <i data-lucide="plus" class="w-4 h-4 mr-2"></i>
                    Create New Kit
                </a>
            </div>
        </div>
    </div>

    <!-- Dynamic Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-xl p-6 border border-gray-200 shadow-sm">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                        <i data-lucide="package" class="w-6 h-6 text-blue-600"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <div class="text-sm font-medium text-gray-600">Total Kits</div>
                    <div class="text-2xl font-bold text-gray-900">{{ number_format($totalKits) }}</div>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl p-6 border border-gray-200 shadow-sm">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                        <i data-lucide="check-circle" class="w-6 h-6 text-green-600"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <div class="text-sm font-medium text-gray-600">Active Kits</div>
                    <div class="text-2xl font-bold text-gray-900">{{ number_format($activeKits) }}</div>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl p-6 border border-gray-200 shadow-sm">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-yellow-100 rounded-xl flex items-center justify-center">
                        <i data-lucide="indian-rupee" class="w-6 h-6 text-yellow-600"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <div class="text-sm font-medium text-gray-600">Total Sales</div>
                    <div class="text-2xl font-bold text-gray-900">₹{{ number_format($totalSales) }}</div>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl p-6 border border-gray-200 shadow-sm">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                        <i data-lucide="trophy" class="w-6 h-6 text-purple-600"></i>
                    </div>
                </div>
                <div class="ml-4">
                    <div class="text-sm font-medium text-gray-600">Best Seller</div>
                    <div class="text-2xl font-bold text-gray-900">
                        {{ optional(optional($bestSeller)->products->first())->name ?? 'N/A' }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Kits Table -->
    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kit</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Vendor</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pujas</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Products</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Discount</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($kits as $kit)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                @if($kit->products->count() > 0 && $kit->products->first()->featured_image)
                                    <img class="h-10 w-10 rounded object-cover" src="{{ asset('storage/' . $kit->image) }}" alt="{{ $kit->kit_name }}">
                                @else
                                    <div class="h-10 w-10 bg-purple-100 rounded flex items-center justify-center">
                                        <i data-lucide="package" class="w-5 h-5 text-purple-600"></i>
                                    </div>
                                @endif
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $kit->kit_name  }}</div>
                                    <div class="text-sm text-gray-500">{{ Str::limit($kit->kit_description, 50) }}</div>
                                </div>
                            </div>
                        </td>
                         {{-- NEW: Vendor Column --}}
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($kit->vendor_id && $kit->vendor)
                                <div class="flex items-center">
                                    <div class="flex-shrink-0">
                                        @if($kit->vendor->vendorProfile && $kit->vendor->vendorProfile->store_logo)
                                            <img class="h-8 w-8 rounded-full" src="{{ asset('storage/' . $kit->vendor->vendorProfile->store_logo) }}" alt="">
                                        @else
                                            <div class="h-8 w-8 rounded-full bg-indigo-100 flex items-center justify-center">
                                                <i data-lucide="user" class="w-4 h-4 text-indigo-600"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="ml-3">
                                        <div class="text-sm font-medium text-gray-900">
                                            {{ $kit->vendor->vendorProfile->business_name ?? ($kit->vendor->first_name . ' ' . $kit->vendor->last_name) }}
                                        </div>
                                        <div class="text-xs text-gray-500">
                                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                                {{ $kit->vendor->vendorProfile->status === 'approved' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                                {{ ucfirst($kit->vendor->vendorProfile->status ?? 'Pending') }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="text-sm text-gray-500 italic flex items-center">
                                    <i data-lucide="building-2" class="w-4 h-4 mr-2 text-gray-400"></i>
                                    Platform Kit
                                </div>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm font-medium text-gray-900">
                                @if($kit->pujas->count() > 0)
                                    {{ $kit->pujas->pluck('name')->implode(', ') }}
                                @else
                                    <span class="text-gray-400">No pujas assigned</span>
                                @endif
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm font-medium text-gray-900">
                                @if($kit->products->count() > 0)
                                    {{ $kit->products->count() }} {{ Str::plural('product', $kit->products->count()) }}
                                @else
                                    <span class="text-gray-400">No products</span>
                                @endif
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($kit->discount_percentage > 0)
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                    {{ $kit->discount_percentage }}% OFF
                                </span>
                            @else
                                <span class="text-sm text-gray-500">No discount</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $kit->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $kit->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex items-center space-x-2">
                                <a href="{{ route('admin.puja-kits.show', $kit) }}" class="text-blue-600 hover:text-blue-900">
                                    <i data-lucide="eye" class="w-4 h-4"></i>
                                </a>
                                <a href="{{ route('admin.puja-kits.edit', $kit) }}" class="text-purple-600 hover:text-purple-900">
                                    <i data-lucide="edit" class="w-4 h-4"></i>
                                </a>
                                <form action="{{ route('admin.puja-kits.destroy', $kit) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900">
                                        <i data-lucide="trash-2" class="w-4 h-4"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center">
                            <div class="text-gray-500">
                                <i data-lucide="package" class="w-12 h-12 mx-auto mb-4 text-gray-300"></i>
                                <p class="text-lg font-medium">No puja kits found</p>
                                <p class="text-sm">Create your first puja kit to get started.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="px-6 py-3 border-t border-gray-200 bg-gray-50">
            {{ $kits->links() }}
        </div>
    </div>
</div>
@endsection
