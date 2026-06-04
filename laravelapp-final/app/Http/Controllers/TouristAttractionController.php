<?php

namespace App\Http\Controllers;

use App\Models\TouristAttraction;
// Pastikan model ini diimpor jika digunakan
use App\Services\CacheService; // Pastikan service ini ada
use Illuminate\Http\Request;

class TouristAttractionController extends Controller
{
    protected $cacheService;

    public function __construct(CacheService $cacheService)
    {
        $this->cacheService = $cacheService;
    }

    /**
     * Display a listing of the attractions with search and filter functionality.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index(Request $request)
    {
        $categories = $this->cacheService->getCategories();
        $popularAttractions = $this->cacheService->getPopularAttractions();

        $query = TouristAttraction::with(['category', 'reviews', 'tickets' => function ($q) {
            $q->orderBy('price', 'asc');
        }]);

        $query->when($request->has('search'), function ($q) use ($request) {
            $search = $request->input('search');
            $q->where('name', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%");
        });

        $query->when($request->has('category_id'), function ($q) use ($request) {
            $q->where('category_id', $request->input('category_id'));
        });

        $query->when($request->has('type'), function ($q) use ($request) {
            $q->where('type', $request->input('type'));
        });

        $attractions = $query->paginate(10);

        return view('attractions.index', compact('attractions', 'categories', 'popularAttractions'));
    }

    /**
     * Display the specified tourist attraction.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function show(TouristAttraction $attraction)
    {
        $attraction->loadMissing(['tickets', 'category']);

        $minTicketPrice = $attraction->tickets->min('price');
        $ticketPriceForButton = $minTicketPrice ?? 0;

        $relatedAttractions = $this->cacheService->getAttractionsByCategory($attraction->category_id)
            ->where('id', '!=', $attraction->id)
            ->take(4);

        return view('attractions.show', compact('attraction', 'relatedAttractions', 'ticketPriceForButton'));
    }

    // Metode lain...
}
