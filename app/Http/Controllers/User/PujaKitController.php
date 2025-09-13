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
        if ($request->has('puja') && $request->puja) {
            $query->whereHas('pujas', function ($q) use ($request) {
                $q->where('slug', $request->puja);
            });
        }

        // Filter by search
        if ($request->has('search') && $request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('kit_name', 'like', '%' . $request->search . '%')
                    ->orWhere('description', 'like', '%' . $request->search . '%')
                    ->orWhereHas('pujas', function ($pujaQuery) use ($request) {
                        $pujaQuery->where('name', 'like', '%' . $request->search . '%');
                    });
            });
        }

        // Filter by price range
        if ($request->has('min_price') && $request->min_price) {
            // Since total_price is calculated, we need to filter after loading
            // For now, we'll use a basic approach - you can optimize this later
        }
        if ($request->has('max_price') && $request->max_price) {
            // Similar approach for max price
        }

        // Sort kits
        $sort = $request->get('sort', 'newest');
        switch ($sort) {
            case 'price_low':
                // For now, order by created date - you can add custom sorting later
                $query->orderBy('created_at', 'asc');
                break;
            case 'price_high':
                $query->orderBy('created_at', 'desc');
                break;
            case 'popular':
                $query->orderBy('created_at', 'desc');
                break;
            case 'name':
                $query->orderBy('kit_name', 'asc');
                break;
            case 'newest':
            default:
                $query->orderBy('created_at', 'desc');
                break;
        }

        $pujaKits = $query->paginate(12)->withQueryString();

        // Get pujas for filter sidebar
        $pujas = Puja::active()
            ->whereHas('pujaKits', function ($q) {
                $q->active();
            })
            ->withCount(['pujaKits' => function ($q) {
                $q->active();
            }])
            ->orderBy('puja_kits_count', 'desc')
            ->get();

        // Calculate total kits count
        $totalKits = PujaKit::active()->count();

        return view('all-kits', compact('pujaKits', 'pujas', 'totalKits'));
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
            'products' => function ($query) {
                $query->active()->approved();
            },
            'vendor'
        ]);

        // Get related kits from same pujas
        $relatedKits = PujaKit::active()
            ->where('id', '!=', $pujaKit->id)
            ->whereHas('pujas', function ($query) use ($pujaKit) {
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
        $individualTotal = $pujaKit->products->sum(function ($product) {
            return ($product->pivot->price ?? $product->price) * $product->pivot->quantity;
        });
        $kitStats['total_savings'] = max(0, $individualTotal - $pujaKit->total_price);

        return view('puja-kits-detail', compact('pujaKit', 'relatedKits', 'kitStats'));
    }
}
