<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TouristAttraction;
use Illuminate\Http\Request;

class TouristAttractionController extends Controller
{
    public function index(Request $request)
    {
        $query = TouristAttraction::query();

        // Filter by type
        if ($request->has('type')) {
            $query->where('type', $request->type);
        }

        // Filter by price range
        if ($request->has('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }
        if ($request->has('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        // Search by name or description
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Sort by
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $attractions = $query->paginate(10);

        return response()->json([
            'attractions' => $attractions,
        ]);
    }

    public function show(TouristAttraction $attraction)
    {
        $attraction->load(['reviews.user', 'bookings']);

        return response()->json([
            'attraction' => $attraction,
        ]);
    }

    public function reviews(TouristAttraction $attraction)
    {
        $reviews = $attraction->reviews()
            ->with('user')
            ->latest()
            ->paginate(10);

        return response()->json([
            'reviews' => $reviews,
        ]);
    }

    public function popular()
    {
        $popularAttractions = TouristAttraction::withCount('bookings')
            ->orderBy('bookings_count', 'desc')
            ->take(5)
            ->get();

        return response()->json([
            'attractions' => $popularAttractions,
        ]);
    }

    public function nearby(Request $request)
    {
        $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'radius' => 'required|numeric|min:1|max:50', // radius in kilometers
        ]);

        $attractions = TouristAttraction::selectRaw('
                *,
                (6371 * acos(
                    cos(radians(?)) * 
                    cos(radians(latitude)) * 
                    cos(radians(longitude) - radians(?)) + 
                    sin(radians(?)) * 
                    sin(radians(latitude))
                )) AS distance',
            [$request->latitude, $request->longitude, $request->latitude])
            ->having('distance', '<=', $request->radius)
            ->orderBy('distance')
            ->get();

        return response()->json([
            'attractions' => $attractions,
        ]);
    }
}
