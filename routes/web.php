<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\OrderController;

Route::get('/', [DashboardController::class, 'index'])->name('admin.dashboard');
Route::resource('admin/products', ProductController::class)->names('admin.products');
Route::resource('admin/categories', CategoryController::class)->names('admin.categories');
Route::get('admin/images', [ImageController::class, 'index'])->name('admin.images.index');
Route::resource('admin/images', ImageController::class)->names('admin.images')->only(['index', 'update', 'destroy']);
Route::resource('admin/categories', CategoryController::class)->names('admin.categories');
Route::get('admin/orders', [OrderController::class, 'index'])->name('admin.orders.index');
