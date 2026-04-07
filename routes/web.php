<?php

use Illuminate\Support\Facades\Route;

// Users Routes
Route::get('/', function () {
    return view('frontend.pages.home');
})->name('/home');
// Route::get('/home', function () {
//     return view('frontend.pages.home');
// })->name('/home');
Route::get('/detail-product', function () {
    return view('frontend.pages.detail-product');
})->name('detail-product');
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
Route::get('/verify-email', function () {
    return view('frontend.pages.profile.account-security.verify-email');
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

// Authentication Routes
Route::get('/login', function () {
    return view('auth.login');
})->name('login');
Route::get('/register', function () {
    return view('auth.register');
})->name('register');
Route::get('/new-password', function () {
    return view('auth.new-password');
})->name('new-password');

//Forgot Password Routes
Route::get('/forgot-password', function () {
    return view('auth.forgot-password');
})->name('forgot-password');

Route::get('/verification', function () {
    return view('auth.verification');
})->name('verification');

Route::get('/register-password', function () {
    return view('auth.register-password');
})->name('register-password');

//Admin Routes
Route::get('/admin/dashboard', function () {
    return view('backend.pages.dashboard');
})->name('admin.dashboard');

Route::get('/admin/product', function () {
    return view('backend.pages.product');
})->name('admin.product');
Route::get('/admin/order', function () {
    return view('backend.pages.order');
})->name('admin.order');

