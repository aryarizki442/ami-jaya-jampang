<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\Admin\AdminCategoryController;
use App\Http\Controllers\Api\Admin\AdminOrderController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\UserAddressController;
use App\Http\Controllers\Api\Admin\AdminProductController;
use App\Http\Controllers\Api\OrderController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/


Route::post('/login', [AuthController::class, 'login'])->name('api.login');
Route::post('/register', [AuthController::class, 'register'])->name('api.register');

Route::middleware(['jwt.auth'])->group(function () {
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::put('/profil', [AuthController::class, 'update']);
    Route::put('change-password',[AuthController::class, 'changePassword']);
    Route::post('/avatar', [AuthController::class, 'uploadAvatar']);
    Route::delete('avatar/delete', [AuthController::class, 'deleteAvatar']);

    // Update email dengan OTP
    Route::post('update-email/request', [AuthController::class, 'requestUpdateEmail']);
    Route::post('update-email/verify',  [AuthController::class, 'verifyUpdateEmail']);

    // Update phone dengan OTP
    Route::post('update-phone/request', [AuthController::class, 'requestUpdatePhone']);
    Route::post('update-phone/verify',  [AuthController::class, 'verifyUpdatePhone']);

});
    
Route::middleware(['jwt.auth'])->group(function () {
    Route::get('/cart', [CartController::class, 'index']);

    Route::post('/cart/items', [CartController::class, 'addItem']);

    Route::put('/cart/items/{item}', [CartController::class, 'updateItem']);

    Route::delete('/cart/items/{item}', [CartController::class, 'removeItem']);

    Route::post('/cart/select-all', [CartController::class, 'selectAll']);

    Route::delete('/cart/clear', [CartController::class, 'clear']);
});

Route::prefix('admin')->group(function () {

    Route::get('/categories', [AdminCategoryController::class, 'index']);
    Route::post('/categories', [AdminCategoryController::class, 'store']);
    Route::put('/categories/{category}', [AdminCategoryController::class, 'update']);
    Route::delete('/categories/{category}', [AdminCategoryController::class, 'destroy']);
    Route::patch('/categories/{category}/toggle-active', [AdminCategoryController::class, 'toggleActive']);
});


Route::prefix('admin')->group(function () {

    // ── Orders
    Route::get('/orders', [AdminOrderController::class, 'index']);
    Route::get('/orders/stats', [AdminOrderController::class, 'stats']);
    Route::get('/orders/{order}', [AdminOrderController::class, 'show']);

    // ── Update status
    Route::patch('/orders/{order}/status', [AdminOrderController::class, 'updateStatus']);
    // ── Refund
    Route::post('/orders/{order}/refund', [AdminOrderController::class, 'refund']);
    Route::post('/orders/{order}/refund-manual', [AdminOrderController::class, 'refundManual']);
});


Route::middleware('auth:api')->group(function () {

    // 📦 List order user
    Route::get('/orders', [OrderController::class, 'index']);

    // 🔍 Detail order
    Route::get('/orders/{order}', [OrderController::class, 'show']);

    // 🚚 Hitung ongkir sebelum checkout
    Route::get('/orders/shipping-calculate', [OrderController::class, 'calculateShipping']);

    // 🛒 Buat order (checkout)
    Route::post('/orders', [OrderController::class, 'store']);

    // ❌ Cancel order
    Route::post('/orders/{order}/cancel', [OrderController::class, 'cancel']);


    Route::post('/orders/{order}/reorder', [OrderController::class, 'reorder']);

   
    Route::get('/orders/{order}/invoice', [OrderController::class, 'invoice']);

});


Route::prefix('admin')->group(function () {

        // ── CRUD Produk ─────────────────────────────
        Route::get('/products', [AdminProductController::class, 'index']);
        Route::get('/products/{product}', [AdminProductController::class, 'show']);
        Route::post('/products', [AdminProductController::class, 'store']);
        Route::put('/products/{product}', [AdminProductController::class, 'update']);
        Route::post('/products/{product}', [AdminProductController::class, 'update']); 
        Route::delete('/products/{product}', [AdminProductController::class, 'destroy']);

        // ── Toggle Active ───────────────────────────
        Route::patch('/products/{product}/toggle-active', [AdminProductController::class, 'toggleActive']);

        // ── Bulk Delete ─────────────────────────────
        Route::delete('/products/bulk-delete', [AdminProductController::class, 'bulkDelete']);

        // ── Image Handling ──────────────────────────
        Route::post('/products/{product}/images', [AdminProductController::class, 'uploadImages']);
        Route::delete('/products/{product}/images/{image}', [AdminProductController::class, 'deleteImage']);
        Route::patch('/products/{product}/images/{image}/set-primary', [AdminProductController::class, 'setPrimaryImage']);
    });



// ─────────────────────────────────────────────
// PUBLIC (tanpa auth) → untuk Midtrans webhook
// ─────────────────────────────────────────────
Route::post('/payment/notification', [PaymentController::class, 'notification']);


// ─────────────────────────────────────────────
// USER (pakai JWT Auth)
// ─────────────────────────────────────────────
Route::middleware(['auth:api'])->group(function () {

    // ── Payment Methods ───────────────────────
    Route::get('/payment-methods', [PaymentController::class, 'methods']);

    // ── Payment Detail ───────────────────────
    Route::get('/orders/{order}/payment', [PaymentController::class, 'show']);

    // ── Midtrans Snap Token ──────────────────
    Route::post('/orders/{order}/payment/snap-token', [PaymentController::class, 'getSnapToken']);

    // ── Upload Bukti Transfer ────────────────
    Route::post('/orders/{order}/payment/upload-proof', [PaymentController::class, 'upload Proof']);
});

//product

Route::get('/products', [ProductController::class, 'index']);

// slug dulu!
Route::get('/products/slug/{slug}', [ProductController::class, 'showBySlug']);

// baru ID
Route::get('/products/{product}', [ProductController::class, 'show']);

Route::get('/products/{product}/reviews', [ProductController::class, 'reviews']);


    // ── CRUD Address ──────────────────────────
    Route::get('/addresses', [UserAddressController::class, 'index']);        // list alamat
    Route::post('/addresses', [UserAddressController::class, 'store']);       // tambah alamat
    Route::get('/addresses/{address}', [UserAddressController::class, 'show']); // detail alamat
    Route::put('/addresses/{address}', [UserAddressController::class, 'update']); // update alamat
    Route::delete('/addresses/{address}', [UserAddressController::class, 'destroy']); // hapus alamat

    // ── Set Primary Address ───────────────────
    Route::patch('/addresses/{address}/set-primary', [UserAddressController::class, 'setPrimary']);
