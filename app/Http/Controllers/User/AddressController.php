<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AddressController extends Controller
{
    /**
     * Display a listing of user's addresses
     */
    public function index()
    {
        $addresses = Auth::user()
            ->addresses()
            ->orderBy('is_default', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('user.addresses.index', compact('addresses'));
    }

    /**
     * Show the form for creating a new address
     */
    public function create()
    {
        return view('user.addresses.create');
    }

    /**
     * Store a newly created address in storage
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone' => 'required|string|max:15',
            'address_line_1' => 'required|string|max:255',
            'address_line_2' => 'nullable|string|max:255',
            'landmark' => 'nullable|string|max:255',
            'city' => 'required|string|max:255',
            'state' => 'required|in:Maharashtra',
            'postal_code' => [
                'required',
                'digits:6',
                'regex:/^(4[0-4])[0-9]{4}$/',
            ],

            'country' => 'required|in:India',
            'type' => 'required|in:billing,shipping,both',
            'is_default' => 'boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $addressData = $validator->validated();
            $addressData['user_id'] = Auth::id();
            $addressData['is_default'] = $request->boolean('is_default');

            // If this is being set as default, unset other default addresses
            if ($addressData['is_default']) {
                Auth::user()->addresses()->update(['is_default' => false]);
            }

            // If this is the user's first address, make it default automatically
            if (Auth::user()->addresses()->count() === 0) {
                $addressData['is_default'] = true;
            }

            $address = Address::create($addressData);

            return redirect()->route('addresses.index')
                ->with('success', 'Address added successfully!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to add address. Please try again.')
                ->withInput();
        }
    }

    /**
     * Display the specified address
     */
    public function show(Address $address)
    {

        return view('user.addresses.show', compact('address'));
    }

    /**
     * Show the form for editing the specified address
     */
    public function edit(Address $address)
    {

        return view('user.addresses.edit', compact('address'));
    }

    /**
     * Update the specified address in storage
     */
    public function update(Request $request, Address $address)
    {

        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone' => 'required|string|max:15',
            'address_line_1' => 'required|string|max:255',
            'address_line_2' => 'nullable|string|max:255',
            'landmark' => 'nullable|string|max:255',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'postal_code' => 'required|string|max:10',
            'country' => 'required|string|max:255',
            'type' => 'required|in:billing,shipping,both',
            'is_default' => 'boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $addressData = $validator->validated();
            $addressData['is_default'] = $request->boolean('is_default');

            // If this is being set as default, unset other default addresses
            if ($addressData['is_default'] && ! $address->is_default) {
                Auth::user()->addresses()->where('id', '!=', $address->id)
                    ->update(['is_default' => false]);
            }

            $address->update($addressData);

            return redirect()->route('addresses.index')
                ->with('success', 'Address updated successfully!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to update address. Please try again.')
                ->withInput();
        }
    }

    /**
     * Remove the specified address from storage
     */
    public function destroy(Address $address)
    {

        try {
            // If this is the default address and there are other addresses,
            // make the first remaining address default
            if ($address->is_default) {
                $nextAddress = Auth::user()->addresses()
                    ->where('id', '!=', $address->id)
                    ->first();

                if ($nextAddress) {
                    $nextAddress->update(['is_default' => true]);
                }
            }

            $address->delete();

            return redirect()->route('addresses.index')
                ->with('success', 'Address deleted successfully!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Failed to delete address. Please try again.');
        }
    }

    /**
     * Set an address as default
     */
    public function setDefault(Address $address)
    {

        try {
            // Unset all other default addresses for this user
            Auth::user()->addresses()->update(['is_default' => false]);

            // Set this address as default
            $address->update(['is_default' => true]);

            if (request()->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Default address updated successfully!',
                ]);
            }

            return redirect()->route('addresses.index')
                ->with('success', 'Default address updated successfully!');

        } catch (\Exception $e) {
            if (request()->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to update default address.',
                ], 500);
            }

            return redirect()->back()
                ->with('error', 'Failed to update default address. Please try again.');
        }
    }

    /**
     * Get addresses for AJAX requests (used in checkout)
     */
    public function getAddresses(Request $request)
    {
        $addresses = Auth::user()
            ->addresses()
            ->when($request->type, function ($query, $type) {
                return $query->whereIn('type', [$type, 'both']);
            })
            ->orderBy('is_default', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'addresses' => $addresses->map(function ($address) {
                return [
                    'id' => $address->id,
                    'full_name' => $address->full_name,
                    'formatted_address' => $address->formatted_address,
                    'phone' => $address->phone,
                    'is_default' => $address->is_default,
                    'type' => $address->type,
                ];
            }),
        ]);
    }
}
