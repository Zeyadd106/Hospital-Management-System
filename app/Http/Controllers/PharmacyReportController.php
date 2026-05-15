<?php

namespace App\Http\Controllers;

use App\Models\StockItem;
use App\Models\PharmacyOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PharmacyReportController extends Controller
{
    /**
     * Display the main reports page.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('pharmacy.reports.index');
    }

    /**
     * Generate sales report.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function salesReport(Request $request)
    {
        $startDate = $request->input('start_date', now()->subMonth());
        $endDate = $request->input('end_date', now());

        $salesReport = PharmacyOrder::whereBetween('created_at', [$startDate, $endDate])
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(total_amount) as total_sales'),
                DB::raw('COUNT(*) as total_orders')
            )
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return view('pharmacy.reports.sales', compact('salesReport', 'startDate', 'endDate'));
    }

    /**
     * Generate stock report.
     *
     * @return \Illuminate\View\View
     */
    public function stockReport()
    {
        $stockReport = StockItem::with('medication')
            ->select(
                'medication_id',
                'current_stock as quantity',
                'min_stock',
                'updated_at as last_updated'
            )
            ->selectRaw('(CASE WHEN current_stock <= min_stock THEN "Low Stock" ELSE "Sufficient Stock" END) as stock_status')
            ->orderBy('current_stock', 'asc')
            ->get()
            ->map(function($item) {
                return [
                    'medicine_name' => $item->medication ? $item->medication->name : 'Unknown',
                    'quantity' => $item->quantity,
                    'min_stock' => $item->min_stock,
                    'last_updated' => Carbon::parse($item->last_updated), 
                    'stock_status' => $item->stock_status
                ];
            });

        return view('pharmacy.reports.stock', compact('stockReport'));
    }
}
