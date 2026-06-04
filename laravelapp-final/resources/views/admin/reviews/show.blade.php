@extends('layouts.app')

@section('content')
<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Review Details
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('admin.reviews.edit', $review) }}" 
                   class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                    Edit Review
                </a>
                <form action="{{ route('admin.reviews.destroy', $review) }}" 
                      method="POST" 
                      onsubmit="return confirm('Are you sure you want to delete this review?');"
                      class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700">
                        Delete Review
                    </button>
                </form>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="space-y-6">
                        <!-- User Info -->
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">User Information</h3>
                            <div class="flex items-center space-x-4">
                                <div class="flex-shrink-0">
                                    <img class="h-12 w-12 rounded-full" src="{{ $review->user->profile_photo_url }}" alt="{{ $review->user->name }}">
                                </div>
                                <div>
                                    <h4 class="text-sm font-medium text-gray-900">{{ $review->user->name }}</h4>
                                    <p class="text-sm text-gray-500">{{ $review->user->email }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Attraction Info -->
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Attraction Information</h3>
                            <div class="flex items-center space-x-4">
                                <div class="flex-shrink-0">
                                    <img class="h-12 w-12 rounded-lg object-cover" src="{{ $review->touristAttraction->image_url }}" alt="{{ $review->touristAttraction->name }}">
                                </div>
                                <div>
                                    <h4 class="text-sm font-medium text-gray-900">{{ $review->touristAttraction->name }}</h4>
                                    <p class="text-sm text-gray-500">{{ $review->touristAttraction->location }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Review Details -->
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Review Details</h3>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <div class="flex items-center mb-4">
                                    @for($i = 1; $i <= 5; $i++)
                                        <svg class="h-6 w-6 {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}" 
                                             fill="currentColor" 
                                             viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                    @endfor
                                    <span class="ml-2 text-sm text-gray-500">{{ $review->created_at->format('M d, Y H:i') }}</span>
                                </div>
                                <p class="text-gray-700">{{ $review->comment }}</p>
                            </div>
                        </div>

                        <!-- Review Statistics -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <h4 class="text-sm font-medium text-gray-500">Total Reviews by User</h4>
                                <p class="mt-1 text-2xl font-semibold text-gray-900">{{ $review->user->reviews_count }}</p>
                            </div>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <h4 class="text-sm font-medium text-gray-500">Average Rating by User</h4>
                                <p class="mt-1 text-2xl font-semibold text-gray-900">
                                    {{ number_format($review->user->reviews()->avg('rating'), 1) }}
                                </p>
                            </div>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <h4 class="text-sm font-medium text-gray-500">Total Reviews for Attraction</h4>
                                <p class="mt-1 text-2xl font-semibold text-gray-900">{{ $review->touristAttraction->reviews_count }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
@endsection 