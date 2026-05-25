<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\InventoryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SupplierController extends Controller
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
        
        $suppliers = [];
        
        try {
            $response = $this->inventoryService->getSuppliers(['page' => $page, 'search' => $search]);
            if ($response->successful()) {
                $suppliers = $response->json();
            }
        } catch (\Exception $e) {
            Log::error('Failed to fetch suppliers for admin: ' . $e->getMessage());
        }

        return view('admin.suppliers.index', compact('suppliers', 'search'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:50',
            'address' => 'nullable|string',
        ]);

        try {
            $response = $this->inventoryService->createSupplier($data);
            if ($response->successful()) {
                return back()->with('success', 'Supplier created successfully.');
            }
            return back()->with('error', 'Failed to create supplier. API returned: ' . $response->body());
        } catch (\Exception $e) {
            Log::error('Failed to create supplier: ' . $e->getMessage());
            return back()->with('error', 'Error connecting to Inventory service.');
        }
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:50',
            'address' => 'nullable|string',
        ]);

        try {
            $response = $this->inventoryService->updateSupplier($id, $data);
            if ($response->successful()) {
                return back()->with('success', 'Supplier updated successfully.');
            }
            return back()->with('error', 'Failed to update supplier. API returned: ' . $response->body());
        } catch (\Exception $e) {
            Log::error('Failed to update supplier: ' . $e->getMessage());
            return back()->with('error', 'Error connecting to Inventory service.');
        }
    }

    public function destroy($id)
    {
        try {
            $response = $this->inventoryService->deleteSupplier($id);
            if ($response->successful()) {
                return back()->with('success', 'Supplier deleted successfully.');
            }
            return back()->with('error', 'Failed to delete supplier.');
        } catch (\Exception $e) {
            Log::error('Failed to delete supplier: ' . $e->getMessage());
            return back()->with('error', 'Error connecting to Inventory service.');
        }
    }
}
