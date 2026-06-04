<?php

namespace App\Services;

use App\Models\Booking;
use App\Support\BookingStatuses;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ExportService
{
    public function exportBookings($startDate = null, $endDate = null)
    {
        $query = Booking::with(['user', 'touristAttraction'])
            ->when($startDate, function ($q) use ($startDate) {
                return $q->whereDate('created_at', '>=', $startDate);
            })
            ->when($endDate, function ($q) use ($endDate) {
                return $q->whereDate('created_at', '<=', $endDate);
            });

        $bookings = $query->get();

        $spreadsheet = new Spreadsheet;
        $sheet = $spreadsheet->getActiveSheet();

        // Set headers
        $headers = [
            'A1' => 'Booking ID',
            'B1' => 'User',
            'C1' => 'Attraction',
            'D1' => 'Visit Date',
            'E1' => 'Tickets',
            'F1' => 'Total Price',
            'G1' => 'Status',
            'H1' => 'Created At',
        ];

        foreach ($headers as $cell => $value) {
            $sheet->setCellValue($cell, $value);
        }

        // Style headers
        $headerStyle = [
            'font' => ['bold' => true],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'E2E8F0'],
            ],
            'borders' => [
                'allBorders' => ['borderStyle' => Border::BORDER_THIN],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
            ],
        ];

        $sheet->getStyle('A1:H1')->applyFromArray($headerStyle);

        // Add data
        $row = 2;
        foreach ($bookings as $booking) {
            $sheet->setCellValue('A'.$row, $booking->id);
            $sheet->setCellValue('B'.$row, $booking->user->name);
            $sheet->setCellValue('C'.$row, $booking->touristAttraction->name);
            $sheet->setCellValue('D'.$row, $booking->visit_date->format('Y-m-d'));
            $sheet->setCellValue('E'.$row, $booking->quantity);
            $sheet->setCellValue('F'.$row, 'Rp '.number_format($booking->total_price, 0, ',', '.'));
            $sheet->setCellValue('G'.$row, ucfirst($booking->status));
            $sheet->setCellValue('H'.$row, $booking->created_at->format('Y-m-d H:i:s'));
            $row++;
        }

        // Auto-size columns
        foreach (range('A', 'H') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Add borders to data
        $dataStyle = [
            'borders' => [
                'allBorders' => ['borderStyle' => Border::BORDER_THIN],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
            ],
        ];

        $sheet->getStyle('A2:H'.($row - 1))->applyFromArray($dataStyle);

        // Create writer
        $writer = new Xlsx($spreadsheet);

        // Set headers for download
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="bookings_'.date('Y-m-d').'.xlsx"');
        header('Cache-Control: max-age=0');

        // Save file
        $writer->save('php://output');
        exit;
    }

    public function exportRevenue($startDate = null, $endDate = null)
    {
        $query = Booking::whereIn('status', [
            BookingStatuses::PAID,
            BookingStatuses::COMPLETED,
        ])
            ->when($startDate, function ($q) use ($startDate) {
                return $q->whereDate('created_at', '>=', $startDate);
            })
            ->when($endDate, function ($q) use ($endDate) {
                return $q->whereDate('created_at', '<=', $endDate);
            });

        $revenue = $query->get()
            ->groupBy(function ($booking) {
                return $booking->created_at->format('Y-m');
            })
            ->map(function ($bookings) {
                return [
                    'total' => $bookings->sum('total_price'),
                    'count' => $bookings->count(),
                ];
            });

        $spreadsheet = new Spreadsheet;
        $sheet = $spreadsheet->getActiveSheet();

        // Set headers
        $headers = [
            'A1' => 'Period',
            'B1' => 'Total Revenue',
            'C1' => 'Number of Bookings',
            'D1' => 'Average Revenue per Booking',
        ];

        foreach ($headers as $cell => $value) {
            $sheet->setCellValue($cell, $value);
        }

        // Style headers
        $headerStyle = [
            'font' => ['bold' => true],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'E2E8F0'],
            ],
            'borders' => [
                'allBorders' => ['borderStyle' => Border::BORDER_THIN],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
            ],
        ];

        $sheet->getStyle('A1:D1')->applyFromArray($headerStyle);

        // Add data
        $row = 2;
        foreach ($revenue as $period => $data) {
            $sheet->setCellValue('A'.$row, $period);
            $sheet->setCellValue('B'.$row, 'Rp '.number_format($data['total'], 0, ',', '.'));
            $sheet->setCellValue('C'.$row, $data['count']);
            $sheet->setCellValue('D'.$row, 'Rp '.number_format($data['total'] / $data['count'], 0, ',', '.'));
            $row++;
        }

        // Auto-size columns
        foreach (range('A', 'D') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Add borders to data
        $dataStyle = [
            'borders' => [
                'allBorders' => ['borderStyle' => Border::BORDER_THIN],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
            ],
        ];

        $sheet->getStyle('A2:D'.($row - 1))->applyFromArray($dataStyle);

        // Create writer
        $writer = new Xlsx($spreadsheet);

        // Set headers for download
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="revenue_'.date('Y-m-d').'.xlsx"');
        header('Cache-Control: max-age=0');

        // Save file
        $writer->save('php://output');
        exit;
    }
}
