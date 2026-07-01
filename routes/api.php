<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\AuthController;

// V1 API Routes
Route::prefix('v1')->group(function () {
    
    // Authentication
    Route::controller(AuthController::class)->group(function () {
        Route::post('register', 'register');
        Route::post('login', 'login');
        Route::post('logout', 'logout')->middleware('auth:sanctum');
        Route::get('user', 'user')->middleware('auth:sanctum');
    });
    
    // Profile Management (Auth Required)
    Route::middleware('auth:sanctum')->group(function () {
        Route::put('profile', [\App\Http\Controllers\Api\V1\ProfileController::class, 'update']);
        Route::put('profile/password', [\App\Http\Controllers\Api\V1\ProfileController::class, 'updatePassword']);
    });

    // Public Routes
    Route::get('categories', [\App\Http\Controllers\Api\V1\CategoryController::class, 'index']);
    Route::get('categories/{category:slug}', [\App\Http\Controllers\Api\V1\CategoryController::class, 'show']);
    
    Route::get('products', [\App\Http\Controllers\Api\V1\ProductController::class, 'index']);
    Route::get('products/{product:slug}', [\App\Http\Controllers\Api\V1\ProductController::class, 'show']);
    Route::get('products/{product:slug}/reviews', [\App\Http\Controllers\Api\V1\ProductReviewController::class, 'index']);

    // Public site settings (e.g. homepage hero content)
    Route::get('settings', [\App\Http\Controllers\Api\V1\Admin\SettingController::class, 'index']);

    // Admin Routes (Auth + Role Required)
    Route::middleware(['auth:sanctum', 'admin.api'])->prefix('admin')->group(function () {
        Route::get('dashboard', [\App\Http\Controllers\Api\V1\Admin\DashboardController::class, 'index']);

        Route::apiResource('categories', \App\Http\Controllers\Api\V1\Admin\CategoryController::class);
        Route::post('categories/{category}', [\App\Http\Controllers\Api\V1\Admin\CategoryController::class, 'update']);

        Route::apiResource('products', \App\Http\Controllers\Api\V1\Admin\ProductController::class);
        Route::post('products/{product}', [\App\Http\Controllers\Api\V1\Admin\ProductController::class, 'update']);
        Route::put('products/{product}/status', [\App\Http\Controllers\Api\V1\Admin\ProductController::class, 'updateStatus']);

        Route::get('settings', [\App\Http\Controllers\Api\V1\Admin\SettingController::class, 'index']);
        Route::post('settings', [\App\Http\Controllers\Api\V1\Admin\SettingController::class, 'update']);

        // Order Management
        Route::get('orders', [\App\Http\Controllers\Api\V1\Admin\OrderController::class, 'index']);
        Route::get('orders/{order}', [\App\Http\Controllers\Api\V1\Admin\OrderController::class, 'show']);
        Route::put('orders/{order}/status', [\App\Http\Controllers\Api\V1\Admin\OrderController::class, 'updateStatus']);
        Route::put('orders/{order}/payment-status', [\App\Http\Controllers\Api\V1\Admin\OrderController::class, 'updatePaymentStatus']);

        // Customer Management
        Route::get('customers', [\App\Http\Controllers\Api\V1\Admin\CustomerController::class, 'index']);
        Route::get('customers/{customer}', [\App\Http\Controllers\Api\V1\Admin\CustomerController::class, 'show']);
    });

    // Protected Routes (Customer)
    Route::middleware('auth:sanctum')->group(function () {
        
        // Cart
        Route::get('cart', [\App\Http\Controllers\Api\V1\CartController::class, 'index']);
        Route::post('cart', [\App\Http\Controllers\Api\V1\CartController::class, 'add']);
        Route::put('cart/{cart}', [\App\Http\Controllers\Api\V1\CartController::class, 'update']);
        Route::delete('cart/clear', [\App\Http\Controllers\Api\V1\CartController::class, 'clear']);
        Route::delete('cart/{cart}', [\App\Http\Controllers\Api\V1\CartController::class, 'remove']);
        
        // Wishlist
        Route::get('wishlist', [\App\Http\Controllers\Api\V1\WishlistController::class, 'index']);
        Route::get('wishlist/ids', [\App\Http\Controllers\Api\V1\WishlistController::class, 'ids']);
        Route::post('wishlist/toggle', [\App\Http\Controllers\Api\V1\WishlistController::class, 'toggle']);
        
        // Orders
        Route::get('orders', [\App\Http\Controllers\Api\V1\OrderController::class, 'index']);
        Route::post('orders', [\App\Http\Controllers\Api\V1\OrderController::class, 'store']);
        Route::get('orders/{order:order_number}', [\App\Http\Controllers\Api\V1\OrderController::class, 'show']);
        
        // Reviews
        // Route::post('products/{product:slug}/reviews', [\App\Http\Controllers\Api\V1\ProductReviewController::class, 'store']);
    });
});
