<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\ReportService;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    protected ReportService $service;

    public function __construct(ReportService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
        $from = $request->input('from', now()->startOfMonth()->toDateString());
        $to   = $request->input('to', now()->toDateString());
        $year = $request->input('year', now()->year);

        $summary      = $this->service->getSalesSummary($from, $to);
        $ordersByStatus = $this->service->getOrdersByStatus($from, $to);
        $topProducts  = $this->service->getTopSellingProducts(10, $from, $to);
        $lowStock     = $this->service->getLowStockProducts();
        $revenueByMonth = $this->service->getRevenueByMonth($year);
        $customerGrowth = $this->service->getCustomerGrowth($year);

        return view('admin.reports.index', compact(
            'summary', 'ordersByStatus', 'topProducts',
            'lowStock', 'revenueByMonth', 'customerGrowth',
            'from', 'to', 'year'
        ));
    }

    public function exportCsv(Request $request)
    {
        $from = $request->input('from', now()->startOfMonth()->toDateString());
        $to   = $request->input('to', now()->toDateString());

        $csv = $this->service->exportOrdersCsv($from, $to);

        return response($csv, 200, [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => "attachment; filename=orders_{$from}_{$to}.csv",
        ]);
    }
}
