<?php

namespace App\Http\Controllers;

use App\Invoice;
use App\Expense;
use App\InvoiceItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function revenue(Request $request)
    {
        $invoices = Invoice::where('status', 'paid')->get();
        $totalRevenue = $invoices->sum('total');
        
        $revenueByMonth = $invoices->groupBy(function($date) {
            return \Carbon\Carbon::parse($date->created_at)->format('Y-m');
        })->map(function ($row) {
            return $row->sum('total');
        });

        return response()->json([
            'total_revenue'    => round($totalRevenue, 2),
            'revenue_by_month' => $revenueByMonth,
        ]);
    }

    public function profit(Request $request)
    {
        $totalRevenue = Invoice::where('status', 'paid')->sum('total');
        $totalExpenses = Expense::sum('amount');
        
        $profit = $totalRevenue - $totalExpenses;

        return response()->json([
            'total_revenue'  => round($totalRevenue, 2),
            'total_expenses' => round($totalExpenses, 2),
            'profit'         => round($profit, 2),
        ]);
    }

    public function topBooks(Request $request)
    {
        $limit = $request->get('limit', 10);

        $topBooks = InvoiceItem::select(
                'book_id',
                'book_title',
                DB::raw('SUM(quantity) as total_sold'),
                DB::raw('SUM(subtotal) as total_revenue')
            )
            ->whereHas('invoice', function ($q) {
                $q->where('status', 'paid');
            })
            ->groupBy('book_id', 'book_title')
            ->orderByDesc('total_sold')
            ->limit($limit)
            ->get();

        return response()->json([
            'top_books' => $topBooks,
        ]);
    }
}
