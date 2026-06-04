<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Category;
use App\Models\TouristAttraction;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

class CacheService
{
    protected $defaultTtl = 3600; // 1 hour

    public function getPopularAttractions($limit = 5)
    {
        return Cache::remember('popular_attractions', $this->defaultTtl, function () use ($limit) {
            return TouristAttraction::withCount('bookings')
                ->orderBy('bookings_count', 'desc')
                ->take($limit)
                // MODIFIKASI: Tambahkan eager loading tickets untuk popular attractions jika diperlukan di tampilan index
                ->with(['tickets' => function ($query) {
                    $query->orderBy('price', 'asc');
                }])
                ->get();
        });
    }

    public function getCategories()
    {
        return Cache::remember('categories', $this->defaultTtl, function () {
            return Category::withCount('touristAttractions')->get();
        });
    }

    public function getAttractionDetails($id)
    {
        return Cache::remember('attraction_'.$id, $this->defaultTtl, function () use ($id) {
            return TouristAttraction::with([
                'category',
                'reviews.user',
                'tickets',
            ])
                ->findOrFail($id);
        });
    }

    public function getAttractionsByCategory($categoryId)
    {
        return Cache::remember('category_'.$categoryId.'_attractions', $this->defaultTtl, function () use ($categoryId) {
            return TouristAttraction::where('category_id', $categoryId)
                ->with([
                    'category',
                    'reviews',
                    'tickets', // MODIFIKASI: Tambahkan eager load tickets untuk atraksi terkait
                ])
                ->get();
        });
    }

    public function getDashboardStats()
    {
        return Cache::remember('dashboard_stats', 300, function () { // 5 minutes
            return [
                'total_bookings' => Booking::count(),
                'total_revenue' => Booking::where('status', 'completed')->sum('total_price'),
                'total_attractions' => TouristAttraction::count(),
                'total_users' => \App\Models\User::count(),
                'recent_bookings' => Booking::with(['user', 'touristAttraction'])
                    ->latest()
                    ->take(5)
                    ->get(),
                'popular_attractions' => $this->getPopularAttractions(),
            ];
        });
    }

    public function getMonthlyStats()
    {
        return Cache::remember('monthly_stats', 3600, function () {
            $startDate = Carbon::now()->startOfMonth();
            $endDate = Carbon::now()->endOfMonth();

            return [
                'bookings' => Booking::whereBetween('created_at', [$startDate, $endDate])->count(),
                'revenue' => Booking::whereBetween('created_at', [$startDate, $endDate])
                    ->where('status', 'completed')
                    ->sum('total_price'),
                'new_users' => \App\Models\User::whereBetween('created_at', [$startDate, $endDate])->count(),
            ];
        });
    }

    public function clearAttractionCache($id = null)
    {
        if ($id) {
            Cache::forget('attraction_'.$id);
            // MODIFIKASI: Ambil ID kategori dari atraksi untuk membersihkan cache kategori terkait
            $attraction = TouristAttraction::find($id);
            if ($attraction && $attraction->category_id) {
                Cache::forget('category_'.$attraction->category_id.'_attractions');
            }
        }
        // MODIFIKASI: Selalu bersihkan cache daftar popular attractions karena update/delete bisa mempengaruhinya
        Cache::forget('popular_attractions');
        // MODIFIKASI: Bersihkan juga cache dashboard karena total attractions dan popular attractions bisa berubah
        Cache::forget('dashboard_stats');
    }

    public function clearCategoryCache($id = null)
    {
        if ($id) {
            Cache::forget('category_'.$id.'_attractions');
        } else {
            Cache::forget('categories');
        }
        // MODIFIKASI: Bersihkan popular attractions dan dashboard jika kategori berubah
        Cache::forget('popular_attractions');
        Cache::forget('dashboard_stats');
    }

    public function clearDashboardCache()
    {
        Cache::forget('dashboard_stats');
        Cache::forget('monthly_stats');
    }

    public function clearAllCache()
    {
        Cache::flush();
    }

    /**
     * Helper method to remember data in cache.
     * Use this if you want to implement the CacheService in your controllers directly.
     * Example: $this->cacheService->remember('my_key', function() { ... });
     *
     * @return mixed
     */
    public function remember(string $key, callable $callback, ?int $ttl = null)
    {
        return Cache::remember($key, $ttl ?? $this->defaultTtl, $callback);
    }
}
