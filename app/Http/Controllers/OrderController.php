<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Services\CatalogService;
use App\Services\InventoryService;
use App\Services\FinanceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    protected $catalogService;
    protected $inventoryService;
    protected $financeService;

    public function __construct(
        CatalogService $catalogService,
        InventoryService $inventoryService,
        FinanceService $financeService
    ) {
        $this->catalogService   = $catalogService;
        $this->inventoryService = $inventoryService;
        $this->financeService   = $financeService;
    }

    /**
     * List orders with optional filtering.
     * GET /api/orders
     */
    public function index(Request $request)
    {
        $query = Order::with('items');

        // Filter by user
        if ($request->has('user_id')) {
            $query->byUser($request->user_id);
        }

        // Filter by status
        if ($request->has('status')) {
            $query->byStatus($request->status);
        }

        // Filter by payment status
        if ($request->has('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        // Date range
        if ($request->has('from_date')) {
            $query->where('created_at', '>=', $request->from_date);
        }
        if ($request->has('to_date')) {
            $query->where('created_at', '<=', $request->to_date . ' 23:59:59');
        }

        // Search by order number or customer name
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'ilike', "%{$search}%")
                  ->orWhere('customer_name', 'ilike', "%{$search}%")
                  ->orWhere('customer_email', 'ilike', "%{$search}%");
            });
        }

        $orders = $query->orderBy('created_at', 'desc')
            ->paginate($request->get('per_page', 15));

        return response()->json($orders);
    }

    /**
     * Get single order with details.
     * GET /api/orders/{id}
     */
    public function show($id)
    {
        $order = Order::with('items')->findOrFail($id);

        return response()->json(['order' => $order]);
    }

    /**
     * Create order from cart (checkout).
     * POST /api/orders
     *
     * This is the KEY microservice integration point:
     * 1. Validate cart items against Catalog service
     * 2. Create the order
     * 3. Deduct stock via Inventory service
     * 4. Create invoice via Finance service
     * 5. Clear the cart
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id'          => 'required|integer',
            'customer_name'    => 'required|string|max:255',
            'customer_email'   => 'required|email|max:255',
            'customer_phone'   => 'nullable|string|max:20',
            'shipping_address' => 'required|string|max:500',
            'shipping_city'    => 'required|string|max:100',
            'shipping_state'   => 'nullable|string|max:100',
            'shipping_zip'     => 'required|string|max:20',
            'shipping_country' => 'sometimes|string|max:5',
            'payment_method'   => 'sometimes|in:cod,card,gcash',
            'notes'            => 'nullable|string|max:1000',
        ]);

        // Get cart items for this user
        $cartItems = CartItem::where('user_id', $request->user_id)->get();

        if ($cartItems->isEmpty()) {
            return response()->json(['message' => 'Cart is empty'], 422);
        }

        // Validate each cart item against catalog (get fresh prices)
        $validatedItems = [];
        foreach ($cartItems as $cartItem) {
            $validation = $this->catalogService->validateBook($cartItem->book_id);

            if (!$validation['valid']) {
                return response()->json([
                    'message' => "Item no longer available: {$validation['error']}",
                    'book_id' => $cartItem->book_id,
                ], 422);
            }

            $book = $validation['book'];
            $validatedItems[] = [
                'book_id'    => $cartItem->book_id,
                'book_title' => $book['title'],
                'book_isbn'  => $book['isbn'] ?? null,
                'unit_price' => $book['price'],
                'quantity'   => $cartItem->quantity,
                'subtotal'   => round($book['price'] * $cartItem->quantity, 2),
            ];
        }

        // Calculate totals
        $subtotal    = array_sum(array_column($validatedItems, 'subtotal'));
        $shippingFee = $subtotal >= 1500 ? 0 : 50; // Free shipping over ₱1500
        $tax         = round($subtotal * 0.12, 2);  // 12% VAT
        $total       = round($subtotal + $shippingFee + $tax, 2);

        // Create order in a transaction
        $order = DB::transaction(function () use ($request, $validatedItems, $subtotal, $shippingFee, $tax, $total) {
            $order = Order::create([
                'user_id'          => $request->user_id,
                'customer_name'    => $request->customer_name,
                'customer_email'   => $request->customer_email,
                'customer_phone'   => $request->customer_phone,
                'shipping_address' => $request->shipping_address,
                'shipping_city'    => $request->shipping_city,
                'shipping_state'   => $request->shipping_state,
                'shipping_zip'     => $request->shipping_zip,
                'shipping_country' => $request->get('shipping_country', 'PH'),
                'payment_method'   => $request->get('payment_method', 'cod'),
                'notes'            => $request->notes,
                'subtotal'         => $subtotal,
                'shipping_fee'     => $shippingFee,
                'tax'              => $tax,
                'total'            => $total,
                'status'           => 'pending',
                'payment_status'   => 'unpaid',
            ]);

            // Create order items
            foreach ($validatedItems as $item) {
                OrderItem::create(array_merge($item, ['order_id' => $order->id]));
            }

            // Clear the user's cart
            CartItem::where('user_id', $order->user_id)->delete();

            return $order;
        });

        $order->load('items');

        // ── Inter-service calls (non-blocking, best-effort) ──────────

        // 1) Deduct stock via Inventory service
        $stockItems = array_map(function ($item) {
            return ['book_id' => $item['book_id'], 'quantity' => $item['quantity']];
        }, $validatedItems);

        $stockResult = $this->inventoryService->deductStock($stockItems, $order->order_number);
        if (!$stockResult['success']) {
            Log::warning("Order {$order->order_number}: Stock deduction failed — {$stockResult['error']}");
        }

        // 2) Create invoice via Finance service
        $invoiceResult = $this->financeService->createInvoice($order->toArray());
        if (!$invoiceResult['success']) {
            Log::warning("Order {$order->order_number}: Invoice creation failed — {$invoiceResult['error']}");
        }

        return response()->json([
            'message' => 'Order placed successfully',
            'order'   => $order,
        ], 201);
    }

    /**
     * Update order status (admin).
     * PATCH /api/orders/{id}/status
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,processing,shipped,delivered,cancelled,refunded',
        ]);

        $order = Order::findOrFail($id);
        $oldStatus = $order->status;
        $newStatus = $request->status;

        // Prevent invalid transitions
        $invalidTransitions = [
            'delivered'  => ['pending', 'confirmed', 'processing'],
            'cancelled'  => ['shipped', 'delivered'],
        ];

        if (isset($invalidTransitions[$oldStatus]) && in_array($newStatus, $invalidTransitions[$oldStatus])) {
            return response()->json([
                'message' => "Cannot change status from '{$oldStatus}' to '{$newStatus}'",
            ], 422);
        }

        // Update status with timestamps
        $updateData = ['status' => $newStatus];

        switch ($newStatus) {
            case 'confirmed':
                $updateData['confirmed_at'] = now();
                break;
            case 'shipped':
                $updateData['shipped_at'] = now();
                break;
            case 'delivered':
                $updateData['delivered_at'] = now();
                $updateData['payment_status'] = 'paid'; // Mark as paid on delivery (COD)
                break;
            case 'cancelled':
                $updateData['cancelled_at'] = now();
                break;
        }

        $order->update($updateData);

        Log::info("Order {$order->order_number}: Status changed from '{$oldStatus}' to '{$newStatus}'");

        return response()->json([
            'message' => "Order status updated to '{$newStatus}'",
            'order'   => $order->fresh('items'),
        ]);
    }

    /**
     * Get order by order number (for tracking).
     * GET /api/orders/track/{orderNumber}
     */
    public function track($orderNumber)
    {
        $order = Order::with('items')
            ->where('order_number', $orderNumber)
            ->firstOrFail();

        // Build timeline
        $timeline = [
            [
                'status'    => 'pending',
                'label'     => 'Order Placed',
                'completed' => true,
                'date'      => $order->created_at->toISOString(),
            ],
            [
                'status'    => 'confirmed',
                'label'     => 'Order Confirmed',
                'completed' => $order->confirmed_at !== null,
                'date'      => optional($order->confirmed_at)->toISOString(),
            ],
            [
                'status'    => 'processing',
                'label'     => 'Processing',
                'completed' => in_array($order->status, ['processing', 'shipped', 'delivered']),
                'date'      => null,
            ],
            [
                'status'    => 'shipped',
                'label'     => 'Shipped',
                'completed' => $order->shipped_at !== null,
                'date'      => optional($order->shipped_at)->toISOString(),
            ],
            [
                'status'    => 'delivered',
                'label'     => 'Delivered',
                'completed' => $order->delivered_at !== null,
                'date'      => optional($order->delivered_at)->toISOString(),
            ],
        ];

        return response()->json([
            'order'    => $order,
            'timeline' => $timeline,
        ]);
    }
}
