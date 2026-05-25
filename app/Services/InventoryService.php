<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class InventoryService
{
    protected $baseUrl;

    public function __construct()
    {
        $this->baseUrl = config('services.inventory.base_url');
    }

    /**
     * Deduct stock for a list of items when an order is confirmed.
     *
     * @param array $items  [['book_id' => int, 'quantity' => int], ...]
     * @param string $orderNumber  Reference for the stock movement
     * @return array ['success' => bool, 'error' => string|null]
     */
    public function deductStock(array $items, $orderNumber)
    {
        try {
            $response = Http::timeout(15)
                ->post("{$this->baseUrl}/stock/deduct", [
                    'items'      => $items,
                    'reference'  => $orderNumber,
                    'reason'     => "Order {$orderNumber} confirmed",
                ]);

            if ($response->successful()) {
                Log::info("InventoryService: Stock deducted for order {$orderNumber}");
                return ['success' => true, 'error' => null];
            }

            $error = $response->json('message') ?? $response->body();
            Log::warning("InventoryService: Failed to deduct stock for {$orderNumber}", [
                'status' => $response->status(),
                'error'  => $error,
            ]);

            return ['success' => false, 'error' => $error];
        } catch (\Exception $e) {
            Log::error("InventoryService: Exception deducting stock for {$orderNumber}", [
                'error' => $e->getMessage(),
            ]);
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Check stock availability for a book.
     *
     * @param int $bookId
     * @return array|null  Inventory data or null on failure
     */
    public function getStock($bookId)
    {
        try {
            $response = Http::timeout(10)
                ->get("{$this->baseUrl}/inventory/{$bookId}");

            if ($response->successful()) {
                return $response->json();
            }

            return null;
        } catch (\Exception $e) {
            Log::error("InventoryService: Exception checking stock for book {$bookId}", [
                'error' => $e->getMessage(),
            ]);
            return null;
        }
    }
}
