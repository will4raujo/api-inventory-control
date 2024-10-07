<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\SuppliersController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\StockMovementController;

//authentification
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout']);
//users
Route::post('/register', [UsersController::class, 'store'])->middleware(['auth:sanctum']);
Route::get('/users', [UsersController::class, 'index'])->middleware(['auth:sanctum']);
Route::get('/users/{id}', [UsersController::class, 'findById'])->where('id', '[0-9]+')->middleware(['auth:sanctum']);
Route::get('/users/profile', [UsersController::class, 'profile'])->middleware(['auth:sanctum']);
Route::put('/users/{id}', [UsersController::class, 'update'])->middleware(['auth:sanctum']);
Route::delete('/users/{id}', [UsersController::class, 'destroy'])->middleware(['auth:sanctum']);
//categories
Route::get('/categories', [CategoriesController::class, 'index'])->middleware(['auth:sanctum']);
Route::post('/categories', [CategoriesController::class, 'store'])->middleware(['auth:sanctum']);
Route::get('/categories/{id}', [CategoriesController::class, 'findById'])->middleware(['auth:sanctum']);
Route::put('/categories/{id}', [CategoriesController::class, 'update'])->middleware(['auth:sanctum']);
Route::delete('/categories/{id}', [CategoriesController::class, 'destroy'])->middleware(['auth:sanctum']);
//suppliers
Route::get('/suppliers', [SuppliersController::class, 'index'])->middleware(['auth:sanctum']);
Route::post('/suppliers', [SuppliersController::class, 'store'])->middleware(['auth:sanctum']);
Route::get('/suppliers/{id}', [SuppliersController::class, 'findById'])->middleware(['auth:sanctum']);
Route::put('/suppliers/{id}', [SuppliersController::class, 'update'])->middleware(['auth:sanctum']);
Route::delete('/suppliers/{id}', [SuppliersController::class, 'destroy'])->middleware(['auth:sanctum']);
//products
Route::get('/products', [ProductsController::class, 'index'])->middleware(['auth:sanctum']);
Route::post('/products', [ProductsController::class, 'store'])->middleware(['auth:sanctum']);
Route::get('/products/{id}', [ProductsController::class, 'findById'])->middleware(['auth:sanctum']);
Route::put('/products/{id}', [ProductsController::class, 'update'])->middleware(['auth:sanctum']);
Route::delete('/products/{id}', [ProductsController::class, 'destroy'])->middleware(['auth:sanctum']);
Route::get('/products/lists', [ProductsController::class, 'lists'])->middleware(['auth:sanctum']);
//stock movements
Route::get('/movements', [StockMovementController::class, 'index'])->middleware(['auth:sanctum']);
Route::post('/movements', [StockMovementController::class, 'store'])->middleware(['auth:sanctum']);