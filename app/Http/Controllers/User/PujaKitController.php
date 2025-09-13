<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\PujaKit;
use App\Models\Puja;
use Illuminate\Http\Request;

class PujaKitController extends Controller
{
    /**
     * Display a listing of puja kits
     */
    public function index(Request $request)
    {
        $query = PujaKit::active()
            ->with(['pujas', 'products', 'vendor']);

        // Filter by puja type
        if ($request->has('puja')) {
            $query->whereHas('pujas', function($q) use ($request) {
                $q->where('slug', $request->puja);
            });
        }

        // Filter by search
        if ($request->has('search') && $request->search) {
            $query->where('kit_name', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%')
                  ->orWhereHas('pujas', function($q) use ($request) {
                      $q->where('name', 'like', '%' . $request->search . '%');
                  });
        }

        // Sort kits
        $sort = $request->get('sort', 'newest');
        switch ($sort) {
            case 'price_low':
                $query->orderBy('created_at', 'desc'); // You can add price calculation here
                break;
            case 'price_high':
                $query->orderBy('created_at', 'desc'); // You can add price calculation here
                break;
            case 'popular':
                $query->orderBy('created_at', 'desc');
                break;
            case 'newest':
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        $pujaKits = $query->paginate(12);

        // Get pujas for filter sidebar
        $pujas = Puja::active()
            ->whereHas('pujaKits', function($q) {
                $q->active();
            })
            ->withCount(['pujaKits' => function($q) {
                $q->active();
            }])
            ->get();

        return view('puja-kits-detail', compact('pujaKits', 'pujas'));
    }

    /**
     * Display the specified puja kit
     */
    public function show(PujaKit $pujaKit)
    {
        // Check if kit is active
        if (!$pujaKit->is_active) {
            abort(404, 'Puja kit not found or not available');
        }

        // Load relationships
        $pujaKit->load([
            'pujas',
            'products' => function($query) {
                $query->active()->approved();
            },
            'vendor'
        ]);

        // Get related kits from same pujas
        $relatedKits = PujaKit::active()
            ->where('id', '!=', $pujaKit->id)
            ->whereHas('pujas', function($query) use ($pujaKit) {
                $query->whereIn('pujas.id', $pujaKit->pujas->pluck('id'));
            })
            ->with(['pujas', 'products', 'vendor'])
            ->limit(6)
            ->get();

        // Calculate kit statistics
        $kitStats = [
            'total_products' => $pujaKit->products->count(),
            'total_items' => $pujaKit->products->sum('pivot.quantity'),
            'total_savings' => 0,
        ];

        // Calculate savings
        $individualTotal = $pujaKit->products->sum(function($product) {
            return ($product->pivot->price ?? $product->price) * $product->pivot->quantity;
        });
        $kitStats['total_savings'] = max(0, $individualTotal - $pujaKit->total_price);

        return view('puja-kits-detail', compact('pujaKit', 'relatedKits', 'kitStats'));
    }
}
