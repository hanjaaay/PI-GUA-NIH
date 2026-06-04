@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Analytics Dashboard</h1>
        <div class="flex space-x-4">
            <select id="period" class="rounded-lg border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                <option value="weekly" {{ $period === 'weekly' ? 'selected' : '' }}>Weekly</option>
                <option value="monthly" {{ $period === 'monthly' ? 'selected' : '' }}>Monthly</option>
                <option value="yearly" {{ $period === 'yearly' ? 'selected' : '' }}>Yearly</option>
            </select>
            <button onclick="exportData()" class="bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600">
                Export Data
            </button>
        </div>
    </div>

    <!-- Overview Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-700 mb-2">Total Bookings</h3>
            <p class="text-3xl font-bold text-blue-600">{{ $data['bookingAnalytics']['total'] }}</p>
            <p class="text-sm text-gray-500 mt-2">
                {{ $data['bookingAnalytics']['growth'] > 0 ? '+' : '' }}{{ $data['bookingAnalytics']['growth'] }}% from last period
            </p>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-700 mb-2">Total Revenue</h3>
            <p class="text-3xl font-bold text-green-600">Rp {{ number_format($data['revenueAnalytics']['total']) }}</p>
            <p class="text-sm text-gray-500 mt-2">
                {{ $data['revenueAnalytics']['growth'] > 0 ? '+' : '' }}{{ $data['revenueAnalytics']['growth'] }}% from last period
            </p>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-700 mb-2">Conversion Rate</h3>
            <p class="text-3xl font-bold text-purple-600">{{ number_format($data['conversionRate'], 2) }}%</p>
            <p class="text-sm text-gray-500 mt-2">Visitors to bookings</p>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-700 mb-2">Retention Rate</h3>
            <p class="text-3xl font-bold text-orange-600">{{ number_format($data['retentionRate'], 2) }}%</p>
            <p class="text-sm text-gray-500 mt-2">Repeat customers</p>
        </div>
    </div>

    <!-- Charts -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Bookings Chart -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-700 mb-4">Booking Trends</h3>
            <canvas id="bookingsChart" height="300"></canvas>
        </div>

        <!-- Revenue Chart -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-700 mb-4">Revenue Trends</h3>
            <canvas id="revenueChart" height="300"></canvas>
        </div>
    </div>

    <!-- Popular Attractions -->
    <div class="bg-white rounded-lg shadow p-6 mb-8">
        <h3 class="text-lg font-semibold text-gray-700 mb-4">Popular Attractions</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Attraction</th>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Bookings</th>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Average Rating</th>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Revenue</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($data['attractionAnalytics'] as $attraction)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $attraction['name'] }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $attraction['total_bookings'] }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ number_format($attraction['average_rating'], 1) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Rp {{ number_format($attraction['revenue']) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Peak Hours & Popular Days -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Peak Hours -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-700 mb-4">Peak Booking Hours</h3>
            <canvas id="peakHoursChart" height="300"></canvas>
        </div>

        <!-- Popular Days -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-700 mb-4">Popular Booking Days</h3>
            <canvas id="popularDaysChart" height="300"></canvas>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Initialize charts
document.addEventListener('DOMContentLoaded', function() {
    // Bookings Chart
    new Chart(document.getElementById('bookingsChart'), {
        type: 'line',
        data: {
            labels: {!! json_encode($data['bookingAnalytics']['labels']) !!},
            datasets: [{
                label: 'Bookings',
                data: {!! json_encode($data['bookingAnalytics']['data']) !!},
                borderColor: 'rgb(59, 130, 246)',
                tension: 0.1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });

    // Revenue Chart
    new Chart(document.getElementById('revenueChart'), {
        type: 'line',
        data: {
            labels: {!! json_encode($data['revenueAnalytics']['labels']) !!},
            datasets: [{
                label: 'Revenue',
                data: {!! json_encode($data['revenueAnalytics']['data']) !!},
                borderColor: 'rgb(16, 185, 129)',
                tension: 0.1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });

    // Peak Hours Chart
    new Chart(document.getElementById('peakHoursChart'), {
        type: 'bar',
        data: {
            labels: {!! json_encode($data['peakHoursAnalytics']['labels']) !!},
            datasets: [{
                label: 'Bookings',
                data: {!! json_encode($data['peakHoursAnalytics']['data']) !!},
                backgroundColor: 'rgb(139, 92, 246)'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });

    // Popular Days Chart
    new Chart(document.getElementById('popularDaysChart'), {
        type: 'bar',
        data: {
            labels: {!! json_encode($data['popularDaysAnalytics']['labels']) !!},
            datasets: [{
                label: 'Bookings',
                data: {!! json_encode($data['popularDaysAnalytics']['data']) !!},
                backgroundColor: 'rgb(249, 115, 22)'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false
        }
    });
});

// Period change handler
document.getElementById('period').addEventListener('change', function() {
    window.location.href = `{{ route('admin.analytics.index') }}?period=${this.value}`;
});

// Export data
function exportData() {
    const period = document.getElementById('period').value;
    window.location.href = `{{ route('admin.analytics.export') }}?period=${period}`;
}
</script>
@endpush
@endsection 