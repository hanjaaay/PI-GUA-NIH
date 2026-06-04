<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\ExportService;
use Illuminate\Http\Request;

class ExportController extends Controller
{
    protected $exportService;

    public function __construct(ExportService $exportService)
    {
        $this->exportService = $exportService;
    }

    public function bookings(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        return $this->exportService->exportBookings($startDate, $endDate);
    }

    public function revenue(Request $request)
    {
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        return $this->exportService->exportRevenue($startDate, $endDate);
    }
}
