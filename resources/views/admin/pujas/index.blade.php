<!-- resources/views/admin/pujas/index.blade.php -->
@extends('layouts.admin')

@section('title', 'Pujas Management')
@section('breadcrumb', 'Pujas')

@section('content')
    <div class="px-4 sm:px-6 lg:px-8 py-8">
        <!-- Page Header -->
        <div class="mb-8">
            <div class="sm:flex sm:items-center sm:justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Pujas Management</h1>
                    <p class="mt-2 text-sm text-gray-700">Manage puja ceremonies and their details</p>
                </div>
                <div class="mt-4 sm:mt-0">
                    <a href="{{ route('admin.pujas.create') }}"
                        class="inline-flex items-center px-4 py-2 bg-purple-600 border border-transparent rounded-lg text-sm font-medium text-white hover:bg-purple-700">
                        <i data-lucide="plus" class="w-4 h-4 mr-2"></i>
                        Add New Puja
                    </a>
                </div>
            </div>
        </div>

        <!-- Dynamic Stats -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-xl p-6 border border-gray-200 shadow-sm">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-orange-100 rounded-xl flex items-center justify-center">
                            <i data-lucide="flame" class="w-6 h-6 text-orange-600"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <div class="text-sm font-medium text-gray-600">Total Pujas</div>
                        <div class="text-2xl font-bold text-gray-900">{{ number_format($totalPujas) }}</div>
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
                        <div class="text-sm font-medium text-gray-600">Active Pujas</div>
                        <div class="text-2xl font-bold text-gray-900">{{ number_format($activePujas) }}</div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl p-6 border border-gray-200 shadow-sm">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-red-100 rounded-xl flex items-center justify-center">
                            <i data-lucide="x-circle" class="w-6 h-6 text-red-600"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <div class="text-sm font-medium text-gray-600">Inactive Pujas</div>
                        <div class="text-2xl font-bold text-gray-900">{{ number_format($inactivePujas) }}</div>
                    </div>
                </div>
            </div>

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
        </div>

        <!-- Pujas Table -->
        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden shadow-sm">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Puja
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Vendor</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kits
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($pujas as $puja)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        @if ($puja->image)
                                            <img class="h-10 w-10 rounded object-cover"
                                                src="{{ $puja->getImageUrlAttribute() }}" alt="{{ $puja->name }}">
                                        @else
                                            <div class="h-10 w-10 bg-orange-100 rounded flex items-center justify-center">
                                                <i data-lucide="flame" class="w-5 h-5 text-orange-600"></i>
                                            </div>
                                        @endif
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $puja->name }}</div>
                                            <div class="text-sm text-gray-500">{{ Str::limit($puja->description, 50) }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                {{-- NEW: Vendor Column --}}
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if ($puja->vendor_id && $puja->vendor)
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0">
                                                @if ($puja->vendor->vendorProfile && $puja->vendor->vendorProfile->store_logo)
                                                    <img class="h-8 w-8 rounded-full"
                                                        src="{{ asset('storage/' . $puja->vendor->vendorProfile->store_logo) }}"
                                                        alt="">
                                                @else
                                                    <div
                                                        class="h-8 w-8 rounded-full bg-indigo-100 flex items-center justify-center">
                                                        <i data-lucide="user" class="w-4 h-4 text-indigo-600"></i>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="ml-3">
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ $puja->vendor->vendorProfile->business_name ?? $puja->vendor->first_name . ' ' . $puja->vendor->last_name }}
                                                </div>
                                                <div class="text-xs text-gray-500">
                                                    <span
                                                        class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                    {{ $puja->vendor->vendorProfile->status === 'approved' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                                        {{ ucfirst($puja->vendor->vendorProfile->status ?? 'Pending') }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    @else
                                        <div class="text-sm text-gray-500 italic flex items-center">
                                            <i data-lucide="building-2" class="w-4 h-4 mr-2 text-gray-400"></i>
                                            Platform Puja
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm font-medium text-gray-900">{{ $puja->kits_count }}</span>
                                    <span class="text-sm text-gray-500">kits</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $puja->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $puja->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex items-center space-x-2">
                                        <a href="{{ route('admin.pujas.show', $puja) }}"
                                            class="text-blue-600 hover:text-blue-900">
                                            <i data-lucide="eye" class="w-4 h-4"></i>
                                        </a>
                                        <a href="{{ route('admin.pujas.edit', $puja) }}"
                                            class="text-purple-600 hover:text-purple-900">
                                            <i data-lucide="edit" class="w-4 h-4"></i>
                                        </a>
                                        @if ($puja->kits_count == 0)
                                            <form action="{{ route('admin.pujas.destroy', $puja) }}" method="POST"
                                                class="inline" onsubmit="return confirm('Are you sure?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900">
                                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center">
                                    <div class="text-gray-500">
                                        <i data-lucide="flame" class="w-12 h-12 mx-auto mb-4 text-gray-300"></i>
                                        <p class="text-lg font-medium">No pujas found</p>
                                        <p class="text-sm">Create your first puja to get started.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="px-6 py-3 border-t border-gray-200 bg-gray-50">
                {{ $pujas->links() }}
            </div>
        </div>
    </div>
@endsection
