<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TouristAttraction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AttractionController extends Controller
{
    public function index()
    {
        $attractions = TouristAttraction::withCount(['tickets', 'bookings', 'reviews'])
            ->latest()
            ->paginate(10);

        return view('admin.attractions.index', compact('attractions'));
    }

    public function create()
    {
        $types = [
            'attraction' => 'Tourist Attraction',
            'concert' => 'Concert',
            'event' => 'Event',
        ];

        $categories = [
            'nature' => 'Nature',
            'music' => 'Music',
            'sports' => 'Sports',
            'culture' => 'Culture',
            'food' => 'Food & Beverage',
        ];

        return view('admin.attractions.create', compact('types', 'categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|in:attraction,concert,event',
            'category' => 'required|string|max:50',
            'description' => 'required|string',
            'location' => 'required|string|max:255',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after:start_date',
            'is_active' => 'boolean',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'gallery' => 'nullable|array',
            'gallery.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'facilities' => 'nullable|array',
            'opening_hours' => 'nullable|string|max:50',
            'closing_hours' => 'nullable|string|max:50',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);

        if ($request->hasFile('featured_image')) {
            $path = $request->file('featured_image')->store('attractions/featured', 'public');
            $validated['featured_image'] = $path;
        }

        $galleryImages = [];
        if ($request->hasFile('gallery')) {
            foreach ($request->file('gallery') as $image) {
                $path = $image->store('attractions/gallery', 'public');
                $galleryImages[] = $path;
            }
        }
        $validated['gallery'] = $galleryImages;

        unset($validated['image']);

        TouristAttraction::create($validated);

        return redirect()
            ->route('admin.attractions.index')
            ->with('success', 'Attraction created successfully.');
    }

    public function show(TouristAttraction $attraction)
    {
        $attraction->load(['tickets', 'bookings', 'reviews']);

        return view('admin.attractions.show', compact('attraction'));
    }

    public function edit(TouristAttraction $attraction)
    {
        $types = [
            'attraction' => 'Tourist Attraction',
            'concert' => 'Concert',
            'event' => 'Event',
        ];

        $categories = [
            'nature' => 'Nature',
            'music' => 'Music',
            'sports' => 'Sports',
            'culture' => 'Culture',
            'food' => 'Food & Beverage',
        ];

        return view('admin.attractions.edit', compact('attraction', 'types', 'categories'));
    }

    public function update(Request $request, TouristAttraction $attraction)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|in:attraction,concert,event',
            'category' => 'required|string|max:50',
            'description' => 'required|string',
            'location' => 'required|string|max:255',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after:start_date',
            'is_active' => 'boolean',
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'gallery' => 'nullable|array',
            'gallery.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'facilities' => 'nullable|array',
            'opening_hours' => 'nullable|string|max:50',
            'closing_hours' => 'nullable|string|max:50',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);

        if ($request->hasFile('featured_image')) {
            if ($attraction->featured_image) {
                Storage::disk('public')->delete($attraction->featured_image);
            }
            $path = $request->file('featured_image')->store('attractions/featured', 'public');
            $validated['featured_image'] = $path;
        }

        $existingGallery = $attraction->gallery ?? [];
        $newGalleryImages = [];
        if ($request->hasFile('gallery')) {
            foreach ($request->file('gallery') as $image) {
                $path = $image->store('attractions/gallery', 'public');
                $newGalleryImages[] = $path;
            }
        }
        $validated['gallery'] = array_merge((array) $existingGallery, $newGalleryImages);

        unset($validated['image']);

        $attraction->update($validated);

        return redirect()
            ->route('admin.attractions.index')
            ->with('success', 'Attraction updated successfully.');
    }

    public function destroy(TouristAttraction $attraction)
    {
        if ($attraction->image) {
            Storage::disk('public')->delete($attraction->image);
        }

        $attraction->delete();

        return redirect()
            ->route('admin.attractions.index')
            ->with('success', 'Attraction deleted successfully.');
    }

    public function toggleStatus(TouristAttraction $attraction)
    {
        $attraction->update(['is_active' => ! $attraction->is_active]);

        return redirect()
            ->route('admin.attractions.index')
            ->with('success', 'Attraction status updated successfully.');
    }
}
