<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Admin\AdminCategoryController;
use App\Http\Controllers\Api\Admin\AdminOrderController;
use App\Http\Controllers\Api\Admin\AdminProductController;
use App\Http\Controllers\Frontend\HomeController;

use Illuminate\Http\Request;


// Authentication Routes
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::get('/register', function () {
    return view('auth.register.register');
})->name('register');

Route::get('/register-password', function () {
    return view('auth.register.register-password');
})->name('register-password');
Route::get('/send-email', function () {
    return view('auth.register.send-email');
})->name('send-email');
Route::get('/send-otp', function () {
    return view('auth.register.send-otp');
})->name('send-otp');


//Forgot Password Routes
Route::get('/forgot-password', function () {
    return view('auth.forgot-password.forgot-password');
})->name('forgot-password');

Route::get('/verification', function () {
    return view('auth.forgot-password.verification');
})->name('verification');

Route::get('/new-password', function () {
    return view('auth.forgot-password.new-password');
})->name('new-password');


// Users Routes
Route::redirect('/', '/home');


Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::get('/product/{slug}', [HomeController::class, 'detail'])->name('detail-product');
Route::get('/all-product', [HomeController::class, 'productAll'])->name('all-product');

Route::get('/cart', function () {
    return view('frontend.pages.cart');
})->name('cart');
Route::get('/checkout', function () {
    return view('frontend.pages.checkout');
})->name('checkout');
Route::get('/payment', function () {
    return view('frontend.pages.payment');
})->name('payment');
Route::get('/detail-payment', function () {
    return view('frontend.pages.detail-payment');
})->name('detail-payment');

// profile routes
Route::get('/profile', function () {
    return view('frontend.pages.profile.profile');
})->name('profile');
Route::get('/verify-email', function (Request $request) {

    return view(
        'frontend.pages.profile.account-security.verify-email',
        [
            'target' => $request->target
        ]
    );

})->name('verify-email');
Route::get('/verify-otp', function () {
    return view('frontend.pages.profile.account-security.verify-otp');
})->name('verify-otp');
Route::get('/change-email', function () {
    return view('frontend.pages.profile.account-security.change-email');
})->name('change-email');
Route::get('/change-phone', function () {
    return view('frontend.pages.profile.account-security.change-phone');
})->name('change-phone');
Route::get('/change-password', function () {
    return view('frontend.pages.profile.account-security.change-password');
})->name('change-password');

Route::get('/address', function () {
    return view('frontend.pages.profile.address');
})->name('address');
Route::get('/order-all', function () {
    return view('frontend.pages.profile.order-all');
})->name('order-all');
Route::get('/order-sent', function () {
    return view('frontend.pages.profile.order-sent');
})->name('order-sent');
Route::get('/order-done', function () {
    return view('frontend.pages.profile.order-done');
})->name('order-done');
Route::get('/order-canceled', function () {
    return view('frontend.pages.profile.order-canceled');
})->name('order-canceled');
Route::get('/invoice', function () {
    return view('frontend.pages.profile.invoice');
})->name('invoice');




//Admin Routes
Route::get('/admin/dashboard', function () {
    return view('backend.pages.dashboard');
})->name('admin.dashboard');

// Admin Product
Route::get('/admin/product/index', [AdminProductController::class, 'frontendProductIndex'])
    ->name('admin.product.index');
Route::get('/admin/product/create', [AdminProductController::class, 'frontendProductCreate'])
    ->name('admin.product.create');
Route::get('/admin/product/edit/{product}', [AdminProductController::class, 'frontendProductEdit'])
    ->name('admin.product.edit');
Route::get('/admin/product/detail/{product}', [AdminProductController::class, 'frontendProductDetail'])
    ->name('admin.product.detail');




// TEST KALENDER
    Route::get('/admin/kalender', function () {
    return view('backend.pages.product.kalender');
})->name('admin.kalender');

// Admin Order
Route::get('/admin/order', function () {
    return view('backend.pages.order.index');
})->name('admin.order');

// Admin Category
Route::get('/admin/category', function () {
    return view('backend.pages.category.index');
})->name('admin.category');
Route::get('/admin/category/create', function () {
    return view('backend.pages.category.create');
})->name('admin.category.create');
Route::get('/admin/category/edit/{id}', function ($id) {
    return view('backend.pages.category.edit', compact('id'));
})->name('admin.category.edit');
Route::get('/admin/category/detail/{id}', function ($id) {
    return view('backend.pages.category.detail', compact('id'));
})->name('admin.category.detail');

// Admin Order


Route::get('/admin/order', [AdminOrderController::class, 'frontendOrderIndex'])
    ->name('admin.order');
Route::get('/admin/order/{order}', [AdminOrderController::class, 'frontendOrderDetail'])
    ->name('admin.order.detail');
   

