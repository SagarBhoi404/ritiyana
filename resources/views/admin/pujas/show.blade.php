<!-- resources/views/admin/pujas/show.blade.php -->
@extends('layouts.admin')

@section('title', 'Puja Details')
@section('breadcrumb', 'Pujas / View')

@section('content')
    <div class="px-4 sm:px-6 lg:px-8 py-8">
        <!-- Page Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Puja Details</h1>
                    <p class="mt-2 text-sm text-gray-700">Complete information about this puja</p>
                </div>
                <div class="flex items-center space-x-3">
                    <a href="{{ route('admin.pujas.edit', $puja) }}"
                        class="inline-flex items-center px-4 py-2 bg-purple-600 border border-transparent rounded-lg text-sm font-medium text-white hover:bg-purple-700">
                        <i data-lucide="edit" class="w-4 h-4 mr-2"></i>
                        Edit Puja
                    </a>
                    <a href="{{ route('admin.pujas.index') }}"
                        class="inline-flex items-center px-4 py-2 bg-gray-100 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-200">
                        <i data-lucide="arrow-left" class="w-4 h-4 mr-2"></i>
                        Back to Pujas
                    </a>
                </div>
            </div>
        </div>

        <!-- Puja Profile Card -->
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm mb-8">
            <div class="p-8">
                <div class="flex flex-col lg:flex-row items-start lg:items-center space-y-6 lg:space-y-0 lg:space-x-8">
                    <!-- Puja Image -->
                    <div class="flex-shrink-0">
                        @if ($puja->image)
                            <img class="h-48 w-48 rounded-xl object-cover border-4 border-white shadow-lg"
                                src="{{ $puja->getImageUrlAttribute() }}" alt="{{ $puja->name }}">
                        @else
                            <div
                                class="h-48 w-48 rounded-xl bg-gradient-to-br from-orange-500 to-red-500 flex items-center justify-center shadow-lg">
                                <i data-lucide="flame" class="w-24 h-24 text-white"></i>
                            </div>
                        @endif
                    </div>

                    <!-- Puja Info -->
                    <div class="flex-1">
                        <h2 class="text-2xl font-bold text-gray-900">{{ $puja->name }}</h2>
                        <p class="text-lg text-gray-600 mb-2">{{ $puja->slug }}</p>

                        <!-- Status -->
                        <div class="flex items-center space-x-3 mb-4">
                            <span
                                class="inline-flex px-3 py-1 text-sm font-semibold rounded-full 
                            {{ $puja->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                <i data-lucide="{{ $puja->is_active ? 'check-circle' : 'x-circle' }}"
                                    class="w-4 h-4 mr-1"></i>
                                {{ $puja->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </div>

                        <!-- Description -->
                        @if ($puja->description)
                            <div class="mb-4">
                                <h4 class="font-medium text-gray-900 mb-2">Description</h4>
                                <p class="text-gray-700">{{ $puja->description }}</p>
                            </div>
                        @endif

                        <!-- Quick Stats -->
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-4 text-sm">
                            <div>
                                <p class="text-gray-500">Total Kits</p>
                                <p class="font-semibold text-lg">{{ $puja->pujaKits->count() }}</p>
                            </div>
                            <div>
                                <p class="text-gray-500">Created</p>
                                <p class="font-semibold text-lg">{{ $puja->created_at->format('M Y') }}</p>
                            </div>
                            <div>
                                <p class="text-gray-500">Updated</p>
                                <p class="font-semibold text-lg">{{ $puja->updated_at->format('M j') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Detailed Information -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Significance & Procedure -->
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <i data-lucide="scroll" class="w-5 h-5 mr-2"></i>
                        Significance & Procedure
                    </h3>
                </div>
                <div class="p-6">
                    @if ($puja->significance)
                        <div class="mb-6">
                            <h4 class="font-medium text-gray-900 mb-2">Significance</h4>
                            <p class="text-gray-700 whitespace-pre-line">{{ $puja->significance }}</p>
                        </div>
                    @endif

                    @if ($puja->procedure)
                        <div>
                            <h4 class="font-medium text-gray-900 mb-2">Procedure</h4>
                            <p class="text-gray-700 whitespace-pre-line">{{ $puja->procedure }}</p>
                        </div>
                    @endif

                    @if (!$puja->significance && !$puja->procedure)
                        <div class="text-center py-8">
                            <i data-lucide="scroll" class="w-12 h-12 mx-auto text-gray-300 mb-4"></i>
                            <p class="text-gray-500">No significance or procedure information available</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Auspicious Days & Required Items -->
            <div class="space-y-8">
                <!-- Auspicious Days -->
                <div class="bg-white rounded-2xl border border-gray-200 shadow-sm">
                    <div class="p-6 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                            <i data-lucide="calendar" class="w-5 h-5 mr-2"></i>
                            Auspicious Days
                        </h3>
                    </div>
                    <div class="p-6">
                        @php
                            $auspiciousDays = $puja->auspicious_days;
                            if (is_string($auspiciousDays)) {
                                $auspiciousDays = json_decode($auspiciousDays, true) ?: [];
                            }
                            $auspiciousDays = is_array($auspiciousDays) ? $auspiciousDays : [];
                        @endphp

                        @if (count($auspiciousDays) > 0)
                            <div class="space-y-2">
                                @foreach ($auspiciousDays as $day)
                                    <div class="flex items-center p-3 bg-orange-50 rounded-lg">
                                        <i data-lucide="calendar-days" class="w-4 h-4 text-orange-600 mr-3"></i>
                                        <span
                                            class="text-gray-900">{{ \Carbon\Carbon::parse($day)->format('F j, Y') }}</span>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8">
                                <i data-lucide="calendar" class="w-12 h-12 mx-auto text-gray-300 mb-4"></i>
                                <p class="text-gray-500">No auspicious days specified</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Required Items -->
                <div class="bg-white rounded-2xl border border-gray-200 shadow-sm">
                    <div class="p-6 border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                            <i data-lucide="list" class="w-5 h-5 mr-2"></i>
                            Required Items
                        </h3>
                    </div>
                    <div class="p-6">
                        @php
                            $requiredItems = $puja->required_items;
                            if (is_string($requiredItems)) {
                                $requiredItems = json_decode($requiredItems, true) ?: [];
                            }
                            $requiredItems = is_array($requiredItems) ? $requiredItems : [];
                        @endphp

                        @if (count($requiredItems) > 0)
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                                @foreach ($requiredItems as $item)
                                    <div class="flex items-center p-3 bg-green-50 rounded-lg">
                                        <i data-lucide="check" class="w-4 h-4 text-green-600 mr-3"></i>
                                        <span class="text-gray-900">{{ $item }}</span>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8">
                                <i data-lucide="list" class="w-12 h-12 mx-auto text-gray-300 mb-4"></i>
                                <p class="text-gray-500">No required items specified</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Associated Kits -->
        @if ($puja->pujaKits->count() > 0)
            <div class="mt-8 bg-white rounded-2xl border border-gray-200 shadow-sm">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <i data-lucide="package" class="w-5 h-5 mr-2"></i>
                        Associated Puja Kits ({{ $puja->pujaKits->count() }})
                    </h3>
                </div>
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach ($puja->pujaKits as $kit)
                            <div class="border border-gray-200 rounded-lg p-4">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <h4 class="font-medium text-gray-900">Kit #{{ $kit->id }}</h4>
                                        <p class="text-sm text-gray-500 mt-1">{{ Str::limit($kit->kit_description, 50) }}
                                        </p>
                                        <p class="text-sm text-gray-600 mt-1">
                                            {{ $kit->products->count() }}
                                            {{ Str::plural('product', $kit->products->count()) }}
                                        </p>
                                        @if ($kit->discount_percentage > 0)
                                            <span
                                                class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800 mt-2">
                                                {{ $kit->discount_percentage }}% OFF
                                            </span>
                                        @endif
                                    </div>
                                    <span
                                        class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $kit->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $kit->is_active ? 'Active' : 'Inactive' }}
                                    </span>
                                </div>
                                <div class="mt-4">
                                    <a href="{{ route('admin.puja-kits.show', $kit) }}"
                                        class="text-purple-600 text-sm hover:text-purple-700">
                                        View Kit Details →
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif


        {{-- Add this vendor section after your existing puja information --}}
        @if ($puja->vendor_id && $puja->vendor)
            <div class="mt-6 p-6 bg-white border border-gray-200 rounded-lg">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <i data-lucide="user" class="w-5 h-5 mr-2"></i>
                    Vendor Information
                </h3>

                <div class="flex items-start space-x-4">
                    <div class="flex-shrink-0">
                        @if ($puja->vendor->vendorProfile && $puja->vendor->vendorProfile->store_logo)
                            <img class="h-16 w-16 rounded-lg object-cover"
                                src="{{ asset('storage/' . $puja->vendor->vendorProfile->store_logo) }}" alt="">
                        @else
                            <div class="h-16 w-16 bg-indigo-100 rounded-lg flex items-center justify-center">
                                <i data-lucide="user" class="w-8 h-8 text-indigo-600"></i>
                            </div>
                        @endif
                    </div>

                    <div class="flex-1">
                        <h4 class="font-medium text-gray-900">
                            {{ $puja->vendor->vendorProfile->business_name ?? $puja->vendor->first_name . ' ' . $puja->vendor->last_name }}
                        </h4>
                        <p class="text-sm text-gray-600 mb-2">{{ $puja->vendor->email }}</p>

                        @if ($puja->vendor->vendorProfile)
                            <div class="space-y-2">
                                <div class="flex items-center text-sm">
                                    <span class="text-gray-500 w-20">Status:</span>
                                    <span
                                        class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                            {{ $puja->vendor->vendorProfile->status === 'approved' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                        {{ ucfirst($puja->vendor->vendorProfile->status) }}
                                    </span>
                                </div>

                                <div class="flex items-center text-sm">
                                    <span class="text-gray-500 w-20">Type:</span>
                                    <span
                                        class="text-gray-900">{{ ucfirst($puja->vendor->vendorProfile->business_type ?? 'Individual') }}</span>
                                </div>

                                @if ($puja->vendor->vendorProfile->business_phone)
                                    <div class="flex items-center text-sm">
                                        <span class="text-gray-500 w-20">Phone:</span>
                                        <span
                                            class="text-gray-900">{{ $puja->vendor->vendorProfile->business_phone }}</span>
                                    </div>
                                @endif
                            </div>
                        @endif

                        <div class="mt-4">
                            <a href="{{ route('admin.vendors.show', $puja->vendor) }}"
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
