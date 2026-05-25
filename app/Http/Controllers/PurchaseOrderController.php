<?php

namespace App\Http\Controllers;

use App\Models\InventoryItem;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use App\Models\StockMovement;
use App\Services\CatalogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PurchaseOrderController extends Controller
{
    protected $catalogService;

    public function __construct(CatalogService $catalogService)
    {
        $this->catalogService = $catalogService;
    }

    /**
     * List purchase orders.
     * GET /api/purchase-orders
     */
    public function index(Request $request)
    {
        $query = PurchaseOrder::with(['supplier', 'items']);

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('supplier_id')) {
            $query->where('supplier_id', $request->supplier_id);
        }

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('po_number', 'ilike', "%{$search}%")
                  ->orWhereHas('supplier', function ($sq) use ($search) {
                      $sq->where('name', 'ilike', "%{$search}%");
                  });
            });
        }

        $orders = $query->orderBy('created_at', 'desc')
            ->paginate($request->get('per_page', 15));

        return response()->json($orders);
    }

    /**
     * Get single purchase order with details.
     * GET /api/purchase-orders/{id}
     */
    public function show($id)
    {
        $po = PurchaseOrder::with(['supplier', 'items'])->findOrFail($id);

        return response()->json(['purchase_order' => $po]);
    }

    /**
     * Create a new purchase order.
     * POST /api/purchase-orders
     */
    public function store(Request $request)
    {
        $request->validate([
            'supplier_id'          => 'required|exists:suppliers,id',
            'notes'                => 'nullable|string|max:1000',
            'items'                => 'required|array|min:1',
            'items.*.book_id'      => 'required|integer',
            'items.*.book_title'   => 'required|string|max:255',
            'items.*.quantity'     => 'required|integer|min:1',
            'items.*.unit_cost'    => 'required|numeric|min:0',
        ]);

        $po = DB::transaction(function () use ($request) {
            $po = PurchaseOrder::create([
                'supplier_id' => $request->supplier_id,
                'status'      => 'draft',
                'notes'       => $request->notes,
            ]);

            $totalAmount = 0;

            foreach ($request->items as $itemData) {
                $subtotal = round($itemData['quantity'] * $itemData['unit_cost'], 2);
                $totalAmount += $subtotal;

                PurchaseOrderItem::create([
                    'purchase_order_id' => $po->id,
                    'book_id'           => $itemData['book_id'],
                    'book_title'        => $itemData['book_title'],
                    'quantity'          => $itemData['quantity'],
                    'unit_cost'         => $itemData['unit_cost'],
                    'subtotal'          => $subtotal,
                ]);
            }

            $po->update([
                'total_amount' => $totalAmount,
                'status'       => 'submitted',
                'submitted_at' => now(),
            ]);

            return $po;
        });

        $po->load(['supplier', 'items']);

        return response()->json([
            'message'        => 'Purchase order created',
            'purchase_order' => $po,
        ], 201);
    }

    /**
     * Receive a purchase order → auto-restock inventory.
     * PATCH /api/purchase-orders/{id}/receive
     *
     * This is the key flow:
     * 1. Mark PO as received
     * 2. For each PO item, add stock to inventory
     * 3. Log stock movements
     * 4. If book was out of stock, notify Catalog to mark available
     */
    public function receive($id)
    {
        $po = PurchaseOrder::with('items')->findOrFail($id);

        if (!$po->isReceivable()) {
            return response()->json([
                'message' => "PO cannot be received (current status: {$po->status})",
            ], 422);
        }

        $restockResults = [];

        DB::transaction(function () use ($po, &$restockResults) {
            foreach ($po->items as $poItem) {
                // Find or create inventory record
                $inventory = InventoryItem::firstOrCreate(
                    ['book_id' => $poItem->book_id],
                    [
                        'book_title'       => $poItem->book_title,
                        'quantity_on_hand' => 0,
                        'reorder_level'    => 10,
                        'reorder_quantity' => 50,
                    ]
                );

                $before = $inventory->quantity_on_hand;
                $after  = $before + $poItem->quantity;

                $inventory->update(['quantity_on_hand' => $after]);

                // Update received quantity on PO item
                $poItem->update(['quantity_received' => $poItem->quantity]);

                // Log stock movement
                StockMovement::create([
                    'inventory_item_id' => $inventory->id,
                    'book_id'           => $inventory->book_id,
                    'type'              => 'in',
                    'quantity'          => $poItem->quantity,
                    'quantity_before'   => $before,
                    'quantity_after'    => $after,
                    'reason'            => "PO {$po->po_number} received",
                    'reference'         => $po->po_number,
                ]);

                // If book was out of stock and now restocked, mark available in Catalog
                if ($before <= 0 && $after > 0) {
                    $this->catalogService->updateBookAvailability($inventory->book_id, true);
                    Log::info("Book {$inventory->book_id} restocked via PO {$po->po_number}");
                }

                $restockResults[] = [
                    'book_id'    => $inventory->book_id,
                    'book_title' => $inventory->book_title,
                    'added'      => $poItem->quantity,
                    'new_total'  => $after,
                ];
            }

            // Mark PO as received
            $po->update([
                'status'      => 'received',
                'received_at' => now(),
            ]);
        });

        return response()->json([
            'message'        => "PO {$po->po_number} received — stock updated",
            'purchase_order' => $po->fresh(['supplier', 'items']),
            'restock_results'=> $restockResults,
        ]);
    }
}
