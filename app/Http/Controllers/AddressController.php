<?php
// app/Http/Controllers/AddressController.php
namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\User;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    // public function index(User $user)
    // {
    //     $addresses = $user->addresses()->get();
    //     return view('admin.users.addresses.index', compact('user', 'addresses'));
    // }

    // public function create(User $user)
    // {
    //     return view('admin.users.addresses.create', compact('user'));
    // }

    // public function store(Request $request, User $user)
    // {
    //     $validated = $request->validate([
    //         'type' => 'required|in:billing,shipping,both',
    //         'title' => 'nullable|string|max:255',
    //         'first_name' => 'required|string|max:255',
    //         'last_name' => 'required|string|max:255',
    //         'company' => 'nullable|string|max:255',
    //         'address_line_1' => 'required|string',
    //         'address_line_2' => 'nullable|string',
    //         'city' => 'required|string|max:255',
    //         'state' => 'required|string|max:255',
    //         'postal_code' => 'required|string|max:10',
    //         'country' => 'required|string|max:255',
    //         'phone' => 'nullable|string|max:15',
    //         'is_default' => 'boolean',
    //     ]);

    //     $validated['user_id'] = $user->id;

    //     $address = Address::create($validated);

    //     return redirect()->route('users.addresses.index', $user)
    //         ->with('success', 'Address created successfully');
    // }

    // public function show(User $user, Address $address)
    // {
    //     $this->authorize('view', $address);
    //     return view('admin.users.addresses.show', compact('user', 'address'));
    // }

    // public function edit(User $user, Address $address)
    // {
    //     $this->authorize('update', $address);
    //     return view('admin.users.addresses.edit', compact('user', 'address'));
    // }

    // public function update(Request $request, User $user, Address $address)
    // {
    //     $this->authorize('update', $address);

    //     $validated = $request->validate([
    //         'type' => 'required|in:billing,shipping,both',
    //         'title' => 'nullable|string|max:255',
    //         'first_name' => 'required|string|max:255',
    //         'last_name' => 'required|string|max:255',
    //         'company' => 'nullable|string|max:255',
    //         'address_line_1' => 'required|string',
    //         'address_line_2' => 'nullable|string',
    //         'city' => 'required|string|max:255',
    //         'state' => 'required|string|max:255',
    //         'postal_code' => 'required|string|max:10',
    //         'country' => 'required|string|max:255',
    //         'phone' => 'nullable|string|max:15',
    //         'is_default' => 'boolean',
    //     ]);

    //     $address->update($validated);

    //     return redirect()->route('users.addresses.index', $user)
    //         ->with('success', 'Address updated successfully');
    // }

    // public function destroy(User $user, Address $address)
    // {
    //     $this->authorize('delete', $address);
        
    //     $address->delete();

    //     return redirect()->route('users.addresses.index', $user)
    //         ->with('success', 'Address deleted successfully');
    // }

    // public function setDefault(User $user, Address $address)
    // {
    //     $this->authorize('update', $address);

    //     // Remove default from other addresses of same type
    //     $user->addresses()
    //         ->where('type', $address->type)
    //         ->update(['is_default' => false]);

    //     // Set this address as default
    //     $address->update(['is_default' => true]);

    //     return response()->json([
    //         'success' => true,
    //         'message' => 'Default address updated successfully'
    //     ]);
    // }
}
