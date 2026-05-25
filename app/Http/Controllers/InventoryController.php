<?php

namespace App\Http\Controllers;

use App\Models\InventoryItem;
use App\Models\StockMovement;
use App\Services\CatalogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class InventoryController extends Controller
{
    protected $catalogService;

    public function __construct(CatalogService $catalogService)
    {
        $this->catalogService = $catalogService;
    }

    /**
     * List all inventory items.
     * GET /api/inventory
     */
    public function index(Request $request)
    {
        $query = InventoryItem::query();

        // Filter low stock only
        if ($request->boolean('low_stock')) {
            $query->lowStock();
        }

        // Filter out of stock only
        if ($request->boolean('out_of_stock')) {
            $query->outOfStock();
        }

        // Search by book title
        if ($request->has('search')) {
            $query->where('book_title', 'ilike', "%{$request->search}%");
        }

        $items = $query->orderBy('book_title')->paginate($request->get('per_page', 20));

        // Append computed attributes
        $items->getCollection()->transform(function ($item) {
            $item->available_quantity = $item->available_quantity;
            $item->is_low_stock = $item->is_low_stock;
            $item->is_out_of_stock = $item->is_out_of_stock;
            return $item;
        });

        return response()->json($items);
    }

    /**
     * Get stock for a specific book.
     * GET /api/inventory/{book_id}
     */
    public function show($bookId)
    {
        $item = InventoryItem::where('book_id', $bookId)->firstOrFail();

        $item->available_quantity = $item->available_quantity;
        $item->is_low_stock = $item->is_low_stock;
        $item->is_out_of_stock = $item->is_out_of_stock;

        // Include recent stock movements
        $movements = StockMovement::where('book_id', $bookId)
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get();

        return response()->json([
            'inventory' => $item,
            'recent_movements' => $movements,
        ]);
    }

    /**
     * Create a new stock record.
     * POST /api/inventory
     */
    public function store(Request $request)
    {
        $request->validate([
            'book_id'          => 'required|integer|unique:inventory_items,book_id',
            'book_title'       => 'required|string|max:255',
            'quantity_on_hand' => 'required|integer|min:0',
            'reorder_level'    => 'sometimes|integer|min:0',
            'reorder_quantity' => 'sometimes|integer|min:1',
            'location'         => 'nullable|string|max:100',
        ]);

        $item = InventoryItem::create($request->only([
            'book_id', 'book_title', 'quantity_on_hand',
            'reorder_level', 'reorder_quantity', 'location',
        ]));

        // Log initial stock movement
        StockMovement::create([
            'inventory_item_id' => $item->id,
            'book_id'           => $item->book_id,
            'type'              => 'in',
            'quantity'          => $item->quantity_on_hand,
            'quantity_before'   => 0,
            'quantity_after'    => $item->quantity_on_hand,
            'reason'            => 'Initial stock setup',
        ]);

        return response()->json([
            'message'   => 'Inventory record created',
            'inventory' => $item,
        ], 201);
    }

    /**
     * Update a stock record.
     * PUT /api/inventory/{id}
     */
    public function update(Request $request, $id)
    {
        $item = InventoryItem::findOrFail($id);

        $request->validate([
            'book_title'       => 'sometimes|string|max:255',
            'reorder_level'    => 'sometimes|integer|min:0',
            'reorder_quantity' => 'sometimes|integer|min:1',
            'location'         => 'nullable|string|max:100',
        ]);

        $item->update($request->only([
            'book_title', 'reorder_level', 'reorder_quantity', 'location',
        ]));

        return response()->json([
            'message'   => 'Inventory record updated',
            'inventory' => $item,
        ]);
    }

    /**
     * Deduct stock — inter-service endpoint called by Orders service.
     * POST /api/stock/deduct
     */
    public function deductStock(Request $request)
    {
        $request->validate([
            'items'           => 'required|array|min:1',
            'items.*.book_id' => 'required|integer',
            'items.*.quantity'=> 'required|integer|min:1',
            'reference'       => 'nullable|string|max:255',
            'reason'          => 'nullable|string|max:500',
        ]);

        $results = [];
        $errors  = [];

        DB::transaction(function () use ($request, &$results, &$errors) {
            foreach ($request->items as $deduction) {
                $item = InventoryItem::where('book_id', $deduction['book_id'])->first();

                if (!$item) {
                    $errors[] = "No inventory record for book_id {$deduction['book_id']}";
                    continue;
                }

                if ($item->quantity_on_hand < $deduction['quantity']) {
                    $errors[] = "Insufficient stock for '{$item->book_title}' (have {$item->quantity_on_hand}, need {$deduction['quantity']})";
                    continue;
                }

                $before = $item->quantity_on_hand;
                $after  = $before - $deduction['quantity'];

                $item->update(['quantity_on_hand' => $after]);

                StockMovement::create([
                    'inventory_item_id' => $item->id,
                    'book_id'           => $item->book_id,
                    'type'              => 'out',
                    'quantity'          => -$deduction['quantity'],
                    'quantity_before'   => $before,
                    'quantity_after'    => $after,
                    'reason'            => $request->get('reason', 'Order stock deduction'),
                    'reference'         => $request->reference,
                ]);

                // If stock hits zero, notify Catalog service
                if ($after <= 0) {
                    $this->catalogService->updateBookAvailability($item->book_id, false);
                    Log::info("Book {$item->book_id} ({$item->book_title}) is now OUT OF STOCK");
                }

                $results[] = [
                    'book_id'  => $item->book_id,
                    'deducted' => $deduction['quantity'],
                    'remaining'=> $after,
                ];
            }
        });

        if (!empty($errors)) {
            return response()->json([
                'message' => 'Some deductions failed',
                'results' => $results,
                'errors'  => $errors,
            ], 422);
        }

        return response()->json([
            'message' => 'Stock deducted successfully',
            'results' => $results,
        ]);
    }

    /**
     * Manual stock adjustment (admin).
     * POST /api/stock/adjust
     */
    public function adjustStock(Request $request)
    {
        $request->validate([
            'book_id'  => 'required|integer',
            'quantity' => 'required|integer', // positive = add, negative = subtract
            'reason'   => 'required|string|max:500',
        ]);

        $item = InventoryItem::where('book_id', $request->book_id)->firstOrFail();

        $before = $item->quantity_on_hand;
        $after  = $before + $request->quantity;

        if ($after < 0) {
            return response()->json([
                'message' => "Adjustment would result in negative stock ({$after})",
            ], 422);
        }

        $item->update(['quantity_on_hand' => $after]);

        StockMovement::create([
            'inventory_item_id' => $item->id,
            'book_id'           => $item->book_id,
            'type'              => 'adjustment',
            'quantity'          => $request->quantity,
            'quantity_before'   => $before,
            'quantity_after'    => $after,
            'reason'            => $request->reason,
        ]);

        // Update catalog availability based on new stock level
        if ($before <= 0 && $after > 0) {
            $this->catalogService->updateBookAvailability($item->book_id, true);
        } elseif ($after <= 0) {
            $this->catalogService->updateBookAvailability($item->book_id, false);
        }

        return response()->json([
            'message'   => 'Stock adjusted',
            'inventory' => $item->fresh(),
            'movement'  => [
                'before' => $before,
                'after'  => $after,
                'change' => $request->quantity,
            ],
        ]);
    }

    /**
     * Low-stock alerts.
     * GET /api/inventory/alerts
     */
    public function alerts()
    {
        $lowStock = InventoryItem::lowStock()
            ->orderBy('quantity_on_hand')
            ->get();

        $lowStock->transform(function ($item) {
            $item->available_quantity = $item->available_quantity;
            $item->is_low_stock = $item->is_low_stock;
            $item->is_out_of_stock = $item->is_out_of_stock;
            $item->deficit = $item->reorder_level - $item->quantity_on_hand;
            return $item;
        });

        return response()->json([
            'alert_count' => $lowStock->count(),
            'low_stock_items' => $lowStock,
        ]);
    }
}
