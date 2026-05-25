<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    /**
     * List all suppliers.
     * GET /api/suppliers
     */
    public function index(Request $request)
    {
        $query = Supplier::query();

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'ilike', "%{$search}%")
                  ->orWhere('contact_person', 'ilike', "%{$search}%")
                  ->orWhere('email', 'ilike', "%{$search}%");
            });
        }

        if ($request->has('is_active')) {
            $query->where('is_active', $request->boolean('is_active'));
        }

        $suppliers = $query->orderBy('name')
            ->paginate($request->get('per_page', 20));

        return response()->json($suppliers);
    }

    /**
     * Get single supplier with PO count.
     * GET /api/suppliers/{id}
     */
    public function show($id)
    {
        $supplier = Supplier::withCount('purchaseOrders')->findOrFail($id);

        return response()->json(['supplier' => $supplier]);
    }

    /**
     * Create a supplier.
     * POST /api/suppliers
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'           => 'required|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'email'          => 'nullable|email|max:255',
            'phone'          => 'nullable|string|max:20',
            'address'        => 'nullable|string|max:500',
            'city'           => 'nullable|string|max:100',
            'country'        => 'sometimes|string|max:5',
            'notes'          => 'nullable|string|max:1000',
        ]);

        $supplier = Supplier::create($request->only([
            'name', 'contact_person', 'email', 'phone',
            'address', 'city', 'country', 'notes',
        ]));

        return response()->json([
            'message'  => 'Supplier created',
            'supplier' => $supplier,
        ], 201);
    }

    /**
     * Update a supplier.
     * PUT /api/suppliers/{id}
     */
    public function update(Request $request, $id)
    {
        $supplier = Supplier::findOrFail($id);

        $request->validate([
            'name'           => 'sometimes|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'email'          => 'nullable|email|max:255',
            'phone'          => 'nullable|string|max:20',
            'address'        => 'nullable|string|max:500',
            'city'           => 'nullable|string|max:100',
            'country'        => 'sometimes|string|max:5',
            'is_active'      => 'sometimes|boolean',
            'notes'          => 'nullable|string|max:1000',
        ]);

        $supplier->update($request->only([
            'name', 'contact_person', 'email', 'phone',
            'address', 'city', 'country', 'is_active', 'notes',
        ]));

        return response()->json([
            'message'  => 'Supplier updated',
            'supplier' => $supplier,
        ]);
    }

    /**
     * Delete a supplier.
     * DELETE /api/suppliers/{id}
     */
    public function destroy($id)
    {
        $supplier = Supplier::findOrFail($id);

        // Check if supplier has POs
        if ($supplier->purchaseOrders()->exists()) {
            // Soft-deactivate instead of hard delete
            $supplier->update(['is_active' => false]);
            return response()->json([
                'message' => 'Supplier has purchase orders and was deactivated instead of deleted',
            ]);
        }

        $supplier->delete();

        return response()->json(['message' => 'Supplier deleted']);
    }
}
