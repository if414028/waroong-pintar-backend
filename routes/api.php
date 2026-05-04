<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProductCategoryController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\StoreController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    Route::get('/stores', [StoreController::class, 'index']);
    Route::post('/stores', [StoreController::class, 'store']);
    Route::get('/stores/{store}', [StoreController::class, 'show']);

    Route::middleware('store.access')->group(function () {
        Route::get('/current-store', function (Request $request) {
            return response()->json([
                'success' => true,
                'data' => $request->attributes->get('active_store'),
            ]);
        });

        Route::middleware('store.access')->group(function () {
            Route::apiResource('products', ProductController::class);
        });

        Route::middleware('auth:sanctum')->group(function () {
            Route::apiResource('product-categories', ProductCategoryController::class);
        });
    });
});
