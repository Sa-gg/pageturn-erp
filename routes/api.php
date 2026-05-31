<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Public routes (no auth required)
Route::post('/auth/register', 'AuthController@register');
Route::post('/auth/login', 'AuthController@login');

// Token validation (inter-service) - does its own token check
Route::get('/auth/validate', 'AuthController@validateToken');

// Protected routes (require valid API token)
Route::middleware('auth:api')->group(function () {
    // Auth routes
    Route::post('/auth/logout', 'AuthController@logout');
    Route::get('/auth/me', 'AuthController@me');
    Route::put('/auth/profile', 'AuthController@updateProfile');

    // Admin-only user management
    Route::middleware('role:admin')->group(function () {
        Route::get('/users', 'UserController@index');
        Route::post('/users', 'UserController@store');
        Route::get('/users/{id}', 'UserController@show');
        Route::put('/users/{id}', 'UserController@update');
        Route::delete('/users/{id}', 'UserController@destroy');
    });
});

// Health check
Route::get('/health', function () {
    return response()->json([
        'service' => 'pageturn-auth',
        'status' => 'healthy',
        'timestamp' => now()->toISOString(),
    ]);
});
