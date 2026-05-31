<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes — Inventory & Purchasing Service
|--------------------------------------------------------------------------
*/

Route::middleware(['role:inventory_admin'])->group(function () {
    // ── Inventory ──────────────────────────────────────────────────────
    Route::get('/inventory/alerts', 'InventoryController@alerts');       // Must be before {book_id}
    Route::get('/inventory', 'InventoryController@index');
    Route::get('/inventory/{book_id}', 'InventoryController@show');
    Route::post('/inventory', 'InventoryController@store');
    Route::put('/inventory/{id}', 'InventoryController@update');

    // ── Stock Operations ───────────────────────────────────────────────
    Route::post('/stock/adjust', 'InventoryController@adjustStock');     // Admin manual adjustment

    // ── Suppliers ──────────────────────────────────────────────────────
    Route::get('/suppliers', 'SupplierController@index');
    Route::get('/suppliers/{id}', 'SupplierController@show');
    Route::post('/suppliers', 'SupplierController@store');
    Route::put('/suppliers/{id}', 'SupplierController@update');
    Route::delete('/suppliers/{id}', 'SupplierController@destroy');

    // ── Purchase Orders ────────────────────────────────────────────────
    Route::get('/purchase-orders', 'PurchaseOrderController@index');
    Route::post('/purchase-orders', 'PurchaseOrderController@store');
    Route::get('/purchase-orders/{id}', 'PurchaseOrderController@show');
    Route::patch('/purchase-orders/{id}/receive', 'PurchaseOrderController@receive');
});

// ── Inter-service (from Orders) ────────────────────────────────────────
Route::post('/stock/deduct', 'InventoryController@deductStock');

// ── Health Check ───────────────────────────────────────────────────
Route::get('/health', function () {
    return response()->json([
        'service'   => 'pageturn-inventory',
        'status'    => 'healthy',
        'timestamp' => now()->toISOString(),
    ]);
});
