@extends('layouts.app')

@section('content')
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Edit Booking
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form action="{{ route('admin.bookings.update', $booking) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- Status -->
                        <div class="mb-4">
                            <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                            <select name="status" id="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="pending" {{ old('status', $booking->status) === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="confirmed" {{ old('status', $booking->status) === 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                                <option value="cancelled" {{ old('status', $booking->status) === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                            @error('status')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Visit Date -->
                        <div class="mb-4">
                            <label for="visit_date" class="block text-sm font-medium text-gray-700">Visit Date</label>
                            <input type="date" 
                                   name="visit_date" 
                                   id="visit_date" 
                                   value="{{ old('visit_date', $booking->visit_date->format('Y-m-d')) }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @error('visit_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Quantity -->
                        <div class="mb-4">
                            <label for="quantity" class="block text-sm font-medium text-gray-700">Number of Tickets</label>
                            <input type="number" 
                                   name="quantity" 
                                   id="quantity" 
                                   min="1"
                                   value="{{ old('quantity', $booking->quantity) }}"
                                   class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @error('quantity')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Total Price -->
                        <div class="mb-4">
                            <label for="total_price" class="block text-sm font-medium text-gray-700">Total Price</label>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 sm:text-sm">Rp</span>
                                </div>
                                <input type="number" 
                                       name="total_price" 
                                       id="total_price" 
                                       value="{{ old('total_price', $booking->total_price) }}"
                                       class="pl-12 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                            @error('total_price')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex justify-end space-x-4">
                            <a href="{{ route('admin.bookings.index') }}" 
                               class="inline-flex items-center px-4 py-2 bg-gray-300 border border-transparent rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-400">
                                Cancel
                            </a>
                            <button type="submit" 
                                    class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">
                                Update Booking
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
@endsection 
