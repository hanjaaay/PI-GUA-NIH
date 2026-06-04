<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\TouristAttraction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class AnalyticsService
{
    public function getBookingAnalytics($period = 'monthly')
    {
        $query = Booking::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('COUNT(*) as total_bookings'),
            DB::raw('SUM(total_price) as total_revenue'),
            DB::raw('AVG(total_price) as average_revenue')
        )
            ->where('status', 'completed')
            ->groupBy('date')
            ->orderBy('date');

        if ($period === 'weekly') {
            $query->whereBetween('created_at', [Carbon::now()->subWeek(), Carbon::now()]);
        } elseif ($period === 'monthly') {
            $query->whereBetween('created_at', [Carbon::now()->subMonth(), Carbon::now()]);
        } elseif ($period === 'yearly') {
            $query->whereBetween('created_at', [Carbon::now()->subYear(), Carbon::now()]);
        }

        return $query->get();
    }

    public function getAttractionAnalytics()
    {
        return TouristAttraction::select(
            'tourist_attractions.*',
            DB::raw('COUNT(bookings.id) as total_bookings'),
            DB::raw('SUM(bookings.total_price) as total_revenue'),
            DB::raw('AVG(reviews.rating) as average_rating')
        )
            ->leftJoin('bookings', 'tourist_attractions.id', '=', 'bookings.tourist_attraction_id')
            ->leftJoin('reviews', 'tourist_attractions.id', '=', 'reviews.tourist_attraction_id')
            ->where('bookings.status', 'completed')
            ->groupBy('tourist_attractions.id')
            ->orderBy('total_bookings', 'desc')
            ->get();
    }

    public function getUserAnalytics()
    {
        return User::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('COUNT(*) as new_users'),
            DB::raw('COUNT(CASE WHEN role = "admin" THEN 1 END) as new_admins')
        )
            ->groupBy('date')
            ->orderBy('date', 'desc')
            ->limit(30)
            ->get();
    }

    public function getRevenueAnalytics($period = 'monthly')
    {
        $query = Booking::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('SUM(total_price) as revenue'),
            DB::raw('COUNT(*) as bookings')
        )
            ->where('status', 'completed')
            ->groupBy('date')
            ->orderBy('date');

        if ($period === 'weekly') {
            $query->whereBetween('created_at', [Carbon::now()->subWeek(), Carbon::now()]);
        } elseif ($period === 'monthly') {
            $query->whereBetween('created_at', [Carbon::now()->subMonth(), Carbon::now()]);
        } elseif ($period === 'yearly') {
            $query->whereBetween('created_at', [Carbon::now()->subYear(), Carbon::now()]);
        }

        return $query->get();
    }

    public function getReviewAnalytics()
    {
        return TouristAttraction::select(
            'tourist_attractions.*',
            DB::raw('COUNT(reviews.id) as total_reviews'),
            DB::raw('AVG(reviews.rating) as average_rating'),
            DB::raw('COUNT(CASE WHEN reviews.rating >= 4 THEN 1 END) as positive_reviews')
        )
            ->leftJoin('reviews', 'tourist_attractions.id', '=', 'reviews.tourist_attraction_id')
            ->groupBy('tourist_attractions.id')
            ->orderBy('average_rating', 'desc')
            ->get();
    }

    public function getPeakHoursAnalytics()
    {
        return Booking::select(
            DB::raw('HOUR(created_at) as hour'),
            DB::raw('COUNT(*) as total_bookings')
        )
            ->where('status', 'completed')
            ->groupBy('hour')
            ->orderBy('hour')
            ->get();
    }

    public function getPopularDaysAnalytics()
    {
        return Booking::select(
            DB::raw('DAYNAME(visit_date) as day'),
            DB::raw('COUNT(*) as total_bookings')
        )
            ->where('status', 'completed')
            ->groupBy('day')
            ->orderBy('total_bookings', 'desc')
            ->get();
    }

    public function getConversionRate()
    {
        $totalVisitors = User::count();
        $totalBookings = Booking::where('status', 'completed')->count();

        return [
            'total_visitors' => $totalVisitors,
            'total_bookings' => $totalBookings,
            'conversion_rate' => $totalVisitors > 0 ? ($totalBookings / $totalVisitors) * 100 : 0,
        ];
    }

    public function getRetentionRate()
    {
        $totalUsers = User::count();
        $returningUsers = Booking::select('user_id')
            ->groupBy('user_id')
            ->having(DB::raw('COUNT(*)'), '>', 1)
            ->count();

        return [
            'total_users' => $totalUsers,
            'returning_users' => $returningUsers,
            'retention_rate' => $totalUsers > 0 ? ($returningUsers / $totalUsers) * 100 : 0,
        ];
    }
}
