@extends('layouts.app')

@section('content')
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Review Details
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="space-y-6">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="flex-shrink-0">
                                    <img class="h-12 w-12 rounded-full" 
                                         src="{{ $review->user->profile_photo_url }}" 
                                         alt="{{ $review->user->name }}">
                                </div>
                                <div>
                                    <h3 class="text-lg font-medium text-gray-900">{{ $review->user->name }}</h3>
                                    <p class="text-sm text-gray-500">{{ $review->created_at->format('F j, Y') }}</p>
                                </div>
                            </div>
                            @if(auth()->check() && (auth()->user()->id === $review->user_id || auth()->user()->isAdmin()))
                                <div class="flex items-center space-x-2">
                                    <a href="{{ route('reviews.edit', $review) }}" 
                                       class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                                        Edit Review
                                    </a>
                                    <form action="{{ route('reviews.destroy', $review) }}" 
                                          method="POST" 
                                          onsubmit="return confirm('Are you sure you want to delete this review?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700">
                                            Delete Review
                                        </button>
                                    </form>
                                </div>
                            @endif
                        </div>

                        <div>
                            <div class="flex items-center">
                                @for($i = 1; $i <= 5; $i++)
                                    <svg class="h-6 w-6 {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}" 
                                         fill="currentColor" 
                                         viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                @endfor
                            </div>
                            <p class="mt-4 text-gray-700 text-lg">{{ $review->comment }}</p>
                        </div>

                        <div class="border-t border-gray-200 pt-6">
                            <h3 class="text-lg font-medium text-gray-900">About the Attraction</h3>
                            <div class="mt-4">
                                <a href="{{ route('attractions.show', $review->touristAttraction) }}" 
                                   class="text-indigo-600 hover:text-indigo-900">
                                    {{ $review->touristAttraction->name }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection 
