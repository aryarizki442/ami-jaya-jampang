<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('frontend.pages.home');
});

Route::get('/login', function () {
    return view('frontend.auth.login');
});
Route::get('/register', function () {
    return view('frontend.auth.register');
});
Route::get('/confirm', function () {
    return view('frontend.auth.confirm');
});
Route::get('/forgot-password', function () {
    return view('frontend.auth.forgot-password');
});
Route::get('/verification', function () {
    return view('frontend.auth.verification');
});
