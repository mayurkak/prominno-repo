<?php

use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProductController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/login', [AuthController::class, 'login']);

Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {
    Route::post('/admin/create-seller', [AdminController::class, 'createSeller']);
    Route::get('/admin/sellers', [AdminController::class, 'listSellers']);
    Route::get('/sellers', [AdminController::class, 'index']);
});

// Seller Only Routes
Route::middleware(['auth:sanctum', 'role:seller'])->group(function () {
    Route::post('/seller/add-product', [ProductController::class, 'store']);
    Route::get('/seller/products', [ProductController::class, 'index']);
});
Route::post('/seller/login', [AuthController::class, 'sellerLogin']);
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/sellers/products/create', [ProductController::class, 'createProduct']);
    Route::post('/seller/products', [ProductController::class, 'store']);
});
Route::middleware('auth:sanctum')->get('/seller/products/list', [ProductController::class, 'index']);
Route::middleware('auth:sanctum')->delete('/seller/products/{id}', [ProductController::class, 'destroy']);
