<!-- resources/views/admin/banners/index.blade.php -->
@extends('layouts.admin')

@section('title', 'Banners Management')

@section('breadcrumb', 'Banners')

@section('content')
<div class="px-4 sm:px-6 lg:px-8 py-8">
    <!-- Page Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Banner Management</h1>
                <p class="mt-2 text-sm text-gray-700">Manage promotional banners for your website</p>
            </div>
            <a href="{{ route('admin.banners.create') }}" class="inline-flex items-center px-4 py-2 bg-purple-600 border border-transparent rounded-lg text-sm font-medium text-white hover:bg-purple-700">
                <i data-lucide="plus" class="w-4 h-4 mr-2"></i>
                Add Banner
            </a>
        </div>
    </div>

    <!-- Success Message -->
    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6">
            <div class="flex">
                <i data-lucide="check-circle" class="w-5 h-5 mr-2 mt-0.5"></i>
                <div>
                    {{ session('success') }}
                </div>
            </div>
        </div>
    @endif

    <!-- Banners Table -->
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        @if($banners->count() > 0)
            <!-- Stats Header -->
            <div class="px-8 py-6 border-b border-gray-200">
                <div class="flex items-center">
                    <i data-lucide="image" class="w-6 h-6 text-purple-600"></i>
                    <div class="ml-3">
                        <h3 class="text-lg font-medium text-gray-900">All Banners</h3>
                        <p class="text-sm text-gray-600">{{ $banners->count() }} banners total</p>
                    </div>
                </div>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Banner</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Link</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($banners as $banner)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    @if($banner->image_path)
                                        <img class="h-16 w-24 rounded-lg object-cover mr-4" src="{{ $banner->image_url }}" alt="{{ $banner->alt_text }}">
                                    @else
                                        <div class="h-16 w-24 bg-gray-200 rounded-lg mr-4 flex items-center justify-center">
                                            <i data-lucide="image" class="w-6 h-6 text-gray-400"></i>
                                        </div>
                                    @endif
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ $banner->title }}</div>
                                        <div class="text-sm text-gray-500">{{ Str::limit($banner->description, 50) }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm font-medium text-gray-900">{{ $banner->sort_order }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($banner->link_url)
                                    <a href="{{ $banner->link_url }}" target="_blank" class="text-purple-600 hover:text-purple-500 text-sm">
                                        <i data-lucide="external-link" class="w-4 h-4 inline mr-1"></i>
                                        View Link
                                    </a>
                                @else
                                    <span class="text-gray-400 text-sm">No link</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full {{ $banner->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $banner->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex items-center space-x-3">
                                    <a href="{{ route('admin.banners.show', $banner) }}" class="text-blue-600 hover:text-blue-500">
                                        <i data-lucide="eye" class="w-4 h-4"></i>
                                    </a>
                                    <a href="{{ route('admin.banners.edit', $banner) }}" class="text-yellow-600 hover:text-yellow-500">
                                        <i data-lucide="edit" class="w-4 h-4"></i>
                                    </a>
                                    <form action="{{ route('admin.banners.toggle-status', $banner) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="text-{{ $banner->is_active ? 'orange' : 'green' }}-600 hover:text-{{ $banner->is_active ? 'orange' : 'green' }}-500">
                                            <i data-lucide="{{ $banner->is_active ? 'eye-off' : 'eye' }}" class="w-4 h-4"></i>
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.banners.destroy', $banner) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-500">
                                            <i data-lucide="trash-2" class="w-4 h-4"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-12">
                <i data-lucide="image" class="w-12 h-12 text-gray-400 mx-auto mb-4"></i>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No banners found</h3>
                <p class="text-gray-600 mb-6">Create your first banner to get started.</p>
                <a href="{{ route('admin.banners.create') }}" class="inline-flex items-center px-4 py-2 bg-purple-600 border border-transparent rounded-lg text-sm font-medium text-white hover:bg-purple-700">
                    <i data-lucide="plus" class="w-4 h-4 mr-2"></i>
                    Add Banner
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
