<?php
// app/Http/Controllers/Admin/RoleController.php
namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::withCount('users')->get();
        
        // Dynamic statistics
        $totalRoles = $roles->count();
        $totalPermissions = 12; // Or Permission::count() if you have permissions table
        $totalUsersAssigned = $roles->sum('users_count');
        $adminCount = Role::where('name', 'admin')->withCount('users')->first()->users_count ?? 0;
        
        return view('admin.roles.index', compact(
            'roles', 
            'totalRoles', 
            'totalPermissions', 
            'totalUsersAssigned', 
            'adminCount'
        ));
    }

    public function create()
    {
        // Get all permissions if you have a permissions table
        // $permissions = Permission::all();
        return view('admin.roles.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:roles,name|max:255',
            'display_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean', // Changed from 'status' to 'is_active'
        ]);

        Role::create([
            'name' => $validated['name'],
            'display_name' => $validated['display_name'],
            'description' => $validated['description'] ?? null,
            'is_active' => $request->boolean('is_active'), // Use boolean helper
        ]);

        return redirect()->route('admin.roles.index')
            ->with('success', 'Role created successfully');
    }

    public function show(Role $role)
    {
        $role->load('users');
        return view('admin.roles.show', compact('role'));
    }

    public function edit(Role $role)
    {
        return view('admin.roles.edit', compact('role'));
    }

    public function update(Request $request, Role $role)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('roles')->ignore($role->id)],
            'display_name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $role->update([
            'name' => $validated['name'],
            'display_name' => $validated['display_name'],
            'description' => $validated['description'] ?? null,
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()->route('admin.roles.index')
            ->with('success', 'Role updated successfully');
    }

    public function destroy(Role $role)
    {
        if ($role->users()->count() > 0) {
            return redirect()->route('admin.roles.index')
                ->with('error', 'Cannot delete role that has assigned users');
        }

        $role->delete();

        return redirect()->route('admin.roles.index')
            ->with('success', 'Role deleted successfully');
    }

    public function toggleStatus(Role $role)
    {
        $role->update(['is_active' => !$role->is_active]);

        return response()->json([
            'success' => true,
            'status' => $role->is_active,
            'message' => 'Role status updated successfully'
        ]);
    }
}
