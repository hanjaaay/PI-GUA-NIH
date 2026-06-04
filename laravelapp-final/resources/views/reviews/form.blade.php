<form action="{{ isset($review) ? route('reviews.update', $review) : route('reviews.store', $attraction) }}" 
      method="POST" 
      class="space-y-4">
    @csrf
    @if(isset($review))
        @method('PUT')
    @endif

    <div>
        <label for="rating" class="block text-sm font-medium text-gray-700">Rating</label>
        <div class="mt-1 flex items-center space-x-2">
            @for($i = 1; $i <= 5; $i++)
                <label class="cursor-pointer">
                    <input type="radio" 
                           name="rating" 
                           value="{{ $i }}" 
                           class="hidden" 
                           {{ (isset($review) && $review->rating == $i) || (!isset($review) && old('rating') == $i) ? 'checked' : '' }}
                           required>
                    <svg class="h-8 w-8 {{ (isset($review) && $review->rating >= $i) || (!isset($review) && old('rating') >= $i) ? 'text-yellow-400' : 'text-gray-300' }} hover:text-yellow-400" 
                         fill="currentColor" 
                         viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>
                </label>
            @endfor
        </div>
        @error('rating')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label for="comment" class="block text-sm font-medium text-gray-700">Your Review</label>
        <textarea name="comment" 
                  id="comment" 
                  rows="4" 
                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                  required>{{ isset($review) ? $review->comment : old('comment') }}</textarea>
        @error('comment')
            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
        @enderror
    </div>

    <div class="flex justify-end">
        <button type="submit" 
                class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
            {{ isset($review) ? 'Update Review' : 'Submit Review' }}
        </button>
    </div>
</form> 