<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::group(['middleware' => ['role:admin']], function () {
    Route::resource('users', UserController::class);
});

Route::group(['middleware' => ['role:manager']], function () {
    Route::resource('products', ProductController::class);
});

Route::group(['middleware' => ['role:user']], function () {
    Route::get('/products', [ProductController::class, 'index']);
});
