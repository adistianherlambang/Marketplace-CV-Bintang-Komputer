<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MonthlyReport;
use App\Services\ReportService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    protected $reportService;

    public function __construct(ReportService $reportService)
    {
        $this->reportService = $reportService;
    }

    public function index()
    {
        $monthlyReports = MonthlyReport::orderBy('report_month', 'desc')->get();
        return view('admin.reports.index', compact('monthlyReports'));
    }

    public function preview(Request $request)
    {
        $type = $request->input('type');
        $data = [];

        switch ($type) {
            case 'daily':
                $date = $request->input('date', now()->toDateString());
                $data = $this->reportService->getDailyReportData($date);
                $data['type'] = 'daily';
                $data['param'] = $date;
                break;
            case 'monthly':
                $month = $request->input('month', now()->format('Y-m'));
                $data = $this->reportService->getMonthlyReportData($month);
                $data['type'] = 'monthly';
                $data['param'] = $month;
                break;
            case 'stock':
                $data = $this->reportService->getStockReportData();
                $data['type'] = 'stock';
                break;
            case 'return':
                $data = $this->reportService->getReturnReportData();
                $data['type'] = 'return';
                break;
            case 'top_products':
                $data = $this->reportService->getTopProductsReportData();
                $data['type'] = 'top_products';
                break;
            default:
                return redirect()->route('admin.reports.index')->with('error', 'Jenis laporan tidak valid.');
        }

        return view('admin.reports.preview', compact('data'));
    }

    public function downloadPdf(Request $request)
    {
        $type = $request->input('type');
        $param = $request->input('param');

        switch ($type) {
            case 'daily':
                $data = $this->reportService->getDailyReportData($param);
                $pdf = Pdf::loadView('pdf.reports.daily', compact('data'))->setPaper('a4', 'portrait');
                return $pdf->download("laporan-harian-{$param}.pdf");

            case 'monthly':
                $data = $this->reportService->getMonthlyReportData($param);
                
                // Record to monthly_reports table when downloading/generating
                MonthlyReport::updateOrCreate(
                    ['report_month' => $param],
                    [
                        'total_sales' => $data['total_sales'],
                        'total_earnings' => $data['total_sales'] * 0.20, // Example profit estimation (e.g. 20% margin)
                        'total_transactions' => $data['total_transactions'],
                        'generated_at' => now(),
                    ]
                );

                $pdf = Pdf::loadView('pdf.reports.monthly', compact('data'))->setPaper('a4', 'portrait');
                return $pdf->download("laporan-bulanan-{$param}.pdf");

            case 'stock':
                $data = $this->reportService->getStockReportData();
                $pdf = Pdf::loadView('pdf.reports.stock', compact('data'))->setPaper('a4', 'portrait');
                return $pdf->download("laporan-stok-" . now()->format('Y-m-d') . ".pdf");

            case 'return':
                $data = $this->reportService->getReturnReportData();
                $pdf = Pdf::loadView('pdf.reports.return', compact('data'))->setPaper('a4', 'portrait');
                return $pdf->download("laporan-retur-" . now()->format('Y-m-d') . ".pdf");

            case 'top_products':
                $data = $this->reportService->getTopProductsReportData();
                $pdf = Pdf::loadView('pdf.reports.top_products', compact('data'))->setPaper('a4', 'portrait');
                return $pdf->download("laporan-produk-terlaris-" . now()->format('Y-m-d') . ".pdf");
        }

        return redirect()->route('admin.reports.index')->with('error', 'Gagal membuat laporan.');
    }
}
