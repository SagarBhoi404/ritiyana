<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\PujaKit;
use App\Models\Puja;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PujaKitController extends Controller
{
    public function index()
    {
        $kits = PujaKit::with(['pujas', 'products'])
            ->latest()
            ->paginate(15);

        // Dynamic statistics
        $totalKits = PujaKit::count();
        $activeKits = PujaKit::where('is_active', true)->count();
        $totalSales = 248650; // This would come from orders table
        $bestSeller = PujaKit::with('products')->first();

        return view('admin.puja-kits.index', compact(
            'kits',
            'totalKits',
            'activeKits',
            'totalSales',
            'bestSeller'
        ));
    }

    public function create()
    {
        $pujas = Puja::where('is_active', true)->get();
        $products = Product::where('is_active', true)->get();

        return view('admin.puja-kits.create', compact('pujas', 'products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kit_name' => 'required|string|max:255',
            'slug' => 'nullable|string|unique:puja_kits,slug',
            'pujas' => 'required|array|min:1',
            'pujas.*' => 'exists:pujas,id',
            'products' => 'required|array|min:1',
            'products.*' => 'exists:products,id',
            'product_quantities' => 'nullable|array',
            'product_quantities.*' => 'nullable|integer|min:1',
            'product_prices' => 'nullable|array',
            'product_prices.*' => 'nullable|numeric|min:0',
            'kit_description' => 'nullable|string',
            'included_items' => 'nullable|array',
            'discount_percentage' => 'nullable|numeric|min:0|max:100',
            'is_active' => 'boolean',
        ]);

        // Generate slug if not provided
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['kit_name']);
        }

        $validated['is_active'] = $request->boolean('is_active');

        // Create the puja kit
        $pujaKit = PujaKit::create($validated);

        // Attach pujas
        $pujaKit->pujas()->sync($validated['pujas']);

        // Attach products with quantities and prices
        $productData = [];
        foreach ($validated['products'] as $index => $productId) {
            $productData[$productId] = [
                'quantity' => $validated['product_quantities'][$index] ?? 1,
                'price' => $validated['product_prices'][$index] ?? null,
            ];
        }

        $pujaKit->products()->sync($productData);

        return redirect()->route('admin.puja-kits.index')
            ->with('success', 'Puja Kit created successfully');
    }

    public function show(PujaKit $pujaKit)
    {
        $pujaKit->load(['pujas', 'products']);
        return view('admin.puja-kits.show', compact('pujaKit'));
    }

    public function edit(PujaKit $pujaKit)
    {
        $pujaKit->load(['pujas', 'products']);
        $pujas = Puja::where('is_active', true)->get();
        $products = Product::where('is_active', true)->get();

        return view('admin.puja-kits.edit', compact('pujaKit', 'pujas', 'products'));
    }

    public function update(Request $request, PujaKit $pujaKit)
    {
        $validated = $request->validate([
            'kit_name' => 'required|string|max:255',
            'slug' => 'nullable|string|unique:puja_kits,slug,' . $pujaKit->id,
            'pujas' => 'required|array|min:1',
            'pujas.*' => 'exists:pujas,id',
            'products' => 'required|array|min:1',
            'products.*' => 'exists:products,id',
            'product_quantities' => 'nullable|array',
            'product_quantities.*' => 'nullable|integer|min:1',
            'product_prices' => 'nullable|array',
            'product_prices.*' => 'nullable|numeric|min:0',
            'kit_description' => 'nullable|string',
            'included_items' => 'nullable|array',
            'discount_percentage' => 'nullable|numeric|min:0|max:100',
            'is_active' => 'boolean',
        ]);

        // Generate slug if not provided
        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['kit_name']);
        }

        $validated['is_active'] = $request->boolean('is_active');

        $pujaKit->update($validated);

        // Sync pujas
        $pujaKit->pujas()->sync($validated['pujas']);

        // Sync products with quantities and prices
        $productData = [];
        foreach ($validated['products'] as $index => $productId) {
            $productData[$productId] = [
                'quantity' => $validated['product_quantities'][$index] ?? 1,
                'price' => $validated['product_prices'][$index] ?? null,
            ];
        }

        $pujaKit->products()->sync($productData);

        return redirect()->route('admin.puja-kits.index')
            ->with('success', 'Puja Kit updated successfully');
    }

    public function destroy(PujaKit $pujaKit)
    {
        $pujaKit->delete();
        return redirect()->route('admin.puja-kits.index')
            ->with('success', 'Puja Kit deleted successfully');
    }
}
