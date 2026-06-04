<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\CacheService;

class DashboardController extends Controller
{
    protected $cacheService;

    public function __construct(CacheService $cacheService)
    {
        $this->cacheService = $cacheService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        $stats = $this->cacheService->getDashboardStats();
        $monthlyStats = $this->cacheService->getMonthlyStats();

        return view('admin.dashboard', compact('stats', 'monthlyStats'));
    }

    public function clearCache()
    {
        $this->cacheService->clearAllCache();

        return redirect()->route('admin.dashboard')
            ->with('success', 'Cache cleared successfully');
    }
}
