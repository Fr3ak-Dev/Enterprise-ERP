<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\CustomerTypeController;
use App\Http\Controllers\InventoryMovementController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\TransactionTypeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ReportController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:api')->group(function () {

    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/refresh', [AuthController::class, 'refresh']);
    Route::apiResource('categories', CategoryController::class);
    Route::apiResource('suppliers', SupplierController::class);
    Route::apiResource('products', ProductController::class);
    Route::apiResource('inventory-movements', InventoryMovementController::class)
        ->except(['update', 'destroy']); // No se pueden editar ni eliminar
    Route::apiResource('customer-types', CustomerTypeController::class);
    Route::apiResource('customers', CustomerController::class);
    Route::apiResource('accounts', AccountController::class);
    Route::apiResource('transaction-types', TransactionTypeController::class);
    Route::apiResource('orders', OrderController::class);
    Route::prefix('reports')->group(function () {
        Route::get('/products-pdf', [ReportController::class, 'productsPdf']);
        Route::get('/products-pdf-preview', [ReportController::class, 'productsPdfPreview']);
        Route::get('/transactions-excel', [ReportController::class, 'transactionsExcel']);
    });
});
