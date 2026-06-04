@extends('layouts.app')

@section('content')
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Edit Review
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form action="{{ route('admin.reviews.update', $review) }}" method="POST" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <div>
                            <label for="rating" class="block text-sm font-medium text-gray-700">Rating</label>
                            <div class="mt-1 flex items-center space-x-2">
                                @for($i = 1; $i <= 5; $i++)
                                    <label class="cursor-pointer">
                                        <input type="radio" 
                                               name="rating" 
                                               value="{{ $i }}" 
                                               class="hidden" 
                                               {{ old('rating', $review->rating) == $i ? 'checked' : '' }}
                                               required>
                                        <svg class="h-8 w-8 {{ old('rating', $review->rating) >= $i ? 'text-yellow-400' : 'text-gray-300' }} hover:text-yellow-400" 
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
                            <label for="comment" class="block text-sm font-medium text-gray-700">Comment</label>
                            <textarea name="comment" 
                                      id="comment" 
                                      rows="4" 
                                      class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                      required>{{ old('comment', $review->comment) }}</textarea>
                            @error('comment')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex justify-end">
                            <a href="{{ route('admin.reviews.index') }}" 
                               class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400 mr-2">
                                Cancel
                            </a>
                            <button type="submit" 
                                    class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                                Update Review
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
@endsection 