<?php
// app/Http/Controllers/Admin/VendorController.php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Vendor;
use App\Models\Product;
use App\Models\VendorOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class VendorController extends Controller
{
    public function index(Request $request)
    {
        $query = User::whereHas('roles', function ($q) {
            $q->where('name', 'shopkeeper');
        })->with(['vendorProfile', 'roles']);

        // Filter by status
        if ($request->filled('status') && $request->status !== 'all') {
            $query->whereHas('vendorProfile', function ($q) use ($request) {
                $q->where('status', $request->status);
            });
        }

        // Search by name or business
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhereHas('vendorProfile', function ($vq) use ($search) {
                      $vq->where('business_name', 'like', "%{$search}%");
                  });
            });
        }

        $vendors = $query->latest()->paginate(15)->withQueryString();

        // Statistics
        $totalVendors = User::whereHas('roles', function ($q) {
            $q->where('name', 'shopkeeper');
        })->count();

        $approvedVendors = Vendor::where('status', 'approved')->count();
        $pendingVendors = Vendor::where('status', 'pending')->count();
        $suspendedVendors = Vendor::where('status', 'suspended')->count();

        return view('admin.vendors.index', compact(
            'vendors',
            'totalVendors',
            'approvedVendors',
            'pendingVendors',
            'suspendedVendors'
        ));
    }

    public function show(User $vendor)
    {
        $vendor->load(['vendorProfile', 'vendorProducts', 'vendorOrders']);
        
        $stats = [
            'totalProducts' => $vendor->vendorProducts()->count(),
            'activeProducts' => $vendor->vendorProducts()->where('is_active', true)->count(),
            'totalOrders' => $vendor->vendorOrders()->count(),
            'totalEarnings' => $vendor->vendorOrders()->where('status', 'delivered')->sum('vendor_earning'),
            'monthlyEarnings' => $vendor->vendorOrders()
                ->where('status', 'delivered')
                ->whereMonth('created_at', now()->month)
                ->sum('vendor_earning'),
        ];

        return view('admin.vendors.show', compact('vendor', 'stats'));
    }

    public function approve(User $vendor)
    {
        $vendorProfile = $vendor->vendorProfile;
        
        if ($vendorProfile) {
            $vendorProfile->update([
                'status' => 'approved',
                'approved_at' => now(),
                'approved_by' => auth()->id(),
            ]);

            // Auto-approve vendor's products
            $vendor->vendorProducts()
                ->where('approval_status', 'pending')
                ->update([
                    'approval_status' => 'approved',
                    'approved_at' => now(),
                    'approved_by' => auth()->id(),
                ]);
        }

        return redirect()->back()->with('success', 'Vendor approved successfully!');
    }

    public function reject(Request $request, User $vendor)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:500'
        ]);

        $vendorProfile = $vendor->vendorProfile;
        
        if ($vendorProfile) {
            $vendorProfile->update([
                'status' => 'rejected',
                'rejection_reason' => $request->rejection_reason,
            ]);
        }

        return redirect()->back()->with('success', 'Vendor rejected successfully!');
    }

    public function suspend(Request $request, User $vendor)
    {
        $vendorProfile = $vendor->vendorProfile;
        
        if ($vendorProfile) {
            $vendorProfile->update([
                'status' => 'suspended',
                'rejection_reason' => $request->reason,
            ]);

            // Deactivate vendor's products
            $vendor->vendorProducts()->update(['is_active' => false]);
        }

        return redirect()->back()->with('success', 'Vendor suspended successfully!');
    }

    public function productApprovals()
    {
        $products = Product::where('approval_status', 'pending')
            ->where('is_vendor_product', true)
            ->with(['vendor.vendorProfile', 'categories'])
            ->latest()
            ->paginate(15);

        return view('admin.vendors.product-approvals', compact('products'));
    }

    public function approveProduct(Product $product)
    {
        $product->update([
            'approval_status' => 'approved',
            'approved_at' => now(),
            'approved_by' => auth()->id(),
            'is_active' => true,
        ]);

        return redirect()->back()->with('success', 'Product approved successfully!');
    }

    public function rejectProduct(Request $request, Product $product)
    {
        $product->update([
            'approval_status' => 'rejected',
        ]);

        return redirect()->back()->with('success', 'Product rejected successfully!');
    }
}
