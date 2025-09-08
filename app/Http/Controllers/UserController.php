<?php
// app/Http/Controllers/Admin/UserController.php
namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    // FIXED: Uncommented and improved index method
    public function index(Request $request)
    {
        $query = User::with('roles');

        // Filter by role
        if ($request->filled('role') && $request->role !== 'all') {
            $query->whereHas('roles', function ($q) use ($request) {
                $q->where('name', $request->role);
            });
        }

        // Filter by status
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Search by name or email
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Get paginated results with query string preserved
        $users = $query->latest()->paginate(15)->withQueryString();

        // Get statistics for the dashboard cards
        $totalUsers = User::count();
        $activeUsers = User::where('status', 'active')->count();
        $customers = User::whereHas('roles', function ($q) {
            $q->where('name', 'user');
        })->count();
        $shopkeepers = User::whereHas('roles', function ($q) {
            $q->where('name', 'shopkeeper');
        })->count();

        return view('admin.users.index', compact(
            'users',
            'totalUsers',
            'activeUsers',
            'customers',
            'shopkeepers'
        ));
    }

    public function create()
    {
        $roles = Role::where('is_active', true)->get(); // Use is_active
        return view('admin.users.create', compact('roles'));
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'nullable|string|max:15',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:male,female,other',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|string|in:admin,shopkeeper,user',
            'status' => 'nullable|string|in:active,inactive,suspended',
            'notes' => 'nullable|string|max:1000',
            'profile_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        // Handle profile image upload
        $profileImagePath = null;
        if ($request->hasFile('profile_image')) {
            $file = $request->file('profile_image');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $profileImagePath = $file->storeAs('profile_images', $filename, 'public');
        }

        // Create user with safe field access
        $user = User::create([
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'email' => $validated['email'],
            'phone' => $request->input('phone', null),
            'date_of_birth' => $request->input('date_of_birth', null),
            'gender' => $request->input('gender', null),
            'password' => Hash::make($validated['password']),
            'status' => $request->input('status', 'active'),
            'profile_image' => $profileImagePath,
            'email_verified_at' => $request->boolean('email_verified') ? now() : null,
            'notes' => $request->input('notes', null),
        ]);

        // Assign role
        if (method_exists($user, 'assignRole')) {
            $user->assignRole($validated['role']);
        }

        return redirect()->route('admin.users.index')
            ->with('success', 'User created successfully');
    }

    public function show(User $user)
    {
        $user->load('roles', 'addresses');
        return view('admin.users.show', compact('user'));
    }

    public function edit(User $user)
    {
        $roles = Role::where('is_active', true)->get(); // Use is_active
        $user->load('roles');
        return view('admin.users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'phone' => 'nullable|string|max:15',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|in:male,female,other',
            'password' => 'nullable|string|min:8|confirmed',
            'status' => 'required|in:active,inactive,suspended',
            'role' => 'required|string|in:admin,shopkeeper,user',
        ]);

        $updateData = [
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'date_of_birth' => $validated['date_of_birth'],
            'gender' => $validated['gender'],
            'status' => $validated['status'],
        ];

        if (!empty($validated['password'])) {
            $updateData['password'] = Hash::make($validated['password']);
        }

        $user->update($updateData);

        // Update role
        if (method_exists($user, 'assignRole')) {
            $user->roles()->detach(); // Remove old roles
            $user->assignRole($validated['role']); // Assign new role
        }

        return redirect()->route('admin.users.index')
            ->with('success', 'User updated successfully');
    }

    public function destroy(User $user)
    {
        // Prevent deletion of own account
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Cannot delete your own account');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'User deleted successfully');
    }

    public function toggleStatus(User $user)
    {
        $newStatus = $user->status === 'active' ? 'inactive' : 'active';
        $user->update(['status' => $newStatus]);

        return response()->json([
            'success' => true,
            'status' => $newStatus,
            'message' => "User status updated to {$newStatus}"
        ]);
    }
}
