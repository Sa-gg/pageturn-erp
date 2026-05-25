<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes — Catalog Service
|--------------------------------------------------------------------------
*/

// Public routes (browsing books)
Route::get('/categories', 'CategoryController@index');
Route::get('/categories/{id}', 'CategoryController@show');

Route::get('/authors', 'AuthorController@index');
Route::get('/authors/{id}', 'AuthorController@show');

Route::get('/books', 'BookController@index');
Route::get('/books/{id}', 'BookController@show');
Route::get('/books/{id}/reviews', 'BookController@getReviews');
Route::post('/books/{id}/reviews', 'BookController@storeReview');

// Internal route (called by Inventory service)
Route::patch('/books/{id}/availability', 'BookController@updateAvailability');

// Admin routes (require auth token passed from frontend)
Route::prefix('admin')->group(function () {
    Route::post('/categories', 'CategoryController@store');
    Route::put('/categories/{id}', 'CategoryController@update');
    Route::delete('/categories/{id}', 'CategoryController@destroy');

    Route::post('/authors', 'AuthorController@store');
    Route::put('/authors/{id}', 'AuthorController@update');
    Route::delete('/authors/{id}', 'AuthorController@destroy');

    Route::post('/books', 'BookController@store');
    Route::put('/books/{id}', 'BookController@update');
    Route::delete('/books/{id}', 'BookController@destroy');
});

// Health check
Route::get('/health', function () {
    return response()->json([
        'service' => 'pageturn-catalog',
        'status' => 'healthy',
        'timestamp' => now()->toISOString(),
    ]);
});
