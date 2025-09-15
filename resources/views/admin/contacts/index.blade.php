<!-- resources/views/admin/contacts/index.blade.php -->
@extends('layouts.admin')

@section('title', 'Contacts Management')
@section('breadcrumb', 'Contacts')

@section('content')
<div class="p-6">
    <!-- Page Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Contacts Management</h1>
                <p class="text-gray-600 mt-1">View and manage all customer inquiries</p>
            </div>
            <div class="flex items-center space-x-3">
                <button class="flex items-center px-4 py-2 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                    <i data-lucide="filter" class="w-4 h-4 mr-2"></i>
                    Filter
                </button>
                <button class="flex items-center px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700">
                    <i data-lucide="download" class="w-4 h-4 mr-2"></i>
                    Export
                </button>
            </div>
        </div>
    </div>

    <!-- Contact Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-xl p-6 border border-gray-200">
            <div class="text-center">
                <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center mx-auto mb-3">
                    <i data-lucide="mail" class="w-6 h-6 text-blue-600"></i>
                </div>
                <p class="text-2xl font-bold text-gray-900">{{ $totalContacts ?? 0 }}</p>
                <p class="text-sm text-gray-600">Total Messages</p>
            </div>
        </div>
        
        <div class="bg-white rounded-xl p-6 border border-gray-200">
            <div class="text-center">
                <div class="w-12 h-12 bg-yellow-100 rounded-xl flex items-center justify-center mx-auto mb-3">
                    <i data-lucide="clock" class="w-6 h-6 text-yellow-600"></i>
                </div>
                <p class="text-2xl font-bold text-yellow-600">{{ $pendingContacts ?? 0 }}</p>
                <p class="text-sm text-gray-600">Pending</p>
            </div>
        </div>
        
        <div class="bg-white rounded-xl p-6 border border-gray-200">
            <div class="text-center">
                <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center mx-auto mb-3">
                    <i data-lucide="check-circle" class="w-6 h-6 text-green-600"></i>
                </div>
                <p class="text-2xl font-bold text-green-600">{{ $resolvedContacts ?? 0 }}</p>
                <p class="text-sm text-gray-600">Resolved</p>
            </div>
        </div>
    </div>

    <!-- Contacts List -->
    <div class="bg-white rounded-xl border border-gray-200">
        <div class="p-6 border-b border-gray-200">
            <h3 class="text-lg font-semibold text-gray-900">Recent Contacts</h3>
        </div>
        
        <div class="divide-y divide-gray-200">
            @forelse($contacts as $contact)
            <div class="p-6 hover:bg-gray-50">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                            <span class="text-purple-600 font-semibold text-sm">#{{ $contact->id }}</span>
                        </div>
                        <div>
                            <h4 class="font-semibold text-gray-900">{{ $contact->first_name }} {{ $contact->last_name }}</h4>
                            <p class="text-sm text-gray-500">{{ $contact->email }} • {{ $contact->phone }}</p>
                            <p class="text-sm text-gray-400">Message: {{ Str::limit($contact->message, 50) }}</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-6">
                        <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full 
                            {{ $contact->status == 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800' }}">
                            {{ ucfirst($contact->status) }}
                        </span>
                        <div class="flex items-center space-x-2">
                            <a href="{{ route('admin.contacts.show', $contact->id) }}" class="p-2 text-gray-400 hover:text-gray-600 rounded-lg hover:bg-gray-100">
                                <i data-lucide="eye" class="w-4 h-4"></i>
                            </a>
                            {{-- <a href="{{ route('admin.contacts.edit', $contact->id) }}" class="p-2 text-gray-400 hover:text-gray-600 rounded-lg hover:bg-gray-100">
                                <i data-lucide="edit" class="w-4 h-4"></i>
                            </a> --}}
                            {{-- <form method="POST" action="{{ route('admin.contacts.destroy', $contact->id) }}" onsubmit="return confirm('Are you sure?')">
                                @csrf
                                @method('DELETE')
                                <button class="p-2 text-gray-400 hover:text-red-600 rounded-lg hover:bg-red-50">
                                    <i data-lucide="trash" class="w-4 h-4"></i>
                                </button>
                            </form> --}}
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="p-6 text-center text-gray-500">
                No contacts found.
            </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
