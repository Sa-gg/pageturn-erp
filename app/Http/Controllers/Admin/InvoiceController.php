<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\FinanceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class InvoiceController extends Controller
{
    protected $financeService;

    public function __construct(FinanceService $financeService)
    {
        $this->financeService = $financeService;
    }

    public function index(Request $request)
    {
        $page = $request->get('page', 1);
        $status = $request->get('status', '');
        
        $invoices = [];
        
        try {
            $params = ['page' => $page];
            if ($status) $params['status'] = $status;
            
            $response = $this->financeService->getInvoices($params);
            if ($response->successful()) {
                $invoices = $response->json();
            }
        } catch (\Exception $e) {
            Log::error('Failed to fetch invoices for admin: ' . $e->getMessage());
        }

        return view('admin.invoices.index', compact('invoices', 'status'));
    }

    public function pay(Request $request, $id)
    {
        $data = $request->validate([
            'payment_method' => 'required|string|max:50',
            'reference_number' => 'nullable|string|max:100',
        ]);

        try {
            $response = $this->financeService->payInvoice($id, $data);
            if ($response->successful()) {
                return back()->with('success', 'Invoice marked as paid.');
            }
            return back()->with('error', 'Failed to pay invoice. API returned: ' . $response->body());
        } catch (\Exception $e) {
            Log::error('Failed to pay invoice: ' . $e->getMessage());
            return back()->with('error', 'Error connecting to Finance service.');
        }
    }
}
