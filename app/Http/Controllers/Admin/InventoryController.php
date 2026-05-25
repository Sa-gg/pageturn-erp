<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\InventoryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class InventoryController extends Controller
{
    protected $inventoryService;

    public function __construct(InventoryService $inventoryService)
    {
        $this->inventoryService = $inventoryService;
    }

    public function index(Request $request)
    {
        $page = $request->get('page', 1);
        $search = $request->get('search', '');
        
        $inventory = [];
        $alerts = [];
        
        try {
            $response = $this->inventoryService->getInventory(['page' => $page, 'search' => $search]);
            
            if ($response->successful()) {
                $inventory = $response->json();
            }
            
            $alertsRes = $this->inventoryService->getAlerts();
            if ($alertsRes->successful()) {
                $alerts = $alertsRes->json();
            }
        } catch (\Exception $e) {
            Log::error('Failed to fetch inventory for admin: ' . $e->getMessage());
        }

        return view('admin.inventory.index', compact('inventory', 'alerts', 'search'));
    }

    public function adjust(Request $request)
    {
        $data = $request->validate([
            'inventory_item_id' => 'required|integer',
            'book_id' => 'required|integer',
            'type' => 'required|in:in,out',
            'quantity' => 'required|integer|min:1',
            'reason' => 'required|string|max:255',
        ]);

        try {
            $response = $this->inventoryService->adjustStock($data);
            
            if ($response->successful()) {
                return back()->with('success', 'Stock adjusted successfully.');
            }
            
            return back()->with('error', 'Failed to adjust stock. API returned: ' . $response->body());
        } catch (\Exception $e) {
            Log::error('Failed to adjust stock: ' . $e->getMessage());
            return back()->with('error', 'Error connecting to Inventory service.');
        }
    }
}
