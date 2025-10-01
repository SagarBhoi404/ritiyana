<!-- resources/views/admin/users/index.blade.php -->
@extends('layouts.admin')

@section('title', 'Users Management')
@section('breadcrumb', 'Users')

@section('content')
    <div class="px-4 sm:px-6 lg:px-8 py-8">
        <!-- Page Header -->
        <div class="mb-8">
            <div class="sm:flex sm:items-center sm:justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Users Management</h1>
                    <p class="mt-2 text-sm text-gray-700">Manage all users, roles and permissions across your platform</p>
                </div>
                <div class="mt-4 sm:mt-0 sm:flex sm:items-center sm:space-x-3">
                    <button
                        class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2">
                        <i data-lucide="download" class="w-4 h-4 mr-2"></i>
                        Export
                    </button>
                    <a href="{{ route('admin.users.create') }}"
                        class="inline-flex items-center px-4 py-2 bg-purple-600 border border-transparent rounded-lg text-sm font-medium text-white hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2">
                        <i data-lucide="plus" class="w-4 h-4 mr-2"></i>
                        Add User
                    </a>
                </div>
            </div>
        </div>

        <!-- Dynamic Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-xl p-6 border border-gray-200 shadow-sm">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                            <i data-lucide="users" class="w-6 h-6 text-blue-600"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <div class="text-sm font-medium text-gray-600">Total Users</div>
                        <div class="text-2xl font-bold text-gray-900">{{ number_format($totalUsers) }}</div>
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
                        <div class="text-sm font-medium text-gray-600">Active Users</div>
                        <div class="text-2xl font-bold text-gray-900">{{ number_format($activeUsers) }}</div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl p-6 border border-gray-200 shadow-sm">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                            <i data-lucide="user" class="w-6 h-6 text-purple-600"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <div class="text-sm font-medium text-gray-600">Customers</div>
                        <div class="text-2xl font-bold text-gray-900">{{ number_format($customers) }}</div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl p-6 border border-gray-200 shadow-sm">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="w-12 h-12 bg-orange-100 rounded-xl flex items-center justify-center">
                            <i data-lucide="store" class="w-6 h-6 text-orange-600"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <div class="text-sm font-medium text-gray-600">Shopkeepers</div>
                        <div class="text-2xl font-bold text-gray-900">{{ number_format($shopkeepers) }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Dynamic Filters -->
        <div class="bg-white rounded-xl border border-gray-200 p-6 mb-6">
            <form method="GET" action="{{ route('admin.users.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Search Users</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i data-lucide="search" class="w-4 h-4 text-gray-400"></i>
                        </div>
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Search by name or email..."
                            class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:ring-purple-500 focus:border-purple-500 text-sm">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Role</label>
                    <select name="role"
                        class="block w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-purple-500 focus:border-purple-500 text-sm">
                        <option value="all" {{ request('role') == 'all' ? 'selected' : '' }}>All Roles</option>
                        <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Administrator</option>
                        <option value="shopkeeper" {{ request('role') == 'shopkeeper' ? 'selected' : '' }}>Shopkeeper
                        </option>
                        <option value="user" {{ request('role') == 'user' ? 'selected' : '' }}>Customer</option>
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                    <select name="status"
                        class="block w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-purple-500 focus:border-purple-500 text-sm">
                        <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>All Status</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                        <option value="suspended" {{ request('status') == 'suspended' ? 'selected' : '' }}>Suspended
                        </option>
                    </select>
                </div>

                <div class="flex items-end">
                    <button type="submit"
                        class="w-full px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 text-sm font-medium">
                        Apply Filters
                    </button>
                </div>
            </form>
        </div>

        <!-- Dynamic Users Table -->
        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden shadow-sm">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                <div class="flex items-center">
                                    <input type="checkbox"
                                        class="rounded border-gray-300 text-purple-600 focus:ring-purple-500">
                                    <span class="ml-2">User</span>
                                </div>
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Vendor</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Last
                                Login</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse ($users as $user)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <input type="checkbox"
                                            class="rounded border-gray-300 text-purple-600 focus:ring-purple-500">
                                        <div class="ml-4 flex items-center">
                                            @if ($user->profile_image)
                                                <img class="h-10 w-10 rounded-full object-cover"
                                                    src="{{ asset('storage/' . $user->profile_image) }}"
                                                    alt="{{ $user->full_name }}">
                                            @else
                                                <div
                                                    class="h-10 w-10 rounded-full bg-purple-100 flex items-center justify-center">
                                                    <span
                                                        class="text-purple-600 font-semibold text-sm">{{ substr($user->first_name, 0, 1) }}{{ substr($user->last_name, 0, 1) }}</span>
                                                </div>
                                            @endif
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">{{ $user->full_name }}
                                                </div>
                                                <div class="text-sm text-gray-500">{{ $user->email }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @foreach ($user->roles as $role)
                                        <span
                                            class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                    @if ($role->name == 'admin') bg-red-100 text-red-800
                                    @elseif($role->name == 'shopkeeper') bg-green-100 text-green-800
                                    @else bg-blue-100 text-blue-800 @endif">
                                            {{ ucfirst($role->name) }}
                                        </span>
                                    @endforeach
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                @if ($user->status == 'active') bg-green-100 text-green-800
                                @elseif($user->status == 'inactive') bg-gray-100 text-gray-800
                                @else bg-red-100 text-red-800 @endif">
                                        {{ ucfirst($user->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    @if ($user->hasRole('shopkeeper') && $user->vendorProfile)
                                        <div class="text-sm">
                                            <div class="font-medium text-gray-900 mb-1">
                                                {{ $user->vendorProfile->business_name }}
                                            </div>
                                            <div class="flex items-center space-x-2">
                                                <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full 
                                                    @if($user->vendorProfile->status === 'approved') bg-green-100 text-green-800
                                                    @elseif($user->vendorProfile->status === 'pending') bg-yellow-100 text-yellow-800
                                                    @elseif($user->vendorProfile->status === 'rejected') bg-red-100 text-red-800
                                                    @elseif($user->vendorProfile->status === 'suspended') bg-gray-100 text-gray-800
                                                    @endif">
                                                    {{ ucfirst($user->vendorProfile->status) }}
                                                </span>
                                                
                                                @if($user->vendorProfile->status === 'pending')
                                                    <div class="flex space-x-1">
                                                        <button 
                                                            onclick="openApprovalModal({{ $user->id }}, 'approve')"
                                                            class="inline-flex items-center px-2 py-1 bg-green-600 hover:bg-green-700 text-white text-xs rounded transition-colors"
                                                            title="Approve Vendor">
                                                            <i data-lucide="check" class="w-3 h-3"></i>
                                                        </button>
                                                        <button 
                                                            onclick="openApprovalModal({{ $user->id }}, 'reject')"
                                                            class="inline-flex items-center px-2 py-1 bg-red-600 hover:bg-red-700 text-white text-xs rounded transition-colors"
                                                            title="Reject Vendor">
                                                            <i data-lucide="x" class="w-3 h-3"></i>
                                                        </button>
                                                    </div>
                                                @elseif($user->vendorProfile->status === 'approved')
                                                    <button 
                                                        onclick="openApprovalModal({{ $user->id }}, 'suspend')"
                                                        class="inline-flex items-center px-2 py-1 bg-gray-600 hover:bg-gray-700 text-white text-xs rounded transition-colors"
                                                        title="Suspend Vendor">
                                                        <i data-lucide="ban" class="w-3 h-3"></i>
                                                    </button>
                                                @endif
                                            </div>
                                        </div>
                                    @elseif($user->hasRole('shopkeeper'))
                                        <span class="text-xs text-gray-500">Vendor profile not set</span>
                                    @else
                                        <span class="text-xs text-gray-400">-</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $user->last_login_at ? $user->last_login_at->diffForHumans() : 'Never' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex items-center space-x-2">
                                        <a href="{{ route('admin.users.show', $user) }}"
                                            class="text-purple-600 hover:text-purple-900 p-1" title="View">
                                            <i data-lucide="eye" class="w-4 h-4"></i>
                                        </a>
                                        <a href="{{ route('admin.users.edit', $user) }}"
                                            class="text-blue-600 hover:text-blue-900 p-1" title="Edit">
                                            <i data-lucide="edit" class="w-4 h-4"></i>
                                        </a>
                                        @if ($user->id !== auth()->id())
                                            <form action="{{ route('admin.users.destroy', $user) }}" method="POST"
                                                class="inline"
                                                onsubmit="return confirm('Are you sure you want to delete this user?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900 p-1"
                                                    title="Delete">
                                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center">
                                    <div class="text-gray-500">
                                        <i data-lucide="users" class="w-12 h-12 mx-auto mb-4 text-gray-300"></i>
                                        <p class="text-lg font-medium">No users found</p>
                                        <p class="text-sm">Try adjusting your search or filter criteria.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Dynamic Pagination -->
            <div class="px-6 py-3 border-t border-gray-200 bg-gray-50">
                <div class="flex items-center justify-between">
                    <div class="text-sm text-gray-700">
                        Showing <span class="font-medium">{{ $users->firstItem() ?? 0 }}</span> to
                        <span class="font-medium">{{ $users->lastItem() ?? 0 }}</span> of
                        <span class="font-medium">{{ $users->total() }}</span> results
                    </div>
                    <div>
                        {{ $users->withQueryString()->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Vendor Approval Modal -->
    <div id="approvalModal" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-full max-w-md shadow-lg rounded-xl bg-white">
            <div class="mt-3">
                <!-- Modal Header -->
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900" id="modalTitle">Approve Vendor</h3>
                    <button onclick="closeApprovalModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                        <i data-lucide="x" class="w-5 h-5"></i>
                    </button>
                </div>

                <!-- Modal Form -->
                <form id="approvalForm" method="POST">
                    @csrf
                    @method('PATCH')
                    
                    <div class="mb-4">
                        <p class="text-sm text-gray-600" id="modalMessage">
                            Are you sure you want to approve this vendor? This will allow them to access the vendor dashboard and start selling products.
                        </p>
                    </div>

                    <!-- Rejection Reason (hidden by default) -->
                    <div id="rejectionReasonDiv" class="mb-4 hidden">
                        <label for="rejection_reason" class="block text-sm font-medium text-gray-700 mb-2">
                            Rejection Reason <span class="text-red-500">*</span>
                        </label>
                        <textarea 
                            name="rejection_reason" 
                            id="rejection_reason" 
                            rows="4" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent text-sm"
                            placeholder="Please provide a reason for rejection..."></textarea>
                    </div>

                    <!-- Suspension Reason (hidden by default) -->
                    <div id="suspensionReasonDiv" class="mb-4 hidden">
                        <label for="reason" class="block text-sm font-medium text-gray-700 mb-2">
                            Suspension Reason <span class="text-red-500">*</span>
                        </label>
                        <textarea 
                            name="reason" 
                            id="reason" 
                            rows="4" 
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent text-sm"
                            placeholder="Please provide a reason for suspension..."></textarea>
                    </div>

                    <!-- Modal Actions -->
                    <div class="flex justify-end space-x-3 mt-6">
                        <button 
                            type="button" 
                            onclick="closeApprovalModal()" 
                            class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors text-sm font-medium">
                            Cancel
                        </button>
                        <button 
                            type="submit" 
                            id="submitBtn"
                            class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors text-sm font-medium">
                            Confirm Approval
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @if (session('success'))
        <div class="fixed top-4 right-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded z-50">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="fixed top-4 right-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded z-50">
            {{ session('error') }}
        </div>
    @endif

    <script>
        // Vendor Approval Modal Functions
        function openApprovalModal(userId, action) {
            const modal = document.getElementById('approvalModal');
            const form = document.getElementById('approvalForm');
            const modalTitle = document.getElementById('modalTitle');
            const modalMessage = document.getElementById('modalMessage');
            const submitBtn = document.getElementById('submitBtn');
            const rejectionDiv = document.getElementById('rejectionReasonDiv');
            const suspensionDiv = document.getElementById('suspensionReasonDiv');
            const rejectionTextarea = document.getElementById('rejection_reason');
            const suspensionTextarea = document.getElementById('reason');

            // Reset form
            rejectionDiv.classList.add('hidden');
            suspensionDiv.classList.add('hidden');
            rejectionTextarea.removeAttribute('required');
            suspensionTextarea.removeAttribute('required');
            rejectionTextarea.value = '';
            suspensionTextarea.value = '';

            // Configure based on action
            if (action === 'approve') {
                form.action = `/admin/vendors/${userId}/approve`;
                modalTitle.textContent = 'Approve Vendor';
                modalMessage.textContent = 'Are you sure you want to approve this vendor? This will allow them to access the vendor dashboard and start selling products.';
                submitBtn.textContent = 'Confirm Approval';
                submitBtn.className = 'px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors text-sm font-medium';
            } else if (action === 'reject') {
                form.action = `/admin/vendors/${userId}/reject`;
                modalTitle.textContent = 'Reject Vendor';
                modalMessage.textContent = 'Please provide a reason for rejecting this vendor application.';
                submitBtn.textContent = 'Confirm Rejection';
                submitBtn.className = 'px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors text-sm font-medium';
                rejectionDiv.classList.remove('hidden');
                rejectionTextarea.setAttribute('required', 'required');
            } else if (action === 'suspend') {
                form.action = `/admin/vendors/${userId}/suspend`;
                modalTitle.textContent = 'Suspend Vendor';
                modalMessage.textContent = 'Please provide a reason for suspending this vendor account. All their products will be deactivated.';
                submitBtn.textContent = 'Confirm Suspension';
                submitBtn.className = 'px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors text-sm font-medium';
                suspensionDiv.classList.remove('hidden');
                suspensionTextarea.setAttribute('required', 'required');
            }

            modal.classList.remove('hidden');
        }

        function closeApprovalModal() {
            const modal = document.getElementById('approvalModal');
            modal.classList.add('hidden');
        }

        // Close modal when clicking outside
        document.getElementById('approvalModal')?.addEventListener('click', function(e) {
            if (e.target === this) {
                closeApprovalModal();
            }
        });

        // Auto-hide alerts after 5 seconds
        setTimeout(function() {
            const alerts = document.querySelectorAll('[class*="fixed top-4 right-4"]');
            alerts.forEach(alert => {
                alert.style.transition = 'opacity 0.5s';
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 500);
            });
        }, 5000);
    </script>
@endsection
