<?php

use Illuminate\Support\Facades\Route;

// Frontend Routes
Route::get('/', function () {
    return view('frontend.pages.home');
})->name('/home');
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

// Authentication Routes
Route::get('/login', function () {
    return view('frontend.auth.login');
})->name('login');
Route::get('/register', function () {
    return view('frontend.auth.register');
})->name('register');
Route::get('/confirm', function () {
    return view('frontend.auth.confirm');
})->name('confirm');

//Forgot Password Routes
Route::get('/forgot-password', function () {
    return view('frontend.auth.forgot-password');
})->name('forgot-password');

Route::get('/verification', function () {
    return view('frontend.auth.verification');
})->name('verification');

Route::get('/register-password', function () {
    return view('frontend.auth.register-password');
})->name('register-password');

