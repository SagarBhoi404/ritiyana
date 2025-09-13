<?php
// app/Http/Controllers/Vendor/VendorPujaController.php
namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\Puja;
use App\Models\PujaKit;
use App\Models\Vendor;
use Illuminate\Http\Request;

class VendorPujaController extends Controller
{
    public function index()
    {
        $pujas = Puja::where('vendor_id', auth()->id())
            ->with(['pujaKits', 'vendor.vendorProfile'])
            ->withCount('pujaKits as kits_count')
            ->latest()
            ->paginate(15);

        // Dynamic statistics filtered by vendor
        $totalPujas = Puja::where('vendor_id', auth()->id())->count();
        $activePujas = Puja::where('vendor_id', auth()->id())
            ->where('is_active', true)
            ->count();
        $inactivePujas = Puja::where('vendor_id', auth()->id())
            ->where('is_active', false)
            ->count();

        // Fix: Use the many-to-many relationship correctly
        $totalKits = \App\Models\PujaKit::whereHas('pujas', function ($query) {
            $query->where('vendor_id', auth()->id());
        })->count();

        return view('shopkeeper.pujas.index', compact(
            'pujas',
            'totalPujas',
            'activePujas',
            'inactivePujas',
            'totalKits'
        ));
    }

    public function create()
    {
        return view('shopkeeper.pujas.create');
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'significance' => 'nullable|string',
            'procedure' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'auspicious_days' => 'nullable|array',
            'auspicious_days.*' => 'nullable|date',
            'required_items' => 'nullable|array',
            'required_items.*' => 'nullable|string',
            'required_items_input' => 'nullable|string', // Accept the textarea input too
            'is_active' => 'boolean',
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('pujas', 'public');
        }

        // Handle required_items conversion if coming from textarea
        if (!empty($validated['required_items_input']) && empty($validated['required_items'])) {
            $validated['required_items'] = array_filter(
                array_map('trim', explode("\n", $validated['required_items_input']))
            );
        }

        // Remove the input field from validated data
        unset($validated['required_items_input']);

        // Ensure arrays are properly set
        $validated['auspicious_days'] = $validated['auspicious_days'] ?? [];
        $validated['required_items'] = $validated['required_items'] ?? [];

        $validated['vendor_id'] = auth()->id();
        $validated['is_active'] = $request->boolean('is_active');

        Puja::create($validated);

        return redirect()->route('vendor.pujas.index')
            ->with('success', 'Puja created successfully');
    }

    public function show(Puja $puja)
    {
        $puja->load(['pujaKits.products', 'vendor.vendorProfile']);
        return view('shopkeeper.pujas.show', compact('puja'));
    }

     public function edit(Puja $puja)
    {
        return view('shopkeeper.pujas.edit', compact('puja'));
    }

    public function update(Request $request, Puja $puja)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => 'nullable|string',
            'significance' => 'nullable|string',
            'procedure' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'auspicious_days' => 'nullable|array',
            'auspicious_days.*' => 'date',
            'required_items' => 'nullable|array',
            'is_active' => 'boolean',
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image
            if ($puja->image) {
                \Storage::disk('public')->delete($puja->image);
            }
            $validated['image'] = $request->file('image')->store('pujas', 'public');
        }

        $validated['is_active'] = $request->boolean('is_active');

        $puja->update($validated);

        return redirect()->route('vendor.pujas.index')
            ->with('success', 'Puja updated successfully');
    }

    public function destroy(Puja $puja)
    {
        // Check if puja has kits
        if ($puja->kits()->count() > 0) {
            return redirect()->route('vendor.pujas.index')
                ->with('error', 'Cannot delete puja with existing kits');
        }

        // Delete image
        if ($puja->image) {
            \Storage::disk('public')->delete($puja->image);
        }

        $puja->delete();

        return redirect()->route('vendor.pujas.index')
            ->with('success', 'Puja deleted successfully');
    }
}
