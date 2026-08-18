<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProductController as ApiProductController;
use App\Http\Controllers\Api\CartController as ApiCartController;
use App\Http\Controllers\Api\OrderController as ApiOrderController;
use App\Http\Controllers\Api\AuthController as ApiAuthController;
use App\Http\Controllers\Api\AdminController as ApiAdminController;

// Public API routes
Route::get('/products', [ApiProductController::class, 'index']);
Route::get('/products/{product}', [ApiProductController::class, 'show']);

// Authentication API routes
Route::post('/login', [ApiAuthController::class, 'login']);
Route::post('/register', [ApiAuthController::class, 'register']);
Route::post('/logout', [ApiAuthController::class, 'logout'])->middleware('auth:sanctum');

// Protected API routes (require authentication)
Route::middleware('auth:sanctum')->group(function () {
    // Cart API routes
    Route::get('/cart', [ApiCartController::class, 'index']);
    Route::post('/cart/add/{product}', [ApiCartController::class, 'add']);
    Route::patch('/cart/{cart}', [ApiCartController::class, 'update']);
    Route::delete('/cart/{cart}', [ApiCartController::class, 'remove']);
    Route::delete('/cart', [ApiCartController::class, 'clear']);

    // Order API routes
    Route::get('/orders', [ApiOrderController::class, 'index']);
    Route::get('/orders/{order}', [ApiOrderController::class, 'show']);
    Route::get('/checkout', [ApiOrderController::class, 'checkout']);
    Route::post('/orders', [ApiOrderController::class, 'store']);
});

// Admin API routes (require superadmin role)
Route::middleware(['auth:sanctum', 'admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [ApiAdminController::class, 'dashboard']);
    Route::get('/products', [ApiAdminController::class, 'products']);
    Route::get('/orders', [ApiAdminController::class, 'orders']);
    Route::patch('/orders/{order}/status', [ApiAdminController::class, 'updateOrderStatus']);
    
    // User Management API
    Route::get('/users', [\App\Http\Controllers\Admin\UserController::class, 'index']);
    Route::post('/users', [\App\Http\Controllers\Admin\UserController::class, 'store']);
    Route::get('/users/{user}', [\App\Http\Controllers\Admin\UserController::class, 'show']);
    Route::put('/users/{user}', [\App\Http\Controllers\Admin\UserController::class, 'update']);
    Route::delete('/users/{user}', [\App\Http\Controllers\Admin\UserController::class, 'destroy']);
    
    // Product Management API
    Route::post('/products', [ApiAdminController::class, 'storeProduct']);
    Route::put('/products/{product}', [ApiAdminController::class, 'updateProduct']);
    Route::delete('/products/{product}', [ApiAdminController::class, 'destroyProduct']);
});
