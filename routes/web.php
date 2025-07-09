<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\VoucherController;
use App\Http\Controllers\UserController;

Route::get('/', [DashboardController::class, 'index'])->name('admin.dashboard');

Route::prefix('admin')->name('admin.')->group(function () {
    Route::resource('products', ProductController::class)->except(['destroy']);
    Route::post('products/{product}/lock', [ProductController::class, 'lock'])->name('products.lock');
    Route::post('products/{product}/unlock', [ProductController::class, 'unlock'])->name('products.unlock');

    Route::post('categories/{category}/lock', [CategoryController::class, 'lock'])->name('categories.lock');
    Route::post('categories/{category}/unlock', [CategoryController::class, 'unlock'])->name('categories.unlock');
    Route::resource('categories', CategoryController::class);

    Route::resource('images', ImageController::class)->except(['show']);
     Route::resource('users', UserController::class)->except(['edit', 'update']);
    Route::patch('users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggleStatus');

    Route::resource('comments', CommentController::class)->only(['index', 'destroy','update','edit']);
    Route::get('comments/{comment}/replies', [CommentController::class, 'replies'])->name('comments.replies');
    Route::post('comments/{comment}/toggle-status', [CommentController::class, 'toggleStatus'])->name('comments.toggleStatus');
    
    Route::resource('reviews', ReviewController::class)->only(['index', 'edit', 'destroy', 'update']);
    Route::get('reviews/{review}/replies', [ReviewController::class, 'replies'])->name('reviews.replies');
    Route::post('reviews/{review}/toggle-status', [ReviewController::class, 'toggleStatus'])->name('reviews.toggleStatus');

    Route::resource('vouchers', VoucherController::class)->except('show','destroy');
    Route::post('vouchers/{voucher}/toggle-status', [VoucherController::class, 'toggleStatus'])->name('vouchers.toggleStatus');

    Route::resource('orders',OrderController::class)->except(['destroy']);
    Route::post('orders/{order}/toggle-status', [OrderController::class, 'toggleStatus'])->name('orders.toggleStatus');
});



