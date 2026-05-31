<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;

use App\Http\Controllers\CategoryController;

Route::get('/', [HomeController::class, 'index']);
Route::get('/categories', [CategoryController::class, 'index']);

// Auth
Route::get('/login', [AuthController::class, 'showLogin']);
Route::post('/login', [AuthController::class, 'processLogin']);
Route::get('/register', [AuthController::class, 'showRegister']);
Route::post('/register', [AuthController::class, 'processRegister']);
Route::get('/logout', [AuthController::class, 'logout']);

// Catalog
Route::get('/catalog', [CatalogController::class, 'index']);
Route::get('/catalog/{id}', [CatalogController::class, 'show']);

// Cart
Route::get('/cart', [CartController::class, 'index']);
Route::post('/cart/add', [CartController::class, 'add']);
Route::patch('/cart/update/{id}', [CartController::class, 'update']);
Route::delete('/cart/remove/{id}', [CartController::class, 'remove']);

// Checkout
Route::get('/checkout', [CheckoutController::class, 'index']);
Route::post('/checkout', [CheckoutController::class, 'process']);

// Profile
Route::get('/profile', [ProfileController::class, 'index']);

// Admin Dashboard
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\BookController;

Route::prefix('admin')->middleware(['role:admin'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index']);
    
    // Catalog Admin Routes
    Route::middleware(['role:catalog_admin'])->group(function () {
        Route::get('/books', [BookController::class, 'index']);
        Route::post('/books', [BookController::class, 'store']);
        Route::put('/books/{id}', [BookController::class, 'update']);
        Route::delete('/books/{id}', [BookController::class, 'destroy']);
        
        Route::get('/authors', [\App\Http\Controllers\Admin\AuthorController::class, 'index']);
        Route::post('/authors', [\App\Http\Controllers\Admin\AuthorController::class, 'store']);
        Route::put('/authors/{id}', [\App\Http\Controllers\Admin\AuthorController::class, 'update']);
        Route::delete('/authors/{id}', [\App\Http\Controllers\Admin\AuthorController::class, 'destroy']);
        
        Route::get('/categories', [\App\Http\Controllers\Admin\CategoryController::class, 'index']);
        Route::post('/categories', [\App\Http\Controllers\Admin\CategoryController::class, 'store']);
        Route::put('/categories/{id}', [\App\Http\Controllers\Admin\CategoryController::class, 'update']);
        Route::delete('/categories/{id}', [\App\Http\Controllers\Admin\CategoryController::class, 'destroy']);
    });
    
    // Orders Admin Routes
    Route::middleware(['role:orders_admin'])->group(function () {
        Route::get('/orders', [\App\Http\Controllers\Admin\OrderController::class, 'index']);
        Route::patch('/orders/{id}/status', [\App\Http\Controllers\Admin\OrderController::class, 'updateStatus']);
    });

    // Inventory Admin Routes
    Route::middleware(['role:inventory_admin'])->group(function () {
        Route::get('/inventory', [\App\Http\Controllers\Admin\InventoryController::class, 'index']);
        Route::post('/inventory/adjust', [\App\Http\Controllers\Admin\InventoryController::class, 'adjust']);
        
        Route::get('/suppliers', [\App\Http\Controllers\Admin\SupplierController::class, 'index']);
        Route::post('/suppliers', [\App\Http\Controllers\Admin\SupplierController::class, 'store']);
        Route::put('/suppliers/{id}', [\App\Http\Controllers\Admin\SupplierController::class, 'update']);
        Route::delete('/suppliers/{id}', [\App\Http\Controllers\Admin\SupplierController::class, 'destroy']);
        
        Route::get('/purchase-orders', [\App\Http\Controllers\Admin\PurchaseOrderController::class, 'index']);
        Route::post('/purchase-orders', [\App\Http\Controllers\Admin\PurchaseOrderController::class, 'store']);
        Route::patch('/purchase-orders/{id}/receive', [\App\Http\Controllers\Admin\PurchaseOrderController::class, 'receive']);
    });

    // Finance Admin Routes
    Route::middleware(['role:finance_admin'])->group(function () {
        Route::get('/invoices', [\App\Http\Controllers\Admin\InvoiceController::class, 'index']);
        Route::patch('/invoices/{id}/pay', [\App\Http\Controllers\Admin\InvoiceController::class, 'pay']);
        
        Route::get('/expenses', [\App\Http\Controllers\Admin\ExpenseController::class, 'index']);
        Route::post('/expenses', [\App\Http\Controllers\Admin\ExpenseController::class, 'store']);
        
        Route::get('/reports', [\App\Http\Controllers\Admin\ReportController::class, 'index']);
    });

    // Users Management (Super Admin only - or generic admin depending on policy)
    // We'll restrict to super_admin or allow default admin since no specific user_admin role was requested.
    // For now, super_admin is enforced via RoleMiddleware for any role not matching.
    Route::middleware(['role:super_admin'])->group(function () {
        Route::get('/users', [\App\Http\Controllers\Admin\UserController::class, 'index']);
        Route::post('/users', [\App\Http\Controllers\Admin\UserController::class, 'store']);
        Route::put('/users/{id}', [\App\Http\Controllers\Admin\UserController::class, 'update']);
        Route::delete('/users/{id}', [\App\Http\Controllers\Admin\UserController::class, 'destroy']);
    });
});
