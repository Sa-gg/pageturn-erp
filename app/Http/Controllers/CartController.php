<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Services\CatalogService;
use Illuminate\Http\Request;

class CartController extends Controller
{
    protected $catalogService;

    public function __construct(CatalogService $catalogService)
    {
        $this->catalogService = $catalogService;
    }

    /**
     * View cart items for a user.
     * GET /api/cart?user_id=X
     */
    public function index(Request $request)
    {
        $request->validate(['user_id' => 'required|integer']);

        $items = CartItem::where('user_id', $request->user_id)
            ->orderBy('created_at', 'desc')
            ->get();

        $subtotal = $items->sum(function ($item) {
            return $item->subtotal;
        });

        return response()->json([
            'cart_items'  => $items,
            'item_count'  => $items->count(),
            'subtotal'    => round($subtotal, 2),
        ]);
    }

    /**
     * Add item to cart.
     * POST /api/cart/items
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id'  => 'required|integer',
            'book_id'  => 'required|integer',
            'quantity' => 'sometimes|integer|min:1',
        ]);

        $quantity = $request->get('quantity', 1);

        // Validate book exists in Catalog service
        $validation = $this->catalogService->validateBook($request->book_id);

        if (!$validation['valid']) {
            return response()->json([
                'message' => $validation['error'],
            ], 422);
        }

        $book = $validation['book'];

        // Check if item already in cart — increment quantity
        $existing = CartItem::where('user_id', $request->user_id)
            ->where('book_id', $request->book_id)
            ->first();

        if ($existing) {
            $existing->update([
                'quantity'   => $existing->quantity + $quantity,
                'unit_price' => $book['price'], // refresh price from catalog
                'book_title' => $book['title'],
            ]);

            return response()->json([
                'message'   => 'Cart item quantity updated',
                'cart_item' => $existing->fresh(),
            ]);
        }

        $cartItem = CartItem::create([
            'user_id'    => $request->user_id,
            'book_id'    => $request->book_id,
            'book_title' => $book['title'],
            'unit_price' => $book['price'],
            'quantity'   => $quantity,
        ]);

        return response()->json([
            'message'   => 'Item added to cart',
            'cart_item' => $cartItem,
        ], 201);
    }

    /**
     * Update cart item quantity.
     * PUT /api/cart/items/{id}
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $cartItem = CartItem::findOrFail($id);
        $cartItem->update(['quantity' => $request->quantity]);

        return response()->json([
            'message'   => 'Cart item updated',
            'cart_item' => $cartItem,
        ]);
    }

    /**
     * Remove item from cart.
     * DELETE /api/cart/items/{id}
     */
    public function destroy($id)
    {
        $cartItem = CartItem::findOrFail($id);
        $cartItem->delete();

        return response()->json(['message' => 'Item removed from cart']);
    }

    /**
     * Clear entire cart for a user.
     * DELETE /api/cart?user_id=X
     */
    public function clear(Request $request)
    {
        $request->validate(['user_id' => 'required|integer']);

        CartItem::where('user_id', $request->user_id)->delete();

        return response()->json(['message' => 'Cart cleared']);
    }
}
