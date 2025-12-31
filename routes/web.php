<?php

use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', [UserController::class, 'index'])->name('login');
Route::get('login', [UserController::class, 'registration'])->name('register');

Route::post('login', [UserController::class, 'login'])->name('login-post');
Route::post('custom-registration', [UserController::class, 'customRegistration'])->name('custom-registration');
Route::get('/index', [UserController::class, 'index'])->name('index');
Route::middleware('auth')->group(function () {
    Route::get('view', [UserController::class, 'view'])->name('view');
    Route::get('signout', [UserController::class, 'signOut'])->name('signout');
    // users routes
    Route::get('users', [UserController::class, 'index'])->name('users');
});
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/sellers', [AdminController::class, 'index'])->name('admin.sellers');
    Route::get('/admin/sellers/create', [AdminController::class, 'create'])->name('admin.sellers.create');

    // Route to handle form submission (Web version)
    Route::post('/admin/sellers/store', [AdminController::class, 'store'])->name('admin.sellers.store');
});
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/sellers/products/create', [ProductController::class, 'createProduct'])->name('sellers-products-create');
    Route::post('/seller/products', [ProductController::class, 'store']);
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::get('/products/{id}/pdf', [ProductController::class, 'downloadPDF'])->name('products.pdf');
    Route::delete('/products/{id}', [ProductController::class, 'destroy'])->name('products.destroy');
});
