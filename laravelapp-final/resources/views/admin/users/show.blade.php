@extends('layouts.app')

@section('content')
<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                User Details
            </h2>
            <div class="flex space-x-2">
                <a href="{{ route('admin.users.edit', $user) }}" 
                   class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                    Edit User
                </a>
                @if($user->id !== auth()->id())
                    <form action="{{ route('admin.users.destroy', $user) }}" 
                          method="POST" 
                          onsubmit="return confirm('Are you sure you want to delete this user?');"
                          class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700">
                            Delete User
                        </button>
                    </form>
                @endif
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="space-y-6">
                        <!-- User Profile -->
                        <div class="flex items-center space-x-4">
                            <div class="flex-shrink-0">
                                <img class="h-16 w-16 rounded-full" src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}">
                            </div>
                            <div>
                                <h3 class="text-lg font-medium text-gray-900">{{ $user->name }}</h3>
                                <p class="text-sm text-gray-500">{{ $user->email }}</p>
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    {{ $user->role === 'admin' ? 'bg-purple-100 text-purple-800' : 'bg-green-100 text-green-800' }}">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </div>
                        </div>

                        <!-- User Statistics -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <h4 class="text-sm font-medium text-gray-500">Total Bookings</h4>
                                <p class="mt-1 text-2xl font-semibold text-gray-900">{{ $user->bookings_count }}</p>
                            </div>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <h4 class="text-sm font-medium text-gray-500">Total Reviews</h4>
                                <p class="mt-1 text-2xl font-semibold text-gray-900">{{ $user->reviews_count }}</p>
                            </div>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <h4 class="text-sm font-medium text-gray-500">Member Since</h4>
                                <p class="mt-1 text-2xl font-semibold text-gray-900">{{ $user->created_at->format('M Y') }}</p>
                            </div>
                        </div>

                        <!-- Recent Bookings -->
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Recent Bookings</h3>
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Attraction</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @forelse($user->bookings()->latest()->take(5)->get() as $booking)
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    {{ $booking->touristAttraction->name }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                        {{ $booking->status === 'paid' ? 'bg-green-100 text-green-800' : 
                                                           ($booking->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                                           'bg-red-100 text-red-800') }}">
                                                        {{ ucfirst($booking->status) }}
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    Rp {{ number_format($booking->total_price, 0, ',', '.') }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    {{ $booking->created_at->format('M d, Y') }}
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">
                                                    No bookings found
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <!-- Recent Reviews -->
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Recent Reviews</h3>
                            <div class="space-y-4">
                                @forelse($user->reviews()->latest()->take(5)->get() as $review)
                                    <div class="bg-gray-50 p-4 rounded-lg">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center space-x-2">
                                                <div class="flex items-center">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        <svg class="h-4 w-4 {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300' }}" 
                                                             fill="currentColor" 
                                                             viewBox="0 0 20 20">
                                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                        </svg>
                                                    @endfor
                                                </div>
                                                <span class="text-sm text-gray-500">{{ $review->created_at->format('M d, Y') }}</span>
                                            </div>
                                            <a href="{{ route('attractions.show', $review->touristAttraction) }}" 
                                               class="text-sm text-indigo-600 hover:text-indigo-900">
                                                {{ $review->touristAttraction->name }}
                                            </a>
                                        </div>
                                        <p class="mt-2 text-sm text-gray-700">{{ $review->comment }}</p>
                                    </div>
                                @empty
                                    <p class="text-center text-sm text-gray-500">No reviews found</p>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
@endsection 
