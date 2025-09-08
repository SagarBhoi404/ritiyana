<?php
// app/Http/Controllers/PujaController.php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Puja;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PujaController extends Controller
{
    public function index()
    {
        // Use the correct many-to-many relationship for counting
        $pujas = Puja::withCount('pujaKits')->latest()->paginate(15);
        
        // Alternative: if you want to use 'kits' as the relationship name
        // $pujas = Puja::withCount('kits')->latest()->paginate(15);
        
        // Dynamic statistics
        $totalPujas = Puja::count();
        $activePujas = Puja::where('is_active', true)->count();
        $inactivePujas = Puja::where('is_active', false)->count();
        $totalKits = \App\Models\PujaKit::count();

        return view('admin.pujas.index', compact(
            'pujas',
            'totalPujas',
            'activePujas', 
            'inactivePujas',
            'totalKits'
        ));
    }
    public function create()
    {
        return view('admin.pujas.create');
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
            'auspicious_days.*' => 'date',
            'required_items' => 'nullable|array',
            'is_active' => 'boolean',
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('pujas', 'public');
        }

        $validated['is_active'] = $request->boolean('is_active');

        Puja::create($validated);

        return redirect()->route('admin.pujas.index')
            ->with('success', 'Puja created successfully');
    }

    public function show(Puja $puja)
{
    $puja->load(['pujaKits.products']);
    return view('admin.pujas.show', compact('puja'));
}

    public function edit(Puja $puja)
    {
        return view('admin.pujas.edit', compact('puja'));
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

        return redirect()->route('admin.pujas.index')
            ->with('success', 'Puja updated successfully');
    }

    public function destroy(Puja $puja)
    {
        // Check if puja has kits
        if ($puja->kits()->count() > 0) {
            return redirect()->route('admin.pujas.index')
                ->with('error', 'Cannot delete puja with existing kits');
        }

        // Delete image
        if ($puja->image) {
            \Storage::disk('public')->delete($puja->image);
        }

        $puja->delete();

        return redirect()->route('admin.pujas.index')
            ->with('success', 'Puja deleted successfully');
    }
}
