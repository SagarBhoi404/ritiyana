@extends('layouts.admin')

@section('title', 'Contact Message Details')
@section('breadcrumb', 'Contacts')

@section('content')
<div class="p-6">
    <!-- Page Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Contact Message #{{ $contact->id }}</h1>
                <p class="text-gray-600 mt-1">Submitted on {{ $contact->created_at->format('d M Y, H:i') }}</p>
            </div>
            <div class="flex items-center space-x-3">
                <a href="{{ route('admin.contacts.index') }}"
                   class="flex items-center px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">
                    <i data-lucide="arrow-left" class="w-4 h-4 mr-2"></i>
                    Back
                </a>
                {{-- <form action="{{ route('admin.contacts.destroy', $contact->id) }}" method="POST"
                      onsubmit="return confirm('Are you sure you want to delete this message?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="flex items-center px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                        <i data-lucide="trash-2" class="w-4 h-4 mr-2"></i>
                        Delete
                    </button>
                </form> --}}
            </div>
        </div>
    </div>

    <!-- Contact Card -->
    <div class="bg-white rounded-xl border border-gray-200">
        <div class="p-6 border-b border-gray-200 flex justify-between items-center">
            <div>
                <h3 class="text-lg font-semibold text-gray-900">{{ $contact->first_name }} {{ $contact->last_name }}</h3>
                <p class="text-sm text-gray-500">{{ $contact->email }} @if($contact->phone) • {{ $contact->phone }} @endif</p>
            </div>
            <form action="{{ route('admin.contacts.update', $contact->id) }}" method="POST">
                @csrf
                @method('PUT')
                <select name="status" onchange="this.form.submit()" 
                        class="px-3 py-1 rounded-lg border text-sm font-medium
                            {{ $contact->status == 'resolved' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                    <option value="pending" {{ $contact->status == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="resolved" {{ $contact->status == 'resolved' ? 'selected' : '' }}>Resolved</option>
                </select>
            </form>
        </div>

        <!-- Message Content -->
        <div class="p-6 space-y-4">
            <div>
                <p class="text-sm text-gray-500 font-medium">Subject</p>
                <p class="text-gray-900 font-semibold">{{ $contact->subject }}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500 font-medium">Message</p>
                <div class="p-4 bg-gray-50 rounded-lg border text-gray-800">
                    {{ $contact->message }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
