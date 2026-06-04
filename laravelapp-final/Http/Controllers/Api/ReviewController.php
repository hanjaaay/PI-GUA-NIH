<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Models\TouristAttraction;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index(Request $request)
    {
        $reviews = Review::with(['user', 'touristAttraction'])
            ->latest()
            ->paginate(10);

        return response()->json([
            'reviews' => $reviews,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'tourist_attraction_id' => 'required|exists:tourist_attractions,id',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|min:10',
        ]);

        // Check if user has visited the attraction
        $hasVisited = $request->user()
            ->bookings()
            ->where('tourist_attraction_id', $request->tourist_attraction_id)
            ->where('status', 'completed')
            ->exists();

        if (! $hasVisited) {
            return response()->json([
                'message' => 'You can only review attractions you have visited',
            ], 403);
        }

        // Check if user has already reviewed this attraction
        $hasReviewed = Review::where('user_id', $request->user()->id)
            ->where('tourist_attraction_id', $request->tourist_attraction_id)
            ->exists();

        if ($hasReviewed) {
            return response()->json([
                'message' => 'You have already reviewed this attraction',
            ], 422);
        }

        $review = Review::create([
            'user_id' => $request->user()->id,
            'tourist_attraction_id' => $request->tourist_attraction_id,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        // Update attraction average rating
        $attraction = TouristAttraction::find($request->tourist_attraction_id);
        $attraction->update([
            'average_rating' => $attraction->reviews()->avg('rating'),
        ]);

        return response()->json([
            'message' => 'Review created successfully',
            'review' => $review->load('user'),
        ], 201);
    }

    public function show(Review $review)
    {
        return response()->json([
            'review' => $review->load(['user', 'touristAttraction']),
        ]);
    }

    public function update(Request $request, Review $review)
    {
        // Check if the review belongs to the authenticated user
        if ($review->user_id !== auth()->id()) {
            return response()->json([
                'message' => 'Unauthorized',
            ], 403);
        }

        $request->validate([
            'rating' => 'sometimes|integer|min:1|max:5',
            'comment' => 'sometimes|string|min:10',
        ]);

        $review->update($request->only(['rating', 'comment']));

        // Update attraction average rating
        $attraction = $review->touristAttraction;
        $attraction->update([
            'average_rating' => $attraction->reviews()->avg('rating'),
        ]);

        return response()->json([
            'message' => 'Review updated successfully',
            'review' => $review->load('user'),
        ]);
    }

    public function destroy(Review $review)
    {
        // Check if the review belongs to the authenticated user
        if ($review->user_id !== auth()->id()) {
            return response()->json([
                'message' => 'Unauthorized',
            ], 403);
        }

        $attraction = $review->touristAttraction;
        $review->delete();

        // Update attraction average rating
        $attraction->update([
            'average_rating' => $attraction->reviews()->avg('rating'),
        ]);

        return response()->json([
            'message' => 'Review deleted successfully',
        ]);
    }

    public function userReviews(Request $request)
    {
        $reviews = $request->user()
            ->reviews()
            ->with('touristAttraction')
            ->latest()
            ->paginate(10);

        return response()->json([
            'reviews' => $reviews,
        ]);
    }
}
