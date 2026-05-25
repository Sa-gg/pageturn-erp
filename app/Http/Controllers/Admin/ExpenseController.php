<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\FinanceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ExpenseController extends Controller
{
    protected $financeService;

    public function __construct(FinanceService $financeService)
    {
        $this->financeService = $financeService;
    }

    public function index(Request $request)
    {
        $page = $request->get('page', 1);
        $category = $request->get('category', '');
        
        $expenses = [];
        
        try {
            $params = ['page' => $page];
            if ($category) $params['category'] = $category;
            
            $response = $this->financeService->getExpenses($params);
            if ($response->successful()) {
                $expenses = $response->json();
            }
        } catch (\Exception $e) {
            Log::error('Failed to fetch expenses for admin: ' . $e->getMessage());
        }

        return view('admin.expenses.index', compact('expenses', 'category'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'category' => 'required|string|max:100',
            'amount' => 'required|numeric|min:0',
            'description' => 'required|string',
            'reference_number' => 'nullable|string|max:100',
            'incurred_at' => 'nullable|date',
        ]);

        try {
            $response = $this->financeService->createExpense($data);
            if ($response->successful()) {
                return back()->with('success', 'Expense recorded successfully.');
            }
            return back()->with('error', 'Failed to record expense. API returned: ' . $response->body());
        } catch (\Exception $e) {
            Log::error('Failed to record expense: ' . $e->getMessage());
            return back()->with('error', 'Error connecting to Finance service.');
        }
    }
}
