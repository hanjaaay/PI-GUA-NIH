<?php

namespace App\Http\Controllers;

use App\Models\TouristAttraction;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Festigo landing page: tampilkan featured attractions dan konser
        $featuredAttractions = TouristAttraction::where('is_active', true)
            ->latest()
            ->take(6)
            ->get();

        $concerts = TouristAttraction::where('is_active', true)
            ->where('type', 'concert')
            ->latest()
            ->take(6)
            ->get();

        return view('home', [
            'featuredAttractions' => $featuredAttractions,
            'concerts' => $concerts,
        ]);
    }

    public function show($id)
    {
        $attraction = TouristAttraction::with(['tickets' => function ($query) {
            $query->where('is_active', true);
        }])->findOrFail($id);

        // Get the cheapest ticket price for the booking button
        $ticketPriceForButton = $attraction->tickets->isNotEmpty() ? $attraction->tickets->min('price') : 0;

        // Get related attractions (same city or province)
        $relatedAttractions = TouristAttraction::where('is_active', true)
            ->where('id', '!=', $attraction->id)
            ->where(function ($query) use ($attraction) {
                $query->where('city', $attraction->city)
                    ->orWhere('province', $attraction->province);
            })
            ->take(4)
            ->get();

        return view('attractions.show', [
            'attraction' => $attraction,
            'ticketPriceForButton' => $ticketPriceForButton,
            'relatedAttractions' => $relatedAttractions,
        ]);
    }

    public function search(Request $request)
    {
        $query = TouristAttraction::where('is_active', true);

        if ($request->has('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('city', 'like', "%{$search}%")
                    ->orWhere('province', 'like', "%{$search}%");
            });
        }

        if ($request->has('city')) {
            $query->where('city', $request->get('city'));
        }

        if ($request->has('province')) {
            $query->where('province', $request->get('province'));
        }

        $attractions = $query->paginate(12);

        return view('attractions.index', [
            'attractions' => $attractions,
        ]);
    }
}
