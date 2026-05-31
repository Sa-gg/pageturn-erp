<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\InventoryService;
use App\Services\CatalogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PurchaseOrderController extends Controller
{
    protected $inventoryService;
    protected $catalogService;

    public function __construct(InventoryService $inventoryService, CatalogService $catalogService)
    {
        $this->inventoryService = $inventoryService;
        $this->catalogService = $catalogService;
    }

    public function index(Request $request)
    {
        $page = $request->get('page', 1);
        $status = $request->get('status', '');
        
        $purchaseOrders = [];
        $suppliers = [];
        $books = [];
        
        try {
            $params = ['page' => $page];
            if ($status) $params['status'] = $status;
            
            $response = $this->inventoryService->getPurchaseOrders($params);
            if ($response->successful()) {
                $purchaseOrders = $response->json();
            }
            
            $supRes = $this->inventoryService->getSuppliers(['limit' => 100]);
            if ($supRes->successful()) {
                $suppliers = $supRes->json();
            }
            
            $bookRes = $this->catalogService->getBooks(['limit' => 1000]);
            if ($bookRes->successful()) {
                $books = $bookRes->json();
            }
        } catch (\Exception $e) {
            Log::error('Failed to fetch PO data for admin: ' . $e->getMessage());
        }

        return view('admin.purchase-orders.index', compact('purchaseOrders', 'suppliers', 'books', 'status'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'supplier_id' => 'required|integer',
            'expected_date' => 'required|date',
            'items' => 'required|array|min:1',
            'items.*.book_id' => 'required|integer',
            'items.*.book_title' => 'required|string|max:255',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_cost' => 'required|numeric|min:0',
        ]);

        try {
            $response = $this->inventoryService->createPurchaseOrder($data);
            if ($response->successful()) {
                return back()->with('success', 'Purchase Order created successfully.');
            }
            return back()->with('error', 'Failed to create PO. API returned: ' . $response->body());
        } catch (\Exception $e) {
            Log::error('Failed to create PO: ' . $e->getMessage());
            return back()->with('error', 'Error connecting to Inventory service.');
        }
    }

    public function receive(Request $request, $id)
    {
        try {
            $response = $this->inventoryService->receivePurchaseOrder($id);
            if ($response->successful()) {
                return back()->with('success', 'Purchase Order received and stock updated.');
            }
            return back()->with('error', 'Failed to receive PO. API returned: ' . $response->body());
        } catch (\Exception $e) {
            Log::error('Failed to receive PO: ' . $e->getMessage());
            return back()->with('error', 'Error connecting to Inventory service.');
        }
    }
}
