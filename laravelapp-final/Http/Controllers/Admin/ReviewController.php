<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index()
    {
        $reviews = Review::with(['user', 'touristAttraction'])
            ->latest()
            ->paginate(10);

        return view('admin.reviews.index', compact('reviews'));
    }

    public function show(Review $review)
    {
        $review->load(['user', 'touristAttraction']);

        return view('admin.reviews.show', compact('review'));
    }

    public function edit(Review $review)
    {
        return view('admin.reviews.edit', compact('review'));
    }

    public function update(Request $request, Review $review)
    {
        $validated = $request->validate([
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['required', 'string', 'max:1000'],
        ]);

        $review->update($validated);

        return redirect()->route('admin.reviews.index')
            ->with('success', 'Review updated successfully.');
    }

    public function destroy(Review $review)
    {
        $review->delete();

        return redirect()->route('admin.reviews.index')
            ->with('success', 'Review deleted successfully.');
    }
}
