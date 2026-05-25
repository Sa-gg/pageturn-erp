<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\ReportController;

Route::get('/health', function () {
    return response()->json(['status' => 'ok', 'service' => 'finance']);
});

Route::post('/invoices', [InvoiceController::class, 'store']);
Route::get('/invoices', [InvoiceController::class, 'index']);
Route::get('/invoices/{id}', [InvoiceController::class, 'show']);
Route::patch('/invoices/{id}/pay', [InvoiceController::class, 'pay']);

Route::get('/payments', [PaymentController::class, 'index']);

Route::apiResource('expenses', ExpenseController::class);

Route::get('/reports/revenue', [ReportController::class, 'revenue']);
Route::get('/reports/profit', [ReportController::class, 'profit']);
Route::get('/reports/top-books', [ReportController::class, 'topBooks']);
