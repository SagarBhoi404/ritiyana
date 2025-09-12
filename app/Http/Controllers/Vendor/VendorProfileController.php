<?php
// app/Http/Controllers/Vendor/ProfileController.php
namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Vendor;
use Illuminate\Http\Request;

class VendorProfileController extends Controller
{
    public function __construct()
    {
    }

    public function show()
    {
        $vendor = auth()->user()->vendorProfile;
        
        if (!$vendor) {
            return redirect()->route('vendor.profile.create');
        }

        return view('vendor.profile.show', compact('vendor'));
    }

    public function create()
    {
        // Check if vendor profile already exists
        if (auth()->user()->vendorProfile) {
            return redirect()->route('vendor.profile.show');
        }

        return view('vendor.profile.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'business_name' => 'required|string|max:255',
            'business_type' => 'required|in:individual,partnership,company,proprietorship',
            'business_address' => 'required|string',
            'business_phone' => 'required|string|max:15',
            'business_email' => 'nullable|email',
            'tax_id' => 'nullable|string|max:50',
            'pan_number' => 'nullable|string|max:10',
            'gst_number' => 'nullable|string|max:15',
            'bank_name' => 'required|string|max:255',
            'account_number' => 'required|string|max:255',
            'ifsc_code' => 'required|string|max:11',
            'account_holder_name' => 'required|string|max:255',
            'store_description' => 'nullable|string',
            'store_logo' => 'nullable|image|mimes:jpg,jpeg,png|max:1024',
        ]);

        // Handle store logo upload
        if ($request->hasFile('store_logo')) {
            $validated['store_logo'] = $request->file('store_logo')->store('vendor-logos', 'public');
        }

        $validated['user_id'] = auth()->id();
        $validated['status'] = 'pending';

        Vendor::create($validated);

        return redirect()->route('vendor.profile.show')
            ->with('success', 'Vendor profile created successfully! Awaiting admin approval.');
    }

    public function edit()
    {
        $vendor = auth()->user()->vendorProfile;
        
        if (!$vendor) {
            return redirect()->route('vendor.profile.create');
        }

        return view('vendor.profile.edit', compact('vendor'));
    }

    public function update(Request $request)
    {
        $vendor = auth()->user()->vendorProfile;

        $validated = $request->validate([
            'business_name' => 'required|string|max:255',
            'business_type' => 'required|in:individual,partnership,company,proprietorship',
            'business_address' => 'required|string',
            'business_phone' => 'required|string|max:15',
            'business_email' => 'nullable|email',
            'store_description' => 'nullable|string',
            'store_logo' => 'nullable|image|mimes:jpg,jpeg,png|max:1024',
        ]);

        // Handle store logo upload
        if ($request->hasFile('store_logo')) {
            if ($vendor->store_logo) {
                \Storage::disk('public')->delete($vendor->store_logo);
            }
            $validated['store_logo'] = $request->file('store_logo')->store('vendor-logos', 'public');
        }

        $vendor->update($validated);

        return redirect()->route('vendor.profile.show')
            ->with('success', 'Profile updated successfully!');
    }
}
