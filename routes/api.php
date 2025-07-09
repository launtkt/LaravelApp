<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApiProductController;
use App\Http\Controllers\Api\ApiAccountController;
use App\Http\Controllers\Api\ApiCommentController;
use App\Http\Controllers\Api\ApiCustomerController;
use App\Http\Controllers\Api\ApiCategoryController;
use App\Http\Controllers\Api\ApiDashboardController;
use App\Http\Controllers\Api\ApiImageController;
use App\Http\Controllers\Api\ApiOrderController;
use App\Http\Controllers\Api\ApiVoucherController;
use App\Http\Controllers\Api\ApiReviewController;
use App\Http\Controllers\Api\ApiBannerController;

use App\Http\Controllers\Client\CProductController;
use App\Http\Controllers\Client\CCategoryController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Client\CCommentController;
use App\Http\Controllers\Client\CReviewController;
use App\Http\Controllers\Client\CCartController;
// use App\Http\Controllers\Client\CCheckoutController;
use App\Http\Controllers\Client\COrderController;
// Admin


// Route::prefix('admin/products')->group(function () {
//     Route::get('/', [ApiProductController::class, 'index']);
//     Route::post('/', [ApiProductController::class, 'store']);
//     Route::get('/low-stock', [ApiProductController::class, 'lowStock']);
//     Route::get('/{id}', [ApiProductController::class, 'show']);
//     Route::put('/{id}', [ApiProductController::class, 'update']);
//     Route::delete('/{id}', [ApiProductController::class, 'destroy']);
// });
// Route::prefix('admin/accounts')->group(function () {
//     Route::get('/', [ApiAccountController::class, 'index']);
//     Route::post('/', [ApiAccountController::class, 'store']);
//     // Route::get('/{id}', [ApiAccountController::class, 'show']);
//     Route::put('/{id}', [ApiAccountController::class, 'update']);
//     Route::delete('/{id}', [ApiAccountController::class, 'destroy']);
// });
// Route::prefix('admin/comments')->group(function () {
//     Route::get('/', [ApiCommentController::class, 'index']);
//     Route::put('/{id}', [ApiCommentController::class, 'update']);
//     Route::delete('/{id}', [ApiCommentController::class, 'destroy']);
// });
// Route::prefix('admin/customers')->group(function () {
//     Route::get('/', [ApiCustomerController::class, 'index']);
//     Route::put('/{id}', [ApiCustomerController::class, 'update']);
//     Route::delete('/{id}', [ApiCustomerController::class, 'destroy']);
// });

// Route::prefix('admin/dashboard')->group(function () {
//     Route::get('/', [ApiDashboardController::class, 'index']);
// });

// Route::prefix('admin/images')->group(function () {
//     Route::get('/', [ApiImageController::class, 'index']);
//     Route::post('/', [ApiImageController::class, 'store']);
//     Route::delete('/{id}', [ApiImageController::class, 'destroy']);
// });
// Route::prefix('admin/orders')->group(function () {
//     Route::get('/', [ApiOrderController::class, 'index']);
//     Route::post('/', [ApiOrderController::class, 'store']);
//     Route::get('/{id}', [ApiOrderController::class, 'show']);
//     Route::put('/{id}', [ApiOrderController::class, 'update']);
//     Route::delete('/{id}', [ApiOrderController::class, 'destroy']);
// });
// Route::prefix('admin/categories')->group(function () {
//     Route::get('/', [ApiCategoryController::class, 'index']);
//     Route::post('/', [ApiCategoryController::class, 'store']);
//     // Route::get('/{id}', [ApiCategoryController::class, 'show']);
//     Route::put('/{id}', [ApiCategoryController::class, 'update']);
//     Route::delete('/{id}', [ApiCategoryController::class, 'destroy']);
// });
// Route::prefix('admin/vouchers')->group(function () {
//     Route::get('/', [ApiVoucherController::class, 'index']);
//     Route::post('/', [ApiVoucherController::class, 'store']);
//     // Route::get('/{id}', [ApiVoucherController::class, 'show']);
//     Route::put('/{id}', [ApiVoucherController::class, 'update']);
//     Route::delete('/{id}', [ApiVoucherController::class, 'destroy']);
// });
// Route::prefix('admin/reviews')->group(function () {
//     Route::get('/', [ApiReviewController::class, 'index']);
//     Route::put('/{id}', [ApiReviewController::class, 'update']);
//     Route::delete('/{id}', [ApiReviewController::class, 'destroy']);
// });
// Route::prefix('admin/banners')->group(function () {
//     Route::get('/', [ApiBannerController::class, 'index']);
//     Route::post('/', [ApiBannerController::class, 'store']);
//     Route::get('/{id}', [ApiBannerController::class, 'show']);
//     Route::put('/{id}', [ApiBannerController::class, 'update']);
//     Route::delete('/{id}', [ApiBannerController::class, 'destroy']);
// });


// Client
Route::get('/products', [CProductController::class, 'index']);
Route::get('/products/latest', [CProductController::class, 'latest']);
Route::get('/products/featured', [CProductController::class, 'featured']);
Route::get('/products/{id}', [CProductController::class, 'show']);
Route::get('/products/{id}/related', [CProductController::class, 'related']);
Route::get('/products/{id}/rating', [CProductController::class, 'rating']);

Route::get('/categories', [CCategoryController::class, 'index']);
Route::get('/categories/{id}/products', [CCategoryController::class, 'products']);


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/comments', [CCommentController::class, 'store']);
    Route::get('/products/{id}/comments', [CCommentController::class, 'list']);
    Route::post('/reviews', [CReviewController::class, 'store']);
    Route::get('/products/{id}/reviews', [CReviewController::class, 'list']);
    Route::middleware('auth:sanctum')->post('/apply-voucher', [\App\Http\Controllers\Client\CVoucherController::class, 'apply']);
    // Cart
    Route::get('/cart', [CCartController::class, 'index']);
    Route::post('/cart', [CCartController::class, 'store']);
    Route::put('/cart/{id}', [CCartController::class, 'update']);
    Route::delete('/cart/{id}', [CCartController::class, 'destroy']);

    // Checkout
   Route::get('/orders', [COrderController::class, 'index']);
    Route::get('/orders/{id}', [COrderController::class, 'show']);
    Route::post('/orders', [COrderController::class, 'store']);
    Route::delete('/orders/{id}', [COrderController::class, 'destroy']);

});
