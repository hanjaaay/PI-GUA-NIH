<?php

namespace App\Http\Controllers;

use App\Models\TouristAttraction;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class AttractionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $attractions = TouristAttraction::with(['tickets'])
            ->latest()
            ->paginate(12);

        return view('attractions.index', compact('attractions'));
    }

    /**
     * Display the specified resource.
     */
    public function show(TouristAttraction $attraction): View
    {
        $attraction->load(['tickets', 'reviews.user']);

        return view('attractions.show', compact('attraction'));
    }

    /**
     * Display a listing of featured attractions.
     */
    public function featured(): View
    {
        $attractions = TouristAttraction::with(['tickets'])
            ->inRandomOrder()
            ->limit(6)
            ->get();

        return view('attractions.featured', compact('attractions'));
    }

    /**
     * Search attractions based on query.
     */
    public function search(Request $request): View
    {
        $query = $request->input('query');

        $attractions = TouristAttraction::with(['tickets'])
            ->where('name', 'like', "%{$query}%")
            ->orWhere('location', 'like', "%{$query}%")
            ->orWhere('city', 'like', "%{$query}%")
            ->orWhere('province', 'like', "%{$query}%")
            ->paginate(12);

        return view('attractions.index', compact('attractions', 'query'));
    }

    /**
     * Display a listing of concert attractions.
     */
    public function concerts(): View
    {
        $attractions = TouristAttraction::with(['tickets'])
            ->where('type', 'concert')
            ->latest()
            ->paginate(12);

        return view('attractions.concerts', compact('attractions'));
    }
}
