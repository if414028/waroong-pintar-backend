<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProductCategoryController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\SaleController;
use App\Http\Controllers\Api\StockController;
use App\Http\Controllers\Api\StockMovementController;
use App\Http\Controllers\Api\StoreController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ReportController;

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    Route::get('/stores', [StoreController::class, 'index']);
    Route::post('/stores', [StoreController::class, 'store']);
    Route::get('/stores/{store}', [StoreController::class, 'show']);

    Route::apiResource('product-categories', ProductCategoryController::class);

    Route::middleware('store.access')->group(function () {
        Route::get('/current-store', function (Request $request) {
            return response()->json([
                'success' => true,
                'data' => $request->attributes->get('active_store'),
            ]);
        });

        Route::apiResource('products', ProductController::class);

        Route::post('/stocks/adjustment', [StockController::class, 'adjustment']);
        Route::get('/stocks', [StockController::class, 'index']);
        Route::get('/stocks/{product}', [StockController::class, 'show']);

        Route::get('/stock-movements', [StockMovementController::class, 'index']);

        Route::post('/sales/{sale}/void', [SaleController::class, 'void']);

        Route::apiResource('sales', SaleController::class)->only([
            'index',
            'store',
            'show',
        ]);

        Route::prefix('reports')->group(function () {
            Route::get('/sales-summary', [ReportController::class, 'salesSummary']);
            Route::get('/top-products', [ReportController::class, 'topProducts']);
            Route::get('/low-stocks', [ReportController::class, 'lowStocks']);
        });
    });
});
