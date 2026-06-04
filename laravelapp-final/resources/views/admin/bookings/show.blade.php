@extends('layouts.app')

@section('content')
<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Booking Details
            </h2>
            <div class="flex space-x-4">
                <a href="{{ route('admin.bookings.edit', $booking) }}" 
                   class="inline-flex items-center px-4 py-2 bg-yellow-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-700">
                    Edit Booking
                </a>
                <form action="{{ route('admin.bookings.destroy', $booking) }}" 
                      method="POST" 
                      onsubmit="return confirm('Are you sure you want to delete this booking?');"
                      class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700">
                        Delete Booking
                    </button>
                </form>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- User Information -->
                        <div class="bg-gray-50 p-6 rounded-lg">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">User Information</h3>
                            <div class="flex items-center space-x-4">
                                <img class="h-16 w-16 rounded-full" 
                                     src="{{ $booking->user->profile_photo_url }}" 
                                     alt="{{ $booking->user->name }}">
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ $booking->user->name }}</p>
                                    <p class="text-sm text-gray-500">{{ $booking->user->email }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Attraction Information -->
                        <div class="bg-gray-50 p-6 rounded-lg">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Attraction Information</h3>
                            <div class="flex items-center space-x-4">
                                <img class="h-16 w-16 object-cover rounded-lg" 
                                     src="{{ $booking->touristAttraction->image_url }}" 
                                     alt="{{ $booking->touristAttraction->name }}">
                                <div>
                                    <p class="text-sm font-medium text-gray-900">{{ $booking->touristAttraction->name }}</p>
                                    <p class="text-sm text-gray-500">{{ $booking->touristAttraction->location }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Booking Details -->
                        <div class="bg-gray-50 p-6 rounded-lg md:col-span-2">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Booking Details</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <p class="text-sm text-gray-500">Booking ID</p>
                                    <p class="text-sm font-medium text-gray-900">{{ $booking->id }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Status</p>
                                    <p class="text-sm font-medium text-gray-900">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            {{ $booking->status === 'paid' ? 'bg-green-100 text-green-800' : 
                                               ($booking->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                               'bg-red-100 text-red-800') }}">
                                            {{ ucfirst($booking->status) }}
                                        </span>
                                    </p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Visit Date</p>
                                    <p class="text-sm font-medium text-gray-900">{{ $booking->visit_date->format('M d, Y') }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Number of Tickets</p>
                                    <p class="text-sm font-medium text-gray-900">{{ $booking->quantity }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Total Amount</p>
                                    <p class="text-sm font-medium text-gray-900">Rp {{ number_format($booking->total_price, 0, ',', '.') }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Created At</p>
                                    <p class="text-sm font-medium text-gray-900">{{ $booking->created_at->format('M d, Y H:i') }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Statistics -->
                        <div class="bg-gray-50 p-6 rounded-lg md:col-span-2">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Statistics</h3>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div class="bg-white p-4 rounded-lg shadow">
                                    <p class="text-sm text-gray-500">Total Bookings by User</p>
                                    <p class="text-2xl font-semibold text-gray-900">{{ $booking->user->bookings->count() }}</p>
                                </div>
                                <div class="bg-white p-4 rounded-lg shadow">
                                    <p class="text-sm text-gray-500">Total Bookings for Attraction</p>
                                    <p class="text-2xl font-semibold text-gray-900">{{ $booking->touristAttraction->bookings->count() }}</p>
                                </div>
                                <div class="bg-white p-4 rounded-lg shadow">
                                    <p class="text-sm text-gray-500">Average Tickets per Booking</p>
                                    <p class="text-2xl font-semibold text-gray-900">
                                        {{ number_format($booking->touristAttraction->bookings->avg('quantity'), 1) }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
@endsection 
