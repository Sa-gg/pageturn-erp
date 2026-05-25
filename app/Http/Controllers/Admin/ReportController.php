<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\FinanceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ReportController extends Controller
{
    protected $financeService;

    public function __construct(FinanceService $financeService)
    {
        $this->financeService = $financeService;
    }

    public function index()
    {
        $revenue = [];
        $profit = [];
        $topBooks = [];
        
        try {
            $revRes = $this->financeService->getReports();
            if ($revRes->successful()) {
                $revenue = $revRes->json();
            }
            
            $profRes = $this->financeService->getProfitReport();
            if ($profRes->successful()) {
                $profit = $profRes->json();
            }
            
            $topRes = $this->financeService->getTopBooksReport();
            if ($topRes->successful()) {
                $topBooks = $topRes->json()['top_books'] ?? [];
            }
        } catch (\Exception $e) {
            Log::error('Failed to fetch reports for admin: ' . $e->getMessage());
        }

        return view('admin.reports.index', compact('revenue', 'profit', 'topBooks'));
    }
}
