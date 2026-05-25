<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes — Orders Service
|--------------------------------------------------------------------------
*/

// ── Cart ───────────────────────────────────────────────────────────
Route::get('/cart', 'CartController@index');
Route::post('/cart/items', 'CartController@store');
Route::put('/cart/items/{id}', 'CartController@update');
Route::delete('/cart/items/{id}', 'CartController@destroy');
Route::delete('/cart', 'CartController@clear');

// ── Orders ─────────────────────────────────────────────────────────
Route::post('/orders', 'OrderController@store');              // Checkout
Route::get('/orders', 'OrderController@index');               // List orders
Route::get('/orders/track/{orderNumber}', 'OrderController@track'); // Track by order number
Route::get('/orders/{id}', 'OrderController@show');           // Order details
Route::patch('/orders/{id}/status', 'OrderController@updateStatus'); // Update status (admin)

// ── Dashboard (Admin) ──────────────────────────────────────────────
Route::get('/dashboard/sales', 'DashboardController@sales');
Route::get('/dashboard/top-books', 'DashboardController@topBooks');

// ── Health Check ───────────────────────────────────────────────────
Route::get('/health', function () {
    return response()->json([
        'service'   => 'pageturn-orders',
        'status'    => 'healthy',
        'timestamp' => now()->toISOString(),
    ]);
});
