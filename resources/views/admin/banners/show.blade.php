<!-- resources/views/admin/banners/show.blade.php -->
@extends('layouts.admin')

@section('title', 'Banner Details')

@section('breadcrumb', 'Banners / View Banner')

@section('content')
<div class="px-4 sm:px-6 lg:px-8 py-8">
    <!-- Page Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Banner Details</h1>
                <p class="mt-2 text-sm text-gray-700">Complete information about this banner</p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('admin.banners.edit', $banner) }}" class="inline-flex items-center px-4 py-2 bg-purple-600 border border-transparent rounded-lg text-sm font-medium text-white hover:bg-purple-700">
                    <i data-lucide="edit" class="w-4 h-4 mr-2"></i>
                    Edit Banner
                </a>
                <a href="{{ route('admin.banners.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-200">
                    <i data-lucide="arrow-left" class="w-4 h-4 mr-2"></i>
                    Back to Banners
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Banner Image -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Banner Image</h3>
                </div>
                <div class="p-6">
                    @if($banner->image_path)
                        <img src="{{ $banner->image_url }}" alt="{{ $banner->alt_text }}" class="w-full h-auto rounded-lg">
                    @else
                        <div class="w-full h-64 bg-gray-200 rounded-lg flex items-center justify-center">
                            <i data-lucide="image" class="w-16 h-16 text-gray-400"></i>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Banner Information -->
        <div class="space-y-6">
            <!-- Basic Info -->
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Basic Information</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <h4 class="text-sm font-medium text-gray-500">Title</h4>
                        <p class="mt-1 text-sm text-gray-900">{{ $banner->title }}</p>
                    </div>
                    @if($banner->description)
                        <div>
                            <h4 class="text-sm font-medium text-gray-500">Description</h4>
                            <p class="mt-1 text-sm text-gray-900">{{ $banner->description }}</p>
                        </div>
                    @endif
                    <div>
                        <h4 class="text-sm font-medium text-gray-500">Alt Text</h4>
                        <p class="mt-1 text-sm text-gray-900">{{ $banner->alt_text }}</p>
                    </div>
                    @if($banner->link_url)
                        <div>
                            <h4 class="text-sm font-medium text-gray-500">Link URL</h4>
                            <a href="{{ $banner->link_url }}" target="_blank" class="mt-1 text-sm text-purple-600 hover:text-purple-500 break-all">
                                {{ $banner->link_url }}
                                <i data-lucide="external-link" class="w-3 h-3 inline ml-1"></i>
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Settings -->
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Settings</h3>
                </div>
                <div class="p-6 space-y-4">
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-gray-500">Status</span>
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $banner->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $banner->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-gray-500">Sort Order</span>
                        <span class="text-sm font-semibold text-gray-900">{{ $banner->sort_order }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-gray-500">Created</span>
                        <span class="text-sm text-gray-900">{{ $banner->created_at->format('M d, Y') }}</span>
                    </div>
                    <div class="flex items-center justify-between">
                        <span class="text-sm font-medium text-gray-500">Updated</span>
                        <span class="text-sm text-gray-900">{{ $banner->updated_at->format('M d, Y') }}</span>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Actions</h3>
                </div>
                <div class="p-6 space-y-3">
                    <form action="{{ route('admin.banners.toggle-status', $banner) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 border border-transparent rounded-lg text-sm font-medium text-white bg-{{ $banner->is_active ? 'orange' : 'green' }}-600 hover:bg-{{ $banner->is_active ? 'orange' : 'green' }}-700">
                            <i data-lucide="{{ $banner->is_active ? 'eye-off' : 'eye' }}" class="w-4 h-4 mr-2"></i>
                            {{ $banner->is_active ? 'Deactivate' : 'Activate' }} Banner
                        </button>
                    </form>
                    
                    <form action="{{ route('admin.banners.destroy', $banner) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this banner?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 border border-red-300 rounded-lg text-sm font-medium text-red-700 bg-white hover:bg-red-50">
                            <i data-lucide="trash-2" class="w-4 h-4 mr-2"></i>
                            Delete Banner
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
