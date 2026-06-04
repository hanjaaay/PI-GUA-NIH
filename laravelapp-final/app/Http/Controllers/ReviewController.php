<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\TouristAttraction;
use App\Notifications\NewReview;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function store(Request $request, TouristAttraction $attraction)
    {
        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:1000',
        ]);

        $review = new Review([
            'user_id' => Auth::id(),
            'tourist_attraction_id' => $attraction->id,
            'rating' => $validated['rating'],
            'comment' => $validated['comment'],
        ]);

        $review->save();

        // Notify admin about new review
        $admin = \App\Models\User::where('role', 'admin')->first();
        if ($admin) {
            $admin->notify(new NewReview($review));
        }

        return redirect()->route('attractions.show', $attraction)
            ->with('success', 'Review submitted successfully');
    }

    public function update(Request $request, Review $review)
    {
        $this->authorize('update', $review);

        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:1000',
        ]);

        $review->update($validated);

        return redirect()->route('attractions.show', $review->touristAttraction)
            ->with('success', 'Review updated successfully');
    }

    public function destroy(Review $review)
    {
        $this->authorize('delete', $review);

        $review->delete();

        return redirect()->route('attractions.show', $review->touristAttraction)
            ->with('success', 'Review deleted successfully');
    }
}
