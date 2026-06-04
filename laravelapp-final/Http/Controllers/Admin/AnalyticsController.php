<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\AnalyticsService;
use Illuminate\Http\Request;

class AnalyticsController extends Controller
{
    protected $analyticsService;

    public function __construct(AnalyticsService $analyticsService)
    {
        $this->analyticsService = $analyticsService;
    }

    public function index(Request $request)
    {
        $period = $request->get('period', 'monthly');

        $data = [
            'bookingAnalytics' => $this->analyticsService->getBookingAnalytics($period),
            'attractionAnalytics' => $this->analyticsService->getAttractionAnalytics(),
            'userAnalytics' => $this->analyticsService->getUserAnalytics(),
            'revenueAnalytics' => $this->analyticsService->getRevenueAnalytics($period),
            'reviewAnalytics' => $this->analyticsService->getReviewAnalytics(),
            'peakHoursAnalytics' => $this->analyticsService->getPeakHoursAnalytics(),
            'popularDaysAnalytics' => $this->analyticsService->getPopularDaysAnalytics(),
            'conversionRate' => $this->analyticsService->getConversionRate(),
            'retentionRate' => $this->analyticsService->getRetentionRate(),
        ];

        return view('admin.analytics.index', compact('data', 'period'));
    }

    public function export(Request $request)
    {
        $period = $request->get('period', 'monthly');
        $type = $request->get('type', 'all');

        $data = [];
        switch ($type) {
            case 'bookings':
                $data = $this->analyticsService->getBookingAnalytics($period);
                break;
            case 'attractions':
                $data = $this->analyticsService->getAttractionAnalytics();
                break;
            case 'users':
                $data = $this->analyticsService->getUserAnalytics();
                break;
            case 'revenue':
                $data = $this->analyticsService->getRevenueAnalytics($period);
                break;
            case 'reviews':
                $data = $this->analyticsService->getReviewAnalytics();
                break;
            default:
                $data = [
                    'bookings' => $this->analyticsService->getBookingAnalytics($period),
                    'attractions' => $this->analyticsService->getAttractionAnalytics(),
                    'users' => $this->analyticsService->getUserAnalytics(),
                    'revenue' => $this->analyticsService->getRevenueAnalytics($period),
                    'reviews' => $this->analyticsService->getReviewAnalytics(),
                ];
        }

        return response()->json($data);
    }
}
