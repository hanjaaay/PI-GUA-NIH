@extends('layouts.app')

@section('content')
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Bookings Management
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4 flex items-center" role="alert">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4" /></svg>
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    <!-- Export & Filter -->
                    <div class="mb-4 flex flex-col md:flex-row md:items-end md:justify-between gap-4">
                        <div class="flex gap-2">
                            <form action="{{ route('admin.export.bookings') }}" method="GET" class="inline">
                                <input type="hidden" name="start_date" value="{{ request('start_date') }}">
                                <input type="hidden" name="end_date" value="{{ request('end_date') }}">
                                <button type="submit" class="inline-flex items-center px-3 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                                    Export Bookings
                                </button>
                            </form>
                            <form action="{{ route('admin.export.revenue') }}" method="GET" class="inline">
                                <input type="hidden" name="start_date" value="{{ request('start_date') }}">
                                <input type="hidden" name="end_date" value="{{ request('end_date') }}">
                                <button type="submit" class="inline-flex items-center px-3 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" /></svg>
                                    Export Revenue
                                </button>
                            </form>
                        </div>
                        <form action="{{ route('admin.bookings.index') }}" method="GET" class="flex flex-wrap gap-2 items-end">
                            <div>
                                <label for="start_date" class="block text-xs font-medium text-gray-700">Start Date</label>
                                <input type="date" name="start_date" id="start_date" value="{{ request('start_date') }}"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-xs">
                            </div>
                            <div>
                                <label for="end_date" class="block text-xs font-medium text-gray-700">End Date</label>
                                <input type="date" name="end_date" id="end_date" value="{{ request('end_date') }}"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-xs">
                            </div>
                            <button type="submit" class="inline-flex items-center px-3 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                                Filter
                            </button>
                        </form>
                    </div>

                    <div class="overflow-x-auto rounded-lg border border-gray-200">
                        <table class="min-w-full divide-y divide-gray-200 text-xs md:text-sm">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-4 py-2 text-left font-semibold text-gray-500 uppercase tracking-wider">User</th>
                                    <th class="px-4 py-2 text-left font-semibold text-gray-500 uppercase tracking-wider">Attraction</th>
                                    <th class="px-4 py-2 text-left font-semibold text-gray-500 uppercase tracking-wider">Visit Date</th>
                                    <th class="px-4 py-2 text-left font-semibold text-gray-500 uppercase tracking-wider">Tickets</th>
                                    <th class="px-4 py-2 text-left font-semibold text-gray-500 uppercase tracking-wider">Amount</th>
                                    <th class="px-4 py-2 text-left font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-4 py-2 text-left font-semibold text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($bookings as $booking)
                                    <tr>
                                        <td class="px-4 py-2 whitespace-nowrap">
                                            <div class="flex items-center gap-2">
                                                <img class="h-8 w-8 rounded-full object-cover border" src="{{ $booking->user->profile_photo_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($booking->user->name) }}" alt="{{ $booking->user->name }}">
                                                <span class="font-medium">{{ $booking->user->name }}</span>
                                            </div>
                                        </td>
                                        <td class="px-4 py-2 whitespace-nowrap">
                                            <span class="text-gray-900">{{ $booking->touristAttraction->name }}</span>
                                        </td>
                                        <td class="px-4 py-2 whitespace-nowrap text-gray-500">
                                            {{ $booking->visit_date->format('M d, Y') }}
                                        </td>
                                        <td class="px-4 py-2 whitespace-nowrap text-gray-500">
                                            {{ $booking->quantity }}
                                        </td>
                                        <td class="px-4 py-2 whitespace-nowrap text-gray-900">
                                            Rp {{ number_format($booking->total_price, 0, ',', '.') }}
                                        </td>
                                        <td class="px-4 py-2 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                                {{ $booking->status === 'paid' ? 'bg-green-100 text-green-800' :
                                                   ($booking->status === 'pending' ? 'bg-yellow-100 text-yellow-800' :
                                                   'bg-red-100 text-red-800') }}">
                                                {{ ucfirst($booking->status) }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-2 whitespace-nowrap text-sm font-medium">
                                            <div class="flex space-x-2">
                                                <a href="{{ route('admin.bookings.show', $booking) }}"
                                                   class="text-indigo-600 hover:text-indigo-900 flex items-center gap-1" title="View">
                                                    <svg class="w-4 h-4" style="width:1.25rem;height:1.25rem;min-width:1.25rem;min-height:1.25rem;max-width:1.25rem;max-height:1.25rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg>
                                                </a>
                                                <a href="{{ route('admin.bookings.edit', $booking) }}"
                                                   class="text-yellow-600 hover:text-yellow-900 flex items-center gap-1" title="Edit">
                                                    <svg class="w-4 h-4" style="width:1.25rem;height:1.25rem;min-width:1.25rem;min-height:1.25rem;max-width:1.25rem;max-height:1.25rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536M9 13h3l8-8a2.828 2.828 0 00-4-4l-8 8v3z" /></svg>
                                                </a>
                                                <form action="{{ route('admin.bookings.destroy', $booking) }}"
                                                      method="POST"
                                                      onsubmit="return confirm('Are you sure you want to delete this booking?');"
                                                      class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                            class="text-red-600 hover:text-red-900 flex items-center gap-1" title="Delete">
                                                        <svg class="w-4 h-4" style="width:1.25rem;height:1.25rem;min-width:1.25rem;min-height:1.25rem;max-width:1.25rem;max-height:1.25rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $bookings->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

<style>
    .pagination svg {
        width: 1.25rem !important;
        height: 1.25rem !important;
        min-width: 1.25rem !important;
        min-height: 1.25rem !important;
        max-width: 1.25rem !important;
        max-height: 1.25rem !important;
    }
</style> 
