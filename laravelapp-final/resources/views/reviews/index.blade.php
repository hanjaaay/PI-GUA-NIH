<div class="space-y-6">
    @if($reviews->isEmpty())
        <p class="text-gray-500 text-center py-4">No reviews yet. Be the first to review this attraction!</p>
    @else
        @foreach($reviews as $review)
            <div class="bg-white shadow rounded-lg p-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="flex-shrink-0">
                            <img class="h-10 w-10 rounded-full" 
                                 src="{{ $review->user->profile_photo_url }}" 
                                 alt="{{ $review->user->name }}">
                        </div>
                        <div>
                            <h3 class="text-sm font-medium text-gray-900">{{ $review->user->name }}</h3>
                            <p class="text-sm text-gray-500">{{ $review->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                    @if(auth()->check() && (auth()->user()->id === $review->user_id || auth()->user()->isAdmin()))
                        <div class="flex items-center space-x-2">
                            <a href="{{ route('reviews.edit', $review) }}" 
                               class="text-indigo-600 hover:text-indigo-900">
                                Edit
                            </a>
                            <form action="{{ route('reviews.destroy', $review) }}" 
                                  method="POST" 
                                  onsubmit="return confirm('Are you sure you want to delete this review?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="text-red-600 hover:text-red-900">
                                    Delete
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
                <div class="mt-4">
                    <div class="flex items-center">
                        @for($i = 1; $i <= 5; $i++)
                            <svg class="h-5 w-5 {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}" 
                                 fill="currentColor" 
                                 viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                            </svg>
                        @endfor
                    </div>
                    <p class="mt-2 text-gray-700">{{ $review->comment }}</p>
                </div>
            </div>
        @endforeach

        <div class="mt-6">
            {{ $reviews->links() }}
        </div>
    @endif
</div> 
